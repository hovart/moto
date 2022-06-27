<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/modules/tptnheaderlinks/views/templates/hook/tptnheaderlinks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1384374413598976a9141418-04339388%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd1f17ef45f5eb55e96ba7ecba09d4f5f398d0a1' => 
    array (
      0 => '/home/prestashop-pp/www/modules/tptnheaderlinks/views/templates/hook/tptnheaderlinks.tpl',
      1 => 1457616706,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1384374413598976a9141418-04339388',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'logged' => 0,
    'link' => 0,
    'isMobile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a9196ab4_42787708',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a9196ab4_42787708')) {function content_598976a9196ab4_42787708($_smarty_tpl) {?><div id="tptn_header_links">
<ul>
	<?php if ($_smarty_tpl->tpl_vars['logged']->value) {?>
		<li>
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'My account','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
" rel="nofollow"><i class="fa fa-user left"></i>
			<?php if ($_smarty_tpl->tpl_vars['isMobile']->value!='mobile') {?>
				<?php echo smartyTranslate(array('s'=>'My account','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
</a>
			<?php }?>
		</li>
		<li>
			<a class="logout" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('index',true,null,"mylogout"), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Sign out','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
"><i class="fa fa-sign-out left"></i>
			<?php if ($_smarty_tpl->tpl_vars['isMobile']->value!='mobile') {?>
				<?php echo smartyTranslate(array('s'=>'Sign out','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
</a>
			<?php }?>
		</li>
	<?php } else { ?>
		<li>
			<a class="login" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Sign in','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
"><i class="fa fa-sign-in left"></i>
			<?php if ($_smarty_tpl->tpl_vars['isMobile']->value!='mobile') {?>
				<?php echo smartyTranslate(array('s'=>'Sign in','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
</a>
			<?php }?>
		</li>
		<li>
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
" rel="nofollow" title="<?php echo smartyTranslate(array('s'=>'Register','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
"><i class="fa fa-user-plus left"></i>
			<?php if ($_smarty_tpl->tpl_vars['isMobile']->value!='mobile') {?>
				<?php echo smartyTranslate(array('s'=>'Register','mod'=>'tptnheaderlinks'),$_smarty_tpl);?>
</a>
			<?php }?>
		</li>
	<?php }?>
</ul>
</div><?php }} ?>
