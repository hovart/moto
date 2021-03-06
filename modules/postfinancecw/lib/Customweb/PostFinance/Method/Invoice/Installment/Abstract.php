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

require_once 'Customweb/PostFinance/Method/Invoice/Abstract.php';


/**
 * 
 * @author Bjoern Hasselmann
 */
class Customweb_PostFinance_Method_Invoice_Installment_Abstract extends Customweb_PostFinance_Method_Invoice_Abstract {

	public function getPaymentMethodBrandAndMethod(Customweb_PostFinance_Authorization_Transaction $transaction) {
		$countryCode = strtoupper($this->getPaymentMethodConfigurationValue('brand_country'));
		return array(
			'pm' => 'Installment ' . $countryCode,
			'brand' => 'Installment ' . $countryCode,
		);
	}
	
}

	