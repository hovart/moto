<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnProdCarousel extends Module
{
	public function __construct()
	{
		$this->name = 'tptnprodcarousel';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Products Carousel on homepage - Templatin');
		$this->description = $this->l('Displays featured and new products carousel on homepage.');
	}

	public function install()
	{
		if ( (parent::install() == false)
			|| (Configuration::updateValue('FEATURED_PROD', 1) == false)
			|| (Configuration::updateValue('CATEG1', 3) == false)
			|| (Configuration::updateValue('CATEG2', 4) == false)
			|| (Configuration::updateValue('CATEG3', 5) == false)
			|| (Configuration::updateValue('CATEG4', 0) == false)
			|| (Configuration::updateValue('CATEG5', 0) == false)
			|| (Configuration::updateValue('CATEG6', 0) == false)
			|| (Configuration::updateValue('CATEG7', 0) == false)
			|| (Configuration::updateValue('CATEG8', 0) == false)
			|| (Configuration::updateValue('CATEG9', 0) == false)
			|| (Configuration::updateValue('CATEG10', 0) == false)
			|| ($this->registerHook('displayHome') == false) )
				return false;
		return true;
	}
	
	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
							
				<label>'.$this->l('Show Featured Products Carousel').' :</label>
				<div class="margin-form">
					<input type="radio" name="featured_prod" id="featured_prod_on" value="1" '.(Tools::getValue('featured_prod', Configuration::get('FEATURED_PROD')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="featured_prod_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="featured_prod" id="featured_prod_off" value="0" '.(!Tools::getValue('featured_prod', Configuration::get('FEATURED_PROD')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="featured_prod_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				
				<label>'.$this->l('Block-1 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_1" name="categ_1" value="'.Tools::safeOutput(Configuration::get('CATEG1')).'" />
				</div>
				
				<label>'.$this->l('Block-2 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_2" name="categ_2" value="'.Tools::safeOutput(Configuration::get('CATEG2')).'" />
				</div>
				
				<label>'.$this->l('Block-3 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_3" name="categ_3" value="'.Tools::safeOutput(Configuration::get('CATEG3')).'" />
				</div>
				
				<label>'.$this->l('Block-4 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_4" name="categ_4" value="'.Tools::safeOutput(Configuration::get('CATEG4')).'" />
				</div>
				
				<label>'.$this->l('Block-5 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_5" name="categ_5" value="'.Tools::safeOutput(Configuration::get('CATEG5')).'" />
				</div>
				
				<label>'.$this->l('Block-6 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_6" name="categ_6" value="'.Tools::safeOutput(Configuration::get('CATEG6')).'" />
				</div>
				
				<label>'.$this->l('Block-7 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_7" name="categ_7" value="'.Tools::safeOutput(Configuration::get('CATEG7')).'" />
				</div>
				
				<label>'.$this->l('Block-8 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_8" name="categ_8" value="'.Tools::safeOutput(Configuration::get('CATEG8')).'" />
				</div>
				
				<label>'.$this->l('Block-9 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_9" name="categ_9" value="'.Tools::safeOutput(Configuration::get('CATEG9')).'" />
				</div>
				
				<label>'.$this->l('Block-10 Category ID').' :</label>
				<div class="margin-form">
					<input type="text" id="categ_10" name="categ_10" value="'.Tools::safeOutput(Configuration::get('CATEG10')).'" />
				</div>

				<div class="margin-form">
					<input type="submit" name="submitProductCarousel" value="'.$this->l('Save').'" class="button" />
				</div>	
			</fieldset>
		</form>';
		return $output;
	}
	
	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitProductCarousel'))
		{
			Configuration::updateValue('FEATURED_PROD', (int)(Tools::getValue('featured_prod')));
			Configuration::updateValue('CATEG1', (int)(Tools::getValue('categ_1')));
			Configuration::updateValue('CATEG2', (int)(Tools::getValue('categ_2')));
			Configuration::updateValue('CATEG3', (int)(Tools::getValue('categ_3')));
			Configuration::updateValue('CATEG4', (int)(Tools::getValue('categ_4')));
			Configuration::updateValue('CATEG5', (int)(Tools::getValue('categ_5')));
			Configuration::updateValue('CATEG6', (int)(Tools::getValue('categ_6')));
			Configuration::updateValue('CATEG7', (int)(Tools::getValue('categ_7')));
			Configuration::updateValue('CATEG8', (int)(Tools::getValue('categ_8')));
			Configuration::updateValue('CATEG9', (int)(Tools::getValue('categ_9')));
			Configuration::updateValue('CATEG10', (int)(Tools::getValue('categ_10')));
						
			$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
		}
		return $output.$this->displayForm();
	}
	
	public function hookDisplayHome($params)
	{
		
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		$featuredProducts = $category->getProducts((int)Context::getContext()->language->id, 1, 100); /* 100 products max. */
		
		$categ1 = new Category((int)(Configuration::get('CATEG1')));
		$myprods1 = $categ1->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname1 = $categ1->getName($this->context->language->id);
		
		$categ2 = new Category((int)(Configuration::get('CATEG2')));
		$myprods2 = $categ2->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname2 = $categ2->getName($this->context->language->id);
		
		$categ3 = new Category((int)(Configuration::get('CATEG3')));
		$myprods3 = $categ3->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname3 = $categ3->getName($this->context->language->id);
		
		$categ4 = new Category((int)(Configuration::get('CATEG4')));
		$myprods4 = $categ4->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname4 = $categ4->getName($this->context->language->id);
		
		$categ5 = new Category((int)(Configuration::get('CATEG5')));
		$myprods5 = $categ5->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname5 = $categ5->getName($this->context->language->id);
		
		$categ6 = new Category((int)(Configuration::get('CATEG6')));
		$myprods6 = $categ6->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname6 = $categ6->getName($this->context->language->id);
		
		$categ7 = new Category((int)(Configuration::get('CATEG7')));
		$myprods7 = $categ7->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname7 = $categ7->getName($this->context->language->id);
		
		$categ8 = new Category((int)(Configuration::get('CATEG8')));
		$myprods8 = $categ8->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname8 = $categ8->getName($this->context->language->id);
		
		$categ9 = new Category((int)(Configuration::get('CATEG9')));
		$myprods9 = $categ9->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname9 = $categ9->getName($this->context->language->id);
		
		$categ10 = new Category((int)(Configuration::get('CATEG10')));
		$myprods10 = $categ10->getProducts($this->context->language->id, 1, 100); /* 100 products max. */
		$catname10 = $categ10->getName($this->context->language->id);
		
		$this->smarty->assign(array(
			'categname1' => $catname1,
			'myprod1' => $myprods1,
			'categname2' => $catname2,
			'myprod2' => $myprods2,
			'categname3' => $catname3,
			'myprod3' => $myprods3,
			'categname4' => $catname4,
			'myprod4' => $myprods4,
			'categname5' => $catname5,
			'myprod5' => $myprods5,
			'categname6' => $catname6,
			'myprod6' => $myprods6,
			'categname7' => $catname7,
			'myprod7' => $myprods7,
			'categname8' => $catname8,
			'myprod8' => $myprods8,
			'categname9' => $catname9,
			'myprod9' => $myprods9,
			'categname10' => $catname10,
			'myprod10' => $myprods10,
			'featured_products' => $featuredProducts,
			'show_featured_prod' => (int)(Configuration::get('FEATURED_PROD')),
			'homeSize' => Image::getSize(ImageType::getFormatedName('category')),
			'self' => dirname(__FILE__)
		));
		
		return $this->display(__FILE__, 'tptnprodcarousel.tpl');
	}

}
