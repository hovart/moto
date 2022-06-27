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
<script type="text/javascript">
//<![CDATA[
	var msg = "{l s='You must agree to the terms of service before continuing.' mod='allinone_rewards'}";
	var url_allinone_sponsorship="{$link->getModuleLink('allinone_rewards', 'sponsorship', [], true)|escape:'strval'}";
//]]>
</script>

{assign var="sback" value="0"}
{if isset($popup)}
	{assign var="sback" value="1"}
{/if}

{if !isset($getcontact)}
<div id="rewards_sponsorship" class="rewards">
	{if !isset($popup)}
		{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account' mod='allinone_rewards'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Sponsorship program' mod='allinone_rewards'}{/capture}

		{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	<h1 class="page-heading">{l s='Sponsorship program' mod='allinone_rewards'}</h1>
		{else}
		{include file="$tpl_dir./breadcrumb.tpl"}

	<h2>{l s='Sponsorship program' mod='allinone_rewards'}</h2>
		{/if}
	{/if}

	{if $error}
	<p class="error">
		{if $error == 'email invalid'}
			{l s='At least one email address is invalid!' mod='allinone_rewards'}
		{elseif $error == 'name invalid'}
			{l s='At least one first name or last name is invalid!' mod='allinone_rewards'}
		{elseif $error == 'email exists'}
			{l s='Someone with this email address has already been sponsored' mod='allinone_rewards'}: {foreach from=$mails_exists item=mail}{$mail} {/foreach}<br>
		{elseif $error == 'no revive checked'}
			{l s='Please mark at least one checkbox' mod='allinone_rewards'}
		{elseif $error == 'bad phone'}
			{l s='The mobile phone is invalid' mod='allinone_rewards'}
		{elseif $error == 'sms already sent'}
			{l s='This mobile phone has already been invited during last 10 days, please retry later.' mod='allinone_rewards'}
		{elseif $error == 'sms impossible'}
			{l s='An error occured, the SMS has not been sent' mod='allinone_rewards'}
		{/if}
	</p>
	{/if}

	{if ($invitation_sent||$sms_sent) && isset($popup)}
	<p class="success popup">
		{if $sms_sent}
		{l s='A SMS has been sent to your friend!' mod='allinone_rewards'}
		{else if $nbInvitation > 1}
		{l s='Emails have been sent to your friends!' mod='allinone_rewards'}
		{else}
		{l s='An email has been sent to your friend!' mod='allinone_rewards'}
		{/if}
	</p>
	{else}
		{if $invitation_sent||$sms_sent}
	<p class="success">
			{if $sms_sent}
		{l s='A SMS has been sent to your friend!' mod='allinone_rewards'}
			{else if $nbInvitation > 1}
		{l s='Emails have been sent to your friends!' mod='allinone_rewards'}
			{else}
		{l s='An email has been sent to your friend!' mod='allinone_rewards'}
			{/if}
	</p>
		{/if}

		{if !isset($popup) && $revive_sent}
	<p class="success">
			{if $nbRevive > 1}
		{l s='Reminder emails have been sent to your friends!' mod='allinone_rewards'}
			{else}
		{l s='A reminder email has been sent to your friend!' mod='allinone_rewards'}
			{/if}
	</p>
		{/if}

		{if !isset($popup)}
	<ul class="idTabs">
		<li><a href="#idTab1" {if $activeTab eq 'sponsor'}class="selected"{/if}>{l s='Sponsor my friends' mod='allinone_rewards'}</a></li>
		<li><a href="#idTab2" {if $activeTab eq 'pending'}class="selected"{/if}>{l s='Pending friends' mod='allinone_rewards'}</a></li>
		<li><a href="#idTab3" {if $activeTab eq 'subscribed'}class="selected"{/if}>{l s='Friends I sponsored' mod='allinone_rewards'}</a></li>
			{if $reward_allowed_s}
		<li><a href="#idTab4" {if $activeTab eq 'statistics'}class="selected"{/if}>{l s='Statistics' mod='allinone_rewards'}</a></li>
			{/if}
	</ul>
	<div class="sheets">
		<div id="idTab1" class="sponsorshipBlock">
		{else}
		<div class="sponsorshipBlock sponsorshipPopup">
		{/if}

		{if isset($text)}
			<div id="sponsorship_text" {if isset($popup) && $afterSubmit}style="display: none"{/if}>
				{$text|escape:'strval'}
			{if isset($popup)}
				<div align="center">
					<input id="invite" type="button" class="button" value="{l s='Invite my friends' mod='allinone_rewards'}" />
					<input id="noinvite" type="button" class="button" value="{l s='No, thanks' mod='allinone_rewards'}" />
				</div>
			{/if}
			</div>
		{/if}

		{if $canSendInvitations || isset($popup)}
			<div id="sponsorship_form"  {if isset($popup) && !$afterSubmit}style="display: none"{/if}>
				<div>
				{l s='Sponsorship is quick and easy. You can invite your friends in different ways :' mod='allinone_rewards'}
				<ul>
					<li>{l s='Propose your sponsorship on the social networks, by clicking the following links' mod='allinone_rewards'}<br>
						&nbsp;<a href="http://www.facebook.com/sharer.php?u={$link_sponsorship_fb|escape:'url'}" target="_blank" title="{l s='Facebook' mod='allinone_rewards'}"><img src='{$rewards_path}img/facebook.png' height='20'></a>
						&nbsp;<a href="http://twitter.com/share?url={$link_sponsorship_twitter|escape:'url'}" target="_blank" title="{l s='Twitter' mod='allinone_rewards'}"><img src='{$rewards_path}img/twitter.png' height='20'></a>
						&nbsp;<a href="https://plus.google.com/share?url={$link_sponsorship_google|escape:'url'}" target="_blank" title="{l s='Google+' mod='allinone_rewards'}"><img src="{$rewards_path}img/google.png"></a>
					</li>
			{if $open_inviter_providers && count($open_inviter_providers)}
					<li>{l s='Invite your friends from your contacts\' lists' mod='allinone_rewards'}&nbsp;
						<form id="open_inviter_form" style="display: inline">
							<select id='provider' name='provider'>
								<option value=''></option>
				{foreach from=$open_inviter_providers key='provider' item='details'}
								<option value='{$provider|escape:'strval'}'>{$details.name|escape:'strval'}</option>
				{/foreach}
							</select>
							<br/>{l s='Be assured your login information will not be registered or used for other purposes' mod='allinone_rewards'}
							<div>
								<span>{l s='Email' mod='allinone_rewards'}</span><input type="text" name="login" size="30" value="{if isset($smarty.post.login)}{$smarty.post.login}{/if}" /><br style="clear: both"/>
								<span>{l s='Password' mod='allinone_rewards'}</span><input type="password" name="password" size="30" value="{if isset($smarty.post.password)}{$smarty.post.password}{/if}" /><br style="clear: both"/>
								<span>&nbsp;</span><input type="submit" id="submitOpenInviter" name="submitOpenInviter" value="{l s='View list' mod='allinone_rewards'}" />
							</div>
						</form>
						<div id="open_inviter_contacts">
							<center><img src="{$base_dir_ssl|escape:'strval'}modules/allinone_rewards/img/loadingAnimation.gif"></center>
						</div>
					</li>
			{/if}
					<li>{l s='Give this sponsorship link to your friends, or post it on internet (forums, blog...)' mod='allinone_rewards'}<br>{$link_sponsorship}</li>
					<li>{l s='Give them your mail' mod='allinone_rewards'} <b>{$email}</b> {l s='or your sponsor code' mod='allinone_rewards'} <b>{$code}</b> {l s='to enter in the registration form.' mod='allinone_rewards'}</li>
			{if $sms}
					<li>
						<form id="sms_form" method="post" action="{$link->getModuleLink('allinone_rewards', 'sponsorship', [], true)}" style="display: inline">{l s='Enter their mobile phone (international format) and send them a SMS' mod='allinone_rewards'} <input id="phone" name="phone" maxlength="16" type="text" placeholder="{l s='e.g. +33612345678' mod='allinone_rewards'}" />
							<input type="image" src="{$base_dir_ssl|escape:'strval'}modules/allinone_rewards/img/sendsms.gif" id="submitSponsorSMS" name="submitSponsorSMS" alt="{l s='Send SMS' mod='allinone_rewards'}" title="{l s='Send SMS' mod='allinone_rewards'}" align="absmiddle" />
						</form>
					</li>
			{/if}
					<li>{l s='Fill in the following form and they will receive an mail.' mod='allinone_rewards'}</li>
				</ul>
				</div>
				<div>
					<form id="list_contacts_form" method="post" action="{$link->getModuleLink('allinone_rewards', 'sponsorship', [], true)|escape:'strval'}">
						<table class="std">
						<thead>
							<tr>
								<th class="first_item">&nbsp;</th>
								<th class="item">{l s='Last name' mod='allinone_rewards'}</th>
								<th class="item">{l s='First name' mod='allinone_rewards'}</th>
								<th class="last_item">{l s='Email' mod='allinone_rewards'}</th>
							</tr>
						</thead>
						<tbody>
							{section name=friends start=0 loop=$nbFriends step=1}
							<tr class="alternate_item">
								<td class="align_right">{$smarty.section.friends.iteration|escape:'intval'}</td>
								<td><input type="text" class="text" name="friendsLastName[{$smarty.section.friends.index}]" size="20" value="{if isset($smarty.post.friendsLastName[$smarty.section.friends.index])}{$smarty.post.friendsLastName[$smarty.section.friends.index]}{/if}" /></td>
								<td><input type="text" class="text" name="friendsFirstName[{$smarty.section.friends.index}]" size="20" value="{if isset($smarty.post.friendsFirstName[$smarty.section.friends.index])}{$smarty.post.friendsFirstName[$smarty.section.friends.index]}{/if}" /></td>
								<td><input type="text" class="text" name="friendsEmail[{$smarty.section.friends.index}]" size="20" value="{if isset($smarty.post.friendsEmail[$smarty.section.friends.index])}{$smarty.post.friendsEmail[$smarty.section.friends.index]}{/if}" /></td>
							</tr>
							{/section}
						</tbody>
						</table>
						<p class="bold">
							{l s='Important: Your friends\' email addresses will only be used in the sponsorship program. They will never be used for other purposes.' mod='allinone_rewards'}
						</p>
						<p class="checkbox">
							<input class="cgv" type="checkbox" name="conditionsValided" id="conditionsValided" value="1" {if isset($smarty.post.conditionsValided) AND $smarty.post.conditionsValided eq 1}checked="checked"{/if} />&nbsp;
							<label for="conditionsValided">{l s='I agree to the terms of service and adhere to them unconditionally.' mod='allinone_rewards'}</label>
							<a href="{$link->getModuleLink('allinone_rewards', 'rules', ['sback' => $sback], true)|escape:'strval'}" class="fancybox rules" title="{l s='Conditions of the sponsorship program' mod='allinone_rewards'}">{l s='Read conditions' mod='allinone_rewards'}</a>
						</p>
						<p>
							{l s='Preview' mod='allinone_rewards'} <a href="{$link->getModuleLink('allinone_rewards', 'email', ['sback' => $sback], true)|escape:'strval'}" class="fancybox mail" title="{l s='Invitation email' mod='allinone_rewards'}">{l s='the default email' mod='allinone_rewards'}</a> {l s='that will be sent to your friends.' mod='allinone_rewards'}
						</p>
						<p class="submit" align="center">
							<input type="submit" id="submitSponsorFriends" name="submitSponsorFriends" class="button_large" value="{l s='Send invitations' mod='allinone_rewards'}" />
						</p>
					</form>
				</div>
			</div>
		{else}
			<div>
				{l s='To become a sponsor, you need to have completed at least' mod='allinone_rewards'} {$orderQuantityS} {if $orderQuantityS > 1}{l s='orders' mod='allinone_rewards'}{else}{l s='order' mod='allinone_rewards'}{/if}.
			</div>
		{/if}
		</div>

		{if !isset($popup)}
		<div id="idTab2" class="sponsorshipBlock">
			{if $pendingFriends AND $pendingFriends|@count > 0}
			<div>
				{l s='These friends have not yet registered on this website since you sponsored them, but you can try again! To do so, mark the checkboxes of the friend(s) you want to remind, then click on the button "Remind my friends".' mod='allinone_rewards'}
			</div>
			<div>
				<form method="post" action="{$link->getModuleLink('allinone_rewards', 'sponsorship', [], true)|escape:'strval'}" class="std">
					<table class="std">
					<thead>
						<tr>
							<th class="first_item">&nbsp;</th>
							<th class="item">{l s='Last name' mod='allinone_rewards'}</th>
							<th class="item">{l s='First name' mod='allinone_rewards'}</th>
							<th class="item">{l s='Email' mod='allinone_rewards'}</th>
							<th class="last_item">{l s='Last invitation' mod='allinone_rewards'}</th>
						</tr>
					</thead>
					<tbody>
					{foreach from=$pendingFriends item=pendingFriend name=myLoop}
						<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}item{else}alternate_item{/if}">
							<td>
								<input type="checkbox" name="friendChecked[{$pendingFriend.id_sponsorship|escape:'intval'}]" id="friendChecked[{$pendingFriend.id_sponsorship|escape:'intval'}]" value="1" />
							</td>
							<td>{$pendingFriend.lastname|escape:'strval'}</td>
							<td>{$pendingFriend.firstname|escape:'strval'}</td>
							<td>{$pendingFriend.email|escape:'strval'}</td>
							<td>{dateFormat date=$pendingFriend.date_upd full=0}</td>
						</tr>
					{/foreach}
					</tbody>
					</table>
					<p class="submit" align="center">
						<input type="submit" value="{l s='Remind my friends' mod='allinone_rewards'}" name="revive" id="revive" class="button_large" />
					</p>
				</form>
			</div>
			{else}
			<div>
				{l s='You have not sponsored any friends.' mod='allinone_rewards'}
			</div>
			{/if}
		</div>

		<div id="idTab3" class="sponsorshipBlock">
			{if $subscribeFriends AND $subscribeFriends|@count > 0}
			<div>
				{l s='Here are sponsored friends who have accepted your invitation:' mod='allinone_rewards'}
			</div>
			<div>
				<table class="std">
				<thead>
					<tr>
						<th class="first_item">&nbsp;</th>
						<th class="item">{l s='Last name' mod='allinone_rewards'}</th>
						<th class="item">{l s='First name' mod='allinone_rewards'}</th>
						<th class="item">{l s='Email' mod='allinone_rewards'}</th>
						<th class="item">{l s='Channel' mod='allinone_rewards'}</th>
						<th class="last_item">{l s='Inscription date' mod='allinone_rewards'}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$subscribeFriends item=subscribeFriend name=myLoop}
					<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}item{else}alternate_item{/if}">
						<td>{$smarty.foreach.myLoop.iteration|escape:'intval'}.</td>
						<td>{$subscribeFriend.lastname|escape:'strval'}</td>
						<td>{$subscribeFriend.firstname|escape:'strval'}</td>
						<td>{$subscribeFriend.email|escape:'strval'}</td>
						<td>{if $subscribeFriend.channel==1}{l s='Email invitation' mod='allinone_rewards'}{elseif $subscribeFriend.channel==2}{l s='Sponsorship link' mod='allinone_rewards'}{elseif $subscribeFriend.channel==3}{l s='Facebook' mod='allinone_rewards'}{elseif $subscribeFriend.channel==4}{l s='Twitter' mod='allinone_rewards'}{elseif $subscribeFriend.channel==5}{l s='Google +1' mod='allinone_rewards'}{/if}</td>
						<td>{dateFormat date=$subscribeFriend.date_upd full=0}</td>
					</tr>
					{/foreach}
				</tbody>
				</table>
			</div>
			{else}
			<div>
				{l s='No sponsored friends have accepted your invitation yet.' mod='allinone_rewards'}
			</div>
			{/if}
		</div>
			{if $reward_allowed_s}
		<div id="idTab4" class="sponsorshipBlock">
			<div class="title">{l s='Details by registration channel' mod='allinone_rewards'}</div>
			<div>
				<table class="std">
					<thead>
						<tr>
							<th colspan="2" class="first_item left">{l s='Channels' mod='allinone_rewards'}</th>
							<th class="item center">{l s='Friends' mod='allinone_rewards'}</th>
							<th class="item center">{l s='Orders' mod='allinone_rewards'}</th>
							<th class="item center">{l s='Rewards' mod='allinone_rewards'}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="left" rowspan="5">{l s='My direct friends' mod='allinone_rewards'}</td>
							<td class="left">{l s='Email invitation' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb1|intval}</td>
							<td class="center">{$statistics.nb_orders_channel1|intval}</td>
							<td class="right">{displayPrice price=$statistics.direct_rewards1}</td>
						</tr>
						<tr>
							<td class="left">{l s='Sponsorship link' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb2|intval}</td>
							<td class="center">{$statistics.nb_orders_channel2|intval}</td>
							<td class="right">{displayPrice price=$statistics.direct_rewards2}</td>
						</tr>
						<tr>
							<td class="left">{l s='Facebook' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb3|intval}</td>
							<td class="center">{$statistics.nb_orders_channel3|intval}</td>
							<td class="right">{displayPrice price=$statistics.direct_rewards3}</td>
						</tr>
						<tr>
							<td class="left">{l s='Twitter' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb4|intval}</td>
							<td class="center">{$statistics.nb_orders_channel4|intval}</td>
							<td class="right">{convertPrice price=$statistics.direct_rewards4}</td>
						</tr>
						<tr>
							<td class="left">{l s='Google +1' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb5|intval}</td>
							<td class="center">{$statistics.nb_orders_channel5|intval}</td>
							<td class="right">{convertPrice price=$statistics.direct_rewards5}</td>
						</tr>
				{if $multilevel}
						<tr>
							<td class="left" colspan="2">{l s='Indirect friends' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.indirect_nb|intval}</td>
							<td class="center">{$statistics.indirect_nb_orders|intval}</td>
							<td class="right">{displayPrice price=$statistics.indirect_rewards}</td>
						</tr>
				{/if}
						<tr class="total">
							<td class="left" colspan="2">{l s='Total' mod='allinone_rewards'}</td>
							<td class="center">{$statistics.direct_nb1+$statistics.direct_nb2+$statistics.direct_nb3+$statistics.direct_nb4+$statistics.direct_nb5+$statistics.indirect_nb|intval}</td>
							<td class="center">{$statistics.nb_orders_channel1+$statistics.nb_orders_channel2+$statistics.nb_orders_channel3+$statistics.nb_orders_channel4+$statistics.nb_orders_channel5+$statistics.indirect_nb_orders|intval}</td>
							<td class="right">{convertPrice price=$statistics.direct_rewards1+$statistics.direct_rewards2+$statistics.direct_rewards3+$statistics.direct_rewards4+$statistics.direct_rewards5+$statistics.indirect_rewards}</td>
						</tr>
					</tbody>
				</table>
			</div>

				{if $multilevel && $statistics.sponsored1}
			<div class="title">{l s='Details by sponsorship level' mod='allinone_rewards'}</div>
			<table class="std">
				<thead>
					<tr>
						<th class="first_item left">{l s='Level' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Friends' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Orders' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Rewards' mod='allinone_rewards'}</th>
					</tr>
				</thead>
				<tbody>
					{section name=levels start=0 loop=$statistics.maxlevel step=1}
						{assign var="indiceFriends" value="nb`$smarty.section.levels.iteration`"}
						{assign var="indiceOrders" value="nb_orders`$smarty.section.levels.iteration`"}
						{assign var="indiceRewards" value="rewards`$smarty.section.levels.iteration`"}
					<tr>
						<td class="left">{l s='Level' mod='allinone_rewards'} {$smarty.section.levels.iteration|escape:'intval'}</td>
						<td class="center">{if isset($statistics[$indiceFriends])}{$statistics[$indiceFriends]|intval}{else}0{/if}</td>
						<td class="center">{if isset($statistics[$indiceOrders])}{$statistics[$indiceOrders]|intval}{else}0{/if}</td>
						<td class="right">{if isset($statistics[$indiceRewards])}{displayPrice price=$statistics[$indiceRewards]}{else}{displayPrice price=0}{/if}</td>
					</tr>
					{/section}
					<tr class="total">
						<td class="left">{l s='Total' mod='allinone_rewards'}</td>
						<td class="center">{$statistics.direct_nb1+$statistics.direct_nb2+$statistics.direct_nb3+$statistics.direct_nb4+$statistics.direct_nb5+$statistics.indirect_nb|intval}</td>
						<td class="center">{$statistics.nb_orders_channel1+$statistics.nb_orders_channel2+$statistics.nb_orders_channel3+$statistics.nb_orders_channel4+$statistics.nb_orders_channel5+$statistics.indirect_nb_orders|intval}</td>
						<td class="right">{convertPrice price=$statistics.direct_rewards1+$statistics.direct_rewards2+$statistics.direct_rewards3+$statistics.direct_rewards4+$statistics.direct_rewards5+$statistics.indirect_rewards}</td>
					</tr>
				</tbody>
			</table>
				{/if}

				{if $statistics.sponsored1}
			<div class="title">{l s='Details for my direct friends' mod='allinone_rewards'}</div>
			<table class="std">
				<thead>
					<tr>
						<th class="first_item left">{l s='Name' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Orders' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Rewards' mod='allinone_rewards'}</th>
					{if $multilevel}
						<th class="item center">{l s='Friends' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Friends\' orders' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Rewards' mod='allinone_rewards'}</th>
						<th class="item center">{l s='Total' mod='allinone_rewards'}</th>
					{/if}
					</tr>
				</thead>
				<tbody>
					{foreach from=$statistics.sponsored1 item=sponsored name=myLoop}
						{assign var="indiceDirect" value="direct_customer`$sponsored.id_customer`"}
						{assign var="indiceIndirect" value="indirect_customer`$sponsored.id_customer`"}
						{if isset($statistics[$indiceDirect])}
							{assign var="valueDirect" value=$statistics[$indiceDirect]}
						{else}
							{assign var="valueDirect" value=0}
						{/if}
						{if isset($statistics[$indiceIndirect])}
							{assign var="valueIndirect" value=$statistics[$indiceIndirect]}
						{else}
							{assign var="valueIndirect" value=0}
						{/if}
					<tr>
						<td class="left">{$sponsored.lastname|escape:'strval'} {$sponsored.firstname|escape:'strval'}</td>
						<td class="center">{$sponsored.direct_orders|intval}</td>
						<td class="right">{displayPrice price=$sponsored.direct}</td>
						{if $multilevel}
						<td class="center">{$valueDirect+$valueIndirect|intval}</td>
						<td class="center">{$sponsored.indirect_orders|intval}</td>
						<td class="right">{displayPrice price=$sponsored.indirect}</td>
						<td class="total right">{displayPrice price=$sponsored.direct+$sponsored.indirect}</td>
						{/if}
					</tr>
					{/foreach}
					<tr class="total">
						<td class="left">{l s='Total' mod='allinone_rewards'}</td>
						<td class="center">{$statistics.total_direct_orders|intval}</td>
						<td class="right">{convertPrice price=$statistics.total_direct_rewards}</td>
						{if $multilevel}
						<td class="center">{$statistics.indirect_nb|intval}</td>
						<td class="center">{$statistics.total_indirect_orders|intval}</td>
						<td class="right">{convertPrice price=$statistics.total_indirect_rewards}</td>
						<td class="right">{convertPrice price=$statistics.direct_rewards1+$statistics.direct_rewards2+$statistics.direct_rewards3+$statistics.direct_rewards4+$statistics.direct_rewards5+$statistics.indirect_rewards}</td>
						{/if}
					</tr>
				</tbody>
			</table>
				{/if}
		</div>
	</div>
			{/if}
		{/if}
	{/if}
</div>
{else}
	{if $error}
<p class="error">
		{if $error == 'login failed'}
			{l s='Login failed. Please check the email and password you have provided' mod='allinone_rewards'}
		{elseif $error == 'email is missing'}
			{l s='Please enter your email to connect to your contacts list' mod='allinone_rewards'}
		{elseif $error == 'password is missing'}
			{l s='Please enter your password to connect to your contacts list' mod='allinone_rewards'}
		{elseif $error == 'unable to get contacts'}
			{l s='Sorry, we were unable to get your contacts list' mod='allinone_rewards'}
		{else}
			{l s='Error :' mod='allinone_rewards'} {$error|escape:'strval'}
		{/if}
</p>
	{else}
<form id="open_inviter_contacts_form" method="post" action="{$link->getModuleLink('allinone_rewards', 'sponsorship', [], true)|escape:'strval'}">
<div>
	<table class="std">
		<thead>
			<tr>
				<th class="first_item"><input type="checkbox" id="checkall" /></th>
				<th class="item">{l s='Name' mod='allinone_rewards'}</th>
				<th class="last_item">{l s='Email' mod='allinone_rewards'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$open_inviter_contacts key=email item=name name=myLoop}
			<tr class="{if ($smarty.foreach.myLoop.iteration % 2) == 0}item{else}alternate_item{/if}">
				<td><input {if isset($smarty.post.friendsEmail[$smarty.foreach.myLoop.index])}checked{/if} type="checkbox" name="friendsEmail[{$smarty.foreach.myLoop.index}]" value="{$email}" /></td>
				<td>{$name|escape:'strval'}</td>
				<td>{$email|escape:'strval'}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
<div>
	<input class="cgv" type="checkbox" name="conditionsValided" id="conditionsValided" value="1"  />&nbsp;
	<label for="conditionsValided">{l s='I agree to the terms of service and adhere to them unconditionally.' mod='allinone_rewards'}</label>
	<a href="{$link->getModuleLink('allinone_rewards', 'rules', ['sback' => $sback], true)|escape:'strval'}" class="fancybox rules" title="{l s='Conditions of the sponsorship program' mod='allinone_rewards'}">{l s='Read conditions' mod='allinone_rewards'}</a>
</div>
<div class="submit" align="center">
	<input type="submit" id="submitOpenInviter2" name="submitOpenInviter2" class="button_large" value="{l s='Send invitations' mod='allinone_rewards'}" />
</div>
</form>
	{/if}
{/if}