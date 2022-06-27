<?php 

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/mailchimp.php');


$oMailChimp = new MailChimp();
echo $oMailChimp->addNewUsersToDefaultList();
?>