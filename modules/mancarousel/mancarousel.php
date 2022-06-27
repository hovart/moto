<?php
/**
* Man Carousel v0.2
* @author kik-off.com <info@kik-off.com>
**/

if (!defined('_PS_VERSION_'))
    exit;

class ManCarousel extends Module
{
    public function __construct()
    {
        $this->name = 'mancarousel';
        $this->tab = 'front_office_features';
        $this->version = 0.2;
        $this->author = 'www.kik-off.com';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Man Carousel');
        $this->description = $this->l('Smooth manufacturer carousel for the footer.');
    }

    public function install()
    {
        Configuration::updateValue('MAN_CAROUSEL_IMAGE_TYPE', '');
		Configuration::updateValue('MAN_CAROUSEL_DISPLAY_ITEMS', 8);
		Configuration::updateValue('MAN_CAROUSEL_SCROLL_ITEMS', 8);
		Configuration::updateValue('MAN_CAROUSEL_PAUSE_TIME', 5000);
		Configuration::updateValue('MAN_CAROUSEL_CIRCULAR', 1);
		Configuration::updateValue('MAN_CAROUSEL_INFINITE', 1);
		Configuration::updateValue('MAN_CAROUSEL_MOUSEOVER_PAUSE', 1);
		Configuration::updateValue('MAN_CAROUSEL_AUTO_START', 1);
		Configuration::updateValue('MAN_CAROUSEL_RANDOM', 1);
		Configuration::updateValue('MAN_CAROUSEL_FX', 'none');
		Configuration::updateValue('MAN_CAROUSEL_FX_TIME', 500);

		if (parent::install() == false OR !$this->registerHook('header') OR !$this->registerHook('footer'))
            return false;
        return true;
    }

	public function uninstall()
    {
        if (!parent::uninstall() == false ||
		    !Configuration::deleteByName('MAN_CAROUSEL_IMAGE_TYPE') ||
			!Configuration::deleteByName('MAN_CAROUSEL_DISPLAY_ITEMS') ||
			!Configuration::deleteByName('MAN_CAROUSEL_SCROLL_ITEMS') ||
			!Configuration::deleteByName('MAN_CAROUSEL_PAUSE_TIME') ||
			!Configuration::deleteByName('MAN_CAROUSEL_CIRCULAR') ||
			!Configuration::deleteByName('MAN_CAROUSEL_INFINITE') ||
			!Configuration::deleteByName('MAN_CAROUSEL_MOUSEOVER_PAUSE') ||
			!Configuration::deleteByName('MAN_CAROUSEL_AUTO_START') ||
			!Configuration::deleteByName('MAN_CAROUSEL_RANDOM') ||
			!Configuration::deleteByName('MAN_CAROUSEL_FX') ||
			!Configuration::deleteByName('MAN_CAROUSEL_FX_TIME')
		)
        parent::uninstall();
    }

