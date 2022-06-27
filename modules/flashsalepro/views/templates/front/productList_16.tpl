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
{if $flash_sale_info.date_end neq '0000-00-00 00:00:00'}
	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
		<div class="view-all-outer-center">
			<div class="view-all-inner-center">
				<span class="">
					<div class="clock"></div>
				</span>
			</div>
		</div>
	<div class="clear">&nbsp;</div>
	</div>
{/if}
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
{foreach from=$flash_sale_items key=k item=v}
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="well">
			<a href="{$v.product_link|escape:'htmlall':'UTF-8'}">
				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<img class="img-responsive flash-sale-product-list-img" src="{$v.custom_img_link|escape:'htmlall':'UTF-8'}">
						</div>
					</div>
				</div>
				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<h5><a class="product-name" href="{$v.product_link|escape:'htmlall':'UTF-8'}">{$v.product_name|escape:'htmlall':'UTF-8'}</a></h5>
						</div>
					</div>
				</div>

				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="view-all-outer-center">
						<div class="view-all-inner-center">
							<span class="price product-price">
								<b>
									{if $flash_sale_info.currency_format eq 1}
										{$v.currency_sign|escape:'htmlall':'UTF-8'}&nbsp;{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}
									{else}
										{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}
									{/if}
								</b>
							</span>&nbsp;&nbsp;
							<span class="old-price product-price">
								{if $flash_sale_info.currency_format eq 1}
									{$v.currency_sign|escape:'htmlall':'UTF-8'}&nbsp;{$v.product_price|escape:'htmlall':'UTF-8'}
								{else}
									{$v.product_price|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}
								{/if}
							</span>&nbsp;&nbsp;
							<span class="price-percent-reduction">
								-&nbsp;{if $v.discount_type eq "percentage"}{$v.discount|escape:'htmlall':'UTF-8'}&nbsp;%{else}{if $flash_sale_info.currency_format eq 1}{$v.currency_sign|escape:'htmlall':'UTF-8'}&nbsp;{$v.discount|escape:'htmlall':'UTF-8'}{else}{$v.discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}{/if}{/if}
							</span>
						</div>
					</div>
				</div>
			</a>
			<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-top">
				<div class="view-all-outer-center">
					<div class="view-all-inner-center">
						<a class="button ajax_add_to_cart_button btn btn-default" href="{$ps_url|escape:'htmlall':'UTF-8'}panier?add=1&amp;id_product={$v.id_product|intval}&amp;id_shop={$flash_sale_info.id_shop|intval}" rel="nofollow" title="{l s='Add to cart' mod='flashsalepro'}" data-id-product="{$v.id_product|intval}" data-minimal_quantity="1" id="fs_add_to_cart_{$v.id_product|intval}">
							<span>{l s='Add to cart' mod='flashsalepro'}</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
{/foreach}
</div>

{literal}
<script type="text/javascript">
			var date_end = "{/literal}{$flash_sale_info.end_date_timestamp|escape:'htmlall':'UTF-8'}{literal}";
			var lang_code = "{/literal}{$flash_sale_info.lang_code|escape:'htmlall':'UTF-8'}{literal}";
			var timer_bg_color = "{/literal}{$timer_bg_color|escape:'htmlall':'UTF-8'}{literal}";
			var timer_text_color = "{/literal}{$timer_text_color|escape:'htmlall':'UTF-8'}{literal}";
			var timer_dot_color = "{/literal}{$timer_dot_color|escape:'htmlall':'UTF-8'}{literal}";
			$(document).ready(function(){
				$('.autoplay').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 1000,
					speed: 5000
				});
			});
</script>
{/literal}