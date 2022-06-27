{*
* All-in-one Rewards Module
*
* @category  Prestashop
* @category  Module
* @author    Yann BONNAILLIE - ByWEB
* @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
* @license   Commercial license see license.txt
* Support by mail  : contact@prestaplugins.com
* Support on forum : Patanock
* Support on Skype : Patanock13
*}
{if ($rewards|@count)}
<!-- MODULE allinone_rewards -->
	<div class="{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}col-lg-7{else}clear{/if}" id="adminorders_sponsorship">
	{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
		<div class="panel" style="overflow: auto">
			<div class="panel-heading">{l s='Sponsorship rewards for this order' mod='allinone_rewards'}</div>
	{else}
			<br>
			<fieldset>
				<legend>{l s='Sponsorship rewards for this order' mod='allinone_rewards'}</legend>
	{/if}

				<table style="width: 100%">
					<tr style="font-weight: bold">
						<td>{l s='Level' mod='allinone_rewards'}</td>
						<td>{l s='Name' mod='allinone_rewards'}</td>
						<td>{l s='Reward' mod='allinone_rewards'}</td>
						<td>{l s='Status' mod='allinone_rewards'}</td>
					</tr>
	{foreach from=$rewards item=reward}
					<tr>
						<td>{$reward['level_sponsorship']|escape:'intval'}</td>
						<td>{$reward['firstname']|escape:'strval'} {$reward['lastname']|escape:'strval'}</td>
						<td>{displayPrice price=$reward['credits']}</td>
						<td>{$reward['state']|escape:'strval'}</td>
					</tr>
	{/foreach}
				</table>
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
		</div>
{else}
			</fieldset>
{/if}
	</div>
<!-- END : MODULE allinone_rewards -->
{/if}