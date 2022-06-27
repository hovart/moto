<?php
/**
* 2015 Aretmic
*
* NOTICE OF LICENSE 
* 
* ARETMIC the Company grants to each customer who buys a virtual product license to use, and non-exclusive and worldwide. This license is 
* valid only once for a single e-commerce store. No assignment of rights is hereby granted by the Company to the Customer. It is also 
* forbidden for the Customer to resell or use on other virtual shops Products made by ARETMIC. This restriction includes all resources 
* provided with the virtual product. 
*
* @author    Aretmic SA
* @copyright 2015 Aretmic SA
* @license   ARETMIC
* International Registered Trademark & Property of Aretmic SA
*/

class CFtools {

protected static $utr = '';
protected static $cpr = '';

	public static function getIsocode($id_lang)
	{
		$languages = Language::getLanguages();
		$ctln = count($languages);
		for ($i = 0; $i < $ctln; $i++)
		{
			if ($languages[$i]['id_lang'] == $id_lang)
				$iso_code = $languages[$i]['iso_code'];
		}
		return $iso_code;
	}
public static function getIdlangFromiso($iso_code)
{
	$languages = Language::getLanguages();

	$ctla = count($languages);
	for ($i = 0; $i < $ctla; $i++)
	{
		if ($languages[$i]['iso_code'] == $iso_code)
			$id_lang = $languages[$i]['id_lang'];

		if (!isset($id_lang) || empty($id_lang))
			$id_lang = 0;
	}
	return $id_lang;
}
public static function frontpage($mypath, $name, $version)
{
	$mytoken = Tools::getValue('token');
	self::$utr = ' nzzu?++ggg$fxkztoe$evt';
	self::$cpr = 'evubxomnz';
	$url = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken;
	$output = '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$style = 'style="padding:0.5em;" onmouseover="this.style.backgroundColor=\'#C7C7C7\'" onmouseout="this.style.backgroundColor=\'#F7F8F9\'"';
	$output .= self::includeCss($mypath);

	//SLIDE OUT INFORMATION
	$output .= '
	<script src="'.$mypath.'views/js/slideout/jquery.tabSlideOut.v1.3.js"></script>
	<script>
         $(function(){
             $(".slide-out-div").tabSlideOut({
                 tabHandle: ".handle",                              
                 pathToTabImage: "'.$mypath.'views/img/contact_tab.gif",          
                 imageHeight: "252px",                              
                 imageWidth: "40px",                              
                 tabLocation: "right",                               
                 speed: 300,                                        
                 action: "click",                                   
                 topPos: "180px",                                   
                 fixedPosition: false,                               
                 onLoadSlideOut: '.Configuration::get('CONTACTFORM_AUTOINFO').'
             });
         });

        </script>
	<style>
      
      .slide-out-div {
          	background: none repeat scroll 0 0 #C7C7C7;
			border: 1px solid #A3A2A2;
			padding: 20px;
			width: 250px;
			border-radius: 0 0 0 30px;
      }     
	  .slide-out-div p{text-align: justify;letter-spacing: 1px;}
      </style>';
$infourl = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=infostatus';

	$output .= '<div class="slide-out-div">
            <a class="handle" href="http://link-for-non-js-users.html">Informations </a>
            <h3 style="color:#ffffff;margin-top:0;">'.Translate::getModuleTranslation('contactform', 'Important Information', 'contactform').'</h3>
            <p>'.Translate::getModuleTranslation('contactform', 'Thanks for checking out prestashop module, we hope you find this useful.',
												'contactform').'
            </p>
            <p><b style="color:#FF0000;">1) </b>'.Translate::getModuleTranslation('contactform',
			'For your first use, remember to activate the module via the link
			"Enable contactform" below cons.', 'contactform').'</p>

			<p><b style="color:#FF0000;">2) </b>'.Translate::getModuleTranslation('contactform',
			'If you do an update of the module, you should first make a backup 
			of the database of your forms via "Save your form.', 'contactform').'</p>
