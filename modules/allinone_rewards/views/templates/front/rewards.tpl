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
{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account' mod='allinone_rewards'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My rewards account' mod='allinone_rewards'}{/capture}
{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
{include file="$tpl_dir./breadcrumb.tpl"}
{/if}

<div id="rewards_account" class="rewards">
	<h1 class="page-heading">{l s='My rewards account' mod='allinone_rewards'}</h1>

{if isset($payment_error)}
	{if $payment_error==1}
	<p class="error">{l s='Please fill all the required fields' mod='allinone_rewards'}</p>
	{elseif $payment_error==2}
	<p class="error">{l s='An error occured during the treatment of your request' mod='allinone_rewards'}</p>
	{/if}
{/if}

	<div id="general_txt" style="padding-bottom: 20px">{$general_txt|escape:'strval'}</div>

{if $return_days > 0}
	<p>{l s='Rewards will be available ' mod='allinone_rewards'} {$return_days|escape:'intval'} {l s='days after the validation of each order.' mod='allinone_rewards'}</p>
{/if}
	<table class="std">
		<thead>
			<tr>
				<th style="text-align: center" class="first_item">{l s='Total rewards' mod='allinone_rewards'}</th>
				{if $voucher_allowed || $totalConverted > 0}
				<th style="text-align: center" class="item">{l s='Already converted' mod='allinone_rewards'}</th>
				{/if}
				{if $payment_allowed || $totalPaid > 0 || $totalWaitingPayment > 0}
				<th style="text-align: center" class="item">{l s='Paid' mod='allinone_rewards'}</th>
				{/if}
				<th style="text-align: center" class="item">{l s='Available' mod='allinone_rewards'}</th>
				<th style="text-align: center" class="last_item">{l s='Awaiting validation' mod='allinone_rewards'}</th>
				{if $payment_allowed || $totalPaid > 0 || $totalWaitingPayment > 0}
				<th style="text-align: center" class="last_item">{l s='Awaiting payment' mod='allinone_rewards'}</th>
				{/if}
			</tr>
		</thead>
		<tr class="alternate_item">
			<td style="text-align: center">{displayPrice price=$totalGlobal}</td>
			{if $voucher_allowed || $totalConverted > 0}
			<td style="text-align: center">{displayPrice price=$totalConverted}</td>
			{/if}
			{if $payment_allowed || $totalPaid > 0 || $totalWaitingPayment > 0}
			<td style="text-align: center">{displayPrice price=$totalPaid}</td>
			{/if}
			<td style="text-align: center">{displayPrice price=$totalAvailable}</td>
			<td style="text-align: center">{displayPrice price=$totalPending}</td>
			{if $payment_allowed || $totalPaid > 0 || $totalWaitingPayment > 0}
			<td style="text-align: center">{displayPrice price=$totalWaitingPayment}</td>
			{/if}
		</tr>
	</table>
{if $rewards}
	<table class="std">
		<thead>
			<tr>
				<th class="first_item">{l s='Event' mod='allinone_rewards'}</th>
				<th class="item">{l s='Date' mod='allinone_rewards'}</th>
				<th class="item">{l s='Reward' mod='allinone_rewards'}</th>
	{if $rewards_duration > 0}
				<th class="item">{l s='Status' mod='allinone_rewards'}</th>
				<th class="last_item">{l s='Validity' mod='allinone_rewards'}</th>
	{else}
				<th class="last_item">{l s='Status' mod='allinone_rewards'}</th>
	{/if}
			</tr>
		</thead>
		<tbody>
	{foreach from=$displayrewards item=reward name=myLoop}
			<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}item{else}alternate_item{/if}">
				<td>{$reward.detail|escape:'strval'}</td>
				<td>{dateFormat date=$reward.date full=1}</td>
				<td align="right">{displayPrice price=$reward.credits}</td>
				<td>{$reward.state|escape:'htmlall':'UTF-8'}</td>
		{if $rewards_duration > 0}
				<td>{if $reward.id_reward_state==RewardsStateModel::getValidationId()}{dateFormat date=$reward.validity full=1}{else}&nbsp;{/if}</td>
		{/if}
			</tr>
	{/foreach}
		</tbody>
	</table>

	{if $nbpagination < $rewards|@count || $rewards|@count > 10}
