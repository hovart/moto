{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Madalweb Free Countdown Prestashop Module -->
<div id="special_block_right" class="block products_block exclusive blockspecials">
	<h2 class="title_block"><a href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='Specials' mod='countdownspecialblock'}">{l s='Specials' mod='countdownspecialblock'}</a></h2>
	<div class="block_content">

{if $special}
		<ul class="products clearfix">
			<li class="ajax_block_product">
			  <div class="left-block">
				<a class="product_img_link" href="{$special.link|escape:'html':'UTF-8'}" title="{$special.name|escape:'html':'UTF-8'}" itemprop="url"><img class="replace-2x img-responsive" src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'home_default')|escape:'html'}" alt="{$special.legend|escape:html:'UTF-8'}" height="{$homeSize.height}" width="{$homeSize.width}" title="{$special.name|escape:html:'UTF-8'}" /></a>
			  </div>
			  <div class="madalweb_free_countdown_right-block">
			  <h5 itemprop="name"><a class="madalweb_free_countdown_module" href="{$special.link}" title="{$special.name|escape:html:'UTF-8'}">{$special.name|escape:html:'UTF-8'}</a></h5>
				
					
					{if (!$PS_CATALOG_MODE )}
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="madalweb_free_countdown_module_special_content_price">
						<span id="madalweb_special_countdown_price-discount">{if !$priceDisplay}{displayWtPrice p=$special.price_without_reduction}{else}{displayWtPrice p=$priceWithoutReduction_tax_excl}{/if}</span>
						 {if $special.specific_prices}
						 {assign var='specific_prices' value=$special.specific_prices}
						  {if $specific_prices.reduction_type == 'percentage' && ($specific_prices.from == $specific_prices.to OR ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' <= $specific_prices.to && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $specific_prices.from))}
						   <span id="madalweb_special_count_reduction">(-{$specific_prices.reduction * 100|floatval}%)</span>
						  {/if}
						 {/if}
						<span id="madalweb_special_count_price">{if !$priceDisplay}{displayWtPrice p=$special.price}{else}{displayWtPrice p=$special.price_tax_exc}{/if}</span>						
					</div>
					{/if}
				
				{if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $specific_prices.to )&& ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $specific_prices.from)&& ($specific_prices.to != '0000-00-00 00:00:00')}
					<b class="counttitle">{l s='Remaining Time' mod='countdownspecialblock'} :</b></br></br>
					<div class="countdownspeblock">
					   <div style="display: none;" id="multilanguageready" data-days={l s='Days' mod='countdownspecialblock'} data-hours={l s='Hours' mod='countdownspecialblock'} data-minutes={l s='Minutes' mod='countdownspecialblock'} data-seconds={l s='Seconds' mod='countdownspecialblock'}></div>
					   </div>
					   
					   <script type="text/javascript">
					   jQuery(document).ready(function($){
					   var $madalweb_multil = $('#multilanguageready');
					   $(".countdownspeblock").dsCountDown({
					    endDate: new Date("{$specific_prices.to}"),
   theme: 'black',titleDays:$madalweb_multil.data('days'),titleHours:$madalweb_multil.data('hours'),titleMinutes:$madalweb_multil.data('minutes'),titleSeconds:$madalweb_multil.data('seconds')
					   });
					   });
					   </script>
					   {elseif ($specific_prices.to == '0000-00-00 00:00:00') 
	&& ($specific_prices.from == '0000-00-00 00:00:00')}
			 <b>{l s='Limited Special Offer' mod='countdownspecialblock'}</b>
			 {/if}
					  <br>
			</div>
			</li>
		</ul>
		<p>
			<a class="madalweb_free_module" href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='All specials' mod='countdownspecialblock'}">&raquo; {l s='All specials' mod='countdownspecialblock'}</a>
		</p>
{else}
		<p>{l s='No specials at this time.' mod='countdownspecialblock'}</p>
{/if}
	</div>
</div>
<!-- /Madalweb Free Countdown Prestashop Module -->