{**
* 2015 Aretmic
*
* NOTICE OF LICENSE 
* 
* ARETMIC the Company grants to each customer who buys a virtual product license to use, and non-exclusive and worldwide. This license is 
* valid only once for a single e-commerce store. No assignment of rights is hereby granted by the Company to the Customer. It is also 
* forbidden for the * Customer to resell or use on other virtual shops Products made by ARETMIC. This restriction includes all resources 
* provided with the virtual product. 
*
* @author    Aretmic SA
* @copyright 2015 Aretmic SA
* @license   ARETMIC
* International Registered Trademark & Property of Aretmic SA
*}
{if $checkversion==''}{include file="$tpl_dir./breadcrumb.tpl"}{/if}
{include file="$tpl_dir./errors.tpl"}
{$tplform|escape:'quotes':'UTF-8'}
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	{literal}
	<script type="text/javascript">
        $.uniform.defaults.fileDefaultHtml = "{/literal}{$nofile|escape:'html':'UTF-8'}{literal}";
        $.uniform.defaults.fileButtonHtml = "{/literal}{$choosefile|escape:'html':'UTF-8'}{literal}";
    </script>
	{/literal}
{/if}