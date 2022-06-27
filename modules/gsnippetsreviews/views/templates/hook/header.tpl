{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{* CASE : HOOK *}
{if !empty($bOpenGraph) && !empty($aRating.review.data)}
<meta property="og:title" content="{l s='Customer review' mod='gsnippetsreviews'} : {$aRating.review.data.sTitle|escape:'htmlall':'UTF-8'}"/>
<meta property="og:type" content="product"/>
<meta property="og:image" content="{$aProduct.img|escape:'htmlall':'UTF-8'}"/>
<meta property="og:url" content="{$sURI|escape:'htmlall':'UTF-8'}"/>
<meta property="og:site_name" content="{$sShopName|escape:'htmlall':'UTF-8'}"/>
<meta property="og:description" content="{$aRating.review.data.sComment|escape:'UTF-8'}" />
{/if}

<script type="text/javascript" data-keepinline="true">
	// instantiate object
	{*{if !empty($bPS17)}*}
		var oGsr = {literal}{}{/literal};
		var bt_msgs = {$oJsTranslatedMsg nofilter};
		var bt_sImgUrl = '{$smarty.const._GSR_URL_IMG|escape:'UTF-8'}';
		var bt_sWebService = '{if !empty($sModuleURI)}{$sModuleURI|escape:'UTF-8'}{/if}';
		var sGsrModuleName = '{$sModuleName|escape:'htmlall':'UTF-8' nofilter}';
		var bt_aFancyReviewForm = {literal}{}{/literal};
		var bt_aFancyReviewTabForm = {literal}{}{/literal};
		var bt_oScrollTo = {literal}{}{/literal};
		bt_oScrollTo.execute = false;
		var bt_oUseSocialButton = {literal}{}{/literal};
		var bt_oActivateReviewTab = {literal}{}{/literal};
		bt_oActivateReviewTab.run = false;
		var bt_oDeactivateReviewTab = {literal}{}{/literal};
		bt_oDeactivateReviewTab.run = false;
		var bt_aReviewReport = new Array();
		var bt_oCallback = {literal}{}{/literal};
		bt_oCallback.run = false;
		var bt_aStarsRating = new Array();
		var bt_oBxSlider = {literal}{}{/literal};
		bt_oBxSlider.run = false;
	{*{else}*}
		{*var oGsr = oGsr || new GsrModule('{$sModuleName|escape:'htmlall':'UTF-8'}');*}
		{*// get errors translation*}
		{*oGsr.msgs = {$oJsTranslatedMsg nofilter};*}

		{*// set URL of admin img*}
		{*oGsr.sImgUrl = '{$smarty.const._GSR_URL_IMG|escape:'UTF-8'}';*}

		{*{if !empty($sModuleURI)}*}
		{*// set URL of module's web service*}
		{*oGsr.sWebService = '{$sModuleURI|escape:'UTF-8'}';*}
		{*{/if}*}
	{*{/if}*}
</script>