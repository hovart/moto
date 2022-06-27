{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<div class="alert alert-info">
    {l s='Select the filters you want to use in your search block, and configure each one as you see fit.' mod='ukoocompat'}
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-heading">
                {l s='Available filters' mod='ukoocompat'}
                <span class="badge" id="badge_available_filter">{count($filters.available)|intval}</span>
            </div>
            <table id="table_available_filter" class="table">
                <tbody>
                    <tr id="table_available_filter_empty"{if count($filters.available) > 0} style="display:none;"{/if}>
                        <td class="list-empty" colspan="4">
                            <div class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i>
                                {l s='No filters available yet.' mod='ukoocompat'}
                            </div>
                        </td>
                    </tr>
                    {foreach from=$filters.available item=filter}
                        {include file='./available_filter_tr.tpl'}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-heading">
                {l s='Used filters' mod='ukoocompat'}
                <span class="badge" id="badge_used_filter">{count($filters.used)|intval}</span>
            </div>
            <table id="table_used_filter" class="table sortable">
                <thead>
                    <tr class="nodrag nodrop">
                        <th>{l s='Name' mod='ukoocompat'}</th>
	                    <th>{l s='SEO tag' mod='ukoocompat'}</th>
                        <th class="center">{l s='Position' mod='ukoocompat'}</th>
                        <th class="center">{l s='Active' mod='ukoocompat'}</th>
                        <th class="fixed-width-xs"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="table_used_filter_empty" class="nodrag nodrop"{if count($filters.used) > 0} style="display:none;" {/if}>
                        <td class="list-empty" colspan="4">
                            <div class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i>
                                {l s='No filters used yet.' mod='ukoocompat'}
                            </div>
                        </td>
                    </tr>
                    {foreach from=$filters.used item=filter}
                        {include file='./used_filter_tr.tpl'}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>