<?php

if (!defined('_PS_VERSION_'))
	exit;

class PrintOrdersByStatusOrCarrier extends Module
{
	public $orders;
	public $orders_states;
	public $carriers;

	public function __construct()
	{
		$this->name = 'printordersbystatusorcarrier';
		$this->tab = 'billing_invoicing';
		$this->version = '1.1';
		$this->module_key = '10b40b2184f6e88ebe8b63ba31524f60';
		$this->bootstrap = true;
		$this->display = 'view';
		$this->author = 'Ixycom';
		$this->need_instance = 1;

		parent::__construct();

		$this->displayName = $this->l('Print orders by status or carrier');
		$this->description = $this->l('Save time printing your orders invoice/delivery slip by status or carrier.');
	}

	public function install()
	{
		global $smarty;

		if (!$this->installModuleTab('AdminPrintOrdersByStatusOrCarrier', Tab::getIdFromClassName('AdminParentOrders'),$this->context->language->id))
			return false;

		if (!parent::install() || !$this->registerHook('backOfficeHeader')
			|| !Configuration::updateValue('PRINTORDERS_STOPRATING', 0)
			|| !Configuration::updateValue('PRINTORDERS_INVOICES_START', '')
			|| !Configuration::updateValue('PRINTORDERS_INVOICES_END', '')
			|| !Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_START', '')
			|| !Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_END', '')
		)
			return false;

		/*if (_PS_VERSION_ >= '1.5')
		{
			$this->override(_PS_MODULE_DIR_.'/'.$this->name.'/override/controllers/admin/AdminPdfController.php',
				_PS_ROOT_DIR_.'/override/controllers/admin/AdminPdfController.php');

			$this->override(_PS_MODULE_DIR_.'/'.$this->name.'/override/classes/order/OrderInvoice.php',
				_PS_ROOT_DIR_.'/override/classes/order/OrderInvoice.php');
		}

		if (_PS_VERSION_ >= '1.5.4')
		{
			if (file_exists(_PS_ROOT_DIR_.'/cache/class_index.php'))
				unlink(_PS_ROOT_DIR_.'/cache/class_index.php');
		}

		$current_dir = defined('__DIR__') ? __DIR__ : dirname(__FILE__);
		@chmod($current_dir, 0755);
		$this->chmodr($current_dir.'/', '');
		$smarty->clearAllCache(null);*/

		return true;
	}

	public function installModuleTab($tab_class, $id_tab_parent, $id_lang = null)
	{
		$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $names[Language::getIdByIso('fr')] = 'Impression des commandes';
		$names[Language::getIdByIso('en')] = 'Print Orders';

        if (!array_key_exists($defaultLanguage, $names))
            $names[$defaultLanguage] = $this->l('Print Orders');

		$this->uninstallModuleTab($tab_class);
		
		@copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$tab_class.'.gif');
		$tab = new Tab();
		$tab->class_name	= $tab_class;
        $tab->name = $names;
		$tab->module		= $this->name;
		$tab->id_parent		= $id_tab_parent;

		if (!$tab->save())
			return false;

