<?php

if (!defined('_PS_VERSION_')) exit;

class TptnTwitter extends Module{
   
    public function __construct(){
		
		$this->name = 'tptntwitter';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		parent::__construct();
		
		$this->displayName = $this->l('Twitter block - Templatin');
		$this->description = $this->l('Adds a Twitter block to right of your window.');
		
    }

	public function install()
	{
		Configuration::updateValue('tptntwitter_widgetUser', 'templatin');
		Configuration::updateValue('tptntwitter_widgetId', '373424014783299585');

		if (parent::install() == false || !$this->registerHook('displaytptnbody'))
			return false;
		return true;
	}

	public function uninstall()
	{ 
		if (parent::uninstall() == false ||
	 		!Configuration::deleteByName('tptntwitter_widgetUser') ||
            !Configuration::deleteByName('tptntwitter_widgetId')
	 	)
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '';
		
		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('tptntwitter_widgetUser', Tools::getValue('tptntwitter_user'));
			Configuration::updateValue('tptntwitter_widgetId', Tools::getValue('tptntwitter_id'));
			
			$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
	    }
		
		return $output.$this->displayForm();
	}
	
	public function displayForm()
	{
		return'
		<form action="'.Tools::htmlentitiesutf8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<p><label for="tptntwitter_user">'.$this->l('Twitter username').' :</label>
				<input type="text" id="tptntwitter_user" name="tptntwitter_user" style="width:200px;" value="'.Tools::safeOutput(Configuration::get('tptntwitter_widgetUser')).'" /></p>
				<p><label for="tptntwitter_id">'.$this->l('Twitter Widget ID').' :</label>
				<input type="text" id="tptntwitter_id" name="tptntwitter_id" style="width:200px;" value="'.Tools::safeOutput(Configuration::get('tptntwitter_widgetId')).'" /></p>
				<div class="margin-form">
					<input type="submit" name="submitModule" value="'.$this->l('Save settings').'" class="button" /></center>
				</div>
			</fieldset>
		</form>';
	}
	
	public function hookdisplaytptnbody($params){
		$this->smarty->assign(array(
			'twitterUser' => Configuration::get('tptntwitter_widgetUser'),
			'twitterWidgetId' => Configuration::get('tptntwitter_widgetId'),
			'iso_code' =>  $this->context->language->iso_code
		));
		return $this->display(__FILE__, 'tptntwitter.tpl');
	}

}
?>