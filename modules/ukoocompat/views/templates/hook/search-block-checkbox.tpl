{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

<ul class="col-lg-12 ukoocompat_search_block_filter_ul">
	{foreach from=$filter->criteria item=criterion}
	<li class="nomargin col-lg-6">
		<input type="checkbox" class="checkbox" name="ukoocompat_filter_{$filter->id_ukoocompat_filter|intval}[]" id="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}_criterion_{$criterion['id']|intval}" value="{$criterion['id']|intval}">
		<label for="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}_criterion_{$criterion['id']|intval}">{$criterion['value']|escape}</label>
	</li>
	{/foreach}
</ul>