<?php
include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
require_once(_PS_MODULE_DIR_ . 'mailchimp/MCAPI.class.php');
include(_PS_MODULE_DIR_ .'mailchimp/mailchimp.php');

class AdminMailChimp extends AdminTab
{
	
	private $oApi = null;
	
	public function __construct()
	{
		global $cookie;
		
	 	$this->table = 'customer';
	 	$this->className = 'Customer';
	 	$this->name = 'mailchimp';
	 	$this->lang = false;
	 	$this->delete = true;
		$this->deleted = true;
		$this->requiredDatabase = true;
		$this->_join = 'LEFT JOIN ' ._DB_PREFIX_.'address as ad ON (a.id_customer = ad.id_customer) '.
					   'LEFT JOIN ' ._DB_PREFIX_.'country_lang as cl ON (ad.id_country = cl.id_country and cl.id_lang = ' . $cookie->id_lang .')' ;
		$this->_select = '(YEAR(CURRENT_DATE)-YEAR(`birthday`)) - (RIGHT(CURRENT_DATE, 5)<RIGHT(`birthday`, 5)) as age, 
		(select count(id_order) from ' ._DB_PREFIX_.'orders as o where a.id_customer = o.id_customer) as order_no,
		cl.name as country_name,
		ad.city as city';
		$genders = array(1 => $this->l('M'), 2 => $this->l('F'), 9 => $this->l('?'));
 		$this->fieldsDisplay = array(
 		'email' => array('title' => $this->l('E-mail address'), 'width' => 120, 'maxlength' => 19),
		'id_gender' => array('title' => $this->l('Gender'), 'width' => 25, 'align' => 'center', 'icon' => array(1 => 'male.gif', 2 => 'female.gif', 'default' => 'unknown.gif'), 'orderby' => false, 'type' => 'select', 'select' => $genders, 'filter_key' => 'a!id_gender'),
 		'country_name' => array('title' => $this->l('Country'), 'width' => 130,'type'=>'text','filter_key' => 'cl!name'),
 		'city' => array('title' => $this->l('City'), 'width' => 130,'type'=>'text','filter_key' => 'ad!city'),
		'age' => array('title' => $this->l('Age'), 'width' => 30, 'search' => false),
 		'order_no' => array('title' => $this->l('No. orders'), 'width' => 30, 'search' => false),
		'active' => array('title' => $this->l('Enabled'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false),
		'newsletter' => array('title' => $this->l('News.'), 'width' => 25, 'align' => 'center', 'type' => 'bool', 'icon' => array(0 => 'disabled.gif', 1 => 'enabled.gif'), 'orderby' => false),
		'date_add' => array('title' => $this->l('Registration'), 'width' => 30, 'type' => 'date', 'align' => 'right'));

 		parent::__construct();
		$this->oApi = new MCAPI(Configuration::get($this->name.'_apikey'));
	}
		
	public function display()
	{
		global $cookie,$currentIndex;
		$this->addCss();
		if(Tools::isSubmit('addNewUsers'))
		{
			echo $this->addNewSubscribedUsers();
		}
		if(Tools::isSubmit('addToList'))
		{
			echo $this->subscribeSelection();
		}
		
		if(Tools::isSubmit('removeFromList'))
		{
			echo $this->unsubscribeSelection();
		}
		
		if(Tools::isSubmit('sendCampain'))
		{
			echo $this->sendCampain();
		}
		
		if(Tools::isSubmit('sendTestCampain'))
		{
			echo $this->sendTestCampain();
		}
		echo '<fieldset class="mailchimp-main">
					<legend>
				           ' . $this->l('Manage Users') . '
				    </legend><p class="title">' . $this->l('Here you can sort the users you want subscribed or unsubsribed from your list, you can use multiple filters.') . '</p>';
		parent::getList((int)($cookie->id_lang));
		parent::displayList();
		parent::displayOptionsList();
		parent::includeSubTab('display');
	}
	
	public function getList($id_lang, $orderBy = NULL, $orderWay = NULL, $start = 0, $limit = NULL)
	{
		global $cookie;
		return parent::getList((int)($cookie->id_lang), !Tools::getValue($this->table.'Orderby') ? 'date_add' : NULL, !Tools::getValue($this->table.'Orderway') ? 'DESC' : NULL);
	}
	
	private function CheckBoxShit()
	{
		$result = true;
		if ($this->deleted)
		{
			foreach(Tools::getValue($this->table.'Box') as $id)
			{
				$toDelete = new $this->className($id);
				$toDelete->deleted = 1;
				$result = $result AND $toDelete->update();
			}
		}
		else
			$result = $object->deleteSelection(Tools::getValue($this->table.'Box'));

		if ($result)
			Tools::redirectAdmin($currentIndex.'&conf=2&token='.$token);
		$this->_errors[] = Tools::displayError('An error occurred while deleting selection.');
	}
	
	/*
	 * overwrite parent function
	 */
	public function displayListFooter($token = NULL)
	{
				echo'<input type="hidden" name="token" value="'.($token ? $token : $this->token).'" />';
		echo '</table>';
		echo'<p class="subscribe"><input type="submit" class="buttons" name="addToList" value="'.$this->l('Subscribe selection to:').'" class="button" /><br>';
		echo $this->getAllLists('subscriptionList');
		echo '</p>';
		echo'<p class="unsubscribe"><input type="submit" class="buttons" name="removeFromList" value="'.$this->l('Unsubscribe selection from:').'" class="button" /><br>';
		echo $this->getAllLists('unsubscribtionList');
		echo '</p>';
		echo '</td>
			</tr>
			<tr>
				<td>';
		echo $this->displayAutoSubscribe();
		echo '  </td>
			</tr>
		</table>
		</fieldset><br>';
		$this->displayUseCampain();
		echo '<br>';
		$this->displayCampainStatisticsMenu();
		
		 echo'</form>';
		if (isset($this->_includeTab) AND sizeof($this->_includeTab))
			echo '<br /><br />';
	}
  
	private function displayAutoSubscribe()
	{
		$sReturnHtml = '<br><br>' . $this->l('Select list to add new registered users wich selected to subscribe to the newsletter.');
		$sReturnHtml .= $this->getAllLists('autoAddList', true);
		$sReturnHtml .= '<input type="submit" class="button" name="addNewUsers" value="'.$this->l('Add new users').'" class="button" />';
		$sReturnHtml .= '<br><p style="font-size: 10px;">' . $this->l('*You can also add this to cron using http://www.yourdomain.com/modules/mailchimp/cron.php in cPanel') . '</p>';
		
		return $sReturnHtml;
	}
	
	/*
	 * Get all lists from MailChimp
	 */
	private function getAllLists($sSelecName, $bAutoselect = false)
	{
		$sReturnHtml = '';
		$aResult = $this->oApi->lists();

		if ($this->oApi->errorCode){
			$this->displayError('MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage);
		}
		else 
		{
			
			$sReturnHtml .= '<select name='. $sSelecName . '>';
			foreach ($aResult['data'] as $aList){
				if($bAutoselect && Configuration::get($this->name.'_defaultListId') == $aList['id'])
				{
  					$sReturnHtml .= '<option selected="selected" value="'.$aList['id'].'">'.$aList['name'].'</option>';
				}
				else
				{
					$sReturnHtml .= '<option value="'.$aList['id'].'">'.$aList['name'].'</option>';
				}
			}
			$sReturnHtml .= '</select>';
		}
		return $sReturnHtml;
	}
	
	/*
	 * Template for displaing error messages
	 */
	private function displayError($sContent)
	{
		return '<div class="alert error">' . $sContent . '</div>';
	}
	
	/*
	 * Template for displaing notification messages
	 */
	private function displayNotification($sContent)
	{
		return '<div class="conf confirm">'.$sContent.'</div>';
	}
	
	/*
	 * Subscribe selection to selected list
	 */
	private function subscribeSelection()
	{
		$sErrors = "";
		$aCustomerIds = Tools::getValue($this->table.'Box');
		foreach ($aCustomerIds as $aUserId)
		{
			$oCustomer = new $this->className($aUserId);
			if(!is_null($oCustomer))
			{
				$aCutomerParams = array('FNAME'=>$oCustomer->firstname, 'LNAME'=>$oCustomer->lastname, 
	                  'GROUPINGS'=>array()
	                    );
				// By default this sends a confirmation email - you will not see new members
				// until the link contained in it is clicked!
				$this->oApi->listSubscribe(Tools::getValue('subscriptionList'), $oCustomer->email, $aCutomerParams );
				if ($this->oApi->errorCode){
					$sErrors .= 'MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>";
				}
			}
		}
		if($sErrors != "")
		{
			return $this->displayError($sErrors);
		}
		
		return $this->displayNotification($this->l("User(s) were subscribed - look for the confirmation email!\n"));
	}
	
	private function addNewSubscribedUsers()
	{
		$sSelectedList = Tools::getValue('autoAddList');
		if(strlen($sSelectedList) == 0)
			$this->displayError($this->l('Invalid Selection.'));
		Configuration::updateValue($this->name.'_defaultListId', Tools::getValue('autoAddList'));	
		
		//add last subscribed users to mailchimp default list
		$oMailChimp = new MailChimp();
		return $this->displayNotification($oMailChimp->addNewUsersToDefaultList());	
	}
	
	/*
	 * Unsubscribe selection from selected list
	 */
	private function unsubscribeSelection()
	{
		$sErrors = "";
		$aCustomerIds = Tools::getValue($this->table.'Box');
		foreach ($aCustomerIds as $aUserId)
		{
			$oCustomer = new $this->className($aUserId);
			if(!is_null($oCustomer))
			{
				// By default this sends a confirmation email - you will not see new members
				// until the link contained in it is clicked!
				$this->oApi->listUnsubscribe( Tools::getValue('unsubscribtionList'), $oCustomer->email );
				if ($this->oApi->errorCode){
					$sErrors .= 'MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>";
				}
			}
		}
		if($sErrors != "")
		{
			return $this->displayError($sErrors);
		}
		
		return $this->displayNotification($this->l("User(s) were unsubscribed - look for the confirmation email!\n"));
	}

	private function getAllCampains($bAutoSubmit = false, $sName = 'Campain')
	{
		$aResult = $this->oApi->campaigns();

		$sReturnHtml = '';
		$aResult = $this->oApi->campaigns();

		if ($this->oApi->errorCode){
			$this->displayError($this->oApi->errorCode . ' ' . $this->oApi->errorMessage);
		}
		else 
		{
			$sReturnHtml .= '<select name="'. $this->name . $sName .'"'. (($bAutoSubmit) ? ' onChange="document.caca.submit()"'  : '') . '>';
			$sReturnHtml .= '<option selected>Select a campaign</option>';
			foreach ($aResult['data'] as $aCampain){
  				$sReturnHtml .= '<option value="'.$aCampain['id'].'">'.$aCampain['title'].'</option>';
			}
			$sReturnHtml .= '</select>';
		}
		return $sReturnHtml;
	}
	
	private function displayUseCampain()
	{
		echo'   <fieldset class="mailchimp-main">
					<legend>
				           ' . $this->l('Send Campaign') . '
				    </legend>
				    <p class="title">' . $this->l('If you have already added users to your lists, select the campaign you want and send it to the registered users.') . '</p>
				    <span>'.$this->l('Select a campain:').'</span>' . $this->getAllCampains() . '
				    <input type="submit" class="button" name="sendCampain" value="'.$this->l('Send campaign').'" class="button" />
					<input type="submit" class="buttonx" name="sendTestCampain" value="'.$this->l('Send test campaign').'" class="button" />					
				</fieldset>';
	}
	
	private function displayCampainStatisticsMenu()
	{
		echo'<fieldset class="mailchimp-main">
					<legend>
				           ' . $this->l('Campaign Statistics') . '
				    </legend>
				    <p class="title">' . $this->l('Display Statistics of your sent campaings, see the success and click rate.') . '</p>
				    <span>'.$this->l('Select a campaign:').'</span>' . $this->getAllCampains(false, 'StatisticsCampain') . '
				    <input type="submit" class="button" name="showCampainStatistics" value="'.$this->l('Show statistics').'" class="button" />';
		if(Tools::isSubmit('showCampainStatistics'))
		{
			echo $this->displayCampainStatistics();
		}
		
		echo'</fieldset>';
	}
	
	private function displayScheduleCampain()
	{
		echo'<fieldset class="mailchimp-main">
					<legend>
				           ' . $this->l('Campaign template customization') . '
				    </legend>
				    <span>'.$this->l('Select a campaign:').'</span>' . $this->getAllCampains(false, 'TemplateCampain');
		
		if(Tools::isSubmit('scheduleCampain'))
		{
			echo $this->displayScheduleOptions();
		}
		echo'</fieldset>';
	}
	
	private function displayCampainStatistics()
	{
		$sErrors = "";
		$sStatistics = "<div id='mailchimp-statisticas'>";
		$sCampainId = Tools::getValue($this->name.'StatisticsCampain');
		if($sCampainId == "")
			return $this->displayError($this->l("Please select a campaign"));
		
		//get campain click statistics
		$aStatistics  = $this->oApi->campaignClickStats($sCampainId);
		if ($this->oApi->errorCode){
			$sErrors .= 'MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>";
		}
		else 
		{
			$sStatistics .= "<ul>";
			$sStatistics .= '<li><b>'. $this->l('Click Statistics:').'</b></li>';
			if (sizeof($aStatistics) == 0)
			{
		        $sStatistics .= '<li>'. $this->l("No stats for this campaign yet!").'</li></ul>';
		    } 
		    else 
		    {
		    	
			    foreach($aStatistics as $url=>$detail)
			    {
				    $sStatistics .= "<li>URL: " . $url . "</li>
				    				 <li>Clicks: " . $this->l($detail['clicks']) . "</li>
				    				 <li>Unique Clicks: " . $this->l($detail['unique']) . "</li>";
			    }
			    $sStatistics .= "</ul>";
		    }
		}
		
		//get campain statistics
		$aCampainStats = $this->oApi->campaignStats($sCampainId);
		if ($this->oApi->errorCode){
			$sErrors .= 'MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>";
		}
		else 
		{
			$sStatistics .= "<ul>";
			$sStatistics .= '<li><b> '. $this->l("General campaign statistics ") . '</b></li>';
			
		    foreach($aCampainStats as $k=>$v){
		    	if(!is_array($v))
		        	$sStatistics .= "<li>" . $this->l($k) . ": " . $v . "</li>";
		    }
		    $sStatistics .= "</ul>";
		}

		//get google analytics stats
		$aGoogleStats = $this->oApi->campaignAnalytics($sCampainId);
		$sStatistics .= "<ul>";
		$sStatistics .= '<li><b>' . $this->l("Google analytics statistics:") . '</b></li>';
		if ($this->oApi->errorCode && is_array($aGoogleStats)){
			$sErrors .= '<li>'.'MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br></li>";
			
		}
		else 
		{
			
			$sStatistics .= "<li>Visits: " . $this->l($aGoogleStats['visits']) . "</li>" .
		    				"<li>Pages: " . $this->l($aGoogleStats['pages']) . "</li>" .
		    				"<li>Goals " . $this->l($aGoogleStats['type']) . "</li>";
		    if (is_array($aGoogleStats['goals'])){
		        foreach($aGoogleStats['goals'] as $goal){
		            $sStatistics .= "<li>" . $goal['name'] . ": " . $goal['conversions'] . "</li>";
		        }
		    }
		    $sStatistics .= "</ul>";
		}
		
		if($sErrors != "")
		{
			return $this->displayError($sErrors);
		}
		$sStatistics .= '</div>';
		return $sStatistics;
	}
	
	private function sendCampain()
	{
		$sCampainId = Tools::getValue($this->name.'Campain');

		if($sCampainId != "")
		{
			$this->oApi->campaignSendNow($sCampainId);
			if ($this->oApi->errorCode){
				return $this->displayError('MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>");
			} else {
				return $this->displayNotification($this->l("Campaign Sent!"));
			}	
		}
		else 
			return $this->displayError($this->l("Please select a campaign"));
	}
	
	private function sendTestCampain()
	{
		$sTestCampainEmails = Configuration::get($this->name.'_testcampainemails');
		$sCampainId = Tools::getValue($this->name.'Campain');
		$aTestEmails = split(',', $sTestCampainEmails);
		
		//if no email save exit
		if(count($aTestEmails) == 0)
			return $this->displayError($this->l("No test emails saved.Please configure the MailChimp module."));
		
		//if campain id empty exit
		if($sCampainId == "")
			return $this->displayError($this->l("Please select a campaign"));
		
		$this->oApi->campaignSendTest($sCampainId, $aTestEmails);
		if ($this->oApi->errorCode){
			return $this->displayError('MailChimp error code: ' . $this->oApi->errorCode . '.Message: ' . $this->oApi->errorMessage . "<br>");
		} else {
			return $this->displayNotification($this->l("Test Campaign Sent!"));
		}		
	}
	/*
	 * adds the customized css file to admin head
	 */
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
}
?>