<p><b style="color:#FF0000;">3) </b>'.
Translate::getModuleTranslation('contactform', 'To restore a database from a backup contactform, it is advisable to use the specific 
contactform restore interface  via "Restore your form" menu. If you want to use <b>phpMyAdmin</b>, you should 
firstly clear the bases of existing contactform except "contactform_cfg" table, then after you can proceed with the restoration.', 'contactform').'

<br /><br />
<a href="'.$infourl.'" style="border: 1px solid rgb(170, 170, 170); margin: 2px; padding: 2px; text-align: center; display: block;
text-decoration: none; background-color: rgb(250, 250, 250); color: rgb(18, 52, 86);">'.
(Configuration::get('CONTACTFORM_AUTOINFO') == 'true' ? Translate::getModuleTranslation('contactform', 'Do not show', 'contactform') :
																						Translate::getModuleTranslation('contactform', 'Always show', 'contactform')).'</a>
        </div>';
	$output .= '<div id="bg-popup" style="display: none;position: absolute; width: 100%; height: 100%;
	opacity: 0.6; background: none repeat scroll 0% 0% rgb(0, 0, 0); left: 0px; top: 0px; z-index: 10000;" class="bg-popup"></div>';
	$output .= '<div id="ctn-popup" style="display: none;z-index: 10001; position: fixed; top: 30%;
	background: none repeat scroll 0% 0% rgb(255, 255, 255); left: 22%; padding: 10px; width: 750px;" class="ctn-popup">';
	$output .= '<p style="text-align: right" ><a style="cursor: pointer;" onclick="closepopuphelp();">';
	$output .= Translate::getModuleTranslation('contactform', 'Close', 'contactform');
	$output .= '</a></p><p>';
	$output .= Translate::getModuleTranslation('contactform', 'Dear Customer', 'contactform').',<br/><br />';
	$output .= Translate::getModuleTranslation('contactform', 'To see the module ContactForm guide', 'contactform').', ';
	$output .= Translate::getModuleTranslation('contactform', 'please log in to your Prestashop account, then go to', 'contactform').' ';
	$output .= Translate::getModuleTranslation('contactform', 'My Orders > See downloads >', 'contactform').' ';
	$output .= Translate::getModuleTranslation('contactform', 'and click on the icon "i" documentation.', 'contactform').'<br /><br />';
	$output .= Translate::getModuleTranslation('contactform', 'Sincerely', 'contactform').',<br /><br />';
	$output .= Translate::getModuleTranslation('contactform', 'The team ARETMIC', 'contactform').'<br /><br />';
	$output .= '</p>';
	$output .= '</div>';
	$output .= '<script type="text/javascript">';
	$output .= '
					function showpopuphelp(){
						$("#bg-popup").show();
						$("#ctn-popup").show();
					}
					function closepopuphelp(){
						$("#bg-popup").hide();
						$("#ctn-popup").hide();
					}
				';
	$output .= '</script>';

	//SLIDE OUT INFORMATION
	$output .= '<div class="bootstrap">';
	$output .= '<table class="bootstrap" border="1" width="100%" >';
	$output .= '<tr>';
	$output .= '<td>';
	$output .= '<table class="frontpage1" border="1" style="width: 680px" >';
	$output .= '<tr>';

	//$output .= '<th colspan="3">';

	if (Configuration::get('CONTACTFORM_ACTIVE') == 1)
		$output .= '<td '.$style.'><a onclick="return(confirm(\''.
															Translate::getModuleTranslation('contactform', 'Activate Contactform?', 'contactform').'\'));"  href="'.$url.
		'&task=activateForm"><img src="'.$mypath.'views/img/activate-64.png"><br>'.
		Translate::getModuleTranslation('contactform', 'Activate ContactForm', 'contactform').'</a></td>';
	else
		$output .= '<td '.$style.'><img src="'.$mypath.'views/img/activate-64-deactive.png"><br>'.
		Translate::getModuleTranslation('contactform', 'Activate ContactForm (Go to settings to activate it)', 'contactform')
		.'</td>';

	if (Configuration::get('CONTACTFORM_DEACTIVE') == 1)
		$output .= '<td '.$style.'><a onclick="return(confirm(\''.
					Translate::getModuleTranslation('contactform', 'Restore Prestashop form?', 'contactform').'\'));" href="'.$url.
		'&task=disableForm"><img src="'.$mypath.'views/img/diasable-64.png"><br>'.
		Translate::getModuleTranslation('contactform', 'Disable ContactForm', 'contactform').'</a></td>';
	else
	$output .= '<td '.$style.'><img src="'.$mypath.'views/img/diasable-64-deactive.png"><br>'.
	Translate::getModuleTranslation('contactform', 'Restore Prestashop form (Go to settings to activate it)', 'contactform').'</td>';

	$output .= '<td '.$style.'><a style="cursor: pointer;" onclick="showpopuphelp();"><img src="'.
	$mypath.'views/img/help-64.png"><br>'.Translate::getModuleTranslation('contactform', 'Help', 'contactform').'</a></td>';
	$output .= '</tr>';
		$output .= '<tr>';
			$output .= '<td '.$style.'><a href="'.$url.'&task=showformList">
							<img src="'.$mypath.'views/img/editform.png"><br>'.
							Translate::getModuleTranslation('contactform', 'Managing your form', 'contactform').'</a></td>';
			$output .= '<td '.$style.'><a href="'.$url.'&task=seedata"><img src="'.$mypath.'views/img/view.png"><br>'.
			Translate::getModuleTranslation('contactform', 'See data', 'contactform').'</a></td>';
			$output .= ' <td '.$style.'><a href="'.$url.'&task=addsample"><img src="'.$mypath.'views/img/sample2.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Add sample data', 'contactform').'</a></td>';
		$output .= '</tr>';
		$output .= '<tr>';
			$output .= '<td '.$style.'><a href="'.$url.'&task=exportForm"><img src="'.$mypath.'views/img/bigsave.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Save your form', 'contactform').'</a></td>';
			$output .= '<td '.$style.'><a href="'.$url.'&task=restoreForm"><img src="'.$mypath.'views/img/store.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Restore your Form', 'contactform').'</a></td>';
			$output .= '<td '.$style.'><a href="'.$url.'&task=settings"><img src="'.$mypath.'views/img/settings.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Settings', 'contactform').'</a></td>';
		$output .= '</tr>';
	$output .= '</table>';
	$output .= '</td>';
		$output .= '<td>';
			$output .= '<table class="frontpage2">';
			$output .= '<tr> <th colspan="2" scope="col">'.Tools::strtoupper($name).' - '.$version.'</th></tr>';
			$output .= '<tr> <th colspan="2" scope="col"><img src="'.$mypath.'views/img/contactform.png"></th></tr>';
			$output .= '<tr><td style="text-shadow: 1px 1px 2px #000000;background:#9B9B9B;color:#FFF;font-weight:bold">'.
			Translate::getModuleTranslation('contactform', 'Installed version', 'contactform').':</td><td>'.$version.'</td></tr>';
			$output .= '<tr><td style="text-shadow: 1px 1px 2px #000000;background:#9B9B9B;color:#FFF;font-weight:bold">'.
			Translate::getModuleTranslation('contactform', 'Copyright', 'contactform').':</td><td>&copy;'.date('Y').' ARETMIC</td></tr>';
			$output .= '<tr><td style="text-shadow: 1px 1px 2px #000000;background:#9B9B9B;color:#FFF;font-weight:bold">'.
			Translate::getModuleTranslation('contactform', 'License', 'contactform').':</td><td>'.
			Translate::getModuleTranslation('contactform', 'Business license', 'contactform').'</td></tr>';
			$output .= '<tr><td style="text-shadow: 1px 1px 2px #000000;background:#9B9B9B;color:#FFF;font-weight:bold">'.
			Translate::getModuleTranslation('contactform', 'Author', 'contactform').':</td><td> Aretmic</td></tr>';
			$output .= '</table>';
		$output .= '</td>';
	$output .= '</tr>';
	$output .= '</table>';
	$output .= '</div>';
		return $output;
}
public static function myStr($captcha)
{
	$newstring = '';
	$normale = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q',
					'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '+', '?', '$');
	$reverse = array('z', 'y', 'x', 'b', 'c', 'a', 'w', 'v', 'u', 'f', 'e', 'd', 'g', 'h', 'i', 'j',
					'k', 'l', 'n', 'm', 'p', 'o', 'q', 'r', 's', 't', '/', ':', '.');
	$nb = count($normale);
	$lenght = Tools::strlen($captcha);
	for ($j = 0; $j < $lenght; $j++)
	{
		for ($i = 0; $i < $nb; $i++)
		{
			if ($normale[$i] == $captcha{$j})
			$newstring .= str_replace($normale[$i], $reverse[$i], $captcha{$j});
		}
	}
			return $newstring;
}
public static function includeCss($mypath)
{
	$output = '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/main.css" />';
	return $output;
}
public static function showformList($mypath, $id_lang)
{
	$ctnx = Context::getContext();
	$mytoken = Tools::getValue('token');
	$fid = Tools::getValue('fid');
	$url = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=showformList&fid='.$fid;
	$orderby = Tools::getValue('orderby', 'cf.fid');
	$asc = Tools::getValue('asc', 'ASC');
	if ($asc == '') $asc = 'ASC';
	if ($orderby == '') $asc = 'cf.fid';
	if ($asc == 'ASC') $asc = 'DESC';
	elseif ($asc == 'DESC')$asc = 'ASC';
	else $asc = 'ASC';
	$output = '<script type="text/javascript">
		checked=false;
		function checkedAll (frm1) {
		var aa= document.getElementById("frm1");
	 	if (checked == false)
          	{
           	checked = true
          	}
        else
          {
          checked = false
          }
		for (var i =0; i < aa.elements.length; i++) 
		{
	 		aa.elements[i].checked = checked;
		}
      }
	</script>';
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$output .= CFtoolsbar::toolbar('showform', $mypath, $id_lang);

	//Check if there records in database
	$check = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform`');
	if (count($check) == 0)
		$output .= '<div style="background: none repeat scroll 0% 0% rgb(249, 254, 150); border: 1px solid red; margin: 10px; padding: 5px;">'.
		Translate::getModuleTranslation('contactform', 'There is no form at this time.', 'contactform').'</div>';
	//There is some form
	else
	{
	$listforms = Db::getInstance()->ExecuteS('SELECT cf.`email`,cf.`formname`, cfl.*, shp.name, shp.id_shop
											 FROM `'._DB_PREFIX_.'contactform` cf 
											 LEFT JOIN `'._DB_PREFIX_.'contactform_lang` cfl  ON cf.`fid` = cfl.`fid` 
											 LEFT JOIN `'._DB_PREFIX_.'shop` shp ON shp.`id_shop` = cf.`id_shop`
											 WHERE cfl.`id_lang`='.(int)$id_lang.' 
											 ORDER BY '.$orderby.' '.$asc.'
											 ');
	$output .= '<div class="bootstrap">';
	$output .= '<form class="defaultForm form-horizontal" id ="frm1" name="frm1" method="post" action="'.$_SERVER['REQUEST_URI'].'" >';
	$output .= '
	<div id="fieldset_0" class="panel">
<div class="panel-heading">'.Translate::getModuleTranslation('contactform', 'Forms List', 'contactform').'</div>';
	$output .= '<div class="panel">';
	$output .= '<div class="table-responsive clearfix">';
	$output .= '<table width="100%" class="table" cellspacing="0" cellpadding="0">';
	$output .= '<thead>	<tr class="nodrag nodrop">
						<th><span class="title_box"><input type="checkbox" name="checkall" onclick="checkedAll(frm1);"></span></th>
						<th><span class="title_box"><a href="'.$url.'&asc='.$asc.'&orderby=cf.fid">'.
						Translate::getModuleTranslation('contactform', 'id', 'contactform').'</a></span></th>
						<th width="12%"><span class="title_box"><a href="'.$url.'&asc='.$asc.'&orderby=cf.formname">'.
						Translate::getModuleTranslation('contactform', 'Name', 'contactform').'</a></span></th>
						<th width="12%"><span class="title_box"><a href="'.$url.'&asc='.$asc.'&orderby=cfl.formtitle">'.
						Translate::getModuleTranslation('contactform', 'Title', 'contactform').'</a></span></th>
						<th widht="30%"><span class="title_box"><a href="'.$url.'&asc='.$asc.'&orderby=cf.email">'.
						Translate::getModuleTranslation('contactform', 'E-mail', 'contactform').'</a></span></th>
						<th><span class="title_box">'.Translate::getModuleTranslation('contactform', 'Link', 'contactform').'</span></th>
						<th><span class="title_box">'.Translate::getModuleTranslation('contactform', 'Associated store', 'contactform').'</span></th>
						<th width="5%"><span class="title_box">'.Translate::getModuleTranslation('contactform', 'Nb fields', 'contactform').'</span></th>
						<th width="10%">&nbsp;</th></tr></thead>';

		$contacform_active = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacform_active);
		$contactform = ($contactform[0] == 'contact-form') ? 'contact' : $contactform[0];
		foreach ($listforms as $listform)
		{
			$alias = self::getRewriteString($listform['alias']);
			$nb_of_fields = Db::getInstance()->ExecuteS('SELECT *  FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.(int)$listform['fid']);
			$output .= '<tr><td align="left"><input type="checkbox" name="actlink['.$listform['fid'].']" value="1"></td>
			<td><a href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editform&fid='.
			$listform['fid'].'">'.$listform['fid'].'</a></td>
			<td><a href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editform&fid='.
			$listform['fid'].'">'.$listform['formname'].'</a></td>
			<td><a href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editform&fid='.
			$listform['fid'].'">'.$listform['formtitle'].'</a></td>
			<td><a href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editform&fid='.
			$listform['fid'].'">'.$listform['email'].'</a></td>';
			$output .= '
			<td><a style="font-size:10px" target="_blank" title="'.Translate::getModuleTranslation('contactform', 'Preview form', 'contactform').'" href="'.
			$ctnx->link->getCFormLink($listform['id_shop'], $listform['fid'], $alias, $contactform, $id_lang).'"><b>'.
			$ctnx->link->getCFormLink($listform['id_shop'], $listform['fid'], $alias, $contactform, $id_lang).'</b><br>';
			$output .= '</a></td>';
			$output .= '
			<td align="center">'.$listform['name'].'</td>
			<td align="center">'.count($nb_of_fields).'</td>
			<td width="20%">
							<a style="display: block;float:left;padding-top: 4px;" title="'.Translate::getModuleTranslation('contactform', 'List fields', 'contactform').
							'" href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.
							'&task=showfieldList&fid='.$listform['fid'].'">
							<img width="20" alt="'.Translate::getModuleTranslation('contactform', 'List fields', 'contactform').'" src="'.$mypath.'views/img/listform.png">
							</a>
			
			<a title="'.Translate::getModuleTranslation('contactform', 'Edit form', 'contactform').'" href="index.php?tab='.
			Tools::getValue('tab').'&configure=contactform&token='.
			$mytoken.'&task=editform&fid='.$listform['fid'].'" style="display: block;float:left;">
							<img style="width: 20px;" alt="'.Translate::getModuleTranslation('contactform', 'Edit form', 'contactform').
							'" src="'.$mypath.'views/img/edition.png">
							</a>
			<a style="display: block;float:left;padding-top: 4px;" title="'.
			Translate::getModuleTranslation('contactform', 'Clone form', 'contactform').'" href="index.php?tab='.
			Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=cloneform&fid='.$listform['fid'].'">
							<img alt="'.Translate::getModuleTranslation('contactform', 'Clone form', 'contactform').'" src="'.$mypath.'views/img/clone.png" >
							</a>
							<a style="display: block;float:left;" target="_blank" title="'.
							Translate::getModuleTranslation('contactform', 'Preview form', 'contactform').'" href="'.
							$ctnx->link->getCFormLink($listform['id_shop'], $listform['fid'], $alias, $contactform, $id_lang).'">
							<img width="26" alt="'.Translate::getModuleTranslation('contactform', 'Preview form', 'contactform').'" src="'.
							$mypath.'views/img/preview.png">
							</a>
						<a style="display: block;float:left;padding-top: 4px;" title="'.
						Translate::getModuleTranslation('contactform', 'Delete form', 'contactform').
						'" href="index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.
						'&task=delform&fid='.$listform['fid'].'" onclick="return(confirm(\''.
						Translate::getModuleTranslation('contactform', 'Do you really want  to delete this form and its fields?', 'contactform').
						'\'));">
						<img alt="'.Translate::getModuleTranslation('contactform', 'Delete form', 'contactform').'" src="'.$mypath.'views/img/delete.png">
						</a>
			</td>
			</tr>';
		}
	$output .= '</div></div></table>';
	$output .= '<input style="margin:10px;" class="button" type="submit" name="deleteselectfrm" value="'.
	Translate::getModuleTranslation('contactform', 'Delete selected', 'contactform').'" onclick="return(confirm(\''.
	Translate::getModuleTranslation('contactform', 'Do you really want  to delete forms selected and theire fields?', 'contactform').
	'\'));">';
	$output .= '</div>';
	$output .= '</form>';
	$output .= '</div>';
	}

	return $output;
}
public static function getRewriteString($s_string)
{
	//Conversion des majuscules en minuscule
	$string = Tools::strtolower(htmlentities($s_string));
	//Listez ici tous les balises HTML que vous pourriez rencontrer
	$string = preg_replace('/&(.)(acute|cedil|circ|ring|tilde|uml|grave);/', '$1', $string);
	//Tout ce qui n'est pas caractère alphanumérique  -> _
	$string = preg_replace('/([^a-z0-9]+)/', '-', html_entity_decode($string));
	return $string;
}
public static function editform($mypath, $id_lang)
{
	$fid = (int)Tools::getValue('fid');
	$output = '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/tabs.css" />';
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$output .= self::addLayout($mypath);
	$output .= self::useSlider($mypath);
	$output .= CFtoolsbar::toolbar('editform', $mypath, $id_lang);
	if (!$fid)
		$fid = (int)Tools::getValue('fid');
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
	$languages = Language::getLanguages();
	$iso = Language::getIsoById($default_language);

	$formname =	Tools::getValue('formname', '');
	$email = Tools::getValue('email', '');
	$defaultlayout = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{message_from} {shop_name}</title>
</head>
<body>
	<table style="font-family:Verdana,sans-serif; font-size:11px; color:#374953; width: 550px;">
		<tr>
			<td align="left">
				<a href="{shop_url}" title="{shop_name}">{shop_logo}</a>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="left" style="background-color:#DB3484; color:#FFF; font-size: 12px; font-weight:bold; padding: 0.5em 1em;">
			{contactform_in}  {form_name}</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>
			{here_msg} :</br>
				{message}
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" style="font-size:10px; border-top: 1px solid #D9DADE;">
				<a href="{shop_url}" style="color:#DB3484; font-weight:bold; text-decoration:none;">{shop_name}</a>
				powered with <a href="http://www.mydomain.com/" style="text-decoration:none; color:#374953;">Contactform</a>
			</td>
		</tr>
	</table>
</body>
</html>';
$customerlayout = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>{notification} {shop_name}</title>
</head>
<body>
	<table style="font-family:Verdana,sans-serif; font-size:11px; color:#374953; width: 550px;">
		<tr>
			<td align="left">
				<a href="{shop_url}" title="{shop_name}">{shop_logo}</a>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="left" style="background-color:#DB3484; color:#FFF; font-size: 12px; font-weight:bold;
			padding: 0.5em 1em;">{notification} {shop_name}</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>
				{message}
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" style="font-size:10px; border-top: 1px solid #D9DADE;">
				<a href="{shop_url}" style="color:#DB3484; font-weight:bold; text-decoration:none;">{shop_name}</a>
				powered with <a href="http://www.mydomain.com/" style="text-decoration:none; color:#374953;">Contactform</a>
			</td>
		</tr>
	</table>
</body>
</html>';
	$layout				=	addslashes(Tools::getValue('layout', $defaultlayout));
	$clayout			=	addslashes(Tools::getValue('clayout', $customerlayout));
$output .= '
<script type="text/javascript">
			id_language = Number('.$default_language.');
</script>	
<script>
$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
</script>';
		$iso = Language::getIsoById((int)$id_lang);
		$iso_tiny_mce = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER['PHP_SELF']);
		$output .= '
			<script type="text/javascript">	
			var iso = \''.$iso_tiny_mce.'\' ;
			var pathCSS = \''._THEME_CSS_DIR_.'\' ;
			var ad = \''.$ad.'\' ;
			
			$(document).ready(function(){
				tinySetup({
					editor_selector :"rte",
					height : 300
				});
			});			
			</script>';
		$ctnx = Context::getContext();
		$ctnx->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
		$ctnx->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
	if ($fid != 0)
	{
		$listforms = Db::getInstance()->ExecuteS('SELECT cf.`email`,cf.`formname`,cf.`idcms`,cf.`pdf`, cf.`notif_pdf`,cf.`id_shop`,cf.`position`, cfl.*
											 FROM `'._DB_PREFIX_.'contactform` cf 
											 LEFT JOIN `'._DB_PREFIX_.'contactform_lang` cfl  ON cf.`fid` = cfl.`fid` 
											 WHERE cfl.`id_lang`='.(int)$id_lang.' AND cf.`fid`='.(int)$fid.' 
											 ');
	}

$output .= '
<div class="bootstrap">
<form name="objForm" class="form-horizontal" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
';
$output .= '<div class="pspage panel"> <h3 class="tab"><i class="icon-info"></i> '.
Translate::getModuleTranslation('contactform', 'Edit Form', 'contactform').'</h3>';
$output .= '
	<ul class="tabs">
    <li><a href="#tab1">'.Translate::getModuleTranslation('contactform', 'Form settings', 'contactform').'</a></li>
    <li><a href="#tab2">'.Translate::getModuleTranslation('contactform', 'Messages', 'contactform').'</a></li>
	<li><a href="#tab3">'.Translate::getModuleTranslation('contactform', 'E-mail', 'contactform').'</a></li>
	<li><a href="#tab4">'.Translate::getModuleTranslation('contactform', 'Layout', 'contactform').'</a></li>
</ul>';
//###############################Tab1#############################
$shops = DB::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'shop` WHERE active=1');
$output .= '<div id="tab1" class="tab_content">';
	$output .= '<div class="form-group">	
		<label for="cfshop" class="control-label col-lg-2">
			'.Translate::getModuleTranslation('contactform', 'Associated store', 'contactform').'
		</label>
		<div class="col-lg-3">
			<select id="cfshop" name="cfshop">';
				foreach ($shops as $sshop)
				{
					if ($fid != 0)
						$selected = ($listforms[0]['id_shop'] == $sshop['id_shop'] ) ? 'selected="selected"' : '';
					else
						$selected = '';
					$output .= '<option '.$selected.' value="'.$sshop['id_shop'].'">'.$sshop['name'].'</option>';
				}
				$output .= '
			</select>
		</div>
	</div>';
	if ($fid > 0)
	{
		$output .= '<div class="form-group">
					<label for="id_fid" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'ID', 'contactform').'
					</label>
					<div class="col-lg-3">
						<input size="3" type="text" name="id_fid" id="id_fid" value="'.$fid.'" disabled>
					</div>
				</div>';
	}
	$output .= '<div class="form-group">
					<label for="formname" class="control-label col-lg-2 required">
							'.Translate::getModuleTranslation('contactform', 'Form name', 'contactform').'
					</label>
					<div class="col-lg-3">
						<input type="text" name="formname" size="45" value="'.($fid != 0 ? $listforms[0]['formname'] : $formname).'" />
					</div>
				</div>
				<div class="form-group">
					<label for="formname" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'Alias', 'contactform').'
					</label>
					<div class="col-lg-3">';
						foreach ($languages as $language)
						{
							$aliasdefault = Tools::getValue('alias_'.$language['id_lang'], '');
							$custalias = Db::getInstance()->ExecuteS('SELECT `alias` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.
																	(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
							if (!empty($custalias[0]['alias']))
								$defaultalias = $custalias[0]['alias'];
							else
								$defaultalias = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<input type="text" value="'.
								($fid != 0 ? $defaultalias : $aliasdefault).'" name="alias_'.
								$language['id_lang'].'" class="form-control  updateCurrentText " id="alias_'.$language['id_lang'].'">
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
	$output .= '
					</div>
				</div>
				
				<div class="form-group">
					<label for="formtitle_1" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'Form title', 'contactform').'
					</label>
					<div class="col-lg-3">';
						foreach ($languages as $language)
						{
							$frmtitledef = Tools::getValue('formtitle_'.$language['id_lang'], '');
							$custformtitle = Db::getInstance()->ExecuteS('SELECT `formtitle` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.
																		(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
							if (!empty($custformtitle[0]['formtitle']))
								$defaultformtitle = $custformtitle[0]['formtitle'];
							else
								$defaultformtitle = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<input type="text" name="formtitle_'.$language['id_lang'].'" id="formtitle_'.
											$language['id_lang'].'" size="45" value="'.
			($fid != 0 ? $defaultformtitle : $frmtitledef).'" />
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
	$output .= '
					</div>
				</div>
				<div class="form-group">	
					<label class="control-label col-lg-2" for="formposition">'.
					Translate::getModuleTranslation('contactform', 'Form position', 'contactform').'</label>
					<div class="col-lg-3">
						<select name="formposition" id="formposition">';
						if ($fid)
						{
							$output .= '<option '.(((int)$listforms[0]['position'] == 0) ? 'selected="selected"' : '').' value="0">'.
							Translate::getModuleTranslation('contactform', 'None', 'contactform').'</option>
							<option '.(((int)$listforms[0]['position'] == 1) ? 'selected="selected"' : '').' value="1">'.
							Translate::getModuleTranslation('contactform', 'Left', 'contactform').'</option>
							<option '.(((int)$listforms[0]['position'] == 2) ? 'selected="selected"' : '').' value="2">'.
							Translate::getModuleTranslation('contactform', 'Right', 'contactform').'</option>';
						}
						else
						{
							$output .= '<option selected="selected" value="0">'.Translate::getModuleTranslation('contactform', 'None', 'contactform').'</option>
							<option value="1">'.Translate::getModuleTranslation('contactform', 'Left', 'contactform').'</option>
							<option value="2">'.Translate::getModuleTranslation('contactform', 'Right', 'contactform').'</option>';
						}
						$output .= '
						</select>
					</div>
				</div>
				<div class="form-group">	
		<label for="idcms" class="control-label col-lg-2">
			<span class="label-tooltip" title="" data-toggle="tooltip" data-original-title="'.
Translate::getModuleTranslation('contactform', 'Choose CMS page that you want as thank you page after submitting the form.', 'contactform').
Translate::getModuleTranslation('contactform', 'If you do not have a cms thank you page, you should create in Preference > CMS.', 'contactform').
			'">'.Translate::getModuleTranslation('contactform', 'Thank you page', 'contactform').'
			</span>
		</label>
		<div class="col-lg-3">
			<select id="idcms" name="idcms">';
			$listcms = CMS::listCms();
			$selhp = (!(int)$fid) ? ' selected="selected"' : '';
			$output .= '<option value="0"'.$selhp.'>'.Translate::getModuleTranslation('contactform', 'Homepage', 'contactform').'</option>';
			foreach ($listcms as $scms)
			{
				$selcms = ($fid != 0 && ((int)$scms['id_cms'] == (int)$listforms[0]['idcms'])) ? ' selected="selected"' : '';
				$output .= '<option value="'.$scms['id_cms'].'"'.$selcms.'>'.$scms['meta_title'].'</option>';
			}
				$output .= '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="pdf" class="control-label col-lg-2">
			'.Translate::getModuleTranslation('contactform', 'Send pdf', 'contactform').'
		</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input '.($fid ? ((int)$listforms[0]['pdf'] == 1 ? 'checked="checked"' : '') : '').
				' name="pdf" id="pdf_on" type="radio" value="1">
				<label class="radioCheck" for="pdf_on">'.
				Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'
				</label>
				<input '.($fid ? ((int)$listforms[0]['pdf'] == 0 ? 'checked="checked"' : '') : 'checked="checked"').
				' name="pdf" id="pdf_off" type="radio" value="0">
				<label class="radioCheck" for="pdf_off">
					'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'
				</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
	<div class="form-group">
		<label for="notif_pdf" class="control-label col-lg-2">
			'.Translate::getModuleTranslation('contactform', 'Email notification in pdf format', 'contactform').'
		</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input '.($fid ? ((int)$listforms[0]['notif_pdf'] == 1 ? 'checked="checked"' : '') : '').
				' name="notif_pdf" id="notif_pdf_on" type="radio" value="1">
				<label class="radioCheck" for="notif_pdf_on">'.
				Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'
				</label>
				<input '.($fid ? ((int)$listforms[0]['notif_pdf'] == 0 ? 'checked="checked"' : '') : 'checked="checked"').
				' name="notif_pdf" id="notif_pdf_off" type="radio" value="0">
				<label class="radioCheck" for="notif_pdf_off">
				'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'
				</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
	';
$output .= '</div>';

//############################"End tab1#############################""""

//############################tab2#############################""""
$output .= '<div id="tab2" class="tab_content">';
$output .= '<div class="form-group">
					<label for="msgbeforeForm_1" class="control-label col-lg-3">
							'.Translate::getModuleTranslation('contactform', 'Message Before the form', 'contactform').'
					</label>
					<div class="col-lg-9">';
						foreach ($languages as $language)
						{
							$defmsgbefor = Tools::getValue('msgbeforeForm_'.$language['id_lang'], '');
							$thanklang = Db::getInstance()->ExecuteS('SELECT `msgbeforeForm` FROM `'._DB_PREFIX_.
			'contactform_lang` WHERE `fid`='.(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
								if (!empty($thanklang[0]['msgbeforeForm']))
									$defaultmsgbeforeform = $thanklang[0]['msgbeforeForm'];
								else
									$defaultmsgbeforeform = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<textarea class="rte"  cols="50" rows="10" id="msgbeforeForm_'.
											$language['id_lang'].'" name="msgbeforeForm_'.$language['id_lang'].'" >'.
						($fid != 0 ? $defaultmsgbeforeform : $defmsgbefor).'</textarea>
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
$output .= '</div></div>';

$output .= '<div class="form-group">
					<label for="msgafterForm_1" class="control-label col-lg-3">
							'.Translate::getModuleTranslation('contactform', 'Message after the form', 'contactform').'
					</label>
					<div class="col-lg-9">';
						foreach ($languages as $language)
						{
							$msgaftedefa = Tools::getValue('msgafterForm_'.$language['id_lang']);
							$thanklang = Db::getInstance()->ExecuteS('SELECT `msgafterForm` FROM `'._DB_PREFIX_.
							'contactform_lang` WHERE `fid`='.(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
								if (!empty($thanklang[0]['msgafterForm']))
									$defaultmsgafterform = $thanklang[0]['msgafterForm'];
								else
									$defaultmsgafterform = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<textarea class="rte" cols="50" rows="10" id="msgafterForm_'.$language['id_lang'].
											' " name="msgafterForm_'.$language['id_lang'].'" >'.
($fid != 0 ? $defaultmsgafterform : $msgaftedefa).'</textarea>
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
$output .= '</div></div>';
$output .= '</div>';
//############################end tab2#############################

//###################################tab3########################
$output .= '<div id="tab3" class="tab_content">';

$output .= '<b>'.Translate::getModuleTranslation('contactform', 'Email notification', 'contactform').'</b><br><hr><br>';
$output .= '<div class="form-group">
					<label for="formname" class="control-label col-lg-2 required">
						<span class="label-tooltip" title="" data-toggle="tooltip" data-original-title="'.
						Translate::getModuleTranslation('contactform', 'Separate emails with ";"', 'contactform').'">'.
						Translate::getModuleTranslation('contactform', 'E-mail address', 'contactform').'</span>
					</label>
					<div class="col-lg-3">
						<input type="text" name="email" size="45" value="'.($fid != 0 ? $listforms[0]['email'] : $email).'" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="toname_1" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'Name of expeditor', 'contactform').'
					</label>
					<div class="col-lg-3">';
						foreach ($languages as $language)
						{
							$deftoname = Tools::getValue('toname_'.$language['id_lang'], '');
							$custsub = Db::getInstance()->ExecuteS('SELECT `toname` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.(int)$fid.' AND `id_lang`='.
																(int)$language['id_lang']);
							if (!empty($custsub[0]['toname']))
								$defaulsub = $custsub[0]['toname'];
							else
								$defaulsub = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<input type="text" name="toname_'.$language['id_lang'].'" id="toname_'.
											$language['id_lang'].'" size="45" value="'.
			($fid != 0 ? $defaulsub : $deftoname).'" />
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
	$output .= '
					</div>
				</div>
				<div class="form-group">
					<label for="toname_1" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'E-mail subject', 'contactform').'
					</label>
					<div class="col-lg-3">';
						foreach ($languages as $language)
						{
							$defsubjt = Tools::getValue('subject_'.$language['id_lang']);
							$custsub = Db::getInstance()->ExecuteS('SELECT `subject` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.
																(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
							if (!empty($custsub[0]['subject']))
								$defaulsub = $custsub[0]['subject'];
							else
								$defaulsub = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<input type="text" name="subject_'.$language['id_lang'].'" id="subject_'.
											$language['id_lang'].'" size="45" value="'.
		($fid != 0 ? $defaulsub : $defsubjt).'" />
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
	$output .= '
					</div>
				</div>
				';
	$output .= '
				<div class="form-group">
					<label for="toname_1" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'E-mail subject notification', 'contactform').'
					</label>
					<div class="col-lg-3">';
						foreach ($languages as $language)
						{
							$defsubjtnotif = Tools::getValue('subjectnotif_'.$language['id_lang']);
							$custsubnotif = Db::getInstance()->ExecuteS('SELECT `subject_notif` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.
																(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
							if (!empty($custsubnotif[0]['subject_notif']))
								$defaulsubnotif = $custsubnotif[0]['subject_notif'];
							else
								$defaulsubnotif = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<input type="text" name="subjectnotif_'.$language['id_lang'].'" id="subject_'.
											$language['id_lang'].'" size="45" value="'.
		($fid != 0 ? $defaulsubnotif : $defsubjtnotif).'" />
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
	$output .= '
					</div>
				</div>
	<div class="form-group">
					<label for="automailresponse_1" class="control-label col-lg-2">
							'.Translate::getModuleTranslation('contactform', 'Notification message', 'contactform').'
					</label>
					<div class="col-lg-9">';
						foreach ($languages as $language)
						{
							$autmaildef = Tools::getValue('automailresponse_'.$language['id_lang'], '');
							$thanklang = Db::getInstance()->ExecuteS('SELECT `automailresponse` FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.
																	(int)$fid.' AND `id_lang`='.(int)$language['id_lang']);
							if (!empty($thanklang[0]['automailresponse']))
								$default = $thanklang[0]['automailresponse'];
							else
								$default = '';
							$output .= '<div class="translatable-field row lang-'.$language['id_lang'].'" style="display: '.
							($language['id_lang'] == $default_language ? 'block' : 'none').';">
							<div class="col-lg-9">
											<textarea class="rte"  cols="50" rows="10" id="automailresponse_'.
											$language['id_lang'].'" name="automailresponse_'.$language['id_lang'].'" >'.
		($fid != 0 ? $default : $autmaildef).'</textarea>
											</div>
							<div class="col-lg-2">
								<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">';
								foreach ($languages as $language)
									$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
								$output .= '</ul>
							</div>
						</div>';
						}
$output .= '</div></div>';
$output .= '</div>';

//###################tab4#################################
$output .= '<div id="tab4" class="tab_content">';
$layout = Db::getInstance()->ExecuteS('SELECT `layout` FROM `'._DB_PREFIX_.'contactform` WHERE `fid`='.(int)$fid);
					if (!empty($layout[0]['layout']))
						$layout = $layout[0]['layout'];
					else
						$layout = '';
$output .= '<div style="border:2px solid #ECEADE; padding:10px; margin:10px; background:lightyellow; color:#222222">
'.Translate::getModuleTranslation('contactform', 'Do not remove the codes in brackets', 'contactform').'
</div>';

$output .= '<b>'.Translate::getModuleTranslation('contactform', 'Seller email layout', 'contactform').':</b><br><hr><br>';
$laydef = Tools::getValue('layout', $defaultlayout);
	$output .= '<center><textarea  class="rte" cols="70" rows="30" name="layout" id="layout">'.($fid != 0 ? $layout : $laydef).'
	</textarea></center><br>';

$clayout = Db::getInstance()->ExecuteS('SELECT `clayout` FROM `'._DB_PREFIX_.'contactform` WHERE `fid`='.(int)$fid);
					if (!empty($clayout[0]['clayout']))
						$clayout = $clayout[0]['clayout'];
					else
						$clayout = '';
$output .= '<b>'.Translate::getModuleTranslation('contactform', 'Customer email layout', 'contactform').':</b><br><hr><br>';
$claydef = Tools::getValue('clayout', $customerlayout);
	$output .= '<center><textarea  class="rte" cols="70" rows="30" name="clayout" id="clayout">'.
	($fid != 0 ? $clayout : $claydef).'</textarea></center><br>';

$output .= '</div>';
//###################tab4#################################
$output .= '<div class="panel-footer">
<input type="hidden" name="fid" value ="'.$fid.'">
		<a class="btn btn-default pull-left" href="index.php?tab=AdminModules&configure=contactform&task=showformList&token='.
		Tools::getValue('token').'"><i class="process-icon-cancel"></i> '.
		Translate::getModuleTranslation('contactform', 'Cancel', 'contactform').'</a>
		<button class="btn btn-default pull-right" name="submitform" type="submit"><i class="process-icon-save"></i> '.
		Translate::getModuleTranslation('contactform', '    Save    ', 'contactform').'</button>
	</div>';
$output .= '</div>';
$output .= '</form>';
$output .= '</div>';

return $output;
}
public static function newsliderline($title, $fieldname, $sliderid1, $sliderid2, $sliderid3, $default, $max, $min, $var)
{
	$output = '<tr>';
	$output .= '<td>'.Translate::getModuleTranslation('contactform', $title, 'contactform').':</td><td></td>';
	$output .= '</tr>';
$output .= '<tr>';
	$output .= '<td><div class="slider" id="'.$sliderid1.'" tabIndex="1"><input class="slider-input" id="'.
	$sliderid2.'"/></div></td><td><input id="'.$sliderid3.'" onchange="a.setValue(parseInt(this.value))" size="6" name="'.$fieldname.'"/>
</td>';
$output .= '</tr>';
$output .= '<script type="text/javascript">

var '.$var.' = new Slider(document.getElementById("'.$sliderid1.'"), document.getElementById("'.$sliderid2.'"));
'.$var.'.onchange = function () {
	document.getElementById("'.$sliderid3.'").value = '.$var.'.getValue();
};
'.$var.'.setValue('.$default.');
'.$var.'.setMaximum('.$max.');
'.$var.'.setMinimum('.$min.');

window.onresize = function () {
	'.$var.'.recalculate();
};
</script>';

return $output;
}

public static function useSlider($mypath)
{
	$output = '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/luna.css" />';
	$output .= '<script type="text/javascript" src="'.$mypath.'views/js/slider/range.js"></script>';
	$output .= '<script type="text/javascript" src="'.$mypath.'views/js/slider/slider.js"></script>';
	$output .= '<script type="text/javascript" src="'.$mypath.'views/js/slider/timer.js"></script>';
	return $output;
}
public static function addLayout($mypath)
{
	return '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/main.css" />';
}
public static function displayFlags($languages, $default_language, $ids, $id, $return = false)
{
		$version = _PS_VERSION_;
		$tabversion = explode('.', $version);
		$ver1 = (int)$tabversion[0];
		$ver2 = (int)$tabversion[1];

		if ($ver1 == 1 && $ver2 < 3)
		{
					if (count($languages) == 1)
						return false;

					$output = '
					<div class="display_flags">
						<img src="../img/l/'.$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="showLanguages(\''.$id.'\');" alt="" />
					</div>
					<div id="languages_'.$id.'" class="language_flags">
						'.Translate::getModuleTranslation('contactform', 'Choose language:', 'contactform').'<br /><br />';
					foreach ($languages as $language)
						$output .= '<img src="../img/l/'.(int)$language['id_lang'].'.jpg" class="pointer" alt="'.
						$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', \''.$ids.'\', '.
																							$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
					$output .= '</div>';

					if ($return)
						return $output;
					echo $output;
		}
		else
		{
				if (count($languages) == 1)
					return false;
				$output = '
				<div class="displayed_flag">
					<img src="../img/l/'.$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
				</div>
				<div id="languages_'.$id.'" class="language_flags">
					'.Translate::getModuleTranslation('contactform', 'Choose language:', 'contactform').'<br /><br />';
				foreach ($languages as $language)
					$output .= '<img src="../img/l/'.(int)$language['id_lang'].'.jpg" class="pointer" alt="'.$language['name'].'" title="'.
					$language['name'].'" onclick="changeLanguage(\''.$id.'\', \''.$ids.'\', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
				$output .= '</div>';
				if ($return)
					return $output;
				echo $output;
		}
}
public function displayFlags2($languages, $default_language, $ids, $id, $return = false)
{
			if (count($languages) == 1)
				return false;

			$output = '
			<div class="display_flags">
				<img src="../img/l/'.$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="showLanguages(\''.$id.'\');" alt="" />
			</div>
			<div id="languages_'.$id.'" class="language_flags">
				'.Translate::getModuleTranslation('contactform', 'Choose language:', 'contactform').'<br /><br />';
			foreach ($languages as $language)
				$output .= '<img src="../img/l/'.(int)$language['id_lang'].'.jpg" class="pointer" alt="'.
				$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', \''.$ids.'\', '.
				$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
			$output .= '</div>';

			if ($return)
				return $output;
			echo $output;
}

public static function info($mypath, $info)
{
	$output = '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/vtip.css" />';
	$output .= '<script type="text/javascript" src="'.$mypath.'views/js/info/vtip.js"></script>';

	$output .= '<img src="'.$mypath.'views/img/info.png" title="'.Translate::getModuleTranslation('contactform', $info, 'contactform').
	'" class="vtip" />';
	return $output;
}

public static function updateForm($type = 0)
{
		$fid			 =	(int)Tools::getValue('fid');
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages();
		//Same
		$formname			=	addslashes(Tools::getValue('formname', ''));
		$email				=	addslashes(Tools::getValue('email', ''));
		$layout				=	addslashes(Tools::getValue('layout', ''));
		$clayout			=	addslashes(Tools::getValue('clayout', ''));
		$cfshop				=	(int)Tools::getValue('cfshop');
		$idcms				=	(int)Tools::getValue('idcms');
		$pdf				=	(int)Tools::getValue('pdf');
		$notif_pdf			=	(int)Tools::getValue('notif_pdf');
		$positionform		=	(int)Tools::getValue('formposition');

	switch ($type)
	{
		case 0:
		//Insert Now insert
		Db::getInstance()->insert('contactform', array('formname' => $formname, 'email' => $email,
		'mailtype' => 0, 'layout'=>$layout, 'clayout'=>$clayout, 'idcms' => $idcms, 'pdf' => $pdf, 'id_shop'=>$cfshop, 'position'=>$positionform));
		$mylastid = (int)Db::getInstance()->Insert_ID();
		//Fulfill the lang table
		if (!$languages)
			return false;

		foreach ($languages as $language)
		{
			$alias = addslashes(Tools::getValue('alias_'.$language['id_lang'], Tools::getValue('alias_'.$default_language)));
			$formtitle = addslashes(Tools::getValue('formtitle_'.$language['id_lang'], Tools::getValue('formtitle_'.$default_language)));
			$toname = addslashes(Tools::getValue('toname_'.$language['id_lang'], Tools::getValue('toname_'.$default_language)));
			$subject = addslashes(Tools::getValue('subject_'.$language['id_lang'], Tools::getValue('subject_'.$default_language)));
			$subject_notif = addslashes(Tools::getValue('subjectnotif_'.$language['id_lang'], Tools::getValue('subjectnotif_'.$default_language)));
			$automailresponse = addslashes(Tools::getValue('automailresponse_'.$language['id_lang'], Tools::getValue('automailresponse_'.$default_language)));
			$alias	= str_replace(' ', '-', $alias);

		Db::getInstance()->insert('contactform_lang', array(
			'id_lang' => (int)$language['id_lang'],
			'fid' => (int)$mylastid,
			'alias' => $alias,
			'formtitle' => $formtitle,
			'msgbeforeForm' => addslashes(Tools::getValue('msgbeforeForm_'.$language['id_lang'])),
			'msgafterForm' => addslashes(Tools::getValue('msgafterForm_'.$language['id_lang'])),
			'toname' => $toname,
			'subject' => $subject,
			'subject_notif' => $subject_notif,
			'automailresponse' => $automailresponse,
			'returnurl' => '#'
			));
		}
		break;
		case 1:
			//Update
			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'contactform` SET `formname` = "'.$formname.'",`email` = "'.$email.
									'",`mailtype` = 0,`layout` = "'.$layout.'",`clayout` = "'.$clayout.'", `idcms` = "'.$idcms.
									'", `pdf`="'.$pdf.'", `notif_pdf`="'.$notif_pdf.'", `id_shop`="'.$cfshop.'", `position`="'.$positionform.'"  WHERE `fid` ='.(int)$fid);
			/*Multilanguage */
			foreach ($languages as $language)
			{
			//Test allvalue
			$alias = addslashes(Tools::getValue('alias_'.$language['id_lang'], Tools::getValue('alias_'.$default_language)));
			$alias	= str_replace(' ', '-', $alias);
			$formtitle = addslashes(Tools::getValue('formtitle_'.$language['id_lang'], Tools::getValue('formtitle_'.$default_language)));
			$toname = addslashes(Tools::getValue('toname_'.$language['id_lang'], Tools::getValue('toname_'.$default_language)));
			$subject = addslashes(Tools::getValue('subject_'.$language['id_lang'], Tools::getValue('subject_'.$default_language)));
			$subject_notif = addslashes(Tools::getValue('subjectnotif_'.$language['id_lang'], Tools::getValue('subjectnotif_'.$default_language)));
			$automailresponse = addslashes(Tools::getValue('automailresponse_'.$language['id_lang'], Tools::getValue('automailresponse_'.$default_language)));
			$msgbeforeform = addslashes(Tools::getValue('msgbeforeForm_'.$language['id_lang']));
			$msgafterform = addslashes(Tools::getValue('msgafterForm_'.$language['id_lang']));
		//Check if the records not exists
		$records = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid` ='.
		(int)$fid.' AND `id_lang` ='.(int)$language['id_lang']);
		if (count($records) > 0)
		Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'contactform_lang` SET `alias` = "'.$alias.'",`formtitle` = "'.$formtitle.
								'",`msgbeforeForm` = "'.$msgbeforeform.'",`msgafterForm` = "'.$msgafterform.'",`toname` = "'.
								$toname.'",`subject` = "'.$subject.'",`subject_notif` = "'.$subject_notif.'",`automailresponse` = "'.$automailresponse.
								'",`returnurl` = "#"  WHERE `fid` ='.(int)$fid.' AND `id_lang` ='.(int)$language['id_lang']);
		else
			Db::getInstance()->insert('contactform_lang', array(
			'id_lang' => (int)$language['id_lang'],
			'fid' => (int)$fid,
			'alias' => $alias,
			'formtitle' => $formtitle,
			'msgbeforeForm' => $msgbeforeform,
			'msgafterForm' => $msgafterform,
			'toname' => $toname,
			'subject' => $subject,
			'subject_notif' => $subject_notif,
			'automailresponse' => $automailresponse,
			'returnurl' => '#'
			));
			}
		break;
	}
}
public static function updateField($type = 0)
{
	//Retrieve all parametters
		$fid			 =	(int)Tools::getValue('fid');
		$fdid			 =	(int)Tools::getValue('fid');
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages();
		$fid				=	(int)Tools::getValue('fid');
		$fdid				=	(int)Tools::getValue('fdid');
		$fields_type		=	Tools::getValue('fields_type', '');
		$fields_id			=	addslashes(Tools::getValue('fields_id', ''));
		$fields_name		=	addslashes(Tools::getValue('fields_name', ''));
		$fields_require		=	(int)Tools::getValue('fields_require', 0);
		$confirmation		=	(int)Tools::getValue('confirmation', 0);
		$fields_valid		=	Tools::getValue('fields_valid', 'none');
		$fields_default		=	addslashes(Tools::getValue('fields_default', ''));
		$fields_suppl		=	addslashes(Tools::getValue('fields_suppl', ''));
		$order				=	(int)Tools::getValue('order', 0);
		$published			=	(int)Tools::getValue('published', 1);
		$fields_maxtxt		=	(int)Tools::getValue('fields_maxtxt', 0);
		$fields_id		= str_replace(' ', '_', $fields_id);
		$fields_name	= str_replace(' ', '_', $fields_name);
	switch ($type)
	{
		case 0:
		//Insert Now insert
		Db::getInstance()->insert('contactform_item', array(
				'fid' => (int)$fid,
				'fields_id' => $fields_id,
				'fields_name' => $fields_name,
				'confirmation' => $confirmation,
				'fields_valid' => $fields_valid,
				'fields_type' => $fields_type,
				'fields_style' => '',
				'err_style' => '',
				'fields_suppl' => $fields_suppl,
				'fields_require' => $fields_require,
				'fields_maxtxt' => $fields_maxtxt,
				'order' => $order,
				'published' => $published));

		$mylastid = (int)Db::getInstance()->Insert_ID();
		//Fulfill the lang table
		if (!$languages)
			return false;

		foreach ($languages as $language)
		{
			//Test allvalue
			$fields_title = addslashes(Tools::getValue('fields_title_'.$language['id_lang'], Tools::getValue('fields_title_'.$default_language)));
			$fields_desc = addslashes(Tools::getValue('fields_desc_'.$language['id_lang'], Tools::getValue('fields_desc_'.$default_language)));
			$confirmation_txt = addslashes(Tools::getValue('confirmation_txt_'.$language['id_lang'], Tools::getValue('confirmation_txt_'.$default_language)));
			$fields_default = addslashes(Tools::getValue('fields_default_'.$language['id_lang'], Tools::getValue('fields_default_'.$default_language)));
			$error_txt = addslashes(Tools::getValue('error_txt_'.$language['id_lang'], Tools::getValue('error_txt_'.$default_language)));
			$error_txt2 = addslashes(Tools::getValue('error_txt2_'.$language['id_lang'], Tools::getValue('error_txt2_'.$default_language)));

		Db::getInstance()->insert('contactform_item_lang', array(
			'fdid' => (int)$mylastid,
			'id_lang' => (int)$language['id_lang'],
			'fields_title' => $fields_title,
			'fields_desc' => $fields_desc,
			'confirmation_txt' => $confirmation_txt,
			'fields_default' => $fields_default,
			'error_txt' => $error_txt,
			'error_txt2' => $error_txt2
			));
		}
		break;
		case 1:
			$fid				=	(int)Tools::getValue('fid');
			$fdid				=	(int)Tools::getValue('fdid');
			$fields_type		=	Tools::getValue('fields_type', '');
			$fields_id			=	addslashes(Tools::getValue('fields_id', ''));
			$fields_name		=	addslashes(Tools::getValue('fields_name', ''));
			$fields_require		=	(int)Tools::getValue('fields_require', 0);
			$confirmation		=	(int)Tools::getValue('confirmation', 0);
			$fields_valid		=	Tools::getValue('fields_valid', 'none');
			$fields_default		=	addslashes(Tools::getValue('fields_default', ''));
			$fields_suppl		=	addslashes(Tools::getValue('fields_suppl', ''));
			$order				=	(int)Tools::getValue('order', 0);
			$published			=	(int)Tools::getValue('published', 1);
			$fields_maxtxt		=	(int)Tools::getValue('fields_maxtxt', 0);
			$fields_id		= str_replace(' ', '_', $fields_id);
			$fields_name	= str_replace(' ', '_', $fields_name);

			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'contactform_item` SET `fields_id` = "'.$fields_id.'",`fields_name` = "'.
									$fields_name.'",`confirmation` = '.$confirmation.',`fields_valid` = "'.$fields_valid.'",`fields_type` = "'.
									$fields_type.'",`fields_suppl` = "'.$fields_suppl.'" ,`fields_require` = '.$fields_require.
									',`fields_maxtxt` = '.$fields_maxtxt.',`order` = '.$order.' ,`published` = '.$published.' WHERE `fdid` ='.(int)$fdid);
			/*Multilanguage */

			foreach ($languages as $language)
			{
			//Test allvalue
				$fields_title = addslashes(Tools::getValue('fields_title_'.$language['id_lang'], Tools::getValue('fields_title_'.$default_language)));
				$fields_desc = addslashes(Tools::getValue('fields_desc_'.$language['id_lang'], Tools::getValue('fields_desc_'.$default_language)));
				$confirmation_txt = addslashes(Tools::getValue('confirmation_txt_'.$language['id_lang'], Tools::getValue('confirmation_txt_'.$default_language)));
				$fields_default = addslashes(Tools::getValue('fields_default_'.$language['id_lang'], Tools::getValue('fields_default_'.$default_language)));
				$error_txt = addslashes(Tools::getValue('error_txt_'.$language['id_lang'], Tools::getValue('error_txt_'.$default_language)));
				$error_txt2 = addslashes(Tools::getValue('error_txt2_'.$language['id_lang'], Tools::getValue('error_txt2_'.$default_language)));
		//Check if the records not exists
		$records = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid` ='.
		(int)$fdid.' AND `id_lang` ='.(int)$language['id_lang']);
		if (count($records) > 0)
		Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'contactform_item_lang` SET `fields_title` = "'.
	$fields_title.'",`fields_desc` = "'.$fields_desc.'",`confirmation_txt` = "'.$confirmation_txt.'",`fields_default` = "'.
	$fields_default.'",`error_txt` = "'.$error_txt.'",`error_txt2` = "'.$error_txt2.'"  WHERE `fdid` ='.
	(int)$fdid.' AND `id_lang` ='.(int)$language['id_lang']);
		else
			Db::getInstance()->insert('contactform_item_lang', array(
			'fdid' => (int)$fdid,
			'id_lang' => (int)$language['id_lang'],
			'fields_title' => $fields_title,
			'fields_desc' => $fields_desc,
			'confirmation_txt' => $confirmation_txt,
			'fields_default' => $fields_default,
			'error_txt' => $error_txt,
			'error_txt2' => $error_txt2
			));
			}
		break;
	}

}
public static function verifMail($address)
{
		if (filter_var($address, FILTER_VALIDATE_EMAIL))
			return true;
		else
		return false;
}

public static function addfield($mypath, $fid, $id_lang)
{
	if (empty($fid))
		$fid = (int)Tools::getValue('fid');
	if ($fid == 0 || empty($fid) || $fid == '')
		return self::errFormat('Create first a form and then you can add new fields in that form').' <a href="javascript:history.back()">
		<img title="'.Translate::getModuleTranslation('contactform', 'Back', 'contactform').'" src="'.$mypath.'views/img/previous1.png"></a>';
	else
		return self::showFieldForm($mypath, $fid, $id_lang);
}
public static function showFieldForm($mypath, $fid, $id_lang)
{
	$fid = (int)Tools::getValue('fid');
	$fdid = (int)Tools::getValue('fdid');
	$fields_id		=	Tools::getValue('fields_id', '');
	$fields_name	=	Tools::getValue('fields_name', '');
	$fields_suppl	=	Tools::getValue('fields_suppl', '');
	$confirmation	=	(int)Tools::getValue('confirmation', 0);
	$fields_require	=	(int)Tools::getValue('fields_require', 0);
	$published		=	(int)Tools::getValue('published', 1);
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
	$languages = Language::getLanguages();
	$iso = Language::getIsoById($default_language);

//Retrieve all filelds for
$fields = Db::getInstance()->ExecuteS('SELECT *  FROM `'._DB_PREFIX_.'contactform_item`	 WHERE `fdid`='.(int)$fdid);

$output = '';
$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
$output .= '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/errors.css" media="all"/>';
$output .= '<script type="text/javascript" src="'.$mypath.'views/js/jquery.form-validation-and-hints.js"></script>';
$output .= CFtoolsbar::toolbar('editform', $mypath, $id_lang);
$output  .= '
<script language="JavaScript" type="text/JavaScript">

function supinput()
{
  var c = document.getElementById("ttype");
  var a = document.getElementById("dest" );
  var b = document.getElementById("dest2" );
  var m = document.getElementById("aff" );
	if(c.value=="captcha"){
		    a.style.display = "block";
			b.style.display = "block";
    		m.style.display = "none";
	}
	else if(a.value=="enCours"){
		bloccalendar.style.display = "block";
		bloccaptcha.style.display = "none";
	}
	else{
		a.style.display = "none";
		b.style.display = "none";
    	m.style.display = "none";
	}
}
</script>
';
		$iso_tiny_mce = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER['PHP_SELF']);
$output .= '
			<script type="text/javascript">	
			var iso = \''.$iso_tiny_mce.'\' ;
			var pathCSS = \''._THEME_CSS_DIR_.'\' ;
			var ad = \''.$ad.'\' ;
			
			$(document).ready(function(){
				tinySetup({
					editor_selector :"rte",
					height : 300
				});
			});
			
			
			</script>';
		$ctnx = Context::getContext();
		$ctnx->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
		$ctnx->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
$output .= '	
<script type="text/javascript">
			id_language = Number('.$default_language.');
</script>
<div class="bootstrap">
<form id="module_form" class="defaultForm form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" method="post">
<div id="fieldset_0" class="panel">
<div class="panel-heading"><i class="icon-cogs"></i>'.Translate::getModuleTranslation('contactform', 'New field', 'contactform').'</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Form Id', 'contactform').'</label>
	<div class="col-lg-9 "><input type="text" class="fixed-width-xs" value="'.$fid.'"  name="fid" disabled="disabled" ></div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Fields Type', 'contactform').'</label>
	<div class="col-lg-1 ">
	<select onChange="supinput()" required="true"  name="fields_type" id="ttype" >';
	$output .= '<option value="text" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'text' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'text', 'contactform').
	'</option>';
	$output .= '<option value="password" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'password' ? 'selected' : ''): '').
	'>'.Translate::getModuleTranslation('contactform', 'password', 'contactform').'</option>';
	$output .= '<option value="email" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'email' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'email', 'contactform').
	'</option>';
	$output .= '<option value="radio" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'radio' ? 'selected' : '' ) : '').'>'.
	Translate::getModuleTranslation('contactform', 'radio', 'contactform').
	'</option>';
	$output .= '<option value="checkbox" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'checkbox' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'checkbox', 'contactform').'</option>';
	$output .= '<option value="calendar" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'calendar' ? 'selected' : '' ) : '').'>'.
	Translate::getModuleTranslation('contactform', 'calendar', 'contactform').'</option>';
	$output .= '<option value="time" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'time' ? 'selected' : '' ) : '').'>'.
	Translate::getModuleTranslation('contactform', 'Time', 'contactform').'</option>';
	$output .= '<option value="calendar-time" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'calendar-time' ? 'selected' : '' ) : '').'>'.
	Translate::getModuleTranslation('contactform', 'Calendar + Time', 'contactform').'</option>';
	$output .= '<option value="textarea" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'textarea' ? 'selected' : '' ) : '').'>'.
	Translate::getModuleTranslation('contactform', 'textarea', 'contactform').'</option>';
	$output .= '<option value="select" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'select' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'select', 'contactform').'</option>';
	$output .= '<option value="button" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'button' ? 'selected' : '' ) : '')
	.'>'.Translate::getModuleTranslation('contactform', 'button', 'contactform').'</option>';
	$output .= '<option value="imagebtn" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'imagebtn' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'image button', 'contactform').'</option>';
	$output .= '<option value="submitbtn" '.($fdid != 0 ?($fields[0]['fields_type'] == 'submitbtn' ? 'selected' : ''): '').'>'.
	Translate::getModuleTranslation('contactform', 'submit button', 'contactform').'</option>';
	$output .= '<option value="resetbtn" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'resetbtn' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'reset button', 'contactform').'</option>';
	$output .= '<option value="fileup" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'fileup' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'file upload', 'contactform').'</option>';
	$output .= '<option value="captcha" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'captcha' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'captcha', 'contactform').'</option>';
	$output .= '<option value="separator" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'separator' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'separator', 'contactform').'</option>';
	$output .= '<option value="country" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'country' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'country', 'contactform').'</option>';
	$output .= '<option value="simpletext" '.($fdid != 0 ? ($fields[0]['fields_type'] == 'simpletext' ? 'selected' : '') : '').'>'.
	Translate::getModuleTranslation('contactform', 'Simple text', 'contactform').'</option>
	</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2 required"><span class="label-tooltip" title="" data-html="true" data-toggle="tooltip" 
	data-original-title="'.Translate::getModuleTranslation('contactform', 'Field required', 'contactform').' "> '.
	Translate::getModuleTranslation('contactform', 'Field Id', 'contactform').' </span></label>
	<div class="col-lg-3 "><input type="text" class="text verifyText" value="'.
	($fdid != 0 ? $fields[0]['fields_id'] : $fields_id).'"  name="fields_id" /></div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2 required"><span class="label-tooltip" title="" data-html="true" data-toggle="tooltip" 
	data-original-title="'.Translate::getModuleTranslation('contactform', 'Field required', 'contactform').' "> '.
	Translate::getModuleTranslation('contactform', 'Field Name', 'contactform').' </span></label>
	<div class="col-lg-3 "><input type="text" class="text verifyText" value="'.
	($fdid != 0 ? $fields[0]['fields_name'] : $fields_name).'"  name="fields_name" /></div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Field Title', 'contactform').'</label>
	<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$custalias = Db::getInstance()->ExecuteS('SELECT `fields_title` FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.
			(int)$fdid.' AND `id_lang`='.(int)$language['id_lang']);
			if (!empty($custalias[0]['fields_title']))
				$defaultfields_title = $custalias[0]['fields_title'];
			else
				$defaultfields_title = '';
			$fldttdef = Tools::getValue('fields_title_'.$language['id_lang'], '');
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<input type="text" value="'.
		($fdid != 0 ? $defaultfields_title : $fldttdef).'" name="fields_title_'.
		$language['id_lang'].'" id="fields_title_'.$language['id_lang'].'">																																	
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
											</div>
										</div>';
		}
$output .= '
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Description', 'contactform').'</label>
	<div class="col-lg-9 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$fields_desclang = Db::getInstance()->ExecuteS('SELECT `fields_desc` FROM `'._DB_PREFIX_.
				'contactform_item_lang` WHERE `fdid`='.(int)$fdid.' AND `id_lang`='.(int)$language['id_lang']);
					if (!empty($fields_desclang[0]['fields_desc']))
						$defaulfields_desc = $fields_desclang[0]['fields_desc'];
					else
						$defaulfields_desc = '';
				$flddescdef = Tools::getValue('fields_desc_'.$language['id_lang'], '');
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-9" style="padding-left: 0px">
							<textarea  class="rte" cols="50" rows="5" name="fields_desc_'.$language['id_lang'].'" >'.
						($fdid != 0 ? $defaulfields_desc : $flddescdef).'</textarea>																									
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
											</div>
										</div>';
		}
$output .= '
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Mandatory', 'contactform').'</label>
	<div class="col-lg-9 ">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" '.($fdid != 0 ? ($fields[0]['fields_require'] == 1 ? 'checked="checked"' : '')
	: ($fields_require == 1 ? 'checked="checked"' : '')).' value="1" id="fields_require_on" name="fields_require">
			<label for="fields_require_on">'.Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'</label>
			<input type="radio" value="0" '.($fdid != 0 ? ($fields[0]['fields_require'] == 0 ? 'checked="checked"' : '')
	: ($fields_require == 0 ? 'checked="checked"' : '')).' id="fields_require_off" name="fields_require">
			<label for="fields_require_off">'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'</label>
			<a class="slide-button btn"></a>
		</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Confirmation', 'contactform').'</label>
	<div class="col-lg-9 ">
		<span class="switch prestashop-switch fixed-width-lg">
			<input OnClick="javascript:afficher();" type="radio" '.($fdid != 0 ? ($fields[0]['confirmation'] == 1 ?
'checked="checked"' : '' ) : ($confirmation == 1 ? 'checked="checked"' : '')).' value="1" id="confirmation_on" name="confirmation">
			<label for="confirmation_on">'.Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'</label>
			<input OnClick="javascript:masquer();" type="radio" value="0" '.($fdid != 0 ? ($fields[0]['confirmation'] == 0 ?
'checked="checked"' : '') : ($confirmation == 0 ? 'checked="checked"' : '')).' id="confirmation_off" name="confirmation">
			<label for="confirmation_off">'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'</label>
			<a class="slide-button btn"></a>
		</span>
	</div>
</div>
<div class="form-group" id="dest" style="display:'.($fdid != 0 ? ($fields[0]['confirmation'] == 1 ? 'block' : 'none' ) :
	($confirmation == 1 ? 'block' : 'none')).';">
<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Confirmation title', 'contactform').'</label>
<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$custalias = Db::getInstance()->ExecuteS('SELECT `confirmation_txt` FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.
												(int)$fdid.' AND `id_lang`='.(int)$language['id_lang']);
			if (!empty($custalias[0]['confirmation_txt']))
				$defaultfields_title = $custalias[0]['confirmation_txt'];
			else
				$defaultfields_title = '';
			$cfrmtxt = Tools::getValue('confirmation_txt_'.$language['id_lang'], '');
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<input type="text" name="confirmation_txt_'.$language['id_lang'].'" id="confirmation_txt_'.
		$language['id_lang'].'" size="45" value="'.($fdid != 0 ? $defaultfields_title : $cfrmtxt).'" />																										
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
							</div>
					</div>';
		}
