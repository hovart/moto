{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<link rel="stylesheet" type="text/css" href="{$smarty.const._GSR_URL_CSS|escape:'htmlall':'UTF-8'}admin.css">
<link rel="stylesheet" type="text/css" href="{$smarty.const._GSR_BT_API_MAIN_URL|escape:'htmlall':'UTF-8'}css/styles.css?ts={$sTs|escape:'htmlall':'UTF-8'}">

<script type="text/javascript" src="{$smarty.const._GSR_URL_JS|escape:'htmlall':'UTF-8'}module.js"></script>
<script type="text/javascript">
	// instantiate object
	var oGsr = oGsr || new GsrModule('{$sModuleName|escape:'htmlall':'UTF-8'}');

	// get errors translation
	oGsr.msgs = {$oJsTranslatedMsg};

	// set URL of admin img
	oGsr.sImgUrl = '{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}';

	{if !empty($sModuleURI)}
	// set URL of module's web service
	oGsr.sWebService = '{$sModuleURI|escape:'UTF-8'}';
	{/if}
</script>