<?php
/**
 * admin-send_class.php file defines method to check callback and send email for review
 */

class BT_AdminSend implements BT_IAdmin
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
	 * run() method deals with callback reviews and send email to customers
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
			case 'callback' : // use case - send an email for callback
				$aDisplayInfo = call_user_func_array(array($this, 'sendCallbacks'), array($aParam));
				break;
			default :
				break;
		}

		return $aDisplayInfo;
	}

	/**
	 * sendCallbacks() method deletes older email callbacks and send email to customers
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function sendCallbacks(array $aPost)
	{
		require_once(_GSR_PATH_LIB . 'module-dao_class.php');
		require_once(_GSR_PATH_LIB_COMMON . 'verbose.class.php');

		// set
		$aAssign = array();

		$aOptions = array(
			'mask' 			=> '[^c ^d m:[^m] pid:[^p]] ^l',
			'separator' 	=> ' - ',
			'stepLine' 		=> '	',
			'datePattern' 	=> 'Y-m-d H:i:s',
		);

		// set current time for getCallbacks() and deleteCallbacks()
		$iTime = time();

		// set status
		$sStatus = 'start';

		// counter
		$iDelete = 0;
		$iUpdate = 0;

		// get valid callbacks
		if (!empty($aPost['callbacks'])
			&& is_array($aPost['callbacks'])
		) {
			$aCallbacks = $aPost['callbacks'];
			$bForceCallbacks = true;
		}
		else {
			$aCallbacks = BT_GsrModuleDao::getCallbacks(GSnippetsReviews::$iShopId, (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL_DELAY'] * 86400 ), $iTime, GSnippetsReviews::$iCurrentLang);
			$bForceCallbacks = false;
		}

		if (!empty($aCallbacks)) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// set
			$aTmpCbk = array();
			$aParams = array();

			if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC'])
				&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_CC'])
			) {
				$aParams['bcc'] = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_CC'];
			}

			// get  subject languages
			$aSubject = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_SUBJECT'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_SUBJECT']) : $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_TRANSLATE'];
			$aCatLabel = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_CAT_LABEL'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_CAT_LABEL']) : $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_CAT_LABEL'];
			$aProdLabel = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_PROD_LABEL'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_PROD_LABEL']) : $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_PROD_LABEL'];
			$aSentence = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_SENTENCE'])? unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REMINDER_MAIL_SENTENCE']) : $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_SENTENCE'];

			// include
			require_once(_GSR_PATH_LIB . 'mail-send_class.php');

			foreach ($aCallbacks as $aCallback) {
				// get data
				$aData = unserialize($aCallback['data']);

				if (!empty($aData) && is_array($aData)) {
					// get data
					$aParams['aSubject']= $aSubject;
					$aParams['aCatLabel']= $aCatLabel;
					$aParams['aProdLabel']= $aProdLabel;
					$aParams['aSentence']= $aSentence;
					$aParams['email']   = $aCallback['email'];
					$aParams['id']      = $aCallback['id'];
					$aParams['shopId']  = $aCallback['shopId'];
					$aParams['custId']  = $aCallback['custId'];

					$aData = array_merge($aData, $aParams);

					// use case - check if order status is a valid order status defined by merchant
					$iOrderState = BT_GsrModuleDao::getOrderStatusById($aCallback['id']);

					// send email or set to update this reminder
					if (BT_GsrModuleTools::checkOrderStatus($iOrderState)
						&& GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_REVIEWS']
					) {
						// check if reviews exists for each product
						if (!empty($aData['products'])) {
							foreach ($aData['products'] as $nKey => $aProduct) {
								// if exists a rating for review, delete product of the products list to review
								$aRating = BT_ReviewCtrl::create()->run('existRating', array('iCustomerId' => $aCallback['custId'], 'iProductId' => $aProduct['prodId']));

								if (!empty($aRating['note'])) {
									unset($aData['products'][$nKey]);
								}
							}
						}
						// use case - products order have been already reviewed
						if (empty($aData['products'])) {
							$bSent = true;
							$sStatus = 'reviewed';
						}
						// use case - reminders sent
						else {
							// succeeded
							if (BT_GsrMailSend::create()->run('callback', $aData)) {
								// store each reminder sent to handle the orders selection to send reminder e-mails manually
								BT_GsrModuleDao::addOrderHistory($aCallback['shopId'], $aCallback['id']);

								$bSent = true;
								$sStatus = 'sent';
							}
							// in error
							else {
								$bSent = false;
								$sStatus = 'mailerror';
							}
						}
					}
					// use case - status order is not valid
					else {
						$bSent = false;
						$sStatus = 'order';
					}

					// set LOG data
					$aLogData = array(
						'cbkId' => $aCallback['cbkId'],
						'orderId' => $aCallback['id'],
						'custId' => $aCallback['custId'],
					);

					// use case - email sent
					if ($bSent) {
						// delete sent reminders if not forced reminders
						if (!$bForceCallbacks
							&& BT_GsrModuleDao::deleteCallback(GSnippetsReviews::$iShopId, (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL_DELAY'] * 86400), $iTime, $aCallback['cbkId'])
						) {
							++$iDelete;
						}
					}
					else {
						// update no sent reminders if not forced reminders
						if (!$bForceCallbacks
							&& BT_GsrModuleDao::updateCallback($aCallback['cbkId'], array('date' => ($aCallback['date'] + 86400)))) {
							++$iUpdate;
						}
					}
					$aTmpCbk[$sStatus]['cbk'][] = $aLogData;
					unset($aLogData);
				}
			}

			// use case - display them if only not forced reminders
			if (!$bForceCallbacks) {
				// verbose - number callbacks sent
				BT_Verbose::create($aOptions)->line(__CLASS__, GSnippetsReviews::$oModule->l('Reminder sending => Number of reminders sent', 'admin-send_class') . ' : ' . $iDelete);

				// verbose - number of callbacks sent and affected lines number for deleting
				BT_Verbose::create()->line(__CLASS__, GSnippetsReviews::$oModule->l('Reminder updating => Number of reminders deleted', 'admin-send_class') . ' : ' . $iDelete);

				// verbose - number of callbacks sent and affected lines number for deleting
				BT_Verbose::create()->line(__CLASS__, GSnippetsReviews::$oModule->l('Reminders in error => Number of reminders in error or delayed', 'admin-send_class') . ' : ' . $iUpdate);
			}

			// log report file
			if (!empty($aTmpCbk)) {
				// add counter for each reminder status
				foreach ($aTmpCbk as $sStatus => &$aCallbacks) {
					$aCallbacks['count'] = count($aCallbacks['cbk']);
				}

				// use case - do not write the log report for manual action
				if (!$bForceCallbacks) {
					// include
					require_once(_GSR_PATH_LIB_COMMON . 'serialize.class.php');

					// serialize
					$sContent = BT_Serialize::create()->set(array_merge(array('date' => $iTime), $aTmpCbk));

					if (false !== $sContent) {
						$mReturn = file_put_contents(_GSR_PATH_LOGS . _GSR_CBK_LOGS . GSnippetsReviews::$iShopId . _GSR_CBK_LOGS_EXT, $sContent);

						if (false !== $mReturn) {
							// verbose - number of callbacks sent and affected lines number for deleting
							BT_Verbose::create()->line(__CLASS__, GSnippetsReviews::$oModule->l('Report log file', 'admin-send_class') . ' => "' .  _GSR_CBK_LOGS . GSnippetsReviews::$iShopId . _GSR_CBK_LOGS_EXT . '" ' . GSnippetsReviews::$oModule->l('has been written', 'admin-send_class') . ' : ' . date('d-m-Y H:i:s', $iTime));
						}
					}
					unset($sContent);
				}
				$aAssign['aReminders'] = $aTmpCbk;
				unset($aTmpCbk);
			}
			unset($aCallbacks);
			unset($aOptions);
		}
		else {
			// verbose - no callbacks to process
			BT_Verbose::create($aOptions)->line(__CLASS__, GSnippetsReviews::$oModule->l('No reminders to send', 'admin-send_class'));
		}

		return (
			array(
				'tpl'	    => _GSR_TPL_ADMIN_PATH . _GSR_TPL_CRON,
				'assign'	=> $aAssign,
			)
		);
	}

	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oSend;

		if (null === $oSend) {
			$oSend = new BT_AdminSend();
		}

		return $oSend;
	}
}