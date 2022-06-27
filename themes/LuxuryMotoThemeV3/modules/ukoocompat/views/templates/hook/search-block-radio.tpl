{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

<input type="hidden" class="{if $search->dynamic_criteria} dynamic_criteria{/if}" name="filters{$filter->id_ukoocompat_filter|intval}" id="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}" value=""/>
<ul class="col-lg-12 ukoocompat_search_block_filter_ul">
    <input type="hidden" class="{if $search->dynamic_criteria} dynamic_criteria{/if}" name="filters[{$filter->id_ukoocompat_filter|intval}]" id="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}" value="" />
    {foreach from=$filter->criteria item=criterion}
        <li class="nomargin col-lg-6">
            {strip}
                <label for="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}_criterion_{$criterion['id']|intval}" class="criterion_{$criterion['id']|intval}">
                    <input type="radio" class="radio{if $search->dynamic_criteria} dynamic_criteria{/if}"{if isset($filter->disabled) && $filter->disabled} disabled="disabled"{/if} name="filters{$filter->id_ukoocompat_filter|intval}" id="ukoocompat_search_{$search->id|intval}_filter_{$filter->id_ukoocompat_filter|intval}_criterion_{$criterion['id']|intval}" value="{$criterion['id']|intval}"{if isset($search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]) && $search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]|intval == $criterion['id']|intval} checked="checked"{/if}>
                    {$criterion['value']|escape}
                </label>
            {/strip}
        </li>
    {/foreach}
</ul>