{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div id='{$sModuleName|escape:'htmlall':'UTF-8'}' class="bootstrap form">
	{* HEADER *}
	{include file="`$sHeaderInclude`"  bContentToDisplay=true}
	{* /HEADER *}

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
					<a class="list-group-item active" id="tab-0"><span class="icon-home"></span>&nbsp;&nbsp;{l s='Welcome' mod='gsnippetsreviews'}</a>
					{* start collapse *}
					<a class="list-group-item" id="tab-001" data-toggle="collapse" href="#submenu-snippets"><span class="icon-chevron-left">&nbsp;</span><span class="icon-chevron-right">&nbsp;</span>&nbsp;{l s='Snippets' mod='gsnippetsreviews'}<span class="pull-right"><i class="icon-caret-down"></i></span></a>
					<div id="submenu-snippets" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-001"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Google Rich Snippets' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-002"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Review Snippets' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-003"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Badges' mod='gsnippetsreviews'}</a>
					</div>
					<a class="list-group-item" id="tab-010" data-toggle="collapse" href="#submenu-reviews"><span class="icon-star">&nbsp;</span>{l s='Reviews' mod='gsnippetsreviews'}<span class="pull-right"><i class="icon-caret-down"></i></span></a>
					<div id="submenu-reviews" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-010"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Global' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-011"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Reviews management' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-012"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Product Page' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-013"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Account Review Page' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-014"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Last Reviews Block' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-015"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Stars in list pages' mod='gsnippetsreviews'}</a>
					</div>
					<a class="list-group-item" id="tab-020" data-toggle="collapse" href="#submenu-voucher"><span class="icon-pencil">&nbsp;</span>{l s='Review E-mails' mod='gsnippetsreviews'}<span class="pull-right"><i class="icon-caret-down"></i></span></a>
					<div id="submenu-voucher" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-020"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Review e-mails' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-021"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Review litigation e-mails' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-022"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Reminders' mod='gsnippetsreviews'}</a>
					</div>
					<a class="list-group-item" id="tab-1"><span class="icon-align-justify"></span>&nbsp;&nbsp;{l s='Voucher Incentives' mod='gsnippetsreviews'}</a>
					<a class="list-group-item" id="tab-030" data-toggle="collapse" href="#submenu-facebook"><span class="icon-thumbs-up">&nbsp;</span>{l s='Facebook Integration' mod='gsnippetsreviews'}<span class="pull-right"><i class="icon-caret-down"></i></span></a>
					<div id="submenu-facebook" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-030"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Voucher Incentives' mod='gsnippetsreviews'}</a>
						<a class="list-group-item" id="tab-031"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Post on Facebook' mod='gsnippetsreviews'}</a>
					</div>
				</div>

				{* moderation tools *}
				<div class="list-group">
					<a class="list-group-item" target="_blank" href="{$sAdmniTabUrl|escape:'htmlall':'UTF-8'}"><span class="icon-cog"></span>&nbsp;&nbsp;{l s='Moderate reviews' mod='gsnippetsreviews'}</a>
					<a class="list-group-item" target="_blank" href="{$sAdmniTabUrl|escape:'htmlall':'UTF-8'}#2"><span class="icon-plus"></span>&nbsp;&nbsp;{l s='Add reviews' mod='gsnippetsreviews'}</a>
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
				<div id="content-tab-0" class="tab-pane panel in active information">
					<h3><i class="icon icon-home"></i>&nbsp;{l s='Welcome' mod='gsnippetsreviews'}</h3>
					<div class="clr_10"></div>

					<div class="form-group">
						<div class="col-xs-6 col-md-6 col-lg-6">
							<div class="alert alert-success">{l s='Welcome and thank you for having purchased our module. Please read carefully the PDF documentation included with the module.' mod='gsnippetsreviews'}</div>
						</div>
					</div>
					<div class="clr_10"></div>
					<div class="clr_hr"></div>
					<div class="clr_10"></div>
					<div class="row">
						<div class="col-xs-12 col-sm-10 col-md-5 col-lg-5">
							<a target="blank" href="{$sCrossSellingUrl|escape:'htmlall':'UTF-8'}"><img class="bt-effect img-responsive" src="{$sCrossSellingImg|escape:'htmlall':'UTF-8'}"/></a>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"></div>
						<div class="col-xs-12 col-sm-10 col-md-6 col-lg-6">
							{include file="`$sWelcome`"}
						</div>
					</div>
				</div>

				{* SNIPPETS SETTINGS *}
				{* global rich snippets *}
				<div id="content-tab-001" class="tab-pane panel">
					<div id="bt_settings-snippets-snippet">
						{include file="`$sSnippetsInclude`" sDisplay="snippet"}
					</div>
				</div>

				{* review rich snippets *}
				<div id="content-tab-002" class="tab-pane panel">
					<div id="bt_settings-snippets-review">
						{include file="`$sSnippetsInclude`" sDisplay="review"}
					</div>
				</div>

				{* badges *}
				<div id="content-tab-003" class="tab-pane panel">
					<div id="bt_settings-snippets-badge">
						{include file="`$sSnippetsInclude`" sDisplay="badge"}
					</div>
				</div>

				<div id="bt_loading-div-snippets" style="display: none;">
					<div class="alert alert-info">
						<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
						<p class="center">{l s='Your update configuration is in progress' mod='gsnippetsreviews'}</p>
					</div>
				</div>
				{* /SNIPPETS SETTINGS *}

				{* REVIEWS SETTINGS *}
				{* global settings *}
				<div id="content-tab-010" class="tab-pane panel">
					<div id="bt_settings-review-global">
						{include file="`$sReviewsInclude`" sDisplay="global"}
					</div>
				</div>

				{* review settings *}
				<div id="content-tab-011" class="tab-pane panel">
					<div id="bt_settings-review-review">
						{include file="`$sReviewsInclude`" sDisplay="review"}
					</div>
				</div>

				{* product page settings *}
				<div id="content-tab-012" class="tab-pane panel">
					<div id="bt_settings-review-product">
						{include file="`$sReviewsInclude`" sDisplay="product"}
					</div>
				</div>

				{* account review page settings *}
				<div id="content-tab-013" class="tab-pane panel">
					<div id="bt_settings-review-account">
						{include file="`$sReviewsInclude`" sDisplay="account"}
					</div>
				</div>

				{* last reviews block settings *}
				<div id="content-tab-014" class="tab-pane panel">
					<div id="bt_settings-review-last">
						{include file="`$sReviewsInclude`" sDisplay="last"}
					</div>
				</div>

				{* review list settings *}
				<div id="content-tab-015" class="tab-pane panel">
					<div id="bt_settings-review-list">
						{include file="`$sReviewsInclude`" sDisplay="list"}
					</div>
				</div>

				<div id="bt_loading-div-review" style="display: none;">
					<div class="alert alert-info">
						<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
						<p class="center">{l s='Your update configuration is in progress' mod='gsnippetsreviews'}</p>
					</div>
				</div>
				{* /REVIEWS SETTINGS *}

				{* REVIEWS E-MAIL SETTINGS *}
				{* global e-mail settings *}
				<div id="content-tab-020" class="tab-pane panel">
					<div id="bt_settings-email-global">
						{include file="`$sEmailsInclude`" sDisplay="global"}
					</div>
				</div>

				{* reviews e-mail litigation *}
				<div id="content-tab-021" class="tab-pane panel">
					<div id="bt_settings-email-litigation">
						{include file="`$sEmailsInclude`" sDisplay="litigation"}
					</div>
				</div>

				{* reviews e-mail reminder settings *}
				<div id="content-tab-022" class="tab-pane panel">
					<div id="bt_settings-email-reminder">
						{include file="`$sEmailsInclude`" sDisplay="reminder"}
					</div>
				</div>

				<div id="bt_loading-div-email" style="display: none;">
					<div class="alert alert-info">
						<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
						<p class="center">{l s='Your update configuration is in progress' mod='gsnippetsreviews'}</p>
					</div>
				</div>
				{* /REVIEWS E-MAIL SETTINGS *}

				{* INCENTIVE VOUCHER SETTINGS *}
				<div id="content-tab-1" class="tab-pane panel">
					<div id="bt_settings-voucher">
						{include file="`$sVouchersInclude`"}
					</div>
					<div class="clr_20"></div>
					<div id="bt_loading-div-voucher" style="display: none;">
						<div class="alert alert-info">
							<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
							<p class="center">{l s='Your update configuration is in progress' mod='gsnippetsreviews'}</p>
						</div>
					</div>
				</div>
				{* /INCENTIVE VOUCHER SETTINGS *}

				{* FACEBOOK INTEGRATION SETTINGS *}
				{* global e-mail settings *}
				<div id="content-tab-030" class="tab-pane panel">
					<div id="bt_settings-fb-voucher">
						{include file="`$sFacebookInclude`" sDisplay="voucher"}
					</div>
				</div>

				{* reviews e-mail litigation *}
				<div id="content-tab-031" class="tab-pane panel">
					<div id="bt_settings-fb-post">
						{include file="`$sFacebookInclude`" sDisplay="post"}
					</div>
				</div>

				<div id="bt_loading-div-facebook" style="display: none;">
					<div class="alert alert-info">
						<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
						<p class="center">{l s='Your update configuration is in progress' mod='gsnippetsreviews'}</p>
					</div>
				</div>
				{* /FACEBOOK INTEGRATION SETTINGS *}

				{* USE CASE - ERRORS HAVE BEEN RETURNED AFTER THE UPDATE *}
				{elseif !empty($aUpdateErrors)}
				<div id="content-tab-1000" class="tab-pane panel in active information">
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
				<div id="content-tab-1000" class="tab-pane panel in active information">
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
				$(".workTabs a[id='tab-001']").removeClass('active');
				$("#content-tab-001").hide();
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

	{if !empty($bCommentsImport)}
	<a class="fancybox.ajax" id="bt_import-comment" href="{$sURI|escape:'htmlall':'UTF-8'}&sAction=display&sType=commentsImport"></a>
	{literal}
	<script>
		$(document).ready(function(){
			$("a#bt_import-comment").fancybox({
				'hideOnContentClick' : false
			});
			$("a#bt_import-comment").trigger('click');
		});
	</script>
	{/literal}
	{/if}
</div>