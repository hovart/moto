<?php
/**
 * reply_class.php file defines all method used for management of after-sales reply (add / delete / update / get)
 */

require_once(dirname(__FILE__) . '/base-review_class.php');

class BT_Reply extends BT_ReviewBase
{
	/**
	 * Magic Method __construct
	 */
	public function __construct()
	{
		// include
		require_once(dirname(__FILE__) . '/reply-dao_class.php');

		// get available serialized keys
		parent::setSerializedKeys();

		// get DAO object
		$this->oDAO = BT_ReplyDao::create();
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}

	/**
	 * add() method add a new after-sales reply
	 *
	 * @param int $iRatingId : rating id
	 * @param array $aData : data
	 * @param bool $bDisplay
	 * @param string $sType : rating or review
	 * @return bool
	 */
	public function add($iRatingId, array $aData, $bDisplay = 0, $sType = 'rating')
	{
		// set
		$mCheck = false;

		if (null !== parent::$aSerializedKeys) {
			$mCheck = parent::check($aData);
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->add($iRatingId, $aData, $bDisplay, $sType);
		}

		return $mCheck;
	}

	/**
	 * delete() method delete reply by rating or review ID
	 *
	 * @param int $iId : rating or product id
	 * @param string $sType : rating or review
	 * @return bool
	 */
	public function delete($iId, $sType = 'rating')
	{
		return (
			$this->oDAO->delete($iId, $sType)
		);
	}

	/**
	 * update() method update after-sales reply related to a rating or review
	 *
	 * @param int $iId : rating or review id
	 * @param array $aData
	 * @param int $bDisplay
	 * @param string $sType : rating or review
	 * @return bool
	 */
	public function update($iId, array $aData = null, $bDisplay = 0, $sType = 'rating')
	{
		// set
		$mCheck = true;

		if (null !== parent::$aSerializedKeys) {
			// check data from a review
			if (!empty($aData['data']) && is_array($aData['data'])) {
				$mCheck = parent::check($aData['data']);
			}
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->update($iId, $aData, $bDisplay, $sType);
		}

		return $mCheck;
	}


	/**
	 * get() method returns related reply data
	 *
	 * @param array $aParams
	 * @return array
	 */
	public function get(array $aParams = null)
	{
		$aReplies = $this->oDAO->get($aParams);

		if (!empty($aReplies)) {
			foreach ($aReplies as &$aReply) {
				//  unserialized data
				$this->unserialize('replyData', $aReply);
				// format date for displaying on Front
				if (isset($aParams['date']) && is_string($aParams['date'])) {
					if (isset($aParams['locale']) && is_string($aParams['locale'])) {
						$aReply['replyDateAdd'] = BT_GsrModuleTools::formatTimestamp($aReply['replyDateAdd'], null, $aParams['locale']);
						$aReply['replyDateAdd'] = BT_GsrModuleTools::formatTimestamp($aReply['replyDateAdd'], null, $aParams['locale']);
					}
					else {
						$aReply['replyDateAdd'] = date($aParams['date'], $aReply['replyDateAdd']);
						$aReply['replyDateAdd'] = date($aParams['date'], $aReply['replyDateAdd']);
					}
				}
			}

			if (count($aReplies) == 1 && empty($aParams['bIndexArray'])) {
				$aReplies = $aReplies[0];
			}
		}

		return $aReplies;
	}


	/**
	 * create() method returns singleton
	 *
	 * @param
	 * @return array
	 */
	public static function create()
	{
		static $oReply;

		if (null === $oReply) {
			$oReply = new BT_Reply();
		}
		return $oReply;
	}
}