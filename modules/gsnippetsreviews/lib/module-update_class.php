<?php
/**
 * module-update_class.php file defines method for updating the module
 */

class BT_GsrModuleUpdate
{
	/**
	 * @var $aErrors : store errors
	 */
	protected $aErrors = array();

	/**
	 * Magic Method __construct
	 *
	 * @category update collection
	 */
	public function __construct()
	{

	}


	/**
	 * run() method execute required function
	 *
	 * @param string $sType
	 * @param array $aParam
	 */
	public function run($sType, array $aParam = null)
	{
		// get type
		$sType = empty($sType)? 'tables' : $sType;

		switch ($sType) {
			case 'tables' : // use case - update tables
			case 'fields' : // use case - update fields
			case 'hooks' : // use case - update hooks
			case 'templates' : // use case - update templates
			case 'shopId' : // use case - update shop ID in case of old version
			case 'langId' : // use case - update lang ID in case of old version
			case 'ratingDate' : // use case - update rating Date in case of old version
			case 'moduleAdminTab' : // use case - update old module admin tab version
				// execute match function
				call_user_func_array(array($this, 'update' . ucfirst($sType)), array($aParam));
				break;
			default :
				break;
		}
	}


	/**
	 * updateTables() method update tables if required
	 *
	 * @param array $aParam
	 * @return bool
	 */
	private function updateTables(array $aParam = null)
	{
		$bResult = false;

		// set transaction
		Db::getInstance()->Execute('BEGIN');

		if (!empty($GLOBALS[_GSR_MODULE_NAME . '_SQL_UPDATE']['table'])) {
			// loop on each elt to update SQL
			foreach ($GLOBALS[_GSR_MODULE_NAME . '_SQL_UPDATE']['table'] as $sTable => $sSqlFile) {
				// execute query
				$bResult = Db::getInstance()->ExecuteS('SHOW TABLES LIKE "' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_'. $sTable .'"');

				// if empty - update
				if (empty($bResult)) {
					require_once(_GSR_PATH_CONF . 'install.conf.php');
					require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');

					// use case - KO update
					if (!BT_InstallCtrl::run('install', 'sql', _GSR_PATH_SQL . $sSqlFile)) {
						$this->aErrors[] = array('table' => $sTable, 'file' => $sSqlFile);
					}
				}
			}
		}

		if (empty($this->aErrors)) {
			Db::getInstance()->Execute('COMMIT');

			$bResult = true;
		}
		else {
			Db::getInstance()->Execute('ROLLBACK');
		}

		return $bResult;
	}


	/**
	 * updateFields() method update fields if required
	 *
	 * @param array $aParam
	 * @return bool
	 */
	private function updateFields(array $aParam = null)
	{
		$bResult = false;

		// set transaction
		Db::getInstance()->Execute('BEGIN');

		if (!empty($GLOBALS[_GSR_MODULE_NAME . '_SQL_UPDATE']['field'])) {
			// loop on each elt to update SQL
			foreach ($GLOBALS[_GSR_MODULE_NAME . '_SQL_UPDATE']['field'] as $sFieldName => $aOption) {
				// execute query
				$bResult = Db::getInstance()->ExecuteS('SHOW COLUMNS FROM ' .  _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_'. $aOption['table'] . ' LIKE "' . $sFieldName .'"');

				// if empty - update
				if (empty($bResult)) {
					require_once(_GSR_PATH_CONF . 'install.conf.php');
					require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');

					// use case - KO update
					if (!BT_InstallCtrl::run('install', 'sql', _GSR_PATH_SQL . $aOption['file'])) {
						$aErrors[] = array('field' => $sFieldName, 'linked' => $aOption['table'], 'file' => $aOption['file']);
					}
				}
			}
		}

		if (empty($this->aErrors)) {
			Db::getInstance()->Execute('COMMIT');

			$bResult = true;
		}
		else {
			Db::getInstance()->Execute('ROLLBACK');
		}

		return $bResult;
	}

	/**
	 * updateHooks() method update hooks if required
	 *
	 * @param array $aParam
	 * @return bool
	 */
	private function updateHooks(array $aParam = null)
	{
		$bResult = true;

		require_once(_GSR_PATH_CONF . 'install.conf.php');
		require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');

		// use case - hook register ko
		if (!BT_InstallCtrl::run('install', 'config', array('bHookOnly' => true))) {
			$this->aErrors[] = array('table' => 'ps_hook_module', 'file' => GSnippetsReviews::$oModule->l('register hooks KO'));

			$bResult = false;
		}

		return $bResult;
	}


	/**
	 * updateTemplates() method update templates if required
	 *
	 * @param array $aParam
	 */
	private function updateTemplates(array $aParam = null)
	{
		require_once(_GSR_PATH_LIB_COMMON . 'dir-reader.class.php');

		// get templates files
		$aTplFiles = BT_DirReader::create()->run(array('path' => _GSR_PATH_TPL, 'recursive' => true, 'extension' => 'tpl', 'subpath' => true));

		if (!empty($aTplFiles)) {
			global $smarty;

			if (method_exists($smarty, 'clearCompiledTemplate')) {
				$smarty->clearCompiledTemplate();
			}
			elseif (method_exists($smarty, 'clear_compiled_tpl')) {
				foreach ($aTplFiles as $aFile) {
					$smarty->clear_compiled_tpl($aFile['filename']);
				}
			}
		}
	}


	/**
	 * updateShopId() method update shop IDs
	 *
	 * @param array $aParam
	 */
	private function updateShopId(array $aParam = null)
	{
		$bResult = false;

		require_once(_GSR_PATH_LIB . 'module-dao_class.php');

		$aTables = array(
			array('table' => 'rating', 'prefix' => 'RTG'),
			array('table' => 'review', 'prefix' => 'RVW'),
			array('table' => 'callback', 'prefix' => 'CBK'),
			array('table' => 'customer', 'prefix' => 'CUST'),
		);

		foreach ($aTables as $aTable) {
			if (!BT_GsrModuleDao::updateTablesShopId($aTable['table'], $aTable['prefix'])) {
				$this->aErrors[] = array('table' => $aTable['table'], 'file' => $this->l('An error occurred during shop id updating'));
			}
		}
	}


	/**
	 * updateLangId() method update lang IDs
	 *
	 * @param array $aParam
	 */
	private function updateLangId(array $aParam = null)
	{
		require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

		// get rating without lang id filled
		$aRatings = BT_ReviewCtrl::create()->run('getRatings', array('langId' => 0));

		// update ratings and reviews lang ID
		if (!empty($aRatings)) {
			foreach ($aRatings as $aRating) {
				// use case - validate obj
				if (($oProduct = BT_GsrModuleTools::isProductObj($aRating['prodId'], GSnippetsReviews::$iCurrentLang, true)) != false) {
					// get each associated review
					$aReview = BT_ReviewCtrl::create()->run('getReviews', array('shopId' => GSnippetsReviews::$iShopId, 'ratingId' => $aRating['id'], 'langId' => 0));

					// get lang id
					$iLangId = !empty($aReview['data']['iLangId'])? $aReview['data']['iLangId'] : GSnippetsReviews::$iCurrentLang;

					if (!empty($aReview)) {
						if (!BT_ReviewCtrl::create()->run('updateReview', array('id' => $aReview['id'], 'langId' => $iLangId))) {
							$aErrors[] = array('field' => 'RVW_LANG_ID', 'file' => $this->l('review ID') . ' = ' . $aReview['id'], 'linked' => $this->l('ps_gsr_review'));
						}
					}
					// set lang id
					if (!BT_ReviewCtrl::create()->run('updateRating', array('id' => $aRating['id'], 'langId' => $iLangId))) {
						$aErrors[] = array('field' => 'RTG_LANG_ID', 'file' => $this->l('rating ID') . ' = ' . $aRating['id'], 'linked' => $this->l('ps_gsr_rating'));
					}
					unset($aReview);
				}
				// destruct
				unset($oProduct);
			}
		}
		unset($aRatings);
	}

	/**
	 * updateLangId() method update rating date
	 *
	 * @param array $aParam
	 */
	private function updateRatingDate(array $aParam = null)
	{
		require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

		// get rating without lang id filled
		$aRatings = BT_ReviewCtrl::create()->run('getRatings', array('date' => 0));

		// update ratings and reviews lang ID
		if (!empty($aRatings)) {

			$iDateAdd = time();

			foreach ($aRatings as $aRating) {
				// use case - validate obj
				if (($oProduct = BT_GsrModuleTools::isProductObj($aRating['prodId'], GSnippetsReviews::$iCurrentLang, true)) != false) {
					// get each associated review
					$aReview = BT_ReviewCtrl::create()->run('getReviews', array('shopId' => GSnippetsReviews::$iShopId, 'ratingId' => $aRating['id']));

					if (!empty($aReview)) {
						$iDateAdd = $aReview['dateAdd'];
					}

					// set rating date
					if (!BT_ReviewCtrl::create()->run('updateRating', array('id' => $aRating['id'], 'date' => $iDateAdd))) {
						$aErrors[] = array('field' => 'RTG_DATE_ADD', 'file' => $this->l('rating ID') . ' = ' . $aRating['id'], 'linked' => $this->l('ps_gsr_rating'));
					}
					unset($aReview);
				}
				// destruct
				unset($oProduct);
			}
		}
		unset($aRatings);
	}

	/**
	 * updateModuleAdminTab() method update module admin tab in case of an update from an old version to PS 1.6
	 *
	 * @param array $aParam
	 */
	private function updateModuleAdminTab(array $aParam = null)
	{
		foreach ($GLOBALS[_GSR_MODULE_NAME . '_TABS'] as $sModuleTabName => $aTab) {
			if (isset($aTab['oldName'])) {
				if (Tab::getIdFromClassName($aTab['oldName']) != false) {
					// include install ctrl class
					require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');

					// use case - if uninstall succeeded
					if (BT_InstallCtrl::run('uninstall', 'tab', array('name' => $aTab['oldName']))) {
						// install new admin tab
						BT_InstallCtrl::run('install', 'tab', array('name' => $sModuleTabName));
					}
				}
			}
			// delete the old PHP file present in the module's root folder
			if (file_exists(_GSR_PATH_ROOT . $sModuleTabName . '.php')) {
				unlink(_GSR_PATH_ROOT . $sModuleTabName . '.php');
			}
		}
	}


	/**
	 * getErrors() method returns errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->aErrors;
	}

	/**
	 * create() method manages singleton
	 *
	 * @return array
	 */
	public static function create()
	{
		static $oModuleUpdate;

		if (null === $oModuleUpdate) {
			$oModuleUpdate = new BT_GsrModuleUpdate();
		}
		return $oModuleUpdate;
	}
}