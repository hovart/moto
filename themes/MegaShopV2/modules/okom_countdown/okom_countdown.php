<?php
/*
*  
* 	Copyright (c) 2014 Okom3pom.com
*	Module CountDown for Prestashop
*
*	Released under the GNU General Public License
*
*	Author Okom3pom.com -> Thomas Roux
*	Version 2.4 - 03/09/2014
* 
*/

if (!defined('_PS_VERSION_'))
	exit;

class okom_countdown extends Module
{
	
	private $_html = '';
	private $_postErrors = array();
	
	
	public function __construct()
	{
		$this->name = 'okom_countdown';
		$this->tab = 'front_office_features';
		$this->version = 2.4;
		$this->author = 'Okom3pom';
		$this->generic_name = 'okom_countdown';
		$this->need_instance = 0;

		if(version_compare(_PS_VERSION_, '1.6.0.0', '>='))
			$this->bootstrap = true;

		parent::__construct();	

		$this->displayName = $this->l('Count Down ');
		$this->description = $this->l('Display a countdown in front office with message .');
		$this->checkforupdates();
		
	}

	public function install()
	{
		return 
		parent::install() 
		&& $this->registerHook('displayTop') 
		&& $this->registerHook('displayHeader')
		&& Configuration::updateValue('OKOM_COUNTDOWN_ACTIVATE', 1)		
		&& Configuration::updateValue('OKOM_COUNTDOWN_HOOK', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_HOOK', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_END_DATE', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_MESSAGE1', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_MESSAGE2', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE1', '')
		&& Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE2', '');

	}
	
	public function uninstall()
	{
		return 
		parent::uninstall()
		&& $this->unregisterHook('displayHeader')
		&& $this->registerHook('displayTop')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_ACTIVATE')		
		&& Configuration::deleteByName('OKOM_COUNTDOWN_MESSAGE1')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_MESSAGE2')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_PRODUCT_MESSAGE1')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_PRODUCT_MESSAGE2')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_END_DATE')		
		&& Configuration::deleteByName('OKOM_COUNTDOWN_HOOK')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_PRODUCT')
		&& Configuration::deleteByName('OKOM_COUNTDOWN_PRODUCT_HOOK');
		
	}


	public function hookDisplayHeader()
	{

			if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 || Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 1 )
			{
				
				$this->context->controller->addCSS($this->_path.'css/okom_countdown.css', 'all');
			}
			
			if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 ) {
				
				$this->context->controller->addCSS($this->_path.'css/jquery.countdown.css', 'all');
				$this->context->controller->addJS($this->_path.'js/jquery.countdown.js', 'all');
			}

			
			if( Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 1 )
			{
				$this->context->controller->addCSS($this->_path.'css/jquery.productcountdown.css', 'all');
				$this->context->controller->addJS($this->_path.'js/jquery.countdownp.js', 'all');
			}
			
	}
	
	// For all next hook method -> || unnecessary because no hook in common ?
	
	public function hookDisplayTop()
	{
		
		if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
		(Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayTop' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayTop') )
			return $this->displayMainCountDown('displayTop');
		else
			return;
		
	}
	
	public function hookDisplayHome()
	{
		
		if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
		(Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayHome' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayHome') )
			return $this->displayMainCountDown('displayHome');
		else
			return;
		
	}
	
	public function hookDisplayTopColumn()
	{
		
		if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
		(Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayTopColumn' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayTopColumn') )
			return $this->displayMainCountDown('displayHome');
		else
			return;
		
	}
	
	
	public function hookDisplayRightColumn()
	{
		
		if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
		(Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayRightColumn' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayRightColumn'  ))
			return $this-> displayMainCountDown('displayRighColumn');
		else
			return;
		
	}
	
	public function hookDisplayLeftColumn( )
	{
		
		if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
		(Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayLeftColumn' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayRightColumn'  ))
			return $this-> displayMainCountDown('displayLeftColumn');
		else
			return;
		
	}
	
	public function hookDisplayFooter( )
	{
		
		
	if( Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 && 
	( Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayFooter'  || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayFooter'  ))
			return $this-> displayMainCountDown('displayFooter');
		else
			return;
		
	}	
	
	public function hookDisplayFooterProduct( $params )
	{
		
		$end_date = false;
		
		if( Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 1 && 
		( Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayFooterProduct' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayFooterProduct' ) 
		&& $this->registerHook('displayFooterProduct') )
		{
			
			if (  $params['product']->specificPrice['to'] != '0000-00-00 00:00:00' )
				$end_date = $params['product']->specificPrice['to'];
			
			return $this->displayProductCountDown('displayFooterProduct',$end_date);
		
		
		}
		else
			return;
		
	}
	
	public function hookDisplayProductButtons( $params )
	{
		
		$end_date = false;
		
		if( Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 1 && 
		( Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayProductButtons' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayProductButtons' ) 
		&& $this->registerHook('displayProductButtons') )
		{
			
			if (  $params['product']->specificPrice['to'] != '0000-00-00 00:00:00' )
				$end_date = $params['product']->specificPrice['to'];
			
			return $this->displayProductCountDown('displayProductButtons',$end_date);
		
		
		}
		else
			return;
		
	}
	
	
	public function hookDisplayRightColumnProduct( $params )
	{
		
		$product = New Product((int)Tools::getValue('id_product'), true, $this->context->language->id, $this->context->shop->id);
		
		
		
		$end_date = false;
		
		if( Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 1 &&
		( Configuration::get('OKOM_COUNTDOWN_HOOK') == 'displayRightColumnProduct' || Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayRightColumnProduct' ) 
		&& $this->registerHook('displayRightColumnProduct') )
		{
			
			if (  $product->specificPrice['to'] != '0000-00-00 00:00:00' )
				$end_date = $product->specificPrice['to'];
			
			return $this->displayProductCountDown('displayProductButtons',$end_date);
		
		
		}
		else
			return;
		
	}
	

	protected function displayMainCountDown( $hook , $end_date = false)
	{
		
			$okom_countdown_message1 = strval(Configuration::get('OKOM_COUNTDOWN_MESSAGE1', $this->context->language->id));
			$okom_countdown_message2 = strval(Configuration::get('OKOM_COUNTDOWN_MESSAGE2', $this->context->language->id));
			
			if(version_compare(_PS_VERSION_, '1.6.0.0', '<'))
				$okom_countdown_end_date = Configuration::get('OKOM_COUNTDOWN_END_DATE').':00';		
			else	
				$okom_countdown_end_date = Configuration::get('OKOM_COUNTDOWN_END_DATE');
			
			
			if( isset( $okom_countdown_end_date ) && Validate::isDate($okom_countdown_end_date) && class_exists('DateTime')   )
			{
				
				$nb_minutes =  $this->compare_date($okom_countdown_end_date);

					if( $nb_minutes < 1 )
					return;
			}
			else
				return;
				
				
			$this->smarty->assign(array(
				'okom_countdown_message1' => $okom_countdown_message1,
				'okom_countdown_message2' => $okom_countdown_message2,
				'okom_countdown_end_date' => $okom_countdown_end_date
				
			));

		if( $hook == 'displayTop'   )
			return $this->display(__FILE__, 'views/templates/hooks/hook_top.tpl');
		
		else if( $hook == 'displayFooter'  )
			return $this->display(__FILE__, 'views/templates/hooks/hook_footer.tpl');
					
		else
			return $this->display(__FILE__, 'views/templates/hooks/hook_left_column.tpl');
		
		
	}
	
	protected function displayProductCountDown( $hook , $end_date = false)
	{
		
			
			if( isset( $end_date ) && Validate::isDate( $end_date ) && class_exists('DateTime')  )
			{
				
				$nb_minutes =  $this->compare_date($end_date);

					if( $nb_minutes < 1 )
						return;
			}
			else
				return;
				
			if( Configuration::get('OKOM_COUNTDOWN_PRODUCT') == 0 )
				return;
				
			$okom_countdown_product_message1 = strval(Configuration::get('OKOM_COUNTDOWN_PRODUCT_MESSAGE1', $this->context->language->id));
			$okom_countdown_product_message2 = strval(Configuration::get('OKOM_COUNTDOWN_PRODUCT_MESSAGE2', $this->context->language->id));
				
				
			$this->smarty->assign(array(
				'okom_countdown_product_message1' => $okom_countdown_product_message1,
				'okom_countdown_product_message2' => $okom_countdown_product_message2,
				'end_date' => $end_date 
				
			));
	
			return $this->display(__FILE__, 'views/templates/hooks/hook_product.tpl');
		
		
		
	}	
	
	private function _postValidation()
	{

		if (Tools::isSubmit('submit'.$this->name))
		{
			
			$languages = Language::getLanguages(false);
			
				if (!Tools::getValue('OKOM_COUNTDOWN_HOOK'))
					$this->_postErrors[] = $this->l('Hook is requierd');
				if (!Tools::getValue('OKOM_COUNTDOWN_END_DATE') && Configuration::get('OKOM_COUNTDOWN_ACTIVATE') == 1 )
					$this->_postErrors[] = $this->l('No date = No CountDown');
					
					foreach ($languages as $lang)
					{
						if (  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_MESSAGE1_'.$lang['id_lang'] ) ) )
								$this->_postErrors[] =  sprintf( $this->l('Invalid message1 for %s'), $lang['name']  );
						if (  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_MESSAGE2_'.$lang['id_lang'] ) ) )
								$this->_postErrors[] = sprintf( $this->l('Invalid message 2 for %s'), $lang['name']  );
								
						if (  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE1_'.$lang['id_lang'] ) ) )
								$this->_postErrors[] =  sprintf( $this->l('Invalid message1 for %s'), $lang['name']  );
						if (  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE2_'.$lang['id_lang'] ) ) )
								$this->_postErrors[] = sprintf( $this->l('Invalid message 2 for %s'), $lang['name']  );
			
					}
				
				if(  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_CSS' ) ) )	
					$this->_postErrors[] = sprintf( $this->l('Invalid terms in css '), $lang['name']  );
				else
					if( !file_put_contents(dirname(__FILE__).'/css/jquery.countdown.css',Tools::getValue('OKOM_COUNTDOWN_CSS')))
						$this->_postErrors[] = $this->l('Error while saving  .css');
						
				if(  !Validate::isCleanHtml( Tools::getValue('OKOM_COUNTDOWN_PRODTUCT_CSS' ) ) )	
					$this->_postErrors[] = sprintf( $this->l('Invalid terms in css '), $lang['name']  );
				else
					if( !file_put_contents(dirname(__FILE__).'/css/jquery.productcountdown.css',Tools::getValue('OKOM_COUNTDOWN_PRODUCT_CSS')))
						$this->_postErrors[] = $this->l('Error while saving  .css');
			
		}	

	
	}
	
	
	public function _postProcess()
	{
		if (Tools::isSubmit('submit'.$this->name))
		{
			$languages = Language::getLanguages(false);
			$conf = array();
			
			foreach ($languages as $lang)
			{

				$conf['OKOM_COUNTDOWN_MESSAGE1'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_MESSAGE1_'.$lang['id_lang']));
				$conf['OKOM_COUNTDOWN_MESSAGE2'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_MESSAGE2_'.$lang['id_lang']));
				$conf['OKOM_COUNTDOWN_PRODUCT_MESSAGE1'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE1_'.$lang['id_lang']));
				$conf['OKOM_COUNTDOWN_PRODUCT_MESSAGE2'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE2_'.$lang['id_lang']));
			}
			
			Configuration::updateValue('OKOM_COUNTDOWN_ACTIVATE', (int)Tools::getValue('OKOM_COUNTDOWN_ACTIVATE'));
			Configuration::updateValue('OKOM_COUNTDOWN_MESSAGE1', $conf['OKOM_COUNTDOWN_MESSAGE1'],true);
			Configuration::updateValue('OKOM_COUNTDOWN_MESSAGE2', $conf['OKOM_COUNTDOWN_MESSAGE2'],true);
			Configuration::updateValue('OKOM_COUNTDOWN_END_DATE', Tools::getValue('OKOM_COUNTDOWN_END_DATE'));
			Configuration::updateValue('OKOM_COUNTDOWN_HOOK', strval(Tools::getValue('OKOM_COUNTDOWN_HOOK')));
			Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_HOOK', strval(Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK')));
			Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT', (int)Tools::getValue('OKOM_COUNTDOWN_PRODUCT'));
			Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE1', $conf['OKOM_COUNTDOWN_PRODUCT_MESSAGE1'],true);
			Configuration::updateValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE2', $conf['OKOM_COUNTDOWN_PRODUCT_MESSAGE2'],true);

				
				$this->unregisterHook('displayLeftColumn');
				$this->unregisterHook('displayRightColumn');
				$this->unregisterHook('displayTop');
				$this->unregisterHook('displayProductButtons');
				$this->unregisterHook('displayLeftColumnProduct');
				$this->unregisterHook('displayRightColumnProduct');
				$this->unregisterHook('displayFooter');
				$this->unregisterHook('displayHome');
				
				
				// or unnecessary no hook in common ?
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayLeftColumn' ||Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayLeftColumn' )
				
					$this->registerHook('displayLeftColumn');

				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayRightColumn' || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayRightColumn' )
				
					$this->registerHook('displayRightColumn');
				
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayTopColumn' || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayTopColumn' )
				
					$this->registerHook('displayTopColumn');						
	
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayTop'  || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayTop' )
				
					$this->registerHook('displayTop');		
					
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayHome'  || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayHome' )
				
					$this->registerHook('displayHome');						
				
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayFooter'  || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayFooter' )
				
					$this->registerHook('displayFooter');
					
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayProductButtons' || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayProductButtons' )
				
					$this->registerHook('displayProductButtons');
					
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayFooterProduct'  || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayFooterProduct' )
				
					$this->registerHook('displayFooterProduct');
				
				if( Tools::getValue('OKOM_COUNTDOWN_HOOK') == 'displayRightColumnProduct'  || Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK') == 'displayRightColumnProduct' )
				
					$this->registerHook('displayRightColumnProduct');
				


								
			$this->_clearCache('hook_top.tpl');
			$this->_html .= $this->displayConfirmation($this->l('The settings have been updated.'));
		}

		return;
	}

	public function getContent()
	{
			if(version_compare(_PS_VERSION_, '1.6.0.0', '<'))
			{
			$this->context->controller->addJqueryUI('ui.slider');
			$this->context->controller->addJqueryUI('ui.datepicker');

			$this->context->controller->addCSS($this->_path.'css/jquery-ui-timepicker-addon.css','all');
			$this->context->controller->addJS($this->_path.'js/jquery-ui-timepicker-addon.js');

			$this->_html .= $this->ModuleDatepicker("datepickers", true);
			
			}
	

		
			if (Tools::isSubmit('submit'.$this->name))
			{
				$this->_postValidation();
					if (!count($this->_postErrors))
							$this->_postProcess();
					else
						foreach ($this->_postErrors as $err)
							$this->_html .= $this->displayError($err);
			}
			else
			$this->_html .= '<br />';

			return $this->_html.$this->hlp().$this->renderForm().$this->hlp();
	}
	
	
	protected function createSelectOptions()
	{
		$selectHook['main'] = array(
			
			array(
				'id_option' => 'displayTop',
				'name' => $this->l('Top of all pages (displayTop)')
			),
			array(
				'id_option' => 'displayTopColumn',
				'name' => $this->l('displayTopColumn (displayTopColumn)')
			),			
			array(
				'id_option' => 'displayHome',
				'name' => $this->l('Home page (displayHome)')
			),
			array(
				'id_option' => 'displayRightColumn',
				'name' => $this->l('Right Column (displayRightColumn)')
			),
			array(
				'id_option' => 'displayLeftColumn',
				'name' => $this->l('Left Column (displayLeftColumn)')
			),
			array(
				'id_option' => 'displayFooter',
				'name' => $this->l('Footer of all pages (displayFooter)')
			)
			

		);
		
		$selectHook['product'] = array(

			array(
				'id_option' => 'displayProductButtons',
				'name' => $this->l('Under Add to cart button (displayProductButtons)')
			),
			
			array(
				'id_option' => 'displayRightColumnProduct',
				'name' => $this->l('Right column Product (displayRightColumnProduct)')
			),
			
			array(
				'id_option' => 'displayFooterProduct',
				'name' => $this->l('Footer product (displayFooterProduct)')
			)
			
		);
	
		return $selectHook;
	
	}
	
	protected function renderForm()
	{
		
		$icon = 'icon-cogs';
		$type = 'icon'; 
		$date_type = 'datetime';
		$class = '';
		$radio = 'switch';

		if(version_compare(_PS_VERSION_, '1.6.0.0', '<'))
		{
			$icon = _PS_ADMIN_IMG_ .'cog.gif';
			$type = 'image'; 
			$date_type = 'text';
			$class = 't';
			$radio = 'radio';
		}
			
		$selectHook = $this->createSelectOptions();
	
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Options for main CountDown'),
				$type  => $icon
				),
				
			'input' => array(	
			
				array(
					'name' => 'OKOM_COUNTDOWN_ACTIVATE',
					'type' => $radio,
					'class' => $class,
					'label' => $this->l('Active main CountDown'),
					'desc' => $this->l('Active main CountDown  '),
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
							),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
							)
						)
					),
		

				array(
					'name' => 'OKOM_COUNTDOWN_HOOK',
					'type' => 'select',
					'label' => $this->l('Select Hook'),
					'desc' => $this->l('Select the hook where the message will be displayed.'),
					'required' => true, 
					'options' => array(
						'query' => $selectHook['main'] ,
						'id' => 'id_option',
						'name' => 'name'
					)
				),

				array(
				'name' => 'OKOM_COUNTDOWN_MESSAGE1',
				'type' => 'textarea',
				'label' => $this->l('Message 1'),
				'desc' => $this->l('The message to be displayed before CountDown.'),
				'lang' => true,
				 'cols' => 60,
				'rows' => 10,
				'class' => 'rte',
				'autoload_rte' => true,
				),
				
				array(
				'name' => 'OKOM_COUNTDOWN_MESSAGE2',
				'type' => 'textarea',
				'label' => $this->l('Message 2'),
				'desc' => $this->l('The message to be displayed after CountDown.'),
				'lang' => true,
				 'cols' => 60,
				'rows' => 10,
				'class' => 'rte',
				'autoload_rte' => true,
				),
				
				array(
				'name' => 'OKOM_COUNTDOWN_END_DATE',
				'type' => $date_type,
				'label' => $this->l('End date for main count down '),
				'desc' => $this->l('Select Date for the end of main CountDown')

				)				
			),
			'submit' => array(
				'title' => $this->l('Save')
			)
		);
		
		$fields_form[1]['form'] = array(
			'legend' => array(
			'title' => $this->l('Options for product Count Down'),
			$type  => $icon
			),
				
			'input' => array(			
					
					array(
					'name' => 'OKOM_COUNTDOWN_PRODUCT',
					'type' => $radio,
					'class' => $class,
					'label' => $this->l('Active CountDown for product'),
					'desc' => $this->l('Active CountDown for product width a specific price and a end date'),
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
							),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
							)
						)
					),
					

				array(
					'name' => 'OKOM_COUNTDOWN_PRODUCT_HOOK',
					'type' => 'select',
					'label' => $this->l('Select Hook'),
					'desc' => $this->l('Select the hook where the message will be displayed.'),
					'required' => true, 
					'options' => array(
						'query' => $selectHook['product'],
						'id' => 'id_option',
						'name' => 'name'
					)
				),
				array(
				'name' => 'OKOM_COUNTDOWN_PRODUCT_MESSAGE1',
				'type' => 'textarea',
				'label' => $this->l('Message 1'),
				'desc' => $this->l('The message to be displayed before CountDown.'),
				'lang' => true,
				 'cols' => 60,
				'rows' => 10,
				'class' => 'rte',
				'autoload_rte' => true,
				),
				
				array(
				'name' => 'OKOM_COUNTDOWN_PRODUCT_MESSAGE2',
				'type' => 'textarea',
				'label' => $this->l('Message 2'),
				'desc' => $this->l('The message to be displayed after CountDown.'),
				'lang' => true,
				 'cols' => 60,
				'rows' => 10,
				'class' => 'rte',
				'autoload_rte' => true,
				)
				
			),
			'submit' => array(
				'title' => $this->l('Save')
			)
		);
		
		$fields_form[2]['form'] = array(
			'legend' => array(
			'title' => $this->l('Edit CSS and graphic option'),
			$type  => $icon
			),
				
			'input' => array(	
			

				array(
				'name' => 'OKOM_COUNTDOWN_CSS',
				'type' => 'textarea',
				'label' => $this->l('CSS for main count down'),
				'desc' => $this->l('Edit main count down.css file'),
				'lang' => false,
				'cols' =>60,
				'rows' => 10,

				),
				
				array(
				'name' => 'OKOM_COUNTDOWN_PRODUCT_CSS',
				'type' => 'textarea',
				'label' => $this->l('CSS for product'),
				'desc' => $this->l('Edit product count down.css file'),
				'lang' => false,
				'cols' =>60,
				'rows' => 10,

				)
					

				
			),
			'submit' => array(
				'title' => $this->l('Save')
			)
		);
		
		
		
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
			);

		return $helper->generateForm($fields_form);
	}


	public function compare_date( $date1 , $date2  =  false ) 
	{
	
		if( class_exists('DateTime') && method_exists('DateTime','diff')  )
		{
		
			if( isset( $date2 ) )
				$date2 =  date('Y-m-d h:i' );
	
			$datetime1 = new DateTime($date2);
			$datetime2 = new DateTime($date1);
			$interval = $datetime1->diff($datetime2);
			return $interval->format(' %I ');
			
		}
		else
		{ 
		
					$datetime1 = new DateTime($date2);
					$datetime2 = new DateTime($date1);
					if( $datetime1 < $datetime2 )
						return 1;
					else
						return -1;
		
		
		}
			
	}


	protected function getConfigFieldsValues()
	{
		$languages = Language::getLanguages(false);
		$fields = array();

		foreach ($languages as $lang)
		{
			$fields['OKOM_COUNTDOWN_MESSAGE1'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_MESSAGE1_'.$lang['id_lang'], Configuration::get('OKOM_COUNTDOWN_MESSAGE1', $lang['id_lang'])));
			$fields['OKOM_COUNTDOWN_MESSAGE2'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_MESSAGE2_'.$lang['id_lang'], Configuration::get('OKOM_COUNTDOWN_MESSAGE2', $lang['id_lang'])));
			$fields['OKOM_COUNTDOWN_PRODUCT_MESSAGE1'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE1_'.$lang['id_lang'], Configuration::get('OKOM_COUNTDOWN_PRODUCT_MESSAGE1', $lang['id_lang'])));
			$fields['OKOM_COUNTDOWN_PRODUCT_MESSAGE2'][$lang['id_lang']] = strval(Tools::getValue('OKOM_COUNTDOWN_PRODUCT_MESSAGE2_'.$lang['id_lang'], Configuration::get('OKOM_COUNTDOWN_PRODUCT_MESSAGE2', $lang['id_lang'])));		
		
		}
		
		$css_file =  file_get_contents(dirname(__FILE__).'/css/jquery.countdown.css');
		$css_product_file =  file_get_contents(dirname(__FILE__).'/css/jquery.productcountdown.css');
	
		return array(
			'OKOM_COUNTDOWN_MESSAGE1' => $fields['OKOM_COUNTDOWN_MESSAGE1'],
			'OKOM_COUNTDOWN_MESSAGE2' => $fields['OKOM_COUNTDOWN_MESSAGE2'],
			'OKOM_COUNTDOWN_PRODUCT_MESSAGE1' => $fields['OKOM_COUNTDOWN_PRODUCT_MESSAGE1'],
			'OKOM_COUNTDOWN_PRODUCT_MESSAGE2' => $fields['OKOM_COUNTDOWN_PRODUCT_MESSAGE2'],
			'OKOM_COUNTDOWN_CSS' => $css_file,
			'OKOM_COUNTDOWN_PRODUCT_CSS' => $css_product_file,
			
			'OKOM_COUNTDOWN_ACTIVATE' => Tools::getValue('OKOM_COUNTDOWN_ACTIVATE', Configuration::get('OKOM_COUNTDOWN_ACTIVATE')),
			'OKOM_COUNTDOWN_PRODUCT_HOOK' => Tools::getValue('OKOM_COUNTDOWN_PRODUCT_HOOK', Configuration::get('OKOM_COUNTDOWN_PRODUCT_HOOK')),
			'OKOM_COUNTDOWN_HOOK' => Tools::getValue('OKOM_COUNTDOWN_HOOK', Configuration::get('OKOM_COUNTDOWN_HOOK')),
						
			'OKOM_COUNTDOWN_PRODUCT' => Tools::getValue('OKOM_COUNTDOWN_PRODUCT', Configuration::get('OKOM_COUNTDOWN_PRODUCT')),
			'OKOM_COUNTDOWN_END_DATE' => Tools::getValue('OKOM_COUNTDOWN_END_DATE', Configuration::get('OKOM_COUNTDOWN_END_DATE')),
			);
	}
	


		function checkforupdates()
		{

			if (ini_get("allow_url_fopen")) 
			{
				if (function_exists("file_get_contents"))
				{					

					$lastversion = (float)@file_get_contents('http://www.okom3pom.com/dev-modules/'.$this->generic_name.'.version');
					
										
						if( number_format($lastversion,2) != number_format($this->version,2) )
						{
							$this->warning = $this->l('Une nouvelle version du module est disponible sur http://www.okom3pom.com/');
							
							$this->_html = '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button>'.$this->l('Une nouvelle version du module est disponible sur http://www.okom3pom.com/').'</div>';
						}	

				}
			}

		}
		
		private function hlp()
		{

			return '
			<div style="text-align: center;">
			<br/> '.$this->l('Help me improve my free modules').'
			<br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input name="cmd" type="hidden" value="_s-xclick" /> 
			<input name="encrypted" type="hidden" value="-----BEGIN PKCS7-----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-----END PKCS7-----" /><input alt="PayPal - la solution de paiement en ligne la plus simple et la plus s&eacute;curis&eacute;e !" name="submit" src="https://www.paypalobjects.com/fr_XC/i/btn/btn_donateCC_LG.gif" type="image" /> 
			<img style="display: block; margin-left: auto; margin-right: auto;" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></form>
			</div>';

		}
		
		private function ModuleDatepicker($class, $time)
		{
   
 
		return '
			<script type="text/javascript">
			$(function() { 
   
				$("#OKOM_COUNTDOWN_END_DATE").datetimepicker({
				
					dateFormat: "yy-mm-dd"

				});
    
			});
	
			</script>';
		}
	
	
}
