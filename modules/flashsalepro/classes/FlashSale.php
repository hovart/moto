<?php

	class FlashSale
	{
		public $id_flash_sale = 0;
		public $parameters = array();

		public function __construct($id_flash_sale = 0)
		{
			$query = 'SELECT * FROM '._DB_PREFIX_.'flashsalespro 
			WHERE id_flashsalespro = '.(int)$id_flash_sale;

			$this->id_flash_sale = $id_flash_sale;
			$this->parameters = Db::getInstance()->getRow($query);
		}

		public function updateParameters($params)
		{
			$sale_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro (
											id_shop, id_group_restriction, id_currency_restriction,
											id_country_restriction, sale_type, date_start,
											date_end, end_date_timestamp, bg_color, text_color,
											font, active, sale_custom_img_link)
							VALUES ('.(int)$params['id_shop'].', '.(int)$params['id_group_restriction'].', '
									.(int)$params['id_currency_restriction'].', '.(int)$params['id_country_restriction'].', "'
									.pSQL($params['sale_type']).'", "'.pSQL($params['date_start']).'", "'
									.pSQL($params['date_end']).'", '.(int)$params['end_date_timestamp'].', "'.pSQL($params['bg_color']).'", "'
									.pSQL($params['text_color']).'", "'.pSQL($params['font']).'", '.(int)$params['active'].', "'
									.pSQL($params['sale_custom_img_link']).'")';

			$result = Db::getInstance()->Execute($sale_query);
			if ($result)
				return Db::getInstance()->ExecuteS('SELECT id_flashsalespro FROM '._DB_PREFIX_.'flashsalespro ORDER BY id_flashsalespro DESC LIMIT 1');
			else
				return 0;
		}

		public function getItems()
		{
			$query_items = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE id_flashsalespro = '.(int)$this->id_flash_sale.' ORDER BY id_flashsalespro';
			$items = Db::getInstance()->ExecuteS($query_items);
			return $items;
		}

		public function getItemsFromIndex($index)
		{
			$query_items = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro_items` WHERE id_flashsalespro = '.(int)$this->id_flash_sale.' ORDER BY id_flashsalespro LIMIT '.((int)($index - 1) * 4).', 4 ';
			$items = Db::getInstance()->ExecuteS($query_items);
			return $items;
		}

		public function getItemsCount()
		{
			$query_items_count = 'SELECT COUNT(id_flashsalespro_item) FROM `'._DB_PREFIX_.'flashsalespro_items`
								WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			$items_count = Db::getInstance()->getValue($query_items_count);
			return $items_count;
		}

		public function getItemsNameLike($lang, $value)
		{
			$sale_items = $this->getItems();
			$ids_sp = array();
			foreach ($sale_items as $si)
				array_push($ids_sp, $si['id_specific_price']);

			$query_items_view = 'SELECT fsi.id_flashsalespro_item, fsi.id_flashsalespro, fsi.id_specific_price, fsi.discount, fsi.discount_type FROM `'._DB_PREFIX_.'flashsalespro_items` fsi
								JOIN `'._DB_PREFIX_.'specific_price` sp
								ON sp.id_specific_price = fsi.id_specific_price
								JOIN `'._DB_PREFIX_.'product_lang` pl
								ON pl.id_product = sp.id_product
								WHERE fsi.id_specific_price IN ('.implode(',', array_map('intval', $ids_sp)).')
								AND pl.name LIKE "%'.pSQL($value).'%"
								AND pl.id_lang = '.(int)$lang;
			$items = Db::getInstance()->ExecuteS($query_items_view);
			return $items;
		}

		public function getNames()
		{
			$query_names = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro_names` 
					WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			$names_list = Db::getInstance()->ExecuteS($query_names);
			$names = array();
			foreach ($names_list as $name)
				$names[$name['id_lang']] = $name['name'];
			return $names;
		}

		public function getSale()
		{
			return $this->parameters;
		}

		public function updateStartDate($new_date)
		{
			$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
			SET date_start = "'.pSQL($new_date).'" WHERE id_flashsalespro = '.(int)$this->id_flash_sale;

			Db::getInstance()->execute($update_query);
			$query = 'SELECT id_specific_price FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			$result = Db::getInstance()->ExecuteS($query);
			foreach ($result as $item)
			{
				$specific_price = new SpecificPrice($item['id_specific_price']);
				$specific_price->from = $new_date;
				$specific_price->update();
			}
		}

		public function updateEndDate($new_date)
		{
			$new_date_timestamp = strtotime($new_date);
			
			$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
			SET date_end = "'.pSQL($new_date).'", end_date_timestamp = '.(int)$new_date_timestamp.' WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			Db::getInstance()->execute($update_query);

			$query = 'SELECT id_specific_price FROM `'._DB_PREFIX_.'flashsalespro_items` 
						WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			$result = Db::getInstance()->ExecuteS($query);
			foreach ($result as $item)
			{
				$specific_price = new SpecificPrice($item['id_specific_price']);
				$specific_price->to = $new_date;
				$specific_price->update();
			}
		}

		public function removeItem($id_specific_price)
		{
			$delete_item_query = 'DELETE FROM `'._DB_PREFIX_.'flashsalespro_items`
			WHERE id_specific_price = \''.(int)$id_specific_price.'\' AND id_flashsalespro = '.(int)$this->id_flash_sale;
			Db::getInstance()->Execute($delete_item_query);
			$specific_price = new SpecificPrice($id_specific_price);
			$specific_price->delete();
		}

		public function deleteFlashSale()
		{
			$query = 'SELECT id_specific_price FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			$result = Db::getInstance()->ExecuteS($query);
			foreach ($result as $item)
			{
				$specific_price = new SpecificPrice($item['id_specific_price']);
				$specific_price->delete();
			}

			$update_query = 'DELETE FROM '._DB_PREFIX_.'flashsalespro 
				WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			Db::getInstance()->execute($update_query);
			$update_query = 'DELETE FROM '._DB_PREFIX_.'flashsalespro_items 
				WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			Db::getInstance()->execute($update_query);
			$update_query = 'DELETE FROM '._DB_PREFIX_.'flashsalespro_names 
				WHERE id_flashsalespro = '.(int)$this->id_flash_sale;
			Db::getInstance()->execute($update_query);
		}

		public function getItemSpecificPrice($id_item)
		{
			$query = 'SELECT id_specific_price FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE id_flashsalespro_item = '.(int)$id_item;
			return Db::getInstance()->getValue($query);
		}
	}