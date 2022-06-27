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


require_once 'PostFinanceCw/FormRenderer.php';
require_once 'PostFinanceCw/Adapter/AbstractAdapter.php';


/**
 * @author Thomas Hunziker
 * @Bean
 *
 */
class PostFinanceCw_Adapter_AjaxAdapter extends PostFinanceCw_Adapter_AbstractAdapter {
	
	private $visibleFormFields = array();
	private $ajaxScriptUrl = null;
	private $javaScriptCallbackFunction = null;
	
	public function getPaymentAdapterInterfaceName() {
		return 'Customweb_Payment_Authorization_Ajax_IAdapter';
	}
	
	/**
	 * @return Customweb_Payment_Authorization_Ajax_IAdapter
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
			$this->ajaxScriptUrl = $this->getInterfaceAdapter()->getAjaxFileUrl($this->getTransaction()->getTransactionObject());
			$this->javaScriptCallbackFunction = $this->getInterfaceAdapter()->getJavaScriptCallbackFunction($this->getTransaction()->getTransactionObject());
		}
		$this->persistTransaction();
	}
	
	protected function getTransactionAjaxResponseCallback() {
		$transactionObject = $this->getTransaction()->getTransactionObject();
		$ajaxScriptUrl = $this->getInterfaceAdapter()->getAjaxFileUrl($transactionObject);
		$callback = $this->getInterfaceAdapter()->getJavaScriptCallbackFunction($transactionObject);
		return 'function() { jQuery.getScript("' . $ajaxScriptUrl .'").done(function(){(' . $callback . '(fields))}).fail(function(){alert("unable to load the AJAX remote script.")});}';
	}
	
	protected function getPaymentFormPane($renderOnLoadJS) {
	
		$this->preparePaymentFormPane();
		$templateVars = $this->getBaseVariables();

		
		$templateVars['aliasForm'] = $this->getAliasForm();
		
		
		$templateVars['ajaxScriptUrl'] = $this->ajaxScriptUrl;
		$templateVars['ajaxSubmitCallback'] = $this->javaScriptCallbackFunction;
		$templateVars['sendFromDataBack'] = false;
		
		if ($this->getTransaction() === null) {
			$templateVars['ajaxPendingOrderSubmit'] = true;
		}
		
		$visibleFormFields = $this->getVisibleFormFields();
		if ($visibleFormFields !== null && count($visibleFormFields) > 0) {
			$renderer = new PostFinanceCw_FormRenderer($this->paymentMethod->getPaymentMethodName());
			$renderer->setRenderOnLoadJs($renderOnLoadJS);
			$templateVars['visibleFormFields'] = $renderer->renderElements($visibleFormFields);
		}
		
		$templateVars['buttons'] = $this->getOrderConfirmationButton();
	
		$this->smarty->assign($templateVars);
		return $this->renderTemplate('form/pane.tpl');
	}
	
	protected function getVisibleFormFields() {
		return $this->visibleFormFields;
	}
		
}