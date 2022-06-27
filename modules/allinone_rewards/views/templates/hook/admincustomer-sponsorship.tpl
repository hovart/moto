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
<!-- MODULE allinone_rewards -->
<style>
	tr.inactive td {
		text-decoration: line-through;
	}
	tr.inactive td.nostrike {
		text-decoration: none;
	}
</style>
<div class="{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}col-lg-12{else}clear{/if}" id="admincustomer_sponsorship">
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	<div class="panel">
		<div class="panel-heading">{l s='Sponsorship program' mod='allinone_rewards'}</div>
		{if $msg}{$msg}{/if}
{else}
		<h2>{l s='Sponsorship program' mod='allinone_rewards'}</h2>
		{if $msg}{$msg}<br>{/if}
{/if}
		{if !$sponsorship_allowed}{l s='This customer is actually not allowed to sponsor his friends (added to a template with sponsorship turned off, or in a group not allowed to sponsor)' mod='allinone_rewards'}<br>{/if}
		{l s='Sponsorship code :' mod='allinone_rewards'} {$sponsorship_code|escape:'strval'}<br>
		<form id='sponsor' method='post'>
			<input type="hidden" id="id_sponsorship_to_update" name="id_sponsorship_to_update" />
		{if $sponsor}
			{l s='Sponsor' mod='allinone_rewards'} <a href="?tab=AdminCustomers&id_customer={$sponsor->id}&viewcustomer&token={getAdminToken tab='AdminCustomers'}">{$sponsor->firstname} {$sponsor->lastname}</a><br>
		{else}
			{l s='Choose a sponsor :' mod='allinone_rewards'}&nbsp;
			&nbsp;<input class="button" name="submitSponsor" id="submitSponsor" value="{l s='Save settings' mod='allinone_rewards'}" type="submit" />
			<select name="new_sponsor" style="display: inline; width: auto;">
				<option value="0">{l s='-- No sponsor --' mod='allinone_rewards'}</option>
			{foreach from=$available_sponsors item=new_sponsor}
				<option value='{$new_sponsor['id_customer']|escape:'intval'}'>{$new_sponsor['firstname']|escape:'strval'} {$new_sponsor['lastname']|escape:'strval'} (ID : {$new_sponsor['id_customer']|escape:'intval'})</option>
			{/foreach}
			</select>
			{if $discount_gc}
				&nbsp;&nbsp;&nbsp;&nbsp;{l s='Generate the welcome voucher ?' mod='allinone_rewards'}&nbsp;<input checked type="checkbox" value="1" name="generate_voucher" style="display: inline; width: auto;">&nbsp;
				<select name="generate_currency" style="display: inline !important; width: auto;">
				{foreach from=$currencies item=currency}
					<option {if $default_currency==$currency['id_currency']}selected{/if} value="{$currency['id_currency']}">{$currency['name']}</option>
				{/foreach}
				</select>
			{/if}
		<br>
		{/if}
		{if $friends|@count}
		<table cellspacing='0' cellpadding='0' class='table'>
			<thead>
				<tr style="background-color: #EEEEEE">
					<th colspan='2' style='text-align: center'>{l s='Rewards' mod='allinone_rewards'}</th>
					<th colspan='5' style='text-align: center'>{l s='Sponsored friends (Level 1)' mod='allinone_rewards'}</th>
				</tr>
				<tr style="background-color: #EEEEEE">
					<th style='text-align: center'>{l s='Direct rewards' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Indirect rewards' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Pending' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Registered' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='With orders' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Orders' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Total' mod='allinone_rewards'}</th>
				</tr>
			</thead>
			<tr>
				<td align='center'>{displayPrice price=$stats['direct_rewards']}</td>
				<td align='center'>{displayPrice price=$stats['indirect_rewards']}</td>
				<td align='center'>{$stats['nb_pending']|escape:'intval'}</td>
				<td align='center'>{$stats['nb_registered']|escape:'intval'}</td>
				<td align='center'>{$stats['nb_buyers']|escape:'intval'}</td>
				<td align='center'>{$stats['nb_orders']|escape:'intval'}</td>
				<td align='center'>{displayPrice price=$stats['total_orders']}</td>
			</tr>
		</table>
		<div class='clear' style="margin-top: 20px">&nbsp;</div>
		<table cellspacing='0' cellpadding='0' class='table'>
			<thead>
				<tr style="background-color: #EEEEEE">
					<th style='text-align: center'>{l s='Levels' mod='allinone_rewards'}</th>
					<th>{l s='Channels' mod='allinone_rewards'}</th>
					<th>{l s='Name of the friends' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Number of orders' mod='allinone_rewards'}</th>
					<th style='text-align: right'>{l s='Total orders' mod='allinone_rewards'}</th>
					<th style='text-align: right'>{l s='Total rewards' mod='allinone_rewards'}</th>
					<th>{l s='End date' mod='allinone_rewards'}</th>
					<th>{l s='Action' mod='allinone_rewards'}</th>
				</tr>
			</thead>
			{foreach from=$friends item=sponsored}
				{assign var="channel" value="{l s='Email invitation' mod='allinone_rewards'}"}
				{if ($sponsored['channel']==2)}
					{assign var="channel" value="{l s='Sponsorship link' mod='allinone_rewards'}"}
				{else if ($sponsored['channel']==3)}
					{assign var="channel" value="{l s='Facebook' mod='allinone_rewards'}"}
				{else if ($sponsored['channel']==4)}
					{assign var="channel" value="{l s='Twitter' mod='allinone_rewards'}"}
				{else if ($sponsored['channel']==5)}
					{assign var="channel" value="{l s='Google +1' mod='allinone_rewards'}"}
				{/if}
			<tr {if !$sponsored['active']}class="inactive"{/if}>
				<td align='center'>{$sponsored['level_sponsorship']|escape:'intval'}</td>
				<td>{$channel|escape:'strval'}</td>
				<td>{$sponsored['lastname']|escape:'strval'} {$sponsored['firstname']|escape:'strval'}</td>
				<td align='center'>{$sponsored['nb_orders']|escape:'intval'}</td>
				<td align='right'>{displayPrice price=$sponsored['total_orders']}</td>
				<td align='right'>{displayPrice price=$sponsored['total_rewards']}</td>
				<td class="nostrike">{if $sponsored['level_sponsorship']==1}<input type="text" name="date_end_{$sponsored['id_sponsorship']}" style="width: 140px" class="datetimepicker" value="{if $sponsored['date_end'] !=0 }{$sponsored['date_end']}{/if}">{/if}</td>
				<td class="nostrike">{if $sponsored['level_sponsorship']==1}<input class="button" name="submitSponsorshipEndDate" type="submit" value="{l s='Save settings' mod='allinone_rewards'}" onClick="$('#id_sponsorship_to_update').val({$sponsored['id_sponsorship']})">{/if}</td>
			</tr>
			{/foreach}
		</table>
		<script>
			$('.datetimepicker').datetimepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',
				// Define a custom regional settings in order to use PrestaShop translation tools
				currentText: '{l s='Now' mod='allinone_rewards'}',
				closeText: '{l s='Done' mod='allinone_rewards'}',
				ampm: false,
				amNames: ['AM', 'A'],
				pmNames: ['PM', 'P'],
				timeFormat: 'hh:mm:ss tt',
				timeSuffix: '',
				timeOnlyTitle: "{l s='Choose Time' mod='allinone_rewards'}",
				timeText: '{l s='Time' mod='allinone_rewards'}',
				hourText: '{l s='Hour' mod='allinone_rewards'}',
				minuteText: '{l s='Minute' mod='allinone_rewards'}',
				secondText: '{l s='Second' mod='allinone_rewards'}',
				showSecond: true
			});
		</script>
		{else}
			{l s='This customer has not sponsored any friends yet.' mod='allinone_rewards'}
		{/if}
		</form>
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	</div>
{/if}
</div>
<!-- END : MODULE allinone_rewards -->