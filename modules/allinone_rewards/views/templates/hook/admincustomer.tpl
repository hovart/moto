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
<div class="{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}col-lg-12{else}clear{/if}" id="admincustomer">
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	<div class="panel">
		<div class="panel-heading">{l s='Rewards account' mod='allinone_rewards'}</div>
		{if $msg}{$msg}{/if}
{else}
		<h2>{l s='Rewards account' mod='allinone_rewards'}</h2>
		{if $msg}{$msg}<br>{/if}
{/if}
{if (float)$totals['total'] > 0}
	{if (float)$totals[RewardsStateModel::getValidationId()] > 0}
		<form id="rewards_reminder" method="post">
			<input class="button" name="submitRewardReminder" type="submit" value="{l s='Send an email with account balance :' mod='allinone_rewards'} {displayPrice price=$totals[RewardsStateModel::getValidationId()]}" /> {if $rewards_account->date_last_remind && $rewards_account->date_last_remind != '0000-00-00 00:00:00'} ({l s='last email :' mod='allinone_rewards'} {dateFormat date=$rewards_account->date_last_remind full=1}){/if}
		</form><br>
	{/if}
		<table cellspacing="0" cellpadding="0" class="table">
			<thead>
				<tr style="background-color: #EEEEEE">
					<th style='text-align: center'>{l s='Total rewards' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Already converted' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Paid' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Available' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Awaiting validation' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Awaiting payment' mod='allinone_rewards'}</th>
				</tr>
			</thead>
			<tr>
				<td class="center">{displayPrice price=$totals['total']}</td>
				<td class="center">{displayPrice price=$totals[RewardsStateModel::getConvertId()]}</td>
				<td class="center">{displayPrice price=$totals[RewardsStateModel::getPaidId()]}</td>
				<td class="center">{displayPrice price=$totals[RewardsStateModel::getValidationId()]}</td>
				<td class="center">{displayPrice price=$totals[RewardsStateModel::getDefaultId()] + $totals[RewardsStateModel::getReturnPeriodId()]}</td>
				<td class="center">{displayPrice price=$totals[RewardsStateModel::getWaitingPaymentId()]}</td>
			</tr>
		</table>
{else}
	{l s='This customer has no reward' mod='allinone_rewards'}
{/if}
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	</div>
	<form id="rewards_history" method="post">
	<div class="panel">
		<div class="panel-heading">{l s='Add a new reward' mod='allinone_rewards'}</div>
{else}
		<form id="rewards_history" method="post">
		<h3>{l s='Add a new reward' mod='allinone_rewards'}</h3>
{/if}
		{l s='Value' mod='allinone_rewards'} <input name="new_reward_value" type="text" size="6" value="{$new_reward_value|escape:'strval'}" style="text-align: right; display: inline; width: auto"/> {$sign|escape:'strval'}&nbsp;&nbsp;&nbsp;&nbsp;
		{l s='Status' mod='allinone_rewards'} <select name="new_reward_state" style="display: inline; width: auto">
			<option {if $new_reward_state == RewardsStateModel::getDefaultId()}selected{/if} value="{RewardsStateModel::getDefaultId()}">{$rewardStateDefault}</option>
			<option {if $new_reward_state == RewardsStateModel::getValidationId()}selected{/if} value="{RewardsStateModel::getValidationId()}">{$rewardStateValidation}</option>
			<option {if $new_reward_state == RewardsStateModel::getCancelId()}selected{/if} value="{RewardsStateModel::getCancelId()}">{$rewardStateCancel}</option>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
		{l s='Reason' mod='allinone_rewards'} <input name="new_reward_reason" type="text" size="40" maxlength="80" value="{$new_reward_reason|escape:'htmlall':'UTF-8'}" style="display: inline; width: auto"/>
		<input class="button" name="submitNewReward" type="submit" value="{l s='Save settings' mod='allinone_rewards'}"/>
{if (float)$totals['total'] > 0}
	{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	</div>
	<div class="panel">
		<div class="panel-heading">{l s='Rewards history' mod='allinone_rewards'}</div>
	{else}
		<h3>{l s='Rewards history' mod='allinone_rewards'}</h3>
	{/if}
		<input type="hidden" id="id_reward_to_update" name="id_reward_to_update" />
		<table cellspacing="0" cellpadding="0" class="table">
			<thead>
				<tr style="background-color: #EEEEEE">
					<th>{l s='Event' mod='allinone_rewards'}</th>
					<th>{l s='Date' mod='allinone_rewards'}</th>
	{if $rewards_duration > 0}
					<th>{l s='Validity' mod='allinone_rewards'}</th>
	{/if}
					<th>{l s='Total (without shipping)' mod='allinone_rewards'}</th>
					<th>{l s='Reward' mod='allinone_rewards'}</th>
					<th>{l s='Status' mod='allinone_rewards'}</th>
					<th>{l s='Action' mod='allinone_rewards'}</th>
				</tr>
			</thead>
	{foreach from=$rewards item=reward name=myLoop}
		{assign var="bUpdate" value="{in_array($reward['id_reward_state'], $states_for_update)}"}
			<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}alt_row{/if}">
				<td>{if ($bUpdate && $reward['plugin'] == "free")}<input name="reward_reason_{$reward['id_reward']}" type="text" size="30" maxlength="80" value="{$reward['detail']|escape:'htmlall':'UTF-8'}" />{else} {$reward['detail']}{if (int)$reward['id_order'] > 0} - <a href="?tab=AdminOrders&id_order={$reward['id_order']}&vieworder&token={getAdminToken tab='AdminOrders'}" style="display: inline; width: auto">{l s='#' mod='allinone_rewards'}{sprintf('%06d', $reward['id_order'])}</a>{/if}{/if}</td>
				<td>{dateFormat date=$reward['date'] full=1}</td>
		{if $rewards_duration > 0}
				<td>{if $reward['id_reward_state']==RewardsStateModel::getValidationId()}{dateFormat date=$reward['validity'] full=1}{else}&nbsp;{/if}</td>
		{/if}
				<td align="right">{if (int)$reward['id_order'] > 0}{Tools::displayPrice(round(Tools::convertPrice($reward['total_without_shipping'], Currency::getCurrency($reward['id_currency']), false), 2), (int)Configuration::get('PS_CURRENCY_DEFAULT'))}{else}-{/if}</td>
				<td align="right">{if $bUpdate}<input name="reward_value_{$reward['id_reward']}" type="text" size="6" value="{(float)$reward['credits']}" style="text-align: right; display: inline; width: auto"/> {$sign}{else}{displayPrice price=$reward['credits']}{/if}</td>
				<td>{if $bUpdate}
					<select name="reward_state_{$reward['id_reward']|escape:'intval'}" style="display: inline; width: auto">
						<option {if $reward['id_reward_state'] == RewardsStateModel::getDefaultId()}selected{/if} value="{RewardsStateModel::getDefaultId()}">{$rewardStateDefault}</option>
						<option {if $reward['id_reward_state'] == RewardsStateModel::getValidationId()}selected{/if} value="{RewardsStateModel::getValidationId()}">{$rewardStateValidation}</option>
						<option {if $reward['id_reward_state'] == RewardsStateModel::getCancelId()}selected{/if} value="{RewardsStateModel::getCancelId()}">{$rewardStateCancel}</option>
						{if ($reward['id_reward_state'] == RewardsStateModel::getReturnPeriodId() || ((int)$reward['id_order'] > 0 && Configuration::get('REWARDS_WAIT_RETURN_PERIOD') && Configuration::get('PS_ORDER_RETURN') && (int)Configuration::get('PS_ORDER_RETURN_NB_DAYS') > 0))}
						<option {if $reward['id_reward_state'] == RewardsStateModel::getReturnPeriodId()}selected{/if} value="{RewardsStateModel::getReturnPeriodId()}">{$rewardStateReturnPeriod} {l s='(Return period)' mod='allinone_rewards'}</option>
						{/if}
					</select>
					{else}
					{$reward['state']|escape:'strval'}
					{/if}
				</td>
				<td>{if $bUpdate}<input class="button" name="submitRewardUpdate" type="submit" value="{l s='Save settings' mod='allinone_rewards'}" onClick="$('#id_reward_to_update').val({$reward['id_reward']})">{/if}</td>
			</tr>
	{/foreach}
		</table>

	{if $payment_authorized}
		{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	</div>
	<div class="panel">
		<div class="panel-heading">{l s='Payments history' mod='allinone_rewards'}</div>
		{else}
		<h3>{l s='Payments history' mod='allinone_rewards'}</h3>
		{/if}
		{if ($payments|@count)}
		<table cellspacing="0" cellpadding="0" class="table">
			<thead>
				<tr style="background-color: #EEEEEE">
					<th>{l s='Request date' mod='allinone_rewards'}</th>
					<th>{l s='Payment date' mod='allinone_rewards'}</th>
					<th style="text-align: right">{l s='Value' mod='allinone_rewards'}</th>
					<th>{l s='Details' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Invoice' mod='allinone_rewards'}</th>
					<th style='text-align: center'>{l s='Action' mod='allinone_rewards'}</th>
				</tr>
			</thead>
			{foreach from=$payments item=payment name=myLoop}
			<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}alt_row{/if}">
				<td>{dateFormat date=$payment['date_add'] full=1}</td>
				<td>{if $payment['paid']}{dateFormat date=$payment['date_upd'] full=1}{else}-{/if}</td>
				<td style="text-align: right">{displayPrice price=$payment['credits']}</td>
				<td>{$payment['detail']|nl2br}</td>
				<td class="center">{if $payment['invoice']}<a href="{$module_template_dir}uploads/{$payment['invoice']}" download="Invoice.pdf">{l s='View' mod='allinone_rewards'}</a>{else}-{/if}</td>
				<td class="center">{if !$payment['paid']}<a href="index.php?tab=AdminCustomers&id_customer={$customer->id}&viewcustomer&token={getAdminToken tab='AdminCustomers'}&accept_payment={$payment['id_payment']}">{l s='Mark as paid' mod='allinone_rewards'}</a>{else}-{/if}</td>
			</tr>
			{/foreach}
		</table>
		{else}
			{l s='No payment request found' mod='allinone_rewards'}
		{/if}
	{/if}
{/if}

{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	</div>
{/if}
	</form>
</div>
<!-- END : MODULE allinone_rewards -->