<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/blocknewsletter/blocknewsletter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1289251006598976a92751e6-84051615%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bdb331f145d3d3e4935391ed82f37b9504c381dc' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/blocknewsletter/blocknewsletter.tpl',
      1 => 1457967840,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1289251006598976a92751e6-84051615',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'msg' => 0,
    'nw_error' => 0,
    'page_name' => 0,
    'img_dir' => 0,
    'img_ps_dir' => 0,
    'lang_iso' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a92d0d23_51279604',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a92d0d23_51279604')) {function content_598976a92d0d23_51279604($_smarty_tpl) {?>
<!-- Block Newsletter module-->
<section id="newsletter_block_left" class="footer-block col-xs-12 col-md-4">
	<h4>Newsletter</h4>
	<!--<p class="lead"><?php echo smartyTranslate(array('s'=>'Subscribe to our newsletter and receive the latest offers, discounts and updates','mod'=>'blocknewsletter'),$_smarty_tpl);?>
</p>-->

    		<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('index',null,null,null,false,null,true), ENT_QUOTES, 'UTF-8', true);?>
" method="post">
    			<div class="form-group<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&$_smarty_tpl->tpl_vars['msg']->value) {?> <?php if ($_smarty_tpl->tpl_vars['nw_error']->value) {?>form-error<?php } else { ?>form-ok<?php }?><?php }?>" >
					<!-- offre inscription newsletter-->
					<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&$_smarty_tpl->tpl_vars['msg']->value) {?>
						<?php if (!$_smarty_tpl->tpl_vars['nw_error']->value) {?>
							<div class="bon-reduc-txt"><?php echo smartyTranslate(array('s'=>'Voici votre code de réduction ','mod'=>'blocknewsletter'),$_smarty_tpl);?>
 : <div class="bon-reduc">MGDNL5</div></div>
						<?php }?>
					<?php } else { ?>
						<!-- <?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?>
                    <div id="nl-img"> <img src="<?php echo $_smarty_tpl->tpl_vars['img_dir']->value;?>
/gift.png" title="<?php echo smartyTranslate(array('s'=>'Un bon de réduction pour les nouveaux inscrits'),$_smarty_tpl);?>
" /></div>
                <?php }?>-->

						<div id="nl-txt">
							<p style="padding:0 5px 10px 5px;"><img src="<?php echo $_smarty_tpl->tpl_vars['img_ps_dir']->value;?>
/admin/gold.gif"/> <?php if ($_smarty_tpl->tpl_vars['lang_iso']->value=='fr') {?>
									Inscrivez-vous à la newsletter et recevez un bon de réduction de 5CHF à partir de 100CHF

								<?php } else { ?>

									Abonnieren Sie unsere Newsletter und erhalten Sie einen Gutschein in Höhe von 5 CHF ab 100 CHF Einkauf
								<?php }?>
							</p></div>
					<?php }?>
					<!-- offre inscription newsletter-->
    				<input class="inputNew form-control newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&$_smarty_tpl->tpl_vars['msg']->value) {?><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
<?php } elseif (isset($_smarty_tpl->tpl_vars['value']->value)&&$_smarty_tpl->tpl_vars['value']->value) {?><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'Enter your e-mail','mod'=>'blocknewsletter'),$_smarty_tpl);?>
<?php }?>" />
                    <button type="submit" name="submitNewsletter" class="button"><i class="fa fa-check"></i></button>
    				<input type="hidden" name="action" value="0" />
    			</div>

				<div class="payment-icon">

				</div>
    <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"displayBlockNewsletterBottom",'from'=>'blocknewsletter'),$_smarty_tpl);?>

			</form>




</section>
<!-- /Block Newsletter module-->
<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&$_smarty_tpl->tpl_vars['msg']->value) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('msg_newsl'=>addcslashes($_smarty_tpl->tpl_vars['msg']->value,'\'')),$_smarty_tpl);?>
<?php }?><?php if (isset($_smarty_tpl->tpl_vars['nw_error']->value)) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('nw_error'=>$_smarty_tpl->tpl_vars['nw_error']->value),$_smarty_tpl);?>
<?php }?><?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'placeholder_blocknewsletter')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'placeholder_blocknewsletter'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'Enter your e-mail','mod'=>'blocknewsletter','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'placeholder_blocknewsletter'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&$_smarty_tpl->tpl_vars['msg']->value) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'alert_blocknewsletter')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'alert_blocknewsletter'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'Newsletter : %1$s','sprintf'=>$_smarty_tpl->tpl_vars['msg']->value,'js'=>1,'mod'=>"blocknewsletter"),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'alert_blocknewsletter'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?>
<script type="text/javascript">
	var placeholder = "<?php echo smartyTranslate(array('s'=>'your e-mail','mod'=>'blocknewsletter','js'=>1),$_smarty_tpl);?>
";
	
	$(document).ready(function() {
		$('#newsletter-input').on({
			focus: function() {
				if ($(this).val() == placeholder) {
					$(this).val('');
				}
			},
			blur: function() {
				if ($(this).val() == '') {
					$(this).val(placeholder);
				}
			}
		});
	});
	
</script><?php }} ?>
