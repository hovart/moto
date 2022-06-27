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

<!-- MODULE Block specials -->
<div id="special_block" class="block colprods">
	<h4 class="title_block"><a href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='Specials' mod='blockspecials'}">{l s='Specials' mod='blockspecials'} &raquo;</a></h4>
	<div class="block_content">
        {if $special}
            <ul>
                <li>
                    <a href="{$special.link}" title="{$special.name|escape:'htmlall':'UTF-8'}" class="product_image">
                        <img src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'medium_default')|escape:'html'}" alt="{$special.name|escape:html:'UTF-8'}" height="{$mediumSize.height}" width="{$mediumSize.width}" title="{$special.name|escape:html:'UTF-8'}" />
                    </a>
                    <div class="content_name">
                        <a href="{$special.link|escape:'html'}" title="{$special.name|escape:'htmlall':'UTF-8'}" class="prod_name">{$special.name|truncate:35:'...'|strip_tags:'UTF-8'|escape:'htmlall':'UTF-8'}</a>
                        {if !$PS_CATALOG_MODE}
                            <div class="price">
                                {if !$priceDisplay}
                                    {displayWtPrice p=$special.price}
                                {else}
                                    {displayWtPrice p=$special.price_tax_exc}
                                {/if}
                            </div>
                            {if isset($special.reduction) && $special.reduction}
                                <div class="price-discount">{convertPrice price=$special.price_without_reduction}</div>
                            {/if}
                        {/if}	
                    </div>	
                </li>
            </ul>
        {else}
            <p>{l s='No product specials are available at this time.' mod='blockspecials'}</p>
        {/if}
	</div>
</div>
<!-- /MODULE Block specials -->