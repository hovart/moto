<?php
/**
 * facebook-ctrl_class.php file defines all method to manage data for facebook posts
 */

class BT_FacebookCtrl
{
	/**
	 * Magic Method __construct assigns few information about hook
	 */
	private function __construct(){
		// include
		require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{

	}

	/**
	 * isFbPostExists() method check if a Fb post has already posted
	 *
	 * @param int $iReviewId
	 * @param bool $bByRating
	 * @return bool
	 */
	public function isFbPostExists($iReviewId, $bByRating = false)
	{
		return (
			BT_Review::create()->isFbPostExist($iReviewId, $bByRating)
		);
	}


	/**
	 * updateAndSharePost() method send FB post and update and share it
	 *
	 * @param int $iReviewId
	 * @return array
	 */
	public function updateAndSharePost($iReviewId)
	{
		$aFbIds = array();

		// post review in FB page
		$mFBResponse = $this->sendPost($iReviewId);

		// get json decode Fb result
		$oFBResponse = BT_GsrModuleTools::jsonDecode($mFBResponse);

		// use case - id exists
		if (isset($oFBResponse->id) && false !== strstr($oFBResponse->id, '_')) {
			// split and get second part
			$aFbId = explode('_', $oFBResponse->id);

			// store only post id
			$aFbIds['page'] = $aFbId[0];
			$aFbIds['post'] = $aFbId[1];

			// stock returned post id
			$mReturn = BT_Review::create()->update($iReviewId, array('fbPageId' => $aFbId[0], 'fbPostId' => $aFbId[1]));

			unset($aFbId);
		}
		unset($oFBResponse);
		unset($mFBResponse);

		return $aFbIds;
	}

	/**
	 * sendPost() method send to FB wall post through FB PS Wall Posts module
	 *
	 * @param int $iReviewId
	 * @return mixed : false or json string
	 */
	public function sendPost($iReviewId)
	{
		$mReturn = '';

		// use case - Facebook wall post is well installed
		$oModule = BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'], true);

		if (is_object($oModule)) {
			// get review
			$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $iReviewId, 'customer' => true));

			if (!empty($aReview)) {
				// get rating
				$aReview['rating'] = BT_ReviewCtrl::create()->run('getRatings', array('id' => $aReview['ratingId']));

				if (!empty($aReview['rating'][0])) {
					$aReview['rating'] = $aReview['rating'][0];
					// instantiate product
					$oProduct = new Product($aReview['productId'], true, GSnippetsReviews::$iCurrentLang);

					// use case - validate obj
					if (Validate::isLoadedObject($oProduct)) {
						// require module-dao class
						require_once(_GSR_PATH_LIB . 'module-dao_class.php');

						$aProduct = array();

						// use case - get Image
						$aImage = Image::getCover($oProduct->id);

						// get image url
						$aProduct['imgUrl'] = !empty($aImage)? Context::getContext()->link->getImageLink($oProduct->link_rewrite, $oProduct->id . '-' . $aImage['id_image'], Configuration::get('FBWALLPOSTS_IMG_SIZE')) : '';

						// use case - get valid IMG URI under prestashop 1.4
						$aProduct['imgUrl'] = BT_GsrModuleTools::detectHttpUri($aProduct['imgUrl']);

						// merge
						$aProduct = array_merge(
							$aProduct,
							BT_GsrModuleDao::getProduct($oProduct->id)
						);
						// use case - check description use
						$aProduct['description'] = BT_GsrModuleTools::manageProductDesc($aProduct);

						// get url
						$aProduct['url'] = Context::getContext()->link->getProductLink($oProduct);

						// get FB phrase
						$aPhrase = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_PHRASE']);
						$aLabel = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_FB_POST_LABEL']);

						// set lang id
						$iLangId = !isset($aPhrase[GSnippetsReviews::$iCurrentLang])? 1 : GSnippetsReviews::$iCurrentLang;

						// format text msg for FB
						$sMsg = $aReview['firstname'] . ' ' . substr(ucfirst($aReview['lastname']), 0, 1)
							.	'. ' . $aPhrase[$iLangId] . ' : ';

						// format stars rating
						$iCount = 0;
						for ($iCount = 1; $iCount <= $aReview['rating']['note']; ++$iCount) {
							$sMsg .= _GSR_FBWP_STAR_FULL;
						}
						// get difference
						$iDiff = _GSR_MAX_RATING - $aReview['rating']['note'];

						if ($iDiff) {
							for ($iCount = 1; $iCount <= $iDiff; ++$iCount) {
								$sMsg .= _GSR_FBWP_STAR_EMPTY;
							}
						}
						$sMsg .= "\n"
							. $aLabel[$iLangId] . ' : ' . "\n"
							. $aReview['data']['sTitle'] . "\n"
							. $aReview['data']['sComment'] . "\n"
						;

						// execute wall post
						$mReturn = $oModule->createFBPost($sMsg, $oProduct->name, $aProduct['url'], $aProduct['description'], $aProduct['imgUrl']);

						unset($oModule);
						unset($oProduct);
						unset($aProduct);
						unset($aImage);
					}
				}
			}
		}
		return $mReturn;
	}

	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oFbCtrl;

		if (null === $oFbCtrl) {
			$oFbCtrl = new BT_FacebookCtrl();
		}
		return $oFbCtrl;
	}
}