<?php
/**
 * review-ctrl_class.php file defines all method manage reviews and ratings
 */

class BT_ReviewCtrl
{  
	/**
	 * @var array $aErrors : define errors array
	 */
	public $aErrors = array();
	
	/**
	 * Magic Method __construct
	 *
	 */
	public function __construct()
	{
		// include matched object
		require_once(dirname(__FILE__) . '/rating_class.php');
		require_once(dirname(__FILE__) . '/reply_class.php');
		require_once(dirname(__FILE__) . '/review_class.php');
	}

	/**
	 * Magic Method __destruct
	 *
	 */
	public function __destruct()
	{

	}


	/**
	 * run() method execute all method linked to ratings and reviews and after-sales reply
	 *
	 * @param string $sAdminType : type of interface to display
	 * @param array $aRequest : request
	 * @return array $aDisplay : empty => false / not empty => true
	 */
	public function run($sAction, $aRequest)
	{
		// set
		$mResult = null;
		
		switch ($sAction) {
			case 'add' :
				// add a review
				$mResult = call_user_func_array(array($this, 'add'), array($aRequest));
				break;
			case 'report' :
				// report a review
				$mResult = call_user_func_array(array($this, 'report'), array($aRequest));
				break;
			case 'average' :
				// get average of one review / category / all
				$mResult = call_user_func_array(array($this, 'averageRating'), array($aRequest));
				break;
			case 'getDistribution' :
				// get distribution on one product
				$mResult = call_user_func_array(array($this, 'getDistribution'), array($aRequest));
				break;
			case 'getLastReview' :
				// get last review
				$mResult = call_user_func_array(array($this, 'getLastReview'), array($aRequest));
				break;
			case 'getLastRating' :
				// get last rating
				$mResult = call_user_func_array(array($this, 'getLastRating'), array($aRequest));
				break;
			case 'getReviewsOnProduct' :
				// get ratings & reviews from a product page
				$mResult = call_user_func_array(array($this, 'getReviewsOnProduct'), array($aRequest));
				break;
			case 'getReviews' :
				// get reviews
				$mResult = call_user_func_array(array($this, 'getReviews'), array($aRequest));
				break;
			case 'getRatings' :
				// get ratings
				$mResult = call_user_func_array(array($this, 'getRatings'), array($aRequest));
				break;
			case 'existReview' :
				// check if review exists
				$mResult = call_user_func_array(array($this, 'isExistReview'), array($aRequest));
				break;
			case 'existRating' :
				// check if rating exists
				$mResult = call_user_func_array(array($this, 'isExistRating'), array($aRequest));
				break;
			case 'countRatings' :
				// count ratings on one product or category
				$mResult = call_user_func_array(array($this, 'countRatings'), array('id' => (!empty($aRequest['productId'])? $aRequest['productId'] : null), 'param' => $aRequest));
				break;
			case 'countReviews' :
				// count reviews on one product or category
				$mResult = call_user_func_array(array($this, 'countReviews'), array('id' => (!empty($aRequest['productId'])? $aRequest['productId'] : null), 'param' => $aRequest));
				break;
			case 'updateReview' :
				// update one review
				$mResult = call_user_func_array(array($this, 'updateReview'), array($aRequest['id'], $aRequest));
				break;
			case 'updateReply' :
				// update a after-sales reply to a review
				$mResult = call_user_func_array(array($this, 'updateReply'), array($aRequest['id'], $aRequest));
				break;
			case 'updateRating' :
				// update one rating
				$mResult = call_user_func_array(array($this, 'updateRating'), array($aRequest['id'], $aRequest));
				break;
			case 'deleteReview' :
				// delete one review
				$mResult = call_user_func_array(array($this, 'deleteReview'), array($aRequest['id'], $aRequest));
				break;
			default :
				break;
		}

		return $mResult;
	}

	/**
	 * getClass() method return an instance of rating / review / reply object
	 *
	 * @param string $sType
	 * @return obj : rating / review / reply object
	 */
	public function getClass($sType)
	{
		$oObj = null;

		switch($sType) {
			case 'rating':
				$oObj = BT_Rating::create();
				break;
			case 'review':
				$oObj = BT_Review::create();
				break;
			case 'reply':
				$oObj = BT_Reply::create();
				break;
			default:
				break;
		}
		return $oObj;
	}