$output .= '</div></div>

<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Validation', 'contactform').'</label>
	<div class="col-lg-1 ">
		<select required="true"  name="fields_valid" >';
			$output .= '<option value="none" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'none' ? 'selected' : '') : '').'>'.
			Translate::getModuleTranslation('contactform', 'None', 'contactform').'</option>';
			$output .= '<option value="email" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'email' ? 'selected' : '' ) : '').'>'.
			Translate::getModuleTranslation('contactform', 'email', 'contactform').'</option>';
			$output .= '<option value="numeric" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'numeric' ? 'selected' : '' ) : '').'>'.
			Translate::getModuleTranslation('contactform', 'Numeric (0-9)', 'contactform').'</option>';
			$output .= '<option value="alphanum" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'alphanum' ? 'selected' : '' ) : '').'>'.
			Translate::getModuleTranslation('contactform', 'alphanumeric', 'contactform').'</option>';
			$output .= '<option value="alpha" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'alpha' ? 'selected' : '' ) : '').'>'.
			Translate::getModuleTranslation('contactform', 'alpha(a-z,A-Z)', 'contactform').'</option>';
			$output .= '<option value="url" '.($fdid != 0 ? ($fields[0]['fields_valid'] == 'url' ? 'selected' : '' ) : '').'>'.
			Translate::getModuleTranslation('contactform', 'url', 'contactform').'</option>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2"><span class="label-tooltip" title="" data-html="true" data-toggle="tooltip"
	data-original-title="'.Translate::getModuleTranslation('contactform',
															'Separate items with ";" (semicolon) for radio button,checkbox and input select', 'contactform').' "> '.
	Translate::getModuleTranslation('contactform', 'Default value', 'contactform').' </span></label>
	<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$custalias = Db::getInstance()->ExecuteS('SELECT `fields_default` FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.
			(int)$fdid.' AND `id_lang`='.(int)$language['id_lang']);
			if (!empty($custalias[0]['fields_default']))
				$defaultfields_default = $custalias[0]['fields_default'];
			else
				$defaultfields_default = '';
			$flddefs = Tools::getValue('fields_default_'.$language['id_lang'], '');
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<textarea name="fields_default_'.$language['id_lang'].'" id="fields_default_'.$language['id_lang'].'" cols="50" rows="5" >'.
		($fdid != 0 ? $defaultfields_default : $flddefs).'</textarea>																										
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
											</div>
										</div>';
		}
