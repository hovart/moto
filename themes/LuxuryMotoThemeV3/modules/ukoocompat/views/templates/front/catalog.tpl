{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2016
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

{if isset($search->catalog_title) && !empty($search->catalog_title)}
    {assign var=catalog_title value=$search->catalog_title|escape}
{else}
    {if isset($search->tags) && !empty($search->tags)}
        {assign var=catalog_title value={l s='All your products for' mod='ukoocompat'}}
        {foreach from=$search->tags item=tag}
            {assign var=catalog_title value=$catalog_title|escape|cat:' '|cat:$tag|escape}
        {/foreach}
    {else}
        {assign var=catalog_title value={l s='All your products' mod='ukoocompat'}}
    {/if}
{/if}

{include file="$tpl_dir./errors.tpl"}

{capture name=path}
    {strip}
        {$catalog_title|escape}
    {/strip}
{/capture}

{if isset($search->alias) && !empty($search->alias)}
    <div id="ukoocompat_search_alias">
        <div class="block_content">
            {if file_exists('modules/ukoocompat/img/'|cat:$search->alias->image|escape:'htmlall':'UTF-8')}
                <p>
                    <img src="{$base_dir_ssl|cat:'modules/ukoocompat/img/'|cat:$search->alias->image|escape:'htmlall':'UTF-8'}" width="120" height="120" alt="{$search->alias->alias|escape:'htmlall':'UTF-8'}"/>
                </p>
            {/if}
            <div>
                <h1 class="title">{$search->alias->alias|escape:'htmlall':'UTF-8'}</h1>
                {if isset($search->alias->description) && !empty($search->alias->description)}
                    <div>{$search->alias->description}</div>
                {/if}
                <p>
                    {if isset($search->alias->link) && !empty($search->alias->link)}
                        <a href="{$search->alias->link|escape}" class="btn btn-default">
                            <i class="icon-eye"></i> {l s='See more' mod='ukoocompat'}
                        </a>
                    {/if}
                    {if file_exists('modules/ukoocompat/pdf/notice_'|cat:$search->alias->id|cat:'.pdf')}
                        <a href="{$base_dir_ssl|cat:'modules/ukoocompat/pdf/notice_'|cat:$search->alias->id|cat:'.pdf'}" target="_blank" class="btn btn-default">
                            <i class="icon-download"></i> {l s='Download documentation' mod='ukoocompat'}
                        </a>
                    {/if}
                    <button type="button" class="btn exclusive" id="change_search_button">
                        <i class="icon-exchange"></i> {l s='Change your search' mod='ukoocompat'}
                    </button>
                </p>
            </div>
        </div>
    </div>

    <div id="toggle_search_block" class="row">
        {hook h="displayUkooCompatBlock" display="catalog"}
    </div>

    <p class="page-heading product-listing">
        <span class="cat-name">
            {$catalog_title|escape}
        </span>
    </p>
{else}
    <div id="ukoocompat_search">
        <div class="block_content">
            <div>
                <h1 class="page-heading product-listing">
                    <span class="cat-name">
                        {$catalog_title}
                    </span>
                    {strip}
                        <span class="heading-counter">
                        {if isset($nb_products) && $nb_products == 1}
                            {l s='There is 1 product' mod='ukoocompat'}
                        {elseif isset($nb_products) && $nb_products == 0}
                            {l s='There is no product' mod='ukoocompat'}
                        {elseif isset($nb_products)}
                            {l s='There are %d products' sprintf=$nb_products mod='ukoocompat'}
                        {/if}
                        </span>
                    {/strip}
                </h1>
                {if isset($search->catalog_description) && !empty($search->catalog_description)}
                    <div class="ukoocompat_description">
                        {$search->catalog_description}
                    </div>
                {/if}
                <button type="button" class="btn" id="change_search_button">
                    <i class="icon-exchange"></i> {l s='Change your search' mod='ukoocompat'}
                </button>
            </div>
        </div>
    </div>

    <div id="toggle_search_block" class="row">
        {hook h="displayUkooCompatBlock" display="catalog"}
    </div>
{/if}

{hook h="displayUkooCompatCatalogTree" search=$search}
