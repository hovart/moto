<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/modules/facebookpixel/views/templates/hook/display.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1913834080598976a87bf7f4-75330115%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0dd55ad5a680e3875edaef7d54bba8403ddca5bc' => 
    array (
      0 => '/home/prestashop-pp/www/modules/facebookpixel/views/templates/hook/display.tpl',
      1 => 1481123994,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1913834080598976a87bf7f4-75330115',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'status' => 0,
    'code' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a87da1c5_67848015',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a87da1c5_67848015')) {function content_598976a87da1c5_67848015($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>

<?php if ($_smarty_tpl->tpl_vars['status']->value=='1') {?>
	<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['code']->value, '');?>

<?php }?><?php }} ?>
