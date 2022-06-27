<?php
/**
 * review-tool-display_class.php file defines method to display content tabs of admin page
 */

class BT_ReviewDisplay implements BT_IAdmin
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

		// get type
		if (empty($sType)) {
			$sType = 'tabs';
		}

		switch ($sType) {
			case 'tabs' :       // use case - display first page with all tabs
			case 'moderation' : // use case - display moderation tool
			case 'reviewForm' : // use case - display review form
			case 'replyForm' : // use case - display review form
			case 'reviewAdd' :  // use case - display add review tool
			case 'status' :     // use case - display status update
			case 'import' :     // use case - display reviews import tool
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
		// set smarty variables
		$aAssign = array(
			'sURI' 			    => BT_GsrModuleTools::truncateUri(array('&iPage', '&sAction', '&sType', '&' . _GSR_PARAM_CTRL_NAME)),
			'sController' 	    => _GSR_ADMIN_REVIEW_CTRL,
			'sCtrlParamName' 	=> _GSR_PARAM_CTRL_NAME,
			'aQueryParams' 	    => $GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'],
			'iDefaultLang'      => Configuration::get('PS_DEFAULT_LANG'),
			'iCurrentLang' 	    => intval(GSnippetsReviews::$iCurrentLang),
			'sCurrentLang' 	    => GSnippetsReviews::$sCurrentLang,
			'aLangs'            => Language::getLanguages(),
			'sTs'				=> time(),
			'sHeaderInclude'    => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_HEADER),
			'sErrorInclude'     => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR),
			'sConfirmInclude'   => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_CONFIRM),
			'sReviewConfirmInclude' => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_CONFIRM),
			'sRatingJs'         => Media::getMediaPath(_GSR_URL_JS . _GSR_JQUERY_RATING_NAME . '.min.js'),
			'sRatingCss'        => Media::getMediaPath(_GSR_URL_CSS . _GSR_JQUERY_RATING_NAME . '.css'),
			'sLoader'           => _GSR_URL_IMG . _GSR_LOADER_GIF,
			'sLoaderLarge'      => _GSR_URL_IMG . _GSR_LOADER_GIF_BIG,
		);

		return $aAssign;
	}

	/**
	 * displayTabs() method displays admin's first page with all tabs
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayTabs(array $aPost)
	{
		// set smarty variables
		$aAssign = array(
			'sDocUri'           => _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/',
			'sDocName'          => 'readme_' . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr' : 'en') . '.pdf',
			'sContactUs'        =>  _GSR_SUPPORT_BT ?  _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/contactez-nous' : 'en/contact-us') :  _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/ecrire-au-developpeur?id_product=' . _GSR_SUPPORT_ID  : 'en/write-to-developper?id_product=' ._GSR_SUPPORT_ID),
			'sRateUrl'          =>  _GSR_SUPPORT_BT ? _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? 'fr/modules-prestashop-reseaux-sociaux-facebook/50-module-prestashop-publicites-de-produits-facebook-pixel-facebook-0656272916497.html' : 'en/prestashop-modules-social-networks-facebook/50-prestashop-addon-facebook-product-ads-facebook-pixel-0656272916497.html') : _GSR_SUPPORT_URL . ((GSnippetsReviews::$sCurrentLang == 'fr')? '/fr/ratings.php'  : '/en/ratings.php'),
			'sCurrentIso'       => Language::getIsoById(GSnippetsReviews::$iCurrentLang),
			'bHideConfiguration'=> BT_GsrWarning::create()->bStopExecution,
			'sModuleVersion'    => GSnippetsReviews::$oModule->version,
		);

		// use case - get display moderation
		$aData = $this->displayModeration($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);

		// use case - get display add review tool
		$aData = $this->displayReviewAdd($aPost);

		$aAssign = array_merge($aAssign, $aData['assign']);
//
//		// use case - get display tool to import a list of external reviews
//		$aData = $this->displayReviewsImport($aPost);
//
//		$aAssign = array_merge($aAssign, $aData['assign']);

		// assign all included templates files
		$aAssign['sModerationInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_MODERATION);
		$aAssign['sReviewAddInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_ADD);
		$aAssign['sReviewsImportInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEWS_IMPORT);

		// set css and js use
		$GLOBALS[_GSR_MODULE_NAME . '_USE_JS_CSS']['bUseJqueryUI'] = true;

		return (
			array(
				'tpl'		=> _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_BODY,
				'assign'	=> array_merge($aAssign, $GLOBALS[_GSR_MODULE_NAME . '_USE_JS_CSS']),
			)
		);
	}


	/**
	 * displayModeration() method displays reviews list for moderation
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayModeration(array $aPost)
	{
		// use case - called in XHR mode
		if (GSnippetsReviews::$sQueryMode == 'xhr') {
			@ob_end_clean();
		}

		// set
		$aAssign = array();
		$bAllShop = false;

		// detect if all shop or not
		if (empty(Context::getContext()->cookie->shopContext)) {
			$bAllShop = true;
		}

		// include
		require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');
		require_once(_GSR_PATH_LIB_COMMON . 'pagination.class.php');

		// get page id
		$iCurrentPage = isset($aPost['iPage'])? intval($aPost['iPage']) : 1;

		// use case - reviews ratings
		$iCountRatings = BT_ReviewCtrl::create()->run('countRatings', array('active' => 2, 'allShop' => (!empty($bAllShop)? true : false)));

		// set pagination
		$aPagination = BT_Pagination::create()->run(array('total' => $iCountRatings, 'perPage' => GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_NB_REVIEWS_MODERATION']));

		// check pagination
		if (!isset($aPagination[$iCurrentPage]['begin'])) {
			$iCurrentPage = 1;
		}

		// set params for getting ratings and reviews
		$aRatingParams = array(
			'active'    => 2,
			'product'   => true,
			'customer'  => true,
			'shop'      => true,
			'date'      => 'Y-m-d H:i:s',
			'interval'  => $aPagination[$iCurrentPage]['begin'] . ',' . $aPagination[$iCurrentPage]['nb'],
		);

		// use case - one shop
		if (empty($bAllShop)) {
			$aRatingParams['shopId'] = GSnippetsReviews::$iShopId;
		}

		// use case - check if sortable review
		if (Tools::getIsset('sSort')
			&& Tools::getIsset('sWay')
			&& in_array(Tools::getValue('sSort'), $GLOBALS[_GSR_MODULE_NAME . '_SORTABLE_FIELDS'])
			&& in_array(Tools::getValue('sWay'), array('asc', 'desc'))
		) {
			$aAssign['sSortableField']  = Tools::getValue('sSort');
			$aAssign['sWay']            = Tools::getValue('sWay');
			
			switch ($aAssign['sSortableField']) {
				case 'shop' :
					$aRatingParams['orderBy'] = 'shopId';
					break;
				case 'id' :
					$aRatingParams['orderBy'] = 'id';
					break;
				case 'customer' :
					$aRatingParams['orderBy'] = 'lastname';
					break;
				case 'product' :
					$aRatingParams['orderBy'] = 'name';
					break;
				case 'dateAdd' :
					$aRatingParams['orderBy'] = 'dateAdd';
					break;
				case 'ranking' :
					$aRatingParams['orderBy'] = 'note';
					break;
				default:
					break;
			}
			if (!empty($aRatingParams['orderBy'])) {
				$aRatingParams['orderBy'] .= ' ' . strtoupper($aAssign['sWay']);
			}
		}
		else {
			$aAssign['sWay'] = 'asc';
			$aRatingParams['orderBy'] = 'dateAdd DESC';
		}

		// set different Id for reply ID
		BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as replyId');

		$aRatingParams['table'] = array(
			array(
				'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
				'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
			),
		);

		// get reviews for moderation
		$aRatings = BT_ReviewCtrl::create()->run('getRatings', $aRatingParams);

		if (count($aRatings)) {
			// set params for getting reviews
			$aReviewParams = array(
				'active'    => 2,
				'report'    => true,
				'date'      => 'Y/m/d',
			);

			foreach ($aRatings as &$aRating) {
				// unserialize data
				BT_ReviewCtrl::create()->getClass('reply')->unserialize('replyData', $aRating);

				// get admin customer's link
				$oLink = new Link();

				// get customer's back-office link
				$aRating['customerLink'] = $oLink->getAdminLink('AdminCustomers') . '&id_customer=' . $aRating['custId'] . '&viewcustomer';

				unset($oLink);

				// get average
				$aAverage = BT_ReviewCtrl::create()->run('average', array('iProductId' => $aRating['prodId']));

				if (!empty($aAverage)) {
					$aRating['bHalfStar']        = !empty($aAverage['bHalf'])? true : false;
					$aRating['iMaxRating']       = !empty($aAverage['bHalf'])? (_GSR_MAX_RATING * 2) : _GSR_MAX_RATING;
					$aRating['iAverage']         = $aAverage['iAverage'];
				}
				// destruct
				unset($aAverage);

				// get shop name on which one the rating has been done
				$aRating['shopName'] = !empty($aRating['shopName'])? $aRating['shopName'] . ' (ID: ' . $aRating['shopId'] . ')' : '';

				// format date
				$aRating['dateAddUnix'] = $aRating['dateAdd'];
				$aRating['dateAdd'] = date('Y/m/d', $aRating['dateAdd']);

				// get language name
				$oLang = new Language($aRating['langId']);
				$aRating['langTitle'] = $oLang->name;
				unset($oLang);

				// set rating ID as param
				$aReviewParams['ratingId'] = $aRating['id'];

				// get related review to each rating
				$aRating['review'] = BT_Review::create()->get($aReviewParams);

				if (!empty($aRating['review'])) {
					// set review URL in standalone mode
					$aRating['review']['sReviewUrl']  = Context::getContext()->link->getModuleLink(
						_GSR_MODULE_SET_NAME,
						_GSR_FRONT_CTRL_REVIEW,
						array (
							'iRId' => $aRating['id'],
							'iPId' => $aRating['review']['productId'],
						),
						null,
						$aRating['review']['langId']
					);
					// format comment
					$aRating['review']['data']['sComment'] = str_replace("\n", "<br/>", trim($aRating['review']['data']['sComment']));

					if (!empty($aRating['review']['data']['sOldComment'])) {
						$aRating['review']['data']['sOldComment'] = str_replace("\n", "<br/>", trim($aRating['review']['data']['sOldComment']));
					}
				}

				// PHP sort
				if (!empty($aAssign['sSortableField'])) {
					// php sort with status
					if ($aAssign['sSortableField'] == 'status') {
						if (!empty($aRating['review'])) {
							if (!empty($aRating['review']['status'])) {
							    $aRatingsTmp['ok'][$aRating['dateAddUnix']] = $aRating;
							}
							else {
								$aRatingsTmp['ko'][$aRating['dateAddUnix']] = $aRating;
							}
						}
						else {
							$aRatingsTmp['ok'][$aRating['dateAddUnix']] = $aRating;
						}
					}
					// php sort with report status
					elseif ($aAssign['sSortableField'] == 'abuse') {
						if (!empty($aRating['review'])) {
							if (!empty($aRating['review']['reportId'])) {
								$aRatingsTmp['ko'][$aRating['review']['ratingId']] = $aRating;
							}
							else {
								$aRatingsTmp['ok'][$aRating['review']['ratingId']] = $aRating;
							}
						}
						else {
							$aRatingsTmp['ok'][$aRating['id']] = $aRating;
						}
					}
				}
			}

			// test if we need to sort in php
			if (!empty($aRatingsTmp)) {
				// clear the current array
				$aRatings = array();

				if (!empty($aRatingsTmp['ok'])
					&& !empty($aRatingsTmp['ko'])
				) {
					krsort($aRatingsTmp['ok']);
					krsort($aRatingsTmp['ko']);

					if ($aAssign['sWay'] == 'asc') {
						$aRatingsTmp = array_merge($aRatingsTmp['ko'],$aRatingsTmp['ok']);
					}
					else {
						$aRatingsTmp = array_merge($aRatingsTmp['ok'], $aRatingsTmp['ko']);
					}
				}
				elseif (!empty($aRatingsTmp['ok'])) {
					krsort($aRatingsTmp['ok']);
					$aRatingsTmp = array_merge($aRatingsTmp['ok']);
				}
				elseif (!empty($aRatingsTmp['ko'])) {
					krsort($aRatingsTmp['ko']);
					$aRatingsTmp = array_merge($aRatingsTmp['ko']);
				}

				foreach ($aRatingsTmp as $aRating) {
					$aRatings[] = $aRating;
				}
				unset($aRatingsTmp);
			}
		}

		// set smarty variables
		$aAssign = array_merge($aAssign, array(
			'sBASE_URI'         => preg_replace('/\?[[a-zA-Z0-9=]{1,}/', '', $_SERVER['REQUEST_URI']),
			'sMODULE_URI'       => _GSR_MODULE_URL . 'ws-' . _GSR_MODULE_SET_NAME . '.php',
			'aRatings'          => ((!isset($aRatings[0]) && !empty($aRatings))?array($aRatings):$aRatings),
			'bAddCssModule'     => true,
			'iMaxRating'        => _GSR_MAX_RATING,
			'aPagination'       => $aPagination,
			'iCurrentPage'      => $iCurrentPage,
			'iTotalPage'        => count($aPagination),
			'bUseJqueryUI'      => true,
			'bUseJqueryFancy'   => true,
			'sMode'             => GSnippetsReviews::$sQueryMode,
			'sShopName'         => Configuration::get('PS_SHOP_NAME'),
			'bAllShop'          => $bAllShop,
			'bMultiShop'        => Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE'),
			'iNbReview'         => count($aRatings),
			'sRatingClassName'  => substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'])),
			'aParamStars'       => array(
				'star' => _GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
			),
			'sReviewStatusInclude' => BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_STATUS),
		));

		unset($aRatings);
		unset($aPagination);
		unset($iCurrentPage);

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_MODERATION,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * displayReviewAdd() method displays review add mode
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayReviewAdd(array $aPost = null)
	{
		// use case - called in XHR mode
		if (GSnippetsReviews::$sQueryMode == 'xhr') {
			@ob_end_clean();
		}

		// activate autocomplete plugin
		$aJsCss = Media::getJqueryPluginPath('autocomplete');

		if (is_array($aJsCss['css'])) {
			$aJsCss['css'] = key($aJsCss['css']);
		}

		// add JS
		$aAssign = array(
			'sAutocompleteCss' => Media::getMediaPath($aJsCss['css']),
			'sAutocompleteJs' => Media::getMediaPath($aJsCss['js']),
			'iMaxRating' => _GSR_MAX_RATING,
			'aParamStars' => array(
				'star'  =>_GSR_URL_IMG . 'picto/' . GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'] . '/' . _GSR_PICTO_NAME,
			),
			'aLangs' => Language::getLanguages(),
			'aShops' => Shop::getShops(),
			'iCurrentShopId' => GSnippetsReviews::$iShopId,
		);

		// stock the current controller in order to use products controller for category tree
		$oOldController = Context::getContext()->controller;

		// set products controller
		Context::getContext()->controller = new AdminCartRulesController();

		$aAssign['sCurrentToken'] = Tools::getAdminToken(Context::getContext()->controller->controller_name.(int)Tab::getIdFromClassName(Context::getContext()->controller->controller_name).(int)Context::getContext()->employee->id);

		// set again the current controller
		Context::getContext()->controller = $oOldController;

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_ADD,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * displayStatus() method displays status of one review
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayStatus(array $aPost = null)
	{
		// set smarty variables
		$aAssign = array(
			'bStatus' => (isset($aPost['bStatus'])? $aPost['bStatus']:true),
		);

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_STATUS,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * displayReviewForm() method displays review form for updating
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayReviewForm(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		$aAssign = array();

		try {
			// use case - edition review mode - check review Id
			if (!isset($aPost['iReviewId'])
				|| (isset($aPost['iReviewId']) && !is_numeric($aPost['iReviewId']))
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Review ID is not valid', 'review-display_class') . '.', 120);
			}
			// use case - check page number
			if (!isset($aPost['iPage'])
				|| (isset($aPost['iPage']) && !is_numeric($aPost['iPage']))
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Page number is not valid', 'review-display_class') . '.', 121);
			}
			// get page number
			$aAssign['iPage'] = $aPost['iPage'];

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// get current review
			$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $aPost['iReviewId'], 'active' => 2, 'reply' => true, 'customer' => true));

			if (!empty($aReview)) {
				// get language name
				$oLang = new Language($aReview['langId']);
				$aReview['langTitle'] = $oLang->name;
				unset($oLang);

				// assign review
				$aAssign['aReview'] = $aReview;
			}
			else {
				throw new Exception(GSnippetsReviews::$oModule->l('Review is not loaded', 'review-display_class') . '.', 122);
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// set smarty variables
		$aAssign['bStatus'] = (!empty($aPost['bStatus'])?$aPost['bStatus']:true);

		// force xhr mode activated
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_FORM,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * displayReplyForm() method displays reply form for after-sales
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function displayReplyForm(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		$aAssign = array();

		try {
			// use case - edition review mode - check rating Id
			if (!isset($aPost['iRatingId'])
				|| (isset($aPost['iRatingId']) && !is_numeric($aPost['iRatingId']))
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Rating ID is not valid', 'review-display_class') . '.', 130);
			}
			// use case - check page number
			if (!isset($aPost['iPage'])
				|| (isset($aPost['iPage']) && !is_numeric($aPost['iPage']))
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Page number is not valid', 'review-display_class') . '.', 131);
			}
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// set different Id for reply ID
			BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as replyId');

			// get current rating & review
			$aParams = array(
				'id' => $aPost['iRatingId'],
				'reply' => true,
				'customer' => true,
				'bAssociativeArray' => true,
				'table' => array(
					array(
						'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
						'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
					)
				)
			);
			$aRating = BT_ReviewCtrl::create()->run('getRatings', $aParams);

			if (!empty($aRating)) {
				// use case - detect if after-sales reply notification text has been filled
				if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_TEXT'])) {
					$aAssign['aReplyEmailText'] = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_TEXT']);
				}
				else {
					$aAssign['aReplyEmailText'] = $GLOBALS[_GSR_MODULE_NAME . '_REPLY_TEXT_DEFAULT_TRANSLATE'];
				}

				foreach ($aAssign['aReplyEmailText'] as $mIsoCode => $sTranslation) {
					$iLangId = is_numeric($mIsoCode)? $mIsoCode : BT_GsrModuleTools::getLangId($mIsoCode);

					if ($iLangId == $aRating['langId']) {
						// get Id by iso
						$aAssign['sReplyEmailText'] = !empty($sTranslation)? $sTranslation : $GLOBALS[_GSR_MODULE_NAME . '_REPLY_TEXT_DEFAULT_TRANSLATE']['en'];
					}
				}

				// unserialize data
				BT_ReviewCtrl::create()->getClass('reply')->unserialize('replyData', $aRating);

				// get review
				$aRating['review'] = BT_ReviewCtrl::create()->run('getReviews', array('ratingId' => $aPost['iRatingId'], 'active' => 2));

				// get page number
				$aAssign['iPage'] = $aPost['iPage'];
				$aAssign['iRating'] = $aRating['note'];
				$aAssign['iMaxRating'] = _GSR_MAX_RATING;
				$aAssign['sRatingClassName'] = substr(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO'], 2, strlen(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_PICTO']));

				// new link
				$oLink = new Link();
				$aRating['customerLink'] = $oLink->getAdminLink('AdminCustomers') . '&id_customer=' . $aRating['custId'] . '&viewcustomer';
				unset($oLink);

				// get language name
				$oLang = new Language($aRating['langId']);
				$aRating['langTitle'] = $oLang->name;
				unset($oLang);

				// assign review
				$aAssign['aRating'] = $aRating;
			}
			else {
				throw new Exception(GSnippetsReviews::$oModule->l('Review is not loaded', 'review-display_class') . '.', 132);
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// force xhr mode activated
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REPLY_FORM,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * getFlagIds() method returns ids used for PrestaShop flags displaying
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
			$oDisplay = new BT_ReviewDisplay();
		}
		return $oDisplay;
	}
}