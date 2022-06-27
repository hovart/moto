{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to http://doc.prestashop.com/display/PS15/Overriding+default+behaviors#Overridingdefaultbehaviors-Overridingamodule%27sbehavior for more information.
*
* @author Samdha <contact@samdha.net>
* @copyright  Samdha
* @license    commercial license see license.txt
*}
{if !$version_16}
<fieldset class="{if !$bootstrap}ui-widget ui-widget-content ui-corner-all{/if}{if $big} width3{else}{if $space} space{/if}"{/if} id="samdha_aboutform">
	<legend class="{if !$bootstrap}ui-widget-header ui-corner-all{/if}">{l s='About' mod='samdha'}</legend>
{/if}
	<p style="font-size: 1.5em; font-weight: bold; padding-bottom: 0"><img src="{$module_path|escape:'htmlall':'UTF-8'}logo.png" alt="{$module_display_name|escape:'htmlall':'UTF-8'}" style="float: left; padding-right: 1em"/>{$module_display_name|escape:'htmlall':'UTF-8'}</p>
	<p style="clear: left;">{l s='Thanks for installing this module on your website.' mod='samdha'}</p>
	{if $description_big_html}{$description_big_html}{else}<p>{$description|escape:'htmlall':'UTF-8'}</p>{/if}
	<p>
		{l s='Made with ❤ by' mod='samdha'} <a style="color: #7ba45b; text-decoration: underline;" href="{$home_url|escape:'htmlall':'UTF-8'}">Samdha</a>{l s=', which helps you develop your e-commerce site.' mod='samdha'}
	</p>
	<p>
		<span style="float: right; opacity: .2; font-size: 9px; padding-top: 5px;"><abbr title="{l s='version' mod='samdha'}">v</abbr>{$module_version|escape:'htmlall':'UTF-8'}</span>
		<a href="{$contact_url|escape:'htmlall':'UTF-8'}" id="samdha_contact">{l s='Contact' mod='samdha'}</a>
	</p>
{if !$version_16}
</fieldset>
{/if}
