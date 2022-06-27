<?php

/*
 * Facebook Fan Coupon: let your customers get a voucher code for liking your Facebook page
 * Version: 3.2.1
 * Last update: 08/01/2014
 * Compatibility: PrestaShop version 1.4.0.17 to 1.5.4.1
 * Initially developed by : PrestaShop 
 * Now maintained and supported by (since June 3rd, 2013): Business Tech (www.businesstech.fr)
 * Please read included installation and configuration instructions (PDF format)
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class FBPromote extends Module
{
    /**
     * @const string BT_FAQ_MAIN_URL : URL of FAQ web site
     */
	const BT_FAQ_MAIN_URL = 'http://faq.businesstech.fr/faq.php?id=';

    /**
     * @const string BT_API_MAIN_URL : URL of API URL
     */
	const BT_API_MAIN_URL = 'http://api.businesstech.fr/prestashop-modules/';

    /**
     * @var string $_html : store html content
     */
	private $_html;

    /**
     * @var obj $oCookie : obj cookie itself
     */
    public static $oCookie = array();

    /**
     * @var array $aHooks : array of available hooks
     */
    public static $aHooks = array();

    /**
     * @var array $aSelectedHooks : array of selected hooks
     */
    public static $aSelectedHooks = array();

    /**
     * Magic Method __construct assigns few information about module and instantiate parent class
     * @author Business Tech (www.businesstech.fr) - Contact: http://www.businesstech.fr/en/contact-us
     * @category main class
     */
	public function __construct()
	{
		$this->name = 'fbpromote';
		$this->tab = 'advertising_marketing';
		$this->version = '3.2.1';
		$this->author = 'Business Tech';
		$this->module_key = '7b8c8cba07d49440bf169ee923b7e8b7';
		
		parent::__construct();

		$this->initCompatibility();
		
		$this->displayName = $this->l('Facebook Fan Coupon');
		$this->description = $this->l('Promote your Facebook page and store');

        if (version_compare(_PS_VERSION_, '1.5', '>')) {
            self::$oCookie = Context::getContext()->cookie;
        }
        else {
            global $cookie;

            self::$oCookie = $cookie;
        }

        self::$aHooks = array(
            array('name' => ((version_compare(_PS_VERSION_, '1.5.0', '>'))? 'displayLeftColumn' : 'leftColumn'), 'title' => $this->l('Left column')),
            array('name' => ((version_compare(_PS_VERSION_, '1.5.0', '>'))? 'displayRightColumn' : 'rightColumn'), 'title' => $this->l('Right column')),
        );

        self::$aSelectedHooks = unserialize(Configuration::get('SELECTED_HOOKS'));
	}

    /**
     * install() method install hooks and create sql tables
     *
     * @category main class
     *
     * @return bool
     */
    public function install()
    {
        if (version_compare(_PS_VERSION_, '1.5', '>')) {
            return (parent::install() &&
                $this->registerHook('displayLeftColumn') && $this->registerHook('displayHeader') && $this->registerHook('displayShoppingCartFooter') &&
                $this->registerHook('displayRightColumn') &&
                Db::getInstance()->Execute('CREATE TABLE '._DB_PREFIX_.'fb_promote (
					id_fb_promote INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					id_guest INT( 11 ) NOT NULL ,
					id_customer INT( 11 ) NOT NULL ,
					id_discount INT( 11 ) NOT NULL ,
					uid_facebook VARCHAR(255) NULL,
					ip_address VARCHAR(255) NOT NULL,
					INDEX (id_guest),
					INDEX (id_customer),
					INDEX (id_discount)
				) ENGINE='._MYSQL_ENGINE_.';')
            );
        }
        else
        {
            return (parent::install() &&
                $this->registerHook('leftColumn') && $this->registerHook('rightColumn') && $this->registerHook('header') && $this->registerHook('shoppingCart') &&
                Db::getInstance()->Execute('CREATE TABLE '._DB_PREFIX_.'fb_promote (
					id_fb_promote INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					id_guest INT( 11 ) NOT NULL ,
					id_customer INT( 11 ) NOT NULL ,
					id_discount INT( 11 ) NOT NULL ,
					uid_facebook VARCHAR(255) NULL,
					ip_address VARCHAR(255) NOT NULL,
					INDEX (id_guest),
					INDEX (id_customer),
					INDEX (id_discount)
				) ENGINE='._MYSQL_ENGINE_.';')
            );
        }
    }

    /**
     * uninstall() method uninstall sql tables
     *
     * @category main class
     *
     * @return bool
     */
    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'fb_promote ');
        return parent::uninstall();
    }


    /**
     * initCompatibility() method returns current object
     *
     * @category main class
     *
     * @return object
     */
	public function initCompatibility()
	{
		if (strpos(dirname(__FILE__), $this->name) === false)
			return $this;
		
		if (!class_exists('Context'))
			require_once(realpath(dirname(__FILE__))  . '/libraries/compatibility/Context.php');
			
		return $this;
	}

    /**
     * isReallyInstalled() method returns active module
     *
     * @category main class
     * @param string $moduleName
     *
     * @return bool
     */
	public function isReallyInstalled($moduleName = '')
	{
		if (!$moduleName)
			$moduleName = $this->name;
			
		$sql = 'SELECT COUNT(id_module)
				FROM ' . _DB_PREFIX_ .'module 
				WHERE name = "' . pSQL($moduleName) . '" AND active = 1';
		
		return (bool)(int)Db::getInstance()->getValue($sql);
	}




    /**
     * postProcess() method process form post
     *
     * @category main class
     *
     * @return bool
     */
	private function postProcess()
	{
		// facebook url fan page
		if ($url = Tools::getValue('FBPROMOTE_FANPAGE_URL'))
			Configuration::updateValue('FBPROMOTE_FANPAGE_URL', $url);

		// description
		$languages = Tools::getValue('FBPROMOTE_VOUCHER_DESCRIPTION');
		foreach ($languages as $key => $value)
		{
			$languages[(int)$key] = ((empty($value)) ? $url : $value); 
		}
		Configuration::updateValue('FBPROMOTE_VOUCHER_DESCRIPTION', Tools::getValue('FBPROMOTE_VOUCHER_DESCRIPTION'));
		
		// type
		if ($type = Tools::getValue('FBPROMOTE_VOUCHER_TYPE'))
			Configuration::updateValue('FBPROMOTE_VOUCHER_TYPE', $type);	
		
		// amount
		foreach (Tools::getValue('FBPROMOTE_VOUCHER_AMOUNT') as $id_currency => $voucherAmount)
			Configuration::updateValue('FBPROMOTE_VOUCHER_AMOUNT_'.(int)($id_currency), (float)($voucherAmount));
		
		// percent
		Configuration::updateValue('FBPROMOTE_VOUCHER_PERCENT', (float)Tools::getValue('FBPROMOTE_VOUCHER_PERCENT'));			
			
		// hasMinAmount
		Configuration::updateValue('FBPROMOTE_VOUCHER_HASMINAMOUNT', (int)Tools::getValue('FBPROMOTE_VOUCHER_HASMINAMOUNT'));
		
		// minAmount
		foreach (Tools::getValue('FBPROMOTE_VOUCHER_MINAMOUNT') as $id_currency => $voucherMinAmount)
			Configuration::updateValue('FBPROMOTE_VOUCHER_MINAMOUNT_'.(int)($id_currency), (float)($voucherMinAmount));
			
		// validity
		Configuration::updateValue('FBPROMOTE_VOUCHER_VALIDITY', (float)Tools::getValue('FBPROMOTE_VOUCHER_VALIDITY'));

        // facebook app ID
        if ($appId = Tools::getValue('FBPROMOTE_FB_APP_ID'))
            Configuration::updateValue('FBPROMOTE_FB_APP_ID', $appId);

        // set variable
        $aSelectedHooks = array();

        // selected hooks
        if (Tools::getIsset('DISPLAY_HOOKS')) {
            // get hooks form
            $aFormHooks = Tools::getValue('DISPLAY_HOOKS');

            foreach (self::$aHooks as $aHook) {
                if (in_array($aHook['name'], $aFormHooks)) {
                    $aSelectedHooks[] = $aHook['name'];
                }
            }
        }
        Configuration::updateValue('SELECTED_HOOKS', serialize($aSelectedHooks));
    }

    /**
     * getContent() method returns back-office content
     *
     * @category main class
     *
     * @return string
     */
	public function getContent()
	{
		global $cookie;
		
		if (Tools::isSubmit('submitFbPromote'))
		{
			$this->postProcess();
			$this->_html .= '
			<div class="conf confirm" style="margin:0 auto 15px;"><img src="../img/admin/ok.gif" alt="'.$this->l('Settings updated').'" />'.$this->l('Settings updated').'</div>';
		}			
		
		$currencies = Currency::getCurrencies();
		$isoCurrent = Language::getIsoById((int)($cookie->id_lang));
		$defaultLanguage = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages(false);

        // check if selected hooks not empty
        $sSelectHooks = Configuration::get('SELECTED_HOOKS');

        if (!empty($sSelectHooks) && is_string($sSelectHooks)) {
            $aSelectHooks = unserialize($sSelectHooks);
        }
        else {
            $aSelectHooks = array();
        }

		$this->_html .= '
		<style type="text/css">
		@import url(\''.self::BT_API_MAIN_URL.'css/styles.css?ts='.time().'\');
		</style>
		<script type="text/javascript">id_language = Number('.$defaultLanguage.');</script>
		<fieldset style="margin: auto;"><legend>'.$this->l('Create a voucher when your customers like your page').'</legend>
							<form method="post" id="fbcoupon-configure" action="#">';
				
				$this->_html .=	'<label>'.$this->l('Facebook Fan page URL').'</label>
								<input type="text" id="FBPROMOTE_FANPAGE_URL" name="FBPROMOTE_FANPAGE_URL" value="'.Configuration::get('FBPROMOTE_FANPAGE_URL').'" style="width:400px;" />
								<div class="margin-form">
									'.$this->l('').'
								</div>
								<div class="clear">&nbsp;</div>';
				
				$this->_html .=	'<label>'.$this->l('Voucher description').'</label>
								<div class="margin-form">';
								
				
				
				foreach ($languages as $language)
					$this->_html .= '<div id="description_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left; margin: 0;">
										<input size="33" type="text" name="FBPROMOTE_VOUCHER_DESCRIPTION['.$language['id_lang'].']"  id="FBPROMOTE_VOUCHER_DESCRIPTION['.$language['id_lang'].']" value="'.(isset($_POST['FBPROMOTE_VOUCHER_DESCRIPTION'][(int)($language['id_lang'])]) ? $_POST['FBPROMOTE_VOUCHER_DESCRIPTION'][(int)($language['id_lang'])] : Configuration::get('FBPROMOTE_VOUCHER_DESCRIPTION', (int)$language['id_lang'])).'" />
									</div>';
					
				$this->_html .= $this->displayFlags($languages, $defaultLanguage, 'description', 'description', true);
				
				$this->_html .= '</div>
								<div class="margin-form clear">'.$this->l('The description is displayed in cart once your customers use their voucher.').'</div>
								<div class="clear">&nbsp;</div>';
				
				$this->_html .= '<label>'.$this->l('Voucher type').'</label>
								<select id="FBPROMOTE_VOUCHER_TYPE" name="FBPROMOTE_VOUCHER_TYPE">
									<option value="currency"' . (Configuration::get('FBPROMOTE_VOUCHER_TYPE') == "currency" ? ' selected="selected"' : '') . '>'.$this->l('Currency').'</option>
									<option value="percent"' . (Configuration::get('FBPROMOTE_VOUCHER_TYPE') == "percent" ? ' selected="selected"' : '') . '>'.$this->l('Percentage').'</option>
								</select>
								<div class="margin-form"></div>
								<div id="voucherbycurrency-container">
									<div class="margin-form">
										<table id="voucherbycurrency" cellpadding="5" style="border: 1px solid #BBB;" border="0">
											<tr>
												<th style="width: 80px;">'.$this->l('Currency').'</th>
												<th>'.$this->l('Voucher amount').'</th>
											</tr>';
											
				foreach ($currencies as $currency)
					$this->_html .= '<tr>
										<td>'.(Configuration::get('PS_CURRENCY_DEFAULT') == $currency['id_currency'] ? '<span style="font-weight: bold;">' : '').htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $currency['id_currency'] ? '<span style="font-weight: bold;">' : '').'</td>
										<td>
											<input class="FBPROMOTE_VOUCHER_AMOUNT" type="text" name="FBPROMOTE_VOUCHER_AMOUNT['.(int)($currency['id_currency']).']" id="FBPROMOTE_VOUCHER_AMOUNT['.(int)($currency['id_currency']).']" value="'.Tools::getValue('FBPROMOTE_VOUCHER_AMOUNT['.(int)($currency['id_currency']).']', Configuration::get('FBPROMOTE_VOUCHER_AMOUNT_'.(int)($currency['id_currency']))).'" style="width: 50px; text-align: right;" /> '.$currency['sign'].'
										</td>
									</tr>';
				
				$this->_html .= '	</table>
									</div>
								</div>';
				
				$this->_html .= '<div id="voucherbypercent-container">
									<label>'.$this->l('Voucher percentage').'</label>
									<input type="text" id="FBPROMOTE_VOUCHER_PERCENT" name="FBPROMOTE_VOUCHER_PERCENT" value="'.Configuration::get('FBPROMOTE_VOUCHER_PERCENT').'" /> %
								</div>
								<div class="clear">&nbsp;</div>';
				
				$this->_html .= '<label>'.$this->l('Minimum checkout').'</label>
								<input type="checkbox" id="FBPROMOTE_VOUCHER_HASMINAMOUNT" name="FBPROMOTE_VOUCHER_HASMINAMOUNT" value="' . (int)Configuration::get('FBPROMOTE_VOUCHER_HASMINAMOUNT') . '"' . ((int)Configuration::get('FBPROMOTE_VOUCHER_HASMINAMOUNT') > 0 ? ' checked="checked"' : '') . ' style="margin-top: 4px;" />
								<div class="margin-form"></div>
								<div id="voucherminamount-container">
									<div class="margin-form">
										<table id="voucherminamountbycurrency" cellpadding="5" style="border: 1px solid #BBB;" border="0">
											<tr>
												<th style="width: 80px;">'.$this->l('Currency').'</th>
												<th>'.$this->l('Minimum checkout').'</th>
											</tr>';
											
				foreach ($currencies as $currency)
					$this->_html .= '<tr>
										<td>'.(Configuration::get('PS_CURRENCY_DEFAULT') == $currency['id_currency'] ? '<span style="font-weight: bold;">' : '').htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $currency['id_currency'] ? '<span style="font-weight: bold;">' : '').'</td>
										<td>
											<input class="FBPROMOTE_VOUCHER_MINAMOUNT" type="text" name="FBPROMOTE_VOUCHER_MINAMOUNT['.(int)($currency['id_currency']).']" id="FBPROMOTE_VOUCHER_MINAMOUNT['.(int)($currency['id_currency']).']" value="'.Tools::getValue('FBPROMOTE_VOUCHER_MINAMOUNT['.(int)($currency['id_currency']).']', Configuration::get('FBPROMOTE_VOUCHER_MINAMOUNT_'.(int)($currency['id_currency']))).'" style="width: 50px; text-align: right;" /> '.$currency['sign'].'
										</td>
									</tr>';
				
				$this->_html .=	'		</table>
									</div>
								</div>';
				
				$this->_html .=	'<label>'.$this->l('Voucher validity').'</label>
								<input type="text" id="FBPROMOTE_VOUCHER_VALIDITY" name="FBPROMOTE_VOUCHER_VALIDITY" value="'.Configuration::get('FBPROMOTE_VOUCHER_VALIDITY').'" /> '.$this->l('hour(s)').'
								<div class="clear">&nbsp;</div>';

                $this->_html .=	'<label>'.$this->l('Facebook App ID').'</label>
								<input type="text" id="FBPROMOTE_FB_APP_ID" name="FBPROMOTE_FB_APP_ID" value="'.Configuration::get('FBPROMOTE_FB_APP_ID').'" /> '.'
								<div class="margin-form clear">'.$this->l('If you have already created a Facebook app for another module or any reason and that is linked to this domain, you can fill out the app ID here and benefit of functionality which checks if your customers are already logged in to Facebook before clicking on like button and warn them if not to log first').'.</div>';

                $this->_html .=	'<label>'.$this->l('Hook to display').'</label>
                                <div class="margin-form">
                                    <select name="DISPLAY_HOOKS[]" multiple="multiple">
                                    ';
                                foreach (self::$aHooks as $aHook) {
                $this->_html .=	'
                                    <option value="' . $aHook['name'] . '" ' . (in_array($aHook['name'], $aSelectHooks) ? 'selected="selected"' : '') . '>' . $aHook['title'] . '</option>';
                                }
                $this->_html .=	'   </select>
								    <div class="clear">'.$this->l('You can select many hooks to display module\'s bloc. "Shopping Cart Footer" hook is installed by default').'.</div>
                                </div>';

				$this->_html .= '<br />
								<div style="text-align: center;">
									<input type="submit" class="button" name="submitFbPromote" value="'.$this->l('Submit').'">
								</div>
							</form>
						</fieldset>
						<p>&nbsp;</p>
						<iframe class="btxsellingiframe" src="'.self::BT_API_MAIN_URL.'?ts='.time().'&sName='.$this->name.'&sLang='.$isoCurrent.'"></iframe>
						';
		
		$this->_html .= $this->display(__FILE__, 'views/templates/admin/module-configure.tpl');
									
		return $this->_html;
	}


    /**
     * hookHeader() method returns HTML content linked to module's header
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
	public function hookHeader($params)
	{
		global $smarty;

		$smarty->assign(
			array(
				'fb_url'	=> $this->getUrlFacebookJsLibrary((int)self::$oCookie->id_lang),
			)
		);
		
		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			$this->context->controller->addCSS($this->_path . 'views/css/fbpromote.css');
		}
		else {
			Tools::addCSS($this->_path . 'views/css/fbpromote.css', 'all');
		}
		
		return $this->display(__FILE__, 'views/templates/hook/header.tpl');
	}


    /**
     * hookDisplayLeftColumn() method returns HTML content linked to module's left column
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    public function hookDisplayLeftColumn($params)
    {
        $sContent = '';

        if (in_array('displayLeftColumn', self::$aSelectedHooks)) {
            $sContent = $this->_execHook($params);
        }

        return $sContent;
    }


    /**
     * hookLeftColumn() method returns HTML content linked to module's left column
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
	public function hookLeftColumn($params)
	{
        $sContent = '';

        if (in_array('leftColumn', self::$aSelectedHooks)) {
            $sContent = $this->_execHook($params);
        }

        return $sContent;
	}

    /**
     * hookDisplayRightColumn() method returns HTML content linked to module's right column
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    public function hookDisplayRightColumn($params)
    {
        $sContent = '';

        if (in_array('displayRightColumn', self::$aSelectedHooks)) {
            $sContent = $this->_execHook($params);
        }

        return $sContent;
    }

    /**
     * hookRightColumn() method returns HTML content linked to module's right column
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    public function hookRightColumn($params)
    {
        $sContent = '';

        if (in_array('rightColumn', self::$aSelectedHooks)) {
            $sContent = $this->_execHook($params);
        }

        return $sContent;
    }

    /**
     * hookDisplayShoppingCartFooter() method returns HTML content linked to module's shopping cart
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    public function hookDisplayShoppingCartFooter($params)
    {
        return $this->_execHook($params);
    }

    /**
     * hookShoppingCart() method returns HTML content linked to module's shopping cart
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    public function hookShoppingCart($params)
    {
        return $this->_execHook($params);
    }

    /**
     * _execHook() method returns HTML content for any hooks - same code as generic code
     *
     * @category main class
     *
     * @param mixed $params
     * @return string
     */
    private function _execHook($params) {

        if (Configuration::get('FBPROMOTE_VOUCHER_TYPE') == "percent") {
            $discountValue = (float)Configuration::get('FBPROMOTE_VOUCHER_PERCENT') . " %";
        }
        else {
            $currency = new Currency((int)self::$oCookie->id_currency);
            $discountValue = Tools::displayPrice((float)Configuration::get('FBPROMOTE_VOUCHER_AMOUNT_'.(int)($currency->id)), $currency);
        }

        global $smarty;

        $smarty->assign(
            array(
                'fb_page' 	=> Configuration::get('FBPROMOTE_FANPAGE_URL'),
                'fb_appId' 	=> Configuration::get('FBPROMOTE_FB_APP_ID'),
                'fb_value' 	=> $discountValue,
                'aJSCallback' => array('url' => Configuration::get('FBPROMOTE_FANPAGE_URL'), 'function' => 'bt_generateVoucherCode'),
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/fbpromoteblock.tpl');
    }

    /**
     * isFacebookLocaleSupported() method returns FB locale
     *
     * @category main class
     *
     * @param string $locale
     * @return bool
     */
	public function isFacebookLocaleSupported($locale)
	{
		return in_array($locale, $this->getFacebookLocaleSupported());
	}

    /**
     * getFacebookLocaleSupported() method returns locales
     *
     * @category main class
     * @return array
     */
	public function getFacebookLocaleSupported()
	{
		$locales = array();

		if (($xml=simplexml_load_file(_PS_MODULE_DIR_ . "fbpromote/views/xml/FacebookLocales.xml")) === false)
			return $locales;
			
		$result = $xml->xpath('/locales/locale/codes/code/standard/representation');

		foreach ($result as $locale)
		{
			list($k, $node) = each($locale);
			$locales[] = $node;
		}
			
		return $locales;
	}


    /**
     * getUrlFacebookJsLibrary() method returns FB JS library
     *
     * @category main class
     *
     * @param int $id_lang
     * @return string
     */
	public function getUrlFacebookJsLibrary($id_lang)
	{
		$lang = new Language((int)$id_lang);
		
		if(strstr($lang->language_code, '-'))
		{
			$res = explode('-', $lang->language_code);
			$language_iso = strtolower($res[0]).'_'.strtoupper($res[1]);
		}
		else
			$language_iso = strtolower($lang->language_code).'_'.strtoupper($lang->language_code);
			
		if (!$this->isFacebookLocaleSupported($language_iso))
			$language_iso = "en_US";

		return '//connect.facebook.net/'.$language_iso.'/all.js';
	}


    /**
     * quoteQuery() method returns query
     *
     * @category main class
     *
     * @param string $sql
     * @param array $values
     * @return string
     */
	public function quoteQuery($sql, $values = array())
	{
		if (!is_array($values))
			throw new Exception("values must be an array");
		
		if (preg_match_all("/(\?)/", $sql, $matches) === false)
			throw new Exception("bad string");
		
		if (count($values) != count($matches[0]))
			throw new Exception("some missing elements into sql or values");
		
		foreach ($values as $value)
		{
			if (is_int($value)) // convert some strings to int
				$value = (int)$value;
				
			if (is_float($value)) // convert some strings to float
				$value = (float)$value;
			
			if (!is_int($value) && !is_float($value))
				$value = "'" . pSQL($value) . "'";
			else
				$value = pSQL($value);
			
			$sql = substr_replace($sql, $value, strpos($sql, "?", 0), 1);
		}
		
		return $sql;
	}

    /**
     * isInnoDb() method returns if engine is INNODB
     *
     * @category main class
     *
     * @return bool
     */
	public function isInnoDb()
	{
		if (_MYSQL_ENGINE_ !== 'InnoDB')
			return false;
			
		return true;
	}


    /**
     * beginTransaction() method start transaction
     *
     * @category main class
     *
     * @return bool
     */
	public function beginTransaction()
	{		
		if (!$this->isInnoDb())
			return true; // default
		
		return (bool)Db::getInstance()->Execute('BEGIN');
	}


    /**
     * rollBack() method rollbacks query
     *
     * @category main class
     *
     * @return bool
     */
	public function rollBack()
	{
		if (!$this->isInnoDb())
			return true; // default
			
		return (bool)Db::getInstance()->Execute('ROLLBACK');
	}


    /**
     * commit() method commits query
     *
     * @category main class
     *
     * @return bool
     */
	public function commit()
	{
		if (!$this->isInnoDb())
			return true; // default
			
		return (bool)Db::getInstance()->Execute('COMMIT');
	}


    /**
     * createVoucher() method creates voucher
     *
     * @category main class
     *
     * @param int $id_guest
     * @param int $id_currency
     * @return string
     */
	public function createVoucher($id_guest, $id_currency)
	{
		if ($this->hasAlreadyVoucher($id_guest))
			return false;
		
        // insert discount
		
		// TYPE AND VALUE
		switch (Configuration::get('FBPROMOTE_VOUCHER_TYPE'))
		{
			case 'percent':
				$id_discount_type = 1;
				$value = Configuration::get('FBPROMOTE_VOUCHER_PERCENT');
				break;
			case 'currency':
			default:
				$id_discount_type = 2;
				$value = Configuration::get('FBPROMOTE_VOUCHER_AMOUNT_'.(int)$id_currency);
		}
		
		// VALIDITY
		$validity = (float)Configuration::get('FBPROMOTE_VOUCHER_VALIDITY'); // return hours
		
		if ($validity <= 0)
			$validity = 30 * 24; // default 30 days
		
		$validity = (int)($validity * 3600); // convert to seconds
		
		// Code
		$code = 'FBCOUPON-'.strtoupper(Tools::passwdGen(8));
		$languages = Language::getLanguages();
		
		// id_customer
		$id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM '._DB_PREFIX_.'guest WHERE id_guest='.(int)$id_guest);		
		
		if (version_compare(_PS_VERSION_, '1.5', '>')) 
		{
			$voucher = new CartRule();
			$voucher->name = Configuration::getInt('FBPROMOTE_VOUCHER_DESCRIPTION');	
			
			if ((int)($id_discount_type) == 1) { // percent
				$voucher->reduction_percent = $value;
			}
			elseif ((int)($id_discount_type) == 2) { // currency
				$voucher->reduction_amount = $value;
				$voucher->reduction_tax = 1;
			}
			
			$voucher->code = $code;
			$voucher->id_customer = (int)$id_customer;
			$voucher->reduction_currency = (int)$id_currency;		
			$voucher->quantity = 1;
			$voucher->quantity_per_user = 1;		
			$voucher->cumulable = 0;
			$voucher->cumulable_reduction = 1;
			$voucher->partial_use = 0;
			$voucher->minimum_amount = Configuration::get('FBPROMOTE_VOUCHER_MINAMOUNT_'.(int)$id_currency);
			$voucher->minimum_amount_currency = (int)($id_currency);
			$voucher->active = 1;
			$voucher->highlight = 0;
			$voucher->date_from = date('Y-m-d H:i:s');
			$voucher->date_to = date('Y-m-d H:i:s', time() + $validity);
		}
		
		else {
			$voucher = new Discount();
			$voucher->id_discount_type = $id_discount_type;
			$voucher->description = Configuration::getInt('FBPROMOTE_VOUCHER_DESCRIPTION');	
			$voucher->value = $value;
			$voucher->name = $code;
			$voucher->id_customer = (int)$id_customer;
			$voucher->id_currency = (int)$id_currency;		
			$voucher->quantity = 1;
			$voucher->quantity_per_user = 1;		
			$voucher->cumulable = 1;
			$voucher->cumulable_reduction = 1;
			$voucher->minimal = Configuration::get('FBPROMOTE_VOUCHER_MINAMOUNT_'.(int)$id_currency);
			$voucher->active = 1;
			$voucher->cart_display = 0;
			$voucher->date_from = date('Y-m-d H:i:s');
			$voucher->date_to = date('Y-m-d H:i:s', time() + $validity);
		}
		
		$this->beginTransaction();
		
		if (!$voucher->add())
		{
			$this->rollBack();
        	throw new Exception("building voucher failed..");
		}

		$id_discount = $voucher->id;
		
        // insert fb_couponforfriends
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false)
		{
			$this->rollBack();
        	throw new Exception("bad ip");
		}
        
        $sql = 'INSERT INTO '._DB_PREFIX_.'fb_promote (id_guest, id_customer, id_discount, ip_address) VALUES (?, ?, ?, ?)';
		$values = array((int)$id_guest, (int)$id_customer, (int)$voucher->id, $ip_address);
        
		$sql = $this->quoteQuery($sql, $values);
        
        if (!Db::getInstance()->Execute($sql))
		{
			$this->rollBack();
        	throw new Exception("building voucher failed..");
		}

        $this->commit();
        
        return $id_discount;
	} //createVoucher


    /**
     * hasAlreadyVoucher() method checks if customer already get his voucher code
     *
     * @category main class
     *
     * @param int $id_guest
     * @return bool
     */
	public function hasAlreadyVoucher($id_guest)
	{
		$sql = 'SELECT id_discount 
				FROM ' . _DB_PREFIX_ . 'fb_promote
				WHERE id_guest = ?';
		
		$sql = $this->quoteQuery($sql, array($id_guest));
		
		return (int)Db::getInstance()->getValue($sql);
	}


    /**
     * isVoucherValid() method if voucher is valid
     *
     * @category main class
     *
     * @param int $id_discount
     * @return bool
     */
	public function isVoucherValid($id_discount)
	{
		if (version_compare(_PS_VERSION_, '1.5', '>')) 
			$discount = new CartRule($id_discount);
		else
			$discount = new Discount($id_discount);
		
		$now = strtotime('now');
		if ($now >= strtotime($discount->date_from) AND $now < strtotime($discount->date_to))
			return true;
		return false;
	}


    /**
     * isVoucherAlreadyUsed() method checks if voucher is already used
     *
     * @category main class
     *
     * @param int $id_discount
     * @return bool
     */
	public function isVoucherAlreadyUsed($id_discount)
	{
		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			$sql = 'SELECT COUNT(oct.id_order_cart_rule) 
					FROM ' . _DB_PREFIX_ . 'order_cart_rule oct
					WHERE oct.id_cart_rule = ?';
		}
		else {
			$sql = 'SELECT COUNT(od.id_order_discount) 
					FROM ' . _DB_PREFIX_ . 'order_discount od
					WHERE od.id_discount = ?';
		}
		
		$sql = $this->quoteQuery($sql, array($id_discount));
		
		return (bool)Db::getInstance()->getValue($sql);
	}


    /**
     * deleteVoucher() method deletes voucher
     *
     * @category main class
     *
     * @param int $id_guest
     * @return bool
     */
	public function deleteVoucher($id_guest)
	{
		$sql = 'SELECT id_discount FROM '._DB_PREFIX_.'fb_promote 
				WHERE id_guest = ?';
		$values = array((int)$id_guest);
			
		if (!($id_voucher = (int)Db::getInstance()->getValue($this->quoteQuery($sql, $values))))
			throw new Exception("unknown id");
			
		$voucher = new Discount((int)$id_voucher);
		$voucher->delete();
		
		$sql = 'DELETE FROM '._DB_PREFIX_.'fb_promote 
				WHERE id_discount = ?';
		$values = array($id_voucher);
		
		return (bool)Db::getInstance()->Execute($this->quoteQuery($sql, $values));
 	}


    /**
     * translate() method translate texts
     *
     * @category main class
     *
     * @param string $key
     * @return string
     */
 	public function translate($key)
	{
		$trad = array(
			'VALID'			=>	$this->l('Your reduction coupon is the following one'),
			'INVALID'		=>	$this->l('A reduction coupon was already associated with your account'),
			'AVAILABLE'		=>	$this->l('available until'),
			'EXPIRED'		=>	$this->l('your voucher has expired'),
			'USED'			=>	$this->l('your voucher has already been used'),
			'DISPLAY_AGAIN'	=>	$this->l('You already have generated a voucher code. We are displaying it again below for your convenience.')
		);
		
		return $trad[$key];
	}


    /**
     * formatDate() method formats date
     *
     * @category main class
     *
     * @param string $date
     * @return string
     */
	public function formatDate($date)
	{		
		return $date;
	}
}
