<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:29:59
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/reviews-settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69488218598976876772e6-08782673%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e5b5e02e264d61c88da2717c89aa53a1d68ba38b' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/reviews-settings.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69488218598976876772e6-08782673',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sURI' => 0,
    'sCtrlParamName' => 0,
    'sDisplay' => 0,
    'aQueryParams' => 0,
    'sAdmniTabUrl' => 0,
    'bUpdate' => 0,
    'sConfirmInclude' => 0,
    'aErrors' => 0,
    'sErrorInclude' => 0,
    'bDisplayReviews' => 0,
    'bEnableRatings' => 0,
    'bEnableComments' => 0,
    'bForceComments' => 0,
    'bAdminApproval' => 0,
    'aAuthorize' => 0,
    'sAuthVal' => 0,
    'sAuthorizeReview' => 0,
    'sAuthTitle' => 0,
    'bEnableCustLang' => 0,
    'aEltPerPage' => 0,
    'nb' => 0,
    'iNbModerateReviews' => 0,
    'iReviewsListPerPage' => 0,
    'bDisplayReportButton' => 0,
    'bDisplayAddress' => 0,
    'bDisplayPhoto' => 0,
    'aImageSize' => 0,
    'sImageSize' => 0,
    'sReviewListProdImg' => 0,
    'aReviewsMode' => 0,
    'sMode' => 0,
    'sDisplayReviewMode' => 0,
    'sTitle' => 0,
    'sCurrentLang' => 0,
    'aReviewHooks' => 0,
    'aHook' => 0,
    'sHook' => 0,
    'sReviewProdImg' => 0,
    'aImages' => 0,
    'aImage' => 0,
    'sPicto' => 0,
    'bUseFontAwesome' => 0,
    'iReviewsPerPage' => 0,
    'bShareVoucher' => 0,
    'bEnableSocialButton' => 0,
    'bCountBoxButton' => 0,
    'iFbButtonType' => 0,
    'sSliderProdImg' => 0,
    'aSliderOpts' => 0,
    'iWidth' => 0,
    'iSliderWidth' => 0,
    'key' => 0,
    'iSliderPause' => 0,
    'iPause' => 0,
    'iSliderSpeed' => 0,
    'iSpeed' => 0,
    'bDisplayLastRvwBlock' => 0,
    'aNbLastReviews' => 0,
    'iNbLastReviews' => 0,
    'aLastBlockPages' => 0,
    'aPage' => 0,
    'sPageVal' => 0,
    'aLastBlockPos' => 0,
    'aPosTitle' => 0,
    'bLastRvwBlockFirst' => 0,
    'bDisplayStarsInList' => 0,
    'bPS17' => 0,
    'bPS1710' => 0,
    'bDisplayEmptyRating' => 0,
    'bDisplayBeFirstMessage' => 0,
    'aLangs' => 0,
    'aLang' => 0,
    'iCurrentLang' => 0,
    'aBeFirst' => 0,
    'idLang' => 0,
    'sLangTitle' => 0,
    'iStarDisplayMode' => 0,
    'bUseSnippetsProdList' => 0,
    'bHasSnippetsProdList' => 0,
    'aStarSizes' => 0,
    'sKey' => 0,
    'iSelectStarSize' => 0,
    'iStarSize' => 0,
    'aTextSizes' => 0,
    'iTextSize' => 0,
    'iSelectTextSize' => 0,
    'aStarsPaddingLeft' => 0,
    'iPaddingLet' => 0,
    'iStarPaddingLeft' => 0,
    'sIncludingCode' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_59897687b63c05_77699018',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59897687b63c05_77699018')) {function content_59897687b63c05_77699018($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>


<script type="text/javascript">
	var oReviewsCallBack = [{
		'name' : 'updateReminders',
		'url' : '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sURI']->value, 'UTF-8');?>
',
		'params' : '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCtrlParamName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
=admin&sAction=display&sType=emailReviews&sDisplay=email',
		'toShow' : 'bt_settings-email',
		'toHide' : 'bt_settings-email',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'updateReminders',
		'url' : '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sURI']->value, 'UTF-8');?>
',
		'params' : '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCtrlParamName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
=admin&sAction=display&sType=emailReviews&sDisplay=litigation',
		'toShow' : 'bt_settings-email-litigation',
		'toHide' : 'bt_settings-email-litigation',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'updateReminders',
		'url' : '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sURI']->value, 'UTF-8');?>
',
		'params' : '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCtrlParamName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
=admin&sAction=display&sType=emailReviews&sDisplay=reminder',
		'toShow' : 'bt_settings-email-reminder',
		'toHide' : 'bt_settings-email-reminder',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	}];
</script>


<div class="bootstrap">
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" method="post" id="bt_form-reviews-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="bt_form-reviews-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" onsubmit="oGsr.form('bt_form-reviews-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', null, 'bt_settings-review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'bt_settings-review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', false, false, oReviewsCallBack, 'review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'review');return false;">
		<input type="hidden" name="sAction" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviews']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
		<input type="hidden" name="sType" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['reviews']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
		<input type="hidden" name="sDisplay" id="sReviewsDisplay" value="<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?>global<?php }?>" />

		<span class="pull-right">
			<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sAdmniTabUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank" class="btn btn-info btn-lg" role="button"><?php echo smartyTranslate(array('s'=>'Moderate reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>&nbsp;<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sAdmniTabUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
