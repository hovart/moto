<?php
/**
* 2015 Novansis
*
* NOTICE OF LICENSE
*
* Conditions and limitations:
*
* A. This source code file is copyrighted, you cannot remove any copyright notice from this file.  You agree to prevent any unauthorized copying of this file.  Except as expressly provided herein, Novansis does not grant any express or implied right to you under copyrights, trademarks, or trade secret information.
*
* B. You may NOT:  (i) rent or lease the file to any third party; (ii) assign this file or transfer the file without the express written consent of Novansis; (iii) modify, adapt, or translate the file in whole or in part; or (iv) distribute, sublicense or transfer the source code form of any components of the file and derivatives thereof to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer versions in the future.
*
*  @author    Novansis <info@novansis.com>
*  @copyright 2015 Novansis SL
*  @license   http://www.novansis.com/
*/

if (!defined('_PS_VERSION_'))
	exit;
class SearchOptimizer extends Module
{
	protected $vlwxuo3w2am3 = false;
	public function __construct()
	{
		$this->name = 'searchoptimizer';
		$this->tab = 'administration';
		$this->version = '1.0.1';
		$this->author = 'Novansis';
		$this->module_key = 'bfa34d60f29e4419576372ed1ca742fc';
		$this->need_instance = 1;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Search Optimizer');
		$this->description = $this->l('Search Optimizer helps you to speed up and optimize the searches performed in your shop. It also allows you to find out the words searched by your customers.');
		$this->confirmUninstall = $this->l('Are you sure you want to unistall Search Optimizer? All the data stored will be lost.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	public function install()
	{
		Configuration::updateValue('SEARCH_OPTIMIZER_RECORD', 0);
		include(dirname(__FILE__).'/sql/install.php');
		return parent::install() &&	$this->registerHook('header') && $this->registerHook('backOfficeHeader');
	}
	public function uninstall()
	{
		Configuration::deleteByName('SEARCH_OPTIMIZER_RECORD');
		include(dirname(__FILE__).'/sql/uninstall.php');
		return parent::uninstall();
	}
	public function getContent()
	{
		$vehbemm2lusy = array();
		if (Tools::getIsset('process'))
			$vehbemm2lusy = $this->pp();
		$this->context->smarty->assign('module_dir', $this->_path);
		if (count(Shop::getContextListShopID()) > 1)
			return $this->ge(2);

		$vjhc3ecgwxjr = '';
		foreach ($vehbemm2lusy as $v2pu30b1r203)
			$vjhc3ecgwxjr .= $v2pu30b1r203;

		return $vjhc3ecgwxjr.$this->rf();
	}
	protected function rf()
	{
		$v43kxmpshykz = $this->a(Shop::getContextShopGroupID(), Shop::getContextShopID());
		$v0cs2w14js4s = $this->gl();
		$v1iufokahwvl = $this->gi();
		$v3aqpvv4v2ui = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Words Management'),
					'icon' => 'icon-align-left',
					),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Language'),
						'desc' => $this->l('Select a language to filter the words'),
						'name' => 'lang',
						'required' => false,
						'options' => array(
							'query' => $v0cs2w14js4s,
							'id' => 'id_option',
							'name' => 'name'
						)
					),
					array(
						'type' => 'select',
						'label' => $this->l('Starts with'),
						'desc' => $this->l('Select an item to filter the current search index words'),
						'name' => 'letterFilter',
						'required' => false,
						'options' => array(
							'query' => $v43kxmpshykz,
							'id' => 'id_option',
							'name' => 'name'
						)
					),
					array(
						'type' => 'words_management',
					),
					array(
						'type' => 'hidden',
						'name' => 'wordsToRemove',
					),
					array(
						'type' => 'hidden',
						'name' => 'process',
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Re-build search index'),
						'desc' => $this->l('Re-build the shop search index after saving. Current products indexed').' '.$v1iufokahwvl,
						'name' => 're-build',
						'values' => array(
							array(
								'id' => 're-build_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 're-build_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			),
		);
		$vzzwfy11g5gl = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Search Queries Management'),
					'icon' => 'icon-search',
					),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Record search queries'),
						'name' => 'SEARCH_OPTIMIZER_RECORD',
						'desc' => $this->l('Enable to start recording customer search queries'),
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => '1',
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => '0',
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'select',
						'label' => $this->l('Language'),
						'desc' => $this->l('Select a language to filter search queries already recorded'),
						'name' => 'lang_s',
						'required' => false,
						'options' => array(
							'query' => $v0cs2w14js4s,
							'id' => 'id_option',
							'name' => 'name'
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Returning results'),
						'desc' => $this->l('Filter search queries already recorded by returning some results or not'),
						'name' => 'show_results',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'show_results_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'show_results_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'search_management',
					),
					array(
						'type' => 'hidden',
						'name' => 'process',
					),
				),
			),
		);
		$vvrw3c13infu = new HelperForm();
		$vvrw3c13infu->show_toolbar = false;
		$vvrw3c13infu->table = $this->table;
		$vvrw3c13infu->module = $this;
		$vvrw3c13infu->default_form_language = $this->context->language->id;
		$vvrw3c13infu->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
		$vvrw3c13infu->identifier = $this->identifier;
		$vvrw3c13infu->submit_action = 'submitsearchoptimizerModule';
		$vvrw3c13infu->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$vvrw3c13infu->token = Tools::getAdminTokenLite('AdminModules');
		$vvrw3c13infu->tpl_vars = array(
			's' => Shop::getContextShopID(),
			'g' => Shop::getContextShopGroupID(),
		);
		$vyr4ic1ys1oj = 0;
		$vdqwvv11k5rs = null;
		$vc1dng4wlq5o = null;
		if (Shop::isFeatureActive())
		{
			$vdqwvv11k5rs = Shop::getContextShopGroupID();
			$vc1dng4wlq5o = Shop::getContextShopID();
		}
		if (Configuration::hasKey('SEARCH_OPTIMIZER_RECORD', null, $vdqwvv11k5rs, $vc1dng4wlq5o))
			$vyr4ic1ys1oj = Configuration::get('SEARCH_OPTIMIZER_RECORD');
		$vvrw3c13infu->fields_value['SEARCH_OPTIMIZER_RECORD'] = $vyr4ic1ys1oj;
		return $vvrw3c13infu->generateForm(array($vzzwfy11g5gl, $v3aqpvv4v2ui));
	}
	public function w($vdqwvv11k5rs, $vc1dng4wlq5o, $v0khu0mvml5y, $vodwm3w531gc)
	{
		$vxj43jnx2v2y = 'SELECT word FROM `'._DB_PREFIX_.'search_word`
				WHERE id_lang = '.$vodwm3w531gc.' AND word like "'.$v0khu0mvml5y.'%"'.$this->sr($vdqwvv11k5rs, $vc1dng4wlq5o).' ORDER BY word;';
		$vdu0eatc4bfd = array();
		if ($vnkqu1vz2dsb = Db::getInstance()->executeS($vxj43jnx2v2y))
			foreach ($vnkqu1vz2dsb as $vlevkjir4o3s)
			{
				$v5fhkbjqjham = $vlevkjir4o3s['word'];
				$v5fhkbjqjhamord = array('id_option'=>$v5fhkbjqjham, 'name'=>$v5fhkbjqjham);
				array_push($vdu0eatc4bfd, $v5fhkbjqjhamord);
			}
		return $vdu0eatc4bfd;
	}
	public function wtr($vdqwvv11k5rs, $vc1dng4wlq5o, $vodwm3w531gc)
	{
		$vxj43jnx2v2y = 'SELECT `id_configuration` FROM `'._DB_PREFIX_.'configuration` WHERE `name` = "PS_SEARCH_BLACKLIST" AND  `id_shop_group` '.(($vdqwvv11k5rs === null)?'IS NULL':'= '.pSQL($vdqwvv11k5rs)).' AND  `id_shop` '.(($vc1dng4wlq5o === null)?'IS NULL':'= '.pSQL($vc1dng4wlq5o));
		$vrk5y55qnrr2 = Db::getInstance()->getValue($vxj43jnx2v2y);
		$vxj43jnx2v2y = 'SELECT `value` FROM `'._DB_PREFIX_.'configuration_lang` WHERE `id_configuration` = '.pSQL($vrk5y55qnrr2).' AND  `id_lang` = '.$vodwm3w531gc;
		if (count(Db::getInstance()->executeS($vxj43jnx2v2y)) == 0)
			return array();
		$v33rix13bged = Configuration::get('PS_SEARCH_BLACKLIST', $vodwm3w531gc, $vdqwvv11k5rs, $vc1dng4wlq5o);
		$vdu0eatc4bfd = array();
		$vdu0eatc4bfdtoremove = explode('|', $v33rix13bged);
		sort($vdu0eatc4bfdtoremove);
		foreach ($vdu0eatc4bfdtoremove as $v5fhkbjqjham)
		{
			$v5fhkbjqjham = $v5fhkbjqjham;
			$v5fhkbjqjhamord = array('id_option'=>$v5fhkbjqjham, 'name'=>$v5fhkbjqjham);
			array_push($vdu0eatc4bfd, $v5fhkbjqjhamord);
		}
		return $vdu0eatc4bfd;
	}
	public function a($vdqwvv11k5rs, $vc1dng4wlq5o, $vodwm3w531gc = null)
	{
		if (!$vodwm3w531gc) $vodwm3w531gc = $this->context->language->id;
		$v43kxmpshykz = array();
		$vxj43jnx2v2y = 'SELECT distinct substring(word,1,1) as letter FROM `'._DB_PREFIX_.'search_word`
				WHERE id_lang = '.$vodwm3w531gc.$this->sr($vdqwvv11k5rs, $vc1dng4wlq5o).' ORDER BY word;';
		$vnkqu1vz2dsb = Db::getInstance()->executeS($vxj43jnx2v2y);
		foreach ($vnkqu1vz2dsb as $vlevkjir4o3s)
		{
			$vqmcdjthokr4 = Tools::strtoupper($vlevkjir4o3s['letter']);
			$v0khu0mvml5y = array('id_option'=>$vqmcdjthokr4, 'name'=>$vqmcdjthokr4);
			array_push($v43kxmpshykz, $v0khu0mvml5y);
		}
		return $v43kxmpshykz;
	}
	protected function gl()
	{
		$vc1dng4wlq5o = (int)Shop::getContextShopID();
		$v4vcp13guzcr = false;
		if (Shop::getContext() != Shop::CONTEXT_SHOP)
			$v4vcp13guzcr = true;
		$v0cs2w14js4s = $this->context->controller->getLanguages();
		$vodwm3w531gcs = array();
		foreach ($v0cs2w14js4s as $vodwm3w531gcuage)
			if (in_array($vc1dng4wlq5o, $vodwm3w531gcuage['shops']) && $vodwm3w531gcuage['active'] == 1 || $v4vcp13guzcr)
			{
				$vodwm3w531gc = array('id_option'=>$vodwm3w531gcuage['id_lang'], 'name'=>$vodwm3w531gcuage['name']);
				array_push($vodwm3w531gcs, $vodwm3w531gc);
			}
		return $vodwm3w531gcs;
	}
	public function sp($vdqwvv11k5rs, $vc1dng4wlq5o, $vkmitxacxwqh, $vodwm3w531gc = null)
	{
		if (!$vodwm3w531gc) $vodwm3w531gc = $this->context->language->id;
		$v0dlvchnicgu = array();
		$vxj43jnx2v2y = 'SELECT id_search, expression FROM `'._DB_PREFIX_.'search_optimizer`
				WHERE id_lang = '.$vodwm3w531gc.$this->sr($vdqwvv11k5rs, $vc1dng4wlq5o).' AND products '.($vkmitxacxwqh?'!=':'=').' "" ORDER BY expression';
		$vnkqu1vz2dsb = Db::getInstance()->executeS($vxj43jnx2v2y);
		if (is_array($vnkqu1vz2dsb))
			foreach ($vnkqu1vz2dsb as $vlevkjir4o3s)
			{
				$v3hs3i3zl0ot = $vlevkjir4o3s['id_search'];
				$vx4uhhydyku4 = $vlevkjir4o3s['expression'];
				$vg04ijelh0je = array('id_option'=>$v3hs3i3zl0ot, 'name'=>$vx4uhhydyku4);
				array_push($v0dlvchnicgu, $vg04ijelh0je);
			}
		return $v0dlvchnicgu;
	}

	public function rv($vdqwvv11k5rs, $vc1dng4wlq5o, $v3hs3i3zl0ots)
	{
		$vxj43jnx2v2y = 'DELETE FROM `'._DB_PREFIX_.'search_optimizer`
				WHERE id_search IN ('.$v3hs3i3zl0ots.');';
		return Db::getInstance()->execute($vxj43jnx2v2y);
	}
	public function r($vdqwvv11k5rs, $vc1dng4wlq5o, $vg04ijelh0je, $vodwm3w531gc = null)
	{
		if (!$vodwm3w531gc) $vodwm3w531gc = $this->context->language->id;
		$vlevkjir4o3s = array();
		$vxj43jnx2v2y = 'SELECT products FROM `'._DB_PREFIX_.'search_optimizer`
				WHERE id_lang = '.$vodwm3w531gc.$this->sr($vdqwvv11k5rs, $vc1dng4wlq5o).' AND id_search = '.$vg04ijelh0je;
		$v3hs3i3zl0ots = Db::getInstance()->getValue($vxj43jnx2v2y);
		$vxj43jnx2v2y = 'SELECT pl.id_product, CONCAT(cl.name, " > ", pl.name) as name FROM `'._DB_PREFIX_.'product_lang` pl, `'._DB_PREFIX_.'product` p, `'._DB_PREFIX_.'category_lang` cl WHERE pl.id_product IN ('.$v3hs3i3zl0ots.') AND pl.id_lang = '.$vodwm3w531gc.$this->sr($vdqwvv11k5rs, $vc1dng4wlq5o, 'pl.').' AND pl.id_product = p.id_product AND p.id_category_default = cl.id_category AND cl.id_lang = '.$vodwm3w531gc;
		if (Shop::isFeatureActive())
			$vxj43jnx2v2y .= ' AND cl.id_shop = '.$vc1dng4wlq5o;
		$vxj43jnx2v2y .= ' ORDER BY FIND_IN_SET (pl.id_product, "'.$v3hs3i3zl0ots.'");';
		$vnkqu1vz2dsb = Db::getInstance()->executeS($vxj43jnx2v2y);
		if (is_array($vnkqu1vz2dsb))
			foreach ($vnkqu1vz2dsb as $vkmitxacxwqh)
			{
				$v3hs3i3zl0ot = $vkmitxacxwqh['id_product'];
				$v3ofs0gzm2g5 = $vkmitxacxwqh['name'];
				$vzja5mpoba1d = array('id_option'=>$v3hs3i3zl0ot, 'name'=>$v3ofs0gzm2g5);
				array_push($vlevkjir4o3s, $vzja5mpoba1d);
			}
		return $vlevkjir4o3s;
	}
	public function p($vdqwvv11k5rs, $vc1dng4wlq5o, $v5fhkbjqjhamord, $vodwm3w531gc)
	{
		$vdenlsnbdd5d = Configuration::get('SEARCH_OPTIMIZER_RECORD');
		if ($vdenlsnbdd5d == 1)
			$this->rsq($vdqwvv11k5rs, $vc1dng4wlq5o, '0');
		$vnkqu1vz2dsb = Search::find($vodwm3w531gc, $v5fhkbjqjhamord, 1, 1, 'position', 'desc', true, true, null);
		if ($vdenlsnbdd5d == 1)
			$this->rsq($vdqwvv11k5rs, $vc1dng4wlq5o, '1');
		$v2u1mbu1w45e = array();
		if (is_array($vnkqu1vz2dsb))
			foreach ($vnkqu1vz2dsb as $vkmitxacxwqh)
				array_push($v2u1mbu1w45e, $vkmitxacxwqh['cname'].' > '.$vkmitxacxwqh['pname']);
		return $v2u1mbu1w45e;
	}
	public function rsq($vdqwvv11k5rs, $vc1dng4wlq5o, $vyr4ic1ys1oj)
	{
		return Configuration::updateValue('SEARCH_OPTIMIZER_RECORD', $vyr4ic1ys1oj, false, $vdqwvv11k5rs, $vc1dng4wlq5o);
	}
	protected function pp()
	{
		$vehbemm2lusy = array();
		$voztdvl0j4vm = array(Tools::getValue('lang') => Tools::strtolower(Tools::getValue('wordsToRemove')));
		if (!Configuration::updateValue('PS_SEARCH_BLACKLIST', $voztdvl0j4vm))
		{
			array_push($vehbemm2lusy, $this->ge(3));
			return $vehbemm2lusy;
		}
		if (Tools::getValue('re-build') == 1)
		{
			$vxj43jnx2v2y = 'DELETE FROM `'._DB_PREFIX_.'search_index` WHERE id_word IN (SELECT id_word from `'._DB_PREFIX_.'search_word` WHERE id_shop = '.Shop::getContextShopID().')';
			if (!Db::getInstance()->execute($vxj43jnx2v2y))
			{
				array_push($vehbemm2lusy, $this->ge(4));
				return $vehbemm2lusy;
			}
			$vxj43jnx2v2y = 'DELETE FROM `'._DB_PREFIX_.'search_word` WHERE id_shop = '.Shop::getContextShopID();
			if (!Db::getInstance()->execute($vxj43jnx2v2y))
			{
				array_push($vehbemm2lusy, $this->ge(4));
				return $vehbemm2lusy;
			}
			ObjectModel::updateMultishopTable('Product', array('indexed' => 0), '1');
			if (!Search::indexation())
			{
				array_push($vehbemm2lusy, $this->ge(3));
				return $vehbemm2lusy;
			}
		}
		else
			array_push($vehbemm2lusy, $this->ge(1));
		array_push($vehbemm2lusy, $this->ge(0));
		return $vehbemm2lusy;
	}
	private function ge($v3hs3i3zl0ot)
	{
		switch ($v3hs3i3zl0ot)
		{
			case 0:
				return '<p class="alert alert-success">'.
					$this->l('Successful update').
				'</p>';
			case 1:
				return '<p class="alert alert-info">'.$this->l('Remember to re-build the shop search index after finishing the words management').'</p>';
			case 2:
				return '<p class="alert alert-warning">'.$this->l('You cannot manage search optimization from a "All Shops" or a "Group Shop" context, select directly the shop you want to manage').'</p>';
			case 3:
				return '<p class="alert alert-warning">'.$this->l('An error occurred while saving/re-building the search index').'</p>';
			case 4:
				return '<p class="alert alert-warning">'.$this->l('An error occurred while deleting the search index from data base').'</p>';
		}
	}
	private function gi()
	{
		$vxj43jnx2v2y = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'product_shop` WHERE active = 1 AND visibility != "none"'.Shop::addSqlRestriction();
		$vwihsixx3zig = Db::getInstance()->getValue($vxj43jnx2v2y);
		$vxj43jnx2v2y = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'product_shop` WHERE active = 1 AND indexed = 1 '.Shop::addSqlRestriction();
		$v2giwd3tahzg = Db::getInstance()->getValue($vxj43jnx2v2y);
		return $v2giwd3tahzg.'/'.$vwihsixx3zig;
	}
	private function sr($vdqwvv11k5rs, $vc1dng4wlq5o, $v0us5xkivvjc = '')
	{
		$vc5selt1q2rq = '';
		if ($vdqwvv11k5rs !== null)
			if ($vc1dng4wlq5o !== null)
				$vc5selt1q2rq = ' AND '.$v0us5xkivvjc.'id_shop = '.$vc1dng4wlq5o;
			else
			{
				$vugu04s24t12 = Shop::getShops(true, $vdqwvv11k5rs);
				$v3hs3i3zl0ots = array_map(function($v0ypii0omb0h)
				{
					return $v0ypii0omb0h['id_shop'];
				}, $vugu04s24t12);
				$vc5selt1q2rq = ' AND '.$v0us5xkivvjc.'id_shop IN ('.implode(',', $v3hs3i3zl0ots).')';
			}
		return $vc5selt1q2rq;
	}
}
