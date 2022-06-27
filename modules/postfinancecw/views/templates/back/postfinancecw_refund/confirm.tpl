
<h2>{lcw s='Refund Transaction' mod='postfinancecw'}</h2>

<p>{lcw s='You are along the way to refund the order %s.' mod='postfinancecw' sprintf=$orderId} 
{lcw s='Do you want to send this order also the?' mod='postfinancecw'}</p>

<p>{lcw s='Amount to refund:' mod='postfinancecw'} {$refundAmount} {$transaction->getCurrencyCode()}</p>

{if !$transaction->isRefundClosable()}
	<p><strong>{lcw s='This is the last refund possible on this transaction. This payment method does not support any further refunds.' mod='postfinancecw'}</strong></p>
{/if}

<form action="{$targetUrl}" method="POST">
<p>
	{$hiddenFields}	
	<a class="button" href="{$backUrl}">{lcw s='Cancel' mod='postfinancecw'}</a>
	<input type="submit" class="button" name="submitPostFinanceCwRefundNormal" value="{lcw s='No' mod='postfinancecw'}" />
	<input type="submit" class="button" name="submitPostFinanceCwRefundAuto" value="{lcw s='Yes' mod='postfinancecw'}" />
</p>
</form>