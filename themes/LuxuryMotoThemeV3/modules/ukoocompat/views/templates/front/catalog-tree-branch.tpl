{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

{*{if $node.nb_products|intval > 0 || count($node.children) > 0}*}
{*<li class="category_{$node.id} level_{$node.level_depth|intval}{if $node.level_depth|intval == 1} col-lg-{$col|intval}{/if}{if isset($last) && $last == 'true'} last{/if}{if isset($node.nb_products)} nb_product_{$node.nb_products|intval}{/if}">*}
    {* Afficher les images des catégories ? *}
    {*{if $node.level_depth|intval > 2}*}
        {*{assign var='imgCompat' value='img/c/'|cat:$node.id|cat:'-category_default.jpg'}*}
        {*{if file_exists($imgCompat)}*}
            {*<img src="{$base_dir|cat:'img/c/'|cat:$node.id|cat:'-category_default.jpg'}" width="100" />*}
        {*{/if}*}
    {*{/if}*}
    {*{if $node.nb_products|intval > 0 || count($node.children) > 0}*}
        {*{if $node.level_depth|intval == 1}<h2>{elseif $node.level_depth|intval == 2}<h3>{/if}*}
        {*{if $node.nb_products|intval > 0}<a href="{$node.link|escape:'html':'UTF-8'}" title="{$node.desc|strip_tags|trim|truncate:255:'...'|escape:'html':'UTF-8'}">{/if}*}
            {*{$node.name|escape:'html':'UTF-8'}{if $search->display_nb_products_catalog && $node.level_depth|intval > 1} <span class="level_{$node.level_depth|intval}">({$node.nb_products|intval} {if $node.nb_products|intval < 2}{l s='product' mod='ukoocompat'}{else}{l s='products' mod='ukoocompat'}{/if})</span>{/if}*}
        {*{if $node.nb_products|intval > 0}</a>{/if}*}
        {*{if $node.level_depth|intval == 2}</h3>{elseif $node.level_depth|intval == 1}</h2>{/if}*}
    {*{/if}*}
    {*{if $node.children|@count > 0}*}
        {*<ul class="level_{$node.level_depth|intval}">*}
            {*{foreach from=$node.children item=child name=catalog_tree_branch}*}
                {*{if $smarty.foreach.catalog_tree_branch.last}*}
                    {*{include file="$catalog_branche_tpl_path" node=$child last='true'}*}
                {*{else}*}
                    {*{include file="$catalog_branche_tpl_path" node=$child last='false'}*}
                {*{/if}*}
            {*{/foreach}*}
        {*</ul>*}
    {*{/if}*}
{*</li>*}
{*{/if}*}

{if in_array($node.id, $active_categories) || count($node.children) > 0}
    <li class="{if !in_array($node.id, $active_categories)}nb_product_0 {/if}category_{$node.id} level_{$node.level_depth|intval}{if $node.level_depth|intval == 1} col-lg-{$col|intval}{/if}{if isset($last) && $last == 'true'} last{/if}">
        {* Afficher les images des catégories ? *}
        {*{if $node.level_depth|intval > 2}*}
        {*{assign var='imgCompat' value='img/c/'|cat:$node.id|cat:'-category_default.jpg'}*}
        {*{if file_exists($imgCompat)}*}
        {*<img src="{$base_dir|cat:'img/c/'|cat:$node.id|cat:'-category_default.jpg'}" width="100" />*}
        {*{/if}*}
        {*{/if}*}
        {if in_array($node.id, $active_categories) || count($node.children) > 0}
            {if $node.level_depth|intval == 1}<h2>{elseif $node.level_depth|intval == 2}<h3>{/if}
        {if in_array($node.id, $active_categories)}<a href="{$node.link|escape:'html':'UTF-8'}" title="{$node.desc|strip_tags|trim|truncate:255:'...'|escape:'html':'UTF-8'}">{/if}
            {$node.name|escape:'html':'UTF-8'}{* if $search->display_nb_products_catalog && $node.level_depth|intval > 1} <span class="level_{$node.level_depth|intval}">({$node.nb_products|intval} {if $node.nb_products|intval < 2}{l s='product' mod='ukoocompat'}{else}{l s='products' mod='ukoocompat'}{/if})</span>{/if *}
        {if in_array($node.id, $active_categories)}</a>{/if}
            {if $node.level_depth|intval == 2}</h3>{elseif $node.level_depth|intval == 1}</h2>{/if}
        {/if}
        {if $node.children|@count > 0}
            <ul class="level_{$node.level_depth|intval}">
                {foreach from=$node.children item=child name=catalog_tree_branch}
                    {if $smarty.foreach.catalog_tree_branch.last}
                        {include file="$catalog_branche_tpl_path" node=$child last='true'}
                    {else}
                        {include file="$catalog_branche_tpl_path" node=$child last='false'}
                    {/if}
                {/foreach}
            </ul>
        {/if}
    </li>
{/if}