<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/modules/mgdstickyfooter/views/templates/front/template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1058230049598976a92ddc93-45236200%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '190190870dd02b1fffb33a488b0228418db82564' => 
    array (
      0 => '/home/prestashop-pp/www/modules/mgdstickyfooter/views/templates/front/template.tpl',
      1 => 1454623240,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1058230049598976a92ddc93-45236200',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a92ed2f2_27538692',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a92ed2f2_27538692')) {function content_598976a92ed2f2_27538692($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>
<nav class="navbar navbar-default navbar-fixed-bottom stickyFooter">
    <div class="container">
        <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['content']->value, 'nofilter');?>

    </div>
</nav><?php }} ?>
