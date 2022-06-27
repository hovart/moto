<?php
/**
 * admin.conf.php file defines all needed constants and variables for admin context
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2015 Business Tech SARL
 */

require_once(dirname(__FILE__) . '/common.conf.php');

/* defines modules support product id */
define('_GSR_SUPPORT_ID', '6144');

/* defines activate the BT support if false we use the ADDONS support url */
define('_GSR_SUPPORT_BT', false);

/* defines activate the BT support if false we use the ADDONS support url */
define('_GSR_SUPPORT_URL', 'https://addons.prestashop.com/');
//define('_GSR_SUPPORT_URL', 'http://www.businesstech.fr/');

/* defines admin library path */
define('_GSR_PATH_LIB_ADMIN', _GSR_PATH_LIB . 'admin/');

/* defines reviews tool path tpl */
define('_GSR_TPL_REVIEWS_TOOL_PATH', _GSR_TPL_ADMIN_PATH . 'review-tool/');

/* defines header tpl */
define('_GSR_TPL_HEADER', 'header.tpl');

/* defines body tpl */
define('_GSR_TPL_BODY', 'body.tpl');

/* defines welcome settings tpl */
define('_GSR_TPL_WELCOME', 'welcome.tpl');

/* defines prerequisites check tpl */
define('_GSR_TPL_PREREQUISITES_CHECK', 'prerequisites-check.tpl');

/* defines rich snippets settings tpl */
define('_GSR_TPL_SNIPPETS_SETTINGS', 'snippets-settings.tpl');

/* defines reviews settings tpl */
define('_GSR_TPL_REVIEWS_SETTINGS', 'reviews-settings.tpl');

/* defines email reviews settings tpl */
define('_GSR_TPL_EMAIL_REVIEWS', 'reviews-email-settings.tpl');

/* defines reviews moderation settings tpl */
define('_GSR_TPL_REVIEW_MODERATION', 'review-moderation.tpl');

/* defines review body tpl */
define('_GSR_TPL_REVIEW_BODY', 'body.tpl');

/* defines reviews status settings tpl */
define('_GSR_TPL_REVIEW_STATUS', 'review-status.tpl');

/* defines review form tpl */
define('_GSR_TPL_REVIEW_FORM', 'review-form.tpl');

/* defines reply form tpl */
define('_GSR_TPL_REPLY_FORM', 'reply-form.tpl');

/* defines review update tpl  */
define('_GSR_TPL_REVIEWS_UPDATE', 'reviews-update.tpl');

/* defines review update tpl */
define('_GSR_TPL_REVIEW_UPDATE', 'review-update.tpl');

/* defines reply update tpl */
define('_GSR_TPL_REPLY_UPDATE', 'reply-update.tpl');

/* defines FB reviews settings tpl */
define('_GSR_TPL_FB_REVIEWS_SETTINGS', 'facebook-reviews-settings.tpl');

/* defines FB vouchers settings tpl */
define('_GSR_TPL_FB_VOUCHERS_SETTINGS', 'vouchers-settings.tpl');

/* defines FB vouchers form tpl */
define('_GSR_TPL_FB_VOUCHERS_FORM', 'vouchers-form.tpl');

/* defines cron tpl */
define('_GSR_TPL_CRON', 'cron.tpl');

/* defines cron report tpl */
define('_GSR_TPL_CRON_REPORT', 'cron-report.tpl');

/* defines review e-mail notification tpl */
define('_GSR_TPL_REVIEW_NOTIFICATION', 'review-notification.tpl');

/* defines reviews import tpl */
define('_GSR_TPL_REVIEWS_IMPORT', 'reviews-import.tpl');

/* defines orders import update tpl */
define('_GSR_TPL_ORDERS_UPDATE', 'orders-update.tpl');

/* defines orders import tpl */
define('_GSR_TPL_ORDERS_IMPORT', 'orders-import.tpl');