	/**
	 * add() method add a rating and / or a review
	 *
	 * @param array $aRequest
	 * @return array : with errors if necessary
	 */
	private function add(array $aRequest)
	{
		// set
		$aInfo  = array();

		// flush
		$this->aErrors = array();

		// use case  - check customer
		if (empty($aRequest['iCustomerId'])
			|| (!empty($aRequest['iCustomerId'])
			&& !BT_GsrModuleTools::checkCustomer($aRequest['iCustomerId']))
		) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('You are not logged as customer or you didn\'t buy this product', 'review-ctrl_class'), 'code' => 180);
		}
		// use case - check product id
		if (empty($aRequest['iProductId']) || (!empty($aRequest['iProductId']) && !is_numeric($aRequest['iProductId']))) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Product Id is not valid', 'review-ctrl_class'), 'code' => 181);
		}
		// use case - check content review
		if (!isset($aRequest['iRating'])
			|| (isset($aRequest['iRating'])
			&& (!is_numeric($aRequest['iRating'])
			|| $aRequest['iRating'] == 0))
		) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Rating is not a numeric', 'review-ctrl_class'), 'code' => 182);
		}
		// use case - check title and comment only if customer have already rated and add a comment
		if (!empty($aRequest['bCheckFieldText'])) {
			if (empty($aRequest['sTitle'])) {
				$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Title is not filled', 'review-ctrl_class'), 'code' => 183);
			}
			if (empty($aRequest['sComment'])) {
				$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Comment is not filled', 'review-ctrl_class'), 'code' => 184);
			}
		}
		// use case - check lang ID and shop Id
		if (empty($aRequest['iLangId']) || empty($aRequest['iShopId'])) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Shop Id or Language ID has not been filled out', 'review-ctrl_class'), 'code' => 185);
		}

		// use case - no errors
		if (empty($this->aErrors)) {
			$mLastInsertId = false;
			$bInsert = false;
			$bAlreadyRated = false;

			// detect if date is passed
			$iDate =  !empty($aRequest['iDate']) && is_numeric($aRequest['iDate']) ? $aRequest['iDate'] : null;

			// use case - validate obj
			if (($oProduct = BT_GsrModuleTools::isProductObj($aRequest['iProductId'], $aRequest['iLangId'], true)) != false) {
				$iCategory = $oProduct->id_category_default;
			}
			else {
				$iCategory = 1;
			}
			unset($oProduct);

			// set transaction
			Db::getInstance()->Execute('BEGIN');

			// check rating
			$aRating = $this->getClass('rating')->isCustomerRating($aRequest['iShopId'], $aRequest['iCustomerId'], $aRequest['iProductId']);

			if (!empty($aRating['id'])) {
				$mLastInsertId = $aRating['id'];
				$bAlreadyRated = true;
			}
			else {
				// add rating
				$mLastInsertId = $this->getClass('rating')->add($aRequest['iShopId'], $aRequest['iProductId'], $aRequest['iLangId'], $aRequest['iRating'], $aRequest['iCustomerId'], 1, $iDate);

				if (!$mLastInsertId) {
					$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('An error occurred during adding note', 'review-ctrl_class'), 'code' => 186);
				}
			}
			unset($aRating);

			// use case - add review
			if ($mLastInsertId) {
				$bInsert = true;

				// use case - add a comment
				if (!empty($aRequest['sTitle']) && !empty($aRequest['sComment'])) {
					// use case - not posted
					if (!self::run('existReview', array('iCustomerId' => $aRequest['iCustomerId'], 'iProductId' => $aRequest['iProductId']))) {

						$aData = array(
							'sTitle'    => strip_tags($aRequest['sTitle']),
							'sComment'  => strip_tags($aRequest['sComment']),
							'iLangId'   => $aRequest['iLangId'],
							'sLangIso'  => $aRequest['sLangIso']
						);

						// set active status or not with moderation
						$bStatus = (GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_COMMENTS_APPROVAL'] && empty($aRequest['bForceModerate']))? 0 : 1;

						// use case - insert review
						$bInsert = $this->getClass('review')->add($aRequest['iShopId'], $mLastInsertId, $aRequest['iProductId'], $aRequest['iCustomerId'], $aRequest['iLangId'], $aData, $bStatus, $iDate);

						if ($bInsert === true) {
							// get last insert id
							$aInfo['iLastInsertId'] = Db::getInstance()->Insert_ID();
						}
						// use case - internal error
						else {
							$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('An internal error occurred, your review has not been added', 'review-ctrl_class'), 'code' => 187);
						}

						unset($bStatus);
						unset($aData);
					}
					else {
						$bInsert = false;
						$sMsg = GSnippetsReviews::$oModule->l('You have already posted a comment', 'review-ctrl_class') . (!empty($aRequest['bBackOffice'])? ' ' . GSnippetsReviews::$oModule->l('for this shop, product and this customer', 'review-ctrl_class') : '');
						$this->aErrors[] = array('msg' => $sMsg, 'code' => 188);
					}
				}
				elseif ($bAlreadyRated) {
					$sMsg = GSnippetsReviews::$oModule->l('You have already noted this product', 'review-ctrl_class') . (!empty($aRequest['bBackOffice'])? ' ' . GSnippetsReviews::$oModule->l('for this shop, product and this customer', 'review-ctrl_class') : '');
					$this->aErrors[] = array('msg' => $sMsg, 'code' => 189);
				}
			}
			// use case - commit transaction
			$bInsert = $bInsert? Db::getInstance()->Execute('COMMIT') : Db::getInstance()->Execute('ROLLBACK');
		}
		
		return $aInfo;
	}

	/**
	 * report() method insert a review as abuse content
	 *
	 * @param array $aRequest
	 * @return bool
	 */
	private function report(array $aRequest)
	{
		// set
		$aInfo  = array();
		$bInsert = false;

		// flush
		$this->aErrors = array();

		// use case  - check review id
		if (empty($aRequest['iReviewId'])
			|| (!empty($aRequest['iReviewId'])
			&& !BT_Review::create()->get(array('id' => $aRequest['iReviewId'])))
			|| empty($aRequest['iCustomerId'])
		) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('The review and or customer ID doesn\'t match', 'review-ctrl_class'), 'code' => 160);
		}
		else {
			$aData = !empty($aRequest['aData'])? $aRequest['aData'] : array();

			$bInsert = $this->getClass('review')->addAbuse($aRequest['iReviewId'], $aRequest['iCustomerId'], $aData);
		}

		return $bInsert;
	}

	/**
	 * averageRating() method returns average of ratings
	 *
	 * @param array $aRequest
	 * @return mixed : int or float
	 */
	private function averageRating(array $aRequest)
	{
		// set
		$aAverage = array('iAverage' => 0, 'fDetailAverage' => 0);

		// use case - check product id
		if (!empty($aRequest['iProductId']) && !is_numeric($aRequest['iProductId'])) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Product Id is not valid', 'review-ctrl_class'), 'code' => 190);
		}
		elseif (!empty($aRequest['iCatId']) && !is_numeric($aRequest['iCatId'])) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Category Id is not valid', 'review-ctrl_class'), 'code' => 191);
		}
		elseif (!empty($aRequest['iBrandId']) && !is_numeric($aRequest['iBrandId'])) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Manufacturer Id is not valid', 'review-ctrl_class'), 'code' => 192);
		}
		else {
			// get the average
			$aAverage['iAverage'] = $this->getClass('rating')->average(GSnippetsReviews::$iShopId, $aRequest);
			$aAverage['fDetailAverage'] = number_format($aAverage['iAverage'], 1, ',', ',');

			// get floating
			$fFloatingPoint = fmod($aAverage['iAverage'], 1);

			// detect round with 1 decimal
			if ($fFloatingPoint != 0) {
				if ($fFloatingPoint < 0.25){
					$fFloatingPoint = 0;
				}
				elseif ($fFloatingPoint < 0.5) {
					$fFloatingPoint = 0.5;
				}
				elseif ($fFloatingPoint < 0.75) {
					$fFloatingPoint = 0.5;
				}
				else {
					$fFloatingPoint = 1;
				}
				// check - half of star or not
				if ($fFloatingPoint == 0.5 && empty($aRequest['bNotJQueryRating'])) {
					$aAverage['bHalf'] = true;
					$aAverage['iAverage'] = (floor($aAverage['iAverage']) + $fFloatingPoint) * 2;
				}
				else {
					$aAverage['iAverage'] = floor($aAverage['iAverage']) + $fFloatingPoint;
				}
			}
			else {
				$aAverage['iAverage'] = (int) $aAverage['iAverage'];
				$aAverage['fDetailAverage'] = $aAverage['iAverage'];
			}
		}

		return $aAverage;
	}


	/**
	 * getDistribution() method returns distribution of ratings
	 *
	 * @param array $aRequest
	 * @return int
	 */
	private function getDistribution(array $aRequest)
	{
		// set
		$aDistribution = array();

		// use case - check title review
		if (empty($aRequest['iProductId'])
			|| (!empty($aRequest['iProductId'])
			&& !is_numeric($aRequest['iProductId']))
		) {
			$this->aErrors[] = array('msg' => GSnippetsReviews::$oModule->l('Product Id is not valid', 'review-ctrl_class'), 'code' => 170);
		}
		else {
			$aQuery = array(
				'shopId'    => GSnippetsReviews::$iShopId,
				'productId' => $aRequest['iProductId'],
				'fields'    => 'RTG_NOTE as note, count(RTG_PROD_ID) as nb',
				'groupBy'   => 'note',
				'orderBy'   => 'note DESC',
			);
			if (!empty($aRequest['langId'])) {
				$aQuery['langId'] = GSnippetsReviews::$iCurrentLang;
			}

			// get ratings distribution for current product
			$aRatings = $this->getClass('rating')->get($aQuery);

			if (!empty($aRatings)) {
				// re sort array key / value
				foreach ($aRatings as $aRating) {
					$aTmp[$aRating['note']] = $aRating['nb'];
				}
				unset($aRatings);

				for ($i = _GSR_MAX_RATING; 1 <= $i; --$i) {
					if (array_key_exists($i, $aTmp)) {
						$aDistribution[$i] = $aTmp[$i];
					}
					else {
						$aDistribution[$i] = 0;
					}
				}
			}
		}

		return $aDistribution;
	}

	/**
	 * getReviewsOnProduct() method returns ratings and reviews of product for the current page
	 *
	 * @param array $aRequest
	 * @return array
	 */
	private function getReviewsOnProduct(array $aRequest)
	{
		// set
		$aRatings = array();

		// get product ID
		$iProductId = !empty($aRequest['productId'])? $aRequest['productId'] : Tools::getValue('id_product');

		// use case  - check product
		if ($iProductId) {
			// use case - change the field definition in order to avoid any conflict on the same field name between different tables
			BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as afsId');

			// get ratings
			$aParams = array(
				'shopId'        => GSnippetsReviews::$iShopId,
				'productId'     => $iProductId,
				'customer'      => ((!isset($aRequest['bRatingCustomer'])? false : $aRequest['bRatingCustomer'])),
				'table' => array(
					array(
						'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
						'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
					)
				)
			);
			$aRatings = $this->getClass('rating')->get(array_merge($aParams, $aRequest));

			unset($aParams);

			if (!empty($aRatings)) {
				// set locale for review date
				$sLocale = setlocale(LC_ALL, GSnippetsReviews::$sCurrentLang);

				// get each associated review
				$aParams = array(
					'shopId'    => GSnippetsReviews::$iShopId,
					'customer'  => ((!isset($aRequest['bCommentCustomer'])? true : $aRequest['bCommentCustomer'])),
					'report'    => ((!isset($aRequest['report'])? false : $aRequest['report']))
				);

				foreach ($aRatings as $nKey => &$aRating) {
					// unserialize data
					BT_ReviewCtrl::create()->getClass('rating')->unserialize('data', $aRating);
					BT_ReviewCtrl::create()->getClass('rating')->unserialize('replyData', $aRating);

					// get customer's address + format date + format reply comment
					$aRating['date'] = date('Y-m-d', $aRating['dateAdd']);
					$aRating['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['dateAdd'], null, $sLocale);
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_ADDRESS'])) {
						$aRating['address'] = BT_GsrModuleTools::getCustomerAddressForReview($aRating['custId'], GSnippetsReviews::$iCurrentLang);
					}
					$aRating['replyDateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['replyDateAdd'], null, $sLocale);
					$aRating['replyData']['sComment'] = str_replace("\n", "<br/>", trim($aRating['replyData']['sComment']));

					// get each associated review
					$aParams['ratingId'] = $aRating['id'];

					if (!empty($aRequest['langId'])) {
						$aParams['langId'] = GSnippetsReviews::$iCurrentLang;
					}
					$aRating['review'] = $this->getClass('review')->get($aParams);

					unset($aParams);

					// no review
					if (empty($aRating['review'])) {
						if (!empty($aRequest['bOnlyReview'])) {
							unset($aRatings[$nKey]);
						}
					}
					else {
						$aRating['review']['date'] = date('Y-m-d', $aRating['review']['dateAdd']);
						$aRating['review']['dateAdd'] = BT_GsrModuleTools::formatTimestamp($aRating['review']['dateAdd'], null, $sLocale);
					}
				}
			}
		}

		return $aRatings;
	}


	/**
	 * getLastReview() method returns last rating and review of product
	 *
	 * @param array $aRequest
	 * @return array
	 */
	private function getLastReview(array $aRequest)
	{
		// set
		$aLastRating = array();

		$aParams = array(
			'shopId'    => GSnippetsReviews::$iShopId,
			'productId' => Tools::getValue('id_product'),
			'limit'     => 1,
		);

		$aLastReview = $this->getClass('review')->get(array_merge($aParams, $aRequest, array('orderBy' => 'r.RVW_ID DESC')));

		if (!empty($aLastReview)) {
			// get last review
			$aLastRating = $this->getClass('rating')->get(array('id' => $aLastReview['ratingId'], 'customer' => true));

			if (!empty($aLastReview['dateAdd'])) {
				$aLastReview['date'] = date('Y-m-d', $aLastReview['dateAdd']);
				$iLangId = !empty($aLastRating[0]['langId'])? $aLastRating[0]['langId'] : null;
				$aLastReview['humanDate'] = BT_GsrModuleTools::formatTimestamp($aLastReview['dateAdd'], null, false, $iLangId);
			}

			$aLastRating = $aLastRating[0];

			unset($aLastRating['id']);
		}
		unset($aParams);

		return (
			array_merge($aLastRating, $aLastReview)
		);
	}

	/**
	 * getLastRating() method returns last rating of product
	 *
	 * @param array $aRequest
	 * @return int
	 */
	private function getLastRating(array $aRequest)
	{
		// set
		$aLastRating = array();

		if (!empty($aRequest['id'])) {
			$iProductId = $aRequest['id'];
		}
		else {
			$iProductId = Tools::getValue('id_product');
		}

		// get ratings
		$aParams = array(
			'shopId'    => GSnippetsReviews::$iShopId,
			'productId' => $iProductId,
			'orderBy'   => 'RTG_ID DESC',
			'limit'     => 1,
		);
		$aLastRating = $this->getClass('rating')->get(array_merge($aParams, $aRequest));

		unset($aParams);

		return (
			!empty($aLastRating[0]['note'])? $aLastRating[0]['note'] : _GSR_MAX_RATING
		);
	}

	/**
	 * getRatings() method returns ratings
	 *
	 * @param array $aRequest
	 * @return array
	 */
	private function getRatings(array $aRequest)
	{
		// set
		$aRatings = array();

		// get each ratings
		$aRatings = $this->getClass('rating')->get($aRequest);

		if (!empty($aRatings)) {
			foreach($aRatings as &$aRating) {
				// unserialize data
				BT_ReviewCtrl::create()->getClass('rating')->unserialize('data', $aRating);

				if (!empty($aRequest['rvwData'])) {
					// unserialize data
					BT_ReviewCtrl::create()->getClass('rating')->unserialize('rvwData', $aRating);
				}
				if (!empty($aRequest['replyData'])) {
					// unserialize data
					BT_ReviewCtrl::create()->getClass('rating')->unserialize('replyData', $aRating);
				}
			}

			if (!empty($aRequest['bAssociativeArray']) && count($aRatings) == 1) {
				$aRatings = $aRatings[0];
			}
		}

		return $aRatings;
	}


	/**
	 * getReviews() method returns reviews
	 *
	 * @param array $aRequest
	 * @return array
	 */
	private function getReviews(array $aRequest)
	{
		// get each associated review
		return (
			$this->getClass('review')->get($aRequest)
		);
	}


	/**
	 * isExistReview() method check if customer has already reviewed
	 *
	 * @param array $aRequest
	 * @return bool
	 */
	private function isExistReview(array $aRequest)
	{
		// set
		$bCheck = false;

		if (isset($aRequest['iCustomerId'])
			&& is_numeric($aRequest['iCustomerId'])
			&& isset($aRequest['iProductId'])
			&& is_numeric($aRequest['iProductId'])
		) {
			$bCheck = $this->getClass('review')->isCustomerReview(GSnippetsReviews::$iShopId, $aRequest['iCustomerId'], $aRequest['iProductId']);
		}

		return $bCheck;
	}

	/**
	 * isExistRating() method check if customer has already noted
	 *
	 * @param array $aRequest
	 * @return array
	 */
	private function isExistRating(array $aRequest)
	{
		return (
			$this->getClass('rating')->isCustomerRating(GSnippetsReviews::$iShopId, $aRequest['iCustomerId'], $aRequest['iProductId'])
		);
	}


	/**
	 * countRatings() method returns total of ratings
	 *
	 * @param int $iId
	 * @param  array $aParams
	 * @return int
	 */
	private function countRatings($iId = null, array $aParams = null)
	{
		return (
			$this->getClass('rating')->count(GSnippetsReviews::$iShopId, $iId, $aParams)
		);
	}

	/**
	 * countReviews() method returns total of reviews
	 *
	 * @param int $iProductId
	 * @param  array $aParams
	 * @return int
	 */
	private function countReviews($iProductId = null, array $aParams = null)
	{
		return (
			$this->getClass('review')->count(GSnippetsReviews::$iShopId, $iProductId, $aParams)
		);
	}

	/**
	 * updateReview() method update one or many reviews
	 *
	 * @param mixed $mReviewsId : reviews id or list of review id
	 * @param array $aParams
	 * @return bool
	 */
	private function updateReview($mReviewsId, array $aParams = null)
	{
		return (
			$this->getClass('review')->update($mReviewsId, $aParams)
		);
	}

	/**
	 * updateReply() method update after-sales reply related to a rating
	 *
	 * @param int $iRId : rating or review id
	 * @param array $aParams
	 * @return bool
	 */
	private function updateReply($iRId, array $aParams = null)
	{
		$bResult = false;

		if (!empty($aParams['action'])
			&& in_array($aParams['action'], array('add','update'))
			&& isset($aParams['data'])
		) {
			// set display
			$bDisplay = !empty($aParams['display'])? 1 : 0;

			// set type
			$sType = !empty($aParams['type'])? $aParams['type'] : null;

			if ($aParams['action'] == 'add') {
				$bResult = $this->getClass('reply')->add($iRId, $aParams['data'], $bDisplay, $sType);
			}
			else {
				$bResult = $this->getClass('reply')->update($iRId, $aParams, $bDisplay, $sType);
			}
		}
		else {
			throw new Exception(GSnippetsReviews::$oModule->l('Action and data are not defined well', 'review-update_class') . '.', 260);
		}

		return $bResult;
	}


	/**
	 * updateRating() method update one rating
	 *
	 * @param int $iRatingId : rating id
	 * @param array $aParams
	 * @return bool
	 */
	private function updateRating($iRatingId, array $aParams = null)
	{
		return (
			$this->getClass('rating')->update($iRatingId, $aParams)
		);
	}


	/**
	 * deleteReview() method delete one review or many reviews by rating or product
	 *
	 * @param mixed $mId : review / product id or list
	 * @param array $aParams
	 * @return bool
	 */
	private function deleteReview($mId, array $aParams = null)
	{
		$bByProduct = !empty($aParams['byProduct'])? true : false;
		$bByRating  = !empty($aParams['byRating'])? true : false;

		// delete rating(s) first
		$bDelete = $this->getClass('rating')->delete($mId, $bByProduct);

		// delete reply(s) next
		if (!$bByProduct) {
			$this->getClass('reply')->delete($mId);
		}

		// delete review(s) next
		if ($bDelete) {
			$this->getClass('review')->delete($mId, $bByProduct, $bByRating);
		}

		return $bDelete;
	}

	/**
	 * create() method returns singleton
	 *
	 * @category admin / hook collection
	 * @return obj
	 */
	public static function create()
	{
		static $oCtrl;

		if (null === $oCtrl) {
			$oCtrl = new BT_ReviewCtrl();
		}
		return $oCtrl;
	}
}