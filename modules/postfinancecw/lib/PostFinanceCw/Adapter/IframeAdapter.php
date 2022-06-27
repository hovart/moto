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


require_once 'PostFinanceCw/Adapter/AbstractAdapter.php';


/**
 * @author Thomas Hunziker
 * @Bean
 *
 */
class PostFinanceCw_Adapter_IframeAdapter extends PostFinanceCw_Adapter_AbstractAdapter {
	
	private $visibleFormFields = array();
	private $formActionUrl = null;
	private $iframeHeight = 500;
	private $iframeUrl = null;
	private $errorMessage = '';
	
	public function getPaymentAdapterInterfaceName() {
		return 'Customweb_Payment_Authorization_Iframe_IAdapter';
	}
	
	/**
	 * @return Customweb_Payment_Authorization_Iframe_IAdapter
	 */
	public function getInterfaceAdapter() {
		return parent::getInterfaceAdapter();
	}
	
	protected function preparePaymentFormPane() {
		$this->visibleFormFields = $this->getInterfaceAdapter()->getVisibleFormFields(
			$this->getOrderContext(),
			$this->getAliasTransactionObject(),
			$this->getFailedTransactionObject(),
			$this->getPaymentCustomerContext()
		);
		
		if ($this->getTransaction() !== null) {
			if ($this->visibleFormFields !== null && count($this->visibleFormFields) > 0) {
				$link = new Link();
				$this->formActionUrl = $link->getModuleLink('postfinancecw', 'iframe', array('cw_transaction_id' => $this->getTransaction()->getTransactionId(), 'id_module' => Tools::getValue('id_module', null)) , true);
			}
			else {
				$this->prepareWithFormData(array(), $this->getTransaction());
			}
		}
		else {
			$this->formActionUrl = "#";
		}
		$this->persistTransaction();
	}

	protected function getTransactionAjaxResponseCallback() {
		$this->prepareWithFormData($this->getFormData(), $this->getTransaction());
		$html = $this->getIframe();
		return 'function() { var html = "' . urlencode($html) . '"; html = decodeURIComponent(html.replace(/\+/g, \' \')); form.replaceWith(html); }';
	}
	
	public function prepareWithFormData(array $formData, PostFinanceCw_Entity_Transaction $transaction) {
		$this->iframeUrl = $this->getInterfaceAdapter()->getIframeUrl($transaction->getTransactionObject(), $formData);
		$this->iframeHeight = $this->getInterfaceAdapter()->getIframeHeight($transaction->getTransactionObject(), $formData);
		if ($transaction->getTransactionObject()->isAuthorizationFailed()) {
			$this->iframeUrl = null;
			$errorMessage = current($transaction->getTransactionObject()->getErrorMessages());
			/* @var $errorMessage Customweb_Payment_Authorization_IErrorMessage */
			if (is_object($errorMessage)) {
				$this->errorMessage = $errorMessage->getUserMessage();
			}
			else {
				$this->errorMessage = PostFinanceCw::translate("Failed to initialize transaction with an unkown error");
			}
		}
	}
	
	public function getIframe() {
		if ($this->iframeUrl !== null) {
			$this->smarty->assign(array(
				'iframeUrl' => $this->iframeUrl,
				'iframeHeight' => $this->iframeHeight,
			));
			return $this->renderTemplate('form/iframe.tpl');
		}
		else {
			return $this->renderErrorMessage($this->errorMessage);
		}
	}
	
	protected function getOrderConfirmationButton() {
		if ($this->formActionUrl === null) {
			return '';
		}
		else {
			return parent::getOrderConfirmationButton();
		}
	}
	
	protected function getAdditionalFormHtml() {
		if ($this->formActionUrl === null) {
			return $this->getIframe();
		}
		else {
			return parent::getAdditionalFormHtml();
		}
	}
	
	protected function getVisibleFormFields() {
		return $this->visibleFormFields;
	}
	
	protected function getFormActionUrl() {
		return $this->formActionUrl;
	}
	
}