<?php
/**
 * reply-dao_class.php file defines method of management of DATA ACCESS OBJECT
 */

require_once(dirname(__FILE__) . '/base-dao_class.php');

class BT_ReplyDao extends BT_DaoBase
{
	/**
	 * const TBL_ALIAS : alias of table
	 */
	const TBL_ALIAS = 'afs';

	/**
	 * const TBL_NAME : name of table
	 */
	const TBL_NAME = 'aftersales';

	/**
	 * var array $aFields : fields of table
	 */
	public $aFields = array(
		'id' => 'AFS_ID as id',
		'type' => 'AFS_TYPE as type',
		'dateAdd' =>'UNIX_TIMESTAMP(AFS_DATE_ADD) as replyDateAdd',
		'dateUpd' => 'UNIX_TIMESTAMP(AFS_DATE_UPD) as replyDateUpd',
		'display' => 'AFS_DISPLAY as replyDisplay',
		'data' => 'AFS_DATA as replyData'
	);


	/**
	 * Magic Method __construct
	 */
	public function __construct()
	{
		// get fields as string
		$this->sFields = $this->getFields();
	}


	/**
	 * add() method add a new after-sales reply to the review
	 *
	 * @param int $iReviewId : review id
	 * @param array $aData : data
	 * @param bool $bDisplay
	 * @param string $sType : rating or review
	 * @return bool
	 */
	public function add($iId, array $aData, $bDisplay = 0, $sType = 'rating')
	{
		$sQuery = 'INSERT INTO ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' (AFS_ID, AFS_TYPE, AFS_DATE_ADD, AFS_DATE_UPD, AFS_DISPLAY, AFS_DATA) '
			. 'VALUES(' . intval($iId) . ', "' . pSQL($sType) . '", NOW(), NOW(), "' . pSQL($bDisplay) . '", "' . pSQL(serialize($aData)) . '")';

		return (
			Db::getInstance()->Execute($sQuery)
		);
	}

	/**
	 * delete() method deletes a record
	 *
	 * @param int $mId : rating or review id
	 * @param string $sType : rating or review
	 * @return bool
	 */
	public function delete($mId, $sType = 'rating')
	{
		$sQuery = 'DELETE FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' WHERE AFS_TYPE = "' . pSQL($sType)  . '" AND AFS_ID';

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
	 * update() method update an after-sales reply
	 *
	 * @param int $iId : rating or review id
	 * @param string $sType : rating or review
	 * @param array $aData
	 * @param bool $bDisplay
	 * @return bool
	 */
	public function update($iId, array $aData = null, $bDisplay = 0, $sType = 'rating')
	{
		$sQuery = 'UPDATE ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' set '
			.   'AFS_DATE_UPD = FROM_UNIXTIME(' . time() . ')';

		// use case - update display
		$sQuery .= ', AFS_DISPLAY = "' . pSQL($bDisplay) . '"';

		// use case - update comment
		if (!empty($aData['data'])) {
			$sQuery .= ', AFS_DATA = "' . pSQL(serialize($aData['data'])) . '"';
		}
		$sQuery .= ' WHERE AFS_TYPE = "' . pSQL($sType) . ' " AND AFS_ID = ' . intval($iId);

		return Db::getInstance()->Execute($sQuery);
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

		// set main body of query
		$sQuery = 'SELECT ' . $sFields . ' FROM ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. $sJoin
		;

		$sQuery .= ' WHERE ';

		// use case - displayed or hidden reply
		if (isset($aParams['display']) && ( $aParams['display'] == 0 || $aParams['display'] == 1)) {
			$sQuery .= self::TBL_ALIAS . '.AFS_DISPLAY = "' . pSQL($aParams['display']) . '" ';
		}
		else {
			$sQuery .= self::TBL_ALIAS . '.AFS_DISPLAY = "1" ';
		}
		// case - one reply
		if ((isset($aParams['id']) && $aParams['id'] !== false)) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.AFS_ID  = ' . intval($aParams['id']) . ' ';
		}
		// case - date
		if (isset($aParams['date']) && $aParams['date'] == false) {
			$sQuery .= ' AND ' . self::TBL_ALIAS . '.AFS_DATE_ADD  = ' . $aParams['date'] . ' ';
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
	 * formatJoin() method returns the formatted join with another table
	 *
	 * @param string $sTblField
	 * @param string $sType
	 * @param string $sJoinType
	 * @return string
	 */
	public function formatJoin($sTblField, $sType, $sJoinType = '')
	{
		return (
			strtoupper($sJoinType) . ' JOIN ' . _DB_PREFIX_ . strtolower(_GSR_MODULE_NAME) . '_' .  self::TBL_NAME . ' as ' . self::TBL_ALIAS
			. ' ON (' . $sTblField . ' = ' . self::TBL_ALIAS . '.AFS_ID AND ' . self::TBL_ALIAS . '.AFS_TYPE = "' . pSQL($sType) . '")'
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
		static $oReply;

		if (null === $oReply) {
			$oReply = new BT_ReplyDao();
		}
		return $oReply;
	}
}