/* defines voucher's update sql file */
define('_GSR_VOUCHER_SQL_FILE', 'update-voucher-fb.sql');

/* defines after-sales' update sql file */
define('_GSR_AFTERSALES_SQL_FILE', 'update-aftersales.sql');

/* defines lang's update sql file */
define('_GSR_LANG_REVIEW_SQL_FILE', 'update-lang-review.sql');

/* defines lang's update sql file */
define('_GSR_DATE_RATING_SQL_FILE', 'update-date-rating.sql');

/* defines abusive report sql file */
define('_GSR_ABUSIVE_REPORT_SQL_FILE', 'update-abusive-report.sql');

/* defines orders history sql file */
define('_GSR_ORDERS_HISTORY_SQL_FILE', 'update-orders-history.sql');

/* defines tpl mail name for callback */
define('_GSR_TPL_MAIL_CALLBACK', 'customer-callback');

/* defines tpl review add */
define('_GSR_TPL_REVIEW_ADD', 'review-add.tpl');

/* defines tpl review add confirmation */
define('_GSR_TPL_REVIEW_CONFIRM', 'review-confirm.tpl');

/* defines review update tpl */
define('_GSR_CALLBACK_INTERVAL', 86400); /* set in sec for unix timestamp */

/* defines constant for external BT API URL */
define('_GSR_BT_API_MAIN_URL', 'https://api.businesstech.fr:441/prestashop-modules/');

/* defines constant for external BT API URL */
define('_GSR_BT_FAQ_MAIN_URL', 'http://faq.businesstech.fr/');

/* defines constant for external Google Rich Snippets tool */
define('_GSR_GOOGLE_SNIPPETS_TOOL', 'https://search.google.com/structured-data/testing-tool');

/* defines loader gif name  */
define('_GSR_LOADER_GIF', 'loader.gif');

/* defines loader large gif name */
define('_GSR_LOADER_GIF_BIG', 'loader-lg.gif');

/* defines constant for activating MOCK reviews import mode */
define('_GSR_MOCK_IMPORT_DEBUG', 0);

/* defines array of  reviews' test for importing from "comments product" module */
$GLOBALS[_GSR_MODULE_NAME . '_MOCK_IMPORT'] = array(
	array(
		'id_product_comment' => 1,
		'id_product' => 2,
		'id_customer' => 2,
		'id_guest' => 0,
		'title' => 'test product comment',
		'content' => 'test product comment in english',
		'customer_name' => 'Thomas Businesstech',
		'grade' => 4,
		'validate' => 1,
		'deleted' => 0,
		'date_add' => '2014-12-04 12:04:24',
	),
	array(
	'id_product_comment' => 2,
		'id_product' => 1,
		'id_customer' => 40,
		'id_guest' => 0,
		'title' => 'test product comment',
		'content' => 'test product comment not happy in english too',
		'customer_name' => '',
		'grade' => 2,
		'validate' => 1,
		'deleted' => 0,
		'date_add' => '2014-12-04 12:06:10'
	),
	array(
		'id_product_comment' => 3,
		'id_product' => 7,
		'id_customer' => 2,
		'id_guest' => 0,
		'title' => 'test without rating in french',
		'content' => 'test without rating in french',
		'customer_name' => 'Thomas Businesstech',
		'grade' => 0,
		'validate' => 0,
		'deleted' => 0,
		'date_add' => '2014-12-04 12:07:38',
	),
	array(
		'id_product_comment' => 4,
		'id_product' => 3,
		'id_customer' => 2,
		'id_guest' => 0,
		'title' => 'test with rating in french',
		'content' => '',
		'customer_name' => 'Thomas Businesstech',
		'grade' => 4,
		'validate' => 0,
		'deleted' => 0,
		'date_add' => '2014-12-04 12:08:51',
	),
);

/* defines variable for setting number of reviews displayed by page */
$GLOBALS[_GSR_MODULE_NAME . '_NB_REVIEWS_VALUES'] = array(3,5,10,15,20,50,100,200);

