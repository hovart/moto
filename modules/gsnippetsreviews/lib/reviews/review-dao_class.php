<?php
/**
 * review-dao_class.php file defines method of management of DATA ACCESS OBJECT
 */

require_once(dirname(__FILE__) . '/base-dao_class.php');

class BT_ReviewDao extends BT_DaoBase
{
	/**
	 * const TBL_ALIAS : alias of table
	 */
	const TBL_ALIAS = 'rvw';

	/**
	 * const TBL_NAME : name of table
	 */
	const TBL_NAME = 'review';

	/**
	 * var array $aFields : fields of table
	 */
	public $aFields = array(
		'id' => 'RVW_ID as id',
		'ratingId' => 'RTG_ID as ratingId',
		'shopId' => 'RVW_SHOP_ID as shopId',
		'productId' => 'RVW_PROD_ID as productId',
		'langId' => 'RVW_LANG_ID as langId',
		'dateAdd' =>'UNIX_TIMESTAMP(RVW_DATE_ADD) as dateAdd',
		'dateUpd' => 'UNIX_TIMESTAMP(RVW_DATE_UPD) as dateUpd',
		'display' => 'RVW_STATUS as status',
		'custId' => 'RVW_CUST_ID as custId',
		'fbPageId' => 'RVW_FB_PAGE_ID as fbPageId',
		'data' => 'RVW_DATA as data'
	);


	/**
	 * Magic Method __construct
	 */
	public function __construct()
	{
		// get fields as string
		$this->getFields();
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
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_review (RVW_SHOP_ID, RTG_ID, RVW_PROD_ID, RVW_LANG_ID, RVW_DATE_ADD, RVW_DATE_UPD, RVW_STATUS, RVW_CUST_ID, RVW_FB_PAGE_ID, RVW_FB_POST_ID, RVW_DATA) '
			. 'VALUES(' . intval($iShopId) . ', "' . intval($iRatingId) . '", "' . intval($iProdId) . '", ' . intval($iLangId) . ', '. ($iDate === null? 'NOW()' : 'FROM_UNIXTIME(' . $iDate . ')') . ', '. ($iDate === null? 'NOW()' : 'FROM_UNIXTIME(' . $iDate . ')') . ', "' . pSQL($bStatus) . '", "' . intval($iCustId) . '", 0, 0, "' . pSQL(serialize($aData)) . '")';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * addAbuse() method add a new abuse for one review
	 *
	 * @param int $iId : review id
	 * @param int $iCustId : customer id
	 * @param array $aData : include comment and others data from the customer who report the review
	 * @return bool
	 */
	public function addAbuse($iId, $iCustId, array $aData = array())
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_review_report (RPT_RVW_ID, RPT_CUST_ID) '
			. 'VALUES(' . intval($iId) . ', ' . intval($iCustId);

		// use case - update title, description
		if (isset($aParam['data'])) {
			$sQuery .= ', "' . pSQL(serialize($aData)) . '"';
		}

		$sQuery .= ')';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * deleteAbuse() method delete abuse flag
	 *
	 * @param int $iId : review id
	 * @return bool
	 */
	public function deleteAbuse($iId)
	{
		$sQuery = 'DELETE FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_review_report WHERE RPT_RVW_ID = ' . intval($iId);

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}


	/**
	 * update() method update a review
	 *
	 * @param mixed $mReviewsId : reviews id or list of review id
	 * @param array $aParam
	 * @return bool
	 */
	public function update($mReviewsId, array $aParam = null)
	{
		$bResult = false;

		// check if update review abuse flag
		if (!empty($aParam['abuse']) && isset($aParam['status'])) {
			// if add abuse flag
			if (empty($aParam['status'])) {
				$bResult = $this->deleteAbuse($mReviewsId);
			}
		}
		else {
			$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' set '
				. 'RVW_DATE_UPD = FROM_UNIXTIME(' . time() . ')';

			// use case - update fb post Id
			if (isset($aParam['fbPostId'])) {
				$sQuery .= ', RVW_FB_POST_ID = ' . intval($aParam['fbPostId']);
			}

			// use case - update fb page Id
			if (isset($aParam['fbPageId'])) {
				$sQuery .= ', RVW_FB_PAGE_ID = ' . intval($aParam['fbPageId']);
			}

			// use case - update title, comment
			if (isset($aParam['data'])) {
				$sQuery .= ', RVW_DATA = "' . pSQL(serialize($aParam['data'])) . '"';
			}

			// use case - update lang id
			if (isset($aParam['langId'])) {
				$sQuery .= ', RVW_LANG_ID = "' . intval($aParam['langId']) . '"';
			}

			// use case - update status
			if (isset($aParam['status'])) {
				$sQuery .= ', RVW_STATUS = "' . pSQL($aParam['status']) . '"';
			}

			$sQuery .= ' WHERE ' . (!empty($aParam['byRating'])? 'RTG_ID ' : 'RVW_ID');

			if (is_array($mReviewsId)) {
				$sQuery .= ' IN(' . implode(',', $mReviewsId) . ')';
			}
			else {
				$sQuery .= ' = ' . intval($mReviewsId);
			}

			$bResult = Db::getInstance()->Execute($sQuery);
		}

		return $bResult;
	}

	/**
	 * delete() method delete a record
	 *
	 * @param mixed $mId : review / product id or list
	 * @param bool $bByProduct
	 * @param bool $bByRating
	 * @return bool
	 */
	public function delete($mId, $bByProduct = false, $bByRating = false)
	{
		// set related field
		$sField = $bByRating ? 'RTG_ID' : ($bByProduct ? 'RVW_PROD_ID' : 'RVW_ID');

		$sQuery = 'DELETE FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' WHERE ' . $sField;

		if (is_array($mId)) {
			$sQuery .= ' IN(' . implode(',', $mId) . ')';
		}
		else {
			$sQuery .= ' = ' . intval($mId);
		}

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * setStatus() method activate or deactivate one / all record(s) in table
	 *
	 * @param int $iShopId : shop id
	 * @param int $iStatus : status value
	 * @param int $iId : rating id
	 * @param int $bByProduct : update ratings of product
	 * @return bool
	 */
	public function setStatus($iShopId, $iStatus, $iId = null, $bByProduct = false)
	{
		$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' SET RVW_STATUS = "' . pSQL($iStatus) . '"'
			. ' WHERE RVW_SHOP_ID = ' . intval($iShopId)
		;

		// use case - id not null
		if( null !== $iId) {
			$sQuery .= ' AND ';

			// set concerned field
			$sField = $bByProduct ? 'RVW_PROD_ID' : 'RTG_ID';

			$sQuery .= $sField . ' = ' . intval($iId);
		}

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * isCustomerReview() method check if customer already reviewed or if review exists
	 *
	 * @param int $iShopId
	 * @param int $iCustId
	 * @param int $iProductId
	 * @return bool
	 */
	public function isCustomerReview($iShopId, $iCustId, $iProductId)
	{
		$sQuery = 'SELECT count(RVW_ID) as count FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME
			.   ' WHERE RVW_SHOP_ID = ' . pSQL($iShopId) . ' AND RVW_PROD_ID = ' . intval($iProductId) . ' AND RVW_CUST_ID = ' . intval($iCustId);

		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['count'])? true : false)
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
		$sField = !empty($bByRating)? 'RTG_ID' : 'RVW_ID';

		$sQuery = 'SELECT count(*) as count FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME
			.   ' WHERE RVW_FB_PAGE_ID <> 0 AND RVW_FB_POST_ID <> 0 AND ' . $sField . ' = ' . intval($iReviewId);

		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['count'])? true : false)
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
		// set variables
		$sFields = isset($aParams['fields']) && is_string($aParams['fields'])? $aParams['fields'] : $this->sFields;
		$sJoin = '';

