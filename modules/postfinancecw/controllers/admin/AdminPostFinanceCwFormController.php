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

require_once 'Customweb/Form.php';
require_once 'Customweb/IForm.php';

require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/BackendFormRenderer.php';


/**
 * This calls intercepts the storage process of the refund executed in the backend of PrestaShop.
 *
 * @author Thomas Hunziker
 *         			 		  	    
 */
class AdminPostFinanceCwFormController extends AdminController
{
	public function __construct() {
		$this->className = 'AdminPostFinanceCwFormController';
		parent::__construct();
		$this->context->smarty->addTemplateDir($this->getTemplatePath());
		$this->tpl_folder = 'postfinancecw_form/';
		$this->bootstrap = true;
	}

	public function initContent()
	{
		$this->addCSS(_MODULE_DIR_ . 'postfinancecw/css/admin.css');
		
		$form = Tools::getValue('form', NULL);
		if ($form !== null && isset($_GET['save']) && $_GET['save'] == 'true') {
			$this->handleSave();
		}
		else if ($form !== null) {
			$this->handleView();
		}
		else {
			$this->handleList();
		}

		parent::initContent();
	}
	
	private function handleList() {
		$adapter = $this->getBackendFormAdapter();
		$forms = $adapter->getForms();
		$this->context->smarty->assign('forms', $forms);
		$this->context->smarty->assign('cronUrl', $this->createCronJobUrl());
		$this->display = 'list';
	}

	private function createCronJobUrl() {
		$link = new Link();
		return $link->getModuleLink('postfinancecw', 'cron', array(), true);
	}
	

	private function handleView() {
		$form = $this->getCurrentForm();
		
		if ($form->isProcessable()) {
			$url = PostFinanceCw::getAdminUrl('AdminPostFinanceCwForm', array('save' => 'true', 'form' => $form->getMachineName()));
			$form = new Customweb_Form($form);
			$form->setTargetUrl($url)->setRequestMethod(Customweb_IForm::REQUEST_METHOD_POST);
		}
		
		$renderer = new PostFinanceCw_BackendFormRenderer();
		$formHtml = $renderer->renderForm($form);
		
		$this->context->smarty->assign('form', $form);
		$this->context->smarty->assign('formHtml', $formHtml);
		
		$this->display = 'edit_form';
	}
	
	private function handleSave() {
		$form = $this->getCurrentForm();
		$params = $_REQUEST;
		if (isset($params['button'])) {
			$pressedButton = null;
			foreach ($params['button'] as $buttonName => $value) {
				foreach ($form->getButtons() as $button) {
					if ($button->getMachineName() == $buttonName) {
						$pressedButton = $button;
					}
				}
			}
			
			if ($pressedButton === null) {
				throw new Exception("Could not find pressed button.");
			}
			$this->getBackendFormAdapter()->processForm($form, $pressedButton, $params);
		}
		$this->handleView();
	}
	

	/**
	 * @return Customweb_Payment_BackendOperation_IForm
	 */
	private function getCurrentForm() {
		$adapter = $this->getBackendFormAdapter();
	
		if ($adapter !== null && isset($_GET['form'])) {
			$forms = $adapter->getForms();
			$formName = $_GET['form'];
			$currentForm = null;
			foreach ($forms as $form) {
				if ($form->getMachineName() == $formName) {
					return $form;
				}
			}
		}
	
		die('No form is set.');
	}
	
	/**
	 * @return Customweb_Payment_BackendOperation_Form_IAdapter
	 */
	private function getBackendFormAdapter() {
		return PostFinanceCw_Util::getBackendFormAdapter();
	}

	public function getTemplatePath()
	{
		return _PS_MODULE_DIR_ . 'postfinancecw/views/templates/back/';
	}


}