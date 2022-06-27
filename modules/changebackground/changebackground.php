<?php


if (!defined('_PS_VERSION_'))
	exit;

class Changebackground extends Module
{
	/* répétition de l'image */
	public $change_repeat;
	
	/* image fixe */
	public $change_fixed;
	
	/*changement de couleur*/
	public $change_color;
	
	/* Name of the image without extension */
	public $change_imgname;
	
	/* Image path with extension */
	public $change_img;
	
	/* Image postioning */
	public $change_positionheight;
	
	/* Image postioning */
	public $change_positionwidth;
	
	/* répétition de l'image */
	public $change_repeat2;
	
	/* image fixe */
	public $change_fixed2;
	
	/*changement de couleur*/
	public $change_color2;
	
	/* Name of the image without extension */
	public $change_imgname2;
	
	/* Image path with extension */
	public $change_img2;
	
	/* Image postioning */
	public $change_positionheight2;
	
	/* Image postioning */
	public $change_positionwidth2;
	
	

	public function __construct()
	{
		$this->name = 'changebackground';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '2.0';
		$this->author = 'My-Theme-Shop.com';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Change background');
		$this->description = $this->l('Adds a background to your shop');
		
		$this->initialize();
	}

	/*
	 * Set the properties of the module, like the link to the image and the title (contextual to the current shop context)
	 */
	protected function initialize()
	{
		$this->change_imgname = 'change';
		if ((Shop::getContext() == Shop::CONTEXT_GROUP  || Shop::getContext() == Shop::CONTEXT_SHOP)
			&& file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname.'-g'.$this->context->shop->getContextShopGroupID().'.'.Configuration::get('CHANGE_IMG_EXT')))
			$this->change_imgname .= '-g'.$this->context->shop->getContextShopGroupID();
		if (Shop::getContext() == Shop::CONTEXT_SHOP
			&& file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname.'-s'.$this->context->shop->getContextShopID().'.'.Configuration::get('CHANGE_IMG_EXT')))
			$this->change_imgname .= '-s'.$this->context->shop->getContextShopID();

		$this->change_img = Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/'.$this->change_imgname.'.'.Configuration::get('CHANGE_IMG_EXT');
		$this->change_repeat = htmlentities(Configuration::get('change_repeat'), ENT_QUOTES, 'UTF-8');
		$this->change_fixed = htmlentities(Configuration::get('change_fixed'), ENT_QUOTES, 'UTF-8');
		$this->change_color = htmlentities(Configuration::get('change_color'), ENT_QUOTES, 'UTF-8');
		$this->change_imageactivate = htmlentities(Configuration::get('change_imageactivate'), ENT_QUOTES, 'UTF-8');
		$this->change_coloractivate = htmlentities(Configuration::get('change_coloractivate'), ENT_QUOTES, 'UTF-8');
		$this->change_positionheight = htmlentities(Configuration::get('change_positionheight'), ENT_QUOTES, 'UTF-8');
		$this->change_positionwidth = htmlentities(Configuration::get('change_positionwidth'), ENT_QUOTES, 'UTF-8');
		
		
		$this->change_imgname2 = 'change2';
		if ((Shop::getContext() == Shop::CONTEXT_GROUP  || Shop::getContext() == Shop::CONTEXT_SHOP)
			&& file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'-g'.$this->context->shop->getContextShopGroupID().'.'.Configuration::get('CHANGE_IMG_EXT2')))
			$this->change_imgname2 .= '-g'.$this->context->shop->getContextShopGroupID();
		if (Shop::getContext() == Shop::CONTEXT_SHOP
			&& file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'-s'.$this->context->shop->getContextShopID().'.'.Configuration::get('CHANGE_IMG_EXT2')))
			$this->change_imgname2 .= '-s'.$this->context->shop->getContextShopID();

		$this->change_img2 = Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'.'.Configuration::get('CHANGE_IMG_EXT2');
		$this->change_repeat2 = htmlentities(Configuration::get('change_repeat2'), ENT_QUOTES, 'UTF-8');
		$this->change_fixed2 = htmlentities(Configuration::get('change_fixed2'), ENT_QUOTES, 'UTF-8');
		$this->change_imageactivate2 = htmlentities(Configuration::get('change_imageactivate2'), ENT_QUOTES, 'UTF-8');
		$this->change_positionheight2 = htmlentities(Configuration::get('change_positionheight2'), ENT_QUOTES, 'UTF-8');
		$this->change_positionwidth2 = htmlentities(Configuration::get('change_positionwidth2'), ENT_QUOTES, 'UTF-8');
	
	}
	
	public function install()
	{
		Configuration::updateGlobalValue('change_repeat','');
		Configuration::updateGlobalValue('change_fixed','');
		Configuration::updateGlobalValue('change_color','');
		Configuration::updateGlobalValue('change_imageactivate','no');
		Configuration::updateGlobalValue('change_coloractivate','no');
		Configuration::updateGlobalValue('change_positionheight','');
		Configuration::updateGlobalValue('change_positionwidth','');
		
		Configuration::updateGlobalValue('change_repeat2','');
		Configuration::updateGlobalValue('change_fixed2','');
		Configuration::updateGlobalValue('change_positionheight2','');
		Configuration::updateGlobalValue('change_positionwidth2','');
		// Try to update with the extension of the image that exists in the module directory
		foreach (scandir(_PS_MODULE_DIR_.$this->name) as $file)
			if (in_array($file, array('change2.jpg', 'change2.gif', 'change2.png')))
				Configuration::updateGlobalValue('CHANGE_IMG_EXT2', substr($file, strrpos($file, '.') + 1));

		return (parent::install() && $this->registerHook('header'));
	}
	
	public function uninstall()
	{
		Configuration::deleteByName('change_repeat');
		Configuration::deleteByName('change_fixed');
		Configuration::deleteByName('change_color');
		Configuration::deleteByName('change_imageactivate');
		Configuration::deleteByName('change_coloractivate');
		Configuration::deleteByName('change_positionheight');
		Configuration::deleteByName('change_positionwidth');
		Configuration::deleteByName('CHANGE_IMG_EXT');
		
		Configuration::deleteByName('change_repeat2');
		Configuration::deleteByName('change_fixed2');
		Configuration::deleteByName('change_imageactivate2');
		Configuration::deleteByName('change_positionheight2');
		Configuration::deleteByName('change_positionwidth2');
		Configuration::deleteByName('CHANGE_IMG_EXT2');
		return (parent::uninstall());
	}

	/**
	 * delete the contextual image (it is not allowed to delete the default image)
	 *
	 * @return void
	 */
	private function _deleteCurrentImg()
	{
		// Delete the image file
		if ($this->change_imgname != 'change' && file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname.'.'.Configuration::get('CHANGE_IMG_EXT')))
			unlink(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname.'.'.Configuration::get('CHANGE_IMG_EXT'));
		
		// Update the extension to the global value or the shop group value if available
		Configuration::deleteFromContext('CHANGE_IMG_EXT');
		Configuration::updateValue('CHANGE_IMG_EXT', Configuration::get('CHANGE_IMG_EXT'));

		// Reset the properties of the module
		$this->initialize();
	}


	private function _deleteCurrentImg2()
	{
		// Delete the image file
		if ($this->change_imgname2 != 'change2' && file_exists(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'.'.Configuration::get('CHANGE_IMG_EXT2')))
			unlink(_PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'.'.Configuration::get('CHANGE_IMG_EXT2'));
		
		// Update the extension to the global value or the shop group value if available
		Configuration::deleteFromContext('CHANGE_IMG_EXT2');
		Configuration::updateValue('CHANGE_IMG_EXT2', Configuration::get('CHANGE_IMG_EXT2'));

		// Reset the properties of the module
		$this->initialize();
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitDeleteImgConf'))
			$this->_deleteCurrentImg();

		$errors = '';
		
		if (Tools::isSubmit('submitDeleteImgConf2'))
			$this->_deleteCurrentImg2();

		$errors = '';
		
		if (Tools::isSubmit('submitchangeConf'))
		{
			if (isset($_FILES['change_img']) && isset($_FILES['change_img']['tmp_name']) && !empty($_FILES['change_img']['tmp_name']))
			{
				if ($error = ImageManager::validateUpload($_FILES['change_img'], Tools::convertBytes(ini_get('upload_max_filesize'))))
					$errors .= $error;
				else
				{
					Configuration::updateValue('CHANGE_IMG_EXT', substr($_FILES['change_img']['name'], strrpos($_FILES['change_img']['name'], '.') + 1));

					// Set the image name with a name contextual to the shop context
					$this->change_imgname = 'change';
					if (Shop::getContext() == Shop::CONTEXT_GROUP)
						$this->change_imgname = 'change'.'-g'.(int)$this->context->shop->getContextShopGroupID();
					elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
						$this->change_imgname = 'change'.'-s'.(int)$this->context->shop->getContextShopID();

					// Copy the image in the module directory with its new name
					if (!move_uploaded_file($_FILES['change_img']['tmp_name'], _PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname.'.'.Configuration::get('CHANGE_IMG_EXT')))
						$errors .= $this->l('Error move uploaded file');
				}
			}
			
			
			if (isset($_FILES['change_img2']) && isset($_FILES['change_img2']['tmp_name']) && !empty($_FILES['change_img2']['tmp_name']))
			{
				if ($error = ImageManager::validateUpload($_FILES['change_img2'], Tools::convertBytes(ini_get('upload_max_filesize'))))
					$errors .= $error;
				else
				{
					Configuration::updateValue('CHANGE_IMG_EXT2', substr($_FILES['change_img2']['name'], strrpos($_FILES['change_img2']['name'], '.') + 1));

					// Set the image name with a name contextual to the shop context
					$this->change_imgname2 = 'change2';
					if (Shop::getContext() == Shop::CONTEXT_GROUP)
						$this->change_imgname2 = 'change2'.'-g'.(int)$this->context->shop->getContextShopGroupID();
					elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
						$this->change_imgname2 = 'change2'.'-s'.(int)$this->context->shop->getContextShopID();

					// Copy the image in the module directory with its new name
					if (!move_uploaded_file($_FILES['change_img2']['tmp_name'], _PS_MODULE_DIR_.$this->name.'/'.$this->change_imgname2.'.'.Configuration::get('CHANGE_IMG_EXT2')))
						$errors .= $this->l('Error move uploaded file');
				}
			}
			
			
			
			// If the image repeat is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($repeat = Tools::getValue('change_repeat'))
				Configuration::updateValue('change_repeat', $repeat);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_repeat');
				
			// If the image fixed is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($fixed = Tools::getValue('change_fixed'))
				Configuration::updateValue('change_fixed', $fixed);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_fixed');
				
			// If the background color is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($color = Tools::getValue('change_color'))
				Configuration::updateValue('change_color', $color);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_color');
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($imgactivate = Tools::getValue('change_imageactivate'))
				Configuration::updateValue('change_imageactivate', $imgactivate);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_imageactivate');
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($activate = Tools::getValue('change_coloractivate'))
				Configuration::updateValue('change_coloractivate', $activate);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_coloractivate');
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($positionheight = Tools::getValue('change_positionheight'))
				Configuration::updateValue('change_positionheight', $positionheight);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_positionheight');	
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($positionwidth = Tools::getValue('change_positionwidth'))
				Configuration::updateValue('change_positionwidth', $positionwidth);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_positionwidth');


		// If the image repeat is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($repeat2 = Tools::getValue('change_repeat2'))
				Configuration::updateValue('change_repeat2', $repeat2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_repeat2');
				
			// If the image fixed is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($fixed2 = Tools::getValue('change_fixed2'))
				Configuration::updateValue('change_fixed2', $fixed2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_fixed2');
				
			// If the background color is not set, then delete it in order to use the next default value (either the global value or the group value)
			if ($color2 = Tools::getValue('change_color2'))
				Configuration::updateValue('change_color2', $color2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_color2');
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($imgactivate2 = Tools::getValue('change_imageactivate2'))
				Configuration::updateValue('change_imageactivate2', $imgactivate2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_imageactivate2');
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($positionheight2 = Tools::getValue('change_positionheight2'))
				Configuration::updateValue('change_positionheight2', $positionheight2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_positionheight2');	
				
			// If the activate background color is not set, then delete it in order to use the next default value (either the global value or the group value)	
			if ($positionwidth2 = Tools::getValue('change_positionwidth2'))
				Configuration::updateValue('change_positionwidth2', $positionwidth2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('change_positionwidth2');	
	
			
			// Reset the module properties
			$this->initialize();
		}
		if ($errors)
			echo $this->displayError($errors);
	}

	/**
	 * getContent used to display admin module form
	 *
	 * @return string content
	 */
	public function getContent()
	{
		$this->postProcess();
		$output = '<link rel="stylesheet" media="screen" type="text/css" href="../modules/changebackground/colorpicker/css/colorpicker.css" />
		<script type="text/javascript" src="../modules/changebackground/colorpicker/js/colorpicker.js"></script>
		<script type="text/javascript" src="../modules/changebackground/colorpicker/js/jquery.js"></script>
		<script type="text/javascript" src="../modules/changebackground/colorpicker/js/colorpicker.js"></script>
		<script type="text/javascript" src="../modules/changebackground/colorpicker/js/eye.js"></script>
		<script type="text/javascript" src="../modules/changebackground/colorpicker/js/utils.js"></script>
	        <script type="text/javascript" src="../modules/changebackground/colorpicker/js/layout.js?ver=1.0.2"></script>
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data" style="float:left;">
			<fieldset>
				<legend>'.$this->l('change block configuration').'</legend>
				
				
				<label for="change_imageactivate">'.$this->l('activer affichage image 1 :').'</label>
				<div class="margin-form">
				<p>'.$this->l('oui').' : <INPUT type=radio name="change_imageactivate" value="yes" '.((Configuration::get('change_imageactivate') != 'yes') ? '' : 'checked="checked"').'>
				'.$this->l('non').'  : <INPUT type=radio name="change_imageactivate" value="no" '.((Configuration::get('change_imageactivate') != 'no') ? '' : 'checked="checked"').'>
				</p>
				<br class="clear"/>
				</div>';
				
				
				
		if ($this->change_img)
		{
			$output .= '
			
				<img src="'.$this->context->link->protocol_content.$this->change_img.'"  style="width:155px;height:163px;margin-left:100px"/>';
			
				$output .= '<input class="button" type="submit" name="submitDeleteImgConf" value="'.$this->l('Delete image').'" style=""/>';
		}
		else
			$output .= '<div style="margin-left: 100px;width:163px;">'.$this->l('no image').'</div>';
		$output .= '<br/><br/>
				<label for="change_img">'.$this->l('Change image').'&nbsp;&nbsp;</label>
				<div class="margin-form">
					<input id="change_img" type="file" name="change_img" />
					
				</div>
				<br class="clear"/>
				
				<label for="change_repeat">'.$this->l('Change repeat').'</label>
				<div class="margin-form">
				<SELECT name="change_repeat" style="width:250px" >
				<OPTION VALUE="repeat" '.((Configuration::get('change_repeat') != 'repeat') ? '' : 'selected="selected"').'>'.$this->l('repeter image').'</OPTION>
				<OPTION VALUE="repeat-x" '.((Configuration::get('change_repeat') != 'repeat-x') ? '' : 'selected="selected"').'>'.$this->l('repeter image horizontalement').'</OPTION>
				<OPTION VALUE="repeat-y" '.((Configuration::get('change_repeat') != 'repeat-y') ? '' : 'selected="selected"').'>'.$this->l('repeter image verticalement').'</OPTION>
				<OPTION VALUE="no-repeat" '.((Configuration::get('change_repeat') != 'no-repeat') ? '' : 'selected="selected"').' >'.$this->l('ne pas repeter image').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
				<label for="change_positionheight">'.$this->l('positionnement image haut/bas').'</label>
				<div class="margin-form">
				<SELECT name="change_positionheight" style="width:250px" >
				<OPTION VALUE="top" '.((Configuration::get('change_positionheight') != 'top') ? '' : 'selected="selected"').'>'.$this->l('haut').'</OPTION>
				<OPTION VALUE="bottom" '.((Configuration::get('change_positionheight') != 'bottom') ? '' : 'selected="selected"').'>'.$this->l('bas').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
				
				<label for="change_positionwidth">'.$this->l('positionnement image Droite/milieu/gauche').'</label>
				<div class="margin-form">
				<SELECT name="change_positionwidth" style="width:250px" >
				<OPTION VALUE="right" '.((Configuration::get('change_positionwidth') != 'right') ? '' : 'selected="selected"').'>'.$this->l('droite').'</OPTION>
				<OPTION VALUE="center" '.((Configuration::get('change_positionwidth') != 'center') ? '' : 'selected="selected"').'>'.$this->l('centre').'</OPTION>
				<OPTION VALUE="left" '.((Configuration::get('change_positionwidth') != 'left') ? '' : 'selected="selected"').'>'.$this->l('gauche').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
			
				
				
				<label for="change_fixed">'.$this->l('image fixe :').'</label>
				<div class="margin-form">
				<p>'.$this->l('oui').' : <INPUT type=radio name="change_fixed" value="fixed" '.((Configuration::get('change_fixed') != 'fixed') ? '' : 'checked="checked"').'>
				'.$this->l('non').'  : <INPUT type=radio name="change_fixed" value="scroll" '.((Configuration::get('change_fixed') != 'scroll') ? '' : 'checked="checked"').'>
				</p>
					
				</div>
				--------------------------------------------------------------------------------------------------------------------------------------------------
				<br class="clear"/>
				
				
				
				<label for="change_imageactivate">'.$this->l('activer affichage image 2 :').'</label>
				<div class="margin-form">
				<p>'.$this->l('oui').' : <INPUT type=radio name="change_imageactivate2" value="yes" '.((Configuration::get('change_imageactivate2') != 'yes') ? '' : 'checked="checked"').'>
				'.$this->l('non').'  : <INPUT type=radio name="change_imageactivate2" value="no" '.((Configuration::get('change_imageactivate2') != 'no') ? '' : 'checked="checked"').'>
				</p>
				<br class="clear"/>
				</div>';
				
				
				
		if ($this->change_img2)
		{
			$output .= '
			
				<img src="'.$this->context->link->protocol_content.$this->change_img2.'"  style="width:155px;height:163px;margin-left:100px"/>';
			
				$output .= '<input class="button" type="submit" name="submitDeleteImgConf2" value="'.$this->l('Delete image').'" style=""/>';
		}
		else
			$output .= '<div>'.$this->l('no image').'</div>';
		$output .= '<br/><br/>
				<label for="change_img2">'.$this->l('Change image').'&nbsp;&nbsp;</label>
				<div class="margin-form">
					<input id="change_img2" type="file" name="change_img2" />
					
				</div>
				<br class="clear"/>
				
				<label for="change_repeat2">'.$this->l('Change repeat').'</label>
				<div class="margin-form">
				<SELECT name="change_repeat2" style="width:250px" >
				<OPTION VALUE="repeat" '.((Configuration::get('change_repeat2') != 'repeat') ? '' : 'selected="selected"').'>'.$this->l('repeter image').'</OPTION>
				<OPTION VALUE="repeat-x" '.((Configuration::get('change_repeat2') != 'repeat-x') ? '' : 'selected="selected"').'>'.$this->l('repeter image horizontalement').'</OPTION>
				<OPTION VALUE="repeat-y" '.((Configuration::get('change_repeat2') != 'repeat-y') ? '' : 'selected="selected"').'>'.$this->l('repeter image verticalement').'</OPTION>
				<OPTION VALUE="no-repeat" '.((Configuration::get('change_repeat2') != 'no-repeat') ? '' : 'selected="selected"').' >'.$this->l('ne pas repeter image').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
				<label for="change_positionheight2">'.$this->l('positionnement image haut/bas').'</label>
				<div class="margin-form">
				<SELECT name="change_positionheight2" style="width:250px" >
				<OPTION VALUE="top" '.((Configuration::get('change_positionheight2') != 'top') ? '' : 'selected="selected"').'>'.$this->l('haut').'</OPTION>
				<OPTION VALUE="bottom" '.((Configuration::get('change_positionheight2') != 'bottom') ? '' : 'selected="selected"').'>'.$this->l('bas').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
				
				<label for="change_positionwidth2">'.$this->l('positionnement image Droite/milieu/gauche').'</label>
				<div class="margin-form">
				<SELECT name="change_positionwidth2" style="width:250px" >
				<OPTION VALUE="right" '.((Configuration::get('change_positionwidth2') != 'right') ? '' : 'selected="selected"').'>'.$this->l('droite').'</OPTION>
				<OPTION VALUE="center" '.((Configuration::get('change_positionwidth2') != 'center') ? '' : 'selected="selected"').'>'.$this->l('centre').'</OPTION>
				<OPTION VALUE="left" '.((Configuration::get('change_positionwidth2') != 'left') ? '' : 'selected="selected"').'>'.$this->l('gauche').'</OPTION>
				</SELECT>
				</div>
				<br class="clear"/>
				
			
				
				
				<label for="change_fixed2">'.$this->l('image fixe :').'</label>
				<div class="margin-form">
				<p>'.$this->l('oui').' : <INPUT type=radio name="change_fixed2" value="fixed" '.((Configuration::get('change_fixed2') != 'fixed') ? '' : 'checked="checked"').'>
				'.$this->l('non').'  : <INPUT type=radio name="change_fixed2" value="scroll" '.((Configuration::get('change_fixed2') != 'scroll') ? '' : 'checked="checked"').'>
				</p>
					
				</div>
				--------------------------------------------------------------------------------------------------------------------------------------------------
				<br class="clear"/>
				
				
				<p><label for="change_coloractivate">'.$this->l('change_coloractivate').':</label>
				oui : <INPUT type=radio name="change_coloractivate" value="yes" '.((Configuration::get('change_coloractivate') != 'yes') ? '' : 'checked="checked"').'>
				non : <INPUT type=radio name="change_coloractivate" value="no" '.((Configuration::get('change_coloractivate') != 'no') ? '' : 'checked="checked"').'>
				</p>
				<label for="change_color">'.$this->l('change_color').'</label>
				<div class="margin-form">
					<input id="colorpickerField1" type="text" name="change_color" value="'.$this->change_color.'" style="width:50px" />
				</div>
				
				<br class="clear"/>
				<div class="margin-form">
					<input class="button" type="submit" name="submitchangeConf" value="'.$this->l('Validate').'"/>
				</div>
				<div class="margin-form">
				<a href="https://www.youtube.com/user/tutoprestashop" alt="'.$this->l('abonnement Youtube').'" title="'.$this->l('abonnement Youtube').'"><br/>
					<img src="../modules/changebackground/youtube.jpg"/><span style="line-height:28px;font-size:14px; font-weight:bold;">'.$this->l('abonnement Youtube').'</a><br/>
					<a href="http://www.my-theme-shop.com/"><img src="../modules/changebackground/logo2.gif"/><span style="line-height:32px;font-size:14px; font-weight:bold;">'.$this->l('suivre actualite blog').'</span></a><br/>
					<a href="http://feeds.feedburner.com/My-theme-shop"><img src="../modules/changebackground/rss.jpg"/><span style="line-height:32px;font-size:14px; font-weight:bold;">'.$this->l('abonner au news').'</span></a><br/>
					</div>
				<br class="clear"/>
			
			</fieldset>
		</form>
		<div style="float:left;margin:20px 0 0 50px;">
		</div>
		';
		return $output;
	}

	public function hookHeader($params)
	{
		$this->smarty->assign(array(
			'image' => $this->context->link->protocol_content.$this->change_img,
			'change_repeat' => $this->change_repeat,
			'change_fixed' => $this->change_fixed,
			'change_color' => $this->change_color,
			'change_imageactivate' => $this->change_imageactivate,
			'change_coloractivate' => $this->change_coloractivate,
			'change_positionheight' => $this->change_positionheight,
			'change_positionwidth' => $this->change_positionwidth,
			'image2' => $this->context->link->protocol_content.$this->change_img2,
			'change_repeat2' => $this->change_repeat2,
			'change_fixed2' => $this->change_fixed2,
			'change_imageactivate2' => $this->change_imageactivate2,
			'change_positionheight2' => $this->change_positionheight2,
			'change_positionwidth2' => $this->change_positionwidth2,
		));

		return $this->display(__FILE__, 'changebackground.tpl');
	}

}