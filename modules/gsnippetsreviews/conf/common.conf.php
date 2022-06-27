<?php
/**
 * common.conf.php file defines all needed constants and variables for all context of using module - install / admin / hook / tab
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2015 Business Tech SARL
 */

/* defines constant of module name */
define('_GSR_MODULE_NAME', 'GSR');

/* defines set module name */
define('_GSR_MODULE_SET_NAME', 'gsnippetsreviews');

/* defines root path of module */
define('_GSR_PATH_ROOT', _PS_MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/');

/* defines conf path */
define('_GSR_PATH_CONF', _GSR_PATH_ROOT . 'conf/');

/* defines libraries path */
define('_GSR_PATH_LIB', _GSR_PATH_ROOT . 'lib/');

/* defines sql path */
define('_GSR_PATH_SQL', _GSR_PATH_ROOT . 'sql/');

/* defines common library path */
define('_GSR_PATH_LIB_COMMON', _GSR_PATH_LIB . 'common/');

/* defines views folder */
define('_GSR_PATH_VIEWS', 'views/');

/* defines reviews library path */
define('_GSR_PATH_LIB_REVIEWS', _GSR_PATH_LIB . 'reviews/');

/* defines vouchers library path */
define('_GSR_PATH_LIB_VOUCHER', _GSR_PATH_LIB . 'voucher/');

/* defines mails path */
define('_GSR_PATH_MAILS', _GSR_PATH_ROOT . 'mails/');

/* defines js URL  */
define('_GSR_URL_JS', _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/' . _GSR_PATH_VIEWS . 'js/');

/* defines css URL */
define('_GSR_URL_CSS', _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/' . _GSR_PATH_VIEWS . 'css/');

/* defines MODULE URL */
define('_GSR_MODULE_URL', _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/');

/* defines img path */
define('_GSR_PATH_IMG', 'img/');

/* defines img URL */
define('_GSR_URL_IMG', _MODULE_DIR_ . _GSR_MODULE_SET_NAME . '/' . _GSR_PATH_VIEWS . _GSR_PATH_IMG);

/*  defines tpl path name */
define('_GSR_PATH_TPL_NAME', _GSR_PATH_VIEWS . 'templates/');

/* defines tpl path */
define('_GSR_PATH_TPL', _GSR_PATH_ROOT . _GSR_PATH_TPL_NAME);

/* defines admin path tpl */
define('_GSR_TPL_ADMIN_PATH', 'admin/');

/* defines constant of error tpl */
define('_GSR_TPL_ERROR', 'error.tpl');

/* defines confirm tpl */
define('_GSR_TPL_CONFIRM', 'confirm.tpl');

/* defines jquery rating script name */
define('_GSR_JQUERY_RATING_NAME', 'jquery.star-rating');

/* defines constant for displaying post in FB */
define('_GSR_FB_URL', 'http://www.facebook.com/');

/* defines tpl mail name for merchant notification */
define('_GSR_TPL_MAIL_NOTIF_M', 'merchant-notification');

/* defines tpl mail name for merchant notification  */
define('_GSR_TPL_MAIL_NOTIF_ASM', 'litigation-merchant-notification');

/* defines tpl mail name for report notification */
define('_GSR_TPL_MAIL_NOTIF_R', 'report-notification');

/* defines tpl mail name for after-sales e-mail notification */
define('_GSR_TPL_MAIL_NOTIF_AS', 'reply-notification');

/* defines tpl mail name for customer notification */
define('_GSR_TPL_MAIL_NOTIF_C', 'customer-notification');

/* defines tpl mail name for voucher notification */
define('_GSR_TPL_MAIL_NOTIF_V', 'voucher-notification');

/* defines admin logs path */
define('_GSR_PATH_LOGS', _GSR_PATH_ROOT . 'logs/');

/* defines Callbacks log file */
define('_GSR_CBK_LOGS', 'log-callbacks-shop');

/* defines Callbacks log file extension */
define('_GSR_CBK_LOGS_EXT', '.txt');

/* defines max rating for review */
define('_GSR_MAX_RATING', 5);

/* defines activate / deactivate debug mode */
define('_GSR_DEBUG', false);

/* defines constant to use or not js on submit action */
define('_GSR_USE_JS', true);

/* defines constants to use for posting rating on Facebook¨using Facebook Wall Posts module */
define('_GSR_FBWP_STAR_EMPTY', '☆');
define('_GSR_FBWP_STAR_FULL', '★');

/* defines constant to use facebookwallpost module's name */
define('_GSR_FBWP_NAME', 'facebookpswallposts');

/* defines variable for admin ctrl name */
define('_GSR_ADMIN_CTRL', 'admin');

/* defines variable for admin review ctrl name */
define('_GSR_ADMIN_REVIEW_CTRL', 'reviewTool');

/* defines variable for admin review ctrl name */
define('_GSR_PARAM_CTRL_NAME', 'sController');

/* defines variable for front module controller of single review */
define('_GSR_FRONT_CTRL_REVIEW', 'review');

/* defines variable for front module controller of single review */
define('_GSR_FRONT_CTRL_REVIEW_FORM', 'form');

/* defines variable for front module controller of reviews list page */
define('_GSR_FRONT_CTRL_REVIEWS', 'reviews');

/* defines variable for front module controller of customer account page */
define('_GSR_FRONT_CTRL_ACCOUNT', 'account');

/* defines variable for picto name (starts or thumbs or others) */
define('_GSR_PICTO_NAME', 'picto.png');

/* defines variable for mandatory keys of FB Ps wall post module */
$GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'] = array('FBWALLPOSTS_PAGE_ID', 'FBWALLPOSTS_PAGE_AUTH_TOKEN');

/* defines variable for setting configuration options */
$GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION'] = array(
	_GSR_MODULE_NAME . '_MODULE_VERSION' => '4.1.3',
	_GSR_MODULE_NAME . '_DISPLAY_PROD_RS' => 0,
	_GSR_MODULE_NAME . '_SORT_DESC' => '',
	_GSR_MODULE_NAME . '_DISPLAY_PROD_DESC' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_BRAND' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_CAT' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_BREADCRUMB' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_IDENTIFIER' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_SUPPLIER' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_COND' => 0,
	_GSR_MODULE_NAME . '_PRODUCT_OFFERS' => 'offer',
	_GSR_MODULE_NAME . '_DISPLAY_PROD_HIGH_PRICE' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_OFFER_COUNT' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_UNTIL_DATE' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_SELLER' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_PROD_AVAILABILITY' => 0,
	_GSR_MODULE_NAME . '_PROD_RATING' => 1,
	_GSR_MODULE_NAME . '_PROD_RVW_DATE' => 1,
	_GSR_MODULE_NAME . '_PROD_RVW_TITLE' => 1,
	_GSR_MODULE_NAME . '_PROD_RVW_DESC' => 1,
	_GSR_MODULE_NAME . '_PROD_RVW_AGGREGATE' => 0,
	_GSR_MODULE_NAME . '_RVW_TYPE' => 'aggregate',
	_GSR_MODULE_NAME . '_DISPLAY_BADGE' => 1,
	_GSR_MODULE_NAME . '_BADGES' => '',
	_GSR_MODULE_NAME . '_DISPLAY_REVIEWS' => 1,
	_GSR_MODULE_NAME . '_ENABLE_RATINGS' => 1,
	_GSR_MODULE_NAME . '_ENABLE_COMMENTS' => 1,
	_GSR_MODULE_NAME . '_FORCE_COMMENTS' => 0,
	_GSR_MODULE_NAME . '_COMMENTS_APPROVAL' => 1,
	_GSR_MODULE_NAME . '_COMMENTS_USER' => 'buyer',
	_GSR_MODULE_NAME . '_DISPLAY_SOCIAL_BUTTON' => 1,
	_GSR_MODULE_NAME . '_COUNT_BOX_BUTTON' => 1,
	_GSR_MODULE_NAME . '_FB_BUTTON_TYPE' => 2,
	_GSR_MODULE_NAME . '_RVW_PROD_IMG' => (version_compare(_PS_VERSION_, '1.7', '>=')? ImageType::getFormattedName('large') : ImageType::getFormatedName('large')),
	_GSR_MODULE_NAME . '_RVW_LIST_PROD_IMG' => (version_compare(_PS_VERSION_, '1.7', '>=')? ImageType::getFormattedName('small') : ImageType::getFormatedName('small')),
	_GSR_MODULE_NAME . '_SLIDER_PROD_IMG' => (version_compare(_PS_VERSION_, '1.7', '>=')? ImageType::getFormattedName('large') : ImageType::getFormatedName('large')),
	_GSR_MODULE_NAME . '_PICTO' => '1-star-yellow',
	_GSR_MODULE_NAME . '_USE_FONTAWESOME' => (version_compare(_PS_VERSION_, '1.7', '>=')? 1 : 0),
	_GSR_MODULE_NAME . '_HOOK' => 'displayProductButtons',
	_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE' => 'classic',
	_GSR_MODULE_NAME . '_ENABLE_RVW_CUST_LANG' => 1,
	_GSR_MODULE_NAME . '_NB_REVIEWS_PROD_PAGE' => 3,
	_GSR_MODULE_NAME . '_NB_REVIEWS_PAGE' => 50,
	_GSR_MODULE_NAME . '_DISPLAY_PHOTO_REVIEWS' => 0,
	_GSR_MODULE_NAME . '_DISPLAY_REPORT_BUTTON' => 1,
	_GSR_MODULE_NAME . '_DISPLAY_ADDRESS' => 1,
	_GSR_MODULE_NAME . '_NB_REVIEWS_MODERATION' => 5,
	_GSR_MODULE_NAME . '_DISP_EMPTY_RATING' => 0,
	_GSR_MODULE_NAME . '_DISP_BEFIRST_MSG' => 0,
	_GSR_MODULE_NAME . '_BEFIRST_SENTENCE' => '',
	_GSR_MODULE_NAME . '_DISP_STAR_RATING_MODE' => 2,
	_GSR_MODULE_NAME . '_NB_PROD_SLIDER' => 200,
	_GSR_MODULE_NAME . '_NB_PROD_REVIEWED' => 200,
	_GSR_MODULE_NAME . '_SLIDER_WIDTH' => 800,
	_GSR_MODULE_NAME . '_SLIDER_SPEED' => 1000,
	_GSR_MODULE_NAME . '_SLIDER_PAUSE' => 5000,
	_GSR_MODULE_NAME . '_DISPLAY_LAST_RVW_BLOCK' => 1,
	_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_HOOK' => 'displayHome',
	_GSR_MODULE_NAME . '_NB_LAST_REVIEWS' => 3,
	_GSR_MODULE_NAME . '_LAST_RVW_BLOCK' => '',
	_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_FIRST' => 1,
	_GSR_MODULE_NAME . '_DISPLAY_HOOK_REVIEW_STARS' => 1,
	_GSR_MODULE_NAME . '_EMAIL_SUBJECT' => '',
	_GSR_MODULE_NAME . '_RVW_EMAIL_SUBJECT' => '',
	_GSR_MODULE_NAME . '_REPLY_EMAIL_SUBJECT' => '',
	_GSR_MODULE_NAME . '_REPLY_EMAIL_TEXT' => '',
	_GSR_MODULE_NAME . '_MAIL_PROD_IMG' => (version_compare(_PS_VERSION_, '1.7', '>=')? ImageType::getFormattedName('small') : ImageType::getFormatedName('small')),
	_GSR_MODULE_NAME . '_ENABLE_REMINDER_MAIL_CC' => 0,
	_GSR_MODULE_NAME . '_REMINDER_MAIL_CC' => '',
	_GSR_MODULE_NAME . '_REMINDER_MAIL_CAT_LABEL' => '',
	_GSR_MODULE_NAME . '_REMINDER_MAIL_PROD_LABEL' => '',
	_GSR_MODULE_NAME . '_REMINDER_MAIL_SENTENCE' => '',
	_GSR_MODULE_NAME . '_REMINDER_SUBJECT' => '',
	_GSR_MODULE_NAME . '_ENABLE_EMAIL' => 0,
	_GSR_MODULE_NAME . '_ENABLE_RVW_EMAIL' => 1,
	_GSR_MODULE_NAME . '_EMAIL' => '',
	_GSR_MODULE_NAME . '_CRON_SECURE_KEY' => '',
	_GSR_MODULE_NAME . '_EMAIL_DELAY' => 7,
	_GSR_MODULE_NAME . '_ENABLE_CALLBACK' => 0,
	_GSR_MODULE_NAME . '_ORDERS_IMPORT' => 0,
	_GSR_MODULE_NAME . '_STATUS_SELECTION' => serialize(array(4)),
	_GSR_MODULE_NAME . '_ENABLE_VOUCHERS' => '',
	_GSR_MODULE_NAME . '_VOUCHERS_SETTINGS' => '',
	_GSR_MODULE_NAME . '_ENABLE_FB_POST' => 0,
	_GSR_MODULE_NAME . '_FB_POST_PHRASE' => '',
	_GSR_MODULE_NAME . '_FB_POST_LABEL' => '',
	_GSR_MODULE_NAME . '_COMMENTS_IMPORT' => 0,
	_GSR_MODULE_NAME . '_COMMENTS_IMPORT_TYPE' => 2,
	_GSR_MODULE_NAME . '_SNIPPETS_PRODLIST' => 0,
	_GSR_MODULE_NAME . '_HAS_SNIPPETS_PRODLIST' => 0,
	_GSR_MODULE_NAME . '_STARS_PADDING_LEFT' => 0,
	_GSR_MODULE_NAME . '_STARS_SIZE' => '115',
	_GSR_MODULE_NAME . '_TEXT_SIZE' => 11,
);

/* defines variable for setting hooks */
$GLOBALS[_GSR_MODULE_NAME . '_HOOKS'] = array(
	'displayHeader' => array('name' => 'displayHeader' , 'use' => false, 'title' => 'Header'),
	'displayRightColumnProduct' => array('name' => 'displayRightColumnProduct', 'use' => false, 'title' => '', 'position' => 'productRight'),
	'displayLeftColumnProduct' => array('name' => 'displayLeftColumnProduct', 'use' => false, 'title' => '', 'position' => 'productLeft'),
	'displayProductButtons' => array('name' => 'displayProductButtons', 'use' => false, 'title' => '', 'position' => 'productAction'),
	'displayFooterProduct' => array('name' => 'displayFooterProduct', 'use' => false, 'title' => '', 'position' => 'productBottom'),
	'displayProductListReviews' => array('name' => 'displayProductListReviews', 'use' => false, 'title' => ''),
	'displayProductTab' => array('name' => 'displayProductTab', 'use' => false, 'title' => ''),
	'displayProductTabContent' => array('name' => 'displayProductTabContent', 'use' => false, 'title' => ''),
	'displayOrderConfirmation' => array('name' => 'displayOrderConfirmation', 'use' => false, 'title' => ''),
	'displayCustomerAccount' => array('name' => 'displayCustomerAccount', 'use' => false, 'title' => ''),
	'actionValidateOrder' => array('name' => 'actionValidateOrder', 'use' => false, 'title' => ''),
	'displayTop' => array('name' => 'displayTop', 'use' => false, 'title' => 'Top'),
	'displayHome' => array('name' => 'displayHome', 'use' => false, 'title' => 'Home'),
	'displayFooter' => array('name' => 'displayFooter', 'use' => false, 'title' => 'Footer'),
	'displayLeftColumn' => array('name' => 'displayLeftColumn', 'use' => false, 'title' => 'Left column'),
	'displayRightColumn' => array('name' => 'displayRightColumn', 'use' => false, 'title' => 'Right column'),
	'actionProductDelete' => array('name' => 'actionProductDelete' , 'use' => false, 'title' => 'Delete'),
);

/* defines hooks for reviews block in product page */
$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_HOOKS'] = array('displayLeftColumnProduct','displayRightColumnProduct', 'displayProductButtons', 'displayFooterProduct');

/* defines variable for available slider's options */
$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE'] = array('classic' => '', 'tabs' => '', 'bootstrap' => '');

if (version_compare(_PS_VERSION_, '1.7', '>=')) {
	$GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION'][_GSR_MODULE_NAME . '_IMPORT_BOOTSTRAP'] = 1;
	$GLOBALS[_GSR_MODULE_NAME . '_HOOKS']['displayReassurance'] = array('name' => 'displayReassurance', 'use' => false, 'title' => '', 'position' => 'productReassurance');
	$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_HOOKS'][] = 'displayReassurance';
	$GLOBALS[_GSR_MODULE_NAME . '_REVIEWS_DISPLAY_MODE']['tabs17'] = '';
}

/* defines variable for setting admin tab title */
$GLOBALS[_GSR_MODULE_NAME . '_TABS'] = array(
	'AdminModerationTool' => array(
		'active' => true,
		'lang' => array(
			'en' => 'Reviews Moderation',
			'fr' => 'Modération Avis',
			'de' => 'Moderation Bewertungen',
			'it' => 'Moderazione Recensioni',
			'es' => 'Comentarios de Moderación',
		),
		'parent' => (version_compare(_PS_VERSION_, '1.7', '>=')? 'AdminParentCustomerThreads' : 'AdminParentModules'),
		'oldName'=> 'AdminGSnippetsReviews'
	),
);

/* defines variable for default translation of sentence and label of FB post */
$GLOBALS[_GSR_MODULE_NAME . '_FB_DEFAULT_TRANSLATE'] = array(
	'en' => array('sentence' => 'rated this product on our shop', 'label' => 'Review'),
	'fr' => array('sentence' => 'a noté ce produit sur notre boutique', 'label' => 'Avis'),
	'de' => array('sentence' => 'hat dieses Produkt auf unser Shop bewertet', 'label' => 'Sehen'),
	'it' => array('sentence' => 'ha valutato questo prodotto sul nostro negozio', 'label' => 'Vista'),
	'es' => array('sentence' => 'ha calificado este producto en nuestra tienda', 'label' => 'Ver'),	
);

/* defines variable for default translation of sentence and label of FB post */
$GLOBALS[_GSR_MODULE_NAME . '_NOTIFICATION_DEFAULT_TRANSLATE'] = array(
	'en' => 'Your review has been published',
	'fr' => 'Votre avis a été publié',
	'de' => 'Ihre Bewertung wurde veröffentlicht',
	'it' => 'Il tuo commento è stato pubblicato',
	'es' => 'Su revisión ha sido publicada',
);

/* defines variable for default translation of reply e-mail subject */
$GLOBALS[_GSR_MODULE_NAME . '_REPLY_DEFAULT_TRANSLATE'] = array(
	'en' => 'The shop owner has replied to your product review',
	'fr' => 'Le propriétaire de la boutique a répondu à votre avis produit',
	'de' => 'sie eine After-Sales-Antwort erhalten',
	'it' => 'ha ricevuto un messaggio servizio post-vendita',
	'es' => 'a recibido un comentario servicio post-venta',
);

/* defines variable for default translation of reply e-mail text */
$GLOBALS[_GSR_MODULE_NAME . '_REPLY_TEXT_DEFAULT_TRANSLATE'] = array(
	'en' => 'Thank you for your product review on our website. We always welcome feedback, whether it is positive or negative. However, in this particular case, we feel that this review was unfair and would like to have a chance to invite you to change your mind. Here is why: ',
	'fr' => 'Merci pour votre avis sur notre boutique. Nous sommes toujours heureux des retours clients, positifs ou négatifs. Cependant, dans ce cas particulier, nous pensons que celui-ci est non-fondé et nous aimerions avoir une chance de vous faire changer d\'avis. Voici pourquoi :',
	'de' => 'Thank you for your product review on our website. We always welcome feedback, whether it is positive or negative. However, in this particular case, we feel that this review was unfair and would like to have a chance to invite you to change your mind. Here is why:',
	'it' => 'Thank you for your product review on our website. We always welcome feedback, whether it is positive or negative. However, in this particular case, we feel that this review was unfair and would like to have a chance to invite you to change your mind. Here is why:',
	'es' => 'Thank you for your product review on our website. We always welcome feedback, whether it is positive or negative. However, in this particular case, we feel that this review was unfair and would like to have a chance to invite you to change your mind. Here is why:',
);

/* defines variable for default translation of sentence and label of reminders */
$GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_TRANSLATE'] = array(
	'en' => 'are you satisfied with your order on our shop',
	'fr' => 'êtes-vous satisfait de votre commande sur notre boutique',
	'de' => 'Sind Sie mit Ihrer Bestellung in unserem Shop zufrieden',
	'it' => 'Sei soddisfatto del tuo ordine nel nostro negozio',
	'es' => '¿Está satisfecho con su pedido en nuestra tienda',
);

/* defines variable for default translation of sentence and label of reminders */
$GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_CAT_LABEL'] = array(
	'en' => 'Category',
	'fr' => 'Catégorie',
	'de' => 'Kategorie',
	'it' => 'Categoria',
	'es' => 'Categoría',
);

/* defines variable for default translation of sentence and label of reminders */
$GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_PROD_LABEL'] = array(
	'en' => 'Product',
	'fr' => 'Produit',
	'de' => 'Produkte',
	'it' => 'Prodotto',
	'es' => 'Producto',
);

