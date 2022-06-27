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

if (!defined('_PS_VERSION_'))
	exit;

include_once _PS_MODULE_DIR_.'contactform/classes/class.tools.php';
include_once _PS_MODULE_DIR_.'contactform/classes/class.toolbar.php';
include_once _PS_MODULE_DIR_.'contactform/classes/class.utils.php';
include_once _PS_MODULE_DIR_.'contactform/classes/class.dataexport.php';
include_once _PS_MODULE_DIR_.'contactform/classes/class.front.php';

class ContactForm extends Module
{
	private $html = '';
	private $utr = '';
	private $cpr = '';
	private $cookie;

	public function __construct()
	{
		$this->name = 'contactform';
		$this->module_key = '6dda00af0f73e9cf66d220677eef69ed';
		$this->version = '3.3.4';
		$this->tab = 'others';
		$this->author = 'Aretmic';
		parent::__construct();
		$this->displayName = $this->l('Form management');
		$this->description = $this->l('This allow you to manage your contact form');
	}
	public function install()
	{
		$cfwidth = (version_compare(_PS_VERSION_, '1.6', '>=')) ? 100 : 94;
		if (!parent::install()
							|| !$this->registerHook('top')
							|| !$this->registerHook('header')
							|| !$this->registerHook('displayLeftColumn')
							|| !$this->registerHook('modalHook')
							|| !Configuration::updateValue('CONTACTFORM_MAILTYPE', 1)
							|| !Configuration::updateValue('CONTACTFORM_FILENAME', 'contact.php')
							|| !Configuration::updateValue('CONTACTFORM_ACTIVESEO', 0)
							|| !Configuration::updateValue('CONTACTFORM_NOTIF', 1)
							|| !Configuration::updateValue('CONTACTFORM_CCTPL', 'default')
							|| !Configuration::updateValue('CONTACTFORM_REQUIRED', '*')
							|| !Configuration::updateValue('CONTACTFORM_UPFORMAT', 'jpg,png,gif,bmp,doc,docx,pdf,txt')
							|| !Configuration::updateValue('CONTACTFORM_AUT', 'nzzu?++ggg$fxkztoe$evt')
							|| !Configuration::updateValue('CONTACTFORM_MAILHEADER', '#009900')
							|| !Configuration::updateValue('CONTACTFORM_AUTOINFO', 'true')
							|| !Configuration::updateValue('CONTACTFORM_CFGCKBOX', 1)
							|| !Configuration::updateValue('CONTACTFORM_CFGRADIO', 1)
							|| !Configuration::updateValue('CONTACTFORM_ACTIVE', 1)
							|| !Configuration::updateValue('CONTACTFORM_DEACTIVE', 0)
							|| !Configuration::updateValue('CONTACTFORM_WIDTH', $cfwidth)
							|| !Configuration::updateValue('CONTACTFORM_STYLE', 1)
							|| !Configuration::updateValue('CONTACTFORM_FORM', 1)
							|| !Configuration::updateValue('CONTACTFORM_AUTH', '0')
							|| !Configuration::updateValue('CONTACTFORM_SHOWCAR', '0')
							|| !Configuration::updateValue('CONTACTFORM_MULTIFORM', 0)
							|| !Configuration::updateValue('CONTACTFORM_DEFAULTFORM', 0)
							|| !$this->installDB())
			return false;
		return true;
	}

