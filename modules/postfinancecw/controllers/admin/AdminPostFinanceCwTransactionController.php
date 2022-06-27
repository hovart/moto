<?php
/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2016 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */
$modulePath = rtrim(_PS_MODULE_DIR_, '/');
require_once $modulePath . '/postfinancecw/postfinancecw.php';

require_once 'Customweb/Grid/Loader.php';
require_once 'Customweb/Payment/BackendOperation/Adapter/Service/IRefund.php';
require_once 'Customweb/Payment/BackendOperation/Adapter/Service/ICancel.php';
require_once 'Customweb/Payment/Authorization/EditableInvoiceItem.php';
require_once 'Customweb/Grid/DataAdapter/DriverAdapter.php';
require_once 'Customweb/Payment/BackendOperation/Adapter/Service/ICapture.php';
require_once 'Customweb/Grid/Column.php';

require_once 'PostFinanceCw/Grid/Renderer.php';
require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/Grid/TransactionActionColumn.php';
require_once 'PostFinanceCw/Entity/Transaction.php';


/**
 * This calls intercepts the storage process of the refund executed in the backend of PrestaShop.
 *
 * @author Thomas Hunziker
 *         			 		  	    
 */
class AdminPostFinanceCwTransactionController extends AdminController
{
	public function __construct() {
		$this->className = 'AdminPostFinanceCwTransactionController';
		parent::__construct();
		$this->context->smarty->addTemplateDir($this->getTemplatePath());
		$this->tpl_folder = 'postfinancecw_transaction/';
		$this->bootstrap = true;
	}


	public function initContent()
	{
		$this->addCSS(_MODULE_DIR_ . 'postfinancecw/css/admin.css');
		
		
		if (!isset($_GET['action'])) {
			$_GET['action'] = 'list';
		}
		switch(strtolower($_GET['action'])) {
			case 'list':
				$this->listTransactions();
				break;
			
			case 'edit':
				$this->editTransaction();
				break;
			
			case 't_capture':
				$this->captureTransaction();
				break;
			
			case 'cancel':
				$this->cancelTransaction();
				break;
			
			case 't_refund':
				$this->refundTransaction();
				break;
		}
		

		parent::initContent();
	}
	
	private function listTransactions() {
		$adapter = new Customweb_Grid_DataAdapter_DriverAdapter(PostFinanceCw_Entity_Transaction::getGridQuery(), PostFinanceCw_Util::getDriver());
		
		$loader = new Customweb_Grid_Loader();
		$loader->setDataAdapter($adapter);
		$loader->setRequestData($_GET);
		$loader
			->addColumn(new Customweb_Grid_Column('transactionExternalId', 'Transaction Number'))
			->addColumn(new Customweb_Grid_Column('cartId', 'Cart ID'))
			->addColumn(new Customweb_Grid_Column('authorizationStatus', 'Authorization Status'))
			->addColumn(new Customweb_Grid_Column('orderId', 'Order ID'))
			->addColumn(new Customweb_Grid_Column('paymentMachineName', 'Payment Method'))
			->addColumn(new Customweb_Grid_Column('createdOn', 'Created On', 'DESC'))
			->addColumn(new PostFinanceCw_Grid_TransactionActionColumn('actions'))
			;
		
		$renderer = new PostFinanceCw_Grid_Renderer($loader, PostFinanceCw::getAdminUrl('AdminPostFinanceCwTransaction', array('action' => 'list')));
		$renderer->setGridId('transaction-grid');
		$this->context->smarty->assign('grid', $renderer->render());
		
		$this->display = 'list';
	}
	
	private function editTransaction() {
		
		$transaction = $this->getCurrentTransaction();
		$this->context->smarty->assign('transaction', $transaction);
		$this->context->smarty->assign('transactionObject', $transaction->getTransactionObject());
		
		$this->display = 'edit';
		
	}
	
	private function cancelTransaction() {
		$transaction = $this->getCurrentTransaction();
		$adapter = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICancel');
		if (!($adapter instanceof Customweb_Payment_BackendOperation_Adapter_Service_ICancel)) {
			throw new Exception("No adapter with interface 'Customweb_Payment_BackendOperation_Adapter_Service_ICancel' provided.");
		}
		
		try {
			$adapter->cancel($transaction->getTransactionObject());
			PostFinanceCw_Util::getEntityManager()->persist($transaction);
			$this->confirmations[] = PostFinanceCw::translate("The cancel was successful.");
		} catch(Exception $e) {
			PostFinanceCw_Util::getEntityManager()->persist($transaction);
			$this->errors[] = $e->getMessage();
		}
		$this->editTransaction();
	}
	
	
	private function captureTransaction() {
		$transaction = $this->getCurrentTransaction();
		
		if (isset($_POST['quantity'])) {
			$this->processCapture($transaction, $_REQUEST);
		}
	
		$this->addJS(_MODULE_DIR_ . 'postfinancecw/js/line-item-grid.js');
	
		$this->context->smarty->assign('transaction', $transaction);
		$this->context->smarty->assign('transactionObject', $transaction->getTransactionObject());
		$this->display = 'capture';
	
	}
	
