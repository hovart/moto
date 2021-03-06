<?php

require (dirname(__FILE__).'/tptnmobilemenuClass.php');

class TptnMobilemenu extends Module
{
	private $_menu = '';
	private $_html = '';
	private $user_groups;

	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $page_name = '';

	private $spacer_size = '5';
	
	public function __construct()
	{
		$this->name = 'tptnmobilemenu';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

	 	parent::__construct();

		$this->displayName = $this->l('Menu for Mobiles/Tablets - Templatin');
		$this->description = $this->l('Add a new menu in header for mobiles/tablets.');
	}

	public function install()
	{
		if (!parent::install() ||
			!$this->registerHook('displayTop') ||
            !$this->registerHook('displayHeader') ||
			!Configuration::updateGlobalValue('MOD_TPTNMOBMENU_ITEMS', 'CAT1,CMS1,CMS2,PRD1') ||
			!$this->registerHook('actionObjectCategoryUpdateAfter') ||
			!$this->registerHook('actionObjectCategoryDeleteAfter') ||
			!$this->registerHook('actionObjectCategoryAddAfter') ||
			!$this->registerHook('actionObjectCmsUpdateAfter') ||
			!$this->registerHook('actionObjectCmsDeleteAfter') ||
			!$this->registerHook('actionObjectCmsAddAfter') ||
			!$this->registerHook('actionObjectSupplierUpdateAfter') ||
			!$this->registerHook('actionObjectSupplierDeleteAfter') ||
			!$this->registerHook('actionObjectSupplierAddAfter') ||
			!$this->registerHook('actionObjectManufacturerUpdateAfter') ||
			!$this->registerHook('actionObjectManufacturerDeleteAfter') ||
			!$this->registerHook('actionObjectManufacturerAddAfter') ||
			!$this->registerHook('actionObjectProductUpdateAfter') ||
			!$this->registerHook('actionObjectProductDeleteAfter') ||
			!$this->registerHook('actionObjectProductAddAfter') ||
			!$this->registerHook('categoryUpdate') ||
			!$this->registerHook('actionShopDataDuplication') ||
			!$this->installDB())
			return false;
		return true;
	}

