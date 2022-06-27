<?php
/**
 * rating_class.php file defines all method used for management of rating (add / delete / modify / average)
 */

require_once(dirname(__FILE__) . '/base-review_class.php');

class BT_Rating extends BT_ReviewBase
{
	/**
	 * Magic Method __construct

	 */
	public function __construct()
	{
		// get available serialized keys
		parent::setSerializedKeys();

		// include
		require_once(dirname(__FILE__) . '/rating-dao_class.php');

		// get DAO object
		$this->oDAO = BT_RatingDao::create();
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}


	/**
	 * add() method adds a new rating
	 *
	 * @param int $iShopId : shop id
	 * @param int $iProdId : product id
	 * @param int $iLangId : lang id
	 * @param int $iNote : note of product
	 * @param int $iCustId : cust id
	 * @param bool $bActive
	 * @param int $iDate
	 * @param array $aData
	 * @return mixed : false or last insert id
	 */
	public function add($iShopId, $iProdId, $iLangId, $iNote, $iCustId, $bActive = 1, $iDate = null, array $aData = null)
	{
		$mCheck = true;

		if (null !== parent::$aSerializedKeys && !empty($aData)) {
			$mCheck = parent::check($aData);
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->add($iShopId, $iProdId, $iLangId, $iNote, $iCustId, $bActive, $iDate, $aData);
		}

		return $mCheck;
	}

	/**
	 * delete() method deletes rating by rating or product id
	 *
	 * @param int $iId : rating or product id
	 * @param bool $bByProduct : delete ratings of product
	 * @return bool
	 */
	public function delete($iId, $bByProduct = false)
	{
		return (
			$this->oDAO->delete($iId, $bByProduct)
		);
	}

	/**
	 * update() method update a rating
	 *
	 * @param int $iRatingId : rating id
	 * @param array $aData
	 * @return bool
	 */
	public function update($iRatingId, array $aData)
	{
		// set
		$bCheck = true;

		if (null !== parent::$aSerializedKeys) {
			// check data from a rating
			if (isset($aData['data']) && is_array($aData['data'])) {
				$bCheck = parent::check($aData['data']);
			}
		}

		if ($bCheck === true) {
			$bCheck = $this->oDAO->update($iRatingId, $aData);
		}

		return $bCheck;
	}

	/**
	 * setStatus() method activates / deactivates by rating or product id
	 *
	 * @param int $iShopId :
	 * @param int $iStatus : status - 0 or 1
	 * @param int $iId : rating or product id
	 * @param bool $bByProduct : delete ratings of product
	 * @return bool
	 */
	public function setStatus($iShopId, $iStatus, $iId, $bByProduct = false)
	{
		return (
			$this->oDAO->setStatus($iShopId, $iStatus, $iId, $bByProduct)
		);
	}

	/**
	 * isCustomerRating() method checks if customer already rated
	 *
	 * @param int $iShopId
	 * @param int $iCustId
	 * @param int $iProductId
	 * @return array
	 */
	public function isCustomerRating($iShopId, $iCustId, $iProductId)
	{
		return (
			$this->oDAO->isCustomerRating($iShopId, $iCustId, $iProductId)
		);
	}

	/**
	 * count() method returns related rating data
	 *
	 * @param int $iShopId
	 * @param int $iProdId
	 * @param array $aParams
	 * @return int
	 */
	public function count($iShopId, $iProdId = null, array $aParams = null)
	{
		return (
			$this->oDAO->count($iShopId, $iProdId, $aParams)
		);
	}


	/**
	 * average() method returns average for product rating
	 *
	 * @param int $iShopId
	 * @param array $aParams
	 * @return int
	 */
	public function average($iShopId, array $aParams = null)
	{
		return (
			$this->oDAO->average($iShopId, $aParams)
		);
	}


	/**
	 * get() method returns related rating data
	 *
	 * @param array $aParams
	 * @return array
	 */
	public function get(array $aParams = null)
	{
		return (
			$this->oDAO->get($aParams)
		);
	}


	/**
	 * create() method returns singleton
	 *
	 * @param
	 * @return array
	 */
	public static function create()
	{
		static $oRating;

		if (null === $oRating) {
			$oRating = new BT_Rating();
		}
		return $oRating;
	}
}