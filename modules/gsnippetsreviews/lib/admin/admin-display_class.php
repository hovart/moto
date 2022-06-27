<?php
/**
 * admin-display_class.php file defines method to display content tabs of admin page
 */

class BT_AdminDisplay implements BT_IAdmin
{
	/**
	 * @var array $aFlagIds : array for all flag ids used in option translation
	 */
	protected $aFlagIds = array();

	/**
	 * Magic Method __construct
	 */
	private function __construct()
	{

	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}

	/**
	 * run() method display all configured data admin tabs
	 * 
	 * @param string $sType => define which method to execute
	 * @param array $aParam
	 * @return array
	 */
	public function run($sType, array $aParam = null)
	{
		// set variables
		$aDisplayInfo = array();

		if (empty($sType)) {
			$sType = 'tabs';
		}

		switch ($sType) {
			case 'tabs' :               // use case - display first page with all tabs
			case 'snippets' :           // use case - display snippets settings page
			case 'reviews' :            // use case - display reviews settings page
			case 'facebookReviews' :    // use case - display FB reviews settings page
			case 'emailReviews' :       // use case - display email reviews settings page
			case 'vouchers' :           // use case - display vouchers settings page
			case 'cronReport' :         // use case - display last cron report
			case 'commentsImport' :     // use case - display comments product import
			case 'ordersSelect' :       // use case - display orders select
				// require voucher class - to factorise
				require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

				// set flag ids used in almost cases
				$this->setFlagIds();

				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'display' . ucfirst($sType)), array($aParam));
				break;
			default :
				break;
		}
		// use case - generic assign
		if (!empty($aDisplayInfo)) {
			$aDisplayInfo['assign'] = array_merge($aDisplayInfo['assign'], $this->assign());
		}

		return (
			$aDisplayInfo
		);
	}

	/**
	 * assign() method assigns transverse data
	 *
	 * @return array
	 */
	private function assign()
	{
		// add jquery sortable plugin
		$aJsCss = Media::getJqueryPluginPath('sortable');
		Context::getContext()->controller->addJS($aJsCss['js']);

		// set smarty variables
		$aAssign = array(
			'sURI' 			    => BT_GsrModuleTools::truncateUri(array('&iPage', '&sAction')),
			'aQueryParams' 	    => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
			'sDisplay'          => Tools::getValue('sDisplay'),
			'iDefaultLang'      => Configuration::get('PS_DEFAULT_LANG'),
			'iCurrentLang' 	    => intval(GSnippetsReviews::$iCurrentLang),
			'sCurrentLang' 	    => GSnippetsReviews::$sCurrentLang,
			'sCurrentIso'       => Language::getIsoById(GSnippetsReviews::$iCurrentLang),
			'aLangs'            => Language::getLanguages(),
			'sFlagIds' 	        => $this->getFlagIds(),
			'aFlagIds' 	        => $this->aFlagIds,
			'sTs'				=> time(),
			'sLoader'           => _GSR_URL_IMG . _GSR_LOADER_GIF,
			'sLoaderLarge'      => _GSR_URL_IMG . _GSR_LOADER_GIF,
			'sHeaderInclude'    => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_HEADER),
			'sErrorInclude'     => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR),
			'sConfirmInclude'   => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_CONFIRM),
		);

		return $aAssign;
	}

	/**
	 * displayTabs() method displays admin's first page with all tabs
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayTabs(array $aPost = null)
	{
		// set smarty variables
		$aAssign = array(
			'sDocUri'           => _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/',
			'sDocName'          => 'readme_' . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr' : 'en') . '.pdf',
			'sContactUs'        =>  _GSR_SUPPORT_BT ?  _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/contactez-nous' : 'en/contact-us') :  _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/ecrire-au-developpeur?id_product=' . _GSR_SUPPORT_ID  : 'en/write-to-developper?id_product=' ._GSR_SUPPORT_ID),
			'sRateUrl'          =>  _GSR_SUPPORT_BT ? _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/modules-prestashop-reseaux-sociaux-facebook/50-module-prestashop-publicites-de-produits-facebook-pixel-facebook-0656272916497.html' : 'en/prestashop-modules-social-networks-facebook/50-prestashop-addon-facebook-product-ads-facebook-pixel-0656272916497.html') : _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? '/fr/ratings.php'  : '/en/ratings.php'),
			'sCrossSellingUrl'  => _GSR_SUPPORT_BT ? _GSR_SUPPORT_URL .  '?utm_campaign=internal-module-ad&utm_source=banniere&utm_medium=' . _GSR_MODULE_SET_NAME  : _GSR_SUPPORT_URL . '/6_business-tech',
			'sCrossSellingImg'  => (GSnippetsReviews::$sCurrentLang == 'fr') ? _GSR_URL_IMG . 'admin/module_banner_cross_selling_FR.jpg' : _GSR_URL_IMG .'admin/module_banner_cross_selling_EN.jpg',
			'bHideConfiguration'=> BT_GsrWarning::create()->bStopExecution,
			'sModuleVersion'    => GSnippetsReviews::$oModule->version,
		);

		// check if comments product and import are already made and done
		if ((!GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_IMPORT'] || _GSR_MOCK_IMPORT_DEBUG)
			&& BT_GsrModuleTools::isInstalled('productcomments', array(), false, true)
		) {
			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			// get number of reviews to import
			$iCountReviews = BT_GsrModuleDao::getModuleProductComments(true, 2, _GSR_MOCK_IMPORT_DEBUG);

			if (!empty($iCountReviews)) {
				$aAssign['bCommentsImport'] = true;
				$aAssign['iReviews'] = $iCountReviews;
				$aAssign['bAddCssModule'] = true;
			}
		}

		// use case - get display data of snippets settings
		$aData = $this->displaySnippets($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// use case - get display data of reviews settings
		$aData = $this->displayReviews($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// use case - get display data of email reviews settings
		$aData = $this->displayEmailReviews($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// use case - get display data for vouchers settings
		$aData = $this->displayVouchers($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// use case - get display data of FB reviews settings
		$aData = $this->displayFacebookReviews($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// assign all included templates files
		$aAssign['sWelcome'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_WELCOME);
		$aAssign['sSnippetsInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_SNIPPETS_SETTINGS);
		$aAssign['sReviewsInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_REVIEWS_SETTINGS);
		$aAssign['sEmailsInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_EMAIL_REVIEWS);
		$aAssign['sVouchersInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_FB_VOUCHERS_SETTINGS);
		$aAssign['sFacebookInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_FB_REVIEWS_SETTINGS);
		$aAssign['sModuleVersion'] = GSnippetsReviews::$oModule->version;

		// set css and js use
		$GLOBALS[_GSR_MODULE_NAME . '_USE_JS_CSS']['bUseJqueryUI'] = true;

		return (
			array(
				'tpl'		=> _GSR_TPL_ADMIN_PATH . _GSR_TPL_BODY,
				'assign'	=> array_merge($aAssign, $GLOBALS[_GSR_MODULE_NAME . '_USE_JS_CSS']),
			)
		);
	}

	/**
	 * displaySnippets() method displays snippets settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displaySnippets(array $aPost = null)
	{
		// translate desc title
		BT_GsrModuleTools::translateDescTitle();

		// translate badge styles' title
		BT_GsrModuleTools::translateBadgeStylesTitle();

		// translate badge pages' title
		BT_GsrModuleTools::translateBadgePagesTitle();

		// get badge pages and its styles
		foreach ($GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES'] as $sType => &$aValue) {
			if ($sType == 'product' && GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_RS']) {
				$aValue['use'] = true;
			}
			if (!empty($aValue['use'])) {
				foreach ($aValue['allow'] as &$aPosition) {
					if (array_key_exists($aPosition['position'], $GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES'])) {
						$aPosition['title'] = $GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES'][$aPosition['position']];
					}
				}
			}
		}

		// set smarty variables
		$aAssign = array(
			'sCtrlParamName' 	        => _GSR_PARAM_CTRL_NAME,
			'sController' 	            => _GSR_ADMIN_CTRL,
			'bDisplayProductRichSnippets'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_RS'],
			'bDisplayDesc'              => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_DESC'],
			'aDesc'                     => BT_GsrModuleTools::getSortDesc(),
			'bDisplayBrand'             => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_BRAND'],
			'bDisplayCat'               => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_CAT'],
			'bDisplayBreadcrumb'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_BREADCRUMB'],
			'bDisplayIdentifier'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_IDENTIFIER'],
			'bDisplaySupplier'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_SUPPLIER'],
			'bDisplayCondition'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_COND'],
			'sOfferType'                => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PRODUCT_OFFERS'],
			'bDisplaySeller'            => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_SELLER'],
			'bDisplayUntilDate'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_UNTIL_DATE'],
			'bDisplayAvaibility'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_AVAILABILITY'],
			'bDisplayHighPrice'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_HIGH_PRICE'],
			'bDisplayOfferCount'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PROD_OFFER_COUNT'],
			'sReviewType'               => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_TYPE'],
			'bDisplayRating'            => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RATING'],
			'bDisplayReviewDate'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_DATE'],
			'bDisplayReviewTitle'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_TITLE'],
			'bDisplayReviewDesc'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_DESC'],
			'bDisplayReviewAggregate'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PROD_RVW_AGGREGATE'],
			'bDisplayBadge'             => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_BADGE'],
			'aBadges'                   => (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_BADGES']) : array()),
			'aBadgePages'               => $GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES'],
		);

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_SNIPPETS_SETTINGS,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * displayReviews() method displays reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayReviews(array $aPost = null)
	{
		// translate hook title
		BT_GsrModuleTools::translateHookTitle();

		// translate authorized people's titles
		BT_GsrModuleTools::translateAuthorize();

		// translate reviews display mode's titles
		BT_GsrModuleTools::translateReviewsDisplayMode();

		// translate last review block pages' title
		BT_GsrModuleTools::translateLastBlockPagesTitle();

		// translate last review block pages' title
		BT_GsrModuleTools::translateLastBlockPosTitle();

		// get last review block pages and its styles
		foreach ($GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES'] as $sType => &$aValue) {
			if (!empty($aValue['use'])) {
				foreach ($aValue['allow'] as &$aPosition) {
					if (array_key_exists($aPosition['position'], $GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS'])) {
						$aPosition['title'] = $GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS'][$aPosition['position']];
					}
				}
			}
		}

		// include
		require_once(_GSR_PATH_LIB_COMMON . 'dir-reader.class.php');

		// get images files
		$aImgFiles = BT_DirReader::create()->run(array('path' => _GSR_PATH_ROOT . _GSR_PATH_VIEWS . _GSR_PATH_IMG . 'picto/', 'recursive' => true, 'extension' => 'png', 'subpath' => true, 'subpathname' => true));

		// use case - sort by folder name
		foreach ($aImgFiles as $k => $v) {
			if (!strstr($v['subpath'], '.AppleDouble')
				&& !strstr($v['subpath'], 'thumbs')
			) {
				$aImgFiles[$v['subpath']] = $v;
			}
			unset($aImgFiles[$k]);
		}
		ksort($aImgFiles);

		// detect if share voucher is activated
		$bShareVoucher = BT_GsrModuleTools::getEnableVouchers('share');

		// set smarty variables
		$aAssign = array(
			'sAdmniTabUrl'          => $_SERVER['SCRIPT_NAME'] . '?controller=AdminModerationTool&token=' . Tools::getAdminTokenLite('AdminModerationTool'),
			'aReviewHooks'          => BT_GsrModuleTools::getUsableHooks($GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_HOOKS']),
			'sHook'                 => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HOOK'],
			'aAuthorize'            => $GLOBALS[_GSR_MODULE_NAME . '_AUTHORIZE'],
			'sAuthorizeReview'      => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_USER'],
			'aReviewsMode'          => $GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'],
			'sDisplayReviewMode'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'],
			'bAdminApproval'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_APPROVAL'],
			'bEnableRatings'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'],
			'bEnableCustLang'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG'],
			'bEnableComments'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'],
			'bForceComments'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FORCE_COMMENTS'],
			'bDisplayReviews'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'],
			'bShareVoucher'         => (($bShareVoucher == true)? true : false),
			'bEnableSocialButton'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'],
			'bCountBoxButton'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COUNT_BOX_BUTTON'],
			'iFbButtonType'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_BUTTON_TYPE'],
			'iReviewsPerPage'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_PROD_PAGE'],
			'iReviewsListPerPage'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_PAGE'],
			'bDisplayPhoto'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_PHOTO_REVIEWS'],
			'bDisplayReportButton'  => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REPORT_BUTTON'],
			'bDisplayAddress'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_ADDRESS'],
			'iNbModerateReviews'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_MODERATION'],
			'iNbProductSlider'      => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_PROD_SLIDER'],
			'iNbProductReviewed'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_PROD_REVIEWED'],
			'aEltPerPage'           => $GLOBALS[_GSR_MODULE_NAME . '_NB_REVIEWS_VALUES'],
			'aSliderOpts'           => $GLOBALS[_GSR_MODULE_NAME . '_SLIDER_OPTS'],
			'iSliderWidth'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_WIDTH'],
			'iSliderSpeed'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_SPEED'],
			'iSliderPause'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_PAUSE'],
			'sReviewProdImg'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_PROD_IMG'],
			'sReviewListProdImg'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_LIST_PROD_IMG'],
			'sSliderProdImg'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SLIDER_PROD_IMG'],
			'bLastRvwBlockFirst'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_FIRST'],
			'bDisplayLastRvwBlock'  => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_LAST_RVW_BLOCK'],
			'sLastReviewHook'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_HOOK'],
			'iNbLastReviews'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_LAST_REVIEWS'],
			'aNbLastReviews'        => array(1,2,3,4,5,6,7,8,9,10,15,20),
			'aLastBlockPos'         => (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK']) : array()),
			'aLastBlockPages'       => $GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES'],
			'bDisplayStarsInList'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_HOOK_REVIEW_STARS'],
			'bUseSnippetsProdList'  => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST'],
			'bHasSnippetsProdList'  => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_HAS_SNIPPETS_PRODLIST'],
			'bDisplayEmptyRating'   => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_EMPTY_RATING'],
			'bDisplayBeFirstMessage'=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_BEFIRST_MSG'],
			'iStarDisplayMode'      => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISP_STAR_RATING_MODE'],
			'aStarsPaddingLeft'     => $GLOBALS[_GSR_MODULE_NAME . '_STAR_PADDING_VALUES'],
			'iStarPaddingLeft'      => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STARS_PADDING_LEFT'],
			'aStarSizes'            => $GLOBALS[_GSR_MODULE_NAME . '_STAR_SIZE_VALUES'],
			'iSelectStarSize'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STARS_SIZE'],
			'aTextSizes'            => $GLOBALS[_GSR_MODULE_NAME . '_TEXT_SIZE_VALUES'],
			'iSelectTextSize'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_TEXT_SIZE'],
			'aImages'               => $aImgFiles,
			'sPicto'                => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'],
			'bUseFontAwesome'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_USE_FONTAWESOME'],
			'bPS17'                 => GSnippetsReviews::$bCompare17,
			'bPS1710'               => GSnippetsReviews::$bCompare1710,
		);

		// use case - detect if be first message is translated with empty stars
		$aAssign['aBeFirst'] = $this->getDefaultTranslations('BEFIRST_SENTENCE', 'BEFIRST_DEFAULT_TRANSLATE');

		// get all active languages in order to loop on field form which need to manage translation
		$aAssign['aLangs'] = (array)Language::getLanguages();

		foreach ($aAssign['aLangs'] as $aLang) {
			if (!isset($aAssign['aBeFirst'][$aLang['id_lang']])) {
				$aAssign['aBeFirst'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_BEFIRST_DEFAULT_TRANSLATE']['en'];
			}
		}

		// get image size available
		$aAvailableTypes = ImageType::getImagesTypes('products');

		foreach ($aAvailableTypes as $key => $aImageSize) {
			$aAssign['aImageSize'][$key] = $aImageSize['name'];
		}

		unset($aAvailableTypes);

		$aAssign['sIncludingCode'] = htmlentities(
			'{literal}' . "\n"
			. '<div id="productRating{/literal}{$product.id_product|intval}{literal}"></div>' . "\n"
			. '<script>' . "\n"
			. ' $(document).ready(function(){' . "\n"
			. '     oGsr.getProductAverage({/literal}{$product.id_product|intval}{literal}, \'\');' . "\n"
			. ' });' . "\n"
			. '</script>' . "\n"
			. '{/literal}');

		return (
			array(
				'tpl'       => _GSR_TPL_ADMIN_PATH . _GSR_TPL_REVIEWS_SETTINGS,
				'assign'    => $aAssign,
			)
		);
	}


	/**
	 * displayEmailReviews() method displays email reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayEmailReviews(array $aPost = null)
	{
		// include
		require_once(_GSR_PATH_LIB . 'module-dao_class.php');
		require_once(_GSR_PATH_LIB_COMMON . 'serialize.class.php');

		// get pre-selection
		$aSelection = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION']) : GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'];

		// set cron secure key for the first time before updating options
		if (empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_CRON_SECURE_KEY'])) {
			Configuration::updateValue(_GSR_MODULE_NAME . '_CRON_SECURE_KEY', md5(_GSR_MODULE_NAME . Configuration::get('PS_SHOP_NAME') . rand(0, 1000)));
			// get configuration options
			BT_GsrModuleTools::getConfiguration();
		}

		// set smarty variables
		$aAssign = array(
			'sCronUrl'              => Configuration::get('PS_SHOP_DOMAIN'),
			'sToday'                => date('Y-m-d H:i:s', time()),
			'bEnableRatings'        => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RATINGS'],
			'bEnableComments'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_COMMENTS'],
			'bDisplayReviews'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS'],
			'sEmail'                => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'],
			'bEnableEmail'          => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_EMAIL'],
			'bEnableReviewEmail'    => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_RVW_EMAIL'],
			'bEnableCallback'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_CALLBACK'],
			'bOrdersImport'         => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ORDERS_IMPORT'],
			'bEnableCarbonCopy'     => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC'],
			'sCarbonCopyMail'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_CC'],
			'iDelayEmail'           => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL_DELAY'],
			'sSecureKey'            => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_CRON_SECURE_KEY'],
			'aImgTypes'             => ImageType::getImagesTypes('products'),
			'sProductImgType'       => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_MAIL_PROD_IMG'],
			'aEmailLangErrors'      => BT_GsrModuleTools::checkMailLanguages(),
			'aStatusSelection'      => $aSelection,
			'aOrderStatusTitle'     => BT_GsrModuleDao::getOrderStatus(),
			'bPsVersion1606'        => (version_compare(_PS_VERSION_, '1.6.0.4', '>')? true : false ),
		);
		unset($aSelection);

		// set the cron file name
		$sCronFile = _GSR_CBK_LOGS . GSnippetsReviews::$iShopId . _GSR_CBK_LOGS_EXT;

		if (file_exists(_GSR_PATH_LOGS . $sCronFile)) {
			$aAssign['aCronReport'] = BT_Serialize::create()->get(file_get_contents(_GSR_PATH_LOGS . $sCronFile));

			if (!is_writable(_GSR_PATH_LOGS . $sCronFile)) {
				$aAssign['bwritableReport'] = false;
				$aAssign['sReportFile'] = _GSR_PATH_LOGS . $sCronFile;
			}
		}

		// use case - detect if review email notification subject has been filled
		$aAssign['aReviewEmailSubject'] = $this->getDefaultTranslations('RVW_EMAIL_SUBJECT', 'NOTIFICATION_DEFAULT_TRANSLATE');
		// use case - detect if after-sales reply notification subject has been filled
		$aAssign['aReplyEmailSubject'] = $this->getDefaultTranslations('REPLY_EMAIL_SUBJECT', 'REPLY_DEFAULT_TRANSLATE');
		// use case - detect if after-sales reply notification text has been filled
		$aAssign['aReplyEmailText'] = $this->getDefaultTranslations('REPLY_EMAIL_TEXT', 'REPLY_TEXT_DEFAULT_TRANSLATE');
		// use case - detect if review email reminder subject has been filled
		$aAssign['aEmailSubject'] = $this->getDefaultTranslations('REMINDER_SUBJECT', 'REMINDER_DEFAULT_TRANSLATE');
		// use case - detect if review email reminder category label has been filled
		$aAssign['aEmailCategoryLabel'] = $this->getDefaultTranslations('REMINDER_MAIL_CAT_LABEL', 'REMINDER_DEFAULT_CAT_LABEL');
		// use case - detect if review email reminder prod label has been filled
		$aAssign['aEmailProductLabel'] = $this->getDefaultTranslations('REMINDER_MAIL_PROD_LABEL', 'REMINDER_DEFAULT_PROD_LABEL');
		// use case - detect if review email reminder sentence has been filled
		$aAssign['aEmailSentence'] = $this->getDefaultTranslations('REMINDER_MAIL_SENTENCE', 'REMINDER_DEFAULT_SENTENCE');

		// get all active languages in order to loop on field form which need to manage translation
		$aAssign['aLangs'] = (array)Language::getLanguages();

		foreach ($aAssign['aLangs'] as $aLang) {
			if (!isset($aAssign['aReviewEmailSubject'][$aLang['id_lang']])) {
				$aAssign['aReviewEmailSubject'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_NOTIFICATION_DEFAULT_TRANSLATE']['en'];
			}
			if (!isset($aAssign['aReplyEmailSubject'][$aLang['id_lang']])) {
				$aAssign['aReplyEmailSubject'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REPLY_DEFAULT_TRANSLATE']['en'];
			}
			if (!isset($aAssign['aReplyEmailText'][$aLang['id_lang']])) {
				$aAssign['aReplyEmailText'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REPLY_TEXT_DEFAULT_TRANSLATE']['en'];
			}
			if (empty($aAssign['aEmailSubject'][$aLang['id_lang']])) {
				$aAssign['aEmailSubject'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_TRANSLATE']['en'];
			}
			if (empty($aAssign['aEmailCategoryLabel'][$aLang['id_lang']])) {
				$aAssign['aEmailCategoryLabel'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_CAT_LABEL']['en'];
			}
			if (empty($aAssign['aEmailProductLabel'][$aLang['id_lang']])) {
				$aAssign['aEmailProductLabel'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_PROD_LABEL']['en'];
			}
			if (empty($aAssign['aEmailSentence'][$aLang['id_lang']])) {
				$aAssign['aEmailSentence'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_SENTENCE']['en'];
			}
		}

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_EMAIL_REVIEWS,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * displayCronReport() method displays last cron report
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayCronReport(array $aPost = null)
	{
		// clean headers
		@ob_end_clean();

		// include
		require_once(_GSR_PATH_LIB_COMMON . 'serialize.class.php');

		// set smarty variables
		$aAssign = array();

		$aAssign['bPsVersion1606'] = version_compare(_PS_VERSION_, '1.6.0.4', '>')? true : false;
		$aAssign['sShopName'] = Configuration::get('PS_SHOP_NAME');

		// set the cron file name
		$sCronFile = _GSR_CBK_LOGS . GSnippetsReviews::$iShopId . _GSR_CBK_LOGS_EXT;

		if (file_exists(_GSR_PATH_LOGS . $sCronFile) && filesize(_GSR_PATH_LOGS . $sCronFile)) {
			$aAssign['aCronReport'] = BT_Serialize::create()->get(file_get_contents(_GSR_PATH_LOGS . $sCronFile));
		}
		else {
			$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('There is no cron job report for the shop', 'admin-display_class') . ': ' . $aAssign['sShopName'], 'code' => 180) ;
		}

		// force xhr mode activated
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_CRON_REPORT,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * displayVouchers() method displays settings for vouchers
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayVouchers(array $aPost = null)
	{
		// get data for comment voucher
		$aAssign = $this->processVoucher('comment');

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_FB_VOUCHERS_SETTINGS,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * processVoucher() method process data to return data to display
	 *
	 * @param string $sVoucherType
	 * @return array
	 */
	private function processVoucher($sVoucherType)
	{
		// Prepare Categories tree for display in Associations tab
		$oCatRoot = Category::getRootCategory();

		// get vouchers data
		$aVouchers = BT_Voucher::create()->getSettings();

		// in order to get categories indexed or not per voucher type
		if (!empty($aVouchers[$sVoucherType])) {
			$aFormatCat = $aVouchers[$sVoucherType]['categories'];
		}
		else {
			$aFormatCat = array();
		}

		$sBoTheme = ((Validate::isLoadedObject(Context::getContext()->employee)
			&& !empty(Context::getContext()->employee->bo_theme)) ? Context::getContext()->employee->bo_theme : 'default');

		if (!file_exists(_PS_BO_ALL_THEMES_DIR_ . $sBoTheme . DIRECTORY_SEPARATOR . 'template')){
			$sBoTheme = 'default';
		}

		// add JS
		Context::getContext()->controller->addJS(_PS_BO_ALL_THEMES_DIR_ . $sBoTheme . '/js/tree.js');

		// stock the current controller in order to use products controller for category tree
		$oOldController = Context::getContext()->controller;

		// set products controller
		Context::getContext()->controller = new AdminProductsController();

		$oTree = new HelperTreeCategories('associated-categories-tree-' . $sVoucherType);

		$oTree->setTemplate('tree_associated_categories.tpl')
			->setHeaderTemplate('tree_associated_header.tpl')
			->setRootCategory($oCatRoot->id)
			->setUseCheckBox(true)
			->setUseSearch(false)
			->setSelectedCategories($aFormatCat)
		;

		// translate
		BT_Voucher::create()->translateVouchersType($sVoucherType);

		// set smarty variables
		$aAssign = array(
			'sVoucherType'  => $sVoucherType,
			'sType' . ucfirst($sVoucherType) => $sVoucherType,
			'sVoucherForm'  => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_FB_VOUCHERS_FORM),
			'aCurrencies'   => Currency::getCurrencies(),
			'aFormatCat' . ucfirst($sVoucherType) => $aFormatCat,
			'aVouchersType'     => $GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE'],
			'aEnableVouchers'   => BT_GsrModuleTools::getEnableVouchers(),
			'aVouchers'         => $aVouchers,
			'sCategoryTree' . ucfirst($sVoucherType) => $oTree->render(),
		);

		// set again the current controller
		Context::getContext()->controller = $oOldController;

		unset($oCatRoot);
		unset($oTree);
		unset($oOldController);

		// get all active languages in order to loop on field form which need to manage translation
		$aAssign['aLangs'] = Language::getLanguages();

		return $aAssign;
	}

	/**
	 * displayFacebookReviews() method displays FB reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayFacebookReviews(array $aPost = null)
	{
		// set smarty variables
		$aAssign = array(
			'bEnableFbPost' 	=> GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_FB_POST'],
			'bFbPsWallPosts' 	=> BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS']),
		);

		$aAssign['bFbPsWallPosts'] = true;

		// use case - detect if FB post sentence / label are filled
		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_PHRASE'])
			&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_LABEL'])
		) {
			$aAssign['aFbPostPhrase'] = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_PHRASE']);
			$aAssign['aFbPostLabel'] = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_LABEL']);
		}
		else {
			foreach ($GLOBALS[_GSR_MODULE_NAME . '_FB_DEFAULT_TRANSLATE'] as $sIsoCode => $aTranslation) {
				$iLangId = BT_GsrModuleTools::getLangId($sIsoCode);

				if ($iLangId) {
					// get Id by iso
					$aAssign['aFbPostPhrase'][$iLangId] = $aTranslation['sentence'];
					$aAssign['aFbPostLabel'][$iLangId] = $aTranslation['label'];
				}
			}
		}

		// get all active languages in order to loop on field form which need to manage translation
		$aAssign['aLangs'] = Language::getLanguages();

		foreach ($aAssign['aLangs'] as $aLang) {
			if (!isset($aAssign['aFbPostPhrase'][$aLang['id_lang']])) {
				$aAssign['aFbPostPhrase'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_FB_DEFAULT_TRANSLATE']['en']['sentence'];
			}
			if (!isset($aAssign['aFbPostLabel'][$aLang['id_lang']])) {
				$aAssign['aFbPostLabel'][$aLang['id_lang']] = $GLOBALS[_GSR_MODULE_NAME . '_FB_DEFAULT_TRANSLATE']['en']['label'];
			}
		}

		// get FB voucher data for Fb sharing
		$aAssign = array_merge($aAssign, $this->processVoucher('share'));

		$aAssign['bEnableSocialButton'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON'];
		$aAssign['bShareVoucher'] = !empty($aAssign['aEnableVouchers']['share'])? true : false;

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_FB_REVIEWS_SETTINGS,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * displayCommentsImport() method display comments import content
	 *
	 * @return string
	 */
	private function displayCommentsImport()
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			//get reviews from "comments product" module
			$aCountReviews = BT_GsrModuleDao::getModuleProductComments(false, 2, _GSR_MOCK_IMPORT_DEBUG);

			if (!empty($aCountReviews)) {
				$aValidReviews = array();
				$aInvalidReviews = array();

				foreach ($aCountReviews as $aReview) {
					if (isset($aReview['validate']) && $aReview['validate'] == true) {
						$aValidReviews[] = $aReview;
					}
					else {
						$aInvalidReviews[] = $aReview;
					}
				}

				$aAssign['bCommentsImport']     = true;
				$aAssign['iCommentsImportType'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_IMPORT_TYPE'];
				$aAssign['iMaxRating']          = _GSR_MAX_RATING;
				$aAssign['iTotalReviews']       = count($aCountReviews);
				$aAssign['aValidReviews']       = $aValidReviews;
				$aAssign['iTotalValidReviews']  = count($aValidReviews);
				$aAssign['aInvalidReviews']     = $aInvalidReviews;
				$aAssign['iTotalInvalidReviews']= count($aInvalidReviews);
				$aAssign['sLoadingImg']         = _GSR_URL_IMG . _GSR_LOADER_GIF;
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// force xhr mode activated
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_REVIEWS_IMPORT,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * displayOrdersSelect() method display orders import content
	 *
	 * @return string
	 */
	private function displayOrdersSelect()
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			// use case - check enable email
			$sDateFrom = Tools::getValue('dateFrom');
			$sDateTo = Tools::getValue('dateTo');

			if (!empty($sDateFrom)) {
				$iImportDateFrom = BT_GsrModuleTools::getTimeStamp($sDateFrom, 'db');
				// check if the date_to is set
				if (!empty($sDateTo)) {
					$iImportDateTo = BT_GsrModuleTools::getTimeStamp($sDateTo, 'db');
				}
				else {
					$iImportDateTo = time();
					$sDateTo = date('Y-m-d H:i:s', $iImportDateTo);
				}

				if ($iImportDateFrom < $iImportDateTo) {
					// get order status selection
					$aAssign['aStatusSelection'] = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION']) : GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'];

					// include
					require_once(_GSR_PATH_LIB . 'module-dao_class.php');

					// count orders of the current shop
					$aOrdersDetail = array('ok' => array(), 'ko' => array());

					$iAlreadySent = 0;

					// get orders from the selected period
					$aOrders = BT_GsrModuleDao::getOrdersIdByDate($sDateFrom, $sDateTo);
					foreach ($aOrders as $iOrder) {
						$oOrder = new Order($iOrder);

						// use case - not valid order
						if (Validate::isLoadedObject($oOrder)) {
							// use case - we exclude all the orders placed on a different shop
							if ($oOrder->id_shop == GSnippetsReviews::$iShopId) {
								// instantiate the customer obj to see if we'll send an email or not
								$oCustomer = new Customer($oOrder->id_customer);

								// use case - not a real customer
								if (Validate::isLoadedObject($oCustomer)) {
									if (!empty($oCustomer->active)) {
										if (in_array($oOrder->current_state, $aAssign['aStatusSelection'])) {
											$aOrdersDetail['ok'][$iOrder] = array(
												'id' => $iOrder,
												'ref' => (!empty($oOrder->reference)? 'Ref: '.$oOrder->reference : '') . ' / ID: '.$iOrder,
												'date' => $oOrder->date_add,
												'customer' => $oCustomer->firstname .  ' ' . $oCustomer->lastname . ' ('.$oCustomer->email.')',
												'state' => 'valid',
											);
											// detect if the reminder has already been sent and how many times
											$aOrderSent = BT_GsrModuleDao::getCallbackDetails($oOrder->id_shop, $oOrder->id);

											if (!empty($aOrderSent)) {
												$iAlreadySent++;
												$aOrdersDetail['ok'][$iOrder]['sent'] = $aOrderSent['count'];
												$aOrdersDetail['ok'][$iOrder]['date_last'] = $aOrderSent['date_last'];
											}
											unset($aOrderSent);
										}
										else {
											$aOrdersDetail['ko'][$iOrder] = array(
												'id' => $iOrder,
												'ref' => (!empty($oOrder->reference)? 'Ref: '.$oOrder->reference : '') . ' / ID: '.$iOrder,
												'date' => $oOrder->date_add,
												'customer' => $oCustomer->firstname .  ' ' . $oCustomer->lastname . ' ('.$oCustomer->email.')',
												'state' => 'not_order_status',
											);
										}
									}
									else {
										$aOrdersDetail['ko'][$iOrder] = array(
											'id' => $iOrder,
											'ref' => (!empty($oOrder->reference)? 'Ref: '.$oOrder->reference : '') . ' / ID: '.$iOrder,
											'date' => $oOrder->date_add,
											'customer' => $oCustomer->firstname .  ' ' . $oCustomer->lastname . ' ('.$oCustomer->email.')',
											'state' => 'not_active_customer',
										);
									}
								}
								else {
									$aOrdersDetail['ko'][$iOrder] = array(
										'id' => $iOrder,
										'state' => 'not_customer',
									);
								}
							}
							else {
								$aOrdersDetail['ko'][$iOrder] = array(
									'id' => $iOrder,
									'state' => 'not_order_shop',
								);
							}
						}
						else {
							$aOrdersDetail['ko'][$iOrder] = array(
								'id' => $iOrder,
								'state' => 'not_order',
							);
						}
						unset($oOrder);
					}
					$aAssign['aOrdersDetail'] = $aOrdersDetail;
					$aAssign['iNbOrders'] = count($aOrdersDetail['ok']) + count($aOrdersDetail['ko']);
					$aAssign['iNbOrdersToSend'] = count($aOrdersDetail['ok']);
					$aAssign['iOrdersSent'] = $iAlreadySent;
					$aAssign['aOrderStatusTitle'] = BT_GsrModuleDao::getOrderStatus();
					$aAssign['sDateFrom'] = $sDateFrom;
					$aAssign['sDateTo'] = $sDateTo;
					$aAssign['sShopName'] = Configuration::get('PS_SHOP_NAME');

					unset($aOrdersDetail);
					unset($aOrders);
				}
				else {
					throw new Exception(GSnippetsReviews::$oModule->l('The orders selection date start should be set as previous date from the date end', 'admin-update_class'), 180);
				}
			}
			else {
				throw new Exception(GSnippetsReviews::$oModule->l('The orders selection date start is not valid', 'admin-update_class'), 181);
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// force xhr mode activated
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_ORDERS_IMPORT,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * getDefaultTranslations() method returns the matching requested translations
	 *
	 * @param string $sSerializedVar
	 * @param string $sGlobalVar
	 * @param string $sAssignVar
	 * @return array
	 */
	private function getDefaultTranslations($sSerializedVar, $sGlobalVar)
	{
		$aTranslations = array();

		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_' . strtoupper($sSerializedVar)])) {
			$aTranslations = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_' . strtoupper($sSerializedVar)]);
		}
		else {
			foreach ($GLOBALS[_GSR_MODULE_NAME . '_' . strtoupper($sGlobalVar)] as $sIsoCode => $sTranslation) {
				$iLangId = BT_GsrModuleTools::getLangId($sIsoCode);

				if ($iLangId) {
					// get Id by iso
					$aTranslations[$iLangId] = $sTranslation;
				}
			}
		}

		return $aTranslations;
	}


	/**
	 * getFlagIds() method returns ids used for Prestashop flags displaying
	 *
	 * @return string
	 */
	private function getFlagIds()
	{
		// set
		$sFlagIds = '';

		if (!empty($this->aFlagIds)) {
			// loop on each ids
			foreach ($this->aFlagIds as $sId) {
				$sFlagIds .= $sId . '¤';
			}

			$sFlagIds = substr($sFlagIds, 0, (strlen($sFlagIds) - 2));
		}

		return $sFlagIds;
	}

	/**
	 * setFlagIds() method sets ids used for Prestashop flags displaying
	 */
	private function setFlagIds()
	{
		// set
		$sFlagIds = '';

		$this->aFlagIds = array(
			strtolower(_GSR_MODULE_NAME) . 'EmailTitle',
			strtolower(_GSR_MODULE_NAME) . 'ReviewsEmailTitle',
			strtolower(_GSR_MODULE_NAME) . 'FBPostPhrase',
		);

		// in order to get categories indexed or not by voucher type
		foreach ($GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE'] as $sType => $aVoucher) {
			if ($aVoucher['active']) {
				$this->aFlagIds[] = strtolower(_GSR_MODULE_NAME) . 'VoucherDesc' . $sType;
			}
		}
	}

	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oDisplay;

		if (null === $oDisplay) {
			$oDisplay = new BT_AdminDisplay();
		}
		return $oDisplay;
	}
}