/* defines variable for stars in list page to set the padding-left value */
$GLOBALS[_GSR_MODULE_NAME . '_STAR_PADDING_VALUES'] = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);

/* defines variable for stars sizes in list page to set the star size css class */
$GLOBALS[_GSR_MODULE_NAME . '_STAR_SIZE_VALUES'] = array('090' => 0.9,'091' => 0.91,'092' => 0.92,'093' => 0.93,'094' => 0.94,'095' => 0.95,'096' => 0.96,'097' => 0.97,'098' => 0.98,'099' => 0.99,'100' => 1.00,'101' => 1.01,'102' => 1.02,'103' => 1.03,'104' => 1.04,'105' => 1.05,'106' => 1.06,'107' => 1.07,'108' => 1.08,'109' => 1.09,'110' => 1.10,'111' => 1.11,'112' => 1.12,'113' => 1.13,'114' => 1.14,'115' => 1.15,'116' => 1.16,'117' => 1.17,'118' => 1.18,'119' => 1.19,'120' => 1.20);

/* defines variable for text sizes in list page to set the text size css class */
$GLOBALS[_GSR_MODULE_NAME . '_TEXT_SIZE_VALUES'] = array(8,9,10,11,12,13,14,15,16,17,18);

/* defines variable for sortable fields of moderation */
$GLOBALS[_GSR_MODULE_NAME . '_SORTABLE_FIELDS'] = array('shop', 'customer','product','dateAdd','dateUpd','status', 'id', 'abuse', 'ranking');

/* defines variable for sql update */
$GLOBALS[_GSR_MODULE_NAME . '_SQL_UPDATE'] = array(
	'table' => array(
		'voucher' => _GSR_VOUCHER_SQL_FILE,
		'aftersales' => _GSR_AFTERSALES_SQL_FILE,
		'ordershistory' => _GSR_ORDERS_HISTORY_SQL_FILE,
	),
	'field' => array(
		'RTG_LANG_ID' => array('table' => 'rating', 'file' => _GSR_LANG_REVIEW_SQL_FILE),
		'RTG_DATE_ADD' => array('table' => 'rating', 'file' => _GSR_DATE_RATING_SQL_FILE),
		'RPT_REPORT_CUST_DATA' => array('table' => 'review_report', 'file' => _GSR_ABUSIVE_REPORT_SQL_FILE),
	)
);

/* defines variable for setting all request params */
$GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'] = array(
	'snippets' => array('action' => 'update', 'type' => 'snippets'),
	'reviews' => array('action' => 'update', 'type' => 'reviews'),
	'reviewsFB' => array('action' => 'update', 'type' => 'facebookReviews'),
	'addReview' => array('action' => 'update', 'type' => 'review'),
	'email' => array('action' => 'update', 'type' => 'email'),
	'vouchers' => array('action' => 'update', 'type' => 'vouchers'),
	'moderation' => array('action' => 'update', 'type' => 'oneStatus'),
	'moderationAll' => array('action' => 'update', 'type' => 'activate'),
	'abuse' => array('action' => 'update', 'type' => 'abuse'),
	'moderationSort' => array('action' => 'display', 'type' => 'tabs'),
	'review' => array('action' => 'display', 'type' => 'reviewForm'),
	'reply' => array('action' => 'display', 'type' => 'replyForm'),
	'moderationComment' => array('action' => 'update', 'type' => 'comment'),
	'moderationReply' => array('action' => 'update', 'type' => 'reply'),
	'delete' => array('action' => 'delete', 'type' => 'review'),
	'import' => array('action' => 'update', 'type' => 'comments'),
	'selectOrders' => array('action' => 'update', 'type' => 'orders'),
	'displayOrders' => array('action' => 'display', 'type' => 'ordersSelect'),
	'standalone' => array('action' => 'display', 'type' => 'standalone'),
);