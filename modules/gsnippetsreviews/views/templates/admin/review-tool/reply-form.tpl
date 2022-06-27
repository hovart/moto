{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="bt_reply-edition">
		<script type="text/javascript">
			{literal}
			var oReplyFormCallback = [{
				'name' : 'moderationList',
				'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
				'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction=display&sType=moderation',
				'toShow' : 'bt_settings-moderation',
				'toHide' : 'bt_settings-moderation',
				'bFancybox' : false,
				'bFancyboxActivity' : false,
				'sLoadbar' : null,
				'sScrollTo' : null,
				'oCallBack' : {}
			}];
			{/literal}
		</script>
		<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post" id="bt_reply-form" name="bt_reply-form" onsubmit="oGsr.form('bt_reply-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_reply-edition', 'bt_reply-edition', false, true, oReplyFormCallback, 'reply', 'reply', null);return false;">
			<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iRatingId" value="{$aRating.id|intval}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.moderationReply.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.moderationReply.type|escape:'htmlall':'UTF-8'}" />
			{if !empty($aRating.replyData)}
				<input type="hidden" name="bt_reply-action" id="bt_reply-action" value="update" />
				<input type="hidden" name="bt_reply-comment" id="bt_reply-comment" value="{$aRating.replyData.sComment|escape:'UTF-8'}" />
			{else}
				<input type="hidden" name="bt_reply-action" id="bt_reply-action" value="add" />
			{/if}
			<input type="hidden" name="bt_nb-reply" id="bt_nb-reply" value="{if !empty($aRating.replyData.iCounter)}{$aRating.replyData.iCounter|intval}{else}1{/if}" />

			<h3>{l s='Reply to review' mod='gsnippetsreviews'}</h3>

			{if !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_hr"></div>

			{if !empty($aRating.data.iOldRating) || (!empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment))}
			<div class="clr_20"></div>
			<div class="alert alert-info">{l s='The customer has replied to your review litigation reply, and has changed his rating and / or review' mod='gsnippetsreviews'}</div>
			{/if}

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Click on the customer name to see his profile' mod='gsnippetsreviews'}">
						<strong>{l s='Customer' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 marginTop5px">
					<span class="badge"><a style="color: #FFF !important;" href="{$aRating.customerLink|escape:'UTF-8'}" target="_blank">{$aRating.firstname|capitalize|escape:'htmlall':'UTF-8'} {$aRating.lastname|capitalize|escape:'htmlall':'UTF-8'}</a></span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Click on the customer name to see his profile' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<strong>{l s='Review\'s rating' mod='gsnippetsreviews'}</strong> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 marginTop5px rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
					{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $iRating >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $iRating >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<strong>{l s='Review\'s language' mod='gsnippetsreviews'}</strong> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 marginTop5px">
					{$aRating.langTitle|escape:'htmlall':'UTF-8'}
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Review\'s title' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<input type="text" value="{if !empty($aRating.review.data.sTitle)}{$aRating.review.data.sTitle|escape:'htmlall':'UTF-8'}{else}N/A{/if}" name="bt_review-title" id="bt_review-title" disabled="disabled" />
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Review\'s comment' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<textarea name="bt_review-comment" id="bt_review-comment" rows="6" disabled="disabled">{if !empty($aRating.review.data.sTitle)}{$aRating.review.data.sComment|escape:'htmlall':'UTF-8'}{else}N/A{/if}</textarea>
				</div>
			</div>

			{if !empty($aRating.data.iOldRating) || (!empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment))}
			<p class="text-center">
	            <span onclick="$('#bt_old-review').slideToggle();" class="btn btn-info btn-lg">{l s='Old review' mod='gsnippetsreviews'}&nbsp;<span class="icon-eye-open"></span></span>
	        </p>

			<div id="bt_old-review" style="display: none;">
				<h3>{l s='Old review' mod='gsnippetsreviews'}</h3>
				<div class="clr_hr"></div>
				<div class="clr_10"></div>

				{if !empty($aRating.data.iOldRating)}
					<div class="form-group">
						<label for="inputTitle" class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<strong>{l s='Old rating' mod='gsnippetsreviews'}</strong> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<span style="float: left;" class="marginTop5px rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
								{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if !empty($aRating.data.iOldRating) && $aRating.data.iOldRating >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if !empty($aRating.data.iOldRating) && $aRating.data.iOldRating >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
							</span>
						</div>
					</div>
				{/if}
				{if !empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment)}
					<div class="clr_10"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<strong>{l s='Old title' mod='gsnippetsreviews'}</strong> :
						</label>
						<div class="control-label col-xs-12 col-sm-12 col-md-8 col-lg-8">
							<input name="bt_review-title" id="bt_review-title" class="form-control" type="text" value="{$aRating.review.data.sOldTitle|escape:'htmlall':'UTF-8'}" disabled="disabled" >
						</div>
					</div>

					<div class="clr_10"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<strong>{l s='Old comment' mod='gsnippetsreviews'}</strong> :
						</label>
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							<textarea class="form-control" name="bt_review-comment" id="bt_review-comment" style="height: 120px !important;" disabled="disabled" >{$aRating.review.data.sOldComment|escape:'UTF-8'}</textarea>
						</div>
					</div>
				{/if}
			</div>
			<div class="clr_10"></div>
			{/if}

			<h3>{l s='Write your reply' mod='gsnippetsreviews'}</h3>

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="alert alert-info">
				{l s='Below, you can type the reply that will be sent by e-mail to the customer who posted the review summarized above' mod='gsnippetsreviews'}.<br/>
				{l s='The standard text which you see below can be modified by going to the module\'s configuration, "Review e-mails > Review litigation e-mails"' mod='gsnippetsreviews'}.<br/>
				{l s='You can also decide to display your reply on the corresponding product page on your shop, below the customer\'s rating / review' mod='gsnippetsreviews'}.
			</div>

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<strong>{l s='Your response' mod='gsnippetsreviews'}</strong> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 marginTop5px">
					<textarea name="bt_reply-comment" id="bt_reply-comment" rows="6" {if !empty($aRating.replyData)}disabled="disabled"{/if}>{if !empty($aRating.replyData.sComment)}{$aRating.replyData.sComment|escape:'UTF-8'}{elseif !empty($sReplyEmailText)}{$sReplyEmailText|escape:'UTF-8'}{/if}</textarea>
				</div>
			</div>

			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<strong>{l s='Display your reply on product page' mod='gsnippetsreviews'}</strong> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_display-reply" id="bt_display-reply_on" value="1" {if !empty($aRating.replyDisplay)}checked="checked"{/if} />
					<label for="bt_display-reply_on" class="radioCheck">
						{l s='Yes' mod='gsnippetsreviews'}
					</label>
					<input type="radio" name="bt_display-reply" id="bt_display-reply_off" value="0" {if empty($aRating.replyDisplay)}checked="checked"{/if} />
					<label for="bt_display-reply_off" class="radioCheck">
						{l s='No' mod='gsnippetsreviews'}
					</label>
					<a class="slide-button btn"></a>
				</span>
			</div>

			{if !empty($aRating.replyData) && (empty($aRating.data.iOldRating) || (empty($aRating.review.data.sOldTitle) && empty($aRating.review.data.sOldComment)))}
				<div class="clr_20"></div>

				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you believe the customer did not receive your reply by e-mail the first time, you may send it again' mod='gsnippetsreviews'}.">
						<strong>{l s='Send your reply by e-mail again to the customer' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_send-reply" id="bt_send-reply_on" value="1" />
						<label for="bt_send-reply_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_send-reply" id="bt_send-reply_off" checked="checked" value="0" />
						<label for="bt_send-reply_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you believe the customer did not receive your reply by e-mail the first time, you may send it again' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>

				<div class="clr_20"></div>

				<div class="alert alert-warning">
					{if !empty($aRating.replyData.iCounter)}
						{l s='Be careful, your response has already been sent' mod='gsnippetsreviews'} <strong>{$aRating.replyData.iCounter|intval}</strong> {l s='time(s)' mod='gsnippetsreviews'}<br/>
					{/if}
					{l s='NOTE: once you have already replied, the text can no longer be modified' mod='gsnippetsreviews'}.
				</div>
			{/if}
		</form>
		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div id="bt_error-reply"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="pull-right">
					<button class="btn btn-success btn-lg"  onclick="oGsr.form('bt_reply-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_reply-edition', 'bt_reply-edition', false, true, oReplyFormCallback, 'reply', 'reply', null);">{if empty($aRating.replyData)}{l s='Add' mod='gsnippetsreviews'}{else}{l s='Modify' mod='gsnippetsreviews'}{/if}</button> -
					<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
				</div>
			</div>
		</div>
	</div>

	<div id="bt_loading-div-reply" style="display: none;">
		<div class="clr_10"></div>
		<div class="alert alert-info">
			<p class="center"><img src="{$sLoader|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p class="center">{l s='Reply is in progress ...' mod='gsnippetsreviews'}</p>
		</div>
	</div>

	{literal}
	<script type="text/javascript">
		$('.label-tooltip, .help-tooltip').tooltip();
	</script>
	{/literal}
</div>