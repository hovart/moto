<?php
/**
 * rating-dao_class.php file defines method of management of DATA ACCESS OBJECT
 */

require_once(dirname(__FILE__) . '/base-dao_class.php');

class BT_RatingDao extends BT_DaoBase
{
	/**
	 * const TBL_ALIAS : alias of table
	 */
	const TBL_ALIAS = 'r';

	/**
	 * const TBL_NAME : name of table
	 */
	const TBL_NAME = 'rating';

	/**
	 * var array $aFields : fields of table
	 */
	public $aFields = array(
		'id' => 'RTG_ID as id',
		'shop' => 'RTG_SHOP_ID as shopId',
		'productId' => 'RTG_PROD_ID as prodId',
		'langId' => 'RTG_LANG_ID as langId',
		'dateAdd' =>'UNIX_TIMESTAMP(RTG_DATE_ADD) as dateAdd',
		'note' =>'RTG_NOTE as note',
		'status' => 'RTG_STATUS as status',
		'customer' => 'RTG_CUST_ID as custId',
		'data' => 'RTG_DATA as data',
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
	 * add() method adds a new rating
	 *
	 * @param int $iShopId : shop id
	 * @param int $iProdId : product id
	 * @param int $iLangId : lang id
	 * @param int $iNote : note of product
	 * @param int $iCustId : cust id
	 * @param bool $bActive
	 * @param int $iDate
	 * @return mixed : false or last insert id
	 */
	public function add($iShopId, $iProdId, $iLangId, $iNote, $iCustId, $bActive = 1, $iDate = null, array $aData = null)
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_rating '
			. '(RTG_SHOP_ID, RTG_PROD_ID, RTG_LANG_ID, RTG_NOTE, RTG_STATUS, RTG_CUST_ID, RTG_DATE_ADD, RTG_DATA) '
			. 'VALUES (' . (int)$iShopId . ', ' . (int)$iProdId . ', ' . (int)$iLangId . ', "' . pSQL($iNote) . '", '
			. '"' . $bActive . '", ' . (int)$iCustId . ', '
			. ($iDate === null? 'NOW()' : 'FROM_UNIXTIME(' . (int)$iDate . ')') . ', '
			. '"' . (!empty($aData)? pSQL(serialize($aData)) : '' ) . '")';

		return (
			(Db::getInstance()->Execute($sQuery))? Db::getInstance()->Insert_ID() : false
		);
	}

