<?php
/**
 * voucher_class.php file defines all method manage vouchers
 */

class BT_Voucher
{
	/**
	 * Magic Method __construct
	 *
	 */
	public function __construct()
	{
		// include
		require_once(_GSR_PATH_LIB_VOUCHER . 'voucher-dao_class.php');
	}

	/**
	 * Magic Method __destruct
	 *
	 */
	public function __destruct()
	{

	}

	/**
	 * translateVouchersType() method returns good translated voucher title
	 *
	 * @param string $sVoucherType
	 */
	public function translateVouchersType($sVoucherType)
	{
		if ($sVoucherType == 'comment') {
			$GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE']['comment']['title'] = GSnippetsReviews::$oModule->l('For comments', 'module-tools_class');
		}
		else {
			$GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE']['share']['title'] = GSnippetsReviews::$oModule->l('For shared posts', 'module-tools_class');
		}
	}


	/**
	 * deactivateFbVoucher() method deactivate Facebook voucher type if wall posts is deactivated or not installed
	 */
	public function deactivateFbVoucher()
	{
		// use case - check if wall post is activated
		if (!BT_GsrModuleTools::isInstalled(_GSR_FBWP_NAME, $GLOBALS[_GSR_MODULE_NAME . '_FBWP_KEYS'])
			|| !GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_FB_POST']
		) {
			$GLOBALS[_GSR_MODULE_NAME . '_VOUCHERS_TYPE']['share']['active'] = false;
		}
	}

	/**
	 * formatData() method formats voucher data to display in specific context
	 *
	 * @param array $aSettings
	 * @param string $sLangIso
	 * @return array
	 */
	public function formatData(array $aSettings, $sLangIso)
	{
		// set variables
		$aVoucher = array('use' => true);

		// get name
		$aVoucher['validity'] = $aSettings['validity'];

		// set amount
		$fAmount = $aSettings['amount'];

		if ('amount' == $aSettings['discountType']) {
			$fAmount = Tools::displayPrice($fAmount, intval($aSettings['currency']));
			// get matching translated Tax label
			$aTaxLabel = !empty($GLOBALS[_GSR_MODULE_NAME . '_LABEL_TAX_DEFAULT_TRANSLATE'][$sLangIso])? $GLOBALS[_GSR_MODULE_NAME . '_LABEL_TAX_DEFAULT_TRANSLATE'][$sLangIso] : $GLOBALS[_GSR_MODULE_NAME . '_LABEL_TAX_DEFAULT_TRANSLATE']['en'];
			$aVoucher['tax'] = ' (' . ($aSettings['tax']? $aTaxLabel[1] : $aTaxLabel[0]) . ') ';
		}
		else {
			$aVoucher['tax'] = '%';
		}
		$aVoucher['amount'] = $fAmount;

		unset($fAmount);

		// use case - only if name exists
		if (isset($aSettings['name'])) {
			// get name
			$aVoucher['name'] = $aSettings['name'];

			// get id
			$iVoucherId = CartRule::getIdByCode($aSettings['name']);

			// voucher exists
			if ($iVoucherId !== false) {
				// get obj
				$oDiscount = new CartRule($iVoucherId);

				// get TS
				$iTsDateTo = BT_GsrModuleTools::getTimeStamp($oDiscount->date_to, 'db');

				unset($oDiscount);

				// set locale for voucher date
				$sLocale = setlocale(LC_ALL, $sLangIso);

				$aVoucher['dateTo'] = BT_GsrModuleTools::formatTimestamp($iTsDateTo, null, $sLocale, $sLangIso);

				unset($iTsDateTo);
				unset($sLocale);
			}
			else {
				$aVoucher['use'] = false;
			}
		}

		return $aVoucher;
	}

