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

require_once 'Customweb/Core/Util/Rand.php';
require_once 'Customweb/Mvc/Template/RenderContext.php';
require_once 'Customweb/Core/Http/Response.php';
require_once 'Customweb/Payment/ExternalCheckout/AbstractCheckoutService.php';
require_once 'Customweb/Core/Exception/CastException.php';
require_once 'Customweb/Mvc/Template/SecurityPolicy.php';


/**
 * 
 * @author Thomas Hunziker
 * @Bean
 */
class PostFinanceCw_ExternalCheckoutService extends Customweb_Payment_ExternalCheckout_AbstractCheckoutService {
	
	public function loadContext($contextId, $cache = true) {
		return $this->getEntityManager()->fetch('PostFinanceCw_Entity_ExternalCheckoutContext', $contextId, $cache);
	}
	
	public function renderShippingMethodSelectionPane(Customweb_Payment_ExternalCheckout_IContext $context, $errorMessages) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		$cart = new Cart($context->getCartId());
		
		$this->refreshContext($context);
		$this->getEntityManager()->persist($context);
		
		$templateContext = new Customweb_Mvc_Template_RenderContext();
		$templateContext->setSecurityPolicy(new Customweb_Mvc_Template_SecurityPolicy());
		$templateContext->setTemplate('checkout/carrier');
		$templateContext->addVariables($this->getVariablesForRenderingShippingMethodSelection($cart));
		$templateContext->addVariable('shippingMethodSelectionError', $errorMessages);
		return PostFinanceCw_Util::getTemplateRenderer()->render($templateContext);
	}

	protected function getVariablesForRenderingShippingMethodSelection(Cart $cart)
	{
		$carriers = $cart->simulateCarriersOutput();
		$checked = $cart->simulateCarrierSelectedOutput();
		$delivery_option_list = $cart->getDeliveryOptionList();
		
		$cart->setDeliveryOption($cart->getDeliveryOption());
		
		// Wrapping fees
		$wrapping_fees = $cart->getGiftWrappingPrice(false);
		$wrapping_fees_tax_inc = $wrapping_fees = $cart->getGiftWrappingPrice();
		
		
		$variabels = array(
			'address_collection' => $cart->getAddressCollection(),
			'delivery_option_list' => $delivery_option_list,
			'carriers' => $carriers,
			'checked' => $checked,
			'virtual_cart' => $cart->isVirtualCart(),
			'delivery_option' => $cart->getDeliveryOption(null, false),
			'recyclablePackAllowed' => (int)(Configuration::get('PS_RECYCLABLE_PACK')),
			'giftAllowed' => (int)(Configuration::get('PS_GIFT_WRAPPING')),
			'total_wrapping_cost' => Tools::convertPrice($wrapping_fees_tax_inc, $cart->id_currency),
			'total_wrapping_tax_exc_cost' => Tools::convertPrice($wrapping_fees, $cart->id_currency),
		);
	
		$vars = array(
			'HOOK_BEFORECARRIER' => Hook::exec('displayBeforeCarrier', array(
				'carriers' => $carriers,
				'checked' => $checked,
				'delivery_option_list' => $delivery_option_list,
				'delivery_option' => $cart->getDeliveryOption(null, false)
			))
		);
		Cart::addExtraCarriers($vars);
	
		return array_merge($variabels, $vars);
	}
	
	
	protected function validateDeliveryOption($delivery_option)
	{
		if (!is_array($delivery_option))
			return false;
	
		foreach ($delivery_option as $option)
			if (!preg_match('/(\d+,)?\d+/', $option))
				return false;
	
			return true;
	}
	
	protected function updateShippingMethodOnContext(Customweb_Payment_ExternalCheckout_IContext $context, Customweb_Core_Http_IRequest $request) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		$cart = new Cart($context->getCartId());
		$parameters = $request->getParameters();
		
		if (!empty($parameters['recyclable'])) {
			$cart->recyclable = (int)$parameters['recyclable'];
		}
		if (!empty($parameters['gift'])) {
			$cart->gift = (int)$parameters['gift'];
			if (!Validate::isMessage($parameters['gift_message'])) {
				throw new Exception('Invalid gift message.');
			}
			else {
				$cart->gift_message = strip_tags($parameters['gift_message']);
			}
		}

		if (isset($parameters['delivery_option'])) {
			if ($this->validateDeliveryOption($parameters['delivery_option'])) {
				$cart->setDeliveryOption($parameters['delivery_option']);
			}
		}
		elseif (isset($parameters['id_carrier'])) {
			// For retrocompatibility reason, try to transform carrier to an delivery option list
			$delivery_option_list = $cart->getDeliveryOptionList();
			if (count($delivery_option_list) == 1)
			{
				$key = Cart::desintifier($parameters['id_carrier']);
				foreach ($delivery_option_list as $id_address => $options) {
					if (isset($options[$key]))
					{
						$cart->id_carrier = (int)$parameters['id_carrier'];
						$cart->setDeliveryOption(array($id_address => $key));
					}
				}
			}
		}
		
		Hook::exec('actionCarrierProcess', array('cart' => $cart));
		
		if (!$cart->update()) {
			throw new Exception("Unable to store cart object.");
		}
		
		$context->setCarrierId($cart->id_carrier);

		// Carrier has changed, so we check if the cart rules still apply
		CartRule::autoRemoveFromCart(Context::getContext());
		CartRule::autoAddToCart(Context::getContext());
	}
	
	protected function extractShippingName(Customweb_Payment_ExternalCheckout_IContext $context, Customweb_Core_Http_IRequest $request) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		return $this->getShippingMethodNameFromContext($context);
	}
	
	private function getShippingMethodNameFromContext(PostFinanceCw_Entity_ExternalCheckoutContext $context) {
		if ($context->getCarrierId() !== null) {
			$carrier = new Carrier($context->getCarrierId());
			return $carrier->name;
		}
		else {
			return null;
		}
	}
	
	protected function refreshContext(Customweb_Payment_ExternalCheckout_AbstractContext $context) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		$cart = new Cart($context->getCartId());
		
		if ($context->getShippingAddress() !== null) {
			$cart->id_address_delivery = $this->updateAddress($cart->id_address_delivery, $context->getShippingAddress());
		}
		
		if ($context->getBillingAddress() !== null) {
			$invoiceAddressId = $cart->id_address_invoice;
			
			// We need to make sure that the address id for invoice is different than the one
			// of the delivery. Otherwise we are unable to use different addresses.
			if (!empty($invoiceAddressId) && $invoiceAddressId == $cart->id_address_delivery) {
				$invoiceAddressId = null;
			}
			
			$cart->id_address_invoice = $this->updateAddress($invoiceAddressId, $context->getBillingAddress());
		}
		
		$options = $cart->getDeliveryOptionList(null, true);
		if(empty($options) ){
			$cart->setDeliveryOption();
			$context->setCarrierId(null);
		}
		else{
			$cart->setDeliveryOption($cart->getDeliveryOption());
		}
		if (!empty($cart->id_carrier)) {
			$context->setCarrierId($cart->id_carrier);
		}
		
		$context->setShippingMethodName($this->getShippingMethodNameFromContext($context));

		$cart->update();
		
		$context->updateFromCart($cart, $context->getPaymentMethod());
		$this->getEntityManager()->persist($context);
	}
	
	private function updateAddress($targetAddressId, Customweb_Payment_Authorization_OrderContext_IAddress $source) {
		if (empty($targetAddressId)) {
			$address = new Address();
			$address->alias = 'default';
		}
		else {
			$address = new Address($targetAddressId);
		}
		
		$address->firstname = $source->getFirstName();
		$address->lastname = $source->getLastName();
		$address->address1 = $source->getStreet();
		$address->city = $source->getCity();
		$address->postcode = $source->getPostCode();
		$address->company = $source->getCompanyName();
		$address->address2 = '';
		
		$phone = $source->getPhoneNumber();
		$phone = preg_replace('/[^0-9. ()-+]/', '', $phone);
		$address->phone = $phone;
		
		 
		$mobile = $source->getMobilePhoneNumber();
		$mobile = preg_replace('/[^0-9. ()-+]/', '', $mobile);
		$address->phone_mobile = $mobile;
		
		$code = $source->getCountryIsoCode();
		if (!empty($code)) {
			$address->id_country = Country::getByIso($source->getCountryIsoCode());
		}
		
		$state = $source->getState();
		if (!empty($state) && !empty($address->id_country)) {
			$address->id_state = State::getIdByIso($state, $address->id_country);
		}
		$address->save();
		
		return $address->id;
	}
	
	protected function updateUserSessionWithCurrentUser(Customweb_Payment_ExternalCheckout_AbstractContext $context) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		$sessionContext = Context::getContext();
		if ($sessionContext->cookie->logged) {
			return;
		}
		$email = $context->getCustomerEmailAddress();
		if (!empty($email) && $context->getBillingAddress() !== null) {
			$sessionContext = Context::getContext();
			$cart = new Cart($context->getCartId());
			if (!Customer::customerExists($context->getCustomerEmailAddress())) {
				$customer = new Customer();
				$customer->is_guest = 1;
				$customer->firstname = Tools::ucwords($context->getBillingAddress()->getFirstName());
				$customer->lastname = $context->getBillingAddress()->getLastName();
				$customer->email = $email;
				$customer->passwd = md5(Customweb_Core_Util_Rand::getRandomString(32));
				$result = $customer->add();
				if ($result == false) {
					throw new Exception("Unable to create the guest account. You may need to activate the guest checkout feature.");
				}
				$customer->addGroups(array((int)Configuration::get('PS_CUSTOMER_GROUP')));
			}
			else {
				$data = current(Customer::getCustomersByEmail($email));
				$customer = new Customer($data['id_customer']);
			}
			$customer->update();
			$cart->secure_key = $customer->secure_key;
			$cart->id_customer = $customer->id;
			$cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
			$cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
			$cart->update();
			$sessionContext->customer = $customer;
			$sessionContext->cookie->id_customer = (int)$customer->id;
			$sessionContext->cookie->customer_lastname = $customer->lastname;
			$sessionContext->cookie->customer_firstname = $customer->firstname;
			$sessionContext->cookie->passwd = $customer->passwd;
			$sessionContext->cookie->logged = 1;
			$customer->logged = 1;
			$sessionContext->cookie->email = $customer->email;
			$sessionContext->cookie->is_guest = $customer->is_guest;
		}
	}
	
	public function authenticate(Customweb_Payment_ExternalCheckout_IContext $context, $emailAddress, $successUrl) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		if ($context->getBillingAddress() === null) {
			throw new Exception("The authenticate method can not be called before the billing address is set on the context.");
		}
		
		$sessionContext = Context::getContext();
		if ($sessionContext->cookie->logged) {
			return Customweb_Core_Http_Response::redirect($successUrl);
		}
		
		$externalCheckout = PostFinanceCw::getInstance()->getConfiguraionValue('external_checkout_account_creation');
		$display_guest_checkout = 1;
		if ($externalCheckout === 'skip_selection' && Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
			$display_guest_checkout = 0;
			if (!empty($emailAddress) && !Customer::customerExists($emailAddress)) {
				$this->updateCustomerEmailAddress($context, $emailAddress);
				return Customweb_Core_Http_Response::redirect($successUrl);
			}
		}
		
		$context->setAuthenticationEmailAddress($emailAddress);
		$context->setAuthenticationSuccessUrl($successUrl);
		$this->getEntityManager()->persist($context);
		
		$link = new Link();
		$url = $link->getPageLink('authentication', true, null, array(
			'postfinancecw-context-id' => $context->getContextId(), 
			'token' => $context->getSecurityToken(),
			'display_guest_checkout' => $display_guest_checkout,
		));
		return Customweb_Core_Http_Response::redirect($url);
	}
	
	protected function createTransactionContextFromContext(Customweb_Payment_ExternalCheckout_IContext $context) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		$paymentMethod = $context->getPaymentMethod();
		if (!($paymentMethod instanceof PostFinanceCw_PaymentMethod)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_PaymentMethod');
		}
		$orderContext = new PostFinanceCw_OrderContext(new Cart($context->getCartId()), new PostFinanceCw_PaymentMethodWrapper($paymentMethod));
		return $paymentMethod->createTransactionContext($orderContext, null, null);
	}

	public function getPossiblePaymentMethods(Customweb_Payment_ExternalCheckout_IContext $context) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		$paymentMethods = array();
		foreach (PaymentModule::getInstalledPaymentModules() as $module) {
			if (strpos($module['name'], 'postfinancecw_') === 0) {
				$paymentMethods[] = Module::getInstanceByName($module['name']);
			}
		}
		
		return $paymentMethods;
	}
	

	public function renderAdditionalFormElements(Customweb_Payment_ExternalCheckout_IContext $context, $errorMessage) {
		$templateContext = new Customweb_Mvc_Template_RenderContext();
		$templateContext->setSecurityPolicy(new Customweb_Mvc_Template_SecurityPolicy());
		$templateContext->setTemplate('checkout/additional-form-fields');
		$templateContext->addVariable('errorMessage', $errorMessage);
		
		$templateContext->addVariable('customerMessage', Tools::getValue('customerMessage', ''));
		return PostFinanceCw_Util::getTemplateRenderer()->render($templateContext);
	}
	
	public function processAdditionalFormElements(Customweb_Payment_ExternalCheckout_IContext $context, Customweb_Core_Http_IRequest $request) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		$parameters = $request->getParameters();
		
		if (isset($parameters['customerMessage'])) {
			$this->updateMessage(strip_tags($parameters['customerMessage']), new Cart($context->getCartId()));
		}
	}
	
	
	public function renderReviewPane(Customweb_Payment_ExternalCheckout_IContext $context, $renderConfirmationFormElements, $errorMessage) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}

		$this->refreshContext($context);
		$this->getEntityManager()->persist($context);
		
		$cart = new Cart($context->getCartId());
		
		$sessionContext = Context::getContext();
		$sessionContext->cookie->cart_total_amount = $cart->getOrderTotal();
		
		$templateContext = new Customweb_Mvc_Template_RenderContext();
		$templateContext->setSecurityPolicy(new Customweb_Mvc_Template_SecurityPolicy());
		$templateContext->setTemplate('checkout/order-review');
		$templateContext->addVariables($this->getReviewPaneVariables($cart, $renderConfirmationFormElements));
		$templateContext->addVariable('confirmationError', $errorMessage);
		$templateContext->addVariable('cart', $cart);

		if ($context->getBillingAddress() != null) {
			$address = new Address($cart->id_address_invoice);
			$fields = AddressFormat::getOrderedAddressFields($address->id_country);
			$templateContext->addVariable('address_invoice', $address);
			$templateContext->addVariable('inv_adr_fields', $fields);
			$deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($address, $fields);
			$templateContext->addVariable('invoiceAddressFormatedValues', $deliveryAddressFormatedValues);
		}
	
		if ($context->getShippingAddress() != null) {
			$address = new Address($cart->id_address_delivery);
			$fields = AddressFormat::getOrderedAddressFields($address->id_country);
			$templateContext->addVariable('address_delivery', $address);
			$templateContext->addVariable('dlv_adr_fields', $fields);
			$deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($address, $fields);
			$templateContext->addVariable('deliveryAddressFormatedValues', $deliveryAddressFormatedValues);
		}
		
		return PostFinanceCw_Util::getTemplateRenderer()->render($templateContext);
	}

	public function validateReviewForm(Customweb_Payment_ExternalCheckout_IContext $context, Customweb_Core_Http_IRequest $request) {
		if (!($context instanceof PostFinanceCw_Entity_ExternalCheckoutContext)) {
			throw new Customweb_Core_Exception_CastException('PostFinanceCw_Entity_ExternalCheckoutContext');
		}
		
		$cart = new Cart($context->getCartId());
		$sessionContext = Context::getContext();
		if ($sessionContext->cookie->cart_total_amount != $cart->getOrderTotal()) {
			throw new Exception(PostFinanceCw::translate("Cart content was modified."));
		}
		
		$parameters = $request->getParameters();
		if ((int)(Configuration::get('PS_CONDITIONS')) && (!isset($parameters['cgv']) || $parameters['cgv'] !== '1')) {
			throw new Exception(PostFinanceCw::translate("You need to accept the general terms and conditions."));
		}
	}
	
	private function updateMessage($messageContent, Cart $cart) {
		if (!empty($messageContent)) {
			if (!Validate::isMessage($messageContent)) {
				throw new Exception(PostFinanceCw::translate('Invalid message'));
			}
			else if ($oldMessage = Message::getMessageByCartId((int)($cart->id)))
			{
				$message = new Message((int)($oldMessage['id_message']));
				$message->message = $messageContent;
				$message->update();
			}
			else
			{
				$message = new Message();
				$message->message = $messageContent;
				$message->id_cart = (int)($cart->id);
				$message->id_customer = (int)($cart->id_customer);
				$message->add();
			}
		}
		else {
			if ($oldMessage = Message::getMessageByCartId($cart->id)) {
				$message = new Message($oldMessage['id_message']);
				$message->delete();
			}
		}
	}
	
	
	
	protected function getReviewPaneVariables(Cart $cart, $renderGtc)
	{
		$summary = $cart->getSummaryDetails();
		$customizedDatas = Product::getAllCustomizedDatas($cart->id);
	
		// override customization tax rate with real tax (tax rules)
		if ($customizedDatas)
		{
			foreach ($summary['products'] as &$productUpdate)
			{
				$productId = (int)(isset($productUpdate['id_product']) ? $productUpdate['id_product'] : $productUpdate['product_id']);
				$productAttributeId = (int)(isset($productUpdate['id_product_attribute']) ? $productUpdate['id_product_attribute'] : $productUpdate['product_attribute_id']);
	
				if (isset($customizedDatas[$productId][$productAttributeId]))
					$productUpdate['tax_rate'] = Tax::getProductTaxRate($productId, $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
			}
	
			Product::addCustomizationPrice($summary['products'], $customizedDatas);
		}
	
		$cart_product_context = Context::getContext()->cloneContext();
		foreach ($summary['products'] as $key => &$product)
		{
			$product['quantity'] = $product['cart_quantity'];// for compatibility with 1.2 themes
	
			if ($cart_product_context->shop->id != $product['id_shop'])
				$cart_product_context->shop = new Shop((int)$product['id_shop']);
			$product['price_without_specific_price'] = Product::getPriceStatic(
					$product['id_product'],
					!Product::getTaxCalculationMethod(),
					$product['id_product_attribute'],
					2,
					null,
					false,
					false,
					1,
					false,
					null,
					null,
					null,
					$null,
					true,
					true,
					$cart_product_context);
	
			if (Product::getTaxCalculationMethod())
				$product['is_discounted'] = $product['price_without_specific_price'] != $product['price'];
			else
				$product['is_discounted'] = $product['price_without_specific_price'] != $product['price_wt'];
		}
	
		// Get available cart rules and unset the cart rules already in the cart
		$available_cart_rules = CartRule::getCustomerCartRules($cart->id_lang, (isset($cart->id_customer) ? $cart->id_customer : 0), true, true, true, $cart);
		$cart_cart_rules = $cart->getCartRules();
		foreach ($available_cart_rules as $key => $available_cart_rule)
		{
			if (!$available_cart_rule['highlight'] || strpos($available_cart_rule['code'], 'BO_ORDER_') === 0)
			{
				unset($available_cart_rules[$key]);
				continue;
			}
			foreach ($cart_cart_rules as $cart_cart_rule)
				if ($available_cart_rule['id_cart_rule'] == $cart_cart_rule['id_cart_rule'])
				{
					unset($available_cart_rules[$key]);
					continue 2;
				}
		}
	
		$show_option_allow_separate_package = (!$cart->isAllProductsInStock(true) && Configuration::get('PS_SHIP_WHEN_AVAILABLE'));
	
		$currency = new Currency($cart->id_currency);
		
		$cms = new CMS(Configuration::get('PS_CONDITIONS_CMS_ID'), $cart->id_lang);
		$link_conditions = Context::getContext()->link->getCMSLink($cms, $cms->link_rewrite, (bool)Configuration::get('PS_SSL_ENABLED'));
		if (!strpos($link_conditions, '?'))
			$link_conditions .= '?content_only=1';
		else
			$link_conditions .= '&content_only=1';
		
		$vars = array(
			'token_cart' => Tools::getToken(false),
			'isLogged' => 0,
			'checkedTOS' => 0,
			'isVirtualCart' => $cart->isVirtualCart(),
			'productNumber' => $cart->nbProducts(),
			'voucherAllowed' => 0,
			'shippingCost' => $cart->getOrderTotal(true, Cart::ONLY_SHIPPING),
			'shippingCostTaxExc' => $cart->getOrderTotal(false, Cart::ONLY_SHIPPING),
			'customizedDatas' => $customizedDatas,
			'CUSTOMIZE_FILE' => Product::CUSTOMIZE_FILE,
			'CUSTOMIZE_TEXTFIELD' => Product::CUSTOMIZE_TEXTFIELD,
			'lastProductAdded' => $cart->getLastProduct(),
			'displayVouchers' => $available_cart_rules,
			'currencySign' => $currency->sign,
			'currencyRate' => $currency->conversion_rate,
			'currencyFormat' => $currency->format,
			'currencyBlank' => $currency->blank,
			'show_option_allow_separate_package' => $show_option_allow_separate_package,
			'smallSize' => Image::getSize(ImageType::getFormatedName('small')),
			'cms_id' => (int)(Configuration::get('PS_CONDITIONS_CMS_ID')),
			'conditions' => (int)(Configuration::get('PS_CONDITIONS')) && $renderGtc,
			'link_conditions' => $link_conditions,
		);
	
		
		return array_merge($summary, $vars);
	}

}