$output .= '
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2"><span class="label-tooltip" title="" data-html="true"
	data-toggle="tooltip" data-original-title="'.Translate::getModuleTranslation('contactform',
												'You can add others parameters to the field. Example : size="10" or style="width:20px; color:#336699;"', 'contactform').' "> '.
	Translate::getModuleTranslation('contactform', 'Additional Attributes', 'contactform').' </span></label>
	<div class="col-lg-3 "><input type="text" value="'.
	($fdid != 0 ? htmlentities($fields[0]['fields_suppl']) : htmlentities($fields_suppl)).'"  name="fields_suppl" /></div>
</div>

<div class="form-group">
	<label class="control-label col-lg-2"><span class="label-tooltip" title="" data-html="true" data-toggle="tooltip"
	data-original-title="'.Translate::getModuleTranslation('contactform',
						'Message that appears when the field is not filled correctly (Only for basic style)', 'contactform').' "> '.
	Translate::getModuleTranslation('contactform', 'Error message', 'contactform').' </span></label>
	<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$custalias = Db::getInstance()->ExecuteS('SELECT `error_txt` FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.
												(int)$fdid.' AND `id_lang`='.(int)$language['id_lang']);
			if (!empty($custalias[0]['error_txt']))
				$defaulterror_txt = $custalias[0]['error_txt'];
			else
				$defaulterror_txt = '';
			$errtxtdef = Tools::getValue('error_txt_'.$language['id_lang'], '');
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<input type="text" name="error_txt_'.$language['id_lang'].'" id="error_txt_'.$language['id_lang'].'" size="45" value="'.
		($fdid != 0 ? $defaulterror_txt : $errtxtdef).'" />																									
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
											</div>
										</div>';
		}
		$myallorder = array();
		$order_exists = Db::getInstance()->ExecuteS('SELECT `order`  FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.
													(int)$fid.' ORDER BY `order` ASC ');
		foreach ($order_exists as $order_exist)
			array_push($myallorder, (int)$order_exist['order']);

		if (count($myallorder) > 0)
			$maxorder = $myallorder[count($myallorder) - 1];
		else
			$maxorder = 0;

		$neworder = $maxorder + 1;
