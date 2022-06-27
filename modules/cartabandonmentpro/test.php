<?php
include_once('../../config/config.inc.php');
include_once(dirname(__FILE__).'/cartabandonmentpro.php');
$cartab = new CartAbandonmentPro();
$cartab->testMail();