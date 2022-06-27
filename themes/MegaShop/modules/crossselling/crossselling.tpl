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

{if isset($orderProducts) && count($orderProducts)}
<div id="crossselling" class="tptncarousel clearfix">
	<h4>{l s='Customers who bought this product also bought:' mod='crossselling'}</h4>
	<div class="crsslides prodlist">
	{foreach from=$orderProducts item='orderProduct' name=orderProduct}
		<div class="item">
			<a class="product_img_link" href="{$orderProduct.link}" title="{$orderProduct.name|escape:html:'UTF-8'}">
				<img src="{$link->getImageLink($orderProduct.link_rewrite, $orderProduct.id_image, 'home_default')}" alt="{$orderProduct.name|escape:html:'UTF-8'}" />
			</a>
			<a class="prod_name" href="{$orderProduct.link}" title="{$orderProduct.name|escape:html:'UTF-8'}">{$orderProduct.name|truncate:30:'...'|escape:html:'UTF-8'}</a>
			{if $crossDisplayPrice AND $orderProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
				<div class="price-content">
					<div class="price">{convertPrice price=$orderProduct.displayed_price}</div>
				</div>	
			{/if}
			<div><a title="{l s='View' mod='crossselling'}" href="{$orderProduct.link}" class="button_small">{l s='View' mod='crossselling'}</a></div>
		</div>
	{/foreach}
	</div>
</div>
{/if}
