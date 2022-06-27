{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<tr id="tr_available_filter_{$filter.id|intval}" class="table_row">
    <td>{$filter.default_name|escape:'htmlall':'UTF-8'}</td>
    <td class="fixed-width-xs">
        <button class="btn btn-default pull-right" type="button" onclick="addFilterToSearch({$filter.id|intval});">
            <i class="icon-plus-sign" style="height: 14px; width: 14px;"></i> {l s='Add to search' mod='ukoocompat'}
        </button>
    </td>
</tr>