<?php

class DompromoDefaultModuleFrontController extends ModuleFrontController {
	
	public function __construct() {
		parent::__construct();
		$this->context = Context::getContext();
	}
		
	public function initContent()	{
		parent::initContent();

		if (Tools::getValue('process') == 'flashsales') 
			$this->processFlashsales();
		elseif (Tools::getValue('process') == 'commingsoon') 
			$this->processCommingsoon();		
	}
	
	
	public function processFlashsales() {
		//setlocale(LC_TIME, "French_France.1252", "fr_FR", "fr_FR.iso-8859-1", "fr_FR.utf-8", "french",'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
		setlocale (LC_TIME, 'fr_FR.utf8','fr'); 
		include_once($this->module->getLocalPath().'dompromo.php');

		$typePromo = $_GET['type'];
		if ($typePromo != "") {
			if ($typePromo == 'vf') $titre = "Flash Sale";
			elseif ($typePromo == 'cp') $titre = "Coutant Price";
			elseif ($typePromo == 'rs') $titre = "Reduction of Stocks";
			elseif ($typePromo == 'pr') $titre = "Price drop";
			elseif ($typePromo == 'sl') $titre = "On sale!";
		} else {
			$titre = "Specials";
		}

		$dompromo = new dompromo();
		$this->productSort();
		$nbProducts = $dompromo->getTypePricesDrop((int)($this->context->cookie->id_lang), NULL, NULL, true, false, false, false, false, $typePromo);
		$this->pagination((int)$nbProducts);

		if (isset($_GET['orderby'])) $orderBy = $_GET['orderby']; else $orderBy = "position";
		if (isset($_GET['orderway'])) $orderWay = $_GET['orderway']; else $orderWay = "asc";
		if (isset($_GET['n'])) $n = $_GET['n']; else $n = Configuration::get('PS_PRODUCTS_PER_PAGE');
		if (isset($_GET['p'])) $p = $_GET['p'];
		
		$tailleimg = 'home';
		if (_PS_VERSION_ >= '1.5.1') $tailleimg .= '_default';
			
		$this->context->smarty->assign(array(
			'products' 						=> $dompromo->getTypePricesDrop((int)($this->context->cookie->id_lang), intval($p) - 1, intval($n), false, $orderBy, $orderWay, false, false, $typePromo),
			'titre' 							=> $titre,
			'titre' 							=> $titre,
			'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM')
			));

		echo $dompromo->showVentesFlash();	
		$this->setTemplate('flashsales.tpl');
	}
	
	
	public function processCommingsoon() {
		setlocale (LC_TIME, 'fr_FR.utf8','fr'); 
		include_once($this->module->getLocalPath().'dompromo.php');

		$dompromo = new dompromo();
		$this->productSort();
		$nbProducts = $dompromo->commingSoonVF((int)($this->context->cookie->id_lang), NULL, NULL, true);
		$this->pagination((int)$nbProducts);

		if (isset($_GET['orderby'])) $orderBy = $_GET['orderby']; else $orderBy = "position";
		if (isset($_GET['orderway'])) $orderWay = $_GET['orderway']; else $orderWay = "asc";
		if (isset($_GET['n'])) $n = $_GET['n']; else $n = Configuration::get('PS_PRODUCTS_PER_PAGE');
		if (isset($_GET['p'])) $p = $_GET['p'];

		$tailleimg = 'home';
		if (_PS_VERSION_ >= '1.5.1') $tailleimg .= '_default';
		
		global $smarty; $cookie;
		$smarty->assign(array(
			'products' 		=>$dompromo->commingSoonVF((int)($this->context->cookie->id_lang), intval($p) - 1, intval($n), false, $orderBy, $orderWay),
			'titre' 			=> $titre,
			'nb_Products' => $nbProducts
			));

		echo $dompromo->showCommingSoonVentesFlash();
		$this->setTemplate('commingsoon.tpl');
	}
	
}
