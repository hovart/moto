{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block best sellers -->
<div id="best-sellers_block" class="block colprods">
    <h4 class="title_block"><a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='Best sellers' mod='tptnbestsplprods'}">{l s='Best sellers' mod='blockbestsellers'} &raquo;</a></h4>
	<div class="block_content">
	{if $best_sellers|@count > 0}
		<ul class="product_images">
            {foreach from=$best_sellers item=product name=myLoop}
			
			{if $smarty.foreach.myLoop.iteration > 3} {break}{/if}
			
            <li>
                <a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="product_image">
                    <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'large_default')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product.name|escape:'htmlall':'UTF-8'}" />
                </a>
				
				<p id="logomarque">{if $product.id_manufacturer != 0}
				<img src="{$img_ps_dir}m/{$product.id_manufacturer}-medium_default.jpg" alt="" />
				{/if}</p>
				
                <div class="content_name">
                    <a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="prod_name">{$product.name|truncate:35:'...'|strip_tags:'UTF-8'|escape:'htmlall':'UTF-8'}</a>
                    {if !$PS_CATALOG_MODE}
                        <div class="price">
                            {if !$priceDisplay}
                                {convertPrice price=$product.price}
                            {else}
                                {convertPrice price=$product.price_tax_exc}
                            {/if}
                        </div>
                        {if isset($product.reduction) && $product.reduction}
                            <div class="prod_price_discount">{convertPrice price=$product.price_without_reduction}</div>
                        {/if}
                    {/if}	
                </div>	
            </li>
            {/foreach}
		</ul>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block best sellers -->
