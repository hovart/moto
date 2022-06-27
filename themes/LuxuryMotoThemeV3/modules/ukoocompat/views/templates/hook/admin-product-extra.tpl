{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

{if isset($id_product) && $id_product|intval != 0}
    <div class="panel product-tab">
        <h3><i class="icon-check"></i> {l s='Create new compatibility' mod='ukoocompat'}</h3>

        <div class="alert alert-info">
            {l s='Indicate the combination of criteria with which there is compatible.' mod='ukoocompat'}
        </div>

        <input type="hidden" name="id_product" id="id_product" value="{$id_product|intval}" />
        <input type="hidden" name="compatToken" id="compatToken" value="{$compatToken|escape}" />

        {foreach from=$filters item=filter}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {$filter.name|escape:'html':'UTF-8'}
                </label>
                <div class="col-lg-9">
                    <select name="id_ukoocompat_criterion[{$filter.id|intval}]" id="id_ukoocompat_criterion_{$filter.id|intval}">
                        <option value="" >{l s='---' mod='ukoocompat'}</option>
                        <option value="*"{if isset($selected_criteria[$filter.id|intval]) && $selected_criteria[$filter.id|intval].id_ukoocompat_criterion == '*'} selected="selected"{/if}>{l s='All' mod='ukoocompat'}</option>
                        {foreach from=$filter.criteria item=criterion}
                            <option value="{$criterion.id|intval}"{if isset($selected_criteria[$filter.id|intval]) && $selected_criteria[$filter.id|intval].id_ukoocompat_criterion == $criterion.id|intval} selected="selected"{/if}>
                                {$criterion.value|escape:'html':'UTF-8'}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        {/foreach}

        <div class="panel-footer">
            <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='ukoocompat'}</a>
            <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='ukoocompat'}</button>
            <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='ukoocompat'}</button>
        </div>

    </div>

    {foreach from=$compatTab item=tab}
    <div class="panel product-tab">
        <h3><i class="icon-search"></i> {l s='Search:' mod='ukoocompat'} {$tab.search->name}</h3>
        <table class="table">
            <thead>
                <tr>
                    {foreach from=$tab.search->filters item=filter}
                        <th class="even"><span class="title_box">{$filter->name}</span></th>
                    {/foreach}
                    <th class="even"><span class="title_box">&nbsp;</span></th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$tab.compatibilities item=compat}
                <tr class="id_compat_{$compat.id_ukoocompat_compat|intval}">
                    {foreach from=$tab.search->filters item=filter}
                        <td>
                            {if $compat['filter_'|cat:$filter->id_ukoocompat_filter] == '*'}
                                {l s='All' mod='ukoocompat'} {$filter->name|lower}
                            {else}
                                {$compat['filter_'|cat:$filter->id_ukoocompat_filter]}
                            {/if}
                        </td>
                    {/foreach}
                    <td>
                        <div class="btn-group-action">
                            <div class="btn-group pull-right">
                                <a href="javascript:void(0);"
                                   onclick="{literal}if (confirm('{/literal}{l s='Delete the compatibility:' mod='ukoocompat'} {foreach from=$tab.search->filters item=filter}{if $compat['filter_'|cat:$filter->id_ukoocompat_filter] == '*'}{l s='All' mod='ukoocompat'} {$filter->name|lower}{else}{$compat['filter_'|cat:$filter->id_ukoocompat_filter]}{/if} {/foreach}?{literal}')){deleteCompatibility({/literal}{$compat.id_ukoocompat_compat|intval}{literal});}else{event.stopPropagation(); event.preventDefault();};{/literal}" class="edit btn btn-default" title="{l s='Delete' mod='ukoocompat'}">
                                    <i class="icon-trash"></i> {l s='Delete' mod='ukoocompat'}
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    {/foreach}
    <script type="text/javascript">
        $(document).ready(function(){

        });

        function deleteCompatibility(id_compat)
        {
            $.ajax({
                url: './index.php?controller=AdminUkooCompatCompat&action=deleteCompatibility',
                type: 'POST',
                dataType: 'json',
                data: {
                    token: $('#compatToken').val(),
                    id_compat: parseInt(id_compat),
                    ajax: true
                },
                success: function(data){
                    if (data.status == 'ok')
                    {
                        showSuccessMessage(data.message);
                        $('.id_compat_' + data.id_compat).fadeOut('slow', function(){ $('.id_compat_' + data.id_compat).remove(); });
                    }
                    else
                        showErrorMessage(data.message);
                }
            });
        }
    </script>
{else}
    <div class="alert alert-warning">
        {l s='There is a warning' mod='ukoocompat'}
        <ul style="display:block;" id="seeMore">
            <li>{l s='You must register the product before creating its compatibility.' mod='ukoocompat'}</li>
        </ul>
    </div>
{/if}