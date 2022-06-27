{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if $bStatus == 0}
	{if !empty($bWarning)}
		<i class="icon-warning text-warning" style="font-size:20px;" title="{l s='warning' mod='gsnippetsreviews'}"></i>
	{else}
		<i class="icon-remove text-danger" style="font-size:20px;" title="{l s='activate' mod='gsnippetsreviews'}"></i>
	{/if}
{else}
	<i class="icon-ok-sign text-success" style="font-size:20px;" title="{l s='deactivate' mod='gsnippetsreviews'}"></i>
{/if}
{if empty($bUpdate) && !empty($aErrors) && !empty($bOneStatusUpdate)}
{include file="`$sErrorInclude`"}
{/if}