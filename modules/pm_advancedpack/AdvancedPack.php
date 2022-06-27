<?php
/**
 * @name Advanced Pack 5
 * @author Presta-Module.com <support@presta-module.com> - http://www.presta-module.com
 * @copyright Presta-Module 2015 - http://www.presta-module.com
 *
 * 		 	 ____     __  __
 * 			|  _ \   |  \/  |
 * 			| |_) |  | |\/| |
 * 			|  __/   | |  | |
 * 			|_|      |_|  |_|
 *
 *
 *************************************
 **         Advanced Pack 5         **
 *************************************
 */
if (!defined('_PS_VERSION_'))
	exit;
class AdvancedPack extends Product {
	const MODULE_ID = 'AP5';
	const PACK_FAKE_STOCK = 10000;
	public static function getPriceStaticPack($id_product, $usetax = true, $id_product_attribute = null, $decimals = 6, $divisor = null,
		$only_reduc = false, $usereduc = true, $quantity = 1, $id_customer = null, $id_cart = null,
		$id_address = null, &$specific_price_output = null, $with_ecotax = true, $use_group_reduction = true, Context $context = null,
		$use_customer_price = true)
	{
		if (!$context)
			$context = Context::getContext();
		$cur_cart = $context->cart;
		if ($divisor !== null)
			Tools::displayParameterAsDeprecated('divisor');
		if (!Validate::isBool($usetax) || !Validate::isUnsignedId($id_product))
			die(Tools::displayError());
		$id_group = (int)Group::getCurrent()->id;
		if (!is_object($cur_cart) || (Validate::isUnsignedInt($id_cart) && $id_cart && $cur_cart->id != $id_cart))
		{
			if (!$id_cart && !isset($context->employee))
				die(Tools::displayError());
			$cur_cart = new Cart($id_cart);
			if (!Validate::isLoadedObject($context->cart))
				$context->cart = $cur_cart;
		}
		$id_currency = (int)Validate::isLoadedObject($context->currency) ? $context->currency->id : Configuration::get('PS_CURRENCY_DEFAULT');
		$id_country = (int)$context->country->id;
		$id_state = 0;
		$zipcode = 0;
		if (!$id_address && Validate::isLoadedObject($cur_cart))
			$id_address = $cur_cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		if ($id_address)
		{
			$address_infos = Address::getCountryAndState($id_address);
			if ($address_infos['id_country'])
			{
				$id_country = (int)$address_infos['id_country'];
				$id_state = (int)$address_infos['id_state'];
				$zipcode = $address_infos['postcode'];
			}
		}
		else if (isset($context->customer->geoloc_id_country))
		{
			$id_country = (int)$context->customer->geoloc_id_country;
			$id_state = (int)$context->customer->id_state;
			$zipcode = (int)$context->customer->postcode;
		}
		if (Tax::excludeTaxeOption())
			$usetax = false;
		if ($usetax != false
			&& !empty($address_infos['vat_number'])
			&& $address_infos['id_country'] != Configuration::get('VATNUMBER_COUNTRY')
			&& Configuration::get('VATNUMBER_MANAGEMENT'))
			$usetax = false;
		if (is_null($id_customer) && Validate::isLoadedObject($context->customer))
			$id_customer = $context->customer->id;
		return Product::priceCalculation(
			$context->shop->id,
			$id_product,
			$id_product_attribute,
			$id_country,
			$id_state,
			$zipcode,
			$id_currency,
			$id_group,
			$quantity,
			$usetax,
			$decimals,
			$only_reduc,
			$usereduc,
			$with_ecotax,
			$specific_price_output,
			$use_group_reduction,
			$id_customer,
			$use_customer_price,
			$id_cart,
			$quantity
		);
	}
	public static function getPackContent($idPack, $idProductAttribute = null, $withFrontDatas = false, $attributesList = array()) {
		$idLang = (int)Context::getContext()->language->id;
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.(int)$idProductAttribute.(int)$withFrontDatas.serialize($attributesList), true);
		if (!self::isInCache($cacheId, true)) {
			$sql = new DbQuery();
			$sql->select('*');
			$sql->from('pm_advancedpack_products', 'app');
			if ($idProductAttribute != null && $idProductAttribute) {
				$sql->innerJoin('pm_advancedpack_cart_products', 'acp', 'acp.`id_pack`='.(int)$idPack.' AND acp.`id_product_pack`=app.`id_product_pack` AND acp.`id_product_attribute_pack`='.(int)$idProductAttribute);
			}
			$sql->where('app.`id_pack`='.(int)$idPack);
			$sql->orderBy('app.`position` ASC');
			$productsPack = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if ($withFrontDatas && AdvancedPackCoreClass::_isFilledArray($productsPack)) {
				$config = pm_advancedpack::getModuleConfigurationStatic();
				list($address, $useTax) = self::getAddressInstance();
				$groupReduction = Group::getReductionByIdGroup((int)Group::getCurrent()->id);
				$tax_manager = TaxManagerFactory::getManager($address, AdvancedPack::getPackIdTaxRulesGroup((int)$idPack));
				$taxCalculator = $tax_manager->getTaxCalculator();
				$gsrModuleInstance = Module::getInstanceByName('gsnippetsreviews');
				if (version_compare(_PS_VERSION_, '1.6.0.0', '<') || !Validate::isLoadedObject($gsrModuleInstance) || !$gsrModuleInstance->active || version_compare($gsrModuleInstance->version, '4.0.0', '<'))
					$gsrModuleInstance = false;
				foreach ($productsPack as $productRowKey => $packProduct) {
					if (!isset($attributesList[$packProduct['id_product_pack']]) || !is_numeric($attributesList[$packProduct['id_product_pack']]))
						$idProductAttribute = (int)$packProduct['default_id_product_attribute'];
					else
						$idProductAttribute = (int)$attributesList[$packProduct['id_product_pack']];
					$productsPack[$productRowKey]['productObj'] = new Product((int)$packProduct['id_product'], false, (int)$idLang);
					if (Validate::isLoadedObject($productsPack[$productRowKey]['productObj']))
						self::transformProductDescriptionWithImg($productsPack[$productRowKey]['productObj']);
					$productsPack[$productRowKey]['image'] = self::_getProductCoverImage((int)$packProduct['id_product'], (int)$idProductAttribute);
					if (isset($config['showImagesOnlyForCombinations']) && $config['showImagesOnlyForCombinations'])
						$productsPack[$productRowKey]['images'] = Image::getImages($idLang, (int)$packProduct['id_product'], (int)$idProductAttribute);
					else
						$productsPack[$productRowKey]['images'] = self::_getProductImages($packProduct, $idLang);
					$specificPriceOutput = null;
					$productPackPrice = $productClassicPrice = self::getPriceStaticPack((int)$packProduct['id_product'], true, (int)$idProductAttribute, 6, null, false, (bool)$packProduct['use_reduc'], $packProduct['quantity'], null, null, null, $specificPriceOutput, false, false);
					$productPackPriceTaxExcl = $productClassicPriceTaxExcl = self::getPriceStaticPack((int)$packProduct['id_product'], false, (int)$idProductAttribute, 6, null, false, (bool)$packProduct['use_reduc'], $packProduct['quantity'], null, null, null, $specificPriceOutput, false, false);
					if ($packProduct['reduction_type'] == 'amount') {
						$productPackPrice -= Tools::ps_round($useTax ? $taxCalculator->addTaxes($packProduct['reduction_amount']) : $packProduct['reduction_amount'], 6);
						$productPackPriceTaxExcl -= $packProduct['reduction_amount'];
					} else if ($packProduct['reduction_type'] == 'percentage') {
						$productPackPrice *= (1 - $packProduct['reduction_amount']);
						$productPackPriceTaxExcl *= (1 - $packProduct['reduction_amount']);
					}
					if ($groupReduction > 0) {
						$productPackPriceTaxExcl -= ($productPackPriceTaxExcl * $groupReduction / 100);
						$productPackPrice -= ($productPackPrice * $groupReduction / 100);
					}
					if ($productPackPriceTaxExcl < 0)
						$productPackPrice = $productPackPriceTaxExcl = 0;
					$productEcoTax = self::getProductEcoTax((int)$packProduct['id_product']);
					if ($useTax) {
						$tax_manager = TaxManagerFactory::getManager($address, (int)Configuration::get('PS_ECOTAX_TAX_RULES_GROUP_ID'));
						$ecotax_tax_calculator = $tax_manager->getTaxCalculator();
						$productPackPrice += $ecotax_tax_calculator->addTaxes($productEcoTax);
						$productPackPriceTaxExcl += $ecotax_tax_calculator->addTaxes($productEcoTax);
						$productClassicPrice += $ecotax_tax_calculator->addTaxes($productEcoTax);
						$productClassicPriceTaxExcl += $ecotax_tax_calculator->addTaxes($productEcoTax);
					} else {
						$productPackPrice += $productEcoTax;
						$productPackPriceTaxExcl += $productEcoTax;
						$productClassicPrice += $productEcoTax;
						$productClassicPriceTaxExcl += $productEcoTax;
					}
					if ($packProduct['reduction_type'] == 'amount') {
						$productsPack[$productRowKey]['reduction_amount_tax_incl'] = Tools::ps_round($useTax ? $taxCalculator->addTaxes($packProduct['reduction_amount']) : $packProduct['reduction_amount'], 6);
						$productsPack[$productRowKey]['reduction_amount_tax_excl'] = Tools::ps_round($packProduct['reduction_amount'], 6);
					}
					$productsPack[$productRowKey]['productPackPrice'] = $productPackPrice;
					$productsPack[$productRowKey]['productPackPriceTaxExcl'] = $productPackPriceTaxExcl;
					$productsPack[$productRowKey]['productClassicPrice'] = $productClassicPrice;
					$productsPack[$productRowKey]['productClassicPriceTaxExcl'] = $productClassicPriceTaxExcl;
					$productsPack[$productRowKey]['attributes'] = false;
					if ($idProductAttribute)
						$productsPack[$productRowKey]['attributes'] = self::_getProductAttributesGroups($productsPack[$productRowKey]['productObj'], (int)$idProductAttribute, self::getProductAttributeWhiteList($packProduct['id_product_pack']), (int)$idLang);
					$productsPack[$productRowKey]['id_product_attribute'] = (int)$idProductAttribute;
					$productsPack[$productRowKey]['features'] = $productsPack[$productRowKey]['productObj']->getFrontFeatures((int)$idLang);
					$productsPack[$productRowKey]['accessories'] = $productsPack[$productRowKey]['productObj']->getAccessories((int)$idLang);
					$productsPack[$productRowKey]['attachments'] = (($productsPack[$productRowKey]['productObj']->cache_has_attachments) ? $productsPack[$productRowKey]['productObj']->getAttachments((int)$idLang) : array());
					if ($gsrModuleInstance) {
						$productsPack[$productRowKey]['gsrAverage'] = $gsrModuleInstance->hookProductRating(array('id' => (int)$packProduct['id_product'], 'display' => 'productRating'));
						if (!empty($productsPack[$productRowKey]['gsrAverage']))
							$productsPack[$productRowKey]['gsrReviewsList'] = $gsrModuleInstance->hookDisplayProductTabContent(array('product' => $productsPack[$productRowKey]['productObj']));
					}
				}
			}
			if (AdvancedPackCoreClass::_isFilledArray($productsPack)) {
				self::storeInCache($cacheId, $productsPack, true);
				return $productsPack;
			};
		} else {;
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, false, true);
		return false;
	}
	public static function getPackContentGroupByProduct($productsPack) {
		$idProductList = array();
		foreach ($productsPack as $productRowKey => $packProduct) {
			if (!in_array((int)$packProduct['id_product'], $idProductList)) {
				$idProductList[] = (int)$packProduct['id_product'];
			} else {
				unset($productsPack[$productRowKey]);
				continue;
			}
		}
		return $productsPack;
	}
	public static function getPackPriceTable($packContent, $packFixedPrice = 0, $packIdTaxRulesGroup = 0, $useTax = true, $includeEcoTax = true, $useGroupReduction = false, $attributesList = array()) {
		$cacheId = self::getPMCacheId(__METHOD__.serialize($packContent).(float)$packFixedPrice.(int)$packIdTaxRulesGroup.(int)$useTax.(int)$includeEcoTax.(int)$useGroupReduction.serialize($attributesList), true);
		if (!self::isInCache($cacheId, true)) {
			list($address) = self::getAddressInstance();
			$groupReduction = Group::getReductionByIdGroup((int)Group::getCurrent()->id);
			$totalClassicPriceWithoutTaxes = $totalClassicPriceWithTaxes = $totalEcoTax = 0;
			if ($packContent !== false) {
				foreach ($packContent as &$packProduct) {
					$productPackIdAttribute = (isset($attributesList[(int)$packProduct['id_product_pack']]) ? $attributesList[(int)$packProduct['id_product_pack']] : (isset($packProduct['id_product_attribute']) && (int)$packProduct['id_product_attribute'] ? (int)$packProduct['id_product_attribute'] : (int)$packProduct['default_id_product_attribute']));
					$specificPriceOutput = null;
					$productPackPriceWt = $productClassicPriceWt = self::getPriceStaticPack($packProduct['id_product'], true, $productPackIdAttribute, 6, null, false, (bool)$packProduct['use_reduc'], (int)$packProduct['quantity'], null, null, null, $specificPriceOutput, false, $useGroupReduction);
					$productPackPrice = $productClassicPrice = self::getPriceStaticPack($packProduct['id_product'], false, $productPackIdAttribute, 6, null, false, (bool)$packProduct['use_reduc'], (int)$packProduct['quantity'], null, null, null, $specificPriceOutput, false, $useGroupReduction);
					$taxManager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$packProduct['id_product']));
					$productTaxCalculator = $taxManager->getTaxCalculator();
					if ($packProduct['reduction_type'] == 'amount') {
						$productPackPrice -= Tools::ps_round($packProduct['reduction_amount'], 6);
						$productPackPriceWt -= Tools::ps_round($useTax ? $productTaxCalculator->addTaxes($packProduct['reduction_amount']) : $packProduct['reduction_amount'], 6);
					} else if ($packProduct['reduction_type'] == 'percentage') {
						$productPackPrice *= (1 - $packProduct['reduction_amount']);
						$productPackPriceWt *= (1 - $packProduct['reduction_amount']);
					}
					if (!(bool)$packProduct['use_reduc'] && $useGroupReduction && $groupReduction > 0) {
						$productPackPrice -= ($productPackPrice * $groupReduction / 100);
						$productPackPriceWt -= ($productPackPriceWt * $groupReduction / 100);
					}
					if ($productPackPrice < 0)
						$productPackPrice = $productPackPriceWt = 0;
					$productEcoTax = self::getProductEcoTax((int)$packProduct['id_product']);
					if ($useTax) {
						$taxManager = TaxManagerFactory::getManager($address, (int)Configuration::get('PS_ECOTAX_TAX_RULES_GROUP_ID'));
						$ecoTaxCalculator = $taxManager->getTaxCalculator();
						$productPackPrice += $ecoTaxCalculator->addTaxes($productEcoTax);
						$productPackPriceWt += $ecoTaxCalculator->addTaxes($productEcoTax);
						$productClassicPrice += $ecoTaxCalculator->addTaxes($productEcoTax);
						$productClassicPriceWt += $ecoTaxCalculator->addTaxes($productEcoTax);
						$totalEcoTax += $ecoTaxCalculator->addTaxes($productEcoTax);
					} else {
						$productPackPrice += $productEcoTax;
						$productPackPriceWt += $productEcoTax;
						$productClassicPrice += $productEcoTax;
						$productClassicPriceWt += $productEcoTax;
						$totalEcoTax += $productEcoTax;
					}
					$totalClassicPriceWithoutTaxes += $productClassicPrice;
					$totalClassicPriceWithTaxes += $productClassicPriceWt;
					$packProduct['priceInfos'] = array(
						'productPackPrice' => $productPackPrice,
						'productPackPriceWt' => $productPackPriceWt,
						'productClassicPrice' => $productClassicPrice,
						'productClassicPriceWt' => $productClassicPriceWt,
						'taxesClassic' => $productClassicPriceWt - $productClassicPrice,
						'taxesPack' => ($productPackPriceWt - $productEcoTax) - $productTaxCalculator->removeTaxes($productPackPriceWt - $productEcoTax),
						'productEcoTax' => $productEcoTax,
						'quantity' =>  (int)$packProduct['quantity'],
					);
				}
				if ($packFixedPrice > 0) {
					foreach ($packContent as &$packProduct) {
						$taxManager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$packProduct['id_product']));
						$productTaxCalculator = $taxManager->getTaxCalculator();
						if ($packIdTaxRulesGroup) {
							$packProduct['priceInfos']['productPackPriceWt'] = Tools::ps_round((($packProduct['priceInfos']['productPackPriceWt'] / (int)$packProduct['quantity']) / $totalClassicPriceWithoutTaxes) * $packFixedPrice, 6);
							if ($packProduct['priceInfos']['productEcoTax'] > 0)
								$packProduct['priceInfos']['productPackPrice'] = Tools::ps_round($productTaxCalculator->removeTaxes($packProduct['priceInfos']['productPackPriceWt'] - $packProduct['priceInfos']['productEcoTax']) + $packProduct['priceInfos']['productEcoTax'], 6);
							else
								$packProduct['priceInfos']['productPackPrice'] = Tools::ps_round((($packProduct['priceInfos']['productPackPrice'] / (int)$packProduct['quantity']) / $totalClassicPriceWithoutTaxes) * $packFixedPrice, 6);
						} else {
							$packProduct['priceInfos']['productPackPriceWt'] = Tools::ps_round((($packProduct['priceInfos']['productPackPriceWt'] / (int)$packProduct['quantity']) / $totalClassicPriceWithTaxes) * $packFixedPrice, 6);
							if ($packProduct['priceInfos']['productEcoTax'] > 0)
								$packProduct['priceInfos']['productPackPrice'] = Tools::ps_round($productTaxCalculator->removeTaxes($packProduct['priceInfos']['productPackPriceWt'] - $packProduct['priceInfos']['productEcoTax']) + $packProduct['priceInfos']['productEcoTax'], 6);
							else
								$packProduct['priceInfos']['productPackPrice'] = Tools::ps_round((($packProduct['priceInfos']['productPackPrice'] / (int)$packProduct['quantity']) / $totalClassicPriceWithTaxes) * $packFixedPrice, 6);
						}
					}
				}
			}
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, $packContent, true);
		return $packContent;
	}
	public static function getPackPrice($idPack, $useTax = true, $usePackReduction = true, $includeEcoTax = true, $priceDisplayPrecision = 6, $attributesList = array(), $packExcludeList = array(), $useGroupReduction = false) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.(int)$useTax.(int)$usePackReduction.(int)$includeEcoTax.(int)$priceDisplayPrecision.serialize($attributesList).serialize($packExcludeList), true);
		if (!self::isInCache($cacheId, true)) {
			$packContent = self::getPackContent($idPack);
			$packFixedPrice = self::getPackFixedPrice($idPack);
			$packClassicPrice = $packClassicPriceWt = $packPrice = $packPriceWt = $totalPackEcoTax = $totalPackEcoTaxWt = 0;
			list($address) = self::getAddressInstance();
			$packProducts = self::getPackPriceTable($packContent, $packFixedPrice, self::getPackIdTaxRulesGroup((int)$idPack), $useTax, $includeEcoTax, $useGroupReduction, $attributesList);
			foreach ($packProducts as $packProduct) {
				if (in_array((int)$packProduct['id_product_pack'], $packExcludeList))
					continue;
				$packClassicPrice += $packProduct['priceInfos']['productClassicPrice'] * (int)$packProduct['quantity'];
				$packClassicPriceWt += $packProduct['priceInfos']['productClassicPriceWt'] * (int)$packProduct['quantity'];
				$packPriceWt += $packProduct['priceInfos']['productPackPriceWt'] * (int)$packProduct['quantity'];
				$packPrice += $packProduct['priceInfos']['productPackPrice'] * (int)$packProduct['quantity'];
				$totalPackEcoTax += $packProduct['priceInfos']['productEcoTax'] * (int)$packProduct['quantity'];
				$totalPackEcoTaxWt += $packProduct['priceInfos']['productEcoTax'] * (int)$packProduct['quantity'];
				if ($usePackReduction && $packFixedPrice > 0) {
					if (isset($attributesList[$packProduct['id_product_pack']]) && (int)$packProduct['default_id_product_attribute'] != $attributesList[$packProduct['id_product_pack']]) {
						$defaultCombinationPriceImpact = Combination::getPrice($packProduct['default_id_product_attribute']);
						$idProductAttribute = (int)$attributesList[$packProduct['id_product_pack']];
						$combinationPriceImpact = Combination::getPrice($idProductAttribute);
						$specificPriceOutput = null;
						$productPackPrice = self::getPriceStaticPack($packProduct['id_product'], $useTax, $idProductAttribute, $priceDisplayPrecision, null, false, (bool)$packProduct['use_reduc'], (int)$packProduct['quantity'], null, null, null, $specificPriceOutput, false, false);
						if ($productPackPrice > 0 && $defaultCombinationPriceImpact > 0)
							$combinationPriceImpact -= $defaultCombinationPriceImpact;
						if ($useTax) {
							$taxManager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$packProduct['id_product']));
							$productTaxCalculator = $taxManager->getTaxCalculator();
							$packPrice += $combinationPriceImpact * (int)$packProduct['quantity'];
							$packPriceWt += $productTaxCalculator->addTaxes($combinationPriceImpact * (int)$packProduct['quantity']);
							$packClassicPrice += $combinationPriceImpact * (int)$packProduct['quantity'];
							$packClassicPriceWt += $productTaxCalculator->addTaxes($combinationPriceImpact * (int)$packProduct['quantity']);
						} else {
							$packPrice += $combinationPriceImpact * (int)$packProduct['quantity'];
							$packPriceWt += $combinationPriceImpact * (int)$packProduct['quantity'];
							$packClassicPrice += $combinationPriceImpact * (int)$packProduct['quantity'];
							$packClassicPriceWt += $combinationPriceImpact * (int)$packProduct['quantity'];
						}
					}
				}
			}
			if (!$includeEcoTax) {
				$packPrice -= $totalPackEcoTax;
				$packPriceWt -= $totalPackEcoTaxWt;
				$packClassicPrice -= $totalPackEcoTax;
				$packClassicPriceWt -= $totalPackEcoTaxWt;
			}
			if ($useTax) {
				if ($usePackReduction)
					return $packPriceWt;
				else
					return $packClassicPriceWt;
			} else {
				if ($usePackReduction)
					return $packPrice;
				else
					return $packClassicPrice;
			}
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, (float)$packPrice, true);
		return (float)$packPrice;
	}
	public static function getPackFixedPrice($idPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack, true);
		if (!self::isInCache($cacheId, true)) {
			$packFixedPrice = 0;
			$sql = new DbQuery();
			$sql->select('ap.`fixed_price`');
			$sql->from('pm_advancedpack', 'ap');
			$sql->where('ap.`id_pack`=' . (int)$idPack);
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
			if (!empty($result) & (float)$result > 0)
				$packFixedPrice = (float)$result;
			else
				$packFixedPrice = false;
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, (float)$packFixedPrice, true);
		return (float)$packFixedPrice;
	}
	public static function getPackAllowRemoveProduct($idPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack, true);
		if (!self::isInCache($cacheId, true)) {
			$sql = new DbQuery();
			$sql->select('ap.`allow_remove_product`');
			$sql->from('pm_advancedpack', 'ap');
			$sql->where('ap.`id_pack`=' . (int)$idPack);
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
			$packAllowRemoveProduct = (bool)$result;
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, (bool)$packAllowRemoveProduct, true);
		return (bool)$packAllowRemoveProduct;
	}
	private static function _getCartProducts() {
		$cartContent = array();
		if (is_object(Context::getContext()->controller) && isset(Context::getContext()->controller->controller_type) && Context::getContext()->controller->controller_type != 'front')
			return $cartContent;
		$cart = Context::getContext()->cart;
		if (Validate::isLoadedObject($cart)) {
			$cacheId = self::getPMCacheId(__METHOD__.(int)$cart->id, true);
			if (!self::isInCache($cacheId, true)) {
				$sql = 'SELECT `id_product`, `id_product_attribute`, `quantity` FROM `'._DB_PREFIX_.'cart_product` WHERE `id_cart` = '.(int)$cart->id;
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
				if (pm_advancedpack::_isFilledArray($result))
					foreach ($result as $cartRow)
						$cartContent[(int)$cartRow['id_product']][(int)$cartRow['id_product_attribute']] = (int)$cartRow['quantity'];
			} else {
				return self::getFromCache($cacheId, true);
			}
			self::storeInCache($cacheId, $cartContent, true);
		}
		return $cartContent;
	}
	public static function getCartQuantity($idProduct, $idProductAttribute = 0) {
		$cartProducts = self::_getCartProducts();
		if (isset($cartProducts[(int)$idProduct][(int)$idProductAttribute]))
			return $cartProducts[(int)$idProduct][(int)$idProductAttribute];
		return 0;
	}
	public static function getPackProductsCartQuantity($idProductAttribute = false) {
		$currentPackCartStock = array();
		if (is_object(Context::getContext()->controller) && isset(Context::getContext()->controller->controller_type) && Context::getContext()->controller->controller_type != 'front')
			return $currentPackCartStock;
		$cart = Context::getContext()->cart;
		if (Validate::isLoadedObject($cart)) {
			$cacheId = self::getPMCacheId(__METHOD__.(int)$cart->id.(int)$idProductAttribute, true);
			if (!self::isInCache($cacheId, true)) {
				foreach ($cart->getProducts() as $cartProduct) {
					if ($idProductAttribute !== false && (int)$idProductAttribute == (int)$cartProduct['id_product_attribute'])
						continue;
					if (AdvancedPack::isValidPack((int)$cartProduct['id_product'])) {
						$packContent = AdvancedPack::getPackContent((int)$cartProduct['id_product'], (int)$cartProduct['id_product_attribute']);
						if ($packContent !== false)
							foreach ($packContent as $packProduct)
								if (isset($currentPackCartStock[(int)$packProduct['id_product']][(int)$packProduct['id_product_attribute']]))
									$currentPackCartStock[(int)$packProduct['id_product']][(int)$packProduct['id_product_attribute']] += (int)$cartProduct['cart_quantity'] * (int)$packProduct['quantity'];
								else
									$currentPackCartStock[(int)$packProduct['id_product']][(int)$packProduct['id_product_attribute']] = (int)$cartProduct['cart_quantity'] * (int)$packProduct['quantity'];
					}
				}
			} else {
				return self::getFromCache($cacheId, true);
			}
			self::storeInCache($cacheId, $currentPackCartStock, true);
		}
		return $currentPackCartStock;
	}
	public static function getPackAvailableQuantity($idPack, $attributesList = array(), $packExcludeList = array(), $idProductAttribute = false, $useCache = true) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.serialize($attributesList).serialize($packExcludeList).(int)$idProductAttribute, true);
		$packAvailableQuantity = 0;
		if (!$useCache || !self::isInCache($cacheId, true)) {
			if (!self::isVirtualPack($idPack)) {
				$currentPackCartStock = self::getPackProductsCartQuantity($idProductAttribute);
				$packContent = self::getPackContent($idPack);
				$productPackQuantityList = array();
				$stockNeededByIdProductIdProductAttribute = array();
				if ($packContent !== false) {
					foreach ($packContent as $packProduct) {
						if (in_array((int)$packProduct['id_product_pack'], $packExcludeList))
							continue;
						if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$packProduct['id_product']))) {
							if (!isset($attributesList[$packProduct['id_product_pack']]) || !is_numeric($attributesList[$packProduct['id_product_pack']]))
								$idProductAttribute = (int)$packProduct['default_id_product_attribute'];
							else
								$idProductAttribute = (int)$attributesList[$packProduct['id_product_pack']];
							if (!isset($stockNeededByIdProductIdProductAttribute[(int)$packProduct['id_product']][$idProductAttribute]))
								$stockNeededByIdProductIdProductAttribute[(int)$packProduct['id_product']][$idProductAttribute] = (int)$packProduct['quantity'];
							else
								$stockNeededByIdProductIdProductAttribute[(int)$packProduct['id_product']][$idProductAttribute] += (int)$packProduct['quantity'];
						}
					}
					foreach ($packContent as $packProduct) {
						if (in_array((int)$packProduct['id_product_pack'], $packExcludeList))
							continue;
						if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$packProduct['id_product']))) {
							if (!isset($attributesList[$packProduct['id_product_pack']]) || !is_numeric($attributesList[$packProduct['id_product_pack']]))
								$idProductAttribute = (int)$packProduct['default_id_product_attribute'];
							else
								$idProductAttribute = (int)$attributesList[$packProduct['id_product_pack']];
							$cartPackStock = 0;
							if (isset($currentPackCartStock[(int)$packProduct['id_product']][(int)$idProductAttribute]))
								$cartPackStock = $currentPackCartStock[(int)$packProduct['id_product']][(int)$idProductAttribute];
							$productPackQuantityList[(int)$packProduct['id_product_pack']] = (int)floor(((int)StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product'], $idProductAttribute) - self::getCartQuantity((int)$packProduct['id_product'], (int)$idProductAttribute) - $cartPackStock) / (int)$stockNeededByIdProductIdProductAttribute[(int)$packProduct['id_product']][$idProductAttribute]);
						}
					}
				}
				if (AdvancedPackCoreClass::_isFilledArray($productPackQuantityList))
					$packAvailableQuantity = (int)min(array_values($productPackQuantityList));
				else
					$packAvailableQuantity = self::PACK_FAKE_STOCK;
			} else {
				$packAvailableQuantity = self::PACK_FAKE_STOCK;
			}
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, (int)$packAvailableQuantity, true);
		return (int)$packAvailableQuantity;
	}
	public static function getPackAvailableQuantityList($idPack, $attributesList = array(), $useCache = true) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.serialize($attributesList), true);
		if (!$useCache || !self::isInCache($cacheId, true)) {
			$currentPackCartStock = self::getPackProductsCartQuantity();
			$packContent = self::getPackContent($idPack);
			$productPackQuantityList = array();
			if ($packContent !== false) {
				foreach ($packContent as $packProduct) {
					$productAttributesList = self::getProductAttributeWhiteList($packProduct['id_product_pack']);
					if (!pm_advancedpack::_isFilledArray($productAttributesList))
						$productAttributesList = array_keys(self::getProductCombinationsByIdProductPack((int)$packProduct['id_product_pack']));
					if (!pm_advancedpack::_isFilledArray($productAttributesList))
						$productAttributesList = array(0);
					if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$packProduct['id_product']))) {
						foreach ($productAttributesList as $idProductAttribute) {
							$cartPackStock = 0;
							if (isset($currentPackCartStock[(int)$packProduct['id_product']][(int)$idProductAttribute]))
								$cartPackStock = $currentPackCartStock[(int)$packProduct['id_product']][(int)$idProductAttribute];
							$productPackQuantityList[(int)$packProduct['id_product_pack']][$idProductAttribute] = (int)floor(((int)StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product'], $idProductAttribute) - self::getCartQuantity((int)$packProduct['id_product'], (int)$idProductAttribute) - $cartPackStock) / (int)$packProduct['quantity']);
						}
					} else {
						foreach ($productAttributesList as $idProductAttribute)
							$productPackQuantityList[(int)$packProduct['id_product_pack']][$idProductAttribute] = self::PACK_FAKE_STOCK;
					}
				}
			}
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, $productPackQuantityList, true);
		return $productPackQuantityList;
	}
	public static function getPackWeight($idPack) {
		$packContent = self::getPackContent($idPack);
		$packWeight = 0;
		if ($packContent !== false) {
			foreach ($packContent as $packProduct) {
				$product = new Product((int)$packProduct['id_product']);
				$packWeight += (float)$product->weight * (int)$packProduct['quantity'];
			}
		}
		return (float)$packWeight;
	}
	public static function getPackIdTaxRulesGroup($idPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack, true);
		$finalIdTaxRulesGroup = 0;
		if (!self::isInCache($cacheId, true)) {
			$packContent = self::getPackContent($idPack);
			$idTaxRulesGroup = array();
			if ($packContent !== false)
				foreach ($packContent as $packProduct)
					$idTaxRulesGroup[] = (int)Product::getIdTaxRulesGroupByIdProduct((int)$packProduct['id_product']);
			$idTaxRulesGroup = array_unique($idTaxRulesGroup);
			if (sizeof($idTaxRulesGroup) == 1)
				$finalIdTaxRulesGroup = (int)current($idTaxRulesGroup);
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, $finalIdTaxRulesGroup, true);
		return $finalIdTaxRulesGroup;
	}
	public static function getPackEcoTax($idPack, $idProductAttributeList = array()) {
		$packContent = self::getPackContent($idPack);
		$packEcoTaxAmount = 0;
		if ($packContent !== false) {
			foreach ($packContent as $packProduct) {
				$product = new Product((int)$packProduct['id_product']);
				$specificPriceOutput = null;
				$productPackIdAttribute = (isset($idProductAttributeList[(int)$packProduct['id_product_pack']]) ? $idProductAttributeList[(int)$packProduct['id_product_pack']] : $packProduct['default_id_product_attribute']);
				if (!isset($idProductAttributeList[(int)$packProduct['id_product_pack']]))
					$idProductAttributeList[(int)$packProduct['id_product_pack']] = (int)$packProduct['default_id_product_attribute'];
				$productPackPrice = self::getPriceStaticPack($packProduct['id_product'], false, $productPackIdAttribute, 6, null, false, (bool)$packProduct['use_reduc'], (int)$packProduct['quantity'], null, null, null, $specificPriceOutput, false, false);
				if ($packProduct['reduction_type'] == 'amount') {
					$productPackPrice -= Tools::ps_round($packProduct['reduction_amount'], 6);
				} else if ($packProduct['reduction_type'] == 'percentage') {
					$productPackPrice *= (1 - $packProduct['reduction_amount']);
				}
				$packEcoTaxAmount += (float)$product->ecotax * (int)$packProduct['quantity'];
			}
		}
		return (float)$packEcoTaxAmount;
	}
	public static function getProductEcoTax($idProduct) {
		$product = new Product((int)$idProduct);
		if (Validate::isLoadedObject($product))
			return (float)$product->ecotax;
		return 0;
	}
	public static function getMaxImagesPerProduct($productsPack) {
		$maxImages = array();
		foreach ($productsPack as $productPack) {
			if (isset($productPack['images']) && is_array($productPack['images']))
				$maxImages[] = count($productPack['images']);
		}
		if (count($maxImages))
			return max($maxImages);
		return 0;
	}
	public static function getExclusiveProducts() {
		$cacheId = self::getPMCacheId(__METHOD__);
		if (!self::isInCache($cacheId)) {
			$idProductExclusiveList = array();
			$sql = new DbQuery();
			$sql->select('GROUP_CONCAT(app.`id_product`)');
			$sql->from('pm_advancedpack_products', 'app');
			$sql->where('app.`exclusive`=1');
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
			if (!empty($result))
				$idProductExclusiveList = explode(',', $result);
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $idProductExclusiveList);
		return $idProductExclusiveList;
	}
	public static function getIdsPacks($fromAllShop = false) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$fromAllShop);
		if (!self::isInCache($cacheId)) {
			$idPackList = array();
			$sql = new DbQuery();
			$sql->select('app.`id_pack`');
			$sql->from('pm_advancedpack', 'app');
			if (!$fromAllShop)
				$sql->where('app.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).')');
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $row)
					$idPackList[] = (int)$row['id_pack'];
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $idPackList);
		return $idPackList;
	}
	public static function getIdPacksByIdProduct($idProduct) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProduct);
		if (!self::isInCache($cacheId)) {
			$idPackList = array();
			$sql = new DbQuery();
			$sql->select('DISTINCT app.`id_pack`');
			$sql->from('pm_advancedpack', 'ap');
			$sql->innerJoin('pm_advancedpack_products', 'app', 'app.`id_pack` = ap.`id_pack`');
			$sql->where('ap.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).')');
			$sql->where('app.`id_product`='.(int)$idProduct);
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $row)
					$idPackList[] = (int)$row['id_pack'];
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $idPackList);
		return $idPackList;
	}
	public static function getIdProductAttributeListByIdPack($idPack, $idProductAttribute = null) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.($idProductAttribute !== null ? (int)$idProductAttribute : ''), true);
		$productAttributeList = array();
		if (!self::isInCache($cacheId, true)) {
			$sql = new DbQuery();
			$sql->select('*');
			$sql->from('pm_advancedpack_products', 'app');
			if ($idProductAttribute !== null)
					$sql->innerJoin('pm_advancedpack_cart_products', 'acp', 'acp.`id_pack`='.(int)$idPack.' AND acp.`id_product_pack`=app.`id_product_pack` AND acp.`id_product_attribute_pack`='.(int)$idProductAttribute);
			$sql->where('app.`id_pack`='.(int)$idPack);
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $row)
					if ($idProductAttribute !== null)
						$productAttributeList[(int)$row['id_product_pack']] = (int)$row['id_product_attribute'];
					else
						$productAttributeList[(int)$row['id_product_pack']] = (int)$row['default_id_product_attribute'];
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, $productAttributeList, true);
		return $productAttributeList;
	}
	public static function getPackAttributeUniqueName($idPack, $idProductAttribute, $idLang = null) {
		if ($idLang == null)
			$idLang = Context::getContext()->language->id;
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.(int)$idProductAttribute.(int)$idLang);
		if (!self::isInCache($cacheId)) {
			$productCombination = new Combination($idProductAttribute);
			$productAttributesNames = $productCombination->getAttributesName($idLang);
			if (is_array($productAttributesNames) && count($productAttributesNames) == 1) {
				$attributeName = current($productAttributesNames);
				if (isset($attributeName['name']) && !empty($attributeName['name'])) {
					self::storeInCache($cacheId, $attributeName['name']);
					return $attributeName['name'];
				}
			}
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, false);
		return false;
	}
	public static function getProductAttributeList($idProductAttribute, $idLang = null) {
		if ($idLang == null)
			$idLang = Context::getContext()->language->id;
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProductAttribute.(int)$idLang);
		if (!self::isInCache($cacheId)) {
			$attributeList = array('attributes' => array(), 'attributes_small' => array());
			if ($idProductAttribute) {
				$result = Db::getInstance()->executeS('
					SELECT pac.`id_product_attribute`, agl.`public_name` AS public_group_name, al.`name` AS attribute_name
					FROM `'._DB_PREFIX_.'product_attribute_combination` pac
					LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
					LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
					LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$idLang.')
					LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)$idLang.')
					WHERE pac.`id_product_attribute`='.(int)$idProductAttribute.'
					ORDER BY agl.`public_name` ASC'
				);
				if (AdvancedPackCoreClass::_isFilledArray($result)) {
					foreach ($result as $attributeRow) {
						$attributeList['attributes'][] = $attributeRow['public_group_name'].' : '.$attributeRow['attribute_name'];
						$attributeList['attributes_small'][] = $attributeRow['attribute_name'];
					}
					$attributeList['attributes'] = implode($attributeList['attributes'], ', ');
					$attributeList['attributes_small'] = implode($attributeList['attributes_small'], ', ');
				}
			}
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $attributeList);
		return $attributeList;
	}
	public static function getProductCombinations($idProduct, $ignoreModuleAttributeGroup = true) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProduct.(int)$ignoreModuleAttributeGroup.Context::getContext()->shop->id);
		if (!self::isInCache($cacheId)) {
			$combinationsList = array();
			$result = Db::getInstance()->executeS('
				SELECT pac.`id_product_attribute`, pac.`id_attribute`
				FROM `'._DB_PREFIX_.'product_attribute` pa
				' . Shop::addSqlAssociation('product_attribute', 'pa') .
				'JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`'
				. ($ignoreModuleAttributeGroup ? ' JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute` AND a.`id_attribute_group` != ' . (int)self::getPackAttributeGroupId() . ')' : '') .
				'WHERE pa.`id_product` = ' . (int)$idProduct
			);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $combinationRow)
					$combinationsList[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $combinationsList);
		return $combinationsList;
	}
	public static function getProductCombinationsByIdProductPack($idProductPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProductPack.Context::getContext()->shop->id);
		if (!self::isInCache($cacheId)) {
			$combinationsList = array();
			$result = Db::getInstance()->executeS('
				SELECT pac.`id_product_attribute`, pac.`id_attribute`
				FROM `'._DB_PREFIX_.'product_attribute` pa
				' . Shop::addSqlAssociation('product_attribute', 'pa') . '
				JOIN `'._DB_PREFIX_.'pm_advancedpack_products` app ON app.`id_product` = pa.`id_product`
				JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
				WHERE app.`id_product_pack` = ' . (int)$idProductPack
			);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $combinationRow)
					$combinationsList[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $combinationsList);
		return $combinationsList;
	}
	public static function getProductAttributeWhiteList($idProductPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProductPack);
		$whiteListFinal = array();
		if (!self::isInCache($cacheId)) {
			$sql = new DbQuery();
			$sql->select('appa.`id_product_attribute`');
			$sql->from('pm_advancedpack_products', 'app');
			$sql->innerJoin('pm_advancedpack_products_attributes', 'appa', 'appa.`id_product_pack`=app.`id_product_pack`');
			$sql->where('app.`id_product_pack`='.(int)$idProductPack);
			$whiteList = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if (AdvancedPackCoreClass::_isFilledArray($whiteList)) {
				foreach ($whiteList as $whiteListRow)
					$whiteListFinal[] = (int)$whiteListRow['id_product_attribute'];
				self::storeInCache($cacheId, $whiteListFinal);
				return $whiteListFinal;
			}
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, $whiteListFinal);
		return $whiteListFinal;
	}
	public static function getPackAttributeGroupId() {
		$cacheId = self::getPMCacheId(__METHOD__.Context::getContext()->language->id.Context::getContext()->shop->id);
		if (!self::isInCache($cacheId)) {
			$attributeGroups = AttributeGroup::getAttributesGroups(Context::getContext()->language->id);
			if (AdvancedPackCoreClass::_isFilledArray($attributeGroups)) {
				foreach ($attributeGroups as $attributeGroup) {
					if ($attributeGroup['name'] == 'AP5-Pack') {
						self::storeInCache($cacheId, (int)$attributeGroup['id_attribute_group']);
						return (int)$attributeGroup['id_attribute_group'];
					}
				}
			}
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, false);
		return false;
	}
	public static function addCustomPackProductAttribute($idPack, $attributesList, $packUniqueHash = false, $defaultCombination = false) {
		$idProductAttribute = false;
		$combinationObj = null;
		if ($packUniqueHash !== false) {
			$idProductAttribute = (int)Db::getInstance()->getValue('SELECT `id_product_attribute_pack` FROM `'._DB_PREFIX_.'pm_advancedpack_cart_products` WHERE `id_order` IS NULL AND `unique_hash` = "'.pSQL($packUniqueHash).'" AND `id_pack` = '.(int)$idPack.' AND `id_cart` = ' . (int)Context::getContext()->cookie->id_cart);
			if ($idProductAttribute) {
				$combinationObj = new Combination($idProductAttribute);
				if (!Validate::isLoadedObject($combinationObj)) {
					Db::getInstance()->getValue('DELETE FROM `'._DB_PREFIX_.'pm_advancedpack_cart_products` WHERE `id_product_attribute_pack`='.(int)$idProductAttribute.' AND `id_order` IS NULL AND `unique_hash` = "'.pSQL($packUniqueHash).'" AND `id_pack` = '.(int)$idPack.' AND `id_cart` = ' . (int)Context::getContext()->cookie->id_cart);
					$idProductAttribute = false;
				}
			}
		}
		if (!$idProductAttribute) {
			if ($defaultCombination)
				$uniqueId = $idPack.'-defaultCombination';
			else
				$uniqueId = uniqid();
			$attributeObj = new Attribute();
			$attributeObj->id_attribute_group = self::getPackAttributeGroupId();
			foreach (Language::getLanguages(false) as $lang)
				$attributeObj->name[$lang['id_lang']] = $uniqueId;
			if ($attributeObj->save()) {
				$idAttribute = $attributeObj->id;
				$combinationObj = new Combination();
				$combinationObj->id_product = (int)$idPack;
				$combinationObj->default_on = (bool)$defaultCombination;
				$idWarehouse = false;
				$packProducts = self::getPackContent($idPack);
				if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
					foreach ($packProducts as $packProduct) {
						$idProductAttributeWeight = (isset($attributesList[(int)$packProduct['id_product_pack']]) ? $attributesList[(int)$packProduct['id_product_pack']] : (int)$packProduct['default_id_product_attribute']);
						if ($idProductAttributeWeight) {
							$combinationWeightObj = new Combination($idProductAttributeWeight);
							if (Validate::isLoadedObject($combinationWeightObj))
								$combinationObj->weight += (float)$combinationWeightObj->weight * (int)$packProduct['quantity'];
							unset($combinationWeightObj);
						}
						if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && !$idWarehouse) {
							$warehouseList = Warehouse::getProductWarehouseList((int)$packProduct['id_product'], $idProductAttributeWeight);
							if (AdvancedPackCoreClass::_isFilledArray($warehouseList)) {
								foreach ($warehouseList as $warehouseRow) {
									$idWarehouse = (int)$warehouseRow['id_warehouse'];
									break;
								}
							}
						}
					}
				}
				unset($packProducts);
				if (!$combinationObj->save() || !$combinationObj->setAttributes(array($idAttribute)))
					return false;
				if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $idWarehouse) {
					$warehouseLocationEntity = new WarehouseProductLocation();
					$warehouseLocationEntity->id_product = (int)$combinationObj->id_product;
					$warehouseLocationEntity->id_product_attribute = (int)$combinationObj->id;
					$warehouseLocationEntity->id_warehouse = (int)$idWarehouse;
					$warehouseLocationEntity->location = '';
					$warehouseLocationEntity->save();
					StockAvailable::synchronize((int)$combinationObj->id_product);
				}
			}
		} else {
			$combinationObj = new Combination($idProductAttribute);
		}
		if (!Validate::isLoadedObject($combinationObj))
			return false;
		self::setStockAvailableQuantity($idPack, $combinationObj->id, self::getPackAvailableQuantity($idPack, $attributesList, array(), $combinationObj->id, false), false);
		return (int)$combinationObj->id;
	}
	public static function setStockAvailableQuantity($idProduct, $idProductAttribute, $quantity, $runUpdateQuantityHook = true) {
		$combinationObj = new Combination($idProductAttribute);
		if (Validate::isLoadedObject($combinationObj)) {
			$currentQuantity = (int)StockAvailable::getQuantityAvailableByProduct((int)$idProduct, (int)$idProductAttribute);
			if ($currentQuantity != $quantity) {
				$combinationObj->quantity = (int)$quantity;
				$combinationObj->minimal_quantity = 1;
				$combinationObj->save();
				if ($runUpdateQuantityHook) {
					return StockAvailable::setQuantity((int)$idProduct, (int)$idProductAttribute, (int)$quantity);
				} else {
					$id_shop = null;
					if (Shop::getContext() != Shop::CONTEXT_GROUP)
						$id_shop = (int)Context::getContext()->shop->id;
					$id_stock_available = (int)StockAvailable::getStockAvailableIdByProductId((int)$idProduct, (int)$idProductAttribute, $id_shop);
					if ($id_stock_available) {
						$stock_available = new StockAvailable($id_stock_available);
						if ((int)$stock_available->quantity != (int)$quantity) {
							$stock_available->quantity = (int)$quantity;
							$stock_available->update();
						}
					} else {
						$out_of_stock = StockAvailable::outOfStock((int)$idProduct, $id_shop);
						$stock_available = new StockAvailable();
						$stock_available->out_of_stock = (int)$out_of_stock;
						$stock_available->id_product = (int)$idProduct;
						$stock_available->id_product_attribute = (int)$idProductAttribute;
						$stock_available->quantity = (int)$quantity;
						if ($id_shop === null)
							$shop_group = Shop::getContextShopGroup();
						else
							$shop_group = new ShopGroup((int)Shop::getGroupFromShop((int)$id_shop));
						if ($shop_group->share_stock) {
							$stock_available->id_shop = 0;
							$stock_available->id_shop_group = (int)$shop_group->id;
						} else {
							$stock_available->id_shop = (int)$id_shop;
							$stock_available->id_shop_group = 0;
						}
						$stock_available->add();
					}
					Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int)$idProduct.'*');
				}
			}
		}
		return false;
	}
	public static function updatePackStock($idPack) {
		$packProducts = self::getPackContent($idPack);
		$finalAttributesList = array();
		$minStockAvailableByIdAttribute = array();
		$minStockAvailableForProductsWithoutAttributes = null;
		$attributeCombinations = array();
		$res = true;
		if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
			foreach ($packProducts as $key => $packProduct) {
				$product = new Product((int)$packProduct['id_product']);
				$attributesWhitelist = self::getProductAttributeWhiteList($packProduct['id_product_pack']);
				if (AdvancedPackCoreClass::_isFilledArray($attributesWhitelist)) {
					foreach ($attributesWhitelist as $idProductAttribute) {
						$combinationList = $product->getAttributeCombinationsById($idProductAttribute, Context::getContext()->language->id);
						if (AdvancedPackCoreClass::_isFilledArray($combinationList))
							foreach ($combinationList as $combinationRow) {
								$attributeCombinations[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
								$finalAttributesList[(int)$combinationRow['id_attribute_group']][] = (int)$combinationRow['id_attribute'];
								$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = (int)$combinationRow['quantity'];
							}
					}
				} else {
					$combinationList = $product->getAttributeCombinations(Context::getContext()->language->id);
					if (AdvancedPackCoreClass::_isFilledArray($combinationList)) {
						foreach ($combinationList as $combinationRow) {
							$attributeCombinations[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
							$finalAttributesList[(int)$combinationRow['id_attribute_group']][] = (int)$combinationRow['id_attribute'];
							$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = (int)$combinationRow['quantity'];
						}
					} else {
						$stockAvailable = StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product']);
						if ($minStockAvailableForProductsWithoutAttributes == null || $stockAvailable < $minStockAvailableForProductsWithoutAttributes)
							$minStockAvailableForProductsWithoutAttributes = $stockAvailable;
					}
				}
			}
		}
		if (AdvancedPackCoreClass::_isFilledArray($finalAttributesList)) {
			$combinationList = self::getProductCombinations($idPack);
			foreach ($finalAttributesList as $key => $attributeList)
				$finalAttributesList[$key] = array_unique($attributeList);
			$finalAttributesList = AdvancedPackCoreClass::array_cartesian($finalAttributesList);
			foreach ($combinationList as $packIdProductAttribute => $attributeList) {
				$availableQuantity = 1;
				foreach ($attributeCombinations as $idProductAttribute => $combinationAttributesList) {
					if (!count(array_diff($combinationAttributesList, $attributeList)) && isset($minStockAvailableByIdAttribute[$idProductAttribute])) {
						$availableQuantity = $minStockAvailableByIdAttribute[$idProductAttribute];
						break;
					}
				}
				if ($minStockAvailableForProductsWithoutAttributes !== null)
					$availableQuantity = min(array($minStockAvailableForProductsWithoutAttributes, $availableQuantity));
				self::setStockAvailableQuantity((int)$idPack, (int)$packIdProductAttribute, $availableQuantity);
			}
		} else {
			self::setStockAvailableQuantity((int)$idPack, (int)Product::getDefaultAttribute($idPack), self::getPackAvailableQuantity($idPack, array(), array(), false, false));
		}
		return $res;
	}
	public static function isValidPack($idPack, $deepCheck = false, $packExcludeList = array()) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.(int)$deepCheck.serialize($packExcludeList));
		if (!self::isInCache($cacheId)) {
			$packIdList = AdvancedPack::getIdsPacks(true);
			$result = in_array((int)$idPack, $packIdList);
			if ($result && $deepCheck) {
				$packContent = AdvancedPack::getPackContent($idPack);
				if ($packContent !== false) {
					foreach ($packContent as $packProduct) {
						if (in_array((int)$packProduct['id_product_pack'], $packExcludeList))
							continue;
						$product = new Product((int)$packProduct['id_product']);
						$result &= Validate::isLoadedObject($product) && $product->active;
						$result &= Validate::isLoadedObject($product) && $product->checkAccess(isset(Context::getContext()->customer) ? Context::getContext()->customer->id : 0);
						$result &= Validate::isLoadedObject($product) && $product->available_for_order;
					}
				}
			}
			self::storeInCache($cacheId, $result);
			return $result;
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, false);
		return false;
	}
	public static function isVirtualPack($idPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack);
		if (!self::isInCache($cacheId)) {
			$packContent = self::getPackContent($idPack);
			$isVirtual = true;
			if ($packContent !== false) {
				foreach ($packContent as $packProduct) {
					$product = new Product((int)$packProduct['id_product']);
					if ($product->getType() != Product::PTYPE_VIRTUAL) {
						$isVirtual = false;
						break;
					}
				}
			}
			self::storeInCache($cacheId, $isVirtual);
			return $isVirtual;
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, false);
		return false;
	}
	public static function isInStock($idPack, $quantity = 1, $attributesList = array(), $incrementCartQuantity = false, $idProductAttribute = false) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack.serialize($attributesList).(int)$incrementCartQuantity.(int)$idProductAttribute, true);
		$packIsInStock = true;
		if (!self::isInCache($cacheId, true)) {
			$currentPackCartStock = self::getPackProductsCartQuantity();
			if ($incrementCartQuantity)
				$packContent = self::getPackContent($idPack, $idProductAttribute);
			else
				$packContent = self::getPackContent($idPack);
			if ($packContent !== false) {
				foreach ($packContent as $packProduct) {
					if (!isset($attributesList[$packProduct['id_product_pack']]) || !is_numeric($attributesList[$packProduct['id_product_pack']]))
						$idProductAttribute = (int)$packProduct['default_id_product_attribute'];
					else
						$idProductAttribute = (int)$attributesList[$packProduct['id_product_pack']];
					$cartPackStock = 0;
					if (isset($currentPackCartStock[(int)$packProduct['id_product']][$idProductAttribute]))
						$cartPackStock = $currentPackCartStock[(int)$packProduct['id_product']][$idProductAttribute];
					if (Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$packProduct['id_product']))) {
						$packIsInStock &= true;
					} else {
						$stockAvailable = ((int)StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product'], $idProductAttribute) * $quantity) - self::getCartQuantity((int)$packProduct['id_product'], $idProductAttribute) - $cartPackStock;
						if ($incrementCartQuantity)
							$packIsInStock &= $stockAvailable >= 0;
						else
							$packIsInStock &= $stockAvailable >= ((int)$packProduct['quantity'] * $quantity);
					}
				}
			}
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, (int)$packIsInStock, true);
		return (int)$packIsInStock;
	}
	public static function getPackIdShop($idPack) {
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idPack, true);
		$idShop = false;
		if (!self::isInCache($cacheId, true)) {
			$sql = new DbQuery();
			$sql->select('ap.`id_shop`');
			$sql->from('pm_advancedpack', 'ap');
			$sql->where('ap.`id_pack`='.(int)$idPack);
			$idShop = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		} else {
			return self::getFromCache($cacheId, true);
		}
		self::storeInCache($cacheId, $idShop, true);
		return $idShop;
	}
	public static function isFromShop($idPack, $idShop) {
		return (self::getPackIdShop($idPack) == $idShop);
	}
	public static function combinationExists($idProductPack, $attributesList) {
		$attributesWhitelist = self::getProductAttributeWhiteList($idProductPack);
		foreach (self::getProductCombinationsByIdProductPack($idProductPack) as $idProductAttribute => $combinationAttributesList) {
			if (AdvancedPackCoreClass::_isFilledArray($attributesWhitelist) && !in_array($idProductAttribute, $attributesWhitelist))
				continue;
			if (!count(array_diff($combinationAttributesList, $attributesList)))
				return (int)$idProductAttribute;
		}
		return false;
	}
	public static function clonePackImages($idPack) {
		$packProducts = self::getPackContent($idPack);
		$res = true;
		$defaultPackImagePath = dirname(__FILE__) . '/img/default-pack-image.png';
		$coverImage = new Image();
		$coverImage->id_product = (int)$idPack;
		$coverImage->position = Image::getHighestPosition($idPack) + 1;
		if ($coverImage->add() && ($new_path = $coverImage->getPathForCreation()) && ImageManager::resize($defaultPackImagePath, $new_path.'.'.$coverImage->image_format)) {
			foreach (ImageType::getImagesTypes('products') as $imageType)
				$res &= ImageManager::resize($defaultPackImagePath, $new_path.'-'.Tools::stripslashes($imageType['name']).'.'.$coverImage->image_format, $imageType['width'], $imageType['height'], $coverImage->image_format);
		}
		if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
			foreach ($packProducts as $packProduct) {
				$res &= Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'image` i, `'._DB_PREFIX_.'image_shop` i_shop SET i.`cover` = NULL, i_shop.`cover` = NULL WHERE i.`id_image`=i_shop.`id_image` AND i.`id_product` = '.(int)$idPack);
				$res &= Image::duplicateProductImages($packProduct['id_product'], $idPack, array());
			}
		}
		if (Validate::isLoadedObject($coverImage)) {
			$res &= Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'image` i, `'._DB_PREFIX_.'image_shop` i_shop SET i.`cover` = NULL, i_shop.`cover` = NULL WHERE i.`id_image`=i_shop.`id_image` AND i.`id_product` = '.(int)$idPack);
			$i = 2;
			$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'image` WHERE `id_product` = '.(int)$idPack.' AND `id_image` != '.(int)$coverImage->id.' ORDER BY `position`');
			if ($result) {
				foreach ($result as $row) {
					$res &= Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'image` SET `position` = '.(int)$i.' WHERE `id_image` = '.(int)$row['id_image']);
					$i++;
				}
			}
			$coverImage->cover = 1;
			$coverImage->update();
		}
		return $res;
	}
	protected static function setDefaultPackAttribute($idPack, $idProductAttribute) {
		$result = Db::getInstance()->update('product_shop', array('cache_default_attribute' => $idProductAttribute), 'id_product = '.(int)$idPack . Shop::addSqlRestriction());
		$result &= Db::getInstance()->update('product', array('cache_default_attribute' => $idProductAttribute), 'id_product = ' . (int)$idPack);
		$result &= Db::getInstance()->update('product_attribute_shop', array('default_on' => 1), 'id_product_attribute = ' . (int)$idProductAttribute . Shop::addSqlRestriction());
		$result &= Db::getInstance()->update('product_attribute', array('default_on' => 1), 'id_product_attribute = ' . (int)$idProductAttribute);
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>=') && method_exists('Tools', 'clearColorListCache'))
			Tools::clearColorListCache($idPack);
		return $result;
	}
	public static function clonePackAttributes($idPack) {
		$packProducts = self::getPackContent($idPack);
		$finalAttributesList = array();
		$minStockAvailableByIdAttribute = array();
		$minStockAvailableForProductsWithoutAttributes = null;
		$attributeCombinations = array();
		$res = true;
		if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
			foreach ($packProducts as $packProduct) {
				$product = new Product((int)$packProduct['id_product']);
				$attributesWhitelist = self::getProductAttributeWhiteList($packProduct['id_product_pack']);
				$isAvailableWhenOutOfStock = Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$packProduct['id_product']));
				if (AdvancedPackCoreClass::_isFilledArray($attributesWhitelist)) {
					foreach ($attributesWhitelist as $idProductAttribute) {
						$combinationList = $product->getAttributeCombinationsById($idProductAttribute, Context::getContext()->language->id);
						if (AdvancedPackCoreClass::_isFilledArray($combinationList))
							foreach ($combinationList as $combinationRow) {
								$attributeCombinations[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
								$finalAttributesList[(int)$combinationRow['id_attribute_group']][] = (int)$combinationRow['id_attribute'];
								if ($isAvailableWhenOutOfStock) {
									$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = self::PACK_FAKE_STOCK;
								} else {
									$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = (int)$combinationRow['quantity'];
								}
							}
					}
				} else {
					$combinationList = $product->getAttributeCombinations(Context::getContext()->language->id);
					if (AdvancedPackCoreClass::_isFilledArray($combinationList)) {
						foreach ($combinationList as $combinationRow) {
							$attributeCombinations[(int)$combinationRow['id_product_attribute']][] = (int)$combinationRow['id_attribute'];
							$finalAttributesList[(int)$combinationRow['id_attribute_group']][] = (int)$combinationRow['id_attribute'];
							if ($isAvailableWhenOutOfStock) {
								$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = self::PACK_FAKE_STOCK;
							} else {
								$minStockAvailableByIdAttribute[(int)$combinationRow['id_product_attribute']] = (int)$combinationRow['quantity'];
							}
						}
					} else {
						if ($isAvailableWhenOutOfStock) {
							$stockAvailable = self::PACK_FAKE_STOCK;
						} else {
							$stockAvailable = StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product']);
						}
						if ($minStockAvailableForProductsWithoutAttributes == null || $stockAvailable < $minStockAvailableForProductsWithoutAttributes)
							$minStockAvailableForProductsWithoutAttributes = $stockAvailable;
					}
				}
			}
		}
		$productPack = new Product((int)$idPack);
		$combinationList = $productPack->getAttributeCombinations(Context::getContext()->language->id);
		if (AdvancedPackCoreClass::_isFilledArray($combinationList)) {
			$combinationToDelete = $attributesToDelete = array();
			foreach ($combinationList as $combinationRow)
				if ($combinationRow['attribute_name'] == $idPack.'-defaultCombination') {
					$attributesToDelete[] = (int)$combinationRow['id_attribute'];
				} else if ($combinationRow['id_attribute_group'] != self::getPackAttributeGroupId()) {
					$combinationToDelete[] = (int)$combinationRow['id_product_attribute'];
				}
			if (AdvancedPackCoreClass::_isFilledArray($finalAttributesList) && AdvancedPackCoreClass::_isFilledArray($attributesToDelete)) {
				foreach ($attributesToDelete as $idAttribute) {
					$objAttribute = new Attribute($idAttribute);
					$objAttribute->delete();
				}
			}
			if (AdvancedPackCoreClass::_isFilledArray($combinationToDelete)) {
				$res &= Db::getInstance()->delete('product_attribute', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
				$res &= Db::getInstance()->delete('product_attribute_shop', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
				$res &= Db::getInstance()->delete('product_attribute_combination', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
				$res &= Db::getInstance()->delete('cart_product', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
				$res &= Db::getInstance()->delete('product_attribute_image', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
				$res &= Db::getInstance()->delete('stock_available', '`id_product_attribute` IN ('. implode(',', $combinationToDelete) .')');
			}
		}
		if (AdvancedPackCoreClass::_isFilledArray($finalAttributesList)) {
			foreach ($finalAttributesList as $key => $attributeList)
				$finalAttributesList[$key] = array_unique($attributeList);
			$finalAttributesList = AdvancedPackCoreClass::array_cartesian($finalAttributesList);
			$defaultOn = 1;
			foreach ($finalAttributesList as $attributeList) {
				$availableQuantity = 1;
				foreach ($attributeCombinations as $idProductAttribute => $combinationAttributesList) {
					if (!count(array_diff($combinationAttributesList, $attributeList)) && isset($minStockAvailableByIdAttribute[$idProductAttribute])) {
						$availableQuantity = $minStockAvailableByIdAttribute[$idProductAttribute];
						break;
					}
				}
				if ($minStockAvailableForProductsWithoutAttributes !== null)
					$availableQuantity = min(array($minStockAvailableForProductsWithoutAttributes, $availableQuantity));
				$obj = new Combination(null, null, (int)AdvancedPack::getPackIdShop($idPack));
				$obj->id_product = (int)$idPack;
				$obj->price = 0;
				$obj->weight = 0;
				$obj->ecotax = 0;
				$obj->quantity = $availableQuantity;
				$obj->reference = '';
				if ($availableQuantity > 0 && $defaultOn) {
					$obj->default_on = $defaultOn;
					$defaultOn = 0;
				} else {
					$obj->default_on = 0;
				}
				$obj->minimal_quantity = 1;
				$defaultOn = 0;
				if ($obj->add()) {
					self::setStockAvailableQuantity((int)$idPack, (int)$obj->id, $availableQuantity, false);
					$attributeToAdd = array();
					foreach ($attributeList as $id_attribute)
						$attributeToAdd[] = array(
							'id_product_attribute' => (int)$obj->id,
							'id_attribute' => (int)$id_attribute
						);
					$res &= Db::getInstance()->insert('product_attribute_combination', $attributeToAdd);
					if ($obj->default_on) {
						$res &= self::setDefaultPackAttribute((int)$idPack, (int)$obj->id);
					}
				}
			}
			if ($defaultOn) {
				$obj->default_on = 1;
				$obj->save();
				$res &= self::setDefaultPackAttribute((int)$idPack, (int)$obj->id);
			}
		} else {
			if (isset($attributesToDelete) && AdvancedPackCoreClass::_isFilledArray($attributesToDelete) && count($attributesToDelete) == 1) {
				self::setStockAvailableQuantity((int)$idPack, (int)Product::getDefaultAttribute($idPack), self::getPackAvailableQuantity($idPack, array(), array(), false, false));
			} else {
				self::addCustomPackProductAttribute($idPack, array(), false, true);
			}
		}
		return $res;
	}
	public static function addPackToCart($idPack, $quantity = 1, $idProductAttributeList = array(), $fromCartController = true) {
		$errors = array();
		$moduleInstance = Module::getInstanceByName('pm_advancedpack');
		if (self::isValidPack($idPack, true)) {
			if (!count($idProductAttributeList))
				$idProductAttributeList = self::getIdProductAttributeListByIdPack($idPack);
			ksort($idProductAttributeList);
			$packUniqueHash = md5((int)Context::getContext()->cookie->id_cart . '-' . (int)$idPack . '-' . serialize($idProductAttributeList));
			if (self::isInStock($idPack, $quantity, $idProductAttributeList)) {
				$idProductAttribute = self::addCustomPackProductAttribute($idPack, $idProductAttributeList, $packUniqueHash);
				$idAddressDelivery = (int)Tools::getValue('id_address_delivery');
				if (is_numeric($idProductAttribute) && $idProductAttribute > 0 && $idProductAttribute !== false) {
					if (self::addPackSpecificPrice($idPack, $idProductAttribute, $idProductAttributeList)) {
						$updateQuantity = Context::getContext()->cart->updateQty($quantity, $idPack, $idProductAttribute, null, 'up', $idAddressDelivery);
						if (!$updateQuantity) {
							$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorMaximumQuantity'), false);
						} else {
							$resPackAdd = true;
							$packProducts = self::getPackContent($idPack);
							if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
								$values = array();
								foreach ($packProducts as $packProduct) {
									$productPackIdAttribute = (isset($idProductAttributeList[(int)$packProduct['id_product_pack']]) ? $idProductAttributeList[(int)$packProduct['id_product_pack']] : (int)$packProduct['default_id_product_attribute']);
									$values[] = '('.(int)Context::getContext()->cookie->id_cart.', '.(int)Context::getContext()->shop->id.', '.(int)$idPack.', '.(int)$packProduct['id_product_pack'].', '.(int)$idProductAttribute.', '.(int)$productPackIdAttribute.', "'.pSQL($packUniqueHash).'")';
								}
								if (AdvancedPackCoreClass::_isFilledArray($values))
									$resPackAdd &= Db::getInstance()->execute('INSERT IGNORE INTO `'._DB_PREFIX_.'pm_advancedpack_cart_products` (`id_cart`, `id_shop`, `id_pack`, `id_product_pack`, `id_product_attribute_pack`, `id_product_attribute`, `unique_hash`) VALUES '.implode($values, ','));
							}
							if ($resPackAdd) {
								if ($fromCartController) {
									ob_start();
									$cartController = new CartController();
									$cartController->displayAjax();
									$jsonCartContent = (array)Tools::jsonDecode(ob_get_contents(), true);
									ob_end_clean();
									if (is_array($jsonCartContent)) {
										foreach ($jsonCartContent['products'] as &$cartProduct) {
											if (AdvancedPack::isValidPack($cartProduct['id']) && $cartProduct['idCombination']) {
												$cartProduct['attributes'] = $moduleInstance->displayPackContent($cartProduct['id'], $cartProduct['idCombination'], pm_advancedpack::PACK_CONTENT_BLOCK_CART);
												if ((int)Group::getCurrent()->price_display_method) {
													$cartProduct['price_float'] = $cartProduct['quantity'] * AdvancedPack::getPackPrice((int)$cartProduct['id'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$cartProduct['id'], (int)$cartProduct['idCombination']), array(), true);
													$cartProduct['price'] = Tools::displayPrice($cartProduct['price_float'], Context::getContext()->currency);
													$cartProduct['priceByLine'] = Tools::displayPrice($cartProduct['price_float'], Context::getContext()->currency);
												}
											}
										}
										if ((int)Group::getCurrent()->price_display_method) {
											$newCartSummary = Context::getContext()->cart->getSummaryDetails(null, true);
											if (is_array($newCartSummary)) {
												$summaryTotal = 0;
												foreach ($newCartSummary['products'] as &$cartProduct) {
													if (AdvancedPack::isValidPack($cartProduct['id_product']) && $cartProduct['id_product_attribute']) {
														$newProductSummaryTotal = (int)$cartProduct['cart_quantity'] * AdvancedPack::getPackPrice((int)$cartProduct['id_product'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$cartProduct['id_product'], (int)$cartProduct['id_product_attribute']), array(), true);
														$summaryTotal += ($cartProduct['total'] - $newProductSummaryTotal);
														$cartProduct['price_without_quantity_discount'] = AdvancedPack::getPackPrice((int)$cartProduct['id_product'], false, false, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$cartProduct['id_product'], (int)$cartProduct['id_product_attribute']), array(), true);
														$cartProduct['price_wt'] = AdvancedPack::getPackPrice((int)$cartProduct['id_product'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$cartProduct['id_product'], (int)$cartProduct['id_product_attribute']), array(), true);
													}
												}
												$jsonCartContent['productTotal'] = Tools::displayPrice($newCartSummary['total_products'] - $summaryTotal, Context::getContext()->currency);
												$jsonCartContent['total'] = Tools::displayPrice(Context::getContext()->cart->getOrderTotal(false) - $summaryTotal, Context::getContext()->currency);
											}
										}
										$jsonCartContent['ap5Data'] = array('idProductAttribute' => $idProductAttribute);
										die(Tools::jsonEncode($jsonCartContent));
									} else {
										$cartController->displayAjax();
									}
								}
							} else {
								$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorSavePackContent'), false);
							}
						}
					} else {
						$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorGeneratingPrice'), false);
					}
				}
			} else {
				$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorOutOfStock'), false);
			}
		} else {
			$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorInvalidPack'), false);
		}
		if (count($errors))
			if ($fromCartController)
				die(Tools::jsonEncode(array('hasError' => true, 'errors' => $errors)));
			else
				Context::getContext()->controller->errors = $errors;
	}
	public static function addExplodedPackToCart($idPack, $quantity = 1, $idProductAttributeList = array(), $packExcludeList = array()) {
		$errors = array();
		$moduleInstance = Module::getInstanceByName('pm_advancedpack');
		if (sizeof($packExcludeList) && self::isValidPack($idPack, true, $packExcludeList)) {
			$resPackAdd = true;
			$packProducts = self::getPackContent($idPack);
			if (AdvancedPackCoreClass::_isFilledArray($packProducts)) {
				$idAddressDelivery = (int)Tools::getValue('id_address_delivery');
				pm_advancedpack::$_preventInfiniteLoop = true;
				foreach ($packProducts as $packProduct) {
					if (in_array((int)$packProduct['id_product_pack'], $packExcludeList))
						continue;
					$productPackIdAttribute = (isset($idProductAttributeList[(int)$packProduct['id_product_pack']]) ? $idProductAttributeList[(int)$packProduct['id_product_pack']] : (int)$packProduct['default_id_product_attribute']);
					$resPackAdd &= Context::getContext()->cart->updateQty((int)$packProduct['quantity'] * $quantity, (int)$packProduct['id_product'], $productPackIdAttribute, null, 'up', $idAddressDelivery);
				}
				pm_advancedpack::$_preventInfiniteLoop = false;
			}
			$cartController = new CartController();
			$cartController->displayAjax();
		} else {
			$errors[] = Tools::displayError($moduleInstance->getFrontTranslation('errorInvalidPack'), false);
		}
		if (count($errors))
			die(Tools::jsonEncode(array('hasError' => true, 'errors' => $errors)));
	}
	public static function getAddressInstance() {
		$address_infos = array();
		$id_country = (int)Context::getContext()->country->id;
		$id_state = 0;
		$zipcode = 0;
		$id_address = 0;
		if (Validate::isLoadedObject(Context::getContext()->cart))
			$id_address = Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
		if ($id_address) {
			$address_infos = Address::getCountryAndState($id_address);
			if ($address_infos['id_country']) {
				$id_country = (int)$address_infos['id_country'];
				$id_state = (int)$address_infos['id_state'];
				$zipcode = $address_infos['postcode'];
			}
		} else if (isset(Context::getContext()->customer->geoloc_id_country)) {
			$id_country = (int)Context::getContext()->customer->geoloc_id_country;
			$id_state = (int)Context::getContext()->customer->id_state;
			$zipcode = (int)Context::getContext()->customer->postcode;
		}
		$useTax = true;
		if (Tax::excludeTaxeOption())
			$useTax = false;
		if ($useTax != false
			&& !empty($address_infos['vat_number'])
			&& $address_infos['id_country'] != Configuration::get('VATNUMBER_COUNTRY')
			&& Configuration::get('VATNUMBER_MANAGEMENT'))
			$useTax = false;
		$address = new Address();
		$address->id_country = $id_country;
		$address->id_state = $id_state;
		$address->postcode = $zipcode;
		$useTax = true;
		if (Tax::excludeTaxeOption())
			$useTax = false;
		if ($useTax != false
			&& !empty($address_infos['vat_number'])
			&& $address_infos['id_country'] != Configuration::get('VATNUMBER_COUNTRY')
			&& Configuration::get('VATNUMBER_MANAGEMENT'))
			$useTax = false;
		return array($address, $useTax);
	}
	public static function addPackSpecificPrice($idPack, $idProductAttribute, &$idProductAttributeList = array()) {
		$packIdTaxRulesGroup = AdvancedPack::getPackIdTaxRulesGroup((int)$idPack);
		$packProducts = self::getPackContent($idPack);
		$packFixedPrice = self::getPackFixedPrice($idPack);
		$reductionAmountTable = $reductionPercentageTable = array();
		if ($packFixedPrice == 0 && AdvancedPackCoreClass::_isFilledArray($packProducts)) {
			foreach ($packProducts as $packProduct)
				if ($packProduct['reduction_type'] == 'amount')
					$reductionAmountTable[] = $packProduct['reduction_amount'];
				else if ($packProduct['reduction_type'] == 'percentage')
					$reductionPercentageTable[] = $packProduct['reduction_amount'];
			$reductionPercentageTable = array_unique($reductionPercentageTable);
		}
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product`='.(int)$idPack.' AND `id_product_attribute`='.(int)$idProductAttribute);
		$sp = new SpecificPrice();
		$sp->id_product = $idPack;
		$sp->id_cart = 0;
		$sp->id_product_attribute = $idProductAttribute;
		$sp->id_shop = AdvancedPack::getPackIdShop($idPack);
		$sp->id_shop_group = 0;
		$sp->id_currency = (Validate::isLoadedObject(Context::getContext()->currency) ? Context::getContext()->currency->id : 0);
		$sp->id_country = 0;
		$sp->id_group = 0;
		$sp->id_customer = 0;
		$sp->from = '0000-00-00 00:00:00';
		$sp->to = '0000-00-00 00:00:00';
		$sp->from_quantity = 1;
		if ($packFixedPrice > 0)
			if ($packIdTaxRulesGroup)
				$sp->price = self::getPackPrice($idPack, false, false, false, 6, $idProductAttributeList);
			else
				$sp->price = self::getPackPrice($idPack, true, false, false, 6, $idProductAttributeList);
		else if (AdvancedPackCoreClass::_isFilledArray($idProductAttributeList))
			if ($packIdTaxRulesGroup)
				$sp->price = self::getPackPrice($idPack, false, false, false, 6, $idProductAttributeList);
			else
				$sp->price = self::getPackPrice($idPack, true, false, false, 6, $idProductAttributeList);
		else
			$sp->price = -1;
		if ($packFixedPrice > 0) {
			$sp->reduction = self::getPackPrice($idPack, true, false, true, 6, $idProductAttributeList) - self::getPackPrice($idPack, true, true, true, 6, $idProductAttributeList);
			$sp->reduction_type = 'amount';
		} else if (count($reductionPercentageTable) == 1 && !count($reductionAmountTable)) {
			$sp->reduction = current($reductionPercentageTable);
			$sp->reduction_type = 'percentage';
		} else {
			$sp->reduction_type = 'amount';
			$sp->reduction = self::getPackPrice($idPack, true, false, true, 6, $idProductAttributeList) - self::getPackPrice($idPack, true, true, true, 6, $idProductAttributeList);
		}
		$sp->reduction = Tools::ps_round($sp->reduction, 6);
		if ($sp->reduction < 0) {
			$sp->reduction = 0;
			if ($packIdTaxRulesGroup)
				$sp->price = self::getPackPrice($idPack, false, true, false, 6, $idProductAttributeList);
			else
				$sp->price = self::getPackPrice($idPack, true, true, false, 6, $idProductAttributeList);
		}
		if ($idProductAttribute)
			return $sp->save();
		if ($sp->save() && !$packIdTaxRulesGroup) {
			foreach (Group::getGroups(Context::getContext()->language->id, Context::getContext()->shop->id) as $group) {
				if ((int)Group::getPriceDisplayMethod((int)$group['id_group']) == 1) {
					$sp->id = $sp->id_specific_price = null;
					$sp->id_group = (int)$group['id_group'];
					if ($packFixedPrice > 0)
						$sp->price = self::getPackPrice($idPack, false, false, false, 6, $idProductAttributeList);
					else if (AdvancedPackCoreClass::_isFilledArray($idProductAttributeList))
						$sp->price = self::getPackPrice($idPack, false, false, false, 6, $idProductAttributeList);
					else
						$sp->price = self::getPackPrice($idPack, false, false, false, 6, $idProductAttributeList);
					if ($packFixedPrice > 0) {
						if ($packIdTaxRulesGroup)
							$sp->reduction = self::getPackPrice($idPack, true, false, true, 6, $idProductAttributeList) - self::getPackPrice($idPack, true, true, true, 6, $idProductAttributeList);
						else
							$sp->reduction = self::getPackPrice($idPack, false, false, true, 6, $idProductAttributeList) - self::getPackPrice($idPack, false, true, true, 6, $idProductAttributeList);
						$sp->reduction_type = 'amount';
					} else if (count($reductionPercentageTable) == 1 && !count($reductionAmountTable)) {
						$sp->reduction = current($reductionPercentageTable);
						$sp->reduction_type = 'percentage';
					} else {
						$sp->reduction_type = 'amount';
						$sp->reduction = self::getPackPrice($idPack, false, false, true, 6, $idProductAttributeList) - self::getPackPrice($idPack, false, true, true, 6, $idProductAttributeList);
					}
					$sp->reduction = Tools::ps_round($sp->reduction, 6);
					if ($sp->reduction < 0) {
						$sp->reduction = 0;
						$sp->price = self::getPackPrice($idPack, false, true, false, 6, $idProductAttributeList);
					}
					if (!$sp->save())
						return false;
				}
			}
			return true;
		}
		return false;
	}
	public static function transformProductDescriptionWithImg($product) {
		$reg = '/\[img\-([0-9]+)\-(left|right)\-([a-zA-Z0-9-_]+)\]/';
		while (preg_match($reg, $product->description, $matches))
		{
			$link_lmg = Context::getContext()->link->getImageLink($product->link_rewrite, $product->id.'-'.$matches[1], $matches[3]);
			$class = $matches[2] == 'left' ? 'class="imageFloatLeft"' : 'class="imageFloatRight"';
			$html_img = '<img src="'.$link_lmg.'" alt="" '.$class.'/>';
			$product->description = str_replace($matches[0], $html_img, $product->description);
		}
		return $product->description;
	}
	private static function _getProductImages($packProduct, $idLang = null) {
		if ($idLang == null)
			$idLang = Context::getContext()->language->id;
		$cacheId = self::getPMCacheId(__METHOD__.(int)$packProduct['id_product_pack'].(int)$idLang.Context::getContext()->shop->id);
		if (!self::isInCache($cacheId)) {
			$productAttributesList = self::getProductAttributeWhiteList($packProduct['id_product_pack']);
			if (!pm_advancedpack::_isFilledArray($productAttributesList)) {
				$productObj = new Product((int)$packProduct['id_product'], false, (int)$idLang);
				$images = $productObj->getImages($idLang);
			} else {
				$sql = 'SELECT i.`id_image`, il.`legend`, ai.`id_product_attribute`
						FROM `'._DB_PREFIX_.'image` i
						'.Shop::addSqlAssociation('image', 'i').'
						LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` ai ON (i.`id_image` = ai.`id_image`)
						LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$idLang.')
						WHERE i.`id_product` = '.(int)$packProduct['id_product'].'
						AND (ai.`id_product_attribute` IN ('. implode(',', $productAttributesList) .') OR ai.`id_product_attribute` IS NULL)
						GROUP BY i.`id_image`
						ORDER BY `position`';
				$images = Db::getInstance()->executeS($sql);
				if (pm_advancedpack::_isFilledArray($images)) {
					foreach ($images as $k => $image)
						if ((int)$image['id_product_attribute'] && !in_array((int)$image['id_product_attribute'], $productAttributesList))
							unset($images[$k]);
				} else {
					$images = array();
				}
			}
			self::storeInCache($cacheId, $images);
		} else {
			return self::getFromCache($cacheId);
		}
		return $images;
	}
	private static function _getProductCoverImage($idProduct, $idProductAttribute = null, $idLang = null) {
		if ($idLang == null)
			$idLang = Context::getContext()->language->id;
		$cacheId = self::getPMCacheId(__METHOD__.(int)$idProduct.(int)$idProductAttribute.(int)$idLang.Context::getContext()->shop->id);
		if (!self::isInCache($cacheId)) {
			$sql = new DbQuery();
			$sql->select('i.`id_image`, il.`legend`');
			$sql->from('image', 'i');
			$sql->join(Shop::addSqlAssociation('image', 'i'));
			$sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image`');
			if ($idProductAttribute != null && $idProductAttribute) {
				$sql->leftJoin('product_attribute_image', 'pai', 'i.`id_image` = pai.`id_image`');
				$sql->where('i.`id_product`='.(int)$idProduct);
				$sql->where('il.`id_lang`='.(int)$idLang);
				$sql->where('pai.`id_product_attribute`='.(int)$idProductAttribute);
			} else {
				$sql->where('i.`id_product`='.(int)$idProduct);
				$sql->where('il.`id_lang`='.(int)$idLang);
			}
			$sql->orderBy('i.`position` ASC');
			$productImage = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
			if (AdvancedPackCoreClass::_isFilledArray($productImage)) {
				self::storeInCache($cacheId, $productImage);
				return $productImage;
			} else {
				$sql = new DbQuery();
				$sql->select('i.`id_image`, il.`legend`');
				$sql->from('image', 'i');
				$sql->join(Shop::addSqlAssociation('image', 'i'));
				$sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image`');
				$sql->where('i.`id_product`='.(int)$idProduct);
				$sql->where('il.`id_lang`='.(int)$idLang);
				$sql->where('image_shop.`cover`=1');
				$productImage = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
				if (AdvancedPackCoreClass::_isFilledArray($productImage)) {
					self::storeInCache($cacheId, $productImage);
					return $productImage;
				} else {
					return false;
				}
			}
		} else {
			return self::getFromCache($cacheId);
		}
		self::storeInCache($cacheId, false);
		return false;
	}
	private static function _getProductAttributesGroups($productObj, $idProductAttributeDefault = null, $idProductAttributeWhiteList = array(), $idLang = null) {
		if ($idLang == null)
			$idLang = Context::getContext()->language->id;
		$colors = $groups = $combinations = $combination_prices_set = array();
		$attributes_groups = $productObj->getAttributesGroups($idLang);
		if (is_array($attributes_groups) && $attributes_groups) {
			$combinationImages = $productObj->getCombinationImages($idLang);
			$combination_specific_price = null;
			$atLeastOneDefaultAttribute = array();
			$alternativeDefaultIdAttributeGroup = array();
			foreach ($attributes_groups as $k => $row) {
				if (count($idProductAttributeWhiteList) && !in_array((int)$row['id_product_attribute'], $idProductAttributeWhiteList)) {
					unset($attributes_groups[$k]);
					continue;
				}
				if ($idProductAttributeDefault != null && (int)$idProductAttributeDefault == (int)$row['id_product_attribute'])
					$attributes_groups[$k]['default_on'] = 1;
				else
					$attributes_groups[$k]['default_on'] = 0;
				if (!isset($alternativeDefaultIdAttributeGroup[$row['id_attribute_group']]))
					$alternativeDefaultIdAttributeGroup[$row['id_attribute_group']] = array('id_attribute_group' => $row['id_attribute_group'], 'id_attribute' => $row['id_attribute']);
			}
			foreach ($attributes_groups as $k => $row) {
				if (isset($row['is_color_group']) && $row['is_color_group'] && (isset($row['attribute_color']) && $row['attribute_color']) || (version_compare(_PS_VERSION_, '1.6.0.0', '>=') && Tools::file_exists_cache(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg'))) {
					$colors[$row['id_attribute']]['value'] = $row['attribute_color'];
					$colors[$row['id_attribute']]['name'] = $row['attribute_name'];
					if (!isset($colors[$row['id_attribute']]['attributes_quantity']))
						$colors[$row['id_attribute']]['attributes_quantity'] = 0;
					$colors[$row['id_attribute']]['attributes_quantity'] += (int)$row['quantity'];
				}
				if (!isset($groups[$row['id_attribute_group']]))
					$groups[$row['id_attribute_group']] = array(
						'group_name' => $row['group_name'],
						'name' => $row['public_group_name'],
						'group_type' => $row['group_type'],
						'default' => -1,
					);
				$groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = $row['attribute_name'];
				if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
					$groups[$row['id_attribute_group']]['default'] = (int)$row['id_attribute'];
					$atLeastOneDefaultAttribute[$row['id_attribute_group']] = true;
				}
				if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']]))
					$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
				$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int)$row['quantity'];
				$combinations[$row['id_product_attribute']]['attributes_values'][$row['id_attribute_group']] = $row['attribute_name'];
				$combinations[$row['id_product_attribute']]['attributes'][] = (int)$row['id_attribute'];
				$combinations[$row['id_product_attribute']]['price'] = (float)$row['price'];
				if (!isset($combination_prices_set[(int)$row['id_product_attribute']])) {
					Product::getPriceStatic((int)$productObj->id, false, $row['id_product_attribute'], 6, null, false, true, 1, false, null, null, null, $combination_specific_price);
					$combination_prices_set[(int)$row['id_product_attribute']] = true;
					$combinations[$row['id_product_attribute']]['specific_price'] = $combination_specific_price;
				}
				$combinations[$row['id_product_attribute']]['ecotax'] = (float)$row['ecotax'];
				$combinations[$row['id_product_attribute']]['weight'] = (float)$row['weight'];
				$combinations[$row['id_product_attribute']]['quantity'] = (int)$row['quantity'];
				$combinations[$row['id_product_attribute']]['reference'] = $row['reference'];
				$combinations[$row['id_product_attribute']]['unit_impact'] = $row['unit_price_impact'];
				$combinations[$row['id_product_attribute']]['minimal_quantity'] = $row['minimal_quantity'];
				if ($row['available_date'] != '0000-00-00') {
					$combinations[$row['id_product_attribute']]['available_date'] = $row['available_date'];
					if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
						$combinations[$row['id_product_attribute']]['date_formatted'] = Tools::displayDate($row['available_date']);
				} else {
					$combinations[$row['id_product_attribute']]['available_date'] = '';
				}
				$combinations[$row['id_product_attribute']]['id_image'] = (isset($combinationImages[$row['id_product_attribute']][0]['id_image']) ? (int)$combinationImages[$row['id_product_attribute']][0]['id_image'] : 1);
			}
			if (!Product::isAvailableWhenOutOfStock($productObj->out_of_stock) && Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0) {
				foreach ($groups as &$group)
					foreach ($group['attributes_quantity'] as $key => &$quantity)
						if ($quantity <= 0)
							unset($group['attributes'][$key]);
				foreach ($colors as $key => $color)
					if ($color['attributes_quantity'] <= 0)
						unset($colors[$key]);
			}
			foreach ($combinations as $id_product_attribute => $comb) {
				$attribute_list = '';
				foreach ($comb['attributes'] as $id_attribute)
					$attribute_list .= '\''.(int)$id_attribute.'\',';
				$attribute_list = rtrim($attribute_list, ',');
				$combinations[$id_product_attribute]['list'] = $attribute_list;
			}
			foreach ($groups as $id_attribute_group => &$group)
				if (!isset($atLeastOneDefaultAttribute[$id_attribute_group]))
					$groups[$id_attribute_group]['default'] = (int)$alternativeDefaultIdAttributeGroup[$id_attribute_group]['id_attribute'];
			return array(
				'groups' => $groups,
				'colors' => (count($colors)) ? $colors : false,
				'combinations' => $combinations,
				'combinationImages' => $combinationImages
			);
		}
		return false;
	}
	public function updatePackContent($packContent, $packSettings, $isNewPack = false, $isMajorUpdate = false) {
		$res = true;
		if ($isNewPack)
			$res &= Db::getInstance()->insert('pm_advancedpack', array('id_pack' => $this->id, 'id_shop' => (int)Context::getContext()->shop->id, 'fixed_price' => $packSettings['fixedPrice'], 'allow_remove_product' => (int)$packSettings['allowRemoveProduct']), true);
		if (!$isNewPack) {
			$sql = new DbQuery();
			$sql->select('`id_cart`, `id_pack`, `id_product_attribute_pack`');
			$sql->from('pm_advancedpack_cart_products', 'acp');
			$sql->leftJoin('product_attribute', 'ipa', 'acp.`id_product_attribute` = ipa.`id_product_attribute`');
			$sql->where('acp.`id_order` IS NULL');
			$sql->where('acp.`id_product_attribute` != 0');
			$sql->where('ipa.`id_product_attribute` IS NULL');
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
			if (AdvancedPackCoreClass::_isFilledArray($result))
				foreach ($result as $packToRemoveFromCart) {
					$res &= Db::getInstance()->delete('cart_product', '`id_cart`='. (int)$packToRemoveFromCart['id_cart'] . ' AND `id_product`='. (int)$packToRemoveFromCart['id_pack'] . ' AND `id_product_attribute`='. (int)$packToRemoveFromCart['id_product_attribute_pack']);
					$res &= Db::getInstance()->delete('pm_advancedpack_cart_products', '`id_pack`='. (int)$packToRemoveFromCart['id_pack'] . ' AND `id_product_attribute_pack`='. (int)$packToRemoveFromCart['id_product_attribute_pack']);
				}
		}
		if ($isMajorUpdate) {
			$sql = new DbQuery();
			$sql->select('GROUP_CONCAT(DISTINCT `id_product_attribute_pack`)');
			$sql->from('pm_advancedpack_cart_products', 'acp');
			$sql->where('acp.`id_pack`='.(int)$this->id);
			$sql->where('acp.`id_order` IS NULL');
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
			if ($result !== false && !empty($result)) {
				$result = array_map('intval', explode(',', $result));
				if (AdvancedPackCoreClass::_isFilledArray($result))
					foreach ($result as $idProductAttribute)
						if ((int)$idProductAttribute > 0)
							self::setStockAvailableQuantity((int)$this->id, (int)$idProductAttribute, 0, false);
			}
		} else if (!$isMajorUpdate && !$isNewPack) {
			$sql = new DbQuery();
			$sql->select('GROUP_CONCAT(DISTINCT `id_product_attribute_pack`)');
			$sql->from('pm_advancedpack_cart_products', 'acp');
			$sql->where('acp.`id_pack`='.(int)$this->id);
			$sql->where('acp.`id_order` IS NULL');
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
			if ($result !== false && !empty($result)) {
				$result = array_map('intval', explode(',', $result));
				if (AdvancedPackCoreClass::_isFilledArray($result))
					foreach ($result as $idProductAttribute)
						if ((int)$idProductAttribute > 0) {
							$idProductAttributeList = self::getIdProductAttributeListByIdPack((int)$this->id, $idProductAttribute);
							self::addPackSpecificPrice((int)$this->id, $idProductAttribute, $idProductAttributeList);
						}
			}
		}
		$res &= Db::getInstance()->delete('pm_advancedpack_products', '`id_pack`='. (int)$this->id);
		$res &= Db::getInstance()->delete('pm_advancedpack_products_attributes', '`id_product_pack` NOT IN (SELECT `id_product_pack` FROM `'._DB_PREFIX_.'pm_advancedpack_products`)');
		self::clearAP5Cache();
		foreach ($packContent as $k => $packContentRow) {
			unset($packContentRow['customCombinations']);
			$res &= Db::getInstance()->insert('pm_advancedpack_products', $packContentRow, true);
			if (is_null($packContentRow['id_product_pack']))
				$packContent[$k]['id_product_pack'] = (int)Db::getInstance()->Insert_ID();
		}
		foreach ($packContent as $k => $packContentRow) {
			if (AdvancedPackCoreClass::_isFilledArray($packContentRow['customCombinations']))
				foreach ($packContentRow['customCombinations'] as $idProductAttribute)
					$res &= Db::getInstance()->insert('pm_advancedpack_products_attributes', array('id_product_pack' => $packContentRow['id_product_pack'], 'id_product_attribute' => $idProductAttribute));
		}
		$res &= Db::getInstance()->update('pm_advancedpack', array('fixed_price' => $packSettings['fixedPrice'], 'allow_remove_product' => (int)$packSettings['allowRemoveProduct']), '`id_pack`=' . (int)$this->id . ' AND `id_shop`=' . (int)Context::getContext()->shop->id, 0, true);
		return $res;
	}
	private static function getPMCacheId($key, $withNativeCacheId = false) {
		return self::MODULE_ID . sha1($key.($withNativeCacheId ? Module::getInstanceByName('pm_advancedpack')->getPMNativeCacheId() : ''));
	}
	private static function isInCache($key, $static = false) {
		if (!_PS_CACHE_ENABLED_ || $static)
			return Cache::isStored($key);
		else
			return Cache::getInstance()->exists($key);
	}
	private static function getFromCache($key, $static = false) {
		if (!_PS_CACHE_ENABLED_ || $static)
			return Cache::retrieve($key);
		else
			return Cache::getInstance()->get($key);
	}
	private static function storeInCache($key, $value, $static = false, $ttl = 0) {
		if (!_PS_CACHE_ENABLED_ || $static)
			return Cache::store($key, $value);
		else
			return Cache::getInstance()->set($key, $value, $ttl);
	}
	public static function clearAP5Cache() {
		if (!_PS_CACHE_ENABLED_) {
			Cache::clean('AP5*');
		} else {
			Cache::clean('AP5*');
			Cache::getInstance()->delete('AP5*');
		}
	}
}