	/**
	 * getSettings() method returns vouchers's settings
	 *
	 * @param string $sVoucherType
	 */
	public function getSettings($sVoucherType = null)
	{
		// set variables
		$aVouchersSettings = array();

		if (!empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_VOUCHERS_SETTINGS'])) {
			$aVouchersSettings = unserialize(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_VOUCHERS_SETTINGS']);

			if ($sVoucherType !== null) {
				$aVouchersSettings = !empty($aVouchersSettings[$sVoucherType])? $aVouchersSettings[$sVoucherType] : array();
			}
		}
		return $aVouchersSettings;
	}


	/**
	 * add() method returns enable vouchers
	 *
	 * @param string $sVoucherType
	 * @param int $iShopId
	 * @param int $iCustId
	 * @param int $iReviewId
	 * @return string : code name if adding is OK
	 */
	public function add($sVoucherType, $iShopId, $iCustId, $iReviewId)
	{
		// set
		$sCodeName = '';

		// get settings
		$aVoucher = self::getSettings($sVoucherType);

		// get count of vouchers generated for this customer
		$iCount = BT_VoucherDao::get(array('shopId' => $iShopId, 'id' => $iCustId, 'count' => true, 'type' => $sVoucherType));

		if (!empty($aVoucher)) {
			// check maximum quantity as limit
			if (empty($aVoucher['maximumQty'])
				|| (!empty($aVoucher['maximumQty']) && $aVoucher['maximumQty'] > $iCount)
			) {
				// stock voucher code
				$sCodeName = $aVoucher['prefixCode'] . (strtoupper($sVoucherType[0])) . $iShopId . $iCustId . $iReviewId;

				// use case - only if code name not exists
				$bExist = CartRule::cartRuleExists($sCodeName);

				if (!$bExist) {
					// get object
					$oDiscount = new CartRule();

					// set language for name or description according to 1.5 or lower versions
					foreach ($aVoucher['langs'] as $iLangId => $sTitle) {
						// set languages name
						$oDiscount->name[$iLangId] = $sTitle;

						// set description
						if ($sVoucherType == 'comment') {
							$oDiscount->description = GSnippetsReviews::$oModule->l('Voucher won with product review', 'voucher_class');
						}
						elseif ($sVoucherType == 'share') {
							$oDiscount->description = GSnippetsReviews::$oModule->l('Voucher won with Facebook review like', 'voucher_class');
						}
						else {
							$oDiscount->description = GSnippetsReviews::$oModule->l('Voucher won with rating and review products', 'voucher_class');
						}
					}

					// set code
					$oDiscount->code = $sCodeName;

					// get reduction type
					$sType = $aVoucher['discountType'] == 'amount'? 'reduction_amount' : 'reduction_percent';

					// set amount
					$oDiscount->{$sType} = floatval($aVoucher['amount']);

					// set reduction currency + minimum amount
					$oDiscount->reduction_currency = !empty($aVoucher['currency'])? intval($aVoucher['currency']) : false;
					$oDiscount->minimum_amount = $aVoucher['minimum'];
					$oDiscount->minimum_amount_currency = !empty($aVoucher['currency'])? intval($aVoucher['currency']) : false;
					$oDiscount->highlight = $aVoucher['highlight'];
					$oDiscount->reduction_tax = !empty($aVoucher['tax'])? $aVoucher['tax'] : false;
					$oDiscount->cart_rule_restriction = (intval($aVoucher['cumulativeOther']) == 0 ? 1 : 0);

					// test for activate exception
					if (!empty($aVoucher['categories'])) {
						$oDiscount->product_restriction = 1;
					}

					// shared data
					$oDiscount->value = floatval($aVoucher['amount']);
					$oDiscount->id_customer = $iCustId;
					$oDiscount->quantity = 1;
					$oDiscount->quantity_per_user = 1;
					$oDiscount->cumulable = intval($aVoucher['cumulativeOther']);
					$oDiscount->cumulable_reduction = intval($aVoucher['cumulativeReduction']);
					$oDiscount->active = 1;
					$oDiscount->date_from = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
					$oDiscount->date_to = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d') + intval($aVoucher['validity']), date('Y')));

					// set transaction
					Db::getInstance()->Execute('BEGIN');

					// use case - adding succeed
					$bInsert = $oDiscount->add(true, false);

					// use case - only if there is specific categories to include as exception
					if ($bInsert && !empty($aVoucher['categories'])) {
						// add a cart rule
						$bInsert = BT_VoucherDao::addProductRule($oDiscount->id, 1, 'categories', $aVoucher['categories']);
					}


					if ($bInsert) {
						$bInsert = BT_VoucherDao::add($iShopId, $sVoucherType, $iCustId);
					}
					else {
						$sCodeName = '';
					}
					unset($oDiscount);

					// succeeded
					if ($bInsert) {
						Db::getInstance()->Execute('COMMIT');
					}
					// failure
					else {
						Db::getInstance()->Execute('ROLLBACK');
					}
				}
				// use case - already exists, return empty
				else {
					$sCodeName = '';
				}
			}
		}
		return $sCodeName;
	}

	/**
	 * create() method returns singleton
	 *
	 * @return obj
	 */
	public static function create()
	{
		static $oCtrl;

		if (null === $oCtrl) {
			$oCtrl = new BT_Voucher();
		}
		return $oCtrl;
	}
}