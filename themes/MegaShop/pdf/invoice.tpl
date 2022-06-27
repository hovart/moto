<div style="font-size: 8pt; color: #444">

<!-- ADDRESSES -->
<table style="width: 100%; font-size: 13pt; color: #000;">
	<tr>
		<td style="width: 48%;"><span style="font-weight: bold;">{l s='Billing Address' pdf='true'}</span></td>
		<td style="width: 4%">&nbsp;</td>
		<td style="width: 48%;"><span style="font-weight: bold;">{l s='Delivery Address' pdf='true'}</span></td>
	</tr>
	<tr>
		<td style="height: 105px;"><br /><br />{$invoice_address}</td>
		<td>&nbsp;</td>
		<td style="height: 105px;"><br /><br />{if empty($delivery_address)}{$invoice_address}{else}{$delivery_address}{/if}</td>
	</tr>
</table>
<!-- / ADDRESSES -->

<br /><br />

<table style="width: 100%; text-align: center; border: 1px solid #CCC; font-size: 9pt;">
	<tr>
		<td style="width: 33%; background-color: #CCC; color: #000;">
			<b>{l s='Order Reference:' pdf='true'}</b>
		</td>
		<td style="width: 33%; background-color: #CCC; color: #000;">
			<b>{l s='Order Date:' pdf='true'}</b>
		</td>
		<td style="width: 34%; background-color: #CCC; color: #000;">
			<b>{l s='Payment Method:' pdf='true'}</b>
		</td>
	</tr>
	<tr>
		<td style="width: 33%;">
			{$order->getUniqReference()}
		</td>
		<td style="width: 33%;">
			{$order->date_add|date_format:"%d-%m-%Y %H:%M"}
		</td>
		<td style="width: 34%;">
			{foreach from=$order_invoice->getOrderPaymentCollection() item=payment}
				<b>{$payment->payment_method}</b> : {displayPrice price=$payment->amount currency=$order->id_currency}
			{foreachelse}
				{l s='No payment' pdf='true'}
			{/foreach}
		</td>
	</tr>
</table>

<br />

{foreach from=$order->getShipping() item=shipping name=shippingsLoop}
	<table style="width: 100%; text-align: center; border: 1px solid #CCC; font-size: 9pt;">
		<tr>
			<td colspan = "3" style="background-color: #CCC; color: #000;">
				<b>{l s='Shipping #' pdf='true'}{$smarty.foreach.shippingsLoop.iteration}</b>
			</td>
		</tr>
		<tr>
			<td style="width: 33%; background-color: #EEE; color: #000;">
				<b>{l s='Weight:' pdf='true'}</b>
			</td>
			<td style="width: 33%; background-color: #EEE; color: #000;">
				<b>{l s='Carrier Name:' pdf='true'}</b>
			</td>
			<td style="width: 34%; background-color: #EEE; color: #000;">
				<b>{l s='Tracking Number:' pdf='true'}</b>
			</td>
		</tr>
		<tr>
			<td style="width: 33%;">
				{$shipping.weight|string_format:"%.3f"} Kg
			</td>
			<td style="width: 33%;">
				{$shipping.state_name}
			</td>
			<td style="width: 34%;">
				{$shipping.tracking_number}
			</td>
		</tr>
	</table>
{/foreach}

<br /><br />
		
