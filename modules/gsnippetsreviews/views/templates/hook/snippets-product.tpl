{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplay) && !empty($sBadgeStyle)}
	<!-- GSR - Rich Snippets Product -->
	{if $sBadgeStyle == "bottom"}
	<div class="clr_20"></div>
	{/if}
	{if !empty($bColStyle)}
	<div class="width-100">
	{/if}
		<div {if !empty($sBadgeFreeStyle)}style="{$sBadgeFreeStyle|escape:'UTF-8'}"{else}class="badge-{$sBadgeStyle|escape:'htmlall':'UTF-8'}"{/if}>
			{* DISPLAY RICH SNIPPETS *}
			{if !empty($sBadgeStyle) && ($sBadgeStyle == "bottom" || $sBadgeStyle == "top")}
				{assign var="sGsrSeparator" value=" - "}
				{assign var="sGsrSeparatorTop" value=""}
			{else}
				{assign var="sGsrSeparator" value="<br />"}
				{assign var="sGsrSeparatorTop" value="<br />"}
			{/if}
			<div class="product-snippets">
				{*<strong class="heading">{l s='Google Rich Snippets' mod='gsnippetsreviews'} {$sGsrSeparatorTop|escape:'UTF-8'}</strong>*}
				<span itemscope itemtype="http://schema.org/Product">
					<strong><span itemprop="name">{$aProduct.name|escape:'UTF-8'}</span></strong>
					{if !empty($bUseBrand) && !empty($aProduct.manufacturer_name)}{$sGsrSeparator|escape:'UTF-8'}<span itemprop="brand">{$aProduct.manufacturer_name|escape:'UTF-8'}</span>{/if}
					{if !empty($bUseDesc) && !empty($aProduct.googleDesc)}{$sGsrSeparator|escape:'UTF-8'}<span itemprop="description">{$aProduct.googleDesc|escape:'UTF-8'|truncate:60:"..."}</span>{/if}
					{* USE CASE - GTIN *}
					{if !empty($bUseIdentifier) && (!empty($aProduct.ean13) || !empty($aProduct.upc))}
						{$sGsrSeparator|escape:'UTF-8'}
						<strong>{l s='Product GTIN' mod='gsnippetsreviews'}</strong> :
						{if !empty($aProduct.ean13)}
							<span itemprop="gtin13">{$aProduct.ean13|escape:'UTF-8'}{$sGsrSeparator|escape:'UTF-8'}</span>
						{elseif !empty($aProduct.upc)}
							<span itemprop="gtin13">0{$aProduct.upc|escape:'UTF-8'}{$sGsrSeparator|escape:'UTF-8'}</span>
						{/if}
						{if !empty($aProduct.reference)}
							<strong>{l s='Product Ref' mod='gsnippetsreviews'}</strong> : <span itemprop="sku">{$aProduct.reference|escape:'UTF-8'}</span>
						{/if}
					{/if}
					{if !empty($bUseSupplier) && !empty($aProduct.supplier_reference)}
						{$sGsrSeparator|escape:'UTF-8'}<strong>{l s='Supplier Ref' mod='gsnippetsreviews'}</strong> : <span itemprop="mpn">{$aProduct.supplier_reference|escape:'UTF-8'}</span>
					{/if}
					{* USE CASE - Product's condition *}
					{if !empty($bUseCondition) && !empty($aProduct.condition)}
						{$sGsrSeparator|escape:'UTF-8'}
						<strong>{l s='Label' mod='gsnippetsreviews'}</strong> :&nbsp;
						{if $aProduct.condition == "used"}
							{l s='Used' mod='gsnippetsreviews'}
							<link itemprop="itemCondition" href="http://schema.org/UsedCondition"/>
							<span>{l s='Used' mod='gsnippetsreviews'}</span>
						{elseif $aProduct.condition == "refurbished"}
							<link itemprop="itemCondition" href="http://schema.org/RefurbishedCondition"/>
							<span>{l s='Refurbished' mod='gsnippetsreviews'}</span>
						{else}
							<link itemprop="itemCondition" href="http://schema.org/NewCondition"/>
							<span>{l s='New' mod='gsnippetsreviews'}</span>
						{/if}
					{/if}

					{* USE CASE - Offer Aggregate *}
					{if !empty($bOfferAggregate) && !empty($aProduct.combinations) && ((!empty($bUseOfferCount) || !empty($bUseHighPrice)) && !empty($aProduct.highestPrice) && $aProduct.highestPrice > $aProduct.lowestPrice)}
						{$sGsrSeparator|escape:'UTF-8'}
						<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
							{if !empty($bUseCat) && !empty($aProduct.category)}<strong>{l s='Category' mod='gsnippetsreviews'}</strong> : <span itemprop="category">{$aProduct.category|escape:'UTF-8'}</span>{$sGsrSeparator|escape:'UTF-8'}{/if}
							{if !empty($bUseHighPrice) && !empty($aProduct.highestPrice) && $aProduct.highestPrice > $aProduct.lowestPrice}{$aProduct.currencyPrefix|escape:'htmlall':'UTF-8'} <span itemprop="lowPrice">{$aProduct.lowestPrice|escape:'htmlall':'UTF-8'}</span> {$aProduct.currencySuffix|escape:'htmlall':'UTF-8'} {/if}
							{if !empty($bUseHighPrice) && !empty($aProduct.highestPrice) && $aProduct.highestPrice > $aProduct.lowestPrice} {l s='to' mod='gsnippetsreviews'} {$aProduct.currencyPrefix|escape:'htmlall':'UTF-8'} <span itemprop="highPrice">{$aProduct.highestPrice|escape:'htmlall':'UTF-8'}</span> {$aProduct.currencySuffix|escape:'htmlall':'UTF-8'} {/if}
							<meta itemprop="priceCurrency" content="{$aProduct.currency|escape:'htmlall':'UTF-8'}" />
							{if !empty($bUseOfferCount) && !empty($aProduct.offerCount) && !empty($aProduct.highestPrice) && !empty($aProduct.lowestPrice) && $aProduct.highestPrice > $aProduct.lowestPrice}{l s='From' mod='gsnippetsreviews'} <span itemprop="offerCount">{$aProduct.offerCount|escape:'UTF-8'} {l s='combinations' mod='gsnippetsreviews'}</span>{/if}
						</span>
					{* USE CASE - Offer *}
					{else}
						{$sGsrSeparator|escape:'UTF-8'}
						<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							{if !empty($bUseCat) && !empty($aProduct.category)}<strong>{l s='Category' mod='gsnippetsreviews'}</strong> : <span itemprop="category">{$aProduct.category|escape:'UTF-8'}</span>{$sGsrSeparator|escape:'UTF-8'}{/if}
							<strong>{l s='Price' mod='gsnippetsreviews'}</strong> : {$aProduct.currencyPrefix|escape:'UTF-8'}<span itemprop="price">{$aProduct.price|escape:'UTF-8'}</span>{$aProduct.currencySuffix|escape:'UTF-8'}
							<meta itemprop="priceCurrency" content="{$aProduct.currency|escape:'htmlall':'UTF-8'}" />
							{if !empty($bUseUntilDate) && !empty($aProduct.untilDate)}
								{$sGsrSeparator|escape:'UTF-8'}
								({l s='Sale ends' mod='gsnippetsreviews'} <span itemprop="priceValidUntil" itemtype="http://schema.org/Date">
								(<time itemprop="endDate" datetime="{$aProduct.untilDate|escape:'htmlall':'UTF-8'}">{$aProduct.untilDateHuman|escape:'UTF-8'}</time>)</span>
							{/if}
							{if !empty($bUseSeller) && !empty($aProduct.seller)}
								{$sGsrSeparator|escape:'UTF-8'}{l s='Available from' mod='gsnippetsreviews'} <span itemprop="seller"> "{$aProduct.seller|escape:'UTF-8'}"</span>
							{/if}
							{if !empty($bUseAvailability)}
								{$sGsrSeparator|escape:'UTF-8'}
								<strong>{l s='Stock' mod='gsnippetsreviews'}</strong> : &nbsp;
								{if $aProduct.quantity > 0 || $aProduct.stockManagement == 0}
									<link itemprop="availability" href="http://schema.org/InStock"/>
									{l s='In Stock' mod='gsnippetsreviews'}
								{else}
									{l s='Out of Stock' mod='gsnippetsreviews'}
								{/if}
							{/if}
						</span>
					{/if}
				</span>
				{if !empty($bUseBreadcrumb) && !empty($aProduct.breadcrumb)}
					{$sGsrSeparator|escape:'UTF-8'}
					<span itemscope itemtype="http://schema.org/WebPage">
					<span class="navigation-pipe" itemprop="breadcrumb">
						{$aProduct.breadcrumb|escape:'UTF-8'}
					</span>
				</span>
				{/if}
				<br />
			</div>
			{* /DISPLAY RICH SNIPPETS *}
		</div>
	{if !empty($bColStyle)}
	</div>
	{/if}
	{if $sBadgeStyle != "bottom"}
	<div class="clr_20"></div>
	{/if}
	<!-- /GSR - Rich Snippets Product -->
{/if}