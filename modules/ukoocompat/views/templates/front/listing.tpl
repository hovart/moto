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
        {foreach from=$search->tags item=tag key=k}
            {if $k != '{CATEGORY}'}
                {assign var=catalog_title value=$catalog_title|escape|cat:' '|cat:$tag|escape}
            {/if}
        {/foreach}
    {else}
        {assign var=catalog_title value={l s='All your products' mod='ukoocompat'}}
    {/if}
{/if}

{include file="$tpl_dir./errors.tpl"}

{capture name=path}
    {strip}
        <a href="{$catalog_link|escape}" title="{$catalog_title|escape}">
            {$catalog_title|escape}
        </a>
        <span class="navigation-pipe">{$navigationPipe|escape}</span>
        {if isset($search->listing_title) && !empty($search->listing_title)}
            {$search->listing_title|escape}
        {else}
            {assign var=listing_title value={$search->tags['{CATEGORY}']|cat:' '|cat:{l s='for' mod='ukoocompat'}}}
            {foreach from=$search->tags item=tag key=k}
                {if $k != '{CATEGORY}'}
                    {assign var=listing_title value=$listing_title|cat:' '|cat:$tag|escape}
                {/if}
            {/foreach}
            {$listing_title|escape}
        {/if}
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
            </div>
        </div>
    </div>

    <div id="toggle_search_block" class="row">
        {hook h="displayUkooCompatBlock" display="listing"}
    </div>

    <p class="page-heading product-listing">
        <span class="cat-name">
            {if isset($search->listing_title) && !empty($search->listing_title)}
                {$search->listing_title|escape}
            {else}
                {$listing_title|escape}
            {/if}
        </span>
    </p>
{else}
    <div id="ukoocompat_search">
        <div class="block_content">
            <div>
                <h1 class="page-heading product-listing">
                    <span class="cat-name">
                        {if isset($search->listing_title) && !empty($search->listing_title)}
                            {$search->listing_title|escape}
                        {else}
                            {$listing_title|escape}
                        {/if}
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
                {if isset($search->listing_description) && !empty($search->listing_description)}
                    <div class="ukoocompat_description">
                        {$search->listing_description}
                    </div>
                {/if}
                <button type="button" class="btn exclusive" id="change_search_button">
                    <i class="icon-exchange"></i> {l s='Change your search' mod='ukoocompat'}
                </button>
            </div>
        </div>
    </div>

    <div id="toggle_search_block" class="row">
        {hook h="displayUkooCompatBlock" display="listing"}
    </div>
{/if}

{if isset($products) && !empty($products)}
    <div class="content_sortPagiBar clearfix">
        <div class="sortPagiBar clearfix">
			{include file="$tpl_dir./product-sort.tpl"}
			{include file="$tpl_dir./nbr-product-page.tpl"}
		</div>
		<div class="top-pagination-content clearfix">
			{include file="$tpl_dir./product-compare.tpl"}
			{include file="$tpl_dir./pagination.tpl"}
		</div>
	</div>
	{include file="$tpl_dir./product-list.tpl" products=$products}
	<div class="content_sortPagiBar">
		<div class="bottom-pagination-content clearfix">
			{include file="$tpl_dir./product-compare.tpl" paginationId='bottom'}
			{include file="$tpl_dir./pagination.tpl" paginationId='bottom'}
		</div>
	</div>
{else}
    <p class="alert alert-warning">{l s='No result for your search.' mod='ukoocompat'}</p>
{/if}

{literal}
<script type="text/javascript">
    $(document).ready(function(){
        // Empêche le module blocklayered de prendre le dessus (les résultats ne sont pas cohérents)
        $('#selectProductSort').unbind('change');
    })
</script>
{/literal}