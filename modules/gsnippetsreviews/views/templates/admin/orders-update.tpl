{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-9">
		<h2>{l s='Review invitation e-mails sent from your past orders' mod='gsnippetsreviews'}</h2>

		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		{if !empty($aErrors)}
		{include file="`$sErrorInclude`"}
		<div class="clr_10"></div>
		{/if}

		{* USE CASE - REMINDERS SENT *}
		{if !empty($iSentReminders)}
		<div class="alert alert-success">
			<strong>{l s='Total of review invitation e-mails sent' mod='gsnippetsreviews'} : {$iSentReminders|intval}</strong>
		</div>
		<div class="clr_20"></div>
		{/if}

		{* USE CASE - REMINDERS NOT SENT because products are already reviewed *}
		{if !empty($iReviewedReminders)}
			<div class="alert alert-warning">
				<strong>{l s='Total of review invitation e-mails not sent because products are already reviewed' mod='gsnippetsreviews'} : {$iReviewedReminders|intval}</strong>
			</div>
			<div class="clr_20"></div>
		{/if}

		{* USE CASE - REMINDERS ERRORS *}
		{if !empty($iErrorReminders)}
		<div class="alert alert-danger">
			<strong>{l s='Total of review invitation e-mails not sent' mod='gsnippetsreviews'} : {$iErrorReminders|intval}</strong>
		</div>
		<div class="clr_20"></div>
		{/if}


		{if !empty($aReminders)}
		<h3>{l s='Review invitation e-mail details' mod='gsnippetsreviews'}</h3>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="alert alert-info">
			<strong style="font-weight: bold;">{l s='You can click on the order ID to view order details or the customer name or e-mail to view customer details.' mod='gsnippetsreviews'}</strong><br />
			<strong style="font-weight: bold;">{l s='Green lines mean the e-mail was sent, red lines mean there was an exceptional error (error returned by the PrestaShop native mail method, it could mean a bad e-mail address or server problem).' mod='gsnippetsreviews'} {l s='Or it also could mean the products of the order are already all reviewed by the customer.' mod='gsnippetsreviews'}</strong><br />
		</div>

		<div class="clr_10"></div>

		<table cellspacing="0" cellpadding="0" class="table table-responsive table-bordered table-striped">
			<thead>
				<tr class="nodrag nodrop">
					<th class="center"><strong>{l s='Order' mod='gsnippetsreviews'}</strong></th>
					<th class="center"><strong>{l s='Customer' mod='gsnippetsreviews'}</strong></th>
				</tr>
			</thead>
			{foreach from=$aReminders key=id item=aReminder}
				<tr class="{if empty($aReminder.error)}success{else}danger{/if}">
					<td class="center">
						<a href="{$aReminder.sBackOrderLink|escape:'htmlall':'UTF-8'}" alt="{l s='Display the customer back-office page' mod='gsnippetsreviews'}" target="_blank" style="color: #666;">{$aReminder.sRef|escape:'UTF-8'}</a>
					</td>
					<td class="center">
						<a href="{$aReminder.sBackCustomerLink|escape:'htmlall':'UTF-8'}" alt="{l s='Display the customer back-office page' mod='gsnippetsreviews'}" target="_blank" style="color: #666;">{$aReminder.sCustomer|escape:'UTF-8'}</a>
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="clr_20"></div>
		{/if}

		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div id="bt_error-orders-import-update"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="pull-right">
					<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Close' mod='gsnippetsreviews'}</button>
				</div>
			</div>
		</div>
	</div>
</div>