<div id="pagination" class="pagination">
		{if true || $nbpagination < $rewards|@count}
	<ul class="pagination">
			{if $page != 1}
			{assign var='p_previous' value=$page-1}
		<li id="pagination_previous"><a href="{$pagination_link|escape:'strval'}p={$p_previous|escape:'intval'}&n={$nbpagination|escape:'intval'}">
			&laquo;&nbsp;{l s='Previous' mod='allinone_rewards'}</a></li>
			{else}
		<li id="pagination_previous" class="disabled"><span>&laquo;&nbsp;{l s='Previous' mod='allinone_rewards'}</span></li>
			{/if}
			{if $page > 2}
		<li><a href="{$pagination_link|escape:'strval'}p=1&n={$nbpagination|escape:'intval'}">1</a></li>
				{if $page > 3}
		<li class="truncate">...</li>
				{/if}
			{/if}
			{section name=pagination start=$page-1 loop=$page+2 step=1}
				{if $page == $smarty.section.pagination.index}
		<li class="current"><span>{$page|escape:'htmlall':'UTF-8'}</span></li>
				{elseif $smarty.section.pagination.index > 0 && $rewards|@count+$nbpagination > ($smarty.section.pagination.index)*($nbpagination)}
		<li><a href="{$pagination_link}p={$smarty.section.pagination.index}&n={$nbpagination}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
				{/if}
			{/section}
			{if $max_page-$page > 1}
				{if $max_page-$page > 2}
		<li class="truncate">...</li>
				{/if}
		<li><a href="{$pagination_link|escape:'strval'}p={$max_page|escape:'intval'}&n={$nbpagination|escape:'intval'}">{$max_page}</a></li>
			{/if}
			{if $rewards|@count > $page * $nbpagination}
				{assign var='p_next' value=$page+1}
		<li id="pagination_next"><a href="{$pagination_link|escape:'strval'}p={$p_next|escape:'intval'}&n={$nbpagination|escape:'intval'}">{l s='Next' mod='allinone_rewards'}&nbsp;&raquo;</a></li>
			{else}
		<li id="pagination_next" class="disabled"><span>{l s='Next' mod='allinone_rewards'}&nbsp;&raquo;</span></li>
			{/if}
	</ul>
		{/if}
		{if $rewards|@count > 10}
	<form action="{$pagination_link|escape:'strval'}" method="get" class="pagination">
		<p>
			<input type="submit" class="button_mini" value="{l s='OK'  mod='allinone_rewards'}" />
			<label for="nb_item">{l s='items:' mod='allinone_rewards'}</label>
			<select name="n" id="nb_item">
			{foreach from=$nArray item=nValue}
				{if $nValue <= $rewards|@count}
				<option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $nbpagination == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
				{/if}
			{/foreach}
			</select>
			<input type="hidden" name="p" value="1" />
		</p>
	</form>
		{/if}
</div>
	{/if}

	{if $voucher_allowed && $voucher_min > 0}
<div id="min_transform" style="clear: both">{l s='The minimum required to be able to transform your rewards into vouchers is' mod='allinone_rewards'} <b>{displayPrice price=$voucher_min}</b></div>
	{/if}
	{if $payment_allowed && $payment_min > 0}
<div id="min_payment" style="clear: both">{l s='The minimum required to be able to ask for a payment is' mod='allinone_rewards'} <b>{displayPrice price=$payment_min}</b></div>
	{/if}

	{if $voucher_button_allowed}
<div id="transform" style="clear: both">
	<a href="{$pagination_link}transform-credits=true" onclick="return confirm('{l s='Are you sure you want to transform your rewards into vouchers ?' mod='allinone_rewards' js=1}');">{l s='Transform my rewards into a voucher worth' mod='allinone_rewards'} <span>{displayPrice price=$totalAvailable}</span></a>
</div>
	{/if}
	{if $payment_button_allowed}
<div id="payment" style="clear: both">
	<a onClick="$('#payment_form').toggle()">{l s='Ask for the payment of your available rewards :' mod='allinone_rewards'} <span>{displayPrice price=$totalForPaymentDefaultCurrency currency=$payment_currency}</span></a>
	<form id="payment_form" class="std" method="post" action="{$pagination_link}" enctype="multipart/form-data" style="display: {if isset($smarty.post.payment_details)}block{else}none{/if}">
		<fieldset>
			<div id="payment_txt">{$payment_txt|escape:'strval'}</div>
			<p class="required textarea">
				<label for="payment_details">{l s='Bank account, paypal address, address, details...' mod='allinone_rewards'} <sup>*</sup></label>
				<textarea id="payment_details" name="payment_details" rows="3" cols="40">{if isset($smarty.post.payment_details)}{$smarty.post.payment_details}{/if}</textarea>
			</p>
			<p class="{if $payment_invoice}required{/if} text">
				<label for="payment_invoice">{l s='Invoice' mod='allinone_rewards'} ({displayPrice price=$totalForPaymentDefaultCurrency currency=$payment_currency}) {if $payment_invoice}<sup>*</sup>{/if}</label>
				<input id="payment_invoice" name="payment_invoice" type="file">
			</p>
			<input class="button" type="submit" value="{l s='Save' mod='allinone_rewards'}" name="submitPayment" id="submitPayment">
			<p class="required"><sup>*</sup>{l s='Required field' mod='allinone_rewards'}</p>
		</fieldset>
	</form>
</div>
	{/if}
{/if}
</div>
<!-- END : MODULE allinone_rewards -->