	/**
	 * delete() method deletes a record
	 *
	 * @param int $mId : rating or product id
	 * @param bool $bByProduct
	 * @return bool
	 */
	public function delete($mId, $bByProduct = false)
	{
		// set related field
		$sField = $bByProduct ? 'RTG_PROD_ID' : 'RTG_ID';

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
	 * update() method update a rating
	 *
	 * @param int $iRatingId : rating id
	 * @param array $aParam
	 * @return bool
	 */
	public function update($iRatingId, array $aParam)
	{
		// set
		$bReturn = false;

		if (!empty($aParam)) {
			$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' set ';

			$bFirst = true;

			// use case - rating
			if (isset($aParam['note'])) {
				$sQuery .= ' RTG_NOTE = "' . pSQL($aParam['note']) . '"';
				$bFirst = false;
			}

			// use case - update lang id
			if (isset($aParam['langId'])) {
				$sQuery .= (!$bFirst?', ' : ' ') . 'RTG_LANG_ID = "' . (int)$aParam['langId'] . '"';
				$bFirst = false;
			}

			// use case - update date add
			if (isset($aParam['date'])) {
				$sQuery .= (!$bFirst?', ' : ' ') . 'RTG_DATE_ADD = FROM_UNIXTIME(' . $aParam['date'] . ')';
				$bFirst = false;
			}

			// use case - update status
			if (isset($aParam['status'])) {
				$sQuery .= (!$bFirst?', ' : ' ') . 'RTG_STATUS = "' . pSQL($aParam['status']) . '"';
				$bFirst = false;
			}

			// use case - update old rating etc...
			if (isset($aParam['data'])) {
				$sQuery .= (!$bFirst?', ' : ' ') . 'RTG_DATA = "' . pSQL(serialize($aParam['data'])) . '"';
			}

			$sQuery .= ' WHERE RTG_ID = ' . intval($iRatingId);

			$bReturn = Db::getInstance()->Execute($sQuery);
		}

		return $bReturn;
	}


	/**
	 * setStatus() method activates or deactivate one / all record(s) in table
	 *
	 * @param int $iShopId : shop id
	 * @param int $iStatus : status value
	 * @param int $iId : rating id
	 * @param int $bByProduct : update ratings of product
	 * @return bool
	 */
	public function setStatus($iShopId, $iStatus, $iId = null, $bByProduct = false)
	{
		$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' SET RTG_STATUS = "' . pSQL($iStatus) . '"'
			. ' WHERE RTG_SHOP_ID = ' . pSQL($iShopId)
		;

		// use case - id not null
		if( null !== $iId) {
			$sQuery .= ' AND ';

			// set concerned field
			$sField = $bByProduct ? 'RTG_PROD_ID' : 'RTG_ID';

			$sQuery .= $sField . ' = ' . (int)$iId;
		}

		return (
			Db::getInstance()->Execute($sQuery)
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
		$sQuery = 'SELECT RTG_NOTE as note, RTG_ID as id FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME
			.   ' WHERE RTG_SHOP_ID = ' . intval($iShopId) . ' AND RTG_PROD_ID = ' . intval($iProductId) . ' AND RTG_CUST_ID = ' . intval($iCustId);

		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['note'])? $aResult[0] : array('note' => 0, 'id' => null))
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
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (pl.id_product = ' . self::TBL_ALIAS . '.RTG_PROD_ID AND pl.id_lang = ' . GSnippetsReviews::$iCurrentLang  . Shop::addSqlRestrictionOnLang('pl') . ')';
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
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'customer as c ON c.id_customer = ' . self::TBL_ALIAS . '.RTG_CUST_ID';
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
				$sJoin .= ' LEFT JOIN ' . _DB_PREFIX_ . 'shop as s ON s.id_shop = ' . self::TBL_ALIAS . '.RTG_SHOP_ID';
			}
		}

		// set main body of query
		$sQuery = 'SELECT ' . $sFields . ' FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. $sJoin
		;

		$sQuery .= ' WHERE ';

		// use case - deactivate or activate ratings
		if (isset($aParams['active']) && ( $aParams['active'] == 0 || $aParams['active'] == 1)) {
			$sQuery .= self::TBL_ALIAS . '.RTG_STATUS = "' . pSQL($aParams['active']) . '" ';
		}
		else {
			$sQuery .= self::TBL_ALIAS . '.RTG_STATUS = "1" ';
		}

		// case - one shop
		if ((isset($aParams['shopId']) && $aParams['shopId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_SHOP_ID  = ' . intval($aParams['shopId']) . ' ';
		}

		// case - one rating
		if ((isset($aParams['id']) && $aParams['id'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_ID  = ' . intval($aParams['id']) . ' ';
		}
		// case - ratings of one product
		if ((isset($aParams['productId']) && $aParams['productId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_PROD_ID  = ' . intval($aParams['productId']) . ' ';
		}
		// case - reviews for one customer
		if ((isset($aParams['custId']) && $aParams['custId'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_CUST_ID  = ' . intval($aParams['custId']) . ' ';
		}
		// case - rating for one language
		if (isset($aParams['langId']) && $aParams['langId'] !== false) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_LANG_ID  = ' . intval($aParams['langId']) . ' ';
		}
		// case - date
		if (isset($aParams['date']) && $aParams['date'] == false) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.RTG_DATE_ADD  = ' . $aParams['date'] . ' ';
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
	 * count() method returns related rating data
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
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'category_product ON (id_product = RTG_PROD_ID AND id_category = ' . intval($aParams['catId']) . ')';
		}

		// use case - for manufacturer filter
		if (!empty($aParams['brandId'])) {
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'product ON (id_product = RTG_PROD_ID AND id_manufacturer = ' . intval($aParams['brandId']) . ')';
		}

		$sQuery .= ' WHERE RTG_SHOP_ID = ' . intval($iShopId) . ' AND RTG_STATUS = "1"';

		if (null !== $iProdId) {
			$sQuery .= ' AND RTG_PROD_ID = ' . intval($iProdId);
		}

		if (null !== $aParams) {
			if (!empty($aParams['langId'])) {
				$sQuery .= ' AND RTG_LANG_ID = ' . intval($aParams['langId']);
			}
		}
		
		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['count'])? $aResult[0]['count'] : 0)
		);
	}

	/**
	 * average() method returns related rating data
	 *
	 * @param int $iShopId
	 * @param array $aParams
	 * @return int
	 */
	public function average($iShopId, array $aParams = null)
	{
		$sQuery = 'SELECT ROUND(AVG(RTG_NOTE), 2) as average FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_rating';

		// use case - for category filter
		if (!empty($aParams['iCatId'])) {
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'category_product ON (id_product = RTG_PROD_ID AND id_category = ' . intval($aParams['iCatId']) . ')';
		}

		// use case - for manufacturer filter
		if (!empty($aParams['iBrandId'])) {
			$sQuery .= ' INNER JOIN ' . _DB_PREFIX_ . 'product ON (id_product = RTG_PROD_ID AND id_manufacturer = ' . intval($aParams['iBrandId']) . ')';
		}

		$sQuery .= ' WHERE RTG_SHOP_ID = ' . intval($iShopId) . ' AND RTG_STATUS = "1"' . ((!empty($aParams['iProductId']))? ' AND RTG_PROD_ID = ' . intval($aParams['iProductId']) : '');

		if (!empty($aParams['langId'])) {
			$sQuery .= ' AND RTG_LANG_ID = ' . intval($aParams['langId']);
		}

		// execute query
		$aResult = Db::getInstance()->ExecuteS($sQuery);

		return (
			(!empty($aResult[0]['average'])? $aResult[0]['average'] : 0)
		);
	}

	/**
	 * formatJoin() method returns the formatted join with another table
	 *
	 * @param string $sTblField
	 * @param string $sJoinType
	 * @return string
	 */
	public function formatJoin($sTblField, $sJoinType = '')
	{
		return (
			strtoupper($sJoinType) . ' JOIN ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. ' ON (' . $sTblField . ' = ' . self::TBL_ALIAS . '.RVW_ID)'
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
			$oRating = new BT_RatingDao();
		}
		return $oRating;
	}
}