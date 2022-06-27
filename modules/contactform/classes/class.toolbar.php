<?php
/**
* 2015 Aretmic
*
* NOTICE OF LICENSE 
* 
* ARETMIC the Company grants to each customer who buys a virtual product license to use, and non-exclusive and worldwide. This license is 
* valid only once for a single e-commerce store. No assignment of rights is hereby granted by the Company to the Customer. It is also 
* forbidden for the * Customer to resell or use on other virtual shops Products made by ARETMIC. This restriction includes all resources 
* provided with the virtual product. 
*
* @author    Aretmic SA
* @copyright 2015 Aretmic SA
* @license   ARETMIC
* International Registered Trademark & Property of Aretmic SA
*/

require_once(dirname(__FILE__).'/../../../config/config.inc.php');
class CFtoolsbar {
public static function toolbar($type, $mypath, $id_lang)
{
	$link = new Link();
	$mytoken = Tools::getValue('token');
	$fid = (int)Tools::getValue('fid');
	$output = DatatExport::includFiles($mypath, $id_lang);
	$output .= '<style type="text/css">
					table tr th a,table tr th{
						text-decoration: none !important;
						font-weight: bold;
						color:#000000;
					}	
					table tr td a,table tr td{
						text-decoration: none !important;	
						color:#000000;
					}
					#cftoolbar tr td a{color: #FFF; text-decoration: none;}
					#form table tr td{padding:5px;}
					a{text-decoration: none;}
				</style>';

	switch ($type)
	{
		case 'showform':
			$size = '75%';
		break;
		case 'editform':
			if ($fid != 0)
				$size = '55%';
			else
				$size = '85%';
		break;
		case 'restoreform':
			$size = '95%';
		break;
		case 'settings':
			$size = '60%';
		break;
		case 'classic':
			$size = '95%';
		break;
		case 'seedata':
			$size = '85%';
		break;
		case 'seedetails':
			$size = '95%';
		break;
		default:
			$size = '60%';
		break;

	}
		$output .= '<div class="bootstrap">
				<div class="panel" style="background: none repeat scroll 0px 0px rgb(0, 175, 240);">
				<table id="cftoolbar" width=100%">
				<tr align="center">
				<td width="'.$size.'" align="left">
				'.self::barHomepage($mypath).
				'</td>';

				switch ($type)
				{
					//-----------------------Settings---------------------------------
					case 'settings':
						$output .= '
	
			<td>
				<a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=editcss">
				<img title="'.Translate::getModuleTranslation('contactform', 'Edit Css', 'contactform').'" src="'.$mypath.'views/img/css.png"></a>
			</td>';
	$output .= '	<td><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'">
	<img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'"  src="'.$mypath.'views/img/cancel.png"></a></td>
		</tr><tr align="center"><td></td>';
	$output .= '<td><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=editcss">'.
	Translate::getModuleTranslation('contactform', 'Edit Css', 'contactform').'</a></td>';
	$output .= '<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
	'" href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'">
	'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
					break;
					//---------------------------------------Show form ---------------------------------
					case 'showform':

							$output .= '	<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&task=editform&token='.$mytoken.'">
							<img title="'.Translate::getModuleTranslation('contactform', 'New form', 'contactform').'" src="'.$mypath.'views/img/addform.png"></a></td>
				<td align="center" style="text-align: center;"><a href="index.php?tab=AdminModules&configure=contactform&task=formrelation&token='.
				$mytoken.'">
				<img title="'.Translate::getModuleTranslation('contactform', 'Back', 'contactform').'" src="'.$mypath.'views/img/relation.png"></a></td>
				<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.
				$mytoken.'">
				<img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.$mypath.'views/img/cancel.png"></a></td>
	</tr>
	<tr >
		<td></td>
		<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&task=editform&token='.
		$mytoken.'">'.Translate::getModuleTranslation('contactform', 'New form', 'contactform').'</a></td>
		<td align="center" style="text-align: center;"><a href="index.php?tab=AdminModules&configure=contactform&task=formrelation&token='.$mytoken.'">'.
		Translate::getModuleTranslation('contactform', 'Relation between form', 'contactform').'</a></td>
		<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.
		$mytoken.'">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
	</tr>';
					break;
					//---------------------------------------Relation form ---------------------------------
					case 'formrelation':

							$output .= '	<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&task=createrelation&token='.$mytoken.'">
							<img title="'.Translate::getModuleTranslation('contactform', 'New form', 'contactform').'" src="'.$mypath.'views/img/addform.png"></a></td>
				<td align="center" style="text-align: center;" ><a href="index.php?tab=AdminModules&configure=contactform&task=formrelation&token='.$mytoken.'">
				<img title="'.Translate::getModuleTranslation('contactform', 'Back', 'contactform').'" src="'.$mypath.'views/img/relation.png"></a></td>
				<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=showformList">
				<img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.$mypath.'views/img/cancel.png"></a></td>
	</tr>
	<tr >
		<td></td>
		<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&task=createrelation&token='.$mytoken.'">'.
		Translate::getModuleTranslation('contactform', 'Create', 'contactform').'</a></td>
		<td align="center" style="text-align: center;">
		<a href="index.php?tab=AdminModules&configure=contactform&task=formrelation&token='.$mytoken.'">'.
		Translate::getModuleTranslation('contactform', 'Relation between form', 'contactform').'</a></td>
		<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=showformList">'.
		Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
	</tr>';
					break;
					//--------------------------------------End relation form ------------------------------
					case 'relviewelm':
							$output .= '<link rel="stylesheet" type="text/css" href="'.$mypath.'views/css/isocraprint.css" />';
							$output .= '<script src="'.$mypath.'views/js/dragdrop/jquery.js" type="text/javascript"></script>';
							$output .= '	<td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
				<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.
				$mytoken.'&task=formrelation"><img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
				'" src="'.$mypath.'views/img/cancel.png"></a></td>
	</tr>
	<tr >
		<td></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center"><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=formrelation">'.
		Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
	</tr>';
					break;
					//--------------------------------------End viewelement relation ------------------------------
					case 'editform':
							if ($fid != 0)
							{
	$output .= '<td>
				<a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=addfield&fid='.$fid.'">
				<img title="'.Translate::getModuleTranslation('contactform', 'New field', 'contactform').'" src="'.$mypath.'views/img/add.png"></a>
				</td>
				<td>
				<a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=showfieldList&fid='.$fid.'">
				<img title="'.Translate::getModuleTranslation('contactform', 'List Fields', 'contactform').'" src="'.$mypath.'views/img/list.png"></a>
			</td>
			<td>
			<a target="_blank" href="'.$link->getPageLink('cform').'&fid='.$fid.'">
			<img title="'.Translate::getModuleTranslation('contactform', 'Preview', 'contactform').'" src="'.$mypath.'views/img/preview.png"></a>
			</td>';
							}
	$output .= '	
					<td><a href="index.php?tab=AdminModules&configure=contactform&task=showformList&token='.
					$mytoken.'"><img title="'.Translate::getModuleTranslation('contactform', 'List Form', 'contactform').'" src="'
					.$mypath.'views/img/list.png"></a></td>
					<td><a href="index.php?tab=AdminModules&configure=contactform&task=showformList&token='.
					$mytoken.'"><img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.
					$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>';
		if ($fid != 0)
		{
		$output .= '
		<td><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'&task=addfield&fid='.$fid.'">'.
		Translate::getModuleTranslation('contactform', 'New field', 'contactform').'</a></td>
			<td><a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.
			'&task=showfieldList&fid='.$fid.'">'.Translate::getModuleTranslation('contactform', 'List Fields', 'contactform').'</a></td>
			<td><a target="_blank" href="'.$link->getPageLink('cform').'&fid='.$fid.'">'.
			Translate::getModuleTranslation('contactform', 'Preview', 'contactform').'</a></td>';
		}

		$output .= '	
				<td><a href="index.php?tab=AdminModules&configure=contactform&token='.
				$mytoken.'&task=showformList">'.Translate::getModuleTranslation('contactform', 'List Form', 'contactform').'</a></td>
				<td><a href="index.php?tab=AdminModules&configure=contactform&token='.
				$mytoken.'&task=showformList">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
				break;
				case 'exportform':

			$output .= '<td><input type="image" name="subSavesql" src="'.$mypath.'views/img/save.png">
			</td>
			<td>
			<a href="index.php?tab=AdminModules&configure=contactform&token='.
			$mytoken.'&task=saveSql"><img src="'.$mypath.'views/img/altsave.png"></a>
			</td>
			
			';
	$output .= '	<td><a href="index.php?tab=AdminModules&configure=contactform&token='.
	$mytoken.'"><img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>
		
			<td>'.Translate::getModuleTranslation('contactform', '   Save   ', 'contactform').'</td>
			<td><a href="index.php?tab=AdminModules&configure=contactform&token='.
			$mytoken.'&task=saveSql">'.Translate::getModuleTranslation('contactform', 'Backup Alternative', 'contactform').'</a></td>';
		$output .= '<td><a href="index.php?tab=AdminModules&configure=contactform&token='.
		$mytoken.'">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
				break;
				case 'restoreform':
					$output .= '	<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
					'" href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'">
					<img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>';
		$output .= '<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" href="
		index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'">'.
		Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
				break;
				case 'classic':
						$output .= '	<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" href=
						"index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'">
						<img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>';
		$output .= '<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
		'" href="index.php?tab=AdminModules&configure=contactform&token='.
		$mytoken.'">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
				break;
				case 'seedata':
						$output .= '
						<td> <a href="#" id="try-1" class="exportcsv"></a></td>
						<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
						'" href="index.php?tab=AdminModules&configure=contactform&token='.
						$mytoken.'"><img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'" src="'.
						$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>
			<td><a href="#" id="try-2" class="">'.Translate::getModuleTranslation('contactform', 'Export', 'contactform').'</a></td>';
		$output .= '<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
		'" href="index.php?tab=AdminModules&configure=contactform&token='.
		$mytoken.'">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
		$cflang = CFtools::getIsocode($id_lang);
		if ($cflang)
			$isoname = $cflang;
		else
			$isoname = 'en';
		if ($isoname == 'en')
			$dateformat = 'd/m/Y';
		else
			$dateformat = 'Y/m/d';
		$output .= '<div id="sign_up">
                <h3>'.Translate::getModuleTranslation('contactform', 'Export your data', 'contactform').'</h3>
                <span>'.Translate::getModuleTranslation('contactform', 'Please, fill the form below', 'contactform').'</span>
				<form method="POST" action="'.$_SERVER['REQUEST_URI'].'">
                <div id="sign_up_form">
                    	
						<ul class="info_export">
						<li>
							<p>'.Translate::getModuleTranslation('contactform', 'Format', 'contactform').':</p>
							<select name="format">
								<option value="xls">Excel xls</option>
								<option selected="selected" value="csv">CSV</option>
							</select>
							'.Translate::getModuleTranslation('contactform', 'Csv separator', 'contactform').' : <input type="text" size="1" name="separator" value=";">
						</li>
						<li>
							<div>
							<p>formulaire:</p>
							<select name="formid">
							<option value="0" selected="selected">'.Translate::getModuleTranslation('contactform', 'All', 'contactform').'</option>'.
							DatatExport::getFormList().
							'</select></div>
						<div id="multi">
						<p>'.Translate::getModuleTranslation('contactform', 'Start date', 'contactform').':</p>	<input type="text" name="dateA"/><br />
							
						<p>'.Translate::getModuleTranslation('contactform', 'End date', 'contactform').':</p>	<input type="text" name="dateB"/></button>
						</div>
						</li>
						</ul>
						<input type="hidden" value="'.$dateformat.'" name="dateformat">
						<input type="hidden" value="'.$isoname.'" name="isoname">
					<input type="submit" name="exportalldata" value="'.Translate::getModuleTranslation('contactform', 'Export', 'contactform').'">	
                  </form>  
                </div>
                <a id="close_x" class="close sprited" href="#" onclick="closePopup()">close</a>
            </div>';
				break;
				case 'seedetails':
						$output .= '	<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
						'" href="index.php?tab=AdminModules&configure=contactform&token='.
						$mytoken.'&task=seedata"><img title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
						'" src="'.$mypath.'views/img/cancel.png"></a></td>
		</tr>
		<tr align="center">
			<td></td>';
		$output .= '<td><a title="'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').
		'" href="index.php?tab=AdminModules&configure=contactform&token='.
		$mytoken.'&task=seedata">'.Translate::getModuleTranslation('contactform', 'Close', 'contactform').'</a></td>
		</tr>';
				break;
					default:
					break;
				}
		$output .= '</table>		';
	$output .= '</div></div><br>';
		return $output;
}
public static function barHomepage($link)
{
$mytoken = Tools::getValue('token');
$url = 'index.php?tab=AdminModules&configure=contactform&token='.$mytoken;
$output = '<link rel="stylesheet" type="text/css" href="'.$link.'views/css/anylinkcssmenu.css" />';
$output .= '<script type="text/javascript" src="'.$link.'views/js/homemenu/anylinkcssmenu.js" /></script>';
$output .= '
	<script type="text/javascript">
	//anylinkcssmenu.init("menu_anchors_class") ////Pass in the CSS class of anchor links (that contain a sub menu)
	anylinkcssmenu.init("anchorclass")
	</script>
	<a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'" >
	<img alt="'.Translate::getModuleTranslation('contactform', 'Home', 'contactform').'" title="'.
	Translate::getModuleTranslation('contactform', 'Home', 'contactform').'" src="'.$link.'views/img/home.png"></a>
	
	<a href="index.php?tab=AdminModules&configure=contactform&token='.$mytoken.'" class="anchorclass myownclass" rel="submenu3">
	<img alt="Menu" title="Menu" src="'.$link.'views/img/kmenu.png"></a>
														
	<div id="submenu3" class="anylinkcsscols">';
$output .= '<table class="homepage" border="1">';
	$output .= '<tr>';
	$output .= '<td>';
	$output .= '<table class="homepage1" border="1" >';
		$output .= '<tr>';
			$output .= '<td><a href="'.$url.'&task=showformList"><img src="'.$link.'views/img/edit-mini.png"><br>'.
							Translate::getModuleTranslation('contactform', 'Managing your form', 'contactform').'</a></td>';
			$output .= '<td><a href="'.$url.'&task=seedata"><img src="'.$link.'views/img/see-mini.png"><br>'.
			Translate::getModuleTranslation('contactform', 'See data', 'contactform').'</a></td>';
			$output .= '<td><a href="'.$url.'&task=addsample"><img src="'.$link.'views/img/sample-mini.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Add sample data', 'contactform').'</a></td>';
		$output .= '</tr>';
		$output .= '<tr>';
			$output .= '<td><a href="'.$url.'&task=exportForm"><img src="'.$link.'views/img/save-mini.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Save your form', 'contactform').'</a></td>';
			$output .= '<td><a href="'.$url.'&task=restoreForm"><img src="'.$link.'views/img/store-mini.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Restore your Form', 'contactform').'</a></td>';
			$output .= '<td><a href="'.$url.'&task=settings"><img src="'.$link.'views/img/settings-mini.png"><br>'.
			Translate::getModuleTranslation('contactform', 'Settings', 'contactform').'</a></td>';
		$output .= '</tr>';
	$output .= '</table>';
	$output .= '</td>';
	$output .= '</tr>';
	$output .= '</table>';
$output .= '	</div></div>';
	return $output;

}
}
?>