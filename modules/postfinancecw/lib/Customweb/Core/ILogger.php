<?php 
/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2016 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */

require_once 'Customweb/Core/Logger/Factory.php';
require_once 'Customweb/Core/ILogger.php';

/**
 * 
 * @author Thomas Hunziker
 *
 */
interface Customweb_Core_ILogger {
	
	const LEVEL_INFO = 'info';
	
	const LEVEL_WARNING = 'warning';
	
	const LEVEL_ERROR = 'error';
	
	/**
	 * This method logs the given parameters for all listeners,
	 * which are registered with Customweb_Core_Logger_Factory::addListener
	 * 
	 * @param string $level		The severity of the logged message (LEVEL_INFO | LEVEL_WARNING | LEVEL_ERROR)
	 * @param string $message
	 * @param Exception $e
	 */
	public function log($level, $message, Exception $e = null);
	
	/**
	 * This method logs the given message for all listeners,
	 * which are registered with Customweb_Core_Logger_Factory::addListener
	 * with a severity of Customweb_Core_ILogger::LEVEL_INFO
	 * 
	 * @param string $message
	 */
	public function logInfo($message);
	/**
	 * This method logs the given message for all listeners,
	 * which are registered with Customweb_Core_Logger_Factory::addListener
	 * with a severity of LEVEL_ERROR
	 *  
	 * @param Exception $e
	 */
	public function logException(Exception $e);
	/**
	 * This method logs the given message for all listeners,
	 * which are registered with Customweb_Core_Logger_Factory::addListener
	 * with a severity of LEVEL_WARNING
	 *  
	 * @param string $message
	 */
	public function logWarning($message);
	
}