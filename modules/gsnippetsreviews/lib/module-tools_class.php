<?php
/**
 * module-tools_class.php file defines all tools method in module - transverse
 */

class BT_GsrModuleTools
{
	/**
	 * formatFbPostUrl() method format FB Post URL
	 *
	 * @param int $iFbPageId
	 * @param int $iFbPostId
	 * @return string
	 */
	public static function formatFbPostUrl($iFbPageId, $iFbPostId)
	{
		return _GSR_FB_URL . $iFbPageId .  '/posts/' . $iFbPostId . '/likes';
	}


	/**
	 * formatReviewReportUrl() method format the URL of review report action
	 *
	 * @param int $iProductId
	 * @param string $sSecureKey
	 * @param string $sURI
	 * @param int $iReviewId
	 * @return string
	 */
	public static function formatReviewReportUrl($iProductId, $sSecureKey, $sURI, $iReviewId = null)
	{
		return _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php?sAction='
		. $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS']['reportForm']['action']
		. '&sType=' . $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS']['reportForm']['type']
		. '&iProductId=' . $iProductId
		. '&btKey=' . $sSecureKey
		. '&sURI=' . urlencode($sURI)
		. ($iReviewId !== null? '&iId=' . $iReviewId : '');
	}

	/**
	 * getEnableVouchers() method returns enable vouchers
	 *
	 * @param string $sType
	 * @return mixed
	 */
	public static function getEnableVouchers($sType = null)
	{
		// set variables
		$mEnableVouchers = null;

		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_VOUCHERS'])) {
			$mEnableVouchers = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_VOUCHERS']);

			if ($sType !== null && isset($mEnableVouchers[$sType])) {
				$mEnableVouchers = $mEnableVouchers[$sType];
			}
		}

