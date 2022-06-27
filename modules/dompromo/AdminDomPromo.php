<?php
/**	
 *	domPromo : Module pour site sous PrestaShop.
 *	Gestion des Promotions, ventes flash, Déstockage, Soldes et déstockage.
 *	
 *	Version	2.0.1
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
 
// include_once(_PS_ROOT_DIR_.'/classes/AdminTab.php');
include_once(_PS_MODULE_DIR_.'/dompromo/dompromo.php');

class AdminDomPromo extends AdminTab
{
  private $module = 'dompromo';

  public function __construct()
  {
    global $cookie, $_LANGADM;
    $langFile = _PS_MODULE_DIR_.$this->module.'/'.Language::getIsoById(intval($cookie->id_lang)).'.php';
    if(file_exists($langFile))
    {
      require_once $langFile;
      foreach($_MODULE as $key=>$value)
        if(substr(strip_tags($key), 0, 5) == 'Admin')
          $_LANGADM[str_replace('_', '', strip_tags($key))] = $value;
    }
    parent::__construct();
  }

  public function display()
  {
    $module = new dompromo;
    echo $module->getContent();
  }
}
?>
