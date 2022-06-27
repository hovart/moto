{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<tr id="tr_used_filter_{$filter.id_ukoocompat_search_filter|intval}" class="table_row">
    <td class="pointer" onclick="getSearchFilterForm({$filter.id_ukoocompat_search_filter|intval});">
        {$filter.name|escape:'htmlall':'UTF-8'}
    </td>
	<td class="pointer" onclick="getSearchFilterForm({$filter.id_ukoocompat_search_filter|intval});">
		{literal}{FILTER:{/literal}{$filter.id|intval}{literal}}{/literal}
	</td>
    <td class="pointer dragHandle fixed-width-xs center">
        <div class="dragGroup">
            <div class="positions">
                {$filter.position|intval}
            </div>
        </div>
    </td>
    <td class="pointer center">
        <a class="list-action-enable action-{if $filter.active|intval == 1}enabled{else}disabled{/if}" href="javascript:void(0);" onclick="toggleSearchFilterState({$filter.id_ukoocompat_search_filter|intval});" title="{if $filter.active|intval == 1}{l s='Enabled' mod='ukoocompat'}{else}{l s='Disabled' mod='ukoocompat'}{/if}">
            <i class="icon-check{if $filter.active|intval == 0} hidden{/if}"></i>
            <i class="icon-remove{if $filter.active|intval == 1} hidden{/if}"></i>
        </a>
    </td>
    <td class="fixed-width-xs">
        <div class="btn-group-action">
            <div class="btn-group pull-right">
                <a href="javascript:void(0);" onclick="getSearchFilterForm({$filter.id_ukoocompat_search_filter|intval});" title="{l s='Options' mod='ukoocompat'}" class="edit btn btn-default">
                    <i class="icon-pencil"></i> {l s='Options' mod='ukoocompat'}
                </a>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-caret-down"></i>&nbsp;
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0);" onclick="removeFilterFromSearch({$filter.id_ukoocompat_search_filter|intval});" title="{l s='Remove from search' mod='ukoocompat'}" class="delete">
                            <i class="icon-remove"></i> {l s='Remove from search' mod='ukoocompat'}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </td>
</tr>