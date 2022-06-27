<?php
/**
 * install-config_class.php file defines InstallTab class to install / unistall module configuration
 */

class BT_InstallTab implements BT_IInstall
{
	/**
	 * @var array $aTabs : store tabs
	 */
	protected static $aTabs = array();

	/**
	 * install() method install of module
	 *
	 * @param mixed $mParam
	 * @return bool $bReturn : true => validate install, false => invalidate install
	 */
	public static function install($mParam = null)
	{
		// declare return
		$bReturn = true;

		static $oTab;

		// instantiate
		if (null === $oTab) {
			$oTab = new Tab();
		}

		// log jam to debug application
		if (defined('_GSR_LOG_JAM_CONFIG') && _GSR_LOG_JAM_CONFIG) {
			$bReturn = _GSR_LOG_JAM_CONFIG;
		}
		else {
			// set variables
			$aTmpLang = array();

			// get available languages
			$aLangs = Language::getLanguages(true);

			// set tabs to install
			if (!empty($mParam['name'])) {
				self::setTab($mParam['name']);
			}
			elseif (empty(self::$aTabs)) {
				self::setTab();
			}

			// loop on each admin tab
			foreach (self::$aTabs as $sAdminClassName => $aTab) {
				// use case - only active tabs
				if (!empty($aTab['active'])) {
					foreach ($aLangs as $aLang) {
						$aTmpLang[$aLang['id_lang']] = array_key_exists($aLang['iso_code'], $aTab['lang'])? $aTab['lang'][$aLang['iso_code']] : $aTab['lang']['en'];
					}
					$oTab->name          = $aTmpLang;
					$oTab->class_name    = $sAdminClassName;
					$oTab->module        = GSnippetsReviews::$oModule->name;
					$oTab->id_parent     = Tab::getIdFromClassName($aTab['parent']);

					// use case - copy icon tab
					if (file_exists(_PS_MODULE_DIR_ . $oTab->module . _GSR_PATH_VIEWS . _GSR_PATH_IMG . _GSR_TPL_ADMIN_PATH . $sAdminClassName . '.gif')) {
						@copy(_PS_MODULE_DIR_ . $oTab->module . _GSR_PATH_VIEWS . _GSR_PATH_IMG . _GSR_TPL_ADMIN_PATH . $sAdminClassName . '.gif', _PS_IMG_DIR_ . 't/' . $sAdminClassName . '.gif');
					}

					// save admin tab
					if (false == $oTab->save()) {
						$bReturn = false;
					}
				}
			}
		}
		// destruct
		unset($mParam);
		unset($oTab);

		return $bReturn;
	}

	/**
	 * uninstall() method uninstall of module
	 *
	 * @param mixed $mParam
	 * @return bool $bReturn : true => validate uninstall, false => invalidate uninstall
	 */
	public static function uninstall($mParam = null)
	{
		// set return execution
		$bReturn = true;

		// set tabs to uninstall
		if (!empty($mParam['name'])) {
			self::setTab($mParam['name']);
		}
		elseif (empty(self::$aTabs)) {
			self::setTab();
		}

		// loop on each admin tab
		foreach (self::$aTabs as $sAdminClassName => $aTab) {
			// get ID
			$iTabId = Tab::getIdFromClassName($sAdminClassName);

			if (!empty($iTabId)) {
				// instantiate
				$oTab = new Tab($iTabId);

				// use case - check delete
				if (false == $oTab->delete()) {
					$bReturn = false;
				}
				else {
					if (!defined('_PS_IMG_DIR')) {
						define('_PS_IMG_DIR', _PS_ROOT_DIR_ . '/img/');
					}
					if (file_exists(_PS_IMG_DIR . 't/' . $sAdminClassName . '.gif')) {
						@unlink(_PS_IMG_DIR . 't/' . $sAdminClassName . '.gif');
					}
				}
				unset($oTab);
			}
		}
		unset($mParam);

		return $bReturn;
	}

	/**
	 * setTab() method set module's tab as specific one if necessary
	 *
	 * @param string $sTabName
	 * @return bool $bReturn : true => validate uninstall, false => invalidate uninstall
	 */
	public static function setTab($sTabName = null)
	{
		// set specific tab
		if ($sTabName !== null && isset($GLOBALS[_GSR_MODULE_NAME . '_TABS'][$sTabName])) {
			self::$aTabs = array($sTabName => $GLOBALS[_GSR_MODULE_NAME . '_TABS'][$sTabName]);
		}
		else {
			self::$aTabs = $GLOBALS[_GSR_MODULE_NAME . '_TABS'];
		}
	}
}