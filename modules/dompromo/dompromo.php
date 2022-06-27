<?php
/**
 *	domPromo : Module pour site sous PrestaShop.
 *	Gestion des Promotions, ventes flash, Destockage, Soldes et ventes à prix coutant.
 *
 *	Version	2.0.6
 * 	Pour Prestashop 1.5.x
 *
 *	Copyright Aideaunet.
 * 	Site de l'auteur : http://www.aideaunet.com
 *
 *	Les scripts PHP de ce module sont sous Copyright.
 *  La modification des scripts de ce module est strictement INTERDITE.
 *
 *  Seules les scripts TPL (scripts de thémes) et CSS (feuilles de style) sont autorisés à modification.
 *
 * 	Ce module est soumis à condition d'acquisition,
 * 	La distribution de ce module est STRICTEMENT INTERDITE.
 *  Le code source est la propriété de son auteur, toute modification est strictement interdite.
 *
 **/

if (!defined('_PS_VERSION_'))
	exit;

if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
	@date_default_timezone_set(@date_default_timezone_get());

class dompromo extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name    = 'dompromo';
		$this->tab = 'pricing_promotion';
		$this->version = '2.0.6';
		$this->author = 'Aideaunet';
		$this->siteauthor = 'www.aideaunet.com';
		$this->path    = $this->_path;
		$this->versionmini = '1.5';
		$this->effect  = Configuration::get('DOM_PROMO_EFFECT');
		$this->offset  = Configuration::get('DOM_PROMO_OFFSET');
		$this->transition = Configuration::get('DOM_PROMO_TRANSITION');
		$this->serial = 'Free';
		$this->multisite = Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE');
		$this->need_instance = 0;
		parent::__construct();
		$this->displayName = $this->l('Aideaunet : Sales and  Promotions');
		$this->description = $this->l('This module creates a Flash sales, coutant price, reduction of stocks, promotion and On sales .');
		$this->confirmUninstall = $this->l('Are you sure you want to delete all Sales and  Promotions?');
		Configuration::updateValue('PS_SPECIFIC_PRICE_FEATURE_ACTIVE',1);

		if (!file_exists(_PS_SMARTY_DIR_.'plugins/function.date_diff.php'))
			$this->warning = $this->l('insufficient access to directory, You must copy this file manually:').' '._MODULE_DIR_.'dompromo/plugins/function.date_diff.php';

		if ($this->multisite == '1') $this->warning = $this->l('Sorry, but this version of the module does not manage the multi.');
		if (_PS_VERSION_ < $this->versionmini) $this->warning = $this->l('Sorry, but this version is not domPromo compatible with your version of PrestaShop. Check out').' <a href="http://www.aideaunet.com" target="_news">http://www.aideaunet.com</a>';
	}

	public function install() {
		!$this->copyfile();

		if (!parent::install()
			OR !Configuration::updateValue('DOM_PROMO_EFFECT','1')
			OR !Configuration::updateValue('DOM_PROMO_OFFSET','0')
			OR !Configuration::updateValue('DOM_PROMO_TRANSITION','5')
			OR !Configuration::updateValue('DOM_PROMO_DESACTIVE_SOLDE','0')
			OR !Configuration::updateValue('DOM_PROMO_DESACTIVE_PROMO','0')
			OR !Configuration::updateValue('DOM_PROMO_DESACTIVE_VFTYPE1','0')
			OR !Configuration::updateValue('DOM_PROMO_DESACTIVE_VFTYPE2','0')
			OR !Configuration::updateValue('DOM_PROMO_DESACTIVE_VFTYPE3','0')
			OR !Configuration::updateValue('DOM_PROMO_FINVENTE_VFTYPE1','0')
			OR !Configuration::updateValue('DOM_PROMO_FINVENTE_VFTYPE2','0')
			OR !Configuration::updateValue('DOM_PROMO_FINVENTE_VFTYPE3','0')
			OR !Configuration::updateValue('DOM_PROMO_FINVENTE_PROMO','0')
			OR !Configuration::updateValue('DOM_PROMO_FINVENTE_SOLDE','0')
			OR !Configuration::updateValue('DOM_PROMO_IMG_VFTYPE1','1')
			OR !Configuration::updateValue('DOM_PROMO_IMG_VFTYPE2','1')
			OR !Configuration::updateValue('DOM_PROMO_IMG_VFTYPE3','1')
			OR !Configuration::updateValue('DOM_PROMO_TITRE_VFTYPE1','1')
			OR !Configuration::updateValue('DOM_PROMO_TITRE_VFTYPE2','1')
			OR !Configuration::updateValue('DOM_PROMO_TITRE_VFTYPE3','1')
			OR !Configuration::updateValue('DOM_PROMO_NOREFRESHAUTO','0')
			OR !$this->registerHook('rightColumn')
			OR !$this->registerHook('extraRight')
			OR !$this->registerHook('header')
			OR !$this->installDB())
			return false;

		/* Installation du sous menu */
		$TabParent = 'AdminPriceRule';
		$tab = new Tab();
		$tab->name[$this->context->language->id] = $this->l('DomPromo');
		$tab->class_name = 'AdminDomPromo';
		$tab->id_parent = Tab::getIdFromClassName($TabParent); //12; // $parent_tab->id;
		$tab->module = $this->name;
		$tab->add();
		return true;
	}

	public function uninstall() {
		$this->uninstalldompromo();
		Configuration::deleteByName('DOM_PROMO_SERIAL');
		if (!parent::uninstall()
			OR !Configuration::deleteByName('DOM_PROMO_DESACTIVE_SOLDE')
			OR !Configuration::deleteByName('DOM_PROMO_DESACTIVE_PROMO')
			OR !Configuration::deleteByName('DOM_PROMO_EFFECT')
			OR !Configuration::deleteByName('DOM_PROMO_OFFSET')
			OR !Configuration::deleteByName('DOM_PROMO_TRANSITION')
			OR !Configuration::deleteByName('DOM_PROMO_DESACTIVE_VFTYPE1')
			OR !Configuration::deleteByName('DOM_PROMO_DESACTIVE_VFTYPE2')
			OR !Configuration::deleteByName('DOM_PROMO_DESACTIVE_VFTYPE3')
			OR !Configuration::deleteByName('DOM_PROMO_FINVENTE_VFTYPE1')
			OR !Configuration::deleteByName('DOM_PROMO_FINVENTE_VFTYPE2')
			OR !Configuration::deleteByName('DOM_PROMO_FINVENTE_VFTYPE3')
			OR !Configuration::deleteByName('DOM_PROMO_FINVENTE_PROMO')
			OR !Configuration::deleteByName('DOM_PROMO_FINVENTE_SOLDE')
			OR !Configuration::deleteByName('DOM_PROMO_IMG_VFTYPE1')
			OR !Configuration::deleteByName('DOM_PROMO_IMG_VFTYPE2')
			OR !Configuration::deleteByName('DOM_PROMO_IMG_VFTYPE3')
			OR !Configuration::deleteByName('DOM_PROMO_TITRE_VFTYPE1')
			OR !Configuration::deleteByName('DOM_PROMO_TITRE_VFTYPE2')
			OR !Configuration::deleteByName('DOM_PROMO_TITRE_VFTYPE3')
			OR !Configuration::deleteByName('DOM_PROMO_NOREFRESHAUTO')
			OR !$this->uninstallDB())
			return false;

		// Uninstall Tabs
		$tab = new Tab((int)Tab::getIdFromClassName('AdminDomPromo'));
		$tab->delete();
		return true;
	}

	function installDB() {
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'dompromo`;');
		Db::getInstance()->Execute('
			CREATE TABLE `'._DB_PREFIX_.'dompromo` (
				`id` int(11) NOT NULL auto_increment,
				`id_venteflash` int(11) NOT NULL,
				`datedebut` DATETIME,
				`datefin` DATETIME,
				`vfreduction` int(11) NOT NULL,
				`oldreduction` int(11),
				`typesale` int(11),
				`id_config` int(11),
				`typeadd` int(11) NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE = MyISAM;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'dompromo_parameters`;');
		Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dompromo_parameters` (			
				`flashtexttimeColor` VARCHAR(6) NOT NULL default "4e4e4e", 
				`flashtimeColor` VARCHAR(6) NOT NULL default "DC143C",		  
				`blocktexttimeColor` VARCHAR(6) NOT NULL default "8B4513",
				`blocktimeColor` VARCHAR(6) NOT NULL default "DC143C",
				`blocktextColor` VARCHAR(6) NOT NULL default "000000",
				`blockpriceColor` VARCHAR(6) NOT NULL default "DC143C",		  
				`blockcommingtexttimeColor` VARCHAR(6) NOT NULL default "8B4513",
				`blockcommingtimeColor` VARCHAR(6) NOT NULL default "DC143C",
				`blockcommingtextColor` VARCHAR(6) NOT NULL default "000000",
				`blockcommingpriceColor` VARCHAR(6) NOT NULL default "DC143C",		  
				`commingtextstartColor` VARCHAR(6) NOT NULL default "4e4e4e", 
				`commingdateColor` VARCHAR(6) NOT NULL default "DC143C",
				`commingtextfinishColor` VARCHAR(6) NOT NULL default "4e4e4e",		  
				`producttypeColor` VARCHAR(6) NOT NULL default "8B4513",
				`producttexttimeColor` VARCHAR(6) NOT NULL default "008000",
				`producttimeColor` VARCHAR(6) NOT NULL default "DC143C"
				) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;');
		Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'dompromo_parameters` 
				(flashtexttimeColor, flashtimeColor,	blocktexttimeColor, blocktimeColor, blocktextColor,
				blockpriceColor, blockcommingtexttimeColor, blockcommingtimeColor, blockcommingtextColor,
				blockcommingpriceColor, commingtextstartColor, commingdateColor, commingtextfinishColor,		  
				producttypeColor, producttexttimeColor, producttimeColor) 
					VALUES ("4e4e4e","DC143C","8B4513","DC143C","000000","DC143C","8B4513","DC143C","000000","DC143C","4e4e4e","DC143C","4e4e4e","8B4513","008000","DC143C");');
		return true;
	}

	private function copyfile() {
		@copy(_PS_MODULE_DIR_.'dompromo/plugins/function.date_diff.php', _PS_SMARTY_DIR_.'plugins/function.date_diff.php');
		return true;
	}

	private function installModuleTab($tabClass, $tabName, $idTabParent) {
		@copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$tabClass.'.gif');
		$tab = new Tab();
		$tab->name = $tabName;
		$tab->class_name = $tabClass;
		$tab->module = $this->name;
		$tab->id_parent = $idTabParent;
		if(!$tab->save())
			return false;
		return true;
	}

	private function uninstallModuleTab($tabClass) {
		$idTab = Tab::getIdFromClassName($tabClass);
		if($idTab != 0) {
			$tab = new Tab($idTab);
			$tab->delete();
			@unlink( _PS_IMG_DIR."t/".$tabClass.".gif");
			return true;
		}
		return false;
	}

	function uninstallDB() {
		Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'dompromo`;');
		Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'dompromo_parameters`;');
		return true;
	}

	function hookRightColumn($params) {
		global $smarty, $cookie;
		$MDParameters = array();
		$MDParameters = $this->getParameters();
		$shop = (int)Context::getContext()->shop->id;
		if (Configuration::get('DOM_PROMO_NOREFRESHAUTO') == 1) $jscount = "countdown2.js";
		else $jscount = "countdown.js";

		$tailleimg = 'medium';
		if (_PS_VERSION_ >= '1.5.1') $tailleimg .= '_default';

		$smarty->assign(array(
			'csproducts' 	=> $this->commingSoonVF((int)($params['cookie']->id_lang)),
			'tailleimg' 	=> $tailleimg,
			'timer' 			=> '<script type="text/javascript" src="'.$this->_path.'js/'.$jscount.'"></script>',
			'shop' 				=> $shop,
			'slider' 			=> '<script type="text/javascript" src="'.$this->_path.'js/slider.js"></script>',
			'ps_version' 	=> _PS_VERSION_,
			'mediumSize' 	=> Image::getSize('medium'),
			'vfday' 			=> $this->l('d'),
			'vfhour' 			=> $this->l('h'),
			'vfminute' 		=> $this->l('m'),
			'vfsecond' 		=> $this->l('s'),
			'effect' 			=> $this->effect*1000,
			'transition' 	=> $this->transition*1000,
			'moduleinfo' 	=> $this->name.' - '.$this->version.' - '.$this->serial.' - '.$this->l('author:').' '.$this->author
		));

		if ($this->thereisVenteFlash() == FALSE AND $this->commingSVF() == TRUE) {
			$smarty->assign(array(
				'blockcommingtexttimeColor' => $MDParameters[0]['blockcommingtexttimeColor'],
				'blockcommingtimeColor' => $MDParameters[0]['blockcommingtimeColor'],
				'blockcommingtextColor' => $MDParameters[0]['blockcommingtextColor'],
				'blockcommingpriceColor' => $MDParameters[0]['blockcommingpriceColor']
			));
			return $this->display(__FILE__,'commingsoonblock.tpl');

		} else {
			$smarty->assign(array(
				'vfproducts'   => $this->getTypePricesDrop((int)($params['cookie']->id_lang)),
				'blocktexttimeColor' => $MDParameters[0]['blocktexttimeColor'],
				'blocktimeColor' => $MDParameters[0]['blocktimeColor'],
				'blocktextColor' => $MDParameters[0]['blocktextColor'],
				'blockpriceColor' => $MDParameters[0]['blockpriceColor']
			));
			return $this->display(__FILE__,'dompromo.tpl');
		}
	}

	function hookHeader($params) {
		if ($this->startVenteFlash()==true) return $this->display(__FILE__,'reload.tpl');
		if ($this->checkVF()==true) return $this->display(__FILE__,'reload.tpl');
		$this->arret_outstock();
		$this->initializventes();
		$this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css', 'all');
		$this->context->controller->addCSS(($this->_path).'css/dompromo.css', 'all');

		global $smarty, $cookie;
		$smarty->assign('vfproducts', $this->getTypePricesDrop((int)($params['cookie']->id_lang)));
		$smarty->assign('csproducts', $this->commingSoonVF((int)($params['cookie']->id_lang)));
	}

	function hookLeftColumn($params) {
		return $this->hookRightColumn($params);
	}

	function hookHome($params) {
		return $this->hookRightColumn($params);
	}

	function hookFooter($params) {
		$this->hookRightColumn($params);
	}

	// Hook ExtraRight.
	function hookExtraRight($params) {
		$product = new Product(intval($_GET['id_product']), true, intval($params['cookie']->id_lang));
		if ($this->checkVFProductId(intval($product->id)) == '0' AND $this->commingSoonVenteFlash(intval($product->id)) == FALSE) return ;

		$MDParameters = array();
		$MDParameters = $this->getParameters();
		if (Configuration::get('DOM_PROMO_NOREFRESHAUTO') == 1) $jscount = "countdown2.js";
		else $jscount = "countdown.js";

		global $smarty, $cookie;
		$smarty->assign(array(
			'timer'      => '<script type="text/javascript" src="'.$this->_path.'js/'.$jscount.'"></script>',
			'vfday'      => $this->l('d'),
			'vfhour'     => $this->l('h'),
			'vfminute'   => $this->l('m'),
			'vfsecond'   => $this->l('s'),
			'saletype'   => $this->gettypeval(intval($product->id)),
			'producttypeColor' => $MDParameters[0]['producttypeColor'],
			'producttexttimeColor' => $MDParameters[0]['producttexttimeColor'],
			'producttimeColor' => $MDParameters[0]['producttimeColor'],
			'moduleinfo' => $this->name.' - v'.$this->version.' - '.$this->serial.' - '.$this->l('author:').' '.$this->author.' - Site Web : '.$this->siteauthor
		));

		if ($this->commingSoonVenteFlash(intval($product->id)) == TRUE) {
			$smarty->assign(array(
				'datetime'   => date("r",strtotime($this->getCSTime(intval($product->id)))),
				'red'        => $this->getpourcentreduction(intval($product->id))
			));
			return $this->display(__FILE__,'csproduct.tpl');
		} else {
			$smarty->assign(array(
				'varpath' 			=> $this->_path,
				'imgvf' 		=> Configuration::get('DOM_PROMO_IMG_VFTYPE1'),
				'imgpc' 		=> Configuration::get('DOM_PROMO_IMG_VFTYPE2'),
				'imgds' 		=> Configuration::get('DOM_PROMO_IMG_VFTYPE3'),
				'titrevf' 		=> Configuration::get('DOM_PROMO_TITRE_VFTYPE1'),
				'titrepc' 		=> Configuration::get('DOM_PROMO_TITRE_VFTYPE2'),
				'titreds' 		=> Configuration::get('DOM_PROMO_TITRE_VFTYPE3'),
				'datetime'  => date("r",strtotime($this->getTime(intval($product->id))))
			));
			return $this->display(__FILE__,'product.tpl');
		}
	}

	// Arrêt de la vente lorsque le temps est terminé.
	function checkVF() {
		$retour = false;
		if(Configuration::get('DOM_PROMO_OFFSET')==0) Configuration::updateValue('DOM_PROMO_OFFSET','2');
		$offset = time() + Configuration::get('DOM_PROMO_OFFSET');
		$query = Db::getInstance()->ExecuteS('
			SELECT vf.`id_venteflash`,vf.`datefin`, vf.`typesale` 
			FROM `'._DB_PREFIX_.'dompromo` vf, `'._DB_PREFIX_.'specific_price` sp
			WHERE sp.`id_product` =  vf.`id_venteflash`
			AND (sp.`reduction` * 100) =  vf.`vfreduction`
			AND (sp.`id_shop`='.(int)($this->getIDMagasin()).' OR sp.`id_shop`=0)
			AND sp.`from_quantity`<=1');
		if ($query) {
			foreach ($query AS $enr) {
				if(strtotime($enr['datefin']) <= $offset) {
					$valactive = 1;
					if($enr['typesale'] == '1' && Configuration::get('DOM_PROMO_DESACTIVE_VFTYPE1') == '1') $valactive = 0;
					elseif($enr['typesale'] == '2' && Configuration::get('DOM_PROMO_DESACTIVE_VFTYPE2') == '1') $valactive = 0;
					elseif($enr['typesale'] == '3' && Configuration::get('DOM_PROMO_DESACTIVE_VFTYPE3') == '1') $valactive = 0;

					$result = Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'product` SET active='.$valactive.', date_upd=NOW() WHERE id_product='.$enr['id_venteflash']);
					$result = Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'product_shop` SET active='.$valactive.', date_upd=NOW() WHERE id_product='.$enr['id_venteflash'].' and `id_shop`='.(int)($this->getIDMagasin()));
					$delete = Db::getInstance()->Execute('
					DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` = '.$enr['id_venteflash'].' AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
					$retour = true;
				}
			}
		}
		return $retour;
	}

	// Arrêt de la vente en cours lorsque le stock est à zéro.
	private function arret_outstock() {
		// Arrêt des ventes flash, Prix coutant et Déstockage.
		$query = Db::getInstance()->ExecuteS('
    		SELECT vf.`id_venteflash`,vf.`datefin`, vf.`typesale`, p.`quantity` 
        FROM `'._DB_PREFIX_.'dompromo` vf, `'._DB_PREFIX_.'specific_price` sp, `'._DB_PREFIX_.'product` p 
        WHERE vf.`id_venteflash` = sp.`id_product` 
        AND vf.`id_venteflash` = p.`id_product`
        AND (sp.`reduction` * 100) =  vf.`vfreduction` 
        AND vf.`datedebut` <= "'.(date("Y-m-d H:i:s")).'"
        AND vf.`datefin` > "'.(date("Y-m-d H:i:s")).'" 
        AND p.`quantity` =0');
		if ($query) {
			foreach ($query AS $enr) {
				if (StockAvailable::getQuantityAvailableByProduct($enr['id_venteflash'],null,(int)$this->getIDMagasin()) > 0) continue;
				if(Configuration::get('DOM_PROMO_FINVENTE_VFTYPE1') == '1') {
					// Arrêt de la vente Flash.
					$delete = Db::getInstance()->Execute('
         			DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE `id_product` = '.$enr['id_venteflash']);
					$delete = Db::getInstance()->Execute('
         			UPDATE `'._DB_PREFIX_.'dompromo` SET `datefin` = "'.(date("Y-m-d H:i:s")).'" WHERE `id_venteflash` = '.$enr['id_venteflash']);
					// On vérifie si on doit désactiver le produit.
					if(Configuration::get('DOM_PROMO_DESACTIVE_VFTYPE1') == '1') {
						// Désactivation du produit.
						$result = Db::getInstance()->Execute('
          		UPDATE `'._DB_PREFIX_.'product` SET active = 0, date_upd=NOW() WHERE id_product='.$enr['id_venteflash']);
					}
				}
			}
		}
	}

	// Retourne le type de promotion (1, 2 ou 3)
	private function gettypeval($id) {
		return Db::getInstance()->getValue('SELECT typesale FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));
	}

	// Retourne le Pourcentage de réduction de la table "dompromo".
	private function getpourcentreduction($id) {
		return Db::getInstance()->getValue('SELECT vfreduction FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));
	}

	//[A supprimer au final] Retourne l'ID du magasin.
	private function getIDMagasin() {
		include_once(dirname(__FILE__).'/requetes.php');
		$IDMag =(int) Context::getContext()->shop->id;
		return $IDMag;
	}

	// Démarrage automatique des ventes.
	function startVenteFlash() {
		$offset = date("Y-m-d H:i:s", time() + Configuration::get('DOM_PROMO_OFFSET'));
		$retour = false;
		$start = Db::getInstance()->ExecuteS('
			SELECT * FROM `'._DB_PREFIX_.'dompromo` WHERE `datedebut` < `datefin` 
			AND `datedebut` <= "'.$offset.'" AND `datefin` > "'.$offset.'"');
		foreach ($start AS $enr) {
			$verif = Db::getInstance()->getValue('SELECT count(id_specific_price) FROM `'._DB_PREFIX_.'specific_price` WHERE  `id_product` = '.$enr['id_venteflash'].' AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
			if ($verif == 0) {
				$insert = Db::getInstance()->Execute('
					INSERT INTO  `'._DB_PREFIX_.'specific_price` 
					(`id_product`,`id_shop`,`from_quantity`,`reduction`, `from`, `to`, `reduction_type`, `price`) 
					VALUES ('.$enr['id_venteflash'].','.(int)($this->getIDMagasin()).',0,'.($enr['vfreduction']/100).', "'.$enr['datedebut'].'", "'.$enr['datefin'].'", "percentage", "-1")');
				$result=Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product` SET on_sale = 0 where id_product = '.$enr['id_venteflash']);
				$retour = true;
			}
		}
		return $retour;
	}

	// Annule les soldes et Promos terminés.
	private function initializventes() {
		return ;
	}


	// Vérifie si il existe des ventes flash à venir.
	function commingSVF() {
		global $link, $cookie;
		$sql = Db::getInstance()->getValue('
			SELECT count(p.id_product) as nbProduct
			FROM `'._DB_PREFIX_.'product` p				
			LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`		
			WHERE vf.`vfreduction` > 0 		
			AND vf.datedebut < vf.datefin
			AND vf.datedebut > "'.date("Y-m-d H:i:s").'"
			AND p.`active` = 1
			AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
				)
			');
		if($sql > 0)
			return TRUE;
		else
			return FALSE;
	}

	// Verifie si un produit à une vente flash à venir.
	function commingSoonVenteFlash($id) {
		$query = Db::getInstance()->getValue('
			SELECT count(vf.id) as nbProduit
			FROM `'._DB_PREFIX_.'dompromo` as vf
			WHERE vf.datedebut < vf.datefin 
			AND   vf.datedebut > "'.(date("Y-m-d H:i:s")).'" 
			AND   vf.id_venteflash = '.(int)($id));
		if($query > 0)
			return TRUE;
		else
			return FALSE;
	}

	private function _displayVenteflash() {
		$this->_html .= '<img src="'.$this->_path.'images/logo_venteflash.gif"  style="float:left; margin-right:15px;"> <p><b>
		'.$this->l('This tab allows you to manage Quick sales:').'</b></p><p>&nbsp;
		'.$this->l('- Enter either the product name, its ID (when the name is Eron, the textbox displays the corresponding names).').'<br />&nbsp;
		'.$this->l('- Choose using the calendar button, or enter a start date of sale, and a completion date of sale.').'<br />&nbsp;
		'.$this->l('- Choose a discount percentage to apply to this sale.').'<br />&nbsp;
		'.$this->l('- Click the "OK".').'</p><p>&nbsp;
		'.$this->l('The start date of a sale flash can be immediate or delayed.').'<br />&nbsp;
		'.$this->l('If the start date is deferred, the sale will begin automatically on the date and time.').'</p><p>&nbsp;
		'.$this->l('You can select a product and click "Start" to start selling immediately flash normally deferred.').'<br />&nbsp;
		'.$this->l('To stop a sale flash, select the product and click Stop.').'<br />&nbsp;
		'.$this->l('To remove a flash sale (current, pending or completed), select the product and click Remove.').'</p><p><b>
		'.$this->l('Complementary actions:').'</b></p><p>&nbsp;
		'.$this->l('Ability to display an image in promotional sales sheet.').'<br />&nbsp;&nbsp;&nbsp;&nbsp;-> &nbsp;
		'.$this->l('Image modified under the folder:').' "'.$this->_path.'images/imports/venteflash.gif"<br />&nbsp;
		'.$this->l('You can automatically stop the sale when the stock is zero.').'<br />&nbsp;
		'.$this->l('You can automatically disable a product once the sale is complete flash.').'</p>
		';
	}

	private function _displayCoutantPrice() {
		$this->_html .= '<img src="'.$this->_path.'images/logo_prixcoutant.jpg"  style="float:left; margin-right:15px;"> <p><b>
		'.$this->l('- This tab allows you to manage a Coutant Price:').'</b></p>';
		$this->_displayUtilisation("pc");
	}

	private function _displayGeneral() {
		$this->_html .= '<p><b>'.$this->l('General Settings module:').'</b></p><p>&nbsp;
		'.$this->l('- Checking the server time and time zone. ').'<br />&nbsp;
		'.$this->l('- Can adjust the scrolling promotional sales. ').'<br />&nbsp;<b>
		'.$this->l('- Warning: A product may not be available in two types of promotional sales at the same time.').'</b><br />&nbsp;&nbsp;&nbsp;&nbsp;
		'.$this->l('If you have such a product for sale at prices declining, put it on sale in this module will void its declining sales.').'
		</p>';
	}

	private function displayMerci() {
		$this->_html .= '<p>&nbsp;</p>
			<fieldset><legend><img src="../img/admin/unknown.gif" />Crédits :</legend>';
		$this->_html .= '<p>Module créé et fourni par le site <a href="http://www.aideaunet.com" target="_news">www.aideaunet.com</a>.</p>';
		$this->_html .= '<p>Module fonctionnel pour Prestashop version 1.5.</p>';
		$this->_html .= '<p>Information des versions dans le fichier "<a href="../modules/dompromo/lisezmoi.txt" target="_news">lisezmoi.txt</a>" joint à ce module.</p>';
		$this->_html .= '<p>Distribution du module interdit sur tout autre support que le site <a href="http://www.aideaunet.com" target="_news">www.aideaunet.com</a></p>';
		$this->_html .= '</fieldset>';
	}

	private function displayDon() {
		$this->_html .= '<p>&nbsp;</p>
				<fieldset>
					<legend><img src="../img/admin/unknown.gif" alt="'.$this->l('Encourage the author, make a donation.').'" title="" />'.$this->l('Encourage the author, make a donation.').'</legend>';
		$this->_html .= '<p>'.$this->l('You use this module in its free version. If this module you should and want to thank the author, you can make a donation using the link below.').'</p>';
		$this->_html .= '<div style="margin:0 auto; text-align:center;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="HA4G6D6YSY9TW">
			<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
			<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
			</form></div>';
		$this->_html .= '<p>&nbsp;</p>';
		$this->_html .= $this->l('You can also purchase the full version of this module on the site').' : <a href="http://www.aideaunet.com" target="_news">aideaunet.com</a>';
		$this->_html .= '</fieldset><p>&nbsp;</p>';
	}

	private function _displayreductiondestocks() {
		$this->_html .= '<img src="'.$this->_path.'images/Logo_destockage.jpg"  style="float:left; margin-right:15px;"> <p><b>
		'.$this->l('- This tab allows you to manage a reduction of stocks:').'</b></p>';
		$this->_displayUtilisation("rs");
	}

	private function _displayUtilisation($option="") {
		$this->_html .= '<p>&nbsp;
		'.$this->l('- Enter either the product name, its ID (when the name is Eron, the textbox displays the corresponding names).').'<br />&nbsp;
		'.$this->l('- Choose using the calendar button, or enter a start date of sale, and a completion date of sale.').'<br />&nbsp;
		'.$this->l('- Choose a discount percentage to apply to this sale.').'<br />&nbsp;
		'.$this->l('- Click the "OK".').'</p><p>&nbsp;<br />&nbsp;
		'.$this->l('The start date of a sale can be immediate or delayed.').'<br />&nbsp;
		'.$this->l('If the start date is deferred, the sale will begin automatically on the date and time.').'</p><p>&nbsp;
		'.$this->l('You can select a product and click "Start" to start selling immediately flash normally deferred.').'<br />&nbsp;
		'.$this->l('To stop a sale, select the product and click Stop.').'<br />&nbsp;
		'.$this->l('To remove a sale (current, pending or completed), select the product and click Remove.').'</p><p><b>
		'.$this->l('Complementary actions:').'</b></p><p>&nbsp;
		'.$this->l('Ability to display an image in promotional sales sheet.').'<br />&nbsp;&nbsp;&nbsp;&nbsp;-> &nbsp;
		'.$this->l('Image modified under the folder:').' "'.$this->_path;
		if($option=="pc") $this->_html .= 'images/imports/prixcoutant.gif"<br />&nbsp;&nbsp;';
		elseif($option=="rs") $this->_html .= 'images/imports/destockage.gif"<br />&nbsp;&nbsp;';
		$this->_html .= $this->l('You can automatically stop the sale when the stock is zero.').'<br />&nbsp;
		'.$this->l('You can automatically disable a product once the sale is complete.').'</p>';
	}

	private function _displayCategoryPrice() {
		$this->_html .= '<p><b>'.$this->l('This tab manages mass Sales Flash, the cost price, Clearance, Promotions and Sales:').'</b></p><p>&nbsp;&nbsp;
		'.$this->l('Here you can assign to a type of promotional sales, a category or all products from a supplier.').'</p><p><b>
		'.$this->l('use:').'</b></p><p>&nbsp;&nbsp;
		'.$this->l('- Select a category.').'<br />&nbsp;&nbsp;
		'.$this->l('- Select a type of sales promotion.').'<br />&nbsp;&nbsp;
		'.$this->l('- Choose a discount percentage to apply to this sale.').'<br />&nbsp;&nbsp;
		'.$this->l('- Choose using the calendar button, or enter a start date of sale, and a completion date of sale.').'<br />&nbsp;&nbsp;
		'.$this->l('- Click the "OK".').'</p><p>
		'.$this->l('After submitting, you will find all items of the selected category tab on the type of sales promotion selected.').'<br />    
		'.$this->l('Act identically with suppliers if you want to make a promotional sale of all items from a supplier.').'</p>
		';
	}

	private function _displayReduction() {
		$this->_html .= '<p><b>'.$this->l('This tab allows you to manage a Promotion or Sale:').'</b></p>';
		$this->_displayUtilisation();
	}

	static public function getParameters() {
		return Db::getInstance()->ExecuteS('
			SELECT *
			FROM '._DB_PREFIX_.'dompromo_parameters LIMIT 1
			' );
	}

	private function _displayForm() {
		$this->_html .='
			<link rel="stylesheet" href="'.$this->_path.'css/colorpicker.css" type="text/css" />
			<link rel="stylesheet" href="'.$this->_path.'css/layout.css" type="text/css"/>
			<script type="text/javascript" src="'.$this->_path.'js/colorpicker.js"></script>
			<script type="text/javascript" src="'.$this->_path.'js/eye.js"></script>
			<script type="text/javascript" src="'.$this->_path.'js/layout.js?ver=1.0.2"></script>
			';
		$MDParameters = array();
		$MDParameters = $this->getParameters();
		$this->_html .= '
			<style>
			.Buttonlistitem
			{
				position: relative;
				display: block;
				float: left;	
				list-style-type: none;
				text-align: center;
			}
			';
		for($k=2;$k<=17; $k++)
		{
			$this->_html .= '
				#colorSelector'.$k.' { position: absolute; top: 0; left: 0; width: 36px; height: 36px; background: url('.$this->_path.'/img/select2.png); }
				#colorSelector'.$k.' div { position: absolute; top: 4px; left: 4px; width: 28px; height: 28px; background: url('.$this->_path.'/img/select2.png) center; }

				#colorpickerHolder'.$k.' { top: 32px; left: 0; width: 356px; height: 0; overflow: hidden; position: absolute; }
				#colorpickerHolder'.$k.' .colorpicker { background-image: url('.$this->_path.'/img/custom_background.png); position: absolute; bottom: 0; left: 0; }
				#colorpickerHolder'.$k.' .colorpicker_hue div { background-image: url('.$this->_path.'/img/custom_indic.gif); } 
				#colorpickerHolder'.$k.' .colorpicker_hex { background-image: url('.$this->_path.'/img/custom_hex.png); }
				#colorpickerHolder'.$k.' .colorpicker_rgb_r { background-image: url('.$this->_path.'/img/custom_rgb_r.png); }
				#colorpickerHolder'.$k.' .colorpicker_rgb_g { background-image: url('.$this->_path.'/img/custom_rgb_g.png); }
				#colorpickerHolder'.$k.' .colorpicker_rgb_b { background-image: url('.$this->_path.'/img/custom_rgb_b.png); }
				#colorpickerHolder'.$k.' .colorpicker_hsb_ss { background-image: url('.$this->_path.'/img/custom_hsb_s.png); display: none; }
				#colorpickerHolder'.$k.' .colorpicker_hsb_h { background-image: url('.$this->_path.'/img/custom_hsb_h.png); display: none; }
				#colorpickerHolder'.$k.' .colorpicker_hsb_b { background-image: url('.$this->_path.'/img/custom_hsb_b.png); display: none; }
				#colorpickerHolder'.$k.' .colorpicker_submit { background-image: url('.$this->_path.'/img/custom_submit.png); }
				#colorpickerHolder'.$k.' .colorpicker input { color: #778398; }
				';
		}
		$this->_html.= '</style>';
		$this->_html .= '
			<style type="text/css">
			.asholder
			{
				position: relative;
			}
			</style>
			&nbsp;<br />
     
			<fieldset><legend><img src="../img/admin/contact.gif" />'.$this->l('Time server and Setting the slider').'</legend>
				<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form">
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="FormVF" class="asholder">
				    <tr>
						<td width="200" style="color:blue; vertical-align: top;font-weight:bold;">'.$this->l('Local Time:').'</td>
						<td width="120" style="color:green; vertical-align: top;font-weight:bold;" id="clock">
						<SCRIPT LANGUAGE="JavaScript">
						<!-- Begin
						function getthedate(){
							var mydate=new Date();
							var hours=mydate.getHours();
							var minutes=mydate.getMinutes();
							var seconds=mydate.getSeconds();
							var dn="AM";
							if (hours>=12) dn="PM";
							if (hours>12) hours=hours-12;
							if (hours==0) hours=12;
							if (minutes<=9) minutes="0"+minutes;
							if (seconds<=9)    seconds="0"+seconds;
							var cdate=""+hours+":"+minutes+":"+seconds+" "+dn+"";
							if (document.all)
							document.all.clock.innerHTML=cdate;
							else if (document.getElementById)
							document.getElementById("clock").innerHTML=cdate;
							else
							document.write(cdate);
							}
							if (!document.all&&!document.getElementById) getthedate();

							function goforit(){
								if (document.all||document.getElementById) setInterval("getthedate()",1000);
							}
							window.onload=goforit;
							// End -->
							</SCRIPT>
						</td>
						<td width="200" style="color:blue; vertical-align: top;font-weight:bold;">'.$this->l('Server Time:').'</td>
						<td style="color:green; vertical-align: top;font-weight:bold;"><iframe height="20" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0 width="700" src="'.$this->_path.'clock.php" ></iframe></td>
					</tr>
					<tr>
						<td colspan="2" style="color:blue;  vertical-align: top;"><b>'.$this->l('Shop TimeZone:').'</b></td>
						<td colspan="2" style="color:red;   vertical-align: top;"><b>'.$this->timeZone().'</b></td>
					</tr>
					</table><br />
					<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form2">
					<tr>
						<td colspan="5" style="color:blue; vertical-align: top;text-align: left;font-weight:bold">'.$this->l('Setting the duration of effect and time before the exchange of promotion (Seconds).').'</td>
					</tr>
					<tr>
						<td colspan="5" style="text-align: left;"><input type="checkbox" name="norefreh" class="categoryBox" id="norefreh" value="0"'.(Configuration::get('DOM_PROMO_NOREFRESHAUTO') == 1 ? ' checked="checked"' : '').' />&nbsp;&nbsp; '.$this->l('Disable automatic refresh at the end of sale.').'</td>
					</tr>
					<tr>
						<td style="color:black; vertical-align: top;text-align: left;font-weight:bold">'.$this->l('Time during sliding:').'</td>
						<td style="vertical-align: top;text-align: left"><input maxlength="1" type="text" name="effect" value="'.Tools::getValue('effect', $this->effect).'" style="width: 70px;" /></td>
						<td style="color:black; vertical-align: top;text-align: left;font-weight:bold">'.$this->l('Time out to start new sliding:').'</td>
						<td style="vertical-align: top;text-align: left"><input maxlength="2" type="text" name="transition" value="'.Tools::getValue('transition', $this->transition).'" style="width: 70px"/>
						<td style="vertical-align: top;text-align: right"><input class="button" name="vfEffect" value="'.$this->l('Set effect times').'" type="submit" style="color:blue;font-weight:bold;width: 180px;font-weight:bold"/></td>
					</tr>
					</form>
				</table>
			</fieldset>&nbsp;<br />';

		$this->_html .= '
			<style type="text/css">
			.asholder
		{
			position: relative;
		}
		</style>
		<fieldset><legend><img src="../img/admin/contact.gif" />'.$this->l('Customiz Color text modul').'</legend>
		<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form10">
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="Form10">';
		$this->_html .= '<TR><TD>'.$this->l('list Price drop').' : </TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text time').'<br />';
		$this->_html .= '<input type="hidden" name="flashtexttimeColor" value="'.$MDParameters[0]['flashtexttimeColor'].'" id="HiddenColor2">
						<div id="customWidget2">
						<div id="colorSelector2"><div style="background-color: #'.$MDParameters[0]['flashtexttimeColor'].'"></div></div>
						<div id="colorpickerHolder2" style="z-index : 1000">
						</div>
					</div>';
		$this->_html .= '</TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('time color').'<br />';
		$this->_html .= '<input type="hidden" name="flashtimeColor" value="'.$MDParameters[0]['flashtimeColor'].'" id="HiddenColor3">
							<div id="customWidget3">
								<div id="colorSelector3"><div style="background-color: #'.$MDParameters[0]['flashtimeColor'].'"></div></div>
								<div id="colorpickerHolder3" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD></TR>';

		$this->_html .= '<TR><TD>'.$this->l('Block Price drop').' : </TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text time').'<br />';
		$this->_html .= '<input type="hidden" name="blocktexttimeColor" value="'.$MDParameters[0]['blocktexttimeColor'].'" id="HiddenColor4">
							<div id="customWidget4">
								<div id="colorSelector4"><div style="background-color: #'.$MDParameters[0]['blocktexttimeColor'].'"></div></div>
								<div id="colorpickerHolder4" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('time color').'<br />';
		$this->_html .= '<input type="hidden" name="blocktimeColor" value="'.$MDParameters[0]['blocktimeColor'].'" id="HiddenColor5">
							<div id="customWidget5">
								<div id="colorSelector5"><div style="background-color: #'.$MDParameters[0]['blocktimeColor'].'"></div></div>
								<div id="colorpickerHolder5" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('text').'<br />';
		$this->_html .= '<input type="hidden" name="blocktextColor" value="'.$MDParameters[0]['blocktextColor'].'" id="HiddenColor6">
							<div id="customWidget6">
								<div id="colorSelector6"><div style="background-color: #'.$MDParameters[0]['blocktextColor'].'"></div></div>
								<div id="colorpickerHolder6" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('Price').'<br />';
		$this->_html .= '<input type="hidden" name="blockpriceColor" value="'.$MDParameters[0]['blockpriceColor'].'" id="HiddenColor7">
							<div id="customWidget7">
								<div id="colorSelector7"><div style="background-color: #'.$MDParameters[0]['blockpriceColor'].'"></div></div>
								<div id="colorpickerHolder7" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD></TR>';

		$this->_html .= '<TR><TD>'.$this->l('Block Price drop has come').' : </TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text time').'<br />';
		$this->_html .= '<input type="hidden" name="blockcommingtexttimeColor" value="'.$MDParameters[0]['blockcommingtexttimeColor'].'" id="HiddenColor8">
							<div id="customWidget8">
								<div id="colorSelector8"><div style="background-color: #'.$MDParameters[0]['blockcommingtexttimeColor'].'"></div></div>
								<div id="colorpickerHolder8" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('time color').'<br />';
		$this->_html .= '<input type="hidden" name="blockcommingtimeColor" value="'.$MDParameters[0]['blockcommingtimeColor'].'" id="HiddenColor9">
							<div id="customWidget9">
								<div id="colorSelector9"><div style="background-color: #'.$MDParameters[0]['blockcommingtimeColor'].'"></div></div>
								<div id="colorpickerHolder9" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('text').'<br />';
		$this->_html .= '<input type="hidden" name="blockcommingtextColor" value="'.$MDParameters[0]['blockcommingtextColor'].'" id="HiddenColor10">
							<div id="customWidget10">
								<div id="colorSelector10"><div style="background-color: #'.$MDParameters[0]['blockcommingtextColor'].'"></div></div>
								<div id="colorpickerHolder10" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('Price').'<br />';
		$this->_html .= '<input type="hidden" name="blockcommingpriceColor" value="'.$MDParameters[0]['blockcommingpriceColor'].'" id="HiddenColor11">
							<div id="customWidget11">
								<div id="colorSelector11"><div style="background-color: #'.$MDParameters[0]['blockcommingpriceColor'].'"></div></div>
								<div id="colorpickerHolder11" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD></TR>';


		$this->_html .= '<TR><TD>'.$this->l('Price drop has come').' : </TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text start').'<br />';
		$this->_html .= '<input type="hidden" name="commingtextstartColor" value="'.$MDParameters[0]['commingtextstartColor'].'" id="HiddenColor12">
							<div id="customWidget12">
								<div id="colorSelector12"><div style="background-color: #'.$MDParameters[0]['commingtextstartColor'].'"></div></div>
								<div id="colorpickerHolder12" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('Date').'<br />';
		$this->_html .= '<input type="hidden" name="commingdateColor" value="'.$MDParameters[0]['commingdateColor'].'" id="HiddenColor13">
							<div id="customWidget13">
								<div id="colorSelector13"><div style="background-color: #'.$MDParameters[0]['commingdateColor'].'"></div></div>
								<div id="colorpickerHolder13" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('text Finish').'<br />';
		$this->_html .= '<input type="hidden" name="commingtextfinishColor" value="'.$MDParameters[0]['commingtextfinishColor'].'" id="HiddenColor14">
							<div id="customWidget14">
								<div id="colorSelector14"><div style="background-color: #'.$MDParameters[0]['commingtextfinishColor'].'"></div></div>
								<div id="colorpickerHolder14" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD></TR>';

		$this->_html .= '<TR><TD>'.$this->l('Chip produces').' : </TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text Type').'<br />';
		$this->_html .= '<input type="hidden" name="producttypeColor" value="'.$MDParameters[0]['producttypeColor'].'" id="HiddenColor15">
							<div id="customWidget15">
								<div id="colorSelector15"><div style="background-color: #'.$MDParameters[0]['producttypeColor'].'"></div></div>
								<div id="colorpickerHolder15" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD>';
		$this->_html .= '<TD>';
		$this->_html .= ''.$this->l('text').'<br />';
		$this->_html .= '<input type="hidden" name="producttexttimeColor" value="'.$MDParameters[0]['producttexttimeColor'].'" id="HiddenColor16">
							<div id="customWidget16">
								<div id="colorSelector16"><div style="background-color: #'.$MDParameters[0]['producttexttimeColor'].'"></div></div>
								<div id="colorpickerHolder16" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD><TD>';
		$this->_html .= ''.$this->l('time color').'<br />';
		$this->_html .= '<input type="hidden" name="producttimeColor" value="'.$MDParameters[0]['producttimeColor'].'" id="HiddenColor17">
							<div id="customWidget17">
								<div id="colorSelector17"><div style="background-color: #'.$MDParameters[0]['producttimeColor'].'"></div></div>
								<div id="colorpickerHolder17" style="z-index : 1000">
								</div>
							</div>';
		$this->_html .= '</TD></TR>';
		$this->_html .= '<TD colspan=5><p align="center"><input name="AddDesign" type="submit" value="'.$this->l('  Add  ').'" class="button"></p></TD>';
		$this->_html .= '
					</table>
			</fieldset>  <br />	';
	}

	public function getContent() {
		/* $this->_html .= '<script type="text/javascript" src="'.$this->_path.'js/jquery.js"></script>'; */
		$this->_html .= ' 
			<style type="text/css">  
			#flowtabs { width:900px; height:40px !important; margin:0 auto !important; padding:0; margin-bottom:-2px; }
			#flowtabs li { float:left; margin:0; padding:0; text-indent:0; list-style-type:none; }
			#flowtabs li a { background: #dddddd url("'.$this->_path.'images/tab.png") no-repeat 0 -80px; display:block; 
				height: 40px; width: 100px; padding:0px 10px; margin:0px; color:#000; font-size:12px; line-height:33px;
				text-align:left; text-decoration:none;
			}
			#flowtabs li a img.img_lang { margin-top: -2px; margin-right: 5px; }
			#flowtabs a.tab_on { background-color: #DDFFAA; }
			#flowtabs a.tab_general { background-color: #FFF6D3; }
			#flowtabs a.tab_lang_default { background-color: #D1EAEF; }
			#flowtabs a:hover { background-position: 0 -40px; }
			#flowtabs a.current { background-position: 0 0 !important; cursor:default; font-weight: bold; }
			#flowpanes { background: #fffff0 none; width:898px; border: 1px solid #999; border-top: none; margin: 0 auto; }
			#flowpanes .flowpanes_content {	color:#000; margin:20px 14px; }
			#flowpanes div h2 { font-weight:normal; letter-spacing:1px; margin:10px 0 0 0; font-size:22px; }
			#flowpanes a { font-size:14px; }
			#flowpanes div.narrow { padding-right:120px; }
			.tab_void { width: 59px; margin-top: 3px; height: 35px;
				border-left: 1px solid #999; border-bottom: 1px solid #999;
			}
			#content_tabs{ margin-left: 14px; }
			</style>
			';

		if ($_POST)
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'. $err .'</div>';
		}

		$this->_html .= '<h2>'.$this->displayName.'</h2><br>';

		if (!file_exists(_PS_SMARTY_DIR_.'plugins/function.date_diff.php')) {
			$this->_html .= '<div class="alert error" style="width:895px;">'. $this->l('Warning: The installer failed to copy the file "function.date_diff.php".') .'<br />';
			$this->_html .= $this->l('You must copy this file manually:').' '._MODULE_DIR_.'dompromo/plugins/function.date_diff.php <br />';
			$this->_html .= $this->l('to:').' '.__PS_BASE_URI__.'tools/smarty/plugins/function.date_diff.php';
			$this->_html .= '</div><br />';
		}
		if ($this->multisite == '1') {
			$this->_html .= '<div class="alert error" style="width:895px;">'. $this->l('Sorry, but this version of the module does not manage the multi.').'<br /></div><br />';
			return $this->_html;
		} else if (_PS_VERSION_ < $this->versionmini) {
			$this->_html .= '<div class="alert error" style="width:895px;">'. $this->l('Sorry, but this version is not domPromo compatible with your version of PrestaShop. Check out').' <a href="http://www.aideaunet.com" target="_news">http://www.aideaunet.com</a><br /></div><br />';
			return $this->_html;
		}

		$this->_html .= $this->chercheMaj();
		$this->_html .= $this->displayDon();
		$this->_html .= '<p>&nbsp;</p>
			<fieldset><legend><img src="'._MODULE_DIR_.strtolower($this->name).'/logo.gif" />domPromo :</legend>';
		//$this->_html .= '<p>'.$this->l('If you want to use this module in its full version, if it have not already, it will soon be available on the website:').' <a href="http://www.aideaunet.com" target="_news">aideaunet.com</a></p>';
		$this->_html .= '<p>&nbsp;</p>';
		$this->_html .= '
	  		<script type="text/javascript"> 
				$(document).ready(function() {
					//When page load
					$(".flowpanes_content").hide(); // Masquer tout les contenus
					//$(".active").show(); //Show tab content <li class="active"> (désactiver les 2 ci-dessous.)
					$("ul.tabs li:first").addClass("active").show(); // Activer le premier onglet
					$("ul.tabs li:first").find("a").addClass("current");
					$(".flowpanes_content:first").show(); // afficher le contenu premier onglet

					//On Click Event
					$("ul.tabs li").click(function() 
					{
						$("ul.tabs li").find("a").removeClass("current"); // desactive visuellement tous les onglets (enleve class "current" des liens)
						$(this).find("a").addClass("current"); // Active visuellement l\'onglet courant (ajoute au lien la class "current")
						//$("ul.tabs li").removeClass("active"); // Remove any "active" class
						//$(this).addClass("active"); // Add "active" class to selected tab
						$(".flowpanes_content").slideUp(); // Masquer tout les contenus onglets
						var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
						$(activeTab).slideDown(); // Fondu dans le contenu ID active
						return false;
					});
				});
			</script>';

		$this->_html .= ' 
			<div id="content_tabs">
			<ul id="flowtabs" class="tabs">
				<li><a href="#General" class="tab_general" >'.$this->l('General').'</a></li> 
				<li><a href="#Category" class="tab_on tab_lang_default" >'.$this->l('Category').'</a></li> 
				<li><a href="#Flash" class="tab_on " >'.$this->l('Flash Sales').'</a></li>     
				<li><a href="#Countant" class="tab_on " style="color:#AAA;">'.$this->l('Coutant Price').'</a></li>     
				<li><a href="#stocks" class="tab_on " style="color:#AAA;">'.$this->l('clearance').'</a></li>
				<li><a href="#Promotion" class="tab_on " style="color:#AAA;">'.$this->l('Promotion').'</a></li>     
				<li><a href="#Sale"class="tab_on "  style="color:#AAA;" >'.$this->l('On sale !').'</a></li>
				<li><div class="tab_void"></div></li>
			</ul>';

		$this->_html .= ' <script type="text/javascript" src="'.$this->_path.'js/datetimepicker_css.js"></script>';
		$this->_html .= ' <script type="text/javascript" src="'.$this->_path.'js/bsn.AutoSuggest_2.1.3_comp.js" charset="utf-8"></script>';
		$this->_html .= ' <link rel="stylesheet" href="'.$this->_path.'css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />';
		$this->_html .= '
			<div id="flowpanes"> 
			<br clear="all"> 
			<div class="flowpanes_content"  id="General">';
		$this->_displayGeneral();
		$this->_displayForm();
		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content"  id="Category">';
		$this->_displayCategoryPrice();
		$this->showCategories();

		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content"  id="Flash">';
		$this->startVenteFlash();
		$this->checkVF();
		$this->_displayVenteflash();
		$this->getVentesFlash();

		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content"  id="Countant">';
		$this->startVenteFlash();
		$this->checkVF();
		$this->_displayCoutantPrice();
		// version Light  
		$this->getVentesFlashLight(1);

		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content"  id="stocks">';
		$this->startVenteFlash();
		$this->checkVF();
		$this->_displayreductiondestocks();
		// version Light          
		$this->getVentesFlashLight(2);

		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content"  id="Promotion">';
		$this->_html .= '<img src="'.$this->_path.'images/logo_promo.gif"  style="float:left; margin-right:15px;">';
		$this->_displayReduction();
		$this->getReduction("Promotion");

		$this->_html .= '</div>';
		$this->_html .= '<div class="flowpanes_content" id="Sale">';
		$this->_html .= '<img src="'.$this->_path.'images/logo_soldes.gif"  style="float:left; margin-right:15px;">';
		$this->_displayReduction();
		$this->getReduction("On sale !");

		$this->_html .= '</div></div>';
		$this->_html .= '</fieldset>';
		$this->displayMerci();
		return $this->_html;
	}

	// Test cohérence des données de la mise en vente promotionnelle.
	private function TestPostProduit($Prefixe) {
		include_once(dirname(__FILE__).'/requetes.php');

		if (Tools::isEmpty($_POST[$Prefixe.'reduction'])) $this->_postErrors[] = $this->l('Reduction is required.');
		if (!is_numeric($_POST[$Prefixe.'reduction'])) $this->_postErrors[] = $this->l('Reduction must be numeric.');
		if (Tools::isEmpty($_POST[$Prefixe.'from'])) $this->_postErrors[] = $this->l('Begin date is required.');
		if (Tools::isEmpty($_POST[$Prefixe.'to'])) $this->_postErrors[] = $this->l('End date is required.');
		if (strtotime($_POST[$Prefixe.'to']) < strtotime($_POST[$Prefixe.'from'])) $this->_postErrors[] = $this->l('Date from is greater than date to.');
		if (strtotime($_POST[$Prefixe.'to']) == strtotime($_POST[$Prefixe.'from'])) $this->_postErrors[] = $this->l('Date from is equal to date to.');
		if (Tools::isEmpty($_POST[$Prefixe.'product'])) $this->_postErrors[] = $this->l('A product id is required.');
		if (!is_numeric($_POST[$Prefixe.'product'])) $this->_postErrors[] = $this->l('Product id must be numeric.');
		if ($this->checkProductId($_POST[$Prefixe.'product']) == FALSE) {
			$this->_postErrors[] = $this->l('Product Id does not exists.');
		} else {
			if ($this->isActiveProduct($_POST[$Prefixe.'product']) == FALSE) $this->_postErrors[] = $this->l('This product is not active product.');
			if ($this->reductionPrice($_POST[$Prefixe.'product']) == TRUE) $this->_postErrors[] = $this->l('This product has a reduction by price.');
		}
		// On vérifie qu'il n'existe pas de vente dégressive en cours.
		if (domPromoReq::isVenteDegressif($_POST[$Prefixe.'product'], $_POST[$Prefixe.'from'], $_POST[$Prefixe.'to']) == TRUE) {
			$this->_postErrors[] = $this->l('not add').' "'.$this->nomProduit($_POST[$Prefixe.'product']).'" (ID = '.$_POST[$Prefixe.'product'].') , '.$this->l('discount on quantity sales underway for this product.');
		}
	}

	private function _postValidation() {
		include_once(dirname(__FILE__).'/requetes.php');

		if (Tools::isSubmit('addSubmit') AND !isset($_POST['vf'])) {
			// Test cohérence des saisies de mise en ventes.
			if(isset($_POST['vf_from'])) $this->TestPostProduit('vf_');
			if(Tools::isSubmit('vf')) $ext='vf';
			if (!empty($_POST[$ext.'_reduction']) AND !is_numeric($_POST[$ext.'_reduction']))
				$this->_postErrors[] = $this->l('Reduction must be numeric.');

			if(is_array($_POST[$ext]) == TRUE) {
				foreach($_POST[$ext] as $chave => $id) {
					if (strtotime($_POST[$ext.'_to']) == strtotime($_POST[$ext.'_from']))
						$this->_postErrors[] = $this->l('Product ID')." ".$id." : ".$this->l('Date from is equal to date to.');
					if (strtotime($_POST[$ext.'_to']) < strtotime($_POST[$ext.'_from']))
						$this->_postErrors[] = $this->l('Product ID')." ".$id." : ".$this->l('Date from is greater than date to.');
				}
			} else {
				if (strtotime($_POST[$ext.'_to']) < strtotime($_POST[$ext.'_from']))
					$this->_postErrors[] = $this->l('Date from is greater than date to.');
			}
		}

		if (Tools::isSubmit('startvf')) {
			if (Tools::isEmpty($_POST['vf']))
				$this->_postErrors[] = $this->l('Please select a product.');
			else {
				if(is_array($_POST['vf']) == TRUE) {
					foreach($_POST['vf'] as $chave => $id) {
						if($this->from_to_Compare($id) == FALSE)
							$this->_postErrors[] = $this->l('Product ID')." ".$id." : ".$this->l('Select a date/time to greater than now.');
					}
				} else {
					if($this->from_to_Compare($_POST['vf']) == FALSE) {
						$this->_postErrors[] = $this->l('Product ID')." ".$_POST['vf']." : ".$this->l('Select a date/time to greater than now.');
					}
				}
				if (!sizeof($this->_postErrors)) {
					foreach($_POST['vf'] as $chave => $id) {
						if (domPromoReq::startNowVF($id) == false)
							$this->_postErrors[] = $this->l('not add').' "'.$this->nomProduit($id).'" (ID = '.$id.')<br />'.$this->l('discount on quantity sales underway for this product.');
					}
				}
			}
		}

		if (Tools::isSubmit('stopvf')) {
			if (Tools::isEmpty($_POST['vf']))
				$this->_postErrors[] = $this->l('Please select a product to stop it.');
		}

		if (Tools::isSubmit('delvf')) {
			if (Tools::isEmpty($_POST['vf']))
				$this->_postErrors[] = $this->l('Please select a product to delete it.');
		}

		if (Tools::isSubmit('vfEffect')) {
			if (Tools::isEmpty($_POST['effect']))
				$this->_postErrors[] = $this->l('Effect time is required.');
			if (!is_numeric($_POST['effect']))
				$this->_postErrors[] = $this->l('Effect time must be numeric.');
			if (Tools::isEmpty($_POST['transition']))
				$this->_postErrors[] = $this->l('Transition time is required.');
			if (!is_numeric($_POST['transition']))
				$this->_postErrors[] = $this->l('Transition time must be numeric.');
		}

		if (Tools::isSubmit('addCat')) {
			if (Tools::isEmpty($_POST['cat']))
				$this->_postErrors[] = $this->l('Please select a category.');
			if (Tools::isEmpty($_POST['cat_reduction']))
				$this->_postErrors[] = $this->l('Reduction field is empty.');
			if (Tools::isEmpty($_POST['vf_fromcat']))
				$this->_postErrors[] = $this->l('Begin date is required.');
			if (Tools::isEmpty($_POST['vf_tocat']))
				$this->_postErrors[] = $this->l('End date is required.');
			if (strtotime($_POST['vf_tocat']) < strtotime($_POST['vf_from']))
				$this->_postErrors[] = $this->l('Date from is greater than date to.');
			if (strtotime($_POST['vf_tocat']) == strtotime($_POST['vf_from']))
				$this->_postErrors[] = $this->l('Date from is equal to date to.');
			if (!sizeof($this->_postErrors)) {
				// On vérifie qu'il n'existe pas de vente dégressive en cours.
				foreach($_POST['cat'] as $chave => $value) {
					$listProd = domPromoReq::ProduitFromCategorie($value);
					if ($listProd) {
						foreach ($listProd AS $list) {
							if (domPromoReq::isVenteDegressif($list['id_product'], $_POST['vf_fromcat'], $_POST['vf_tocat']) == TRUE) {
								$this->_postErrors[] = $this->l('not add').' "'.$this->nomProduit($list['id_product']).'" (ID = '.$list['id_product'].') , '.$this->l('discount on quantity sales underway for this product.');
							} else {
								domPromoReq::add_vente($list['id_product'], $_POST['vf_fromcat'], $_POST['vf_tocat'], $_POST['cat_reduction'], 1, $_POST["typesg$value"]);
							}
						}
					}
				}
			}
		}

		if (Tools::isSubmit('addfor')) {
			if (Tools::isEmpty($_POST['for']))
				$this->_postErrors[] = $this->l('Please select a category.');
			if (Tools::isEmpty($_POST['for_reduction']))
				$this->_postErrors[] = $this->l('Reduction field is empty.');
			if (Tools::isEmpty($_POST['for_fromcat']))
				$this->_postErrors[] = $this->l('Begin date is required.');
			if (Tools::isEmpty($_POST['for_tocat']))
				$this->_postErrors[] = $this->l('End date is required.');
			if (strtotime($_POST['for_tocat']) < strtotime($_POST['vf_from']))
				$this->_postErrors[] = $this->l('Date from is greater than date to.');
			if (strtotime($_POST['for_tocat']) == strtotime($_POST['vf_from']))
				$this->_postErrors[] = $this->l('Date from is equal to date to.');
			if (!sizeof($this->_postErrors)) {
				// On vérifie qu'il n'existe pas de vente dégressive en cours.
				foreach($_POST['for'] as $chave => $value) {
					$listProd = domPromoReq::ProduitFromFournisseur($value);
					if ($listProd) {
						foreach ($listProd AS $list) {
							if (domPromoReq::isVenteDegressif($list['id_product'], $_POST['for_fromcat'], $_POST['for_tocat']) == TRUE) {
								$this->_postErrors[] = $this->l('not add').' "'.$this->nomProduit($list['id_product']).'" (ID = '.$list['id_product'].') , '.$this->l('discount on quantity sales underway for this product.');
							} else {
								domPromoReq::add_vente($list['id_product'], $_POST['for_fromcat'], $_POST['for_tocat'], $_POST['for_reduction'], 1, $_POST["typesg$value"]);
							}
						}
					}
				}
			}
		}
	}


	private function _postProcess() {
		include_once(dirname(__FILE__).'/requetes.php');

		if(Tools::isSubmit('AddDesign'))
		{
			Db::getInstance()->delete(_DB_PREFIX_.'dompromo_parameters');
			$tabDesign = array();
			Tools::getValue('flashtexttimeColor')=="" 				? false : $tabDesign["flashtexttimeColor"] 				= Tools::getValue('flashtexttimeColor');
			Tools::getValue('flashtimeColor')=="" 						? false : $tabDesign["flashtimeColor"] 						= Tools::getValue('flashtimeColor');
			Tools::getValue('blocktexttimeColor')=="" 				? false : $tabDesign["blocktexttimeColor"] 				= Tools::getValue('blocktexttimeColor');
			Tools::getValue('blocktimeColor')=="" 						? false : $tabDesign["blocktimeColor"] 						= Tools::getValue('blocktimeColor');
			Tools::getValue('blocktextColor')=="" 						? false : $tabDesign["blocktextColor"] 						= Tools::getValue('blocktextColor');
			Tools::getValue('blockpriceColor')=="" 						? false : $tabDesign["blockpriceColor"] 					= Tools::getValue('blockpriceColor');
			Tools::getValue('blockcommingtexttimeColor')=="" 	? false : $tabDesign["blockcommingtexttimeColor"]	= Tools::getValue('blockcommingtexttimeColor');
			Tools::getValue('blockcommingtimeColor')=="" 			? false : $tabDesign["blockcommingtimeColor"] 		= Tools::getValue('blockcommingtimeColor');
			Tools::getValue('blockcommingtextColor')=="" 			? false : $tabDesign["blockcommingtextColor"] 		= Tools::getValue('blockcommingtextColor');
			Tools::getValue('blockcommingpriceColor')=="" 		? false : $tabDesign["blockcommingpriceColor"] 		= Tools::getValue('blockcommingpriceColor');
			Tools::getValue('commingtextstartColor')=="" 			? false : $tabDesign["commingtextstartColor"] 		= Tools::getValue('commingtextstartColor');
			Tools::getValue('commingdateColor')=="" 					? false : $tabDesign["commingdateColor"] 					= Tools::getValue('commingdateColor');
			Tools::getValue('commingtextfinishColor')=="" 		? false : $tabDesign["commingtextfinishColor"] 		= Tools::getValue('commingtextfinishColor');
			Tools::getValue('producttypeColor')=="" 					? false : $tabDesign["producttypeColor"] 					= Tools::getValue('producttypeColor');
			Tools::getValue('producttexttimeColor')=="" 			? false : $tabDesign["producttexttimeColor"] 			= Tools::getValue('producttexttimeColor');
			Tools::getValue('producttimeColor')=="" 					? false : $tabDesign["producttimeColor"] 					= Tools::getValue('producttimeColor');
			$result = Db::getInstance()->autoExecuteWithNullValues(
				_DB_PREFIX_.'dompromo_parameters',
				$tabDesign,
				"INSERT"
			);
		}

		if (Tools::isSubmit('addSubmit') AND Tools::isSubmit('vf') == FALSE AND (isset($_POST['vf_product']))) {
			$vf_product = $_POST['vf_product'];
			$this->addVenteFlash($_POST['vf_product'],$_POST['vf_from'],$_POST['vf_to'],$_POST['vf_reduction'], 1,$_POST["vf_reduction$vf_product"]);
		}

		if (Tools::isSubmit('addSubmit') AND Tools::isSubmit('vf')) {
			foreach ($_POST as $key => $value) {
				$$key = $value;
			}

			if(empty($_POST['vf_reduction'])) $vfreduction = "";
			elseif(isset($_POST['vf_reduction'])) $vfreduction = ',`vfreduction` ="'.$_POST['vf_reduction'].'"';

			if(Tools::isSubmit('vf')) {
				// Mise à jour de la vente flash
				foreach($_POST['vf'] as $chave => $value) {
					domPromoReq::maj_vf_pc_destock($value, $vf_from, $vf_to, $vfreduction, $_POST["typesg$value"], $vf_reduction); }
			}
		}


		if (Tools::isSubmit('delvf')) {
			// Suppression d'une vente Flash
			domPromoReq::del_vf_pc_destock($_POST['vf']);
		}

		if (Tools::isSubmit('stopvf')) {
			// Stopper une vente Flash
			foreach($_POST['vf'] as $chave => $value) {
				domPromoReq::stopVente($value); }
		}

		if (Tools::isSubmit('vfEffect')) {
			// Enregistrement du réglage des effets.
			Configuration::updateValue('DOM_PROMO_EFFECT', $_POST['effect']);
			Configuration::updateValue('DOM_PROMO_TRANSITION', $_POST['transition']);
			// Enregistrement du No-Refresh.
			$norefreh =  $_POST['norefreh'];
			if (Tools::isEmpty($norefreh)) $active = '0';
			else $active = '1';
			Configuration::updateValue('DOM_PROMO_NOREFRESHAUTO', $active);
		}

		if (Tools::isSubmit('aftervflash')) {
			$DOM_PROMO_DESACTIVE_VFTYPE1 =  $_POST['DOM_PROMO_DESACTIVE_VFTYPE1'];
			if (Tools::isEmpty($DOM_PROMO_DESACTIVE_VFTYPE1)) $active = '0';
			else $active = '1';
			Configuration::updateValue('DOM_PROMO_DESACTIVE_VFTYPE1', $active);

			$DOM_PROMO_FINVENTE_VFTYPE1 =  $_POST['DOM_PROMO_FINVENTE_VFTYPE1'];
			if (Tools::isEmpty($DOM_PROMO_FINVENTE_VFTYPE1)) $active = '0';
			else $active = '1';
			Configuration::updateValue('DOM_PROMO_FINVENTE_VFTYPE1', $active);

			$DOM_PROMO_IMG_VFTYPE1 =  $_POST['DOM_PROMO_IMG_VFTYPE1'];
			if (Tools::isEmpty($DOM_PROMO_IMG_VFTYPE1)) $active = '0';
			else $active = '1';
			Configuration::updateValue('DOM_PROMO_IMG_VFTYPE1', $active);

			$DOM_PROMO_TITRE_VFTYPE1 =  $_POST['DOM_PROMO_TITRE_VFTYPE1'];
			if (Tools::isEmpty($DOM_PROMO_TITRE_VFTYPE1)) $active = '0';
			else $active = '1';
			Configuration::updateValue('DOM_PROMO_TITRE_VFTYPE1', $active);
		}
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Settings updated').'</div>';
	}

	// Ajout d'une catégorie : Vérifie si le produit existe dans la VF, soit on insert soit on met à jour.
	public function addCatVenteFlash($id, $debut, $fin, $reduction,$typesale,$typesg) {
		include_once(dirname(__FILE__).'/requetes.php');

		$id_supplier = intval(Tools::getValue('id_supplier'));
		// On liste tous les produits de la catégorie.
		$query = domPromoReq::ProduitFromCategorie($id, $id_supplier);

		if ($query) {
			foreach ($query AS $list) {
				// Mise en vente ou mise à jour de chacun des produits.
				domPromoReq::add_vente($list['id_product'], $debut, $fin, $reduction, $typesale, $typesg);
			}
		}
	}

	// Ajout d'un fournisseur : Vérifie si le produit existe dans la VF, soit on insert soit on met à jour.
	public function addforVenteFlash($id, $debut, $fin, $reduction, $typesale, $typesg) {
		include_once(dirname(__FILE__).'/requetes.php');

		// On liste tous les produits du fournisseur ID.
		$query  = domPromoReq::ProduitFromFournisseur((int)($id));

		if ($query) {
			foreach ($query AS $list) {
				// Mise en vente ou mise à jour de chacun des produits.
				domPromoReq::add_vente($list['id_product'], $debut, $fin, $reduction, $typesale, $typesg);
			}
		}
	}


	//Affiche la zone horaire.
	function timeZone() {
		$tz = Configuration::get('PS_TIMEZONE');
		// Selon version Prestashop.
		if (intval($tz) > 0) {
			$sql = Db::getInstance()->getValue('
					SELECT name FROM `'._DB_PREFIX_.'timezone`
					WHERE `id_timezone` = "'.$tz.'"');
			return $sql;
		} else
			return $tz;
	}

	// On vérifie si la date de fin de Vente est supérieure à la date et heure actuelle.
	function from_to_Compare($id) {
		$sql = Db::getInstance()->getRow('
				SELECT datefin FROM `'._DB_PREFIX_.'dompromo`
				WHERE `id_venteflash` = '.(int)($id));

		if(strtotime($sql['datefin']) < strtotime(date("r")))
			return FALSE;
		else
			return TRUE;
	}

	// On vérifie l'état des ventes Flash, Prix coutant et déstockage.
	public function statusVenteFlash($id,$reduction,$base) {
		$sql2 = Db::getInstance()->getRow('
					SELECT datedebut, datefin
					FROM `'._DB_PREFIX_.'dompromo`
					WHERE `id_venteflash` =  '.(int)($id));
		if ($sql2['datedebut'] > date("Y-m-d H:i:s"))
			return '<img src="'.$this->_path.'images/date-icon.png" />';
		elseif ($sql2['datedebut'] <= date("Y-m-d H:i:s") AND $sql2['datefin'] > date("Y-m-d H:i:s"))
			return '<img src="../img/admin/enabled.gif" />';
		else
			return '<img src="../img/admin/disabled.gif" />';
	}

	// {PS 1.4] Vérifie si la promotion est active Ou en dehors de la plage Date actuelle.
	public function statusReduction($id) {
		$result = Db::getInstance()->getRow('
					SELECT `from`, `to` FROM   `'._DB_PREFIX_.'specific_price` WHERE  `id_product` =  '.(int)($id).' 
					AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		if ($result) {
			if (($result['from'] <= date("Y-m-d H:i:s")) AND ($result['to'] >= date("Y-m-d H:i:s")))
				return '<img src="../img/admin/enabled.gif" />';
			elseif (($result['from'] > date("Y-m-d H:i:s")) AND ($result['to'] > date("Y-m-d H:i:s")))
				return '<img src="'.$this->_path.'images/date-icon.png" />';
			else
				return '<img src="../img/admin/disabled.gif" />';
		} else {
			return '<img src="../img/admin/disabled.gif" />';
		}
	}

	// Vérifie  si il y a des ventes flash active.
	public function thereisVenteFlash() {
		global $link, $cookie;
		$nb_reg = Db::getInstance()->getValue('
					SELECT count(p.`id_product`) 
					FROM `'._DB_PREFIX_.'product` p				
					LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`		
					WHERE vf.`vfreduction` > 0 
					AND vf.`datedebut` <= "'.date("Y-m-d H:i:s").'" AND vf.`datefin` >= "'.date("Y-m-d H:i:s").'"		
					AND p.`active` = 1
					AND p.`id_product` IN (
						SELECT cp.`id_product`
						FROM `'._DB_PREFIX_.'category_group` cg
						LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
						WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').')
					');
		if ($nb_reg == 0)
			return FALSE;
		else
			return TRUE;
	}

	function recurseCategory($indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL, $CategorySelected) {
		global $done;
		static $irow;
		if(!is_array($CategorySelected))
			$CategorySelected = array();
		$id_obj = intval(Tools::getValue($this->identifier));
		if (!isset($done[$current['infos']['id_parent']]))
			$done[$current['infos']['id_parent']] = 0;
		$done[$current['infos']['id_parent']] += 1;

		$todo = sizeof($categories[$current['infos']['id_parent']]);
		$doneC = $done[$current['infos']['id_parent']];

		$level = $current['infos']['level_depth'] + 1;
		$img = $level == 1 ? 'lv1.gif' : 'lv'.$level.'_'.($todo == $doneC ? 'f' : 'b').'.png';

		$this->_html .= '
		<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
			<td>
				'.($level == 1 ? '' : '
					<input type="checkbox" name="cat[]" class="categoryBox'.($id_category_default != NULL ? ' id_category_default' : '').'" id="categoryBox_'.$id_category.'" value="'.$id_category.'"'.(((in_array($id_category, $indexedCategories) OR (intval(Tools::getValue('id_category')) == $id_category AND !intval($id_obj))) OR in_array($id_category, $CategorySelected)) ? ' checked="checked"' : '').' />
				').'
			</td>
			<td>
				'.$id_category.'
			</td>
			<td>
				<img src="../img/admin/'.$img.'" alt="" /> &nbsp;<label for="categoryBox_'.$id_category.'" class="t">'.stripslashes($current['infos']['name']).'</label>
			</td>
			<td >
				'.($level == 1 ? '' : '
					<select name="typesale'.$id_category.'" disabled="true"><option value="1" selected="selected">'.$this->l('Flash Sale').'</option>
			 		<option value="2" >'.$this->l('Coutant price').'</option><option value="3" >'.$this->l('reduction of stocks').'</option>
			 		<option value="4" >'.$this->l('Promotions').'</option><option value="5" >'.$this->l('On sale !').'</option></select>
			 	').'
			 </td>
			</tr>';
		if (isset($categories[$id_category]))
			foreach ($categories[$id_category] AS $key => $row)
				if ($key != 'infos')
					$this->recurseCategory($indexedCategories, $categories, $categories[$id_category][$key], $key, NULL, $CategorySelected);
	}

	//Retrieve product categories
	function showCategories()
	{
		global $irow;
		global $cookie;
		$tab_category = array();
		$tabCategorySelected = array();
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$iso = Language::getIsoById($defaultLanguage);

		$this->_html.='&nbsp;<br />
			<script type="text/javascript">
			id_language = Number('.$defaultLanguage.');
			function CheckCatID()
      {
      	for (var i=0;i<document.formcat.elements.length;i++)
        {
        	var x = document.formcat.elements[i];
          if (x.name == "cat[]")
        	{
        		x.checked = document.formcat.selall.checked;
      		}
      	}
      }</script>
		 	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" id="formcat" name="formcat">
			
			<table cellspacing="0" cellpadding="0" class="table" width="100%">
				<tr>		
			 		<td bgcolor="#CECECE" style="color:#FF1723;font-weight:bold;vertical-align:middle;text-align:left;" colspan="4" >
			 		&nbsp;&nbsp;&nbsp;<nobr>'.$this->l('Select by category').'</nobr><td><tr> 
				<tr>
					<th width="20"><input type="checkbox" name="selall" class="noborder" onclick="CheckCatID()" /></th>
					<th width="20">'.$this->l('ID').'</th>
					<th>'.$this->l('Name').'</th>
					<th>'.$this->l('Type Sale').'</th>
				</tr>
			';
		$irow = 0;
		$done = array();
		$index = array();
		$indexedCategories = array();
		$categories = Category::getCategories(intval($cookie->id_lang), false);
		foreach ($indexedCategories AS $k => $row)
			$index[] = $row['id_category'];
		$this->recurseCategory($index, $categories, $categories[0][1], 1, $obj->id, $tabCategorySelected);
		$this->_pagehtml.='
				</table>';
		$this->_html .='
    		<table  class="table" border="0" width="100%" cellpadding="0" cellspacing="2" id="formcat">            
        	<tr>
          	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Reduction').'</td>            
			 			<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time From').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time To').'</b></td>
            <td style="text-align: right"></td>
          </tr>
			  	<tr>
			  		<td style="color:blue; vertical-align: top;" ><input type="text" MAXLENGTH="2" name="cat_reduction" value="" style="width: 150px;"/></td>
			  		<td style="color:blue; vertical-align: top;"><input type="text" id="vf_fromcat" name="vf_fromcat" value="'.(date('Y-m-d H:i:s')).'" style="width: 180px;"/>
            	<a href="javascript:NewCssCal(\'vf_fromcat\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input type="text" id="vf_tocat" name="vf_tocat"   value="'.(date('Y-m-d H:i:s')).'" style="width: 180px;"/>
            	<a href="javascript:NewCssCal(\'vf_tocat\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
			 			<td style="text-align: right"><input class="button" name="addCat" value= "'.$this->l('Add').'" type="submit" style="color:green;font-weight:bold;width: 180px;"/></td>
			 		</tr>
        </table> </form>	  
				<p>&nbsp;</p>
				';
		$this->_html.='
				<script type="text/javascript">
					id_language = Number('.$defaultLanguage.');
					function CheckforID()
         	{
          	for (var i=0;i<document.formcatfournisseur.elements.length;i++)
            {
            	var x = document.formcatfournisseur.elements[i];
              if (x.name == "for[]")
              {
              	x.checked = document.formcatfournisseur.selall.checked;
              }
            }
         	}</script>
		 		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" id="formcatfournisseur" name="formcatfournisseur">
					
				<table cellspacing="0" cellpadding="0" class="table" width="100%">
					<tr>		
				 		<td bgcolor="#CECECE" style="color:#FF1723;font-weight:bold;vertical-align:middle;text-align:left;" colspan="4" >
				 		&nbsp;&nbsp;&nbsp;<nobr>'.$this->l('Select by Supplier').'</nobr><td><tr> 
					<tr>
						<th width="20"><input type="checkbox" name="selall" class="noborder" onclick="CheckforID()" /></th>
						<th width="20">'.$this->l('ID').'</th>
						<th>'.$this->l('Name').'</th>
						<th>'.$this->l('Type Sale').'</th>
					</tr>
				';
		$sql = Db::getInstance()->ExecuteS('
    		SELECT id_supplier,	name
        FROM `'._DB_PREFIX_.'supplier`         
        ');
		foreach ($sql AS $list)
		{
			$this->_html .= '
					<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
						<td>
							<input type="checkbox" name="for[]" VALUE="'.$list['id_supplier'].'">
						</td>
						<td>
							'.$list['id_supplier'].'
						</td>
						<td>
							'.$list['name'].'
						</td>
						<td ><select name="typefsale'.$list['id_supplier'].'" disabled="true"><option value="1" selected="selected">'.$this->l('Flash Sale').'</option>
			 				<option value="2" >'.$this->l('Coutant price').'</option><option value="3" >'.$this->l('reduction of stocks').'</option>
			 				<option value="4" >'.$this->l('Promotions').'</option><option value="5" >'.$this->l('On sale !').'</option>
			 				</select></td>
						';
		}
		$this->_pagehtml.='
					</tr>
				</table>
				';
		$this->_html .='
    		<table  class="table" border="0" width="100%" cellpadding="0" cellspacing="2" id="formcatfournisseur">             
        	<tr>
          	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Reduction').'</td>            
			 			<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time From').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time To').'</b></td>
            <td style="text-align: right"></td>
          </tr>
			  	<tr>
			  		<td style="color:blue; vertical-align: top;" ><input type="text" MAXLENGTH="2" name="for_reduction" value="" style="width: 150px;"/></td>
			  		<td style="color:blue; vertical-align: top;"><input type="text" id="for_fromcat" name="for_fromcat" value="'.(date('Y-m-d H:i:s')).'" style="width: 180px;"/>
            	<a href="javascript:NewCssCal(\'for_fromcat\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input type="text" id="for_tocat" name="for_tocat"   value="'.(date('Y-m-d H:i:s')).'" style="width: 180px;"/>
            	<a href="javascript:NewCssCal(\'for_tocat\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
			 			<td style="text-align: right"><input class="button" name="addfor" value= "'.$this->l('Add').'" type="submit" style="color:green;font-weight:bold;width: 180px;"/></td>
			 		</tr>
        </table> </form><br />&nbsp;<br />';
	}

	// Affichage des Ventes flash, Prix coutant et Déstockage dans le BO (Version Light)
	function getVentesFlashLight($base)
	{
		$vlight = "onClick=\"javascript: alert('Version compléte uniquement !'); return;\"";
		if($base=="1") {
			$text="Coutant Price";
			$ext="cp";
			$txtcheckbox = $this->l('Deactivate the product when coutant price be finished:');
			$txtbutton = "afterprixcoutant";
			$nomimg = "images/imports/prixcoutant.gif";
		}
		if($base=="2") {
			$text="Reduction of Stocks";
			$ext="rs";
			$txtcheckbox = $this->l('Deactivate the product when reduction of stocks be finished:');
			$txtbutton = "afterdestockage";
			$nomimg = "images/imports/destockage.gif";
		}
		$txtfincheckbox = $this->l('Stop the sell-through if the stock is zero:');
		$txtimgcheckbox = $this->l('View an image of promotional sales in the product');
		$txttitrecheckbox = $this->l('Displays the type of sale in the product.');
		$disable = '<div style="text-align:center; color: red; font-size: 20px; margin: 20px auto 10px; font-weight: bold;">'.$this->l('Light version: Tab is not active in this version').'</div>';

		$this->_html .= $disable.' <br /> <fieldset>
    		<legend><img src="'.$this->_path.'logo.gif" />';
		if($ext=='cp')
			$this->_html .= ''.$this->l('Produced to put Coutant Price').'';
		if($ext=='rs')
			$this->_html .= ''.$this->l('Produced to put Reduction of Stocks').'';
		$this->_html .= '</legend>';

		if (!$sql)
		{
			$this->_html .= '<div style="font-size:15px;color: red;text-align: left">';
			if($ext=='cp')
				$this->_html .= ''.$this->l('No Coutant Price for the time being.').'';
			if($ext=='rs')
				$this->_html .= ''.$this->l('No Reduction of Stocks for the time being.').'';
			$this->_html .= '</div>';

		}
		$this->_html .='
    		<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="10">
        	<tr>
        		<td style="color:blue; vertical-align: top;"><b>'.$this->l('Product').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Id').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time From').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time To').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Reduction (%)').'</b></td>
        	</tr>
        	<tr>
          	<td style="color:blue; vertical-align: top;"><input style="width: 200px" type="text" '.$vlight.' id="'.$ext.'productinput" value="" /></td>
          	<td style="color:blue; vertical-align: top;"><input type="text" name="'.$ext.'_product" id="'.$ext.'product" '.$vlight.' value="" style="font-size: 10px; width: 20px;" /></td>
          	<td style="color:blue; vertical-align: top;"><input type="text" '.$vlight.' id="'.$ext.'_from" name="'.$ext.'_from" value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
          		<a href="#" '.$vlight.'><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input type="text" '.$vlight.' id="'.$ext.'_to" name="'.$ext.'_to"   value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
            	<a href="#" '.$vlight.'><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input '.$vlight.' type="text" MAXLENGTH="2" name="'.$ext.'_reduction" value="" style="width: 100px;"/></td>
        	</tr>
        	<tr>
          	<td align="right" colspan="5"><input class="button" name="addSubmit" value= "'.$this->l('Add').'" type="submit" style="color:blue;font-weight:bold;width: 180px;" '.$vlight.'/></td></tr>
        </table>
        </fieldset>
			';

		$this->_html .='<p>&nbsp;</p>
    		<fieldset><legend><img src="../img/admin/contact.gif" />'.$this->l('Complementary Actions').'</legend>
		      <table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="formDesactivFlah">
				    <tr>
				      <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				    		'.$txttitrecheckbox.'</td>
				      <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$titrecheckbox.'" value="0"  '.$vlight.' >&nbsp;&nbsp;&nbsp;'.$this->l('Display').' </td>
				    </tr>
				    <tr>
				      <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				    		'.$txtimgcheckbox.'</td>
				      <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$imgcheckbox.'" value="0"  '.$vlight.' >&nbsp;&nbsp;&nbsp;'.$this->l('Display').' </td>
				    </tr>
				    <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				       	'.$txtcheckbox.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$namecheckbox.'" value="0"  '.$vlight.' >&nbsp;&nbsp;&nbsp;'.$this->l('Deactivate').' </td>
				     </tr>
				     <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				       	'.$txtfincheckbox.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$fincheckbox.'" value="0"  '.$vlight.' >&nbsp;&nbsp;&nbsp;'.$this->l('Stop').' </td>
				     </tr>
				     <tr>
				        <td colspan="3" style="vertical-align: top;text-align: right"><input class="button" name="'.$txtbutton.'" value="'.$this->l('Update settings').'" type="submit" '.$vlight.' style="color:blue;font-weight:bold;width: 180px;font-weight:bold"/></td>
				     </tr>
		      </table>
        </fieldset>  <br />';
	}

	// Affichage des Ventes flash, Prix coutant et Déstockage dans le BO
	function getVentesFlash()
	{
		$text="sale flash";
		$ext="vf";
		$txtcheckbox = $this->l('Deactivate the product when sale flash be finished:');
		$txtbutton = "aftervflash";
		$nomimg = "images/imports/venteflash.gif";
		$namecheckbox = "DOM_PROMO_DESACTIVE_VFTYPE1";
		$fincheckbox = "DOM_PROMO_FINVENTE_VFTYPE1";
		$imgcheckbox = "DOM_PROMO_IMG_VFTYPE1";
		$titrecheckbox = "DOM_PROMO_TITRE_VFTYPE1";
		$txtfincheckbox = $this->l('Stop the sell-through if the stock is zero:');
		$txtimgcheckbox = $this->l('View an image of promotional sales in the product');
		$txttitrecheckbox = $this->l('Displays the type of sale in the product.');

		global $cookie;
		$id_lang = $cookie->id_lang;
		$sql = Db::getInstance()->ExecuteS('
    		SELECT vf.*, cl.name, pl.name as nom FROM `'._DB_PREFIX_.'product` p
    		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product`= p.`id_product` AND pl.`id_lang` = '.(int)($id_lang).'
		 		LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`
		 		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON cl.`id_category` = p.`id_category_default` AND  cl.id_lang='.(int)($id_lang).'
				WHERE p.`id_product` = vf.`id_venteflash` AND vf.typesale = 1
        ORDER BY id_category_default DESC
        ');
		$this->_html .='
    		<script language = "javascript">			
		 		function CheckAll'.$ext.'ID()
				{
        	for (var i=0;i<document.form'.$ext.'.elements.length;i++)
          {
          	var x = document.form'.$ext.'.elements[i];
            if (x.name == "'.$ext.'[]")
            {
            	x.checked = document.form'.$ext.'.selall.checked;
            }
          }
        }	         
		 		</script>';
		$this->_html .= '<br />   <fieldset>
    		<legend><img src="'.$this->_path.'logo.gif" />';
		$this->_html .= ''.$this->l('Produced to put  Flash Sale').'';
		$this->_html .= '</legend>';
		$this->_html .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" id="form'.$ext.'" name="form'.$ext.'">';

		if (!$sql)
		{
			$this->_html .= '<div style="font-size:15px;color: red;text-align: left">';
			if($ext=='vf')
				$this->_html .= ''.$this->l('No Flash Sale for the time being.').'';
			if($ext=='cp')
				$this->_html .= ''.$this->l('No Coutant Price for the time being.').'';
			if($ext=='rs')
				$this->_html .= ''.$this->l('No Reduction of Stocks for the time being.').'';
			$this->_html .= '</div>';

		} else {
			$this->_html .= '
      		<table  class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form'.$ext.'">
          	<tr>
            	<td width="10" align="left" style="vertical-align: top;"><input type=checkbox name="selall" onClick="CheckAll'.$ext.'ID()" id="selall"></td>
              <td colspan="7" style="color: blue;"><b>'.$this->l('Select/Deselect all').'</b></td>
           	</tr>
           	<tr>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">---</td>
              <td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('ID').'</td>               
              <td width="280" style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Product Name').'</td>
              <td width="180" style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Date/Time From').'</td>
              <td width="180" style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Date/Time To').'</td>
              <td style="color:green;vertical-align: top;font-weight:bold;text-align: center;">'.$this->l('Reduction').'</td>
              <td style="color:green;vertical-align: top;font-weight:bold;text-align: center;">'.$this->l('Status').'</td>
            </tr>
            ';
			foreach ($sql as $listevente)
			{
				if($listevente['name'] != $nomcat)
				{
					$nomcat = $listevente['name'];
					$this->_html .='
          		<tr>		
			 					<td bgcolor="#CECECE" style="color:#FF1723;font-weight:bold;vertical-align:middle;text-align:center;" colspan="8" ><nobr>'.($nomcat).'</nobr><td><tr>';
				}

				$this->_html .='
        		<tr>
            	<td style="vertical-align: top;text-align: left"><INPUT TYPE=checkbox VALUE="'.$listevente['id_venteflash'].'" name="'.$ext.'[]"></td>
            	<td style="color:#8B0000;font-weight:bold;vertical-align: top;text-align: left"><a href="#" title="'.$this->l('Click!').'" onclick="document.getElementById(\''.$ext.'product\').value=\''.$listevente['id_venteflash'].'\'">'.$listevente['id_venteflash'].'</a></td>            
            	<td width="280" style="color:#191970;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['nom'].'</td>
            	<td width="180" style="color:#8B0A50;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['datedebut'].'</td>
            	<td width="180" style="color:#000000;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['datefin'].'</td>
            	<td style="color:#8B008B;font-weight:bold;vertical-align: top;text-align: center">'.$listevente['vfreduction'].'%</td>';
				$this->_html .= '
        		<td style="vertical-align: top;text-align: center">'.$this->statusVenteFlash($listevente['id_venteflash'],$listevente['vfreduction'],1).'</td>
           </tr>
           ';
			}
			$this->_html .='
      		<tr>
      			<td style="text-align: right" colspan="4"><input class="button" name="start'.$ext.'" value= "'.$this->l('Start').'" type="submit" style="color:green;font-weight:bold;width: 165px;"/></td>
            <td style="text-align: right" ><input class="button" name="stop'.$ext.'" value= "'.$this->l('Stop').'" type="submit" style="color:orange;font-weight:bold;width: 165px;"/></td>
            <td style="text-align: right" colspan="2"><input class="button" name="del'.$ext.'" value= "'.$this->l('Delete').'" type="submit" style="color:red;font-weight:bold;width: 165px;"/></td>
          </tr>
        </table> <br />';
		}
		$this->_html .='
    		<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="10">
        	<tr>
        		<td style="color:blue; vertical-align: top;"><b>'.$this->l('Product').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Id').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time From').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time To').'</b></td>
           	<td style="color:blue; vertical-align: top;"><b>'.$this->l('Reduction (%)').'</b></td>
        	</tr>
        	<tr>
          	<td style="color:blue; vertical-align: top;"><input style="width: 200px" type="text" id="'.$ext.'productinput" value="" /></td>
          	<td style="color:blue; vertical-align: top;"><input type="text" name="'.$ext.'_product" id="'.$ext.'product" value="" style="font-size: 10px; width: 20px;" /></td>
          	<td style="color:blue; vertical-align: top;"><input type="text" id="'.$ext.'_from" name="'.$ext.'_from" value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
          		<a href="javascript:NewCssCal(\''.$ext.'_from\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input type="text" id="'.$ext.'_to" name="'.$ext.'_to"   value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
            	<a href="javascript:NewCssCal(\''.$ext.'_to\',\'yyyymmdd\',\'arrow\',true,24,false)"><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
           	<td style="color:blue; vertical-align: top;"><input type="text" MAXLENGTH="2" name="'.$ext.'_reduction" value="" style="width: 100px;"/></td>
        	</tr>
        	<tr>
          	<td align="right" colspan="5"><input class="button" name="addSubmit" value= "'.$this->l('Add').'" type="submit" style="color:blue;font-weight:bold;width: 180px;" onClick="javascript: submitform()"/></td></tr>
        </table>
        <script language="JavaScript">
        	var options = {
		      script:"'.$this->_path.'productsuggest.php?json=true&limit=6&",
		      varname:"input",
		      json:true,
		      shownoresults:true,
		      maxresults:15,
		      callback: function (obj) { document.getElementById(\''.$ext.'product\').value = obj.id; }
	        };
	        var as_json = new bsn.AutoSuggest(\''.$ext.'productinput\', options);
        </script>
        </form></fieldset>
			';

		$this->_html .='<p>&nbsp;</p>
    		<fieldset><legend><img src="../img/admin/contact.gif" />'.$this->l('Complementary Actions').'</legend>
		      <table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="formDesactivFlah">
				    <form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="formDesactivFlah">
				    <tr>
				      <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				    		'.$txttitrecheckbox.'</td>
				      <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$titrecheckbox.'" value="0"  '.htmlentities(Configuration::get($titrecheckbox) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Display').' </td>
				    </tr>
				    <tr>
				      <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				    		'.$txtimgcheckbox.'</td>
				      <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$imgcheckbox.'" value="0"  '.htmlentities(Configuration::get($imgcheckbox) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Display').' </td>
				    </tr>
				    <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				       	'.$txtcheckbox.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$namecheckbox.'" value="0"  '.htmlentities(Configuration::get($namecheckbox) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Deactivate').' </td>
				     </tr>
				     <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">
				       	'.$txtfincheckbox.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input type="checkbox" name="'.$fincheckbox.'" value="0"  '.htmlentities(Configuration::get($fincheckbox) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Stop').' </td>
				     </tr>
				     <tr>
				        <td colspan="3" style="vertical-align: top;text-align: right"><input class="button" name="'.$txtbutton.'" value="'.$this->l('Update settings').'" type="submit" style="color:blue;font-weight:bold;width: 180px;font-weight:bold"/></td>
				     </tr>
				    </form>
		      </table>
        </fieldset>  <br />';
	}

	// Affichage des Promotions et Soldes dans le BO
	function getReduction($solde)
	{
		$vlight = "onClick=\"javascript: alert('Version compléte uniquement !'); return;\"";
		if($solde=="Promotion") {
			$variable='and on_sale="0"';
			$formreduction="PR";
			$ext="pr";
		}
		if($solde=="On sale !") {
			$variable='and on_sale="1"';
			$formreduction="SL";
			$ext="sl";
		}
		$txtfincheckbox = $this->l('Stop the sell-through if the stock is zero:');
		$disable = '<div style="text-align:center; color: red; font-size: 20px; margin: 20px auto 10px; font-weight: bold;">'.$this->l('Light version: Tab is not active in this version').'</div>';

		global $cookie;
		$id_lang = $cookie->id_lang;
		$sql = Db::getInstance()->ExecuteS('
    		SELECT DISTINCT p.*, sp.reduction, sp.from, sp.to, cl.name, pl.name as nom 
		 		FROM `'._DB_PREFIX_.'product` p	
		 		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product`= p.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
		 		LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product` AND (sp.`id_shop`='.(int)($this->getIDMagasin()).' OR sp.`id_shop`=0) AND sp.`from_quantity`<=1)
		 		LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON (vf.`id_venteflash` = p.`id_product`)
		 		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.`id_category` = p.`id_category_default` AND  cl.id_lang='.(int)($id_lang).')
				WHERE sp.reduction > 0  '.$variable.'  and vf.`id_venteflash` IS NULL 
				AND sp.from_quantity <= 1 AND sp.reduction_type = \'percentage\'
        ORDER BY id_category_default DESC
        ');
		$this->_html .='
    		<script language = "javascript">
    		function CheckAll'.$ext.'ID()
        {
        	for (var i=0;i<document.form'.$formreduction.'.elements.length;i++)
          {
          	var x = document.form'.$formreduction.'.elements[i];
            if (x.name == "'.$formreduction.'[]")
            {
            	x.checked = document.form'.$formreduction.'.selall.checked;
            }
          }
        }
		 		</script>';

		$this->_html .= $disable.' <br />
    		<fieldset><legend><img src="'.$this->_path.'logo.gif" />';
		if($ext=='sl')
			$this->_html .= ''.$this->l('Produced to put in On sale !').'';
		if($ext=='pr')
			$this->_html .= ''.$this->l('Produced to put in Promotion').'';
		$this->_html .= '</legend>';

		if (!$sql) {
			$this->_html .= '<div style="font-size:15px;color: red;text-align: left">';
			if($ext=='sl')
				$this->_html .= ''.$this->l('No on sale ! for the time being.').'';
			if($ext=='pr')
				$this->_html .= ''.$this->l('No Promotion for the time being.').'';
			$this->_html .= '</div>';

		} else {
			$this->_html .= '
      		<table  class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form'.$formreduction.'">
         		<tr>
            	<td width="10" align="left" style="vertical-align: top;"><input '.$vlight.' type=checkbox name="selall"></td>
            	<td colspan="7" style="color: blue;"><b>'.$this->l('Select/Deselect all').'</b></td>
         		</tr>
         		<tr>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">---</td>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('ID').'</td>                 
            	<td width="280" style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Product Name').'</td>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Date/Time From').'</td>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: left">'.$this->l('Date/Time To').'</td>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: center;">'.$this->l('Reduction').'</td>
            	<td style="color:green;vertical-align: top;font-weight:bold;text-align: center;">'.$this->l('Status').'</td>
         		</tr>
         		';
			foreach ($sql as $listevente)
			{
				if($listevente['name'] != $nomcat)
				{
					$nomcat = $listevente['name'];
					$this->_html .='
       				<tr>
								<td bgcolor="#CECECE" style="color:#FF1723;font-weight:bold;vertical-align:middle;text-align:center;" colspan="7" ><nobr>'.($nomcat).'</nobr><td><tr>';
				}

				$this->_html .='
       		<tr>
	        	<td style="vertical-align: top;text-align: left"><INPUT '.$vlight.' TYPE=checkbox VALUE="'.$listevente['id_product'].'"></td>
	        	<td style="color:#8B0000;font-weight:bold;vertical-align: top;text-align: left"><a href="#" title="'.$this->l('Click!').'" onclick="document.getElementById(\''.$formreduction.'product\').value=\''.$listevente['id_product'].'\'">'.$listevente['id_product'].'</a></td>             
	        	<td width="280" style="color:#191970;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['nom'].'</td>
	        	<td style="color:#8B0A50;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['from'].'</td>
	        	<td style="color:#000000;font-weight:bold;vertical-align: top;text-align: left">'.$listevente['to'].'</td>
	        	<td style="color:#8B008B;font-weight:bold;vertical-align: top;text-align: center">'.($listevente['reduction']*100).'%</td>
	        	<td style="vertical-align: top;text-align: center">'.$this->statusReduction($listevente['id_product']).'</td>
       		</tr>
       		';
			}
			$this->_html .='
      		<tr>
          	<td style="text-align: right" colspan="5"><input class="button" '.$vlight.' value= "'.$this->l('Start').'" type="submit" style="color:green;font-weight:bold;width: 165px;"/></td>
          	<td style="text-align: right" colspan="2"><input class="button" '.$vlight.' value= "'.$this->l('Delete').'" type="submit" style="color:red;font-weight:bold;width: 165px;"/></td>
      		</tr>
    			</table> <br />';
		}

		$this->_html .='
    		<table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="form">
  				<tr>
     				<td style="color:blue; vertical-align: top;"><b>'.$this->l('Product').'</b></td>
     				<td style="color:blue; vertical-align: top;"><b>'.$this->l('Id').'</b></td>
     				<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time From').'</b></td>
     				<td style="color:blue; vertical-align: top;"><b>'.$this->l('Date/Time To').'</b></td>
     				<td style="color:blue; vertical-align: top;"><b>'.$this->l('Reduction (%)').'</b></td>
  				</tr>
  				<tr>
     				<td style="color:blue; vertical-align: top;"><input '.$vlight.' style="width: 200px" type="text" id="'.$formreduction.'productinput" value="" /></td>
     				<td style="color:blue; vertical-align: top;"><input '.$vlight.' type="text" name="'.$formreduction.'_product" id="'.$formreduction.'product" value="" style="font-size: 10px; width: 20px;" /></td>
     				<td style="color:blue; vertical-align: top;"><input '.$vlight.' type="text" id="'.$formreduction.'_from" name="'.$formreduction.'_from" value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
       				<a href="#" '.$vlight.' ><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
     				<td style="color:blue; vertical-align: top;"><input type="text" id="'.$formreduction.'_to" name="'.$formreduction.'_to"   value="'.(date('Y-m-d H:i:s')).'" style="width: 150px;"/>
       				<a href="#" '.$vlight.' ><img src="'.$this->_path.'images/cal.gif" width="16" height="16" alt="'.$this->l('Pick a date').'"></a></td>
     				<td style="color:blue; vertical-align: top;"><input '.$vlight.' type="text" MAXLENGTH="2" name="'.$formreduction.'_reduction" value="" style="width: 100px;"/></td>
  				</tr>
  				<tr>
     				<td align="right" colspan="5"><input class="button" name="addSubmit" value= "'.$this->l('Add').'" type="submit" style="color:blue;font-weight:bold;width: 180px;" '.$vlight.' /></td></tr>
  			</table>
        </fieldset>
       	';

		$this->_html .='<p>&nbsp;</p>
    		<fieldset><legend><img src="../img/admin/contact.gif" />'.$this->l('Complementary Actions').'</legend>
		      <table class="table" border="0" width="800" cellpadding="0" cellspacing="2" id="formDesactivSolde">
				    <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">'.
			($solde=="On sale !" ? $this->l('Deactivate the product when sale be finished:') : $this->l('Deactivate the product when promotion be finished:'))
			.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input '.$vlight.' type="checkbox" name="'.($solde=="On sale !" ? "DOM_PROMO_DESACTIVE_SOLDE" : "DOM_PROMO_DESACTIVE_PROMO").'" value="0"  '.htmlentities(Configuration::get(($solde=="On sale !" ? 'DOM_PROMO_DESACTIVE_SOLDE' : 'DOM_PROMO_DESACTIVE_PROMO')) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Deactivate').' </td>
				     </tr>
				    <tr>
				       <td colspan="2" style="color:blue; vertical-align: top;font-weight:bold;">'.$txtfincheckbox.'</td>
				       <td style="color: black;text-align: left;font-weight:bold"><input '.$vlight.' type="checkbox" name="'.($solde=="On sale !" ? $finsoldecheckbox : $finpromocheckbox).'" value="0"  '.htmlentities(Configuration::get(($solde=="On sale !" ? $finsoldecheckbox : $finpromocheckbox)) == '1' ? 'checked="checked" ' : '').' >&nbsp;&nbsp;&nbsp;'.$this->l('Stop').' </td>
				     </tr>
				     <tr>
				        <td colspan="3" style="vertical-align: top;text-align: right"><input class="button" name="'.($solde=="On sale !" ? "aftersolde" : "afterpromo").'" value="'.$this->l('Update settings').'" '.$vlight.' type="submit" style="color:blue;font-weight:bold;width: 180px;font-weight:bold"/></td>
				     </tr>
		      </table>
        </fieldset>  <br />';
	}

	// Retourne date/time de début d'une vente flash
	private function getCSTime($id) {
		return Db::getInstance()->getValue('SELECT `datedebut` FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));
	}

	// Retourne date/time de fin d'une vente flash
	private function getTime($id) {
		return Db::getInstance()->getValue('SELECT `datefin` FROM `'._DB_PREFIX_.'dompromo` WHERE `id_venteflash` = '.(int)($id));
	}

	// Vérifie si un produit est en vente flash
	private function checkVFProductId($id) {
		$query = Db::getInstance()->getValue('
         			SELECT count(vf.id) as nbProduit
        			FROM   `'._DB_PREFIX_.'dompromo` as vf, `'._DB_PREFIX_.'specific_price` as sp
        			WHERE  sp.`id_product` =  vf.`id_venteflash` 
        			AND (sp.`reduction` * 100) = vf.`vfreduction`  
						  AND  vf.`id_venteflash` = '.(int)($id).'
						  AND (sp.`id_shop`='.(int)($this->getIDMagasin()).' OR sp.`id_shop`=0) AND sp.`from_quantity`<=1 
						  ');
		if($query > 0)
			return '1';
		else
			return '0';
	}

	// Mise à jour ou ajout de la vente flash.
	public function addVenteFlash($id, $debut, $fin, $reduction, $type,$id_config) {
		// au cas ou le produit soit en solde, on l'enléve des soldes.
		$venteflash = Db::getInstance()->Execute('
	  		UPDATE `'._DB_PREFIX_.'product`
	      SET on_sale=0
	      WHERE `id_product` = '.(int)($id));
		$check  = Db::getInstance()->getValue('
	  		SELECT count(id) as nbProduit FROM `'._DB_PREFIX_.'dompromo`
	      WHERE  `id_venteflash` = '.(int)($id));
		if ($check == '0') {
			$query =  Db::getInstance()->Execute('
	    		INSERT INTO  `'._DB_PREFIX_.'dompromo`
	        (`id_venteflash`, `datedebut`,`datefin`,`vfreduction`,`typesale`,`id_config`,`typeadd`)
	        VALUES ("'.(int)($id).'","'.$debut.'","'.$fin.'","'.$reduction.'","'.$type.'","'.$id_config.'","0")
	        ');
		} else {
			$venteflash = Db::getInstance()->Execute('
	    		UPDATE `'._DB_PREFIX_.'dompromo`
	        SET `datedebut` ="'.$debut.'",`datefin` ="'.$fin.'",`vfreduction` ="'.$reduction.'",
	        `typesale`="'.$type.'",`id_config`="'.$id_config.'",`typeadd`="0"
	        WHERE `id_venteflash` = '.(int)($id));
		}
		// Supression des ventes promo en cours dans "specific_price".
		$venteflash = Db::getInstance()->Execute('
				DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE id_product='.(int)($id).' 
				AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		// On vérifie si la vente est en cours et est à ajouter.
		if($debut < date("Y-m-d H:i:s") AND $fin > date("Y-m-d H:i:s")) {
			$insert = Db::getInstance()->Execute('
					INSERT INTO  `'._DB_PREFIX_.'specific_price`
					(`id_product`,`id_shop`,`from_quantity`,`reduction`, `from`, `to`, `reduction_type`, `price`)
					VALUES ('.(int)($id).','.(int)($this->getIDMagasin()).',0,'.($reduction/100).', "'.$debut.'", "'.$fin.'", "percentage", "-1")');
		}
	}

	// Recherche du nom de produit.
	private function nomProduit($id) {
		global $cookie;
		$id_lang = $cookie->id_lang;
		return Db::getInstance()->getValue('SELECT `name` FROM `'._DB_PREFIX_.'product_lang` 
												WHERE `id_product` = '.(int)($id).' AND `id_lang` = '.(int)($id_lang));
	}

	// Vérifie si la réduction existe, l'ajoute ou la met à jour suivant résultat.
	public function addReduction($id, $debut, $fin, $reduction, $solde) {
		$onsale="on_sale=0";
		if($solde==1)
			$onsale="on_sale=1";

		$venteflash = Db::getInstance()->Execute('
    		UPDATE `'._DB_PREFIX_.'product` SET '.$onsale.', date_upd=NOW() WHERE `id_product` = '.(int)($id));
		// On vérifie si on doit mettre à jour la table ps_specific_price
		$verif = Db::getInstance()->getValue('SELECT count(id_specific_price) FROM `'._DB_PREFIX_.'specific_price` WHERE  `id_product` = '.(int)($id).'
    							AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		if ($verif > 0) {
			$update = Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'specific_price`
				SET `reduction`='.($reduction/100).', `from_quantity`=1, `from`="'.$debut.'", `to`="'.$fin.'", `reduction_type`="percentage", `price`="-1"
				WHERE id_product='.(int)($id).' 
				AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) AND `from_quantity`<=1');
		} else {
			$sql = Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product` SET '.$onsale.' WHERE id_product='.(int)($id));
			$insert = Db::getInstance()->Execute('
					INSERT INTO  `'._DB_PREFIX_.'specific_price`
					(`id_product`,`id_shop`,`from_quantity`,`reduction`, `from`, `to`, `reduction_type`, `price`)
					VALUES ('.(int)($id).','.(int)($this->getIDMagasin()).',1,'.($reduction/100).', "'.$debut.'", "'.$fin.'", "percentage", "-1")');
		}
		$sql=Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'dompromo` WHERE id_venteflash='.(int)($id));
	}

	// Remet tout à 0 avant désinstallation du module.
	function uninstalldompromo() {
		$query = Db::getInstance()->ExecuteS('
    		SELECT id_venteflash 
        FROM  `'._DB_PREFIX_.'dompromo`
        ');
		if ($query) {
			foreach ($query AS $enr) {
				$sql=Db::getInstance()->Execute('
        		DELETE FROM `'._DB_PREFIX_.'specific_price`
            WHERE id_product='.(int)($enr['id_venteflash']).'
            AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) 
            AND `from_quantity`<=1');
			}
		}
	}


	// Recherche si le produit existe.
	function checkProductId($id)
	{
		$nb_reg  = Db::getInstance()->getValue('
    		SELECT  count(id_product) FROM `'._DB_PREFIX_.'product`
        WHERE  `id_product` = '.(int)($id));
		if ($nb_reg == '0')
			return FALSE;
		else
			return TRUE;
	}

	// Recherche si le produit existe et est actif
	function isActiveProduct($id)
	{
		$nb_reg  = Db::getInstance()->getValue('
    		SELECT  count(id_product) FROM `'._DB_PREFIX_.'product`
        WHERE `id_product` = '.(int)($id).'
        AND `active` =1
        ');
		if ($nb_reg  == '0')
			return FALSE;
		else
			return TRUE;
	}

	// Recherche si le produit a une réduction de prix dans la table ps_specific_price
	function reductionPrice($id) {
		$nb_reg = Db::getInstance()->getValue('
    		SELECT  count(id_specific_price) FROM `'._DB_PREFIX_.'specific_price`
        WHERE `id_product` = '.(int)($id).'
        AND `to` < "'.date('Y-m-d H:i:s').'"
        AND (`id_shop`='.(int)($this->getIDMagasin()).' OR `id_shop`=0) 
        AND `from_quantity`<=1');
		if ($nb_reg  == '0')
			return FALSE;
		else
			return TRUE;
	}

	public function getTypePricesDrop($id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL, $beginning = false, $ending = false, $type = false)
	{
		global $link, $cookie;
		if (!Validate::isBool($count))
			die(Tools::displayError());

		if ($type != false) {
			if ($type == 'vf') $addSql = 'AND vf.typesale = 1 ';
			else if ($type == 'cp') $addSql = 'AND vf.`typesale` = 2 ';
			else if ($type == 'rs') $addSql = 'AND vf.`typesale` = 3 ';
			else if ($type == 'pr') $addSql = 'AND p.`on_sale` = 0 AND p.`id_product` not in (select vf2.`id_venteflash` from `'._DB_PREFIX_.'dompromo` vf2) ';
			else if ($type == 'sl') $addSql = 'AND p.`on_sale` = 1 ';
		}

		if ($pageNumber < 0) $pageNumber = 0;
		if ($nbProducts < 1) $nbProducts = 10;
		if (empty($orderBy) || $orderBy == 'position') $orderBy = 'myprice';
		if (empty($orderWay)) $orderWay = 'DESC';
		if ($orderBy == 'id_product' OR $orderBy == 'price' OR $orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name') $orderByPrefix = 'pl';

		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());

		if ($count) {
			$sql = '
					SELECT COUNT(DISTINCT p.`id_product`) AS nb
					FROM `'._DB_PREFIX_.'product` p
					LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product` AND (sp.`id_shop`='.(int)($this->getIDMagasin()).' OR sp.`id_shop`=0) AND sp.reduction_type = \'percentage\' AND sp.`from_quantity`<=1)
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
					LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
					LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
    	    LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`		
					WHERE sp.`reduction` > 0 ';
			if ($type != false) $sql .= $addSql;
			$sql .= '
					AND (sp.`from` <= \''.date('Y-m-d H:i:s').'\' AND sp.`to` >= \''.date('Y-m-d H:i:s').'\')
					AND sp.from_quantity <= 1 
					AND p.`active` = 1
					AND p.`id_product` IN (
					SELECT cp.`id_product`
					FROM `'._DB_PREFIX_.'category_group` cg
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
					WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.(int)($cookie->id_customer).')').'
				)';
			$result = Db::getInstance()->getRow($sql);
			return intval($result['nb']);
		}

		$sql = '
				SELECT DISTINCT vf.*, p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, 
				pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`, i.`id_image`, il.`legend`, t.`rate`, 
				(p.`price` - (vf.`vfreduction` * p.`price` / 100)) AS myprice, m.`name` AS manufacturer_name, 
				(sp.reduction*100) as reduction_percent, sp.from, sp.to, sp.reduction_type, pa.id_product_attribute
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product` AND (sp.`id_shop`='.(int)($this->getIDMagasin()).' OR sp.`id_shop`=0) AND sp.reduction_type = \'percentage\' AND sp.`from_quantity`<=1)
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = p.`id_tax_rules_group`)
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product` AND pa.`default_on` = 1) 
        LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`		
				WHERE sp.`reduction` > 0 ';
		if ($type != false) $sql .= $addSql;
		$sql .= '
				AND (sp.`from` <= \''.date('Y-m-d H:i:s').'\' AND sp.`to` >= \''.date('Y-m-d H:i:s').'\')
				AND sp.from_quantity <= 1 
				AND p.`active` = 1
				AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.(int)($cookie->id_customer).')').'
				)
				ORDER BY '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.$orderBy.'` '.$orderWay.'
				LIMIT '.(int)($pageNumber * $nbProducts).', '.(int)($nbProducts);
		$result = Db::getInstance()->ExecuteS($sql);
		if($orderBy == 'price')
			Tools::orderbyPrice($result,$orderWay);
		if (!$result)
			return false;
		return Product::getProductsProperties($id_lang, $result);
	}

	// Affichage des produits de vente flash à venir.
	public function commingSoonVF($id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL)
	{
		global $link, $cookie;
		if (!Validate::isBool($count))
			die(Tools::displayError());

		if ($pageNumber < 0) $pageNumber = 0;
		if ($nbProducts < 1) $nbProducts = 10;
		if (empty($orderBy) || $orderBy == 'position') $orderBy = 'myprice';
		if (empty($orderWay)) $orderWay = 'DESC';
		if ($orderBy == 'id_product' OR $orderBy == 'price' OR $orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());

		$offset = time()+Configuration::get('DOM_PROMO_OFFSET');
		$currentDate = date('Y-m-d H:i:s',$offset);
		if ($count)
		{
			$sql = '
					SELECT COUNT(DISTINCT p.`id_product`) AS nb
					FROM `'._DB_PREFIX_.'product` p
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
					LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
					LEFT JOIN `'._DB_PREFIX_.'tax` t ON ( t.`id_tax` = p.`id_tax_rules_group` )
					LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
    	    LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`				  
					WHERE vf.`vfreduction` > 0 
					AND vf.`datedebut` > "'.$currentDate.'" AND vf.`datefin` > vf.`datedebut` AND vf.`datedebut` > "'.$currentDate.'"
					AND p.`active` = 1
					AND p.`id_product` IN (
					SELECT cp.`id_product`
					FROM `'._DB_PREFIX_.'category_group` cg
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
					WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
				)';
			$result = Db::getInstance()->getRow($sql);
			return intval($result['nb']);
		}

		$offset = time() + Configuration::get('DOM_PROMO_OFFSET');
		$currentDate = date("Y-m-d H:i:s", $offset);
		$sql = '
				SELECT DISTINCT vf.*, p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, 
				pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`, i.`id_image`, il.`legend`, t.`rate`, 
				(p.`price` - (vf.`vfreduction` * p.`price` / 100)) AS myprice, m.`name` AS manufacturer_name
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON ( t.`id_tax` = p.`id_tax_rules_group` )
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
        LEFT JOIN `'._DB_PREFIX_.'dompromo` vf ON vf.`id_venteflash` = p.`id_product`		
				WHERE vf.`vfreduction` > 0 
				AND vf.`datedebut` > "'.$currentDate.'" AND vf.`datefin` > vf.`datedebut` AND vf.`datedebut` > "'.$currentDate.'"
				AND p.`active` = 1
				AND p.`id_product` IN (
				SELECT cp.`id_product`
				FROM `'._DB_PREFIX_.'category_group` cg
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
				WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
				)
				ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'`'.' '.pSQL($orderWay).'
				LIMIT '.intval($pageNumber * $nbProducts).', '.intval($nbProducts);
		$result = Db::getInstance()->ExecuteS($sql);

		if($orderBy == 'price')
			Tools::orderbyPrice($result,$orderWay);
		if (!$result)
			return false;
		return Product::getProductsProperties($id_lang, $result);
	}

	public function showVentesFlash() {
		global $smarty;
		$MDParameters = array();
		$MDParameters = $this->getParameters();
		if (Configuration::get('DOM_PROMO_NOREFRESHAUTO') == 1) $jscount = "countdown2.js";
		else $jscount = "countdown.js";
		$smarty->assign(array(
			'ps_version' => _PS_VERSION_,
			'timer'      => '<script type="text/javascript" src="'.$this->_path.'js/'.$jscount.'"></script>',
			'vfd'        => $this->l('d'),
			'vfh'        => $this->l('h'),
			'vfm'        => $this->l('m'),
			'vfs'        => $this->l('s'),
			'flashtexttimeColor' => $MDParameters[0]['flashtexttimeColor'],
			'flashtimeColor' => $MDParameters[0]['flashtimeColor'],
			'moduleinfo' => $this->name.' - '.$this->version.' - '.$this->serial.' - '.$this->l('author:').' '.$this->author
		));
		return ;
	}

	public function showCommingSoonVentesFlash() {
		global $smarty;
		$MDParameters = array();
		$MDParameters = $this->getParameters();
		if (Configuration::get('DOM_PROMO_NOREFRESHAUTO') == 1) $jscount = "countdown2.js";
		else $jscount = "countdown.js";
		$smarty->assign(array(
			'timer'      => '<script type="text/javascript" src="'.$this->_path.'js/'.$jscount.'"></script>',
			'vfd'        => $this->l('d'),
			'vfh'        => $this->l('h'),
			'vfm'        => $this->l('m'),
			'vfs'        => $this->l('s'),
			'commingtextstartColor' => $MDParameters[0]['commingtextstartColor'],
			'commingdateColor' => $MDParameters[0]['commingdateColor'],
			'commingtextfinishColor' => $MDParameters[0]['commingtextfinishColor'],
			'moduleinfo' => $this->name.' - '.$this->version.' - '.$this->serial.' - '.$this->l('author:').' '.$this->author
		));
		return ;
	}

	private function chercheMaj() {
		$fichier_xml = 'http://maj.aideaunet.com/xml/dominfo.xml';
		$domMessage = '';
		// On test l'existance du fichier xml.
		if (@fclose(@fopen($fichier_xml, 'r'))) {
			$raw = file_get_contents($fichier_xml); // Lit le fichier et le place dans la variable $raw
			if ($raw) {
				// On lit la zone de "dominfo".
				if(preg_match('#<aideaunet>(.*)</aideaunet>#is',$raw,$rawdominfo)){
					if(preg_match('#<domPromoLight2>(.*)</domPromoLight2>#is',$rawdominfo[1],$rawinfo)){
						preg_match('#<version>(.*)</version>#is',$rawinfo[1], $versioninfo);
						preg_match('#<url>(.*)</url>#is',$rawinfo[1], $url);
						preg_match('#<majauto>(.*)</majauto>#is',$rawinfo[1], $majauto);
					}
					if ($this->version < $versioninfo[1]) {
						// Version inférieure, on informe l'utilisateur.
						$domMessage .= '<fieldset><legend><img src="../img/admin/module_warning.png" />'.$this->l('Update Available').'</legend>';
						$domMessage .= '<p>'.$this->l('Update module').' "'.$nom_Module.'" '.$this->l('Available:').'</p><p>';
						$domMessage .= '<p>&nbsp;&nbsp;&nbsp; - '.$this->l('Your version:').' '.$this->version;
						$domMessage .= '<br />&nbsp;&nbsp;&nbsp; - '.$this->l('Version available:').' '.$versioninfo[1].'<br />&nbsp;</p>';
						$domMessage .= $this->l('To perform the update, download the latest version on the following link:').' <a href="'.$url[1].'">http://www.aideaunet.com</a></p>';
						if($majauto[1] == 1) {
							$domMessage .= '<p>'.$this->l('Experimental: Automatic Update module').' ==> <a href="'._MODULE_DIR_.strtolower($this->name).'/maj/maj.php?module=domPromoLight2&majauto=1&refere='.$_SERVER['SERVER_NAME'].'&addml='.Configuration::get('PS_SHOP_EMAIL').'">'.$this->l('Click here').'</a> ('.$this->l('Requires write permissions on the directory module.').')</p>';
						}
						// On ajoute les autres informations disponibles.
						if(preg_match("#<nbreinfo>(.*)</nbreinfo>#is",$rawinfo[1], $nbreinfo)){
							if(intval($nbreinfo[1]) > 0) {
								//preg_match("#<infos>(.*)</infos>#is",$rawinfo[1], $infos); 
								$texte = explode("<infos>", $rawinfo[0]);
								for ($i=1;$i<$nbreinfo[1]+1;$i++) {
									preg_match("#<info_util>(.*)</info_util>#is",$texte[$i], $rawinfoutil);
									$domMessage .= "<blockquote>".$rawinfoutil[1]."</blockquote>";
								}
							}
						}
						$domMessage .= '</fieldset><p>&nbsp;</p>';
					}
				}
			}
		}
		return $domMessage;
	}

}
?>