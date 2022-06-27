<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnSearch extends Module
{
	public function __construct()
	{
		$this->name = 'tptnsearch';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

		parent::__construct();

		$this->displayName = $this->l('Block Search - Templatin');
		$this->description = $this->l('Adds a quick search block.');
                
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('top') || !$this->registerHook('header'))
			return false;
		return true;
	}
	
	public function hookHeader($params)
	{
		if (Configuration::get('PS_SEARCH_AJAX'))
		$this->context->controller->addJqueryPlugin('autocomplete');
	}

	public function hookTop($params)
	{
		if (Tools::getValue('search_query') || !$this->isCached('tptnsearch.tpl', $this->getCacheId('tptnsearch')))
		{
			$this->smarty->assign(array(
				'ENT_QUOTES' =>	ENT_QUOTES,
				'search_ssl' =>	Tools::usingSecureMode(),
				'ajaxsearch' =>	Configuration::get('PS_SEARCH_AJAX'),
				'instantsearch' => Configuration::get('PS_INSTANT_SEARCH'),
				'self' => dirname(__FILE__)
			));
		}
		return $this->display(__FILE__, 'tptnsearch.tpl', Tools::getValue('search_query') ? null : $this->getCacheId('tptnsearch'));
	}
}

