{if $tax_exempt}
	<div style = "text-align: center">
		{l s='Exempt of VAT according section 259B of the General Tax Code.' pdf='true'}
	</div>
{else}
	<br /><br />
	<!--  TAX DETAILS -->
	<table style="width: 100%">
		<tr>
			<td style="text-align: left; background-color: #CCC; color: #FFF; padding-left: 10px; font-weight: bold; width: 40%">{l s='Tax Detail' pdf='true'}</td>
			<td style="text-align: right; background-color: #CCC; color: #FFF; padding-left: 10px; font-weight: bold; width: 20%">{l s='Tax Rate' pdf='true'}</td>
			{if !$use_one_after_another_method}
				<td style="text-align: right; background-color: #CCC; color: #FFF; padding-left: 10px; font-weight: bold; width: 20%">{l s='Total Tax Excl' pdf='true'}</td>
			{/if}
			<td style="text-align: right; background-color: #CCC; color: #FFF; padding-left: 10px; font-weight: bold; width: 20%">{l s='Total Tax' pdf='true'}</td>
		</tr>

		{if isset($product_tax_breakdown)}
			{foreach $product_tax_breakdown as $rate => $product_tax_infos}
				<tr style="line-height:6px;background-color:{cycle values='#FFF,#EEE'};">
					<td style="width: 40%">{l s='Products' pdf='true'}</td>
					<td style="width: 20%; text-align: right;">{$rate} %</td>
					{if !$use_one_after_another_method}
						<td style="width: 20%; text-align: right;">
							{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$product_tax_infos.total_price_tax_excl}
						</td>
					{/if}
					<td style="width: 20%; text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$product_tax_infos.total_amount}</td>
				</tr>
			{/foreach}
		{/if}

		{if isset($shipping_tax_breakdown)}
			{foreach $shipping_tax_breakdown as $shipping_tax_infos}
				<tr style="line-height:6px;background-color:{cycle values='#FFF,#EEE'};">
					<td style="width: 40%">{l s='Shipping' pdf='true'}</td>
					<td style="width: 20%; text-align: right;">{$shipping_tax_infos.rate} %</td>
					{if !$use_one_after_another_method}
						<td style="width: 20%; text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$shipping_tax_infos.total_tax_excl}</td>
					{/if}
					<td style="width: 20%; text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$shipping_tax_infos.total_amount}</td>
				</tr>
			{/foreach}
		{/if}

		{if isset($ecotax_tax_breakdown)}
			{foreach $ecotax_tax_breakdown as $ecotax_tax_infos}
				{if $ecotax_tax_infos.ecotax_tax_excl > 0}
					<tr style="line-height:6px;background-color:{cycle values='#FFF,#EEE'};">
						<td style="width: 40%">{l s='Ecotax' pdf='true'}</td>
						<td style="width: 20%; text-align: right;">{$ecotax_tax_infos.rate  } %</td>
						{if !$use_one_after_another_method}
							<td style="width: 20%; text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=$ecotax_tax_infos.ecotax_tax_excl}</td>
						{/if}
						<td style="width: 20%; text-align: right;">{if isset($is_order_slip) && $is_order_slip}- {/if}{displayPrice currency=$order->id_currency price=($ecotax_tax_infos.ecotax_tax_incl - $ecotax_tax_infos.ecotax_tax_excl)}</td>
					</tr>
				{/if}
			{/foreach}
		{/if}
	</table>
	<!--  / TAX DETAILS -->
{/if}

