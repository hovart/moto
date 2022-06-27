<?php 

if (!defined('_PS_VERSION_'))
	exit;
	
class TptnFacebook extends Module {
	function __construct(){
		
		$this->name = 'tptnfacebook';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;		
        
		parent::__construct();

		$this->displayName = $this->l('Facebook block - Templatin');
		$this->description = $this->l('Adds Facebook block to right of your window.');
        
	}
    
	function install(){
		if (parent::install() == false 
		|| $this->registerHook('displaytptnbody') == false
		|| Configuration::updateValue('tptnfbbox_fanpageurl', 'http://www.facebook.com/templatin') == false){
			return false;
		}
        return true;
	}
    
	public function getContent(){
		$output = '';
		
		if (Tools::isSubmit('submit_settings')){
			Configuration::updateValue('tptnfbbox_fanpageurl', ((isset($_POST['new_fanpageurl']) && $_POST['new_fanpageurl'] != '') ? $_POST['new_fanpageurl'] : ''));
			$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
		}	   
		return $output.$this->displayForm();
	}

	public function displayForm(){
      	return'
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<legend>'.$this->l('Settings').'</legend>
					
				<label>'.$this->l('Facebook Page URL').'</label>
				<div class="margin-form">
					<input type="text" id="new_fanpageurl" name="new_fanpageurl" size="58" value="'.Tools::safeOutput(Configuration::get('tptnfbbox_fanpageurl')).'" />
					<p class="clear">'.$this->l('The URL of the Facebook Page for LikeBox').'</p>
				</div>
                              
				<div class="margin-form">
					<input type="submit" name="submit_settings" value="'.$this->l('Save Settings').'" class="button" />
				</div>
				
			</fieldset>                    
		</form>';
	}
   
	function hookdisplaytptnbody($params){
		$this->smarty->assign('fb_url', Configuration::get('tptnfbbox_fanpageurl'));
		return $this->display(__FILE__, 'tptnfacebook.tpl');
	}		   
}
?>