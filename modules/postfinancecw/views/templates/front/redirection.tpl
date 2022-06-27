
{capture name=path}{lcw s='Payment' mod='postfinancecw'}{/capture}

<h2>{lcw s='Redirection' mod='postfinancecw'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}
	
<h3>{$paymentMethodName}</h3>

<form action="{$form_target_url}" method="POST" name="process_form">
	
	{$hidden_fields}

	<input class="button" type="submit" name="continue_button" value="{lcw s='Continue' mod='postfinancecw'}" />

</form>
<script type="text/javascript"> 
jQuery(document).ready(function() {
	document.process_form.submit(); 
});
</script>