	public function installDb()
	{
		return (Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnmobilemenu` (
			`id_tptnmobilemenu` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`id_shop` INT(11) UNSIGNED NOT NULL,
			`new_window` TINYINT( 1 ) NOT NULL,
			INDEX (`id_shop`)
		) ENGINE = '._MYSQL_ENGINE_.' CHARACTER SET utf8 COLLATE utf8_general_ci;') &&
			Db::getInstance()->execute('
			 CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnmobilemenu_lang` (
			`id_tptnmobilemenu` INT(11) UNSIGNED NOT NULL,
			`id_lang` INT(11) UNSIGNED NOT NULL,
			`id_shop` INT(11) UNSIGNED NOT NULL,
			`label` VARCHAR( 128 ) NOT NULL ,
			`link` VARCHAR( 128 ) NOT NULL ,
			INDEX ( `id_tptnmobilemenu` , `id_lang`, `id_shop`)
		) ENGINE = '._MYSQL_ENGINE_.' CHARACTER SET utf8 COLLATE utf8_general_ci;'));
	}

	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Configuration::deleteByName('MOD_TPTNMOBMENU_ITEMS') ||
			!$this->uninstallDB())
			return false;
		return true;
	}

	private function uninstallDb()
	{
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'tptnmobilemenu`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'tptnmobilemenu_lang`');
		return true;
	}

	public function getContent()
	{
		$id_lang = (int)Context::getContext()->language->id;
		$languages = $this->context->controller->getLanguages();
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

		$labels = Tools::getValue('label') ? array_filter(Tools::getValue('label'), 'strlen') : array();
		$links_label = Tools::getValue('link') ? array_filter(Tools::getValue('link'), 'strlen') : array();
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$divLangName = 'link_label';
		
		$update_cache = false;

		if (Tools::isSubmit('submitTptnmobilemenu'))
		{
			if (Configuration::updateValue('MOD_TPTNMOBMENU_ITEMS', Tools::getValue('items')))
				$this->_html .= $this->displayConfirmation($this->l('The settings have been updated.'));
			else
				$this->_html .= $this->displayError($this->l('Unable to update settings.'));
			$update_cache = true;
		}
		else if (Tools::isSubmit('submitTptnmobilemenuLinks'))
		{

			if ((!count($links_label)) && (!count($labels)))
				;
			else if (!count($links_label))
				$this->_html .= $this->displayError($this->l('Please complete the "link" field.'));
			else if (!count($labels))
				$this->_html .= $this->displayError($this->l('Please add a label'));
			else if (!isset($labels[$default_language]))
				$this->_html .= $this->displayError($this->l('Please add a label for your default language.'));
			else
			{
				TptnMobilemenuClass::add(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('new_window', 0), (int)Shop::getContextShopID());
				$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
			}
			$update_cache = true;
		}
		else if (Tools::isSubmit('submitTptnmobilemenuRemove'))
		{
			$id_tptnmobilemenu = Tools::getValue('id_tptnmobilemenu', 0);
			TptnMobilemenuClass::remove($id_tptnmobilemenu, (int)Shop::getContextShopID());
			Configuration::updateValue('MOD_TPTNMOBMENU_ITEMS', str_replace(array('LNK'.$id_tptnmobilemenu.',', 'LNK'.$id_tptnmobilemenu), '', Configuration::get('MOD_TPTNMOBMENU_ITEMS')));
			$this->_html .= $this->displayConfirmation($this->l('The link has been removed'));
			$update_cache = true;
		}
		else if (Tools::isSubmit('submitTptnmobilemenuEdit'))
		{
			$id_tptnmobilemenu = (int)Tools::getValue('id_tptnmobilemenu', 0);
			$id_shop = (int)Shop::getContextShopID();

			if (!Tools::isSubmit('link'))
			{
				$tmp = TptnMobilemenuClass::getLinkLang($id_tptnmobilemenu, $id_shop);
				$links_label_edit = $tmp['link'];
				$labels_edit = $tmp['label'];
				$new_window_edit = $tmp['new_window'];
			}
			else
			{
				TptnMobilemenuClass::update(Tools::getValue('link'), Tools::getValue('label'), Tools::getValue('new_window', 0), (int)$id_shop, (int)$id_tptnmobilemenu, (int)$id_tptnmobilemenu);
				$this->_html .= $this->displayConfirmation($this->l('The link has been edited'));
			}
			$update_cache = true;
		}
		
		if ($update_cache)
			$this->clearMenuCache();
		
		$this->_html .= '
		<fieldset>
			<div class="multishop_info">
			'.$this->l('The modifications will be applied to').' '.(Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop').' '.$this->context->shop->name : $this->l('all shops')).'.
			</div>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
			<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" id="form">
				<div style="display: none">
				<label>'.$this->l('Items').'</label>
				<div class="margin-form">
					<input type="text" name="items" id="itemsInput" value="'.Tools::safeOutput(Configuration::get('MOD_TPTNMOBMENU_ITEMS')).'" size="70" />
				</div>
				</div>

				<div class="clear">&nbsp;</div>
				<table style="margin-left: 130px;">
					<tbody>
						<tr>
							<td style="padding-left: 20px;">
								<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';

		// BEGIN CMS
		$this->_html .= '<optgroup label="'.$this->l('CMS').'">';
		$this->getCMSOptions(0, 1, $id_lang);
		$this->_html .= '</optgroup>';

		// BEGIN SUPPLIER
		$this->_html .= '<optgroup label="'.$this->l('Supplier').'">';
		// Option to show all Suppliers
		$this->_html .= '<option value="ALLSUP0">'.$this->l('All suppliers').'</option>';
		$suppliers = Supplier::getSuppliers(false, $id_lang);
		foreach ($suppliers as $supplier)
			$this->_html .= '<option value="SUP'.$supplier['id_supplier'].'">'.$spacer.$supplier['name'].'</option>';
		$this->_html .= '</optgroup>';

		// BEGIN Manufacturer
		$this->_html .= '<optgroup label="'.$this->l('Manufacturer').'">';
		// Option to show all Manufacturers
		$this->_html .= '<option value="ALLMAN0">'.$this->l('All manufacturers').'</option>';
		$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
		foreach ($manufacturers as $manufacturer)
			$this->_html .= '<option value="MAN'.$manufacturer['id_manufacturer'].'">'.$spacer.$manufacturer['name'].'</option>';
		$this->_html .= '</optgroup>';

		// BEGIN Categories
		$this->_html .= '<optgroup label="'.$this->l('Categories').'">';
		$this->getCategoryOption(1, (int)$id_lang, (int)Shop::getContextShopID());
		$this->_html .= '</optgroup>';
		
		// BEGIN Shops
		if (Shop::isFeatureActive())
		{
			$this->_html .= '<optgroup label="'.$this->l('Shops').'">';
			$shops = Shop::getShopsCollection();
			foreach ($shops as $shop)
			{
				if (!$shop->setUrl() && !$shop->getBaseURL())
					continue;
				$this->_html .= '<option value="SHOP'.(int)$shop->id.'">'.$spacer.$shop->name.'</option>';
			}	
			$this->_html .= '</optgroup>';
		}
		
		// BEGIN Products
		$this->_html .= '<optgroup label="'.$this->l('Products').'">';
		$this->_html .= '<option value="PRODUCT" style="font-style:italic">'.$spacer.$this->l('Choose product ID').'</option>';
		$this->_html .= '</optgroup>';

		// BEGIN Menu Top Links
		$this->_html .= '<optgroup label="'.$this->l('Menu Top Links').'">';
		$links = TptnMobilemenuClass::gets($id_lang, null, (int)Shop::getContextShopID());
		foreach ($links as $link)
		{
			if ($link['label'] == '')
			{
				$link = TptnMobilemenuClass::get($link['id_tptnmobilemenu'], $default_language, (int)Shop::getContextShopID());
				$this->_html .= '<option value="LNK'.(int)$link[0]['id_tptnmobilemenu'].'">'.$spacer.$link[0]['label'].'</option>';
			}
			else
				$this->_html .= '<option value="LNK'.(int)$link['id_tptnmobilemenu'].'">'.$spacer.$link['label'].'</option>';
		}
		$this->_html .= '</optgroup>';

		$this->_html .= '</select><br />
								<br />
								<a href="#" id="addItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">'.$this->l('Add').' &gt;&gt;</a>
							</td>
							<td>
								<select multiple="multiple" id="items" style="width: 300px; height: 160px;">';
		$this->makeMenuOption();
		$this->_html .= '</select><br/>
								<br/>
								<a href="#" id="removeItem" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block; text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">&lt;&lt; '.$this->l('Remove').'</a>
							</td>
							<td style="vertical-align:top;padding:5px 15px;">
								<h4 style="margin-top:5px;">'.$this->l('Change position').'</h4> 
								<a href="#" id="menuOrderUp" class="button" style="font-size:20px;display:block;">&uarr;</a><br/>
								<a href="#" id="menuOrderDown" class="button" style="font-size:20px;display:block;">&darr;</a><br/>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="clear">&nbsp;</div>
				<script type="text/javascript">
				function add()
				{
					$("#availableItems option:selected").each(function(i){
						var val = $(this).val();
						var text = $(this).text();
						text = text.replace(/(^\s*)|(\s*$)/gi,"");
						if (val == "PRODUCT")
						{
							val = prompt("'.$this->l('Set ID product').'");
							if (val == null || val == "" || isNaN(val))
								return;
							text = "'.$this->l('Product ID').' "+val;
							val = "PRD"+val;
						}
						$("#items").append("<option value=\""+val+"\">"+text+"</option>");
					});
					serialize();
					return false;
				}

				function remove()
				{
					$("#items option:selected").each(function(i){
						$(this).remove();
					});
					serialize();
					return false;
				}

				function serialize()
				{
					var options = "";
					$("#items option").each(function(i){
						options += $(this).val() + ",";
					});
					$("#itemsInput").val(options.substr(0, options.length - 1));
				}

				function move(up)
				{
					var tomove = $("#items option:selected");
					if (tomove.length >1)
					{
						alert(\''.Tools::htmlentitiesUTF8($this->l('Please select just one item')).'\');
						return false;
					}
					if (up)
						tomove.prev().insertAfter(tomove);
					else
						tomove.next().insertBefore(tomove);
					serialize();
					return false;
				}

				$(document).ready(function(){
					$("#addItem").click(add);
					$("#availableItems").dblclick(add);
					$("#removeItem").click(remove);
					$("#items").dblclick(remove);
					$("#menuOrderUp").click(function(e){
						e.preventDefault();
						move(true);
					});
					$("#menuOrderDown").click(function(e){
						e.preventDefault();
						move();
					});
				});
				</script>
				<p class="center">
					<input type="submit" name="submitTptnmobilemenu" value="'.$this->l('Save	').'" class="button" />
				</p>
			</form>
		</fieldset><br />';

		$this->_html .= '
		<fieldset>
			<legend><img src="../img/admin/add.gif" alt="" title="" />'.$this->l('Add Menu Top Link').'</legend>
			<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" id="form">

				';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div id="link_label_'.(int)$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang ? 'block' : 'none').';">
				<label>'.$this->l('Label').'</label>
				<div class="margin-form">
						<input type="text" name="label['.(int)$language['id_lang'].']" id="label_'.(int)$language['id_lang'].'" size="70" value="'.(isset($labels_edit[$language['id_lang']]) ? $labels_edit[$language['id_lang']] : '').'" />
			  </div>
					';

			$this->_html .= '
				  <label>'.$this->l('Link').'</label>
				<div class="margin-form">
					<input type="text" name="link['.(int)$language['id_lang'].']" id="link_'.(int)$language['id_lang'].'" value="'.(isset($links_label_edit[$language['id_lang']]) ? $links_label_edit[$language['id_lang']] : '').'" size="70" />
				</div>
				</div>';
		}

		$this->_html .= '<label>'.$this->l('Language').'</label>
				<div class="margin-form">'.$this->displayFlags($languages, (int)$id_lang, $divLangName, 'link_label', true).'</div><p style="clear: both;"> </p>';

		$this->_html .= '<label style="clear: both;">'.$this->l('New Window').'</label>
				<div class="margin-form">
					<input style="clear: both;" type="checkbox" name="new_window" value="1" '.(isset($new_window_edit) && $new_window_edit ? 'checked' : '').'/>
				</div>
<div class="margin-form">';

		if (Tools::isSubmit('id_tptnmobilemenu'))
			$this->_html .= '<input type="hidden" name="id_tptnmobilemenu" value="'.(int)Tools::getValue('id_tptnmobilemenu').'" />';

		if (Tools::isSubmit('submitTptnmobilemenuEdit'))
			$this->_html .= '<input type="submit" name="submitTptnmobilemenuEdit" value="'.$this->l('Edit').'" class="button" />';

		$this->_html .= '
					<input type="submit" name="submitTptnmobilemenuLinks" value="'.$this->l('Add	').'" class="button" />
</div>

			</form>
		</fieldset><br />';

		$links = TptnMobilemenuClass::gets((int)$id_lang, null, (int)Shop::getContextShopID());

		if (!count($links))
			return $this->_html;

		$this->_html .= '
		<fieldset>
			<legend><img src="../img/admin/details.gif" alt="" title="" />'.$this->l('List Menu Top Link').'</legend>
			<table style="width:100%;">
				<thead>
					<tr style="text-align: left;">
						<th>'.$this->l('Id Link').'</th>
						<th>'.$this->l('Label').'</th>
						<th>'.$this->l('Link').'</th>
						<th>'.$this->l('New Window').'</th>
						<th>'.$this->l('Action').'</th>
					</tr>
				</thead>
				<tbody>';
		foreach ($links as $link)
		{
			$this->_html .= '
					<tr>
						<td>'.(int)$link['id_tptnmobilemenu'].'</td>
						<td>'.Tools::safeOutput($link['label']).'</td>
						<td><a href="'.Tools::safeOutput($link['link']).'"'.(($link['new_window']) ? ' target="_blank"' : '').'>'.Tools::safeOutput($link['link']).'</a></td>
						<td>'.(($link['new_window']) ? $this->l('Yes') : $this->l('No')).'</td>
						<td>
							<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
								<input type="hidden" name="id_tptnmobilemenu" value="'.(int)$link['id_tptnmobilemenu'].'" />
								<input type="submit" name="submitTptnmobilemenuEdit" value="'.$this->l('Edit').'" class="button" />
								<input type="submit" name="submitTptnmobilemenuRemove" value="'.$this->l('Remove').'" class="button" />
							</form>
						</td>
					</tr>';
		}
		$this->_html .= '</tbody>
			</table>
		</fieldset>';
		return $this->_html;
	}

	private function getMenuItems()
	{
		return explode(',', Configuration::get('MOD_TPTNMOBMENU_ITEMS'));
	}

	private function makeMenuOption()
	{
		$menu_item = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $values);
			$id = (int)substr($item, strlen($values[1]), strlen($item));

			switch (substr($item, 0, strlen($values[1])))
			{
				case 'CAT':
					$category = new Category((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$this->_html .= '<option value="CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				case 'PRD':
					$product = new Product((int)$id, true, (int)$id_lang);
					if (Validate::isLoadedObject($product))
						$this->_html .= '<option value="PRD'.$id.'">'.$product->name.'</option>'.PHP_EOL;
					break;

				case 'CMS':
					$cms = new CMS((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($cms))
						$this->_html .= '<option value="CMS'.$id.'">'.$cms->meta_title.'</option>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$this->_html .= '<option value="CMS_CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				// Case to handle the option to show all Manufacturers
				case 'ALLMAN':
					$this->_html .= '<option value="ALLMAN0">'.$this->l('All manufacturers').'</option>'.PHP_EOL;
					break;

				case 'MAN':
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($manufacturer))
						$this->_html .= '<option value="MAN'.$id.'">'.$manufacturer->name.'</option>'.PHP_EOL;
					break;

				// Case to handle the option to show all Suppliers
				case 'ALLSUP':
					$this->_html .= '<option value="ALLSUP0">'.$this->l('All suppliers').'</option>'.PHP_EOL;
					break;
					
				case 'SUP':
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($supplier))
						$this->_html .= '<option value="SUP'.$id.'">'.$supplier->name.'</option>'.PHP_EOL;
					break;

				case 'LNK':
					$link = TptnMobilemenuClass::get((int)$id, (int)$id_lang, (int)$id_shop);
					if (count($link))
					{
						if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
						{
							$default_language = Configuration::get('PS_LANG_DEFAULT');
							$link = TptnMobilemenuClass::get($link[0]['id_tptnmobilemenu'], (int)$default_language, (int)Shop::getContextShopID());
						}
						$this->_html .= '<option value="LNK'.$link[0]['id_tptnmobilemenu'].'">'.$link[0]['label'].'</option>';
					}
					break;
				case 'SHOP':
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
						$this->_html .= '<option value="SHOP'.(int)$id.'">'.$shop->name.'</option>'.PHP_EOL;
					break;
			}
		}
	}

	private function makeMenu()
	{
		$menu_items = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();

		foreach ($menu_items as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $value);
			$id = (int)substr($item, strlen($value[1]), strlen($item));

			switch (substr($item, 0, strlen($value[1])))
			{
				case 'CAT':
					$this->getCategory($id, $id_lang, $id_shop);
					break;

				case 'PRD':
					$selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover"' : '';
					$product = new Product((int)$id, true, (int)$id_lang);
					if (!is_null($product->id))
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($product->getLink()).'">'.$product->name.'</a></li>'.PHP_EOL;
					break;

				case 'CMS':
					$selected = ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? ' class="sfHover"' : '';
					$cms = CMS::getLinks((int)$id_lang, array($id));
					if (count($cms))
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($cms[0]['link']).'">'.$cms[0]['meta_title'].'</a></li>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					if (count($category))
					{
						$this->_menu .= '<li><a href="'.Tools::HtmlEntitiesUTF8($category->getLink()).'">'.$category->name.'</a>';
						$this->getCMSMenuItems($category->id);
						$this->_menu .= '</li>'.PHP_EOL;
					}
					break;

				// Case to handle the option to show all Manufacturers
				case 'ALLMAN':
					$link = new Link;
					$this->_menu .= '<li><a href="'.$link->getPageLink('manufacturer').'">'.$this->l('All manufacturers').'</a><ul>'.PHP_EOL;
					$manufacturers = Manufacturer::getManufacturers();
					foreach ($manufacturers as $key => $manufacturer)
						$this->_menu .= '<li><a href="'.$link->getManufacturerLink((int)$manufacturer['id_manufacturer'], $manufacturer['link_rewrite']).'">'.$manufacturer['name'].'</a></li>'.PHP_EOL;
					$this->_menu .= '</ul>';
					break;

				case 'MAN':
					$selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover"' : '';
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (!is_null($manufacturer->id))
					{
						if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
							$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
						else
							$manufacturer->link_rewrite = 0;
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($link->getManufacturerLink((int)$id, $manufacturer->link_rewrite)).'">'.$manufacturer->name.'</a></li>'.PHP_EOL;
					}
					break;

				// Case to handle the option to show all Suppliers
				case 'ALLSUP':
					$link = new Link;
					$this->_menu .= '<li><a href="'.$link->getPageLink('supplier').'">'.$this->l('All suppliers').'</a><ul>'.PHP_EOL;
					$suppliers = Supplier::getSuppliers();
					foreach ($suppliers as $key => $supplier)
						$this->_menu .= '<li><a href="'.$link->getSupplierLink((int)$supplier['id_supplier'], $supplier['link_rewrite']).'">'.$supplier['name'].'</a></li>'.PHP_EOL;
					$this->_menu .= '</ul>';
					break;

				case 'SUP':
					$selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover"' : '';
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (!is_null($supplier->id))
					{
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($link->getSupplierLink((int)$id, $supplier->link_rewrite)).'">'.$supplier->name.'</a></li>'.PHP_EOL;
					}
					break;

				case 'SHOP':
					$selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover"' : '';
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
					{
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($shop->getBaseURL()).'">'.$shop->name.'</a></li>'.PHP_EOL;
					}
					break;
				case 'LNK':
					$link = TptnMobilemenuClass::get((int)$id, (int)$id_lang, (int)$id_shop);
					if (count($link))
					{
						if (!isset($link[0]['label']) || ($link[0]['label'] == ''))
						{
							$default_language = Configuration::get('PS_LANG_DEFAULT');
							$link = TptnMobilemenuClass::get($link[0]['id_tptnmobilemenu'], $default_language, (int)Shop::getContextShopID());
						}
						$this->_menu .= '<li><a href="'.Tools::HtmlEntitiesUTF8($link[0]['link']).'"'.(($link[0]['new_window']) ? ' target="_blank"': '').'>'.$link[0]['label'].'</a></li>'.PHP_EOL;
					}
					break;
			}
		}
	}

	private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);

		if (is_null($category->id))
			return;

		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$category->level_depth);
		}

		$shop = (object) Shop::getShop((int)$category->getShopID());
		$this->_html .= '<option value="CAT'.(int)$category->id.'">'.(isset($spacer) ? $spacer : '').$category->name.' ('.$shop->name.')</option>';

		if (isset($children) && count($children))
			foreach ($children as $child)
				$this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
	}

	private function getCategory($id_category, $id_lang = false, $id_shop = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category->level_depth > 1)
			$category_link = $category->getLink();
		else
			$category_link = $this->context->link->getPageLink('index');

		if (is_null($category->id))
			return;

		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
		$selected = ($this->page_name == 'category' && ((int)Tools::getValue('id_category') == $id_category)) ? ' class="sfHoverForce"' : '';

		$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
		// filter the categories that the user is allowed to see and browse
		if (!empty($is_intersected))
		{
			$this->_menu .= '<li '.$selected.'>';
			$this->_menu .= '<a href="'.Tools::HtmlEntitiesUTF8($category_link).'">'.$category->name.'</a>';

			if (count($children))
			{
				$this->_menu .= '<ul>';

				foreach ($children as $child)
					$this->getCategory((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);

				$this->_menu .= '</ul>';
			}
			$this->_menu .= '</li>';
		}
	}

	private function getCMSMenuItems($parent, $depth = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($depth > 3)
			return;

		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent);

		if (count($categories) || count($pages))
		{
			$this->_menu .= '<ul>';

			foreach ($categories as $category)
			{
				$this->_menu .= '<li>';
				$this->_menu .= '<a href="#">'.$category['name'].'</a>';
				$this->getCMSMenuItems($category['id_cms_category'], (int)$depth + 1);
				$this->_menu .= '</li>';
			}

			foreach ($pages as $page)
			{
				$cms = new CMS($page['id_cms'], (int)$id_lang);
				$links = $cms->getLinks((int)$id_lang, array((int)$cms->id));

				$selected = ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms') == $page['id_cms'])) ? ' class="sfHoverForce"' : '';
				$this->_menu .= '<li '.$selected.'>';
				$this->_menu .= '<a href="'.$links[0]['link'].'">'.$cms->meta_title.'</a>';
				$this->_menu .= '</li>';
			}

			$this->_menu .= '</ul>';
		}
	}

	private function getCMSOptions($parent = 0, $depth = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);

		$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$depth);

		foreach ($categories as $category)
		{
			$this->_html .= '<option value="CMS_CAT'.$category['id_cms_category'].'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
			$this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang);
		}

		foreach ($pages as $page)
			$this->_html .= '<option value="CMS'.$page['id_cms'].'">'.$spacer.$page['meta_title'].'</option>';
	}
	
	protected function getCacheId($name = null)
	{
		parent::getCacheId($name);
		$page_name = in_array($this->page_name, array('category', 'supplier', 'manufacturer', 'cms', 'product')) ? $this->page_name : 'index';
		return 'tptnmobilemenu|'.(int)Tools::usingSecureMode().'|'.$page_name.'|'.(int)$this->context->shop->id.'|'.implode(', ',$this->user_groups).'|'.(int)$this->context->language->id.'|'.(int)Tools::getValue('id_category').'|'.(int)Tools::getValue('id_manufacturer').'|'.(int)Tools::getValue('id_supplier').'|'.(int)Tools::getValue('id_cms').'|'.(int)Tools::getValue('id_product');
	}

	public function hookDisplayTop($param)
	{
		$this->user_groups =  ($this->context->customer->isLogged() ? $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
		$this->page_name = Dispatcher::getInstance()->getController();
		if (!$this->isCached('tptnmobilemenu.tpl', $this->getCacheId()))
		{
			$this->makeMenu();
			$this->smarty->assign('MENU', $this->_menu);
			$this->smarty->assign('this_path', $this->_path);
		}

		$this->context->controller->addJS($this->_path.'js/tptnmobilemenu.js');
		
		$html = $this->display(__FILE__, 'tptnmobilemenu.tpl', $this->getCacheId());
		return $html;
	}
    
    public function hookDisplayHeader($param)
	{
        $this->context->controller->addCSS($this->_path.'css/tptnmobilemenu.css');    
		$this->context->controller->addJS($this->_path.'js/tptnmobilemenu.js');	
	}

	private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($recursive === false)
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			return Db::getInstance()->executeS($sql);
		}
		else
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;

			$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
				$sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
				if ($sub_categories && count($sub_categories) > 0)
					$result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}

			return isset($categories) ? $categories : false;
		}

	}

	private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
	{
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
			AND cs.`id_shop` = '.(int)$id_shop.'
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}

	public function hookActionObjectCategoryAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectCategoryUpdateAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectCategoryDeleteAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectCmsUpdateAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectCmsDeleteAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectCmsAddAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectSupplierUpdateAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectSupplierDeleteAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectSupplierAddAfter($params)
	{
		$this->clearMenuCache();
	}

	public function hookActionObjectManufacturerUpdateAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectManufacturerDeleteAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectManufacturerAddAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectProductUpdateAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectProductDeleteAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookActionObjectProductAddAfter($params)
	{
		$this->clearMenuCache();
	}
	
	public function hookCategoryUpdate($params)
	{
		$this->clearMenuCache();
	}
	
	private function clearMenuCache()
	{
		$this->_clearCache('tptnmobilemenu.tpl');
	}
	
	public function hookActionShopDataDuplication($params)
	{
		$tptnmobilemenu = Db::getInstance()->executeS('
			SELECT *
			FROM '._DB_PREFIX_.'tptnmobilemenu 
			WHERE id_shop = '.(int)$params['old_id_shop']
			);

		foreach($tptnmobilemenu as $id => $link)
		{
			Db::getInstance()->execute('
				INSERT IGNORE INTO '._DB_PREFIX_.'tptnmobilemenu (id_tptnmobilemenu, id_shop, new_window) 
				VALUES (null, '.(int)$params['new_id_shop'].', '.(int)$link['new_window'].')');
			
			$tptnmobilemenu[$id]['new_id_tptnmobilemenu'] = Db::getInstance()->Insert_ID();
		}
		
		foreach($tptnmobilemenu as $id => $link)
		{
			$lang = Db::getInstance()->executeS('
					SELECT id_lang, '.(int)$params['new_id_shop'].', label, link 
					FROM '._DB_PREFIX_.'tptnmobilemenu_lang 
					WHERE id_tptnmobilemenu = '.(int)$link['id_tptnmobilemenu'].' AND id_shop = '.(int)$params['old_id_shop']);
			
			foreach($lang as $l)
				Db::getInstance()->execute('
					INSERT IGNORE INTO '._DB_PREFIX_.'tptnmobilemenu_lang (id_tptnmobilemenu, id_lang, id_shop, label, link) 
					VALUES ('.(int)$link['new_id_tptnmobilemenu'].', '.(int)$l['id_lang'].', '.(int)$params['new_id_shop'].', '.(int)$l['label'].', '.(int)$l['link'].' )');
		}
		
		
	}
}
