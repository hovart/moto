<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:00
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/reviews-email-settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77178754559897688668f59-51219972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77a326e020b325ce9af25fefacb774bb69349f42' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/admin/reviews-email-settings.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77178754559897688668f59-51219972',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aEmailLangErrors' => 0,
    'sLangName' => 0,
    'sIsoCode' => 0,
    'sCurrentLang' => 0,
    'sURI' => 0,
    'sDisplay' => 0,
    'aQueryParams' => 0,
    'bUpdate' => 0,
    'sConfirmInclude' => 0,
    'aErrors' => 0,
    'sErrorInclude' => 0,
    'aLangs' => 0,
    'aLang' => 0,
    'iCurrentLang' => 0,
    'aReviewEmailSubject' => 0,
    'idLang' => 0,
    'sLangTitle' => 0,
    'aImgTypes' => 0,
    'aTypes' => 0,
    'sProductImgType' => 0,
    'bEnableEmail' => 0,
    'sEmail' => 0,
    'aReplyEmailSubject' => 0,
    'aReplyEmailText' => 0,
    'bDisplayReviews' => 0,
    'bEnableRatings' => 0,
    'bEnableCallback' => 0,
    'sToday' => 0,
    'sCronUrl' => 0,
    'sSecureKey' => 0,
    'bEnableCarbonCopy' => 0,
    'sCarbonCopyMail' => 0,
    'aEmailSubject' => 0,
    'aEmailCategoryLabel' => 0,
    'aEmailProductLabel' => 0,
    'aEmailSentence' => 0,
    'aOrderStatusTitle' => 0,
    'aOrder' => 0,
    'id' => 0,
    'aStatusSelection' => 0,
    'iIdSelect' => 0,
    'iDelayEmail' => 0,
    'bPsVersion1606' => 0,
    'bwritableReport' => 0,
    'sReportFile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976889fb385_23856981',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976889fb385_23856981')) {function content_598976889fb385_23856981($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>


