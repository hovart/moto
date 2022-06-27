<?php
require_once _PS_MODULE_DIR_ . 'mailchimp/MCAPI.class.php';

class MailChimp extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
	function __construct()
	{
		$this->name = 'mailchimp';
		parent::__construct();
		
		$this->tab = 'advertising_marketing';
		$this->version = '1.0';
		$this->displayName = $this->l('MailChimp Email Marketing');
		$this->description = $this->l('Send Newsletters through MailChimp to your customers directly from the Prestashop backend');
	}	
	
	public function install()
	{
	  if(!parent::install() ||
	     !$this->installModuleTab('AdminMailChimp', $this->l('MailChimp'), 7) ||
	     !$this->registerHook('rightColumn') ||
	     !$this->registerHook('header'))
	    return false;
	  return true;
	} 
	
	public function uninstall()
	{
	  if(!parent::uninstall() || !$this->uninstallModuleTab('AdminMailChimp'))
	    return false;
	  return true;
	} 
	
	private function installModuleTab($tabClass, $tabName, $idTabParent)
	{
	  @copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$tabClass.'.gif');
	  $oTab = new Tab();
	  foreach (Language::getLanguages() as $language) {
			$oTab->name[$language['id_lang']] = 'MailChimp';
	  }
	  $oTab->class_name = $tabClass;
	  $oTab->module = $this->name;
	  $oTab->id_parent = $idTabParent;
	  if(!$oTab->save())
	    return false;
	  return true;
	} 
	
	private function uninstallModuleTab($tabClass)
	{
	  $idTab = Tab::getIdFromClassName($tabClass);
	  if($idTab != 0)
	  {
	    $tab = new Tab($idTab);
	    $tab->delete();
	    return true;
	  }
	  return false;
	} 
	
	public function getContent()
	{	
		if (Tools::isSubmit('submit')) //salveaza user si parola
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
			{
				Configuration::updateValue($this->name.'_apikey', Tools::getValue('apikey'));
				Configuration::updateValue($this->name.'_testcampainemails', Tools::getValue('testcampainemails'));
				Configuration::updateValue($this->name.'_formcode', Tools::getValue('formcode'), true);
				$this->_html .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
			}
			else
			{
				$this->_html .= '<div class="alert error">';
				foreach ($this->_postErrors AS $err)
				{
					$this->_html .= '<p>' . $err . '</p>';
				}
				$this->_html .= '</div>';
			}
		}		
		$this->addCss();
		$this->_displayForm();
		return $this->_html;
	}
	
	private function _displayForm()
	{
		
		$this->_html .='
		  <form action="" method="post" class="mailchimp-config">
	      <fieldset>
	        <legend>
	          <img src="' . _MODULE_DIR_ . $this->name . '/images/account.gif" alt="" title="" /> ' . $this->l('MailChimp Login Details') . '
	        </legend>
	        <div id="account">
	        	<p><span class="text">' . $this->l('MailChimp ApiKey') . ':</span><br><input type="text" name="apikey" value="' . Configuration::get($this->name.'_apikey') . '"></p>
	        	<p><span class="text">' . $this->l('Test Email') . ':</span><br><input type="text" name="testcampainemails" value="' . Configuration::get($this->name.'_testcampainemails') . '"></p>
	        	<p><span class="text">' . $this->l('Newsletter Form Code') . ':</span><br><textarea class="mailchimp-textarea" name="formcode" rows="15" cols="80">' . Configuration::get($this->name.'_formcode') . '</textarea></p>
	        	<p><input type="submit" class="button" name="submit" value="'.$this->l('Save').'" class="button" /></p>	        	
	        </div>
	        <div class="info">
	        	<h1>' . $this->l('Instructions') . '</h1>
	        	<p>' . $this->l('You can find or generate your MailChimp ApiKey in your MailChimp Dashboard under <span class=imp>Account > Extras > API Keys&Info</span> ') . '</p>
	        	<p>' . $this->l('Enter your <span class=imp>email address or a test</span> address to see a campaign test before you send an actual campaign to users ') . '</p>
	        	<p>' . $this->l('From your MailChimp DashBoard you can generate a <span class=imp>custom subscription form</span> for registering users under a custom List directly from your website, use this input to paste the code, and a submit form will appear in your shop, you can move it like any other block from the <span class=imp>Manage Hooks Menu</span> ') . '</p>
	        	<p class="done">' . $this->l('After you succesfully set up your MailChimp API, Test Email and Newslletter Code you can filter and import users using the top menu named <span class=imp>"MailChimp"</span> right under the <span class=imp>Modules Tab</span>') . '</p>
	        	<p align="center">You don\'t have an account?<br><a class="create" href="http://www.mailchimp.com/signup?pid=seerkan&source=website">' . $this->l('Create a MailChimp Account') . '</a></p>
	        </div>
	        </fieldset>
			</form>';
	}
	
	private function _postValidation()
	{			
		$sApikey = Tools::getValue('apikey');
		$sTestEmails = Tools::getValue('testcampainemails');

		if(strlen($sApikey) == 0)
			$this->_postErrors[] = $this->l('API key is invalid.');

		if(strlen($sTestEmails) == 0)
			$this->_postErrors[] = $this->l('You need to add at least one email address used for testing a campain.');
		
		if(!Validate::isEmail($sTestEmails))
			$this->_postErrors[] = $this->l('Invalid test email address.');
	}
	
	public function hookRightColumn($params)
	{
	  global $smarty;
	  $smarty->assign('MAILCHIMP_FORM', Configuration::get($this->name.'_formcode'));
	  return $this->display(__FILE__, 'mailchimp.tpl');
	}
	
		public function hookLeftColumn($params)
	{
	  global $smarty;
	  $smarty->assign('MAILCHIMP_FORM', Configuration::get($this->name.'_formcode'));
	  return $this->display(__FILE__, 'mailchimp.tpl');
	}
	
	public function hookHeader($params)
	{
		Tools::addCSS(($this->_path).'mailchimp.css', 'all');
	}
	
	private function addCss()
	{
		$sCssPath = "../modules/" . $this->name . "/" . $this->name . ".css";
		echo '<script type="text/javascript">';
		echo "var headID = document.getElementsByTagName('head')[0];         
		var cssNode = document.createElement('link');
		cssNode.type = 'text/css';
		cssNode.rel = 'stylesheet';
		cssNode.href = '$sCssPath';
		cssNode.media = 'screen';
		headID.appendChild(cssNode)";
		echo'</script>';
	}
	
	
	/*
	 * update last added users timestamp
	 */
	private function getLastCheckTimestamp()
	{
		//if first time use import all users to DB
		if( Configuration::get($this->name.'_lastUserUpdate') == "")
		{
			$this->updateLastUpdateTimestamp();
			return "";
		}
				//if all users already imported run incremental
		
		return $this->updateLastUpdateTimestamp();
	}
	
	private function updateLastUpdateTimestamp()
	{
		$sNewTimestamp = date("Y-m-d H:m:s");
		Configuration::updateValue($this->name.'_lastUserUpdate', $sNewTimestamp);
		return $sNewTimestamp;
	}
	
	/*
	 * Used also like cronjob needs to be static
	 */
	public function addNewUsersToDefaultList()
	{
		$oApi = new MCAPI(Configuration::get($this->name.'_apikey'));
		$sErrors = "";
		$iDefaultListId = Configuration::get($this->name.'_defaultListId');
		$sTimeStamp = $this->getLastCheckTimestamp();
		
		if($sTimeStamp != "")
			$sCondition = " AND date_add > '" . $sTimeStamp ."'" ;
		
		//no default list is selected return
		if(strlen($iDefaultListId) == 0)
			return;

		//select only newly added users
		$result = Db::getInstance()->ExecuteS('SELECT firstname, lastname, email FROM `'._DB_PREFIX_.'customer` WHERE newsletter = 1' . $sCondition);
		
		if ($result)
			foreach ($result as $row)
			{
				$aCutomerParams = array('FNAME'=>$row['firstname'], 'LNAME'=>$row['lastname'], 
                  'GROUPINGS'=>array()
                    );
				// By default this sends a confirmation email - you will not see new members
				// until the link contained in it is clicked!
				$oApi->listSubscribe($iDefaultListId, $row['email'], $aCutomerParams );
				if ($oApi->errorCode){
					$sErrors .= 'MailChimp error code: ' . $oApi->errorCode . '.Message: ' . $oApi->errorMessage . "<br>";
				}
			}
			
		if($sErrors != "")
			return $sErrors;
		else
		{
			if($sTimeStamp == "") 
				return $this->l("All users have been added to default list");
			else
				return $this->l("Users created until " . $sTimeStamp . " have been added to the selected list");
		}
	}
}