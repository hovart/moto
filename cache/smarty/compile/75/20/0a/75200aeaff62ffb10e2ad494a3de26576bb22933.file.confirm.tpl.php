<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:29:59
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/confirm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:46797714759897687b6e950-38245053%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75200aeaff62ffb10e2ad494a3de26576bb22933' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/confirm.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '46797714759897687b6e950-38245053',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sMsg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_59897687b7b0a7_04541784',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59897687b7b0a7_04541784')) {function content_59897687b7b0a7_04541784($_smarty_tpl) {?>

<div class="alert alert-success" id="bt_confirm"><button type="button" class="close" data-dismiss="alert">×</button>
	<?php echo smartyTranslate(array('s'=>'Settings updated','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<?php if (isset($_smarty_tpl->tpl_vars['sMsg']->value)) {?>&nbsp;(<?php echo smartyTranslate(array('s'=>'e-mail reminders are','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
&nbsp;<strong><?php if ($_smarty_tpl->tpl_vars['sMsg']->value==1) {?><?php echo smartyTranslate(array('s'=>'now active','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'now inactive','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<?php }?></strong>)<?php }?>
</div><?php }} ?>
