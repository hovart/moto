
{capture name=path}{lcw s='Payment' mod='postfinancecw'}{/capture}

<h1 class="page-heading">{lcw s='Order Confirmation' mod='postfinancecw'}</h1>
{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{lcw s='Your shopping cart is empty.'}</p>
{else}
	
	{if isset($error_message) && !empty($error_message)}
		<p class="payment-error alert alert-danger">
			{$error_message}
		</p>
	{/if}
	
	<p>{lcw s='CONFIRM_ORDER_TEXT' mod='postfinancecw' }</p>
	
	{$paymentPane}
		
	<p class="cart_navigation clearfix">
		<a href="{$link->getPageLink('order', true, NULL, "step=3")}" class="button-exclusive btn btn-default">
			<i class="icon-chevron-left"></i>
			{lcw s='Other payment methods' mod='postfinancecw'}
		</a>
	</p>
{/if}
