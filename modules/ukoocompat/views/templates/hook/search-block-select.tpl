{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2016
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

<select id="ukoocompat_select_{$filter->id_ukoocompat_filter|intval}" name="filters{$filter->id_ukoocompat_filter|intval}" class="form-control-2{if $search->dynamic_criteria} dynamic_criteria{/if}{if isset($filter->disabled) && $filter->disabled|intval == 1} disabled{/if}">
    <option value="">{$filter->name|escape}</option>
    {if !isset($filter->disabled) || $filter->disabled|intval != 1}
        {if isset($filter->groups) && !empty($filter->groups)}
            {foreach from=$filter->groups item=group}
                <optgroup label="{$group->name|escape}">
                    {foreach from=$filter->criteria item=criterion}
                        {if in_array($criterion['id']|intval, $group->criteria)}
                            <option value="{$criterion['id']|intval}"{if isset($search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]) && $search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]|intval == $criterion['id']|intval} selected="selected"{/if}>{$criterion['value']|escape}</option>
                        {/if}
                    {/foreach}
                </optgroup>
            {/foreach}
            <optgroup label="{$filter->name|escape}">
        {/if}
        {foreach from=$filter->criteria item=criterion}
                <option value="{$criterion['id']|intval}"{if isset($search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]) && $search->selected_criteria[{$filter->id_ukoocompat_filter|intval}]|intval == $criterion['id']|intval} selected="selected"{/if}>{$criterion['value']|escape}</option>
        {/foreach}
        {if isset($filter->groups) && !empty($filter->groups)}
            </optgroup>
        {/if}
    {/if}
</select>