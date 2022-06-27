{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="bt_review-form-edition">
		<script type="text/javascript">
		{literal}
			var oReviewFormCallback = [{
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

		<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post" id="bt_review-update-form" name="bt_review-update-form" onsubmit="oGsr.form('bt_review-update-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_review-form-edition', 'bt_review-form-edition', false, true, oReviewFormCallback, 'review', 'review');return false;">
			<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iReviewId" value="{$aReview.id|intval}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.moderationComment.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.moderationComment.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iLangId" value="{$aReview.data.iLangId|intval}" />
			<input type="hidden" name="sLangIso" value="{$aReview.data.sLangIso|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iPage" value="{$iPage|intval}" />
			<input type="hidden" id="bCheckFieldText" name="bCheckFieldText" value="1" />

			<h3>{l s='Modify Comment' mod='gsnippetsreviews'}</h3>

			{if !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Review\'s language' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 marginTop10px">
					{$aReview.langTitle|escape:'htmlall':'UTF-8'}
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Review\'s title' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<input type="text" value="{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}" name="bt_review-title" id="bt_review-title" />
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Review\'s comment' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<textarea name="bt_review-comment" id="bt_review-comment" rows="6">{$aReview.data.sComment|escape:'UTF-8'}</textarea>
				</div>
			</div>
		</form>
		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div id="bt_error-review"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="pull-right">
					<button class="btn btn-success btn-lg"  onclick="oGsr.form('bt_review-update-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_review-form-edition', 'bt_review-form-edition', false, true, oReviewFormCallback, 'review', 'review');">{l s='Modify' mod='gsnippetsreviews'}</button> -
					<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
				</div>
			</div>
		</div>
	</div>
	<div id="bt_loading-div-review" style="display: none;">
		<div class="clr_10"></div>
		<div class="alert alert-info">
			<p class="center"><img src="{$sLoader|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p class="center">{l s='Reply is in progress ...' mod='gsnippetsreviews'}</p>
		</div>
	</div>
</div>