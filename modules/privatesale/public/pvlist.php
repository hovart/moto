<?php

require(dirname(__FILE__).'../../../../config/config.inc.php');

require ('../privatesale.php');
//require ('pvlist_ctrl.php');
//ControllerFactory::getController('PvlistController')->run();

// init front controller in order to use Tools::redirect
$controller = new FrontController();
$controller->init();

Tools::redirect(Context::getContext()->link->getModuleLink('privatesale', 'pvlist', array("register" => $_GET['register'])));