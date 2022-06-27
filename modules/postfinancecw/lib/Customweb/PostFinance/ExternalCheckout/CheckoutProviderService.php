<?php

/**
 *  * You are allowed to use this API in your web application.
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

require_once 'Customweb/Payment/ExternalCheckout/AbstractProviderService.php';
require_once 'Customweb/PostFinance/ExternalCheckout/MasterPass/Checkout.php';
require_once 'Customweb/PostFinance/ExternalCheckout/AbstractCheckout.php';
require_once 'Customweb/Core/Exception/CastException.php';
require_once 'Customweb/PostFinance/Authorization/Transaction.php';
require_once 'Customweb/PostFinance/Container.php';



/**
 * MasterPass checkout object.
 *
 * @author Thomas Hunziker
 * @Bean
 */
class Customweb_PostFinance_ExternalCheckout_CheckoutProviderService extends Customweb_Payment_ExternalCheckout_AbstractProviderService {

	private $masterPassCheckout;
	
	/**
	 * @var Customweb_PostFinance_Container
	 */
	private $container;
	
	public function __construct(Customweb_DependencyInjection_IContainer $container) {
		$this->container = new Customweb_PostFinance_Container($container);
		$this->masterPassCheckout = new Customweb_PostFinance_ExternalCheckout_MasterPass_Checkout($this->container);
	}
	
	public function getCheckoutsUnfiltered() {
		return array(
			$this->masterPassCheckout,
		);
	}

	public function getWidgetHtml(Customweb_Payment_ExternalCheckout_ICheckout $checkout, Customweb_Payment_ExternalCheckout_IContext $context) {
		if (!($checkout instanceof Customweb_PostFinance_ExternalCheckout_AbstractCheckout)) {
			throw new Customweb_Core_Exception_CastException('Customweb_PostFinance_ExternalCheckout_AbstractCheckout');
		}
		return $checkout->getWidget($context);
	}

	public function createTransaction(Customweb_Payment_Authorization_ITransactionContext $transactionContext, Customweb_Payment_ExternalCheckout_IContext $context) {
		
		$transaction = new Customweb_PostFinance_Authorization_Transaction($transactionContext);
		$transaction->setLiveTransaction(!$this->container->getConfiguration()->isTestMode());
		$transaction->setAuthorizationMethod(self::AUTHORIZATION_METHOD_NAME);
		
		return $transaction;
	}
	
}