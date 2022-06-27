<?php
/**
 * mail-send_class.php file defines all method to send email
 */

class BT_GsrMailSend
{
	/**
	 * @var array $_aLANGUAGES : stock languages
	 */
	static protected $_aLANGUAGES = null;

	/**
	 * @var bool $bProcess : define if process or not
	 */
	protected $bProcess = null;

	/**
	 * Magic Method __construct assigns few information about hook
	 *
	 * @param string
	 */
	public function __construct(){
		if (null === self::$_aLANGUAGES) {
			$aLanguages = Language::getLanguages(true);

			foreach ($aLanguages as $aLanguage) {
				self::$_aLANGUAGES[] = $aLanguage['id_lang'];
			}
		}
	}

	/**
	 * Magic Method __destruct
	 */
	public function __destruct()
	{
	
	}

	/**
	 * run() method execute hook
	 *
	 * @param string $sType
	 * @param array $aParams
	 * @return bool
	 */
	public function run($sType, array $aParams)
	{
		$bSend = false;
		$this->bProcess = false;

		switch ($sType) {
			case 'customerNotification' : // use case - send a customer notification
				// check if emails folder are added - there is no option for this kind of sending
				$aLangErrors = BT_GsrModuleTools::checkMailLanguages();

				if (empty($aLangErrors)) {
					$aParams = $this->processCustomerNotification($aParams);
				}
				break;
			case 'voucherNotification' : // use case - send a voucher notification to the customer
				// check if emails folder are added - there is no option for this kind of sending
				$aLangErrors = BT_GsrModuleTools::checkMailLanguages();

				if (empty($aLangErrors)) {
					$aParams = $this->processVoucherNotification($aParams);
				}
				break;
			case 'replyNotification' : // use case - send a notification to the customer as review litigation reply
				$aParams = $this->processReplyNotification($aParams);
				break;
			case 'merchantNotification' : // use case - send a notification to the merchant
				$aParams = $this->processMerchantNotification($aParams);
				break;
			case 'aftersalesMerchantNotification' : // use case - send a notification to the merchant when a customer change his review
				$aParams = $this->processAftersalesMerchantNotification($aParams);
				break;
			case 'reviewReport' : // use case - send a notification to the merchant for an abuse
				$aParams = $this->processReviewReport($aParams);
				break;
			case 'callback' : // use case - send to customers a callback for rating products
				$aParams = $this->processCallback($aParams);
				break;
			default :
				break;
		}

		// use case - only if process true
		if ($this->bProcess) {
			// set iso template mail
			if (is_dir(_GSR_PATH_MAILS . $aParams['iso'] . '/') && !empty($aParams['isoId'])) {
				$iIsoLangId = $aParams['isoId'];
			}
			// get default language
			else {
				$iIsoLangId = Configuration::get('PS_LANG_DEFAULT');
			}

			// use case - send e-mail with bcc
			if (isset($aParams['bcc']) && is_string($aParams['bcc'])) {
				$bSend = Mail::send($iIsoLangId, $aParams['tpl'], $aParams['subject'], $aParams['vars'], $aParams['email'], null, null, null, null, null, _GSR_PATH_MAILS, false, null, $aParams['bcc']);
			}
			else {
				$bSend = Mail::send($iIsoLangId, $aParams['tpl'], $aParams['subject'], $aParams['vars'], $aParams['email'], null, null, null, null, null, _GSR_PATH_MAILS);
			}
		}

		return $bSend;
	}


