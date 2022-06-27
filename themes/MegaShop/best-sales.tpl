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

{capture name=path}{l s='Top sellers'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Top sellers'}</h1>

{if $products}
	<div class="content_sortPagiBar">
		<div class="PagiNbr clearfix">
			{include file="$tpl_dir./pagination.tpl"}
			{include file="./nbr-product-page.tpl"}
		</div>
		<div class="sortPagiBar clearfix">
			{include file="./product-compare.tpl"}
			{include file="./product-sort.tpl"}
		</div>
	</div>
	
	{include file="./product-list.tpl" products=$products}
	
	<div class="content_sortPagiBar clearfix">
		{include file="./pagination.tpl" paginationId='bottom'}
	</div>
{else}
	<p class="warning">{l s='No top sellers for the moment.'}</p>
{/if}