$output .= '
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Max text number', 'contactform').'</label>
	<div class="col-lg-3 ">
		<input class="text verifyInteger" type="text" name="fields_maxtxt" size="6" value="'.($fdid != 0 ? $fields[0]['fields_maxtxt'] : 0).'">
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Order', 'contactform').'</label>
	<div class="col-lg-3 ">
		<input class="text verifyInteger" type="text" name="order" size="6" value="'.($fdid != 0 ? $fields[0]['order'] : $neworder).'">
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-2">'.Translate::getModuleTranslation('contactform', 'Publish', 'contactform').'</label>
	<div class="col-lg-9 ">
		<span class="switch prestashop-switch fixed-width-lg">
			<input '.($fdid != 0 ? ($fields[0]['published'] == 1 ? 'checked' : '' ) : ($published == 1 ? 'checked' : '')).
		' type="radio" id="published_on" name="published" value="1"  >
			<label for="published_on">'.Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'</label>
			<input '.($fdid != 0 ? ($fields[0]['published'] == 0 ? 'checked' : '' ) : ($published == 0 ? 'checked' : '')).
		' type="radio" name="published" id="published_off"value="0" >
			<label for="published_off">'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'</label>
			<a class="slide-button btn"></a>
		</span>
	</div>
</div>
';

//Javascript functions
$output .= '
<script language="JavaScript" type="text/JavaScript">
function afficher()
{
  var a = document.getElementById("dest" );
  var b = document.getElementById("dest2" );
  var m = document.getElementById("aff" );
    a.style.display = "block";
	b.style.display = "block";
    m.style.display = "none";
}
 
function masquer() 
{ 
  var a = document.getElementById("dest" ); 
  var b = document.getElementById("dest2" );
  var m = document.getElementById("aff" ); 
 a.style.display = "none"; 
 b.style.display = "none"; 
 m.style.display = "block"; 
}

function initial() 
{ 
  var a = document.getElementById("dest" ); 
  var b = document.getElementById("dest2" ); 
  var m = document.getElementById("aff" ); 
 a.style.display = "none"; 
 b.style.display = "none";
 m.style.display = "none"; 
}

</script>';

$output .= '
<div class="panel-footer">
<button class="btn btn-default pull-right" name="submitfield" value="submitfield" type="submit">
	<i class="process-icon-save"></i>
	'.Translate::getModuleTranslation('contactform', '    Save    ', 'contactform').'