	private function processCapture(PostFinanceCw_Entity_Transaction $transaction, $parameters = array()) {
		if (isset($parameters['quantity'])) {
			$captureLineItems = array();
			$lineItems = $transaction->getTransactionObject()->getUncapturedLineItems();
			foreach ($parameters['quantity'] as $index => $quantity) {
				if (isset($parameters['price_including'][$index]) && abs(floatval($parameters['price_including'][$index])) > 0) {
					$originalItem = $lineItems[$index];
					$captureLineItems[$index] = new Customweb_Payment_Authorization_EditableInvoiceItem($originalItem);
					$captureLineItems[$index]->setAmountIncludingTax(abs(floatval($parameters['price_including'][$index])));
					$captureLineItems[$index]->setQuantity($quantity);
				}
			}
		}
		else {
			$captureLineItems = $transaction->getTransactionObject()->getUncapturedLineItems();
		}
			
		if (count($captureLineItems) > 0) {
			$adapter = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICapture');
			if (!($adapter instanceof Customweb_Payment_BackendOperation_Adapter_Service_ICapture)) {
				throw new Exception("No adapter with interface 'Customweb_Payment_BackendOperation_Adapter_Service_ICapture' provided.");
			}
	
			$close = false;
			if (isset($parameters['close']) && $parameters['close'] == 'on') {
				$close = true;
			}
			try {
				$adapter->partialCapture($transaction->getTransactionObject(), $captureLineItems, $close);
				PostFinanceCw_Util::getEntityManager()->persist($transaction);
				$this->confirmations[] = PostFinanceCw::translate("Capture was successful.");
			} catch(Exception $e) {
				PostFinanceCw_Util::getEntityManager()->persist($transaction);
				$this->errors[] = $e->getMessage();
			}
		}
		else {
			$this->errors[] = "No item was captured.";
			return PostFinanceCw::translate("No item was captured.");
		}
	}
	
	
	
	private function refundTransaction() {
		$transaction = $this->getCurrentTransaction();
	
		if (isset($_POST['quantity'])) {
			$this->processRefund($transaction, $_REQUEST);
		}
	
		$this->addJS(_MODULE_DIR_ . 'postfinancecw/js/line-item-grid.js');
	
		$this->context->smarty->assign('transaction', $transaction);
		$this->context->smarty->assign('transactionObject', $transaction->getTransactionObject());
		$this->display = 'refund';
	
	}
	
	private function processRefund(PostFinanceCw_Entity_Transaction $transaction, $parameters = array()) {
		if (isset($parameters['quantity'])) {
			$refundLineItems = array();
			$lineItems = $transaction->getTransactionObject()->getNonRefundedLineItems();
			foreach ($parameters['quantity'] as $index => $quantity) {
				if (isset($parameters['price_including'][$index]) && abs(floatval($parameters['price_including'][$index])) > 0) {
					$originalItem = $lineItems[$index];
					$refundLineItems[$index] = new Customweb_Payment_Authorization_EditableInvoiceItem($originalItem);
					$refundLineItems[$index]->setAmountIncludingTax(abs(floatval($parameters['price_including'][$index])));
					$refundLineItems[$index]->setQuantity($quantity);
				}
			}
		}
		else {
			$refundLineItems = $transaction->getTransactionObject()->getNonRefundedLineItems();
		}
			
		if (count($refundLineItems) > 0) {
			$adapter = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_IRefund');
			if (!($adapter instanceof Customweb_Payment_BackendOperation_Adapter_Service_IRefund)) {
				throw new Exception("No adapter with interface 'Customweb_Payment_BackendOperation_Adapter_Service_IRefund' provided.");
			}
	
			$close = false;
			if (isset($parameters['close']) && $parameters['close'] == 'on') {
				$close = true;
			}
			try {
				$adapter->partialRefund($transaction->getTransactionObject(), $refundLineItems, $close);
				PostFinanceCw_Util::getEntityManager()->persist($transaction);
				$this->confirmations[] = PostFinanceCw::translate("Refund was successful.");
			} catch(Exception $e) {
				PostFinanceCw_Util::getEntityManager()->persist($transaction);
				$this->errors[] = $e->getMessage();
			}
		}
		else {
			$this->errors[] = "No item was refunded.";
			return PostFinanceCw::translate("No item was refunded.");
		}
	}
	
	
	private function getCurrentTransaction() {
		$transactionId = Tools::getValue('transactionId', Null);
		
		if (empty($transactionId)) {
			throw new Exception("No transaction id given.");
		}
		
		return PostFinanceCw_Entity_Transaction::loadById($transactionId);
	}

	public function getTemplatePath()
	{
		return _PS_MODULE_DIR_ . 'postfinancecw/views/templates/back/';
	}


}