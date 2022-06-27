{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<tr id="tr_available_group_{$group->id|intval}" class="table_row">
    <td class="pointer" onclick="editGroup({$group->id|intval});">
        {$group->name[$current_id_lang|intval]|escape}
    </td>
    <td class="pointer dragHandle fixed-width-xs center">
        <div class="dragGroup">
            <div class="positions">
                {$group->position|intval}
            </div>
        </div>
    </td>
    <td class="pointer center">
        <a class="list-action-enable action-{if $group->active|intval == 1}enabled{else}disabled{/if}" href="javascript:void(0);" onclick="toggleGroupState({$group->id|intval});" title="{if $group->active|intval == 1}{l s='Enabled' mod='ukoocompat'}{else}{l s='Disabled' mod='ukoocompat'}{/if}">
            <i class="icon-check{if $group->active|intval == 0} hidden{/if}"></i>
            <i class="icon-remove{if $group->active|intval == 1} hidden{/if}"></i>
        </a>
    </td>
    <td class="fixed-width-xs">
        <div class="btn-group-action">
            <div class="btn-group pull-right">
                <a href="javascript:void(0);" onclick="editGroup({$group->id|intval});" title="{l s='Edit' mod='ukoocompat'}" class="edit btn btn-default">
                    <i class="icon-pencil"></i> {l s='Edit' mod='ukoocompat'}
                </a>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-caret-down"></i>&nbsp;
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0);" onclick="deleteGroup({$group->id|intval});" title="{l s='Delete group' mod='ukoocompat'}" class="delete">
                            <i class="icon-remove"></i> {l s='Delete group' mod='ukoocompat'}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </td>
</tr>