{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

{* use case - no emails folder missing *}
<div class="bootstrap">
	{* USE CASE - LANGS FOLDERS ARE NOT FILLED OUT *}
	{if !empty($aEmailLangErrors)}
		<div class="clr_20"></div>
		<div class="alert alert-danger">
			<h2>{l s='Missing languages for emails folder' mod='gsnippetsreviews'} ({$smarty.const._GSR_PATH_MAILS|escape:'htmlall':'UTF-8'})</h2>
			<div class="clr_5"></div>
			<p>{l s='This panel will be active once you would have added emails folder to each active language as noticed below. If there is any active language you are not using, you can deactivate it in your Back-office' mod='gsnippetsreviews'}.</p>
			<div class="clr_20"></div>
			<h4>{l s='This is all languages which are not added in this folder' mod='gsnippetsreviews'} : </h4>
			{foreach from=$aEmailLangErrors name=condition key=sIsoCode item=sLangName}
				<p>{l s='Language' mod='gsnippetsreviews'} : {$sLangName|escape:'htmlall':'UTF-8'} ({l s='ISO' mod='gsnippetsreviews'} : {$sIsoCode|escape:'htmlall':'UTF-8'}) </p>
			{/foreach}
			<div class="clr_20"></div>
			<p><strong>{l s='YOU DO NOT KNOW WHAT TO DO WITH THIS ISSUE, JUST FOLLOW OUR FAQ LINK HERE:' mod='gsnippetsreviews'}</strong> <a href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=150&pid=7&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank">{l s='How to add a new folder of mail templates into the module?' mod='gsnippetsreviews'}</a></p>
		</div>
	{* USE CASE - EVERYHTING IS OK AROUND LANGUAGES EMAIL FOLDERS *}
	{else}
		<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="{$sURI|escape:'UTF-8'}" method="post" id="bt_form-email-{$sDisplay|escape:'htmlall':'UTF-8'}" name="bt_form-email-{$sDisplay|escape:'htmlall':'UTF-8'}" onsubmit="oGsr.form('bt_form-email-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-email-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-email-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, null, 'email-{$sDisplay|escape:'htmlall':'UTF-8'}', 'email');return false;">
			<input type="hidden" name="sAction" value="{$aQueryParams.email.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.email.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sDisplay" id="sEmailsDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}global{/if}" />

			{************ REVIEW E-MAIL SETTINGS ************}
			{if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'global')}
				<h3 class="subtitle">{l s='Review e-mail settings' mod='gsnippetsreviews'}</h3>

				{if !empty($bUpdate)}
					<div class="clr_10"></div>
					{include file="`$sConfirmInclude`"}
				{elseif !empty($aErrors)}
					<div class="clr_10"></div>
					{include file="`$sErrorInclude`"}
				{/if}

				<div class="clr_10"></div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.' mod='gsnippetsreviews'}">
							<strong>{l s='Subject of notification email when a review is published' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
					{foreach from=$aLangs item=aLang}
						<div id="bt_reviews-email-title_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<input type="text" id="bt_email-review-title_{$aLang.id_lang|intval}" name="bt_email-review-title_{$aLang.id_lang|intval}" {if !empty($aReviewEmailSubject)}{foreach from=$aReviewEmailSubject key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
								<ul class="dropdown-menu">
									{foreach from=$aLangs item=aLang}
										<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
									{/foreach}
								</ul>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
					{/foreach}
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The e-mails will contain a photo of each product. Please select the image size. You should select small or a size approaching 50 x 50 pixels for correct visual rendering.' mod='gsnippetsreviews'}">
							<strong>{l s='Set default image type for products' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<select name="bt_products-img-type" id="bt_products-img-type" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							{foreach from=$aImgTypes name=productType key=key item=aTypes}
								<option value="{$aTypes.name|escape:'htmlall':'UTF-8'}" {if $sProductImgType == $aTypes.name}selected="selected"{/if}>{$aTypes.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The e-mails will contain a photo of each product. Please select the image size. You should select small or a size approaching 50 x 50 pixels for correct visual rendering.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, this will allow you to receive an e-mail notification when a new review / rating is posted or a review is reported as an abuse.' mod='gsnippetsreviews'}">
							<strong>{l s='Receive an alert by email' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-email" id="bt_enable-email_on" value="1" {if !empty($bEnableEmail)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-email', null, null, true, true);"  />
							<label for="bt_enable-email_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_enable-email" id="bt_enable-email_off" value="0" {if empty($bEnableEmail)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-email', null, null, true, false);" />
							<label for="bt_enable-email_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, this will allow you to receive an e-mail notification when a new review / rating is posted or a review is reported as an abuse.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div class="form-group" id="bt_div-email" style="display: {if !empty($bEnableEmail)}block{else}none{/if};">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Enter your email address for notifications' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-envelope"></i></span>
							<input type="text" id ="bt_email" name="bt_email" value="{if !empty($sEmail)}{$sEmail|escape:'htmlall':'UTF-8'}{/if}" />
						</div>
					</div>
				</div>
			{/if}

			{************ REVIEW LITIGATION REPLY E-MAIL SETTINGS ************}
			{if !empty($sDisplay) && $sDisplay == 'litigation'}
				<h3 class="subtitle">{l s='Review litigation e-mails' mod='gsnippetsreviews'}</h3>

				{if !empty($bUpdate)}
					<div class="clr_10"></div>
					{include file="`$sConfirmInclude`"}
				{elseif !empty($aErrors)}
					<div class="clr_10"></div>
					{include file="`$sErrorInclude`"}
				{/if}

				<div class="clr_10"></div>

				<div class="alert alert-info">
					{l s='There are times when your customers will leave unfair or inadequate reviews, and you will want to have a chance to contact the customer and try to convince him / her to modify his / her rating and / or review. To save you time, this section allows you to predefine the text of the e-mail subject and main content. The main content can then of course be personalized on a case by case basis when you reply to a customer review' mod='gsnippetsreviews'}.
				</div>

				<div class="clr_20"></div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This defines the subject of the e-mail that customer will receive when you send a review litigation reply' mod='gsnippetsreviews'}">
							<strong>{l s='Subject of the e-mail' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						{foreach from=$aLangs item=aLang}
							<div id="bt_replies-email-title_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<input type="text" id="bt_email-reply-title_{$aLang.id_lang|intval}" name="bt_email-reply-title_{$aLang.id_lang|intval}" {if !empty($aReplyEmailSubject)}{foreach from=$aReplyEmailSubject key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
									<ul class="dropdown-menu">
										{foreach from=$aLangs item=aLang}
											<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
										{/foreach}
									</ul>
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This defines the subject of the e-mail that customer will receive when you send a review litigation reply' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>
						{/foreach}
					</div>
				</div>

				<div class="form-group ">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This allows you to set a predefined message that will constitute the body of the e-mail. You will of course be able to personalize it on a case by case basis when you reply to a customer review' mod='gsnippetsreviews'}.">
							<strong>{l s='Default content of the e-mail' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						{foreach from=$aLangs item=aLang}
							<div id="bt_replies-email-text_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<textarea id="bt_email-reply-text_{$aLang.id_lang|intval}" rows="10" name="bt_email-reply-text_{$aLang.id_lang|intval}">{if !empty($aReplyEmailText)}{foreach from=$aReplyEmailText key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang}{$sLangTitle|escape:'htmlall':'UTF-8'}{/if}{/foreach}{/if}</textarea>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
									<ul class="dropdown-menu">
										{foreach from=$aLangs item=aLang}
											<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
										{/foreach}
									</ul>
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This allows you to set a predefined message that will constitute the body of the e-mail. You will of course be able to personalize it on a case by case basis when you reply to a customer review' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>
						{/foreach}
					</div>
				</div>
			{/if}

			{************ REMINDERS SETTINGS ************}
			{if !empty($sDisplay) && $sDisplay == 'reminder'}
				<h3>{l s='Reminders settings' mod='gsnippetsreviews'}</h3>

				{if !empty($bUpdate)}
					<div class="clr_10"></div>
					{include file="`$sConfirmInclude`"}
				{elseif !empty($aErrors)}
					<div class="clr_10"></div>
					{include file="`$sErrorInclude`"}
				{/if}

				<div class="clr_10"></div>

				{* USE CASE -  check if the full review system is activated and the ratings system is activated *}
				{if !empty($bDisplayReviews) && !empty($bEnableRatings)}
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product' mod='gsnippetsreviews'}">
							<strong>{l s='Send a review reminder email to customers' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" id="bt_enable-callback-div">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-callback" id="bt_enable-callback_on" value="1" {if !empty($bEnableCallback)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_callback', null, null, true, true);"  />
							<label for="bt_enable-callback_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_enable-callback" id="bt_enable-callback_off" value="0" {if empty($bEnableCallback)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_callback', null, null, true, false);" />
							<label for="bt_enable-callback_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
						<a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=107&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ: How do I test my reminder e-mails?' mod='gsnippetsreviews'}</a>
					</div>
				</div>

				<div id="bt_callback" style="display: {if !empty($bEnableCallback)}block{else}none{/if};">
					<div class="clr_20"></div>

					<h4 class="subtitle">{l s='Want to get reviews fast ? Start by inviting all your past customers to review their products below' mod='gsnippetsreviews'}</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div id="bt_orders-import-div">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
								<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='Please select a period. All orders placed during the period will cause the corresponding customers to receive an invitation e-mail' mod='gsnippetsreviews'}".>
									<strong>{l s='Select orders placed between these dates' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<div class="input-group">
										<span class="input-group-addon">{l s='From' mod='gsnippetsreviews'}:</span>
										<input type="text" class="datepicker input-medium" name="bt_order-date-from" value="" id="bt_order-date-from">
										<span class="input-group-addon">{l s='To:' mod='gsnippetsreviews'}</span>
										<input type="text" class="datepicker input-medium" name="bt_order-date-to" value="{$sToday|escape:'htmlall':'UTF-8'}" id="bt_order-date-to">
										<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
									</div>
								</div>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please select a period. All orders placed during the period will cause the corresponding customers to receive an invitation e-mail' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
								&nbsp;<input type="button" name="bt_orders-select-button" value="{l s='Preview sending' mod='gsnippetsreviews'}" class="btn btn-success" onclick="loadOrdersImport();return false;" />
								&nbsp;<a id="bt_display-orders-popup" class="fancybox.ajax"  href="{$sURI|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.displayOrders.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.displayOrders.type|escape:'htmlall':'UTF-8'}"></a>

								<div class="clr_10"></div>
								<div class="alert alert-info">
									<strong style="color: red; font-weight: bold;">{l s='IMPORTANT NOTE:' mod='gsnippetsreviews'}</strong> {l s='You have just installed or updated our module and you wish you could invite all your customers from past orders to post a rating and review ? Select a period above (we recommend not selecting more than 3 months) and the e-mails will go out instantly. This is a great way to quickly start populating your website with customer reviews. You can do this several times, but we recommend you space out each batch by at least one week, and do it no more than 3 times total.' mod='gsnippetsreviews'}
								</div>
							</div>
						</div>

						<div class="form-group" id="bt_orders-select-error" style="display: none;">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
								<div class="alert alert-danger ">
									<button type="button" class="close" onclick="$('#bt_orders-select-error').slideUp();">×</button>
									{l s='The date is still empty, you should select a date first' mod='gsnippetsreviews'}.
								</div>
							</div>
						</div>
					</div>

					<div class="clr_10"></div>

					<h4 class="subtitle">{l s='Your CRON Url for the automated batch to send reminders' mod='gsnippetsreviews'}</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="">
								<strong>{l s='Your CRON URL to call' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
							<input type="text" id ="bt_reminder-url" name="bt_reminder-url" placeholder="{if !empty($sCronUrl)}http://{$sCronUrl|escape:'htmlall':'UTF-8'}{else}{$smarty.const._PS_BASE_URL_|escape:'htmlall':'UTF-8'}{/if}{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}{$smarty.const._GSR_MODULE_SET_NAME|escape:'htmlall':'UTF-8'}/cron.php?bt_key={$sSecureKey|escape:'htmlall':'UTF-8'}" value="{if !empty($sCronUrl)}http://{$sCronUrl|escape:'htmlall':'UTF-8'}{else}{$smarty.const._PS_BASE_URL_|escape:'htmlall':'UTF-8'}{/if}{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}{$smarty.const._GSR_MODULE_SET_NAME|escape:'htmlall':'UTF-8'}/cron.php?bt_key={$sSecureKey|escape:'htmlall':'UTF-8'}" />
							<div class="clr_10"></div>
							<div class="alert alert-info">
								<strong style="color: red; font-weight: bold;">{l s='IMPORTANT NOTE:' mod='gsnippetsreviews'}</strong> {l s='This requires to set a CRON task on your server. Please refer to the included PDF documentation (link in "Help" tab above) for detailed instructions.' mod='gsnippetsreviews'}
							</div>
						</div>
					</div>

					<div class="clr_10"></div>

					<h4 class="subtitle">{l s='General Reminders settings' mod='gsnippetsreviews'}</h4>

					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, when a customer receive the reminder e-mail, you can receive this e-mail either as blind carbon copy' mod='gsnippetsreviews'}">
								<strong>{l s='Receive a carbon copy of each e-mail sent' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_enable-carbon-copy" id="bt_enable-carbon-copy_on" value="1" {if !empty($bEnableCarbonCopy)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-carbon-copy-email', null, null, true, true);" />
								<label for="bt_enable-carbon-copy_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_enable-carbon-copy" id="bt_enable-carbon-copy_off" value="0" {if empty($bEnableCarbonCopy)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-carbon-copy-email', null, null, true, false);" />
								<label for="bt_enable-carbon-copy_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, when a customer receive the reminder e-mail, you can receive this e-mail either as blind carbon copy' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
						</div>
					</div>

					<div class="form-group" id="bt_div-carbon-copy-email" style="display: {if !empty($bEnableCarbonCopy)}block{else}none{/if};">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Enter your email address for reminder notifications' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-envelope"></i></span>
								<input type="text" id ="bt_carbon-copy-email" name="bt_carbon-copy-email" size="35" value="{if !empty($sCarbonCopyMail)}{$sCarbonCopyMail|escape:'htmlall':'UTF-8'}{/if}" />
							</div>

							<div class="clr_20"></div>

							<div class="alert alert-warning">
								<strong style="color: red; font-weight: bold;">{l s='IMPORTANT NOTE:' mod='gsnippetsreviews'}</strong> {l s='You need to understand once this option is activated, you would receive a lot e-mails according to your daily orders placed on your shop. After a while, once your e-mails sent have been checked on your own, you should deactivate it' mod='gsnippetsreviews'}.
							</div>
						</div>
					</div>

					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.' mod='gsnippetsreviews'}">
								<strong>{l s='Email reminder subject' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							{foreach from=$aLangs item=aLang}
								<div id="bt_div-tab-email-title_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
									<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
										<input type="text" id="bt_email-reminder-title_{$aLang.id_lang|intval}" name="bt_email-reminder-title_{$aLang.id_lang|intval}" {if !empty($aEmailSubject)}{foreach from=$aEmailSubject key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
									</div>
									<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
										<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
										<ul class="dropdown-menu">
											{foreach from=$aLangs item=aLang}
												<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
											{/foreach}
										</ul>
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You can customize the subject of the e-mail here. If you wish to customize the e-mail message itself, you will need to manually edit the files in the "mails" folder inside the "gsnippetsreviews" module folder, for each language, both the text and the HTML version each time.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
									</div>
								</div>
							{/foreach}
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This sections allows you to decide which labels and sentence you would have in the body text of each product included into the reminder e-mail' mod='gsnippetsreviews'}".>
								<strong>{l s='Custom product detail text' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							{foreach from=$aLangs item=aLang}
								<div id="bt_tab-email-body-text_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<input type="text" id="bt_email-category-label_{$aLang.id_lang|intval}" name="bt_email-category-label_{$aLang.id_lang|intval}" {if !empty($aEmailCategoryLabel)}{foreach from=$aEmailCategoryLabel key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<input type="text" id="bt_email-product-label_{$aLang.id_lang|intval}" name="bt_email-product-label_{$aLang.id_lang|intval}" {if !empty($aEmailProductLabel)}{foreach from=$aEmailProductLabel key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4">
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-11">
											<input type="text" id="bt_email-sentence_{$aLang.id_lang|intval}" name="bt_email-sentence_{$aLang.id_lang|intval}" {if !empty($aEmailSentence)}{foreach from=$aEmailSentence key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
										</div>
										&nbsp;<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please note how this text is used and placed into the reminder e-mail text close to each product details by clicking on preview below' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>&nbsp;
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
										<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
										<ul class="dropdown-menu">
											{foreach from=$aLangs item=aLang}
												<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
											{/foreach}
										</ul>
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This sections allows you to decide which labels and sentence you would have in the body text of each product included into the reminder e-mail' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
									</div>
								</div>
							{/foreach}
							<div class="clr_10"></div>
							<!-- Button trigger modal -->
							<a href="#" data-toggle="modal" data-target="#modal_reminder_preview"><span class="icon-eye-open">&nbsp;</span>{l s='Click here to show a preview' mod='gsnippetsreviews'}</a>
							<!-- Modal -->
							<div class="modal fade" id="modal_reminder_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content modal-lg">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='gsnippetsreviews'}</span></button>
											<h4 class="modal-title" id="myModalLabel">{l s='Preview' mod='gsnippetsreviews'}</h4>
										</div>
										<div class="modal-body">
											<div class="center"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/screenshot-reminder-email.jpg" width="700" height="546"></div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-info" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input type="hidden" id="bCheckStatus" name="bCheckStatus" value="1" />
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3" for="bt_order-statuses">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='We recommend you check at least one status considered as a valid order in your back-office. Only orders with one of the checked statuses above will receive a review reminder e-mail.' mod='gsnippetsreviews'}">
								<strong>{l s='Order statuses for reminders' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
							<div class="btn-actions">
								<div class="btn btn-default btn-mini" id="categoryCheck" onclick="return oGsr.selectAll('.myCheckbox', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gsnippetsreviews'}</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGsr.selectAll('.myCheckbox', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gsnippetsreviews'}</div>
								<div class="clr_10"></div>
							</div>
							<table cellspacing="0" cellpadding="0" class="table table-responsive table-bordered table-striped">
								{foreach from=$aOrderStatusTitle key=id item=aOrder}
									<tr>
										<td>
											<label style="float: right !important;" for="bt_order-status">{$aOrder[$iCurrentLang]|escape:'htmlall':'UTF-8'}</label>
										</td>
										<td>
											<input type="checkbox" name="bt_order-status[]" id="bt_order-status" value="{$id|escape:'htmlall':'UTF-8'}"{if !empty($aStatusSelection)}{foreach from=$aStatusSelection key=key item=iIdSelect}{if $iIdSelect == $id} checked="checked"{/if}{/foreach}{/if} class="myCheckbox" />
										</td>
									</tr>
								{/foreach}
							</table>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The review reminder e-mail will be sent X days after reaching the order\'s adding date + delay you set here, and of course if the order\'s state has reached one of the above statuses. So, for example, if you set it to Payment Accepted, this should probably be 7 days so there is enough time for you to prepare and ship the order. But if you set it to Shipped, then this might be 3 or 4 days, which is enough time for the actual shipment to be transported to its destination' mod='gsnippetsreviews'}.">
								<strong>{l s='Delay for sending reminder email' mod='gsnippetsreviews'}</strong>
							</span> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<input type="text" size="2" maxlength="2" name="bt_delay-email" id ="bt_delay-email" value="{if isset($iDelayEmail)}{$iDelayEmail|intval}{/if}" />&nbsp;({l s='days' mod='gsnippetsreviews'})
							</div>
							<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The review reminder e-mail will be sent X days after reaching the order\'s adding date + delay you set here, and of course if the order\'s state has reached one of the above statuses. So, for example, if you set it to Payment Accepted, this should probably be 7 days so there is enough time for you to prepare and ship the order. But if you set it to Shipped, then this might be 3 or 4 days, which is enough time for the actual shipment to be transported to its destination' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span>&nbsp;</span>
								<a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=125&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ: How to fill my delay according to statuses?' mod='gsnippetsreviews'}</a>
							</div>
						</div>
					</div>

					{* use case - display cron report *}
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='You can check your last emails reminder cron job' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<a id="cronReport" class="btn btn-warning" data-toggle="modal" href="{$sURI|escape:'htmlall':'UTF-8'}&sAction=display&sType=cronReport" data-target="#modalCronReport">{l s='Click here' mod='gsnippetsreviews'}</a>
							<div class="modal fade" id="modalCronReport" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
								{if !empty($bPsVersion1606)}
								<div class="modal-dialog">
									<div class="modal-content">
									</div>
								</div>
								{/if}
							</div>

							{if isset($bwritableReport) && $bwritableReport == false}
							<div class="clr_20"></div>
							<div class="alert alert-danger"><strong>{$sReportFile|escape:'htmlall':'UTF-8'}</strong> => {l s='The log report file is not writable, please check the file permission via your FTP' mod='gsnippetsreviews'}.</div>
							{/if}
						</div>
					</div>
					<div class="clr_20"></div>
				</div>
				{else}
					<div class="alert alert-danger">{l s='You have deactivated the full review system or the ratings feature alone. Please note you cannot configure this section if your customers won\'t be able to add a rating at least'  mod='gsnippetsreviews'}.</div>
				{/if}
			{/if}

			<div class="clr_20"></div>
			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<div id="bt_error-email-{$sDisplay|escape:'htmlall':'UTF-8'}"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-email-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-email-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-email-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, null, 'email-{$sDisplay|escape:'htmlall':'UTF-8'}', 'email');return false;"><i class="process-icon-save"></i>{l s='Update' mod='gsnippetsreviews'}</button></div>
			</div>
		</form>

		<div class="clr_20"></div>

		{literal}
		<script type="text/javascript">
			// activate select all option in status features
			{/literal}oGsr.selectAll('bt_order-status-all', '.myCheckbox'){literal};

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
					var sHref = '{/literal}{$sURI|escape:'UTF-8'}&sAction={$aQueryParams.displayOrders.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.displayOrders.type|escape:'htmlall':'UTF-8'}{literal}&dateFrom='+encodeURI($('#bt_order-date-from').val())+'&dateTo='+encodeURI($('#bt_order-date-to').val());
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
		{/literal}
	{/if}
</div>