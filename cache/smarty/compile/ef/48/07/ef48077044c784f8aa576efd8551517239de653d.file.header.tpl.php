<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:703587325598976a763dd47-21234006%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef48077044c784f8aa576efd8551517239de653d' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/header.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '703587325598976a763dd47-21234006',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bOpenGraph' => 0,
    'aRating' => 0,
    'aProduct' => 0,
    'sURI' => 0,
    'sShopName' => 0,
    'oJsTranslatedMsg' => 0,
    'sModuleURI' => 0,
    'sModuleName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a76715d6_57056228',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a76715d6_57056228')) {function content_598976a76715d6_57056228($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>

<?php if (!empty($_smarty_tpl->tpl_vars['bOpenGraph']->value)&&!empty($_smarty_tpl->tpl_vars['aRating']->value['review']['data'])) {?>
<meta property="og:title" content="<?php echo smartyTranslate(array('s'=>'Customer review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 : <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aRating']->value['review']['data']['sTitle'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<meta property="og:type" content="product"/>
<meta property="og:image" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['img'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<meta property="og:url" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<meta property="og:site_name" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sShopName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<meta property="og:description" content="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aRating']->value['review']['data']['sComment'], 'UTF-8');?>
" />
<?php }?>

<script type="text/javascript" data-keepinline="true">
	// instantiate object
	
		var oGsr = {};
		var bt_msgs = <?php echo $_smarty_tpl->tpl_vars['oJsTranslatedMsg']->value;?>
;
		var bt_sImgUrl = '<?php echo smarty_modifier_escape(@constant('_GSR_URL_IMG'), 'UTF-8');?>
';
		var bt_sWebService = '<?php if (!empty($_smarty_tpl->tpl_vars['sModuleURI']->value)) {?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sModuleURI']->value, 'UTF-8');?>
<?php }?>';
		var sGsrModuleName = '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
';
		var bt_aFancyReviewForm = {};
		var bt_aFancyReviewTabForm = {};
		var bt_oScrollTo = {};
		bt_oScrollTo.execute = false;
		var bt_oUseSocialButton = {};
		var bt_oActivateReviewTab = {};
		bt_oActivateReviewTab.run = false;
		var bt_oDeactivateReviewTab = {};
		bt_oDeactivateReviewTab.run = false;
		var bt_aReviewReport = new Array();
		var bt_oCallback = {};
		bt_oCallback.run = false;
		var bt_aStarsRating = new Array();
		var bt_oBxSlider = {};
		bt_oBxSlider.run = false;
	
		
		
		

		
		

		
		
		
		
	
</script><?php }} ?>
