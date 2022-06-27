<?php
/**
 * hook-ctrl_class.php file defines controller which manage type of hook object derived of abstract type as factory pattern
 */

class BT_GsrHookCtrl
{  
	/**
	 * @var obj $_oHook : defines hook object to display 
	 */
	private $_oHook = null;
	
	/**
	 * Magic Method __construct assigns few information about module and instantiate parent class
	 *
	 * @uses Add hook object type => declare a new class file in hook lib with match name of hook type, define method hook name in main class
	 * @example :   Hook name = frontCanonicalRedirect
	 *              declare file in lib/hook/ = hook-frontCanonicalRedirect_class.php
	 *              class name = BT_HookFrontCanonicalRedirect
	 *              method name = display
	 *              add implement = implements BT_IHook
	 *
	 * @param string $sType : type of interface to execute
	 * @param string $sAction
	 */
	public function __construct($sType, $sAction)
	{
		// include interface of hook executing
		require_once(_GSR_PATH_LIB_HOOK . 'hook-base_class.php');

		// check if file exists
		if (!file_exists(_GSR_PATH_LIB_HOOK . 'hook-' . $sType . '_class.php')) {
			throw new Exception("no valid file", 130);
		}
		else {
			// include matched hook object
			require_once(_GSR_PATH_LIB_HOOK . 'hook-' . $sType . '_class.php');

			if (!class_exists('BT_GsrHook' . ucfirst($sType))
					&&
				!method_exists('BT_GsrHook' . ucfirst($sType), 'run')
			) {
				throw new Exception("no valid class and method", 131);
			}
			else {
				// set class name
				$sClassName = 'BT_GsrHook' . ucfirst($sType);

				// instantiate
				$this->_oHook = new $sClassName($sAction);
			}
		}
	}

	/**
	 * run() method execute hook
	 *
	 * @param array $aParams
	 * @return array $aDisplay : empty => false / not empty => true
	 */
	public function run(array $aParams = null)
	{
		return (
			$this->_oHook->run($aParams)
		);
	}
}