<!-- PRODUCTS TAB -->
<table style="width: 100%; font-size: 8pt;">
	<tr style="line-height:4px;">
		<td style="text-align: left; background-color: #CCC; color: #000; padding-left: 10px; font-weight: bold; width: 12%">{l s='Reference' pdf='true'}</td>
		<td style="text-align: left; background-color: #CCC; color: #000; padding-left: 10px; font-weight: bold; width: 41%">{l s='Product' pdf='true'}</td>
		<!-- unit price tax excluded is mandatory -->
		{if !$tax_excluded_display}
			<td style="background-color: #CCC; color: #000; text-align: right; font-weight: bold; width: 10%">{l s='Unit Price' pdf='true'} <br />{l s='(Tax Excl.)' pdf='true'}</td>
		{/if}
		<td style="background-color: #CCC; color: #000; text-align: right; font-weight: bold; width: 11%">
			{l s='Unit Price' pdf='true'}
			{if $tax_excluded_display}
				 {l s='(Tax Excl.)' pdf='true'}
			{else}
				 {l s='(Tax Incl.)' pdf='true'}
			{/if}
		</td>
		<td style="background-color: #CCC; color: #000; text-align: right; font-weight: bold; width: 9%">{l s='Discount' pdf='true'}</td>
		<td style="background-color: #CCC; color: #000; text-align: center; font-weight: bold; width: 5%">{l s='Qty' pdf='true'}</td>
		<td style="background-color: #CCC; color: #000; text-align: right; font-weight: bold; width: 12%">
			{l s='Total' pdf='true'}
			{if $tax_excluded_display}
				{l s='(Tax Excl.)' pdf='true'}
			{else}
				{l s='(Tax Incl.)' pdf='true'}
			{/if}
		</td>
	</tr>
	<!-- PRODUCTS -->
	{foreach $order_details as $order_detail}
	{cycle values='#FFF,#EEE' assign=bgcolor}
	<tr style="line-height:6px;background-color:{$bgcolor};">
		<td style="text-align: center;">
			{if !empty($order_detail.product_reference)}
				{$order_detail.product_reference}
			{else}
				--
			{/if}
		</td>
		<td style="text-align: left;">{$order_detail.product_name}</td>
		<!-- unit price tax excluded is mandatory -->
		{if !$tax_excluded_display}
			<td style="text-align: right; width: 10%">
			{displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl}
			</td>
		{/if}
		<td style="text-align: right;">
		{if $tax_excluded_display}
			{displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl}
		{else}
			{displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_incl}
		{/if}
		</td>
		<td style="text-align: right;">
		{if (isset($order_detail.reduction_amount) && $order_detail.reduction_amount > 0)}
			-{displayPrice currency=$order->id_currency price=$order_detail.reduction_amount}
		{else if (isset($order_detail.reduction_percent) && $order_detail.reduction_percent > 0)}
			-{$order_detail.reduction_percent}%
		{else}
		--
		{/if}
		</td>
		<td style="text-align: center;">{$order_detail.product_quantity}</td>
		<td style="text-align: right;">
		{if $tax_excluded_display}
			{displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl}
		{else}
			{displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_incl}
		{/if}
		</td>
	</tr>
		{foreach $order_detail.customizedDatas as $customizationPerAddress}
			{foreach $customizationPerAddress as $customizationId => $customization}
				<tr style="line-height:6px;background-color:{$bgcolor}; ">
					<td style="line-height:3px; text-align: left; width: 60%; vertical-align: top">

							<blockquote>
								{if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
									{foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
										{$customization_infos.name}: {$customization_infos.value}
										{if !$smarty.foreach.custo_foreach.last}<br />
										{else}
										<div style="line-height:0.4pt">&nbsp;</div>
										{/if}
									{/foreach}
								{/if}

								{if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
									{count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])} {l s='image(s)' pdf='true'}
								{/if}
							</blockquote>
					</td>
					<td style="text-align: right; width: 15%"></td>
					<td style="text-align: center; width: 10%; vertical-align: top">({$customization.quantity})</td>
					<td style="width: 15%; text-align: right;"></td>
				</tr>
			{/foreach}
		{/foreach}
	{/foreach}
	<!-- END PRODUCTS -->

	<!-- CART RULES -->
	{assign var="shipping_discount_tax_incl" value="0"}
	{foreach $cart_rules as $cart_rule}
	{cycle values='#FFF,#DDD' assign=bgcolor}
		<tr style="line-height:6px;background-color:{$bgcolor}; text-align:right;">
			<td colspan="{if !$tax_excluded_display}6{else}5{/if}">{$cart_rule.name}</td>
			<td>
				{if $cart_rule.free_shipping}
					{assign var="shipping_discount_tax_incl" value=$order_invoice->total_shipping_tax_incl}
				{/if}
				{if $tax_excluded_display}
					- {displayPrice currency=$order->id_currency price=$cart_rule.value_tax_excl}
				{else}
					- {displayPrice currency=$order->id_currency price=$cart_rule.value}
				{/if}
			</td>
		</tr>
	{/foreach}
	<!-- END CART RULES -->
</table>

<table style="width: 100%;">
	{if (($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl) > 0)}
	<tr style="line-height:5px;">
		<td style="width: 85%; text-align: right; font-weight: bold">{l s='Product Total (Tax Excl.)' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_products}</td>
	</tr>

	<tr style="line-height:5px;">
		<td style="width: 85%; text-align: right; font-weight: bold">{l s='Product Total (Tax Incl.)' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_products_wt}</td>
	</tr>
	{else}
	<tr style="line-height:5px;">
		<td style="width: 85%; text-align: right; font-weight: bold">{l s='Product Total' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_products}</td>
	</tr>
	{/if}

	{if $order_invoice->total_discount_tax_incl > 0}
	<tr style="line-height:5px;">
		<td style="text-align: right; font-weight: bold">{l s='Total Vouchers' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">-{displayPrice currency=$order->id_currency price=($order_invoice->total_discount_tax_incl + $shipping_discount_tax_incl)}</td>
	</tr>
	{/if}

	{if $order_invoice->total_wrapping_tax_incl > 0}
	<tr style="line-height:5px;">
		<td style="text-align: right; font-weight: bold">{l s='Wrapping Cost' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">
		{if $tax_excluded_display}
			{displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_excl}
		{else}
			{displayPrice currency=$order->id_currency price=$order_invoice->total_wrapping_tax_incl}
		{/if}
		</td>
	</tr>
	{/if}

	{if $order_invoice->total_shipping_tax_incl > 0}
	<tr style="line-height:5px;">
		<td style="text-align: right; font-weight: bold">{l s='Shipping Cost' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">
			{if $tax_excluded_display}
				{displayPrice currency=$order->id_currency price=$order_invoice->total_shipping_tax_excl}
				{else}
				{displayPrice currency=$order->id_currency price=$order_invoice->total_shipping_tax_incl}
			{/if}
		</td>
	</tr>
	{/if}

	{if ($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl) > 0}
	<tr style="line-height:5px;">
		<td style="text-align: right; font-weight: bold">{l s='Total Tax' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">{displayPrice currency=$order->id_currency price=($order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl)}</td>
	</tr>
	{/if}

	<tr style="line-height:5px;">
		<td style="text-align: right; font-weight: bold">{l s='Total' pdf='true'}</td>
		<td style="width: 15%; text-align: right;">{displayPrice currency=$order->id_currency price=$order_invoice->total_paid_tax_incl}</td>
	</tr>

</table>
<!-- / PRODUCTS TAB -->

{$tax_tab}

{if isset($order_invoice->note) && $order_invoice->note}
<div style="line-height: 1pt">&nbsp;</div>
<table style="width: 100%">
	<tr>
		<td style="width: 15%"></td>
		<td style="width: 85%">{$order_invoice->note|nl2br}</td>
	</tr>
</table>
{/if}

{if isset($HOOK_DISPLAY_PDF)}

<table style="width: 100%">
	<tr>
		<td style="width: 15%"></td>
		<td style="width: 85%">{$HOOK_DISPLAY_PDF}</td>
	</tr>
</table>
{/if}

<table style="width: 100% ; font-size:13pt; font-weight: bold">
	<tr>
		<td style="width: 100%  ; height : 25px">{l s='In case of return' pdf='true'}</td>
	</tr>
</table>

</div>