		// use case - join another table
		if (!empty($aParams['table']) && is_array($aParams['table'])) {
			foreach ($aParams['table'] as $aTable) {
				$sFields .= ' ,' . $aTable['fields'];
				$sJoin .= ' ' . $aTable['join'];
			}
		}
		// use case - product info
		if (!empty($aParams['product'])) {
			if (!empty($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['productlang'])) {
				$sFields .= ', ';
				foreach ($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['productlang'] as $aField) {
					if ($aField['use']) {
						$sFields .= $aField['field'] . ', ';
					}
				}
				if (substr($sFields, -2, 2) == ', ') {
					$sFields = substr($sFields, 0, strlen($sFields) - 2);
				}
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (pl.id_product = '
					. self::TBL_ALIAS . '.RVW_PROD_ID AND pl.id_lang = ' . GSnippetsReviews::$iCurrentLang
					. Shop::addSqlRestrictionOnLang('pl') . ')';
			}
		}
		// use case - customer info
		if (!empty($aParams['customer'])) {
			if (!empty($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['customer'])) {
				$sFields .= ', ';
				foreach ($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['customer'] as $aField) {
					if ($aField['use']) {
						$sFields .= $aField['field'] . ', ';
					}
				}
				if (substr($sFields, -2, 2) == ', ') {
					$sFields = substr($sFields, 0, strlen($sFields) - 2);
				}
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'customer as c ON c.id_customer = ' . self::TBL_ALIAS . '.RVW_CUST_ID';
			}
		}
		// use case - shop info
		if (!empty($aParams['shop'])) {
			if (!empty($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['shop'])) {
				$sFields .= ', ';
				foreach ($GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS']['shop'] as $aField) {
					if ($aField['use']) {
						$sFields .= $aField['field'] . ', ';
					}
				}
				if (substr($sFields, -2, 2) == ', ') {
					$sFields = substr($sFields, 0, strlen($sFields) - 2);
				}
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'shop as s ON s.id_shop = ' . self::TBL_ALIAS . '.RVW_SHOP_ID';
			}
		}
		// use case - report
		if (!empty($aParams['report'])) {
			$sFields .= ', RPT_RVW_ID as reportId';
			$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_review_report ON RPT_RVW_ID = ' . self::TBL_ALIAS . '.RVW_ID';
		}

		// set main body of query
		$sQuery = 'SELECT ' . $sFields . ' FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. $sJoin
		;

		$sQuery .= ' WHERE ';

		// use case - deactivate or activate ratings
		if (isset($aParams['active'])) {
			if ( $aParams['active'] == 0 || $aParams['active'] == 1) {
				$sQuery .= self::TBL_ALIAS . '.RVW_STATUS = "' . pSQL($aParams['active']) . '" ';
			}
			else {
				$sQuery .= ' 1 = 1 ';
			}
		}
		else {
			$sQuery .= self::TBL_ALIAS . '.RVW_STATUS = "1" ';
		}

		// case - one review
		if ((isset($aParams['id']) && $aParams['id'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_ID  = ' . intval($aParams['id']) . ' ';
		}
		// case - one shop
		if ((isset($aParams['shopId']) && $aParams['shopId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_SHOP_ID  = ' . intval($aParams['shopId']) . ' ';
		}
		// case - rating of one review
		if ((isset($aParams['ratingId']) && is_numeric($aParams['ratingId']))) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_ID  = ' . intval($aParams['ratingId']) . ' ';
		}
		// case - reviews of one product
		if ((isset($aParams['productId']) && $aParams['productId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_PROD_ID  = ' . intval($aParams['productId']) . ' ';
		}
		// case - reviews of lang id
		if (isset($aParams['langId']) && $aParams['langId'] !== false) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_LANG_ID  = ' . intval($aParams['langId']) . ' ';
		}
		// case - reviews for one customer
		if ((isset($aParams['custId']) && $aParams['custId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_CUST_ID  = ' . intval($aParams['custId']) . ' ';
		}

		// case - reviews for one Fb post id
		if ((isset($aParams['fbPostId']) && $aParams['fbPostId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RVW_FB_POST_ID  = ' . intval($aParams['fbPostId']) . ' ';
		}

		// case - group by
		if (isset($aParams['groupBy'])) {
			$sQuery .= ' GROUP BY ' . $aParams['groupBy'] . ' ';
		}
		// case - order by
		if (isset($aParams['orderBy'])) {
			$sQuery .= ' ORDER BY ' . $aParams['orderBy'] . ' ';
		}
		// case - limit
		if (isset($aParams['limit'])) {
			$sQuery .= ' LIMIT ' . $aParams['limit'] . ' ';
		}
		// case - interval
		elseif (isset($aParams['interval'])) {
			$sQuery .= 'LIMIT ' . $aParams['interval'] . ' ';
		}

		return (
			Db::getInstance()->ExecuteS($sQuery)
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
		$sQuery = 'SELECT count(*) as count FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME;

		// use case - for category filter
		if (!empty($aParams['catId'])) {
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'category_product ON (id_product = RVW_PROD_ID AND id_category = ' . intval($aParams['catId']) . ')';
		}

		// use case - for manufacturer filter
		if (!empty($aParams['brandId'])) {
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'product ON (id_product = RVW_PROD_ID AND id_manufacturer = ' . intval($aParams['brandId']) . ')';
		}

		// use case - all shop in BO
		if (!empty($aParams['allShop'])) {
			$sQuery .= ' WHERE 1 = 1';
		}
		else {
			$sQuery .= ' WHERE RVW_SHOP_ID = ' . intval($iShopId);
		}

		// use case - deactivate or activate ratings
		if (isset($aParams['active'])) {
			if ( $aParams['active'] == 0 || $aParams['active'] == 1) {
				$sQuery .= ' AND RVW_STATUS = "' . pSQL($aParams['active']) . '" ';
			}
		}
		else {
			$sQuery .= ' AND RVW_STATUS = "1" ';
		}

		if (null !== $iProdId) {
			$sQuery .= ' AND RVW_PROD_ID = ' . intval($iProdId);
		}

		if (null !== $aParams) {
			if (!empty($aParams['langId'])) {
				$sQuery .= ' AND RVW_LANG_ID = ' . intval($aParams['langId']);
			}
		}

		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['count'])? $aResult[0]['count'] : 0)
		);
	}

	/**
	 * formatJoin() method returns the formatted join with another table
	 *
	 * @param string $sTblField
	 * @param string $sJoinType
	 * @return string
	 */
	public function formatJoin($sTblField, $sJoinType = '', $sTableAlias = self::TBL_ALIAS)
	{
		return (
			strtoupper($sJoinType) . ' JOIN ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. ' ON (' . $sTblField . ' = ' . self::TBL_ALIAS . '.RTG_ID)'
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
		static $oReview;

		if (null === $oReview) {
			$oReview = new BT_ReviewDao();
		}
		return $oReview;
	}
}