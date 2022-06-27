{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='Manufacturers:'}{/capture}

<h1 class="page-heading product-listing">
	{l s='Brands'}
    {strip}
		<span class="heading-counter">
			{if $nbManufacturers == 0}{l s='There are no manufacturers.'}
			{else}
				{if $nbManufacturers == 1}
					{l s='There is 1 brand'}
				{else}
					{l s='There are %d brands' sprintf=$nbManufacturers}
				{/if}
			{/if}
		</span>
    {/strip}
</h1>
{if isset($errors) AND $errors}
	{include file="$tpl_dir./errors.tpl"}
{else}
	{if $nbManufacturers > 0}
       	<ul id="manufacturers_list" class="list row">
			{foreach from=$manufacturers item=manufacturer name=manufacturers}
	        	<li class="col-xs-6 col-sm-4 col-md-3">
					<div class="mansup-container">
						<div class="logo">
							{if $manufacturer.nb_products > 0}
								<a
								class="lnk_img" 
								href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html':'UTF-8'}" 
								title="{$manufacturer.name|escape:'html':'UTF-8'}" >
							{/if}
								<img src="{$img_manu_dir}{$manufacturer.id_manufacturer|escape:'html':'UTF-8'}-small_default.jpg" alt="{$manufacturer.name|escape:'html':'UTF-8'}" />
							{if $manufacturer.nb_products > 0}
								</a>
							{/if}
						</div>
			        </div>
				</li>
			{/foreach}
		</ul>
		<div class="content_sortPagiBar">
			<div class="bottom-pagination-content clearfix">
				{include file="$tpl_dir./pagination.tpl" no_follow=1 paginationId='bottom'}
			</div>
		</div>
	{/if}
{/if}