<?php
/**
 * base-review_class.php file defines method for handling common rating / review /reply methods
 */

abstract class BT_ReviewBase
{
	/**
	 * @var obj $oDAO : stock DAO object for persistent data
	 */
	public $oDAO = null;

	/**
	 * @var array $aSerializedKeys : available keys for serializing data
	 */
	public static $aSerializedKeys = null;

	/**
	 * setSerializedKeys() method set serialized keys
	 */
	public function setSerializedKeys(array $aSerializedKeys = null)
	{
		if (!empty($aSerializedKeys)) {
			self::$aSerializedKeys = $aSerializedKeys;
		}
		elseif (empty(self::$aSerializedKeys)) {
			self::$aSerializedKeys = $GLOBALS[_GSR_MODULE_NAME . '_SERIALIZED_KEYS'];
		}
	}


	/**
	 * check() method check all keys to serialize
	 *
	 * @param array $aData
	 * @return mixed: true or array
	 */
	public function check(array $aData)
	{
		$aCheck = array();

		foreach ($aData as $sKey => $mVal) {
			if (!in_array($sKey, self::$aSerializedKeys)) {
				$aCheck[$sKey] = $mVal;
			}
		}

		return (
			empty($aCheck)? true : $aCheck
		);
	}

	/**
	 * unserialize() method unserialize content
	 *
	 * @param string $sNeedle
	 * @param array $aData
	 * @return bool
	 */
	public function unserialize($sNeedle, array &$aData)
	{
		$bResult = false;

		if (isset($aData[$sNeedle]) && is_string($aData[$sNeedle])) {
			$aData[$sNeedle] = unserialize($aData[$sNeedle]);
			$bResult = true;
		}
		return $bResult;
	}
}