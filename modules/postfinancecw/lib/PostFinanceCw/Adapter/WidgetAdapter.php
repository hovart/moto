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
class PostFinanceCw_Adapter_WidgetAdapter extends PostFinanceCw_Adapter_AbstractAdapter {

	private $visibleFormFields = array();
	private $formActionUrl = null;
	private $widgetHtml = null;
	private $errorMessage = '';

	public function getPaymentAdapterInterfaceName() {
		return 'Customweb_Payment_Authorization_Widget_IAdapter';
	}

	/**
	 * @return Customweb_Payment_Authorization_Widget_IAdapter
	 */
	public function getInterfaceAdapter() {
		return parent::getInterfaceAdapter();
	}

	protected function getTransactionAjaxResponseCallback() {
		$this->prepareWithFormData($this->getFormData(), $this->getTransaction());
		$html = $this->getWidget();
		return 'function() { 
					var html = "' . urlencode($html) . '"; 
					html = decodeURIComponent(html.replace(/\+/g, \' \')); 
					var jQueryAjaxSettingsCache = jQuery.ajaxSettings.cache; 
					jQuery.ajaxSettings.cache = true; 
					form.replaceWith(html); 
					jQuery.ajaxSettings.cache = jQueryAjaxSettingsCache;
				}';
		// We need to deactivate the jQuery cache temporarly to prevent adding to each URL '_=TIMESTAMP'. This can cause issues with the loaded resources.
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
				$this->formActionUrl = $link->getModuleLink('postfinancecw', 'widget', array('cw_transaction_id' => $this->getTransaction()->getTransactionId(), 'id_module' => Tools::getValue('id_module', null)) , true);
			}
			else {
				$this->prepareWithFormData(array(), $this->getTransaction());
			}
		}
		else {
			$this->formActionUrl = '#';
		}
		$this->persistTransaction();
	}

	public function prepareWithFormData(array $formData, PostFinanceCw_Entity_Transaction $transaction) {
		$this->widgetHtml = $this->getInterfaceAdapter()->getWidgetHTML($transaction->getTransactionObject(), $formData);
		if ($transaction->getTransactionObject()->isAuthorizationFailed()) {
			$this->widgetHtml = null;
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

	public function getWidget() {
		if ($this->widgetHtml !== null) {
			$this->smarty->assign(array(
				'widget' => $this->widgetHtml,
			));
			return $this->renderTemplate('form/widget.tpl');
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
			return $this->getWidget();
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