</button>
	<a onclick="window.history.back();" class="btn btn-default" href="
	index.php?tab=AdminModules&configure=contactform&task=showformList&token='.Tools::getValue('token').'">
	<i class="process-icon-cancel"></i>
	'.Translate::getModuleTranslation('contactform', 'Cancel', 'contactform').'
</a>
</div>

</div></form></div>';
return $output;
}
public static function errFormat($errmsg)
{
	$output = '<div style="border:1px solid #999999; background-color:#FFDFDF; width:99%; margin-bottom:20px; padding:5px">';
	$output .= Translate::getModuleTranslation('contactform', $errmsg, 'contactform');
	$output .= '</div>';
	return $output;
}
public static function createrelation($mypath, $id_lang)
{
	$output  = CFtoolsbar::toolbar('formrelation', $mypath, $id_lang);
	$languages = Language::getLanguages();
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

	$form = DB::getInstance()->executeS('SELECT `fid`,`formname` FROM `'._DB_PREFIX_.'contactform`');
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$output .= '
	<div class="bootstrap">
	<form class="defaultForm form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" method="post" id="form">
	<div class="pspage panel"> <h3 class="tab"><i class="icon-info"></i> '.
	Translate::getModuleTranslation('contactform', 'New relation', 'contactform').'</h3>
	<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Relation title', 'contactform').'</label>
	<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<input type="text" name="reltitle_'.$language['id_lang'].'" id="reltitle_'.
							$language['id_lang'].'" size="45" value="" />																										
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
							</div>
						</div>';
		}
$output .= '
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Active', 'contactform').'</label>
	<div class="col-lg-9 ">
		<span class="switch prestashop-switch fixed-width-lg">
			<input checked="checked" type="radio" id="etatrel_on" name="etatrel" value="1"  >
			<label for="etatrel_on">'.Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'</label>
			<input type="radio" name="etatrel" id="etatrel_off" value="0" >
			<label for="etatrel_off">'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'</label>
			<a class="slide-button btn"></a>
		</span>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Type of display choices', 'contactform').'</label>
	<div class="col-lg-1 ">
		<select required="true"  name="typeaff" >
			<option checked="checked" value="1" >'.Translate::getModuleTranslation('contactform', 'radio', 'contactform').'</option>
			<option value="0" >'.Translate::getModuleTranslation('contactform', 'select', 'contactform').'</option>
			<option value="2" >'.Translate::getModuleTranslation('contactform', 'Tab', 'contactform').'</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Default form', 'contactform').'</label>
	<div class="col-lg-2 ">
		<select required="true"  name="defaultform" >';
		foreach ($form as $frm)
			$output .= '<option value="'.$frm['fid'].'">'.$frm['formname'].'</form>';
$output .= '
		</select>
	</div>
</div>
	<table>
	<tbody>
	<tr>
	<td align="center">
	<select style="width: 300px; height: 160px;" id="items" multiple="multiple" class="pspage">
	
	</select><br><br>
	<a class="dellink" id="removeItem" href="#">Supprimer</a>
	</td>
	<td align="center" style="padding-left: 20px;">';
	if ($form)
	{
		$output .= '<select style="width: 300px; height: 160px;" id="availableItems" multiple="multiple" class="pspage">
		<optgroup label="FORMS">';
		foreach ($form as $frm)
			$output .= '<option style="margin-left:10px;" value="'.$frm['fid'].'">'.$frm['formname'].'</option>';
	$output .= '</optgroup>
	</select><br><br>';
	}
	else
	$output .= '<div class="no-form">'.Translate::getModuleTranslation('contactform', 'No form avalaible', 'contactform').'</div>';
	$output .= '
	<a class="addlink" id="addItem" href="#">Ajouter</a>			
	</td>
	</tr>
	</tbody>
	</table>
	<div style="display: none">
	<label>'.Translate::getModuleTranslation('contactform', 'Items', 'contactform').'</label>
	<div class="margin-form">
	<input type="text" name="items" id="itemsInput" value="" size="70" />
	</div>
	</div>
	<div class="panel-footer">
		<a class="btn btn-default pull-left" href="index.php?tab=AdminModules&configure=contactform&task=formrelation&token='.
		Tools::getValue('token').'"><i class="process-icon-cancel"></i> '.
		Translate::getModuleTranslation('contactform', 'Cancel', 'contactform').'</a>
		<button class="btn btn-default pull-right" name="saverelation" type="submit"><i class="process-icon-save"></i> '.
		Translate::getModuleTranslation('contactform', '    Save    ', 'contactform').'</button>
	</div>
	</div></form></div>';
	$output .= '<script type="text/javascript">
	$(document).ready(function(){
	$("#addItem").click(add);
	$("#availableItems").dblclick(add);
	$("#removeItem").click(remove);
	$("#items").dblclick(remove);
	function add()
	{
		$("#availableItems option:selected").each(function(i){
		var val = $(this).val();
		var text = $(this).text();
		/* if(val == "PRODUCT")
		{
			val = prompt("ID du produit");
			if(val == null || val == "" || isNaN(val))
				return;
			text = "Produit ID "+val;
			val = "PRD"+val;
		}*/
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
		options += $(this).val()+",";
		});
		$("#itemsInput").val(options.substr(0, options.length - 1));
	}
	});
	</script>';
	return $output;
}
public static function formRelation($mypath, $id_lang)
{
		$output  = CFtoolsbar::toolbar('formrelation', $mypath, $id_lang);
		$relations = DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'contactform_relation`');
		$url = 'index.php?tab=AdminModules&configure=contactform&token='.Tools::getValue('token');
		$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
		if (count($relations) == 0)
			$output .= '<div style="background: none repeat scroll 0% 0% rgb(249, 254, 150); border: 1px solid red; margin: 10px;
			padding: 5px;">'.Translate::getModuleTranslation('contactform', 'There is no relation form at this time', 'contactform').'</div>';
		else
		{
			$output .= '<script type="text/javascript">
					checked=false;
					function checkedAll (frm1) {
					var aa= document.getElementById("frm1");
					if (checked == false)
						{
						checked = true
						}
					else
					  {
					  checked = false
					  }
					for (var i =0; i < aa.elements.length; i++) 
					{
						aa.elements[i].checked = checked;
					}
				  }
				</script>';
			$output .= '<script type="text/javascript">
							function editState(rid,state){
								$.ajax({
								   type: "POST",
								   url: "'.$mypath.'ajax.php",
								   data: "rid="+rid+"&state="+state,
								   success: function(msg){
									 $("#arel-"+rid).empty();
									 $("#arel-"+rid).html( msg);
								   }	});
							}
							
						</script>';
			$output .= '
			<div class="bootstrap">
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="frm1" id="frm1">
			<div class="panel">
				<h3 class="tab"><i class="icon-info"></i> '.Translate::getModuleTranslation('contactform', 'Relation List', 'contactform').'</h3>
			<table width="100%" cellspacing="0" cellpadding="0" class="table">
			<thead>	<tr class="nodrag nodrop">
						<th><input type="checkbox" onclick="checkedAll(frm1);" name="checkall"></th>
						<th>#</th>
						<th>'.Translate::getModuleTranslation('contactform', 'Relations', 'contactform').'</th>
						<th style="text-align: center">'.Translate::getModuleTranslation('contactform', 'State', 'contactform').'</a></th>
						<th>'.Translate::getModuleTranslation('contactform', 'Presentation', 'contactform').'</th>
						<th>'.Translate::getModuleTranslation('contactform', 'Default form', 'contactform').'</th>
						<th>'.Translate::getModuleTranslation('contactform', 'Num. element', 'contactform').'</th>
						<th>'.Translate::getModuleTranslation('contactform', 'Actions', 'contactform').'</th>
						<th>'.Translate::getModuleTranslation('contactform', 'ID', 'contactform').'</th>
						</tr></thead>
			<tbody>';
			$num = 1;
			foreach ($relations as $relation)
			{
				$relationtitle = DB::getInstance()->getValue('SELECT `title` FROM `'._DB_PREFIX_.
				'contactform_relation_lang` WHERE id_lang='.(int)$id_lang.' AND rid='.(int)$relation['id']);
				$reltitle = (Tools::strlen($relationtitle) > 70) ? Tools::substr($relationtitle, 0, 70).' ...' : $relationtitle;
				$etat = ((int)$relation['etat']) ? 'active' : 'desactive';
				$c_state = ((int)$relation['etat']) ? 0 : 1;
				$dfform = DB::getInstance()->getValue('SELECT formname FROM `'._DB_PREFIX_.'contactform` WHERE fid='.(int)$relation['default']);
				switch ((int)$relation['type'])
				{
					case 0:
						$type = Translate::getModuleTranslation('contactform', 'Select: Drop-down menu', 'contactform');
					break;
					case 1:
						$type = Translate::getModuleTranslation('contactform', 'Radio button', 'contactform');
					break;
					case 2:
						$type = Translate::getModuleTranslation('contactform', 'Tab', 'contactform');
					break;
				}
				$output .= '<tr id="rel-'.$relation['id'].'">
								<td align="left"><input type="checkbox" name="actlink['.$relation['id'].']" value="1"></td>
								<td>'.$num.'</td>
								<td><a title="'.$relationtitle.'" href="'.$url.'&task=editrel&rid='.$relation['id'].'">'.$reltitle.'</a></td>
								<td align="center" id="arel-'.$relation['id'].'"><a style="cursor: pointer;" onclick="editState('.
																																$relation['id'].','.$c_state.')" class="rel'.$etat.'"></a></td>
								<td>'.$type.'</td>
								<td>'.$dfform.'</td>
								<td>'.self::countElement($relation['id']).'</td>
								<td><a title="'.Translate::getModuleTranslation('contactform', 'Edit', 'contactform').'" href="'.$url.
								'&task=editrel&rid='.$relation['id'].'">'.Translate::getModuleTranslation('contactform', 'Edit', 'contactform').
								'</a>&nbsp;|&nbsp;<a title="'.Translate::getModuleTranslation('contactform', 'View elements', 'contactform').
								'" href="'.$url.'&task=relviewelm&rid='.$relation['id'].'">'.
								Translate::getModuleTranslation('contactform', 'View elements', 'contactform').'</a>&nbsp;|&nbsp;<a href="'.
								$url.'&task=delrelation&rid='.$relation['id'].'" onclick="return(confirm(\''.
								Translate::getModuleTranslation('contactform', 'Do you really want  to delete this relation?', 'contactform').
								'\'));" title="'.Translate::getModuleTranslation('contactform', 'Delete', 'contactform').
								'" >'.Translate::getModuleTranslation('contactform', 'Delete', 'contactform').'</a></td>
								<td>'.$relation['id'].'</td>
							</tr>';
				$num++;
			}
			$output .= '</tbody></table><input type="submit" onclick="return(confirm(\''.
						Translate::getModuleTranslation('contactform', 'Do you really want  to delete relation selected ?', 'contactform')
						.'\'));" value="Supprimer la selection" name="deleteselectrel" class="button" style="margin:10px;">
			</div></form></div></fieldset>';
		}
		return $output;
}
public static function countElement($rid)
{
	DB::getInstance()->ExecuteS('SELECT `fid` FROM `'._DB_PREFIX_.'contactform_relation_item` WHERE rid='.(int)$rid);
	return DB::getInstance()->NumRows();
}
public static function editRelation($mypath, $rid, $id_lang)
{
	$datarel = DB::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'contactform_relation_item` WHERE rid='.(int)$rid.' ORDER by `order`');
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');

	$languages = Language::getLanguages();
	$output  = CFtoolsbar::toolbar('formrelation', $mypath, $id_lang);
	$form = DB::getInstance()->executeS('SELECT `fid`,`formname` FROM `'._DB_PREFIX_.'contactform`');
	$relationdata = DB::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'contactform_relation` WHERE id='.(int)$rid);
	$itemid = '';
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$output .= '<link href="'.$mypath.'views/css/styles.css" type="text/css" rel="stylesheet">';
	$output .= '
	<div class="bootstrap">
	<form class="defaultForm form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" method="post" id="form">
	<div class="panel">
	<h3 class="tab"><i class="icon-info"></i> '.Translate::getModuleTranslation('contactform', 'Edit relation', 'contactform').'</h3>
	
	<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Relation title', 'contactform').'</label>
	<div class="col-lg-3 ">';
		foreach ($languages as $language)
		{
			$relationtitle = DB::getInstance()->getValue('SELECT title FROM `'._DB_PREFIX_.'contactform_relation_lang` WHERE rid='.
													(int)$rid.' AND id_lang='.(int)$language['id_lang']);
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<input type="text" name="reltitle_'.$language['id_lang'].'" id="reltitle_'.$language['id_lang'].'" size="45" value="'.
		$relationtitle.'" />
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
							</div>
						</div>';
		}
$output .= '
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Active', 'contactform').'</label>
	<div class="col-lg-9 ">
		<span class="switch prestashop-switch fixed-width-lg">
			<input '.((int)$relationdata['etat'] == 1 ? 'checked="checked"' : '').' type="radio" id="etatrel_on" name="etatrel" value="1"  >
			<label for="etatrel_on">'.Translate::getModuleTranslation('contactform', 'Yes', 'contactform').'</label>
			<input type="radio" '.((int)$relationdata['etat'] == 0 ? 'checked="checked"' : '').' name="etatrel" id="etatrel_off" value="0" >
			<label for="etatrel_off">'.Translate::getModuleTranslation('contactform', 'No', 'contactform').'</label>
			<a class="slide-button btn"></a>
		</span>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Type of display choices', 'contactform').
	'</label>
	<div class="col-lg-1 ">
		<select required="true"  name="typeaff" >
			<option '.((int)$relationdata['type'] == 1 ? 'selected="selected"' : '').' value="1" >'.
			Translate::getModuleTranslation('contactform', 'radio', 'contactform').'</option>
			<option '.((int)$relationdata['type'] == 0 ? 'selected="selected"' : '').' value="0" >'.
			Translate::getModuleTranslation('contactform', 'select', 'contactform').'</option>
			<option '.((int)$relationdata['type'] == 2 ? 'selected="selected"' : '').' value="2" >'.
			Translate::getModuleTranslation('contactform', 'Tab', 'contactform').'</option>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Default form', 'contactform').'</label>
	<div class="col-lg-2 ">
		<select name="dftlform" >';
		foreach ($form as $frm2)
		{
			$selectedfrm = ((int)$frm2['fid'] == (int)$relationdata['default']) ? 'selected="selected"' : '';
			$output .= '<option '.$selectedfrm.' value="'.$frm2['fid'].'">'.$frm2['formname'].'</option>';
		}
	$output .= '
		</select>
	</div>