/* defines variable for default translation of sentence and label of reminders */
$GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_SENTENCE'] = array(
	'en' => 'Rate / post a review for this product by clicking on one of the stars below (1 to 5)',
	'fr' => 'Noter / poster un avis pour ce produit en cliquant sur l\'une des étoiles dessous de 1 à 5',
	'de' => 'Bewerten / Geben Sie einen Meinung für diesen Produkt, indem auf den Sterne klicken (1 to 5)',
	'it' => 'Valutare / pubblicare una recensione cliccando sulle stelle (1 to 5)',
	'es' => 'Escribir / publicar una opinión para este producto en (1 to 5)',
);

/* defines variable for default translation of category used in breadcrumbs */
$GLOBALS[_GSR_MODULE_NAME . '_CATEGORY_DEFAULT_TRANSLATE'] = array(
	'en' => 'product',
	'fr' => 'produits',
	'de' => 'produkte',
	'it' => 'prodotti',
	'es' => 'productos',
);

/* defines variable for default translation of tax label used in email templates */
$GLOBALS[_GSR_MODULE_NAME . '_LABEL_TAX_DEFAULT_TRANSLATE'] = array(
	'en' => array('Tax Excluded','Tax Included'),
	'fr' => array('H.T','TTC'),
	'de' => array('Ohne Steuer','Steuern Inklusive'),
	'it' => array('IVA esclusa','Tasse Incluse'),
	'es' => array('Sin Impuestos','Impuesto Incluido'),
);

