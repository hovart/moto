<?php
/*
* 2012 Michel Dumont | Graphart créations 
*
*  @author Michel Dumont <md@graphart.fr>
*  @copyright  2012 - 2013
*  @version  2.2
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.5.x
*
*/

if (!defined('_CAN_LOAD_FILES_')) exit;

class mdg_hfpModule extends Module
{
	protected $sql_1 = 'mdg_hfp';
	protected $sql_2 = 'mdg_hfplang';
	protected $area;

	public $fields_value = array('id_area','id_shop','active','position','type','shuffle','nb','hook','id_category','ids_products','names_products','restrict_type','rId_category','rIds_products','rNames_products');
	public $fields_value_lang = array('title');

	public function __construct()
	{
		$this->name = 'mdg_hfp';
		$this->tab = 'front_office_features';
		$this->version = '2.3';
		$this->author = 'Michel DUMONT';
		$this->module_key = 'e40a600bdba6b085c59a19f73c50867e';
		
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
		
		parent::__construct();

		$this->displayName = '(mdg) '.$this->l('Blocs featured products');
		$this->description = $this->l('Display a lot of features products blocs on your homepage');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

/* =============================================================== //
	INSTALL
/* =============================================================== */
	public function install()
	{
		if (Shop::isFeatureActive())
		Shop::setContext(Shop::CONTEXT_ALL);
	
		if (!parent::install()
			|| !$this->registerHook('displayCategoryMDG')
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('displayLeftColumn')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displayShoppingCartFooter'))
				return false;

		if (!Db::getInstance()->Execute('
			CREATE TABLE `'._DB_PREFIX_.$this->sql_1.'` (
			`id_area` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL,
			`active` int(10) NOT NULL, 
			`position` int(10) NOT NULL, 
			`type` int(10) NOT NULL, 
			`restrict_type` int(10) NOT NULL, 
			`shuffle` int(10) NOT NULL, 
			`nb` int(10) NOT NULL, 
			`hook` varchar(255) NOT NULL,
			`id_category` text NOT NULL,
			`ids_products` text NOT NULL,
			`names_products` text NOT NULL,
			`rId_category` text NOT NULL,
			`rIds_products` text NOT NULL,
			`rNames_products` text NOT NULL,
			PRIMARY KEY (`id_area`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
			)
			OR 
			!Db::getInstance()->Execute('
			CREATE TABLE `'._DB_PREFIX_.$this->sql_2.'` (
			`id_lang` int(10) unsigned NOT NULL, 
			`id_area` int(10) unsigned NOT NULL,
			`title` varchar(255) NOT NULL,
			PRIMARY KEY (`id_lang`,`id_area`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
			)
		)
			return false;

		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall() 
			|| !Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.$this->sql_1.'`')
			|| !Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.$this->sql_2.'`'))
				return false;
	
		return true;
	}
/* =============================================================== //
	BO
/* =============================================================== */
	public function getValues($id_area=null, $id_lang=5)
	{
		$tmp_values = array();
		if(!$id_area)
		{
			foreach($this->fields_value as $v)
				$tmp_values[$v] = '';
			foreach($this->fields_value_lang as $v)
				$tmp_values[$v] = '';
			return $tmp_values;
		}

		$sql_1 = Db::getInstance()->executeS(
			'SELECT *
			FROM `'._DB_PREFIX_.$this->sql_1.'`
			WHERE id_area='.$id_area
		);
		$sql_2 = Db::getInstance()->executeS(
			'SELECT title, id_lang
			FROM `'._DB_PREFIX_.$this->sql_2.'`
			WHERE id_area='.$id_area
		);
		
		if(!count($sql_1) || !count($sql_2))
			return $tmp_values;
		
		foreach($sql_2 as $v)
			$sql_1[0]['title'][$v['id_lang']] = $v['title'];

		return $sql_1[0];
	}

/* =============================================================== //
	PROCESS
/* =============================================================== */
	public function process()
	{
		$output = null;
		
		return $output;
	}
/* =============================================================== //
	BO
/* =============================================================== */
	public function getContent()
	{
		require_once '/home/ddauteuil/www/mgd/modules/mdg_hfp'.'/classes/AdmMdgForm.php';

		$this->context->controller->addCSS($this->_path.'views/css/admForm.css','all');
		
		$this->context->controller->addjqueryPlugin('autocomplete');
		$this->context->controller->addJS($this->_path.'views/js/doAutoComplete.js');
		$this->context->controller->addJS($this->_path.'views/js/doToolsBar.js');
//		$this->context->controller->addJS($this->_path.'js/admForm.js');

		$output = null;
			
		if (Tools::isSubmit('submit'.$this->name))
		{
			$titles = Tools::getValue('title');

			if(empty($titles[(int)(Configuration::get('PS_LANG_DEFAULT'))]))
				$output .= $this->displayError($this->l('Default language title must be filled'));
			else
			{
				$values = Tools::getValue('setting');
				foreach($values as $k=>$v)
					if(!is_numeric($v)) $values[$k] = pSql($v);

				if($values['id_area'] != '')
				{
					$id_area = $values['id_area'];
					Db::getInstance()->update($this->sql_1, $values, 'id_area='.$id_area);
					foreach($titles as $k=>$v)
						Db::getInstance()->update($this->sql_2, array('title'=>pSql($v)), 'id_lang='.$k.' AND id_area='.$id_area);
				}
				else
				{
					Db::getInstance()->insert($this->sql_1, $values);
					$id_area = Db::getInstance()->Insert_ID();
					foreach($titles as $k=>$v)
						Db::getInstance()->insert($this->sql_2, array('id_lang'=>$k,'id_area'=>$id_area,'title'=>$v));
				}

				$output .= $this->displayConfirmation($this->l('Treatment completed'));
			}
		}
		elseif(Tools::isSubmit('deleteArea'))
		{
			$id_area = Tools::getValue('id_area');
			Db::getInstance()->delete($this->sql_1, 'id_area = '.$id_area);
			Db::getInstance()->delete($this->sql_2, 'id_area = '.$id_area);
			$output .= $this->displayConfirmation($this->l('Treatment completed'));
		}
		return $output . $this->displayForm();
	}
	
	public function displayForm()
	{
		$mdgForm = new AdmMdgForm();

		/* Select category values */
		$shop = Context::getContext()->shop;
		$id_shop = (int)$shop->id;
		$id_lang = (int)Context::getContext()->language->id;
		$categories = Category::getCategories($id_lang, true);


		$id_area = Tools::isSubmit('deleteArea')?0:Tools::getValue('id_area',0);
		$fields_values = $this->getValues($id_area);
		
		$config_url = 'index.php?controller=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules');
		
		/* Tool barre */
		$output ='
			<div class="toolbar-placeholder">
				<div class="toolbarBox toolbarHead">
					<ul class="cc_button">
		';
		if($id_area!=0)
			$output .='
						<li>
							<a id="desc--save" class="toolbar_btn" href="'.$config_url.'&submit'.$this->name.'" title="'.$this->l('Edit').'" >
								<span class="process-icon-form process-icon-edit" ></span>
								<div>'.$this->l('Edit').'</div>
							</a>
						</li>
						<li>
							<a id="desc--new" class="toolbar_btn" href="'.$config_url.'" title="'.$this->l('Add new').'">
								<span class="process-icon-new" ></span>
								<div>'.$this->l('Add new').'</div>
							</a>
						</li>
			';
		else
			$output .='
						<li>
							<a id="desc--save" class="toolbar_btn" href="'.$config_url.'&submit'.$this->name.'" title="'.$this->l('Save').'" >
								<span class="process-icon-form process-icon-save" ></span>
								<div>'.$this->l('Save').'</div>
							</a>
						</li>
			';
		$output .='
						<li>
							<a id="desc--back" class="toolbar_btn" href="index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules').'" title="'.$this->l('Back to list').'" >
								<span class="process-icon-back " ></span>
								<div>'.$this->l('Back to list').'</div>
							</a>
						</li>
					</ul>
					<div class="pageTitle">
					<h3>
						<span id="current_obj" style="font-weight: normal;">
							<img src="../modules/'.$this->name.'/logo.png"> 
							<span class="breadcrumb item-0 ">'.$this->displayName.'</span>
						</span>
					</h3>
					</div>
				</div>
			</div>
			<div class="leadin"></div>
		';

		$output .='
			<script type="text/javascript">
				function displayHookInfos(){$(".hook").hide();$("#hook_"+$("#hook").val()).show();}
				function displayCatContent(){var v = ($("#type").val()!=2) ? 1 : 2;$(".type").hide();$("#type_"+v).show();}
				function displayRestrictedContent(){$(".restrict_type").hide();$(".restrict_type_"+$("#restrict_type").val()).show();}
				$(function(){
					displayHookInfos();
					displayCatContent();
					displayRestrictedContent();
					$("#hook").change(displayHookInfos);
					$("#type").change(displayCatContent);
					$("#restrict_type").change(displayRestrictedContent);
				});
			</script>
		';

		/* Entete du formulaire */
		$output .= '
			<form id="_form" class="mdg-content" method="post" action="'.$config_url.'" enctype="multipart/form-data">
            <fieldset>
		';		 

		/* Titre / hook / active / shuffle / position / nb */
		$output .='<h4>'.$this->l('General settings').'</h4>';		 
		$output .= $mdgForm->addField(array('type'=>'text','lang'=>true,'label'=>$this->l('Block title'),'names_lang_array'=>$this->fields_value_lang,'name'=>'title','value'=>$fields_values['title'],'required'=>true ));
		$output .= $mdgForm->addField(array('type'=>'select','label'=>$this->l('Display on'),'name'=>'setting[hook]','id'=>'hook','value'=>$fields_values['hook'],
					'options' => array(
						array('label'=>$this->l('displayHome'), 'value'=>'displayHome'),
						array('label'=>$this->l('displayLeftColumn'), 'value'=>'displayLeftColumn'),
						array('label'=>$this->l('displayRightColumn'), 'value'=>'displayRightColumn'),
						array('label'=>$this->l('displayFooterProduct'), 'value'=>'displayFooterProduct'),
						array('label'=>$this->l('displayCategoryMDG (not native)'), 'value'=>'displayCategoryMDG'),
						array('label'=>$this->l('displayShoppingCartFooter'), 'value'=>'displayShoppingCartFooter'),
					),
				));
		$output .= $mdgForm->addField(array('type'=>'warning','class'=>'hook','id'=>'hook_displayCategoryMDG', 'value'=>'<p>'.$this->l('To use this hook you must add "<b>{$HOOK_DISPLAYCATEGORYMDG}</b>" on your theme').'</p>'));
		$output .= $mdgForm->addField(array('type'=>'onoff','label'=>$this->l('Shuffle products'),'name'=>'setting[shuffle]','value'=> $fields_values['shuffle'] ));
		$output .= $mdgForm->addField(array('type'=>'onoff','label'=>$this->l('Enable / Disable'),'name'=>'setting[active]','value'=> $fields_values['active'] ));
		$output .= $mdgForm->addField(array('type'=>'text','label'=>$this->l('Display position'),'name'=>'setting[position]','value'=> ($fields_values['position']?$fields_values['position']:1),'desc'=>$this->l('Can manage the display order of blocks.'),'size'=>1 ));
		$output .= $mdgForm->addField(array('type'=>'text','label'=>$this->l('Number of products'),'name'=>'setting[nb]','value'=> ($fields_values['nb']?$fields_values['nb']:4),'desc'=>$this->l('Maximum number of products to display.'),'size'=>1 ));

	 
		/* type */
		$output .= '<hr /><br />';
		$output .= '<h4>'.$this->l('Content of block').'</h4>';
		$output .= $mdgForm->addField(array('type'=>'select','label'=>$this->l('Display on'),'name'=>'setting[type]','id'=>'type','value'=>$fields_values['type'],
					'options' => array(
						array('label'=>$this->l('products from an unique category'), 'value'=>1),
						array('label'=>$this->l('your own products list'), 'value'=>2),
						array('label'=>$this->l('new'), 'value'=>3),
						array('label'=>$this->l('best sellers'), 'value'=>4),
						array('label'=>$this->l('specials'), 'value'=>5),
					),
				));
		/* id_category / ids_products / names_products */
		$output .='<div id="type_1" class="type" style="display:none">';
		$output .= $mdgForm->addField(array('type'=>'select','label'=>$this->l('Select a category'),'name'=>'setting[id_category]',
				'options_html' => 
					'<option value="0">'.$this->l('Entire site').'</option>'.
					self::recurseCategory($categories, $categories[1][(int)Context::getContext()->shop->id_category], (int)Context::getContext()->shop->id_category, $fields_values['id_category']),
			));
		$output .='</div>';
		$output .='<div id="type_2" class="type" style="display:none">';
		$output .= $mdgForm->addField(array(
			'type'=>'autocomplete', 'label'=>$this->l('Choose your products'),'name'=>'setting[ids_products]','name_2'=>'setting[names_products]','id'=>'ids_products',
			'value'=>$fields_values['ids_products'], 'value_2'=>$fields_values['names_products'])
		);
		$output .='</div>';
		
		
		/* restrict_type */
		$output .= '<hr /><br />';
		$output .= '<h4>'.$this->l('Restrictions').'</h4>';
		$output .= $mdgForm->addField(array('type'=>'select','label'=>$this->l('Restrict display'),'name'=>'setting[restrict_type]','id'=>'restrict_type','value'=>$fields_values['restrict_type'],
					'options' => array(
						array('label'=>$this->l('No display restriction'), 'value'=>0),
						array('label'=>$this->l('Display only on the category...'), 'value'=>1),
						array('label'=>$this->l('Display only on one category and her subcategories ...'), 'value'=>2),
						array('label'=>$this->l('Display only on products...'), 'value'=>3),
					),
				));
		/* rId_category / rIds_products / rNames_products */
		$output .='<div class="restrict_type restrict_type_1 restrict_type_2" style="display:none">';
		$output .= $mdgForm->addField(array('type'=>'select','label'=>$this->l('Select a category'),'name'=>'setting[rId_category]',
				'options_html' => self::recurseCategory($categories, $categories[1][(int)Context::getContext()->shop->id_category], (int)Context::getContext()->shop->id_category, $fields_values['rId_category'])
			));
		$output .='</div>';
		$output .='<div class="restrict_type restrict_type_3" style="display:none">';
		$output .= $mdgForm->addField(array(
			'type'=>'autocomplete', 'label'=>$this->l('Choose your products'),'name'=>'setting[rIds_products]','name_2'=>'setting[rNames_products]','id'=>'rIds_products',
			'value'=>$fields_values['rIds_products'], 'value_2'=>$fields_values['rNames_products'])
		);
		$output .='</div>';



		$output .= '
				<div class="margin-form">
					<input type="hidden" name="setting[id_area]" value="'.$fields_values['id_area'].'" />
					<input type="hidden" name="setting[id_shop]" value="'.$id_shop.'" />
					<input id="_form_submit_btn" class="button" type="submit" name="submit'.$this->name.'" value="'.($id_area!=0?$this->l('Edit'):$this->l('Save')).'">
				</div>
			</fieldset>
			</form>
		';
		
		/* Listing des block */
		$output .= '
			<form method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>'.$this->l('Your products block for this shop').'</legend>
				
					<table id="1" class="table space tableDnD" cellspacing="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<th width="20">id</th>
							<th width="20"></th>
							<th width="200">'.$this->l('Hook').'</th>
							<th>'.$this->l('Title').'</th>
							<th width="60"></th>
						</tr>
					</thead>
					<tbody>
		';
		$list = Db::getInstance()->ExecuteS(
			'SELECT a.id_area, a.active, a.hook, al.title
			FROM `'._DB_PREFIX_.$this->sql_1.'` a
			LEFT JOIN `'._DB_PREFIX_.$this->sql_2.'` al ON (al.id_area = a.id_area AND al.id_lang = '.$id_lang.')
			WHERE a.id_shop = '.$id_shop.' 
			ORDER BY a.position' 
		);
		if(count($list))
			foreach($list as $row)
				$output .= '
					<tr>
						<td>'.$row['id_area'].'</td>
						<td><img src="../img/admin/'.($row['active']?'enabled':'disabled').'.gif" /></td>
						<td>'.$this->l($row['hook']).'</td>
						<td>'.$row['title'].'</td>
						<td>
							<a href="'.$config_url.'&id_area='.$row['id_area'].'"><img src="../img/admin/edit.gif" alt="Editer" title="Editer" border="0"></a>
							<a onclick="return confirm(\''.$this->l('Do your really want to delete this block?').'\');" href="'.$config_url.'&id_area='.$row['id_area'].'&deleteArea"><img src="../img/admin/delete.gif" alt="Supprimer" title="Supprimer" border="0"></a>
						</td>
					</tr>';
		else
				$output .= '<tr><td class="warning" colspan="4">'.$this->l('No block featured products for moment').'</td></tr>';
			
		
		$output .= '
					</tbody>
					</table>
					<div class="clear pspace"></div>
				</fieldset>
			</form><div class="clear">&nbsp;</div>
		';
		
		return $output;
	}
	public static function recurseCategory($categories, $current, $id_category = 1, $id_selected = 1)
	{
		$output = '<option value="'.$id_category.'"'.(($id_selected == $id_category) ? ' selected="selected"' : '').'>'.
		str_repeat('&nbsp;', ($current['infos']['level_depth']-1) * 5).stripslashes($current['infos']['name']).'</option>';
		if (isset($categories[$id_category]))
			foreach (array_keys($categories[$id_category]) as $key)
				$output .= self::recurseCategory($categories, $categories[$id_category][$key], $key, $id_selected);
		return $output;
	}



/* =============================================================== //
	TOOLS
/* =============================================================== */
	public function _htmlFieldWithLang($id_lang,$labels_array,$label,$content=NULL,$type=NULL,$required=false) {
		$languages = Language::getLanguages(false);
		
		$labels = '';
		foreach($labels_array as $v)
			$labels .= $v.'¤';
		$labels = trim($labels,'¤');		
		
		$_html='';
		foreach ($languages as $lg)
			if($type!=2)
				$_html .= '
					<div id="'.$label.'_'.$lg['id_lang'].'" style="display: '.($lg['id_lang'] == $id_lang ? 'block' : 'none').';float: left;">
						<input type="text" name="'.$label.'['.$lg['id_lang'].']" id="'.$label.'_'.$lg['id_lang'].'" value="'.($content?$content[$lg['id_lang']]:'').'" />
						'.($required?'<sup> *</sup>':'').'
					</div>';
			else
				$_html .= '
					<div id="'.$label.'_'.$lg['id_lang'].'" style="display: '.($lg['id_lang'] == $id_lang ? 'block' : 'none').';float: left;">
						<textarea name="'.$label.'['.$lg['id_lang'].']">'.($content?$content[$lg['id_lang']][$label]:'').'</textarea>
						'.($required?'<sup> *</sup>':'').'
					</div>';
		$_html .= $this->displayFlags($languages,$id_lang,$labels,$label,true);
		$_html .='<div class="clear"></div>';
		return $_html;
	}
/* =============================================================== //
	FO - VALUES
/* =============================================================== */
	public function getAreaS($hook='displayHome')
	{
		$id_lang = (int)$this->context->cookie->id_lang;
		$id_shop = (int)$this->context->shop->id;
		
		$areaS = Db::getInstance()->ExecuteS(
			'SELECT a.*, al.title
			FROM `'._DB_PREFIX_.$this->sql_1.'` a
			LEFT JOIN `'._DB_PREFIX_.$this->sql_2.'` al ON (al.id_area = a.id_area AND al.id_lang = '.$id_lang.')
			WHERE a.id_shop = '.$id_shop.' AND a.active = 1 AND a.hook = "'.$hook.'"
			ORDER BY a.position' 
		);
		if(!count($areaS))
			return array();
		foreach($areaS as $k=>$area)
			if($area['restrict_type']==1 && (!Tools::getValue('id_category') || Tools::getValue('id_category')!=$area['rId_category']) )
				unset($areaS[$k]);
			elseif( $area['restrict_type']==2 
					&& Tools::getValue('id_category')!=$area['rId_category']
					&& !Db::getInstance()->getValue('SELECT count(*) FROM `'._DB_PREFIX_.'category` WHERE `id_parent`='.(int)$area['rId_category'].' AND `id_category`='.(int)Tools::getValue('id_category'))
			)
				unset($areaS[$k]);
			elseif($area['restrict_type']==3 && ( !Tools::getValue('id_product') || !in_array(Tools::getValue('id_product'),explode(',',$area['rIds_products'])) ) )
				unset($areaS[$k]);
			else
				$areaS[$k]['products'] = $this->getArea($id_lang, $area);

		return $areaS;
	}
	public function getArea($id_lang, $area)
	{
		if($area['type']!=2)
		{
			if(!$area['id_category'])
			{
				if($area['type']==1) return $this->getProducts($id_lang, 0, 0, (int)$area['nb'], 'position');
				if($area['type']==3) return Product::getNewProducts($id_lang, 0, (int)$area['nb']);
				if($area['type']==4) return ProductSale::getBestSales($id_lang, 0, (int)$area['nb']);
				if($area['type']==5) return Product::getPricesDrop($id_lang, 0, (int)$area['nb']);
			}
			$category = new Category($area['id_category'], $id_lang);
			if (!$category->active || !$category->checkAccess($this->context->customer->id))
				return array();
			if($area['type']==1) $order_by = 'position';
			elseif($area['type']==3) $order_by = 'new';
			elseif($area['type']==4) $order_by = 'sales';
			elseif($area['type']==5) $order_by = 'pricedrop';
			return $this->getProducts($id_lang, $area['id_category'], 1, (int)$area['nb'], $order_by, (boolean)($area['shuffle']), (int)$area['nb'] );
		}
		
		$tmp_products = explode(',',trim($area['ids_products'],','));
		$products = array();
		if(!count($tmp_products))
			return array();
		
		if($area['shuffle'])
			shuffle($tmp_products);
		$tmp_products = array_slice($tmp_products,0,$area['nb']);
		
		foreach($tmp_products as $id_product)
			if($id_product!='') $products[] = $this->getProduct($id_lang,$id_product);
		
		return $products;
	}
	
	public function getProduct($id_lang, $id_product)
	{
		$context = Context::getContext();

		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`id_product_attribute`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image`,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'product` p 
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
				AND p.`id_product` = '.(int)$id_product.'
				AND (pa.id_product_attribute IS NULL OR product_attribute_shop.id_shop='.(int)$context->shop->id.') 
				AND (i.id_image IS NULL OR image_shop.id_shop='.(int)$context->shop->id.')
				AND product_shop.`active` = 1
				AND product_shop.`visibility` IN ("both", "catalog")';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		$accessCategory = new Category($result[0]['id_category_default'],$id_lang);
		if (!$accessCategory->checkAccess($context->customer->id))
			return array();

		$result = Product::getProductsProperties($id_lang, $result);
		return $result[0];
	}
	
	public function getProducts($id_lang, $id_category, $p, $n, $order_by, $random = false, $random_number_products = 1)
	{
		$context = Context::getContext();

		$pricedrop = false;
		$newOnly = false;
		$newDays = (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20);
		
		$p = ($p < 1) ? 1 : $p;
		$order_way = 'ASC';

		if ($order_by == 'new')
		{
			$order_by_prefix = 'cp';
			$order_by = 'position';
			$newOnly = true;
		}
		elseif ($order_by == 'sales')
			$order_way = 'DESC';
		elseif ($order_by == 'position')
			$order_by_prefix = 'cp';
		elseif($order_by == 'pricedrop')
		{
			$id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
			$ids = Address::getCountryAndState($id_address);
			$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));

			$product_reductions = SpecificPrice::getProductIdByDate($context->shop->id,	$context->currency->id,	$id_country, $context->customer->id_default_group, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 0, true);
			$ids_product = ' AND (';
			if(count($product_reductions))
				foreach ($product_reductions as $product_reduction)
					$ids_product .= '( product_shop.`id_product` = '.(int)$product_reduction['id_product'].($product_reduction['id_product_attribute'] ? ' AND product_attribute_shop.`id_product_attribute`='.(int)$product_reduction['id_product_attribute'] :'').') OR';

			$ids_product = rtrim($ids_product, 'OR').')';
			
			$order_by_prefix = 'cp';
			$order_by = 'position';
			$pricedrop = true;
		}
		

		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());

		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					ps.`quantity` AS sales,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),INTERVAL '.$newDays.'	DAY)) > 0 AS new, 
					product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
				LEFT JOIN `'._DB_PREFIX_.'product_sale` ps ON (p.`id_product` = ps.`id_product`)
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
					AND product_shop.`active` = 1
					AND product_shop.`visibility` IN ("both", "catalog")'
					.($id_category ? ' AND cp.`id_category` = '.(int)$id_category : '')
					.($pricedrop ? $ids_product : '')
					.($newOnly ? ' AND DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),INTERVAL '.$newDays.'	DAY)) > 0':'')
					.' GROUP BY product_shop.id_product';

		if ($random === true)
			$sql .= ' ORDER BY RAND() LIMIT 0, '.(int)$random_number_products;
		else
			$sql .= ' ORDER BY '.(isset($order_by_prefix) ? $order_by_prefix.'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way).' LIMIT '.(((int)$p - 1) * (int)$n).','.(int)$n;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if ($order_by == 'orderprice')
			Tools::orderbyPrice($result, $order_way);

		if (!$result)
			return array();

		/* Modify SQL result */
		return Product::getProductsProperties($id_lang, $result);
	}

/* =============================================================== //
	HOOKS
/* =============================================================== */

	public function hookdisplayHeader($params)
	{ 
		$this->context->controller->addCSS($this->_path.'css/'.$this->name.'.css', 'all');
		if(Tools::isSubmit('id_category'))
			$this->context->smarty->assign(array(
				'HOOK_DISPLAYCATEGORYMDG' => Hook::exec('displayCategoryMDG'),
			));
	}

	public function hookdisplayHome($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS(),
			'hook' => 'home'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayCategoryMDG()
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS('displayCategoryMDG'),
			'hook' => 'categoryProducts'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayFooterProduct($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS('displayFooterProduct'),
			'hook' => 'footerProduct'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayShoppingCartFooter($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS('displayShoppingCartFooter'),
			'hook' => 'shoppingCartFooter'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayLeftColumn($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS('displayLeftColumn'),
			'hook' => 'columns'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayRightColumn($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize(Image::getSize('home')?'home':'home_default'),
			'areaS' => $this->getAreaS('displayRightColumn'),
			'hook' => 'columns'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
}