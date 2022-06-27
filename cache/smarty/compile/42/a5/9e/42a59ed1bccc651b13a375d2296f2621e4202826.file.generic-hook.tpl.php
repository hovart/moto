<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/generic-hook.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1699415942598976a7bccf37-80380120%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '42a59ed1bccc651b13a375d2296f2621e4202826' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/generic-hook.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1699415942598976a7bccf37-80380120',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aContent' => 0,
    'sModuleName' => 0,
    'sContent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a7be81e7_86200384',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a7be81e7_86200384')) {function content_598976a7be81e7_86200384($_smarty_tpl) {?>
<?php if (!empty($_smarty_tpl->tpl_vars['aContent']->value)) {?>
	<div id="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
	<?php  $_smarty_tpl->tpl_vars['sContent'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sContent']->_loop = false;
 $_smarty_tpl->tpl_vars['iKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aContent']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sContent']->key => $_smarty_tpl->tpl_vars['sContent']->value) {
$_smarty_tpl->tpl_vars['sContent']->_loop = true;
 $_smarty_tpl->tpl_vars['iKey']->value = $_smarty_tpl->tpl_vars['sContent']->key;
?>
		<?php if (!empty($_smarty_tpl->tpl_vars['sContent']->value)) {?><?php echo $_smarty_tpl->tpl_vars['sContent']->value;?>
<?php }?>
	<?php } ?>
	</div>
<?php }?><?php }} ?>
