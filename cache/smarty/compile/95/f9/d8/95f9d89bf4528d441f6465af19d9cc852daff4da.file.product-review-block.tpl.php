<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-review-block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1014365767598976a83fb355-65053340%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '95f9d89bf4528d441f6465af19d9cc852daff4da' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-review-block.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1014365767598976a83fb355-65053340',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bDisplayReviews' => 0,
    'bUseSocialNetworkJs' => 0,
    'sFbLang' => 0,
    'sModuleName' => 0,
    'sBlockPosition' => 0,
    'iReviewAverage' => 0,
    'sReviewType' => 0,
    'bDisplayProductRichSnippets' => 0,
    'bProductBadge' => 0,
    'sItemReviewed' => 0,
    'sRatingClassName' => 0,
    'iMaxRatingBlock' => 0,
    'bHalfStar' => 0,
    'fReviewAverage' => 0,
    'iAverageMaxRating' => 0,
    'bEnableCustLang' => 0,
    'iCountRatings' => 0,
    'iCountReviews' => 0,
    'aDistribution' => 0,
    'iDefaultMaxRating' => 0,
    'iNote' => 0,
    'iCount' => 0,
    'sReviewTabId' => 0,
    'bUseRatings' => 0,
    'bUseComments' => 0,
    'sMODULE_URI' => 0,
    'aQueryParams' => 0,
    'iProductId' => 0,
    'iCustomerId' => 0,
    'sURI' => 0,
    'sSecureReviewKey' => 0,
    'rtg' => 0,
    'sProductLink' => 0,
    'bOpenForm' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a84d7ab9_46792020',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a84d7ab9_46792020')) {function content_598976a84d7ab9_46792020($_smarty_tpl) {?><?php if (!is_callable('smarty_function_math')) include '/home/prestashop-pp/www/tools/smarty/plugins/function.math.php';
?>

<!-- GSR - Product review block -->
<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)) {?>
	
	<?php if (!empty($_smarty_tpl->tpl_vars['bUseSocialNetworkJs']->value)) {?>
	
	<script type="text/javascript">
		bt_oUseSocialButton.sFbLang = '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sFbLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
';
	</script>
	
	<?php }?>
	
	
	
	
	
	
		
			
			
				
				
			
			
				
				
				
				
				
			
		
	
	
	

	
	
	
		
			
				
				
					
					
					
					
				
			
		
	
	
	
	
	
	<div id="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBlockPosition']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 average-heading">
		<div class="clear"></div>
		<p class="average-heading-title padding-left-15"><i class="icon-star-empty"></i> <strong><?php echo smartyTranslate(array('s'=>'Customer ratings and reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong></p>
		<?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)&&$_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?>
		<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayProductRichSnippets']->value)&&!empty($_smarty_tpl->tpl_vars['bProductBadge']->value)) {?>
		<div itemscope itemtype="http://schema.org/Product">
			<meta itemprop="name" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sItemReviewed']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
		<?php }?>
		<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<?php } else { ?>
			<div>
				<?php }?>
				<div class="display-review padding-left-right">
					<div class="pull-left">
						<?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)) {?>
							<div class="rating-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sRatingClassName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
								<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['note'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['note']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['iMaxRatingBlock']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
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
?>
									<input type="radio" value="<?php if (!empty($_smarty_tpl->tpl_vars['bHalfStar']->value)) {?><?php echo smarty_function_math(array('equation'=>"x/2",'x'=>$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']),$_smarty_tpl);?>
<?php } else { ?><?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
<?php }?>" <?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)&&$_smarty_tpl->tpl_vars['iReviewAverage']->value>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?>checked="checked"<?php }?>/><label class="<?php if (!empty($_smarty_tpl->tpl_vars['bHalfStar']->value)&&$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']%2) {?>half<?php }?> product-block<?php if (!empty($_smarty_tpl->tpl_vars['bHalfStar']->value)) {?>-half<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)&&$_smarty_tpl->tpl_vars['iReviewAverage']->value>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?> checked<?php }?>" for="rating<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" title="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
"></label>
								<?php endfor; endif; ?>
							</div>
							<div class="pull-right">
								<span class="left text-size-07 padding-left5">(</span><?php if ($_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?><meta itemprop="worstRating" content="1" /><?php }?><span class="left text-size-07" <?php if ($_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?>itemprop="ratingValue"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fReviewAverage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span><span class="left text-size-07">/<?php if ($_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?><span itemprop="bestRating" class="text-size-1"><?php echo intval($_smarty_tpl->tpl_vars['iAverageMaxRating']->value);?>
</span><?php } else { ?><?php echo intval($_smarty_tpl->tpl_vars['iAverageMaxRating']->value);?>
<?php }?>)</span>
							</div>
						<?php } else { ?>
							<span class="left default-text"><?php echo smartyTranslate(array('s'=>'Nobody has posted a review yet','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span><?php if (!empty($_smarty_tpl->tpl_vars['bEnableCustLang']->value)) {?><div class="clr_0"></div><span class="left default-text"><?php echo smartyTranslate(array('s'=>'in this language','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span><?php }?>
						<?php }?>
					</div>

					<?php if (!empty($_smarty_tpl->tpl_vars['iCountRatings']->value)||!empty($_smarty_tpl->tpl_vars['iCountReviews']->value)) {?>
						<div class="review-count-text left"><?php if (!empty($_smarty_tpl->tpl_vars['iCountRatings']->value)) {?><span class="padding-left5" <?php if ($_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?>itemprop="ratingCount"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iCountRatings']->value);?>
</span> <?php echo smartyTranslate(array('s'=>'rating(s)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<?php if (!empty($_smarty_tpl->tpl_vars['iCountReviews']->value)) {?> - <span <?php if ($_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate') {?>itemprop="reviewCount"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iCountReviews']->value);?>
</span> <?php echo smartyTranslate(array('s'=>'review(s)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<?php }?><?php }?></div>
					<?php }?>

					<?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)) {?>
						<div class="clr_5"></div>
						<a class="distrib-text padding-left-15" href="javascript:void(0);" onclick="bt_toggle('.display-distribution');/*$('.display-distribution').toggle();*/"><?php echo smartyTranslate(array('s'=>'View distribution','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					<?php }?>
				</div>

				<?php if (!empty($_smarty_tpl->tpl_vars['aDistribution']->value)) {?>
					<div class="display-distribution" style="display: none;">
						<?php  $_smarty_tpl->tpl_vars['iCount'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iCount']->_loop = false;
 $_smarty_tpl->tpl_vars['iNote'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aDistribution']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['distrib']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['iCount']->key => $_smarty_tpl->tpl_vars['iCount']->value) {
$_smarty_tpl->tpl_vars['iCount']->_loop = true;
 $_smarty_tpl->tpl_vars['iNote']->value = $_smarty_tpl->tpl_vars['iCount']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['distrib']['iteration']++;
?>
							<div class="display-distribution-line rating-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sRatingClassName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
Distribution<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['distrib']['iteration']);?>
"><?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['note'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['note']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['note']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['iDefaultMaxRating']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
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
" <?php if ($_smarty_tpl->tpl_vars['iNote']->value>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?>checked="checked"<?php }?>/><label class="distrib-front<?php if ($_smarty_tpl->tpl_vars['iNote']->value>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?> checked<?php }?>" for="rating<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" title="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
"></label><?php endfor; endif; ?>&nbsp;<strong><?php echo intval($_smarty_tpl->tpl_vars['iCount']->value);?>
</strong></div>
						<?php } ?>
						<div class="clr_5"></div>
					</div>
				<?php }?>

				<div class="clr_5"></div>

				<div <?php if ($_smarty_tpl->tpl_vars['sBlockPosition']->value=='productAction') {?>class="text-center"<?php }?>>
					<?php if (!empty($_smarty_tpl->tpl_vars['iCountReviews']->value)) {?>
						<a class="btn btn-primary" href="javascript:void(0);" onclick="<?php if (!empty($_smarty_tpl->tpl_vars['sReviewTabId']->value)) {?>bt_triggerClick('<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sReviewTabId']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
');/*$('<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sReviewTabId']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
').trigger('click');*/<?php }?>bt_scrollTo('#anchorReview', 1200);/*$.scrollTo('#anchorReview', 1200);*/"><i class="icon-star-empty"></i> <?php echo smartyTranslate(array('s'=>'Read reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					<?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseRatings']->value)||!empty($_smarty_tpl->tpl_vars['bUseComments']->value)) {?>
						<a class="btn btn-default fancybox.ajax" id="bt_btn-review-form" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sMODULE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
?sAction=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviewForm']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sType=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviewForm']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&iPId=<?php echo intval($_smarty_tpl->tpl_vars['iProductId']->value);?>
&iCId=<?php echo intval($_smarty_tpl->tpl_vars['iCustomerId']->value);?>
&sURI=<?php echo urlencode($_smarty_tpl->tpl_vars['sURI']->value);?>
&btKey=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sSecureReviewKey']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php if (!empty($_smarty_tpl->tpl_vars['rtg']->value)) {?>&rtg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['rtg']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" rel="nofollow"><i class="icon-pencil"></i> <?php echo smartyTranslate(array('s'=>'Rate it','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					<?php }?>
				</div>
				<div class="clr_0"></div>
			</div>
		<?php if (!empty($_smarty_tpl->tpl_vars['iReviewAverage']->value)&&$_smarty_tpl->tpl_vars['sReviewType']->value=='aggregate'&&!empty($_smarty_tpl->tpl_vars['bDisplayProductRichSnippets']->value)&&!empty($_smarty_tpl->tpl_vars['bProductBadge']->value)) {?>
		</div>
		<?php }?>
	</div>
	
	<script type="text/javascript">
		bt_aFancyReviewForm.selector = 'a#bt_btn-review-form';
		bt_aFancyReviewForm.hideOnContentClick = false;
		bt_aFancyReviewForm.beforeClose = '<?php echo $_smarty_tpl->tpl_vars['sProductLink']->value;?>
';
		bt_aFancyReviewForm.click = <?php if (!empty($_smarty_tpl->tpl_vars['bOpenForm']->value)&&(!empty($_smarty_tpl->tpl_vars['bUseRatings']->value)||!empty($_smarty_tpl->tpl_vars['bUseComments']->value))) {?>true<?php } else { ?>false<?php }?>;
	</script>
	
	
	
		
			
			
				
				
			
			
			
			
			
			
			
		
	
	
	<?php }?>
	<!-- /GSR - Product review block -->
<?php }} ?>
