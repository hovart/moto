<?php
/**
 * account.php file defines account front controller
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2015 Business Tech SARL
 */

class GSnippetsReviewsAccountModuleFrontController extends ModuleFrontController
{
	/**
	 * init() method init module front controller
	 */
	public function init()
	{
		// exec parent
		parent::init();

		// include main module's classes
		require_once($this->module->getLocalPath() . 'gsnippetsreviews.php');
		require_once(_GSR_PATH_CONF . 'hook.conf.php');
		require_once(_GSR_PATH_LIB_HOOK . 'hook-ctrl_class.php');
	}

	/**
	 * initContent() method init module front controller content
	 *
	 * @return bool
	 */
	public function initContent()
	{
		// exec parent
		parent::initContent();

		// use case - customer not logged
		if (!Context::getContext()->customer->isLogged()) {
			Tools::redirect('index.php?controller=authentication&redirect=module&module=' . _GSR_MODULE_SET_NAME . '&action=account');
		}
		// use case - if customer id exists
		elseif (Context::getContext()->customer->id)
		{
			// instantiate
			$oModule = new GSnippetsReviews();

			// Own Module's front controller
			$oHook = new BT_GsrHookCtrl('display', 'customerAccount');

			// displays good block content
			$aContent = $oHook->run(array('display' => 'review', 'bCtrlTplPath' => true));

			// set module name
			$aContent['assign']['sGsrModuleName'] = Tools::strtolower(_GSR_MODULE_NAME);

			foreach ($aContent['assign'] as $sKey => $mValue) {
				$this->context->smarty->assign($sKey, $mValue);
			}

			// get FancyBox plugin
			$aJsCss = Media::getJqueryPluginPath('fancybox');

			// add fancybox plugin
			if (!empty($aJsCss['js']) && !empty($aJsCss['css'])) {
				Context::getContext()->controller->addCSS($aJsCss['css']);
				Context::getContext()->controller->addJS($aJsCss['js']);
			}

			if (GSnippetsReviews::$bCompare17) {
				$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', $aContent['tpl']): $aContent['tpl'];
				$aContent['tpl'] = 'module:gsnippetsreviews/views/templates/front/'. $sTpl;
				unset($sTpl);
			}

			$this->setTemplate($aContent['tpl']);

			unset($oModule);
			unset($aJsCss);
		}
	}
}