<?php
/**
 * hook-display_class.php file defines controller which manage hooks sequentially
 */

class BT_GsrHookDisplay extends BT_GsrHookBase
{
	/**
	 * @var int $iProductId : get Product id
	 */
	protected $iProductId = null;

	/**
	 * @var string $sSecureKey : set secure key
	 */
	protected $sSecureKey = null;

	/**
	 * @var string $sHookType : define hook type
	 */
	protected $sHookType = null;

	/**
	 * @var string $sPageType : define page type
	 */
	protected $sPageType = null;

	/**
	 * @var bool $bUseWizardBadge : define if badge already displayed in any hook
	 */
	protected static $bUseWizardBadge = false;

	/**
	 * @var array $aLastBlockOptions : get badges options
	 */
	protected static $aBadges = array();

	/**
	 * @var array $aBadges : get last reviews block options
	 */
	protected static $aLastBlockOptions = array();

	/**
	 * Magic Method __construct assigns few information about hook
	 *
	 * @param string
	 */
	public function __construct($sHookType)
	{
		// set hook type
		$this->sHookType = $sHookType;

		// get page type
		$this->sPageType = BT_GsrModuleTools::getFrontPageType();
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}

	/**
	 * run() method execute hook
	 *
	 * @param array $aParams
	 * @return array
	 */
	public function run(array $aParams = null)
	{
		// set variables
		$aDisplayHook = array();

		// get product id
		$this->iProductId = Tools::getValue('id_product');

		// set secure key
		$this->sSecureKey = $this->setSecureKey($this->iProductId);

		switch ($this->sHookType) {
			case 'header' :
				// use case - display in header
				$aDisplayHook = call_user_func(array($this, 'displayHeader'));
				break;
			case 'extraRight' :
			case 'displayRightColumnProduct' : // use case - display in extraRight
			case 'productfooter' :
			case 'displayFooterProduct' : // use case - display in product footer
			case 'productActions' :
			case 'displayProductButtons' : // use case - display in product actions
			case 'extraLeft' :
			case 'displayLeftColumnProduct' : // use case - display in extra left
			case 'displayReassurance' : // use case - display in product reassurance
				$aDisplayHook = call_user_func(array($this, 'displayReviewBlock'));
				break;
			case 'displayRightColumn' :
			case 'rightColumn' : // use case - display snippets badge and last review block in the right column
				$aDisplayHook = call_user_func(array($this, 'displayGenericHook'), $this->getGenericFuncToExecute('colRight'));
				break;
			case 'displayLeftColumn' :
			case 'leftColumn' : // use case - display snippets badge and last review block in the left column
				$aDisplayHook = call_user_func(array($this, 'displayGenericHook'), $this->getGenericFuncToExecute('colLeft'));
				break;
			case 'top' : // use case - // use case - display snippets badge and last review block in the top position
				$aDisplayHook = call_user_func(array($this, 'displayGenericHook'), $this->getGenericFuncToExecute('top'));
				break;
			case 'footer' :
				// use case - // use case - display snippets badge and last review block in the bottom position
				$aDisplayHook = call_user_func(array($this, 'displayGenericHook'), $this->getGenericFuncToExecute('bottom'));
				break;
			case 'home' :
				// use case - // use case - display snippets badge and last review block in the home page
				$aDisplayHook = call_user_func(array($this, 'displayGenericHook'), $this->getGenericFuncToExecute('home'));
				break;
			case 'productTab' :
				// use case - display module product tab
				$aDisplayHook = call_user_func(array($this, 'displayProductTab'));
				break;
			case 'productTabContent' :
				// use case - display module product tab content
				$aDisplayHook = call_user_func_array(array($this, 'displayProductTabReviews'), array($aParams));
				break;
			case 'customerAccount' :
				// use case - display option for callback and all reviews by customer
				$aDisplayHook = call_user_func_array(array($this, 'displayAccount'), array($aParams));
				break;
			case 'popinFb' :
				// use case - display popin for shared Fb post
				$aDisplayHook = call_user_func_array(array($this, 'displayPopinFb'), array($aParams));
				break;
			case 'productRating' :
				// use case - displays product rating in product list page
				$aDisplayHook = call_user_func_array(array($this, 'displayProductRating'), array($aParams));
				break;
			case 'reviewForm' :
				// use case - displays review form
				$aDisplayHook = call_user_func_array(array($this, 'displayReviewForm'), array($aParams));
				break;
			case 'reportForm' :
				// use case - displays review report form
				$aDisplayHook = call_user_func_array(array($this, 'displayReportForm'), array($aParams));
				break;
			case 'modifyReview' :
				// use case - displays review form in reply mode
				$aDisplayHook = call_user_func_array(array($this, 'displayModifyReviewForm'), array($aParams));
				break;
			case 'review' :
				// use case - displays review standalone
				$aDisplayHook = call_user_func_array(array($this, 'displayReview'), array($aParams));
				break;
			case 'reviews' :
				// use case - displays review list page
				$aDisplayHook = call_user_func_array(array($this, 'displayReviewListPage'), array($aParams));
				break;
			default :
				break;
		}

		// assign the report button display
		if (!empty($aDisplayHook['assign'])) {
			$aDisplayHook['assign']['bDisplayReportAbuse'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REPORT_BUTTON'];
		}
		// use case - PS 17
		if (!empty(GSnippetsReviews::$bCompare17)) {
			$aDisplayHook['assign']['bPS17'] = true;
		}

		return $aDisplayHook;
	}

	/**
	 * displayHeader() method add to header JS and CSS needed for rating and reviews - preprocessing post review for available layout in product tab content hook
	 *
	 * @return array
	 */
	private function displayHeader()
	{
		// set
		$aAssign = array();

		// if reviews system is displayed
		$aAssign['bDisplayReview'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];
		$aAssign['bDisplayBadge'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_BADGE'];
		$aAssign['bAddJsCss'] = false;

		// set js msg translation
		BT_GsrModuleTools::translateJsMsg();

		$aAssign['oJsTranslatedMsg']  = BT_GsrModuleTools::jsonEncode($GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']);
		$aAssign['sModuleURI'] = _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php';

		// use case : display review
		if ($aAssign['bDisplayReview']) {
			// add in minify process by prestahsop
			if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_USE_FONTAWESOME'])) {
				Context::getContext()->controller->addCSS(_GSR_URL_CSS . 'font-awesome.css');
			}
			// add css and js files for 1.7
			if (!empty(GSnippetsReviews::$bCompare17)) {
				Context::getContext()->controller->addCSS(_GSR_URL_CSS . 'hook-17.css');
				Context::getContext()->controller->addCSS(_GSR_URL_CSS . _GSR_JQUERY_RATING_NAME . '-17.css');
			}
			else {
				Context::getContext()->controller->addCSS(_GSR_URL_CSS . 'hook.css');
				Context::getContext()->controller->addCSS(_GSR_URL_CSS . _GSR_JQUERY_RATING_NAME . '.css');
			}
			Context::getContext()->controller->addJS(_GSR_URL_JS . 'module.js');
			Context::getContext()->controller->addJS(_GSR_URL_JS . _GSR_JQUERY_RATING_NAME . '.min.js');

			// use case - product page
			if ($this->sPageType == 'product') {
				Context::getContext()->controller->addJS(_GSR_URL_JS . 'jquery.scrollTo.min.js');
			}

			Context::getContext()->controller->addJS(_GSR_URL_JS . 'init.js');

			if (GSnippetsReviews::$bCompare17) {
				Context::getContext()->controller->addJqueryPlugin('fancybox');
			}
			else {
				// get FancyBox plugin
				$aJsCss = Media::getJqueryPluginPath('fancybox');

				// add fancybox plugin
				if (!empty($aJsCss['js']) && !empty($aJsCss['css'])) {
					Context::getContext()->controller->addCSS($aJsCss['css']);
					Context::getContext()->controller->addJS($aJsCss['js']);
				}
			}
		}

		// use case : display badge
		if ($aAssign['bDisplayBadge']) {
			Context::getContext()->controller->addCSS(_GSR_URL_CSS . 'snippets.css');
		}

		// use case - standalone review display - fill OG Tags
		if (Tools::getIsset('iRId')
			&& Tools::getIsset('iPId')
		) {
			$aOpenGraph = $this->getStandaloneReview();

			if (empty($aOpenGraph['aErrors'])) {
				$aAssign = array_merge($aAssign, $aOpenGraph);
				$aAssign['bOpenGraph'] = true;
			}
			unset($aOpenGraph);
		}

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_HEADER, 'assign' => $aAssign)
		);
	}

