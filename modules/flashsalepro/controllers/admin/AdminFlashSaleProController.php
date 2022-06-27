<?php
/**
* 2007-2015 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/
require_once dirname(__FILE__).'/../../classes/FlashSale.php';

class AdminFlashSaleProController extends ModuleAdminController
{
	public function ajaxProcessEditSaleProductSearch()
	{
		$context = Context::getContext();
		$value = pSQL(trim(Tools::getValue('value')));
		$lang = (int)Tools::getValue('lang');
		$flash_sale = new FlashSalePro();
		$product_info = $flash_sale->getProductInfoEdit($lang, $value);

		$json = array();
		if (empty($product_info))
			$json['empty'] = 1;
			
		$html = '';
		$context->smarty->assign(array(
				'currency_symbol' => $context->currency->getSign(),
				));
		foreach ($product_info as $product)
		{
			$context->smarty->assign(array(
				'product' => $product,
				));
			$html .= $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/forms/editAddProductBlock.tpl');
		}
		$json['content'] = $html;
		exit(Tools::jsonEncode($json));
	}

	public function ajaxProcessViewSaleProductSearch()
	{
		$value = pSQL(trim(Tools::getValue('value')));
		$lang = (int)Tools::getValue('lang');
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		
		$sale = new FlashSale($id_flash_sale);
		$items = $sale->getItemsNameLike($lang, $value);
		
		$flash_sale = new FlashSalePro();
		$search_html = $flash_sale->getEditProductsBlock($items);
		
		$json = array();
		$json['search_html'] = $search_html;
		exit(Tools::jsonEncode($json));
	}

	public function ajaxProcessSearch()
	{
		$value = pSQL(trim(Tools::getValue('value')));
		$lang = (int)Tools::getValue('lang');
		$flash_sale = new FlashSalePro();

		$result = $flash_sale->getProductInfo($lang, $value);

		exit(Tools::jsonEncode($result));
	}

	public function ajaxProcessSearchCategory()
	{
		$value = pSQL(trim(Tools::getValue('value')));
		$lang = (int)Tools::getValue('lang');
		$flash_sale = new FlashSalePro();

		$result = $flash_sale->getCategoryInfo($lang, $value);

		exit(Tools::jsonEncode($result));
	}

	public function ajaxProcessShowFlashSaleItemInfo()
	{
		$context = Context::getContext();
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$flash_sale = new FlashSalePro();

		$flash_sale_item = $flash_sale->getAllFlashSaleItems($id_flash_sale, 0);

		$html = '';
		$count_flash_sale_item = count($flash_sale_item);
		$stock_flag = 0;
		if ($count_flash_sale_item > 0 && $flash_sale_item[0]['sale_type'] === 'stock')
			$stock_flag = 1;
		$context->smarty->assign(array(
			'stock_flag' => $stock_flag,
			));
		$headers = $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/flashsaleItemTableHeaders.tpl');

		$html = '';
		foreach ($flash_sale_item as $item)
		{
			if (isset($item['id_product']) && $item['id_product'] != '' && $item['id_product'] != null)
			{
				$context->smarty->assign(array(
				'id_product' => $item['id_product'],
				'product_name' => $item['product_name'],
				'discount' => $item['discount'],
				'discount_type' => $item['discount_type'],
				'sale_type' => $item['sale_type']
				));

				if ($item['sale_type'] === 'stock')
				{
					$context->smarty->assign(array(
						'stock_status' => $item['active'],
					));
				}
				$html .= $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/flashsaleItemTableRow.tpl');
			}
		}
		$json = array();
		$json['html'] = $html;
		$json['headers'] = $headers;
		exit(Tools::jsonEncode($json));
	}

	public function ajaxProcessRefreshTable()
	{
		$flash_sale = new FlashSalePro();

		$context = Context::getContext();
		$lang = $context->employee->id_lang;

		$flash_sale_info = $flash_sale->getFlashSaleTableInfo($lang);
		$json = array();
		$context->smarty->assign(array(
			'flash_sale_info' => $flash_sale_info,
			));
		$json['content'] = $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/flashsaleTable.tpl');
		exit(Tools::jsonEncode($json));
	}

	public function ajaxProcessDeactivateShowFlashSale()
	{
		$flash_sale = new FlashSalePro();
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');

		$flash_sale->deactivateFlashSale($id_flash_sale);
		exit;
	}

	public function ajaxProcessActivateShowFlashSale()
	{
		$flash_sale = new FlashSalePro();
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');

		$activate_result = $flash_sale->activateFlashSale($id_flash_sale);
		exit(Tools::jsonEncode($activate_result));
	}

	public function ajaxProcessEditSaleSelectProduct()
	{
		$flash_sale = new FlashSalePro();
		$currency = Currency::getDefaultCurrency();
		$lang = (int)Tools::getValue('lang');
		$id_product = (int)Tools::getValue('id_product');
		$flash_type = pSQL(Tools::getValue('flash_type'));
		$product = new Product($id_product);

		if (Validate::isLoadedObject($product))
		{
			$flash_sale->addToTempTable($id_product, 'product');
			$product_name = $product->getProductName($id_product, null, $lang);
			$price = number_format($product->price, 2);
			$image = $flash_sale->getImage($id_product);

			$context = Context::getContext();

			$json = array();
			$context->smarty->assign(array(
				'id_product' => $id_product,
				'product_name' => $product_name,
				'image' =>$image,
				'default_currency_sign' => $currency->sign,
				'price' => $price,
				'flash_type' =>$flash_type
				));
			$json['content'] = $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/productRow.tpl');
			exit(Tools::jsonEncode($json));
		}
		else
			Tools::displayError('Error loading Product.');
	}


	public function ajaxProcessSelectProduct()
	{
		$flash_sale = new FlashSalePro();
		$currency = Currency::getDefaultCurrency();
		$lang = (int)Tools::getValue('lang');
		$id_product = (int)Tools::getValue('id_product');
		$flash_type = pSQL(Tools::getValue('flash_type'));
		$product = new Product($id_product);

		if (Validate::isLoadedObject($product))
		{
			$flash_sale->addToTempTable($id_product, 'product');
			$product_name = $product->getProductName($id_product, null, $lang);
			$price = number_format($product->price, 2);
			$image = $flash_sale->getImage($id_product);

			$context = Context::getContext();

			$json = array();
			$context->smarty->assign(array(
				'id_product' => $id_product,
				'product_name' => $product_name,
				'image' =>$image,
				'default_currency_sign' => $currency->sign,
				'price' => $price,
				'flash_type' =>$flash_type
				));
			$json['content'] = $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/productRow.tpl');

			exit(Tools::jsonEncode($json));
		}
		else
			Tools::displayError('Error loading Product.');
	}

	public function ajaxProcessSelectCategory()
	{
		$flash_sale = new FlashSalePro();
		$currency = Currency::getDefaultCurrency();
		$id_category = (int)Tools::getValue('id_category');
		$category_name = pSQL(Tools::getValue('category_name'));

		$flash_sale->addToTempTable($id_category, 'category');

		$context = Context::getContext();

		$json = array();
		$context->smarty->assign(array(
			'id_category' => $id_category,
			'category_name' => $category_name,
			'default_currency_sign' => $currency->sign,
			));
		$json['content'] = $context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/tabs/categoryRow.tpl');
		exit(Tools::jsonEncode($json));
	}

	public function ajaxProcessRemoveItemFromList()
	{
		$id_item = (int)Tools::getValue('id_item');
		$item_type = (string)Tools::getValue('type');
		$flash_sale = new FlashSalePro();
		$flash_sale->removeItemFromList($id_item, $item_type);
		exit;
	}

	public function ajaxProcessUpdateFlashSaleInfo()
	{
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$group_restriction = (int)Tools::getValue('group_restriction');
		$currency_restriction = (int)Tools::getValue('currency_restriction');
		$country_restriction = (int)Tools::getValue('country_restriction');
		$font = pSQL(Tools::getValue('font'));
		$bg_color = pSQL(Tools::getValue('bg_color'));
		$text_color = pSQL(Tools::getValue('text_color'));
		$name_keys = Tools::getValue('name_keys');
		$name_values = Tools::getValue('name_values');
		$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
			SET id_group_restriction = "'.(int)$group_restriction.'", id_currency_restriction = "'.(int)$currency_restriction.'",
			id_country_restriction = "'.(int)$country_restriction.'", font = "'.pSQL($font).'", bg_color = "'.pSQL($bg_color).'",
			text_color = "'.pSQL($text_color).'" WHERE id_flashsalespro = '.(int)$id_flash_sale;

		$update_names_query = '';
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		$count_name_values = count($name_values);
		for ($i = 0; $i < $count_name_values; $i++)
		{
			if ($name_values[$i] === '')
				$name = $name_values[$id_lang_default];
			else
				$name = $name_values[$i];
			$update_names_query = 'UPDATE '._DB_PREFIX_.'flashsalespro_names 
				SET name = "'.pSQL($name).'" WHERE id_flashsalespro = '.(int)$id_flash_sale.' AND id_lang = '.(int)$name_keys[$i];
			Db::getInstance()->execute($update_names_query);
		}

		Db::getInstance()->execute($update_query);
		exit;
	}

	public function ajaxProcessUpdateEndDate()
	{
		$new_date = pSQL(Tools::getValue('new_date').':00');
		
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$sale = new FlashSale($id_flash_sale);
		$sale->updateEndDate($new_date);
	}

	public function ajaxProcessUpdateStartDate()
	{
		$new_date = pSQL(Tools::getValue('new_date').':00');
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$sale = new FlashSale($id_flash_sale);
		$sale->updateStartDate($new_date);
	}

	public function ajaxProcessEditFlashSale()
	{
		$flash_sale = new FlashSalePro();
		$context = Context::getContext();
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');

		$sale = new FlashSale($id_flash_sale);
		$context->smarty->assign('sale', $sale->getSale());

		$items = $sale->getItems();
		$items_count = $sale->getItemsCount();
		$result = array();
		$result['sale'] = $sale->getSale();
		$result['items'] = $items;
		$result['items_count'] = $items_count;
		$result['names'] = $sale->getNames();
		$result['product_html'] = $flash_sale->getEditProductsBlock($items);
		$result['pagination_html'] = $flash_sale->getEditPaginationHtml($items);
		echo Tools::jsonEncode($result);
		exit;
	}

	public function ajaxProcessItemPaginationChange()
	{
		$flash_sale = new FlashSalePro();
		
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$index = (int)Tools::getValue('index');
		$item_count = (int)Tools::getValue('item_count');
		
		$sale = new FlashSale($id_flash_sale);
		$result['items'] = $sale->getItemsFromIndex($index);
		$result['product_html'] = $flash_sale->getEditProductsBlock($result['items']);
		echo Tools::jsonEncode($result);
		exit;
	}

	public function ajaxProcessAddItemToFlashSale()
	{
		$flash_sale = new FlashSalePro();
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$id_product = (int)Tools::getValue('id_product');
		$discount_type = pSQL(Tools::getValue('discount_type'));
		$discount_amount = (float)Tools::getValue('discount_amount');
		$sale = $flash_sale->getFlashSale($id_flash_sale);

		$specific_price = new SpecificPrice();
		$specific_price->id_shop = 1;
		$specific_price->id_product = $id_product;
		$specific_price->from_quantity = 1;
		$specific_price->id_country = (int)$sale['id_country_restriction'];
		$specific_price->id_group = (int)$sale['id_group_restriction'];
		$specific_price->id_customer = 0;
		$specific_price->price = -1;
		$specific_price->id_currency = (int)$sale['id_currency_restriction'];
		$specific_price->from = $sale['date_start'];
		$specific_price->to = $sale['date_end'];

		if ($discount_type === 'percentage')
			$specific_price->reduction = $discount_amount / 100;
		else
			$specific_price->reduction = $discount_amount;
		$specific_price->reduction_type = $discount_type;
		$specific_price->add();
		$result = array();
		$item_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro_items (
								id_flashsalespro, id_specific_price, discount, discount_type, custom_img_link_flag, custom_img_link, active)
							VALUES ('.(int)$id_flash_sale.', '.(int)$specific_price->id.', '.(float)$discount_amount.', "'
								.pSQL($discount_type).'", 0, "", 1) ';
		$result['query'] = Db::getInstance()->Execute($item_query);
		echo Tools::jsonEncode($result);
		exit;
	}

	public function ajaxProcessEditSaleRemoveItem()
	{
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$sale = new FlashSale($id_flash_sale);

		$id_specific_price = (int)Tools::getValue('id_specific_price');

		$sale->removeItem($id_specific_price);
	}

	public function ajaxProcessDeleteItAll()
	{
		$id_flash_sale = (int)Tools::getValue('id_flash_sale');
		$sale = new FlashSale($id_flash_sale);
		$sale->deleteFlashSale();
	}

	public function ajaxProcessCreateConfirmationTable()
	{
		$ids = array();
		$html = '';
		$flash_sale = new FlashSalePro();
		$context = Context::getContext();
		$lang = (int)$context->employee->id_lang;

		$id_group = (int)Tools::getValue('group');
		$id_currency = (int)Tools::getValue('currency');
		$id_country = (int)Tools::getValue('country');
		$flash_type = pSQL(Tools::getValue('flash_type'));

		if ($flash_type == 'timed')
			$ids['flash_type_name'] = $flash_sale->l('Timed');
		else if ($flash_type == 'manual')
			$ids['flash_type_name'] = $flash_sale->l('Manual');
		else
			$ids['flash_type_name'] = $flash_sale->l('Stock Dependent');

		if ($id_group > 0)
		{
			$group = new Group($id_group, $lang);
			$ids['group_name'] = $group->name;
		}
		else
			$ids['group_name'] = $flash_sale->l('No Restriction');

		if ($id_country > 0)
			$ids['country_name'] = Country::getNameById($lang, $id_country);
		else
			$ids['country_name'] = $flash_sale->l('No Restriction');

		if ($id_currency > 0)
		{
			$currency = new Currency($id_currency);
			$ids['currency_name'] = $currency->name;
		}
		else
			$ids['currency_name'] = $flash_sale->l('No Restriction');
		$list_category = array();
		$list_product = array();

		$temp_query_products = 'SELECT id_item FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE `item_type` = "product"';
		$result_temp_products = Db::getInstance()->ExecuteS($temp_query_products);
		foreach ($result_temp_products as $id_product)
			array_push($list_product, $id_product['id_item']);

		$temp_query_categories = 'SELECT id_item FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE `item_type` = "category"';
		$result_temp_categories = Db::getInstance()->ExecuteS($temp_query_categories);
		foreach ($result_temp_categories as $id_category)
			array_push($list_category, $id_category['id_item']);

		foreach ($list_product as $id_product)
		{
			$discount_query_products = 'SELECT discount_amount, discount_type FROM `'._DB_PREFIX_.'flashsalespro_temp`
			 							WHERE `item_type` = "product" AND `id_item` = '.(int)$id_product;
			$discount = Db::getInstance()->getRow($discount_query_products);
			$product = new Product($id_product);
			$product_name = $product->getProductName($id_product, null, $lang);
			$html .= '<tr id="tr_flash_info_'.$id_product.'">
						<td>'.$id_product.'</td><td>'.$product_name.'</td><td>'.$discount['discount_amount'].'</td><td>'.$discount['discount_type'].'</td>
						<td><i class="icon-trash icon-2x" onclick="removeItemFromSaleCreation('.$id_product.');" style="cursor:pointer; padding: 2px;"></i></td>
					</tr>';
		}

		foreach ($list_category as $id_category)
		{
			if ($flash_sale->isParentCatInList($id_category, $list_category) === 0) /* Parent categories always get priority */
			{
				$discount_query_categories = 'SELECT discount_amount, discount_type FROM `'._DB_PREFIX_.'flashsalespro_temp`
											 WHERE `item_type` = "category" AND `id_item` = '.(int)$id_category;
				$discount = Db::getInstance()->getRow($discount_query_categories);
				$products = $flash_sale->getProductsFromCategory($id_category, $list_product);

				foreach ($products as $id_product)
				{
					$product = new Product($id_product);
					$product_name = $product->getProductName($id_product, null, $lang);
					$html .= '<tr id="tr_flash_info_'.$id_product.'">
								<td>'.$id_product.'</td><td>'.$product_name.'</td><td>'.$discount['discount_amount'].'</td><td>'.$discount['discount_type'].'</td>
								<td><i class="icon-trash icon-2x" onclick="removeItemFromSaleCreation('.$id_product.');" style="cursor:pointer; padding: 2px;"></i></td>
							</tr>';
				}
			}
		}
		$ids['html'] = $html;

		echo Tools::jsonEncode($ids);
		exit;
	}

	public function ajaxProcessTempInsertDiscount()
	{
		$id_item = (int)Tools::getValue('id_item');
		$discount = (float)Tools::getValue('discount');
		$type = pSQL(Tools::getValue('type'));
		$temp_query = 'UPDATE `'._DB_PREFIX_.'flashsalespro_temp` SET discount_amount = '.(float)$discount.
						' WHERE item_type = \''.pSQL($type).'\' AND id_item = '.(int)$id_item;
		return Db::getInstance()->Execute($temp_query);
	}

	public function ajaxProcessEmptyTempDiscount()
	{
		$item_query = 'DELETE FROM `'._DB_PREFIX_.'flashsalespro_temp`'; /* Clear the table that temporarily holds information */
		return Db::getInstance()->Execute($item_query);
	}

	public function ajaxProcessTempInsertDiscountType()
	{
		$id_item = (int)Tools::getValue('id_item');
		$discount_type = pSQL(Tools::getValue('discount_type'));
		$type = pSQL(Tools::getValue('type'));
		$temp_query = 'UPDATE `'._DB_PREFIX_.'flashsalespro_temp` SET discount_type = "'.pSQL($discount_type).
						'" WHERE item_type = \''.pSQL($type).'\' AND id_item = '.(int)$id_item;
		return Db::getInstance()->Execute($temp_query);
	}
}