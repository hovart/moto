{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2016
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

{if isset($catalog_tree) && $catalog_tree.children|@count}
    {if $catalog_tree.children|@count >= 3}
        {assign var='col' value=4}
    {else}
        {math equation='round(12 / x)' x=$catalog_tree.children|@count assign='col'}
    {/if}
    <div id="ukoocompat_catalog_block">
        <ul class="root">
            {foreach from=$catalog_tree.children item=child name=catalog_tree}
                {if $smarty.foreach.catalog_tree.last}
                    {include file="$catalog_branche_tpl_path" node=$child last='true'}
                {else}
                    {include file="$catalog_branche_tpl_path" node=$child}
                {/if}
            {/foreach}
        </ul>
    </div>
    <p><a href="{$link->getPageLink('contact', true)}" class="btn exclusive">{l s='Can not find your part? Contact us!' mod='ukoocompat'}</a></p>
    {* TODO [ukoo_guillaume] 2016-01-20 :: rendre dynamique au nombre de niveaux de catégories *}
    {*<script type="text/javascript">*}
    {*$(document).ready(function(){*}
    {*// on masque les niveaux 3 sans produits*}
    {*$('li.level_3.nb_product_0').remove();*}
    {*$('li.level_2').each(function(){*}
    {*var countChildren = parseInt($(this).find('li.level_3:visible').length);*}
    {*if(countChildren < 1){*}
    {*$(this).remove();*}
    {*}*}
    {*});*}
    {*$('li.level_2.nb_product_0 span.level_2').remove();*}
    {*});*}
    {*</script>*}
{/if}
