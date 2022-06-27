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
class pm_advancedpackadd_packModuleFrontController extends ModuleFrontController {
	protected $idPack;
	protected $quantity;
	protected $idProductAttributeList = array();
	protected $productPackExclude = array();
	public $ajax = true;
	public $display_header = false;
	public $display_footer = false;
	public $display_column_left = false;
	public $display_column_right = false;
	public function init() {
		parent::init();
		header('X-Robots-Tag: noindex, nofollow', true);
		$this->ajax = true;
		$this->idPack = (int)Tools::getValue('id_pack');
		$this->quantity = (int)abs(Tools::getValue('qty', 1));
		if ($this->quantity <= 0)
			$this->quantity = 1;
		$idProductAttributeList = Tools::getValue('id_product_attribute_list');
		$idProductAttributeList = (array)Tools::jsonDecode($idProductAttributeList);
		if (!is_array($idProductAttributeList))
			$this->idProductAttributeList = array();
		else
			foreach ($idProductAttributeList as $idProductPack => $idProductAttribute)
				if (empty($idProductPack) || empty($idProductAttribute) || !is_numeric($idProductPack) || !is_numeric($idProductAttribute))
					continue;
				else
					$this->idProductAttributeList[(int)$idProductPack] = (int)$idProductAttribute;
		unset($idProductAttributeList);
		if (Tools::getIsset('productPackExclude'))
			$this->productPackExclude = array_unique(array_map('intval', (array)Tools::getValue('productPackExclude')));
		if (!$this->context->cart->id) {
			if (Context::getContext()->cookie->id_guest) {
				$guest = new Guest(Context::getContext()->cookie->id_guest);
				$this->context->cart->mobile_theme = $guest->mobile_theme;
			}
			$this->context->cart->add();
			if ($this->context->cart->id)
				$this->context->cookie->id_cart = (int)$this->context->cart->id;
		}
	}
	public function postProcess() {
		if (!$this->isTokenValid())
			Tools::redirect('index.php');
	}
	public function displayAjax() {
		if (!sizeof($this->productPackExclude)) {
			AdvancedPack::addPackToCart($this->idPack, $this->quantity, $this->idProductAttributeList);
		} else {
			AdvancedPack::addExplodedPackToCart($this->idPack, $this->quantity, $this->idProductAttributeList, $this->productPackExclude);
		}
	}
	public function initContent() {}
}