#2" target="_blank" class="btn btn-info btn-lg" role="button"><?php echo smartyTranslate(array('s'=>'Add a review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
		</span>

		<div class="clr_10"></div>

		
		<?php if (empty($_smarty_tpl->tpl_vars['sDisplay']->value)||(!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='global')) {?>
			<h3><?php echo smartyTranslate(array('s'=>'Global Settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
						<strong><?php echo smartyTranslate(array('s'=>'Activate ratings and reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-reviews" id="bt_display-reviews_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_display-review-rating', null, null, true, true);"  />
						<label for="bt_display-reviews_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_display-reviews" id="bt_display-reviews_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_display-review-rating', null, null, true, false);" />
						<label for="bt_display-reviews_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_display-review-rating" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)) {?>block<?php } else { ?>none<?php }?>;">
				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Enable Ratings input','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-ratings" id="bt_enable-ratings_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableRatings']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-enable-comments', null, null, true, true);"  />
							<label for="bt_enable-ratings_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_enable-ratings" id="bt_enable-ratings_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableRatings']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-enable-comments', null, null, true, false);" />
							<label for="bt_enable-ratings_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div id="bt_div-enable-comments" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableRatings']->value)) {?>block<?php } else { ?>none<?php }?>;">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Enable Comments input','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_enable-comments" id="bt_enable-comments_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableComments']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-force-comments', null, null, true, true);" />
								<label for="bt_enable-comments_on" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<input type="radio" name="bt_enable-comments" id="bt_enable-comments_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableComments']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-force-comments', null, null, true, false);" />
								<label for="bt_enable-comments_off" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>

					<div id="bt_div-force-comments" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableComments']->value)) {?>block<?php } else { ?>none<?php }?>;">
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Force to write a comment','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_force-comments" id="bt_force-comments_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bForceComments']->value)) {?>checked="checked"<?php }?>  />
								<label for="bt_force-comments_on" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<input type="radio" name="bt_force-comments" id="bt_force-comments_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bForceComments']->value)) {?>checked="checked"<?php }?> />
								<label for="bt_force-comments_off" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }?>


		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='review') {?>
			<h3><?php echo smartyTranslate(array('s'=>'Handling Review Settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Require Admin Approval','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_admin" id="bt_admin_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bAdminApproval']->value)) {?>checked="checked"<?php }?>  />
						<label for="bt_admin_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_admin" id="bt_admin_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bAdminApproval']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_admin_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Who can review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<select name="bt_authorize" id="bt_authorize">
						<?php  $_smarty_tpl->tpl_vars['sAuthTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sAuthTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['sAuthVal'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aAuthorize']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sAuthTitle']->key => $_smarty_tpl->tpl_vars['sAuthTitle']->value) {
$_smarty_tpl->tpl_vars['sAuthTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['sAuthVal']->value = $_smarty_tpl->tpl_vars['sAuthTitle']->key;
?>
							<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sAuthVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['sAuthorizeReview']->value)&&$_smarty_tpl->tpl_vars['sAuthorizeReview']->value==$_smarty_tpl->tpl_vars['sAuthVal']->value) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sAuthTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If you activate this option, language filtering will be applied. For example, say one your products has 2 reviews in English and 1 in French. If this is activated, then the English version of your website will say it has 2 reviews, and the French version will say it has 1 review. However, if you do not activate it, then both languages will say it has 3 reviews. You should set this once and for all on initial setup of the module and avoid changing the setting after that, so as not to confuse Google with the number of ratings changing abruptly.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
"><strong><?php echo smartyTranslate(array('s'=>'Always count and display reviews in the current language ONLY','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong></span> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_enable-cust-lang" id="bt_enable-cust-lang_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableCustLang']->value)) {?>checked="checked"<?php }?>  />
						<label for="bt_enable-cust-lang_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_enable-cust-lang" id="bt_enable-cust-lang_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableCustLang']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_enable-cust-lang_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If you activate this option, language filtering will be applied. For example, say one your products has 2 reviews in English and 1 in French. If this is activated, then the English version of your website will say it has 2 reviews, and the French version will say it has 1 review. However, if you do not activate it, then both languages will say it has 3 reviews. You should set this once and for all on initial setup of the module and avoid changing the setting after that, so as not to confuse Google with the number of ratings changing abruptly.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<strong><?php echo smartyTranslate(array('s'=>'Number of reviews per page for moderation','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews-moderation" id="bt_nb-reviews-moderation">
						<?php  $_smarty_tpl->tpl_vars['nb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nb']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEltPerPage']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nb']->key => $_smarty_tpl->tpl_vars['nb']->value) {
$_smarty_tpl->tpl_vars['nb']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nb']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['nb']->value==$_smarty_tpl->tpl_vars['iNbModerateReviews']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Number of reviews per reviews list page','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews-list-page" id="bt_nb-reviews-list-page">
						<?php  $_smarty_tpl->tpl_vars['nb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nb']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEltPerPage']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nb']->key => $_smarty_tpl->tpl_vars['nb']->value) {
$_smarty_tpl->tpl_vars['nb']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nb']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['nb']->value==$_smarty_tpl->tpl_vars['iReviewsListPerPage']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Display the report abuse button','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-report-abuse" id="bt_display-report-abuse_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReportButton']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_display-report-abuse_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_display-report-abuse" id="bt_display-report-abuse_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayReportButton']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_display-report-abuse_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Display the customer address','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-address" id="bt_display-address_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayAddress']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_display-address_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_display-address" id="bt_display-address_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayAddress']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_display-address_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Display the product\'s image in the reviews list','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-product-photo" id="bt_display-product-photo_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayPhoto']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-display-product-photo', null, null, true, true);" />
						<label for="bt_display-product-photo_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_display-product-photo" id="bt_display-product-photo_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayPhoto']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-display-product-photo', null, null, true, false);" />
						<label for="bt_display-product-photo_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div id="bt_div-display-product-photo" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayPhoto']->value)) {?>block<?php } else { ?>none<?php }?>;">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Select product image size for reviews list','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<select name="bt_review-list-prod-img" id="bt_review-list-prod-img">
							<?php  $_smarty_tpl->tpl_vars['sImageSize'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sImageSize']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImageSize']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sImageSize']->key => $_smarty_tpl->tpl_vars['sImageSize']->value) {
