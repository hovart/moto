<?php
/**
* 2007-2014 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/


if (defined('_PS_VERSION_') === false)
	exit;

class Prestagiftvouchers extends PaymentModule
{
	public function __construct()
	{
		$this->name = 'prestagiftvouchers';
		if (version_compare(_PS_VERSION_, '1.4', '>'))
			$this->tab = 'payments_gateways';
		else
			$this->tab = 'Payment';
		$this->version = '1.7.9';
		$this->author = 'PrestaShop';
		$this->module_key = '2096267b6f9def42848cdfb17b45be0d';

		if (version_compare(_PS_VERSION_, '1.5', '>'))
		{
			$this->id_shop = Context::getContext();
			$this->id_shop = $this->id_shop->shop->id;
		}
		else
			$this->id_shop = 1;

		parent::__construct();

		/** Backward compatibility */
		require(_PS_MODULE_DIR_.'/prestagiftvouchers/backward_compatibility/backward.php');

		$this->displayName = $this->l('Prestagiftvouchers');
		$this->description = $this->l('This module allows you to use gift vouchers on your shop.');
	}

	public function install()
	{
		if (parent::install() === false
			|| $this->registerHook('payment') === false
			|| $this->registerHook('updateOrderStatus') === false
			|| $this->installDB() === false)
			return false;
		return true;
	}

	public function installDB()
	{
		return Db::getInstance()->Execute('
		CREATE TABLE `'._DB_PREFIX_.'prestagiftvouchers` (
			`id_giftvoucher` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_product` INT UNSIGNED NOT NULL,
			`id_shop` INT  NOT NULL,
			`amount` FLOAT NOT NULL,
			`cumulable` BOOL NOT NULL,
			`validity` INT NOT NULL,
			PRIMARY KEY (`id_giftvoucher`)
		) DEFAULT CHARSET=utf8 ;');
	}

	public function uninstall()
	{
		return (parent::uninstall() && $this->uninstallDB());
	}

	public function uninstallDB()
	{
		return Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'prestagiftvouchers`');
	}

	private function getGiftVoucher($id_voucher)
	{
		return Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'prestagiftvouchers` WHERE id_giftvoucher = '.$id_voucher);
	}

	private function getGiftVouchers()
	{
		return Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'prestagiftvouchers` WHERE id_shop = '.$this->id_shop);
	}

	private function copybackport($source, $destination, $stream_context = null)
	{
		if (is_null($stream_context) && !preg_match('/^https?:\/\//', $source))
			return @copy($source, $destination);
		return @file_put_contents($destination, Tools::file_get_contents($source, false, $stream_context));
	}

	private function copyImg($id_entity, $id_image = null, $url, $regenerate = true)
	{
		$tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
		$watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

		$entity = 'products';

		$image_obj = new Image($id_image);
		$path = $image_obj->getPathForCreation();
		$url = str_replace(' ', '%20', trim($url));

		// Evaluate the memory required to resize the image: if it's too much, you can't resize it.
		if (!ImageManager::checkImageMemoryLimit($url))
			return false;

		// 'file_exists' doesn't work on distant file, and getimagesize make the import slower.
		// Just hide the warning, the traitment will be the same.
		if (version_compare(_PS_VERSION_, '1.5.5', '>'))
		{
			if (Tools::copy($url, $tmpfile))
			{
				ImageManager::resize($tmpfile, $path.'.jpg');
				$images_types = ImageType::getImagesTypes($entity);

				if ($regenerate)
					foreach ($images_types as $image_type)
					{
						ImageManager::resize($tmpfile, $path.'-'.stripslashes($image_type['name']).'.jpg', $image_type['width'], $image_type['height']);
						if (in_array($image_type['id_image_type'], $watermark_types))
							Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
					}
			}
			else
			{
				unlink($tmpfile);
				return false;
			}
			unlink($tmpfile);
			return true;
		}
		elseif ($this->copybackport($url, $tmpfile))
		{
			ImageManager::resize($tmpfile, $path.'.jpg');
			$images_types = ImageType::getImagesTypes($entity);

			if ($regenerate)
				foreach ($images_types as $image_type)
				{
					ImageManager::resize($tmpfile, $path.'-'.stripslashes($image_type['name']).'.jpg', $image_type['width'], $image_type['height']);
					if (in_array($image_type['id_image_type'], $watermark_types))
						Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
				}
			else
			{
				unlink($tmpfile);
				return false;
			}
			unlink($tmpfile);
			return true;
		}
	}

	private function addGiftVoucher()
	{
		global $cookie;

		$html = '';
		$tax_to_use = 0;
		$count = 0;
		$id_product = 0;
		$convert_price = strtr(Tools::getValue('amount'), ',', '.');
		$price = ($convert_price && preg_match('/^-?(?:\d+|\d*\.\d+)$/', $convert_price)) ? $convert_price : 10;
		$cumulable = (Tools::getValue('is_cumulable')) ? Tools::getValue('is_cumulable') : 1;
		$validity = (Tools::getValue('validity') && preg_match('/^\d+$/', Tools::getValue('validity'))) ? Tools::getValue('validity') : 12;

		if (Tools::getValue('has_associated_product') == '1')
		{
			$result_product = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int)Tools::getValue('associated_product'));
			(!empty($result_product))
				? $id_product = (int)Tools::getValue('associated_product')
				: $id_product = 0;
		}

		$html .= $this->displayConfirmation($this->l('Gift voucher added'));

		// If the product doesn't exist
		if ($id_product === 0)
		{
			// Add the product in DB

			$product = new Product();

			$product->name = array((int)Configuration::get('PS_LANG_DEFAULT') => 'Gift Card');
			$product->id_supplier = 0;
			$product->id_manufacturer = 0;
			$product->id_tax_rules_group = 0;
			$product->id_category_default = (int)Configuration::get('PS_HOME_CATEGORY');
			$product->on_sale = 0;
			$product->online_only = 0;
			$product->ean13 = '';
			$product->upc = '';
			$product->ecotax = '0.00';
			$product->quantity = '9999';
			$product->minimal_quantity = '1';
			$product->price = $price;
			$product->wholesale_price ='0.000000';
			$product->unity = 'NULL';
			$product->unit_price_ratio = '0.000000';
			$product->additional_shipping_cost = '0.00';
			$product->reference = '';
			$product->supplier_reference = 'NULL';
			$product->location = 'NULL';
			$product->width = '0';
			$product->height = '0';
			$product->depth = '0';
			$product->weight = '0';
			$product->out_of_stock = '0';
			$product->quantity_discount = '0';
			$product->customizable = '0';
			$product->uploadable_files = '0';
			$product->text_fields = '0';
			$product->active = 1;
			$product->available_for_order = 1;
			$product->condition = 'new';
			$product->show_price = 1;
			$product->indexed = 1;
			$product->cache_is_pack = 0;
			$product->cache_has_attachments = 0;
			$product->is_virtual = 1;
			$product->cache_default_attribute = 'NULL';
			$product->date_add = date('Y-m-d G:i:s');
			$product->date_upd = date('Y-m-d G:i:s');
			$product->link_rewrite = array((int)Configuration::get('PS_LANG_DEFAULT') => 'gift-card');
			$product->save();
			$product->addToCategories($categories = array($product->id_category_default));

			$id_product = (int)$product->id;

			$token_prod = '&token='.Tools::getAdminTokenLite('AdminProducts');

			if (version_compare(_PS_VERSION_, '1.5', '>'))
				$html .= '<p>'.$this->l('Please make sure to give a name, a description, a price and a rewritten URL to the').'
				<a style="font-weight:bold;" href="index.php?tab=AdminProducts&id_product='.$id_product.'&updateproduct'.$token_prod.'">
					'.$this->l('product newly created').'
				</a>.
				</p>';
			else
				$html .= '<p>'.$this->l('Please make sure to give a name , a description and a friendly URL to the').'
				<a style="font-weight:bold;color:red;" href="index.php?tab=AdminCatalog&id_product='.$id_product.'&updateproduct'.$token_prod.'">
				'.$this->l('product newly created').'
				</a>.
				</p>';

			// Add image for the product
			$image_obj = new Image();
			$image_obj->id_product = (int)$id_product;
			$image_obj->position = Image::getHighestPosition($id_product) + 1;
			$image_obj->cover = true;
			$image_obj->associateTo($this->id_shop);
			$image_obj->save();

			$url = _PS_MODULE_DIR_.$this->name.'/product.png';
			$this->copyImg($id_product, $image_obj->id, $url, true);
		}

		// Add the voucher in DB
		Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'prestagiftvouchers`
		(`id_product`, id_shop, `amount`, `cumulable`, `validity`)
		VALUES ("'.(int)$id_product.'", "'.(int)$this->id_shop.'","'.(float)$price.'","'.(int)$cumulable.'","'.(int)$validity.'")');

		return $html;
	}

	private function deleteGiftVoucher()
	{
		$html = '';
		$deleteid = Tools::getValue('deletegiftvoucher');
		$id_giftvoucher = ($deleteid && preg_match('/^\d+$/', $deleteid)) ? (int)$deleteid : 0;
		if ($id_giftvoucher != 0)
		{
			Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'prestagiftvouchers` WHERE `id_giftvoucher` = '.(int)$id_giftvoucher.' AND `id_shop` = '.(int)$this->id_shop);
			$html .= $this->displayError($this->l('Gift voucher deleted'));
			return $html;
		}
	}

	public function getContent()
	{
		global $cookie;

		$currency = '';
		$content_html = '';
		$amount = (Tools::getValue('amount')) ? (float)strtr(Tools::getValue('amount'), ',', '.') : 10;
		$validity = (Tools::getValue('validity')) ? (int)Tools::getValue('validity') : 12;
		$array_gifts = array();
		$products_tab = array();

		if (Tools::getIsset($cookie->id_lang) && $cookie->id_lang)
			$products_tab = Product::getProducts((int)$cookie->id_lang, 0, 0, 'name', 'ASC');
		else
			$products_tab = Product::getProducts((int)Configuration::get('PS_LANG_DEFAULT'), 0, 0, 'name', 'ASC');

		if (version_compare(_PS_VERSION_, '1.5', '>'))
			$currency = $this->context->currency;
		else
			$currency = Currency::getCurrent();

		if (Tools::getValue('submitGiftVoucher'))
			$content_html .= $this->addGiftVoucher();
		if (Tools::getValue('deletegiftvoucher'))
			$content_html .= $this->deleteGiftVoucher();

		$content_html .= '<h2>'.$this->displayName.'</h2>
		<p>
			<a href="#" id="showgiftvoucher"><img border="0" src="../img/admin/add.gif">&nbsp;Nouveau</a>
		</p>';
		$token_mod = '&token='.Tools::getAdminTokenLite('AdminModules');
		$tab_mod = '&tab_module=pricing_promotion&module_name=prestagiftvouchers';
		$content_html .= '<div class="clear">&nbsp;</div>
		<form id="newgiftvoucher" action="'.Tools::htmlentitiesutf8('index.php?tab=AdminModules&configure=prestagiftvouchers'.$token_mod.$tab_mod).'" method="post" >
			<fieldset class="width6">
				<legend>'.$this->l('Add a new gift voucher').'</legend>
				<label>'.$this->l('Is this gift voucher is associated to an existing product ?').'</label>
				<div class="margin-form">
					<input type="radio" name="has_associated_product" id="has_associated_product_yes" value="1" />
					<label class="t" for="has_associated_product_yes">
						<img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" />
					</label>
					<input type="radio" name="has_associated_product" id="has_associated_product_no" value="0" checked="checked" />
					<label class="t" for="has_associated_product_no">
						<img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" />
					</label>
					<p>'.$this->l('If this gift voucher is not associated to an existing product, a new product will automatically be created').'.</p>
					<div class="clear"></div>
					<div id="div_assoc_prod" style="display:none;">
						<select name="associated_product">';
						foreach ($products_tab as $product_tab)
							$content_html .= '<option value="'.$product_tab['id_product'].'">'.$product_tab['name'].'</option>';
						unset($products_tab, $product_tab);
		$content_html .= '</select>
						<p>'.$this->l('Please select the associated product').'.</p>
					</div>
				</div>
				<div class="clear"></div>
				<label>'.$this->l('Amount of the gift voucher').'</label>
				<div class="margin-form">
					<input type="text" name="amount" id="amount" value="'.(($amount) ? $amount : '10').'" />&nbsp;'.$currency->sign.$this->l(' (Excl. Tax)').
					'<p>'.$this->l('The amount of the gift voucher defines the amount used for voucher generation').'.</p>
				</div>
				<div class="clear"></div>
				<label>'.$this->l('Is this gift voucher is cumulable with price reductions ?').'</label>
				<div class="margin-form">
					<input type="radio" name="is_cumulable" id="is_cumulable_yes" value="1" checked="checked" />
					<label class="t" for="is_cumulable_yes">
						<img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" />
					</label>
					<input type="radio" name="is_cumulable" id="is_cumulable_no" value="0" />
					<label class="t" for="is_cumulable_no">
						<img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" />
					</label>
					<p>'.$this->l('If this gift voucher is cumulable with price reductions, users will be authorized to use this voucher on "on sale" products').'.</p>
				</div>
				<div class="clear"></div>
				<label>'.$this->l('Period of validity').'</label>
				<div class="margin-form">
					<input type="text" name="validity" id="validity" value="'.(($validity) ? $validity : '12').'" />&nbsp;'.$this->l('month(s)').
					'<p>'.$this->l('The period of validity is the number of months used for voucher expiration').'.</p>
				</div>
				<div class="clear"></div>
				<br /><center><input type="submit" name="submitGiftVoucher" value="'.$this->l('Add').'" class="button" /></center>
			</fieldset>
			<div class="clear">&nbsp;</div>
		</form>';

		$array_gifts = $this->getGiftVouchers();
		// If there's gift vouchers in DB
		if (!empty($array_gifts))
		{
			$content_html .= '<table cellspacing="0" cellpadding="0" class="table">
				<thead>
					<tr class="nodrag nodrop">
						<th>'.$this->l('ID Gift voucher').'</th>
						<th>'.$this->l('Associated product').'</th>
						<th>'.$this->l('Amount').'</th>
						<th>'.$this->l('Is cumulable ?').'</th>
						<th>'.$this->l('Validity time').'</th>
					</tr>
				</thead>
				<tbody>';
			foreach ($array_gifts as $key => $value)
			{
				$product = new Product((int)$value['id_product']);
				$content_html .= '<tr><td>'.$value['id_giftvoucher'].'</td>';
				$token_mod = '&token='.Tools::getAdminTokenLite('AdminModules');
				$token_prod = '&token='.Tools::getAdminTokenLite('AdminProducts');
				if (version_compare(_PS_VERSION_, '1.5', '>'))
					$content_html .= '<td>'.(($product->name[(int)$cookie->id_lang]) ? $product->name[(int)$cookie->id_lang] : '').'&nbsp;
					<a href="index.php?tab=AdminProducts&id_product='.(int)$value['id_product'].'&updateproduct'.$token_prod.'">
						<img title="'.$this->l('Modify').'" alt="" src="../img/admin/edit.gif">
					</a>';
				else
					$content_html .= '<td>'.(($product->name[(int)$cookie->id_lang]) ? $product->name[(int)$cookie->id_lang] : '').'&nbsp;
					<a href="index.php?tab=AdminCatalog&id_product='.(int)$value['id_product'].'&updateproduct'.$token_prod.'">
						<img title="'.$this->l('Modify').'" alt="" src="../img/admin/edit.gif">
					</a>';

				$content_html .= '<a href="'.$product->getLink().'" target="_blank">
						<img title="'.$this->l('See').'" alt="'.$this->l('See').'" src="../img/admin/details.gif">
					</a>
					<a href="'.Tools::htmlentitiesutf8('index.php?tab=AdminModules&configure=prestagiftvouchers'.$token_mod).'&deletegiftvoucher='.(int)$value['id_giftvoucher'].'">
						<img title="'.$this->l('Delete').'" alt="'.$this->l('Delete').'" src="../img/admin/delete.gif">
					</a></td>
					<td>'.$value['amount'].'&nbsp;'.$currency->sign.'</td>
					<td>'.(($value['cumulable']) ? $this->l('Yes') : $this->l('No')).'</td>
					<td>'.$value['validity'].'&nbsp;'.$this->l('months').'</td>
				</tr>';
			}
			unset($array_gifts, $key, $value);

			$content_html .= '</tbody>
			</table><div class="clear">&nbsp;</div>';
		}

		$content_html .= '<fieldset>
			<legend>Addons</legend>
			<b>'.$this->l('Thank you for choosing a module developed by the Addons Team of PrestaShop.').'</a></b><br /><br />
			'.$this->l('If you encounter a problem using the module, our team is at your service via the ').'<a href="http://addons.prestashop.com/contact-form.php">'.$this->l('contact form').'</a>.

		</fieldset>
		<script type="text/javascript">
			$(document).ready(function(){
				// At the page loading
				if ($("input[name=\'has_associated_product\']:checked").val() == 1)
					$("#div_assoc_prod").show();
				else
					$("#div_assoc_prod").hide();
				$("#showgiftvoucher").click(function(){
					$("#newgiftvoucher").toggle();
				});
				$("input[name=\'has_associated_product\']").change(function(){
					if ($("input[name=\'has_associated_product\']:checked").val() == 1)
						$("#div_assoc_prod").show();
					else
						$("#div_assoc_prod").hide();
				});
			});
		</script>';

		return $content_html;
	}

	public function hookUpdateOrderStatus($params)
	{
		if (!Validate::isLoadedObject($params['newOrderStatus']))
			return false;
		$status = $params['newOrderStatus'];
		$order = new Order((int)$params['id_order']);
		if (!$order || !Validate::isLoadedObject($order))
			return false;

		$customer = new Customer($order->id_customer);
		$currency = new Currency($order->id_currency);
		$discount_id = 0;

		if ($order->valid == 0 && $status->logable == 1)
		{
			$productsgiftvouchers = $this->getGiftVouchers();
			if (!empty($productsgiftvouchers))
			{
				foreach ($order->getProducts() as $product)
				{
					foreach ($productsgiftvouchers as $productgiftvouchers)
					{
						if ($product['product_id'] == $productgiftvouchers['id_product'])
						{
							for ($i = 0; $i < $product['product_quantity']; $i++)
							{
								$discount_id = (int)$this->registerDiscount($productgiftvouchers['id_giftvoucher'], $currency->id);
								if ($discount_id !== 0)
								{
									if (version_compare(_PS_VERSION_, '1.5', '>'))
									{
										$discount = new CartRule($discount_id);
										$data = array(
											'{customer_firstname}' => $customer->firstname,
											'{customer_lastname}' => $customer->lastname,
											'{giftvoucher_code}' => $discount->code,
											'{giftvoucher_value}' => $productgiftvouchers['amount'],
											'{giftvoucher_currency}' => $currency->sign
										);
									}
									else
									{
										$discount = new Discount($discount_id);
										$data = array(
											'{customer_firstname}' => $customer->firstname,
											'{customer_lastname}' => $customer->lastname,
											'{giftvoucher_code}' => $discount->name,
											'{giftvoucher_value}' => $productgiftvouchers['amount'],
											'{giftvoucher_currency}' => $currency->sign
										);
									}
									Mail::Send((int)$order->id_lang, 'giftvouchers-thanks', $this->l('Thanks !'), $data, $customer->email, $customer->firstname.' '.$customer->lastname, Configuration::get('PS_SHOP_EMAIL'), Configuration::get('PS_SHOP_NAME'), null, null, dirname(__FILE__).'/mails/');
								}
							}
						}
					}
				}
				unset($product);
			}
			return true;
		}
		return false;
	}

	private function registerDiscount($id_giftvoucher, $id_currency)
	{
		$infos_voucher = $this->getGiftVoucher((int)$id_giftvoucher);
		$languages = Language::getLanguages();
		$name = 'GV'.Tools::passwdGen(8);

		$default_currency = Currency::getDefaultCurrency();

		if (version_compare(_PS_VERSION_, '1.5', '>'))
		{
			$discount = new CartRule();
			foreach ($languages as $language)
				$discount->name[$language['id_lang']] = $name;
			unset($languages, $language);
			$discount->description = $name;
			$discount->code = 'GV'.Tools::passwdGen(16);
			$discount->minimum_amount_currency = $default_currency->id;
			$discount->minimum_amount = 0;
			$discount->reduction_currency = $default_currency->id;
			$discount->reduction_amount = (float)$infos_voucher[0]['amount'];
			/*Modificaiton  prix en ttc*/
			$discount->reduction_tax = 1;
/*			var_dump($discount);
			die();*/
			$discount->highlight = 0;
		}
		else
		{
			$discount = new Discount();
			$discount->id_discount_type = 2;
			$discount->name = $name;
			foreach ($languages as $language)
				$discount->description[$language['id_lang']] = $name;
			unset($languages, $language);
			$discount->value = (float)$infos_voucher[0]['amount'];
			$discount->cart_display = 1;
			$discount->id_currency = (int)$id_currency;
			$discount->cumulable = (int)$infos_voucher[0]['cumulable'];
			$discount->cumulable_reduction = (int)$infos_voucher[0]['cumulable'];
			$discount->minimal = 0;
		}

		$discount->quantity = 1;
		$discount->quantity_per_user = 1;
		$discount->id_customer = null;
		$discount->active = 1;
		$discount->date_from = date('Y-m-d H:i:s', strtotime('yesterday 00:00'));
		$discount->date_to = date('Y-m-d H:i:s', mktime(date('H') + 48, date('i') + 60, date('s') + 60, date('m') + (int)$infos_voucher[0]['validity'], date('d'), date('Y')));

		if ($discount->add())
			return $discount->id;
		return 0;
	}
}