/* defines variable for default translation of the message displayed with empty stars on the product list page */
$GLOBALS[_GSR_MODULE_NAME . '_BEFIRST_DEFAULT_TRANSLATE'] = array(
	'en' => 'Be first to review',
	'fr' => 'Soyez le premier à noter',
	'de' => 'Schreiben Sie die erste Bewertung',
	'it' => 'Puoi essere il primo a commentare',
	'es' => 'Sé el primero en comentar',
);

/* defines variable for badge pages */
$GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS'] = array(
	'openForm' => array('name' => 'open', 'value' => true),
	'addReview' => array('name' => 'post', 'value' => true),
);

/* defines variable for sort desc order  */
$GLOBALS[_GSR_MODULE_NAME . '_SORT_DESC'] = array(
	'short' => '',
	'long' => '',
	'meta' => '',
);

/* defines variable for authorized person */
$GLOBALS[_GSR_MODULE_NAME . '_AUTHORIZE'] = array(
	'buyer' => '',
	'registered' => '',
);

/* defines variable for moderation vouchers type : review posted / review shared in FB */
$GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE'] = array('comment' => array('active' => true, 'title' => ''), 'share' => array('active' => true, 'title' => ''));

/* defines variable for badge pages */
$GLOBALS[_GSR_MODULE_NAME . '_BADGE_PAGES'] = array(
	'home' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'home', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'wizard', 'title' => ''))),
	'category' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''), array('position' => 'wizard', 'title' => ''))),
	'manufacturer' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''), array('position' => 'wizard', 'title' => ''))),
	'product' => array('use' => false, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''), array('position' => 'wizard', 'title' => ''))),
);

