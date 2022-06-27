<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class TptnThemeConfig extends Module
{
	public function __construct()
	{
		$this->name = 'tptnthemeconfig';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('LuxuryShop Configurator');
		$this->description = $this->l('Change the theme colors.');
	}
	
	public function install()
	{
		return (parent::install()
		&& Configuration::updateValue('Tconfig', 1)
		&& Configuration::updateValue('Tskin', 'Orange')
		&& $this->registerHook('header')
		&& $this->registerHook('displaytptnhead')
		&& $this->registerHook('displaytptnbody'));
	}
	
	public function uninstall()
	{
		//Delete configuration			
		return (Configuration::deleteByName('Tconfig')
				&& Configuration::deleteByName('Tskin')
				&& parent::uninstall() );
	}
	
	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{	
			Configuration::updateValue('Tconfig', Tools::getValue('t_config'));
			Configuration::updateValue('Tskin', Tools::getValue('t_skin'));
						
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
		}

		$html .= $this->renderForm();

		return $html;
	}

	public function renderForm() {

		$skin_colors = array(
			array('id' => 'Orange', 'label' => 'Orange'),
			array('id' => 'Green', 'label' => 'Green'),
			array('id' => 'Pink', 'label' => 'Pink'),
			array('id' => 'Yellow', 'label' => 'Yellow'),
			array('id' => 'Blue', 'label' => 'Blue')
		);

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Show Theme configurator?'),
						'name' => 't_config',
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
						),
					),
					array(
						'type' => 'select',
						'label' => $this->l('Color Scheme'),
						'name' => 't_skin',
						'options' => array(
							'query' => $skin_colors,
							'id' => 'id',
							'name' => 'label'
						),
					),					
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			't_config' => Tools::getValue('t_config', Configuration::get('Tconfig')),
			't_skin' => Tools::getValue('t_skin', Configuration::get('Tskin'))
		);
	}
	
	public function ConfigHead()
	{		
		$tptnskin = Configuration::get('Tskin');
		
		$htmlhead = '';
		$htmlhead .= '<link href="'._THEME_CSS_DIR_.'skins/'.$tptnskin.'.css" rel="stylesheet" data-name="skins" type="text/css" media="all" />';
		$htmlhead .= '		
					<script type="text/javascript">
						var tptn_theme_path = "'._THEME_CSS_DIR_.'";
					</script>';
		
		$ckskin = isset($_COOKIE['ckskin']) ? $_COOKIE['ckskin'] : '';
		
		if ( $ckskin != '') {
			$htmlhead .= '<link href="'._THEME_CSS_DIR_.'skins/'.$ckskin.'.css" rel="stylesheet" type="text/css" media="all" />';
		}
		return $htmlhead;
	}
	
	public function ConfigBody()
	{
		
		$htmlbody = '';
		$htmlbody .='
			<div id="tptn-config" class="hidden-sm hidden-xs">
				<a id="tptn-config-switch" class="config-open" href="#"></a>
				<div id="tptn-config-inner">
					<form method="post" action="index.php" id="config_form">
						
						<div class="tptn-config-block">
							<div class="tptn-config-title">Color Scheme</div>
							<a class="skin-input-item simptip" data-rel="Orange" data-tooltip="Orange" style="background-color:#ED5564"></a>
							<a class="skin-input-item simptip" data-rel="Green" data-tooltip="Green" style="background-color:#4DC7A0"></a>
							<a class="skin-input-item simptip" data-rel="Pink" data-tooltip="Pink" style="background-color:#DB6AC5"></a>
							<a class="skin-input-item simptip" data-rel="Yellow" data-tooltip="Yellow" style="background-color:#F1BE03"></a>
							<a class="skin-input-item simptip lastitem" data-rel="Blue" data-tooltip="Blue" style="background-color:#388BD1"></a>
							
							<p>Other color schemes can be added.</p>
						</div>
											
						<div class="tptn-config-block tptn-btn-block last">
							<button type="submit" name="apply" class="apply"><i class="fa fa-check left"></i>Apply</button>
							<button type="submit" name="reset" class="reset"><i class="fa fa-repeat left"></i>Reset</button>
						</div>						
					</form>
				</div>
			</div>';
		
			if ( isset($_REQUEST['apply']) ) {
				$ckskin = (isset($_COOKIE['ckskin'])) ? $_COOKIE['ckskin'] : '';
			} 
			elseif ( isset($_REQUEST['reset']) || !(isset($_REQUEST['reset'])) ) {
				$ckskin = '';
			} 

		return $htmlbody;
	}

	public function hookdisplaytptnhead($params)
	{
		return $this->ConfigHead();
	}
	
	public function hookdisplaytptnbody($params)
	{
		if(Configuration::get('Tconfig') == 0) return;
		return $this->ConfigBody();
	}
	
	public function hookHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'css/configstyle.css');
		$this->context->controller->addJqueryPlugin('cooki-plugin');
		$this->context->controller->addJqueryPlugin('idTabs');
		$this->context->controller->addJS($this->_path.'js/configjs.js');
	}
}
?>
