{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	{* HEADER *}
	{include file="`$sHeaderInclude`"  bContentToDisplay=true}
	{* /HEADER *}

	<script type="text/javascript" src="{$sRatingJs|escape:'htmlall':'UTF-8'}"></script>
	<link rel="stylesheet" type="text/css" href="{$sRatingCss|escape:'htmlall':'UTF-8'}">

	<div class="clr_20"></div>

	<div>
		<img class="bt-effect image image-responsive" src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/banner.png" width="350" height="60" alt="{l s='Customer Ratings and Reviews Pro + Google Rich Snippets' mod='gsnippetsreviews'}" />
	</div>

	<div class="clr_10"></div>

	{literal}
	<script>
		var languages = new Array();
		{/literal}
		{foreach $aLangs as $k => $language}
		{literal}
		languages[{/literal}{$k|intval}{literal}] = {
			id_lang: {/literal}{$language.id_lang|intval}{literal},
			iso_code: '{/literal}{$language.iso_code|escape:'htmlall':'UTF-8'}{literal}',
			name: '{/literal}{$language.name|escape:'htmlall':'UTF-8'}{literal}',
			is_default: '{/literal}{if $iDefaultLang == $language.id_lang}1{else}0{/if}{literal}'
		};
		{/literal}
		{/foreach}
		{literal}
		// we need allowEmployeeFormLang var in ajax request
		allowEmployeeFormLang = {/literal}{$iCurrentLang|intval}{literal};
		displayFlags(languages, id_language, allowEmployeeFormLang);
	</script>
	{/literal}

	<div class="clr_20"></div>

	<div id="bt_block-tab">
		{* START LEFT MENU *}
		<div class="row">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
				<div class="list-group workTabs">
					<a class="list-group-item active" id="tab-1"><span class="icon-star"></span>&nbsp;&nbsp;{l s='Moderation' mod='gsnippetsreviews'}</a>
					<a class="list-group-item" id="tab-2"><span class="icon-pencil"></span>&nbsp;&nbsp;{l s='Add a review' mod='gsnippetsreviews'}</a>
				</div>

				{* more tools *}
				<div class="list-group">
					<a class="list-group-item documentation" target="_blank" href="{$smarty.const._GSR_GOOGLE_SNIPPETS_TOOL|escape:'htmlall':'UTF-8'}"><span class="icon-file"></span>&nbsp;&nbsp;{l s='GOOGLE RICH SNIPPETS TEST TOOL' mod='gsnippetsreviews'}</a>
					<a class="list-group-item documentation" target="_blank" href="{$sDocUri|escape:'htmlall':'UTF-8'}{$sDocName|escape:'htmlall':'UTF-8'}"><span class="icon-file"></span>&nbsp;&nbsp;{l s='Documentation' mod='gsnippetsreviews'}</a>
					<a class="list-group-item" target="_blank" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}?module=23&lg={$sCurrentIso|escape:'htmlall':'UTF-8'}"><span class="icon-info-circle"></span>&nbsp;&nbsp;{l s='Online FAQ' mod='gsnippetsreviews'}</a>
					<a class="list-group-item" target="_blank" href="{$sContactUs|escape:'htmlall':'UTF-8'}"><span class="icon-ambulance"></span>&nbsp;&nbsp;{l s='Contact support' mod='gsnippetsreviews'}</a>
				</div>

				{* rate *}
				<div class="list-group">
					<a class="list-group-item" target="_blank" href="{$sRateUrl|escape:'htmlall':'UTF-8'}"><i class="icon-star" style="color: #fbbb22;"></i>&nbsp;&nbsp;{l s='Rate me' mod='gsnippetsreviews'}</a>
				</div>

				{* module version *}
				<div class="list-group"">
				<a class="list-group-item" href="#"><span class="icon icon-info"></span>&nbsp;&nbsp;{l s='Version' mod='gsnippetsreviews'} : {$sModuleVersion|escape:'htmlall':'UTF-8'}</a>
			</div>
		</div>
		{* END LEFT MENU *}

		{* START TAB CONTENT *}
		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
			<div class="tab-content">
				{* USE CASE - NOT NEED TO HIDE CONFIGURATION *}
				{if empty($bHideConfiguration)}
					{* MODERATION SETTINGS *}
					<div id="content-tab-1" class="tab-pane panel active">
						<div id="bt_settings-moderation">
							{include file="`$sModerationInclude`"}
						</div>
					</div>

					<div id="bt_loading-div-moderation" style="display: none;">
						<div class="alert alert-info">
							<p class="center"><img src="{$sLoader|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
							<p class="center">{l s='Reviews are in progress (Facebook wall posts and vouchers can take a long time ) ...' mod='gsnippetsreviews'}</p>
						</div>
					</div>
					{* /MODERATION SETTINGS *}

					{* ADD SETTINGS *}
					<div id="content-tab-2" class="tab-pane panel">
						<div id="bt_settings-add">
							{include file="`$sReviewAddInclude`"}
						</div>
					</div>

					<div id="bt_loading-div-add" style="display: none;">
						<div class="alert alert-info">
							<p class="center"><img src="{$sLoader|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
							<p class="center">{l s='Your review is in progress. Do not forget that a review could already have been done for this customer and product' mod='gsnippetsreviews'}</p>
						</div>
					</div>
					{* /ADD SETTINGS *}
				{* USE CASE - ERRORS HAVE BEEN RETURNED AFTER THE UPDATE *}
				{elseif !empty($aUpdateErrors)}
					<div id="content-tab-1111" class="tab-pane panel in active information">
						<div class="alert alert-danger">
							{foreach from=$aUpdateErrors name=condition key=nKey item=aError}
								<h3>{l s='An error occured while SQL was executed for ' mod='gsnippetsreviews'} {if isset($aError.table)}{l s='table' mod='gsnippetsreviews'} "{$aError.table|escape:'htmlall':'UTF-8'}" {else}{l s='field' mod='gsnippetsreviews'} "{$aError.field|escape:'htmlall':'UTF-8'}" {l s='in table' mod='gsnippetsreviews'} "{$aError.linked|escape:'htmlall':'UTF-8'}"{/if} </h3>
								<ol>
									<li>{l s='SQL file' mod='gsnippetsreviews'} : {$aError.file|escape:'htmlall':'UTF-8'}</li>
								</ol>
							{/foreach}
							<p>{l s='Please reload this page for trying again to update SQL tables and fields or see with your web hosting why you\'ve got a SQL error' mod='gsnippetsreviews'}.</p>
						</div>
					</div>
				{else}
					<div id="content-tab-1111" class="tab-pane panel in active information">
						<div class="alert alert-danger">
							{l s='The module\'s configuration will be available once you have deactivated the "Product comments" module by PrestaShop. There is a conflict between this module and ours, which prevents our module from working correctly on the front-office of your shop.' mod='gsnippetsreviews'}
						</div>
					</div>
				{/if}
			</div>
		</div>
		{* END TAB CONTENT *}
	</div>

	{literal}
	<script type="text/javascript">
		$(document).ready(function() {
			$('#content').removeClass('nobootstrap');
			$('#content').addClass('bootstrap');

			var sHash = $(location).attr('hash');
			if (sHash != null && sHash != '') {
				sHash = sHash.replace('#', '');
				$(".workTabs a[id='tab-1']").removeClass('active');
				$("#content-tab-1").hide();
				$(".workTabs a[id='tab-"+sHash+"']").addClass('active');
				$("#content-tab-"+sHash).show();
			}

			$(".workTabs a").click(function(e) {
				e.preventDefault();
				// currentId is the current workTabs id
				var currentId = $(".workTabs a.active").attr('id').substr(4);
				// id is the wanted workTabs id
				var id = $(this).attr('id').substr(4);

				if (id != currentId) {
					$(".workTabs a[id='tab-"+currentId+"']").removeClass('active');
					$("#content-tab-"+currentId).hide();
					$(".workTabs a[id='tab-"+id+"']").addClass('active');
					$("#content-tab-"+id).show();
				}
			});
			$(".workTabs a.active").click();

			$('.label-tooltip, .help-tooltip').tooltip();
			$('.dropdown-toggle').dropdown();
			{/literal}{if !empty($bDisplayAdvice)}{literal}
			$("a#bt_disp-advice").fancybox({
				'hideOnContentClick' : false
			});
			$('#bt_disp-advice').trigger('click');
			{/literal}{/if}{literal}
		});
	</script>
	{/literal}
</div>