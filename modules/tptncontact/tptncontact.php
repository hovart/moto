<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class TptnContact extends Module
{
	public function __construct()
	{
		$this->name = 'tptncontact';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

		parent::__construct();

		$this->displayName = $this->l('Block Contact - Templatin');
		$this->description = $this->l('Adds a block to add information about contacting the shop.');
	}
	
	public function install()
	{
		return (parent::install()
				&& Configuration::updateValue('tptncontact_address1', '123, Riverdale Tower,')
				&& Configuration::updateValue('tptncontact_address2', 'North Block, ABC Road,')
				&& Configuration::updateValue('tptncontact_address3', 'YourCity YC7890.')
				&& Configuration::updateValue('tptncontact_phone', '1800-123-4567')
				&& Configuration::updateValue('tptncontact_email', 'name@domain.com')
				&& Configuration::updateValue('tptncontact_facebook', 'http://www.facebook.com/YourName')
				&& Configuration::updateValue('tptncontact_twitter', 'http://www.twitter.com/YourName')
				&& Configuration::updateValue('tptncontact_pinterest', 'http://www.pinterest.com/YourName')
				&& Configuration::updateValue('tptncontact_google', '#')
				&& Configuration::updateValue('tptncontact_linkedin', '#')
				&& Configuration::updateValue('tptncontact_youtube', '#')
				&& $this->registerHook('footer'));
	}
	
	public function uninstall()
	{
		//Delete configuration			
		return (Configuration::deleteByName('tptncontact_address1') && Configuration::deleteByName('tptncontact_address2') && Configuration::deleteByName('tptncontact_address3')
				&& Configuration::deleteByName('tptncontact_phone') && Configuration::deleteByName('tptncontact_email') && Configuration::deleteByName('tptncontact_facebook')
				&& Configuration::deleteByName('tptncontact_twitter') && Configuration::deleteByName('tptncontact_pinterest') && Configuration::deleteByName('tptncontact_google')
				&& Configuration::deleteByName('tptncontact_linkedin') && Configuration::deleteByName('tptncontact_youtube')
				&& parent::uninstall());
	}
	
	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (isset($_POST['submitModule']))
		{	
			Configuration::updateValue('tptncontact_address1', ((isset($_POST['address1']) && $_POST['address1'] != '') ? $_POST['address1'] : ''));
			Configuration::updateValue('tptncontact_address2', ((isset($_POST['address2']) && $_POST['address2'] != '') ? $_POST['address2'] : ''));
			Configuration::updateValue('tptncontact_address3', ((isset($_POST['address3']) && $_POST['address3'] != '') ? $_POST['address3'] : ''));
			Configuration::updateValue('tptncontact_phone', ((isset($_POST['phone']) && $_POST['phone'] != '') ? $_POST['phone'] : ''));
			Configuration::updateValue('tptncontact_email', ((isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : ''));
			Configuration::updateValue('tptncontact_facebook', ((isset($_POST['facebook']) && $_POST['facebook'] != '') ? $_POST['facebook'] : ''));
			Configuration::updateValue('tptncontact_twitter', ((isset($_POST['twitter']) && $_POST['twitter'] != '') ? $_POST['twitter'] : ''));
			Configuration::updateValue('tptncontact_pinterest', ((isset($_POST['pinterest']) && $_POST['pinterest'] != '') ? $_POST['pinterest'] : ''));
			Configuration::updateValue('tptncontact_google', ((isset($_POST['google']) && $_POST['google'] != '') ? $_POST['google'] : ''));
			Configuration::updateValue('tptncontact_linkedin', ((isset($_POST['linkedin']) && $_POST['linkedin'] != '') ? $_POST['linkedin'] : ''));
			Configuration::updateValue('tptncontact_youtube', ((isset($_POST['youtube']) && $_POST['youtube'] != '') ? $_POST['youtube'] : ''));
						
			$html .= '<div class="conf confirm">'.$this->l('Configuration updated').'</div>';
		}

		$html .= '
		<h2>'.$this->displayName.'</h2>
		<form action="'.Tools::htmlentitiesutf8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>	
				<p><label for="address1">'.$this->l('Address line-1').' :</label>
				<input type="text" id="address1" name="address1" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_address1')).'" /></p>
				<p><label for="address2">'.$this->l('Address line-2').' :</label>
				<input type="text" id="address2" name="address2" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_address2')).'" /></p>
				<p><label for="address3">'.$this->l('Address line-3').' :</label>
				<input type="text" id="address3" name="address3" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_address3')).'" /></p>
				<p><label for="phone">'.$this->l('Phone number').' :</label>
				<input type="text" id="phone" name="phone" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_phone')).'" /></p>
				<p><label for="email">'.$this->l('Email').' :</label>
				<input type="text" id="email" name="email" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_email')).'" /></p>
				<p><label for="facebook">'.$this->l('Facebook URL').' :</label>
				<input type="text" id="facebook" name="facebook" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_facebook')).'" /></p>
				<p><label for="twitter">'.$this->l('Twitter URL').' :</label>
				<input type="text" id="twitter" name="twitter" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_twitter')).'" /></p>
				<p><label for="pinterest">'.$this->l('Pinterest URL').' :</label>
				<input type="text" id="pinterest" name="pinterest" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_pinterest')).'" /></p>
				<p><label for="google">'.$this->l('Google+ URL').' :</label>
				<input type="text" id="google" name="google" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_google')).'" /></p>
				<p><label for="linkedin">'.$this->l('Linkedin URL').' :</label>
				<input type="text" id="linkedin" name="linkedin" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_linkedin')).'" /></p>
				<p><label for="youtube">'.$this->l('Youtube URL').' :</label>
				<input type="text" id="youtube" name="youtube" size="58" value="'.Tools::safeOutput(Configuration::get('tptncontact_youtube')).'" /></p>
				<div class="margin-form">
					<input type="submit" name="submitModule" value="'.$this->l('Update settings').'" class="button" /></center>
				</div>
			</fieldset>
		</form>
		';
		
		return $html;
	}
	
	public function hookFooter($params)
	{	
		global $smarty;
		
		$smarty->assign(array(
			'tptncontact_address1' => Configuration::get('tptncontact_address1'),
			'tptncontact_address2' => Configuration::get('tptncontact_address2'),
			'tptncontact_address3' => Configuration::get('tptncontact_address3'),
			'tptncontact_phone' => Configuration::get('tptncontact_phone'),
			'tptncontact_email' => Configuration::get('tptncontact_email'),
			'tptncontact_facebook' => Configuration::get('tptncontact_facebook'),
			'tptncontact_twitter' => Configuration::get('tptncontact_twitter'),
			'tptncontact_pinterest' => Configuration::get('tptncontact_pinterest'),
			'tptncontact_google' => Configuration::get('tptncontact_google'),
			'tptncontact_linkedin' => Configuration::get('tptncontact_linkedin'),
			'tptncontact_youtube' => Configuration::get('tptncontact_youtube')
		));
		return $this->display(__FILE__, 'tptncontact.tpl');
	}
}
?>
