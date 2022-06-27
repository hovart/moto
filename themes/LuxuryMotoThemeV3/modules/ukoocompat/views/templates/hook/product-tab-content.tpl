{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

<h3 id="ukoocompat_tabcontent_title" class="idTabHrefShort page-product-heading">{l s='Compatibilities' mod='ukoocompat'}</h3>
<div id="ukoocompat_tabcontent">
    {foreach from=$compatTab item=tab}
    <table class="table table-bordered">
        <thead>
            <tr>
                {foreach from=$tab.search->filters item=filter}
                    <th class="even">{$filter->name}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            {foreach from=$tab.compatibilities item=compat name=compatRow}
                <tr{if $smarty.foreach.compatRow.index >= 5} style="display:none;" class="compatNotDisplay" {/if}>
                    {foreach from=$tab.search->filters item=filter}
                        <td>
                            {if $compat['filter_'|cat:$filter->id_ukoocompat_filter] == '*'}
                                {l s='All' mod='ukoocompat'} {$filter->name|lower}
                            {else}
                                {$compat['filter_'|cat:$filter->id_ukoocompat_filter]}
                            {/if}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p><a id="showMoreCompat"{if count($tab.compatibilities) < 5} style="display:none" {/if} class="btn button" href="javascript:void();" onclick="$('.compatNotDisplay:hidden, #reduceCompat').show(); $(this).hide();">{l s='See more compatibilities' mod='ukoocompat'}</a></p>
    <p><a id="reduceCompat" style="display:none;" class="btn button" href="javascript:void();" onclick="$('.compatNotDisplay:visible').hide(); $('#showMoreCompat').show(); $(this).hide();">{l s='Reduce' mod='ukoocompat'}</a></p>
    {/foreach}
</div>