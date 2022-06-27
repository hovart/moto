<?php
/**
 * hook.conf.php file defines all needed constants and variables for hook context
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2015 Business Tech SARL
 */

require_once(dirname(__FILE__) . '/common.conf.php');

/* defines hook library path */
define('_GSR_PATH_LIB_HOOK', _GSR_PATH_LIB . 'hook/');

/* defines hook tpl path */
define('_GSR_TPL_HOOK_PATH', 'hook/');

/* defines front tpl path */
define('_GSR_TPL_FRONT_PATH', 'front/');

/* defines generic tpl */
define('_GSR_TPL_GENERIC', 'generic-hook.tpl');

/* defines header tpl */
define('_GSR_TPL_HEADER', 'header.tpl');

/* defines empty tpl */
define('_GSR_TPL_EMPTY', 'empty.tpl');

/* defines reviews tpl */
define('_GSR_TPL_REVIEWS', 'product-review-block.tpl');

/* defines reviews list tpl */
define('_GSR_TPL_REVIEW_LIST', 'review-list.tpl');

/* defines product tab tpl */
define('_GSR_TPL_PRODUCT_TAB', 'product-tab.tpl');

/* defines product tab content tpl */
define('_GSR_TPL_PRODUCT_TAB_CTN', 'product-tab-content.tpl');

/* defines product review form tpl */
define('_GSR_TPL_PROD_REVIEW_FORM', 'product-review-form.tpl');

/* defines module front controller review form tpl */
define('_GSR_TPL_REVIEW_FORM', 'review-form.tpl');

/* defines review display */
define('_GSR_TPL_REVIEW_DISPLAY', 'review-display.tpl');

/* efines last reviews block display */
define('_GSR_TPL_LAST_REVIEWS_BLOCK', 'block-last-reviews.tpl');

/* defines order confirmation tpl */
define('_GSR_TPL_ORDER_CONFIRMATION', 'post-order-confirmation.tpl');

/* defines customer account tpl */
define('_GSR_TPL_CUST_ACCOUNT', 'customer-account.tpl');

/* defines my account tpl */
define('_GSR_TPL_MY_ACCOUNT', 'my-account.tpl');

/* defines update callback tpl */
define('_GSR_TPL_POST_CBK', 'post-callback.tpl');

/* defines post popin tpl  */
define('_GSR_TPL_POST_POPIN', 'post-review-popin.tpl');

/* defines post product snippets */
define('_GSR_TPL_PROD_SNIPPETS', 'snippets-product.tpl');

/* defines post hp snippets and badge tpl */
define('_GSR_TPL_HP_SNIPPETS', 'snippets-hp.tpl');

/* defines post category snippets and badge tpl */
define('_GSR_TPL_CAT_SNIPPETS', 'snippets-category.tpl');

/* defines post reviews snippets */
define('_GSR_TPL_REVIEW_SNIPPETS', 'snippets-review.tpl');

/* defines review list */
define('_GSR_TPL_REVIEW_PAGE_LIST', 'review-page-list.tpl');

/* defines review report */
define('_GSR_TPL_REVIEW_REPORT', 'review-report.tpl');

/* defines post report popin tpl */
define('_GSR_TPL_POST_REPORT_POPIN', 'post-report-popin.tpl');

/* defines product tab id */
define('_GSR_PRODUCT_TAB_ID', 9999);

/* defines jquery rating script name */
define('_GSR_SINGLE_REVIEW_IMG_SIZE', (version_compare(_PS_VERSION_, '1.7', '>=')? ImageType::getFormattedName('large') : ImageType::getFormatedName('large')));

/* defines loader gif name */
define('_GSR_LOADER_GIF', 'loader.gif');

/* defines loader gif name */
define('_GSR_LOADER_GIF_BG', 'loader-lg.gif');

/* defines quote image width */
define('_GSR_IMG_QUOTE_WIDTH', 13);

/* defines quote image height */
define('_GSR_IMG_QUOTE_HEIGHT', 10);

/* defines variable for matching lang ref between FB / twitter to prestashop */
$GLOBALS[_GSR_MODULE_NAME . '_REF_LANG'] = array(
	'en' => array('FB' => 'en_US', 'TWITTER' => 'en'),
	'fr' => array('FB' => 'fr_FR', 'TWITTER' => 'fr'),
	'es' => array('FB' => 'es_LA', 'TWITTER' => 'es'),
	'de' => array('FB' => 'de_DE', 'TWITTER' => 'de'),
	'it' => array('FB' => 'it_IT', 'TWITTER' => 'it'),
	'zh' => array('FB' => 'zh_CN', 'TWITTER' => 'zh-cn'),
	'tw' => array('FB' => 'zh_TW', 'TWITTER' => 'zh-tw'),
	'cs' => array('FB' => 'cs_CZ', 'TWITTER' => 'en'),
	'nl' => array('FB' => 'nl_NL', 'TWITTER' => 'nl'),
	'ja' => array('FB' => 'ja_JP', 'TWITTER' => 'ja'),
	'pl' => array('FB' => 'pl_PL', 'TWITTER' => 'pl'),
	'pt' => array('FB' => 'pt_PT', 'TWITTER' => 'pt'),
	'ru' => array('FB' => 'ru_RU', 'TWITTER' => 'ru'),
	'sv' => array('FB' => 'sv_SE', 'TWITTER' => 'sv'),
);

/* defines variable for setting all request params */
$GLOBALS[_GSR_MODULE_NAME . '_REQUEST_PARAMS'] = array(
	'account' => array('action' => 'display', 'type' => 'account'),
	'status' => array('action' => 'update', 'type' => 'account'),
	'popinFB' => array('action' => 'display', 'type' => 'popinFB'),
	'reviewForm' => array('action' => 'display', 'type' => 'review'),
	'postReview' => array('action' => 'post', 'type' => 'review'),
	'reportForm' => array('action' => 'display', 'type' => 'report'),
	'reportReview' => array('action' => 'report', 'type' => 'review'),
	'standalone' => array('action' => 'display', 'type' => 'standalone'),
	'reply' => array('action' => 'update', 'type' => 'review'),
);