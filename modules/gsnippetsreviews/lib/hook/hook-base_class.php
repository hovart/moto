<?php
/**
 * hook-base_class.php file defines controller which manage hooks sequentially
 */

abstract class BT_GsrHookBase
{
	/**
	 * Magic Method __construct assigns few information about hook
	 *
	 * @param string $sHookAction
	 */
	abstract public function __construct($sHookAction);

	/**
	 * run() method execute hook
	 *
	 * @param array $aParams
	 * @return array
	 */
	abstract public function run(array $aParams = null);


	/**
	 * generateVoucher() method generates a voucher code when a comment is posted
	 *
	 * @param int $iReviewId
	 * @param int $iCustomerId
	 * @param string $sType
	 * @return string code name
	 */
	protected function generateVoucher($iReviewId, $iCustomerId, $sType = 'comment')
	{
		$sCodeName = '';

		// use case - generate voucher if activated
		$aVoucher = BT_GsrModuleTools::getEnableVouchers($sType);

		if (!empty($aVoucher)) {
			// require
			require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

			// generate voucher
			$sCodeName = BT_Voucher::create()->add($sType, GSnippetsReviews::$iShopId, $iCustomerId, $iReviewId);
		}
		unset($aVoucher);

		return $sCodeName;
	}

	/**
	 * sendNotification() method sends a notification to the customer (reviews validated or incentive voucher created) or to the merchant (abusive report sent or a new review sent)
	 *
	 * @param string $sType
	 * @param array $aData
	 * @return bool
	 */
	protected function sendNotification($sType, array $aData)
	{
		// include
		require_once(_GSR_PATH_LIB . 'mail-send_class.php');

		// send notification
		return (
			BT_GsrMailSend::create()->run($sType , $aData)
		);
	}

	/**
	 * setSecureKey() method sets a secure key when we use report or review form
	 *
	 * @param mixed $mEltId
	 * @param bool $bSetProperty
	 * @return string
	 */
	protected function setSecureKey($mEltId, $bSetProperty = true)
	{
		$sEncoded = BT_GsrModuleTools::setSecureKey($mEltId);

		if ($bSetProperty) {
			$this->sSecureKey = $sEncoded;
		}

		return $sEncoded;
	}
}