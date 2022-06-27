<?php

require(dirname(__FILE__).'../../../../config/config.inc.php');

require ('../privatesale.php');
//require ('pvprods_ctrl.php');
//ControllerFactory::getController('PvCatProdsController')->run();

$controller = new FrontController();
$controller->init();
//d($_GET['id']);
Tools::redirect(Context::getContext()->link->getModuleLink('privatesale', 'pvprods', array("id" => $_GET['id'])));

?>