<?php
if (!defined('_PS_VERSION_'))
	exit;

class CountdownSpecialBlock extends Module
{
	private $_html = '';
	private $_postErrors = array();

    function __construct()
    {
        $this->name = 'countdownspecialblock';
        $this->tab = 'front_office_features';
        $this->version = '0.1';
		$this->author = 'themes.madalweb.com';
		$this->need_instance = 0;

		parent::__construct();	

		$this->displayName = $this->l('Countdown Specials block');
		$this->description = $this->l('Adds a countdown block on your shop with specials price products.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	
	public function install()
	{	if(!parent::install() || !$this->registerHook('displayHeader') || !$this->registerHook('displayLeftColumn'))
			return false;
		return true;
	}
	
	public function uninstall()
	{
		return parent::uninstall();
	}
		
	public function getContent() {
	$output = '';
		if (Tools::isSubmit('submitcountdown'))
		{
			Configuration::updateValue('PS_BLOCK_SPECIALS_DISPLAY', (int)Tools::getValue('always_display'));
			$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
		}
		return $output.$this->displayForm();
	}
	
	public function displayForm()
	{
		$output = '
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
					  <fieldset><legend><img src="'.$this->_path.'logo.png" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Always display this block.').'</label>
				<div class="margin-form">
					<input type="radio" name="always_display" id="display_on" value="1" '.(Tools::getValue('always_display', Configuration::get('PS_BLOCK_SPECIALS_DISPLAY')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="display_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="always_display" id="display_off" value="0" '.(!Tools::getValue('always_display', Configuration::get('PS_BLOCK_SPECIALS_DISPLAY')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="display_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="clear">'.$this->l('Show the block even if no product is available.').'</p>
				</div>
				<center><input type="submit" name="submitSpecials" value="'.$this->l('Save').'" class="button" /></center>
				
			</fieldset>
		</form>';
		
		return $output.$this->getAdver();
	}
	
	protected function getAdver()
	{
	$output  = '
	<div style="width:440px;float:left;margin-right:18px;>
	<p style="text-align:left;font-size:17px;">'.$this->l('If you like this module make a small donation or buy full version of this module ').'</p>
	<p style="text-align:left;font-size:13px;">'.$this->l('Make a donation of 8 usd and you will receive version pro - Prestashop Specials Price Sidebar Countdown module.Module file Prestashop Specials Price Sidebar Countdown will be sent to the email address from which the donation was received ').'</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<fieldset>
	   <legend>'.$this->l('Donation').'</legend>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="AN794MTH5A7N8">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
<p></p>
<p>Donate $8 and receive Prestashop Specials Price Sidebar Countdown</p>
<p><ul>Module Featured:
<li>Left/Right Countdown Products Block Slider</li>
 <li>Multilanguage,Multistore,Responsive Design.</li>
 <li>Compatible with Prestashop 1.6.</li>
 <li>Color Editor Included.</li></ul></p>
<p><a href="http://prestashop01.pounduk.com/3-women">
						'.$this->l('Demo Prestashop Specials Price Sidebar Countdown.').'</a></p>
</fieldset></form></div>
  <div style="width:440px;float:left;>
	<p style="text-align:left;font-size:13px;">'.$this->l('Buy Now Prestashop Reduced Price Countdown Module - Price $18 ').'</p>
	<p><ul>Module Featured:
	   <li>Homepage Countdown Products Block Slider.</li>
	   <li>Left/Right Countdown Products Block Slider</li>
	   <li>Products Page Countdown.</li>
	   <li>Multilanguage,Multistore,Responsive Design.</li>
	   <li>Compatible with Prestashop 1.6.</li>
	   <li>Color Editor Included.</li>
	   <li>Free Support<ul>Not Offer Support when:
							<li>Prestashop Core Modifications.</li>
							<li>Module Modification.</li>
							<li>Incomplete update of Prestashop.</li>
							<li>Other changes that may cause incorrect operation of Prestashop CMS.</ul></li>
		<li>Refund if the module is not functioning properly.</li>				   
	   </ul></p>
	 <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	  <fieldset>
	   <legend>'.$this->l('Prestashop Reduced Price Countdown Module - working only with Prestashop v1.6').'</legend>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="78T9CS4JUP6S2">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
   <p><a href="http://www.prestashop.com/forums/topic/341815-prestashop-countdown-special-price-products-module/#entry1731707">
						'.$this->l('More Details on Prestashop Forum.').'</a></p>
		<p><a href="http://prestashop01.pounduk.com/">
						'.$this->l('Demo Special Price Countdown Module.').'</a>
	</p>
	</fieldset>
	 </form>
		</div>
		<br><br>
				<div style="width:100%;text-align:center;font-size:21px"><label style="width:100%!important;text-align:center!important;"><b>'.$this->l('Other Prestashop Modules to Customize your online store').'</b></label></div><br><br>
					<div style="float:left;width:100%;margin-bottom:14px;"><div style="width:300px;margin-right:10px;float:left;">
					<p style="font-size:17px;">'.$this->l('Prestashop Homecategories Multiple Carousels.').'</p>
					<p>'.$this->l('This module allows you to display products from categories on main page in multiple carousel and left/right sidebar in a single vertical carousel.').'</p>							    
					<p><a href="http://codecanyon.net/item/prestashop-homecategories-multiple-carousels-/8070383?WT.ac=item_more_thumb&WT.z_author=orizo"><img src="'.$this->_path.'images/homecategoriebyid_small.png" width="300px" height="400px" alt="Prestashop Homecategories by ID"></a></p></div>
					<div style="width:300px;float:left;margin-right:10px;"><p style="font-size:17px;">'.$this->l('Prestashop Recommended Products by ID.').'</p>
					<p>'.$this->l('This module allow you to select different products by ID and will be displayed on your homepage store and product page in a carousel').'</p>
					<p><a href="http://codecanyon.net/item/prestashop-recommended-products-by-id-module/8217271?WT.ac=item_more_thumb&WT.z_author=orizo">
					<img src="'.$this->_path.'images/recomm_small.png" width="300px" height="126px" alt="Prestashop Recommended Products Carousel"></a></p></div>
					<div style="width:300px;float:left;margin-right:10px;"><p style="font-size:17px;">'.$this->l('Prestashop Carousels Pack with Countdown.').'</p>
					<p>'.$this->l('Prestashop Carousel’s Pack module allow you to display featured products,new products,top sellers,special price in a carousel on homepage, viewed products also in a carousel on product page and on left or right column top sellers carousel.Prestashop Carousel’s Pack have included a color editor to change very easy style of the module and a countdown for homepage special price carousel..').'</p>
					<p><a href="http://codecanyon.net/item/prestashop-carousels-pack-module/8469497?ref=orizo">
					<img src="'.$this->_path.'images/TP-carou.png" width="300px" height="220px" alt="Prestashop Countdown Products"></a></p></div></div>
					<div style="width:300px;float:left;margin-right:10px;"><p style="font-size:17px;">'.$this->l('Prestashop Countdown Special Price Products.').'</p>
					<p>'.$this->l('This module will display a countdown on product page with special price,will create a left/right sidebar block with special price products and on homepage will create a countdown block with special price products in a carousel.').'</p>
					<p><a href="http://codecanyon.net/item/prestashop-countdown-special-price-products-module/8057694?WT.ac=item_more_thumb&WT.z_author=orizo">
					<img src="'.$this->_path.'images/countdown_small.png" width="300px" height="152px" alt="Prestashop Countdown Products"></a></p></div>
				</div>';
	return $output;
	}
	
	public function hookLeftColumn($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		
			if (!($special = Product::getRandomSpecial((int)$params['cookie']->id_lang)) && !Configuration::get('PS_BLOCK_SPECIALS_DISPLAY'))
				return;

			$this->smarty->assign(array(
				'special' => $special,
				'priceWithoutReduction_tax_excl' => Tools::ps_round($special['price_without_reduction'], 2),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
			));

		return $this->display(__FILE__, 'countdownspecialblock.tpl');
	}

	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	public function hookHeader($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return ;
		$this->context->controller->addCSS(($this->_path).'css/countdownspecialblock.css', 'all');
		$this->context->controller->addCSS(($this->_path).'css/dscountdown.css');
		$this->context->controller->addJS($this->_path.'js/dscountdown.min.js');
	}
}