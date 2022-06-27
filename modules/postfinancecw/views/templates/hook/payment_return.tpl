<table class="postfinancecw-payment-return-table">
	<tr>
		<th>{lcw s='Order Reference' mod='postfinancecw'}</th>
		<td><a href="{$historyLink}">{Order::getUniqReferenceOf($order->id)}</a></td>
	</tr>
	<tr>
		<th>{lcw s='Amount' mod='postfinancecw'}</th>
		<td>{displayPrice price=$transaction->getAuthorizationAmount() currency=$order->id_currency no_utf8=false convert=false}</td>
	</tr>
	<tr>
		<th>{lcw s='Status' mod='postfinancecw'}</th>
		<td>{$orderState}</td>
	</tr>
	<tr>
		<th>{lcw s='Date' mod='postfinancecw'}</th>
		<td>{dateFormat date=$order->date_add full=0}</td>
	</tr>
</table>

{if isset($paymentMethodMessage) && !empty($paymentMethodMessage)}
	<p class="payment-method-message">{$paymentMethodMessage}</p>
{/if}