	/**
	 * displayGenericHook() method display many functions in the same hook
	 *
	 * @param array $aFunctionToExecute
	 * @return array
	 */
	private function displayGenericHook(array $aFunctionToExecute)
	{
		$aContent = array();

		foreach ($aFunctionToExecute as $aFunction) {
			if (isset($aFunction['name'])
				&& method_exists($this, $aFunction['name'])
				&& isset($aFunction['params'])
			) {
				$aResult = call_user_func_array(array($this, $aFunction['name']), array($aFunction['params']));

				if (!empty($aResult)) {
					$mResult = GSnippetsReviews::$oModule->displayModule($aResult['tpl'], $aResult['assign']);

					if (!empty($mResult)) {
						$aContent[] = $mResult;
					}
				}
			}
		}

		$aAssign = array(
			'aContent' => $aContent
		);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_GENERIC, 'assign' => $aAssign)
		);
	}


	/**
	 * displaySnippets() method set snippets tags for : product or category or brand or HP
	 *
	 * @param string $sPosition : position in page
	 * @return array
	 */
	private function displaySnippets($sPosition)
	{
		// set
		$aReturn = array();
		$bDisplay = false;

		// get badge pages options
		if (empty(self::$aBadges)) {
			self::$aBadges = (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']) && is_string(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']))? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']) : array();
		}

		// test position : left or right col / top / bottom / custom
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_BADGE']
			&& !empty(self::$aBadges[$this->sPageType]['position'])
			&&  array_key_exists($sPosition, $GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES'])
		) {
			if ($sPosition == self::$aBadges[$this->sPageType]['position']) {
				$bDisplay = true;
			}
			elseif (self::$aBadges[$this->sPageType]['position'] == 'wizard'
				&& !empty(self::$aBadges[$this->sPageType]['custom'])
				&& empty(self::$aBadges[$this->sPageType]['once'])
			) {
				$bDisplay = true;
				self::$aBadges[$this->sPageType]['once'] = true;
			}
		}

		// test snippets page type
		switch ($this->sPageType) {
			case 'home' :
				$aReturn = $this->displayHPSnippets($bDisplay);
				break;
			case 'category' :
				$aReturn = $this->displayCategorySnippets($bDisplay);
				break;
			case 'manufacturer' :
				$aReturn = $this->displayManufacturerSnippets($bDisplay);
				break;
			case 'product' :
				$aReturn = $this->displayProductSnippets($bDisplay);
				break;
			default:
				break;
		}
		if (empty($aReturn)) {
			$aReturn = array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_EMPTY, 'assign' => array());
		}

		return $aReturn;
	}

	/**
	 * displayProductSnippets() method set product's snippets tags
	 *
	 * @param bool $bDisplay
	 * @return array
	 */
	private function displayProductSnippets($bDisplay = false)
	{
		// set
		$aAssign = array();

		$aAssign['bDisplayReviews'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];

		// use case - display if hook is matching
		if ($bDisplay && GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_RS']) {
			// display badge
			$aAssign['sBadgeStyle'] = !empty(self::$aBadges['product']['position'])? self::$aBadges['product']['position'] : '';

			if ($aAssign['sBadgeStyle'] == 'wizard' && !empty(self::$aBadges['product']['custom'])) {
				$aAssign['sBadgeFreeStyle'] = self::$aBadges['product']['custom'];
			}
			if ($aAssign['sBadgeStyle'] == 'colLeft' || $aAssign['sBadgeStyle'] == 'colRight') {
				$aAssign['bColStyle'] = true;
			}

			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			// assign
			$aAssign = array_merge($aAssign, array(
				'aProduct'          => $this->getDataProduct(),
				'bUseBrand' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_BRAND'],
				'bUseIdentifier'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_IDENTIFIER'],
				'bUseSupplier' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_SUPPLIER'],
				'bUseDesc'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_DESC'],
				'bUseCat'           => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_CAT'],
				'bUseBreadcrumb'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_BREADCRUMB'],
				'bUseHighPrice'     => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_HIGH_PRICE'],
				'bUseOfferCount'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_OFFER_COUNT'],
				'bUseCondition'     => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_COND'],
				'bUseUntilDate'     => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_UNTIL_DATE'],
				'bUseSeller'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_SELLER'],
				'bUseAvailability'  => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_AVAILABILITY'],
				'bOfferAggregate'   => (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PRODUCT_OFFERS'] != 'offer'? true : false),
				'bEnableCustLang'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG'],
				'sUrl'              => BT_GsrModuleTools::detectHttpUri($_SERVER['REQUEST_URI']),
				'sProductLink'      => Context::getContext()->link->getProductLink(BT_GsrModuleTools::isProductObj($this->iProductId, GSnippetsReviews::$iCurrentLang, true)),
			));

			// use case - get reviewed item name
			$aAssign['sItemReviewed'] = $aAssign['aProduct']['name'];
		}
		// assign if display or not
		$aAssign['bDisplay'] = $bDisplay;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_PROD_SNIPPETS, 'assign' => $aAssign)
		);
	}

	/**
	 * getDataProduct() method get product data
	 *
	 * @category hook collection
	 * @uses
	 *
	 * @param -
	 * @return array
	 */
	private function getDataProduct()
	{
		// set
		$aProduct = array();

		$oProduct = BT_GsrModuleTools::isProductObj($this->iProductId, GSnippetsReviews::$iCurrentLang, true);

		if (!empty($oProduct) && is_object($oProduct)) {
			// get product properties
			$aProduct = BT_GsrModuleDao::getProduct($oProduct->id);

			/*
			 * use case - get description for google / currency / breadcrumb / price / stock management activate or not /
			 * attribute combinations for quantity and images
			 */
			$aProduct['googleDesc'] = BT_GsrModuleTools::manageProductDesc($aProduct);
			$aProduct['currency'] = BT_GsrModuleTools::getCurrency('iso_code', GSnippetsReviews::$oCookie->id_currency);
			$aProduct['sign'] = BT_GsrModuleTools::getCurrency('sign', GSnippetsReviews::$oCookie->id_currency);
			$aProduct['breadcrumb'] = Tools::getPath($oProduct->id_category_default, $oProduct->name, true);
			$aProduct['price'] = $this->calculateProductPrice($oProduct->id, null, false);
			$aProduct['stockManagement'] = Configuration::get('PS_STOCK_MANAGEMENT');
			$aProduct['combinations'] = $oProduct->getAttributeCombinations(GSnippetsReviews::$iCurrentLang);

			// use case - if quantity is 0 => calculate total quantity
			if (empty($aProduct['quantity']) && !empty($aProduct['combinations'])) {
				$aProduct['quantity'] = 0;

				foreach ($aProduct['combinations'] as &$aCombination) {
					$aCombination['quantity'] = $oProduct->getQuantity($oProduct->id, $aCombination['id_product_attribute']);
					$aProduct['quantity'] += $aCombination['quantity'];
				}
			}

			// use case - offer aggregate - get combinations and images
			if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PRODUCT_OFFERS'] == 'aggregate') {
				$aImages = $oProduct->getCombinationImages(GSnippetsReviews::$iCurrentLang);

				// associate images to combinations
				if (!empty($aProduct['combinations'])) {
					foreach ($aProduct['combinations'] as &$aCombination) {
						if (isset($aImages[$aCombination['id_product_attribute']][0])) {
							$aCombination = array_merge($aCombination, $aImages[$aCombination['id_product_attribute']][0]);
						}
						// use case - get price
						$aProduct['boundariesPrices'][] = $this->calculateProductPrice($oProduct->id, $aCombination['id_product_attribute'], false);
					}
					// use case - count combinations
					$aProduct['offerCount'] = count($aProduct['combinations']);

					// use case - sort from lowest to highest
					if (!empty($aProduct['boundariesPrices'])) {
						sort($aProduct['boundariesPrices'], SORT_NUMERIC);
						$aProduct['lowestPrice'] = array_shift($aProduct['boundariesPrices']);
						$aProduct['highestPrice'] = array_pop($aProduct['boundariesPrices']);

						// destruct
						unset($aProduct['boundariesPrices']);
					}
				}
				unset($aImages);
			}
			// use case - offer
			else {
				// use case - get price
				$aProduct['lowestPrice'] = $this->calculateProductPrice($oProduct->id, null, false);

				//use case - calculate until date
				if (isset($aProduct['specific_prices']['to']) && $aProduct['specific_prices']['to'] != '0000-00-00 00:00:00') {
					$aProduct['untilDate'] = BT_GsrModuleTools::getUntilDate($aProduct['specific_prices']['to']);
					$aProduct['untilDateHuman'] = BT_GsrModuleTools::formatTimestamp(BT_GsrModuleTools::getTimeStamp($aProduct['specific_prices']['to'], 'db'), null, setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang));
				}
				else {
					$aProduct['untilDate'] = false;
				}
			}

			// set prefix and suffix currency
			if (in_array(intval(BT_GsrModuleTools::getCurrency('format', GSnippetsReviews::$oCookie->id_currency)), array(1,3))) {
				$aProduct['currencyPrefix'] = $aProduct['sign'];
				$aProduct['currencySuffix'] = '';
			}
			else {
				$aProduct['currencyPrefix'] = '';
				$aProduct['currencySuffix'] = $aProduct['sign'];
			}

			// use case - check default category to fill with set category only for home
			if ($aProduct['id_category_default'] == 1
				&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_CATEGORY_TITLE'])
			) {
				$aCatLang = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_CATEGORY_TITLE']);
				if (isset($aCatLang[GSnippetsReviews::$iCurrentLang])) {
					$aProduct['category'] = $aCatLang[GSnippetsReviews::$iCurrentLang];
				}
			}
			else {
				$oCategory = new Category($aProduct['id_category_default'], GSnippetsReviews::$iCurrentLang);
				$aProduct['category'] = $oCategory->name;
				unset($oCategory);
			}
			// destruct
			unset($oProduct);
		}

		return $aProduct;
	}

	/**
	 * displayHPSnippets() method display rich snippets and reviews badge on HP
	 *
	 * @param bool $bDisplay
	 * @return array
	 */
	private function displayHPSnippets($bDisplay = false)
	{
		// set
		$aAssign = array();

		$aAssign['bDisplayReviews'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];

		// use case - only if reviews are displayed
		if ($bDisplay && $aAssign['bDisplayReviews']) {
			// get displayed review snippets
			$aAssign = array_merge($aAssign, BT_GsrModuleTools::getReviewSnippetsConf());

			// display badge
			$aAssign['sBadgeStyle'] = !empty(self::$aBadges['home']['position'])? self::$aBadges['home']['position'] : '';

			if ($aAssign['sBadgeStyle'] == 'wizard' && !empty(self::$aBadges['home']['custom'])) {
				$aAssign['sBadgeFreeStyle'] = self::$aBadges['home']['custom'];
			}

			// review snippets to include
			$aAssign['sReviewSnippetsIncl'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEW_SNIPPETS);

			// set label of current object
			$aAssign['sCurrentName'] = GSnippetsReviews::$oModule->l('Shop', 'hook-display_class');

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - reviews ratings
			$aAssign['iCountRatings'] = BT_ReviewCtrl::create()->run('countRatings', array('langId' => BT_GsrModuleTools::getCustomerLanguage()));

			if (!empty($aAssign['iCountRatings'])) {
				// get max rate
				$aAssign['iBestRating'] = _GSR_MAX_RATING;

				// use case - get reviewed item name
				$aAssign['sItemReviewed'] = Configuration::get('PS_SHOP_NAME');

				// class name for stars
				$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));

				// use case - reviews
				$aAssign['iCountReviews'] = BT_ReviewCtrl::create()->run('countReviews', array('langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// get average
				$aAverage = BT_ReviewCtrl::create()->run('average', array('langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// check for average
				if (!empty($aAverage['iAverage'])) {
					$aAssign = array_merge($aAssign, BT_GsrModuleTools::getAverageOptions($aAverage['iAverage']));
					$aAssign['fReviewAverage'] = $aAverage['fDetailAverage'];

					// if average is float
					if (!empty($aAverage['bHalf'])) {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING * 2;
						$aAssign['bHalfStar'] = true;
					}
					else {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING;
						$aAssign['bHalfStar'] = false;
					}
				}
				else {
					$aAssign['iAverage'] = 0;
					$aAssign['fReviewAverage'] = 0;
				}
				unset($aAverage);

				$aAssign['sReviewsControllerUrl'] = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true),
					null,
					GSnippetsReviews::$iCurrentLang
				);
			}
		}
		// assign if display or not
		$aAssign['bDisplay'] = $bDisplay;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_HP_SNIPPETS, 'assign' => $aAssign)
		);
	}

	/**
	 * displayCategorySnippets() method returns rich snippets on category page
	 *
	 * @param bool $bDisplay
	 * @return array
	 */
	private function displayCategorySnippets($bDisplay = false)
	{
		// set
		$aAssign = array();

		$aAssign['bDisplayReviews'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];

		// get category
		$oCategory = new Category(Tools::getValue('id_category'), GSnippetsReviews::$iCurrentLang);
		
		// use case - only if reviews are displayed and obj validated
		if ($bDisplay
			&& Validate::isLoadedObject($oCategory)
			&& $aAssign['bDisplayReviews']
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_BADGE']
		) {
			// get displayed review snippets
			$aAssign = array_merge($aAssign, BT_GsrModuleTools::getReviewSnippetsConf());

			// display badge
			$aAssign['sBadgeStyle'] = !empty(self::$aBadges['category']['position'])? self::$aBadges['category']['position'] : '';

			if ($aAssign['sBadgeStyle'] == 'wizard' && !empty(self::$aBadges['category']['custom'])) {
				$aAssign['sBadgeFreeStyle'] = self::$aBadges['category']['custom'];
			}
			elseif ($aAssign['sBadgeStyle'] == 'colLeft' || $aAssign['sBadgeStyle'] == 'colRight') {
				$aAssign['bColStyle'] = true;
			}

			// set label of current object
			$aAssign['sCurrentName'] = GSnippetsReviews::$oModule->l('Category', 'hook-display_class');

			// review snippets to include
			$aAssign['sReviewSnippetsIncl'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEW_SNIPPETS);

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - reviews ratings
			$aAssign['iCountRatings'] = BT_ReviewCtrl::create()->run('countRatings', array('catId' => $oCategory->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

			if (!empty($aAssign['iCountRatings'])) {
				// get max rate
				$aAssign['iBestRating'] = _GSR_MAX_RATING;

				// use case - get reviewed item name
				$aAssign['sItemReviewed'] = $oCategory->name;

				// class name for stars
				$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));

				// use case - reviews
				$aAssign['iCountReviews'] = BT_ReviewCtrl::create()->run('countReviews', array('catId' => $oCategory->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// get average
				$aAverage = BT_ReviewCtrl::create()->run('average', array('iCatId' => $oCategory->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// check for average
				if (!empty($aAverage['iAverage'])) {
					$aAssign = array_merge($aAssign, BT_GsrModuleTools::getAverageOptions($aAverage['iAverage']));
					$aAssign['fReviewAverage'] = $aAverage['fDetailAverage'];

					// if average is float
					if (!empty($aAverage['bHalf'])) {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING * 2;
						$aAssign['bHalfStar'] = true;
					}
					else {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING;
						$aAssign['bHalfStar'] = false;
					}
				}
				else {
					$aAssign['iAverage'] = 0;
					$aAssign['fReviewAverage'] = 0;
				}
				unset($aAverage);

				$aAssign['sReviewsControllerUrl'] = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true),
					null,
					GSnippetsReviews::$iCurrentLang
				);
			}
		}
		// assign if display or not
		$aAssign['bDisplay'] = $bDisplay;

		unset($oCategory);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_CAT_SNIPPETS, 'assign' => $aAssign)
		);
	}

	/**
	 * displayManufacturerSnippets() method returns rich snippets on manufacturer page
	 *
	 * @param bool $bDisplay
	 * @return array
	 */
	private function displayManufacturerSnippets($bDisplay = false)
	{
		// set
		$aAssign = array();

		$aAssign['bDisplayReviews'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];

		// get Manufacturer
		$oManufacturer = new Manufacturer(Tools::getValue('id_manufacturer'), GSnippetsReviews::$iCurrentLang);

		// use case - only if reviews are displayed and obj validated
		if ($bDisplay
			&& Validate::isLoadedObject($oManufacturer)
			&& $aAssign['bDisplayReviews']
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_BADGE']
		) {
			// get displayed review snippets
			$aAssign = array_merge($aAssign, BT_GsrModuleTools::getReviewSnippetsConf());

			// display badge
			$aAssign['sBadgeStyle'] = !empty(self::$aBadges['manufacturer']['position'])? self::$aBadges['manufacturer']['position'] : '';

			if ($aAssign['sBadgeStyle'] == 'wizard' && !empty(self::$aBadges['manufacturer']['custom'])) {
				$aAssign['sBadgeFreeStyle'] = self::$aBadges['manufacturer']['custom'];
			}
			elseif ($aAssign['sBadgeStyle'] == 'colLeft' || $aAssign['sBadgeStyle'] == 'colRight') {
				$aAssign['bColStyle'] = true;
			}

			// set label of current object
			$aAssign['sCurrentName'] = GSnippetsReviews::$oModule->l('Brand', 'hook-display_class');

			// review snippets to include
			$aAssign['sReviewSnippetsIncl'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEW_SNIPPETS);

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - reviews ratings
			$aAssign['iCountRatings'] = BT_ReviewCtrl::create()->run('countRatings', array('brandId' => $oManufacturer->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

			if (!empty($aAssign['iCountRatings'])) {
				// get max rate
				$aAssign['iBestRating'] = _GSR_MAX_RATING;

				// use case - get reviewed item name
				$aAssign['sItemReviewed'] = $oManufacturer->name;

				// class name for stars
				$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));

				// use case - reviews
				$aAssign['iCountReviews'] = BT_ReviewCtrl::create()->run('countReviews', array('brandId' => $oManufacturer->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// get average
				$aAverage = BT_ReviewCtrl::create()->run('average', array('iBrandId' => $oManufacturer->id, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// check for average
				if (!empty($aAverage['iAverage'])) {
					$aAssign = array_merge($aAssign, BT_GsrModuleTools::getAverageOptions($aAverage['iAverage']));
					$aAssign['fReviewAverage'] = $aAverage['fDetailAverage'];

					// if average is float
					if (!empty($aAverage['bHalf'])) {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING * 2;
						$aAssign['bHalfStar'] = true;
					}
					else {
						$aAssign['iMaxRating'] = _GSR_MAX_RATING;
						$aAssign['bHalfStar'] = false;
					}
				}
				else {
					$aAssign['iAverage'] = 0;
					$aAssign['fReviewAverage'] = 0;
				}
				unset($aAverage);

				$aAssign['sReviewsControllerUrl'] = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true),
					null,
					GSnippetsReviews::$iCurrentLang
				);
			}
		}
		// assign if display or not
		$aAssign['bDisplay'] = $bDisplay;

		unset($oCategory);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_CAT_SNIPPETS, 'assign' => $aAssign)
		);
	}


	/**
	 * displayReviewBlock() method display reviews block : product average, distribution and action buttons
	 *
	 * @return array
	 */
	private function displayReviewBlock()
	{
		// set
		$aAssign = array();

		// check and get Product object
		$oProduct = BT_GsrModuleTools::isProductObj($this->iProductId, GSnippetsReviews::$iCurrentLang, true);

		// use case - display if hook is matching and if reviews are displayed
		if ($this->sHookType == GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HOOK']
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
			&& !empty($oProduct)
		) {
			// get badge pages options
			if (empty(self::$aBadges)) {
				self::$aBadges = (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']) && is_string(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']))? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']) : array();
			}

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// get name of front open form option
			$sAddReviewOption = $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['name'];

			// determine which position is set for the reviews block
			$sBlockPosition = '';
			foreach ($GLOBALS[_GSR_MODULE_NAME . '_HOOKS'] as $aHook) {
				if ($aHook['name'] === GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HOOK']
					&& !empty($aHook['position'])
				) {
					$sBlockPosition = $aHook['position'];
				}
			}

			// detect the customer ID
			$iCustomerId = Tools::getIsset('iCId')? Tools::getValue('iCId') : GSnippetsReviews::$oCookie->id_customer;

			// assign
			$aAssign = array(
				'bDisplayReviews'   => true,
				'bDisplayBlock'     => true,
				'sURI'              => $_SERVER['REQUEST_URI'],
				'iProductId'        => $this->iProductId,
				'iCustomerId'       => $iCustomerId,
				'bUseRatings' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'],
				'bUseComments' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'],
				'bEnableCustLang'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG'],
				'sReviewType'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_TYPE'],
				'iIdTab'            => _GSR_PRODUCT_TAB_ID,
				'sIncludeReview'    => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEWS),
				'sProductLink'      => Context::getContext()->link->getProductLink($oProduct),
				'aQueryParams'      => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
				'sSecureReviewKey'  => $this->setSecureKey(GSnippetsReviews::$iShopId.$this->iProductId.(int)$iCustomerId.'review'),
				'iCountRatings'     => BT_ReviewCtrl::create()->run('countRatings', array('productId' => $this->iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage())),
				'sBlockPosition'    => $sBlockPosition,
				'sDisplayReviewMode'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'],
				'sReviewTabId'      => BT_GsrModuleTools::setReviewTabId(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'], _GSR_PRODUCT_TAB_ID),
				'bDisplayProductRichSnippets'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_RS'],
				'bProductBadge'     => (!empty(self::$aBadges[$this->sPageType]['position'])? true : false),
				'sItemReviewed'     => $oProduct->name,
				'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
				'bOpenForm'         => Tools::getValue($sAddReviewOption),
				'rtg'               => Tools::getValue('rtg'),
			);

			// check if already exist a parameter in URI
			$aAssign['sProductLink'] = $aAssign['sProductLink'] . (strstr($aAssign['sProductLink'], '?')? '&' : '?' )
				. $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['addReview']['name'] . '=' . $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['addReview']['value'];

			if (!empty($aAssign['iCountRatings'])) {
				// use case - reviews
				$aAssign['iCountReviews'] = BT_ReviewCtrl::create()->run('countReviews', array('productId' => $this->iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				if (!empty($aAssign['iCountReviews'])
					&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'])
				) {
					$aAssign['bUseSocialNetworkJs'] = true;
					// set default lang
					$aAssign['sFbLang'] = !isset($GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['FB'])? 'en_US' : $GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['FB'];

					// set default twitter lang
					$aAssign['sTwitterLang'] = !isset($GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['TWITTER'])? 'en' : $GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['TWITTER'];
				}

				// get average
				$aAverage = BT_ReviewCtrl::create()->run('average', array('iProductId' => $this->iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

				// use case - available average array
				if (!empty($aAverage['iAverage'])) {
					$aAssign['iDefaultMaxRating']   = _GSR_MAX_RATING;
					$aAssign['iReviewAverage']      = $aAverage['iAverage'];
					$aAssign['fReviewAverage']      = $aAverage['fDetailAverage'];
					$aAssign['aDistribution']       = BT_ReviewCtrl::create()->run('getDistribution', array('iProductId' => $this->iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));
				}
				// if average is float
				if (!empty($aAverage['bHalf'])) {
					$aAssign['iMaxRatingBlock'] = _GSR_MAX_RATING * 2;
					$aAssign['bHalfStar'] = true;
				}
				else {
					$aAssign['iMaxRatingBlock'] = _GSR_MAX_RATING;
					$aAssign['bHalfStar'] = false;
				}
				$aAssign['iAverageMaxRating'] = _GSR_MAX_RATING;

				unset($aAverage);
			}
			$aAssign['sMODULE_URI']  = _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php';
			$aAssign['aErrors'] = BT_ReviewCtrl::create()->aErrors;
		}
		else {
			$aAssign['bDisplayReviews'] = false;
			$aAssign['bDisplayBlock'] = false;
		}

		// use case - display if hook is matching and if reviews are displayed for PS 1.7 to display the reviews list in the product bottom
		if ($this->sHookType == 'displayFooterProduct'
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
			&& !empty($oProduct)
			&& !empty(GSnippetsReviews::$bCompare17)
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'] == 'classic'
		) {
			$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_PRODUCT_TAB_CTN): _GSR_TPL_PRODUCT_TAB_CTN;
			$aAssign['sProductListTemplateInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . $sTpl);
			$aReturn = $this->displayProductTabReviews(array('bForce' => true));

			if (!empty($aReturn['assign'])) {
				$aAssign = array_merge($aAssign, $aReturn['assign']);
			}
		}

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_REVIEWS): _GSR_TPL_REVIEWS;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}

	/**
	 * displayProductTab() method displays title of review product tab
	 *
	 * @return array
	 */
	private function displayProductTab()
	{
		$aAssign = array();

		// use case - only if reviews are displayed
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
			&& 'classic' != GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']
			&& BT_GsrModuleTools::isProductObj($this->iProductId, GSnippetsReviews::$iCurrentLang)
		) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// set
			$aAssign = array(
				'iIdTab'            => _GSR_PRODUCT_TAB_ID,
				'bDisplayReviews'   => true,
				'iCountRatings'     => BT_ReviewCtrl::create()->run('countRatings', array('productId' => $this->iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage())),
				'sTabMode'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'],
			);
		}
		else {
			$aAssign['bDisplayReviews'] = false;
		}
		$aAssign['bUseRatings'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'];

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_PRODUCT_TAB): _GSR_TPL_PRODUCT_TAB;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}

	/**
	 * displayProductTabReviews() method displays content of review product tab
	 *
	 * @params array $aParams
	 * @return array
	 */
	private function displayProductTabReviews(array $aParams = null)
	{
		// set
		$aAssign = array();

		if (isset($aParams['product']) && Validate::isLoadedObject($aParams['product'])) {
			$iProductId = $aParams['product']->id;
			$oProduct = $aParams['product'];
		}
		else {
			$iProductId = $this->iProductId;
			$oProduct = BT_GsrModuleTools::isProductObj($iProductId, GSnippetsReviews::$iCurrentLang, true);
		}

		// detect the customer ID
		$iCustomerId = Tools::getIsset('iCId')? Tools::getValue('iCId') : GSnippetsReviews::$oCookie->id_customer;

		$aAssign['bDisplayReviews'] = (GSnippetsReviews::$bCompare17 && GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'] == 'classic' && empty($aParams['bForce']))? false : true;

		// use case - only if reviews are displayed
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
			&& !empty($oProduct)
			&& $aAssign['bDisplayReviews']
		) {
			// set
			$aAssign = array(
				'sURI'              => $_SERVER['REQUEST_URI'],
				'bUseRatings' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'],
				'bUseComments' 	    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'],
				'sDisplayReviewMode'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'],
				'sReviewTabId'      => BT_GsrModuleTools::setReviewTabId(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'], _GSR_PRODUCT_TAB_ID),
				'bDisplayButtons'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'],
				'bCountBoxButton' 	=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COUNT_BOX_BUTTON'],
				'bReviewAggregate'  => ((GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_TYPE'] != 'individual')? true : false),
				'bAddReview'        => Tools::getValue($GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['addReview']['name']),
				'bGetReviewPage'    => (Tools::getIsset('iPage')? true : false),
				'iIdTab'            => _GSR_PRODUCT_TAB_ID,
				'bPs15'             => true,
				'iProductId'        => $iProductId,
				'iCustomerId'       => $iCustomerId,
				'iMaxRating'        => _GSR_MAX_RATING,
				'sShopName'         => Configuration::get('PS_SHOP_NAME'),
				'sMODULE_URI'       => _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php',
				'sImgUrl'           => _GSR_URL_IMG,
				'sProductLink'      => Context::getContext()->link->getProductLink($oProduct),
				'sProductName'      => $oProduct->name,
				'sSecureReviewKey'  => $this->setSecureKey(GSnippetsReviews::$iShopId.$iProductId.(int)$iCustomerId.'review'),
				'aQueryParams'      => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
				'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
				'iFbButton'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_BUTTON_TYPE'],
				'sErrorInclude'     => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME .  _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR),
			);

			// get base URI
			$aAssign['sBASE_URI'] = $aAssign['sProductLink'] . (strstr($aAssign['sProductLink'], '?')? '&' : '?' );

			// get page id
			$iCurrentPage = $aAssign['bGetReviewPage']? intval(Tools::getValue('iPage')) : 1;

			// include
			require_once(_GSR_PATH_LIB_COMMON . 'pagination.class.php');
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - reviews ratings
			$iCountRatings = BT_ReviewCtrl::create()->run('countRatings', array('productId' => $iProductId, 'langId' => BT_GsrModuleTools::getCustomerLanguage()));

			// set pagination
			$aPagination = BT_Pagination::create()->run(array('total' => $iCountRatings, 'perPage' => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_PROD_PAGE']));

			// check pagination
			if (!isset($aPagination[$iCurrentPage]['begin'])) {
				$iCurrentPage = 1;
			}

			// set params for getting ratings and reviews
			$aReviewsParams = array(
				'bOnlyReview'       => false,
				'bCommentCustomer'  => false,
				'bRatingCustomer'   => true,
				'orderBy'           => 'dateAdd DESC',
				'productId'         => $iProductId,
				'interval'          => $aPagination[$iCurrentPage]['begin'] . ',' . $aPagination[$iCurrentPage]['nb'],
				'langId'            => BT_GsrModuleTools::getCustomerLanguage(),
				'report'            => true,
			);

			$aAssign['aReviews']        = BT_ReviewCtrl::create()->run('getReviewsOnProduct', $aReviewsParams);
			$aAssign['aPagination']     = $aPagination;
			$aAssign['iCurrentPage']    = $iCurrentPage;
			$aAssign['iTotalPage']      = count($aPagination);
			$aAssign['bDisplayReviews'] = true;
			$aAssign['aErrors']         = BT_ReviewCtrl::create()->aErrors;
			$aAssign['sIMG_URI']        = _GSR_URL_IMG . 'hook/';
			$aAssign['aJSCallback']     = array();

			// set standalone review URL
			if (count($aAssign['aReviews'])) {
				// set module link
				foreach ($aAssign['aReviews'] as &$aReview) {
					if (!empty($aReview['review'])) {
						// format CR into BR
						$aReview['review']['data']['sComment'] = str_replace("\n", "<br />", $aReview['review']['data']['sComment']);

						// set secure key
						$aReview['review']['sReportUrl'] = BT_GsrModuleTools::formatReviewReportUrl($iProductId, $this->setSecureKey($iProductId . $aReview['review']['id']), $aAssign['sURI'], $aReview['review']['id']);

						// set review URL in standalone mode
						$aReview['review']['sReviewUrl']  = Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW,
							array(
								'iRId' => $aReview['id'],
								'iPId' => $iProductId,
							),
							null,
							$aReview['review']['langId']
						);
						$aAssign['aJSCallback'][] = array('url' => $aReview['review']['sReviewUrl'], 'function' => 'bt_generateFbVoucherCode');
					}
				}
			}
			unset($aReviewsParams);
			unset($aPagination);
		}
		else {
			$aAssign['bDisplayReviews'] = false;
		}

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_PRODUCT_TAB_CTN): _GSR_TPL_PRODUCT_TAB_CTN;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}

	/**
	 * displayReviewForm() method display review form
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayReviewForm(array $aParams = null)
	{
		// get product id
		$iProductId = Tools::getValue('iPId');
		$iCustomerId = Tools::getValue('iCId');

		// set
		$sForbiddenMsg = '';
		$bDisplayForm = false;
		$aAssign = array();

		// use case - if check secure key for identifying current product OK
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey(GSnippetsReviews::$iShopId.$iProductId.$iCustomerId.'review')
		) {
			// detect if customer have to be a buyer or only a registered customer
			$bDisplayForm = BT_GsrModuleTools::checkCustomer($iCustomerId, $iProductId);

			// use case - check if is already reviewed
			if ($bDisplayForm) {
				// assign secure key
				$aAssign['sSecureKey'] = Tools::getValue('btKey');

				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// use case - review active
				$aAssign['bCanReview'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'];

				// use case - if enable comments
				if ($aAssign['bCanReview']) {
					// use case - check if review already exists
					$bDisplayForm  = (BT_ReviewCtrl::create()->run('existReview', array('iCustomerId' => $iCustomerId, 'iProductId' => $iProductId))? false : true);
				}

				// use case - check if rating already exists
				$aCustomerNote = BT_ReviewCtrl::create()->run('existRating', array('iCustomerId' => $iCustomerId, 'iProductId' => $iProductId));

				// use case - rating already exists
				if (!empty($aCustomerNote)) {
					$aAssign['iCustomerNote'] = $aCustomerNote['note'];
				}
				else {
					$bDisplayForm = true;
				}

				// review not already posted
				if ($bDisplayForm) {
					// check and get product data
					$oProduct = BT_GsrModuleTools::isProductObj($iProductId, GSnippetsReviews::$iCurrentLang, true);

					if ($oProduct != false) {
						$aDescProd = array(
							'meta_description' => $oProduct->meta_description,
							'description_short' => $oProduct->description_short,
							'description' => $oProduct->description,
						);
						// set
						$aAssign = array_merge($aAssign,
							array(
								'aProduct'  => array(
									'id'    => $iProductId,
									'name'  => $oProduct->name,
									'desc'  => BT_GsrModuleTools::manageProductDesc($aDescProd),
									'img'   => BT_GsrModuleTools::getProductImage($oProduct, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_PROD_IMG']),
								),
								'iCustomerId'       => $iCustomerId,
								'iMaxRating'        => _GSR_MAX_RATING,
								'iPreSelectedRating'=> Tools::getValue('rtg'),
								'bDisplayForm'      => $bDisplayForm,
								'sLoadingImg'       => _GSR_URL_IMG . (!empty(GSnippetsReviews::$bCompare17)? _GSR_LOADER_GIF : _GSR_LOADER_GIF_BG),
								'bEnableComments'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'],
								'bForceComments'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FORCE_COMMENTS'],
								'aQueryParams' 	    => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
								'sMODULE_URI'       => _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php',
								'sIMG_URI'  		=> _GSR_URL_IMG . 'hook/',
								'aParamStars' 	    => array(
									'star' => _GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
								),
								'sErrorInclude'     => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME .  _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR),
							)
						);

						// use case - voucher for sharing FB post is activated
						if (BT_GsrModuleTools::getEnableVouchers('share')) {
							// require
							require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

							$aVoucher = BT_Voucher::create()->getSettings('share');

							if (!empty($aVoucher)) {
								// get voucher data
								$aSettings = BT_Voucher::create()->formatData($aVoucher, GSnippetsReviews::$sCurrentLang);

								// assign voucher data
								if (!empty($aSettings['use'])) {
									$aAssign['aVoucherShare'] = $aSettings;
								}
							}
							unset($aSettings);
							unset($aVoucher);
						}

						// use case - voucher for sharing FB post is activated
						if (BT_GsrModuleTools::getEnableVouchers('comment')) {
							// require
							require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

							$aVoucher = BT_Voucher::create()->getSettings('comment');

							if (!empty($aVoucher)) {
								// get voucher data
								$aSettings = BT_Voucher::create()->formatData($aVoucher, GSnippetsReviews::$sCurrentLang);

								// assign voucher data
								if (!empty($aSettings['use'])) {
									$aAssign['aVoucherComment'] = $aSettings;
								}
							}
							unset($aSettings);
							unset($aVoucher);
						}
					}
				}
				// use case - set in order to display msg as review has already been posted
				else {
					$sForbiddenMsg = 'review';
				}
			}
			else {
				if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_USER'] == 'buyer') {
					$sForbiddenMsg = 'buyer';
				}
				else {
					$sForbiddenMsg = 'customer';

					// format login URI callback
					$sURI = BT_GsrModuleTools::truncateUri(array('post','iPage'), Tools::getValue('sURI'));
					if (isset($sURI[strlen($sURI)-1]) && $sURI[strlen($sURI)-1] == '?') {
						$sURI = substr($sURI, 0, strlen($sURI)-1);
					}
					$aAssign['sLoginURI'] = BT_GsrModuleTools::getLoginLink($sURI . '?' . $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['name'] . '=true');
					unset($sURI);
				}
			}
		}
		else {
			$sForbiddenMsg = 'secure';
		}
		// assign forbidden access message
		$aAssign['sForbiddenMsg'] = $sForbiddenMsg;

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_PROD_REVIEW_FORM): _GSR_TPL_PROD_REVIEW_FORM;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}


	/**
	 * displayReportForm() method display review form
	 *
	 * @category hook collection
	 * @uses
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayReportForm(array $aParams = null)
	{
		// set
		$sForbiddenMsg = '';
		$bDisplayForm = false;
		$aAssign = array();

		// get product id
		$iProductId = Tools::getValue('iProductId');
		$iReviewId = Tools::getValue('iId');
		$sURI = Tools::getValue('sURI');

		// use case - if check secure key for identifying current product OK
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey($iProductId . $iReviewId, false)
		) {
			// detect if customer have to be a buyer or only a registered customer
			$aAssign['bCanReport'] = BT_GsrModuleTools::checkCustomer(GSnippetsReviews::$oCookie->id_customer);

			if ($aAssign['bCanReport']) {
				$aAssign['iCustomerId'] = GSnippetsReviews::$oCookie->id_customer;
				$aAssign['sSecureKey'] = $this->setSecureKey($aAssign['iCustomerId'] . $iReviewId, false);
				$aAssign['iDefaultLang'] = Configuration::get('PS_LANG_DEFAULT');

				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// get current review
				$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $iReviewId, 'report' => true));

				if (!empty($aReview)) {
					if (empty($aReview['reportId'])) {
						$bDisplayForm = true;

						$aAssign['aReview'] = $aReview;
						$aAssign['aQueryParams'] = $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'];
						$aAssign['sMODULE_URI'] = _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php';
						$aAssign['sLoadingImg'] = _GSR_URL_IMG . (!empty(GSnippetsReviews::$bCompare17)? _GSR_LOADER_GIF : _GSR_LOADER_GIF_BG);
					}
					else {
						$sForbiddenMsg = 'exist';
					}
				}
				else {
					$sForbiddenMsg = 'secure';
				}
			}
			else {
				$sForbiddenMsg = 'customer';
				// format login URI callback
				$aAssign['sLoginURI'] = BT_GsrModuleTools::getLoginLink(urldecode($sURI));
				unset($sURI);
			}
		}
		else {
			$sForbiddenMsg = 'secure';
		}

		$aAssign['sForbiddenMsg'] = $sForbiddenMsg;
		$aAssign['bDisplayForm'] = $bDisplayForm;

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_REVIEW_REPORT): _GSR_TPL_REVIEW_REPORT;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}

	/**
	 * displayModifyReviewForm() method display review form
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayModifyReviewForm(array $aParams = null)
	{
		// get params => customer Id, rating / review ID, product ID
		$iProductId = Tools::getValue('iPId');
		$iRatingId = Tools::getValue('iRId');
		$iCustomerId = Tools::getValue('iCId');
		$oProduct = BT_GsrModuleTools::isProductObj($iProductId, GSnippetsReviews::$iCurrentLang, true);

		// set
		$sForbiddenMsg = '';
		$bDisplayForm = false;
		$aAssign = array();

		// use case - if check secure key for identifying current rating / review, product, customer
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey(GSnippetsReviews::$iShopId.$iProductId.$iRatingId.$iCustomerId.'modify')
			&& $oProduct != false
		) {
			// check if the customer who received the after-sales reply is the same as the one connected
//			if ($iCustomerId == GSnippetsReviews::$oCookie->id_customer) {
				// use case - check if is already reviewed
				if (BT_GsrModuleTools::checkCustomer($iCustomerId, $iProductId)) {
					// include
					require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

					// get current rating
					$aRating = BT_ReviewCtrl::create()->run('getRatings', array('id' => $iRatingId, 'customer' => true, 'report' => true, 'address' => true, 'bAssociativeArray' => true));

					if (!empty($aRating)) {
						$bDisplayForm = true;

						// set locale for review date
						$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

						// format date
						$aRating['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['dateAdd'], null, $sLocale);

						// get review
						$aRating['review'] = BT_ReviewCtrl::create()->run('getReviews', array('ratingId' => $aRating['id'], 'date' => 'd-m-Y', 'locale' => $sLocale, 'active' => 2));

						unset($sLocale);

						$aDescProd = array(
							'meta_description' => $oProduct->meta_description,
							'description_short' => $oProduct->description_short,
							'description' => $oProduct->description,
						);
						// set
						$aAssign = array_merge($aAssign,
							array(
								'aProduct' => array(
									'id' => $iProductId,
									'name' => $oProduct->name,
									'desc' => BT_GsrModuleTools::manageProductDesc($aDescProd),
									'img' => BT_GsrModuleTools::getProductImage($oProduct, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_PROD_IMG']),
									'link' => Context::getContext()->link->getProductLink($oProduct),
								),
								'iMaxRating' => _GSR_MAX_RATING,
								'sLoadingImg' => _GSR_URL_IMG . (!empty(GSnippetsReviews::$bCompare17)? _GSR_LOADER_GIF : _GSR_LOADER_GIF_BG),
								'aQueryParams' => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
								'sMODULE_URI' => _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php',
								'sIMG_URI' => _GSR_URL_IMG . 'hook/',
								'aParamStars' => array(
									'star' => _GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
								),
								'aRating' => $aRating,
								'sSecureKey' => $this->setSecureKey(GSnippetsReviews::$iShopId . $iProductId . $iRatingId . $iCustomerId . 'update'),
							)
						);
					}
					else {
						$sForbiddenMsg = 'secure';
					}
					unset($aRating);
				}
				else {
					if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_USER'] == 'buyer') {
						$sForbiddenMsg = 'buyer';
					}
					else {
						$sForbiddenMsg = 'customer';
					}
				}
//			}
//			else {
//				$sForbiddenMsg = 'identification';
//			}
		}
		else {
			$sForbiddenMsg = 'secure';
		}
		unset($oProduct);

		// assign forbidden access message
		$aAssign['path'] = GSnippetsReviews::$oModule->l('Review modification', 'hook-display_class');
		$aAssign['meta_title'] = GSnippetsReviews::$oModule->l('Review modification on shop', 'hook-display_class') . ' ' . Configuration::get('PS_SHOP_NAME');
		$aAssign['meta_description'] = GSnippetsReviews::$oModule->l('Our customers are able to modify their review', 'hook-display_class');
		$aAssign['sForbiddenMsg'] = $sForbiddenMsg;
		$aAssign['bDisplayForm'] = $bDisplayForm;

		return (
			array('tpl' => _GSR_TPL_REVIEW_FORM, 'assign' => $aAssign)
		);
	}



	/**
	 * displayPopinFb() method displays popin of created Fb voucher
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayPopinFb(array $aPost = null)
	{
		@ob_end_clean();

		$aAssign = array();

		// only case of valid review URL
		if (isset($aPost['sReviewUrl'])) {
			// check URL
			preg_match('/iRId=([0-9]{1,})/', $aPost['sReviewUrl'], $aMatchingReview);
			preg_match('/iPId=([0-9]{1,})/', $aPost['sReviewUrl'], $aMatchingProduct);

			$iReviewId = isset($aMatchingReview[1])? $aMatchingReview[1] : 0;
			$iProductId = isset($aMatchingProduct[1])? $aMatchingProduct[1] : 0;

			// get Fb page and post IDs
			if (!empty($iReviewId) && !empty($iProductId)) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// get matched review
				$aReview = BT_ReviewCtrl::create()->run('getReviews', array('ratingId' => $iReviewId, 'customer' => true));

				if (!empty($aReview)) {
					$aAssign['aFbLike']['reviewId'] = $aReview['id'];

					// check if author is the same connected customer
					if ($aReview['custId'] == GSnippetsReviews::$oCookie->id_customer) {
						// use case - voucher for sharing FB post is activated
						if (BT_GsrModuleTools::getEnableVouchers('share')) {
							// require
							require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

							$aVoucher = BT_Voucher::create()->getSettings('share');

							// generate voucher
							if (!empty($aVoucher)) {
								$sVoucherCode = $this->generateVoucher($aReview['id'], $aReview['custId'], 'share');

								if (!empty($sVoucherCode)) {
									// get settings
									$aVoucher = BT_Voucher::create()->getSettings('share');

									$aVoucher['name'] = $sVoucherCode;

									// get voucher data
									$aSettings = BT_Voucher::create()->formatData($aVoucher, GSnippetsReviews::$sCurrentLang);

									// assign voucher data
									if (!empty($aSettings['use'])) {
										$aAssign['aVoucherFb'] = $aSettings;

										$aData = array(
											'aReview'   => $aReview,
											'aVoucher'  => $aSettings,
										);

										// send notification
										$mSend = $this->sendNotification('voucherNotification', $aData);
									}
									unset($aSettings);
								}
							}
							unset($aVoucher);
						}
					}
				}
				unset($aReview);
			}
			unset($iReviewId);
			unset($iProductId);
			unset($aMatchingReview);
			unset($aMatchingProduct);
		}

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_POST_POPIN, 'assign' => $aAssign)
		);
	}


	/**
	 * displayLastReviewsBlock() method display last reviews block
	 *
	 * @param bool $sPosition
	 * @return array
	 */
	private function displayLastReviewsBlock($sPosition)
	{
		// set
		$aAssign = array();

		// get badge pages options
		if (empty(self::$aLastBlockOptions)) {
			self::$aLastBlockOptions = (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK']) && is_string(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK']))? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK']) : array();
		}

		// test position : left or right col / top / bottom
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
			&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_LAST_RVW_BLOCK']
			&& !empty(self::$aLastBlockOptions[$this->sPageType]['position'])
			&& array_key_exists($sPosition, $GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS'])
			&& $sPosition == self::$aLastBlockOptions[$this->sPageType]['position']
			&& Tools::getValue('list') == false
		) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			$aReviewParams = array(
				'limit' => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_LAST_REVIEWS'],
				'langId' => BT_GsrModuleTools::getCustomerLanguage(),
				'shopId' => GSnippetsReviews::$iShopId,
				'orderBy' => 'dateAdd DESC',
				'customer' => true,
				'product' => true,
				'report' => true,
				'bIndexArray' => true,
			);

			// use case - if the last review block is activated on the other pages, we should set all the fields required
			// for teh query to the original state, because the account hook is executed before this one and we change the
			// alias name of each field to not get conflict on different queries done in the same hook
			if (Tools::getValue('controller') == 'account') {
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('id', 'RVW_ID as id');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('ratingId', 'rvw.RTG_ID as ratingId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('display', 'RVW_STATUS as display');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('data', 'RVW_DATA as data');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('shopId', 'RVW_SHOP_ID as shopId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('productId', 'RVW_PROD_ID as productId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('dateAdd', 'UNIX_TIMESTAMP(RVW_DATE_ADD) as dateAdd');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('dateUpd', 'UNIX_TIMESTAMP(RVW_DATE_UPD) as dateUpd');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('custId', 'RVW_CUST_ID as custId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->getFields();
			}
			// get last reviews
			$aReviews = BT_ReviewCtrl::create()->run('getReviews', $aReviewParams);

			if (!empty($aReviews)) {
				$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

				foreach ($aReviews as &$aReview) {
					// set review information : date / address / rating / product link
					$oProduct = BT_GsrModuleTools::isProductObj($aReview['productId'], GSnippetsReviews::$iCurrentLang, true);
					$aReview['sProductLink'] = Context::getContext()->link->getProductLink($oProduct);
					$aReview['sProductName'] = $oProduct->name;
					$aReview['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateAdd'], null, $sLocale);
					$aReview['dateUpd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateUpd'], null, $sLocale);
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_ADDRESS'])) {
						$aReview['address'] = BT_GsrModuleTools::getCustomerAddressForReview($aReview['custId'], $aReview['langId']);
					}

					// get related rating to each review
					$aReview['rating'] = BT_ReviewCtrl::create()->run('getRatings', array('id' => $aReview['ratingId'], 'bAssociativeArray' => true));
					unset($oProduct);
				}
			}
			unset($aReviewParams);

			// assign
			$aAssign = array(
				'aBadeOptions'      => self::$aLastBlockOptions[$this->sPageType],
				'sPosition'         => $sPosition,
				'bDisplayReviews'   => true,
				'sURI'              => $_SERVER['REQUEST_URI'],
				'iMaxRating'        => _GSR_MAX_RATING,
				'aQueryParams'      => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
				'sSecureReviewKey'  => $this->sSecureKey,
				'aReviews'          => $aReviews,
				'bDisplayFirst'     => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_FIRST'],
				'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
				'sReviewsControllerUrl' => Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true),
					null,
					GSnippetsReviews::$iCurrentLang
				),
			);
			$aAssign['aErrors'] = BT_ReviewCtrl::create()->aErrors;
		}
		else {
			$aAssign['bDisplayReviews'] = false;
		}

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_LAST_REVIEWS_BLOCK): _GSR_TPL_LAST_REVIEWS_BLOCK;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . $sTpl, 'assign' => $aAssign)
		);
	}

	/**
	 * displayAccount() method displays link and content of customer account for reviews
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayAccount(array $aParams = null)
	{
		$aAssign = array();
		$aProductsToReview = array();
		$aTmpReviewedProducts = array();
		$aReviewedProducts = array();
		$aReviews = array();

		// detect the good template to use according to the PS version
		$sTpl = !empty(GSnippetsReviews::$bCompare17)? str_replace('.tpl', '-17.tpl', _GSR_TPL_MY_ACCOUNT): _GSR_TPL_MY_ACCOUNT;
		$sTpl = _GSR_TPL_HOOK_PATH . $sTpl;

		// use case - if customer logged and page account required
		if (!empty(GSnippetsReviews::$oCookie->id_customer)
			&& !empty($aParams['display'])
			&& $aParams['display'] == 'review'
		) {
			$aAssign['bEnableRatings'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'];

			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');
				// get all products bought by the customer + product review
				$aBoughtProducts = BT_GsrModuleDao::getBoughtProducts(GSnippetsReviews::$oCookie->id_customer);

				// use case - get customer's reviews
				BT_ReviewCtrl::create()->getClass('rating')->oDAO->setField('id', 'r.RTG_ID as id');
				BT_ReviewCtrl::create()->getClass('rating')->oDAO->getFields();
				BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as afsId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('id', 'RVW_ID as rvwId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('ratingId', 'rvw.RTG_ID as ratingId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('display', 'RVW_STATUS as display');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('data', 'RVW_DATA as rvwData');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('shopId', 'RVW_SHOP_ID as rvwShopId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('productId', 'RVW_PROD_ID as rvwProdId');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('dateAdd', 'UNIX_TIMESTAMP(RVW_DATE_ADD) as rvwDateAdd');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('dateUpd', 'UNIX_TIMESTAMP(RVW_DATE_UPD) as rvwDateUpd');
				BT_ReviewCtrl::create()->getClass('review')->oDAO->setField('custId', 'RVW_CUST_ID as rvwCustId');
				$aReviewsParams = array(
					'custId' => GSnippetsReviews::$oCookie->id_customer,
					'customer' => true,
					'report' => true,
					'rvwData' => true,
					'replyData' => true,
					'address' => true,
					'table' => array(
						array(
							'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
							'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
						),
						array(
							'fields' => BT_ReviewCtrl::create()->getClass('review')->oDAO->getFields(),
							'join' => BT_ReviewCtrl::create()->getClass('review')->oDAO->formatJoin('r.RTG_ID', 'LEFT'),
						),
					)
				);
				$aTmpReviews = BT_ReviewCtrl::create()->run('getRatings', $aReviewsParams);

				if (!empty($aTmpReviews)) {
					foreach ($aTmpReviews as $iKey => $aRating) {
						// set module modify review link
						$aRating['modifyReviewLink'] = Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW_FORM,
							array(
								'iRId' => $aRating['id'],
								'iPId' => $aRating['prodId'],
								'iCId' => $aRating['custId'],
								'btKey' => BT_GsrModuleTools::setSecureKey($aRating['shopId'].$aRating['prodId'].$aRating['id'].$aRating['custId'].'modify'),
							),
							null,
							$aRating['langId']
						);
						$aRating['reviewLink']  = Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW,
							array(
								'iRId' => $aRating['id'],
								'iPId' => $aRating['prodId'],
							)
						);
						$aReviews[$aRating['prodId']] = $aRating;
					}
				}
				unset($aTmpReviews);

				// use case - there are product bought
				if (!empty($aBoughtProducts) ) {
					// use case - detect product to review
					foreach ($aBoughtProducts as $iKey => &$aProduct) {
						// get product data
						$aProduct = array_merge($aProduct, BT_GsrModuleTools::getProductData($aProduct['id_product'], GSnippetsReviews::$iCurrentLang, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_PROD_IMG']));

						// use case - check if the product si already reviewed
						if (!empty($aReviews)) {
							// product to review
							if (!array_key_exists($aProduct['id_product'], $aReviews)) {
								$aProductsToReview[] = $aProduct;
							}
							// products already reviewed
							else {
								$aProduct['productBought'] = true;
								$aTmpReviewedProducts[] = array_merge($aReviews[$aProduct['id_product']], $aProduct);
								// clear each product review in order to leave only alone reviews at the end
								unset($aReviews[$aProduct['id_product']]);
							}
						}
						else {
							// product to review
							$aProductsToReview[] = $aProduct;
						}
					}
				}
				// if it's still left reviews related to no product bought
				if (!empty($aReviews)) {
					foreach($aReviews as &$aReview) {
						$aReview = array_merge($aReview, BT_GsrModuleTools::getProductData($aReview['prodId'], GSnippetsReviews::$iCurrentLang, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_PROD_IMG']));
						$aReview['productBought'] = false;
						$aTmpReviewedProducts[] = $aReview;
					}
				}
				unset($aReviews);

				// add homeslider css and js
				if (!empty($aProductsToReview) && !empty($aAssign['bEnableRatings'])) {
					$aAssign['iNbNoReview'] = count($aProductsToReview);
					Context::getContext()->controller->addCSS(_GSR_URL_CSS . 'homeslider.css');
					Context::getContext()->controller->addJqueryPlugin(array('bxslider'));
				}

				$aAssign['bActivateReview'] = true;
				$aAssign['iSliderWidth'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_WIDTH'];
				$aAssign['iSliderSpeed'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_SPEED'];
				$aAssign['iSliderPause'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_PAUSE'];
			}
			else {
				$aAssign['bActivateReview'] = false;
			}

			// get status of customer callback
			$aAssign['bCallbackStatus'] = BT_GsrModuleDao::getCustCallbackStatus(GSnippetsReviews::$iShopId, GSnippetsReviews::$oCookie->id_customer);

			($aAssign['bCallbackStatus'] === false)? $aAssign['bCallbackStatus'] = 1 : '';

			$sTpl = ((!empty($aParams['bCtrlTplPath']))? '' : _GSR_TPL_HOOK_PATH) . _GSR_TPL_CUST_ACCOUNT;

			// set js msg translation
			BT_GsrModuleTools::translateJsMsg();

			$aAssign['oJsTranslatedMsg']  = BT_GsrModuleTools::jsonEncode($GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']);

			// set my-account link
			$aAssign['sMyAccountLink'] = Context::getContext()->link->getPageLink('my-account');

			if (!empty($aTmpReviewedProducts)) {
				foreach ($aTmpReviewedProducts as $aReview) {
					$aReviewedProducts[$aReview['rvwDateAdd']] = $aReview;
				}
				krsort($aReviewedProducts);
			}
			unset($aTmpReviewedProducts);

			// set module's URI
			$aAssign['aProductsToReview']   = $aProductsToReview;
			$aAssign['aReviewedProducts']   = $aReviewedProducts;
			$aAssign['aQueryParams']    = $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'];
			$aAssign['sAjaxUri']        = _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php';
			$aAssign['iMaxRating']      = _GSR_MAX_RATING;
			$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));
			$aAssign['aParamStars']     = array(
				'star' =>_GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
			);
			$aAssign['bUseCallback']    = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_CALLBACK'];
			$aAssign['sOpenForm']       = $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['name'];
			$aAssign['sHeaderInclude']  = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_HEADER);
			$aAssign['sConfirmInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_CONFIRM);
			$aAssign['sErrorInclude']   = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR);
			$aAssign['meta_title']      = GSnippetsReviews::$oModule->l('My Reviews on shop', 'hook-display_class') . ' ' . Configuration::get('PS_SHOP_NAME');
			$aAssign['meta_description']= GSnippetsReviews::$oModule->l('Customers are able to handle their reviews', 'hook-display_class');
		}
		$aAssign['sBASE_URI']     = Context::getContext()->link->getPageLink('index');
		$aAssign['sMODULE_URI']     = Context::getContext()->link->getModuleLink(_GSR_MODULE_SET_NAME, _GSR_FRONT_CTRL_ACCOUNT);

		return (
			array('tpl' => $sTpl, 'assign' => $aAssign)
		);
	}


	/**
	 * displayReview() method display review in standalone mode
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayReview(array $aParams = null)
	{
		return (
			array('tpl' => ((!empty($aParams['bCtrlTplPath']))? '' : _GSR_TPL_HOOK_PATH) . _GSR_TPL_REVIEW_DISPLAY, 'assign' => $this->getStandaloneReview())
		);
	}


	/**
	 * getStandaloneReview() method return standalone review data
	 *
	 * @return array
	 */
	private function getStandaloneReview()
	{
		// set
		$aAssign = array();

		// get product and review IDs
		$iProductId = Tools::getValue('iPId');
		$iRatingId = Tools::getValue('iRId');

		$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME .  _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR);

		// use case - display reviews system
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']) {
			// use case - if check product and rating id
			if (!empty($iProductId) && !empty($iRatingId)) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// use case - change the field definition in order to avoid any conflict on the same field name between different tables
				BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as afsId');

				// get current rating
				$aParams = array(
					'id' => $iRatingId,
					'customer' => true,
					'report' => true,
					'bAssociativeArray' => true,
					'table' => array(
						array(
							'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
							'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
						),
					)
				);
				$aRating = BT_ReviewCtrl::create()->run('getRatings', $aParams);

				// get product
				$oProduct = BT_GsrModuleTools::isProductObj($iProductId, GSnippetsReviews::$iCurrentLang, true);

				// match review and product
				if (!empty($aRating) && $oProduct != false) {
					// unserialize data
					BT_ReviewCtrl::create()->getClass('reply')->unserialize('replyData', $aRating);
					$aRating['replyData']['sComment'] = str_replace("\n", "<br/>", trim($aRating['replyData']['sComment']));

					// set locale for review date
					$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

					// format date
					$aRating['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['dateAdd'], null, $sLocale);
					$aRating['replyDateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['replyDateAdd'], null, $sLocale);

					// get review
					$aParams = array(
						'ratingId' => $aRating['id'],
						'date' => 'd-m-Y',
						'locale' => $sLocale,
						'forceDate' => true,
						'active' => 2,
						'report' => true,
						'address' => (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_ADDRESS'])? true : false),
					);
					$aRating['review'] = BT_ReviewCtrl::create()->run('getReviews', $aParams);

					unset($aParams);
					unset($sLocale);

					$aDescProd = array(
						'meta_description' => $oProduct->meta_description,
						'description_short' => $oProduct->description_short,
						'description' => $oProduct->description,
					);

					$aAssign = array(
						'sURI'              => BT_GsrModuleTools::detectHttpUri($_SERVER['REQUEST_URI']),
						'sShopName'         => Configuration::get('PS_SHOP_NAME'),
						'aQueryParams' 	    => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
						'iMaxRating'        => _GSR_MAX_RATING,
						'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
						'aProduct' => array(
							'id'    => $iProductId,
							'name'  => $oProduct->name,
							'desc'  => BT_GsrModuleTools::manageProductDesc($aDescProd),
							'img'   => BT_GsrModuleTools::getProductImage($oProduct, _GSR_SINGLE_REVIEW_IMG_SIZE),
							'link'  => Context::getContext()->link->getProductLink(BT_GsrModuleTools::isProductObj($iProductId, GSnippetsReviews::$iCurrentLang, true)),
						),
						'aRating' => $aRating,
						'oJsTranslatedMsg'  => BT_GsrModuleTools::jsonEncode($GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']),
						'sHeaderInclude'    => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_HEADER),
						'path'              => GSnippetsReviews::$oModule->l('Shop\'s reviews', 'hook-display_class'),
						'meta_title'        => GSnippetsReviews::$oModule->l('Reviews of shop', 'hook-display_class') . ' ' . Configuration::get('PS_SHOP_NAME'),
						'meta_description'  => GSnippetsReviews::$oModule->l('Our customers are able to visualize their review they shared on Facebook', 'hook-display_class'),
						'sImgUrl'           => _GSR_URL_IMG,
					);
					// check if review not empty
					if (!empty($aRating['review'])) {
						$aAssign['sReportUrl'] = BT_GsrModuleTools::formatReviewReportUrl($iProductId, $this->setSecureKey($iProductId . $aRating['review']['id'], false), $aAssign['sURI'], $aRating['review']['id']);
					}
					unset($oProduct);
					unset($aReview);
				}
				else {
					$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('The review requested has either been deactivated or deleted', 'hook-display_class'), 'code' => 101);
				}
			}
			else {
				$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('There was an internal server error (unsecure request), check product and rating IDs', 'hook-display_class'), 'code' => 102);
			}
		}
		else {
			$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('Reviews system has been deactivated', 'hook-display_class'), 'code' => 103);
		}

		return $aAssign;
	}

	/**
	 * displayReviewListPage() method display reviews page
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayReviewListPage(array $aParams = null)
	{
		// set
		$aAssign = array();

		// use case - only if reviews are displayed
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']) {
			$aAssign = array(
				'sURI'              => $_SERVER['REQUEST_URI'],
				'bDisplayButtons'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'],
				'bCountBoxButton' 	=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COUNT_BOX_BUTTON'],
				'iFbButton'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_BUTTON_TYPE'],
				'iMaxRating'        => _GSR_MAX_RATING,
				'sMODULE_URI'       => _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php',
				'aQueryParams'      => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
				'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
				'sIMG_URI'          => _GSR_URL_IMG . 'hook/',
				'sImgUrl'           => _GSR_URL_IMG,
				'aJSCallback'       => array(),
				'bDisplayReviewList'=> true,
				'sErrorInclude'     => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME .  _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR),
				'iPage'             => (Tools::getIsset('iPage')? Tools::getValue('iPage') : 1),
				'path'              => GSnippetsReviews::$oModule->l('all reviews', 'hook-display_class'),
				'meta_title'        => GSnippetsReviews::$oModule->l('All reviews of the shop', 'hook-display_class') . ' ' . Configuration::get('PS_SHOP_NAME'),
				'meta_description'  => GSnippetsReviews::$oModule->l('All reviews of the shop', 'hook-display_class'),
			);

			// include
			require_once(_GSR_PATH_LIB_COMMON . 'pagination.class.php');
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - count reviews
			$iCountReviews = BT_ReviewCtrl::create()->run('countReviews', array('langId' => BT_GsrModuleTools::getCustomerLanguage()));

			// set pagination
			$aPagination = BT_Pagination::create()->run(array('total' => $iCountReviews, 'perPage' => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_PAGE']));

			// check pagination
			if (!isset($aPagination[$aAssign['iPage']]['begin'])) {
				$aAssign['iPage'] = 1;
			}

			// set params for getting ratings and reviews
			$aReviewsParams = array(
				'orderBy'           => 'dateAdd DESC',
				'interval'          => $aPagination[$aAssign['iPage']]['begin'] . ',' . $aPagination[$aAssign['iPage']]['nb'],
				'langId'            => BT_GsrModuleTools::getCustomerLanguage(),
				'shopId'            => GSnippetsReviews::$iShopId,
				'customer'          => true,
				'report'            => true,
				'address'           => (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_ADDRESS'])? true : false),
				'bIndexArray'       => true,
			);

			$aAssign['aReviewList'] = BT_ReviewCtrl::create()->run('getReviews', $aReviewsParams);
			$aAssign['aErrors']     = BT_ReviewCtrl::create()->aErrors;
			$aAssign['aPagination'] = $aPagination;
			$aAssign['iCurrentPage']= $aAssign['iPage'];
			$aAssign['iTotalPage']  = count($aPagination);

			// set standalone review URL
			if (count($aAssign['aReviewList'])) {
				// use case - handle social buttons
				if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'])) {
					$aAssign['bUseSocialNetworkJs'] = true;
					// set default lang
					$aAssign['sFbLang'] = !isset($GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['FB'])? 'en_US' : $GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['FB'];

					// set default twitter lang
					$aAssign['sTwitterLang'] = !isset($GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['TWITTER'])? 'en' : $GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'][GSnippetsReviews::$sCurrentLang]['TWITTER'];
				}

				// set module front controller URL
				$aAssign['sCurrentRvwCtrlUrl'] = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true, 'iPage' => $aAssign['iPage']),
					null,
					GSnippetsReviews::$iCurrentLang
				);
				$aAssign['sPaginationRvwCtrlUrl'] = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEWS,
					array('list' => true),
					null,
					GSnippetsReviews::$iCurrentLang
				);

				// set locale for review date
				$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

				// set standalone review data
				foreach ($aAssign['aReviewList'] as $iIndex => &$aReview) {
					// get product name
					$oProduct = BT_GsrModuleTools::isProductObj($aReview['productId'], $aReview['langId'], true);

					if (!empty($oProduct)) {
						// set secure key
						$aReview['sReportUrl'] = BT_GsrModuleTools::formatReviewReportUrl($aReview['productId'], $this->setSecureKey($aReview['productId'] . $aReview['id']), $aAssign['sURI'], $aReview['id']);
						// format date
						$aReview['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateAdd'], null, $sLocale);
						$aReview['dateUpd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateUpd'], null, $sLocale);

						$aReview['sProductName'] = $oProduct->name;
						$aReview['sProductLink'] = Context::getContext()->link->getProductLink($oProduct);

						// get the product image
						if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PHOTO_REVIEWS']) {
							$aReview['sProductImage'] = BT_GsrModuleTools::getProductImage($oProduct, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_LIST_PROD_IMG']);
						}

						// use case - change the field definition in order to avoid any conflict on the same field name between different tables
						BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as afsId');

						// get related rating to each review
						$aParams = array(
							'id' => $aReview['ratingId'],
							'bAssociativeArray' => true,
							'table' => array(
								array(
									'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
									'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
								),
							)
						);
						$aReview['rating'] = BT_ReviewCtrl::create()->run('getRatings', $aParams);

						// unserialize data
						BT_ReviewCtrl::create()->getClass('reply')->unserialize('replyData', $aReview['rating']);
						$aReview['rating']['replyData']['sComment'] = str_replace("\n", "<br/>", trim($aReview['rating']['replyData']['sComment']));
						$aReview['data']['sComment'] = str_replace("\n", "<br/>", trim($aReview['data']['sComment']));

						// set locale for review date
						$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

						// format date
						$aReview['rating']['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aReview['rating']['dateAdd'], null, $sLocale);
						$aReview['rating']['replyDateAdd'] = BT_GsrModuleTools::formatTimestamp($aReview['rating']['replyDateAdd'], null, $sLocale);

						// set review URL in standalone mode
						$aReview['sReviewUrl'] = Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW,
							array(
								'iRId' => $aReview['ratingId'],
								'iPId' => $aReview['productId'],
							),
							null,
							$aReview['langId']
						);
						$aAssign['aJSCallback'][] = array('url' => $aReview['sReviewUrl'], 'function' => 'bt_generateFbVoucherCode');
					}
					else {
						unset($aAssign['aReviewList'][$iIndex]);
					}
					unset($oProduct);
				}
			}
			unset($aPagination);
			unset($aReviewsParams);
		}
		else {
			$aAssign['bDisplayReviewList'] = false;
		}

		return (
			array('tpl' => _GSR_TPL_REVIEW_LIST, 'assign' => $aAssign)
		);
	}


	/**
	 * displayProductRating() method returns average and ratings number posted for one product in product list page
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function displayProductRating(array $aParams = null)
	{
		// set
		$aAssign = array('iAverage' => 0);
		$aReviewsJson = array('status' => 'ko');
		$bProcessJson = !empty($aParams['json'])? true : false;

		// if review system is displayed
		$aAssign['bDisplayReviews'] = !empty($aParams['force'])? true : GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'];

		// if id exists
		if (!empty($aParams['id']) && !empty($aAssign['bDisplayReviews'])) {
			// check and get Product object
			$oProduct = BT_GsrModuleTools::isProductObj($aParams['id'], GSnippetsReviews::$iCurrentLang, (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST']) && empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HAS_SNIPPETS_PRODLIST'])? true : false));

			// use case - only if reviews are displayed and obj validated
			if (!empty($oProduct)) {
				$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));

				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// detect if we force language id
				$iLangId = !empty($aParams['langId'])? $aParams['langId'] : BT_GsrModuleTools::getCustomerLanguage();

				if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST'])
					&& empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HAS_SNIPPETS_PRODLIST'])) {
					$aAssign['sItemReviewed'] = $oProduct->name;
				}
				else {
					$aAssign['sItemReviewed'] = '';
				}
				// use case - ratings
				$aAssign['iCountRatings'] = BT_ReviewCtrl::create()->run('countRatings', array('productId' => $aParams['id'], 'langId' => $iLangId));

				if ($aAssign['iCountRatings']) {
					// get average
					$aAverage = BT_ReviewCtrl::create()->run('average', array('iProductId' => $aParams['id'], 'langId' => $iLangId));

					if (!empty($aAverage)) {
						$aAssign['iAverage'] = $aAverage['iAverage'];
						$aAssign['fDetailAverage'] = $aAverage['fDetailAverage'];
						$aAssign['iDefaultMaxRating'] = _GSR_MAX_RATING;

						// use case - if the script is called from server to server or by ajax method from another module
						if ($bProcessJson) {
							$aReviewsJson['status'] = 'ok';
							$aReviewsJson['average'] = $aAssign['iAverage'];
							$aReviewsJson['maxRating'] = $aAssign['iDefaultMaxRating'];
							$aReviewsJson['message'] = 'this product has ratings and reviews';

							// use case - if not average only
							if (empty($aParams['average'])
								|| (isset($aParams['average'])
								&& $aParams['average'] == 'false')
							) {
								$aReviewsParams = array(
									'productId'         => $aParams['id'],
									'bOnlyReview'       => false,
									'bCommentCustomer'  => false,
									'bRatingCustomer'   => true,
									'orderBy'           => 'dateAdd DESC',
									'langId'            => $iLangId,
									'report'            => false,
								);
								$aReviewsJson['reviews'] = BT_ReviewCtrl::create()->run('getReviewsOnProduct', $aReviewsParams);
							}
						}
						else {
							$aAssign['bHalfStar'] = !empty($aAverage['bHalf']) ? true : false;
							$aAssign['iMaxRating'] = !empty($aAverage['bHalf']) ? (_GSR_MAX_RATING * 2) : _GSR_MAX_RATING;
						}
					}
					else {
						$aReviewsJson['message'] = 'this product has no rating average';
					}
					$aAssign['iProductId']  = $aParams['id'];
					$aAssign['sSuffix']     = !empty($aParams['suffix'])? $aParams['suffix'] : '';
				}
				else {
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_EMPTY_RATING'])) {
						$aAssign['iAverage'] = 0;
						$aAssign['bHalfStar'] = false;
						$aAssign['iMaxRating'] = _GSR_MAX_RATING;
						$aAssign['iProductId']  = $aParams['id'];
						$aAssign['sSuffix']     = !empty($aParams['suffix'])? $aParams['suffix'] : '';

						if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_BEFIRST_MSG'])) {
							if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BEFIRST_SENTENCE'])) {
								$aFirstText = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BEFIRST_SENTENCE']);

								if (isset($aFirstText[GSnippetsReviews::$iCurrentLang])) {
									$aAssign['sTextFirst'] = $aFirstText[GSnippetsReviews::$iCurrentLang];
								}
								if (empty($aAssign['sTextFirst'])) {
									$aAssign['sTextFirst'] = $GLOBALS[_GSR_MODULE_NAME . '_BEFIRST_DEFAULT_TRANSLATE']['en'];
								}
								$aAssign['sProductLink'] = Context::getContext()->link->getProductLink($aParams['id']);
								$aAssign['sProductLink'] .= (strstr($aAssign['sProductLink'], '?')? '&' : '?' ). $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['name'].'='.$GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['value'];
							}
						}
					}
					else {
						$aAssign['bDisplayReviews'] = false;
					}
				}
				$aAssign['iStarDisplayMode'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_STAR_RATING_MODE'];
				unset($aAverage);
			}
			unset($oProduct);
		}

		$aAssign['bSnippetsProdList'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST'];
		$aAssign['bPsVersion1611'] = GSnippetsReviews::$bCompare1611;
		$aAssign['iStarPaddingLeft'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STARS_PADDING_LEFT'];
		$aAssign['iStarSize'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STARS_SIZE'];
		$aAssign['iTextSize'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_TEXT_SIZE'];
		$aAssign['bProcessJson'] = $bProcessJson;
		$aAssign['json'] = Tools::jsonEncode($aReviewsJson);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEW_PAGE_LIST, 'assign' => $aAssign)
		);
	}


	/**
	 * calculateProductPrice() method calculate price with or without tax
	 *
	 * @param int $iProductId
	 * @param int $iProductAttributeId
	 * @param bool $bFormat
	 * @return float
	 */
	private function calculateProductPrice($iProductId, $iProductAttributeId = null, $bFormat = true)
	{
		$fPrice = 0.00;

		$iCustId = isset(GSnippetsReviews::$oCookie->id_customer)? GSnippetsReviews::$oCookie->id_customer : null;

		// get type of display price
		$iDisplayPriceType = Product::getTaxCalculationMethod($iCustId);

		// set if display price with or without tax
		$bDisplayTax = $iDisplayPriceType == 1 || $iDisplayPriceType === null ? false : true;

		// calculate
		$fPrice = Product::getPriceStatic($iProductId, $bDisplayTax, $iProductAttributeId);

		if ($bFormat) {
			$fPrice = $this->formatProductPrice($fPrice, GSnippetsReviews::$oCookie->id_currency);
		}
		else {
			$fPrice = number_format(BT_GsrModuleTools::round($fPrice, 2), 2, '.', '');
		}
		unset($iCustId);

		return $fPrice;
	}

	/**
	 * formatProductPrice() method format price
	 *
	 * @param float $fPrice
	 * @param int $iCurrencyId
	 * @return string
	 */
	private function formatProductPrice($fPrice, $iCurrencyId)
	{
		// use case - price
		return Tools::displayPrice($fPrice, intval($iCurrencyId));
	}

	/**
	 * getGenericFuncToExecute() method returns a list of function to execute in the generic function in order to get many contents from this module in the same hook
	 *
	 * @param mixed $mParam
	 * @return array
	 */
	private function getGenericFuncToExecute($mParam)
	{
		// use case if last reviews block first
		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_FIRST'])) {
			$aFuncToExecute = array(
				array('name' => 'displayLastReviewsBlock', 'params' => $mParam),
				array('name' => 'displaySnippets', 'params' => $mParam),
			);
		}
		else {
			$aFuncToExecute = array(
				array('name' => 'displaySnippets', 'params' => $mParam),
				array('name' => 'displayLastReviewsBlock', 'params' => $mParam),
			);
		}

		return $aFuncToExecute;
	}
}