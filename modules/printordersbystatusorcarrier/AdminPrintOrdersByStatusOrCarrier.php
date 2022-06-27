<?php

class AdminPrintOrdersByStatusOrCarrier extends AdminTab {

	public $multishop_context = -1;
	public $multishop_context_group = true;

	public function __construct()
	{
		$my_context = Context::getContext();
		$configuration = $my_context->link->getAdminLink('AdminModules')
		.'&configure=printordersbystatusorcarrier&module_name=printordersbystatusorcarrier';

		Tools::redirectAdmin($configuration);
	}
}