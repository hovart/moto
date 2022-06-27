{**
*  Michel Dumont
*
*  @author Michel Dumont <michel|at|dumont|dot|io>
*  @version  Release: 2.1.1 - 2015-06-08
*  @theme : presstashop
*
*  @author Michel Dumont <michel|at|dumont|dot|io>
*  @copyright  2007-2015 Michel Dumont
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
*}

{if $blocks}
<!-- MODULE Home Featured Products -->
<div id="mdg_hfp-{$hook}" class="mdg_hfp_column clearfix{if $hook!='columns'} col-xs-12 col-xs-12{/if}">
	{if $hook=="shoppingCartFooter"}{assign var=itemPerLine value=5}{else}{assign var=itemPerLine value=4}{/if}

	{if isset($count_tab)}
    
        <!-- tabs block -->
        <div id="more_info_block-{$hook}">
            <ul id="mdg-{$hook}-page-tabs" class="mdg-page-tabs nav nav-tabs clearfix">
                {foreach $blocks as $block}
                    {if $block.is_tab}
                        <li><a data-toggle="tab" class="{$block.id}-featured" href="#{$block.id}-featured">{$block.title}</a></li>
                    {/if}
                {/foreach}
            </ul>
            <div class="tab-content">
                {foreach $blocks as $block}
                    {if $block.is_tab}
                        <ul id="{$block.id}-featured" class="product_list grid row {$block.id}-featured tab-pane">
                            {include file="$tpl_dir./product-list.tpl" products=$block.products}
                        </ul>
                    {/if}
                {/foreach}
            </div>
        </div>
        {literal}<script type="text/javascript">$(function(){$('#mdg-{/literal}{$hook}{literal}-page-tabs li:first a').trigger('click');});</script>{/literal}
        
	{/if}
    
    <!-- default block -->
    {foreach $blocks as $block}
        {if !$block.is_tab}
        <div class="mdg_hfp mdg_hfp-{$hook} block products_block clearfix">
        	{if $hook=='columns'}
                <p class="title_block">{$block.title}</p>
                <div class="block_content products-block" style="">
                    <ul>
                    {foreach $block.products as $product}
                        <li class="clearfix">
                            <a class="products-block-image" href="{$product.link|escape:'html':'UTF-8'}">
                                <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($smallSize)} width="{$smallSize.width}" height="{$smallSize.height}"{/if} itemprop="image">
                            </a>
                            <div class="product-content">
                                <h5><a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name|truncate:30:'...'|escape:'html':'UTF-8'}</a></h5>
                                <p class="product-description">{$product.description_short|strip_tags:'UTF-8'|truncate:45:'...'}</p>
                                {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="price-box">
                                    {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                                        {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                            <span itemprop="price" class="price special-price">
                                                {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                                            </span>
                                            <meta itemprop="priceCurrency" content="{$priceDisplay}" />
                                            {if $product.specific_prices.reduction_type == 'percentage'}
                                                <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
                                            {/if}
                                            <span class="old-price">
                                                {displayWtPrice p=$product.price_without_reduction}
                                            </span>
                                        {else}
                                            <span itemprop="price" class="price product-price">
                                                {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                                            </span>
                                        {/if}
                                    {/if}
                                </div>
                                {/if}
                            </div>
                        </li>
                    {/foreach}
                    </ul>
                </div>
            {else}
                <h2 class="page-heading product-listing">{$block.title}</h2>
                <div class="block_content">
                    {include file="$tpl_dir./product-list.tpl" products=$block.products}
                </div>
            {/if}
        </div>
        {/if}
    {/foreach}
    
</div>
{/if}