<div class="bootstrap">
	
	<?php if (!empty($_smarty_tpl->tpl_vars['aEmailLangErrors']->value)) {?>
		<div class="clr_20"></div>
		<div class="alert alert-danger">
			<h2><?php echo smartyTranslate(array('s'=>'Missing languages for emails folder','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 (<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_PATH_MAILS'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
)</h2>
			<div class="clr_5"></div>
			<p><?php echo smartyTranslate(array('s'=>'This panel will be active once you would have added emails folder to each active language as noticed below. If there is any active language you are not using, you can deactivate it in your Back-office','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.</p>
			<div class="clr_20"></div>
			<h4><?php echo smartyTranslate(array('s'=>'This is all languages which are not added in this folder','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 : </h4>
			<?php  $_smarty_tpl->tpl_vars['sLangName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangName']->_loop = false;
 $_smarty_tpl->tpl_vars['sIsoCode'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEmailLangErrors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangName']->key => $_smarty_tpl->tpl_vars['sLangName']->value) {
$_smarty_tpl->tpl_vars['sLangName']->_loop = true;
 $_smarty_tpl->tpl_vars['sIsoCode']->value = $_smarty_tpl->tpl_vars['sLangName']->key;
?>
				<p><?php echo smartyTranslate(array('s'=>'Language','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 : <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo smartyTranslate(array('s'=>'ISO','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 : <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sIsoCode']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
) </p>
			<?php } ?>
			<div class="clr_20"></div>
			<p><strong><?php echo smartyTranslate(array('s'=>'YOU DO NOT KNOW WHAT TO DO WITH THIS ISSUE, JUST FOLLOW OUR FAQ LINK HERE:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> <a href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=150&pid=7&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><?php echo smartyTranslate(array('s'=>'How to add a new folder of mail templates into the module?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a></p>
		</div>
	
	<?php } else { ?>
		<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sURI']->value, 'UTF-8');?>
" method="post" id="bt_form-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="bt_form-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" onsubmit="oGsr.form('bt_form-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', null, 'bt_settings-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'bt_settings-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', false, false, null, 'email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'email');return false;">
			<input type="hidden" name="sAction" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['email']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
			<input type="hidden" name="sType" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['email']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
			<input type="hidden" name="sDisplay" id="sEmailsDisplay" value="<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?>global<?php }?>" />

			
			<?php if (empty($_smarty_tpl->tpl_vars['sDisplay']->value)||(!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='global')) {?>
				<h3 class="subtitle"><?php echo smartyTranslate(array('s'=>'Review e-mail settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

				<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
					<div class="clr_10"></div>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
					<div class="clr_10"></div>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php }?>

				<div class="clr_10"></div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Subject of notification email when a review is published','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
					<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
						<div id="bt_reviews-email-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<input type="text" id="bt_email-review-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-review-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aReviewEmailSubject']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReviewEmailSubject']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The e-mails will contain a photo of each product. Please select the image size. You should select small or a size approaching 50 x 50 pixels for correct visual rendering.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Set default image type for products','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<select name="bt_products-img-type" id="bt_products-img-type" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<?php  $_smarty_tpl->tpl_vars['aTypes'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aTypes']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aImgTypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aTypes']->key => $_smarty_tpl->tpl_vars['aTypes']->value) {
$_smarty_tpl->tpl_vars['aTypes']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['aTypes']->key;
?>
								<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aTypes']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['sProductImgType']->value==$_smarty_tpl->tpl_vars['aTypes']->value['name']) {?>selected="selected"<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aTypes']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
							<?php } ?>
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The e-mails will contain a photo of each product. Please select the image size. You should select small or a size approaching 50 x 50 pixels for correct visual rendering.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, this will allow you to receive an e-mail notification when a new review / rating is posted or a review is reported as an abuse.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Receive an alert by email','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-email" id="bt_enable-email_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableEmail']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-email', null, null, true, true);"  />
							<label for="bt_enable-email_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_enable-email" id="bt_enable-email_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableEmail']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-email', null, null, true, false);" />
							<label for="bt_enable-email_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, this will allow you to receive an e-mail notification when a new review / rating is posted or a review is reported as an abuse.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group" id="bt_div-email" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableEmail']->value)) {?>block<?php } else { ?>none<?php }?>;">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Enter your email address for notifications','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-envelope"></i></span>
							<input type="text" id ="bt_email" name="bt_email" value="<?php if (!empty($_smarty_tpl->tpl_vars['sEmail']->value)) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sEmail']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" />
						</div>
					</div>
				</div>
			<?php }?>

			
			<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='litigation') {?>
				<h3 class="subtitle"><?php echo smartyTranslate(array('s'=>'Review litigation e-mails','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
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
					<?php echo smartyTranslate(array('s'=>'There are times when your customers will leave unfair or inadequate reviews, and you will want to have a chance to contact the customer and try to convince him / her to modify his / her rating and / or review. To save you time, this section allows you to predefine the text of the e-mail subject and main content. The main content can then of course be personalized on a case by case basis when you reply to a customer review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
				</div>

				<div class="clr_20"></div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This defines the subject of the e-mail that customer will receive when you send a review litigation reply','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Subject of the e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
							<div id="bt_replies-email-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="bt_email-reply-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-reply-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aReplyEmailSubject']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReplyEmailSubject']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This defines the subject of the e-mail that customer will receive when you send a review litigation reply','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This allows you to set a predefined message that will constitute the body of the e-mail. You will of course be able to personalize it on a case by case basis when you reply to a customer review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
							<strong><?php echo smartyTranslate(array('s'=>'Default content of the e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
							<div id="bt_replies-email-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<textarea id="bt_email-reply-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" rows="10" name="bt_email-reply-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
"><?php if (!empty($_smarty_tpl->tpl_vars['aReplyEmailText']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aReplyEmailText']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangTitle']->key => $_smarty_tpl->tpl_vars['sLangTitle']->value) {
$_smarty_tpl->tpl_vars['sLangTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['idLang']->value = $_smarty_tpl->tpl_vars['sLangTitle']->key;
?><?php if ($_smarty_tpl->tpl_vars['idLang']->value==$_smarty_tpl->tpl_vars['aLang']->value['id_lang']) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?><?php } ?><?php }?></textarea>
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
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This allows you to set a predefined message that will constitute the body of the e-mail. You will of course be able to personalize it on a case by case basis when you reply to a customer review','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php }?>

			
			<?php if (!empty($_smarty_tpl->tpl_vars['sDisplay']->value)&&$_smarty_tpl->tpl_vars['sDisplay']->value=='reminder') {?>
				<h3><?php echo smartyTranslate(array('s'=>'Reminders settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h3>

				<?php if (!empty($_smarty_tpl->tpl_vars['bUpdate']->value)) {?>
					<div class="clr_10"></div>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sConfirmInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php } elseif (!empty($_smarty_tpl->tpl_vars['aErrors']->value)) {?>
					<div class="clr_10"></div>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sErrorInclude']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				<?php }?>

				<div class="clr_10"></div>

				
				<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)&&!empty($_smarty_tpl->tpl_vars['bEnableRatings']->value)) {?>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
							<strong><?php echo smartyTranslate(array('s'=>'Send a review reminder email to customers','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" id="bt_enable-callback-div">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-callback" id="bt_enable-callback_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableCallback']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_callback', null, null, true, true);"  />
							<label for="bt_enable-callback_on" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<input type="radio" name="bt_enable-callback" id="bt_enable-callback_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableCallback']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_callback', null, null, true, false);" />
							<label for="bt_enable-callback_off" class="radioCheck">
								<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
						<a class="badge badge-info" href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=107&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><i class="icon icon-link"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'FAQ: How do I test my reminder e-mails?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
					</div>
				</div>

				<div id="bt_callback" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableCallback']->value)) {?>block<?php } else { ?>none<?php }?>;">
					<div class="clr_20"></div>

					<h4 class="subtitle"><?php echo smartyTranslate(array('s'=>'Want to get reviews fast ? Start by inviting all your past customers to review their products below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div id="bt_orders-import-div">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
								<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo smartyTranslate(array('s'=>'Please select a period. All orders placed during the period will cause the corresponding customers to receive an invitation e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
".>
									<strong><?php echo smartyTranslate(array('s'=>'Select orders placed between these dates','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<div class="input-group">
										<span class="input-group-addon"><?php echo smartyTranslate(array('s'=>'From','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
:</span>
										<input type="text" class="datepicker input-medium" name="bt_order-date-from" value="" id="bt_order-date-from">
										<span class="input-group-addon"><?php echo smartyTranslate(array('s'=>'To:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span>
										<input type="text" class="datepicker input-medium" name="bt_order-date-to" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sToday']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="bt_order-date-to">
										<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
									</div>
								</div>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please select a period. All orders placed during the period will cause the corresponding customers to receive an invitation e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span></span>
								&nbsp;<input type="button" name="bt_orders-select-button" value="<?php echo smartyTranslate(array('s'=>'Preview sending','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
" class="btn btn-success" onclick="loadOrdersImport();return false;" />
								&nbsp;<a id="bt_display-orders-popup" class="fancybox.ajax"  href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sAction=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['displayOrders']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sType=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['displayOrders']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"></a>

								<div class="clr_10"></div>
								<div class="alert alert-info">
									<strong style="color: red; font-weight: bold;"><?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> <?php echo smartyTranslate(array('s'=>'You have just installed or updated our module and you wish you could invite all your customers from past orders to post a rating and review ? Select a period above (we recommend not selecting more than 3 months) and the e-mails will go out instantly. This is a great way to quickly start populating your website with customer reviews. You can do this several times, but we recommend you space out each batch by at least one week, and do it no more than 3 times total.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</div>
							</div>
						</div>

						<div class="form-group" id="bt_orders-select-error" style="display: none;">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="alert alert-danger ">
									<button type="button" class="close" onclick="$('#bt_orders-select-error').slideUp();">×</button>
									<?php echo smartyTranslate(array('s'=>'The date is still empty, you should select a date first','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
								</div>
							</div>
						</div>
					</div>

					<div class="clr_10"></div>

					<h4 class="subtitle"><?php echo smartyTranslate(array('s'=>'Your CRON Url for the automated batch to send reminders','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="">
								<strong><?php echo smartyTranslate(array('s'=>'Your CRON URL to call','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
							<input type="text" id ="bt_reminder-url" name="bt_reminder-url" placeholder="<?php if (!empty($_smarty_tpl->tpl_vars['sCronUrl']->value)) {?>http://<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCronUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars(@constant('_PS_BASE_URL_'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?><?php echo mb_convert_encoding(htmlspecialchars(@constant('_MODULE_DIR_'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_MODULE_SET_NAME'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/cron.php?bt_key=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sSecureKey']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" value="<?php if (!empty($_smarty_tpl->tpl_vars['sCronUrl']->value)) {?>http://<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCronUrl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars(@constant('_PS_BASE_URL_'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?><?php echo mb_convert_encoding(htmlspecialchars(@constant('_MODULE_DIR_'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_MODULE_SET_NAME'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/cron.php?bt_key=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sSecureKey']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
							<div class="clr_10"></div>
							<div class="alert alert-info">
								<strong style="color: red; font-weight: bold;"><?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> <?php echo smartyTranslate(array('s'=>'This requires to set a CRON task on your server. Please refer to the included PDF documentation (link in "Help" tab above) for detailed instructions.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							</div>
						</div>
					</div>

					<div class="clr_10"></div>

					<h4 class="subtitle"><?php echo smartyTranslate(array('s'=>'General Reminders settings','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, when a customer receive the reminder e-mail, you can receive this e-mail either as blind carbon copy','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
								<strong><?php echo smartyTranslate(array('s'=>'Receive a carbon copy of each e-mail sent','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_enable-carbon-copy" id="bt_enable-carbon-copy_on" value="1" <?php if (!empty($_smarty_tpl->tpl_vars['bEnableCarbonCopy']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-carbon-copy-email', null, null, true, true);" />
								<label for="bt_enable-carbon-copy_on" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'Yes','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<input type="radio" name="bt_enable-carbon-copy" id="bt_enable-carbon-copy_off" value="0" <?php if (empty($_smarty_tpl->tpl_vars['bEnableCarbonCopy']->value)) {?>checked="checked"<?php }?> onclick="oGsr.changeSelect(null, 'bt_div-carbon-copy-email', null, null, true, false);" />
								<label for="bt_enable-carbon-copy_off" class="radioCheck">
									<?php echo smartyTranslate(array('s'=>'No','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								</label>
								<a class="slide-button btn"></a>
							</span>
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'If activated, when a customer receive the reminder e-mail, you can receive this e-mail either as blind carbon copy','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
						</div>
					</div>

					<div class="form-group" id="bt_div-carbon-copy-email" style="display: <?php if (!empty($_smarty_tpl->tpl_vars['bEnableCarbonCopy']->value)) {?>block<?php } else { ?>none<?php }?>;">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'Enter your email address for reminder notifications','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-envelope"></i></span>
								<input type="text" id ="bt_carbon-copy-email" name="bt_carbon-copy-email" size="35" value="<?php if (!empty($_smarty_tpl->tpl_vars['sCarbonCopyMail']->value)) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCarbonCopyMail']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" />
							</div>

							<div class="clr_20"></div>

							<div class="alert alert-warning">
								<strong style="color: red; font-weight: bold;"><?php echo smartyTranslate(array('s'=>'IMPORTANT NOTE:','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> <?php echo smartyTranslate(array('s'=>'You need to understand once this option is activated, you would receive a lot e-mails according to your daily orders placed on your shop. After a while, once your e-mails sent have been checked on your own, you should deactivate it','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.
							</div>
						</div>
					</div>

					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
								<strong><?php echo smartyTranslate(array('s'=>'Email reminder subject','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
								<div id="bt_div-tab-email-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
									<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
										<input type="text" id="bt_email-reminder-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-reminder-title_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aEmailSubject']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEmailSubject']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This sections allows you to decide which labels and sentence you would have in the body text of each product included into the reminder e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
".>
								<strong><?php echo smartyTranslate(array('s'=>'Custom product detail text','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value) {
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
								<div id="bt_tab-email-body-text_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" class="translatable-field row lang-<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if ($_smarty_tpl->tpl_vars['aLang']->value['id_lang']!=$_smarty_tpl->tpl_vars['iCurrentLang']->value) {?>style="display:none"<?php }?>>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<input type="text" id="bt_email-category-label_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-category-label_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aEmailCategoryLabel']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEmailCategoryLabel']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangTitle']->key => $_smarty_tpl->tpl_vars['sLangTitle']->value) {
$_smarty_tpl->tpl_vars['sLangTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['idLang']->value = $_smarty_tpl->tpl_vars['sLangTitle']->key;
?><?php if ($_smarty_tpl->tpl_vars['idLang']->value==$_smarty_tpl->tpl_vars['aLang']->value['id_lang']) {?> value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php }?><?php } ?><?php }?> />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<input type="text" id="bt_email-product-label_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-product-label_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aEmailProductLabel']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEmailProductLabel']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangTitle']->key => $_smarty_tpl->tpl_vars['sLangTitle']->value) {
$_smarty_tpl->tpl_vars['sLangTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['idLang']->value = $_smarty_tpl->tpl_vars['sLangTitle']->key;
?><?php if ($_smarty_tpl->tpl_vars['idLang']->value==$_smarty_tpl->tpl_vars['aLang']->value['id_lang']) {?> value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php }?><?php } ?><?php }?> />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-11">
											<input type="text" id="bt_email-sentence_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" name="bt_email-sentence_<?php echo intval($_smarty_tpl->tpl_vars['aLang']->value['id_lang']);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['aEmailSentence']->value)) {?><?php  $_smarty_tpl->tpl_vars['sLangTitle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLangTitle']->_loop = false;
 $_smarty_tpl->tpl_vars['idLang'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aEmailSentence']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLangTitle']->key => $_smarty_tpl->tpl_vars['sLangTitle']->value) {
$_smarty_tpl->tpl_vars['sLangTitle']->_loop = true;
 $_smarty_tpl->tpl_vars['idLang']->value = $_smarty_tpl->tpl_vars['sLangTitle']->key;
?><?php if ($_smarty_tpl->tpl_vars['idLang']->value==$_smarty_tpl->tpl_vars['aLang']->value['id_lang']) {?> value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sLangTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php }?><?php } ?><?php }?> />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
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
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'This sections allows you to decide which labels and sentence you would have in the body text of each product included into the reminder e-mail','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">&nbsp;<span class="icon-question-sign"></span></span>
									</div>
								</div>
							<?php } ?>
							<div class="clr_10"></div>
							<!-- Button trigger modal -->
							<a href="#" data-toggle="modal" data-target="#modal_reminder_preview"><span class="icon-eye-open">&nbsp;</span><?php echo smartyTranslate(array('s'=>'Click here to show a preview','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
							<!-- Modal -->
							<div class="modal fade" id="modal_reminder_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
admin/screenshot-reminder-email.jpg" width="700" height="546"></div>
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

					<input type="hidden" id="bCheckStatus" name="bCheckStatus" value="1" />
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3" for="bt_order-statuses">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'We recommend you check at least one status considered as a valid order in your back-office. Only orders with one of the checked statuses above will receive a review reminder e-mail.','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
">
								<strong><?php echo smartyTranslate(array('s'=>'Order statuses for reminders','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="btn-actions">
								<div class="btn btn-default btn-mini" id="categoryCheck" onclick="return oGsr.selectAll('.myCheckbox', 'check');"><i class="icon-plus-square"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'Check All','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGsr.selectAll('.myCheckbox', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'Uncheck All','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</div>
								<div class="clr_10"></div>
							</div>
							<table cellspacing="0" cellpadding="0" class="table table-responsive table-bordered table-striped">
								<?php  $_smarty_tpl->tpl_vars['aOrder'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aOrder']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aOrderStatusTitle']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aOrder']->key => $_smarty_tpl->tpl_vars['aOrder']->value) {
$_smarty_tpl->tpl_vars['aOrder']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['aOrder']->key;
?>
									<tr>
										<td>
											<label style="float: right !important;" for="bt_order-status"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aOrder']->value[$_smarty_tpl->tpl_vars['iCurrentLang']->value], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</label>
										</td>
										<td>
											<input type="checkbox" name="bt_order-status[]" id="bt_order-status" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['id']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php if (!empty($_smarty_tpl->tpl_vars['aStatusSelection']->value)) {?><?php  $_smarty_tpl->tpl_vars['iIdSelect'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['iIdSelect']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aStatusSelection']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['iIdSelect']->key => $_smarty_tpl->tpl_vars['iIdSelect']->value) {
$_smarty_tpl->tpl_vars['iIdSelect']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['iIdSelect']->key;
?><?php if ($_smarty_tpl->tpl_vars['iIdSelect']->value==$_smarty_tpl->tpl_vars['id']->value) {?> checked="checked"<?php }?><?php } ?><?php }?> class="myCheckbox" />
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The review reminder e-mail will be sent X days after reaching the order\'s adding date + delay you set here, and of course if the order\'s state has reached one of the above statuses. So, for example, if you set it to Payment Accepted, this should probably be 7 days so there is enough time for you to prepare and ship the order. But if you set it to Shipped, then this might be 3 or 4 days, which is enough time for the actual shipment to be transported to its destination','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">
								<strong><?php echo smartyTranslate(array('s'=>'Delay for sending reminder email','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<input type="text" size="2" maxlength="2" name="bt_delay-email" id ="bt_delay-email" value="<?php if (isset($_smarty_tpl->tpl_vars['iDelayEmail']->value)) {?><?php echo intval($_smarty_tpl->tpl_vars['iDelayEmail']->value);?>
<?php }?>" />&nbsp;(<?php echo smartyTranslate(array('s'=>'days','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
)
							</div>
							<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="<?php echo smartyTranslate(array('s'=>'The review reminder e-mail will be sent X days after reaching the order\'s adding date + delay you set here, and of course if the order\'s state has reached one of the above statuses. So, for example, if you set it to Payment Accepted, this should probably be 7 days so there is enough time for you to prepare and ship the order. But if you set it to Shipped, then this might be 3 or 4 days, which is enough time for the actual shipment to be transported to its destination','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.">&nbsp;<span class="icon-question-sign"></span>&nbsp;</span>
								<a class="badge badge-info" href="<?php echo mb_convert_encoding(htmlspecialchars(@constant('_GSR_BT_FAQ_MAIN_URL'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
faq.php?id=125&lg=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sCurrentLang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" target="_blank"><i class="icon icon-link"></i>&nbsp;<?php echo smartyTranslate(array('s'=>'FAQ: How to fill my delay according to statuses?','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
							</div>
						</div>
					</div>

					
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong><?php echo smartyTranslate(array('s'=>'You can check your last emails reminder cron job','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<a id="cronReport" class="btn btn-warning" data-toggle="modal" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sAction=display&sType=cronReport" data-target="#modalCronReport"><?php echo smartyTranslate(array('s'=>'Click here','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</a>
							<div class="modal fade" id="modalCronReport" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
								<?php if (!empty($_smarty_tpl->tpl_vars['bPsVersion1606']->value)) {?>
								<div class="modal-dialog">
									<div class="modal-content">
									</div>
								</div>
								<?php }?>
							</div>

							<?php if (isset($_smarty_tpl->tpl_vars['bwritableReport']->value)&&$_smarty_tpl->tpl_vars['bwritableReport']->value==false) {?>
							<div class="clr_20"></div>
							<div class="alert alert-danger"><strong><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sReportFile']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</strong> => <?php echo smartyTranslate(array('s'=>'The log report file is not writable, please check the file permission via your FTP','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.</div>
							<?php }?>
						</div>
					</div>
					<div class="clr_20"></div>
				</div>
				<?php } else { ?>
					<div class="alert alert-danger"><?php echo smartyTranslate(array('s'=>'You have deactivated the full review system or the ratings feature alone. Please note you cannot configure this section if your customers won\'t be able to add a rating at least','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
.</div>
				<?php }?>
			<?php }?>

			<div class="clr_20"></div>
			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<div id="bt_error-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sURI']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', null, 'bt_settings-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'bt_settings-email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', false, false, null, 'email-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sDisplay']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
', 'email');return false;"><i class="process-icon-save"></i><?php echo smartyTranslate(array('s'=>'Update','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</button></div>
			</div>
		</form>

		<div class="clr_20"></div>

		
		<script type="text/javascript">
			// activate select all option in status features
			oGsr.selectAll('bt_order-status-all', '.myCheckbox');

			//bootstrap components init
			$('.label-tooltip, .help-tooltip').tooltip();
			$('.dropdown-toggle').dropdown();

			if ($(".datepicker").length > 0) {
				var date = new Date();
				var hours = date.getHours();
				if (hours < 10)
					hours = "0" + hours;
				var mins = date.getMinutes();
				if (mins < 10)
					mins = "0" + mins;
				var secs = date.getSeconds();
				if (secs < 10)
					secs = "0" + secs;
				$(".datepicker").datepicker({
					prevText: '',
					nextText: '',
					dateFormat: 'yy-mm-dd ' + hours + ':' + mins + ':' + secs
				});
			}

			function loadOrdersImport(){
				if($('#bt_order-date-from').val() == '') {
					$('#bt_orders-select-error').slideDown();
				}
				else {
					$('#bt_orders-select-error').slideUp();
					var sHref = '<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sURI']->value, 'UTF-8');?>
&sAction=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['displayOrders']['action'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&sType=<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aQueryParams']->value['displayOrders']['type'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
&dateFrom='+encodeURI($('#bt_order-date-from').val())+'&dateTo='+encodeURI($('#bt_order-date-to').val());
					$('#bt_display-orders-popup').attr('href', sHref);
					$("a#bt_display-orders-popup").fancybox({
						'hideOnContentClick' : false,
						'maxWidth' : 1000,
						'minWidth' : 800
					});
					$("a#bt_display-orders-popup").click();
				}
			}
		</script>
		
	<?php }?>
</div><?php }} ?>
