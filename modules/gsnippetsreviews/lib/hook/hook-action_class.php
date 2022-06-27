<?php
/**
 * hook-action_class.php file defines controller which manage hooks sequentially
 */

class BT_GsrHookAction extends BT_GsrHookBase
{
	/**
	 * @var mixed $bAddReview : define if review is added or not
	 */
	protected static $bAddReview = null;

	/**
	 * @var mixed $mAddReview : define if review is added or not and returns errors
	 */
	protected static $mAddReview = array();

	/**
	 * @var string $sHookAction : define hook action
	 */
	protected $sHookAction = null;

	/**
	 * Magic Method __construct assigns few information about hook
	 *
	 * @param string $sHookAction
	 */
	public function __construct($sHookAction)
	{
		// set hook action
		$this->sHookAction = $sHookAction;
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

		switch ($this->sHookAction) {
			case 'orderConfirmation' :
				// use case - display nothing only process storage in order to send an email
				$aDisplayHook = call_user_func_array(array($this, 'saveDataOrder'), array($aParams));
				break;
			case 'updateReminderStatus' :
				// use case - update reminder status of customer
				$aDisplayHook = call_user_func_array(array($this, 'updateReminderStatus'), array($aParams));
				break;
			case 'postReview' :
				// use case - post a review
				$aDisplayHook = call_user_func_array(array($this, 'postReview'), array($aParams));
				break;
			case 'reportReview' :
				// use case - report a review
				$aDisplayHook = call_user_func_array(array($this, 'reportReview'), array($aParams));
				break;
			case 'updateReview' :
				// use case - update a review
				$aDisplayHook = call_user_func_array(array($this, 'updateReview'), array($aParams));
				break;
			case 'productDelete' :
				// use case - delete reviews related to the product that is currently deleted
				$aDisplayHook = call_user_func_array(array($this, 'deleteProductReviews'), array($aParams));
				break;
			default :
				break;
		}

		return $aDisplayHook;
	}

