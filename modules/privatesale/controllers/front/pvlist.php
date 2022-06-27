<?php

require_once(dirname(__FILE__).'/../../privatesale.php');

class privatesalepvlistModuleFrontController extends ModuleFrontController
{
	protected $pvs_module;
	
	public function PvSaleRegister($id_user, $id_pvs)
	{		
		$check = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale_registered WHERE id_pvsale="'.(int)$id_pvs.'" && id_user="'.(int)$id_user.'"');
		if ($check == FALSE)
		{
			$ret_val = Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'pvsale_registered(`id_user`, `id_pvsale`) VALUES("'.(int)$id_user.'", "'.(int)$id_pvs.'")');
			return $ret_val;
		}
		return FALSE;
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		
		$this->displayContent();
	}

	public function displayContent()
	{
		global $smarty, $cookie;
		
		if (isset($_GET['register']) && is_numeric($_GET['register']))
		{
			$sale_ret =	$this->PvSaleRegister($cookie->id_customer, $_GET['register']);
			$sale_ret == FALSE ? $smarty->assign('register', 0) : $smarty->assign('register', 1);
		}
		
		$pvs = new privatesale();
		$pvs_list = $pvs->getPvSaleList(1);
		if (!empty($pvs_list))
		{
			$pvs_result[0] = array();
			$pvs_result[1] = array();
			$pvs_result[2] = array();
			
			$now = time();
			foreach($pvs_list as $temp)
			{
				$access = 0;
				$grp_agree = explode(',', $temp['groups']);
				foreach($grp_agree as $grp_tmp)
				{
					$check = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'customer_group WHERE id_customer="'.$cookie->id_customer.'" AND id_group="'.$grp_tmp.'"');
					if ($check != FALSE)
						$access = 1;
				}
				
				if ($access == 1)
				{
					$temp['status'] = $this->getSaleStatus($temp['time_start'], $temp['time_end']);
					($this->CheckSaleRegister((int)$cookie->id_customer, (int)$temp['id']) == FALSE) ? $temp['access'] = 0 : $temp['access'] = 1;
					$temp['link'] = Context::getContext()->link->getModuleLink('privatesale', 'pvprods', array("id" => $temp['id']));
					$temp['register'] = Context::getContext()->link->getModuleLink('privatesale', 'pvlist', array("register" => $temp['id']));					
					if (file_exists(__PS_BASE_URI__."modules/privatesale/public/img/".(int)$temp['id'].".jpg"));
						$temp['file_exists'] = __PS_BASE_URI__."modules/privatesale/public/img/".(int)$temp['id'].".jpg";
					$pvs_result[$temp['status'] - 1][] = $temp;
				}
			}

			$list = array();
			foreach($pvs_result[1] as $temp1)
				$list[] = $temp1;
			foreach($pvs_result[0] as $temp2)
				$list[] = $temp2;
			foreach($pvs_result[2] as $temp3)
				$list[] = $temp3;
			
			$this->context->smarty->assign('pvs_list', $list);
		}
		$this->context->smarty->assign('pv_cookie', $cookie);
		
		$this->setTemplate('pvlist.tpl');
	}

	public function getSaleStatus($datetime_start, $datetime_end)
	{
		$now = time();
		
		//$time_start = new DateTime($datetime_start);
		//$start = $time_start->getTimestamp();
		$start = strtotime($datetime_start);
		
		//$time_end = new DateTime($datetime_end);
		//$end = $time_end->getTimestamp();
		$end = strtotime($datetime_end);
		
		if ($now < $start)
			return 1;
		elseif ($start < $now && $now < $end)
			return 2;
		else
			return 3;
	}

	public function CheckSaleRegister($id_user, $id_pvsale)
	{
		$register =	Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale_registered WHERE id_pvsale="'.(int)$id_pvsale.'" AND id_user="'.(int)$id_user.'"');
		if ($register == false)
			return false;
		return true;
	}
}