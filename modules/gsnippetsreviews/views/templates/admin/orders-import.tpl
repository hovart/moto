{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	{* USE CASE - orders import mode *}
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post" id="bt_orders-import-form" name="bt_orders-import-form" onsubmit="oGsr.form('bt_orders-import-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_orders-import-form', 'bt_orders-import-form', false, true, null, 'orders-import', 'orders-import');return false;">
		<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sAction" value="{$aQueryParams.selectOrders.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.selectOrders.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="bt_date-from" value="{$sDateFrom|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="bt_date-to" value="{$sDateTo|escape:'htmlall':'UTF-8'}" />

		<h3>{l s='Send reminders e-mails from your past orders on the shop:' mod='gsnippetsreviews'} {$sShopName|escape:'htmlall':'UTF-8'}</h3>

		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		{if !empty($aErrors)}
		{include file="`$sErrorInclude`"}
		<div class="clr_10"></div>
		{/if}

		{* USE CASE - one status is checked at least *}
		{if !empty($aStatusSelection)}
			{if !empty($iNbOrders)}
				{if !empty($iNbOrdersToSend)}
					<div class="clr_10"></div>
					<div class="alert alert-info">
						<strong style="color: red; font-weight: bold;">{l s='IMPORTANT NOTE:' mod='gsnippetsreviews'}</strong> {l s='You are about to send your review invitations to customers who placed past orders. They will be selected based on the order dates you have selected and the order statuses you have checked in the previous screen.' mod='gsnippetsreviews'}<br /><br />
						{l s='You can however click the "Orders detail" blue button below to have better control over who will receive the e-mails. If you do not check any checkboxes there, everyone (except red lines) will receive the e-mail.' mod='gsnippetsreviews'}<br />
					</div>

					{* USE CASE - check if orders have been already sent and how many times *}
					{if !empty($iOrdersSent)}
					<div class="clr_10"></div>
					<div class="alert alert-warning">
						{l s='Warning: ' mod='gsnippetsreviews'} <strong>{$iOrdersSent|intval}</strong>  {l s=' orders have already received invitation e-mails.' mod='gsnippetsreviews'} <br />
						{l s='You can get more information by clicking the blue "See orders detail" button below. Lines in yellow have already received 1 or more e-mails (the number of times will be indicated on each line).' mod='gsnippetsreviews'}<br />
					</div>
					{/if}

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-4 col-lg-3"><strong>{l s='Selected period' mod='gsnippetsreviews'}</strong> : </label>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">{l s='From' mod='gsnippetsreviews'}:</span>
								<input type="text" class="datepicker input-medium" name="bt_order-date-from" value="{$sDateFrom|escape:'htmlall':'UTF-8'}" id="bt_order-date-from">
								<span class="input-group-addon">{l s='To:' mod='gsnippetsreviews'}</span>
								<input type="text" class="datepicker input-medium" name="bt_order-date-to" value="{$sDateTo|escape:'htmlall':'UTF-8'}" id="bt_order-date-to">
								<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
							</div>
						</div>
					</div>

					<div class="clr_20"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-4 col-lg-3" for="bt_order-statuses"><strong>{l s='Selected Order statuses' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<table cellspacing="0" cellpadding="0" class="table table-responsive table-bordered table-striped">
								<tr class="nodrag nodrop">
									<td class="center"><strong>{l s='Order state' mod='gsnippetsreviews'}</strong></td>
									<td class="center"></td>
								</tr>
								{foreach from=$aOrderStatusTitle key=id item=aOrder}
									{foreach from=$aStatusSelection key=key item=iIdSelect}
										{if $iIdSelect == $id}
										<tr>
											<td>
												{$aOrder[$iCurrentLang]|escape:'htmlall':'UTF-8'}
											</td>
											<td class="center">
												<input type="checkbox" name="{$sModuleName|escape:'htmlall':'UTF-8'}OrderStatus[]" id="{$sModuleName|escape:'htmlall':'UTF-8'}OrderStatus" value="{$id|escape:'htmlall':'UTF-8'}" checked="checked" disabled="disabled" />
											</td>
										</tr>
										{/if}
									{/foreach}
								{/foreach}
							</table>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-4 col-lg-3"><strong>{l s='Total reminder e-mails to send' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-5">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<div class="input-group">
									<input type="text" value="{$iNbOrdersToSend|intval}" disabled="disabled" />
								</div>
							</div>
							{if !empty($aOrdersDetail)}
								<span onclick="$('#bt_orders-detail').slideToggle();" class="btn btn-info btn">{l s='See orders detail' mod='gsnippetsreviews'}&nbsp;<span class="icon-eye-open"></span></span>
							{/if}
						</div>
					</div>

					{if !empty($aOrdersDetail)}
					<div id="bt_orders-detail" style="display: none;">
						<div class="clr_20"></div>
						<div class="btn-actions">
							<div class="btn btn-default btn-mini" id="ordersCheck" onclick="return oGsr.selectAll('.myCheckbox', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gsnippetsreviews'}</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGsr.selectAll('.myCheckbox', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gsnippetsreviews'}</div>
							<div class="clr_10"></div>
						</div>
						<table cellspacing="0" cellpadding="0" class="table table-responsive table-bordered table-striped">
							<thead>
							<tr class="nodrag nodrop">
								<th class="center"><strong>{l s='Manual selection' mod='gsnippetsreviews'}</strong></th>
								<th class="center"><strong>{l s='Order' mod='gsnippetsreviews'}</strong></th>
								<th class="center"><strong>{l s='Order date' mod='gsnippetsreviews'}</strong></th>
								<th class="center"><strong>{l s='Customer' mod='gsnippetsreviews'}</strong></th>
								<th class="center"><strong>{l s='The order will receive an invitation e-mail?' mod='gsnippetsreviews'}</strong></th>
							</tr>
							</thead>
							{foreach from=$aOrdersDetail.ok key=id item=aDetailOk}
								<tr class="{if empty($aDetailOk.sent)}success{else}warning{/if}">
									<td class="center">
										<input type="checkbox" name="bt_orders-to-send[]" id="bt_orders-to-send" value="{$aDetailOk.id|escape:'htmlall':'UTF-8'}" class="myCheckbox" />
									</td>
									<td class="center">
										{if !empty($aDetailOk.ref)}{$aDetailOk.ref|escape:'htmlall':'UTF-8'}{else}{$aDetailOk.id|intval}{/if}
									</td>
									<td class="center">
										{if !empty($aDetailOk.date)}{$aDetailOk.date|escape:'htmlall':'UTF-8'}{else}{l s='No information' mod='gsnippetsreviews'}{/if}
									</td>
									<td class="center">
										{if !empty($aDetailOk.customer)}{$aDetailOk.customer|escape:'htmlall':'UTF-8'}{else}{l s='No information' mod='gsnippetsreviews'}{/if}
									</td>
									<td class="center">
										{if empty($aDetailOk.sent)}
										{l s='Yes' mod='gsnippetsreviews'}
										{else}
										{l s='Yes. But e-mail was already sent' mod='gsnippetsreviews'} <strong>{$aDetailOk.sent|intval}</strong> {l s='times' mod='gsnippetsreviews'} ({l s='last time on the' mod='gsnippetsreviews'} <strong>{$aDetailOk.date_last|escape:'htmlall':'UTF-8'}</strong>)
										{/if}
									</td>
								</tr>
							{/foreach}
							{if !empty($aOrdersDetail)}
							{foreach from=$aOrdersDetail.ko key=id item=aDetailKo}
								<tr class="danger">
									<td class="center">
										<input type="checkbox" name="bt_orders-to-send[]" id="bt_orders-to-send" value="{$aDetailKo.id|escape:'htmlall':'UTF-8'}" disabled="disabled" />
									</td>
									<td class="center">
										{if !empty($aDetailKo.ref)}{$aDetailKo.ref|escape:'htmlall':'UTF-8'}{else}{$aDetailKo.id|intval}{/if}
									</td>
									<td class="center">
										{if !empty($aDetailKo.date)}{$aDetailKo.date|escape:'htmlall':'UTF-8'}{else}{l s='No information' mod='gsnippetsreviews'}{/if}
									</td>
									<td class="center">
										{if !empty($aDetailKo.customer)}{$aDetailKo.customer|escape:'htmlall':'UTF-8'}{else}{l s='No information' mod='gsnippetsreviews'}{/if}
									</td>
									<td class="center">
										{if $aDetailKo.state == 'not_order_status'}
										{l s='No. The order status doesn\'t match to one of them above' mod='gsnippetsreviews'}
										{elseif $aDetailKo.state == 'not_active_customer'}
										{l s='No. The customer who placed this order is not active on this shop' mod='gsnippetsreviews'}
										{elseif $aDetailKo.state == 'not_customer'}
										{l s='No. The customer doesn\'t exist on this shop' mod='gsnippetsreviews'}
										{elseif $aDetailKo.state == 'not_order_shop'}
										{l s='No. The order has not been placed on this shop' mod='gsnippetsreviews'}
										{else}
										{l s='No. The order doesn\'t exist on this shop' mod='gsnippetsreviews'}
										{/if}
									</td>
								</tr>
							{/foreach}
							{/if}
						</table>
					</div>
					{/if}

					<div class="clr_10"></div>
					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							<div id="bt_error-review"></div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							<div class="pull-right">
								<button class="btn btn-success btn-lg" onclick="oGsr.form('bt_orders-import-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_orders-import-form', 'bt_orders-import-form', false, true, null, 'orders-import', 'orders-import');return false;">{l s='Confirm send' mod='gsnippetsreviews'}</button>
								<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
							</div>
						</div>
					</div>
				{else}
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
							<div class="input-group">
								<span class="input-group-addon">{l s='From' mod='gsnippetsreviews'}:</span>
								<input type="text" class="datepicker input-medium" name="bt_order-date-from" value="{$sDateFrom|escape:'htmlall':'UTF-8'}" id="bt_order-date-from">
								<span class="input-group-addon">{l s='To:' mod='gsnippetsreviews'}</span>
								<input type="text" class="datepicker input-medium" name="bt_order-date-to" value="{$sDateTo|escape:'htmlall':'UTF-8'}" id="bt_order-date-to">
								<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
							</div>
						</div>
					</div>

					<div class="alert alert-danger col-xs-10 col-md-10 col-lg-10">
						<strong style="font-weight: bold;">{$iNbOrders|intval} {l s='has been found on this period but any of them has its status corresponding to the statuses selected in the module.' mod='gsnippetsreviews'}</strong>
					</div>
					<div class="clr_10"></div>
					<div class="clr_hr"></div>
					<div class="clr_20"></div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<div id="bt_error-review"></div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<div class="pull-right">
								<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
							</div>
						</div>
					</div>
				{/if}
			{else}
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
						<div class="input-group">
							<span class="input-group-addon">{l s='From' mod='gsnippetsreviews'}:</span>
							<input type="text" class="datepicker input-medium" name="bt_order-date-from" value="{$sDateFrom|escape:'htmlall':'UTF-8'}" id="bt_order-date-from">
							<span class="input-group-addon">{l s='To:' mod='gsnippetsreviews'}</span>
							<input type="text" class="datepicker input-medium" name="bt_order-date-to" value="{$sDateTo|escape:'htmlall':'UTF-8'}" id="bt_order-date-to">
							<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
						</div>
					</div>
				</div>

				<div class="alert alert-danger col-xs-10 col-md-10 col-lg-10">
					<strong style="font-weight: bold;">{l s='Any order to send an invitation e-mail has been found on this period' mod='gsnippetsreviews'}</strong>
				</div>

				<div class="clr_10"></div>
				<div class="clr_hr"></div>
				<div class="clr_20"></div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<div id="bt_error-review"></div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="pull-right">
							<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
						</div>
					</div>
				</div>
			{/if}
		{else}
		<div class="alert alert-danger col-xs-10 col-md-10 col-lg-10">
			<strong style="font-weight: bold;">{l s='Any status is selected, so you cannot import your past orders in order to send a review invitation e-mail.' mod='gsnippetsreviews'}</strong>
		</div>
		{/if}
	</form>

	<div class="clr_20"></div>

	<div id="bt_loading-div-orders-import" style="display: none;">
		<div class="alert alert-warning">
			<p class="center"><img src="{$sLoaderLarge|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p class="center">{l s='Review invitation e-mails sending is in progress ...' mod='gsnippetsreviews'}</p>
		</div>
	</div>
</div>