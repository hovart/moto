<?php
/**
 * review_class.php file defines method for reviews management
 */

require_once(dirname(__FILE__) . '/base-review_class.php');


class BT_Review extends BT_ReviewBase
{
	/**
	 * Magic Method __construct
	 */
	private function __construct()
	{
		// get available serialized keys
		parent::setSerializedKeys();

		// include
		require_once(dirname(__FILE__) . '/review-dao_class.php');

		// get DAO object
		$this->oDAO = BT_ReviewDao::create();
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}


	/**
	 * add() method add a new review
	 *
	 * @param int $iShopId : shop id
	 * @param int $iRatingId : rating id
	 * @param int $iProdId : product id
	 * @param int $iCustId : customer id
	 * @param int $iLangId : lang id
	 * @param array $aData : data
	 * @param bool $bStatus
	 * @param int $iDate
	 * @return bool
	 */
	public function add($iShopId, $iRatingId, $iProdId, $iCustId, $iLangId, array $aData, $bStatus = 1, $iDate = null)
	{
		// set
		$mCheck = true;

		if (null !== parent::$aSerializedKeys && !empty($aData)) {
			$mCheck = parent::check($aData);
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->add($iShopId, $iRatingId, $iProdId, $iCustId, $iLangId, $aData, $bStatus, $iDate);
		}

		return $mCheck;
	}

	/**
	 * addAbuse() method add an abuse for one review
	 *
	 * @param int $iId : review id
	 * @param int $iCustId : customer id
	 * @param array $aData : include comment and others data from the customer who report the review
	 * @return bool
	 */
	public function addAbuse($iId, $iCustId, array $aData = array())
	{
		// set
		$mCheck = true;

		if (null !== parent::$aSerializedKeys && !empty($aData)) {
			$mCheck = parent::check($aData);
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->addAbuse($iId, $iCustId, $aData);
		}

		return $mCheck;
	}

	/**
	 * update() method update a review
	 *
	 * @param mixed $mReviewsId : reviews id or list of review id
	 * @param array $aData
	 * @return bool
	 */
	public function update($mReviewsId, $aData)
	{
		// set
		$mCheck = true;

		if (null !== parent::$aSerializedKeys) {
			// check data from a review
			if (isset($aData['data']) && is_array($aData['data'])) {
				$mCheck = parent::check($aData['data']);
			}
		}

		if ($mCheck === true) {
			$mCheck = $this->oDAO->update($mReviewsId, $aData);
		}

		return $mCheck;
	}

	/**
	 * delete() method delete rating by rating or product id
	 *
	 * @param mixed $mId : review / product id or list
	 * @param bool $bByProduct : delete reviews from product
	 * @param bool $bByRating : delete reviews from rating
	 * @return bool
	 */
	public function delete($mId, $bByProduct = false, $bByRating = false)
	{
		return (
			$this->oDAO->delete($mId, $bByProduct, $bByRating)
		);
	}

	/**
	 * setStatus() method activate / deactivate by review or product id
	 *
	 * @param int $iShopId : shop id
	 * @param int $iStatus : status = 0 or 1
	 * @param int $iId : rating or product id
	 * @param bool $bByProduct : delete ratings of product
	 * @return bool
	 */
	public function setStatus($iShopId, $iStatus, $iId, $bByProduct = false)
	{
		return (
			$this->oDAO->setStatus($iStatus,$iId, $bByProduct)
		);
	}

	/**
	 * isCustomerReview() method check if customer already reviewed
	 *
	 * @param int $iShopId
	 * @param int $iCustId
	 * @param int $iProductId
	 * @return bool
	 */
	public function isCustomerReview($iShopId, $iCustId, $iProductId)
	{
		return (
			$this->oDAO->isCustomerReview($iShopId, $iCustId, $iProductId)
		);
	}

	/**
	 * isFbPostExist() method check if Fb post already exists
	 *
	 * @param int $iReviewId
	 * @param bool $bByRating
	 * @return bool
	 */
	public function isFbPostExist($iReviewId, $bByRating = false)
	{
		return (
			$this->oDAO->isFbPostExist($iReviewId, $bByRating)
		);
	}

	/**
	 * count() method returns related reviews data
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
	 * get() method returns related review data
	 *
	 * @param array $aParams
	 * @return array
	 */
	public function get(array $aParams = null)
	{
		// get reviews
		$aReviews = $this->oDAO->get($aParams);

		if (!empty($aReviews)) {
			foreach ($aReviews as &$aReview) {
				// format  serialized data
				if ($this->unserialize('data', $aReview)) {
					if (!empty($aReview['fbPostId']) && !empty($aReview['fbPageId'])) {
						$aReview['data']['sFbPostUrl'] = BT_GsrModuleTools::formatFbPostUrl($aReview['fbPageId'], $aReview['fbPostId']);
					}
				}
				// format date for displaying on Front
				if (isset($aParams['date']) && is_string($aParams['date'])) {
					if (isset($aParams['locale']) && (is_string($aParams['locale']) || !empty($aParams['forceDate']))) {
						$aReview['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateAdd'], null, $aParams['locale']);
						$aReview['dateUpd'] = BT_GsrModuleTools::formatTimestamp($aReview['dateUpd'], null, $aParams['locale']);
					}
					else {
						$aReview['dateAdd'] = date($aParams['date'], $aReview['dateAdd']);
						$aReview['dateUpd'] = date($aParams['date'], $aReview['dateUpd']);
					}
				}
				// get customer address
				if (!empty($aParams['address'])) {
					$aReview['address'] = BT_GsrModuleTools::getCustomerAddressForReview($aReview['custId'], $aReview['langId']);
				}
			}

			if (count($aReviews) == 1 && empty($aParams['bIndexArray'])) {
				$aReviews = $aReviews[0];
			}
		}

		return $aReviews;
	}

	/**
	 * create() method returns singleton
	 *
	 * @return array
	 */
	public static function create()
	{
		static $oReview;

		if (null === $oReview) {
			$oReview = new BT_Review();
		}
		return $oReview;
	}
}