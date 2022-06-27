{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to http://doc.prestashop.com/display/PS15/Overriding+default+behaviors
* #Overridingdefaultbehaviors-Overridingamodule%27sbehavior for more information.
*
* @author Samdha <contact@samdha.net>
* @copyright  Samdha
* @license    commercial license see license.txt
*}
<script type="text/javascript">
	module.models_filename = [];
	module.models_shop = [];
	module.models_url = [];
	module.models_lang = [];
	module.models_currency = [];
	module.models_shop = [];
	module.exports_model = [];
	module.possible_fields = [];

	{foreach from=$models_filename key=key item=filename}
		module.models_filename['{$key|escape:'javascript':'UTF-8'}'] = '{$filename|strftime|escape:'javascript':'UTF-8'}';
	{/foreach}
	{foreach from=$models_shop key=key item=id_shop}
		module.models_shop['{$key|escape:'javascript':'UTF-8'}'] = {$id_shop|escape:'javascript':'UTF-8'};
	{/foreach}
	{foreach from=$models_url key=key item=url}
		module.models_url['{$key|escape:'javascript':'UTF-8'}'] = '{$url|escape:'javascript':'UTF-8'}';
	{/foreach}
	{foreach from=$models_lang key=key item=lang}
		module.models_lang['{$key|escape:'javascript':'UTF-8'}'] = '{$lang|escape:'javascript':'UTF-8'}';
	{/foreach}
	{foreach from=$models_currency key=key item=currency}
		module.models_currency['{$key|escape:'javascript':'UTF-8'}'] = '{$currency|escape:'javascript':'UTF-8'}';
	{/foreach}
	{foreach from=$models_shop key=key item=id_shop}
		module.models_shop['{$key|escape:'javascript':'UTF-8'}'] = {$id_shop|escape:'javascript':'UTF-8'};
	{/foreach}
	{foreach from=$exports_model key=key item=id_model}
		module.exports_model['{$key|escape:'javascript':'UTF-8'}'] = '{$id_model|escape:'javascript':'UTF-8'}';
	{/foreach}
	{foreach from=$possible_fields key=key item=field}
		module.possible_fields['{$key|escape:'javascript':'UTF-8'}'] = '{$field|escape:'javascript':'UTF-8'}';
	{/foreach}
	var messages = {ldelim}
		delete_confirmation : '{capture name=temp}{l s='Are you sure?' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		override_confirmation : '{capture name=temp}{l s='This will override an existing element. Are you sure?' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		missing_name : '{capture name=temp}{l s='A name is required.' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		missing_action : '{capture name=temp}{l s='You didn\'t choose an action.' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		done : '{capture name=temp}{l s='Done.' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		ajax_error : '{capture name=temp}{l s='Impossible to access to export.php. Please check if this file is in the module folder and its permissions.' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		value : '{capture name=temp}{l s='Value' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		separator : '{capture name=temp}{l s='Separator' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		select_all : '{capture name=temp}{l s='Select all items' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		unselect_all : '{capture name=temp}{l s='Unselect all items' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		selectable_header : '{capture name=temp}{l s='Selectable item' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}',
		selection_header : '{capture name=temp}{l s='Selection items' mod='export_catalog' js=1}{/capture}{$smarty.capture.temp|replace:'\\\'':'\''|escape:'javascript':'UTF-8'}'
	{rdelim};
</script>
<link rel="stylesheet" type="text/css" href="{$module_path|escape:'htmlall':'UTF-8'}views/css/admin.css?v={$module_version|escape:'htmlall':'UTF-8'}">
<link rel="stylesheet" type="text/css" href="{$module_path|escape:'htmlall':'UTF-8'}views/css/jqueryFileTree.css?v={$module_version|escape:'htmlall':'UTF-8'}">
<link rel="stylesheet" type="text/css" href="{$module_path|escape:'htmlall':'UTF-8'}views/css/multi-select.css?v={$module_version|escape:'htmlall':'UTF-8'}">
<script src="{$module_path|escape:'htmlall':'UTF-8'}views/js/jqueryFileTree.js?v={$module_version|escape:'htmlall':'UTF-8'}" type="text/javascript"></script>
<script src="{$module_path|escape:'htmlall':'UTF-8'}views/js/jquery.multi-select.js?v={$module_version|escape:'htmlall':'UTF-8'}" type="text/javascript"></script>
