{*
	*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
	*
	* @author    Línea Gráfica E.C.E. S.L.
	* @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
	* @license   https://www.lineagrafica.es/licenses/license_en.pdf https://www.lineagrafica.es/licenses/license_es.pdf https://www.lineagrafica.es/licenses/license_fr.pdf
*}

<script type="text/javascript">
	{if $isOrder}
		{literal}
		dataLayer = [{
			'event' : 'mgd.orderCompleted',
			'transactionId': '{/literal}{$trans.id|escape:'javascript':'UTF-8'}{literal}',
			'transactionAffiliation': '{/literal}{$trans.store|escape:'javascript':'UTF-8'}{literal}',
			'transactionTotal': {/literal}{$trans.total|escape:'javascript':'UTF-8'}{literal},
			'transactionTax': {/literal}{$trans.tax|escape:'javascript':'UTF-8'}{literal},
			'transactionShipping': {/literal}{$trans.shipping|escape:'javascript':'UTF-8'}{literal},
			'transactionProducts': [{
				{/literal}
			{foreach from=$items item=item}
				'sku': '{$item.SKU|escape:'javascript':'UTF-8'}',
				'name': '{$item.Product|escape:'javascript':'UTF-8'}',
				'category': '{$item.Category|escape:'javascript':'UTF-8'}',
				'price': {$item.Price|escape:'javascript':'UTF-8'},
				'quantity': {$item.Quantity|escape:'javascript':'UTF-8'}
				{rdelim},
				{/foreach}

			{literal}
			]
		}];
		{/literal}

	{/if}

</script>
