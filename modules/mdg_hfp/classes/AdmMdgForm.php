<?php
/*
* 2007-2013 Michel Dumont | Graphart créations 
*
*  @author Michel Dumont <md@graphart.fr>
*  @copyright  2012 - 2013
*  @version  1.8 - 2013-11-12 by Michel Dumont <md@graphart.fr>
*  @version  1.7 - 2013-11-01 by Michel Dumont <md@graphart.fr>
*  @version  1.6 - 2013-09-25 by Michel Dumont <md@graphart.fr>
*  @version  1.5 - 2013-08-05 by Michel Dumont <md@graphart.fr>
*  @version  1.4 - 2013-07-29 by Michel Dumont <md@graphart.fr>
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.5.x
*
*/

if (!defined('_CAN_LOAD_FILES_')) exit;

class AdmMdgForm extends module
{
	public function __construct(){}	

/* =============================================================== //
	FORMS
/* =============================================================== */
	/**
	 * Affiche les tabs pour changer de formulaire
	 * $arrTabs : array( array('id_tab' => int, 'title' => string, 'href' => string) )
	 */
	public static function displayTabs($arrTabs)
	{
		$config_url = 'index.php?controller=AdminModules&configure='.Tools::getValue('configure').'&token='.Tools::getAdminTokenLite('AdminModules');
		$output ='
			<div class="mdgTabs">
				<ul class="tab">
		';
		
		foreach($arrTabs as $tab)
		{
			$selected = Tools::GetValue('form_tab',1)==$tab['id_tab'] ? 'selected' : '';
			$output .='
				<li class="tab-row">
					<a id="link-'.$tab['id_tab'].'" class="tab-page '.$selected.'" href="'.$config_url.$tab['href'].'">'.$tab['title'].'</a>
				</li>
			';
		}
		$output .='
				</ul>
			</div>
		';
		
		return $output;
	}
	
