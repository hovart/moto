<?php
/**
 * ws-gsnippetsreviews.php file execute module for Front Office
 */

require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');
require_once(dirname(__FILE__) . '/gsnippetsreviews.php');

/* get type of content to display */
$sAction = Tools::getIsset('sAction') ? Tools::getValue('sAction') : '';
$sType = Tools::getIsset('sType') ? Tools::getValue('sType') : '';

/* instantiate */
$oModule = new GSnippetsReviews();

$sUseCase = $sAction . $sType;

switch ($sUseCase) {
	case 'displayaccount' :
		/* test for customer login */
		if (GSnippetsReviews::$oCookie->id_customer) {
			Tools::redirect('authentication.php?back=' . BT_GsrModuleTools::detectHttpUri($_SERVER['REQUEST_URI']));
		}
		/* display header */
		include(dirname(__FILE__) . '/../../header.php');

		/* display Customer account's reviews */
		echo $oModule->hookCustomerAccount(array_merge($_POST, array('display' => 'review')));

		/* display footer */
		include(dirname(__FILE__) . '/../../footer.php');
		break;
	case 'updateaccount' :
		/* use only without XHR mode */
		if (Tools::getValue('sMode') == 'xhr') {
			/* update customer status */
			echo $oModule->hookCustomerReminderStatus($_POST);
		}
		break;
	case 'displaystandalone' :
		/* display header */
		include(dirname(__FILE__) . '/../../header.php');

		echo $oModule->hookReviewStandalone($_POST);

		/* display footer */
		include(dirname(__FILE__) . '/../../footer.php');
		break;
	case 'displaypopinFB' :
		/* display Facebook share post popin */
		echo $oModule->hookPopinFb(array_merge($_POST, $_GET, array('display' => 'popinFb')));
		break;
	case 'displayaverage' :
		/* display rating product in product list page */
		echo $oModule->hookProductRating(array_merge($_POST, $_GET, array('display' => 'productRating')));
		break;
	case 'displayreview' :
		/* display review form */
		echo $oModule->hookReviewForm($_POST);
		break;
	case 'postreview' :
		/* post a review */
		echo $oModule->hookPostReview($_POST);
		break;
	case 'displayreport' :
		/* display review report form */
		echo $oModule->hookReportForm($_POST);
		break;
	case 'reportreview' :
		/* report a review */
		echo $oModule->hookReportReview($_POST);
		break;
	case 'updatereview' :
		/* update review by the customer with review litigation reply feature */
		echo $oModule->hookUpdateReview($_POST);
		break;
	default:
		break;
}