	public function getContent()
	{
		if (Tools::isSubmit('manCarouselFormSubmit'))
		{
		    $man_carousel_image_type = Tools::getValue('man_carousel_image_type');
			$man_carousel_display_items = Tools::getValue('man_carousel_display_items');
			$man_carousel_scroll_items = Tools::getValue('man_carousel_scroll_items');
			$man_carousel_pause_time = Tools::getValue('man_carousel_pause_time');
			$man_carousel_auto_start = Tools::getValue('man_carousel_auto_start');
			$man_carousel_random = Tools::getValue('man_carousel_random');
			$man_carousel_circular = Tools::getValue('man_carousel_circular');
			$man_carousel_infinite = Tools::getValue('man_carousel_infinite');
			$man_carousel_mouseover_pause = Tools::getValue('man_carousel_mouseover_pause');
			$man_carousel_fx = Tools::getValue('man_carousel_fx');
			$man_carousel_fx_time = Tools::getValue('man_carousel_fx_time');

			Configuration::updateValue('MAN_CAROUSEL_IMAGE_TYPE', $man_carousel_image_type);
			Configuration::updateValue('MAN_CAROUSEL_DISPLAY_ITEMS', $man_carousel_display_items);
			Configuration::updateValue('MAN_CAROUSEL_SCROLL_ITEMS', $man_carousel_scroll_items);
			Configuration::updateValue('MAN_CAROUSEL_PAUSE_TIME', $man_carousel_pause_time);
			Configuration::updateValue('MAN_CAROUSEL_AUTO_START', $man_carousel_auto_start);
			Configuration::updateValue('MAN_CAROUSEL_RANDOM', $man_carousel_random);
			Configuration::updateValue('MAN_CAROUSEL_CIRCULAR', $man_carousel_circular);
			Configuration::updateValue('MAN_CAROUSEL_INFINITE', $man_carousel_infinite);
			Configuration::updateValue('MAN_CAROUSEL_MOUSEOVER_PAUSE', $man_carousel_mouseover_pause);
			Configuration::updateValue('MAN_CAROUSEL_FX', $man_carousel_fx);
			Configuration::updateValue('MAN_CAROUSEL_FX_TIME', $man_carousel_fx_time);

			$this->_html .= '<div class="conf">'.$this->l('Settings updated').'</div>';
	    }

		$this->_html .= '<h2>'.$this->displayName.'</h2>';
		
		$this->_html .= '<style type="text/css">
		    #imprint{width: 100%;text-align: right;}
		    #imprint img {float: left;}
		</style>';
		
		$images = ImageType::getImagesTypes('manufacturers');

		$this->_html .= '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" />'.$this->l('Settings').'</legend><br/>
			    <label>'.$this->l('Select image type').'</label>
			    <div class="margin-form">
			        <select name="man_carousel_image_type">';
		        foreach ($images AS $key => $image)
		        {
				    $this->_html .= '<option value="' . $image['name'] . '"'.(Configuration::get('MAN_CAROUSEL_IMAGE_TYPE') == $image['name'] ? ' selected="selected"' : '').'>' . $image['name'] . ' (' . $image['width'] . 'x' . $image['height'] . ')</option>';
		        }
		        $this->_html .= '
				    </select>
				</div>
			    <label>'.$this->l('Visible items').'</label>
				<div class="margin-form">
					<input type="text" name="man_carousel_display_items" id="man_carousel_display_items" size="3" value="'.Tools::getValue('man_carousel_display_items', Configuration::get('MAN_CAROUSEL_DISPLAY_ITEMS')).'" />
				</div>
				<label>'.$this->l('Scroll items').'</label>
				<div class="margin-form">
					<input type="text" name="man_carousel_scroll_items" id="man_carousel_scroll_items" size="3" value="'.Tools::getValue('man_carousel_scroll_items', Configuration::get('MAN_CAROUSEL_SCROLL_ITEMS')).'" />
				</div>
				<label>'.$this->l('Pause').'</label>
				<div class="margin-form">
					<input type="text" name="man_carousel_pause_time" id="man_carousel_pause_time" size="3" value="'.Tools::getValue('man_carousel_pause_time', Configuration::get('MAN_CAROUSEL_PAUSE_TIME')).'" />
					<p class="preference_description clear">'.$this->l('The amount of milliseconds the carousel will pause').'.</p>
				</div>
				<label>'.$this->l('Auto start').'</label>
				<div class="margin-form">
					<input type="radio" name="man_carousel_auto_start" id="man_carousel_auto_start_on" value="1" '.(Tools::getValue('man_carousel_auto_start', Configuration::get('MAN_CAROUSEL_AUTO_START')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_auto_start_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="man_carousel_auto_start" id="man_carousel_auto_start_off" value="0" '.(!Tools::getValue('man_carousel_auto_start', Configuration::get('MAN_CAROUSEL_AUTO_START')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_auto_start_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('Random').'</label>
				<div class="margin-form">
					<input type="radio" name="man_carousel_random" id="man_carousel_random_on" value="1" '.(Tools::getValue('man_carousel_random', Configuration::get('MAN_CAROUSEL_RANDOM')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_random_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="man_carousel_random" id="man_carousel_random_off" value="0" '.(!Tools::getValue('man_carousel_random', Configuration::get('MAN_CAROUSEL_RANDOM')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_random_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('Circular').'</label>
				<div class="margin-form">
					<input type="radio" name="man_carousel_circular" id="man_carousel_circular_on" value="1" '.(Tools::getValue('man_carousel_circular', Configuration::get('MAN_CAROUSEL_CIRCULAR')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_circular_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="man_carousel_circular" id="man_carousel_circular_off" value="0" '.(!Tools::getValue('man_carousel_circular', Configuration::get('MAN_CAROUSEL_CIRCULAR')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_circular_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="preference_description clear">'.$this->l('Determines whether the carousel should be circular').'.</p>
				</div>
				<label>'.$this->l('Infinite').'</label>
				<div class="margin-form">
					<input type="radio" name="man_carousel_infinite" id="man_carousel_infinite_on" value="1" '.(Tools::getValue('man_carousel_infinite', Configuration::get('MAN_CAROUSEL_INFINITE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_infinite_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="man_carousel_infinite" id="man_carousel_infinite_off" value="0" '.(!Tools::getValue('man_carousel_infinite', Configuration::get('MAN_CAROUSEL_INFINITE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_infinite_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="preference_description clear">'.$this->l('Determines whether the carousel should be infinite. Note: It is possible to create a non-circular, infinite carousel, but it is not possible to create a circular, non-infinite carousel').'.</p>
				</div>
				<label>'.$this->l('Mouseover pause').'</label>
				<div class="margin-form">
					<input type="radio" name="man_carousel_mouseover_pause" id="man_carousel_mouseover_on" value="1" '.(Tools::getValue('man_carousel_mouseover_pause', Configuration::get('MAN_CAROUSEL_MOUSEOVER_PAUSE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_mouseover_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="man_carousel_mouseover_pause" id="man_carousel_mouseover_off" value="0" '.(!Tools::getValue('man_carousel_mouseover_pause', Configuration::get('MAN_CAROUSEL_MOUSEOVER_PAUSE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="man_carousel_mouseover_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('Transition').'</label>
				<div class="margin-form">
					<select name="man_carousel_fx" style="width:100px;" />
					    <option value="none"'.(Configuration::get('MAN_CAROUSEL_FX') == 'none' ? ' selected="selected"' : '').'>'.$this->l('None').'</option>
					    <option value="scroll"'.(Configuration::get('MAN_CAROUSEL_FX') == 'scroll' ? ' selected="selected"' : '').'>'.$this->l('Scroll').'</option>
						<option value="directscroll"'.(Configuration::get('MAN_CAROUSEL_FX') == 'directscroll' ? ' selected="selected"' : '').'>'.$this->l('Direct scroll').'</option>
						<option value="fade"'.(Configuration::get('MAN_CAROUSEL_FX') == 'fade' ? ' selected="selected"' : '').'>'.$this->l('Fade').'</option>
						<option value="crossfade"'.(Configuration::get('MAN_CAROUSEL_FX') == 'crossfade' ? ' selected="selected"' : '').'>'.$this->l('Crossfade').'</option>
						<option value="cover"'.(Configuration::get('MAN_CAROUSEL_FX') == 'cover' ? ' selected="selected"' : '').'>'.$this->l('Cover').'</option>
						<option value="cover-fade"'.(Configuration::get('MAN_CAROUSEL_FX') == 'cover-fade' ? ' selected="selected"' : '').'>'.$this->l('Cover fade').'</option>
						<option value="uncover"'.(Configuration::get('MAN_CAROUSEL_FX') == 'uncover' ? ' selected="selected"' : '').'>'.$this->l('Uncover').'</option>
						<option value="uncover-fade"'.(Configuration::get('MAN_CAROUSEL_FX') == 'uncover-fade' ? ' selected="selected"' : '').'>'.$this->l('Uncover fade').'</option>
					</select>
				</div>
				<label>'.$this->l('Duration').'</label>
				<div class="margin-form">
					<input type="text" name="man_carousel_fx_time" id="man_carousel_fx_time" size="3" value="'.Tools::getValue('man_carousel_fx_time', Configuration::get('MAN_CAROUSEL_FX_TIME')).'" />
					<p class="preference_description clear">'.$this->l('Duration of the transition in milliseconds').'.</p>
				</div>
				<div class="margin-form">
				    <input class="button" name="manCarouselFormSubmit" type="submit" value="'.$this->l('Save').'" />
				</div>
			</fieldset>
		</form>
		<br/>
		<fieldset><div id="imprint"><a href="http://www.kik-off.com" target="_blank" title="http://www.kik-off.com"><img src="'.$this->_path.'img/logo_kik_off.gif" alt="" /></a>'.$this->l('Module by').': <a href="http://www.kik-off.com" target="_blank" title="http://www.kik-off.com">www.kik-off.com</a>, <a href="mailto:info@kik-off.com">info@kik-off.com</a></div></fieldset>';

		return $this->_html;
	}

	public function hookHeader( $params )
    {
		if(_PS_VERSION_ > "1.4.0.0" && _PS_VERSION_ < "1.5.0.0" )
        {
		    Tools::addCSS($this->_path.'css/'.$this->name.'.css', 'all');
			Tools::addJS($this->_path.'js/jquery.carouFredSel-6.1.0-packed.js');
		}
		if(_PS_VERSION_ > "1.5.0.0")
		{
		    $this->context->controller->addCSS(($this->_path).'css/'.($this->name).'.css', 'all');
			$this->context->controller->addJS(($this->_path).'js/jquery.carouFredSel-6.1.0-packed.js');
		}
    }

	public function hookFooter( $params )
    {
        global $smarty, $cookie;

		$man_carousel_image_type = Configuration::get('MAN_CAROUSEL_IMAGE_TYPE');
		
		if(_PS_VERSION_ > "1.4.0.0" && _PS_VERSION_ < "1.5.0.0" )
        {
		    $mancarousel = Manufacturer::getManufacturers(false, $cookie->id_lang, true, false, false, false);
			
			$smarty->assign(array(
				'imageSize' => Image::getSize($man_carousel_image_type),
				'imageName' => $man_carousel_image_type,
				'mancarousel' => $mancarousel
			));
		}
		if(_PS_VERSION_ > "1.5.0.0")
		{
		    $id_current_shop_group = Shop::getContextShopGroupID();
		    $mancarousel = Manufacturer::getManufacturers(true, $this->context->language->id, true, false, false, false, $id_current_shop_group);
			
			$this->context->smarty->assign(array(
				'imageSize' => Image::getSize($man_carousel_image_type),
				'imageName' => $man_carousel_image_type,
				'mancarousel' => $mancarousel
			));
		}

		$man_carousel_display_items = Configuration::get('MAN_CAROUSEL_DISPLAY_ITEMS');
		$man_carousel_scroll_items = Configuration::get('MAN_CAROUSEL_SCROLL_ITEMS');
		$man_carousel_pause_time = Configuration::get('MAN_CAROUSEL_PAUSE_TIME');
		$man_carousel_auto_start = Configuration::get('MAN_CAROUSEL_AUTO_START');
		$man_carousel_random = Configuration::get('MAN_CAROUSEL_RANDOM');
		$man_carousel_circular = Configuration::get('MAN_CAROUSEL_CIRCULAR');
		$man_carousel_infinite = Configuration::get('MAN_CAROUSEL_INFINITE');
		$man_carousel_mouseover_pause = Configuration::get('MAN_CAROUSEL_MOUSEOVER_PAUSE');
		$man_carousel_fx = Configuration::get('MAN_CAROUSEL_FX');
		$man_carousel_fx_time = Configuration::get('MAN_CAROUSEL_FX_TIME');
		
		    if($man_carousel_circular == 1){
		    	$man_carousel_cir = 'true';
			}
			elseif($man_carousel_circular == 0){
			    $man_carousel_cir = 'false';
			}
			if($man_carousel_infinite == 1){
		    	$man_carousel_inf = 'true';
			}
			elseif($man_carousel_infinite == 0){
			    $man_carousel_inf = 'false';
			}
			if($man_carousel_auto_start == 1){
			    $man_carousel_auto = 'true';
			}
			elseif($man_carousel_auto_start == 0){
			    $man_carousel_auto = 'false';
			}
			if($man_carousel_mouseover_pause == 1){
			    $man_carousel_mouseover = 'true';
			}
			elseif($man_carousel_mouseover_pause == 0){
			    $man_carousel_mouseover = 'false';
			}
			if($man_carousel_random == 1){
			    $man_carousel_rand = '"random"';
			}
			else{
			    $man_carousel_rand = 0;
			}
			
        if (version_compare(_PS_VERSION_,'1.5','<'))
        {
	        $smarty->assign(array(
				'man_carousel_display_items' => $man_carousel_display_items,
				'man_carousel_scroll_items' => $man_carousel_scroll_items,
				'man_carousel_pause_time' => $man_carousel_pause_time,
				'man_carousel_auto' => $man_carousel_auto,
				'man_carousel_rand' => $man_carousel_rand,
				'man_carousel_cir' => $man_carousel_cir,
				'man_carousel_inf' => $man_carousel_inf,
				'man_carousel_mouseover' => $man_carousel_mouseover,
				'man_carousel_fx' => $man_carousel_fx,
				'man_carousel_fx_time' => $man_carousel_fx_time
		    ));
		}
	    else
		{
		    $this->context->smarty->assign(array(
				'man_carousel_display_items' => $man_carousel_display_items,
				'man_carousel_scroll_items' => $man_carousel_scroll_items,
				'man_carousel_pause_time' => $man_carousel_pause_time,
				'man_carousel_auto' => $man_carousel_auto,
				'man_carousel_rand' => $man_carousel_rand,
				'man_carousel_cir' => $man_carousel_cir,
				'man_carousel_inf' => $man_carousel_inf,
				'man_carousel_mouseover' => $man_carousel_mouseover,
				'man_carousel_fx' => $man_carousel_fx,
				'man_carousel_fx_time' => $man_carousel_fx_time
		    ));
		}
			
        return $this->display(__FILE__, 'mancarousel.tpl');
    }
	
	public function hookHome( $params )
    {
	    return $this->hookFooter($params);
	}
	
	public function hookTop( $params )
    {
	    return $this->hookFooter($params);
	}
}
?>