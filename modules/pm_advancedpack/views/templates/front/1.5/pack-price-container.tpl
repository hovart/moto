{if !$priceDisplay || $priceDisplay == 2}
	{assign var='productPrice' value=AdvancedPack::getPackPrice($product->id, true, true, true, $priceDisplayPrecision, $packAttributesList, $packExcludeList, true)}
	{assign var='productPriceWithoutReduction' value=AdvancedPack::getPackPrice($product->id, true, false, true, $priceDisplayPrecision, $packAttributesList, $packExcludeList, true)}
{elseif $priceDisplay == 1}
	{assign var='productPrice' value=AdvancedPack::getPackPrice($product->id, false, true, true, $priceDisplayPrecision, $packAttributesList, $packExcludeList, true)}
	{assign var='productPriceWithoutReduction' value=AdvancedPack::getPackPrice($product->id, false, false, true, $priceDisplayPrecision, $packAttributesList, $packExcludeList, true)}
{/if}

						<div id="ap5-buy-block-container">
							{* prices *}
							{if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
							<div id="ap5-price-container" class="content_prices clearfix">
								<div class="price">
									<p class="our_price_display">
										{if $priceDisplay >= 0 && $priceDisplay <= 2}
											<span id="our_price_display" itemprop="price">{convertPrice price=$productPrice}</span>
											{* {if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) || !isset($display_tax_label))}
												{if $priceDisplay == 1}{l s='tax excl.' mod='pm_advancedpack'}{else}{l s='tax incl.' mod='pm_advancedpack'}{/if}
											{/if} *}
											<meta itemprop="priceCurrency" content="{$currency->iso_code|escape:'html':'UTF-8'}" />
										{/if}
									</p>
									{if $product->specificPrice.reduction != 0}
										<p id="reduction_percent" {if !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>
											<span id="reduction_percent_display">
												{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}-{$product->specificPrice.reduction*100}%{/if}
											</span>
										</p>
										<p id="reduction_amount" {if !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if}>
											<span id="reduction_amount_display">
											{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|intval !=0}
												-{convertPrice price=$productPriceWithoutReduction-$productPrice|floatval}
											{/if}
											</span>
										</p>	
										<p id="old_price"{if (!$product->specificPrice || !$product->specificPrice.reduction) && $group_reduction == 1} class="hidden"{/if}>
											{if $priceDisplay >= 0 && $priceDisplay <= 2}
												<span id="old_price_display">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction}{/if}</span>
												{* {if $tax_enabled && $display_tax_label == 1}{if $priceDisplay == 1}{l s='tax excl.' mod='pm_advancedpack'}{else}{l s='tax incl.' mod='pm_advancedpack'}{/if}{/if} *}
											{/if}
										</p>
									{/if}
									{if $priceDisplay == 2}
										<br />
										<span id="pretaxe_price">
											<span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>
											{l s='tax excl.' mod='pm_advancedpack'}
										</span>
									{/if}
								</div>
	
								{if $product->ecotax != 0}
									<p class="price-ecotax">{l s='Include' mod='pm_advancedpack'} <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span> {l s='For green tax' mod='pm_advancedpack'}
										{if $product->specificPrice && $product->specificPrice.reduction}
										<br />{l s='(not impacted by the discount)' mod='pm_advancedpack'}
										{/if}
									</p>
								{/if}
	
								{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
									{math equation="pprice / punit_price"  pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
									<p class="unit-price"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per' mod='pm_advancedpack'} {$product->unity|escape:'html':'UTF-8'}</p>
								{/if}
							</div>
							{/if}
							{* end prices *}
	
							<!-- buy action and errors message -->
							<div id="ap5-buy-container" {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE} class="unvisible"{/if}>
							{if isset($productsPackFatalErrors) && count($productsPackFatalErrors)}
								<p class="ap5-pack-unavailable animated shake alert alert-danger">
									<span>{l s='One of product is no longer available. This pack can\t be purchased' mod='pm_advancedpack'}</span>
								</p>
							{else if isset($productsPackErrors) && count($productsPackErrors)}
								<p class="ap5-combination-unavailable animated flash alert alert-warning">
									<span><a href="#ap5-pack-product-{current(array_keys($productsPackErrors))|intval}">{l s='One of product combination is no longer available. Please select another attribute to this product' mod='pm_advancedpack'}</a></span>
								</p>
							{else}
								<!-- quantity wanted -->
								<p id="quantity_wanted_p">
									<label>{l s='Quantity:' mod='pm_advancedpack'}&nbsp;</label>
									<input type="text" name="qty" id="quantity_wanted" class="text" value="1" size="2" maxlength="3" />
								</p>
								<p id="add_to_cart_pack" {if (!$allow_oosp && $product->quantity <= 0) OR !$product->available_for_order OR (isset($restricted_country_mode) AND $restricted_country_mode) OR $PS_CATALOG_MODE}style="display:none"{/if} class="buttons_bottom_block">
									<input type="submit" name="Submit" value="{l s='Add this pack' mod='pm_advancedpack'}" class="exclusive" />
								</p>
							{/if}
							</div>
							<!-- end buy action and errors message -->
						</div>