$_smarty_tpl->tpl_vars['sImageSize']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['sImageSize']->key;
?>
								<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sImageSize']->value==$_smarty_tpl->tpl_vars['sReviewListProdImg']->value) {?> selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		<?php }?>

		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='product') {?>
			<h3><?php echo smartyTranslate(array('s'=>'Product page Settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections. But some custom themes have added back tabs on the product page. Please select the correct option below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Your theme layout','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_reviews-display-mode" id="bt_reviews-display-mode" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<?php  $_smarty_tpl->tpl_vars['sTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['sMode'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviewsMode']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sTitle']->key => $_smarty_tpl->tpl_vars['sTitle']->value) {
$_smarty_tpl->tpl_vars['sTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['sMode']->value = $_smarty_tpl->tpl_vars['sTitle']->key;
?>
							<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sMode']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value==$_smarty_tpl->tpl_vars['sMode']->value) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections. But some custom themes have added back tabs on the product page. Please select the correct option below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="form-group" id="bt_display-theme-warning" style="display: <?php if ($_smarty_tpl->tpl_vars['sDisplayReviewMode']->value=='tabs17') {?>block<?php } else { ?>none<?php }?>;">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="alert alert-warning">
						<?php echo smartyTranslate(array('s'=>'On a standard PrestaShop 1.7 theme, the product page has tabs for the various sections close to the description block but there isn\'t any hook to display the module with. You may want to use this tabs section to display the reviews list, so you would follow our FAQ on how to display the module there:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <a class="badge badge-info" href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=151&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><i class="icon icon-link"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'FAQ: How do I display the reviews list in the description block tabs?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
						<div class="clr_10"></div>
						<a href="#" data-toggle="modal" data-target="#modal_review_prod_tabs_preview"><span class="icon-eye-open">&nbsp;</span><?php echo smartyTranslate(array('s'=>'Click here to show a preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="modal_review_prod_tabs_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span></button>
							<h4 class="modal-title" id="myModalLabel"><?php echo smartyTranslate(array('s'=>'Preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
						</div>
						<div class="modal-body">
							<div class="center"><img src="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_URL_IMG'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
admin/screenshot-review-product-tabs.jpg" width="700"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-dismiss="modal"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button>
						</div>
					</div>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'A small block with the average rating, the number of reviews, and a link to post a new rating / review will be displayed on the product page. This determines where this block will be displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Hook to display','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_hooks" id="bt_hooks" class="col-xs-4 col-sm-4 col-md-4 col-lg-8">
						<?php  $_smarty_tpl->tpl_vars['aHook'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aHook']->_loop = false;
 $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviewHooks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aHook']->key => $_smarty_tpl->tpl_vars['aHook']->value) {
$_smarty_tpl->tpl_vars['aHook']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->value = $_smarty_tpl->tpl_vars['aHook']->key;
?>
							<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aHook']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sHook']->value==$_smarty_tpl->tpl_vars['aHook']->value['name']) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aHook']->value['title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'A small block with the average rating, the number of reviews, and a link to post a new rating / review will be displayed on the product page. This determines where this block will be displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This image size is used in review form when a customer is going to post a review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
						<strong><?php echo smartyTranslate(array('s'=>'Select image size for review form','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_review-prod-img" id="bt_review-prod-img"  class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['sImageSize'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sImageSize']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImageSize']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sImageSize']->key => $_smarty_tpl->tpl_vars['sImageSize']->value) {
$_smarty_tpl->tpl_vars['sImageSize']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['sImageSize']->key;
?>
							<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sImageSize']->value==$_smarty_tpl->tpl_vars['sReviewProdImg']->value) {?> selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This image size is used in review form when a customer is going to post a review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>

					<!-- Button trigger modal -->
					<a href="#" data-toggle="modal" data-target="#modal_preview_form"><span class="icon-eye-open">&nbsp;</span><?php echo smartyTranslate(array('s'=>'Click here to show a preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					<!-- Modal -->
					<div class="modal fade" id="modal_preview_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content modal-lg">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span></button>
									<h4 class="modal-title" id="myModalLabel"><?php echo smartyTranslate(array('s'=>'Preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
								</div>
								<div class="modal-body">
									<div class="center"><img src="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_URL_IMG'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
admin/screenshot-review-form.jpg" width="700" height="870"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			
			<?php if (!empty($_smarty_tpl->tpl_vars['aImages']->value)) {?>
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Choose your style for the star icons. It is a "sprite" image (3 images in one). The first one is used when no rating has been made, the second one when the user hovers with his mouse, and the third one when the rating has been made. You can create new styles if you want. Simply go to the img/admin/picto folder inside the gsnippetsreviews module folder. Duplicate any existing folder, rename it to something different (no spaces or accents, only letters and dashes "-"), and modify the image to your taste, but make sure it is still called "picto.png" and keep the same image size and space used by each star / element.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Pictogram to choose for rating','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<select name="bt_picto" id="bt_picto" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<?php  $_smarty_tpl->tpl_vars['aImage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aImage']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['aImage']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['aImage']->key => $_smarty_tpl->tpl_vars['aImage']->value) {
$_smarty_tpl->tpl_vars['aImage']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['aImage']->key;
 $_smarty_tpl->tpl_vars['aImage']->index++;
 $_smarty_tpl->tpl_vars['aImage']->first = $_smarty_tpl->tpl_vars['aImage']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['picto']['first'] = $_smarty_tpl->tpl_vars['aImage']->first;
?>
								<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpath'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['sPicto']->value)&&$_smarty_tpl->tpl_vars['sPicto']->value==$_smarty_tpl->tpl_vars['aImage']->value['subpath']) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpath'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
							<?php } ?>
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Choose your style for the star icons. It is a "sprite" image (3 images in one). The first one is used when no rating has been made, the second one when the user hovers with his mouse, and the third one when the rating has been made. You can create new styles if you want. Simply go to the img/admin/picto folder inside the gsnippetsreviews module folder. Duplicate any existing folder, rename it to something different (no spaces or accents, only letters and dashes "-"), and modify the image to your taste, but make sure it is still called "picto.png" and keep the same image size and space used by each star / element.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
						<?php  $_smarty_tpl->tpl_vars['aImage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aImage']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['aImage']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['aImage']->key => $_smarty_tpl->tpl_vars['aImage']->value) {
$_smarty_tpl->tpl_vars['aImage']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['aImage']->key;
 $_smarty_tpl->tpl_vars['aImage']->index++;
 $_smarty_tpl->tpl_vars['aImage']->first = $_smarty_tpl->tpl_vars['aImage']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['picto']['first'] = $_smarty_tpl->tpl_vars['aImage']->first;
?>
							<span id="bt_picto-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpath'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" style="float: left; margin-left: 15px; margin-bottom: 10px; display: <?php if (!empty($_smarty_tpl->tpl_vars['sPicto']->value)) {?><?php if ($_smarty_tpl->tpl_vars['sPicto']->value==$_smarty_tpl->tpl_vars['aImage']->value['subpath']) {?>inline<?php } else { ?>none<?php }?><?php } elseif ($_smarty_tpl->getVariable('smarty')->value['foreach']['picto']['first']==true) {?>inline<?php } else { ?>none<?php }?>;">
								<img src="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_URL_IMG'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
picto/<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpathname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpath'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aImage']->value['subpath'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
							</span>
						<?php } ?>
						<!-- Button trigger modal -->
						<a href="#" data-toggle="modal" data-target="#modal_product_preview"><span class="icon-eye-open">&nbsp;</span><?php echo smartyTranslate(array('s'=>'Click here to show a preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
						<!-- Modal -->
						<div class="modal fade" id="modal_product_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content modal-lg">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span></button>
										<h4 class="modal-title" id="myModalLabel"><?php echo smartyTranslate(array('s'=>'Preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
									</div>
									<div class="modal-body">
										<div class="center"><img src="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_URL_IMG'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
admin/screenshot-product-review.jpg" width="700" height="591"></div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-info" data-dismiss="modal"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'It\'s possible you couldn\'t see the review stars correctly and you see them as square because we display them in CSS, and maybe your theme doesn\'t include the CSS file of the default theme to get the "FontAwesome" review stars rendering. In that case, you should activate this option to allow the module to include our own "FontAwesome" css file to display the stars correctly','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'You don\'t see the review stars correctly, I include the module CSS stars file?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_use-fontawesome" id="bt_use-fontawesome_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bUseFontAwesome']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_use-fontawesome_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_use-fontawesome" id="bt_use-fontawesome_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bUseFontAwesome']->value)) {?>checked="checked"<?php }?> />
						<label for="bt_use-fontawesome_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'It\'s possible you couldn\'t see the review stars correctly and you see them as square because we display them in CSS, and maybe your theme doesn\'t include the CSS file of the default theme to get the "FontAwesome" review stars rendering. In that case, you should activate this option to allow the module to include our own "FontAwesome" css file to display the stars correctly','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
					<a class="badge badge-info" href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=153&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><i class="icon icon-link"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'How do I solve my review stars rendering issue?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Number of reviews per page','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews" id="bt_nb-reviews">
						<?php  $_smarty_tpl->tpl_vars['nb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nb']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEltPerPage']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nb']->key => $_smarty_tpl->tpl_vars['nb']->value) {
$_smarty_tpl->tpl_vars['nb']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nb']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['nb']->value==$_smarty_tpl->tpl_vars['iReviewsPerPage']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<h4><?php echo smartyTranslate(array('s'=>'Social buttons','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
			<div class="clr_hr"></div>
			<div class="clr_10"></div>

			<?php if (!empty($_smarty_tpl->tpl_vars['bShareVoucher']->value)) {?>
				<div class="clr_10"></div>
				<div class="alert alert-warning">
					<?php echo smartyTranslate(array('s'=>'Because you have activated the "offer a voucher for sharing a review" feature in the "Facebook integration" tab, you should definitely also enable the "display share buttons" option below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

				</div>
			<?php }?>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="alert alert-info">
						<?php echo smartyTranslate(array('s'=>'If you enable this "display share buttons" option, please be aware that you can also take it further by rewarding your customers for sharing their reviews. To do so, simply go to the "Facebook integration" tab','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you enable this option, each product review can be shared by your customers on their Facebook and / or Twitter account(s)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Display share buttons','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" id ="bt_social-button-div">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_enable-social-button" id="bt_enable-social-button_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableSocialButton']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-social-button', null, null, true, true);"  />
						<label for="bt_enable-social-button_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_enable-social-button" id="bt_enable-social-button_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableSocialButton']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-social-button', null, null, true, false);" />
						<label for="bt_enable-social-button_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you enable this option, each product review can be shared by your customers on their Facebook and / or Twitter account(s)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_div-social-button" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableSocialButton']->value)) {?>block<?php } else { ?>none<?php }?>;">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Display count box','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_count-box-button" id="bt_count-box-button_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bCountBoxButton']->value)) {?>checked="checked"<?php }?>  />
							<label for="bt_count-box-button_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_count-box-button" id="bt_count-box-button_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bCountBoxButton']->value)) {?>checked="checked"<?php }?> />
							<label for="bt_count-box-button_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Which FB button kind to use?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<select name="bt_fb-button-type" id="bt_fb-button-type">
							<option value="1" <?php if ($_smarty_tpl->tpl_vars['iFbButtonType']->value==1) {?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Display the like button','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
							<option value="2" <?php if ($_smarty_tpl->tpl_vars['iFbButtonType']->value==2) {?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Display the like button with the share button','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
							<option value="3" <?php if ($_smarty_tpl->tpl_vars['iFbButtonType']->value==3) {?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Display the share button alone','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
						</select>
					</div>
				</div>

				<div id="bt_div-fb-warning-msg" style="display: <?php if ($_smarty_tpl->tpl_vars['iFbButtonType']->value!=1) {?>inline<?php } else { ?>none<?php }?>;">
					<div class="clr_10"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="alert alert-warning">
								<?php echo smartyTranslate(array('s'=>'Be careful, only the like button can allow your customers to get an incentive if you\'ve activated the option in the "Facebook integration" tab. The click on the share button doesn\'t allow to get a click event on and detect if the user has posted or not something on his own FB timeline','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }?>

		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='account') {?>
			<h3><?php echo smartyTranslate(array('s'=>'Customer account review settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="alert alert-info">
				<?php echo smartyTranslate(array('s'=>'Your customers will have access to a "my reviews" section when they visit their account main page. In that section, they will be able to see the products they have not yet reviewed (presented in a visual slideshow fashion), as well as the products they have reviewed (in a simple table format). The settings below give you little bit control on how all this is displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please select a size','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
						<strong><?php echo smartyTranslate(array('s'=>'Product image size for slideshow','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-prod-img" id="bt_slider-prod-img" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['sImageSize'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sImageSize']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImageSize']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sImageSize']->key => $_smarty_tpl->tpl_vars['sImageSize']->value) {
$_smarty_tpl->tpl_vars['sImageSize']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['sImageSize']->key;
?>
							<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sImageSize']->value==$_smarty_tpl->tpl_vars['sSliderProdImg']->value) {?> selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sImageSize']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please select a size','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
					<!-- Button trigger modal -->
					<a href="#" data-toggle="modal" data-target="#modal_slider_preview"><span class="icon-eye-open">&nbsp;</span><?php echo smartyTranslate(array('s'=>'Click here to show a preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					<!-- Modal -->
					<div class="modal fade" id="modal_slider_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content modal-lg">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span></button>
									<h4 class="modal-title" id="myModalLabel"><?php echo smartyTranslate(array('s'=>'Preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
								</div>
								<div class="modal-body">
									<div class="center"><img src="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_URL_IMG'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
admin/screenshot-product-slider.jpg"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Based on the image size you selected above, as well as the specificities of your theme, you may need to adjust this value','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Width of slideshow container','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-width" id="bt_slider-width" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['iWidth'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iWidth']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aSliderOpts']->value['width']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iWidth']->key => $_smarty_tpl->tpl_vars['iWidth']->value) {
$_smarty_tpl->tpl_vars['iWidth']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['iWidth']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['iWidth']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['iWidth']->value==$_smarty_tpl->tpl_vars['iSliderWidth']->value) {?> selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iWidth']->value);?>
 px</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Based on the image size you selected above, as well as the specificities of your theme, you may need to adjust this value','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The amount of time (in seconds) each product will be displayed in the slider before the next product','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Slider time interval','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-pause" id="bt_slider-pause" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['iPause'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iPause']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aSliderOpts']->value['pause']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iPause']->key => $_smarty_tpl->tpl_vars['iPause']->value) {
$_smarty_tpl->tpl_vars['iPause']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['iPause']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['key']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['iSliderPause']->value) {?> selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iPause']->value);?>
 <?php echo smartyTranslate(array('s'=>'sec','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The amount of time (in seconds) each product will be displayed in the slider before the next product','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The amount of time (in seconds) it will take for a product to slide from right to left','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Slider movement speed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-speed" id="bt_slider-speed" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['iSpeed'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iSpeed']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aSliderOpts']->value['speed']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iSpeed']->key => $_smarty_tpl->tpl_vars['iSpeed']->value) {
$_smarty_tpl->tpl_vars['iSpeed']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['iSpeed']->key;
?>
							<option value="<?php echo intval($_smarty_tpl->tpl_vars['key']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['iSliderSpeed']->value) {?> selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iSpeed']->value);?>
 <?php echo smartyTranslate(array('s'=>'sec','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
						<?php } ?>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The amount of time (in seconds) it will take for a product to slide from right to left','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>
		<?php }?>

		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='last') {?>
			<h3><?php echo smartyTranslate(array('s'=>'Last Reviews Block Settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="alert alert-info">
				<?php echo smartyTranslate(array('s'=>'The module lets you display a block with the latest customer reviews on various sections of your website. The options below give you control over how this is all displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If disabled, the last reviews block will not be displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'Display block of last reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-last-reviews" id="bt_display-last-reviews_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayLastRvwBlock']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_last-review-block', null, null, true, true);" />
						<label for="bt_display-last-reviews_on" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<input type="radio" name="bt_display-last-reviews" id="bt_display-last-reviews_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayLastRvwBlock']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_last-review-block', null, null, true, false);" />
						<label for="bt_display-last-reviews_off" class="radioCheck">
							<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If disabled, the last reviews block will not be displayed','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_last-review-block" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayLastRvwBlock']->value)) {?>block<?php } else { ?>none<?php }?>;">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This determines how many reviews will be displayed in the latest reviews block','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
							<strong><?php echo smartyTranslate(array('s'=>'Number of reviews to display','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						<select name="bt_nb-last-reviews" id="bt_nb-last-reviews" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<?php  $_smarty_tpl->tpl_vars['nb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nb']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aNbLastReviews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nb']->key => $_smarty_tpl->tpl_vars['nb']->value) {
$_smarty_tpl->tpl_vars['nb']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nb']->key;
?>
								<option value="<?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['nb']->value==$_smarty_tpl->tpl_vars['iNbLastReviews']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['nb']->value);?>
</option>
							<?php } ?>
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This determines how many reviews will be displayed in the latest reviews block','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPages']->value)) {?>
				<div class="clr_10"></div>

				
				<script type="text/javascript">
					function activateLastReviewBlock(elt) {
						if ($(elt).is('.action-enabled')){
							$(elt).removeClass('action-enabled');
							$(elt).addClass('action-disabled');
							$(elt).children('i').removeClass('icon-check');
							$(elt).parent().removeClass('success');
							$(elt).children('i').addClass('icon-remove');
							$(elt).parent().addClass('danger');
							$(elt).children('input').removeAttr('checked', 'checked');
							$(elt).children('input').val(0);
						}
						else {
							$(elt).removeClass('action-disabled');
							$(elt).addClass('action-enabled');
							$(elt).children('i').removeClass('icon-remove');
							$(elt).parent().removeClass('danger');
							$(elt).children('i').addClass('icon-check');
							$(elt).parent().addClass('success');
							$(elt).children('input').attr('checked', 'checked');
							$(elt).children('input').val(1);
						}
					};
				</script>
				

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This determines where the block will be displayed. You can display it on more than one page','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
							<strong><?php echo smartyTranslate(array('s'=>'Display block on the following pages','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<table class="table">
							<thead>
							<tr>
								<th><div class="title_box center"><?php echo smartyTranslate(array('s'=>'Page','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</div></th>
								<th><div class="title_box center"><?php echo smartyTranslate(array('s'=>'Active','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</div></th>
								<th><div class="title_box center"><?php echo smartyTranslate(array('s'=>'Hook','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</div></th>
								<th><div class="title_box center"><?php echo smartyTranslate(array('s'=>'Width','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Default width is 100%','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span></div></th>
								<th><div class="title_box center"><?php echo smartyTranslate(array('s'=>'Truncate comments','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This determines how many characters of comments that the module will truncate to display the last reviews block correctly','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span></div></th>
							</tr>
							</thead>
							<tbody>
							<?php  $_smarty_tpl->tpl_vars['aPage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aPage']->_loop = false;
 $_smarty_tpl->tpl_vars['sPageVal'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aLastBlockPages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aPage']->key => $_smarty_tpl->tpl_vars['aPage']->value) {
$_smarty_tpl->tpl_vars['aPage']->_loop = true;
 $_smarty_tpl->tpl_vars['sPageVal']->value = $_smarty_tpl->tpl_vars['aPage']->key;
?>
								<?php if (!empty($_smarty_tpl->tpl_vars['aPage']->value['use'])) {?>
									<tr>
										<td class="center col-xs-12 col-sm-12 col-md-3 col-lg-3"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aPage']->value['title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

											<input type="hidden" name="bt_select-block-pos[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-1 col-lg-1 <?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['display'])) {?>success<?php } else { ?>danger<?php }?>">
											<div class="list-action-enable action-<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['display'])) {?>enabled<?php } else { ?>disabled<?php }?>" onclick="javascript: activateLastReviewBlock(this);"><i class="icon-<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['display'])) {?>check<?php } else { ?>remove<?php }?>"></i><input type="hidden" name="bt_select-block-pos[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" value="<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['display'])) {?>1<?php } else { ?>0<?php }?>" <?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['display'])) {?> checked="checked"<?php }?> /></div>
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-4 col-lg-3">
											<select name="bt_last-block-position[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" id="bt_last-block-position[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" class="col-xs-12 col-md-12 col-lg-12">
												<?php  $_smarty_tpl->tpl_vars['aPosTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aPosTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['iPos'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aPage']->value['allow']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aPosTitle']->key => $_smarty_tpl->tpl_vars['aPosTitle']->value) {
$_smarty_tpl->tpl_vars['aPosTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['iPos']->value = $_smarty_tpl->tpl_vars['aPosTitle']->key;
?>
													<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aPosTitle']->value['position'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['position'])&&$_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['position']==$_smarty_tpl->tpl_vars['aPosTitle']->value['position']) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aPosTitle']->value['title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
												<?php } ?>
											</select>
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-2 col-lg-2"><span class="col-xs-10 col-sm-10 col-md-10 col-lg-10"><input type="text" id="bt_last-block-width[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" name="bt_last-block-width[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" value="<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['width'])) {?><?php echo intval($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['width']);?>
<?php } else { ?>100<?php }?>" /></span>&nbsp;<span class="col-xs-2 col-sm-2 col-md-2 col-lg-2">%</span></td>
										<td class="center col-xs-12 col-sm-12 col-md-2 col-lg-2"><span class="col-xs-10 col-sm-10 col-md-10 col-lg-10"><input type="text" id="bt_last-block-truncate[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" name="bt_last-block-truncate[<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sPageVal']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
]" value="<?php if (!empty($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['truncate'])) {?><?php echo intval($_smarty_tpl->tpl_vars['aLastBlockPos']->value[$_smarty_tpl->tpl_vars['sPageVal']->value]['truncate']);?>
<?php } else { ?>30<?php }?>" /></span>&nbsp;<span class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo smartyTranslate(array('s'=>'chars','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span></td>
									</tr>
								<?php }?>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php }?>

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-2 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The module usually displays a badge on your website with your average ratings and other information. If you also display the last reviews block, you can choose to display it first (before the badge)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
							<strong><?php echo smartyTranslate(array('s'=>'Display before the Badge block','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-block-first" id="bt_display-block-first_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bLastRvwBlockFirst']->value)) {?>checked="checked"<?php }?>  />
							<label for="bt_display-block-first_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_display-block-first" id="bt_display-block-first_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bLastRvwBlockFirst']->value)) {?>checked="checked"<?php }?> />
							<label for="bt_display-block-first_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The module usually displays a badge on your website with your average ratings and other information. If you also display the last reviews block, you can choose to display it first (before the badge)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>
			</div>
		<?php }?>

		
		<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='list') {?>
			<h3><?php echo smartyTranslate(array('s'=>'Product star ratings in list pages (e.g: category / brand / search etc...)','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

			<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
				<div class="clr_10"></div>
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php }?>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'There are 2 ways to apply it, either by using the PrestaShop hook and leave the option activated below or by including the code below by yourself','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
						<strong><?php echo smartyTranslate(array('s'=>'How to display review stars in your product-list.tpl','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_stars-review" id="bt_stars-review" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<option value="">...</option>
						<option value="hook" selected="selected"><?php echo smartyTranslate(array('s'=>'Use "displayProductListReviews" hook','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
						<option value="yourself"><?php echo smartyTranslate(array('s'=>'Copy / paste the code below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'There are 2 ways to apply it, either by using the PrestaShop hook and leave the option activated below or by including the code below by yourself','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_display-options">
				<div class="clr_10"></div>
				
				<div id="bt_div-stars-hook" style="display: none;">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Display review stars in the product-list.tpl','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-stars-in-list" id="bt_display-stars-in-list_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayStarsInList']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-use-snippets-prodlist', null, null, true, true);" />
							<label for="bt_display-stars-in-list_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_display-stars-in-list" id="bt_display-stars-in-list_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayStarsInList']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-use-snippets-prodlist', null, null, true, false);" />
							<label for="bt_display-stars-in-list_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>

					<div id="bt_disp-use-snippets-prodlist" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayStarsInList']->value)) {?>block<?php } else { ?>none<?php }?>;">

						<div class="clr_10"></div>
						
						<?php if (!empty($_smarty_tpl->tpl_vars['bPS17']->value)&&empty($_smarty_tpl->tpl_vars['bPS1710']->value)) {?>
						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-warning">
									<?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE: We have identified you are on PS version between 1.7 and 1.7.1.0, so your version doesn\'t handle the hook "displayProductListReviews" in the standard theme, that\'s why you absolutely need to follow ou FAQ on how to include it in your theme and display review stars in the product list template here:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									<div class="clr_5"></div>
									<a class="badge badge-info" href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=152&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><i class="icon icon-link"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'FAQ: How do I display the review stars in the product list?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
								</div>
							</div>
						</div>
						<?php }?>

						<div class="clr_20"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, empty stars will be displayed when any rating has been done for the current product in the product list.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Display empty stars by default in the product list','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_display-empty-stars" id="bt_display-empty-stars_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayEmptyRating']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-befirst-text', null, null, true, true);" />
									<label for="bt_display-empty-stars_on" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<input type="radio" name="bt_display-empty-stars" id="bt_display-empty-stars_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayEmptyRating']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-befirst-text', null, null, true, false);" />
									<label for="bt_display-empty-stars_off" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, empty stars will be displayed when any rating has been done for the current product in the product list.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>

						<div id="bt_disp-befirst-text" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayEmptyRating']->value)) {?>block<?php } else { ?>none<?php }?>;">
							<div class="form-group">
								<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, a custom message will be displayed to invite your customers to be the first to review the product, but of course according to the option "who can review".','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Display a custom message with empty stars','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
								</label>
								<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_display-befirst-msg" id="bt_display-befirst-msg_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayBeFirstMessage']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_use-befirst-text', null, null, true, true);" />
									<label for="bt_display-befirst-msg_on" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<input type="radio" name="bt_display-befirst-msg" id="bt_display-befirst-msg_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bDisplayBeFirstMessage']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_use-befirst-text', null, null, true, false);" />
									<label for="bt_display-befirst-msg_off" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<a class="slide-button btn"></a>
								</span>
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, a custom message will be displayed to invite your customers to be the first to review the product, but of course according to the option "who can review".','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>

							<div id="bt_use-befirst-text" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bDisplayBeFirstMessage']->value)) {?>block<?php } else { ?>none<?php }?>;">
								<div class="form-group ">
									<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This allows you to set a predefined text that will constitute the text beside the empty stars','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
											<strong><?php echo smartyTranslate(array('s'=>'Custom message','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
										</span> :
									</label>
									<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
										<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
											<div id="bt_div-befirst-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
												<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
													<input type="text" id="bt_befirst-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_befirst-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aBeFirst']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aBeFirst']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangTitle']->key => $_smarty_tpl->tpl_vars['sLangTitle']->value) {
$_smarty_tpl->tpl_vars['sLangTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['idLang']->value = $_smarty_tpl->tpl_vars['sLangTitle']->key;
?><?php if ($_smarty_tpl->tpl_vars['idLang']->value==$_smarty_tpl->tpl_vars['aLang']->value['id_lang']) {?> value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php }?><?php } ?><?php }?> />
												</div>
												<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
													<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aLang']->value['iso_code'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&nbsp;<i class="icon-caret-down"></i></button>
													<ul class="dropdown-menu">
														<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
															<li><a href="javascript:hideOtherLanguage(<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
);" tabindex="-1"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aLang']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</a></li>
														<?php } ?>
													</ul>
													<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This allows you to set a predefined text that will constitute the text beside the empty stars','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you display stars rating in the product-list.tpl, you have the choice between 2 cases: 1/ display stars alone, 2/ display stars + numeric average + total of ratings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'How do you want to display stars rating?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<select name="bt_stars-display-mode" id="bt_stars-display-mode" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<option value="1" <?php if ($_smarty_tpl->tpl_vars['iStarDisplayMode']->value==1) {?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Display stars only','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
									<option value="2" <?php if ($_smarty_tpl->tpl_vars['iStarDisplayMode']->value==2) {?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Display stars + numeric average + total of ratings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</option>
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you display stars rating in the product-list.tpl, you have the choice between 2 cases: 1/ display stars alone, 2/ display stars + numeric average + total of ratings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-warning">
									<?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE: If you have selected "display stars only" above, both options below won\'t be activated and you couldn\'t display rich snippets rating tags associated to each product in the product list.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</div>
							</div>
						</div>

						<h4><?php echo smartyTranslate(array('s'=>'Rich Snippets Rating in list page','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
						<div class="clr_hr"></div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, you should check if your theme already includes rich snippets "product" or not in the product-list.tpl. In that way, our module will make your module\'s rich snippets "rating" perfectly compatible with your existing rich snippets tags.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Display rich snippets "rating"','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_use-snippets-prodlist" id="bt_use-snippets-prodlist_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bUseSnippetsProdList']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-has-snippets-prodlist', null, null, true, true);" />
									<label for="bt_use-snippets-prodlist_on" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<input type="radio" name="bt_use-snippets-prodlist" id="bt_use-snippets-prodlist_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bUseSnippetsProdList']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_disp-has-snippets-prodlist', null, null, true, false);" />
									<label for="bt_use-snippets-prodlist_off" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'When you activate this option, you should check if your theme already includes rich snippets "product" or not in the product-list.tpl. In that way, our module will make your module\'s rich snippets "rating" perfectly compatible with your existing rich snippets tags.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group" id="bt_disp-has-snippets-prodlist" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bUseSnippetsProdList']->value)) {?>block<?php } else { ?>none<?php }?>;">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Do not forget when you activate it, you should know first if your theme already includes rich snippets "product" tags in the product-list.tpl. You just need to do a test of your pages that using product-list.tpl by doing copy/paste of the URL of these pages in the google rich snippets tool. Then you\'ll be able to say yes or no with the button below as well as our module will include only rich snippets "rating" or "rating" + "product".','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Do you have rich snippets "product" in your product-list.tpl?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_has-snippets-prodlist" id="bt_has-snippets-prodlist_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bHasSnippetsProdList']->value)) {?>checked="checked"<?php }?>  />
									<label for="bt_has-snippets-prodlist_on" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<input type="radio" name="bt_has-snippets-prodlist" id="bt_has-snippets-prodlist_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bHasSnippetsProdList']->value)) {?>checked="checked"<?php }?> />
									<label for="bt_has-snippets-prodlist_off" class="radioCheck">
										<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Do not forget when you activate it, you should know first if your theme already includes rich snippets "product" tags in the product-list.tpl. You just need to do a test of your pages that using product-list.tpl by doing copy/paste of the URL of these pages in the google rich snippets tool. Then you\'ll be able to say yes or no with the button below as well as our module will include only rich snippets "rating" or "rating" + "product".','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<h4><?php echo smartyTranslate(array('s'=>'Advanced displaying tool','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>
						<div class="clr_hr"></div>
						<div class="clr_20"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-info">
									<?php echo smartyTranslate(array('s'=>'You may have some issues due to your theme around the stars + text rendering in the product list page, then these options below will offer you to fit the stars + text rendering close to the best rendering to your theme.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

									<div class="clr_5"></div>
									<span class="red-text"><?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE: only use these options below if you are technical. If not, please advise your technical contact or web agency to set them as the best way as possible to fit to your theme.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span>
								</div>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the star size by decreasing or increasing the value below in "em" unit. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Adjust the stars size','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_stars-size" id="bt_stars-size" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<?php  $_smarty_tpl->tpl_vars['iStarSize'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iStarSize']->_loop = false;
 $_smarty_tpl->tpl_vars['sKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aStarSizes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iStarSize']->key => $_smarty_tpl->tpl_vars['iStarSize']->value) {
$_smarty_tpl->tpl_vars['iStarSize']->_loop = true;
 $_smarty_tpl->tpl_vars['sKey']->value = $_smarty_tpl->tpl_vars['iStarSize']->key;
?>
										<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sKey']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['iSelectStarSize']->value)&&$_smarty_tpl->tpl_vars['iSelectStarSize']->value==$_smarty_tpl->tpl_vars['sKey']->value) {?>selected="selected"<?php }?>><?php echo floatval($_smarty_tpl->tpl_vars['iStarSize']->value);?>
 em</option>
									<?php } ?>
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the star size by decreasing or increasing the value below in "em" unit. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the text size by decreasing or increasing the value below in "px" unit. The defined value will load the matching css class included into the "hook.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Adjust the text size','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_text-size" id="bt_text-size" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<?php  $_smarty_tpl->tpl_vars['iTextSize'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iTextSize']->_loop = false;
 $_smarty_tpl->tpl_vars['iPos'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aTextSizes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iTextSize']->key => $_smarty_tpl->tpl_vars['iTextSize']->value) {
$_smarty_tpl->tpl_vars['iTextSize']->_loop = true;
 $_smarty_tpl->tpl_vars['iPos']->value = $_smarty_tpl->tpl_vars['iTextSize']->key;
?>
										<option value="<?php echo intval($_smarty_tpl->tpl_vars['iTextSize']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['iSelectTextSize']->value)&&$_smarty_tpl->tpl_vars['iSelectTextSize']->value==$_smarty_tpl->tpl_vars['iTextSize']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iTextSize']->value);?>
 px</option>
									<?php } ?>
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the text size by decreasing or increasing the value below in "px" unit. The defined value will load the matching css class included into the "hook.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'In most cases, stars and text will be displayed together and it looks center-aligned well, so the default value is 0. Sometimes this content is not aligned center in the product block, so you can play and adjust the padding left to pull stars and text in pixel to the middle as much as possible. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
									<strong><?php echo smartyTranslate(array('s'=>'Adjust the div stars padding-left','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_div-stars-padding" id="bt_div-stars-padding" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<?php  $_smarty_tpl->tpl_vars['iPaddingLet'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iPaddingLet']->_loop = false;
 $_smarty_tpl->tpl_vars['iPos'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aStarsPaddingLeft']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iPaddingLet']->key => $_smarty_tpl->tpl_vars['iPaddingLet']->value) {
$_smarty_tpl->tpl_vars['iPaddingLet']->_loop = true;
 $_smarty_tpl->tpl_vars['iPos']->value = $_smarty_tpl->tpl_vars['iPaddingLet']->key;
?>
										<option value="<?php echo intval($_smarty_tpl->tpl_vars['iPaddingLet']->value);?>
" <?php if (isset($_smarty_tpl->tpl_vars['iStarPaddingLeft']->value)&&$_smarty_tpl->tpl_vars['iStarPaddingLeft']->value==$_smarty_tpl->tpl_vars['iPaddingLet']->value) {?>selected="selected"<?php }?>><?php echo intval($_smarty_tpl->tpl_vars['iPaddingLet']->value);?>
 px</option>
									<?php } ?>
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'In most cases, stars and text will be displayed together and it looks center-aligned well, so the default value is 0. Sometimes this content is not aligned center in the product block, so you can play and adjust the padding left to pull stars and text in pixel to the middle as much as possible. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
					</div>
				</div>

				
				<div id="bt_div-stars-yourself" style="display: none;">
					<div class="clr_20"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="alert alert-info">
								<?php echo smartyTranslate(array('s'=>'You can also have the average rating appear on list pages (e.g: category / brand / search etc...) for each product (guaranteed to work on the default PrestaShop theme ONLY). To do so, simply copy and paste the code below in the product-list.tpl template of your theme, right after the','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
  &lt;p class="product_desc"&gt;&lt;/p&gt; <?php echo smartyTranslate(array('s'=>'tag of the product description','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
:<br /><br />
								<strong style="color: red; font-weight: bold;"><?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> <?php echo smartyTranslate(array('s'=>'This is very technical, and if you are not an integrator or webmaster, simply ignore this section','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
<br /><br />
								<pre style="font-family: 'Courier New', Courier, monospace;"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sIncludingCode']->value, 'UTF-8');?>
</pre><br />
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php }?>

		<div class="clr_20"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-reviews-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', null, 'bt_settings-review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'bt_settings-review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', false, false, oReviewsCallBack, 'review-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'review');return false;"><i class="process-icon-save"></i><?php echo smartyTranslate(array('s'=>'Update','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button></div>
		</div>
	</form>
	<div class="clr_20"></div>
</div>


<script type="text/javascript">
	// handle stars reviews type
	$("#bt_stars-review").bind('change', function (event) {
		$("#bt_stars-review option:selected").each(function () {
			switch ($(this).val()) {
				case 'hook' :
					$("#bt_display-options").show();
					$("#bt_div-stars-hook").show();
					$("#bt_div-stars-yourself").hide();
					break;
				case 'yourself' :
					$("#bt_display-options").show();
					$("#bt_div-stars-hook").hide();
					$("#bt_div-stars-yourself").show();
					break;
				default:
					$("#bt_display-options").hide();
					$("#bt_div-stars-hook").hide();
					$("#bt_div-stars-yourself").hide();
					break;
			}
		});
	}).change();

	// handle fb kind to use
	$("#bt_fb-button-type").bind('change', function (event) {
		$("#bt_fb-button-type option:selected").each(function () {
			switch ($(this).val()) {
				case '1' :
					$("#bt_div-fb-warning-msg").hide();
					break;
				case '2' :
					$("#bt_div-fb-warning-msg").show();
					break;
				case '3' :
					$("#bt_div-fb-warning-msg").show();
					break;
				default:
					$("#bt_div-fb-warning-msg").hide();
					break;
			}
		});
	}).change();

	
	<?php if (!empty($_smarty_tpl->tpl_vars['aImages']->value)) {?>
	
	$("#bt_picto").bind('change', function (event) {
		$("#bt_picto option").each(function (i) {
			if ($(this).attr('selected')) {
				$("#bt_picto-" + $(this).val()).css('display', 'inline');
			}
			else {
				$("#bt_picto-" + $(this).val()).css('display', 'none');
			}
		});
	});
	
	<?php }?>
	

	// handle stars reviews type
	$("#bt_reviews-display-mode").bind('change', function (event) {
		$("#bt_reviews-display-mode option:selected").each(function () {
			switch ($(this).val()) {
				case 'tabs17' :
					$("#bt_display-theme-warning").show();
					break;
				default:
					$("#bt_display-theme-warning").hide();
					break;
			}
		});
	}).change();

	//bootstrap components init
	$('.label-tooltip, .help-tooltip').tooltip();
	$('.dropdown-toggle').dropdown();
</script>
<?php }} ?>
