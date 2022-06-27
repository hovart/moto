<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-tab-content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:488516204598976a85e9b33-76364036%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4437a4fdc0fb4e4eacdac7803083d4a57622c7ba' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-tab-content.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '488516204598976a85e9b33-76364036',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bDisplayReviews' => 0,
    'bUseRatings' => 0,
    'bUseComments' => 0,
    'aReviews' => 0,
    'sDisplayReviewMode' => 0,
    'iIdTab' => 0,
    'sModuleName' => 0,
    'aErrors' => 0,
    'sErrorInclude' => 0,
    'aReview' => 0,
    'sProductName' => 0,
    'iMaxRating' => 0,
    'sRatingClassName' => 0,
    'bDisplayButtons' => 0,
    'bCountBoxButton' => 0,
    'iFbButton' => 0,
    'sTwitterLang' => 0,
    'bDisplayReportAbuse' => 0,
    'iTotalPage' => 0,
    'iCurrentPage' => 0,
    'prev' => 0,
    'sBASE_URI' => 0,
    'nStart' => 0,
    'nEnd' => 0,
    'next' => 0,
    'sMODULE_URI' => 0,
    'aQueryParams' => 0,
    'sProductLink' => 0,
    'bAddReview' => 0,
    'bGetReviewPage' => 0,
    'bUseSocialNetworkJs' => 0,
    'aJSCallback' => 0,
    'aCallback' => 0,
    'iProductId' => 0,
    'iCustomerId' => 0,
    'sURI' => 0,
    'sSecureReviewKey' => 0,
    'rtg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a8778db0_91933128',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a8778db0_91933128')) {function content_598976a8778db0_91933128($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>
