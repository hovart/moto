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
    {l s='The criteria groups allow you to highlight some search criteria compared to others. For example you can group the "Most Popular Brand" when searching for vehicle...' mod='ukoocompat'}
</div>

{*{if !empty($currentObject->groups)}*}
<div class="form-group" id="add_new_group_block">
    <div class="col-lg-9">
        <p class="form-control-static">
            <a href="javascript:void(0);" class="btn btn-default" target="_blank" id="add_new_group">
                <i class="icon-plus-sign"></i> {l s='Add new criteria group' mod='ukoocompat'}
            </a>
        </p>
    </div>
</div>
{*{/if}*}

<div id="group_form">{include file='./group_form.tpl'}</div>

{* Tableau des groupes de critères enregistrés *}
<div class="panel" id="group_criteria_block">
    <div class="panel-heading">
        {l s='Criteria groups' mod='ukoocompat'}
        <span class="badge" id="badge_available_groups">{count($currentObject->groups)|intval}</span>
    </div>
    <table id="table_available_groups" class="table">
        <thead>
        <tr class="nodrag nodrop">
            <th>{l s='Name' mod='ukoocompat'}</th>
            <th class="center">{l s='Position' mod='ukoocompat'}</th>
            <th class="center">{l s='Active' mod='ukoocompat'}</th>
            <th class="fixed-width-xs"></th>
        </tr>
        </thead>
        <tbody>
            <tr id="table_available_groups_empty"{if count($currentObject->groups) > 0} style="display:none;"{/if}>
                <td class="list-empty" colspan="4">
                    <div class="list-empty-msg">
                        <i class="icon-warning-sign list-empty-icon"></i>
                        {l s='No groups available yet.' mod='ukoocompat'}
                    </div>
                </td>
            </tr>
            {foreach from=$currentObject->groups item=group}
                {include file='./group_tr.tpl'}
            {/foreach}
        </tbody>
    </table>
</div>