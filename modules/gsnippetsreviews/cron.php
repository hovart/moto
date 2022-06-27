<?php
/**
 * cron.php file execute cron task for sending emails of purchased products
 */

require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');
require_once(dirname(__FILE__) . '/gsnippetsreviews.php');

$oModule = new GSnippetsReviews();

$sGetKey = Tools::getValue('bt_key');
$sSecureKey = GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_CRON_SECURE_KEY'];

if ($sGetKey == $sSecureKey) {
	/* use case - send action */
	$_POST['sAction'] = 'send';
	/* email type */
	$_POST['sType'] = 'callback';

	echo $oModule->getContent();
}
else {
	echo GSnippetsReviews::$oModule->l('Internal server error! (security error)', 'cron');
}