<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)) {?>
	<?php if (!empty($_smarty_tpl->tpl_vars['bUseRatings']->value)||!empty($_smarty_tpl->tpl_vars['bUseComments']->value)||!empty($_smarty_tpl->tpl_vars['aReviews']->value)) {?>
		<!-- GSR - Product Review Tab content -->
		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value)&&$_smarty_tpl->tpl_vars['sDisplayReviewMode']->value!='classic') {?>
		<div id="idTab<?php echo intval($_smarty_tpl->tpl_vars['iIdTab']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='bootstrap') {?>class="page-product-box tab-pane"<?php }?>>
		
		<?php } else { ?>
		<section class="page-product-box">
			<h3 class="page-product-heading"><i class="icon-star-empty"></i> <?php echo smartyTranslate(array('s'=>'Reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>
		<?php }?>
		<a name="anchorReview" id="anchorReview"></a>
		<div id="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="rte">
		<?php if (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php }?>
		<?php if (!empty($_smarty_tpl->tpl_vars['aReviews']->value)) {?>
			<?php  $_smarty_tpl->tpl_vars['aReview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aReview']->_loop = false;
 $_smarty_tpl->tpl_vars['iKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['review']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['aReview']->key => $_smarty_tpl->tpl_vars['aReview']->value) {
$_smarty_tpl->tpl_vars['aReview']->_loop = true;
 $_smarty_tpl->tpl_vars['iKey']->value = $_smarty_tpl->tpl_vars['aReview']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['review']['iteration']++;
?>
				<div class="review-line">
					<div itemprop="review" itemscope itemtype="http://schema.org/Review">
						<div class="review-line-name text-muted">
							<?php echo smartyTranslate(array('s'=>'By','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							<strong>
								<span itemprop="author">
								<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['firstname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

								<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['lastname'])) {?>
									<?php echo mb_convert_encoding(htmlspecialchars(mb_strtoupper($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['aReview']->value['lastname'],"1",''), 'UTF-8'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
.
								<?php }?>
								</span>
							</strong>

							<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['address'])) {?>
								(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['address'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
)
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['review']['dateAdd'])||!empty($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'])) {?>
								<?php echo smartyTranslate(array('s'=>'on','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
&nbsp;
								<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'])) {?>
									<meta itemprop="datePublished" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['date'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aReview']->value['dateAdd'], 'UTF-8');?>

								<?php } elseif (!empty($_smarty_tpl->tpl_vars['aReview']->value['review']['dateAdd'])&&!empty($_smarty_tpl->tpl_vars['aReview']->value['review']['status'])) {?>
									<meta itemprop="datePublished" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['date'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aReview']->value['review']['dateAdd'], 'UTF-8');?>

								<?php }?>
							<?php }?>
							<span class="text-size-07">(<span itemprop="itemReviewed"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sProductName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span>)</span> :

							<div class="review-line-rating">
								<div class="left text-size-07" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
									(<span itemprop="ratingValue"><?php echo intval($_smarty_tpl->tpl_vars['aReview']->value['note']);?>
</span>/<span itemprop="bestRating"><?php echo intval($_smarty_tpl->tpl_vars['iMaxRating']->value);?>
</span>)&nbsp;
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
" <?php if ($_smarty_tpl->tpl_vars['aReview']->value['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?>checked="checked"<?php }?>/><label class="product-tab<?php if ($_smarty_tpl->tpl_vars['aReview']->value['note']>=$_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']) {?> checked<?php }?>" for="rating<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
" title="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['note']['iteration']);?>
"></label><?php endfor; endif; ?></div>
								<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['replyDisplay'])&&!empty($_smarty_tpl->tpl_vars['aReview']->value['data']['iOldRating'])) {?><br /><span class="rvw-additional-txt">(<?php echo smartyTranslate(array('s'=>'old rating','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php echo intval($_smarty_tpl->tpl_vars['aReview']->value['data']['iOldRating']);?>
/<?php echo intval($_smarty_tpl->tpl_vars['iMaxRating']->value);?>
)&nbsp;</span><?php }?>
							</div>
						</div>

						<div class="review-line-comment">
							<span class="clr_0"></span>
							<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['review']['data'])) {?>
								<p itemprop="name"><strong><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['data']['sTitle'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</strong></p>
								<p itemprop="description"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aReview']->value['review']['data']['sComment'], 'UTF-8');?>
</p>

								<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayButtons']->value)) {?>
								<div class="inline <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
_social_buttons">
									
									<?php if (!empty($_smarty_tpl->tpl_vars['bCountBoxButton']->value)) {?>
									<div class="inline width-auto">
										
										<?php if (isset($_smarty_tpl->tpl_vars['iFbButton']->value)&&$_smarty_tpl->tpl_vars['iFbButton']->value==3) {?>
											<div class="inline zindex">
												<div class="fb-share-button" data-href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-layout="button_count" data-size="small"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['sTwitterLang']->value)) {?>data-lang="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sTwitterLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php }?>>Tweet</a>
										<?php } else { ?>
											<div class="inline zindex">
												<div class="fb-like valign-top" data-href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-show-faces="false" data-width="60" data-layout="button_count" data-share="<?php if (isset($_smarty_tpl->tpl_vars['iFbButton']->value)&&$_smarty_tpl->tpl_vars['iFbButton']->value==2) {?>true<?php } else { ?>false<?php }?>"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['sTwitterLang']->value)) {?>data-lang="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sTwitterLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php }?>>Tweet</a>
										<?php }?>
									</div>
									
									<?php } else { ?>
										
										<?php if (isset($_smarty_tpl->tpl_vars['iFbButton']->value)&&$_smarty_tpl->tpl_vars['iFbButton']->value==3) {?>
											<div class="inline zindex">
												<div class="fb-share-button" data-href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-layout="button" data-size="small"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share"  data-url="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['sTwitterLang']->value)) {?>data-lang="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sTwitterLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php }?>>Tweet</a>
										<?php } else { ?>
											<a class="valign-top padding0202 twitter-share-button" href="https://twitter.com/share"  data-url="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['sTwitterLang']->value)) {?>data-lang="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sTwitterLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php }?>>Tweet</a>
											<div class="absolute inline">
												<div class="padding0202 zindex">
													<div class="fb-like valign-top" data-href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReviewUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-show-faces="false" data-width="220" data-share="<?php if (isset($_smarty_tpl->tpl_vars['iFbButton']->value)&&$_smarty_tpl->tpl_vars['iFbButton']->value==2) {?>true<?php } else { ?>false<?php }?>"></div>
												</div>
											</div>
										<?php }?>
									<?php }?>
								</div>
								<?php }?>
								<?php if (empty($_smarty_tpl->tpl_vars['aReview']->value['review']['reportId'])&&!empty($_smarty_tpl->tpl_vars['aReview']->value['review'])&&!empty($_smarty_tpl->tpl_vars['bDisplayReportAbuse']->value)) {?>
								<span class="review-report <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sModuleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
_report_button">
									<a class="fancybox.ajax" id="reportReview<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['review']['iteration']);?>
" rel="nofollow" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['review']['sReportUrl'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'report a review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
"><i class="icon-warning-sign text-primary"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'Report abuse','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
								</span>
								<?php }?>
								<div class="clr_5"></div>
							<?php } else { ?>
							<?php echo smartyTranslate(array('s'=>'The customer has rated the product but has not posted a review, or the review is pending moderation','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							<div class="clr_15"></div>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['review']['data'])&&!empty($_smarty_tpl->tpl_vars['aReview']->value['replyDisplay'])&&!empty($_smarty_tpl->tpl_vars['aReview']->value['replyData']['sComment'])) {?>
							<div class="clr_10"></div>
							<blockquote class="blockquote">
								<p><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aReview']->value['replyData']['sComment'], 'UTF-8');?>
</p>
								<footer><?php echo smartyTranslate(array('s'=>'Shop owner reply','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php if (!empty($_smarty_tpl->tpl_vars['aReview']->value['replyDateAdd'])) {?><?php echo smartyTranslate(array('s'=>'on','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aReview']->value['replyDateAdd'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?></footer>
							</blockquote>
							<?php }?>
						</div>
					</div>
				</div>
			<?php } ?>

			
			<?php if ($_smarty_tpl->tpl_vars['iTotalPage']->value>1) {?>
			<div class="pagination">
				<ul class="pagination">
				<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value>1) {?>
					<?php $_smarty_tpl->tpl_vars['prev'] = new Smarty_variable($_smarty_tpl->tpl_vars['iCurrentPage']->value-1, null, 0);?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['iTotalPage']->value>10) {?>
					<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value>5) {?>
						<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value<=$_smarty_tpl->tpl_vars['iTotalPage']->value-5) {?>
							<?php $_smarty_tpl->tpl_vars['nStart'] = new Smarty_variable($_smarty_tpl->tpl_vars['iCurrentPage']->value-4, null, 0);?>
							<?php $_smarty_tpl->tpl_vars['nEnd'] = new Smarty_variable($_smarty_tpl->tpl_vars['iCurrentPage']->value+5, null, 0);?>
						<?php } else { ?>
							<?php $_smarty_tpl->tpl_vars['nStart'] = new Smarty_variable($_smarty_tpl->tpl_vars['iTotalPage']->value-9, null, 0);?>
							<?php $_smarty_tpl->tpl_vars['nEnd'] = new Smarty_variable($_smarty_tpl->tpl_vars['iTotalPage']->value+1, null, 0);?>
						<?php }?>
					<?php } else { ?>
						<?php $_smarty_tpl->tpl_vars['nStart'] = new Smarty_variable(1, null, 0);?>
						<?php $_smarty_tpl->tpl_vars['nEnd'] = new Smarty_variable(11, null, 0);?>
					<?php }?>
				<?php } else { ?>
					<?php $_smarty_tpl->tpl_vars['nStart'] = new Smarty_variable(1, null, 0);?>
					<?php $_smarty_tpl->tpl_vars['nEnd'] = new Smarty_variable($_smarty_tpl->tpl_vars['iTotalPage']->value+1, null, 0);?>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value>1) {?>
					<li id="previous"><a <?php if ($_smarty_tpl->tpl_vars['prev']->value==1) {?>href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBASE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
iPage=1"<?php } else { ?>href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBASE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
iPage=<?php echo intval($_smarty_tpl->tpl_vars['prev']->value);?>
"<?php }?> rel="nofollow">&laquo;&nbsp;<?php echo smartyTranslate(array('s'=>'Previous','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a></li>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value>10) {?>
					<li class="disabled"><a href="javascript:void(0);">…</a></li>
				<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['name'] = 'pagination';
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'] = (int) $_smarty_tpl->tpl_vars['nStart']->value;
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['nEnd']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'] = 1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['pagination']['total']);
?>
					<?php if ($_smarty_tpl->getVariable('smarty')->value['section']['pagination']['index']==$_smarty_tpl->tpl_vars['iCurrentPage']->value) {?>
						<li class="active"><span><?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['pagination']['index']);?>
</span></li>
					<?php } else { ?>
						<li><a <?php if ($_smarty_tpl->getVariable('smarty')->value['section']['pagination']['index']==1) {?>href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBASE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
iPage=1"<?php } else { ?>href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBASE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
iPage=<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['pagination']['index']);?>
"<?php }?> rel="nofollow"><?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['pagination']['index']);?>
</a></li>
					<?php }?>
				<?php endfor; endif; ?>
				<?php if ($_smarty_tpl->tpl_vars['iTotalPage']->value>10&&$_smarty_tpl->tpl_vars['iCurrentPage']->value<$_smarty_tpl->tpl_vars['iTotalPage']->value) {?>
					<li class="disabled"><a href="javascript:void(0);">…</a></li>
					
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value<$_smarty_tpl->tpl_vars['iTotalPage']->value) {?>
					<?php $_smarty_tpl->tpl_vars['next'] = new Smarty_variable($_smarty_tpl->tpl_vars['iCurrentPage']->value+1, null, 0);?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['iCurrentPage']->value<$_smarty_tpl->tpl_vars['iTotalPage']->value) {?>
					<li id="next"><a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBASE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
iPage=<?php echo intval($_smarty_tpl->tpl_vars['next']->value);?>
" rel="nofollow"><?php echo smartyTranslate(array('s'=>'Next','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
&nbsp;&raquo;</a></li>
				<?php }?>
				</ul>
			</div>
			<?php }?>
			

			
			<script type="text/javascript">
				// declare the FB callback to execute after clicking on the like button
				function bt_generateFbVoucherCode(response) {
					
					oGsr.ajax('<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sMODULE_URI']->value, 'UTF-8');?>
', 'sAction=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['popinFB']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sType=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['popinFB']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sReviewUrl=' + encodeURIComponent(response), null, null, true, false, false);
					
				}

				
				
				<?php  $_smarty_tpl->tpl_vars['aReview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aReview']->_loop = false;
 $_smarty_tpl->tpl_vars['iKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['review']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['aReview']->key => $_smarty_tpl->tpl_vars['aReview']->value) {
$_smarty_tpl->tpl_vars['aReview']->_loop = true;
 $_smarty_tpl->tpl_vars['iKey']->value = $_smarty_tpl->tpl_vars['aReview']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['review']['iteration']++;
?>
				<?php if (empty($_smarty_tpl->tpl_vars['aReview']->value['review']['reportId'])) {?>
				
					bt_aReviewReport.push({'selector' : "a#reportReview<?php echo intval($_smarty_tpl->getVariable('smarty')->value['foreach']['review']['iteration']);?>
", 'hideOnContentClick' : false, 'afterClose' : "<?php echo $_smarty_tpl->tpl_vars['sProductLink']->value;?>
", 'minWidth' : 500});
				
				<?php }?>
				<?php } ?>

				<?php if (!empty($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value)&&$_smarty_tpl->tpl_vars['sDisplayReviewMode']->value!='classic') {?>
				
					bt_oActivateReviewTab.run = true;
					bt_oActivateReviewTab.theme = '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
';
					bt_oActivateReviewTab.idTab = '<?php echo intval($_smarty_tpl->tpl_vars['iIdTab']->value);?>
';
					bt_oActivateReviewTab.liSelector = '<?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>.tabs<?php } else { ?>#more_info_tabs<?php }?>';
					bt_oActivateReviewTab.cntSelector = '<?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>#tab-content<?php } else { ?>#more_info_sheets<?php }?>';
				
				<?php }?>

				
				<?php if (!empty($_smarty_tpl->tpl_vars['bAddReview']->value)||!empty($_smarty_tpl->tpl_vars['bGetReviewPage']->value)) {?>
					bt_oScrollTo.execute = true;
					bt_oScrollTo.id = '#anchorReview';
					bt_oScrollTo.duration = 500;
				<?php } elseif (!empty($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value)&&$_smarty_tpl->tpl_vars['sDisplayReviewMode']->value!='classic') {?>
					bt_oDeactivateReviewTab.run = true;
					bt_oDeactivateReviewTab.duration = 3000;
					bt_oDeactivateReviewTab.theme = '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
';
					bt_oDeactivateReviewTab.idTab = '<?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>description<?php } else { ?>idTab1<?php }?>';
					bt_oDeactivateReviewTab.liSelector = '<?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>.tabs<?php } else { ?>#more_info_tabs<?php }?>';
					bt_oDeactivateReviewTab.cntSelector = '<?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>#tab-content<?php } else { ?>#more_info_sheets<?php }?>';
				<?php }?>

				
				<?php if (!empty($_smarty_tpl->tpl_vars['bUseSocialNetworkJs']->value)&&!empty($_smarty_tpl->tpl_vars['aJSCallback']->value)) {?>
				
				<?php  $_smarty_tpl->tpl_vars['aCallback'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aCallback']->_loop = false;
 $_smarty_tpl->tpl_vars['iKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aJSCallback']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aCallback']->key => $_smarty_tpl->tpl_vars['aCallback']->value) {
$_smarty_tpl->tpl_vars['aCallback']->_loop = true;
 $_smarty_tpl->tpl_vars['iKey']->value = $_smarty_tpl->tpl_vars['aCallback']->key;
?>
				
					bt_aFacebookCallback.push({'url' : '<?php echo $_smarty_tpl->tpl_vars['aCallback']->value['url'];?>
', 'function' : '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aCallback']->value['function'], 'UTF-8');?>
'});
				
				<?php } ?>
				<?php }?>
				
			</script>
			
			
			
			
			
				
			

			
			
				
				
				
			

			
				
				
				
					
					
					
						
						
						
					
					
					
				

				
				
				
					
					
						
							
							
							
							
							
							
							
						
						
							
							
							
							
							
							
							
						
					
				
				
					
					
						
							
							
						
						
							
							
						
					
				
				
				

				
				
					
				
					
					
						
							
							
								
									
									
									
									
									
									
									
								
								
									
									
									
									
									
									
									
								
							
						
						
							
							
								
									
									
								
								
									
									
								
							
						
					
					
					
				

				
				
					
					
					
					
					
					
					

					
						
							
								
									
										
										
									
								
							
						
					
					
				
				
			
			
			
		<?php } else { ?>
			<p class="align_center">
				<a class="fancybox.ajax" id="reviewTabForm" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sMODULE_URI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
?sAction=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviewForm']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sType=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviewForm']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&iPId=<?php echo intval($_smarty_tpl->tpl_vars['iProductId']->value);?>
&iCId=<?php echo intval($_smarty_tpl->tpl_vars['iCustomerId']->value);?>
&sURI=<?php echo urlencode($_smarty_tpl->tpl_vars['sURI']->value);?>
&btKey=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sSecureReviewKey']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php if (!empty($_smarty_tpl->tpl_vars['rtg']->value)) {?>&rtg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['rtg']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" rel="nofollow"><?php echo smartyTranslate(array('s'=>'Be the first to write your review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 !</a>
			</p>
			
			<script type="text/javascript">
				bt_aFancyReviewTabForm.selector = 'a#reviewTabForm';
				bt_aFancyReviewTabForm.hideOnContentClick = false;
				bt_aFancyReviewTabForm.beforeClose = '<?php echo $_smarty_tpl->tpl_vars['sProductLink']->value;?>
';
			</script>
			
			
			
				
					
					
						
						
					
				
			
			
		<?php }?>
		</div>
	<?php if (!empty($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value)&&$_smarty_tpl->tpl_vars['sDisplayReviewMode']->value!='classic') {?>
	</div>
	<?php } else { ?>
	</section>
	<?php }?>
	<!-- /GSR - Product Review Tab content -->
	<?php }?>
<?php }?><?php }} ?>
