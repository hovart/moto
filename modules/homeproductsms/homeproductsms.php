<?php
/**
* 2007-2015 Mack Stores
*
* NOTICE OF LICENSE
*
* This code is a property of MackStores. In No way any one is authorised to use this code  or modify this code and redistribute without prior
* permission from the authour i.e MackStores
*
*
*  @author    Mack Stores contact:-sales@mackstores.com
*  @copyright 2007-2015 Mack Stores
*  International Registered Trademark & Property of Mack Stores
*/

if (!defined('_PS_VERSION_'))
	exit;

class Homeproductsms extends Module
{

	public function __construct()
	{
		$this->name = 'homeproductsms';
		$this->tab = 'others';
		$this->version = '1.0.2';
		$this->author = 'Mack Stores';
		$this->need_instance = 0;
        $this->module_key = "92323ea049fe28f4183779bbf2726b1b";

		parent::__construct();

		$this->displayName = $this->l('Display Products on the Home Page');
		$this->description = $this->l('This module allows to continently display products according  to your own choice');
		$this->secure_key = Tools::encrypt($this->name);
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		$this->confirmUninstall = $this->l('Are you sure you want to unistall the product?');
	}


	public function install()
	{
		include(dirname(__FILE__).'/sql/install.php');
		$this->_clearCache('*');
		//find default shop language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		if (
				!Configuration::updateValue('lang_select_hpms', $default_lang)

			)
				return false;
			if (parent::install() == false ||
				$this->registerHook('header') == false ||
				$this->registerHook('backofficeheader') == false ||
				$this->registerHook('footer') == false ||
				$this->registerHook('home') == false ||
				$this->registerHook('leftColumn') == false ||
				$this->registerHook('rightColumn') == false ||
				$this->registerHook('addproduct') == false  ||
				$this->registerHook('updateproduct') == false ||
				$this->registerHook('deleteproduct') == false ||
				$this->registerHook('categoryUpdate') == false
			)
				return (false);
			return (true);
	}

	public function uninstall()
	{
			include(dirname(__FILE__).'/sql/uninstall.php');
			Configuration::deleteByName('mkst_advert');
			Configuration::deleteByName('mkst_gglad_sh_left');
			Configuration::deleteByName('mkst_gglad_sh_center');
			Configuration::deleteByName('mkst_gglad_sh_right');
			Configuration::deleteByName('mkst_gglad_sh_top');
			return parent::uninstall();
	}


	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
        parent::_clearCache('homeproductms.tpl');
		if (Tools::isSubmit('hmprdcsms'))
        {

        	//chg 1
        	/* get form valuses */
			$en_dis_blk = Tools::getValue('en_dis_blk');
			$prd_cat_shw = implode(',', Tools::getValue('prd_cat_shw'));
			$prd_cat_dsp_order = Tools::getValue('prd_cat_dsp_order');
			$prd_num_dsp = Tools::getValue('prd_num_dsp');
			$prd_disp_new = Tools::getValue('prd_disp_new');
			$hdg_title = htmlentities(Tools::getValue('hdg_title'),ENT_COMPAT, 'UTF-8');
			$add_cart_shw = Tools::getValue('add_cart_shw');
			$more_shw = Tools::getValue('more_shw');
			$qck_view_shw = Tools::getValue('qck_view_shw');
			$dsp_prc = Tools::getValue('dsp_prc');
			$tim_cnt_dwn_shw = Tools::getValue('tim_cnt_dwn_shw');
			$pos_blk = Tools::getValue('pos_blk');
			$id_ms = Tools::getValue('id_ms');
			$en_dis_adv = Tools::getValue('en_dis_adv');
			$mkst_ad_blk = Tools::getValue('mkst_ad_blk');
			$mkst_advert_blk = htmlspecialchars($mkst_ad_blk, ENT_QUOTES, 'UTF-8');
			$lang_select_hpms =  (int)Tools::getValue('lang_select_hpms');
			$prd_disp_sale = Tools::getValue('prd_disp_sale');
			$prd_disp_reductions = Tools::getValue('prd_disp_reductions');
			//chg 2
			//set value for form
            Configuration::updateValue('en_dis_blk', $en_dis_blk);
            Configuration::updateValue('prd_cat_shw', $prd_cat_shw);
            Configuration::updateValue('prd_cat_dsp_order', $prd_cat_dsp_order);
            Configuration::updateValue('prd_num_dsp', $prd_num_dsp);
            Configuration::updateValue('prd_disp_new', $prd_disp_new);
            Configuration::updateValue('hdg_title', $hdg_title);
            Configuration::updateValue('add_cart_shw', $add_cart_shw);
            Configuration::updateValue('more_shw', $more_shw);
            Configuration::updateValue('qck_view_shw', $qck_view_shw);
            Configuration::updateValue('tim_cnt_dwn_shw', $tim_cnt_dwn_shw);
            //Configuration::updateValue('dsp_stk', $dsp_stk);
            Configuration::updateValue('pos_blk', $pos_blk);
            Configuration::updateValue('en_dis_adv', $en_dis_adv);
            Configuration::updateValue('mkst_ad_blk', $mkst_advert_blk);
			Configuration::updateValue('lang_select_hpms', $lang_select_hpms);
			Configuration::updateValue('prd_disp_sale', $prd_disp_sale);
			Configuration::updateValue('prd_disp_reductions', $prd_disp_reductions);


			/*add new put them in sql table*/
			if ($id_ms)
			{

				Db::getInstance()->Execute('
                UPDATE `'._DB_PREFIX_.'homeproductsms`
                SET `en_dis_blk` = '.(int)$en_dis_blk.',
                	`prd_cat_shw` = "'.(string)$prd_cat_shw.'",
                	`prd_cat_dsp_order` = '.(int)$prd_cat_dsp_order.',
                	`prd_num_dsp` = '.(int)$prd_num_dsp.',
                	`prd_disp_new` = '.(int)$prd_disp_new.',
                	`add_cart_shw` = '.(int)$add_cart_shw.',
                	`more_shw` = '.(int)$more_shw.',
                	`qck_view_shw` = '.(int)$qck_view_shw.',
                	`dsp_prc` = '.(int)$dsp_prc.',
                	`tim_cnt_dwn_shw` = '.(int)$tim_cnt_dwn_shw.',
                	`pos_blk` = '.(int)$pos_blk.',
                	`en_dis_adv` = '.(int)$en_dis_adv.',
                	`mkst_advert_blk` = "'.(string)$mkst_advert_blk.'",
                	`prd_disp_sale` = '.(int)$prd_disp_sale.',
                	`prd_disp_reductions` = '.(int)$prd_disp_reductions.'

                WHERE `id_ms`= '.(int)$id_ms.'
                ');

				//check default language and if  matches then update main title
				$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
				if ((int)$default_lang == (int)$lang_select_hpms)
				{
				Db::getInstance()->Execute('
                UPDATE `'._DB_PREFIX_.'homeproductsms`
                SET `hdg_title` = "'.(string)$hdg_title.'"
                WHERE `id_ms`= '.(int)$id_ms.'
                ');
				}


                Db::getInstance()->Execute('
                REPLACE INTO  `'._DB_PREFIX_.'homeproductsms_lang`
                	(
                	`id_mss`,
                	`id_lang`,
                	`hdg_title_lang`
					)
					VALUES
					(
					'.(int)$id_ms.',
					'.(int)$lang_select_hpms.',
					 "'.(string)$hdg_title.'"
					 )
                ');
			}
			else
			{
				//check the table for column_id

				//check if postiion exists
				$query = Db::getInstance()->ExecuteS('SELECT pos_blk FROM `'._DB_PREFIX_.'homeproductsms` WHERE `pos_blk` = '.(int)$pos_blk.'');
				//the above is to check accidential double clicking

				if (!$query)
				{
				Db::getInstance()->Execute('
                INSERT INTO `'._DB_PREFIX_.'homeproductsms`(
	                `en_dis_blk`,
	                `prd_cat_shw`,
	                `prd_cat_dsp_order`,
	                `prd_num_dsp`,
	                `prd_disp_new`,
	                `hdg_title`,
	                `add_cart_shw`,
	                `more_shw`,
	                `qck_view_shw`,
	                `dsp_prc`,
	                `tim_cnt_dwn_shw`,
	                `pos_blk`,
	                `en_dis_adv`,
	                `mkst_advert_blk`,
	                `prd_disp_sale`,
	                `prd_disp_reductions`

                )
                VALUES(
	                '.(int)$en_dis_blk.',
	                "'.(string)$prd_cat_shw.'",
	                '.(int)$prd_cat_dsp_order.',
	                '.(int)$prd_num_dsp.',
	                '.(int)$prd_disp_new.',
	                "'.(string)$hdg_title.'",
	                '.(int)$add_cart_shw.',
	                '.(int)$more_shw.',
	                '.(int)$qck_view_shw.',
	                '.(int)$dsp_prc.',
	                '.(int)$tim_cnt_dwn_shw.',
	                '.(int)$pos_blk.',
	                '.(int)$en_dis_adv.',
	                "'.(string)$mkst_advert_blk.'",
	                '.(int)$prd_disp_sale.',
	                '.(int)$prd_disp_reductions.'

	                )
                ');

                $get_id_ms = (int)Db::getInstance()->Insert_ID();//helps you get current insert id
                Db::getInstance()->Execute('
                INSERT INTO  `'._DB_PREFIX_.'homeproductsms_lang`
                	(
                	`id_mss`,
                	`id_lang`,
                	`hdg_title_lang`
					)
					VALUES
					(
					'.(int)$get_id_ms.',
					'.(int)$lang_select_hpms.',
					 "'.(string)$hdg_title.'"
					 )
                ');
				}
			}
			$output .= $this->displayConfirmation($this->l('Updated'));
		}


		if (Tools::isSubmit('hpms_general_set'))
		{
			$head_disp_typ =  Tools::getValue('cat_header_style');
			$center_ms =  Tools::getValue('center_ms');
			//exit (var_dump($head_disp_typ));
				Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'homeproductsms_gen_set`
			SET
				`head_disp_typ` = '.(int)$head_disp_typ.',
				`center_ms` = '.(int)$center_ms.'
			WHERE `id_ms_gen` = "1" ');
			Configuration::updateValue('cat_header_style', $head_disp_typ);
			Configuration::updateValue('center_ms', $center_ms);
			$output .= $this->displayConfirmation($this->l('Updated'));
		}


		if (Tools::isSubmit('submit_lang_new_edit'))
		{
			$lang_select_hpms =  (int)Tools::getValue('lang_select_hpms');
			Configuration::updateValue('lang_select_hpms', $lang_select_hpms);
			$output .= $this->displayConfirmation($this->l('Selected'));
		}


		return $output.$this->displayForm();
	}


	public function displayForm()
	{



				//get postition numbers current
				//determine the max position number
				$array =  $this->getcurrposblck();
				$aaa = array();
				//if no result then just started position 1
				if ($array)
					{
						foreach ($array as $val)
						{
							$aaa[] = $val['pos_blk'];
						}
						$max_value = max($aaa);
						$pos_blk_cal = $max_value + 1;
						//count the positions
						//$count_pos_ms = count($aaa);
					}
				else
				{
					$pos_blk_cal = 0;
				}



			$skipcat_home =  Configuration::get('prd_cat_shw');
            if (!empty($skipcat_home))
            {
                $skipcat_array_home = explode(',',$skipcat_home);
            }
            else
            {
                $skipcat_array_home = array();
            }

           	//get languages
            		$languages = Language::getLanguages(true, $this->context->shop->id);
            		// process selected lang
		            $lang_select_hpms = Configuration::get('lang_select_hpms');
		            $lang_selected_details = Language::getLanguage($lang_select_hpms);//get language name from id
		            $lang_selected_id = $lang_selected_details['id_lang'];





			$output = '
			<script type="text/javascript" src="'.$this->_path.'/js/color_picker/jscolor.js"></script>
			<script type="text/javascript">

			$( document ).ready(function() {
    					$(".pdr_blk").hide();

								});


			function edit_blk(
								id_ms,
								en_dis_blk,
								prd_cat_shw,
								prd_cat_dsp_order,
								prd_num_dsp,
								prd_disp_new,
								hdg_title,
								add_cart_shw,
								more_shw,
								qck_view_shw,
								dsp_prc,
								tim_cnt_dwn_shw,
								pos_blk,
								en_dis_adv,
								mkst_ad_blk,
								prd_disp_sale,
								prd_disp_reductions)
			{



		/* alert (
								id_ms+"---"+
								en_dis_blk+"---"+
								prd_cat_shw+"---"+
								prd_cat_dsp_order+"---"+
								prd_num_dsp+"---"+
								prd_disp_new+"---"+
								hdg_title+"---"+
								add_cart_shw+"---"+
								more_shw+"---"+
								qck_view_shw+"---"+
								dsp_prc+"---"+
								tim_cnt_dwn_shw+"---"+
								pos_blk+"---"+
								en_dis_adv+"---"+
								unescape (mkst_ad_blk)

			); */
				function defvalms(namee,valuem)
				{
					if (document.getElementById(namee))
					document.getElementById(namee).value = valuem;
				}


				function defvalradio(namee,valuem)
				{

					if (valuem == 1)
					{

						$("#"+namee+"1").attr("checked", "checked");
					}
					else
					{
						$("#"+namee+"0").attr("checked", "checked");
					}

				}

				function defvalmultipvaledit(nameeEdit,valuemEdit)
				{
				if (document.getElementById(nameeEdit))
					var arrayAreaEdit = valuemEdit.split(",");
					$("#prd_cat_shw").val(arrayAreaEdit);
				}


				defvalms("id_ms", id_ms);
				defvalms("id_ms1", id_ms);
            	defvalradio("en_dis_blk", en_dis_blk);
            	defvalmultipvaledit("prd_cat_shw", prd_cat_shw);
            	defvalms("prd_cat_dsp_order", prd_cat_dsp_order);
            	defvalms("prd_num_dsp", prd_num_dsp);
            	defvalradio("prd_disp_new", prd_disp_new);
            	defvalms("hdg_title", hdg_title);
            	defvalradio("add_cart_shw", add_cart_shw);
            	defvalradio("more_shw", more_shw);
            	defvalradio("qck_view_shw", qck_view_shw);
            	defvalradio("dsp_prc", dsp_prc);
            	defvalradio("tim_cnt_dwn_shw", tim_cnt_dwn_shw);
            	defvalms("pos_blk", pos_blk);
            	defvalradio("en_dis_adv", en_dis_adv);
    			document.getElementById("mkst_ad_blk").innerHTML = mkst_ad_blk;
				defvalms("lang_select_hpms", '.Configuration::get('lang_select_hpms').');
            	defvalradio("prd_disp_sale", prd_disp_sale);
            	defvalradio("prd_disp_reductions", prd_disp_reductions);
            	$(".pdr_blk").show();


				$("html, body").animate({
      scrollTop: $("body").offset().top
    }, 1000);

			}

			var pos_blk_cal = '.$pos_blk_cal.';

			function sub_new()
			{

				function defvalms(namee,valuem)
				{
				if (document.getElementById(namee))
				document.getElementById(namee).value = valuem;
				}

				function defvalradio(namee,valuem)
				{

					if (valuem == 1)
					{

						$("#"+namee+"1").attr("checked", "checked");
					}
					else
					{
						$("#"+namee+"0").attr("checked", "checked");
					}

				}

				function defvalmultipval(namee,valuem)
				{
				if (document.getElementById(namee))
					var arrayAreaNew = valuem.split(",");
					$("#prd_cat_shw").val(arrayAreaNew);
				}

				defvalms("id_ms", "");
				defvalms("id_ms1", "Auto");
            	defvalradio("en_dis_blk", 1);
            	defvalmultipval("prd_cat_shw", "6");
            	defvalms("prd_cat_dsp_order", 0);
            	defvalms("prd_num_dsp", 4);
            	defvalradio("prd_disp_new", 0);
            	defvalms("hdg_title", "Products");
            	defvalradio("add_cart_shw", 1);
            	defvalradio("more_shw", 1);
            	defvalradio("qck_view_shw", 1);
            	defvalradio("dsp_prc", 1);
            	defvalradio("tim_cnt_dwn_shw", 1);
            	defvalradio("en_dis_adv", 0);
            	defvalms("mkst_advert_blk", " ");
            	document.getElementById("mkst_ad_blk").innerHTML = "";
            	defvalms("pos_blk", pos_blk_cal)
            	defvalms("lang_select_hpms", '.Configuration::get('lang_select_hpms').');
            	defvalradio("prd_disp_sale", 0);
            	defvalradio("prd_disp_reductions", 0);

            	$(".pdr_blk").show();

            	$("html, body").animate({
      scrollTop: $("body").offset().top
    }, 1000);

			}

			function dele_blk(del_id)
			{
				$(".ms_"+del_id).fadeOut(3000);
				$(".pdr_blk").hide();
				$.ajax({
					 			type: "POST",
								url: "../modules/homeproductsms/ajax/del_blk.php",
								async: true,
								cache: false,
								data: "&del_id="+del_id+"&hmprdmsseckey='.$this->secure_key.'",
								success: function(html)
								{
								pos_blk_cal  = pos_blk_cal - 1;
								$(".notif_ms_del").fadeIn(3000).delay(5000).fadeOut(3000);

								}
});

			}





			</script>





			<div class="box_me">
			<div class="pdr_blk">
			<h1>'.$this->l('Configure').'</h1>

			<hr class="style-two"></hr>
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="myform">
			<input type="hidden" value="" name="id_ms"  id="id_ms">
			<input type="hidden" value="" name="lang_select_hpms"  id="lang_select_hpms">
	        1.&nbsp;&nbsp;'.$this->l('Enable This Block').'
				<BR>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" value="1" name="en_dis_blk"  id="en_dis_blk1"  '.((1 == (int)Configuration::get('en_dis_blk')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="en_dis_blk" id="en_dis_blk0" '.((0 == (int)Configuration::get('en_dis_blk')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>';

				$output .='
			2.&nbsp;&nbsp;'.$this->l('Position Number of This Block and ID').'
				<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	Position&nbsp;
            	<input size="4"  style="text-align:center;" value="'.(int)$pos_blk_cal.'" name="pos_blk"  id="pos_blk" readonly="readonly">
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	ID&nbsp;
            	<input size="4"  style="text-align:center;"  value="" name="id_ms1"  id="id_ms1" readonly="readonly">


				<br><br>
	        3.&nbsp;&nbsp;'.$this->l('Product Category to be showed').'
				<BR>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="prd_cat_shw[]"  id="prd_cat_shw" multiple="multiple" style="width: 80%; height:300px;" > ';

            	$categories_home = Category::getCategories((int)Context::getContext()->cookie->id_lang);
            	//exit(print_r($categories_home));
            	ob_start();
            	$this->recurseCategorys($categories_home, $categories_home[0][1], 1 , $skipcat_array_home);
            	$output .= ob_get_contents();
            	ob_end_clean();

            	$output .=
            	'</select>
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	'.$this->l('1. (Please Press and hold the CTRL and click to select multiples) Select the categories or subcategories you want to Include.').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	'.$this->l('2. Selecting all  will Display All the Products(tip!!! To select all click on home and press the key shift and while shift key is pressed , then end, this will select all)').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	'.$this->l('3. These are default set categories. You can make your own category in Catalog in the backend of the prestashop. eg lets make a new category called Hot Selling. Include cerian products in Hot Selling. Well rest use your creativity ))').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	'.$this->l('4. Selecting Root only will also disable the whole block just as disable block because root is empty . No products...').'
            	<br><br>

            4.&nbsp;&nbsp;'.$this->l('Product Sorting').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<select name="prd_cat_dsp_order" id="prd_cat_dsp_order">
	            <option value="0" '.(Configuration::get('prd_cat_dsp_order') == 0 ? 'selected' : '').'>'.$this->l('No Sort - Sort by Back Office => Catalogue -> Position').'</option>
	            <option value="1" '.(Configuration::get('prd_cat_dsp_order') == 1 ? 'selected' : '').'>'.$this->l('Display of selected products at Random').'</option>
	            <option value="2" '.(Configuration::get('prd_cat_dsp_order') == 2 ? 'selected' : '').'>'.$this->l('Display of selected products by Price in ASCENDING order').'</option>
	            <option value="3" '.(Configuration::get('prd_cat_dsp_order') == 3 ? 'selected' : '').'>'.$this->l('Display of selected products by Price in DECENDING order').'</option>
	            <option value="4" '.(Configuration::get('prd_cat_dsp_order') == 4 ? 'selected' : '').'>'.$this->l('Display of selected products Latest updated in first').'</option>
	            <option value="5" '.(Configuration::get('prd_cat_dsp_order') == 5 ? 'selected' : '').'>'.$this->l('Display of selected products Oldest updated in first').'</option>
	            <option value="6" '.(Configuration::get('prd_cat_dsp_order') == 6 ? 'selected' : '').'>'.$this->l('Display of selected products in Alphabetical order').'</option>
	            <option value="7" '.(Configuration::get('prd_cat_dsp_order') == 7 ? 'selected' : '').'>'.$this->l('Display of selected products in Reverse Alphabetical order').'</option>
	            </select>
	            <br><br>


			5.&nbsp;&nbsp;'.$this->l('Number Of Prducts to be Displayed in this block').'
				<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="text" size="5" name="prd_num_dsp" id="prd_num_dsp" value="'.Configuration::get('prd_num_dsp').'" />
				<br><br>

            6.&nbsp;&nbsp;'.$this->l('Display only New Products').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="prd_disp_new" id="prd_disp_new1"  '.((1 == (int)Configuration::get('prd_disp_new')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="prd_disp_new" id="prd_disp_new0" '.((0 == (int)Configuration::get('prd_disp_new')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>

			7.&nbsp;&nbsp;'.$this->l('Display only  On sale').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="prd_disp_sale" id="prd_disp_sale1"  '.((1 == (int)Configuration::get('prd_disp_sale')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="prd_disp_sale" id="prd_disp_sale0" '.((0 == (int)Configuration::get('prd_disp_sale')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>

			8.&nbsp;&nbsp;'.$this->l('Display only Reduced Prices').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="prd_disp_reductions" id="prd_disp_reductions1"  '.((1 == (int)Configuration::get('prd_disp_reductions')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="prd_disp_reductions" id="prd_disp_reductions0" '.((0 == (int)Configuration::get('prd_disp_reductions')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>



			9.&nbsp;&nbsp;'.$this->l('Block Heading Title').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="text" size="35" minlength="2"  name="hdg_title" id="hdg_title" value="'.Configuration::get('hdg_title').'" required aria-required="true"/>
            	&nbsp;&nbsp;'.$this->l('Language').'&nbsp:-&nbsp;'.$lang_selected_details['name'].'
				<br><br>';


			$output .='
			10.&nbsp;&nbsp;'.$this->l('Add to cart show').'
				<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="add_cart_shw" id="add_cart_shw1"  '.((1 == (int)Configuration::get('add_cart_shw')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="add_cart_shw" id="add_cart_shw0" '.((0 == (int)Configuration::get('add_cart_shw')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>



			11.&nbsp;&nbsp;'.$this->l('More button show').'
				<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="more_shw" id="more_shw1"  '.((1 == (int)Configuration::get('more_shw')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="more_shw" id="more_shw0" '.((0 == (int)Configuration::get('more_shw')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>

			12.&nbsp;&nbsp;'.$this->l('Quick view show').'
				<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="qck_view_shw" id="qck_view_shw1"  '.((1 == (int)Configuration::get('qck_view_shw')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="qck_view_shw" id="qck_view_shw0" '.((0 == (int)Configuration::get('qck_view_shw')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>

			13.&nbsp;&nbsp;'.$this->l('Display price').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="dsp_prc" id="dsp_prc1"  '.((1 == (int)Configuration::get('dsp_prc')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="dsp_prc" id="dsp_prc0" '.((0 == (int)Configuration::get('dsp_prc')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>

			14.&nbsp;&nbsp;'.$this->l('Display count down').'
            	<br>
            	&nbsp;&nbsp;&nbsp;&nbsp;
            	<input type="radio" value="1" name="tim_cnt_dwn_shw" id="tim_cnt_dwn_shw1"  '.((1 == (int)Configuration::get('dsp_stk')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="tim_cnt_dwn_shw" id="tim_cnt_dwn_shw0" '.((0 == (int)Configuration::get('dsp_stk')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>
			15.&nbsp;&nbsp;'.$this->l('Enable This Advertisment below').'
				<BR>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" value="1" name="en_dis_adv"  id="en_dis_adv1"  '.((1 == (int)Configuration::get('en_dis_blk')) ? 'checked="checked" ' : '').' ">
				<label class="ms_t" for="text_list_on"> <img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				&nbsp;&nbsp;
				<input type="radio" value="0" name="en_dis_adv" id="en_dis_adv0" '.((0 == (int)Configuration::get('en_dis_blk')) ? 'checked="checked"' : '').' ">
				<label class="ms_t" for="text_list_off"> <img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				<BR><BR>
			16.&nbsp;&nbsp;'.$this->l('Advertisment').'
				<BR>
				<BR>
				&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<textarea name="mkst_ad_blk" id="mkst_ad_blk" style="width:80%; height:200px;">
				'.Configuration::get('mkst_ad_blk').'
				</textarea>
					<BR><BR>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<input  id="buttonhpms" type="submit" value="'.$this->l('Submit').'" name="hmprdcsms">
				</form>

				</div>';




				//----------------------------TABLE START ----------------------------------------------------------------------------//


				$output .='
				<script type="text/javascript" src="../js/jquery/plugins/jquery.tablednd.js"></script>
				<script type="text/javascript">
					$(document).ready(function() {
    				$("#tablems").tableDnD({

					 onDrop: function(table, row) {
        			 var orders = $.tableDnD.serialize();
        			 $.ajax({
					 			type: "POST",
								url: "../modules/homeproductsms/ajax/ajax_pos_updt.php",
								async: true,
								cache: false,
								data: orders + "&hmprdmsseckey='.$this->secure_key.'",
								success: function(html)
								{
								$(".notif_ms").fadeIn(3000).delay(5000).fadeOut(3000);

								}
});


    }



    									});
					});
					</script>';


	$output .='
				<hr class="style-two"></hr>
				<div  class="notif_ms"><div class="text">Your Positions Have Been Updated.</div></div>
				<div  class="notif_ms_del"><div class="text">Deleted</div></div>
				<div  style="float:right" onClick="sub_new()" id="buttonhpms" name="Add new">Add a New Block</div>

				<table id="tablems" class="hm_prd_ms_tb">
					<thead>
						<tr class="nodrag nodrop">
							<th>'.$this->l('ID.').'</th>
							<th>'.$this->l('Display Number').'<br> '.$this->l('Of Products').'</th>
							<th>'.$this->l('En|Disabled').'</th>
							<th>'.$this->l('Product cat').'</th>
							<th>'.$this->l('Heading').'<br> '.$this->l('Lanuage').'|&nbsp;'.$lang_selected_details['name'].'</th>
							<th>'.$this->l('Edit/delete').'</th>
						</tr>
					</thead>
					<tbody>
						';



						//selectiong of language
            		$output  .='<form action="" method="post">
            		 				<select name="lang_select_hpms" id="lang_select_hpms">
            							';
            								foreach ($languages as $lang_m)
            								{
                								if ($lang_m['name'])
                    							$output .= '<option value="'.$lang_m['id_lang'].'" '.(Configuration::get('lang_select_hpms') == $lang_m['id_lang'] ? 'selected' : '').'  >'.$lang_m['name'].'</option>';
            								}
            								$output .='<input style="margin:5px" type="submit" name="submit_lang_new_edit" value="'.$this->l('Select Language For Headings').'" class="button" />
            						</select>
            					</form>';




						/* Retrieval of the shop cats to construct the multiple select */

						//use selected language

						$getallblockinfos = $this->getAllBlockInfo((int)$lang_selected_id);
						//exit(var_dump($getallblockinfos));
						if ($getallblockinfos)
						 foreach ($getallblockinfos as $gtalblckinf)
						 {
						$output .='
						<tr class ="ms_'.$gtalblckinf['id_ms'].'" id="'.$gtalblckinf['id_ms'].'">
							<td><center>'.$gtalblckinf['id_ms'].'</center></td>
							';
							// see if it is product or advertisment

								$output .= '<td><center>'.$gtalblckinf['prd_num_dsp'].'</center></td>';


							//see if it is enabled or disabled
							if ((int)$gtalblckinf['en_dis_blk'] == 1)
							{
								$output .= '<td><center><img src="'.$this->_path.'/img/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /><center></td>';
							}
							else
							{
								$output .= '<td><img src="'.$this->_path.'/img/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></td>';
							}

							//show which categories
							//convert num to name in categories
							$get_cat_ms_explode = explode(',',$gtalblckinf['prd_cat_shw']);
							$get_cat_ms = Category::getCategoryInformations($get_cat_ms_explode);
							$output .='<td>';
							foreach ($get_cat_ms as $get_cat_ms_name)
									$output .=''.$get_cat_ms_name['name'].',&nbsp;';
							$output .='</td>';

							//title  show
							if ($gtalblckinf['hdg_title_lang'])
							{
								$output .='<td>'.$gtalblckinf['hdg_title_lang'].'</td>';
							}
							else
							{
								$output .='<td>'.$this->l('Heading in ').''.$lang_selected_details['name'].' '.$this->l('is empty please click edit to add.').'</td>';
							}
							//edit
							$output .='<td>
										<form id="ed_del" name="'.$gtalblckinf['id_ms'].'" action="'.$_SERVER['REQUEST_URI'].'" method="post">
										<center>
										<div  onclick="edit_blk(
																'.$gtalblckinf['id_ms'].',
																'.$gtalblckinf['en_dis_blk'].',
																\''.$gtalblckinf['prd_cat_shw'].'\',
																'.$gtalblckinf['prd_cat_dsp_order'].',
																'.$gtalblckinf['prd_num_dsp'].',
																'.$gtalblckinf['prd_disp_new'].',
																\''.$gtalblckinf['hdg_title_lang'].'\',
																\''.$gtalblckinf['add_cart_shw'].'\',
																'.$gtalblckinf['more_shw'].',
																\''.$gtalblckinf['qck_view_shw'].'\',
																'.$gtalblckinf['dsp_prc'].',
																'.$gtalblckinf['tim_cnt_dwn_shw'].',
																'.$gtalblckinf['pos_blk'].',
																'.$gtalblckinf['en_dis_adv'].',
																\''.preg_replace('/\r?\n/', '\\n', addslashes(htmlentities($gtalblckinf['mkst_advert_blk']))).'\',
																'.$gtalblckinf['prd_disp_sale'].',
																'.$gtalblckinf['prd_disp_reductions'].'
																)"
										id="buttonhpms">'.$this->l('Edit').'</div>
										</center>

										<center><div id="buttonhpms" onclick="dele_blk('.$gtalblckinf['id_ms'].')">'.$this->l('Delete').'</div></center></td>
										</form>';

							$output .='
						</tr>
							';
						}
				 		$output .='
				 	</tbody>
				</table>';
				//get general settings info
				//get general settings from table
				$get_gen_set_info = $this->get_gen_set_info();
				foreach ($get_gen_set_info as $gen_settings)
					$gen_set_tab  = $gen_settings['head_disp_typ'];
					$gen_set_center_ms  = $gen_settings['center_ms'];


				$output .='
				<hr class="style-two"></hr>
				<h1>'.$this->l('General Settings').'</h1>
				<br>
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post">

		            1.&nbsp;&nbsp;'.$this->l('Category Header Style').'
            			<br>
            			&nbsp;&nbsp;&nbsp;&nbsp;
            			<select name="cat_header_style" id="cat_header_style">
			            <option value="0" '.((int)$gen_set_tab == 0 ? 'selected' : '').'>'.$this->l('Tabbed').'</option>
			            <option value="1" '.((int)$gen_set_tab == 1 ? 'selected' : '').'>'.$this->l('Non Tabbed').'</option>
			            </select>
			            <br><br>

					2.&nbsp;&nbsp;'.$this->l('Show The Products Centered or Left Aligned').'
						<BR>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" value="0" name="center_ms"  id="center_ms"  '.((0 == (int)$gen_set_center_ms) ? 'checked="checked" ' : '').' ">
						&nbsp; Left Aligned
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" value="1" name="center_ms" id="center_ms" '.((1 == (int)$gen_set_center_ms) ? 'checked="checked"' : '').' ">
						&nbsp; Center Aligned
						<BR><BR>
			            <input  id="buttonhpms" type="submit" value="'.$this->l('Submit').'" name="hpms_general_set">
				</form>
				';
				
				$output .= '
				<br>
<a id="buttonhpms"target="_blank"href="'.$this->context->link->getAdminLink('AdminModulesPositions', true).'#displayHome">Display Position</a>
'.$this->l('<---- Change the position on the home page where the products are to be displayed.(clicking this will take you to " displayHome " there look for " Display Products on the home page" and change position  )').'

			</div>


			';

			return $output;

	}
	public function recurseCategorys($categories, $current, $id_category = 1, $selectids_array)
    {
			if ($id_category != 1)
			{
            echo '
            <option value="'.$id_category.'"'.(in_array($id_category,$selectids_array) ? ' selected="selected"' : '').'>'
            .str_repeat('-',$current ['infos']['level_depth'] * 5) . '|&nbsp;&nbsp;'.preg_replace('/^[0-9]+\./', '', Tools::stripslashes($current['infos']['name'])),'&nbsp;&nbsp|'.
            '</option>';
			}
            if (isset($categories[$id_category]))
                foreach ($categories[$id_category] AS $key => $row)
                    $this->recurseCategorys($categories, $categories[$id_category][$key], $key, $selectids_array);
           
            $row ='';       
            if ($row) {}; //added for prestashop
    }

    public function getAllBlockInfo($id_lang)
    {

        return Db::getInstance()->ExecuteS('
                SELECT p.*, pa.*
                FROM `'._DB_PREFIX_.'homeproductsms` p
                LEFT JOIN `'._DB_PREFIX_.'homeproductsms_lang` pa
                ON (p.`id_ms` = pa.`id_mss` AND pa.`id_lang` = '.(int)$id_lang.')
                ORDER BY p.`pos_blk` ASC
                ');
    }

     public function get_all_head_title_lang()
    {
		return Db::getInstance()->ExecuteS('
                SELECT *
                FROM `'._DB_PREFIX_.'homeproductsms_lang`
                ORDER BY `id_ms` ASC
                ');
    }

    public function get_gen_set_info()
    {
		return Db::getInstance()->ExecuteS('
                SELECT *
                FROM `'._DB_PREFIX_.'homeproductsms_gen_set`
                ');
    }

    public function getcurrposblck()
    {
		return Db::getInstance()->ExecuteS('
                SELECT pos_blk
                FROM `'._DB_PREFIX_.'homeproductsms`
                ');
    }

	public function hookBackOfficeHeader()
	{
		$this->context->controller->addJS($this->_path.'js/back.js');
		$this->context->controller->addCSS($this->_path.'css/back/back.css');
	}

	/**
	 * Add the CSS & JavaScript files you want to be added on the FO.
	 */
	public function hookHeader()
	{

		$this->context->controller->addJS($this->_path.'/js/front.js');
		$this->context->controller->addJS($this->_path.'/js/tabs/tabs_ms.js');
		$this->context->controller->addJS($this->_path.'/js/qckviu/qckviu.js');
		$this->context->controller->addJS($this->_path.'/js/count_timer/jq_cnt_down.js');
		$this->context->controller->addCSS($this->_path.'/css/front/main.css');

	}

	public function hookDisplayHome()
	{
		//get all blocks information
		$get_id_fr_disp = $this->getAllBlockInfo((int)Context::getContext()->language->id);


		//get general settings from table
		$get_gen_set_info = $this->get_gen_set_info();
		foreach ($get_gen_set_info as $gen_settings)
		{
			$gen_set_tab  = $gen_settings['head_disp_typ'];
			$gen_set_center_ms  = $gen_settings['center_ms'];
		}

		//exit(var_dump((int)$gen_set_tab));

		$timeZone = Configuration::get('PS_TIMEZONE');
    	//$dateSrc = date('Y-m-d h:i:s');
    	//$dateTime = new DateTime($dateSrc);
    	//$curr_date_hpm =  date('Y-m-d h:i:s', strtotime($dateSrc));


		$dateTimeutczone = new DateTimeZone("UTC");
		$dateTimeshopzone = new DateTimeZone($timeZone);


		$dateTimeutc = new DateTime("now", $dateTimeutczone);
		//$dateTimeshop = new DateTime("now", $dateTimeshopzone);

		$timeOffset = $dateTimeshopzone->getOffset($dateTimeutc);
		$time_offset_hours = (int)$timeOffset/3600;
		//exit (var_dump($time_offset_hours));

		$blk_home_prdcts = array();

		foreach ($get_id_fr_disp as $got_blck_info)
		{
            $nbb2 = (int)$got_blck_info['prd_num_dsp'];
            $sort_blck = (int)$got_blck_info['prd_cat_dsp_order'];
            $cat_disp = (string)$got_blck_info['prd_cat_shw'];
            $sale_onlya = (int)$got_blck_info['prd_disp_sale'];
            $new_onlya = (int)$got_blck_info['prd_disp_new'];
            $prd_disp_reductions = (int)$got_blck_info['prd_disp_reductions'];


			//sorting
			/*
			switch ($sort_blck) {
                case '0':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10));
                    break;
                case '1':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, 1000);
                    shuffle($products_home);
                    array_splice($products_home, ($nbb2 ? $nbb2 : 10));
                    break;
                case '2':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'weight', 'ASC');
                    break;
                case '3':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'price', 'DESC');
                    break;
                case '4':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'date_upd', 'DESC');
                    break;
                case '5':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'date_add', 'DESC');
                    break;
                case '6':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'name', 'ASC');
                    break;
                case '7':
                    $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'date_add', 'DESC', false, true, true, 30);
                    break;



                default:
                $products_home = $def_sort->getProductselect_cat((int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10));
                break;
            }
			*/
			if ($sort_blck == 0)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10),NUll,NULL,NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 1)
            {
			$products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, 1000,NUll,NULL,NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
                    shuffle($products_home);
                    array_splice($products_home, ($nbb2 ? $nbb2 : 10));
            }
                  
            if ($sort_blck == 2)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'price', 'ASC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 3)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'price', 'DESC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 4)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'date_upd', 'ASC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 5)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'date_upd', 'DESC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 6)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'name', 'ASC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            if ($sort_blck == 7)
            {
            $products_home = $this->getProductselect_cat($cat_disp,(int)Context::getContext()->language->id, 1, ($nbb2 ? $nbb2 : 10), 'name', 'DESC',NULL,NULL,NULL,NULL,NULL,NULL,$sale_onlya, $new_onlya, $prd_disp_reductions);
            }
            
            
        	$blk_home_prdcts[] = array(
					'id_ms' => (int)$got_blck_info['id_ms'],
					'products_home' => $products_home,
					'en_dis_blk' => (int)$got_blck_info['en_dis_blk'],
					'prd_disp_new' => (int)$got_blck_info['prd_disp_new'],
					'hdg_title_lang' => (string)$got_blck_info['hdg_title_lang'],
					'hdg_title' => (string)$got_blck_info['hdg_title'],
					'add_cart_shw' => (int)$got_blck_info['add_cart_shw'],
					'more_shw' => (int)$got_blck_info['more_shw'],
					'qck_view_shw' => (int)$got_blck_info['qck_view_shw'],
					'dsp_prc' => (int)$got_blck_info['dsp_prc'],
					'tim_cnt_dwn_shw' => (int)$got_blck_info['tim_cnt_dwn_shw'],
					'en_dis_adv' => (int)$got_blck_info['en_dis_adv'],
					'mkst_advert_blk' => (string)$got_blck_info['mkst_advert_blk']

				);

		}

		$this->smarty->assign('blk_home_prdcts' , $blk_home_prdcts);
		$this->smarty->assign('theme_path' , $this->_path);
		$this->smarty->assign('time_offset_hours' , $time_offset_hours);
		$this->smarty->assign('gen_set_tab' , $gen_set_tab);
		$this->smarty->assign('gen_set_center_ms' , $gen_set_center_ms);
		return $this->display(__FILE__, '/theme1/homeproductsms.tpl');
	}

public function getProductselect_cat($select_cat, $id_lang, $p, $n, $order_by = null, $order_way = null, $get_total = false, $active = true, $random = false, $random_number_products = 1, $check_access = false, Context $context = null, $sale_only_set = null, $new_only_set = null, $prd_disp_reductions = null)
	{
       // exit ($sale_only_set.' '.$new_only_set.' '.$prd_disp_reductions);
        $check_access = ' '; //added for prestshop
        if ($check_access){};  //for prestshop
        
		if (!$context)
			$context = Context::getContext();
            
			$now = date('Y-m-d H:i:s');
		// filter for new and slales

        
		//if sale only
		if ((int)$sale_only_set == 1 && (int)$new_only_set == 0 && (int)$prd_disp_reductions == 0)
		{
			$sale_only = 'AND p.`on_sale` = 1';
			$new_only = '';
			$reduction_only = '';
		}
		//if newonly
		elseif ((int)$new_only_set == 1 && (int)$sale_only_set == 0 && (int)$prd_disp_reductions == 0)
		{
			$new_only = 'AND DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
			$sale_only = '';
			$reduction_only = '';
		}
		//if specials reduced rates only
		elseif ((int)$prd_disp_reductions == 1 && (int)$new_only_set == 0 && (int)$sale_only_set == 0)
		{
			$reduction_only = 'AND
								(
									(splprc.`from` = \'0000-00-00 00:00:00\' OR \''.$now.'\' >= splprc.`from`)
									AND
									(splprc.`to` = \'0000-00-00 00:00:00\' OR \''.$now.'\' <= splprc.`to`)
								)
								';
			$new_only = '';
			$sale_only = '';


		}
		//if both sales and new but not reduction
		elseif ((int)$new_only_set == 1 && (int)$sale_only_set == 1 && (int)$prd_disp_reductions == 0)
		{
            
			$sale_only = 'AND p.`on_sale` = 1';
			$new_only = 'OR DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
			$reduction_only = '';
		}
		//if  both sales and reduction but not new
		elseif ((int)$sale_only_set == 1 && (int)$prd_disp_reductions == 1 && (int)$new_only_set == 0)
		{
			$sale_only = 'AND p.`on_sale` = 1';
			$reduction_only = 'OR
								(
									(splprc.`from` = \'0000-00-00 00:00:00\' OR \''.$now.'\' >= splprc.`from`)
									AND
									(splprc.`to` = \'0000-00-00 00:00:00\' OR \''.$now.'\' <= splprc.`to`)
								)
								';
			$new_only = '';
		}
		//if  both new and reduction but not sales
		elseif ((int)$new_only_set == 1 && (int)$prd_disp_reductions == 1  && (int)$sale_only_set == 0)
		{
			$new_only = 'AND DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
			$reduction_only = 'OR
								(
									(splprc.`from` = \'0000-00-00 00:00:00\' OR \''.$now.'\' >= splprc.`from`)
									AND
									(splprc.`to` = \'0000-00-00 00:00:00\' OR \''.$now.'\' <= splprc.`to`)
								)
								';
			$sale_only = '';
		}
		// all three show new , sale and reduction
		elseif ((int)$new_only_set == 1 && (int)$prd_disp_reductions == 1  && (int)$sale_only_set == 1)
		{
            
			$sale_only = 'AND p.`on_sale` = 1';
			$new_only = 'OR DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
			$reduction_only = 'OR
								(
									(splprc.`from` = \'0000-00-00 00:00:00\' OR \''.$now.'\' >= splprc.`from`)
									AND
									(splprc.`to` = \'0000-00-00 00:00:00\' OR \''.$now.'\' <= splprc.`to`)
								)
								';
		}
		// nuthing then show all products
		else
		{
			$new_only = '';
			$sale_only = '';
			$reduction_only = '';

		}
/*
		//for specials on sale only products
		if ($sale_only_set == 1 && $new_only_set == 0)
		{
			$sale_only = 'AND p.`on_sale` = 1';
		}
		else
		{
			$sale_only = '';
		}

		//for new only products
		if ($new_only_set == 1 && $sale_only_set == 0)
		{
		$new_only = 'AND DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
		}
		else
		{
			$new_only = '';
		}

//for new only products and sale both
		if ($new_only_set == 1 && $sale_only_set == 1)
		{

			$sale_only = 'AND p.`on_sale` = 1';
			$new_only = 'OR DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
					)
					) > 0';
		}
		else
		{
			$new_only = '';
			$sale_only = '';
		}
*/

		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;

		if ($p < 1) $p = 1;

		if (empty($order_by))
			$order_by = 'position';
		else
			/* Fix for all modules which are now using lowercase values for 'orderBy' parameter */
			$order_by = Tools::strtolower($order_by);

		if (empty($order_way))
			$order_way = 'ASC';

		$order_by_prefix = false;
		if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		elseif ($order_by == 'name')
			$order_by_prefix = 'pl';
		elseif ($order_by == 'manufacturer')
		{
			$order_by_prefix = 'm';
			$order_by = 'name';
		}
		elseif ($order_by == 'position')
			$order_by_prefix = 'cp';

		if ($order_by == 'price')
			$order_by = 'orderprice';

		if (!Validate::isBool($active) || !Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());

		$id_supplier = (int)Tools::getValue('id_supplier');


		/* Return only the number of products */
		if ($get_total)
		{
			$sql = 'SELECT COUNT(cp.`id_product`) AS total
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
					WHERE cp.`id_category` = '.(int)$this->id.
					($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
					($active ? ' AND product_shop.`active` = 1' : '').
					($id_supplier ? 'AND p.id_supplier = '.(int)$id_supplier : '');
			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		}

		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
						LEFT JOIN `'._DB_PREFIX_.'product` p
							ON p.`id_product` = cp.`id_product`
						'.Shop::addSqlAssociation('product', 'p').'
						LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
						ON (p.`id_product` = pa.`id_product`)
						'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
						'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
						LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
							ON (product_shop.`id_category_default` = cl.`id_category`
							AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
						LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
							ON (p.`id_product` = pl.`id_product`
							AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
						LEFT JOIN `'._DB_PREFIX_.'image` i
							ON (i.`id_product` = p.`id_product`)'.
						Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
						LEFT JOIN `'._DB_PREFIX_.'image_lang` il
							ON (image_shop.`id_image` = il.`id_image`
							AND il.`id_lang` = '.(int)$id_lang.')
						LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
							ON m.`id_manufacturer` = p.`id_manufacturer`
						LEFT JOIN `'._DB_PREFIX_.'specific_price` splprc
							ON p.`id_product` = splprc.`id_product`
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
					AND cp.`id_category` IN ('.$select_cat.')

					'.($active ? ' AND product_shop.`active` = 1' : '')
					.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
					.($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '').'
					'.(string)$sale_only.'
					'.(string)$new_only.'
					'.(string)$reduction_only.'


					GROUP BY product_shop.id_product';

		if ($random === true)
			$sql .= ' ORDER BY RAND() LIMIT '.(int)$random_number_products;
		else
			$sql .= ' ORDER BY '.(!empty($order_by_prefix) ? $order_by_prefix.'.' : '').'`'.bqSQL($order_by).'` '.pSQL($order_way).'
			LIMIT '.(((int)$p - 1) * (int)$n).','.(int)$n;



		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if ($order_by == 'orderprice')
			Tools::orderbyPrice($result, $order_way);

		if (!$result)
			return array();

		//exit (var_dump($result));
		/* Modify SQL result */
		return Product::getProductsProperties($id_lang, $result);
	}

	public function hookAddProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookCategoryUpdate($params)
	{
		$this->_clearCache('*');
	}

	public function _clearCache($template, $cache_id = NULL, $compile_id = NULL)
	{
        if ($template){};
        if ($cache_id){};
        if ($compile_id){};
		parent::_clearCache('homeproductms.tpl');
	}

}