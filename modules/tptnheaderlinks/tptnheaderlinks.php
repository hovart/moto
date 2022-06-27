<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnHeaderLinks extends Module
{
	public function __construct()
	{
		$this->name = 'tptnheaderlinks';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		parent::__construct();
		
		$this->displayName = $this->l('Header Links');
		$this->description = $this->l('Adds a block that displays account and sign in texts in header.');
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('displayNav'));
	}
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}
	
	public function hookDisplayNav($params)
	{
		if (!$this->active)
			return;

		$this->smarty->assign('logged', $this->context->customer->isLogged());

		return $this->display(__FILE__, 'tptnheaderlinks.tpl');
	}
}