		return $mEnableVouchers;
	}

	/**
	 * checkMailLanguages() method check if active language match to mail languages folders
	 *
	 * @return array
	 */
	public static function checkMailLanguages()
	{
		// set
		$aLangError = array();

		// get active languages
		$aLanguages = Language::getLanguages(true);

		foreach ($aLanguages as $aLanguage) {
			if (!is_dir(_GSR_PATH_MAILS . $aLanguage['iso_code'] . '/')) {
				$aLangError[$aLanguage['iso_code']] = $aLanguage['name'];
			}
		}
		return $aLangError;
	}

	/**
	 * getCustomerLanguage() method check if customer language is active for displaying reviews in same language
	 * and returns the current language ID
	 *
	 * @return int
	 */
	public static function getCustomerLanguage()
	{
		return (
			!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG'])? GSnippetsReviews::$iCurrentLang : false
		);
	}

	/**
	 * addCallback() method check the callback for each order and add it if necessary
	 *
	 * @param int $iShopId
	 * @param int $iOrderId
	 * @param int $iCustomerId
	 * @param int $iLangId
	 * @param string $sDate
	 * @param bool $bReturnOnly
	 * @return mixed
	 */
	public static function addCallback($iShopId, $iOrderId, $iCustomerId, $iLangId, $sDate = null, $bReturnOnly = false)
	{
		$mResult = $bReturnOnly? array() : false;

		// include
		require_once(_GSR_PATH_LIB . 'module-dao_class.php');

		// check if customer is guest
		$oCustomer = new Customer($iCustomerId);

		if ($oCustomer->active
//			&& !$oCustomer->isGuest()
			&& (!BT_GsrModuleDao::isCallBackExist($iShopId, $iOrderId)
			|| $bReturnOnly)
		) {
			// use case - get customer callback status
			$bCallBack = BT_GsrModuleDao::getCustCallbackStatus($iShopId, $iCustomerId);

			if (false === $bCallBack) {
				// add customer callback status
				BT_GsrModuleDao::addCustCallbackStatus($iShopId, $iCustomerId, 1);

				$bCallBack = 1;
			}
			// use case - only in adding status or with activated status
			if (!empty($bCallBack)) {
				// use case - get data of purchased products
				$aProducts = BT_GsrModuleDao::getProductsByOrder($iOrderId);

				if (!empty($aProducts)) {
					foreach ($aProducts as $aProduct) {
						// hack for product without all data (test product for example)
						$aProduct['id_product'] = $aProduct['prodId'];

						// hack for version under 1.3.0.1
						$aProduct['rate'] = 0;
						$aAttributes = Product::getProductProperties($iLangId, $aProduct);
						$aData[] = array('prodId' => $aProduct['id_product'] ,'title' => $aAttributes['name'], 'category' => $aAttributes['category'], 'link' => $aAttributes['link']);

						unset($aAttributes);
					}
					// use case - register callbacks
					if (empty($bReturnOnly)) {
						// add associated data to order
						$mResult = BT_GsrModuleDao::addCallBack($iShopId, $iOrderId, $iCustomerId, array('langId' => $iLangId, 'langIso' => BT_GsrModuleTools::getLangIso($iLangId), 'products' => $aData));
					}
					// use case - return callbacks data only
					else {
						$oCustomer = new Customer($iCustomerId);

						if (Validate::isLoadedObject($oCustomer)
							&& $oCustomer->active
						) {
							$mResult = array(
								'cbkId' => 0,
								'id' => $iOrderId,
								'shopId' => $iShopId,
								'date' => $sDate,
								'data' => serialize(array(
									'langId' => $iLangId,
									'langIso' => BT_GsrModuleTools::getLangIso($iLangId),
									'products' => $aData
								)),
								'custId' => $iCustomerId,
								'email' => $oCustomer->email,
							);
						}
						unset($oCustomer);
					}
					unset($aData);
				}
				unset($aProducts);
			}
		}
		unset($oCustomer);

		return $mResult;
	}

	/**
	 * getFrontPageType() method check the current page and returns the page type
	 *
	 * @return string
	 */
	public static function getFrontPageType()
	{
		// use case - product page
		if (Tools::getValue('id_product')) {
			$sType = 'product';
		}
		// use case - category page
		elseif (Tools::getValue('id_category')){
			$sType = 'category';
		}
		// use case - manufacturer page
		elseif (Tools::getValue('id_manufacturer')){
			$sType = 'manufacturer';
		}
		// use case - HP
		elseif (Tools::getValue('controller') == 'index') {
			$sType = 'home';
		}
		else {
			$sType = 'other';
		}

		return $sType;
	}

	/**
	 * setSecureKey() method sets a secure key
	 *
	 * @param mixed $mEltId
	 * @return string
	 */
	public static function setSecureKey($mEltId)
	{
		return md5(_GSR_MODULE_NAME . $mEltId);
	}


	/**
	 * getCustomerAddressForReview() method returns a customer address if exists
	 *
	 * @param int $iCustId
	 * @param int $iLangId
	 * @return string
	 */
	public static function getCustomerAddressForReview($iCustId, $iLangId)
	{
		$sAddress = '';

		// set the current customer
		$oCustomer = new Customer($iCustId);

		// get addresses and get a city name
		$aAddresses = $oCustomer->getAddresses($iLangId);

		if (!empty($aAddresses)) {
			foreach ($aAddresses as $aAddress) {
				// get city
				if (!empty($aAddress['city']) && empty($aRating['city'])) {
					$sCity = $aAddress['city'];
				}
				// get city
				if (!empty($aAddress['id_country']) && empty($aRating['country'])) {
					$oCountry = new Country($aAddress['id_country'], $iLangId);
					$sCountry = $oCountry->name;
					unset($oCountry);
				}
			}
		}
		unset($aAddresses);

		// set localization
		if (!empty($sCity) && !empty($sCountry)) {
			$sAddress = ucfirst($sCity) . ', ' . ucfirst($sCountry);
		}
		elseif (!empty($sCity) && empty($sCountry)) {
			$sAddress = ucfirst($sCity);
		}
		elseif (empty($sCity) && !empty($sCountry)) {
			$sAddress = ucfirst($sCountry);
		}

		return $sAddress;
	}

	/**
	 * getReviewSnippetsConf() method returns review snippets options
	 * and returns the current language ID
	 *
	 * @return array
	 */
	public static function getReviewSnippetsConf()
	{
		return (
			array(
				'bUseRating'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RATING'],
				'bUseReviewDate'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_DATE'],
				'bUseReviewTitle'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_TITLE'],
				'bUseReviewDesc'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_DESC'],
				'bUseReviewAggregate'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_AGGREGATE'],
			)
		);
	}


	/**
	 * getAverageOptions() method returns review average options
	 * and returns the current language ID
	 *
	 * @param float fAverage
	 * @return array
	 */
	public static function getAverageOptions($fAverage)
	{
		$aAssign = array();

		$aAssign['iAverage'] = $fAverage;

		if ($fAverage != intval($fAverage)) {
			$aAssign['iRating'] = intval($fAverage);
			$aAssign['bHalfStar'] = true;
		}
		else {
			$aAssign['bHalfStar'] = false;
			$aAssign['iRating'] = $fAverage;
		}

		$aAssign['aParamStars'] = array(
			'star' => _GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
		);

		return $aAssign;
	}


	/**
	 * checkOrderStatus() method check if status
	 *
	 * @param int $iOrderStateId
	 * @return bool
	 */
	public static function checkOrderStatus($iOrderStateId)
	{
		// set
		$bReturn = false;

		// get selection
		$aSelection = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION']) : GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'];

		if (!is_array($aSelection)) {
			$bReturn = true;
		}
		else {
			$bReturn = in_array($iOrderStateId, $aSelection)? true : false;
		}

		return $bReturn;
	}

	/**
	 * translateJsMsg() method sets good errors' translations
	 */
	public static function translateJsMsg()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['delay'] = GSnippetsReviews::$oModule->l('You have not filled out a numeric for delay option', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['fbId'] = GSnippetsReviews::$oModule->l('You have not filled out a numeric for Facebook App ID option', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['fbsecret'] = GSnippetsReviews::$oModule->l('You have not filled out Facebook App Secret option', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['reviewDate'] = GSnippetsReviews::$oModule->l('You have not filled out the review\'s date', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['title'] = GSnippetsReviews::$oModule->l('You have not filled out the title', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['comment'] = GSnippetsReviews::$oModule->l('You have not filled out the comment', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['report'] = GSnippetsReviews::$oModule->l('You have not filled out the report comment', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['rating'] = GSnippetsReviews::$oModule->l('You have not selected the rating for the review', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['checkreview'] = GSnippetsReviews::$oModule->l('You have not selected one review', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['email'] = GSnippetsReviews::$oModule->l('You have not filled out your email', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['status'] = GSnippetsReviews::$oModule->l('You didn\'t select any status', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['vouchercode'] = GSnippetsReviews::$oModule->l('You have not filled out the voucher code', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['voucheramount'] = GSnippetsReviews::$oModule->l('You have left 0 as value for voucher\'s value', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['voucherminimum'] = GSnippetsReviews::$oModule->l('Minimum amount is not a numeric', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['vouchermaximum'] = GSnippetsReviews::$oModule->l('Maximum quantity is not a numeric', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['vouchervalidity'] = GSnippetsReviews::$oModule->l('You have left 0 as value for voucher\'s validity', 'module-tools_class');
		foreach (Language::getLanguages() as $aLang) {
			$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['emailTitle'][$aLang['id_lang']] = GSnippetsReviews::$oModule->l('You have not filled out your title or text with language', 'module-tools_class')
				. ' ' . $aLang['name'] . '. ' . GSnippetsReviews::$oModule->l('Click on the language drop-down list in order to fill out the correct language field(s).', 'module-tools_class');
			$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['emaillCategory'][$aLang['id_lang']] = GSnippetsReviews::$oModule->l('You have not filled out your category label with language', 'module-tools_class')
				. ' ' . $aLang['name'] . '. ' . GSnippetsReviews::$oModule->l('Click on the language drop-down list in order to fill out the correct language field(s).', 'module-tools_class');
			$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['emaillProduct'][$aLang['id_lang']] = GSnippetsReviews::$oModule->l('You have not filled out your product label with language', 'module-tools_class')
				. ' ' . $aLang['name'] . '. ' . GSnippetsReviews::$oModule->l('Click on the language drop-down list in order to fill out the correct language field(s).', 'module-tools_class');
			$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']['emaillSentence'][$aLang['id_lang']] = GSnippetsReviews::$oModule->l('You have not filled out your custom body sentence with language', 'module-tools_class')
				. ' ' . $aLang['name'] . '. ' . GSnippetsReviews::$oModule->l('Click on the language drop-down list in order to fill out the correct language field(s).', 'module-tools_class');
		}
	}

	/**
	 * translateAuthorize() method sets good authorize person's translations
	 */
	public static function translateAuthorize()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_AUTHORIZE']['buyer'] = GSnippetsReviews::$oModule->l('Only people who already bought the product to review', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_AUTHORIZE']['registered'] = GSnippetsReviews::$oModule->l('Any registered customer', 'module-tools_class');
	}

	/**
	 * translateDescTitle() method returns good translated desc title
	 */
	public static function translateDescTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC']['short'] = GSnippetsReviews::$oModule->l('Short description', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC']['long'] = GSnippetsReviews::$oModule->l('Long description', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC']['meta'] = GSnippetsReviews::$oModule->l('Meta description', 'module-tools_class');
	}

	/**
	 * translateHookTitle() method sets good translations' hook title
	 */
	public static function translateHookTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayHeader']['title'] = GSnippetsReviews::$oModule->l('Header', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayRightColumnProduct']['title'] = GSnippetsReviews::$oModule->l('Extra right', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayLeftColumnProduct']['title'] = GSnippetsReviews::$oModule->l('Extra left', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayProductButtons']['title'] = GSnippetsReviews::$oModule->l('Product actions', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayFooterProduct']['title'] = GSnippetsReviews::$oModule->l('Product footer', 'module-tools_class');

		if (GSnippetsReviews::$bCompare17) {
			$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayReassurance']['title'] = GSnippetsReviews::$oModule->l('Product reassurance', 'module-tools_class');
		}
	}

	/**
	 * translateBadgePagesTitle() method sets good used badge styles' translations
	 */
	public static function translateBadgePagesTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES']['home']['title'] = GSnippetsReviews::$oModule->l('for home page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES']['category']['title'] = GSnippetsReviews::$oModule->l('for each category page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES']['manufacturer']['title'] = GSnippetsReviews::$oModule->l('for each brand page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES']['product']['title'] = GSnippetsReviews::$oModule->l('for each product page', 'module-tools_class');
	}

	/**
	 * translateBadgeStylesTitle() method sets good used badge styles' translations
	 */
	public static function translateBadgeStylesTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['top'] = GSnippetsReviews::$oModule->l('Top, 100% width', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['home'] = GSnippetsReviews::$oModule->l('Home, 100% width', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['bottom'] = GSnippetsReviews::$oModule->l('Bottom, 100% width', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['colLeft'] = GSnippetsReviews::$oModule->l('Left column', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['colRight'] = GSnippetsReviews::$oModule->l('Right column', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES']['wizard'] = GSnippetsReviews::$oModule->l('Custom CSS', 'module-tools_class');
	}

	/**
	 * translateLastBlockPagesTitle() method sets good used last review block styles' translations
	 */
	public static function translateLastBlockPagesTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES']['other']['title'] = GSnippetsReviews::$oModule->l('other pages', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES']['home']['title'] = GSnippetsReviews::$oModule->l('home page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES']['category']['title'] = GSnippetsReviews::$oModule->l('category page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES']['manufacturer']['title'] = GSnippetsReviews::$oModule->l('brand page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES']['product']['title'] = GSnippetsReviews::$oModule->l('product page', 'module-tools_class');
	}

	/**
	 * translateLastBlockPosTitle() method sets good used last review block styles' translations
	 */
	public static function translateLastBlockPosTitle()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS']['top'] = GSnippetsReviews::$oModule->l('Top', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS']['home'] = GSnippetsReviews::$oModule->l('Home', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS']['bottom'] = GSnippetsReviews::$oModule->l('Footer', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS']['colLeft'] = GSnippetsReviews::$oModule->l('Left column', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS']['colRight'] = GSnippetsReviews::$oModule->l('Right column', 'module-tools_class');
	}

	/**
	 * translateReviewsDisplayMode() method sets display review mode's titles
	 */
	public static function translateReviewsDisplayMode()
	{
		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['classic'] = GSnippetsReviews::$oModule->l('Standard theme (1.6 and 1.7)', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['tabs'] = GSnippetsReviews::$oModule->l('Custom theme with tabs on product page', 'module-tools_class');
		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['bootstrap'] = GSnippetsReviews::$oModule->l('Custom theme with bootstrap tabs on product page', 'module-tools_class');
		if (GSnippetsReviews::$bCompare17) {
			$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['tabs17'] = GSnippetsReviews::$oModule->l('Standard theme 1.7 with description tabs', 'module-tools_class');
		}
//		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['rightsidetab'] = GSnippetsReviews::$oModule->l('Tab on right side', 'module-tools_class');
//		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['leftsidetab'] = GSnippetsReviews::$oModule->l('Tab on left side', 'module-tools_class');
//		$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['bottomtab'] = GSnippetsReviews::$oModule->l('Tab on bottom', 'module-tools_class');
	}

	/**
	 * updateConfiguration() method update new keys in new module version
	 */
	public static function updateConfiguration()
	{
		// check to update new module version
		foreach ($GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION'] as $sKey => $mVal) {
			// use case - not exists
			if (Configuration::get($sKey) === false) {
				// update key/ value
				Configuration::updateValue($sKey, $mVal);
			}
		}
	}

	/**
	 * getConfiguration() method sets all constant module in ps_configuration
	 *
	 * @param int $iShopId
	 */
	public static function getConfiguration($iShopId = null)
	{
		// get configuration options
		if (null !== $iShopId && is_numeric($iShopId)) {
			GSnippetsReviews::$aConfiguration = Configuration::getMultiple(array_keys($GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION']), null, null, $iShopId);
		}
		else {
			GSnippetsReviews::$aConfiguration = Configuration::getMultiple(array_keys($GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION']));
		}
	}

	/**
	 * isActiveLang() method sets the active language
	 *
	 * @param mixed $mLang
	 * @return bool
	 */
	public static function isActiveLang($mLang)
	{
		if (is_numeric($mLang)) {
		   $sField = 'id_lang';
		}
		else {
			$sField = 'iso_code';
			$mLang = strtolower($mLang);
		}

		$mResult = Db::getInstance()->getValue('SELECT count(*) FROM `'._DB_PREFIX_.'lang` WHERE active = 1 AND `' . $sField . '` = "' . pSQL($mLang) . '"');

		return (
			!empty($mResult)? true : false
		);
	}

	/**
	 * getLangIso() method set good iso lang
	 *
	 * @return string
	 */
	public static function getLangIso($iLangId = null)
	{
		if (null === $iLangId) {
			$iLangId = GSnippetsReviews::$iCurrentLang;
		}

		// get iso lang
		$sIsoLang = Language::getIsoById($iLangId);

		if (false === $sIsoLang) {
			$sIsoLang = 'en';
		}
		return $sIsoLang;
	}

	/**
	 * getLangId() method return Lang id from iso code
	 *
	 * @param string $sIsoCode
	 * @return int
	 */
	public static function getLangId($sIsoCode, $iDefaultId = null)
	{
		// get iso lang
		$iLangId = Language::getIdByIso($sIsoCode);

		if (empty($iLangId) && $iDefaultId !== null) {
			$iLangId = $iDefaultId;
		}
		return $iLangId;
	}
	
	/**
	 * getCurrency() method returns current currency sign or id
	 *
	 * @param string $sField : field name has to be returned
	 * @param string $iCurrencyId : currency id
	 * @return mixed : string or array
	 */
	public static function getCurrency($sField = null, $iCurrencyId = null)
	{
		// set
		$mCurrency = null;

		// get currency id
		if (null === $iCurrencyId) {
			$iCurrencyId = Configuration::get('PS_CURRENCY_DEFAULT');
		}

		$aCurrency = Currency::getCurrency($iCurrencyId);

		if ($sField !== null) {
			switch ($sField) {
				case 'id_currency' :
					$mCurrency = $aCurrency['id_currency'];
					break;
				case 'name' :
					$mCurrency = $aCurrency['name'];
					break;
				case 'iso_code' :
					$mCurrency = $aCurrency['iso_code'];
					break;
				case 'iso_code_num' :
					$mCurrency = $aCurrency['iso_code_num'];
					break;
				case 'sign' :
					if (empty($aCurrency['sign'])) {
						$oCurrency = new Currency($iCurrencyId);
						if (!empty($oCurrency)) {
							$mCurrency = $oCurrency->getSign();
						}
						unset($oCurrency);
					}
					else {
						$mCurrency = $aCurrency['sign'];
					}
					break;
				case 'conversion_rate' :
					$mCurrency = $aCurrency['conversion_rate'];
					break;
				case 'format' :
					if (empty($aCurrency['sign'])) {
						$oCurrency = new Currency($iCurrencyId);
						if (!empty($oCurrency)) {
							$mCurrency = $oCurrency->format;
						}
						unset($oCurrency);
					}
					else {
						$mCurrency = $aCurrency['format'];
					}
					break;
				default:
					$mCurrency = $aCurrency;
					break;
			}
		}

		return $mCurrency;
	}

	/**
	 * getTimeStamp() method returns timestamp
	 *
	 * @param string $sDate
	 * @param string $sType
	 * @return mixed : bool or int
	 */
	public static function getTimeStamp($sDate, $sType = 'en')
	{
		// set variable
		$iTimeStamp = false;

		// get date
		$aTmpDate = explode(' ', str_replace(array('-', '/', ':'), ' ', $sDate));

		if (count($aTmpDate) > 1) {
			$iHour = isset($aTmpDate[3])? $aTmpDate[3] : 0;
			$iMin = isset($aTmpDate[4])? $aTmpDate[4] : 0;
			$iSec = isset($aTmpDate[5])? $aTmpDate[5] : 0;

			if ($sType == 'en') {
				$iTimeStamp = mktime($iHour, $iMin, $iSec, $aTmpDate[0], $aTmpDate[1], $aTmpDate[2]);
			}
			elseif ($sType == 'db') {
				$iTimeStamp = mktime($iHour, $iMin, $iSec, $aTmpDate[1], $aTmpDate[2], $aTmpDate[0]);
			}
			else {
				$iTimeStamp = mktime($iHour, $iMin, $iSec, $aTmpDate[1], $aTmpDate[0], $aTmpDate[2]);
			}
		}
		// destruct
		unset($aTmpDate);

		return $iTimeStamp;
	}//getTimeStamp

	/**
	 * getUntilDate() method returns valid ISO format date
	 *
	 * @param string $sDate
	 * @return string
	 */
	public static function getUntilDate($sDate)
	{
		// set
		$sUntilDate = '';

		// get timestamp
		$iTimestamp = self::getTimeStamp($sDate);

		if ($iTimestamp && $iTimestamp > time()) {
			$sUntilDate = date('Y-m-d', $iTimestamp);
		}

		return $sUntilDate;
	}


	/**
	 * formatTimestamp() method returns a formatted date
	 *
	 * @param int $iTimestamp
	 * @param mixed $mLocale
	 * @param string $sLangIso
	 * @return string
	 */
	public static function formatTimestamp($iTimestamp, $sTemplate = null, $mLocale = false, $sLangIso = null)
	{
		// set
		$sDate = '';

		if ($mLocale !== false) {
			if (null === $sTemplate) {
				$sTemplate = '%d %h. %Y';
			}
			// set date with locale format
			$sDate = strftime($sTemplate, $iTimestamp);
		}
		else {
			// get Lang ISO
			$sLangIso = ($sLangIso !== null)? $sLangIso : GSnippetsReviews::$sCurrentLang;

			switch ($sTemplate) {
				case 'snippet' :
					$sDate = date('d', $iTimestamp)
						. ' '
						. (!empty($GLOBALS[_GSR_MODULE_NAME . '_MONTH'][$sLangIso])? $GLOBALS[_GSR_MODULE_NAME . '_MONTH'][$sLangIso]['long'][date('n', $iTimestamp)] : date('M', $iTimestamp))
						. ' '
						. date('Y', $iTimestamp);
					break;
				default:
					// set date with matching month or with default language
					$sDate = date('d', $iTimestamp)
						. ' '
						. (!empty($GLOBALS[_GSR_MODULE_NAME . '_MONTH'][$sLangIso])? $GLOBALS[_GSR_MODULE_NAME . '_MONTH'][$sLangIso]['short'][date('n', $iTimestamp)] : date('M', $iTimestamp))
						. ' '
						. date('Y', $iTimestamp);
					break;
			}
		}
		return $sDate;
	}


	/**
	 * getPageName() method returns formatted URI for page name type
	 *
	 * @return mixed
	 */
	public static function getPageName()
	{
		$sScriptName = '';

		// use case - script name filled
		if (!empty($_SERVER['SCRIPT_NAME'])) {
			$sScriptName = $_SERVER['SCRIPT_NAME'];
		}
		// use case - php_self filled
		elseif ($_SERVER['PHP_SELF']) {
			$sScriptName = $_SERVER['PHP_SELF'];
		}
		// use case - default script name
		else {
			$sScriptName = 'index.php';
		}
		return (
			substr(basename($sScriptName), 0, strpos(basename($sScriptName), '.'))
		);
	}


	/**
	 * getTemplatePath() method returns template path
	 *
	 * @param string $sTemplate
	 * @return string
	 */
	public static function getTemplatePath($sTemplate)
	{
		return (
			GSnippetsReviews::$oModule->getTemplatePath($sTemplate)
		);
	}


	/**
	 * getLoginLink() method returns link object
	 *
	 * @category tools collection
	 * @param string $sURI : relative URI
	 * @return obj
	 */
	public static function getLoginLink($sURI)
	{
		return (Context::getContext()->link->getPageLink('authentication', true) . (Configuration::get('PS_REWRITING_SETTINGS')? '?' : '&') . 'back=' . urlencode(self::detectHttpUri($sURI)));

	}//getLoginLink

	/**
	 * getProductImage() method returns product image
	 *
	 * @param obj $oProduct
	 * @param string $sImageType
	 * @return obj
	 */
	public static function getProductImage(Product &$oProduct, $sImageType)
	{
		$sImgUrl = '';

		if (Validate::isLoadedObject($oProduct)) {
			// use case - get Image
			$aImage = Image::getCover($oProduct->id);

			if (!empty($aImage)) {
				// get image url
				$sImgUrl = Context::getContext()->link->getImageLink($oProduct->link_rewrite, $oProduct->id . '-' . $aImage['id_image'], $sImageType);

				// use case - get valid IMG URI before  Prestashop 1.4
				$sImgUrl = self::detectHttpUri($sImgUrl);
			}
		}
		return $sImgUrl;
	}

	/**
	 * detectHttpUri() method detects and returns available URI - resolve Prestashop compatibility
	 *
	 * @param string $sURI
	 * @return mixed
	 */
	public static function detectHttpUri($sURI)
	{
		// use case - only with relative URI
		if (!strstr($sURI, 'http')) {
			$sURI = 'http' . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'? 's' : '')  . '://' . $_SERVER['HTTP_HOST'] . $sURI;
		}
		return $sURI;
	}

	/**
	 * truncateUri() method truncate current request_uri in order to delete params : sAction and sType
	 *
	 * @param mixed: string or array $mNeedle
	 * @return mixed
	 */
	public static function truncateUri($mNeedle = '&sAction', $sURI = '')
	{
		// set tmp
		$aQuery = is_array($mNeedle)? $mNeedle : array($mNeedle);

		// get URI
		$sURI = !empty($sURI)? $sURI : $_SERVER['REQUEST_URI'];

		foreach ($aQuery as $sNeedle) {
			$sURI = strstr($sURI, $sNeedle)? substr($sURI, 0 , strpos($sURI, $sNeedle)) : $sURI;
		}
		return $sURI;
	}

	/**
	 * jsonEncode() method detects available method and apply json encode
	 *
	 * @return string
	 */
	public static function jsonEncode($aData)
	{
		if (function_exists('json_encode')) {
			$aData = json_encode($aData);
		}
		elseif (method_exists('Tools', 'jsonEncode')) {
			$aData = Tools::jsonEncode($aData);
		}
		else {
			if (is_null($aData)) {
				return 'null';
			}
			if ($aData === false) {
				return 'false';
			}
			if ($aData === true) {
				return 'true';
			}
			if (is_scalar($aData)) {
				$aData = addslashes($aData);
				$aData = str_replace("\n", '\n', $aData);
				$aData = str_replace("\r", '\r', $aData);
				$aData = preg_replace('{(</)(script)}i', "$1'+'$2", $aData);
				return "'$aData'";
			}
			$isList = true;
			for ($i=0, reset($aData); $i<count($aData); $i++, next($aData)) {
				if (key($aData) !== $i) {
					$isList = false;
					break;
				}
			}
			$result = array();

			if ($isList) {
				foreach ($aData as $v) {
					$result[] = self::json_encode($v);
				}
				$aData = '[ ' . join(', ', $result) . ' ]';
			}
			else {
				foreach ($aData as $k => $v) {
					$result[] = self::json_encode($k) . ': ' . self::json_encode($v);
				}
				$aData = '{ ' . join(', ', $result) . ' }';
			}
		}

		return $aData;
	}

	/**
	 * jsonDecode() method detects available method and apply json decode
	 *
	 * @return mixed
	 */
	public static function jsonDecode($aData)
	{
		if (function_exists('json_decode')) {
			$aData = json_decode($aData);
		}
		elseif (method_exists('Tools', 'jsonDecode')) {
			$aData = Tools::jsonDecode($aData);
		}
		return $aData;
	}

	/**
	 * isInstalled() method check if specific module and module's vars are available
	 *
	 * @param int $sModuleName
	 * @param array $aCheckedVars
	 * @param bool $bObjReturn
	 * @param bool $bOnlyInstalled
	 * @return mixed : true or false or obj
	 */
	public static function isInstalled($sModuleName, array $aCheckedVars = array(), $bObjReturn = false, $bOnlyInstalled = false)
	{
		$mReturn = false;

		// use case - check module is installed in DB
		if (Module::isInstalled($sModuleName)) {
			if (!$bOnlyInstalled) {
				$oModule = Module::getInstanceByName($sModuleName);

				if (!empty($oModule)) {
					// check if module is activated
					$aActivated = Db::getInstance()->ExecuteS('SELECT id_module as id, active FROM ' . _DB_PREFIX_ . 'module WHERE name = "' . pSQL($sModuleName)  .'" AND active = 1');

					if (!empty($aActivated[0]['active'])) {
						$mReturn = true;

						$aActivated = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . 'module_shop WHERE id_module = ' . pSQL($aActivated[0]['id'])  .' AND id_shop = ' . Context::getContext()->shop->id);

						if (empty($aActivated)) {
							$mReturn = false;
						}

						if ($mReturn) {
							if (!empty($aCheckedVars)) {
								foreach ($aCheckedVars as $sVarName) {
									$mVar = Configuration::get($sVarName);

									if (empty($mVar)) {
										$mReturn = false;
									}
								}
							}
						}
					}
				}
				if ($mReturn && $bObjReturn) {
					$mReturn = $oModule;
				}
				unset($oModule);
			}
			else {
				$mReturn = true;
			}
		}
		return $mReturn;
	}

	/**
	 * isProductObj() method check if the product is a valid obj
	 *
	 * @param int $iProdId
	 * @param int $iLangId
	 * @param bool $bObjReturn
	 * @param bool $bAllProperties
	 * @return mixed : true or false
	 */
	public static function isProductObj($iProdId, $iLangId, $bObjReturn = false, $bAllProperties = false)
	{
		// set
		$bReturn = false;

		$oProduct = new Product($iProdId, $bAllProperties, $iLangId);

		if (Validate::isLoadedObject($oProduct)) {
			$bReturn = true;
		}

		return (
			!empty($bObjReturn) && $bReturn? $oProduct : $bReturn
		);
	}

	/**
	 * getProductPath() method write breadcrumbs of product for category
	 *
	 * @param int $iCatId
	 * @param int $iCatId
	 * @return string
	 */
	public static function getProductPath($iCatId, $iLangId)
	{
		$oCategory = new Category($iCatId);

		return (
			(Validate::isLoadedObject($oCategory)? str_replace('>', ' &gt; ', strip_tags(self::getPath((int)($oCategory->id), (int)($iLangId)))) : '')
		);
	}

	/**
	 * getPath() method write breadcrumbs of product for category
	 *
	 * Forced to redo the function from Tools here as it works with cookie
	 * for language, not a passed parameter in the function
	 *
	 * @param int $iCatId
	 * @param int $iLangId
	 * @param string $sPath
	 * @param bool $bEncoding
	 * @return string
	 */
	public static function getPath($iCatId, $iLangId, $sPath = '', $bEncoding = true)
	{
		$mReturn = '';

		if ($iCatId == 1) {
			$mReturn = $sPath;
		}
		else {
			// get pipe
			$sPipe = Configuration::get('PS_NAVIGATION_PIPE');

			if (empty($sPipe)) {
				$sPipe = '>';
			}

			$sFullPath = '';

			/* Old way: v1.2 - v1.3 */
			if (version_compare(_PS_VERSION_, '1.4.1') == -1) {
				// instantiate
				$oCategory = new Category((int)($iCatId), (int)($iLangId));

				if (Validate::isLoadedObject($oCategory)) {
					$sCatName = Category::hideCategoryPosition($oCategory->name);

					// htmlentities because this method generates some view
					if ($sPath != $sCatName) {
						$sDisplayedPath = ($bEncoding? htmlentities($sCatName, ENT_NOQUOTES, 'UTF-8') : $sCatName). $sPipe . $sPath;
					}
					else {
						$sDisplayedPath = ($bEncoding? htmlentities($sPath, ENT_NOQUOTES, 'UTF-8') : $sPath);
					}

					$mReturn = self::getPath((int)($oCategory->id_parent), $iLangId, trim($sDisplayedPath, $sPipe));
				}
			}
			/* New way for versions between v1.4 to v1.5.6.0 */
			elseif (version_compare(_PS_VERSION_, '1.5.6.0', '<')) {
				$aCurrentCategory = Db::getInstance()->getRow('
					SELECT id_category, level_depth, nleft, nright
					FROM '._DB_PREFIX_.'category
					WHERE id_category = '.(int)$iCatId
				);

				if (isset($aCurrentCategory['id_category'])) {
					$sQuery = '
						SELECT c.id_category, cl.name, cl.link_rewrite
						FROM '._DB_PREFIX_.'category c';

					// use case 1.5
					if (version_compare(_PS_VERSION_, '1.5', '>')) {
						Shop::addSqlAssociation('category', 'c', false);
					}

					$sQuery .= ' LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = c.id_category AND cl.`id_lang` = ' . (int)($iLangId) . (version_compare(_PS_VERSION_, '1.5', '>') ? Shop::addSqlRestrictionOnLang('cl') : '') . ')';

					$sQuery .= '
						WHERE c.nleft <= '.(int)$aCurrentCategory['nleft'].' AND c.nright >= '.(int)$aCurrentCategory['nright'].' AND cl.id_lang = '.(int)($iLangId).' AND c.id_category != 1
						ORDER BY c.level_depth ASC
						LIMIT '.(int)$aCurrentCategory['level_depth'];

					$aCategories = Db::getInstance()->ExecuteS($sQuery);

					$iCount = 1;
					$nCategories = count($aCategories);

					foreach ($aCategories as $aCategory) {
						$sFullPath .=
							($bEncoding? htmlentities($aCategory['name'], ENT_NOQUOTES, 'UTF-8') : $aCategory['name']).
							(($iCount++ != $nCategories OR !empty($sPath)) ? '<span class="navigation-pipe">' . $sPipe . '</span>' : '');
					}
					$mReturn = $sFullPath . $sPath;
				}
			}
			else {
				$aInterval = Category::getInterval($iCatId);
				$aIntervalRoot = Category::getInterval(Context::getContext()->shop->getCategory());

				if (!empty($aInterval) && !empty($aIntervalRoot)) {
					$sQuery = 'SELECT c.id_category, cl.name, cl.link_rewrite'
						. ' FROM '._DB_PREFIX_.'category c'
						. (version_compare(_PS_VERSION_, '1.5', '>') ? Shop::addSqlAssociation('category', 'c', false) : '')
						. ' LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = c.id_category'.Shop::addSqlRestrictionOnLang('cl').')'
						. 'WHERE c.nleft <= '.$aInterval['nleft']
						. ' AND c.nright >= '.$aInterval['nright']
						. ' AND c.nleft >= '.$aIntervalRoot['nleft']
						. ' AND c.nright <= '.$aIntervalRoot['nright']
						. ' AND cl.id_lang = '.(int)$iLangId
						. ' AND c.level_depth > '.(int)$aIntervalRoot['level_depth']
						. ' ORDER BY c.level_depth ASC';

					$aCategories = Db::getInstance()->executeS($sQuery);

					$iCount = 1;
					$nCategories = count($aCategories);

					foreach ($aCategories as $aCategory) {
						$sFullPath .=
							($bEncoding? htmlentities($aCategory['name'], ENT_NOQUOTES, 'UTF-8') : $aCategory['name']).
							(($iCount++ != $nCategories || !empty($sPath)) ? '<span class="navigation-pipe">' . $sPipe . '</span>' : '');
					}
					$mReturn = $sFullPath . $sPath;
				}
			}
		}

		return $mReturn;
	}

	/**
	 * getSortDesc() method returns configured desc
	 *
	 * @category tools collection
	 *
	 * @param -
	 * @return array
	 */
	public static function getSortDesc()
	{
		// set variables
		$aTmpDesc = array();

		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SORT_DESC'])) {
			$aDescPosition = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SORT_DESC']);
		}
		else {
			$aDescPosition = array('meta', 'short', 'long');
		}
		foreach ($aDescPosition as $sDesc) {
			$aTmpDesc[$sDesc] = $GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC'][$sDesc];
		}
		// destruct
		unset($aDescPosition);

		return ($aTmpDesc);
	}

	/**
	 * recursiveCategoryTree() method process categories to generate tree of them
	 *
	 * @param array $aCategories
	 * @param array $aIndexedCat
	 * @param array $aCurrentCat
	 * @param int $iCurrentIndex
	 * @param int $iDefaultId
	 * @return array
	 */
	public static function recursiveCategoryTree(array $aCategories, array $aIndexedCat, $aCurrentCat, $iCurrentIndex = 1, $iDefaultId = null)
	{
		// set variables
		static $_aTmpCat;
		static $_aFormatCat;

		if ($iCurrentIndex == 1) {
			$_aTmpCat = null;
			$_aFormatCat = null;
		}

		if (!isset($_aTmpCat[$aCurrentCat['infos']['id_parent']])) {
			$_aTmpCat[$aCurrentCat['infos']['id_parent']] = 0;
		}
		$_aTmpCat[$aCurrentCat['infos']['id_parent']] += 1;

		// calculate new level
		$aCurrentCat['infos']['iNewLevel'] = $aCurrentCat['infos']['level_depth'];

		// calculate if checked
		if (in_array($iCurrentIndex, $aIndexedCat)) {
			$aCurrentCat['infos']['bCurrent'] = true;
		}
		else {
			$aCurrentCat['infos']['bCurrent'] = false;
		}

		// define classname with default cat id
		$aCurrentCat['infos']['mDefaultCat'] = ($iDefaultId === null)? 'default' : $iCurrentIndex;

		$_aFormatCat[] = $aCurrentCat['infos'];

		if (isset($aCategories[$iCurrentIndex])) {
			foreach ($aCategories[$iCurrentIndex] as $iCatId => $aCat) {
				if ($iCatId != 'infos') {
					self::recursiveCategoryTree($aCategories, $aIndexedCat, $aCategories[$iCurrentIndex][$iCatId], $iCatId);
				}
			}
		}
		return $_aFormatCat;
	}

	/**
	 * displayPsHeader() method displays PS header
	 *
	 * @return string
	 */
	public static function displayPsHeader()
	{
		// use case - 1.5 version
//        if (version_compare(_PS_VERSION_, '1.5', '>')) {
//            if (isset(Context::getContext()->controller)) {
//                $oController = Context::getContext()->controller;
//            }
//            else {
//                $oController = new FrontController();
//                $oController->init();
//            }
//            // header
//            @$oController->displayHeader();
//        }
//        else {
			// header
			include(dirname(__FILE__) . '/../../../header.php');
//        }
	}

	/**
	 * displayPsFooter() method displays PS footer
	 *
	 * @return string
	 */
	public static function displayPsFooter()
	{
		// use case - 1.5 version
//        if (version_compare(_PS_VERSION_, '1.5', '>')) {
//            if (isset(Context::getContext()->controller)) {
//                $oController = Context::getContext()->controller;
//            }
//            else {
//                $oController = new FrontController();
//                $oController->init();
//            }
//            // header
//            @$oController->displayFooter();
//        }
//        else {
			// footer
			include(dirname(__FILE__) . '/../../../footer.php');
//        }
	}

	/**
	 * getUsableHooks() method return required hooks list
	 *
	 * @param array $aWhiteList
	 * @return array
	 */
	public static function getUsableHooks(array $aWhiteList)
	{
		$aHooks = array();

		foreach ($GLOBALS[_GSR_MODULE_NAME . '_HOOKS'] as $aHook) {
			if (in_array($aHook['name'], $aWhiteList)) {
				$aHooks[] = $aHook;
			}
		}

		return $aHooks;
	}


	/**
	 * manageProductDesc() method detect priority order to fill description : long description / short description / meta description
	 *
	 * @param array $aData
	 */
	public static function manageProductDesc($aData)
	{
		// set
		$sDesc = '';

		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SORT_DESC'])) {
			$aDescPosition = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SORT_DESC']);
		}
		else {
			$aDescPosition = array('short', 'long', 'meta');
		}

		foreach ($aDescPosition as $sOrder) {
			switch ($sOrder) {
				case 'meta' :
					if (empty($sDesc) && !empty($aData['meta_description'])) {
						$sDesc = $aData['meta_description'];
					}
					break;
				case 'short' :
					if (empty($sDesc) && !empty($aData['description_short'])) {
						$sDesc = $aData['description_short'];
					}
					break;
				case 'long' :
					if (empty($sDesc) && !empty($aData['description'])) {
						$sDesc = $aData['description'];
					}
					break;
				default:
					break;
			}
		}
		$sDesc = strip_tags($sDesc);

		return $sDesc;
	}


	/**
	 * checkCustomer() method check if customer is logged or he has already bought current product
	 *
	 * @param int $iCustomerId
	 * @param int $iProductId
	 * @param bool $bCheckBuyer
	 * @return bool
	 */
	public static function checkCustomer($iCustomerId, $iProductId = null, $bCheckBuyer = false)
	{
		// set
		$bCheck = false;

		// use case - first check if customer logged
		if (isset($iCustomerId) && is_numeric($iCustomerId)) {
			if (Customer::customerIdExistsStatic($iCustomerId)) {

				$bCheck = true;
				$bBuyer = (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_USER'] == 'buyer' || $bCheckBuyer)? true : false;

				if ($bBuyer && null !== $iProductId) {
					// include
					require_once(_GSR_PATH_LIB . 'module-dao_class.php');

					$bCheck = BT_GsrModuleDao::checkProductBought($iCustomerId, $iProductId);
				}
				unset($bBuyer);
			}
		}
		return $bCheck;
	}

	/**
	 * getProductData() method returns product data
	 *
	 * @param int $iProductId
	 * @param int $iLangId
	 * @param string $sImageSize
	 * @return array
	 */
	public static function getProductData($iProductId, $iLangId, $sImageSize)
	{
		// set
		$aProduct = array();

		// set current product obj from id
		$oProduct = self::isProductObj($iProductId, $iLangId, true, false);

		if (!empty($oProduct)) {
			$aDescProd = array(
				'meta_description' => $oProduct->meta_description,
				'description_short' => $oProduct->description_short,
				'description' => $oProduct->description,
			);

			// get product data
			$aProduct['link']         = Context::getContext()->link->getProductLink($oProduct);
			$aProduct['productId']    = $oProduct->id;
			$aProduct['product_name'] = $oProduct->name;
			$aProduct['desc']         = self::manageProductDesc($aDescProd);
			$aProduct['img']          = self::getProductImage($oProduct, $sImageSize);

			unset($aDescProd);
		}
		unset($oProduct);

		return $aProduct;
	}

	/**
	 * round() method round on numeric
	 *
	 * @param float $fVal
	 * @param int $iPrecision
	 * @return float
	 */
	public static function round($fVal, $iPrecision = 2)
	{
		if (method_exists('Tools', 'ps_round')) {
			$fVal = Tools::ps_round($fVal, $iPrecision);
		}
		else {
			$fVal = round($fVal, $iPrecision);
		}

		return $fVal;
	}


	/**
	 * setReviewTabId() method defines the correct review tab id
	 *
	 * @param string $sTheme
	 * @param string $sTabId
	 * @return string
	 */
	public static function setReviewTabId($sTheme, $sTabId)
	{
		$sId = '';

		switch ($sTheme) {
			case 'classic':
				break;
			case 'tabs':
				$sId = 'a#more_info_tab_reviews[href="#idTab'. $sTabId .'"]';
				break;
			case 'bootstrap':
				$sId = 'a#more_info_tab_reviews[href="#idTab'. $sTabId .'"]';
				break;
			case 'tabs17':
				$sId = 'a[href="#idTab'. $sTabId .'"]';
				break;
			default:
				break;
		}
		return $sId;
	}
}