/* defines variable for displayed badge styles */
$GLOBALS[_GSR_MODULE_NAME . '_BADGE_STYLES'] = array(
	'home' => '', 'top' => '', 'bottom' => '', 'colLeft' => '', 'colRight' => '', 'wizard' => '',
);

/* defines variable for displayed last review block positions */
$GLOBALS[_GSR_MODULE_NAME . '_AVAILABLE_BLOCK_POS'] = array(
	'home' => '', 'top' => '', 'bottom' => '', 'colLeft' => '', 'colRight' => '',
);

/* defines variable for last reviews block pages */
$GLOBALS[_GSR_MODULE_NAME . '_LAST_RVW_BLOCK_PAGES'] = array(
	'home' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'home', 'title' => ''),array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''))),
	'category' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''))),
	'manufacturer' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''))),
	'product' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''))),
	'other' => array('use' => true, 'title' => '', 'allow' => array(array('position' => 'top', 'title' => ''), array('position' => 'bottom', 'title' => ''), array('position' => 'colLeft', 'title' => ''), array('position' => 'colRight', 'title' => ''))),
);
/*  defines variable for related fields in joined tables */
$GLOBALS[_GSR_MODULE_NAME . '_RELATED_SQL_FIELDS'] = array(
	'productlang' => array(
		array('field' => 'pl.name as name', 'use' => true),
	),
	'customer' => array(
		array('field' => 'c.firstname as firstname', 'use' => true),
		array('field' => 'c.lastname as lastname', 'use' => true),
		array('field' => 'c.email as email', 'use' => true),
	),
	'shop' => array(
		array('field' => 's.name as shopName', 'use' => true),
	),
);