		Configuration::updateValue('PRINTORDERS_TAB_INSTALLED', 1);
		return true;
	}

	public function uninstall()
	{
		global $smarty;

		if (!parent::uninstall()
			|| !Configuration::deleteByName('PRINTORDERS_STOPRATING')
			|| !Configuration::deleteByName('PRINTORDERS_TAB_INSTALLED')
			|| !Configuration::deleteByName('PRINTORDERS_INVOICES_START')
			|| !Configuration::deleteByName('PRINTORDERS_INVOICES_END')
			|| !Configuration::deleteByName('PRINTORDERS_DELIVERY_SLIPS_START')
			|| !Configuration::deleteByName('PRINTORDERS_DELIVERY_SLIPS_END')
			|| !$this->uninstallModuleTab('AdminPrintOrdersByStatusOrCarrier'))
			return false;

		/*if (file_exists(_PS_ROOT_DIR_.'/override/controllers/admin/AdminPdfController.php'))
			unlink(_PS_ROOT_DIR_.'/override/controllers/admin/AdminPdfController.php');

		if (file_exists(_PS_ROOT_DIR_.'/override/classes/order/OrderInvoice.php'))
			unlink(_PS_ROOT_DIR_.'/override/classes/order/OrderInvoice.php');

		if (_PS_VERSION_ >= '1.5.4')
		{
			if (file_exists(_PS_ROOT_DIR_.'/cache/class_index.php'))
				unlink(_PS_ROOT_DIR_.'/cache/class_index.php');
		}*/

		$smarty->clearAllCache(null);

		return true;
	}

	public function uninstallModuleTab($tab_class)
	{
		$id_tab = Tab::getIdFromClassName($tab_class);

		if ($id_tab != 0)
		{
			$tab = new Tab($id_tab);
			$tab->delete();
			return true;
		}

		return false;
	}

	public function override($origine, $destination)
	{
		$header = $origine;
		$fpheader = fopen($header, 'r+');
		$contentfpdf = '';

		while (!feof($fpheader))
		{
			$char_header = fgetc($fpheader);
			$contentfpdf .= $char_header;
		}
		fclose($fpheader);

		$fpwrite = fopen($destination, 'w+');
		fseek($fpwrite, 0);
		fputs($fpwrite, $contentfpdf);
		fclose($fpwrite);

		if (!file_exists($destination))
		{
			$errors = $this->l('An error occurred in the function Override the destination file does not found, the module may not be working properly');
			$this->AddMessageErrors($errors);
		}
	}

	private function chmodr($rep, $ssrep)
	{
		if ($dir = opendir($rep))
		{
			while (($fich = readdir($dir)) !== false)
			{
				if ($fich != '.' && $fich != '..')
				{
					$chemin = "$rep$fich";
					if (is_dir($chemin))
						$this->chmodr($chemin.'/', ($ssrep == '' ? $fich : $ssrep.'/'.$fich));
					@chmod($chemin, 0755);
				}
			}
		}
	}

	public function postValidation()
	{
		$output = '';

		$date_invoices_start = Tools::getValue('po_invoices_start') ? Tools::getValue('po_invoices_start') : '';
		if ($date_invoices_start != '')
		{
			$date_invoices_start_tab = explode('/', $date_invoices_start);
			$start_invoices = mktime('00', '00', '00', $date_invoices_start_tab[1], $date_invoices_start_tab[0], $date_invoices_start_tab[2]);
			$start_invoices_sql = $date_invoices_start_tab[2].'-'.$date_invoices_start_tab[1].'-'.$date_invoices_start_tab[0].' 00:00:00';
			Configuration::updateValue('PRINTORDERS_INVOICES_START', $start_invoices_sql);
		}
		else
		{
			Configuration::updateValue('PRINTORDERS_INVOICES_START', '');
		}

		$date_invoices_end = Tools::getValue('po_invoices_end') ? Tools::getValue('po_invoices_end') : '';
		if ($date_invoices_end != '')
		{
			$date_invoices_end_tab = explode('/', $date_invoices_end);
			$end_invoices = mktime('23', '59', '59', $date_invoices_end_tab[1], $date_invoices_end_tab[0], $date_invoices_end_tab[2]);
			$end_invoices_sql = $date_invoices_end_tab[2].'-'.$date_invoices_end_tab[1].'-'.$date_invoices_end_tab[0].' 23:59:59';
			Configuration::updateValue('PRINTORDERS_INVOICES_END', $end_invoices_sql);
		}
		else
		{
			Configuration::updateValue('PRINTORDERS_INVOICES_END', '');
		}

		$date_delivery_slips_start = Tools::getValue('po_delivery_slips_start') ? Tools::getValue('po_delivery_slips_start') : '';
		if ($date_delivery_slips_start != '')
		{
			$date_delivery_slips_start_tab = explode('/', $date_delivery_slips_start);
			$start_delivery_slips = mktime('00', '00', '00', $date_delivery_slips_start_tab[1], $date_delivery_slips_start_tab[0], $date_delivery_slips_start_tab[2]);
			$start_delivery_slips_sql = $date_delivery_slips_start_tab[2].'-'.$date_delivery_slips_start_tab[1].'-'.$date_delivery_slips_start_tab[0].' 00:00:00';
			Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_START', $start_delivery_slips_sql);
		}
		else
		{
			Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_START', '');
		}

		$date_delivery_slips_end = Tools::getValue('po_delivery_slips_end') ? Tools::getValue('po_delivery_slips_end') : '';
		if ($date_delivery_slips_end != '')
		{
			$date_delivery_slips_end_tab = explode('/', $date_delivery_slips_end);
			$end_delivery_slips = mktime('23', '59', '59', $date_delivery_slips_end_tab[1], $date_delivery_slips_end_tab[0], $date_delivery_slips_end_tab[2]);
			$end_delivery_slips_sql = $date_delivery_slips_end_tab[2].'-'.$date_delivery_slips_end_tab[1].'-'.$date_delivery_slips_end_tab[0].' 23:59:59';
			Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_END', $end_delivery_slips_sql);
		}
		else
		{
			Configuration::updateValue('PRINTORDERS_DELIVERY_SLIPS_END', '');
		}

		if ( ($end_invoices) && ($start_invoices) && ($end_invoices < $start_invoices) )
			$output .= $this->displayError($this->l('Please enter a valid date interval for invoices'));
		elseif ( ($end_delivery_slips) && ($start_delivery_slips) && ($end_delivery_slips < $start_delivery_slips) )
			$output .= $this->displayError($this->l('Please enter a valid date interval for delivery slips'));
		elseif ( ($end_invoices) || ($start_invoices) || ($end_delivery_slips) || ($start_delivery_slips) )
			$output .= $this->displayConfirmation($this->l('Configuration saved.'));
		else {}

		return $output;
	}

	public function postProcess()
	{
		if (isset($_GET['stop_rating']))
		{
			Configuration::updateValue('PRINTORDERS_STOPRATING', 1);
			die;
		}

		$output = '';

		if (Tools::getValue('po_invoices_start')
			|| Tools::getValue('po_invoices_end')
			|| Tools::getValue('po_delivery_slips_start')
			|| Tools::getValue('po_delivery_slips_end'))
			$output .= $this->postValidation();

		if (Tools::isSubmit('PrintInvoices'))
			$output .= $this->processInvoices(explode('_', pSQL(Tools::getValue('po_invoices'))),Tools::getValue('po_invoices_start'),Tools::getValue('po_invoices_end'));
		else if (Tools::isSubmit('PrintDeliverySlips'))
			$output .= $this->processDeliverySlips(explode('_', pSQL(Tools::getValue('po_delivery_slips'))),Tools::getValue('po_delivery_slips_start'),Tools::getValue('po_delivery_slips_end'));

		return $output;
	}

	protected function processInvoices($params = null, $po_invoices_start = null, $po_invoices_end = null)
	{
		$output = '';
		$id_carrier = $params[0];
		$id_order_state = $params[1];

		if ($po_invoices_start != '')
		{
			$date_invoices_start_tab = explode('/', $po_invoices_start);
			$start_invoices_sql = $date_invoices_start_tab[2].'-'.$date_invoices_start_tab[1].'-'.$date_invoices_start_tab[0].' 00:00:00';
		}

		if ($po_invoices_end != '')
		{
			$date_invoices_end_tab = explode('/', $po_invoices_end);
			$end_invoices_sql = $date_invoices_end_tab[2].'-'.$date_invoices_end_tab[1].'-'.$date_invoices_end_tab[0].' 23:59:59';
		}

		if (count(OrderInvoice::getInvoicesByStatusAndCarriers($id_order_state, $id_carrier, $start_invoices_sql, $end_invoices_sql)))
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminPdf').'&submitAction=generateInvoicesPDF3&id_order_state='
				.$id_order_state.'&id_carrier='.$id_carrier.'&start='.$start_invoices_sql.'&end='.$end_invoices_sql);
		else
			$output .= $this->displayError($this->l('No invoice'));

		return $output;
	}

	protected function processDeliverySlips($params = null, $po_delivery_slips_start = null, $po_delivery_slips_end = null)
	{
		$output = '';
		$id_carrier = $params[0];
		$id_order_state = $params[1];

		if ($po_delivery_slips_start != '')
		{
			$date_delivery_slips_start_tab = explode('/', $po_delivery_slips_start);
			$start_delivery_slips_sql = $date_delivery_slips_start_tab[2].'-'.$date_delivery_slips_start_tab[1].'-'.$date_delivery_slips_start_tab[0].' 00:00:00';
		}

		if ($po_delivery_slips_end != '')
		{
			$date_delivery_slips_end_tab = explode('/', $po_delivery_slips_end);
			$end_delivery_slips_sql = $date_delivery_slips_end_tab[2].'-'.$date_delivery_slips_end_tab[1].'-'.$date_delivery_slips_end_tab[0].' 23:59:59';
		}

		if (count(OrderInvoice::getInvoicesByStatusAndCarriers($id_order_state, $id_carrier, $start_delivery_slips_sql, $end_delivery_slips_sql)))
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminPdf').'&submitAction=GenerateDeliverySlipsPDF2&id_order_state='
				.$id_order_state.'&id_carrier='.$id_carrier.'&start='.$start_delivery_slips_sql.'&end='.$end_delivery_slips_sql);
		else
			$output .= $this->displayError($this->l('No delivery slip'));

		return $output;
	}

	public function renderForm()
	{
		$this->fields_form[0]['form'] =
			array(
				'legend' => array(
					'title' => $this->l('Invoices'),
					'icon'  => 'icon-cogs'
				),
				'input'  => array(
					array(
						'type'    => 'select',
						'label'   => $this->l('Invoices'),
						'name'    => 'po_invoices',
						'options' => array(
							'query' => $this->getInvoices(),
							'id'    => 'id',
							'name'  => 'name'
						)
					),
					array(
						'type'  => 'text',
						'id'    => 'datepicker',
						'label' => $this->l('Start Date :'),
						'name'  => 'po_invoices_start',
						'hint'  => $this->l('empty field = no filter on date'),
						'required' => false,
						'class' => 'fixed-width-md'
					),
					array(
						'type'  => 'text',
						'id'    => 'datepicker2',
						'label' => $this->l('End Date :'),
						'name'  => 'po_invoices_end',
						'hint'  => $this->l('empty field = no filter on date'),
						'required' => false,
						'class' => 'fixed-width-md'
					)
				),
				'submit' => array(
					'name' => 'PrintInvoices',
					'title' => $this->l('Print Invoices'),
					'icon' => 'process-icon-preview'
				)
			);

		$this->fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Delivery Slips'),
				'icon'  => 'icon-cogs'
			),
			'input'  => array(
				array(
					'type'    => 'select',
					'label'   => $this->l('Delivery Slips'),
					'name'    => 'po_delivery_slips',
					'options' => array(
						'query' => $this->getDeliverySlips(),
						'id'    => 'id',
						'name'  => 'name'
					)
				),
				array(
					'type'  => 'text',
					'id'    => 'datepicker3',
					'label' => $this->l('Start Date :'),
					'name'  => 'po_delivery_slips_start',
					'hint'  => $this->l('empty field = no filter on date'),
					'required' => false,
					'class' => 'fixed-width-md'
				),
				array(
					'type'  => 'text',
					'id'    => 'datepicker4',
					'label' => $this->l('End Date :'),
					'name'  => 'po_delivery_slips_end',
					'hint'  => $this->l('empty field = no filter on date'),
					'required' => false,
					'class' => 'fixed-width-md'
				)
			),
			'submit' => array(
				'name' => 'PrintDeliverySlips',
				'title' => $this->l('Print Delivery Slips'),
				'icon' => 'process-icon-preview'
			)
		);

		$helper = new HelperForm();
		$helper->title = $this->l('Print Orders By Status Or Carrier');
		$helper->show_toolbar = true;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang =
			Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages'   => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm($this->fields_form);
	}


	public function getConfigFieldsValues()
	{
		$fields_value = array();

		$po_delivery_slips_start = (Configuration::get('PRINTORDERS_DELIVERY_SLIPS_START')) ? (Configuration::get('PRINTORDERS_DELIVERY_SLIPS_START')) : ('');
		if ($po_delivery_slips_start != '')
		{
			$po_delivery_slips_start = str_replace(' 00:00:00', '', $po_delivery_slips_start);
			$po_delivery_slips_start_tab = explode('-', $po_delivery_slips_start);
			$fields_value['po_delivery_slips_start'] = $po_delivery_slips_start_tab[1].'/'.$po_delivery_slips_start_tab[2].'/'.$po_delivery_slips_start_tab[0];
		}

		$po_delivery_slips_end = (Configuration::get('PRINTORDERS_DELIVERY_SLIPS_END')) ? (Configuration::get('PRINTORDERS_DELIVERY_SLIPS_END')) : ('');
		if ($po_delivery_slips_end != '')
		{
			$po_delivery_slips_end = str_replace(' 23:59:59', '', $po_delivery_slips_end);
			$po_delivery_slips_end_tab = explode('-', $po_delivery_slips_end);
			$fields_value['po_delivery_slips_end'] = $po_delivery_slips_end_tab[1].'/'.$po_delivery_slips_end_tab[2].'/'.$po_delivery_slips_end_tab[0];
		}

		$po_invoices_start = (Configuration::get('PRINTORDERS_INVOICES_START')) ? (Configuration::get('PRINTORDERS_INVOICES_START')) : ('');
		if ($po_invoices_start != '')
		{
			$po_invoices_start = str_replace(' 00:00:00', '', $po_invoices_start);
			$po_invoices_start_tab = explode('-', $po_invoices_start);
			$fields_value['po_invoices_start'] = $po_invoices_start_tab[1].'/'.$po_invoices_start_tab[2].'/'.$po_invoices_start_tab[0];
		}

		$po_invoices_end = (Configuration::get('PRINTORDERS_INVOICES_END')) ? (Configuration::get('PRINTORDERS_INVOICES_END')) : ('');
		if ($po_invoices_end != '')
		{
			$po_invoices_end = str_replace(' 23:59:59', '', $po_invoices_end);
			$po_invoices_end_tab = explode('-', $po_invoices_end);
			$fields_value['po_invoices_end'] = $po_invoices_end_tab[1].'/'.$po_invoices_end_tab[2].'/'.$po_invoices_end_tab[0];
		}

		return $fields_value;
	}

	public function getContent()
	{
		$this->getLists();

		$output = $this->postProcess();
		$output .= $this->getRating();
		$output .= $this->renderForm();
		$output .= $this->getCredit();

		return $output;
	}

	public function getCredit()
	{
		return '<div class="row text-center"><a class="center" href="http://www.ixycom.com" title="Agence web à Lille" target="_blank"><img
src="http://www.ixycom.com/images/logo-300.jpg" alt="Logo Ixycom" width="300" height="134" /></a></div><script type="text/javascript"
src="'.$this->_path.'js/admin.js"></script>
';
	}

	public function hookbackOfficeHeader()
	{
		if ( (Tools::getValue('module_name') == 'printordersbystatusorcarrier') || (Tools::getValue('configure') == 'printordersbystatusorcarrier') )
		{
			$this->context->controller->addJquery();
			$this->context->controller->addJqueryUi('ui.datepicker');
			$this->context->controller->addJS($this->_path.'js/printordersbystatusorcarrier.js');
		}
	}

	public function getRating()
	{
		$output = '';
		$stop_rating = (int)Configuration::get('PRINTORDERS_STOPRATING');
		if ($stop_rating != 1)
		{
			$output .= '
			<div id="stop_rating" class="row text-center">
				<div style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em; text-align: center;">
					<p class="invite">'
				.$this->l('You are satisfied with our module and want to encourage us to add new features ?')
				.'<br/><a href="http://addons.prestashop.com/ratings.php" target="_blank"><strong>'
				.$this->l('Please rate it on Prestashop Addons, and give us 5 stars !')
				.'</strong></a>
					</p>
					<p class="stop" style="display: block;"><a style="cursor: pointer">'
				.'['
				.$this->l('No thanks, I don\'t want to help you. Close this dialog.')
				.']
					 </a></p>
				</div>
			</div>';
		}

		return $output;
	}

	public function getInvoices()
	{
		$my_invoices = array();

		foreach ($this->carriers as $id_carrier => $name)
		{
			$orders = $this->orders[$id_carrier]['invoices'];
			foreach ($orders as $key => $order)
			{
				
				$n = count($orders[$key]);
				$option_name = $name.' - '.$key.' ('.$n.' '.($n > 1 ? $this->l('orders') : $this->l('order')).')';
				$my_invoices[] = array(
					'id'   => $id_carrier.'_'.$this->orders_states[$key],
					'name' => $option_name
				);
				
			}
		}

		return $my_invoices;
	}

	public function getDeliverySlips()
	{
		$my_delivery_slips = array();

		foreach ($this->carriers as $id_carrier => $name)
		{
			$orders = $this->orders[$id_carrier]['delivery_slips'];
			foreach ($orders as $key => $order)
			{
				$n = count($orders[$key]);
				$option_name = $name.' - '.$key.' ('.$n.' '.($n > 1 ? $this->l('orders') : $this->l('order')).')';
				$my_delivery_slips[] = array(
					'id'   => $id_carrier.'_'.$this->orders_states[$key],
					'name' => $option_name
				);
			}
		}

		return $my_delivery_slips;
	}

	public function getLists()
	{
		$orders_states = OrderState::getOrderStates((int)$this->context->language->id);
		$carriers = Carrier::getCarriers((int)$this->context->language->id, true, false, false, null, ALL_CARRIERS);

		foreach ($carriers as $carrier)
		{
			$id_carrier = $carrier['id_carrier'];

			foreach ($orders_states as $state)
			{
				$id_order_state = (int)$state['id_order_state'];
				$orders = $this->getOrderIdsByCarrierAndStatus($id_order_state, $id_carrier);
				$this->orders_states[$state['name']] = $id_order_state;

				if ($state['invoice'] == 1)
					$this->orders[$carrier['id_carrier']]['invoices'][$state['name']] = $orders;

				if ($state['delivery'] == 1)
					$this->orders[$carrier['id_carrier']]['delivery_slips'][$state['name']] = $orders;
			}

			$this->carriers[$id_carrier] = $carrier['name'];
		}
	}

	public function getOrderIdsByCarrierAndStatus($id_order_state, $id_carrier)
	{
		$my_orders_tab = array();

		$my_orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT id_order
		FROM '._DB_PREFIX_.'orders o
		WHERE '.(int)$id_order_state.' = (
			SELECT id_order_state
			FROM '._DB_PREFIX_.'order_history oh
			WHERE oh.id_order = o .id_order
			ORDER BY date_add DESC, id_order_history DESC
			LIMIT 1
		)
		AND id_carrier = '.(int)$id_carrier.'
		ORDER BY invoice_date ASC');

		foreach ($my_orders as $my_order)
			$my_orders_tab[] = (int)$my_order['id_order'];

		return $my_orders_tab;
	}
}