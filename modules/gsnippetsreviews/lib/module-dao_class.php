<?php
/**
 * module-dao_class.php file defines method of management of DATA ACCESS OBJECT
 */

class BT_GsrModuleDao
{
	/**
	 * Magic Method __construct
	 *
	 */
	private function __construct()
	{

	}

	/**
	 * addCallBack() method add a callback for review
	 *
	 * @param int $iShopId
	 * @param int $iOrderId
	 * @param int $iCustId
	 * @param string $sDate
	 * @param array $aData
	 * @return bool
	 */
	public static function addCallBack($iShopId, $iOrderId, $iCustId, array $aData)
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_callback (CBK_SHOP_ID, ORDER_ID, CBK_DATE_ADD, CBK_STATUS, CBK_CUST_ID, CBK_DATA) '
			. 'VALUES("' . (int)$iShopId . '", "' . pSQL($iOrderId) . '", NOW(), "0", "' . (int)$iCustId . '", "' . pSQL(serialize($aData)) . '")';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}


	/**
	 * addOrderHistory() method add order as reminder sent
	 *
	 * @param int $iShopId
	 * @param int $iOrderId
	 * @return bool
	 */
	public static function addOrderHistory($iShopId, $iOrderId)
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_orders_history (oh_shop_id, oh_order_id, oh_date_sent, oh_sent_count) '
			. 'VALUES(' . (int)$iShopId . ', ' . (int)$iOrderId . ', NOW(), 1) '
		    . ' ON DUPLICATE KEY UPDATE oh_sent_count = oh_sent_count + 1, oh_date_sent = NOW()';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}


	/**
	 * getCallbackDetails() method return the reminder's history details
	 *
	 * @param int $iShopId
	 * @param int $iOrderId
	 * @return bool
	 */
	public static function getCallbackDetails($iShopId, $iOrderId)
	{
		$sQuery = 'SELECT oh_sent_count as count, oh_date_sent as date_last'
			.   ' FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_orders_history '
			.   ' WHERE oh_shop_id = ' . (int)$iShopId . ' AND oh_order_id = ' . (int)$iOrderId;

		$aData = Db::getInstance()->ExecuteS($sQuery);

		return (
			!empty($aData[0])? $aData[0] : false
		);
	}


	/**
	 * isCallBackExist() method detect if there is already exist a callback for review
	 *
	 * @param int $iShopId
	 * @param int $iOrderId
	 * @return bool
	 */
	public static function isCallBackExist($iShopId, $iOrderId)
	{
		$sQuery = 'SELECT count(*) as count FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_callback WHERE CBK_SHOP_ID = ' . (int)$iShopId . ' AND ORDER_ID = ' . (int)$iOrderId;

		$aCount = Db::getInstance()->ExecuteS($sQuery);

		return (
			!empty($aCount[0]['count'])? true : false
		);
	}

	/**
	 * deleteCallback() method delete callbacks
	 *
	 * @param int $iShopId
	 * @param int $iDelay
	 * @param int $iTime
	 * @param int $iCbkId
	 * @return bool
	 */
	public static function deleteCallback($iShopId, $iDelay, $iTime, $iCbkId = null)
	{
		$sQuery = 'DELETE FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_callback WHERE ';

		// delete one cbk
		if (null !== $iCbkId) {
			$sQuery .= ' CBK_ID = ' . (int)$iCbkId;
		}
		// delete some cbks matching period time
		else {
			$sQuery .= ' CBK_SHOP_ID = ' . (int)$iShopId . ' AND ' . (int)$iTime . ' >= (UNIX_TIMESTAMP(CBK_DATE_ADD) + ' . (int)$iDelay . ')';
		}

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * updateCallback() method update status to 1 for all sent callbacks
	 *
	 * @param int $iCbkId
	 * @param array $aParams
	 * @return bool
	 */
	public static function updateCallback($iCbkId, array $aParams)
	{
		// set
		$bReturn = true;

		if (!empty($aParams)) {
			// create query
			$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_callback ';

			// status
			if (isset($aParams['status'])) {
				$sQuery .= 'SET CBK_STATUS = "' . pSQL($aParams['status']) . '", ';
			}
			// date
			if (isset($aParams['date'])) {
				$sQuery .= 'SET CBK_DATE_ADD = FROM_UNIXTIME(' . pSQL($aParams['date']) . '), ';
			}
			if (substr($sQuery, -2, 2) == ', ') {
				$sQuery = substr($sQuery, 0, strlen($sQuery) - 2);

				$sQuery .= ' WHERE CBK_ID = ' . (int)$iCbkId;

				$bReturn = Db::getInstance()->Execute($sQuery);
			}
		}
		return $bReturn;
	}

	/**
	 * getCallbacks() method returns all email callbacks
	 *
	 * @param int $iShopId
	 * @param int $iDelay
	 * @param int $iTime
	 * @return array
	 */
	public static function getCallbacks($iShopId, $iDelay, $iTime)
	{
		$sQuery = 'SELECT cbk.CBK_ID as cbkId, cbk.ORDER_ID as id, cbk.CBK_SHOP_ID as shopId, UNIX_TIMESTAMP(cbk.CBK_DATE_ADD) as date, cbk.CBK_DATA as data, cbk.CBK_CUST_ID as custId, c.email as email'
			.   ' FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_callback as cbk '
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'customer as c ON c.id_customer = cbk.CBK_CUST_ID'
			.   ' WHERE CBK_SHOP_ID = ' . (int)$iShopId . ' AND ' . intval($iTime) . ' >= (UNIX_TIMESTAMP(CBK_DATE_ADD) + ' . intval($iDelay) . ')'
			.   ' ORDER BY CBK_ID DESC';

		$aData = Db::getInstance()->ExecuteS($sQuery);

		return $aData;
	}


	/**
	 *
	 * getOrderStatus() method returns list of status order
	 *
	 * @return array
	 */
	public static function getOrderStatus()
	{
		// set variable
		$aStatusTmp = array();

		// set query
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . 'order_state_lang';

		$aStatusTmp = Db::getInstance()->ExecuteS($sQuery);

		foreach ($aStatusTmp as $aStatus) {
			$aStatusOrder[$aStatus['id_order_state']][$aStatus['id_lang']] = $aStatus['name'];
		}
		// destruct
		unset($aStatusTmp);

		return $aStatusOrder;
	}

	/**
	 *
	 * getOrderStatusById() method returns list of status order
	 *
	 * @param int $iOrderId
	 * @return mixed int
	 */
	public static function getOrderStatusById($iOrderId)
	{
		// set query
		$sQuery = 'SELECT id_order_state FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . intval($iOrderId)
			. ' AND id_order_history = (SELECT MAX(id_order_history) FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$iOrderId . ')'
		;

		$aData = Db::getInstance()->getRow($sQuery);

		return (
			!empty($aData['id_order_state'])? $aData['id_order_state'] : 0
		);
	}


	/**
	 * getProduct() method get all properties of product
	 *
	 * @param int $iProductId
	 * @return array
	 */
	public static function getProduct($iProductId)
	{
		$aProduct = array();

		$sQuery = 'SELECT p.*, pa.id_product_attribute,pl.*, i.*, il.*, m.name AS manufacturer_name, s.name AS supplier_name,'
			.   ' ps.product_supplier_reference AS supplier_reference'
			.   ' FROM ' . _DB_PREFIX_ . 'product as p '
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute as pa ON (p.id_product = pa.id_product AND default_on = 1)'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . intval(GSnippetsReviews::$iCurrentLang) . Shop::addSqlRestrictionOnLang('pl') . ')'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image as i ON (i.id_product = p.id_product AND i.cover = 1)'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image_lang as il ON (i.id_image = il.id_image AND il.id_lang = ' . intval(GSnippetsReviews::$iCurrentLang) . ')'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer as m ON m.id_manufacturer = p.id_manufacturer'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'supplier as s ON s.id_supplier = p.id_supplier'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'product_supplier as ps ON (p.id_product = ps.id_product AND pa.id_product_attribute = ps.id_product_attribute)'
			.   ' WHERE p.id_product = ' . intval($iProductId);

		$aAttributes = Db::getInstance()->ExecuteS($sQuery);

		$aProduct = array();

		if (!empty($aAttributes[0])) {
			// hack for version under 1.3.0.1
			$aAttributes[0]['rate'] = 0;

			// get properties
			$aProduct = Product::getProductProperties(GSnippetsReviews::$iCurrentLang, $aAttributes[0]);

			if (empty($aProduct)) {
				$aProduct = array();
			}
			else {
				$aProduct['supplier_reference'] = $aAttributes[0]['supplier_reference'];
			}
		}

		return $aProduct;
	}

	/**
	 * getBoughtProducts() method get all bought products purchased by a customer
	 *
	 * @param int $iCustomerId
	 * @param int $iLimit
	 * @return array
	 */
	public static function getBoughtProducts($iCustomerId, $iLimit = null)
	{
		// query for number of customer's bought products
		$sQuery = 'SELECT p.id_product as id_product'
			. ' FROM ' . _DB_PREFIX_ . 'orders as o '
			. ' LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od ON (o.id_order = od.id_order AND o.valid = 1)'
			. ' INNER JOIN ' . _DB_PREFIX_ . 'product as p ON (od.product_id = p.id_product)'
			. ' WHERE o.id_customer = ' . intval($iCustomerId)
			. ' GROUP BY od.product_id'
			. ' ORDER BY o.id_order DESC';

		if ($iLimit !== null && is_int($iLimit)) {
			$sQuery .= ' LIMIT ' . (int)$iLimit;
		}

		return Db::getInstance()->ExecuteS($sQuery);
	}


	/**
	 * getProductsByOrder() method returns product data by order id
	 *
	 * @param int $iOrderId
	 * @return array
	 */
	public static function getProductsByOrder($iOrderId)
	{
		$sQuery = 'SELECT p.id_product as prodId, p.*, pa.id_product_attribute,pl.*, i.*, il.*, m.name AS manufacturer_name, s.name AS supplier_name'
			.   ' FROM ' . _DB_PREFIX_ . 'order_detail as od '
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'product as p ON p.id_product = od.product_id'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute as pa ON (p.id_product = pa.id_product AND default_on = 1)'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . intval(GSnippetsReviews::$iCurrentLang) . Shop::addSqlRestrictionOnLang('pl') . ' AND pl.id_shop = ' .intval(GSnippetsReviews::$iShopId) . ' )'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image as i ON (i.id_product = p.id_product AND i.cover = 1)'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image_lang as il ON (i.id_image = il.id_image AND il.id_lang = ' . intval(GSnippetsReviews::$iCurrentLang) . ')'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer as m ON m.id_manufacturer = p.id_manufacturer'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'supplier as s ON s.id_supplier = p.id_supplier'
			.   ' WHERE od.id_order = ' . intval($iOrderId);

		$aData = Db::getInstance()->ExecuteS($sQuery);

		return $aData;
	}

	/**
	 * getOrdersIdByDate() method returns order IDs by date
	 *
	 * @param string $sDateFrom
	 * @param string $sDateTo
	 * @param int $iCustomerId
	 * @param string $sDateType : date_add or date_upd
	 * @return array
	 */
	public static function getOrdersIdByDate($sDateFrom, $sDateTo, $iCustomerId = null, $sDateType = 'date_add')
	{
		$sQuery = 'SELECT `id_order`'
			. ' FROM `'._DB_PREFIX_.'orders`'
			. ' WHERE '.$sDateType.' <= \''.pSQL($sDateTo).'\' AND '.$sDateType.' >= \''.pSQL($sDateFrom).'\''
			. Shop::addSqlRestriction()
			.($iCustomerId ? ' AND id_customer = '.(int)$iCustomerId : '');

		$aResult = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sQuery);

		// set
		$aOrders = array();

		if (!empty($aResult)) {
			foreach ($aResult as $aOrder) {
				$aOrders[] = (int)$aOrder['id_order'];
			}
		}
		return $aOrders;
	}

	/**
	 * checkProductBought() method checks if customer already bought product
	 *
	 * @param int $iCustId
	 * @param int $iProductId
	 * @return bool
	 */
	public static function checkProductBought($iCustId, $iProductId)
	{
		$sQuery = 'SELECT count(o.id_order) as nb FROM ' . _DB_PREFIX_ .'orders as o '
		.   ' LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od ON  o.id_order = od.id_order'
		.   ' WHERE o.id_customer = ' . intval($iCustId) . ' and od.product_id = ' . intval($iProductId);

		$aBought = Db::getInstance()->ExecuteS($sQuery);

		return (
			!empty($aBought[0]['nb'])? true : false
		);
	}


	/**
	 * getCustCallbackStatus() method returns customer callback status
	 *
	 * @param int $iShopId
	 * @param int $iCustId
	 * @return bool
	 */
	public static function getCustCallbackStatus($iShopId, $iCustId)
	{
		$sQuery = 'SELECT CUST_CBK_STATUS as status FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_customer '
			.   ' WHERE CUST_SHOP_ID = ' . intval($iShopId) . ' AND CUST_ID = ' . intval($iCustId);

		// execute
		$aResult = 	Db::getInstance()->ExecuteS($sQuery);

		return (
			isset($aResult[0]['status'])? $aResult[0]['status'] : false
		);
	}

	/**
	 * addCustCallbackStatus() method inserts new customer
	 *
	 * @param int $iShopId
	 * @param int $iCustId
	 * @param bool $bStatus
	 * @return bool
	 */
	public static function addCustCallbackStatus($iShopId, $iCustId, $bStatus)
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_customer (CUST_SHOP_ID, CUST_ID, CUST_CBK_STATUS) VALUES(' . intval($iShopId) . ','. intval($iCustId) . ', "' . pSQL($bStatus) . '")'
			.   ' ON DUPLICATE KEY UPDATE CUST_CBK_STATUS = "' . pSQL($bStatus) . '"';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * updateTablesShopId() method update shop id for each table because of one module's version has bad updated the tables
	 *
	 * @param string $sTable
	 * @param string $sPrefixField
	 * @return bool
	 */
	public static function updateTablesShopId($sTable, $sPrefixField)
	{
		// check if shop id is set to 0
		$sQuery = 'SELECT count(*) as nb FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . $sTable . ' WHERE ' . $sPrefixField . '_SHOP_ID = 0';

		$bReturn = Db::getInstance()->getValue($sQuery);

		if (!empty($bReturn)) {
			// update shop id to default shop id
			$bReturn = Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' . $sTable . '  SET ' . $sPrefixField . '_SHOP_ID = 1 WHERE ' . $sPrefixField . '_SHOP_ID = 0');
		}
		else {
			$bReturn = true;
		}

		return $bReturn;
	}

	/**
	 * getModuleProductComments() method returns comments product of Prestashop module
	 *
	 * @param bool $bCount
	 * @param int $iModerate => define which kind of reviews we return : 0 = unmoderated / 1 = moderated / 2 = both
	 * @param bool $bMockReviewImport => define if we activate the mock reviews import mode
	 * @return int or array
	 */
	public static function getModuleProductComments($bCount = false, $iModerate = 2, $bMockReviewImport = false)
	{
		// set
		$mReturn = null;

		// use case - normal mode
		if (!$bMockReviewImport) {
			// check module version
			$bResult = Db::getInstance()->ExecuteS('SHOW COLUMNS FROM ' .  _DB_PREFIX_ . 'product_comment LIKE "id_guest"');

			// set condition review type : unmoderated / moderated / both
			$sReviewCondition = '';

			if ($iModerate != 2) {
				if ($iModerate) {
					$sReviewCondition = ' validate = 1 ';
				}
				else {
					$sReviewCondition = ' validate = 0 ';
				}
			}

			// set where condition
			$sWhere = '';

			if (!empty($sReviewCondition) && !empty($bResult)) {
				$sWhere = ' WHERE (id_guest = 0 OR id_guest is NULL) AND ' . $sReviewCondition;
			}
			elseif (!empty($bResult)) {
				$sWhere = ' WHERE id_guest = 0 OR id_guest is NULL';
			}
			elseif (!empty($sReviewCondition)) {
				$sWhere = ' WHERE ' . $sReviewCondition;
			}

			if ($bCount) {
				$sQuery = 'SELECT count(*) FROM ' . _DB_PREFIX_ . 'product_comment' . $sWhere;

				$mReturn = Db::getInstance()->getValue($sQuery);
			}
			else {
				$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . 'product_comment' . $sWhere;

				$mReturn = Db::getInstance()->ExecuteS($sQuery);
			}
		}
		// use case - mock reviews import mode
		elseif (!empty($GLOBALS[_GSR_MODULE_NAME . '_MOCK_IMPORT'])) {
			$aReviews = $GLOBALS[_GSR_MODULE_NAME . '_MOCK_IMPORT'];
			foreach($aReviews as $iKey => $aReview) {
				if (!empty($aReview['id_guest'])) {
					unset($aReviews[$iKey]);
				}
				elseif ($iModerate != 2) {
					if ($iModerate) {
						if (!$aReview['validate']) {
							unset($aReviews[$iKey]);
						}
					}
					else {
						if ($aReview['validate']) {
							unset($aReviews[$iKey]);
						}
					}
				}
			}
			$mReturn = $bCount? count($aReviews) : $aReviews;
		}

		return $mReturn;
	}
}