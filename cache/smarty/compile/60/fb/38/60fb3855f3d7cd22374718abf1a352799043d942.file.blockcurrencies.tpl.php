<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/blockcurrencies/blockcurrencies.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1413708037598976a9035a21-09156715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60fb3855f3d7cd22374718abf1a352799043d942' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/blockcurrencies/blockcurrencies.tpl',
      1 => 1454626470,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1413708037598976a9035a21-09156715',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'currencies' => 0,
    'request_uri' => 0,
    'cookie' => 0,
    'f_currency' => 0,
    'currency_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a90ac0b5_00082625',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a90ac0b5_00082625')) {function content_598976a90ac0b5_00082625($_smarty_tpl) {?>
<!-- Block currencies module -->
<?php if (count($_smarty_tpl->tpl_vars['currencies']->value)>1) {?>
	<div id="currencies-block-top">
		<form id="setCurrency" action="<?php echo $_smarty_tpl->tpl_vars['request_uri']->value;?>
" method="post">
			<div class="current">
				<input type="hidden" name="id_currency" id="id_currency" value=""/>
				<input type="hidden" name="SubmitCurrency" value="" />
				
				<?php  $_smarty_tpl->tpl_vars['f_currency'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f_currency']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['currencies']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f_currency']->key => $_smarty_tpl->tpl_vars['f_currency']->value) {
$_smarty_tpl->tpl_vars['f_currency']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['f_currency']->key;
?>
					<?php if ($_smarty_tpl->tpl_vars['cookie']->value->id_currency==$_smarty_tpl->tpl_vars['f_currency']->value['id_currency']) {?> <?php echo $_smarty_tpl->tpl_vars['f_currency']->value['iso_code'];?>
 (<?php echo $_smarty_tpl->tpl_vars['f_currency']->value['sign'];?>
) <?php }?>
				<?php } ?>
			</div>
			<ul id="first-currencies" class="currencies_ul toogle_content">
				<?php  $_smarty_tpl->tpl_vars['f_currency'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f_currency']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['currencies']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f_currency']->key => $_smarty_tpl->tpl_vars['f_currency']->value) {
$_smarty_tpl->tpl_vars['f_currency']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['f_currency']->key;
?>
					<?php if (strpos($_smarty_tpl->tpl_vars['f_currency']->value['name'],('(').($_smarty_tpl->tpl_vars['f_currency']->value['iso_code']).(')'))===false) {?>
						<?php ob_start();?><?php echo smartyTranslate(array('s'=>'%s (%s)','sprintf'=>array($_smarty_tpl->tpl_vars['f_currency']->value['name'],$_smarty_tpl->tpl_vars['f_currency']->value['iso_code'])),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php $_smarty_tpl->tpl_vars["currency_name"] = new Smarty_variable($_tmp7, null, 0);?>
					<?php } else { ?>
						<?php $_smarty_tpl->tpl_vars["currency_name"] = new Smarty_variable($_smarty_tpl->tpl_vars['f_currency']->value['name'], null, 0);?>
					<?php }?>
					<li <?php if ($_smarty_tpl->tpl_vars['cookie']->value->id_currency==$_smarty_tpl->tpl_vars['f_currency']->value['id_currency']) {?>class="selected"<?php }?>>
						<?php if ($_smarty_tpl->tpl_vars['cookie']->value->id_currency!=$_smarty_tpl->tpl_vars['f_currency']->value['id_currency']) {?>
						<a href="javascript:setCurrency(<?php echo $_smarty_tpl->tpl_vars['f_currency']->value['id_currency'];?>
);" rel="nofollow" title="<?php echo $_smarty_tpl->tpl_vars['currency_name']->value;?>
">
						<?php } else { ?>
						<span>
						<?php }?>
							<?php echo $_smarty_tpl->tpl_vars['f_currency']->value['iso_code'];?>
 (<?php echo $_smarty_tpl->tpl_vars['f_currency']->value['sign'];?>
)
						<?php if ($_smarty_tpl->tpl_vars['cookie']->value->id_currency!=$_smarty_tpl->tpl_vars['f_currency']->value['id_currency']) {?>
						</a>
						<?php } else { ?>
						</span>
						<?php }?>
					</li>
				<?php } ?>
			</ul>
		</form>
	</div>
<?php }?>
<!-- /Block currencies module --><?php }} ?>
