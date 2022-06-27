<?php
/**
 * review-delete_class.php file defines method to delete comments of reviews
 */

class BT_ReviewDelete implements BT_IAdmin
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
	 * run() method delete asked content
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
			case 'review'		: // use case - delete comment
				// execute match function
				$aDisplayInfo = call_user_func_array(array($this, 'delete' . ucfirst($sType)), array($aParam));
				break;
			default :
				break;
		}
		return (
			$aDisplayInfo
		);
	}

	/**
	 * deleteReview() method delete rating and review
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function deleteReview(array $aPost)
	{
		// clean headers
		@ob_end_clean();

		// set
		$aUpdateInfo = array();
		$bDelete = false;

		try {
			// use case - check rating Id - one delete
			if (Tools::getIsset('iRatingId')) {
				// use case - check review Id or list of review id
				if (!is_numeric(Tools::getValue('iRatingId'))
					&& !is_array(Tools::getValue('iRatingId'))
				) {
					throw new Exception(GSnippetsReviews::$oModule->l('Rating Id is not valid', 'review-delete_class') . '.', 110);
				}
				$mId = Tools::getValue('iRatingId');
			}
			// use case - check review list - many delete
			if (Tools::getIsset('bt_check-rating')) {
				if (!is_array(Tools::getValue('bt_check-rating'))) {
					throw new Exception(GSnippetsReviews::$oModule->l('Rating list is not valid', 'review-delete_class') . '.', 111);
				}
				$mId = Tools::getValue('bt_check-rating');
			}
			if (empty($mId)) {
				throw new Exception(GSnippetsReviews::$oModule->l('Rating Id is not numeric or not a valid ID list', 'review-delete_class') . '.', 112);
			}
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// delete rating & reviews
			$bDelete = BT_ReviewCtrl::create()->run('deleteReview', array('id' => $mId, 'byRating' => true));
		}
		catch (Exception $e) {
			$aUpdateInfo['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
		}

		// require review configure class - to factorise
		require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

		// get run of review tool display in order to display first page of moderation
		$aInfo = BT_ReviewDisplay::create()->run('moderation', array('iPage' => (Tools::getIsset('iPage')? intval(Tools::getValue('iPage')) : 1)));

		// use case - empty error and updating status
		$aInfo['assign'] = array_merge($aInfo['assign'], array(
			'bDelete' => $bDelete,
			'bUpdate' => (empty($aUpdateInfo['aErrors']) ? true : false),
		), $aUpdateInfo);

		// destruct
		unset($aUpdateInfo);

		return $aInfo;
	}

	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oDelete;

		if (null === $oDelete) {
			$oDelete = new BT_ReviewDelete();
		}
		return $oDelete;
	}
}