	/**
	 * updateReminderStatus() method update customer's reminder status
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function updateReminderStatus(array $aParams = null)
	{
		$aAssign = array();

		// use case - if customer logged and page account required
		if (!empty(GSnippetsReviews::$oCookie->id_customer)
			&& !empty($aParams['status'])
			&& in_array($aParams['status'], array('true', 'false', 'checked', 'unchecked', 'undefined'))
		) {
			// include
			require_once(_GSR_PATH_LIB . 'module-dao_class.php');

			$aParams['status'] = ($aParams['status'] == 'true' || $aParams['status'] == 'checked') ? 1 : 0;

			// update callback
			if (!BT_GsrModuleDao::addCustCallbackStatus(GSnippetsReviews::$iShopId, GSnippetsReviews::$oCookie->id_customer, $aParams['status'])) {
				$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('An error occurred during callback status update', 'hook-action_class'), 'code' => 160);
			}
			$aAssign['bStatus'] = $aParams['status'];
		}
		else {
			$aAssign['sIMG_URI']     = _GSR_URL_IMG . 'hook/';
		}
		$aAssign['sConfirmInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_CONFIRM);
		$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_POST_CBK, 'assign' => $aAssign)
		);
	}


	/**
	 * saveDataOrder() method save data about order
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function saveDataOrder(array $aParams = null)
	{
		if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_CALLBACK']
			&& !empty($aParams['order'])
			&& is_object($aParams['order'])
			&& $aParams['order']->module != GSnippetsReviews::$oModule->name
			&& $aParams['order']->getCurrentState() != Configuration::get('PS_OS_ERROR')
		) {
			// check callback and add it if necessary
			$mDataOrder = BT_GsrModuleTools::addCallback(GSnippetsReviews::$iShopId, $aParams['order']->id, $aParams['order']->id_customer, GSnippetsReviews::$iCurrentLang);
		}

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_ORDER_CONFIRMATION, 'assign' => array())
		);
	}


	/**
	 * postReview() method post a review with a rating or / and comment
	 *
	 * @param array $aParams
	 * @return bool
	 */
	private function postReview(array $aParams = null)
	{
		$aAssign = array();

		$iProductId = Tools::getValue('iPId');
		$iCustomerId = Tools::getValue('iCId');

		// use case - if check secure key for identifying current product OK
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey(GSnippetsReviews::$iShopId.$iProductId.$iCustomerId.'review', false)
		) {
			// set params for posting a rating and review
			$aParams = array(
				'iShopId'           => GSnippetsReviews::$iShopId,
				'iProductId'        => $iProductId,
				'iCustomerId'       => $iCustomerId,
				'sTitle'            => Tools::getValue('bt_review-title'),
				'sComment'          => Tools::getValue('bt_review-comment'),
				'bCheckFieldText'   => Tools::getValue('bCheckFieldText'),
				'iRating'           => Tools::getValue('iRating'),
				'iLangId'           => GSnippetsReviews::$iCurrentLang,
				'sLangIso'          => GSnippetsReviews::$sCurrentLang,
			);

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// add review
			self::$mAddReview = BT_ReviewCtrl::create()->run('add', $aParams);

			// use case - review added
			if (empty(BT_ReviewCtrl::create()->aErrors)) {
				$aAssign['sIMG_URI'] = _GSR_URL_IMG . 'hook/';

				// use case - review added
				self::$bAddReview = true;

				// use case - send a notification to the merchant
				if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_EMAIL']) {
					// send a notification
					$mSend = $this->sendNotification('merchantNotification', $aParams);
				}

				// use case - no moderation - add review OK => post in FB + generate voucher if true + send notification
				if (empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_APPROVAL'])
					&& isset(self::$mAddReview['iLastInsertId'])
				) {
					// post in Fb if last insert id of review exists
					self::$mAddReview['aFbIds'] = $this->postFbReview(self::$mAddReview['iLastInsertId']);
					// generate a voucher
					self::$mAddReview['sVoucherCode'] = $this->generateVoucher(self::$mAddReview['iLastInsertId'], $iCustomerId);

					// use case - voucher for sharing FB post is activated
					if (BT_GsrModuleTools::getEnableVouchers('share')) {
						// require
						require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

						// get voucher settings
						$aVoucher = BT_Voucher::create()->getSettings('share');

						if (!empty($aVoucher)) {
							// get voucher data
							$aSettings = BT_Voucher::create()->formatData($aVoucher, GSnippetsReviews::$sCurrentLang);

							// assign voucher data
							if (!empty($aSettings['use'])) {
								$aAssign['aVoucherShare'] = $aSettings;
							}
							unset($aSettings);
						}
						unset($aVoucher);
					}

					// voucher activated
					if (!empty(self::$mAddReview['sVoucherCode'])) {
						// require
						require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

						// get settings
						$aVoucher = BT_Voucher::create()->getSettings('comment');

						$aVoucher['name'] = self::$mAddReview['sVoucherCode'];

						// get voucher data
						$aSettings = BT_Voucher::create()->formatData($aVoucher, GSnippetsReviews::$sCurrentLang);

						// assign voucher data
						if (!empty($aSettings['use'])) {
							$aAssign['aVoucherComment'] = $aSettings;
						}

						unset($aSettings);
						unset($aVoucher);
					}

					// use case - no moderation - send an e-mail review notification to the customer
					$aData = array(
						'iReviewId' => self::$mAddReview['iLastInsertId']
					);
					// add voucher code
					if (!empty(self::$mAddReview['sVoucherCode'])) {
						$aData['sVoucherCode'] = self::$mAddReview['sVoucherCode'];
					}

					// send notification
					$mSend = $this->sendNotification('customerNotification', $aData);
				}

				$aAssign['bAddReview'] = self::$bAddReview;
			}
		}

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_POST_POPIN, 'assign' => $aAssign)
		);
	}

	/**
	 * reportReview() method report a review with abuse status
	 *
	 * @param array $aParams
	 * @return bool
	 */
	private function reportReview(array $aParams = null)
	{
		$aAssign = array();
		$bReport = false;

		// get customer and review
		$iCustomerId = Tools::getValue('iCustomerId');
		$iReviewId = Tools::getValue('iId');
		$sReportComment = Tools::getValue('bt_report-comment');

		// use case - if check secure key for identifying review and customer
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey($iCustomerId . $iReviewId, false)
			&& BT_GsrModuleTools::checkCustomer($iCustomerId)
		) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// report review
			$bReport = BT_ReviewCtrl::create()->run('report', array('iReviewId' => $iReviewId, 'iCustomerId' => $iCustomerId, 'aData' => array('sComment' => $sReportComment)));

			if ($bReport) {
				// include
				require_once(_GSR_PATH_LIB . 'mail-send_class.php');

				// send notification
				$aAssign['bSendMail'] = BT_GsrMailSend::create()->run('reviewReport' , array('iReviewId' => $iReviewId, 'iCustomerId' => $iCustomerId, 'sComment' => $sReportComment));
			}
		}
		$aAssign['bReport'] = $bReport;

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_POST_REPORT_POPIN, 'assign' => $aAssign)
		);
	}

	/**
	 * updateReview() method update a review by the customer with after-sales reply feature
	 *
	 * @param array $aParams
	 * @return bool
	 */
	private function updateReview(array $aParams = null)
	{
		$aAssign = array();

		$iProductId = Tools::getValue('iPId');
		$iRatingId = Tools::getValue('iRId');
		$iCustomerId = Tools::getValue('iCId');

		// use case - if check secure key for identifying current customer / product / rating
		if (Tools::getIsset('btKey')
			&& Tools::getValue('btKey') == $this->setSecureKey(GSnippetsReviews::$iShopId.$iProductId.$iRatingId.$iCustomerId.'update', false)
//			&& $iCustomerId == GSnippetsReviews::$oCookie->id_customer
			&& BT_GsrModuleTools::checkCustomer($iCustomerId, $iProductId)
		) {
			// set
			$bSendEmail = false;
			$bNothingHappened = false;
			$bCheckRating = Tools::getValue('rating');
			$iOldRating = Tools::getValue('iOldRating');
			$iRating = Tools::getValue('iRating');
			$iLangId = Tools::getValue('iLangId');
			$sLangIso = Tools::getValue('sLangIso');
			$sOldTitle = Tools::getValue('bt_old-review-title');
			$sOldComment = Tools::getValue('bt_old-review-comment');
			$sTitle = Tools::getValue('bt_review-title');
			$sComment = Tools::getValue('bt_review-comment');

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// use case - update the rating first
			if (!empty($iOldRating) && !empty($iRating)
				&& $iOldRating != $iRating
				&& !empty($bCheckRating)
			) {
				$aParams = array(
					'id' => $iRatingId,
					'note' => $iRating,
					'data' => array('iOldRating' => $iOldRating),
				);
				$bResult = BT_ReviewCtrl::create()->run('updateRating', $aParams);

				if ($bResult) {
					$aAssign['bAddRating'] = true;
					$bSendEmail = true;
				}
				else {
					$aAssign['bAddRating'] = false;
					$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('There was an internal server error (unsecure request), your new rating hasn\'t been taken into account', 'hook-action_class'), 'code' => 171);
				}
				unset($aParams);
			}
			else {
				$bNothingHappened = true;
			}

			// use case - update title and comment in the second time if necessary
			if (!empty($sOldTitle)
				&& !empty($sTitle)
				&& !empty($sOldComment)
				&& !empty($sComment)
				&& (md5($sOldTitle) != md5($sTitle)
				|| md5($sOldComment) != md5($sComment))
			) {
				$aParams = array(
					'byRating' => true,
					'id' => $iRatingId,
					'data' => array(
						'sOldTitle' => $sOldTitle,
						'sOldComment' => $sOldComment,
						'sTitle' => $sTitle,
						'sComment' => $sComment,
						'iLangId' => $iLangId,
						'sLangIso' => $sLangIso,
					),
				);
				// use case - if moderation is activated
				if (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_APPROVAL']) {
					$aParams['status'] = 0;
				}
				$bResult = BT_ReviewCtrl::create()->run('updateReview', $aParams);

				if ($bResult) {
					$aAssign['bAddReview'] = true;
					$bSendEmail = true;
				}
				else {
					$aAssign['bAddReview'] = false;
					$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('There was an internal server error (unsecure request), your new review hasn\'t been taken into account', 'hook-action_class'), 'code' => 172);
				}
				unset($aParams);
			}
			else {
				if ($bNothingHappened) {
					$aAssign['aErrors'][] = array('msg' => GSnippetsReviews::$oModule->l('You may have not changed anything, sorry reload the page and try again', 'hook-action_class') . '!', 'code' => 172);
					$aAssign['bAddRefreshButton'] = true;
				}
			}

			// use case - send a notification to the merchant
			if ($bSendEmail && GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_EMAIL']) {
				// send a notification
				$aParams = array(
					'iProductId'    => $iProductId,
					'iRating'       => $iRating,
					'iOldRating'    => $iOldRating,
					'sTitle'        => $sTitle,
					'sComment'      => $sComment,
					'sOldTitle'     => $sOldTitle,
					'sOldComment'   => $sOldComment,
				);
				$mSend = $this->sendNotification('aftersalesMerchantNotification', $aParams);
			}
		}
		$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME .  _GSR_TPL_HOOK_PATH . _GSR_TPL_ERROR);

		return (
			array('tpl' => _GSR_TPL_HOOK_PATH . _GSR_TPL_POST_POPIN, 'assign' => $aAssign)
		);
	}

	/**
	 * deleteProductReviews() method check reviews and delete them when a product is deleted
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function deleteProductReviews(array $aParams = null)
	{
		$bDelete = false;

		if (!empty($aParams['id_product'])) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			$bDelete = BT_ReviewCtrl::create()->run('deleteReview', array('id' => $aParams['id_product'], 'byProduct' => true));
		}

		return $bDelete;
	}


	/**
	 * postFbReview() method posts rating and review in FB if configured with FB Ps wall post
	 *
	 * @param int $iLastInsertId
	 * @return array
	 */
	private function postFbReview($iLastInsertId)
	{
		$aFbIds = array();

		// use case - send FB post
		if (!empty(self::$bAddReview)
			&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_FB_POST'])
			&& empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_APPROVAL'])
			&& BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'])
		) {
			// require module-dao class
			require_once(_GSR_PATH_LIB . 'facebook-ctrl_class.php');

			// post review in FB page and store it
			$aFbIds = BT_FacebookCtrl::create()->updateAndSharePost($iLastInsertId);
		}
		return $aFbIds;
	}
}