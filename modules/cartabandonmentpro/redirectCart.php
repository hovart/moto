<?php
include_once('../../config/config.inc.php');
$token = Tools::getValue('token');
$token_bdd = Configuration::get('CARTABAND_TOKEN');
$wichRemind = Tools::getValue('wichRemind');
$id_cart = Tools::getValue('id_cart');
$link = Tools::getValue('link');

if($token != $token_bdd) Tools::redirect();

include_once('controllers/RedirectController.class.php');
$context = Context::getContext();

$cart = new Cart((int)Tools::getValue('id_cart'));
$cart->secure_key = Tools::getValue('secure_key');

$cartInfos = Db::getInstance()->getRow('SELECT cu.id_customer, cu.passwd, cu.email 
    FROM '._DB_PREFIX_.'cart ca JOIN '._DB_PREFIX_.'customer cu ON (cu.id_customer = ca.id_cart)
    WHERE ca.id_cart = '.(int)Tools::getValue('id_cart'));

$context->cookie->__set('id_cart', Tools::getValue('id_cart'));
$context->cookie->__set('logged', 1);
$context->cookie->__set('passwd', $cartInfos['passwd']);
$context->cookie->__set('email', $cartInfos['email']);
$context->cookie->__set('id_customer', $cartInfos['id_customer']);
$context->customer = new Customer($cartInfos['id_customer']);

$context->cart = new Cart(Tools::getValue('id_cart'));

$controller = new RedirectController();
$controller->redirect();