	/**
	 * Prépare les javascript pour l'éditeur TinyMce
	 * $class : (string) Class des textarea concernés
	 */
	public static function displayMce($class='autoload_rte')
	{
		/* Tiny MCE */
		$iso = Context::getContext()->language->iso_code;
		$iso = file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$iso.'.js') ? $iso : 'en';
		return '
			<script type="text/javascript" src="'._PS_JS_DIR_.'tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="'._PS_JS_DIR_.'tinymce.inc.js"></script>
			<script type="text/javascript">
				var iso = "'.$iso.'";
				var pathCSS = "'._THEME_CSS_DIR_.'";
				var ad = "'.dirname($_SERVER["PHP_SELF"]).'";
				$(document).ready(function(){
					tinySetup({
						editor_selector :"'.$class.'",
						theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull|cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,undo,redo",
						theme_advanced_buttons2 : "link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,charmap,media,|,ltr,rtl,|,fullscreen",
						theme_advanced_buttons3 : "",theme_advanced_buttons4 : ""
					});
				});
			</script>
		';
	}

	/**
	 * Ajoute un champ de formulaire
	 * $arr : (array)
	 */
	public function addField($arr)
	{
		if(!count($arr))
			return;
		
		$_arr = array(
			'label' 	=> '',
			'name' 		=> '',
			'id' 		=> '',
			'class'		=> '',
			'type' 		=> '',
			'value'		=> '',
			'desc' 		=> '', 
			'sufix'		=> '', 
			'required' 	=> false,
			'width'		=> null,
			'height'	=> null,
			'size'		=> 40,
			'disabled'	=> false,
			'readonly'	=> false,
			'lang'	 	=> false,
			'names_lang_array' => array(),
			'config_url' => null,
			'fancy_box' => false,
			/* for textaea */
			'cols'	=> 60,
			'rows'	=> 5,
			/* for select */
			'onchange'	=> '',
			'options_html' => (string)'',
			'options' 	=> array(/*array('label','id','value','disabled')*/),
			/* for autocomplete */
			'data_max'	=> null,	// maximum de produits
			'data_count'=> null,	// produits sélectionnés
			'value_ids' => '',		// Nom des produits
			'value_names' => '',	// Nom des produits
		);
		
		$output = null;
		
		$_arr = array_merge($_arr,$arr);
		foreach($_arr as $k=>$v)
			$$k =  $v;
			
		if(!$id)
			$id = $_arr['id'] = str_replace(array('[',']'),array('-',''),$name);
		
		$boxClass = null;		
		/* If only boxe infos */
		if($type == 'infos')
			$boxClass = 'multishop_info ';
		elseif($type == 'warning')
			$boxClass = 'warn ';
		if($boxClass)
			return '<div id="'.$id.'" class="'.$boxClass.$class.'" style="'.($width?'width:'.$width.'px;':'').' '.($height?'height:'.$height.'px;':'').'" >
						'.$value.'
					</div>
				';

		$output .= '<label for="'.$id.'">'.$label.($required?' <sup>*</sup>':'').'</label>';
		
		
		$output .= '<div class="margin-form '.($required?'required':'').'" >';
		
		$output .= ($lang) ? $this->addFieldLang($_arr) : $this->_addField($_arr);
		
		$output .= isset($desc)? '<p>'.$desc .'</p>': '';
		$output .= '</div>';
		return $output;
	}
	public function _addField($_arr) {
		foreach($_arr as $k=>$v) $$k =  $v;
				
		$output=null;
		switch ($type)
		{
			case 'text' :
				$output .='
					<input type="text" id="'.$id.'" class="'.$class.'" name="'.$name.'" value="'.$value.'" '.($size?'size="'.$size.'"':'').' '.($disabled?'disabled="disabled"':'').' '.($readonly?'readonly="readonly"':'').' />
					'.($sufix?$sufix:'').'
					
				';
				break;
			case 'textarea' :
				$output .='
					<textarea id="'.$id.'" class="'.$class.'" name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea>
					'.($sufix?$sufix:'').'
				';
				break;
			case 'select' :
				$output .='<select id="'.$id.'" name="'.$name.'" '.($onchange?'onchange="'.$onchange.'"':'').' autocomplete="off">';
				if($options_html)
					$output .= $options_html;
				elseif(count($options))
					foreach($options as $option)
						if(!isset($option['optionGroup']))
							$output .='<option '.(isset($option['id'])?'id="'.$option['id'].'"':'').' value="'.$option['value'].'" '.($option['value']==$value?'selected="selected"':'').' '.(isset($option['disabled'])&&$option['disabled']?'disabled="disabled"':'').'>'.$option['label'].'</option>';
						elseif(count($option['value']))
						{
							$children = $option['value'];
							$output .= '<optgroup label="'.$option['label'].'">';
							foreach($children as $child)
								$output .='<option '.(isset($option['id'])?'id="'.$option['id'].'"':'').' value="'.$child['value'].'" '.($child['value']==$value?'selected="selected"':'').' '.(isset($option['disabled'])&&$option['disabled']?'disabled="disabled"':'').'>'.$child['label'].'</option>';
							$output .= '</optgroup>';
						}
				$output .='</select>';
				break;
			case 'onoff' :
				$output .= '
					<input type="radio" id="'.$id.'On" name="'.$name.'" value="1" checked="checked" />
					<label class="t" for="'.$id.'On"><img title="'.$this->l('Enable').'" alt="'.$this->l('Enable').'" src="../img/admin/enabled.gif"></label>
					<input type="radio" id="'.$id.'Off" name="'.$name.'" value="0" '.($value!=1?'checked="checked"':'').' />
					<label class="t" for="'.$id.'Off"><img title="'.$this->l('Disable').'" alt="'.$this->l('Disable').'" src="../img/admin/disabled.gif"></label>
				';
				break;				
			case 'autocomplete' :
				$output .='
					<input type="hidden" id="'.$id.'_ids" name="'.$name.'_ids" value="'.$value_ids.'" autocomplete="off" />
					<input type="hidden" id="'.$id.'_names" name="'.$name.'_names" value="'.$value_names.'" autocomplete="off" />
					<span id="'.$id.'_choose" data-id="'.$id.'" '.($data_max?'data-max="'.$data_max.'"':'').' style="'.($data_count && $data_max>=$data_count?'display:none':'').'">
						<input id="'.$id.'_autocomplete_input" type="text" value="" autocomplete="off">
						<p>'.$this->l('Begin typing the first letters of the product name, then select the product from the drop-down list').'</p>
					</span>
					<ul id="'.$id.'_selected">';
				$ids_products = explode(',',$value_ids);
				$names_products = explode('¤',$value_names);
				if(count($ids_products))
					foreach($ids_products as $k=>$v)
						if($v!='')
							$output .= '<li id="product_'.$v.'">'.$names_products[$k].' <span class="delSelectedProduct" data-id="'.$v.'" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span></li>';
				$output .= 
					'</ul>
					<script type="text/javascript">$(function(){$("#'.$id.'_autocomplete_input").doAutoComplete();});</script>
				';
				break;
			case 'file' :
				$output .= '
					<input type="hidden" name="'.$name.'_old" value="'.($value?$value:'').'" />
					<input type="file" class="'.$class.'" name="'.$name.'" id="'.$name.'" />
				';
				if($value)
				{
					$output .= (self::isImageExtention($value) ? 
						'<br /><br />
						'.($fancy_box?'<a class="fancybox" href="../modules/'.Tools::getValue('configure').'/'.$value.'">':'').'
							<img src="../modules/'.Tools::getValue('configure').'/'.$value.'" width="'.($size?$size:'200').'" />
						'.($fancy_box?'</a>':'') 
						: '<br /><br />'.$value);
						
					$output .= ($config_url ? '
						<a onclick="return confirm(\''.$this->l('Do your really want to delete this image? Warning page will be reloaded!').'\');" href="'.$config_url.'&delete_'.$name.'='.$value.'">
							<img src="../img/admin/delete.gif" alt="Supprimer" title="Supprimer" border="0" />
						</a>':'');
				}
				break;
		}
		return $output;
	}
	public function addFieldLang($_arr) {
		$id_lang = Context::getContext()->language->id;
		$languages = Language::getLanguages(false);

		foreach($_arr as $k=>$v) $$k =  $v;
				
		$names = '';
		foreach($names_lang_array as $v) $names .= $v.'¤';
		$names = trim($names,'¤');		
		
		$output=null;
		switch ($type)
		{
			case 'file' :
				foreach ($languages as $lg)
				{
					$output .= '
						<div id="'.$name.'_'.$lg['id_lang'].'" style="display: '.($lg['id_lang'] == $id_lang ? 'block' : 'none').';float: left;">
							<input type="hidden" name="'.$name.'_old['.$lg['id_lang'].']" value="'.($value?$value[$lg['id_lang']]:'').'" />
							<input type="file" class="'.$class.'" name="'.$name.'['.$lg['id_lang'].']" id="'.$name.'_'.$lg['id_lang'].'" />
					';
					if($value && $value[$lg['id_lang']])
					{
						$output .= (self::isImageExtention($value[$lg['id_lang']]) ? 
							'<br /><br />
							'.($fancy_box?'<a class="fancybox" href="../modules/'.Tools::getValue('configure').'/'.$value[$lg['id_lang']].'">':'').'
								<img src="../modules/'.Tools::getValue('configure').'/'.$value[$lg['id_lang']].'" width="200" />
							'.($fancy_box?'</a>':'') 
							: '<br /><br />'.$value[$lg['id_lang']]);
							
						$output .= ($config_url ? '
							<a onclick="return confirm(\''.$this->l('Do your really want to delete this image? Warning page will be reloaded!').'\');" href="'.$config_url.'&delete_'.$name.'='.$value[$lg['id_lang']].'">
								<img src="../img/admin/delete.gif" alt="Supprimer" title="Supprimer" border="0" />
							</a>':'');
					}
					$output .= '</div>';
				}
				break;
			case 'text' :
				foreach ($languages as $lg)
					$output .= '
						<div id="'.$name.'_'.$lg['id_lang'].'" style="display: '.($lg['id_lang'] == $id_lang ? 'block' : 'none').';float: left;">
							<input type="text" class="'.$class.'" name="'.$name.'['.$lg['id_lang'].']" id="'.$name.'_'.$lg['id_lang'].'" value="'.($value?$value[$lg['id_lang']]:'').'" '.($size?'size="'.$size.'"':'').' />
						</div>';
				break;
			case 'textarea' :
				foreach ($languages as $lg)
					$output .= '
						<div id="'.$name.'_'.$lg['id_lang'].'" style="display: '.($lg['id_lang'] == $id_lang ? 'block' : 'none').';float: left;">
							<textarea class="'.$class.'" name="'.$name.'['.$lg['id_lang'].']" cols="'.$cols.'" rows="'.$rows.'">'.($value?$value[$lg['id_lang']]:'').'</textarea>
						</div>';
		}

		$output .= $this->displayFlags($languages,$id_lang,$names,$name,true);
		$output .='<div class="clear"></div>';
		return $output;
	}
	
/* =============================================================== //
	TOOLS
/* =============================================================== */
	public function l($string, $specific = false, $id_lang = null)
	{
		if (parent::$_generate_config_xml_mode) return $string;
		return Translate::getModuleTranslation(Tools::getValue('configure'), $string, 'AdmMdgForm');
	}

	public static function isImageExtention($file_name)
	{
		$ext = substr(strtolower(strrchr(basename(str_replace(array('jpeg','pjpeg'),'jpg',$file_name)), ".")), 1);
		return in_array($ext,array('jpg','png','gif','bmp'));
	}

	/**
	 * vide le dossier dir
	 * $dir : (string) chemin vers le dossier
	 */
	public function clearDir($dir) {
		if(!preg_match("/^.*\/$/",$dir))
			$dir .= '/';
		if ($supr_dir = opendir($dir)) {
			while( false !== ($item = readdir($supr_dir)) )	
				if($item != "." && $item != "..")
					@unlink( $dir.$item );
			closedir($supr_dir);
			return true;
		}
		return false;
	}

}
