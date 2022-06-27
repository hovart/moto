<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnBrands extends Module
{
	public function __construct()
	{
		$this->name = 'tptnbrands';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Brands Carousel');
		$this->description = $this->l('Displays carousel for brands on homepage.');
	}

	public function install()
	{
		if ( (parent::install() == false) || ($this->registerHook('displayHome') == false) )
				return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	public function hookDisplayHome($params)
	{
		$this->smarty->assign(array(
			'manufacturers' => Manufacturer::getManufacturers()
		));
		
		return $this->display(__FILE__, 'tptnbrands.tpl', $this->getCacheId());
	}

}
