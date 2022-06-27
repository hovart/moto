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
<div class="venteflashbackground">
	<div class="container venteflash" id="venteflash">
		<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="clear">&nbsp;</div>
			<div id="flash_sale_complete_banner">

				{*
				{if $corner_banner_switch eq 1}
					<div id="cornerBanner" style="color:{$banner_text_color|escape:'htmlall':'UTF-8'};background-color:{$banner_bg_color|escape:'htmlall':'UTF-8'};">
						<h2 style="font-family:{$corner_banner_font|escape:'htmlall':'UTF-8'};"><b>{$corner_banner_text|escape:'htmlall':'UTF-8'}</b></h2>
					</div>
				{/if}
				*}
				<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">

					<!-- Carousel -->
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 tighten-up carousel-banner" {* style="background-color:{$flash_sale_info.bg_color|escape:'htmlall':'UTF-8'}; *}">
					{*<div class="block_title">{$corner_banner_text|escape:'htmlall':'UTF-8'}</div>*}
                    <div class="autoplay">
                        {if $flash_sale_info.sale_type eq "timed"}
                            <!-- Default Image if other side is used by timer -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sale-image-bground flash-sale-item-img tighten-up-devise" style="height:200px;background-image:url('{if $flash_sale_info.sale_custom_img_link neq ""}{$flash_sale_info.sale_custom_img_link|escape:'htmlall':'UTF-8'}{else}{$image_default|escape:'htmlall':'UTF-8'}{/if}');">
                                <a href="{$ps_url|escape:'htmlall':'UTF-8'}index.php?fc=module&module=flashsalepro&controller=flashSaleProducts&flashSaleId={$flash_sale_info.id_flashsalespro|escape:'htmlall':'UTF-8'}" class="mgd-flash-banner-link">
                                    <h2 style="color: transparent">{$flash_sale_info.name|escape:'htmlall':'UTF-8'}</h2>
                                </a>
                            </div>
                        {/if}
                        {foreach from=$flash_sale_items key=k item=v}
                            <!-- Each product -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up-devise picture">
                                <a  href="{$v.product_link|escape:'htmlall':'UTF-8'}">
                                    <div class="col-xs-6  col-md-4  tighten-up-devise" id="flash_sale_{$v.id_specific_price|escape:'htmlall':'UTF-8'}">
                                        <!-- Product image--><span class="sale-box">
                                                -{if $v.discount_type eq "percentage"}
                                            {$v.discount|escape:'htmlall':'UTF-8'}&nbsp;%
                                            {else}
                                            {if $flash_sale_info.currency_format eq 1}
                                                {$v.currency_sign|escape:'htmlall':'UTF-8'}{$v.discount|escape:'htmlall':'UTF-8'}
                                            {else}
                                                {$v.discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}{/if}
                                            {/if}
                                            </span>
                                        <a href="{$v.product_link|escape:'htmlall':'UTF-8'}"><img src="{$v.custom_img_link|escape:'htmlall':'UTF-8'}" class="flash-sale-item-img" id="flash_sale_img_{$v.id_specific_price|escape:'htmlall':'UTF-8'}"></a>
                                    </div>
                                </a>
                                <div class="col-xs-6 col-md-8 tighten-up-devise product" id="flash_sale_{$v.id_specific_price|escape:'htmlall':'UTF-8'}">
                                    <a  href="{$v.product_link|escape:'htmlall':'UTF-8'}" class="flash-product-link">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                                            <!-- Product Name -->
                                            <h2 class="home-product-name">{$v.product_name|escape:'htmlall':'UTF-8'}</h2>
										</div>
										<div class="clear">&nbsp;</div>
                                    	<div class="col-xs-5  col-md-3 ">
                                            <span class="mgd-venteflashicon {if $lang_iso == de }de{else}fr{/if}"></span>
                                            <!-- The amount discounted -->
                                    	</div>
										<div class="col-xs-7 col-md-9 content-price">
                                            
												<span class="price product-price new_product_price" >
												{if $flash_sale_info.currency_format eq 1}
														{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}&nbsp;
														{$v.currency_sign|escape:'htmlall':'UTF-8'}
													{else}
														{$v.product_price_after_discount|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}
													{/if}
												</span>
												<span class="old-price product-price">
													<!-- Original price of product -->

															{if $flash_sale_info.currency_format eq 1}
																{$v.product_price|escape:'htmlall':'UTF-8'}&nbsp;
																{$v.currency_sign|escape:'htmlall':'UTF-8'}
															{else}
																{$v.product_price|escape:'htmlall':'UTF-8'}&nbsp;{$v.currency_sign|escape:'htmlall':'UTF-8'}
															{/if}
												</span>
												<!-- Final price of discounted product -->

										</div>
									</a>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
                <!-- Clock -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 tighten-up{if $flash_sale_info.sale_type neq 'timed'} flash-sale-item-img sale-image-bground{/if}" {* style="{if $flash_sale_info.sale_type neq 'timed'}background-image:url('{$image_default|escape:'htmlall':'UTF-8'}');{else}background-color:{$flash_sale_info.bg_color|escape:'htmlall':'UTF-8'};{/if}" *}>
						<div class="clear">&nbsp;</div>
						<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
							{if $flash_sale_info.date_end neq '0000-00-00 00:00:00' && $flash_sale_info.sale_type eq 'timed'}
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
									<div class="clock-outer-center">
										<div class="clock-inner-center">
											<div class="clock"></div>
										</div>
									</div>
								</div>
							{elseif $flash_sale_info.sale_type eq 'stock'}
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 tighten-up">
									<h2 class="stock-manual-h2" style="font-family:{$flash_sale_info.font|escape:'htmlall':'UTF-8'};">{l s='Limited Stock' mod='flashsalepro'}</h2>
								</div>
							{elseif $flash_sale_info.sale_type eq 'manual'}
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 tighten-up">
									<h2 class="stock-manual-h2" style="font-family:{$flash_sale_info.font|escape:'htmlall':'UTF-8'};">{$flash_sale_info.name|escape:'htmlall':'UTF-8'}</h2>
								</div>
							{/if}
						</div>
						<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
								<div class="view-all-outer-center">
									<div class="view-all-inner-center">
										<span id="flash_sale_view_all">
											<a class="ghost-button{if $flash_sale_info.sale_type eq 'timed'} ghost-button-timed{else} ghost-button-stock-manual{/if}" href="{$ps_url|escape:'htmlall':'UTF-8'}index.php?fc=module&module=flashsalepro&controller=flashSaleProducts&flashSaleId={$flash_sale_info.id_flashsalespro|escape:'htmlall':'UTF-8'}" style="{if $flash_sale_info.sale_type eq 'timed'}color:{$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};border: 1px solid {$flash_sale_info.text_color|escape:'htmlall':'UTF-8'};{/if}">
												<span>{l s='View All Flash Sales' mod='flashsalepro'}</span>
											</a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
		/* home - Variables used in flipclock.min.js for inline style on element of class "inn" */
		var timer_bg_color = "{/literal}{$timer_bg_color|escape:'htmlall':'UTF-8'}{literal}";
		var timer_text_color = "{/literal}{$timer_text_color|escape:'htmlall':'UTF-8'}{literal}";
		var timer_dot_color = "{/literal}{$timer_dot_color|escape:'htmlall':'UTF-8'}{literal}";

		var date_end = "{/literal}{$flash_sale_info.end_date_timestamp|escape:'htmlall':'UTF-8'}{literal}";
		var lang_code = "{/literal}{$flash_sale_info.lang_code|escape:'htmlall':'UTF-8'}{literal}";
		$(document).ready(function(){
				$('.autoplay').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2500,
					speed: 2000,
					dots : false,
					draggable: false,
					touchMove: false,
				});
		});
</script>
{/literal}