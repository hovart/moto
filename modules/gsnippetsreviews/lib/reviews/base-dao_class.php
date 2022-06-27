<?php
/**
 * base-dao_class.php file defines method for handling DAOy methods
 */

abstract class BT_DaoBase
{
	/**
	 * var string $sFields : fields of table
	 */
	public $sFields = '';

	/**
	 * setField() method set value of passed field
	 *
	 * @param string $sFieldName : one field of the related table
	 * @param string $sFieldValue : field's value
	 * @return bool
	 */
	public function setField($sFieldName, $sFieldValue)
	{
		$bResult = false;

		if (array_key_exists($sFieldName, $this->aFields)) {
			$this->aFields[$sFieldName] = $sFieldValue;

			$bResult = true;
		}

		return $bResult;
	}

	/**
	 * getFields() method return the list of fields as a string
	 *
	 * @return string
	 */
	public function getFields()
	{
		$this->sFields = implode(',', $this->aFields);

		return $this->sFields;
	}
}