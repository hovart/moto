
{capture name=path}{lcw s='Payment' mod='postfinancecw'}{/capture}

<h1 class="page-heading">{lcw s='Payment' mod='postfinancecw'}</h1>
{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{$iframe}
