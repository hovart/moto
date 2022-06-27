<?php
/**	
 *	domPromo : Module pour site sous PrestaShop.
 *	Gestion des Promotions, ventes flash, Déstockage, Soldes et déstockage.
 *	
 *	Version	2.0.5
 * 	Pour Prestashop 1.5.x
 *
 *	Copyright Dominique PAUL.
 * 	Site de l'auteur : http://www.aideaunet.com
 *  
 *	Les scripts PHP de ce module sont sous Copyright.
 *  La modification des scripts de ce module est strictement INTERDITE.
 *
 *  Seules les scripts TPL (scripts de thèmes) et CSS (feuilles de style) sont autorisés à modification.
 *
 * 	Ce module est en téléchargement libre sur le site de l'auteur,
 * 	La distribution de ce module est INTERDITE sur tout autre support sans accord préalable de l'auteur.
 *
 **/

class AdminDomPromoController extends ModuleAdminController {
	public function __construct() {
		$this->lang = true;
		$this->deleted = false;
		$this->context = Context::getContext();
		parent::__construct();
	}
	

	/**
	 * Function used to render the list to display for this controller
	*/
	public function renderList() {
		$module = new dompromo;
    return $module->getContent();
	}	
	
	public function renderForm() {
		return parent::renderForm();
	}
	
}