	public function installDB()
	{
		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform` (
		`fid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`formname`  VARCHAR( 225 ) NOT NULL,
		`email` VARCHAR( 225 )  NOT NULL, 
		`mailtype` VARCHAR( 225 )  NOT NULL,
		`layout` text,
		`clayout` text,
		`idcms` INT(11) NOT NULL DEFAULT \'1\',
		`id_shop` INT(11) NOT NULL DEFAULT \'1\',
		`position` INT(11) NOT NULL DEFAULT \'0\',
		`pdf` INT( 2 )  NOT NULL DEFAULT \'0\',
		`notif_pdf` INT( 2 )  NOT NULL DEFAULT \'0\'
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_lang` (
		`id_lang` INT NOT NULL,
		`fid`  VARCHAR( 225 ) NOT NULL,
		`alias` VARCHAR( 225 )  NULL ,
		`formtitle` VARCHAR( 225 ) NOT NULL,
		`thankyou` text,
		`msgbeforeForm` text,
		`msgafterForm` text,
		`toname` VARCHAR( 225 ) NOT NULL,
		`subject` VARCHAR( 225 )  NULL,
		`subject_notif` VARCHAR( 225 )  NULL,
		`automailresponse` text,
		`returnurl` VARCHAR( 225 )  NULL 
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_item` (
		`fdid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`fid` INT NOT NULL ,
		`fields_id` VARCHAR( 225 ) NULL ,
		`fields_name` VARCHAR( 225 ) NULL ,
		`confirmation` INT NOT NULL ,
		`fields_valid` VARCHAR( 225 )  NOT NULL ,
		`fields_type` VARCHAR( 225 ) NOT NULL ,
		`fields_style` text,
		`err_style` text,
		`fields_suppl` VARCHAR( 255 ) NOT NULL ,
		`fields_require` INT NOT NULL ,
		`fields_maxtxt` VARCHAR( 255 ) NOT NULL ,
		`order` INT NOT NULL ,
		`published` INT NOT NULL ,
		INDEX ( `fdid` , `fid` )
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_item_lang` (
		`fdid` INT NOT NULL ,
		`id_lang` INT NOT NULL ,
		`fields_title` VARCHAR( 225 ) NOT NULL ,
		`fields_desc` TEXT( 1024 )  NULL ,
		`confirmation_txt` VARCHAR( 225 )  NOT NULL ,
		`fields_default` text,
		`error_txt` VARCHAR( 255 )  NULL,
		`error_txt2` VARCHAR( 255 )  NULL 
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_data` (
		`data_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`ip`  VARCHAR( 225 ) NOT NULL,
		`date` VARCHAR( 225 ) NOT NULL,
		`toemail` VARCHAR( 225 )  NULL,
		`foremail` VARCHAR( 225 )  NULL,
		`info` text,
		`statut_mail` VARCHAR( 225 ) NOT NULL, 
		`comment` VARCHAR( 225 ) NULL,
		`fid` INT NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_info` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`type` VARCHAR( 225 )  NULL,
		PRIMARY KEY (`id`)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_info_lang` (
		`id` int(11) NOT NULL,
		`id_lang` int(11) NOT NULL,
		`value` varchar(255) NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_relation` (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`etat`  TINYINT(1) NOT NULL DEFAULT \'1\',
		`type` TINYINT(1) NOT NULL DEFAULT \'1\',
		`default` INT(10) NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_relation_lang` (
		`rid` INT(11) UNSIGNED NOT NULL ,
		`id_lang`  INT(11) NOT NULL ,
		`title` TEXT(1024)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_relation_item` (
		`rid` INT(11)NOT NULL ,
		`fid` INT(11) NOT NULL ,
		`order`  INT(11) NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');

		Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'contactform_relation_item_lang` (
		`rid` INT(11)NOT NULL ,
		`fid` INT(11) NOT NULL ,
		`id_lang`  INT(11) NOT NULL,
		`txtsuppl` VARCHAR(1024) NOT NULL
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');
		/* BACKUP existing file Dispatcher.php*/
			if (file_exists(_PS_ROOT_DIR_.'/override/classes/Dispatcher.php'))
					Tools::copy(_PS_ROOT_DIR_.'/override/classes/Dispatcher.php',
						_PS_MODULE_DIR_.'contactform/bkp/original/Dispatcher.php');
		/* COPY Dispatcher in override folder */
			$cpdisp = Tools::copy(_PS_MODULE_DIR_.'contactform/install/override/Dispatcher.php',
			_PS_ROOT_DIR_.'/override/classes/Dispatcher.php');
			/* BACKUP existing file Link.php*/
			if (file_exists(_PS_ROOT_DIR_.'/override/classes/Link.php'))
					Tools::copy(_PS_ROOT_DIR_.'/override/classes/Link.php',
						_PS_MODULE_DIR_.'contactform/bkp/original/Link.php');
			/* BACKUP existing file Link.php*/
			if (file_exists(_PS_ROOT_DIR_.'/override/classes/Mail.php'))
					Tools::copy(_PS_ROOT_DIR_.'/override/classes/Mail.php',
						_PS_MODULE_DIR_.'contactform/bkp/original/Mail.php');
		/* COPY Link in override folder */
			$cpdisp2 = Tools::copy(_PS_MODULE_DIR_.'contactform/install/override/Link.php',
						_PS_ROOT_DIR_.'/override/classes/Link.php');
		/* COPY Link in override folder */
			$cpdisp3 = Tools::copy(_PS_MODULE_DIR_.'contactform/install/override/Mail.php',
						_PS_ROOT_DIR_.'/override/classes/Mail.php');
		/* DELETE cache file*/
			if (file_exists(_PS_ROOT_DIR_.'/cache/class_index.php'))
					unlink(_PS_ROOT_DIR_.'/cache/class_index.php');
			if (!$cpdisp || !$cpdisp2 || !$cpdisp3)
				return false;
		return true;
	}
	public function uninstall()
	{
		if (file_exists(_PS_MODULE_DIR_.'contactform/bkp/original/Dispatcher.php'))
			Tools::copy(_PS_MODULE_DIR_.'contactform/bkp/original/Dispatcher.php',
				_PS_ROOT_DIR_.'/override/classes/Dispatcher.php');
		if (file_exists(_PS_MODULE_DIR_.'contactform/bkp/original/Link.php'))
			Tools::copy(_PS_MODULE_DIR_.'contactform/bkp/original/Link.php',
				_PS_ROOT_DIR_.'/override/classes/Link.php');

		if (!parent::uninstall()
							|| !$this->unregisterHook('displayLeftColumn')
							|| !Configuration::deleteByName('CONTACTFORM_NOTIF')
							|| !Configuration::deleteByName('CONTACTFORM_CCTPL')
							|| !Configuration::deleteByName('CONTACTFORM_REQUIRED')
							|| !Configuration::deleteByName('CONTACTFORM_AUT')
							|| !Configuration::deleteByName('CONTACTFORM_MAILHEADER')
							|| !Configuration::deleteByName('CONTACTFORM_AUTOINFO')
							|| !Configuration::deleteByName('CONTACTFORM_CFGCKBOX')
							|| !Configuration::deleteByName('CONTACTFORM_CFGRADIO')
							|| !Configuration::deleteByName('CONTACTFORM_UPFORMAT')
							|| !Configuration::deleteByName('CONTACTFORM_WIDTH')
							|| !Configuration::deleteByName('CONTACTFORM_STYLE')
							|| !Configuration::deleteByName('CONTACTFORM_FORM')
							|| !Configuration::deleteByName('CONTACTFORM_AUTH')
							|| !Configuration::deleteByName('CONTACTFORM_SHOWCAR')
							|| !Configuration::deleteByName('CONTACTFORM_MULTIFORM')
							|| !Configuration::deleteByName('CONTACTFORM_DEFAULTFORM')
							|| !$this->uninstallDB())
		return false;
		return true;
	}

	private function uninstallDb()
	{
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_item`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_lang`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_item_lang`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_data`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_info`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_info_lang`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_relation`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_relation_lang`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_relation_item`');
		Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'contactform_relation_item_lang`');
		return true;
	}
	public function getContent()
	{
		$fid			 =	(int)Tools::getValue('fid');
		$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages();
		$mytoken = Tools::getValue('mytoken');

		$task = Tools::getValue('task');

		if (Configuration::get('CONTACTFORM_AUTOINFO') != 'false' && Configuration::get('CONTACTFORM_AUTOINFO') != 'true')
					Configuration::updateValue('CONTACTFORM_AUTOINFO', 'true');

			if (Tools::isSubmit('exportalldata'))
			{
			$format = Tools::getValue('format');
			$isoname = Tools::getValue('isoname');

			$separator = Tools::getValue('separator');
			if ($separator == '')
				$separator = ';';
			$date1 = Tools::getValue('dateA');
			$date2 = Tools::getValue('dateB');
			$formid = (int)Tools::getValue('formid', 0);
			if ($formid)
			{
				switch ($format)
				{
					case 'csv':
						DatatExport::exportcsvform($date1, $date2, $isoname, $separator, $formid);
					break;
					case 'xls':
						DatatExport::exportxlsform($date1, $date2, $isoname, $formid);
					break;
					case 'xlsx':
						DatatExport::exportxlsxform($date1, $date2, $isoname, $formid);
					break;
					default:
						DatatExport::exportcsvform($date1, $date2, $isoname, $separator, $formid);
					break;
				}
			}
			else
			{
				switch ($format)
				{
					case 'csv':
						DatatExport::exportcsv($date1, $date2, $isoname, $separator);
					break;
					case 'xls':
						DatatExport::exportxls($date1, $date2, $isoname);
					break;
					case 'xlsx':
						DatatExport::exportxlsx($date1, $date2, $isoname);
					break;
					default:
						DatatExport::exportcsv($date1, $date2, $isoname, $separator);
					break;
				}
			}
			}
		//----------------- SUBMIT NEW, EDIT FORM
		if (Tools::isSubmit('submitform'))
		{
			//SAME
$defaultlayout = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>'.$this->l('Message from.').' {shop_name}</title>
</head>
<body>
	<table style="font-family:Verdana,sans-serif; font-size:11px; color:#374953; width: 550px;">
		<tr>
			<td align="left">
				<a href="{shop_url}" title="{shop_name}"><img alt="{shop_name}" src="{shop_logo}" style="border:none;" ></a>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="left" style="background-color:#DB3484; color:#FFF; font-size: 12px; font-weight:bold;
			padding: 0.5em 1em;">Message from your shop {shop_name}</td>
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
				<a href="{shop_url}" style="color:#DB3484; font-weight:bold; text-decoration:none;">
				{shop_name}</a> powered with <a href="http://www.mydomain.com/" style="text-decoration:none;
				color:#374953;">Contactform</a>
			</td>
		</tr>
	</table>
</body>
</html>';

			$fid				=	(int)Tools::getValue('fid');
			$formname			=	addslashes(Tools::getValue('formname', ''));
			$email				=	Tools::getValue('email', '');
			$layout				=	addslashes(Tools::getValue('layout', $defaultlayout));
			$aliasdfs = Tools::getValue('alias_'.$default_language, false);
			$frmttdef = Tools::getValue('formtitle_'.$default_language, false);
			$tonamdef = Tools::getValue('toname_'.$default_language, false);
			$subjedef = Tools::getValue('subject_'.$default_language, false);

			//Check all value with PHP (Used if jquery is disabled)
			if ($formname == '' || empty($formname))
				$this->errors[] = $this->l('Form name is required');
			if (!$aliasdfs)
				$this->errors[] = $this->l('You must specify alias for the default language');
			if (!$frmttdef)
				$this->errors[] = $this->l('You must specify a form title for the default language');

			if (!$tonamdef)
				$this->errors[] = $this->l('You must specify the name of mail expeditor for the default language');
			if (!$subjedef)
				$this->errors[] = $this->l('You must specify the mail subject for the default language');

			if ($email == '' || empty($email))
				$this->errors[] = $this->l('Email address is required');
			//Verify email
			$allmail = explode(';', $email);
			$ctallm = count($allmail);
			for ($i = 0; $i < $ctallm; $i++)
			{
				if (!CFtools::verifMail($allmail[$i]))
					$this->errors[] = $this->l('Invalid mail address').': '.$allmail[$i];
			}
			$atuodef = Tools::getValue('automailresponse_'.$default_language, false);
			if ($layout == '' || empty($layout))
				$this->errors[] = $this->l('Email layout is required');
			if (!$atuodef)
				$this->errors[] = $this->l('You must specify the mail notification for the default language');

			if (isset($this->errors) && count($this->errors))
				return $this->displayError(implode('<br />', $this->errors)).CFtools::editform($this->_path, $this->context->language->id);
			else
			{

				if ($fid == 0)
				{ //New form
					$check = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform` WHERE formname="'.(string)$formname.'"');
					if (count($check) > 0)
							return $this->displayError($this->l('Form already exist')).CFtools::editform($this->_path, $this->context->language->id);
					else
					{
						CFtools::updateForm(0);
						return $this->displayConfirmation($this->l('New form created')).CFtools::showformList($this->_path, $this->context->language->id);
					}
				}
				else
				{ //Update
					CFtools::updateForm(1);
					return $this->displayConfirmation($this->l('Form updated')).CFtools::showformList($this->_path, $this->context->language->id);
				}

			}//End else

		}
if (Tools::isSubmit('submitfield'))
{

			//SAME
			$fid				=	(int)Tools::getValue('fid');
			$fdid				=	(int)Tools::getValue('fdid');

			$fields_id			=	Tools::getValue('fields_id', '');
			$fields_name		=	Tools::getValue('fields_name', '');
			$order				=	(int)Tools::getValue('order', 0);
			if ($fields_id == '' || empty($fields_id))
				$this->errors[] = $this->l('Form id is required');

			if ($fields_name == '' || empty($fields_name))
				$this->errors[] = $this->l('Form id is required');

			if (isset($this->errors) && count($this->errors))
				return $this->displayError(implode('<br />', $this->errors)).CFtools::addfield($this->_path, $fid, $this->context->language->id);
			else
			{

				if ($fdid == 0)
				{
					$check = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_item` WHERE `fields_id`="'.(string)$fields_id.'"');
					if (count($check) > 0)
							return $this->displayError($this->l('Field id already exist')).CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
					elseif ($fields_id == 'email')
						return $this->displayError($this->l('Don\'t use the id "email"')).CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
					else
					{
						CFtools::updateField(0);
						return $this->displayConfirmation($this->l('New field created')).CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
					}
				}
				else
				{
					CFtools::updateField(1);
					return $this->displayConfirmation($this->l('Field updated')).CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
				}

			}

}
		if (Tools::isSubmit('deleteselectfld'))
		{
		$fid = (int)Tools::getValue('fid');
		$actlink = Tools::getValue('actlink');
		if (!empty($actlink))
		{
				$keys = array_keys($actlink);
				$ctke = count($keys);
				for ($i = 0; $i < $ctke; $i++)
					CFtools::delField($fid, (int)$keys[$i]);
		}
		}
		if (Tools::isSubmit('deleteselectfrm'))
		{
			$actlink = Tools::getValue('actlink');

			if (!empty($actlink))
			{
				$keys = array_keys($actlink);
				$ctkey = count($keys);
				for ($i = 0; $i < $ctkey; $i++)
					CFtools::delForm((int)$keys[$i]);

			}
		}

		if (Tools::isSubmit('deleteselectdata'))
		{
			$actlink = Tools::getValue('actlink');

			if (!empty($actlink))
			{
				$keys = array_keys($actlink);
				$ctek = count($keys);
				for ($i = 0; $i < $ctek; $i++)
					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_data` WHERE `data_id`='.(int)$keys[$i]);
			}
		}
	if (Tools::isSubmit('deleteselectrel'))
	{
			$actlink = Tools::getValue('actlink');
			if (!empty($actlink))
			{
				$keys = array_keys($actlink);
				$ctekl = count($keys);
				for ($i = 0; $i < $ctekl; $i++)
				{
					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_relation` WHERE `id`='.(int)$keys[$i]);
					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_relation_item` WHERE `rid`='.(int)$keys[$i]);
					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'contactform_relation_item_lang` WHERE `rid`='.(int)$keys[$i]);
				}

			}
			return $this->displayConfirmation($this->l('Relation deleted successfully')).CFtools::formRelation($this->_path, $this->context->language->id);
	}
	if (Tools::isSubmit('submitorder'))
	{
	$neworder = Tools::getValue('neworder');
	$neworder = trim($neworder);
	$allorders = explode(' ', $neworder);
		$compteur = 0;
		foreach ($allorders as $myorder)
		{
			$forder = $compteur + 1;
			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'contactform_item` SET `order` = '.(int)$forder.' WHERE `fdid` ='.(int)$myorder);
			$compteur++;
		}
	return $this->displayConfirmation($this->l('Order changed successfully')).CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
	}

		if (Tools::isSubmit('upFieldorder'))
		{
				$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
				$fid			 = (int)Tools::getValue('fid');
				$languages = Language::getLanguages();
				$listforms = Db::getInstance()->ExecuteS('SELECT `fdid` FROM `'._DB_PREFIX_.'contactform_item` WHERE `fid`='.(int)$fid);
				foreach ($listforms as $listform)
				{
					${'order_'.$listform['fdid']}	=	(int)Tools::getValue('order_'.$listform['fdid']);
					Db::getInstance()->execute(' UPDATE `'._DB_PREFIX_.'contactform_item` SET `order`= '.
						(int)${'order_'.$listform['fdid']}.' WHERE `fdid`='.(int)$listform['fdid'].' ');
				}
		}
	if (Tools::isSubmit('subSavesql'))
	{
			$file_name = dirname(__FILE__).'/library/sql/contactform.sql.txt';

			$sqldump		=	Tools::getValue('sqldump');
			$fp = fopen (dirname(__FILE__).'/library/sql/contactform.sql.txt', 'w');

			fputs ($fp, $sqldump);
			fclose ($fp);

			//if file is writting
			header('Content-disposition: attachment; filename=contactform.sql.txt');
			header('Content-Type: application/force-download');
			header('Content-Transfer-Encoding: binary');
			header('Pragma: no-cache');
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
			ob_clean();
			flush();
			readfile($file_name);
			exit;
	}

		if (Tools::isSubmit('subimportsql'))
		{
		$tmp_file = $_FILES['txtimportsql']['tmp_name'];
		$type_file = $_FILES['txtimportsql']['type'];
		if (!is_uploaded_file($tmp_file))
			return $this->displayError($this->l('File not found')).CFutils::importForm($this->_path, $this->context->language->id);
		if ($type_file != 'text/plain')
			return $this->displayError($this->l('Invalid file format')).CFutils::importForm($this->_path, $this->context->language->id);

			CFutils::truncateAllTable();

			$fp = fopen ($tmp_file, 'r');

			$contenu_du_fichier = '';
			while (!feof($fp))
			$contenu_du_fichier .= fgets ($fp, 1255);

			$allsql = explode('-- [CF_tag]', $contenu_du_fichier);
			$allsql = array_values(array_filter($allsql));

			$ctm = count($allsql) - 1;
				for ($i = 0; $i < $ctm; $i++)
				{
					if ($allsql[$i] != '')
						Db::getInstance()->Execute($allsql[$i]);
				}
				fclose($fp);
			return $this->displayConfirmation($this->l('Data import completed successfully')).CFutils::importForm($this->_path, $this->context->language->id);
		}
if (Tools::isSubmit('submitsettings'))
{
	$cfgrequired	=	Tools::getValue('cfgrequired');
	$cfgupload		=	Tools::getValue('cfgupload');
	$cfstyle		=	(int)Tools::getValue('cfstyle');
	$cfgfwidth		=	(int)Tools::getValue('cfgfwidth');
	$cfgradio		=	(int)Tools::getValue('cfgradio');
	$cfgckbox		=	(int)Tools::getValue('cfgckbox');

	$cfmultiform    =	(int)Tools::getValue('multiform');

	if (Tools::getValue('captchapubkey') == '')
		return $this->displayError($this->l('Invalid captcha public key ')).CFutils::settings($this->_path, $this->context->language->id);
	else if (Tools::getValue('captchaprivkey') == '')
		return $this->displayError($this->l('Invalid captcha private key')).CFutils::settings($this->_path, $this->context->language->id);

	Configuration::updateValue('CONTACTFORM_ACTIVESEO', (int)Tools::getValue('activeseo'));
	Configuration::updateValue('CONTACTFORM_REQUIRED', $cfgrequired);
	Configuration::updateValue('CONTACTFORM_UPFORMAT', $cfgupload);
	Configuration::updateValue('CONTACTFORM_CFGCKBOX', $cfgckbox);
	Configuration::updateValue('CONTACTFORM_CFGRADIO', $cfgradio);
	Configuration::updateValue('CONTACTFORM_WIDTH', $cfgfwidth);
	Configuration::updateValue('CONTACTFORM_FORM', $cfstyle);
	Configuration::updateValue('CONTACTFORM_AUTH', (int)Tools::getValue('cfauth'));
	Configuration::updateValue('CONTACTFORM_SHOWCAR', (int)Tools::getValue('showcar'));
	Configuration::updateValue('CONTACTFORM_CAPTCHATHEME', Tools::getValue('captchatheme'));
	Configuration::updateValue('CONTACTFORM_CAPTCHAPUBKEY', Tools::getValue('captchapubkey'));
	Configuration::updateValue('CONTACTFORM_CAPTCHAPRIVKEY', Tools::getValue('captchaprivkey'));
	Configuration::updateValue('CONTACTFORM_NOTIF', (int)Tools::getValue('notif'));
	Configuration::updateValue('CONTACTFORM_CCTPL', Tools::getValue('cctpl'));
	Configuration::updateValue('CONTACTFORM_MAILTYPE', Tools::getValue('mailtype'));
	Configuration::updateValue('CONTACTFORM_DEFAULTFORM', Tools::getValue('defaultform'));
	Configuration::updateValue('CONTACTFORM_MULTIFORM', $cfmultiform);

	return $this->displayConfirmation($this->l('Settings were updated successfully')).CFutils::settings($this->_path, $this->context->language->id);

}
if (Tools::isSubmit('submitphpfile'))
{
	$admindir = explode('/', _PS_ADMIN_DIR_);
	$ctn = count($admindir);
	$adminrep = __PS_BASE_URI__.$admindir[$ctn - 1].'/';
	$ext = explode('.', Tools::getValue('phpfile'));
	$phpext = $ext[count($ext) - 1];
	if ($phpext != 'php')
		echo $this->displayError($this->l('Your file name must have the extension .php (Exemple : contact.php)'));
	elseif ($phpext == 'php')
	{
		Configuration::updateValue('CONTACTFORM_FILENAME', Tools::getValue('phpfile'));
		Tools::redirectAdmin('index.php?controller='.Tools::getValue('controller').'&tab='.Tools::getValue('tab').'&configure=contactform&token='.
			Tools::getValue('token').'&task=activateForm2&phpfile='.Tools::getValue('phpfile'), $adminrep);

	}
}

	if (Tools::isSubmit('mailsubmit'))
	{
		$mailadress  = Tools::getValue('mailadress');
		$mailsubject = Tools::getValue('mailsubject');
		$mailmessage = Tools::getValue('mailmessage');
		$mailsender = Tools::getValue('mailsender');
		$asc = Tools::getValue('asc', 'ASC');
		$orderby = Tools::getValue('orderby', 'data_id');
		$start = (int)Tools::getValue('start', 0);
		$pagelimit = (int)Tools::getValue('pagelimit', 10);
		$syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
		if ($mailadress == '' || $mailsubject == '' || $mailmessage == '' || $mailsender == '')
			return $this->displayError($this->l('Please fulfill all fields')).
			CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);

		elseif (!preg_match($syntaxe, $mailadress))
			return $this->displayError($this->l('Invalid email address')).
			CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);

		else
		{
			$template_vars = array('{mailmessage}' => $mailmessage);
			$mail_dir = _PS_MODULE_DIR_.'contactform/mails/'; //Directory with message templates
			$bcc = array();
			$m1 = Mail::Send($this->context->language->id,
							'cf_bo_mail',
							$mailsubject,
							$template_vars,
							$mailadress,
							null,
							$mailsender,
							null,
							null,
							null,
							$mail_dir,
							false,
							$this->context->shop->id,
							$bcc,
							0);

			// On envoi l’email
				if ($m1)
					return $this->displayConfirmation($this->l('Mail sent')).
					CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);
				else
					return $this->displayError($this->l('Failed sending email')).
					CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);
		}
	}
if (Tools::isSubmit('subeditcss'))
{
		$newcss  = Tools::getValue('newcss');
		$mytoken = Tools::getValue('token');

	if (Configuration::get('CONTACTFORM_FORM') == 0)
		$fp = fopen (dirname(__FILE__).'/views/css/front/basic.css', 'r+');
	else
	{
	if (Configuration::get('CONTACTFORM_STYLE') == 1)

			$fp = fopen (dirname(__FILE__).'/views/css/front/advance.css', 'r+');

		else
			$fp = fopen (dirname(__FILE__).'/views/css/front/template.css', 'r+');
	}

		fputs ($fp, $newcss);
		fclose ($fp);
		Tools::redirectAdmin('index.php?tab='.Tools::getValue('tab').'&configure=contactform&token='.$mytoken.'&task=editcss');
}

if (Tools::isSubmit('uphtaccess'))
{
	$filename = _PS_ROOT_DIR_.'/.htaccess';
	$content = '# Contactform\nRewriteRule ^([a-z]{2})/contact/([0-9]+)\-[a-zA-Z0-9-]* '.
	__PS_BASE_URI__.Configuration::get('CONTACTFORM_FILENAME').'?isolang=$1&fid=$2 [QSA,L]\nRewriteRule ^contact/([0-9]+)\-([a-zA-Z0-9-]*) '.
	__PS_BASE_URI__.Configuration::get('CONTACTFORM_FILENAME').'?fid=$1 [QSA,L]\n# Contactform\n';

	$file_data = Tools::file_get_contents($filename);
	if (is_writable($filename))
	{
		if (!$fp = fopen($filename, 'w'))
			return $this->displayError($this->l('Cannot open .htaccess file')).CFutils::settings($this->_path, $this->context->language->id);
		fseek($fp, 0);
		fwrite($fp, $content);
		fwrite($fp, $file_data);
		fclose($fp);

		return $this->displayConfirmation($this->l('.htaccess file updated successfully')).CFutils::settings($this->_path, $this->context->language->id);
	}
	else
		return $this->displayError($this->l('The file is not available. Make sure you have the right to write to the file'))
		.CFutils::settings($this->_path, $this->context->language->id);
}
if (Tools::isSubmit('saverelation'))
{
	$items = Tools::getValue('items');
	$languages = Language::getLanguages();
	$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
	DB::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_relation` as cfr WHERE cfr.default = '.(int)Tools::getValue('defaultform'));
	$numdef = DB::getInstance()->NumRows();
	if ($numdef)
		return $this->displayError($this->l('The default form that you have chosen is already being used as default in another relation.').
		' '.$this->l('Thank you to choose another.')).CFtools::createrelation($this->_path, $this->context->language->id);
	if (!$items)
		return $this->displayError($this->l('Please select form for the relation.')).CFtools::createrelation($this->_path);
	else
	{
		$defaulttitle = Tools::getValue('reltitle_'.$default_language);

		if (empty($defaulttitle))
			return $this->displayError($this->l('You must specify a relation title for the default language.')).CFtools::createrelation($this->_path);
		$itms = explode(',', $items);
		$etatrel = (int)Tools::getValue('etatrel');
		$typeaff = (int)Tools::getValue('typeaff');
		$defaultform = (int)Tools::getValue('defaultform');

		array_unshift($itms, (string)$defaultform);

		DB::getInstance()->insert('contactform_relation', array(
																	'id' => null,
																	'etat' => $etatrel,
																	'type' => $typeaff,
																	'default' =>$defaultform,
																	));
		$last_id = DB::getInstance()->Insert_ID();

		$order = 1;
		foreach ($itms as $itm)
		{
			DB::getInstance()->insert('contactform_relation_item', array(
																	'rid' => (int)$last_id,
																	'fid' => (int)$itm,
																	'order' => (int)$order,
																));
			foreach ($languages as $language)
			{
				DB::getInstance()->insert('contactform_relation_item_lang', array(
																			'rid' =>(int)$last_id,
																			'fid' =>(int)$itm,
																			'id_lang'=>(int)$language['id_lang'],
																			'txtsuppl'=>'#',
																			));
				DB::getInstance()->insert('contactform_relation_lang', array(
																'rid' => (int)$last_id,
																'id_lang'=>(int)$language['id_lang'],
																'title' => Tools::getValue('reltitle_'.(int)$language['id_lang']),
															));
			}

			$order++;
		}

		return $this->displayConfirmation($this->l('Relation was created successfully.')).CFtools::formRelation($this->_path, $this->context->language->id);
	}
}
if (Tools::isSubmit('updaterelation'))
{
	$items = Tools::getValue('items');
	$languages = Language::getLanguages();
	$rid = (int)Tools::getValue('rid');
	$defaultfrm = (int)Tools::getValue('dftlform');
	DB::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'contactform_relation` as cfr WHERE cfr.default = '.
								(int)$defaultfrm.' AND cfr.id!='.(int)$rid);
	$numdef = DB::getInstance()->NumRows();
	if ($numdef)
		return $this->displayError($this->l('The default form that you have chosen is already being used as default in another relation.').
		' '.$this->l('Thank you to choose another.')).CFtools::formRelation($this->_path, $this->context->language->id);
	if (!$items)
		return $this->displayError($this->l('Please select form for the relation.')).CFtools::createrelation($this->_path);
	else
	{
		$olditems = DB::getInstance()->executeS('SELECT fid FROM `'._DB_PREFIX_.'contactform_relation_item` WHERE rid='.(int)$rid);

		$itms = explode(',', $items);
		$itms = array_filter($itms);
		array_unshift($itms, (string)$defaultfrm);

		$etatrel = (int)Tools::getValue('etatrel');
		$typeaff = (int)Tools::getValue('typeaff');
		DB::getInstance()->update('contactform_relation', array(
																	'etat' => (int)$etatrel,
																	'type' => (int)$typeaff,
																	'default'=> (int)$defaultfrm,
																	), 'id='.(int)$rid);
		$oldits = array();
		foreach ($olditems as $olditem)
			$oldits[] = $olditem['fid'];
		foreach ($oldits as $oldit)
		{
			if (!in_array((int)$oldit, $itms))
			{
				DB::getInstance()->delete('contactform_relation_item', 'rid='.(int)$rid.' AND fid='.(int)$oldit);
				DB::getInstance()->delete('contactform_relation_item_lang', 'rid='.(int)$rid.' AND fid='.(int)$oldit);
			}
		}
		$lastorder = Db::getInstance()->getValue('SELECT `order` FROM `'._DB_PREFIX_.'contactform_relation_item` WHERE rid='.
												(int)$rid.' ORDER BY `order` DESC');
		$neworder = (int)$lastorder + 1;
		foreach ($itms as $itm)
		{
			if (!in_array((int)$itm, $oldits))
			{
				DB::getInstance()->insert('contactform_relation_item', array(
																	'rid' => (int)$rid,
																	'fid' => (int)$itm,
																	'order' => (int)$neworder,
																));
				foreach ($languages as $language)
				{
					DB::getInstance()->insert('contactform_relation_item_lang', array(
																				'rid' =>(int)$rid,
																				'fid' =>(int)$itm,
																				'id_lang'=>(int)$language['id_lang'],
																				'txtsuppl'=>'#',));
				}
				$neworder++;
			}
		}
		foreach ($languages as $language)
		{
			$testexist = Db::getInstance()->getValue('SELECT rid FROM `'._DB_PREFIX_.'contactform_relation_lang` WHERE rid='.(int)$rid.
													' AND id_lang='.(int)$language['id_lang']);
			if ($testexist)
			{
				DB::getInstance()->update('contactform_relation_lang', array('title'=>addslashes(
			Tools::getValue('reltitle_'.(int)$language['id_lang'])),),
			'rid='.(int)$rid.' AND id_lang='.(int)$language['id_lang']);
			}
			else
			{
				DB::getInstance()->insert('contactform_relation_lang', array(
																			'title' => addslashes(
																			Tools::getValue('reltitle_'.$language['id_lang'])),
																			'rid' => $rid,
																			'id_lang' => $language['id_lang'],)
										);
			}
		}

		return $this->displayConfirmation($this->l('Relation was created successfully.')).CFtools::formRelation($this->_path, $this->context->language->id);
	}
}
if (Tools::isSubmit('saveelmrel'))
{
	$languages = Language::getLanguages();
	$fid = (int)Tools::getValue('fid');
	$rid = (int)Tools::getValue('rid');
	$this->context->controller->addJS($this->_path.'views/js/jquery.tablednd.0.7.min.js');

	foreach ($languages as $language)
	{
		$testexist = Db::getInstance()->getValue('SELECT rid FROM `'._DB_PREFIX_.'contactform_relation_item_lang` WHERE rid='.(int)$rid.
												' AND fid='.(int)$fid.' AND id_lang='.(int)$language['id_lang']);
				if ($testexist)
				{
					DB::getInstance()->update('contactform_relation_item_lang', array(
																			'txtsuppl'=>addslashes(Tools::getValue('txtsuppl_'.(int)$language['id_lang'])),
																		), 'rid='.(int)$rid.' AND fid='.(int)$fid.' AND id_lang='.(int)$language['id_lang']);
				}
				else
				{
					DB::getInstance()->insert('contactform_relation_item_lang', array(
																			'txtsuppl'=>addslashes(Tools::getValue('txtsuppl_'.(int)$language['id_lang'])),
																			'rid' => (int)$rid,
																			'fid' => (int)$fid,
																			'id_lang' => (int)$language['id_lang'],
																		));
				}
	}
	return $this->displayConfirmation($this->l('Element was saved successfully.')).
	CFtools::viewRelElement($this->_path, $rid, $this->context->language->id);
}
if (Tools::isSubmit('cfsaveorder'))
{
	$rid = (int)Tools::getValue('rid');
	$cforders = Tools::getValue('cforder');
	$cforderers = explode(',', $cforders);
	$cforderers = array_filter($cforderers);
	$i = 1;
	foreach ($cforderers as $cforderer)
	{
		DB::getInstance()->update('contactform_relation_item', array('order'=>(int)$i,), 'rid='.(int)$rid.' AND fid='.(int)$cforderer);
		$i++;
	}
	$this->context->controller->addJS($this->_path.'views/js/jquery.tablednd.0.7.min.js');
	return $this->displayConfirmation($this->l('Order was saved successfully.')).
	CFtools::viewRelElement($this->_path, $rid, $this->context->language->id);

}
		switch ($task)
		{
			case 'uphtaccess':
				$filename = '';
			break;
			case 'activateForm':
				return CFutils::activateForm($this->_path, $this->context->language->id);
			case 'activateForm2':
				return CFutils::activateForm2($this->_path, Tools::getValue('phpfile'), $this->context->language->id);
			case 'disableForm':
				return CFutils::disableForm();
			case 'showformList':
				return CFtools::showformList($this->_path, $this->context->language->id);
			case 'showformList2':
				if ((int)Tools::getValue('statut') == 0)
					return $this->displayConfirmation($this->l('Form was deleted successfully')).
					CFtools::showformList($this->_path, $this->context->language->id);
				if ((int)Tools::getValue('statut') == 1)
					return $this->displayError($this->l('Error was occured where deleting the form')).
					CFtools::showformList($this->_path, $this->context->language->id);
				if ((int)Tools::getValue('statut') == 3)
					return $this->displayConfirmation($this->l('Form cloned successfully')).
					CFtools::showformList($this->_path, $this->context->language->id);

			case 'showfieldList':
				return CFtools::showfieldList($this->_path, $fid, $this->context->language->id);

			case 'showfieldList2':
				if ((int)Tools::getValue('statut') == 1)
					return $this->displayConfirmation($this->l('Field was deleted successfully')).
					CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
				if ((int)Tools::getValue('statut') == 0)
					return $this->displayError($this->l('Error was occured where deleting the field')).
					CFtools::showfieldList($this->_path, $fid, $this->context->language->id);
			case 'editform':
				return CFtools::editform($this->_path, $this->context->language->id);
			case 'addfield':
				return CFtools::addfield($this->_path, (int)Tools::getValue('fid'), $this->context->language->id);
			case 'cloneform':
				return CFtools::cloneform((int)Tools::getValue('fid'));

			case 'infostatus':
				if (Configuration::get('CONTACTFORM_AUTOINFO') == 'false')
					Configuration::updateValue('CONTACTFORM_AUTOINFO', 'true');
				else
					Configuration::updateValue('CONTACTFORM_AUTOINFO', 'false');
				return CFtools::frontpage($this->_path, $this->name, $this->version);
			case 'delform':
				return CFtools::delForm($fid);
			case 'delfield':
				$fid = (int)Tools::getValue('fid');
				$fdid = (int)Tools::getValue('fdid');
				return CFtools::delField($fid, $fdid);

			case 'changestatus':
				$status = (int)Tools::getValue('status');
				$fdid = (int)Tools::getValue('fdid');
				if ($status == 1)
					$updatestatus = 0;
				else
					$updatestatus = 1;
					CFtools::changestatus($fdid, $updatestatus);
			break;

			case 'exportForm':
				return CFutils::exportForm($this->_path, $this->context->language->id);

			case 'restoreForm':
				return CFutils::importForm($this->_path, $this->context->language->id);

			case 'saveSql':
				$file_name = dirname(__FILE__).'/library/sql/contactform.sql.txt';
				CFutils::saveAs($file_name, 'contactform.sql.txt');
			break;
			case 'settings':
				return CFutils::settings($this->_path, $this->context->language->id);

			case 'btnActivecf':
				$mode = Tools::getValue('mode');
				CFutils::btnActivecf($mode);
				return $this->displayConfirmation($this->l('Changes have been made on the activation button')).
				CFutils::settings($this->_path, $this->context->language->id);

			case 'btnDeactivecf':
				$mode = Tools::getValue('mode');
				CFutils::btnDeactivecf($mode);
				return $this->displayConfirmation($this->l('Changes have been made on the activation button')).
				CFutils::settings($this->_path, $this->context->language->id);
			case 'saveHelp':
				$file_name = dirname(__FILE__).'/help/help_'.CFtools::getIsocode($this->context->language->id).'.pdf';
					if (!file_exists($file_name))
						$file_name = dirname(__FILE__).'/help/help_es.pdf';
				CFutils::saveAs($file_name, 'contacform_'.CFtools::getIsocode($this->context->language->id).'.pdf');
			break;
			case 'seedata':
				$asc = Tools::getValue('asc', 'ASC');
				$orderby = Tools::getValue('orderby', 'data_id');
				$start = (int)Tools::getValue('start', 0);
				$pagelimit = (int)Tools::getValue('pagelimit', 10);
				return CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);

			case 'deldata':
				$data_id = (int)Tools::getValue('data_id');
				$asc = Tools::getValue('asc', 'ASC');
				$orderby = Tools::getValue('orderby', 'data_id');
				$start = (int)Tools::getValue('start', 0);
				$pagelimit = (int)Tools::getValue('pagelimit', 10);
				Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'contactform_data WHERE data_id='.(int)$data_id);
				return $this->displayConfirmation($this->l('Data deleted successfully')).
				CFutils::seedata($this->_path, $asc, $orderby, $pagelimit, $start, $this->context->language->id);
			case 'seedatadetails':
				$data_id = (int)Tools::getValue('data_id');
				return CFutils::seedatadetails($this->_path, $data_id, $this->context->language->id);
			case 'seo':
				return CFutils::seo($this->_path);
			case 'addsample':
				return CFutils::addsample($this->_path, $this->context->language->id);
			case 'editcss':
				return CFutils::editcss($this->_path, $this->context->language->id);
			case 'importsample':
				$model = (int)Tools::getValue('model');
				return CFutils::importSample($model, $this->_path, $this->context->language->id);
			case 'exportxls':
				return DatatExport::exportxls();
			case 'formrelation':
				return CFtools::formRelation($this->_path, $this->context->language->id);
			case 'createrelation':
				return CFtools::createrelation($this->_path, $this->context->language->id);
			case 'relviewelm':
				$rid = (int)Tools::getValue('rid');
				$this->context->controller->addJS($this->_path.'views/js/jquery.tablednd.0.7.min.js');
				return CFtools::viewRelElement($this->_path, $rid, $this->context->language->id);
			case 'editrelelm':
				$rid = (int)Tools::getValue('rid');
				$fid = (int)Tools::getValue('fid');
				return CFtools::editRelElement($this->_path, $rid, $fid, $this->context->language->id);

			case 'delemtrel':
				$rid = (int)Tools::getValue('rid');
				$fid = (int)Tools::getValue('fid');
				DB::getInstance()->delete('contactform_relation_item', 'rid='.(int)$rid.' AND fid='.(int)$fid);
				DB::getInstance()->delete('contactform_relation_item_lang', 'rid='.(int)$rid.' AND fid='.(int)$fid);
				return $this->displayConfirmation($this->l('Element deleted successfully')).
				CFtools::viewRelElement($this->_path, $rid, $this->context->language->id);

			case 'delrelation':
				$rid = (int)Tools::getValue('rid');
				DB::getInstance()->delete('contactform_relation', 'id='.(int)$rid);
				DB::getInstance()->delete('contactform_relation_item', 'rid='.(int)$rid);
				DB::getInstance()->delete('contactform_relation_item_lang', 'rid='.(int)$rid);
				return $this->displayConfirmation($this->l('Relation deleted successfully')).
				CFtools::formRelation($this->_path, $this->context->language->id);

			case 'editrel':
				$rid = (int)Tools::getValue('rid');
				return CFtools::editRelation($this->_path, $rid, $this->context->language->id);

			default:
				return CFtools::frontpage($this->_path, $this->name, $this->version);

		}
	}
	public function hookHeader()
	{
		$p_name = $this->context->smarty->tpl_vars['page_name']->value;
		$contacformactive = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacformactive);
		$cff = ($contactform[0] == 'contact-form' ) ? 'contact' : $contactform[0];
		if ($p_name == $cff)
		{
			if (Configuration::get('CONTACTFORM_FORM') == 0)
			{
				$this->context->controller->addCSS(($this->_path).'views/css/front/basic.css', 'all');
				$this->context->controller->addJS(($this->_path).'views/js/jquery.validate.js', 'all');
			}
			else
				$this->context->controller->addCSS($this->_path.'views/css/front/advance.css', 'all');
			$this->context->controller->addJS($this->_path.'views/js/form/languages/jquery.validationEngine-'.$this->context->language->iso_code.'.js', 'all');
			$this->context->controller->addJS($this->_path.'views/js/form/jquery.validationEngine.js', 'all');
			$this->context->controller->addJS($this->_path.'views/js/jquery.datetimepicker.js');
			$this->context->controller->addCSS(array(
													($this->_path).'views/css/validationEngine.jquery.css' => 'all',
													($this->_path).'views/css/jquery.datetimepicker.css' => 'all'
													));
		}
	}
	public function hookDisplayLeftColumn()
	{
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;

		$formss = CFtools::getColumnForm(1, $id_lang, $id_shop);
		$contacformactive = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacformactive);
		if (!empty($formss))
		{
			$txt_field = $this->getHtmlUploadFieldLang();
			$this->context->smarty->assign(array(
				'forms' => $formss,
				'nofile' => $txt_field[1],
				'choosefile' => $txt_field[0],
				'contactform' => $contactform[0]
			));

			return $this->display(__FILE__, 'ContactForm_column.tpl');
		}
	}
	public function hookmodalHook()
	{
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;

		$formss = CFtools::getColumnForm(1, $id_lang, $id_shop);
		$contacformactive = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacformactive);
		if (!empty($formss))
		{
			$txt_field = $this->getHtmlUploadFieldLang();
			$this->context->smarty->assign(array(
					'forms' => $formss,
					'nofile' => $txt_field[1],
					'choosefile' => $txt_field[0],
					'contactform' => $contactform[0]
			));

			return $this->display(__FILE__, 'ContactForm_column.tpl');
		}
	}

	public function hookDisplayRightColumn()
	{
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;

		$formes = CFtools::getColumnForm(2, $id_lang, $id_shop);
		$contacformactive = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacformactive);
		if (!empty($formes))
		{
			$txt_field = $this->getHtmlUploadFieldLang();
			$this->context->smarty->assign(array(
				'forms' => $formes,
				'nofile' => $txt_field[1],
				'choosefile' => $txt_field[0],
				'contactform' => $contactform[0]
			));

			return $this->display(__FILE__, 'ContactForm_column.tpl');
		}
	}
	public function getHtmlUploadFieldLang()
	{
		$isolang = $this->context->language->iso_code;
		$txt_field = array();
		switch ($isolang)
		{
			case 'fr':
				$txt_field[0] = 'Parcourir';
				$txt_field[1] = 'Aucun fichier selectione';
			break;
			case 'en':
				$txt_field[0] = 'Choose file';
				$txt_field[1] = 'No field selected';
			break;
			case 'es':
				$txt_field[0] = 'Examinar';
				$txt_field[1] = 'No hay archivos seleccionados';
			break;
			case 'it':
				$txt_field[0] = 'viaggi';
				$txt_field[1] = 'Nessun file selezionato';
			break;
			case 'de':
				$txt_field[0] = 'Reise';
				$txt_field[1] = 'Keine Dateien ausgewählt';
			break;
			case 'pl':
				$txt_field[0] = 'podróz';
				$txt_field[1] = 'Brak Pliki Wybrane';
			break;
			default:
				$txt_field[0] = 'Choose file';
				$txt_field[1] = 'No field selected';
			break;
		}
		return $txt_field;
	}
}
?>