	/**
	 * processCustomerNotification() method process data for sending an e-mail notification to the customer
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processCustomerNotification(array $aData)
	{
		$aParams = array();

		if (isset($aData['iReviewId'])) {
			// use case - sent as arg or not sent
			if (empty($aData['aReview'])) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// get review data
				$aDataReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $aData['iReviewId'], 'customer' => true));
			}
			else {
				$aDataReview = $aData['aReview'];
			}

			if (!empty($aDataReview)) {
				BT_GsrModuleTools::getConfiguration($aDataReview['shopId']);

				if (empty($aData['subject'])) {
					// use case - get translated email subject
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_EMAIL_SUBJECT'])) {
						$aSubject = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_RVW_EMAIL_SUBJECT']);

						if (isset($aSubject[$aDataReview['data']['iLangId']])) {
							$aParams['subject'] = $aSubject[$aDataReview['data']['iLangId']];
						}
					}
					if (empty($aParams['subject'])) {
						$aParams['subject'] = GSnippetsReviews::$oModule->l('Your review has been published', 'mail-send_class');
					}
				}
				else {
					$aParams['subject'] = $aData['subject'];
				}

				// get iso id & iso lang & email
				$aParams['isoId'] = !empty($aDataReview['data']['iLangId'])? $aDataReview['data']['iLangId'] : Configuration::get('PS_LANG_DEFAULT');
				$aParams['iso'] = !empty($aDataReview['data']['sLangIso'])? $aDataReview['data']['sLangIso'] : BT_GsrModuleTools::getLangIso($aParams['isoId']);
				$aParams['email'] = $aDataReview['email'];

				// use case - validate obj
				$oProduct = BT_GsrModuleTools::isProductObj($aDataReview['productId'], $aParams['isoId'], true);

				if ($oProduct != false) {

					// use case - get Image
					$sImgUrl = BT_GsrModuleTools::getProductImage($oProduct, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_MAIL_PROD_IMG']);

					if (!empty($aDataReview['firstname']) && !empty($aDataReview['lastname'])) {
						$sName = $aDataReview['firstname'] . ' ' . $aDataReview['lastname'];
					}

					if (empty($sName)) {
						$sName = GSnippetsReviews::$oModule->l('Customer', 'mail-send_class');
					}
					// send mail vars
					$aParams['vars'] = array(
						'{name}' 	    => $sName,
						'{product}' 	=> $oProduct->name,
						'{productImg}' 	=> $sImgUrl,
						'{title}' 		=> $aDataReview['data']['sTitle'],
						'{comment}' 	=> str_replace("\n", "<br />", $aDataReview['data']['sComment']),
						'{productUri}' 	=> Context::getContext()->link->getProductLink($oProduct, null, null, null, $aParams['isoId']),
					);

					// require
					require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

					// check if a voucher has been generated
					if (!empty($aData['sVoucherCode'])) {
						$aParams['vars']['{amountComment}'] = 'N.A';
						$aParams['vars']['{validityComment}'] = 'N.A';

						// use case - generate voucher if activated
						$aVoucher = BT_Voucher::create()->getSettings('comment');

						if (!empty($aVoucher)) {
							// get name
							$aVoucher['name'] = $aData['sVoucherCode'];

							// get voucher data
							$aSettings = BT_Voucher::create()->formatData($aVoucher, $aParams['iso']);

							// assign voucher data
							if (!empty($aSettings['use'])) {
								$aParams['vars']['{amountComment}']     = $aSettings['amount'];
								$aParams['vars']['{validityComment}']   = $aSettings['dateTo'];
								$aParams['vars']['{taxComment}']        = $aSettings['tax'];
							}
							unset($aSettings);
						}

						$aParams['vars']['{voucherCode}'] = $aData['sVoucherCode'];

						$bVoucherComment = true;
					}

					// check if Fb share voucher could be won
					if (BT_GsrModuleTools::getEnableVouchers('share')) {
						$aVoucher = BT_Voucher::create()->getSettings('share');

						if (!empty($aVoucher)) {
							// get voucher data
							$aSettings = BT_Voucher::create()->formatData($aVoucher, $aParams['iso']);

							// assign voucher data
							if (!empty($aSettings['use'])) {
								$aParams['vars']['{amountShare}']   = $aSettings['amount'];
								$aParams['vars']['{validityShare}'] = $aVoucher['validity'];
								$aParams['vars']['{taxShare}']      = $aSettings['tax'];

								// set share feature active
								$bVoucherShare = true;
							}
							unset($aSettings);
						}
						unset($aVoucher);
					}

					// define match tpl
					$aParams['tpl'] = _GSR_TPL_MAIL_NOTIF_C;

					if (!empty($bVoucherComment) && !empty($bVoucherShare)) {
						$aParams['tpl'] .= '-fb-voucher-share';
					}
					elseif (!empty($bVoucherComment)) {
						$aParams['tpl'] .= '-fb-voucher';
					}
					elseif (!empty($bVoucherShare)) {
						$aParams['tpl'] .= '-fb-share';
					}
					else {
						$aParams['tpl'] .= '-fb';
					}

					$this->bProcess = true;

					unset($oProduct);
					unset($aData);
				}
			}
		}
		return $aParams;
	}

	/**
	 * processVoucherNotification() method process data for sending an e-mail notification to the customer with his fresh created voucher
	 *
	 * @param array $aPost
	 * @return array
	 */
	private function processVoucherNotification(array $aPost)
	{
		$aParams = array();

		if (!empty($aPost['aVoucher'])) {
			// use case - sent as arg or not sent
			if (empty($aPost['aReview']) && !empty($aPost['bNeedReview']) && !empty($aPost['iReviewId'])) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				// get review data
				$aDataReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $aPost['iReviewId'], 'customer' => true));
			}
			else {
				$aDataReview = $aPost['aReview'];
			}

			if (!empty($aDataReview)) {
				// get iso id & iso lang & email
				$aParams['subject'] = GSnippetsReviews::$oModule->l('Your voucher has been created', 'mail-send_class');
				$aParams['isoId'] = !empty($aDataReview['data']['iLangId'])? $aDataReview['data']['iLangId'] : Configuration::get('PS_LANG_DEFAULT');
				$aParams['iso'] = !empty($aDataReview['data']['sLangIso'])? $aDataReview['data']['sLangIso'] : BT_GsrModuleTools::getLangIso($aParams['isoId']);
				$aParams['email'] = $aDataReview['email'];
				$aParams['tpl'] = _GSR_TPL_MAIL_NOTIF_V;

				// use case - validate obj
				$oProduct = BT_GsrModuleTools::isProductObj($aDataReview['productId'], $aParams['isoId'], true);

				if ($oProduct != false) {
					if (!empty($aDataReview['firstname']) && !empty($aDataReview['lastname'])) {
						$sName = $aDataReview['firstname'] . ' ' . $aDataReview['lastname'];
					}
					else {
						$sName = GSnippetsReviews::$oModule->l('Customer', 'mail-send_class');
					}

					// send mail vars
					$aParams['vars'] = array(
						'{name}' 	    => $sName,
						'{product}' 	=> $oProduct->name,
						'{reviewUri}' 	=> Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW,
							array(
								'iRId' => $aDataReview['ratingId'],
								'iPId' => $aDataReview['productId'],
							),
							null,
							$aParams['isoId']
						),
						'{amountShare}' => $aPost['aVoucher']['amount'],
						'{validityShare}' => $aPost['aVoucher']['dateTo'],
						'{taxShare}' => $aPost['aVoucher']['tax'],
						'{voucherCode}' => $aPost['aVoucher']['name'],
					);

					$this->bProcess = true;

					unset($oProduct);
					unset($aDataReview);
				}
			}
		}
		return $aParams;
	}


	/**
	 * processReplyNotification() method process data for sending an after-sales notification to the customer
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processReplyNotification(array $aData)
	{
		$aParams = array();

		if (isset($aData['iRatingId'])) {
			// use case - only rating Id and the full rating's data
			if (empty($aData['aRating'])) {
				// include
				require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

				BT_ReviewCtrl::create()->getClass('reply')->oDAO->setField('id', 'AFS_ID as replyId');

				$aParams = array(
					'id' => $aData['iRatingId'],
					'reply' => true,
					'customer' => true,
					'bAssociativeArray' => true,
					'table' => array(
						array(
							'fields' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->getFields(),
							'join' => BT_ReviewCtrl::create()->getClass('reply')->oDAO->formatJoin('r.RTG_ID', 'rating', 'LEFT'),
						)
					)
				);

				// get rating & review data
				$aRating = BT_ReviewCtrl::create()->run('getRatings', $aParams);
				BT_ReviewCtrl::create()->getClass('reply')->unserialize('replyData', $aRating);
				$aRating['review'] = BT_ReviewCtrl::create()->run('getReviews', array('ratingId' => $aData['iRatingId'], 'active' => 2));

			}
			else {
				$aRating = $aData['aRating'];
			}

			if (!empty($aRating)) {
				// get iso id & iso lang & email
				$aParams['isoId'] = !empty($aRating['langId'])? $aRating['langId'] : Configuration::get('PS_LANG_DEFAULT');
				$aParams['iso'] = BT_GsrModuleTools::getLangIso($aParams['isoId']);
				$aParams['email'] = $aRating['email'];

				BT_GsrModuleTools::getConfiguration($aRating['shopId']);

				if (empty($aData['subject'])) {
					// use case - get translated email subject
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_SUBJECT'])) {
						$aSubject = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_SUBJECT']);

						if (isset($aSubject[$aParams['isoId']])) {
							$aParams['subject'] = $aSubject[$aParams['isoId']];
						}
					}
					if (empty($aParams['subject'])) {
						$aParams['subject'] = GSnippetsReviews::$oModule->l('you have received a review reply', 'mail-send_class');
					}
				}
				else {
					$aParams['subject'] = $aData['subject'];
				}

				// use case - validate obj
				$oProduct = BT_GsrModuleTools::isProductObj($aRating['prodId'], $aParams['isoId'], true);

				if ($oProduct != false) {
					if (!empty($aRating['firstname']) && !empty($aRating['lastname'])) {
						$sName = $aRating['firstname'] . ' ' . $aRating['lastname'];
					}

					if (empty($sName)) {
						$sName = GSnippetsReviews::$oModule->l('Customer', 'mail-send_class');
					}

					// send mail vars
					$aParams['vars'] = array(
						'{name}'    => $sName,
						'{product}' => $oProduct->name,
						'{rating}'  => $aRating['note'],
						'{max}'     => _GSR_MAX_RATING,
						'{title}'   => (!empty($aRating['review']['data']['sTitle'])? $aRating['review']['data']['sTitle'] : '--'),
						'{comment}' => (!empty($aRating['review']['data']['sComment'])? str_replace("\n", "<br/>", $aRating['review']['data']['sComment']) : '--'),
						'{reviewUri}' => Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW,
							array(
								'iRId' => $aRating['id'],
								'iPId' => $oProduct->id,
							),
							null,
							$aRating['langId']
						),
						'{reply}' => str_replace("\n", "<br/>", $aRating['replyData']['sComment']),
						'{reviewFormUri}' => Context::getContext()->link->getModuleLink(
							_GSR_MODULE_SET_NAME,
							_GSR_FRONT_CTRL_REVIEW_FORM,
							array(
								'iRId' => $aRating['id'],
								'iPId' => $oProduct->id,
								'iCId' => $aRating['custId'],
								'btKey' => BT_GsrModuleTools::setSecureKey($aRating['shopId'].$oProduct->id.$aRating['id'].$aRating['custId'].'modify'),
							),
							null,
							$aRating['langId']
						),
					);

					// use case - get translated merchant generic text
					if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_TEXT'])) {
						$aText = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_REPLY_EMAIL_TEXT']);

						if (isset($aSubject[$aRating['review']['data']['iLangId']])) {
							$aParams['vars']['{merchantText}'] = $aText[$aRating['review']['data']['iLangId']];
						}
					}
					if (empty($aParams['vars']['{merchantText}'])) {
						$aParams['vars']['{merchantText}'] = GSnippetsReviews::$oModule->l('you have received a review reply', 'mail-send_class');
					}

					// define match tpl
					$aParams['tpl'] = _GSR_TPL_MAIL_NOTIF_AS;

					$this->bProcess = true;

					unset($oProduct);
					unset($aData);
				}
			}
		}

		return $aParams;
	}


	/**
	 * processMerchantNotification() method process data for sending an e-mail notification to the merchant
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processMerchantNotification(array $aData)
	{
		$aParams = array();

		// get default language iso ID
		$aParams['isoId'] = (int) Configuration::get('PS_LANG_DEFAULT');
		$aParams['iProductId'] = !empty($aData['iProductId'])? $aData['iProductId'] : 0;

		// get current product
		$oProduct = BT_GsrModuleTools::isProductObj($aParams['iProductId'], $aParams['isoId'], true);

		// check product ID and validate obj
		if ($oProduct != false) {
			$aParams['iso']     = BT_GsrModuleTools::getLangIso($aParams['isoId']);
			$aParams['email']   = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'])? GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'] : Configuration::get('PS_SHOP_EMAIL') ;
			$aParams['tpl']     = _GSR_TPL_MAIL_NOTIF_M;
			$aParams['subject'] = GSnippetsReviews::$oModule->displayName . ' - '
				.	 GSnippetsReviews::$oModule->l('review notification', 'mail-send_class');

			$aParams['vars'] = array(
				'{customer}'	=> GSnippetsReviews::$oCookie->customer_firstname . ' ' . ucfirst(GSnippetsReviews::$oCookie->customer_lastname),
				'{product}' 	=> $oProduct->name,
				'{rating}'      => (!empty($aData['iRating'])? $aData['iRating'] : '--'),
				'{max}' 		=> _GSR_MAX_RATING,
				'{title}' 	    => (!empty($aData['sTitle'])? $aData['sTitle'] : '--'),
				'{comment}' 	=> (!empty($aData['sComment'])? str_replace("\n", "<br />", $aData['sComment']) : '--'),
				'{productUri}'  => Context::getContext()->link->getProductLink($oProduct, null, null, null, $aParams['isoId']),
			);

			unset($oProduct);

			$this->bProcess = true;
		}

		return $aParams;
	}

	/**
	 * processAftersalesMerchantNotification() method process data for sending an e-mail notification to the merchant when a customer
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processAftersalesMerchantNotification(array $aData)
	{
		$aParams = array();

		// get default language iso ID
		$aParams['isoId'] = (int) Configuration::get('PS_LANG_DEFAULT');
		$aParams['iProductId'] = !empty($aData['iProductId'])? $aData['iProductId'] : 0;

		// get current product
		$oProduct = BT_GsrModuleTools::isProductObj($aParams['iProductId'], $aParams['isoId'], true);

		// check product ID and validate obj
		if ($oProduct != false) {
			$aParams['iso']     = BT_GsrModuleTools::getLangIso($aParams['isoId']);
			$aParams['email']   = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'])? GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'] : Configuration::get('PS_SHOP_EMAIL') ;
			$aParams['tpl']     = _GSR_TPL_MAIL_NOTIF_ASM;
			$aParams['subject'] = GSnippetsReviews::$oModule->displayName . ' - '
				.	 GSnippetsReviews::$oModule->l('One of your customers has modified his / her product review', 'mail-send_class');

			$sOldReviewTxt = '';
			$sNewReviewTxt = '';

			// use case - old rating and review
			if (!empty($aData['iOldRating'])
				|| (!empty($aData['sOldTitle'])
				&& !empty($aData['sTitle'])
				&& !empty($aData['sOldComment'])
				&& !empty($aData['sComment'])
				&& (md5($aData['sOldTitle']) != md5($aData['sTitle'])
				|| md5($aData['sOldComment']) != md5($aData['sComment'])))
			) {
				$sOldReviewTxt .= '<span style="color:#333">' . GSnippetsReviews::$oModule->l('Here is the old review', 'mail-send_class') . '</span> : <br />';

				if (!empty($aData['iOldRating'])) {
					$sOldReviewTxt .= '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Rating', 'mail-send_class') . '</strong></span> : ' . $aData['iOldRating'] . '/' . _GSR_MAX_RATING . '<br />';
				}
				if (!empty($aData['sOldTitle'])
					&& !empty($aData['sTitle'])
					&& !empty($aData['sOldComment'])
					&& !empty($aData['sComment'])
					&& (md5($aData['sOldTitle']) != md5($aData['sTitle'])
					|| md5($aData['sOldComment']) != md5($aData['sComment']))
				) {
					$sOldReviewTxt .= '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Title', 'mail-send_class') . '</strong></span> : "' . $aData['sOldTitle'] . '"<br />'
						. '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Comment', 'mail-send_class') . '</strong></span> : "' . str_replace("\n", "<br />", $aData['sOldComment']) . '"<br />';
				}
				$sOldReviewTxt .= '<br />';
			}

			// use case - new rating and review
			if (!empty($aData['iRating'])
				|| (!empty($aData['sOldTitle'])
				&& !empty($aData['sTitle'])
				&& !empty($aData['sOldComment'])
				&& !empty($aData['sComment'])
				&& (md5($aData['sOldTitle']) != md5($aData['sTitle'])
				|| md5($aData['sOldComment']) != md5($aData['sComment'])))
			) {
				$sNewReviewTxt .= '<span style="color:#333">' . GSnippetsReviews::$oModule->l('Here is the new review', 'mail-send_class') . '</span> : <br />';

				if (!empty($aData['iRating'])) {
					$sNewReviewTxt .= '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Rating', 'mail-send_class') . '</strong></span> : ' . $aData['iRating'] . '/' . _GSR_MAX_RATING . '<br />';
				}
				if (!empty($aData['sOldTitle'])
					&& !empty($aData['sTitle'])
					&& !empty($aData['sOldComment'])
					&& !empty($aData['sComment'])
					&& (md5($aData['sOldTitle']) != md5($aData['sTitle'])
					|| md5($aData['sOldComment']) != md5($aData['sComment']))
				) {
					$sNewReviewTxt .= '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Title', 'mail-send_class') . '</strong></span> : "' . $aData['sTitle'] . '"<br />'
						. '<span style="color:#333"><strong>' . GSnippetsReviews::$oModule->l('Comment', 'mail-send_class') . '</strong></span> : "' . str_replace("\n", "<br />", $aData['sComment']) . '"<br />';
				}
				$sNewReviewTxt .= '<br />';
			}

			$aParams['vars'] = array(
				'{customer}'	=> GSnippetsReviews::$oCookie->customer_firstname . ' ' . ucfirst(GSnippetsReviews::$oCookie->customer_lastname),
				'{product}' 	=> $oProduct->name,
				'{oldReview}'   => $sOldReviewTxt,
				'{newReview}' 	=> $sNewReviewTxt,
				'{productUri}'  => Context::getContext()->link->getProductLink($oProduct, null, null, null, $aParams['isoId']),
			);

			unset($oProduct);

			$this->bProcess = true;
		}

		return $aParams;
	}

	/**
	 * processReviewReport() method process data for sending an e-mail notification to the merchant for an abuse
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processReviewReport(array $aData)
	{
		$aParams = array();

		// check customer and review IDs
		if (!empty($aData['iReviewId']) && !empty($aData['iCustomerId'])) {
			// include
			require_once(_GSR_PATH_LIB_REVIEWS . 'review-ctrl_class.php');

			// get current review
			$aReview = BT_ReviewCtrl::create()->run('getReviews', array('id' => $aData['iReviewId']));

			// match review
			if ($aReview) {
				$aParams['isoId']   = Configuration::get('PS_LANG_DEFAULT');
				$aParams['iso']     = BT_GsrModuleTools::getLangIso($aParams['isoId']);
				$aParams['email']   = !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'])? GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_EMAIL'] : Configuration::get('PS_SHOP_EMAIL') ;
				$aParams['tpl']     = _GSR_TPL_MAIL_NOTIF_R;

				// send mail
				$aParams['subject'] = GSnippetsReviews::$oModule->displayName . ' - '
					.	 GSnippetsReviews::$oModule->l('Abusive review reporting', 'mail-send_class');

				// get customer data
				$oCustomer = new Customer($aData['iCustomerId']);

				// set review URL in standalone mode
				$sReviewLink  = Context::getContext()->link->getModuleLink(
					_GSR_MODULE_SET_NAME,
					_GSR_FRONT_CTRL_REVIEW,
					array(
						'iRId' => $aReview['ratingId'],
						'iPId' => $aReview['productId'],
					)
				);

				$aParams['vars'] = array(
					'{link}'            => $sReviewLink,
					'{customer}'        => $oCustomer->firstname . ' ' . ucfirst($oCustomer->lastname),
					'{customerComment}' => str_replace("\n", "<br />", $aData['sComment']),
					'{title}'           => $aReview['data']['sTitle'],
					'{comment}'         => str_replace("\n", "<br />", $aReview['data']['sComment']),
				);
				unset($oCustomer);

				$this->bProcess = true;
			}
			unset($aReview);
		}

		return $aParams;
	}

	/**
	 * processCallback() method process data
	 *
	 * @param array $aData
	 * @return array
	 */
	private function processCallback(array $aData)
	{
		$aParams = array();

		// get iso id & iso lang & email & tpl
		$aParams['isoId']   = !empty($aData['langId'])? $aData['langId'] : Configuration::get('PS_LANG_DEFAULT');
		$aParams['iso']     = !empty($aData['langIso'])? $aData['langIso'] : BT_GsrModuleTools::getLangIso($aParams['isoId']);
		$aParams['email']   = $aData['email'];
		if (!empty($aData['bcc'])) {
			$aParams['bcc'] = $aData['bcc'];
		}
		$aParams['tpl']     = _GSR_TPL_MAIL_CALLBACK;

		// get good translated subject
		if (!empty($aData['aSubject'])
			&& (isset($aData['aSubject'][$aParams['iso']])
			|| isset($aData['aSubject'][$aParams['isoId']]))
		) {
			$aParams['subject'] = isset($aData['aSubject'][$aParams['iso']])? $aData['aSubject'][$aParams['iso']] : $aData['aSubject'][$aParams['isoId']];
		}
		else {
			$aParams['subject'] = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_TRANSLATE']['en'];
		}
		// get all labels and sentence for product details
		if (!empty($aData['aCatLabel'])
			&& (isset($aData['aCatLabel'][$aParams['iso']])
			|| isset($aData['aCatLabel'][$aParams['isoId']]))
		) {
			$sCategoryLabel = isset($aData['aCatLabel'][$aParams['iso']])? $aData['aCatLabel'][$aParams['iso']] : $aData['aCatLabel'][$aParams['isoId']];
		}
		else {
			$sCategoryLabel = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_CAT_LABEL']['en'];
		}
		if (!empty($aData['aProdLabel'])
			&& (isset($aData['aProdLabel'][$aParams['iso']])
			|| isset($aData['aProdLabel'][$aParams['isoId']]))
		) {
			$sProductLabel = isset($aData['aProdLabel'][$aParams['iso']])? $aData['aProdLabel'][$aParams['iso']] : $aData['aProdLabel'][$aParams['isoId']];
		}
		else {
			$sProductLabel = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_PROD_LABEL']['en'];
		}
		if (!empty($aData['aSentence'])
			&& isset($aData['aSentence'][$aParams['isoId']])
		) {
			$sProductSentence = $aData['aSentence'][$aParams['isoId']];
		}
		else {
			$sProductSentence = $GLOBALS[_GSR_MODULE_NAME . '_REMINDER_DEFAULT_SENTENCE']['en'];
		}

		// set vars
		$sContent = '<table>';
		$sStarImgLink = BT_GsrModuleTools::detectHttpUri(_GSR_URL_IMG . 'hook/star-popup.gif');

		foreach ($aData['products'] as $aProduct ) {
			// case of product id filled
			if (!empty($aProduct['prodId'])) {
				// use case - validate obj
				$oProduct = BT_GsrModuleTools::isProductObj($aProduct['prodId'], $aParams['isoId'], true);

				if ($oProduct != false) {
					// use case - get Image
					$aProduct['imageUrl'] = BT_GsrModuleTools::getProductImage($oProduct, GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_MAIL_PROD_IMG']);

					// get product link
					$aProduct['link'] = Context::getContext()->link->getProductLink($oProduct, null, null, null, $aParams['isoId']);

					// add parameter for opening review form automatically
					$aProduct['link'] .= (strstr($aProduct['link'], '&')? '&' : '?')
						. $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['name'] . '=' . $GLOBALS[_GSR_MODULE_NAME . '_FRONT_OPTIONS']['openForm']['value']
						. '&iCId='. $aData['custId'] .'&iPId='. $aProduct['prodId'] .'&btKey='. BT_GsrModuleTools::setSecureKey(GSnippetsReviews::$iShopId.$aProduct['prodId'].$aData['custId'].'review');

					// get product's category and name if null
					if (empty($aProduct['category'])) {
						$aProduct['category'] = BT_GsrModuleTools::getProductPath($oProduct->id_category_default, $aParams['isoId']);
					}
					if (empty($aProduct['title'])) {
						$aProduct['title'] = $oProduct->name;
					}
					if (!empty($aProduct['imageUrl'])) {
						$sContent .= '<tr><td><img src="' . $aProduct['imageUrl'] . '" align="left" style="margin-right: 10px;" /></td>';
					}
					$sContent .= '<td>' . $sCategoryLabel . ' : <strong>' . $aProduct['category'] . '</strong><br/>'
						. $sProductLabel . ' : <strong>' . $aProduct['title'] . '</strong><br/>'
						. $sProductSentence . '<br/>'
					;
					$sRatingLink = ' ';
					// set each rating product link
					for ($i = 1; $i <= _GSR_MAX_RATING; ++$i) {
						$sRatingLink .= '<a rel="' . $i . ' ' . GSnippetsReviews::$oModule->l('on', 'mail-send_class') . ' ' . _GSR_MAX_RATING . '" target="_blank" href="' . $aProduct['link'] . '&rtg=' . $i . '" style="color:{color}; font-weight:bold; text-decoration:none;"><img src="' . $sStarImgLink . '" alt="' . $i . ' ' . GSnippetsReviews::$oModule->l('on', 'mail-send_class') . ' ' . _GSR_MAX_RATING . '" align="left" width="15" height="15" /></a>';
					}
					$sContent .= $sRatingLink;
					$sContent .= '</td></tr>';
					unset($oProduct);
				}
			}
		}
		$sContent .= '</table><br/><br/>';

		// if one product is available at least
		if (!empty($sContent)) {
			// get customer data
			if (!empty($aData['custId'])) {
				$oCustomer = new Customer($aData['custId']);

				if (Validate::isLoadedObject($oCustomer)
					&& $oCustomer->active
				) {
					$sName = $oCustomer->firstname . ' ' . $oCustomer->lastname;
				}
				unset($oCustomer);
			}
			if (empty($sName)) {
				$sName = GSnippetsReviews::$oModule->l('Customer', 'mail-send_class');
			}

			// vars used in e-mail template
			$aParams['vars'] = array(
				'{name}' 	    => $sName,
				'{products}' 	=> $sContent,
				'{orderId}' 	=> $aData['id'],
				'{color}' 	    => ((Configuration::get('PS_MAIL_COLOR')? Configuration::get('PS_MAIL_COLOR') : '#DB3484')),
			);

			// require
			require_once(_GSR_PATH_LIB_VOUCHER . 'voucher_class.php');

			// check if Fb share voucher could be won
			if (BT_GsrModuleTools::getEnableVouchers('share')) {
				$aVoucher = BT_Voucher::create()->getSettings('share');

				if (!empty($aVoucher)) {
					BT_GsrModuleTools::getConfiguration($aData['shopId']);

					// get voucher data
					$aSettings = BT_Voucher::create()->formatData($aVoucher, $aParams['iso']);

					// assign voucher data
					if (!empty($aSettings['use'])) {
						$aParams['vars']['{amountShare}']     = $aSettings['amount'];
						$aParams['vars']['{validityShare}']   = $aVoucher['validity'];
						$aParams['vars']['{taxShare}']        = $aSettings['tax'];

						$bVoucherShare = true;
					}
					unset($aSettings);
				}
				unset($aVoucher);
			}

			// check if review posting voucher could be won
			if (BT_GsrModuleTools::getEnableVouchers('comment')) {
				$aVoucher = BT_Voucher::create()->getSettings('comment');

				if (!empty($aVoucher)) {
					// get voucher data
					$aSettings = BT_Voucher::create()->formatData($aVoucher, $aParams['iso']);

					// assign voucher data
					if (!empty($aSettings['use'])) {
						$aParams['vars']['{amountComment}']   = $aSettings['amount'];
						$aParams['vars']['{validityComment}'] = $aVoucher['validity'];
						$aParams['vars']['{taxComment}']      = $aSettings['tax'];

						$bVoucherComment = true;
					}
					unset($aSettings);
				}
				unset($aVoucher);
			}

			// get the matching template name
			if (!empty($bVoucherComment) && !empty($bVoucherShare)) {
				$aParams['tpl'] .= '-voucher-share';
			}
			elseif (!empty($bVoucherComment)) {
				$aParams['tpl'] .= '-voucher';
			}
			elseif (!empty($bVoucherShare)) {
				$aParams['tpl'] .= '-share';
			}

			// active this current process
			$this->bProcess = true;
		}

		return $aParams;
	}


	/**
	 * create() method set singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oMailSend;

		if (null === $oMailSend) {
			$oMailSend = new BT_GsrMailSend();
		}
		return $oMailSend;
	}
}