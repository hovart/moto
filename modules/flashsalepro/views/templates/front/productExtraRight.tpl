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
<!-- Flash Sale Pro Module content -->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color:{$flash_sale_info.bg_color|escape:'htmlall':'UTF-8'};">
	<!-- Carousel -->
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="clear">&nbsp;</div>
		<div class="autoplay">
			{foreach from=$flash_sale_items key=k item=v}
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
					<a href="{$v.product_link|escape:'htmlall':'UTF-8'}">
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<a href="{$v.product_link|escape:'htmlall':'UTF-8'}"><img src="{$v.custom_img_link|escape:'htmlall':'UTF-8'}" style="max-height:160px;"></a>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="color:{$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};font-family:{$flash_sale_info.font|escape:'htmlall':'UTF-8'};">
								<span style="float:right;">
									<span class="old-price product-price">
										{if $flash_sale_info.currency_format eq 1}
											{$v.currency_sign|escape:'htmlall':'UTF-8'}
											{$v.product_price|escape:'htmlall':'UTF-8'}&nbsp;
										{else}
											{$v.product_price|escape:'htmlall':'UTF-8'}
											{$v.currency_sign|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;
										{/if}
									</span>
									<span class="price-percent-reduction">
										&nbsp;-
										{if $v.discount_type eq "percentage"}
											{$v.discount|escape:'htmlall':'UTF-8'}%
										{else}
											{if $flash_sale_info.currency_format eq 1}
												{$v.currency_sign|escape:'htmlall':'UTF-8'}
												{$v.discount|escape:'htmlall':'UTF-8'}
											{else}
												{$v.discount|escape:'htmlall':'UTF-8'}
												{$v.currency_sign|escape:'htmlall':'UTF-8'}
											{/if}
										{/if}
									</span>
									<span class="price product-price">
										{if $flash_sale_info.currency_format eq 1}
											{$v.currency_sign|escape:'htmlall':'UTF-8'}
											{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}&nbsp;
										{else}
											{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}
											{$v.currency_sign|escape:'htmlall':'UTF-8'}
										{/if}
									</span>
								</span>
							</div>
						</div>
						
						<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color:{$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};font-family:{$flash_sale_info.font|escape:'htmlall':'UTF-8'};">
							<h5 class="product-name" style="color:{$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};">{$v.product_name|escape:'htmlall':'UTF-8'}</h5>
						</div>
					</a>
				</div>
			{/foreach}
		</div>
	</div>
	{if $flash_sale_info.date_end neq '0000-00-00 00:00:00' && $flash_sale_info.sale_type eq 'timed' && $this_product_in_sale eq 0}
		{include file="./minitureClock.tpl"}
	{/if}
	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
		<div class="view-all-outer-center">
			<div class="view-all-inner-center">
				<span id="flash_sale_view_all">
					<a class="ghost-button{if $flash_sale_info.sale_type eq 'timed'} ghost-button-timed{else} ghost-button-stock-manual{/if}" href="{$ps_url|escape:'htmlall':'UTF-8'}index.php?fc=module&module=flashsalepro&controller=flashSaleProducts&flashSaleId={$flash_sale_info.id_flashsalespro|escape:'htmlall':'UTF-8'}" style="{if $flash_sale_info.sale_type eq 'timed'}color:{$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};border: 1px solid {$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};{else}margin-top:0px;{/if}">
						<span style="font-family:{$flash_sale_info.font|escape:'htmlall':'UTF-8'};">{l s='View All Products' mod='flashsalepro'}</span>
					</a>
				</span>
			</div>
		</div>
	</div>
</div>