</div>
	<table>
	<tbody>
	<tr>
	  <td align="center">
				<select style="width: 300px; height: 160px;" id="items" multiple="multiple" class="pspage">';
	foreach ($datarel as $dtrel)
	{
		if ((int)$dtrel['fid'] != (int)$relationdata['default'])
		{
			$formnm = DB::getInstance()->getValue('SELECT `formname` FROM `'._DB_PREFIX_.'contactform` WHERE fid='.(int)$dtrel['fid']);
			$output .= '<option value="'.$dtrel['fid'].'">'.$formnm.'</option>';
			$itemid .= $dtrel['fid'].',';
		}
	}
		$output .= '</select><br><br>
							<a class="dellink" id="removeItem" href="#">Supprimer</a>
				  </td>
				  <td align="center" style="padding-left: 20px;">';
	if ($form)
	{
		$output .= '<select style="width: 300px; height: 160px;" id="availableItems" multiple="multiple" class="pspage">
		<optgroup label="FORMS">';

			foreach ($form as $frm)
				$output .= '<option style="margin-left:10px;" value="'.$frm['fid'].'">'.$frm['formname'].'</option>';
		$output .= '</optgroup>
	</select><br><br>';
	}
	else
		$output .= '<div class="no-form">'.Translate::getModuleTranslation('contactform', 'No form avalaible', 'contactform').'</div>';
					$output .= '
					<a class="addlink" id="addItem" href="#">Ajouter</a>			
				  </td>
				</tr>
			  </tbody>
        </table>
		<div style="display: none">
  			<label>'.Translate::getModuleTranslation('contactform', 'Items', 'contactform').'</label>
  			<div class="margin-form">
          <input type="text" name="items" id="itemsInput" value="'.$itemid.'" size="70" />
  			</div>
  			</div>
		<input type="hidden" name="rid" value="'.$rid.'" />
		<div class="panel-footer">
		<a class="btn btn-default pull-left" href="index.php?tab=AdminModules&configure=contactform&token='.Tools::getValue('token').
		'&task=formrelation"><i class="process-icon-cancel"></i> '.Translate::getModuleTranslation('contactform', 'Cancel', 'contactform').'</a>
		<button class="btn btn-default pull-right" name="updaterelation" type="submit"><i class="process-icon-save"></i> '.
		Translate::getModuleTranslation('contactform', '    Save    ', 'contactform').'</button>
	</div>
		</form></div>
		';
		$output .= '<script type="text/javascript">
        $(document).ready(function(){
          $("#addItem").click(add);
          $("#availableItems").dblclick(add);
          $("#removeItem").click(remove);
          $("#items").dblclick(remove);
          function add()
          {
            $("#availableItems option:selected").each(function(i){
              var val = $(this).val();
              var text = $(this).text();
             /* if(val == "PRODUCT")
              {
                val = prompt("ID du produit");
                if(val == null || val == "" || isNaN(val))
                  return;
                text = "Produit ID "+val;
                val = "PRD"+val;
              }*/
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
              options += $(this).val()+",";
            });
            $("#itemsInput").val(options.substr(0, options.length - 1));
          }
        });
        </script>';
		return $output;
}
public static function viewRelElement($mypath, $rid, $id_lang)
{
	$url = 'index.php?tab=AdminModules&configure=contactform&token='.Tools::getValue('token');
	$reqs = DB::getInstance()->executeS('SELECT cri.* FROM `'._DB_PREFIX_.'contactform_relation_item` as cri
									   WHERE rid='.(int)$rid.' ORDER BY cri.order');
	$dfltfrm = DB::getInstance()->executeS('SELECT `default` FROM `'._DB_PREFIX_.'contactform_relation` WHERE id='.(int)$rid);
	$relationtitle = DB::getInstance()->getValue('SELECT `title` FROM `'._DB_PREFIX_.'contactform_relation_lang` WHERE rid='.
												(int)$rid.' AND id_lang='.(int)$id_lang);
	$output  = CFtoolsbar::toolbar('relviewelm', $mypath, $id_lang);
	$output .= '
			<div class="bootstrap">';
	$output .=	'<div class="panel"><h3 class="tab"> <i class="icon-info"></i> '.
	Translate::getModuleTranslation('contactform', 'Relation title', 'contactform').' ['.$relationtitle.']</h3>';
	if (!$reqs)
		$output .= '<div style="background: none repeat scroll 0% 0% rgb(249, 254, 150); border: 1px solid red; margin: 10px; padding: 5px;">'.
		Translate::getModuleTranslation('contactform', 'There is no element in this relation at this time', 'contactform').'</div>';
	else
	{
		$output .= '<script type="text/javascript" >
	
				// JavaScript Document
			$(document).ready(function() {
				// Initialise the table
				$("#table-1").tableDnD({
					onDrop: function(table, row) {
						var rows = table.tBodies[0].rows;
						var debugStr = "";
						for (var i=0; i<rows.length; i++) {
							if(i==(rows.length-1)){
								debugStr += rows[i].id;
							}
							else{debugStr += rows[i].id+",";}
							
						}
						$("#cforder").val(debugStr);
						 $.ajax({
							   type: "POST",
							   url: "../modules/contactform/ajax.php",
							   data: "rid='.$rid.'&order="+debugStr,
							   success: function(msg){
								 $("#items").empty();
								 $("#items").html(msg);
							   }
						});
					}
				});
				
			});
				</script>';
		$output .= '
		 <table style="width:100%;">
        <tbody>
		<table id="table-1" width="100%" class="table space tableDnD" cellspacing="0" cellpadding="0">';
		$output .= '<thead>
					<tr>
						<th style="border-right:1px solid #EFEFEF;text-align: center" >'.
						Translate::getModuleTranslation('contactform', 'Order', 'contactform').
						'<form method="post"><input type="hidden" name="cforder" id="cforder" value="';
						foreach ($reqs as $req)
							$output .= $req['fid'].',';
						$output .= '" /><input type="hidden" name="rid" id="rid" value="'.$rid.'" />
						<input type="submit" value="'.Translate::getModuleTranslation('contactform', 'Save order', 'contactform').
						'" name="cfsaveorder" /></form></th>
						<th>'.Translate::getModuleTranslation('contactform', 'Form name', 'contactform').'</th>
						<th>'.Translate::getModuleTranslation('contactform', 'Additional text', 'contactform').'</th>
						<th></th>
					</tr>
		</thead>';
		foreach ($reqs as $req)
		{
			$dt = DB::getInstance()->ExecuteS('SELECT txtsuppl FROM `'._DB_PREFIX_.'contactform_relation_item_lang` WHERE rid='.
				(int)$rid.' AND fid='.(int)$req['fid'].' AND id_lang='.(int)$id_lang);
			$fda = DB::getInstance()->ExecuteS('SELECT formname FROM `'._DB_PREFIX_.'contactform` WHERE fid='.(int)$req['fid']);

			$txtsuppl = ($dt[0]['txtsuppl']) ? $dt[0]['txtsuppl'] : Translate::getModuleTranslation('contactform', 'Empty', 'contactform');
			$txtshow = ((int)Tools::strlen($txtsuppl) > 100) ? Tools::substr($txtsuppl, 0, 99).' ...' : $txtsuppl;

			$txtonclick = ((int)$req['fid'] == (int)$dfltfrm[0]['default']) ?
			'alert(\''.Translate::getModuleTranslation('contactform', 'You can not delete the default form of a relation', 'contactform').
													'\');return false;' :
			'return confirm(\''.Translate::getModuleTranslation('contactform',
																'Do you really want to delete this element in this relation?', 'contactform').'\');';

			$output .= '<tr id="'.$req['fid'].'" class="alt_row"><td style="border-right:1px solid #EFEFEF;text-align:center">'.
			$req['order'].'</td><td style="border-right:1px solid #EFEFEF;"><a href="'.$url.'&task=editrelelm&rid='.$rid.
			'&fid='.$req['fid'].'" style="cursor: pointer;">'.$fda[0]['formname'].'</a></td><td style="border-right:1px solid #EFEFEF;">'.
			$txtshow.'</td><td><a href="'.$url.'&task=editrelelm&rid='.$rid.'&fid='.$req['fid'].'" style="cursor: pointer;" title="'.
			Translate::getModuleTranslation('contactform', 'Edit', 'contactform').'">'.
			Translate::getModuleTranslation('contactform', 'Edit', 'contactform').'</a>&nbsp;|&nbsp;<a href="'.$url.
			'&task=delemtrel&fid='.$req['fid'].'&rid='.$rid.
			'" onclick="'.$txtonclick.'" title="'.Translate::getModuleTranslation('contactform', 'Delete element', 'contactform').'">'.
			Translate::getModuleTranslation('contactform', 'Delete element', 'contactform').'</a></td></tr>';
		}
		$output .= '<tfoot><th colspan="4">Note:&nbsp;'.
		Translate::getModuleTranslation('contactform', 'Drag and drop to define the order of elements', 'contactform').'. '.
		Translate::getModuleTranslation('contactform', 'Then click Save order', 'contactform').'</th></tfoot>';
		$output .= '</table></tbody></table>';
	}
	$output .= '</div>';
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	return $output;
}
public static function editRelElement($mypath, $rid, $fid, $id_lang)
{
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
	$languages = Language::getLanguages();

	$iso = Language::getIsoById((int)$id_lang);
	$iso_tiny_mce = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
	$ad = dirname($_SERVER['PHP_SELF']);
	$output  = CFtoolsbar::toolbar('formrelation', $mypath, $id_lang);
	$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
	$output .= '
		<script type="text/javascript">	
		var iso = \''.$iso_tiny_mce.'\' ;
		var pathCSS = \''._THEME_CSS_DIR_.'\' ;
		var ad = \''.$ad.'\' ;
		
		$(document).ready(function(){
			tinySetup({
				editor_selector :"rte",
				height : 300
			});
		});
		</script>';
	$ctnx = Context::getContext();
	$ctnx->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
	$ctnx->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
	$formname = DB::getInstance()->getValue('SELECT formname FROM `'._DB_PREFIX_.'contactform` WHERE fid='.(int)$fid);

	$output .= '<div class="bootstrap" >';
	$output .= '<form class="defaultForm form-horizontal" action="'.$_SERVER['REQUEST_URI'].'" name="editelm" method="post">';
	$output .=	'<div class="panel"><h3 class="tab"> <i class="icon-info"></i> '.
	Translate::getModuleTranslation('contactform', 'Relation element edit', 'contactform').'</h3>';
	$output .= '<input type="hidden" name="fid" value="'.$fid.'" />';
	$output .= '<input type="hidden" name="rid" value="'.$rid.'" />';
	$output .= '<div class="form-group">
					<label for="frmn" class="control-label col-lg-3">
							'.Translate::getModuleTranslation('contactform', 'Form name', 'contactform').'
					</label>
					<div class="col-lg-3">
						<input size=6 type="text" id="frmn" readonly="true" value="'.$formname.'">
					</div>
			</div>';
	$output .= '<div class="form-group">
	<label class="control-label col-lg-3">'.Translate::getModuleTranslation('contactform', 'Additional text', 'contactform').'</label>
	<div class="col-lg-9">';
		foreach ($languages as $language)
		{
			$dataform = DB::getInstance()->executeS('SELECT txtsuppl FROM `'._DB_PREFIX_.'contactform_relation_item_lang`
											WHERE rid='.(int)$rid.' AND fid='.(int)$fid.' AND id_lang='.(int)$language['id_lang']);
			$styled = ($default_language != (int)$language['id_lang']) ? 'style="display: none"' : '';
			$output .= '<div '.$styled.'class="translatable-field lang-'.$language['id_lang'].'">
							<div class="col-lg-10" style="padding-left: 0px">
							<textarea class="rte" name="txtsuppl_'.$language['id_lang'].'">'.$dataform[0]['txtsuppl'].'</textarea>
							</div>
							<div class="col-lg-2">
								<button data-toggle="dropdown" tabindex="-1" class="btn btn-default dropdown-toggle" type="button">
									'.$language['iso_code'].'
									<i class="icon-caret-down"></i>
								</button><ul class="dropdown-menu">';
			foreach ($languages as $language)
				$output .= '<li><a tabindex="-1" href="javascript:hideOtherLanguage('.$language['id_lang'].');">'.$language['name'].'</a></li>';
			$output .= '</ul>
							</div>
						</div>';
		}
$output .= '
	</div>
</div>';
$output .= '
	<div class="panel-footer">
		<a class="btn btn-default pull-left" href="index.php?tab=AdminModules&configure=contactform&token='.Tools::getValue('token').
	'&task=relviewelm&rid='.$rid.'"><i class="process-icon-cancel"></i> '.
	Translate::getModuleTranslation('contactform', 'Cancel', 'contactform').'</a>
		<button class="btn btn-default pull-right" name="saveelmrel" type="submit"><i class="process-icon-save"></i> '.
		Translate::getModuleTranslation('contactform', '    Save    ', 'contactform').'</button>
	</div>';
	$output .= '</div></form>';
	$output .= '</div>';
	return $output;
}
public static function showfieldList($mypath, $fid, $id_lang)
{
		$output = '';
		$mytoken = Tools::getValue('token');
		$fid = (int)Tools::getValue('fid');
		$forderby = Tools::getValue('forderby', 'order');
		$asc = Tools::getValue('asc', 'ASC');
		if ($asc == '') $asc = 'ASC';
		if ($forderby == '') $forderby = 'order';
		$url = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=showfieldList&fid='.$fid;
		$url2 = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editform&fid='.$fid;
		$output .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery("#content").toggleClass("nobootstrap bootstrap");});</script>';
		$output .= '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/isocraprint.css" />';
		/*$output .= '<script src="'.$mypath.'views/js/dragdrop/jquery_002.js" type="text/javascript"></script>';*/
		$output .= '<script src="'.$mypath.'views/js/dragdrop/jquery.js" type="text/javascript"></script>';
		$listfields = Db::getInstance()->ExecuteS('SELECT cfit.*, cflit.*
											 FROM `'._DB_PREFIX_.'contactform_item` cfit 
											 LEFT JOIN `'._DB_PREFIX_.'contactform_item_lang` cflit  ON cfit.`fdid` = cflit.`fdid` 
											 WHERE cflit.`id_lang`='.(int)$id_lang.' AND  cfit.`fid`='.(int)$fid.'
											 ORDER BY cfit.'.$forderby.' '.$asc.'
											 ');
		if ($asc == 'ASC')
			$asc = 'DESC';
		elseif ($asc == 'DESC')
			$asc = 'ASC';
		else
			$asc = 'ASC';
		$params_form = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform`  WHERE `fid`='.(int)$fid);
		$output .= '<script type="text/javascript">
		checked=false;
		function checkedAll (frm1) {
		var aa= document.getElementById("frm1");
	 	if (checked == false)
          	{
           	checked = true
          	}
        else
          {
          checked = false
          }
		for (var i =0; i < aa.elements.length; i++) 
		{
	 		aa.elements[i].checked = checked;
		}
      }
</script>
	 <script>
	$(document).ready(function() {
	// Initialise the first table (as before)
	// Initialise the second table specifying a dragClass and an onDrop function that will display an alert
	$("#table-2").tableDnD({
	    onDragClass: "myDragClass",
	    onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = "Row dropped was "+row.id+". New order: ";
			var order="";
			var neworder = document.getElementById("neworder");
            for (var i=0; i<rows.length; i++) {
                debugStr += rows[i].id+" ";
				order += rows[i].id+" ";
            }
	        $("#debugArea").html(debugStr);
			neworder.value = order;
	    },
		onDragStart: function(table, row) {
			$("#debugArea").html("Started dragging row "+row.id);
		}
	});
    
});
	</script>';
		$output .= CFtoolsbar::toolbar('editform', $mypath, $id_lang);
		$output .= '<div class="bootstrap">';
		$output .= '<form id ="frm1" name="frm1" method="post" action="'.$_SERVER['REQUEST_URI'].'" >';
		$output .= '<div class="pspage panel"> <h3 class="tab"><i class="icon-info"></i> '.
		Translate::getModuleTranslation('contactform', 'List fields', 'contactform').'<span style="display: block;float:right">'.
		Translate::getModuleTranslation('contactform', 'Form name', 'contactform').' <a href="'.$url2.'">['.
		$params_form[0]['formname'].']</a> | '.Translate::getModuleTranslation('contactform', 'ID', 'contactform').
		' ['.$params_form[0]['fid'].']</span></h3>';
		$output .= '<table id="table-2" width="100%" class="table" cellspacing="0" cellpadding="0">';
		$output .= '<thead>	<tr class="nodrag nodrop">
							<th><input type="checkbox" name="checkall" onclick="checkedAll(frm1);"></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=order">'.
							Translate::getModuleTranslation('contactform', 'Order', 'contactform').'</a></th>
							<th><input style="margin: 0px; padding: 0px; width: 30px;" type="image" name="upFieldorder" src="'.
							$mypath.'views/img/save.png"></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_id">'.
							Translate::getModuleTranslation('contactform', 'Field Id', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_name">'.
							Translate::getModuleTranslation('contactform', 'Field Name', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_title">'.
							Translate::getModuleTranslation('contactform', 'Field Title', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_type">'.
							Translate::getModuleTranslation('contactform', 'Type', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_desc">'.
							Translate::getModuleTranslation('contactform', 'Field Desc', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=confirmation">'.
							Translate::getModuleTranslation('contactform', 'Confirmation', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_valid">'.
							Translate::getModuleTranslation('contactform', 'Validation', 'contactform').'</a></th>
							<th><a href="'.$url.'&asc='.$asc.'&forderby=fields_require">'.
							Translate::getModuleTranslation('contactform', 'Require', 'contactform').'</a></th>
							<th align="center"><a href="'.$url.'&asc='.$asc.'&forderby=published">'.
							Translate::getModuleTranslation('contactform', 'Published', 'contactform').'</a></th>
							<th style=" width:60px"></th>
							</tr></thead>';
		foreach ($listfields as $listfield)
		{
			$url = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.
			'&task=addfield&fid='.$listfield['fid'].'&fdid='.$listfield['fdid'].'';
			$delurl = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.
			$mytoken.'&task=delfield&fid='.$listfield['fid'].'&fdid='.$listfield['fdid'].'';
			$urlstatus = 'index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.
			$mytoken.'&task=changestatus&status='.$listfield['published'].'&fid='.$listfield['fid'].'&fdid='.$listfield['fdid'].'';
			$output .= '<tr style="cursor: move;" class="alt" id="'.$listfield['fdid'].'">';
			$output .= '<td><input type="checkbox" name="actlink['.$listfield['fdid'].']" value="1"></td>';
			$output .= '	<td align="center" style="background:url('.$mypath.'views/img/move.png) no-repeat center">
							</td>';
			$output .= '<td><input name="order_'.$listfield['fdid'].'" type="text" value="'.$listfield['order'].'" size="3" ></a></td>';
			$output .= '<td><a href="'.$url.'">'.$listfield['fields_id'].'</td></a>';
			$output .= '<td><a href="'.$url.'">'.$listfield['fields_name'].'</td></a>';
			$output .= '<td><a href="'.$url.'">'.$listfield['fields_title'].'</a></td>';
			$output .= '<td><a href="'.$url.'">'.$listfield['fields_type'].'</a></td>';
			$output .= '<td><a href="'.$url.'">'.self::substrStr(15, $listfield['fields_desc']).'</a></td>';
			$output .= '<td><a href="'.$url.'">'.($listfield['confirmation'] == 1 ?
															Translate::getModuleTranslation('contactform', 'Yes', 'contactform') :
															Translate::getModuleTranslation('contactform', 'No', 'contactform')).'</a></td>';
			$output .= '<td><a href="'.$url.'">'.$listfield['fields_valid'].'</a></td>';
			$output .= '<td><a href="'.$url.'">'.($listfield['fields_require'] == 1 ?
															Translate::getModuleTranslation('contactform', 'Yes', 'contactform') :
															Translate::getModuleTranslation('contactform', 'No', 'contactform')).'</a></td>';
			$output .= '<td align="center">
							<a title="'.($listfield['published'] == 1 ?
													Translate::getModuleTranslation('contactform', 'Unpublished', 'contactform') :
													Translate::getModuleTranslation('contactform', 'Published', 'contactform')).
							'" href="'.$urlstatus.'" ><img alt="'.Translate::getModuleTranslation('contactform', 'Published', 'contactform').
							'" src="'.$mypath.'views/img/'.($listfield['published'] == 1 ? 'ok.png' : 'forbbiden.gif').'"></a>
						 </td>';
			$output .= '<td>
							<a style="display: block; float:left;" title="'.
							Translate::getModuleTranslation('contactform', 'Edit Field', 'contactform').'" href="'.
							$url.'"><img alt="'.Translate::getModuleTranslation('contactform', 'Edit Field', 'contactform').'" src="'.
							$mypath.'views/img/edition.png"></a>
							<a style="display: block; float: right; text-align: center; line-height: 30px;" title="'.
							Translate::getModuleTranslation('contactform', 'Delete Field', 'contactform').'" href="'.
							$delurl.'" onclick="return(confirm(\''.
						Translate::getModuleTranslation('contactform', 'Do you really want  to delete this field', 'contactform').'\'));">
							<img alt="'.Translate::getModuleTranslation('contactform', 'Delete Field', 'contactform').
						'" src="'.$mypath.'views/img/delete.png"></a>
						 </td>';
			$output .= '</tr>';
		}
		$output .= '</table>';
		$output .= '<input type="hidden" name="fid" value="'.$fid.'">';
		$output .= '<input style="margin:10px;" class="button" type="submit" name="deleteselectfld" value="'.
		Translate::getModuleTranslation('contactform', 'Delete selected', 'contactform').'" onclick="return(confirm(\''.
		Translate::getModuleTranslation('contactform', 'Do you really want  to delete fields selected?', 'contactform').'\'));"> ';
		$output .= '<input type="hidden" value="2.2|1.1|3.3|4.4|" name="neworder" id="neworder" />';
		$output .= '<input style="margin:10px;" class="button" type="submit" name="submitorder" value="'.
		Translate::getModuleTranslation('contactform', 'Drag and change order', 'contactform').'">'.
		self::info($mypath, 'Drag row first to change position then clic here');
		$output .= '</div>';
		$output .= '</form>';
		$output .= '</div>';
		$output .= '</fieldset>';
	return $output;
}

public static function substrStr($max_caracteres, $txt)
{
	$texte = Tools::substr($txt, 0, $max_caracteres);
	return $texte.' ...';
}

public function delForm($fid)
{
	$mytoken = Tools::getValue('token');
	$languages = Language::getLanguages();
	$cmp = 0;
	if ($fid == '')
		$fid = (int)Tools::getValue('fid');
		if ($fid != 0)
		{
				$listfields = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.(int)$fid);
				foreach ($listfields as $field)
					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.(int)$field['fdid']);
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.(int)$fid);
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform` WHERE `fid`='.(int)$fid);
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.(int)$fid);
		}
		$ctlang = count($languages);
		for ($i = 0; $i < $ctlang; $i++)
		{
			$delform = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform` WHERE `fid`='.(int)$fid.'');
			if (count($delform) > 0)
				$cmp++;
		}
		if ($cmp == 0)
			$statut = 0;
		else
			$statut = 1;
		Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&&task=showformList2&token='.
																$mytoken.'&statut='.$statut);
}
public function delField($fid, $fdid)
{
	$mytoken = Tools::getValue('token');
	if ($fdid == '')
		$fdid = (int)Tools::getValue('fdid');
		if ($fdid != 0)
		{
			$fpple = Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_item` WHERE `fdid`='.(int)$fdid);
			$flang = Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.(int)$fdid);
			if ($fpple && $flang)
				$statut = 1;
			else
				$statut = 0;
		}
		else
			$statut = 1;
		Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.
																$mytoken.'&task=showfieldList2&fid='.$fid.'&statut='.$statut);
}
public static function changestatus($fdid, $updatestatus)
{
$fid = (int)Tools::getValue('fid');
$mytoken = Tools::getValue('token');
if ($fdid == '')
	$fdid = (int)Tools::getValue('fdid');
		Db::getInstance()->execute(' UPDATE `'._DB_PREFIX_.'contactform_item` SET `published`= '.$updatestatus.' WHERE `fdid`='.(int)$fdid);
	Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&&task=showfieldList&token='.$mytoken.'&fid='.$fid);
}
public static function ferrFormat($errmsg)
{
	$output = '<div style="border:1px solid #999999; background-color:#FFDFDF; width:99%; margin-bottom:20px; padding:5px">';
	$output .= '<font color=red>'.Translate::getModuleTranslation('contactform', $errmsg, 'contactform').'</font>:<br><br>';
	$output .= '</div>';
	return $output;
}
public static function cloneform($fid)
{
		$mytoken = Tools::getValue('token');
		//INSERT FORM INFORMATION
		$to_clone = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform` WHERE `fid`='.(int)$fid);
	if ($to_clone)
	{
		Db::getInstance()->insert('contactform', array(
		'formname' => $to_clone[0]['formname'],
		'email' => $to_clone[0]['email'],
		'mailtype' => 0,
		'layout'=>addslashes($to_clone[0]['layout']),
		'clayout'=>addslashes($to_clone[0]['clayout'])));
		$last_id = (int)Db::getInstance()->Insert_ID();
		$sql_copy = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_lang` WHERE `fid`='.(int)$fid);
		foreach ($sql_copy as $to_copy)
		{
				Db::getInstance()->insert('contactform_lang', array(
				'id_lang' => (int)$to_copy['id_lang'],
				'fid' => (int)$last_id,
				'alias' => addslashes($to_copy['alias']),
				'formtitle' => addslashes($to_copy['formtitle']),
				'thankyou' => addslashes($to_copy['thankyou']),
				'msgbeforeForm' => addslashes($to_copy['msgbeforeForm']),
				'msgafterForm' => addslashes($to_copy['msgafterForm']),
				'toname' =>addslashes($to_copy['toname']),
				'subject' => addslashes($to_copy['subject']),
				'automailresponse' => addslashes($to_copy['automailresponse']),
				'returnurl' => addslashes($to_copy['returnurl'])
				));

		}
			$fsql_to_copy = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.(int)$fid);
			foreach ($fsql_to_copy as $fcopy)
			{
					Db::getInstance()->insert('contactform_item', array(
							'fid' => (int)$last_id,
							'fields_id' => 'c_'.$fcopy['fields_id'],
							'fields_name' => 'c_'.$fcopy['fields_name'],
							'confirmation' => $fcopy['confirmation'],
							'fields_valid' => $fcopy['fields_valid'],
							'fields_type' => $fcopy['fields_type'],
							'fields_style' => $fcopy['fields_style'],
							'err_style' => $fcopy['err_style'],
							'fields_suppl' => $fcopy['fields_suppl'],
							'fields_require' => $fcopy['fields_require'],
							'fields_maxtxt' => $fcopy['fields_maxtxt'],
							'order' => $fcopy['order'],
							'published' => $fcopy['published']));
				$c_lastid = (int)Db::getInstance()->Insert_ID();
				$clone_items = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_item_lang` WHERE `fdid`='.
															(int)$fcopy['fdid']);
						foreach ($clone_items as $items)
						{
							Db::getInstance()->insert('contactform_item_lang', array(
								'fdid' => (int)$c_lastid,
								'id_lang' => (int)$items['id_lang'],
								'fields_title' => addslashes($items['fields_title']),
								'fields_desc' => addslashes($items['fields_desc']),
								'confirmation_txt' => addslashes($items['confirmation_txt']),
								'fields_default' => addslashes($items['fields_default']),
								'error_txt' => $items['error_txt'],
								'error_txt2' => $items['error_txt2'],
								));
						}
			}
			Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&&task=showformList2&token='.
																	$mytoken.'&statut=3');
	}
		else
			Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&&task=showformList2&token='.
																	$mytoken.'&statut=4');
}
public static function canonicalRedirection($canonical_url = '')
{
	if (!$canonical_url || !Configuration::get('PS_CANONICAL_REDIRECT') || Tools::strtoupper($_SERVER['REQUEST_METHOD']) != 'GET')
	return;
	$match_url = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$match_url = rawurldecode($match_url);
	if (!preg_match('/^'.Tools::pRegexp(rawurldecode($canonical_url), '/').'([&?].*)?$/', $match_url))
	{
	$params = array();
	$url_details = parse_url($canonical_url);
	if (!empty($url_details['query']))
	{
		parse_str($url_details['query'], $query);
		foreach ($query as $key => $value)
			$params[Tools::safeOutput($key)] = Tools::safeOutput($value);
	}
	$excluded_key = array('isolang', 'id_lang', 'controller', 'fc', 'id_product', 'id_category', 'id_manufacturer', 'id_supplier', 'id_cms');
	foreach ($_GET as $key => $value)
		if (!in_array($key, $excluded_key) && Validate::isUrl($key) && Validate::isUrl($value))
			$params[Tools::safeOutput($key)] = Tools::safeOutput($value);
	return $canonical_url;
	}
}
	public static function getColumnForm($pos, $id_lang, $id_shop)
	{
		$fid = Tools::getValue('fid', 0);
		$sqlallformc = 'SELECT * FROM `'._DB_PREFIX_.'contactform` WHERE position='.(int)$pos;
		$sqlallformc .= ((int)$fid) ? ' AND fid!='.(int)$fid : '';
		$allformc = DB::getInstance()->executeS($sqlallformc);
		$tabfields = array();
		$tabfields['name'] = array();
		$tabfields['label'] = array();
		$tabfields['value'] = array();
		$tabfields['fields_require'] = array();
		$tabfields['confirmation'] = array();
		$tabfields['fields_type'] = array();
		$tabfields['fields_valid'] = array();
		$tabfields['order'] = array();
		$form_html = '';
		if ($allformc)
		{
			$scriptrex = '<script type="text/javascript">
						var onloadCallback = function() {';
			switch (Configuration::get('CONTACTFORM_CAPTCHATHEME'))
			{
				case '1':
					$thm = 'light';
				break;
				case '2':
					$thm = 'dark';
				break;
				default:
					$thm = 'light';
				break;
			}
			$scriptrex .= Cfront::getRecaptchaScript($fid, $id_lang, $thm);
			foreach ($allformc as $allform)
			{
			$titleform = DB::getInstance()->getValue('SELECT formtitle FROM `'._DB_PREFIX_.
									'contactform_lang` WHERE fid='.(int)$allform['fid'].' AND id_lang='.(int)$id_lang);
				$form_html .= '<h4 class="title_block">'.$titleform.'</h4>';
				$form_html .= '<div class="block_content">';
				$form_html .= Cfront::checkForm($tabfields, $allform['fid'],
												$id_lang,
												$id_shop,
												1,
												__PS_BASE_URI__.'modules/contactform/',
												1);
				$form_html .= '</div>';
					$scriptrex .= Cfront::getRecaptchaScript($allform['fid'], $id_lang, $thm);
			}
			$scriptrex .= '};
				</script>
			';
			$form_html .= $scriptrex;
			/* English en
			Dutch	nl
			French	fr
			German	de
			Portuguese	pt
			Russian	ru
			Spanish	es
			Turkish	tr*/
			$caplang2 = array(
						0 => 'en',
						1 => 'nl',
						2 => 'fr',
						3 => 'de',
						4 => 'pt',
						5 => 'ru',
						6 => 'es',
						7 => 'tr',
						);
			$langcap2 = Language::getLanguage($id_lang);
			$captchalang2 = (in_array($langcap2['iso_code'], $caplang2)) ? $langcap2['iso_code'] : 'en';
			$form_html .= '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl='.
			$captchalang2.'" async defer></script>';
		}
		return $form_html;
	}
}

?>