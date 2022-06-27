<?php
/**
 * review-update_class.php file defines method to handle reviews update action
 */

class BT_ReviewUpdate implements BT_IAdmin
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
			case 'oneStatus'    : // use case - update reviews status
			case 'comment'		: // use case - update reviews title and comment
			case 'review'		: // use case - add new review from the BO
			case 'reply'		: // use case - add new reply from the BO
			case 'moderation'   : // use case - update status or delete comment
				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'update' . ucfirst($sType)), array($aParam));
				break;
			case 'activate'     : // use case - update status to activate
			case 'deactivate'   : // use case - update status to deactivate
				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'updateStatus'), array($sType, $aParam));
				break;
			case 'abuse'   : // use case - update status for abuse flag
				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'updateAbuseFlag'), array($aParam));
				break;
			default :
				break;
		}
		return (
			$aDisplayInfo
		);
	}

	/**
	 * updateOneStatus() method update status of one review
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateOneStatus(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check review Id
			if (!Tools::getIsset('iReviewId')) {
				throw new Exception(GSnippetsReviews::$oModule->l('Review ID is not valid', 'review-update_class') . '.', 190);
			}
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			$iReviewId = Tools::getValue('iReviewId');
			
			// get status of current review
			$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $iReviewId, 'active' => 2));

			if (!empty($aReview)) {
				if ($aReview['status']) {
					$aUpdateInfo['bStatus'] = 0;
				}
				else {
					$aUpdateInfo['bStatus'] = 1;
				}

				// update status
				BT_ReviewCtrl::create()->run('updateReview', array('id' => $iReviewId, 'status' => $aUpdateInfo['bStatus']));
				
				// use case - enable send wall post and review e-mail notification
				if ($aUpdateInfo['bStatus']) {
					// use case - send wall post
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_FB_POST'])
							&&
						BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'])
					) {
						// require module-dao class
						require_once(_GSR_PATH_LIB . 'facebook-ctrl_class.php');

						// use case - check if comment has already posted
						if (!BT_FacebookCtrl::create()->isFbPostExists($iReviewId)) {
							// post review in FB page and store it
							$aUpdateInfo['iFbPostId'] = BT_FacebookCtrl::create()->updateAndSharePost($iReviewId);
						}
					}

					// use case - send an e-mail review notification
					require_once(_GSR_PATH_LIB . 'mail-send_class.php');

					// generate voucher code
					$sVoucherCode = $this->generateVoucher($aReview['shopId'], $aReview['custId'], $iReviewId);

					// get run of admin send in order to send an e-mail notification
					$bSend = BT_GsrMailSend::create()->run('customerNotification' , array('iReviewId' => $iReviewId, 'sVoucherCode' => $sVoucherCode));
				}
			}
			unset($iReviewId);
			unset($aReview);
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// require review configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

		BT_GsrModuleTools::getConfiguration();

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_ReviewDisplay::create()->run('status');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bUpdate' => (empty($aUpdateInfo['aErrors']) ? true : false),
			'bOneStatusUpdate' => true,
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}

	/**
	 * updateAbuseFlag() method updates abuse flag of one review
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateAbuseFlag(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check review Id
			if (!Tools::getIsset('iReviewId')) {
				throw new Exception(GSnippetsReviews::$oModule->l('Review ID is not valid', 'review-update_class') . '.', 190);
			}
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			$iReviewId = Tools::getValue('iReviewId');

			// get status of current review
			$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $iReviewId, 'report' => true));

			if (!empty($aReview['reportId'])) {
				// update status
				BT_ReviewCtrl::create()->run('updateReview', array('id' => $iReviewId, 'abuse' => true, 'status' => false));
			}
			$aUpdateInfo['bStatus'] = true;
			unset($iReviewId);
			unset($aReview);
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

		BT_GsrModuleTools::getConfiguration();

		// get run of review display in order to display first page with moderation tool
		$aInfo = BT_ReviewDisplay::create()->run('status');

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bUpdate' => (empty($aUpdateInfo['aErrors']) ? true : false),
			'bOneStatusUpdate' => true,
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}


	/**
	 * updateStatus() method update selected reviews status (activate or deactivate)
	 *
	 * @param string $sAction
	 * @param array $aPost
	 * @return array
	 */
	private function updateStatus($sAction, array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check review Id
			if (!Tools::getIsset('bt_check-review')
				|| (Tools::getIsset('bt_check-review')
				&& !is_array(Tools::getValue('bt_check-review')))
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Reviews list is not valid', 'review-update_class') . '.', 191);
			}
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// get the review IDs
			$aReviewIds = Tools::getValue('bt_check-review');

			foreach ($aReviewIds as $iKey => $iReviewId) {
				if (empty($iReviewId)) {
					unset($aReviewIds[$iKey]);
				}
			}

			if (!empty($aReviewIds)) {
				// update status
				$bUpdate = BT_ReviewCtrl::create()->run('updateReview', array('id' => $aReviewIds, 'status' => ($sAction == 'activate'? 1 : 0)));

				// use case - enable send wall post and review e-mail notification
				if ($bUpdate && $sAction == 'activate') {
					// require class
					require_once(_GSR_PATH_LIB . 'mail-send_class.php');

					// use case - send wall post
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_FB_POST'])
						&& BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'])
					) {
						// require module-dao class
						require_once(_GSR_PATH_LIB . 'facebook-ctrl_class.php');

						// set sending to true
						$bSendFbPost = true;
					}
					// loop on each review id to update review and send Fb wall post + send email notification
					foreach ($aReviewIds as $iReviewId) {
						if (is_numeric($iReviewId)) {
							// use case - send wall post
							if (!empty($bSendFbPost)) {
								// use case - check if comment has already posted
								if (!BT_FacebookCtrl::create()->isFbPostExists($iReviewId)) {
									// post review in FB page and store it
									$iFbPostId = BT_FacebookCtrl::create()->updateAndSharePost($iReviewId);
								}
							}
							// get status of current review
							$aReview = BT_ReviewCtrl::create()->run('getReviews', array('ratingId' => $iReviewId, 'active' => 1, 'customer' => true));

							if (!empty($aReview)) {
								// generate voucher code
								$sVoucherCode = $this->generateVoucher($aReview['shopId'], $aReview['custId'], $iReviewId);
							}
							else {
								$sVoucherCode = '';
							}

							// get run of admin send in order to send an e-mail notification
							$bSend = BT_GsrMailSend::create()->run('customerNotification', array('iReviewId' => $iReviewId, 'sVoucherCode' => $sVoucherCode, 'aReview' => $aReview));
						}
					}
				}
			}
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

		BT_GsrModuleTools::getConfiguration();

		// get run of admin display in order to display first page of admin with basic settings updated
		$aInfo = BT_ReviewDisplay::create()->run('moderation', array('iPage' => (Tools::getIsset('iPage')?intval(Tools::getValue('iPage')):1)));

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bUpdate' => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);
		
		// destruct
		unset($aUpdateInfo);

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		return $aInfo;
	}


	/**
	 * updateComment() method update reviews text
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateComment(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			// use case - check review Id
			if (empty($aPost['iReviewId'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Review ID is not valid', 'review-update_class') . '.', 192);
			}
			// use case - check page number
			if (empty($aPost['iPage'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Page number is not valid', 'review-update_class') . '.', 193);
			}
			// use case - check title and comment
			if (empty($aPost['bt_review-title'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Title is not filled', 'review-update_class') . '.', 194);
			}
			if (empty($aPost['bt_review-comment'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Comment is not filled', 'review-update_class') . '.', 195);
			}
			// get current page
			$aAssign['iPage'] = $aPost['iPage'];

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			if (empty($aPost['iLangId'])) {
				$aPost['iLangId'] = Configuration::get('PS_LANG_DEFAULT');
			}
			if (empty($aPost['sLangIso'])) {
				$aPost['sLangIso'] = BT_GsrModuleTools::getLangIso($aPost['iLangId']);
			}

			$aData = array(
				'data' => array(
					'sTitle'    => strip_tags($aPost['bt_review-title']),
					'sComment'  => strip_tags($aPost['bt_review-comment']),
					'iLangId'  => strip_tags($aPost['iLangId']),
					'sLangIso'  => strip_tags($aPost['sLangIso']),
				),
				'id'  => $aPost['iReviewId'],
			);

			$bUpdate = BT_ReviewCtrl::create()->run('updateReview', $aData);

			if (!$bUpdate) {
				throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during comment update', 'review-update_class') . '.', 196);
			}
			unset($aData);
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
			$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR);
		}

		// check update OK
		$aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REVIEW_UPDATE,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * updateReply() method update after-sales reply
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateReply(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aAssign = array();

		try {
			// use case - check rating Id
			if (empty($aPost['iRatingId'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Rating ID is not valid', 'review-update_class') . '.', 280);
			}
			if (!empty($aPost['bt_reply-action'])
				&& $aPost['bt_reply-action'] == 'add'
				&& empty($aPost['bt_reply-comment'])
			) {
				throw new Exception(GSnippetsReviews::$oModule->l('Comment is not filled', 'review-update_class') . '.', 281);
			}

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			$aData = array(
				'type' => 'rating',
				'action' => $aPost['bt_reply-action'],
				'display' => $aPost['bt_display-reply'],
				'id' => $aPost['iRatingId'],
				'data' => array(),
			);
			// get data to serialize
			if (!empty($aPost['bt_reply-comment'])) {
				$aData['data']['sComment'] = strip_tags($aPost['bt_reply-comment']);
			}
			// use case - only if send e-mail option is activated
			if (!empty($aPost['bt_send-reply'])) {
				$aData['data']['iCounter'] = intval($aPost['bt_nb-reply']) + 1;
				$bSendEmail = true;
			}
			else {
				$aData['data']['iCounter'] = intval($aPost['bt_nb-reply']);
				$bSendEmail = false;
			}
			// update reply
			$bUpdate = BT_ReviewCtrl::create()->run('updateReply', $aData);

			if (!$bUpdate) {
				throw new Exception(GSnippetsReviews::$oModule->l('An error occurred during reply update', 'review-update_class') . '.', 282);
			}
			// use case - send a notification to the customer with the after-sales reply
			if ($bSendEmail || $aData['action'] == 'add') {
				// require class
				require_once(_GSR_PATH_LIB . 'mail-send_class.php');

				// get run of admin send in order to send an e-mail notification
				$aAssign['bSendEmail'] = BT_GsrMailSend::create()->run('replyNotification' , array('iRatingId' => $aData['id']));
			}

			unset($aData);
		}
		catch (Exception $e) {
			$aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
			$aAssign['sErrorInclude'] = BT_GsrModuleTools::getTemplatePath(_GSR_PATH_TPL_NAME . _GSR_TPL_ADMIN_PATH . _GSR_TPL_ERROR);
		}

		// check update OK
		$aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		return (
			array(
				'tpl'	    => _GSR_TPL_REVIEWS_TOOL_PATH . _GSR_TPL_REPLY_UPDATE,
				'assign'	=> $aAssign,
			)
		);
	}


	/**
	 * updateReview() method add new review from BO
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function updateReview(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();

		try {
			// use case - check shop Id
			if (empty($aPost['bt_shop-id'])) {
				$aPost['bt_shop-id'] = GSnippetsReviews::$iShopId;
			}
			// use case - check product Id
			if (empty($aPost['bt_product-id'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Product ID is not valid', 'review-update_class') . '.', 201);
			}

			// use case - check customer Id
			if (empty($aPost['bt_customer-id'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Customer ID is not valid', 'review-update_class') . '.', 202);
			}

			// use case - check language Id
			if (empty($aPost['bt_lang-id'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Language ID is not valid', 'review-update_class') . '.', 203);
			}

			// use case - check rating
			if (empty($aPost['iRating'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Rating is not valid', 'review-update_class') . '.', 204);
			}

			// use case - check review's date
			if (empty($aPost['bt_review-date'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Date is not valid', 'review-update_class') . '.', 205);
			}

			// use case - check title and comment
			if (empty($aPost['bt_review-title'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Title is not filled', 'review-update_class') . '.', 206);
			}
			if (empty($aPost['bt_review-comment'])) {
				throw new Exception(GSnippetsReviews::$oModule->l('Comment is not filled', 'review-update_class') . '.', 207);
			}

			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			if (empty($aPost['iLangId'])) {
				$aPost['iLangId'] = Configuration::get('PS_LANG_DEFAULT');
			}
			if (empty($aPost['sLangIso'])) {
				$aPost['sLangIso'] = BT_GsrModuleTools::getLangIso($aPost['iLangId']);
			}

			// set params for posting a rating and review
			$aParams = array(
				'iShopId'           => $aPost['bt_shop-id'],
				'iProductId'        => $aPost['bt_product-id'],
				'iCustomerId'       => $aPost['bt_customer-id'],
				'iDate'             => BT_GsrModuleTools::getTimeStamp($aPost['bt_review-date'], 'db'),
				'sTitle'            => $aPost['bt_review-title'],
				'sComment'          => $aPost['bt_review-comment'],
				'iRating'           => $aPost['iRating'],
				'bCheckFieldText'   => $aPost['bCheckFieldText'],
				'iLangId'           => $aPost['bt_lang-id'],
				'sLangIso'          => BT_GsrModuleTools::getLangIso($aPost['bt_lang-id']),
				'bForceModerate'    => true,
				'bBackOffice'       => true,
			);

			$aUpdate = BT_ReviewCtrl::create()->run('add', $aParams);

			if (!$aUpdate) {
				$aUpdateInfo['aErrors'] = BT_ReviewCtrl::create()->aErrors;
			}

			unset($aParams);
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// check update OK
		$aUpdateInfo['bUpdate'] = empty($aUpdateInfo['aErrors']) ? true : false;

		// require admin configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

		// get run of review display in order to display review's feature page
		$aInfo = BT_ReviewDisplay::create()->run('reviewAdd');

		// force xhr mode
		GSnippetsReviews::$sQueryMode = 'xhr';

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bAjaxMode'         => GSnippetsReviews::$sQueryMode,
			'iActiveTab'        => 2,
			'bUpdate'           => (empty($aUpdateInfo['aErrors']) ? true : false),
			'iLastInsertId'     => (!empty($aUpdate['iLastInsertId']) ? $aUpdate['iLastInsertId'] : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
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
			if (empty($aPost[$sFieldName . '_' . $aLang['id_lang']])) {
				$sException = GSnippetsReviews::$oModule->l('One title of', 'review-update_class')
					. ' " ' . $sFieldName . ' " '
					. GSnippetsReviews::$oModule->l('have not been filled', 'review-update_class')
					. '.';
				throw new Exception($sException, 197);
			}
			else {
				$aLangs[$aLang['id_lang']] = strip_tags($aPost[$sFieldName . '_' . $aLang['id_lang']]);
			}
		}
		if (!$bCheckOnly) {
			// update titles
			if (!Configuration::updateValue(_GSR_MODULE_NAME . '_' . $sGlobalName, serialize($aLangs))) {
				$sException = GSnippetsReviews::$oModule->l('An error occurred during', 'review-update_class')
					. ' " ' . $sGlobalName . ' " '
					. GSnippetsReviews::$oModule->l('update', 'review-update_class')
					. '.';
				throw new Exception($sException, 198);
			}
		}
		return $aLangs;
	}

	/**
	 * generateVoucher() method generates a voucher code when a comment is posted
	 *
	 * @param int $iShopId
	 * @param int $iCustomerId
	 * @param int $iReviewId
	 * @return string code name
	 */
	private function generateVoucher($iShopId, $iCustomerId, $iReviewId)
	{
		$sCodeName = '';

		// use case - generate voucher if activated
		$aVoucher = BT_GsrModuleTools::getEnableVouchers('comment');

		if (!empty($aVoucher)) {
			// require admin configure class - to factorise
			require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

			// generate voucher
			$sCodeName = BT_Voucher::create()->add('comment', $iShopId, $iCustomerId, $iReviewId);
		}

		return $sCodeName;
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
			$oUpdate = new BT_ReviewUpdate();
		}
		return $oUpdate;
	}
}