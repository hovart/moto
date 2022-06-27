<?php
/**
 * voucher-dao_class.php file defines method of management of DATA ACCESS OBJECT
 */

class BT_VoucherDao
{
	/**
	 * Magic Method __construct
	 *
	 */
	private function __construct()
	{

	}

	/**
	 * add() method add a new voucher
	 *
	 * @param int $iShopId
	 * @param string $sVoucherType
	 * @param int $iCustId
	 * @return bool
	 */
	public static function add($iShopId, $sVoucherType, $iCustId)
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_voucher (VCHR_SHOP_ID, VCHR_TYPE, VCHR_CUST_ID, VCHR_COUNT) VALUES('
			. $iShopId . ', "' . pSQL($sVoucherType) . '", ' .  intval($iCustId) . ', 1 )'
			. ' ON DUPLICATE KEY UPDATE VCHR_COUNT = VCHR_COUNT + 1';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}


	/**
	 * addProductRule() method add a new cart rule
	 *
	 * @param int $iCartRuleId
	 * @param int $iQuantity
	 * @param string $sType
	 * @param array $aIds
	 * @return bool
	 */
	public static function addProductRule($iCartRuleId, $iQuantity, $sType, array $aIds)
	{
		$bInsert = false;

		// set transaction
		Db::getInstance()->Execute('BEGIN');

		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule_group (id_cart_rule, quantity) VALUES('
			. (int)$iCartRuleId . ', ' . (int)$iQuantity . ')';

		// only if group rule is added
		if (Db::getInstance()->Execute($sQuery)) {

			$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule (id_product_rule_group, type) VALUES('
				. Db::getInstance()->Insert_ID() . ', "' . pSQL($sType) . '")';

			// only if product rule is added
			if (Db::getInstance()->Execute($sQuery)) {

				if (!empty($aIds)) {
					$bInsert = true;

					$iLastInsertId = Db::getInstance()->Insert_ID();

					foreach ($aIds as $iId) {
						$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule_value (id_product_rule, id_item) VALUES('
							. (int)$iLastInsertId . ', ' . (int)$iId . ')';

						if (!Db::getInstance()->Execute($sQuery)) {
							$bInsert = false;
						}
					}
				}
			}
		}
		// commit or rollback transaction
		$bInsert = ($bInsert)? Db::getInstance()->Execute('COMMIT') : Db::getInstance()->Execute('ROLLBACK');

		return $bInsert;
	}


	/**
	 * delete() method delete a record
	 *
	 * @param int $iShopId : shop id
	 * @param mixed $mCustId : customer id / ids
	 * @return bool
	 */
	public static function delete($iShopId, $mCustId)
	{
		$sQuery = 'DELETE FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_voucher WHERE VCHR_SHOP_ID = ' . $iShopId . ' AND VCHR_CUST_ID ';

		if (is_array($mCustId)) {
			$sQuery .= ' IN(' . implode(',', $mCustId) . ')';
		}
		else {
			$sQuery .= ' = ' . intval($mCustId);
		}

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}


	/**
	 * get() method returns related voucher count by customer
	 *
	 * @param array $aParams
	 * @return array
	 */
	public static function get(array $aParams = null)
	{
		// set variables
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_voucher WHERE 1 = 1';

		// case - one customer
		if ((isset($aParams['id']) && $aParams['id'] !== false)) {
			$sQuery .= ' AND VCHR_CUST_ID  = ' . (int)$aParams['id'] . ' ';
		}

		// case - specific type
		if (!empty($aParams['type'])) {
			$sQuery .= ' AND VCHR_TYPE  = "' . pSQL($aParams['type']) . '" ';
		}

		// case - one shop
		if (!empty($aParams['shopId'])) {
			$sQuery .= ' AND VCHR_SHOP_ID  = ' . intval($aParams['shopId']) . ' ';
		}

		// case - over nb voucher
		if ((isset($aParams['over']) && is_numeric($aParams['over']))) {
			$sQuery .= ' AND VCHR_COUNT  > ' . pSQL($aParams['over']) . ' ';
		}
		// case - reviews of one product
		if ((isset($aParams['less']) && $aParams['less'] !== false)) {
			$sQuery .= ' AND VCHR_COUNT  < ' . pSQL($aParams['over']) . ' ';
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

		$aVoucher = Db::getInstance()->ExecuteS($sQuery);

		if (!empty($aParams['count'])) {
			$aVoucher = !empty($aVoucher[0]['VCHR_COUNT'])? $aVoucher[0]['VCHR_COUNT'] : 0;
		}

		return $aVoucher;
	}
}