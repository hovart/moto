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
class pm_advancedpackupdate_cartModuleFrontController extends ModuleFrontController {
	protected $jsonOutput = array();
	protected $hasPackInCart = false;
	protected $moduleInstance;
	public $ajax = true;
	public $display_header = false;
	public $display_footer = false;
	public $display_column_left = false;
	public $display_column_right = false;
	public function init() {
		parent::init();
		$this->moduleInstance = Module::getInstanceByName('pm_advancedpack');
		header('X-Robots-Tag: noindex, nofollow', true);
		$this->ajax = true;
	}
	public function postProcess() {
		if (!$this->isTokenValid())
			Tools::redirect('index.php');
	}
	public function displayAjax() {
		ob_start();
		$cartController = new CartController();
		$cartController->displayAjax();
		$this->jsonOutput = (array)Tools::jsonDecode(ob_get_contents(), true);
		ob_end_clean();
		if (is_array($this->jsonOutput)) {
			$newCartSummary = $this->context->cart->getSummaryDetails(null, true);
			$summaryTotal = 0;
			$summaryTotalVAT = 0;
			if (is_array($newCartSummary)) {
				foreach ($newCartSummary['products'] as &$product) {
					if (AdvancedPack::isValidPack($product['id_product']) && $product['id_product_attribute'] && !AdvancedPack::getPackIdTaxRulesGroup($product['id_product'])) {
						if (!$this->hasPackInCart)
							$this->hasPackInCart = true;
						$product['price'] = AdvancedPack::getPackPrice((int)$product['id_product'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$product['id_product'], (int)$product['id_product_attribute']), array(), true);
						if (isset($product['price_without_quantity_discount'])) {
							$product['price_without_quantity_discount'] = AdvancedPack::getPackPrice((int)$product['id_product'], false, false, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$product['id_product'], (int)$product['id_product_attribute']), array(), true);
						}
						$product['price_wt'] = AdvancedPack::getPackPrice((int)$product['id_product'], true, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$product['id_product'], (int)$product['id_product_attribute']), array(), true);
						$summaryTotalVAT += (int)$product['cart_quantity'] * ($product['price_wt'] - $product['price']);
						$newProductSummaryTotal = (int)$product['cart_quantity'] * AdvancedPack::getPackPrice((int)$product['id_product'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$product['id_product'], (int)$product['id_product_attribute']), array(), true);
						$summaryTotal += ($product['total'] - $newProductSummaryTotal);
						$product['total'] = $newProductSummaryTotal;
						$product['total_wt'] = $product['price_wt'] * (int)$product['cart_quantity'];
						$product['attributes'] = $this->moduleInstance->displayPackContent((int)$product['id_product'], (int)$product['id_product_attribute'], pm_advancedpack::PACK_CONTENT_BLOCK_CART);
					}
				}
			}
			if ($this->hasPackInCart) {
				$newCartSummary['total_products'] -= $summaryTotal;
				$newCartSummary['total_price_without_tax'] -= $summaryTotal;
				$newCartSummary['total_tax'] += $summaryTotalVAT;
				$this->jsonOutput['summary'] = $newCartSummary;
				foreach ($this->jsonOutput['products'] as &$product) {
					if (AdvancedPack::isValidPack($product['id']) && $product['idCombination']) {
						$product['price_float'] = $product['quantity'] * AdvancedPack::getPackPrice((int)$product['id'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$product['id'], (int)$product['idCombination']), array(), true);
						$product['price'] = Tools::displayPrice($product['price_float'], $this->context->currency);
						$product['priceByLine'] = Tools::displayPrice($product['price_float'], $this->context->currency);
					}
				}
				$this->jsonOutput['productTotal'] = Tools::displayPrice($newCartSummary['total_products'], $this->context->currency);
				$this->jsonOutput['total'] = Tools::displayPrice($this->context->cart->getOrderTotal(false) - $summaryTotal, $this->context->currency);
			}
			die(Tools::jsonEncode(array('hasError' => false, 'cartData' => $this->jsonOutput)));
		}
		die(Tools::jsonEncode(array('hasError' => true)));
	}
	public function initContent() {}
}