/* defines variable for translating js msg */
$GLOBALS[_GSR_MODULE_NAME . '_JS_MSG'] = array();

/* defines variable for available serialized keys */
$GLOBALS[_GSR_MODULE_NAME . '_SERIALIZED_KEYS'] = array('sTitle', 'sComment', 'iLangId', 'sLangIso','sOldTitle','sOldComment','iOldRating','bReply','iCounter');

/* defines variable for setting all request params */
$GLOBALS[_GSR_MODULE_NAME . '_MONTH'] = array(
	'en' => array(
		'short' => array('','Jan.','Feb.','March','Apr.','May','June','July','Aug.','Sept.','Oct.','Nov.','Dec.'),
		'long' => array('','January','February','March','April','May','June','July','August','September','October','November','December'),
	),
	'fr' => array(
		'short' => array('','Jan.','Fév.','Mars','Avr.','Mai','Juin','Juil.','Aout','Sept.','Oct.','Nov.','Déc.'),
		'long' => array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre'),
	),
	'de' => array(
		'short' => array('','Jan.','Feb.','M' . chr(132) . 'rz','Apr.','Mai','Juni','Juli','Aug.','Sept.','Okt.','Nov.','Dez.'),
		'long' => array('','Januar','Februar','M' . chr(132) . 'rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'),
	),
	'it' => array(
		'short' => array('','Gen.','Feb.','Marzo','Apr.','Mag.','Giu.','Lug.','Ago.','Sett.','Ott.','Nov.','Dic.'),
		'long' => array('','Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'),
	),
	'es' => array(
		'short' => array('','Ene.','Feb.','Marzo','Abr.','Mayo','Junio','Jul.','Ago.','Sept.','Oct.','Nov.','Dic.'),
		'long' => array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
	),
);

/* defines variable for available slider's options */
$GLOBALS[_GSR_MODULE_NAME . '_SLIDER_OPTS'] = array(
	'width' => array('100','150','200','250','300','350','400','450','500','550','600','650','700','750','800','850','900','950','1000'),
	'speed' => array('500'=> '0.5','1000' => '1','2000' => '2','3000' => '3','4000' => '4','5000' => '5','6000' => '6','7000' => '7','8000' => '8','9000' => '9','10000' => '10'),
	'pause' => array('1000' => '1','2000' => '2','3000' => '3','4000' => '4','5000' => '5','6000' => '6','7000' => '7','8000' => '8','9000' => '9','10000' => '10'),
);