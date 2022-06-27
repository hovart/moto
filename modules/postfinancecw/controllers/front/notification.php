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
require_once 'Customweb/Core/Http/Response.php';
require_once 'Customweb/Core/Http/ContextRequest.php';

require_once 'PostFinanceCw/Util.php';


/**
 * 
 * 
 * @author Thomas Hunziker
 * @deprecated Used only for backward compatibility
 *
 */
class PostFinanceCwNotificationModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()          			 		  	    
	 */
	public function postProcess() {

		$dispatcher = PostFinanceCw_Util::getEndpointDispatcher();
		$response = new Customweb_Core_Http_Response($dispatcher->invokeControllerAction(Customweb_Core_Http_ContextRequest::getInstance(), 'process', 'index'));
		$response->send();
		die();
		
	}
	
	protected function displayMaintenancePage() {
		// We want never to see here the maintenance page.
	}
	
	protected function displayRestrictedCountryPage() {
		// We do not want to restrict the content by any country.
	}
	
	protected function canonicalRedirection($canonical_url = '') {
		// We do not need any canonical redirect
	}

}
