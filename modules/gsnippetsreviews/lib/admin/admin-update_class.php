<?php
/**
 * admin-update_class.php file defines method to add or update content for basic settings / FILL ALL update data type
 */

class BT_AdminUpdate implements BT_IAdmin
{
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
	 * run() method update all tabs content of admin page
	 *
	 * @param string $sType => define which method to execute
	 * @param array $aParam
	 * @return array
	 */
	public function run($sType, array $aParam = null)
	{
		// set variables
		$aDisplayInfo = array();

		switch ($sType) {
			case 'snippets'		: // use case - update snippets settings
			case 'reviews'		: // use case - update reviews settings
			case 'email'		: // use case - update reviews settings
			case 'orders'		: // use case - update orders
			case 'facebookReviews'		: // use case - update reviews settings
			case 'vouchers'		: // use case - update reviews settings
			case 'comments'		: // use case - import reviews from product comments module
				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'update' . ucfirst($sType)), array($aParam));
				break;
			default :
				break;
		}
		return (
			$aDisplayInfo
		);
	}

	/**
	 * updateSnippets() method update snippets settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateSnippets(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check activate product rich snippets
			if (Tools::getIsset('bt_display-rich-snippets-prod')) {
				$bDisplayProductRichSnippets = (Tools::getValue('bt_display-rich-snippets-prod') == true)? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_RS', $bDisplayProductRichSnippets)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate product rich snippets update', 'admin-update_class') . '.', 150);
				}
				// use case - check only if activated
				if ($bDisplayProductRichSnippets) {
					$bDisplayDesc = (Tools::getIsset('bt_display-desc') && Tools::getValue('bt_display-desc') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_DESC', $bDisplayDesc)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate product desc update', 'admin-update_class') . '.', 151);
					}
					// use case - update desc sort
					if ($bDisplayDesc && Tools::getIsset('bt_desc_list')) {
						$aDesc = explode(',', $aPost['bt_desc_list']);

						if (count($aDesc) != count($GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC'])) {
							throw new Exception(GSnippetsReviews::$oModule->l('Number of description type not valid', 'admin-update_class') . '.', 152);
						}
						else {
							// use case - check if valid type
							foreach ($aDesc as $sDesc) {
								if (!array_key_exists($sDesc, $GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC'])) {
									throw new Exception('"' . $sDesc . '" ' . GSnippetsReviews::$oModule->l('not a valid type', 'admin-update_class') . '.', 153);
								}
							}
							if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SORT_DESC', serialize($aDesc))) {
								throw new Exception(GSnippetsReviews::$oModule->l('An error occured during sort desc update', 'admin-update_class') . '.', 154);
							}
						}
					}
					unset($bDisplayDesc);

					$bDisplayBrand = (Tools::getIsset('bt_display-brand') && Tools::getValue('bt_display-brand') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_BRAND', $bDisplayBrand)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display brand update', 'admin-update_class') . '.', 155);
					}
					unset($bDisplayBrand);

					$bDisplayCat = (Tools::getIsset('bt_display-category') && Tools::getValue('bt_display-category') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_CAT', $bDisplayCat)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display category update', 'admin-update_class') . '.', 156);
					}
					unset($bDisplayCat);

					$bDisplayBreadcrumb = (Tools::getIsset('bt_display-breadcrumb') && Tools::getValue('bt_display-breadcrumb') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_BREADCRUMB', $bDisplayBreadcrumb)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display breadcrumb update', 'admin-update_class') . '.', 157);
					}
					unset($bDisplayBreadcrumb);

					$bDisplayIdentifier = (Tools::getIsset('bt_display-identifier') && Tools::getValue('bt_display-identifier') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_IDENTIFIER', $bDisplayIdentifier)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display identifier update', 'admin-update_class') . '.', 158);
					}
					unset($bDisplayIdentifier);

					$bDisplaySupplier = (Tools::getIsset('bt_display-supplier') && Tools::getValue('bt_display-supplier') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_SUPPLIER', $bDisplaySupplier)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display supplier update', 'admin-update_class') . '.', 159);
					}
					unset($bDisplaySupplier);

					$bDisplayCondition = (Tools::getIsset('bt_display-condition') && Tools::getValue('bt_display-condition') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_COND', $bDisplayCondition)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display condition update', 'admin-update_class') . '.', 160);
					}
					unset($bDisplayCondition);

					// use case - update offer type
					if (Tools::getIsset('bt_offers')) {
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_PRODUCT_OFFERS', Tools::getValue('bt_offers'))) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during offer type update', 'admin-update_class') . '.', 162);
						}
					}

					$bDisplaySeller = (Tools::getIsset('bt_display-seller') && Tools::getValue('bt_display-seller') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_SELLER', $bDisplaySeller)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display seller update', 'admin-update_class') . '.', 163);
					}
					unset($bDisplaySeller);

					$bDisplayUntilDate = (Tools::getIsset('bt_display-until-date') && Tools::getValue('bt_display-until-date') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_UNTIL_DATE', $bDisplayUntilDate)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display until date update', 'admin-update_class') . '.', 164);
					}
					unset($bDisplayUntilDate);

					$bDisplayAvaibility = (Tools::getIsset('bt_display-avaibility') && Tools::getValue('bt_display-avaibility') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_AVAILABILITY', $bDisplayAvaibility)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display until date update', 'admin-update_class') . '.', 165);
					}
					unset($bDisplayAvaibility);

					$bDisplayHighestPrice = (Tools::getIsset('bt_display-highest-price') && Tools::getValue('bt_display-highest-price') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_HIGH_PRICE', $bDisplayHighestPrice)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display highest price update', 'admin-update_class') . '.', 167);
					}
					unset($bDisplayHighestPrice);

					$bDisplayOfferCount = (Tools::getIsset('bt_display-offer-count') && Tools::getValue('bt_display-offer-count') == true)? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PROD_OFFER_COUNT', $bDisplayOfferCount)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate display offer count update', 'admin-update_class') . '.', 168);
					}
					unset($bDisplayOfferCount);
				}
			}

			// use case - check display badge
			if (Tools::getIsset('bt_display-badge')) {
				$bDisplayBadge = (Tools::getValue('bt_display-badge') == true) ? true : false;

				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_BADGE', $bDisplayBadge)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during badge display update', 'admin-update_class') . '.', 169);
				}

				// chosen badge page if displayed
				if ($bDisplayBadge) {
					// set variable
					$aBadgeOptions = array();

					// loop on each badge page type
					foreach ($GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES'] as $sBadgeType => $aOptions) {
						if (!empty($aPost['bt_select-badge'][$sBadgeType])) {
							$aBadgeOptions[$sBadgeType]['display'] = true;

							// set position
							if (!empty($aPost['bt_badge-position'][$sBadgeType])) {
								$aBadgeOptions[$sBadgeType]['position'] = $aPost['bt_badge-position'][$sBadgeType];
							}

							// set custom
							if (!empty($aPost['bt_badge-freestyle'][$sBadgeType])) {
								$aBadgeOptions[$sBadgeType]['custom'] = $aPost['bt_badge-freestyle'][$sBadgeType];
							}
						}
					}

					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_BADGES', serialize($aBadgeOptions))) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during badges options update', 'admin-update_class') . '.', 170);
					}
				}
				unset($bDisplayBadge);
			}

			// use case - check review type
			if (Tools::getIsset('bt_review-type')) {
				$sReviewType = (Tools::getValue('bt_review-type') == 'aggregate') ? 'aggregate' : 'individual';
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_RVW_TYPE', $sReviewType)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during review type update', 'admin-update_class') . '.', 171);
				}
				unset($sReviewType);
			}
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'admin-display_class.php');

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_AdminDisplay::create()->run('snippets');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 1,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}

	/**
	 * updateReviews() method update reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateReviews(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check activate reviews and comments
			if (Tools::getIsset('bt_display-reviews')) {
				$bDisplayReviews = (Tools::getValue('bt_display-reviews') == true)? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_REVIEWS', $bDisplayReviews)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during activate reviews update', 'admin-update_class') . '.', 201);
				}
				unset($bDisplayReviews);
			}

			// use case - update authorized person
			if (Tools::getIsset('bt_authorize')) {
				if (!array_key_exists(Tools::getValue('bt_authorize'), $GLOBALS[_GSR_MODULE_NAME . '_AUTHORIZE'])) {
					throw new Exception(GSnippetsReviews::$oModule->l('Authorized person is not valid', 'admin-update_class') . '.', 202);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_COMMENTS_USER', Tools::getValue('bt_authorize'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during authorized person update', 'admin-update_class') . '.', 203);
				}
			}

			// use case - check admin approval
			if (Tools::getIsset('bt_admin')) {
				$bAdminApproval = Tools::getValue('bt_admin') == true ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_COMMENTS_APPROVAL', $bAdminApproval)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during admin approval update', 'admin-update_class') . '.', 204);
				}
				unset($bAdminApproval);
			}

			// use case - check count box button activate
			if (Tools::getIsset('bt_enable-social-button')) {
				$bEnableSocialButton = (Tools::getValue('bt_enable-social-button') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON', $bEnableSocialButton)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during social button update', 'admin-update_class') . '.', 205);
				}
				unset($bEnableSocialButton);
			}

			// use case - check count box button activate
			if (Tools::getIsset('bt_count-box-button')) {
				$bCountBoxButton = (Tools::getValue('bt_count-box-button') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_COUNT_BOX_BUTTON', $bCountBoxButton)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during count box button update', 'admin-update_class') . '.', 206);
				}
				unset($bAdminApproval);
			}

			// use case - check hooks
			if (Tools::getIsset('bt_hooks')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_HOOK', Tools::getValue('bt_hooks'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during hook update', 'admin-update_class') . '.', 207);
				}
			}
			// use case - check nb reviews per page for front and moderation
			if (Tools::getIsset('bt_nb-reviews')) {
				if (!in_array(Tools::getValue('bt_nb-reviews'), $GLOBALS[_GSR_MODULE_NAME . '_NB_REVIEWS_VALUES'])
					&& !in_array(Tools::getValue('bt_nb-reviews-moderation'), $GLOBALS[_GSR_MODULE_NAME . '_NB_REVIEWS_VALUES'])
				) {
					throw new Exception(GSnippetsReviews::$oModule->l('Nb Reviews for shop and moderation is not numeric', 'admin-update_class') . '.', 208);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_REVIEWS_PROD_PAGE', Tools::getValue('bt_nb-reviews'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb reviews update', 'admin-update_class') . '.', 209);
				}
			}
			if (Tools::getIsset('bt_nb-reviews-moderation')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_REVIEWS_MODERATION', Tools::getValue('bt_nb-reviews-moderation'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb reviews for moderation update', 'admin-update_class') . '.', 210);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_REVIEWS_PAGE', Tools::getValue('bt_nb-reviews-list-page'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb reviews list page update', 'admin-update_class') . '.', 211);
				}
			}

			// use case - check enable rating
			if (Tools::getIsset('bt_enable-ratings')) {
				$bEnableRatings = (Tools::getValue('bt_enable-ratings') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_RATINGS', $bEnableRatings)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable ratings update', 'admin-update_class') . '.', 212);
				}
				if (!$bEnableRatings) {
					$bEnableComments = false;
					$bForceComments = false;
				}
				else {
					// use case - check enable comments
					$bEnableComments = (Tools::getIsset('bt_enable-comments') && Tools::getValue('bt_enable-comments') == true)? true : false;
				}
				if (!$bEnableComments) {
					$bForceComments = false;
				}
				else {
					// use case - check force comments
					$bForceComments = (Tools::getIsset('bt_force-comments') && Tools::getValue('bt_force-comments') == true)? true : false;
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_COMMENTS', $bEnableComments)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable comments update', 'admin-update_class') . '.', 213);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_FORCE_COMMENTS', $bForceComments)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable force comments update', 'admin-update_class') . '.', 235);
				}
				unset($bForceComments);
				unset($bEnableComments);
				unset($bEnableRatings);
			}

			// use case - save chosen picto
			if (Tools::getIsset('bt_picto')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_PICTO', Tools::getValue('bt_picto'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during picto update', 'admin-update_class') . '.', 214);
				}
			}

			// use case - display reviews with customer language
			if (Tools::getIsset('bt_enable-cust-lang')) {
				$bEnableCustLang = (Tools::getValue('bt_enable-cust-lang') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG', $bEnableCustLang)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display reviews with customer language update', 'admin-update_class') . '.', 215);
				}
				unset($bEnableCustLang);
			}

			// use case - check image sizes
			if (Tools::getIsset('bt_review-prod-img')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_RVW_PROD_IMG', Tools::getValue('bt_review-prod-img'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during review product image size update', 'admin-update_class') . '.', 216);
				}
			}
			if (Tools::getIsset('bt_slider-prod-img')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SLIDER_PROD_IMG', Tools::getValue('bt_slider-prod-img'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during slider product image size update', 'admin-update_class') . '.', 217);
				}
			}

			// use case - check slider opts
			if (Tools::getIsset('bt_slider-width')
				&& Tools::getIsset('bt_slider-pause')
				&& Tools::getIsset('bt_slider-speed')
			) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SLIDER_WIDTH', Tools::getValue('bt_slider-width'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during slider width update', 'admin-update_class') . '.', 218);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SLIDER_PAUSE', Tools::getValue('bt_slider-pause'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during slider pause update', 'admin-update_class') . '.', 218);
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SLIDER_SPEED', Tools::getValue('bt_slider-speed'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during slider speed update', 'admin-update_class') . '.', 219);
				}
			}

			// use case - check reviews display mode
			if (Tools::getIsset('bt_reviews-display-mode') && array_key_exists(Tools::getValue('bt_reviews-display-mode'), $GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'])) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE', Tools::getValue('bt_reviews-display-mode'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during reviews display mode update', 'admin-update_class') . '.', 220);
				}
			}

			// use case - check display last reviews block
			if (Tools::getIsset('bt_display-last-reviews')) {
				$bDisplayLastBlock = (Tools::getValue('bt_display-last-reviews') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_LAST_RVW_BLOCK', $bDisplayLastBlock)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during last reviews display update', 'admin-update_class') . '.', 221);
				}
				if ($bDisplayLastBlock) {
					// use case - hooks of last reviews block
					if (Tools::getIsset('bt_last-review-hooks')) {
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_HOOK', $aPost['bt_last-review-hooks'])) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during hook update', 'admin-update_class') . '.', 222);
						}
					}
					// use case - nb of reviews displayed in the block
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_LAST_REVIEWS', Tools::getValue('bt_nb-last-reviews'))) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb last reviews update', 'admin-update_class') . '.', 223);
					}

					// set variable
					$aLastRvwBlockOptions = array();

					// chosen last rvw block page if displayed
					// loop on each last rvw block page type
					foreach ($GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES'] as $sPageType => $aOptions) {
						if (!empty($aPost['bt_select-block-pos'][$sPageType])) {
							$aLastRvwBlockOptions[$sPageType]['display'] = true;

							// set position
							if (!empty($aPost['bt_last-block-position'][$sPageType])) {
								$aLastRvwBlockOptions[$sPageType]['position'] = $aPost['bt_last-block-position'][$sPageType];
							}
							// set width
							if (!empty($aPost['bt_last-block-width'][$sPageType])) {
								$aLastRvwBlockOptions[$sPageType]['width'] = $aPost['bt_last-block-width'][$sPageType];
							}
							// set comment truncate
							if (!empty($aPost['bt_last-block-truncate'][$sPageType])) {
								$aLastRvwBlockOptions[$sPageType]['truncate'] = $aPost['bt_last-block-truncate'][$sPageType];
							}
						}
					}

					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_LAST_RVW_BLOCK', serialize($aLastRvwBlockOptions))) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during last review block options update', 'admin-update_class') . '.', 224);
					}

					// use case - update first position
					$bLastReviewsBlockFirst = (Tools::getIsset('bt_display-block-first') && Tools::getValue('bt_display-block-first') == true) ? true : false;
					if (!Configuration::updateValue(_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_FIRST', $bLastReviewsBlockFirst)) {
						throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during last reviews block first update', 'admin-update_class') . '.', 225);
					}
					unset($bLastReviewsBlockFirst);
				}
				unset($bDisplayLastBlock);
			}

			// use case - nb products to review in slider with account page
			if (Tools::getIsset('bt_nb-product-to-review')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_PROD_SLIDER', Tools::getValue('bt_nb-product-to-review'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb product in slider update', 'admin-update_class') . '.', 226);
				}
			}

			// use case - nb reviewed products displayed in table with account page
			if (Tools::getIsset('bt_nb-product-reviewed')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_NB_PROD_REVIEWED', Tools::getValue('bt_nb-product-reviewed'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during nb product in table update', 'admin-update_class') . '.', 227);
				}
			}

			// use case - check if display review stars with PS hook
			if (Tools::getIsset('bt_display-stars-in-list')) {
				$bDisplayStarsInList = (Tools::getValue('bt_display-stars-in-list') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_HOOK_REVIEW_STARS', $bDisplayStarsInList)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display review stars update', 'admin-update_class') . '.', 228);
				}
				unset($bDisplayStarsInList);
			}

			// use case - check if display report abuse button
			if (Tools::getIsset('bt_display-report-abuse')) {
				$bDisplayReportAbuse = (Tools::getValue('bt_display-report-abuse') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_REPORT_BUTTON', $bDisplayReportAbuse)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display report abuse button update', 'admin-update_class') . '.', 229);
				}
				unset($bDisplayReportAbuse);
			}

			// use case - check if display review product photo
			if (Tools::getIsset('bt_display-product-photo')) {
				$bDisplayProductPhoto = (Tools::getIsset('bt_display-product-photo') && Tools::getValue('bt_display-product-photo') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_PHOTO_REVIEWS', $bDisplayProductPhoto)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display review\'s product photo update', 'admin-update_class') . '.', 230);
				}
				if ($bDisplayProductPhoto) {
					// use case - check image sizes
					if (Tools::getIsset('bt_review-list-prod-img')) {
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_RVW_LIST_PROD_IMG', Tools::getValue('bt_review-list-prod-img'))) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during review list product image size update', 'admin-update_class') . '.', 231);
						}
					}
				}
				unset($bDisplayProductPhoto);
			}

			// use case - check if display rich snippets review in the product-list.tpl via the "displayProductListReview" hook
			if (Tools::getIsset('bt_use-snippets-prodlist')) {
				$bDisplaySnippetsInList = (Tools::getValue('bt_use-snippets-prodlist') == 1) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST', $bDisplaySnippetsInList)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display review snippets update', 'admin-update_class') . '.', 232);
				}
				unset($bDisplaySnippetsInList);
			}

			// use case - check if the theme usr rich snippets product in the product-list.tpl
			if (Tools::getIsset('bt_has-snippets-prodlist')) {
				$bHasSnippetsInList = (Tools::getValue('bt_has-snippets-prodlist') == 1) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_HAS_SNIPPETS_PRODLIST', $bHasSnippetsInList)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display review snippets update', 'admin-update_class') . '.', 233);
				}
				unset($bHasSnippetsInList);
			}

			// use case - check if we display empty stars by default in the product list
			if (Tools::getIsset('bt_display-empty-stars')) {
				$bDisplayEmptyStars = (Tools::getValue('bt_display-empty-stars') == 1) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISP_EMPTY_RATING', $bDisplayEmptyStars)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display empty stars update', 'admin-update_class') . '.', 234);
				}
				if ($bDisplayEmptyStars) {
					// use case - check if we display empty stars by default in the product list
					if (Tools::getIsset('bt_display-befirst-msg')) {
						$bDisplayBeFirstMsg = (Tools::getValue('bt_display-befirst-msg') == 1) ? true : false;

						if ($bDisplayBeFirstMsg) {
							// update multi-languages fields
							$this->updateLang($aPost, 'bt_befirst-text', 'BEFIRST_SENTENCE');
						}
					}
				}
				else {
					$bDisplayBeFirstMsg = false;
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISP_BEFIRST_MSG', $bDisplayBeFirstMsg)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display be first message update', 'admin-update_class') . '.', 235);
				}
				unset($bDisplayBeFirstMsg);
				unset($bDisplayEmptyStars);
			}

			// use case - check if we display stars only and / or numeric average and / or total of ratings
			if (Tools::getIsset('bt_stars-display-mode')) {
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISP_STAR_RATING_MODE', Tools::getValue('bt_stars-display-mode'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during stars displaying mode update', 'admin-update_class') . '.', 236);
				}
			}

			// use case - check fb button kind
			if (Tools::getIsset('bt_fb-button-type')) {
				$iFbSharer = Tools::getValue('bt_fb-button-type');
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_FB_BUTTON_TYPE', $iFbSharer)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during fb button kind update', 'admin-update_class') . '.', 237);
				}
				unset($iFbSharer);
			}

			// use case - check stars padding value
			if (Tools::getIsset('bt_div-stars-padding')) {
				$iStarsPadding = Tools::getValue('bt_div-stars-padding');
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_STARS_PADDING_LEFT', $iStarsPadding)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during stars padding-left update', 'admin-update_class') . '.', 238);
				}
				unset($iStarsPadding);
			}

			// use case - check stars size
			if (Tools::getIsset('bt_stars-size')) {
				$iStarsSize = Tools::getValue('bt_stars-size');
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_STARS_SIZE', $iStarsSize)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during stars size update', 'admin-update_class') . '.', 239);
				}
				unset($iStarsSize);
			}

			// use case - check text size
			if (Tools::getIsset('bt_text-size')) {
				$iTextSize = Tools::getValue('bt_text-size');
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_TEXT_SIZE', $iTextSize)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during text size update', 'admin-update_class') . '.', 240);
				}
				unset($iTextSize);
			}

			// use case - check if display address
			if (Tools::getIsset('bt_display-address')) {
				$bDisplayAddress = (Tools::getValue('bt_display-address') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_DISPLAY_ADDRESS', $bDisplayAddress)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during display address update', 'admin-update_class') . '.', 241);
				}
				unset($bDisplayAddress);
			}

			// use case - check if use the FontAwesome css file
			if (Tools::getIsset('bt_use-fontawesome')) {
				$bUseFontAwesome = (Tools::getValue('bt_use-fontawesome') == true) ? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_USE_FONTAWESOME', $bUseFontAwesome)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during use fontawesome update', 'admin-update_class') . '.', 242);
				}
				unset($bUseFontAwesome);
			}
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'admin-display_class.php');

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_AdminDisplay::create()->run('reviews');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 2,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}


	/**
	 * updateEmail() method update email reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateEmail(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check enable email
			if (Tools::getIsset('bt_enable-email')) {
				$bEnableEmail = (Tools::getValue('bt_enable-email') == true)? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_EMAIL', $bEnableEmail)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable email update', 'admin-update_class') . '.', 165);
				}

				// use case - enable email
				if ($bEnableEmail) {
					// use case - update email
					if (Tools::getIsset('bt_email')) {
						if (!preg_match_all('/^[a-zA-Z0-9-_.]{1,}@[a-zA-Z0-9-_.]{1,}.[a-z]{2,3}$/', Tools::getValue('bt_email'), $aMatches)) {
							throw new Exception(GSnippetsReviews::$oModule->l('Email is not valid', 'admin-update_class') . '.', 166);
						}
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_EMAIL', Tools::getValue('bt_email'))) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during email update', 'admin-update_class') . '.', 167);
						}
					}
				}
				unset($bEnableEmail);
			}

			// use case - default image type
			if (Tools::getIsset('bt_products-img-type')) {
				// set use image type
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_MAIL_PROD_IMG', Tools::getValue('bt_products-img-type'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during product image type update', 'admin-update_class'), 168);
				}
			}

			// use case - review email
			// update multi-languages fields
			$this->updateLang($aPost, 'bt_email-review-title', 'RVW_EMAIL_SUBJECT');
			$this->updateLang($aPost, 'bt_email-reply-title', 'REPLY_EMAIL_SUBJECT');
			$this->updateLang($aPost, 'bt_email-reply-text', 'REPLY_EMAIL_TEXT');

			// use case - check enable callback
			if (Tools::getIsset('bt_enable-callback')) {
				$bEnableCallback = (Tools::getValue('bt_enable-callback') == true)? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_CALLBACK', $bEnableCallback)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable callback update', 'admin-update_class') . '.', 170);
				}
				
				// use case - enable callback
				if ($bEnableCallback) {
					// update multi-languages fields
					$this->updateLang($aPost, 'bt_email-reminder-title', 'REMINDER_SUBJECT');
					$this->updateLang($aPost, 'bt_email-category-label', 'REMINDER_MAIL_CAT_LABEL');
					$this->updateLang($aPost, 'bt_email-product-label', 'REMINDER_MAIL_PROD_LABEL');
					$this->updateLang($aPost, 'bt_email-sentence', 'REMINDER_MAIL_SENTENCE');
					
					// use case - check email delay
					if (Tools::getIsset('bt_delay-email')) {
						if (!is_numeric(Tools::getValue('bt_delay-email'))) {
							throw new Exception(GSnippetsReviews::$oModule->l('Delay is not numeric', 'admin-update_class') . '.', 171);
						}
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_EMAIL_DELAY', Tools::getValue('bt_delay-email'))) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during email delay update', 'admin-update_class') . '.', 172);
						}
					}
					// use case - order status
					if (!empty($aPost['bt_order-status'])) {
						// update status selection
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_STATUS_SELECTION', serialize($aPost['bt_order-status']))) {
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during the "Status selection" update', 'admin-update_class'), 173);
						}
					}
					else {
						throw new Exception(GSnippetsReviews::$oModule->l('You didn\'t select any order status', 'admin-update_class'), 174);
					}
				}
				// destruct
				unset($bEnableCallback);
			}

			// use case - check enable carbon copy email
			if (Tools::getIsset('bt_enable-carbon-copy')) {
				$bEnableCarbonCopy = (Tools::getValue('bt_enable-carbon-copy') == true)? true : false;
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC', $bEnableCarbonCopy)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable email update', 'admin-update_class') . '.', 175);
				}

				// use case - enable email
				if ($bEnableCarbonCopy) {
					// use case - update email
					if (Tools::getIsset('bt_carbon-copy-email')) {
						if (!preg_match_all('/^[a-zA-Z0-9-_.]{1,}@[a-zA-Z0-9-_.]{1,}.[a-z]{2,3}$/', Tools::getValue('bt_carbon-copy-email'), $aMatches)) {
							if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC', false)) {
								throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable email update', 'admin-update_class') . '.', 176);
							}
							throw new Exception(GSnippetsReviews::$oModule->l('Email is not valid', 'admin-update_class') . '.', 177);
						}
						if (!Configuration::updateValue(_GSR_MODULE_NAME . '_REMINDER_MAIL_CC', Tools::getValue('bt_carbon-copy-email'))) {
							if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC', false)) {
								throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable email update', 'admin-update_class') . '.', 178);
							}
							throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during email update', 'admin-update_class') . '.', 179);
						}
					}
				}
				// destruct
				unset($bEnableCarbonCopy);
			}
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'admin-display_class.php');

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_AdminDisplay::create()->run('emailReviews');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 3,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}

	/**
	 * updateOrders() method import past orders
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateOrders(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			// use case - check orders select date begin
			$sDateFrom = Tools::getValue('bt_date-from');
			$sDateTo = Tools::getValue('bt_date-to');

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
					// include
					require_once(_GSR_PATH_LIB . 'module-dao_class.php');

					// get orders from the selected period
					$aOrders = BT_GsrModuleDao::getOrdersIdByDate($sDateFrom, $sDateTo);

					if (!empty($aOrders)) {
						// set
						$aCallbacks = array();

						// get order status selection
						$aStatusSelection = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION']) : GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_STATUS_SELECTION'];

						$iCountCbk = 1;

						foreach ($aOrders as $iOrder) {
							// check if we have the orders list to process passed as argument
							if (!empty($aPost['bt_orders-to-send'])) {
								if (in_array($iOrder, $aPost['bt_orders-to-send'])) {
									$bProcess = true;
								}
								else {
									$bProcess = false;
								}
							}
							else {
								$bProcess = true;
							}
							// use case - we can a have a list of orders to send so we check if the current order is in this list.
							if ($bProcess) {
								$oOrder = new Order($iOrder);

								if (Validate::isLoadedObject($oOrder)
									&& $oOrder->id_shop == GSnippetsReviews::$iShopId
									&& in_array($oOrder->current_state, $aStatusSelection)
								) {
									// check callback and add it if necessary
									$mResult = BT_GsrModuleTools::addCallback($oOrder->id_shop, $oOrder->id, $oOrder->id_customer, $oOrder->id_lang, $oOrder->date_add, true);

									if (!empty($mResult)) {
										$mResult['cbkId'] = (string)GSnippetsReviews::$oModule->l('manual reminder: ', 'admin-update_class') . $iCountCbk;
										$aCallbacks[] = $mResult;
										$iCountCbk++;
									}
								}
								unset($oOrder);
							}
						}
						unset($aOrders);
						// update the option
						Configuration::updateValue(_GSR_MODULE_NAME . '_ORDERS_IMPORT', 0);

						// include admin send object
						require_once(_GSR_PATH_LIB_ADMIN . 'admin-send_class.php');

						// send the orders just registered
						$aContent = BT_AdminSend::create()->run('callback', array('callbacks' => $aCallbacks));

						// send reminder e-mails return the e-mails log in order to display the result of the sending action
						$aTmpReminders = !empty($aContent['assign']['aReminders'])? $aContent['assign']['aReminders'] : array();

						// get the sent reminders
						if (!empty($aTmpReminders['sent']['count'])) {
							$aAssign['iSentReminders'] = $aTmpReminders['sent']['count'];
							$aSentReminders = $aTmpReminders['sent']['cbk'];
						}
						else {
							$aSentReminders = array();
						}
						// get the sent reminders
						if (!empty($aTmpReminders['reviewed']['count'])) {
							$aAssign['iReviewedReminders'] = $aTmpReminders['reviewed']['count'];
							$aReviewedReminders = $aTmpReminders['reviewed']['cbk'];

							foreach ($aReviewedReminders as &$aReviewedReminder) {
								$aReviewedReminder['error'] = true;
							}
						}
						else {
							$aReviewedReminders = array();
						}
						// get the error reminders
						if (!empty($aTmpReminders['mailerror']['count'])) {
							$aAssign['iErrorReminders'] = $aTmpReminders['mailerror']['count'];
							$aErrorReminders = $aTmpReminders['mailerror']['cbk'];

							foreach ($aErrorReminders as &$aErrorReminder) {
								$aErrorReminder['error'] = true;
							}
						}
						else {
							$aErrorReminders = array();
						}

						// merge
						$aReminders = array_merge($aSentReminders, $aReviewedReminders, $aErrorReminders);

						unset($aTmpReminders);
						unset($aSentReminders);
						unset($aReviewedReminders);
						unset($aErrorReminders);

						if (!empty($aReminders)) {
							$sBaseOrderLink = $_SERVER['SCRIPT_NAME'] . '?controller=AdminOrders&token=' . Tools::getAdminTokenLite('AdminOrders');
							$sBaseCustomerLink = $_SERVER['SCRIPT_NAME'] . '?controller=AdminCustomers&token=' . Tools::getAdminTokenLite('AdminCustomers');

							foreach ($aReminders as $iIndex => &$aReminder) {
								$oCustomer = new Customer($aReminder['custId']);
								$oOrder = new Order($aReminder['orderId']);

								// use case - not valid order
								if (Validate::isLoadedObject($oOrder)
									&& Validate::isLoadedObject($oCustomer)
									&& $oCustomer->active
								) {
									$aReminder['sRef'] = (!empty($oOrder->reference)? $oOrder->reference : '');
									$aReminder['sCustomer'] = $oCustomer->firstname .  ' ' . $oCustomer->lastname . ' (@: '.$oCustomer->email.')';
									$aReminder['sBackOrderLink'] = $sBaseOrderLink . '&vieworder&id_order='. $aReminder['orderId'];
									$aReminder['sBackCustomerLink'] = $sBaseCustomerLink . '&viewcustomer&id_customer='. $aReminder['custId'];
								}
								else {
									unset($aReminders[$iIndex]);
								}
								unset($oOrder);
								unset($oCustomer);
							}
							unset($sBaseOrderLink);
							unset($sBaseCustomerLink);
						}

						$aAssign['aReminders'] = $aReminders;
						unset($aReminders);

						// get configuration options
						BT_GsrModuleTools::getConfiguration();
					}
					else {
						throw new Exception(GSnippetsReviews::$oModule->l('There are no orders to send reminder e-mails for', 'admin-update_class'), 180);
					}
				}
				else {
					throw new Exception(GSnippetsReviews::$oModule->l('The orders selection date start should be set as previous date from the date end', 'admin-update_class'), 181);
				}
			}
			else {
				throw new Exception(GSnippetsReviews::$oModule->l('The orders selection date start is not valid', 'admin-update_class'), 182);
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}
		$aAssign['sURI'] = BT_GsrModuleTools::truncateUri(array('&iPage', '&sAction'));
		$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR);

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_ORDERS_UPDATE,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * updateVouchers() method update vouchers settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateVouchers(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

		try {
			$this->processVoucher('comment', $aPost);
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'admin-display_class.php');

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_AdminDisplay::create()->run('vouchers');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 4,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}

	/**
	 * _processVoucher() method process data to update
	 *
	 * @param string $sType
	 * @param array $aPost
	 * @return bool
	 */
	private function processVoucher($sType, array $aPost)
	{
		if (isset($aPost['bt_enable-voucher_' . $sType])) {
			// use case - check enable email
			$bEnableVoucher = $aPost['bt_enable-voucher_' . $sType] == true? true : false;

			// get serialized data if not empty
			$aVouchers = BT_GsrModuleTools::getEnableVouchers();

			// assign new value
			$aVouchers[$sType] = $bEnableVoucher;

			if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_VOUCHERS', serialize($aVouchers))) {
				throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during enable vouchers update', 'admin-update_class') . '.', 180);
			}

			// use case  - only when voucher is activated
			if ($bEnableVoucher) {
				// get serialized data if not empty
				$aVouchers = BT_Voucher::create()->getSettings();

				// use case - prefix code
				if (empty($aPost['bt_prefix-code'][$sType])) {
					throw new Exception(GSnippetsReviews::$oModule->l('You have not filled out the prefix code', 'admin-update_class') . '.', 181);
				}
				$aVouchers[$sType]['prefixCode'] = $aPost['bt_prefix-code'][$sType];

				// use case - voucher's discount type
				if (empty($aPost['bt_discount-type'][$sType])) {
					throw new Exception(GSnippetsReviews::$oModule->l('You have not selected the discount format', 'admin-update_class') . '.', 182);
				}
				$aVouchers[$sType]['discountType'] = $aPost['bt_discount-type'][$sType];

				// use case - voucher's amount
				if ($aVouchers[$sType]['discountType'] == 'amount') {
					if (empty($aPost['bt_voucher-amount'][$sType])) {
						throw new Exception(GSnippetsReviews::$oModule->l('You have not filled out the voucher amount', 'admin-update_class') . '.', 183);
					}
					$aVouchers[$sType]['amount'] = $aPost['bt_voucher-amount'][$sType];

					// use case - voucher's currency
					if (empty($aPost['bt_currency-id'][$sType])) {
						throw new Exception(GSnippetsReviews::$oModule->l('You have not selected the voucher\'s currency', 'admin-update_class') . '.', 184);
					}
					if (isset($aPost['bt_tax'][$sType])) {
						$aVouchers[$sType]['tax'] = $aPost['bt_tax'][$sType];
					}
					$aVouchers[$sType]['currency'] = $aPost['bt_currency-id'][$sType];
				}
				// use case - voucher's percentage
				if ($aVouchers[$sType]['discountType'] == 'percentage') {
					if (empty($aPost['bt_voucher-percent'][$sType])) {
						throw new Exception(GSnippetsReviews::$oModule->l('You have not filled out the voucher amount', 'admin-update_class') . '.', 183);
					}
					$aVouchers[$sType]['amount'] = $aPost['bt_voucher-percent'][$sType];
				}

				// use case - voucher's minimal order amount
				if (isset($aPost['bt_minimum'][$sType])) {
					$aVouchers[$sType]['minimum'] = $aPost['bt_minimum'][$sType];
				}
				else {
					$aVouchers[$sType]['minimum'] = 0;
				}

				// use case - voucher's maximum qty per customer
				if (isset($aPost['bt_maximum-qte'][$sType])) {
					$aVouchers[$sType]['maximumQty'] = $aPost['bt_maximum-qte'][$sType];
				}
				else {
					$aVouchers[$sType]['maximumQty'] = 0;
				}

				// use case - voucher's validity
				if (empty($aPost['bt_validity'][$sType])) {
					throw new Exception(GSnippetsReviews::$oModule->l('You have not filled out the voucher\'s validity', 'admin-update_class') . '.', 185);
				}
				$aVouchers[$sType]['validity'] = $aPost['bt_validity'][$sType];

				// use case - check language for desc
				foreach (Language::getLanguages() as $nKey => $aLang) {
					if (empty($aPost['bt_tab-voucher-desc_' . $sType][$aLang['id_lang']])) {
						$sException = GSnippetsReviews::$oModule->l('One title of voucher description have not been filled out', 'admin-update_class')
							. '.';
						throw new Exception($sException, 186);
					}
					else {
						$aVouchers[$sType]['langs'][$aLang['id_lang']] = strip_tags($aPost['bt_tab-voucher-desc_' . $sType][$aLang['id_lang']]);
					}
				}
				// use case - check categories selected
				if (!empty($aPost['categoryBox'])) {
					$aVouchers[$sType]['categories'] = $aPost['categoryBox'];
				}
				else {
					$aVouchers[$sType]['categories'] = array();
				}

				// use case - check voucher is highlighted
				$aVouchers[$sType]['highlight'] = (!empty($aPost['bt_highlight_' . $sType]) && $aPost['bt_highlight_' . $sType] == true)? true : false;

				// use case - check voucher is cumulative
				$aVouchers[$sType]['cumulativeOther'] = (!empty($aPost['bt_cumulative-other_' . $sType]) && $aPost['bt_cumulative-other_' . $sType] == true)? true : false;

				// use case - check voucher is cumulative
				$aVouchers[$sType]['cumulativeReduction'] = (!empty($aPost['bt_cumulative-reduc_' . $sType]) && $aPost['bt_cumulative-reduc_' . $sType] == true)? true : false;

				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_VOUCHERS_SETTINGS', serialize($aVouchers))) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during vouchers settings update', 'admin-update_class') . '.', 187);
				}
			}
		}

		return true;
	}


	/**
	 * updateFacebookReviews() method update Facebook reviews settings
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateFacebookReviews(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// require admin configure class - to factorise
			require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

			// check FB voucher configuration
			$this->processVoucher('share', $aPost);

			if (Tools::getIsset('bt_enable-fb-post')) {
				// check requirements for FB
				$bFbWarning = BT_GsrWarning::create()->run('function', 'curl_init');

				if (Tools::getValue('bt_enable-fb-post') == true
					&& empty($bFbWarning)
				) {
					$bEnableFbPost = true;

					// update multi-languages fields
					$this->updateLang($aPost, 'bt_tab-fb-post-phrase', 'FB_POST_PHRASE');
					$this->updateLang($aPost, 'bt_tab-fb-post-label', 'FB_POST_LABEL');
				}
				else {
					$bEnableFbPost = false;
				}
				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_ENABLE_FB_POST', $bEnableFbPost)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during Enable FB post update', 'admin-update_class') . '.', 175);
				}
			}
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'admin-display_class.php');

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_AdminDisplay::create()->run('facebookReviews');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 5,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}


	/**
	 * updateComments() method import reviews from PrestaShop module
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateComments(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			$iCount = 0;

			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			$iTypeOfImport = 2;

			// use case - if we return unmoderated or moderated or both reviews
			if (isset($aPost['ImportType'])
				&& $aPost['ImportType'] != 2
			) {
				$iTypeOfImport = $aPost['ImportType'];
			}

			// get comments of prestashop module
			$aReviews = BT_GsrModuleDao::getModuleProductComments(false, $iTypeOfImport, _GSR_MOCK_IMPORT_DEBUG);

			if (!empty($aReviews)) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// check if default rating
				if (isset($aPost['ImportRating'])) {
					$iDefaultRating = intval($aPost['ImportRating']);
				}
				else {
					$iDefaultRating = floor(floatval(_GSR_MAX_RATING / 2));
				}

				// set params for posting a rating and review
				$aParams = array(
					'iShopId'           => GSnippetsReviews::$iShopId,
					'bCheckFieldText'   => 1,
					'iLangId'           => Configuration::get('PS_LANG_DEFAULT'),
				);
				$aParams['sLangIso'] = BT_GsrModuleTools::getLangIso($aParams['iLangId']);

				// set total reviews
				$aAssign['iTotalReviews'] = count($aReviews);

				// default time
				$iDateAdd = time();

				foreach ($aReviews as $aReview) {
					$sError = GSnippetsReviews::$oModule->l('The review', 'admin-update_class')
						. ' "'. $aReview['id_product_comment'] . '" '
						. GSnippetsReviews::$oModule->l('has not been imported', 'admin-update_class')
					;
					// control customer ID and comment
					if (Customer::customerIdExistsStatic($aReview['id_customer'])) {
						$oCurrentProd = new Product($aReview['id_product']);

						if (Validate::isLoadedObject($oCurrentProd)
							&& $oCurrentProd->active
						) {
							if (!empty($aReview['content'])) {
								// handle grade format when a rating is a float from multi-criterias into the comments product module
								if (!empty($aReview['grade'])) {
									$aReview['grade'] = intval(ceil(floatval($aReview['grade'])));
								}
								else {
									$aReview['grade'] = $iDefaultRating;
								}

								// set params matching for review adding
								$aParams['iProductId'] = $aReview['id_product'];
								$aParams['iCustomerId'] = $aReview['id_customer'];
								$aParams['iRating'] = $aReview['grade'];
								$aParams['sTitle'] = !empty($aReview['title']) ? $aReview['title'] : '--';
								$aParams['sComment'] = $aReview['content'];
								$aParams['iDate'] = !empty($aReview['date_add']) ? BT_GsrModuleTools::getTimeStamp($aReview['date_add'], 'db') : $iDateAdd;
								$aParams['bForceModerate'] = true;

								// add review
								$aAdd = BT_ReviewCtrl::create()->run('add', $aParams);

								if (!empty(BT_ReviewCtrl::create()->aErrors)) {
									$sError .= ' (' . BT_ReviewCtrl::create()->aErrors[0]['msg'] . ')';
									$aAssign['aErrors'][] = array('msg' => $sError, 'code' => 195);
								}
								else {
									++$iCount;
								}
							}
							else {
								$sError .= ' (' . GSnippetsReviews::$oModule->l('The comment is empty', 'admin-update_class') . ')';
								$aAssign['aErrors'][] = array('msg' => $sError, 'code' => 196);
							}
						}
						else {
							$sError .= ' (' . GSnippetsReviews::$oModule->l('The product doesn\'t exist anymore', 'admin-update_class') . ')';
							$aAssign['aErrors'][] = array('msg' => $sError, 'code' => 197);
						}
						unset($oCurrentProd);
					}
					else {
						$sError .= ' (' . GSnippetsReviews::$oModule->l('Customer ID', 'admin-update_class')
							. ' "'. $aReview['id_customer'] . '" '
							. GSnippetsReviews::$oModule->l('doesn\'t exist', 'admin-update_class') . ')';
						$aAssign['aErrors'][] = array('msg' => $sError, 'code' => 198);
					}
				}
				unset($aReviews);
				unset($aParams);

				// check update OK
				$aAssign['bUpdate'] = true;
				$aAssign['iCountImport'] = $iCount;
				$aAssign['iCountError'] = $aAssign['iTotalReviews'] - $iCount;

				if (!Configuration::updateValue(_GSR_MODULE_NAME . '_COMMENTS_IMPORT', true)) {
					throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during comments import update', 'admin-update_class') . '.', 199);
				}
			}
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		$aAssign['bImport'] = true;
		$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR);

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_REVIEWS_UPDATE,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * updateLang() method check and update lang of multi-language fields
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateLang(array $aPost, $sFieldName, $sGlobalName, $bCheckOnly = false)
	{
		// check title in each active language
		$aLangs = array();

		foreach (Language::getLanguages() as $nKey => $aLang) {
			if (isset($aPost[$sFieldName . '_' . $aLang['id_lang']])) {
				if (empty($aPost[$sFieldName . '_' . $aLang['id_lang']])) {
					$sException = GSnippetsReviews::$oModule->l('One title of', 'admin-update_class')
						. ' " ' . $sFieldName . ' " '
						. GSnippetsReviews::$oModule->l('have not been filled', 'admin-update_class')
						. '.';
					throw new Exception($sException, 197);
				}
				else {
					$aLangs[$aLang['id_lang']] = strip_tags($aPost[$sFieldName . '_' . $aLang['id_lang']]);
				}
			}
		}
		if (!$bCheckOnly  && !empty($aLangs)) {
			// update titles
			if (!Configuration::updateValue(_GSR_MODULE_NAME . '_' . $sGlobalName, serialize($aLangs))) {
				$sException = GSnippetsReviews::$oModule->l('An error occurred during', 'admin-update_class')
					. ' " ' . $sGlobalName . ' " '
					. GSnippetsReviews::$oModule->l('update', 'admin-update_class')
					. '.';
				throw new Exception($sException, 198);
			}
		}
		return $aLangs;
	}


	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oUpdate;

		if (null === $oUpdate) {
			$oUpdate = new BT_AdminUpdate();
		}
		return $oUpdate;
	}
}