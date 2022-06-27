<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class TptnOverride extends Module
{
	public function __construct()
	{
		$this->name = 'tptnoverride';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Override default behaviors');
		$this->description = $this->l('Overrides PrestaShops classes and controllers');
	}
}
?>
