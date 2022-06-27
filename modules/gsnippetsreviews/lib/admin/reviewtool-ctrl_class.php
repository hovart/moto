	<?php
/**
 * review-ctrl_class.php file defines controller which manage type of derived admin object of abstract type as factory pattern
 */

class BT_ReviewToolCtrl extends BT_GsrBaseCtrl
{  
	/**
	 * Magic Method __construct
	 *
	 * @param array $aParams
	 */
	public function __construct(array $aParams = null)
	{
		//defines type to execute
		// use case : no key sAction sent in POST mode (no form has been posted => first page is displayed with admin-display.class.php)
		// use case : key sAction sent in POST mode (form or ajax query posted ).
		$sAction = (!Tools::getIsset('sAction') || (Tools::getIsset('sAction') && 'display' == Tools::getValue('sAction')))? (Tools::getIsset('sAction')?Tools::getValue('sAction') : 'display') : Tools::getValue('sAction');

		// set action
		$this->setAction($sAction);

		// set type
		$this->setType();
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}


	/**
	 * run() method execute abstract derived review tool object
	 *
	 * @param array $aRequest : request
	 * @return array $aDisplay : empty => false / not empty => true
	 */
	public function run($aRequest)
	{
		// set
		$aDisplay = array();

		// include interface
		require_once(_GSR_PATH_LIB_ADMIN . 'i-admin.php');

		switch (self::$sAction) {
			case 'display' :
				// include matched review tool object
				require_once(_GSR_PATH_LIB_ADMIN . 'review-display_class.php');

				// set js msg translation
				BT_GsrModuleTools::translateJsMsg();

				$oReviewType = BT_ReviewDisplay::create();
				break;
			case 'update'   : // update basic settings / 
				// include matched review tool object
				require_once(_GSR_PATH_LIB_ADMIN . 'review-update_class.php');

				$oReviewType = BT_ReviewUpdate::create();
				break;
			case 'delete'   : // delete comment
				// include matched review tool object
				require_once(_GSR_PATH_LIB_ADMIN . 'review-delete_class.php');

				$oReviewType = BT_ReviewDelete::create();
				break;
			default :
				$oReviewType = false;
				break;
		}

		// process data to use in view (tpl)
		if (!empty($oReviewType)) {
			$aDisplay = $oReviewType->run(parent::$sType, $aRequest);

			// destruct
			unset($oReviewType);
		}

		return $aDisplay;
	}
}