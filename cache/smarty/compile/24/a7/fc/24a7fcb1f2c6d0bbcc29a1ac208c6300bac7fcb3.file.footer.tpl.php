<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1266398504598976a921b038-32137549%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24a7fcb1f2c6d0bbcc29a1ac208c6300bac7fcb3' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/footer.tpl',
      1 => 1455881174,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1266398504598976a921b038-32137549',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content_only' => 0,
    'page_name' => 0,
    'HOOK_FOOTER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a9233db3_27912264',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a9233db3_27912264')) {function content_598976a9233db3_27912264($_smarty_tpl) {?>
	<?php if (!isset($_smarty_tpl->tpl_vars['content_only']->value)||!$_smarty_tpl->tpl_vars['content_only']->value) {?>
						
					</div> <!-- #center_column -->
				<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?>
				</div>
				</div>
				<?php }?>
			</div> <!-- #columns -->
			<div id="topFooter">
				<div class="container">
					<div class="row">
						<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'TopFooter'),$_smarty_tpl);?>

					</div>
				</div>
			</div>
			<?php if (isset($_smarty_tpl->tpl_vars['HOOK_FOOTER']->value)) {?>

					<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'StickyFooter'),$_smarty_tpl);?>



			<!-- Footer -->			
			<footer id="footer">
				<div class="footer_top">
					<div class="container">
					<div class="row">
						<?php echo $_smarty_tpl->tpl_vars['HOOK_FOOTER']->value;?>

					</div>
					</div>
				</div>
				<div class="footer_bottom">
					<div class="container">
					<div class="row">
						<div class="copyright_txt col-xs-12 col-md-12">©Copyright Motogoodeal <bR>Designed and handcrafted by <a href="http://details.ch" alt="Details.ch - Agence de communication digitale - Genève">details.ch</a></div>

					</div>
					</div>
				</div>
			</footer>
			<?php }?>
			
		</div> <!-- #page -->
		<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'modalHook'),$_smarty_tpl);?>

	<?php }?>

	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./global.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


	</body>
</html><?php }} ?>
