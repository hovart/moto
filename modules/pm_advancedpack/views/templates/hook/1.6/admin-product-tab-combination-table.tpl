				{foreach from=$packProduct['productCombinations'] item='productCombination'}
					{assign var=ap5_isDefaultCombination value=((isset($packProduct['default_id_product_attribute']) && (int)$packProduct['default_id_product_attribute'] && (int)$packProduct['default_id_product_attribute'] == (int)$productCombination['id_product_attribute']) || (!isset($packProduct['default_id_product_attribute']) || !(int)$packProduct['default_id_product_attribute']) && (int)$productCombination['id_product_attribute'] == (int)Product::getDefaultAttribute($packProduct['productObj']->id))}
					{assign var=ap5_isSelected value=count($packProduct['productCombinationsWhiteList']) && in_array($productCombination['id_product_attribute'], $packProduct['productCombinationsWhiteList'])}
					{assign var=ap5_combinationAvailableQuantity value=StockAvailable::getQuantityAvailableByProduct($packProduct['productObj']->id, $productCombination['id_product_attribute'])|intval}
				<tr id="ap5_combination-{$idProductPack}-{$productCombination['id_product_attribute']|intval}" class="nodrag nodrop{if $ap5_isDefaultCombination} highlighted{/if}">
					<td class="center">
						<input type="checkbox"{if $ap5_isSelected || !count($packProduct['productCombinationsWhiteList'])} checked="checked"{/if} value="{$productCombination['id_product_attribute']|intval}" id="ap5_combinationInclude-{$idProductPack}-{$productCombination['id_product_attribute']|intval}" name="ap5_combinationInclude-{$idProductPack}[]" class="ap5_combinationInclude" data-id-product-pack="{$idProductPack}" data-id-product-attribute="{$productCombination['id_product_attribute']|intval}" />
					</td>
					<td class="center">
						<input type="radio"{if $ap5_isDefaultCombination} checked="checked"{else if !$ap5_isSelected && count($packProduct['productCombinationsWhiteList'])} disabled="disabled" {/if} value="{$productCombination['id_product_attribute']|intval}" id="ap5_defaultCombination-{$idProductPack}_{$productCombination['id_product_attribute']|intval}" name="ap5_defaultCombination-{$idProductPack}" class="ap5_defaultCombination" data-id-product-pack="{$idProductPack}" data-id-product-attribute="{$productCombination['id_product_attribute']|intval}" />
					</td>
					<td>{$productCombination['attribute_designation']|escape:'html':'UTF-8'}</td>
					<td>{convertPrice price=$productCombination['price']}</td>
					<td>{$productCombination['reference']|escape:'html':'UTF-8'}</td>
					<td>{$productCombination['ean13']|escape:'html':'UTF-8'}</td>
					<td class="center">
						{if $ap5_combinationAvailableQuantity <= 10}<span class="badge badge-{if $ap5_combinationAvailableQuantity <= 5}danger{else}warning{/if}">{/if}
						{$ap5_combinationAvailableQuantity|intval}
						{if $ap5_combinationAvailableQuantity <= 10}</span{/if}
					</td>
				</tr>
				{/foreach}