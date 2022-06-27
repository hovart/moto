<?php

class privatesale extends Module
{
	public function __construct()
	{
		$this->name = 'privatesale';
		$this->version = '1.2.1';
		
		if (_PS_VERSION_ >= '1.5')
		{
			$this_context = Context::getContext();
			$this->id_shop = $this_context->shop->id;
		}
		else
			$this->id_shop = 0;

		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'pricing_promotion';
		else
			$this->tab = 'Tools';
		
		parent::__construct(); 
 
 		 
 /** Backward compatibility */ 
 		require(_PS_MODULE_DIR_.'/privatesale/backward_compatibility/backward.php'); 

		
		$this->displayName = $this->l('Private sale');
		$this->description = $this->l('Private sale is a module that enable you to create private sales for your customers.');
	}
	
	public function install()
	{
		if (!parent::install() || 
			!$this->registerHook('rightColumn') || 
			!$this->registerHook('header'))
			return FALSE;
		
		Db::getInstance()->Execute("
			CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."pvsale` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_shop` int(11) NOT NULL,
			  `name` varchar(100) NOT NULL,
			  `description` text NOT NULL,
			  `category` int(11) NOT NULL,
			  `groups` text NOT NULL,
			  `time_start` datetime NOT NULL,
			  `time_end` datetime NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM;
		");
		
		Db::getInstance()->Execute("
			CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."pvsale_registered` (
			  `id_user` int(11) NOT NULL,
			  `id_pvsale` int(11) NOT NULL
			) ENGINE=MyISAM;
		");
		
		return TRUE;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return FALSE;
		Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'pvsale`');
		Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'pvsale_registered`');
		return TRUE;
	}
	
	public function MakeDateTime($date, $time)
	{
		$date_temp = explode('/', $date);
		if (!isset($date_temp[2]) || !checkdate($date_temp[1], $date_temp[0], $date_temp[2]))
			return FALSE;
		
		$time_temp = explode('h', $time);
		!is_numeric($time_temp[0]) || $time_temp[0] >= 24 || $time_temp[0] < 0 ? $new_time['h'] = '00' : $new_time['h'] = $time_temp[0];
		!is_numeric($time_temp[1]) || $time_temp[1] >= 60 || $time_temp[1] < 0 ? $new_time['m'] = '00' : $new_time['m'] = $time_temp[1];
		
		/*$datetime = new DateTime();
		$datetime->setDate($date_temp[2], $date_temp[1], $date_temp[0]);
		$datetime->setTime($new_time['h'], $new_time['m']);
		*/
		return mktime($new_time['h'],$new_time['m'],0,$date_temp[1],$date_temp[0],$date_temp[2]);
	}
	
	public function CheckSaleRegister($id_user, $id_pvsale)
	{
		$register =	Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale_registered WHERE id_pvsale="'.(int)$id_pvsale.'" && id_user="'.(int)$id_user.'"');
		if ($register == FALSE)
			return FALSE;
		return TRUE;
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
	
	public function PostProcess()
	{
		global $smarty, $cookie;
		
		// Delete PV Sale
		if (Tools::getIsset('pvs_delete') && is_numeric(Tools::getValue('pvs_delete')))
		{
			$result = Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'pvsale` WHERE id="'.(int)Tools::getValue('pvs_delete').'"');
			if ($result != FALSE)
			{
				if (file_exists('../modules/privatesale/public/img/'.(int)Tools::getValue('pvs_delete').'.jpg'))
					unlink('../modules/privatesale/public/img/'.(int)Tools::getValue('pvs_delete').'.jpg');
				$smarty->assign('success', $this->l('Private sale deleted.'));
				return TRUE;
			}
			else
			{
				$smarty->assign('error', $this->l('Error when deleting the private sale.'));
				return FALSE;
			}
		}
		
		// Add and Edit PV Sale
		if (Tools::isSubmit('add_pvsale') || Tools::isSubmit('edit_pvsale'))
		{
			$name = htmlspecialchars(Tools::getValue('add_name'));
			$description = htmlspecialchars(Tools::getValue('add_description'));
			$category = (int)Tools::getValue('add_cat');
			
			$time_start = $this->MakeDateTime(Tools::getValue('date_begin'), (int)Tools::getValue('hour_start_h').'h'.(int)Tools::getValue('hour_start_m'));
			$time_end = $this->MakeDateTime(Tools::getValue('date_finish'), (int)Tools::getValue('hour_finish_h').'h'.(int)Tools::getValue('hour_finish_m'));
			if ($time_start == FALSE || $time_end == FALSE || $time_start >= $time_end)
			{
				$smarty->assign('error', $this->l('Error, date is incorrect.'));
				return FALSE;
			}

			$groups = '';
			$groups_list = Group::getGroups($cookie->id_lang);
			foreach ($groups_list as $temp)
			{
				if(Tools::getValue('grp_'.$temp['id_group']) != FALSE)
					$groups .= Tools::getValue('grp_'.$temp['id_group']) . ',';
			}
						
			if (Tools::isSubmit('add_pvsale'))
			{
				$ret_val = Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'pvsale(`id_shop`, `name`, `description`, `category`, `groups`, `time_start`, `time_end`) 
				VALUES("'.(int)$this->id_shop.'", "'.$name.'", "'.$description.'", "'.$category.'", "'.$groups.'", "'.date('Y-m-d H:i:s', $time_start).'","'.date('Y-m-d H:i:s', $time_end).'")');
				if ($ret_val != FALSE)
				{
					$smarty->assign('success', $this->l('Private sale added.'));
					$id_upd = Db::getInstance()->Insert_ID();
				}
				else
				{
					$smarty->assign('error', $this->l('Error when adding the private sale.'));
					return FALSE;
				}
			}
			else
			{
				$ret_val = Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'pvsale 
				SET `name`="'.$name.'", `description`="'.$description.'", `category`="'.$category.'", `groups`="'.$groups.'", `time_start`="'.date('Y-m-d H:i:s', $time_start).'", `time_end`="'.date('Y-m-d H:i:s', $time_end).'" 
				WHERE `id`="'.(int)Tools::getValue('edit_id').'"');
				
				if ($ret_val != FALSE)
				{
					$smarty->assign('success', $this->l('Private sale updated.'));
					$id_upd = (int)Tools::getValue('edit_id');
				}
				else
				{
					$smarty->assign('error', $this->l('Error when updating the private sale.'));
					return FALSE;
				}
			}
			
			if ($_FILES['logo_upload']['error'] == 0)
			{
				if (file_exists('../modules/privatesale/public/img/'.$id_upd.'.jpg'))
					unlink('../modules/privatesale/public/img/'.$id_upd.'.jpg');
				$ret_val = move_uploaded_file($_FILES['logo_upload']['tmp_name'], '../modules/privatesale/public/img/'.$id_upd.'.jpg');
				if ($ret_val == FALSE)
				{
					$smarty->assign('error', $this->l('Error when uploading the image.'));
					return FALSE;
				}
			}
			return TRUE;
		}
		
		// Edit PV Sale => Get informations
		if (Tools::getIsset('pvs_edit') && is_numeric(Tools::getValue('pvs_edit')))
		{
			$pvs_data = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'pvsale` WHERE id="'.(int)Tools::getValue('pvs_edit').'"');
			if ($pvs_data != FALSE)
			{
				$time_start = strtotime($pvs_data['time_start']);
				$smarty->assign('time_start', $time_start);
				
				$time_end = strtotime($pvs_data['time_end']);
				$smarty->assign('time_end', $time_end);
				
				$smarty->assign('pvs_edit', $pvs_data);
				$smarty->assign('pvs_edit_exists', file_exists("../modules/privatesale/public/img/".$pvs_data['id'].".jpg"));
				
				return TRUE;
			}
			return FALSE;
		}
		
		// Active PV Sale
		if (Tools::getIsset('pvs_active') && is_numeric(Tools::getValue('pvs_active')))
		{
			$check_active = Db::getInstance()->getRow('SELECT `active` FROM `'._DB_PREFIX_.'pvsale` WHERE id="'.(int)Tools::getValue('pvs_active').'"');
			if ($check_active != FALSE)
			{
				if ($check_active['active'] == 0)
				{
					$ret_val = Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'pvsale SET `active`=1 WHERE `id`="'.(int)Tools::getValue('pvs_active').'"');
					$smarty->assign('success', $this->l('Private sale enabled.'));
				}
				else
				{
					$ret_val = Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'pvsale SET `active`=0 WHERE `id`="'.(int)Tools::getValue('pvs_active').'"');
					$smarty->assign('success', $this->l('Private sale disabled.'));
				}
				return TRUE;
			}
			else
			{
				$smarty->assign('error', $this->l('Private sale not found.'));
				return FALSE;
			}
		}
	}
	
	public function getPvSaleList($active = NULL)
	{
		global $cookie;
		
		if ($active == NULL)
			$pvs_list = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'pvsale WHERE id_shop = "'.(int)$this->id_shop.'" ORDER BY id DESC');
		elseif ($active == 1 || $active == 0)
			$pvs_list = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'pvsale WHERE `active`="'.(int)$active.'" AND id_shop = "'.(int)$this->id_shop.'" ORDER BY id DESC');
		else
			return (FALSE);
		
		if (!empty($pvs_list))
		{
			foreach ($pvs_list as $temp)
			{
				$category = new Category($temp['category'], $cookie->id_lang);
				$grp_list = '';
				$grp_temp = explode(',', $temp['groups']);
				foreach ($grp_temp as $temp_2)
				{
					$grp = new Group($temp_2, $cookie->id_lang);
					if ($grp_list != '') $grp_list .= '<br />';
					$grp_list .= $grp->name;
				}
				$temp['cat_name'] = $category->name;
				$temp['grp_name'] = $grp_list;
				$new[] = $temp;
			}
			$pvs_list = $new;
		}
		return $pvs_list;
	}
	
	public function getContent()
	{
		global $smarty, $cookie;

		$this->PostProcess();

		// Get group and categories list
		$list_data['groups'] = Group::getGroups($cookie->id_lang);
		$list_data['categories'] = Category::getSimpleCategories($cookie->id_lang);
		$smarty->assign('list_data', $list_data);
		 
		// Get PV Sale list
		$smarty->assign('pvs_list', $this->getPvSaleList());
		
		// Clean the admin link
		$link = explode("&pvs_delete", $_SERVER['REQUEST_URI']);
		$link = explode("&pvs_edit", $link[0]);
		$link = explode("&pvs_action", $link[0]);
		$link = explode("&pvs_active", $link[0]);
		$link = $link[0];
		$smarty->assign('link', $link);
		
		// Get ISO Code
		$lang = new Language($cookie->id_lang);

		$addJS = '';
        if (_PS_VERSION_ >= '1.5')
        {
            $jqui_tabs = Media::getJqueryUIPath('ui.datepicker', false, true);
            foreach ($jqui_tabs["js"] as $jqui)
                $addJS .= '<script type="text/javascript" src="'.$jqui.'"></script>';

            $addJS .= '<script type="text/javascript">'.$this->bindDatepicker('sales_begindate', false).'</script>';
            $addJS .= '<script type="text/javascript">'.$this->bindDatepicker('sales_enddate', false).'</script>';
        }
        else
            includeDatepicker(array('sales_begindate', 'sales_enddate'), false);

        $smarty->assign('iso_lang', $lang->iso_code);
		$smarty->assign('addJS', $addJS);
		return ($this->display(__FILE__, 'configuration.tpl'));
	}
		
	public function hookRightColumn($params)
	{
		global $smarty;
		
		$pvs_list = Db::getInstance()->ExecuteS('SELECT id FROM '._DB_PREFIX_.'pvsale WHERE active = 1 AND id_shop = '.(int)$this->id_shop);
		$id_list = array();
		foreach($pvs_list as $pvs_temp)
			$id_list[] = $pvs_temp['id'];
		
		$img_url_list = array();
		$img_list = scandir(_PS_ROOT_DIR_.'/modules/privatesale/public/img');
		foreach ($img_list as $temp)
		{
			if ($temp != '.' && $temp != '..')
			{
				$file_id = explode('.', $temp);
				if (in_array($file_id[0], $id_list))
					$img_url_list[] = _MODULE_DIR_.'/privatesale/public/img/'.$temp;
			}
		}
		
		$smarty->assign('pvs_link', _MODULE_DIR_.'privatesale/public/');
		$smarty->assign('img_list', $img_url_list);
	
		return ($this->display(__FILE__, 'views/templates/front/pvblock.tpl'));
	}
	
	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}
	
	public function hookHeader($params)
	{
		global $cookie;
		
		$now = time();

		Tools::addJS(__PS_BASE_URI__."modules/privatesale/js/easySlider1.7.js");
		// On product page, check if the product is in a private sale
		if (is_numeric(Tools::getValue('id_product')))
		{
			$product = new Product(Tools::getValue('id_product'));
			$pvs = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale WHERE `category`="'.(int)$product->id_category_default.'"');
			if ($pvs != FALSE)
			{
				$pvstatus = $this->getSaleStatus($pvs['time_start'], $pvs['time_end']);
				if ($pvstatus != 2 || (!isset($cookie->id_customer)))
					header('Location: '._MODULE_DIR_.'privatesale/public/');
				else
				{
					$new_breadcrumb  = "<a href='"._MODULE_DIR_."privatesale/public/'>".$this->displayName."</a>";
					$new_breadcrumb .= "<span class='navigation-pipe'>&gt;</span>";
					$new_breadcrumb .= "<a href='"._MODULE_DIR_."privatesale/public/pvprods.php?id=".$pvs['id']."'>".$pvs['name']."</a>";
					$new_breadcrumb .= "<span class='navigation-pipe'>&gt;</span>";
					$new_breadcrumb .= "<a href='".$_SERVER['REQUEST_URI']."'>".$product->name[$cookie->id_lang]."</a>";
					
					echo '	
						 <script>
							window.onload = function()
							{
								document.querySelector(".breadcrumb").innerHTML = "'.$new_breadcrumb.'"; 
							}
						 </script>';
				}
			}
		}
		
		// On category page, check if the category is a private sale
		if (is_numeric(Tools::getValue('id_category')))
		{
			$id_cat = Tools::getValue('id_category');
			$pvs = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale WHERE `category`="'.(int)$id_cat.'"');
			if ($pvs != FALSE)
			{
				$pvstatus = $this->getSaleStatus($pvs['time_start'], $pvs['time_end']);
				if ($pvstatus != 2)
					header('Location: '._MODULE_DIR_.'privatesale/public/');
				else
					header('Location: '._MODULE_DIR_.'privatesale/public/pvprods.php?id='.$pvs['id']);
			}
		}
	}
	
	public function getTranslations()
	{
		return array(
			0 => $this->l('You must be connected to see this page.'),
			1 => $this->l('Sale not found.'),
			2 => $this->l('You must be registered to the sale before.'),
			3 => $this->l('Sale finished'),
			4 => $this->l('Sale not open yet')
		);
	}

    function bindDatepicker($id, $time)
    {
        $return = "";
        if ($time)
            $return .= '
            var dateObj = new Date();
            var hours = dateObj.getHours();
            var mins = dateObj.getMinutes();
            var secs = dateObj.getSeconds();
            if (hours < 10) { hours = "0" + hours; }
            if (mins < 10) { mins = "0" + mins; }
            if (secs < 10) { secs = "0" + secs; }
            var time = " "+hours+":"+mins+":"+secs;';

        $return .= '
        $(function() {
            $("#'.Tools::htmlentitiesUTF8($id).'").datepicker({
                prevText:"",
                nextText:"",
                dateFormat:"yy-mm-dd"'.($time ? '+time' : '').'});
        });';

        return $return;
    }
}