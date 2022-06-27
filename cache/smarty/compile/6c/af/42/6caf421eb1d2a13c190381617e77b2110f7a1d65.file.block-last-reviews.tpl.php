<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/block-last-reviews.tpl" */ ?>
<?php /*%%SmartyHeaderCode:576158477598976a7b37bf7-72035317%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6caf421eb1d2a13c190381617e77b2110f7a1d65' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/block-last-reviews.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '576158477598976a7b37bf7-72035317',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bDisplayReviews' => 0,
    'aReviews' => 0,
    'bDisplayFirst' => 0,
    'sPosition' => 0,
    'aBadeOptions' => 0,
    'sReviewsControllerUrl' => 0,
    'aReview' => 0,
    'sRatingClassName' => 0,
    'iMaxRating' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a7bc8fc3_14185571',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a7bc8fc3_14185571')) {function content_598976a7bc8fc3_14185571($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>
<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)&&!empty($_smarty_tpl->tpl_vars['aReviews']->value)) {?>
	<!-- GSR - Block last reviews -->
	<div class="<?php if (empty($_smarty_tpl->tpl_vars['bDisplayFirst']->value)) {?>clr_20<?php } else { ?>clr_10<?php }?>"></div>
	<div class="block last-reviews-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPosition']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aBadeOptions']->value['width'])||!empty($_smarty_tpl->tpl_vars['aBadeOptions']->value['height'])) {?>style="<?php if (!empty($_smarty_tpl->tpl_vars['aBadeOptions']->value['width'])) {?>width: <?php echo intval($_smarty_tpl->tpl_vars['aBadeOptions']->value['width']);?>
% !important;<?php }?>"<?php }?>>
		<p class="title_block">
			<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sReviewsControllerUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'all Last reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
				<?php echo smartyTranslate(array('s'=>'Last reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

			</a>
		</p>
		<div class="block_content">
				<?php  $_smarty_tpl->tpl_vars['aReview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aReview']->_loop = false;
 $_smarty_tpl->tpl_vars['iKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aReview']->key => $_smarty_tpl->tpl_vars['aReview']->value) {
$_smarty_tpl->tpl_vars['aReview']->_loop = true;
 $_smarty_tpl->tpl_vars['iKey']->value = $_smarty_tpl->tpl_vars['aReview']->key;
?>
				<div class="review-line">
					
					<?php if ($_smarty_tpl->tpl_vars['sPosition']->value=='colLeft'||$_smarty_tpl->tpl_vars['sPosition']->value=='colRight') {?>
					<p class="review-name">
						<?php echo smartyTranslate(array('s'=>'By','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <strong><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['firstname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['lastname'])) {?><?php echo mb_convert_encoding(htmlspecialchars(mb_strtoupper($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['aReview']->value['lastname'],"1",''), 'UTF-8'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
.<?php }?></strong><?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['address'])) {?> (<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['address'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
)<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'])) {?>&nbsp;<?php echo smartyTranslate(array('s'=>'on','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'], 'UTF-8');?>
<?php }?> :
					</p>
					<span class="clr_5"></span>
					<p class="inline">
						<div class="rating-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sRatingClassName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['note'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['note']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['iMaxRating']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['name'] = 'note';
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total']);
?><input type="radio" value="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" <?php if ($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?>checked="checked"<?php }?>/><label class="product-tab<?php if ($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?> checked<?php }?>" for="rating<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" title="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
"></label><?php endfor; endif; ?></div>
						<div class="text-size-07">
							(<?php echo intval($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']);?>
/<?php echo intval($_smarty_tpl->tpl_vars['iMaxRating']->value);?>
)&nbsp;
						</div>
					</p>
					<?php } else { ?>
					
					<div class="review-line-name">
						<?php echo smartyTranslate(array('s'=>'By','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <strong><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['firstname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['lastname'])) {?><?php echo mb_convert_encoding(htmlspecialchars(mb_strtoupper($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['aReview']->value['lastname'],"1",''), 'UTF-8'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
.<?php }?></strong><?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['address'])) {?> (<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['address'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
)<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'])) {?>&nbsp;<span><?php echo smartyTranslate(array('s'=>'on','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span><?php }?> :
						<div class="review-line-rating">
							<div class="left text-size-07">
								(<?php echo intval($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']);?>
/<?php echo intval($_smarty_tpl->tpl_vars['iMaxRating']->value);?>
)&nbsp;
							</div>
							<div class="rating-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sRatingClassName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['note'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['note']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['iMaxRating']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['name'] = 'note';
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['note']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['note']['total']);
?><input type="radio" value="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" <?php if ($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?>checked="checked"<?php }?>/><label class="product-tab<?php if ($_smarty_tpl->tpl_vars['aReview']->value['rating']['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?> checked<?php }?>" for="rating<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" title="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
"></label><?php endfor; endif; ?></div>
						</div>
					</div>
					<?php }?>
					<div class="review-line-comment">
						<span class="clr_5"></span>
						<?php echo smartyTranslate(array('s'=>'Product rated','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 : <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['sProductLink'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['sProductName'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['sProductName'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <i class="icon icon-chevron-right"></i></a>
						<span class="clr_10"></span>
						<?php if (!empty($_smarty_tpl->tpl_vars['aBadeOptions']->value['truncate'])) {?>
						<?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['aReview']->value['data']['sComment'],$_smarty_tpl->tpl_vars['aBadeOptions']->value['truncate'],"..."), 'UTF-8');?>

						<?php } else { ?>
						<?php echo smarty_modifier_escape($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['aReview']->value['data']['sComment'],30,"..."), 'UTF-8');?>

						<?php }?>
					</div>
					<div class="clr_5"></div>
				</div>
			<?php } ?>

			<div class="clr_10"></div>

			<div class="align-right">
				<a class="btn btn-default button button-small" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sReviewsControllerUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'All last reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
					<span><?php echo smartyTranslate(array('s'=>'All reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i></span>
				</a>
			</div>
		</div>
	</div>
	<div class="clr_10"></div>
	<!-- /GSR - Block last reviews -->
<?php }?><?php }} ?>
