<?php  

class DamienudryController extends FrontController
{

public function init(){

    parent::init();
}
public $php_self = 'damienudry';
public function initContent()
  {
   parent::initContent();
   $this->context->smarty->assign('content_only', 0);
   $this->setTemplate(_PS_THEME_DIR_.'damienudry.tpl');
  }

  public function setMedia() {

    parent::setMedia();
      $this->addCSS(array(
        _THEME_DIR_.'damienudry/css/damienudry.css',
        _THEME_DIR_.'damienudry/css/dcsns_wall.css'
      ));
      $this->addJS(array(
        _THEME_DIR_.'damienudry/js/damienudry.js'
      ));
  }

/**
  * Assign template vars related to page content
  * @see FrontController::initContent()
  */

}



?>

