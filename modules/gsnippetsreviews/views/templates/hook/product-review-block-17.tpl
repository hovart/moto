{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{* DISPLAY REVIEWS BLOCK *}
<!-- GSR - Product review block -->
{if !empty($bDisplayReviews) && !empty($bDisplayBlock)}
	<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="{$sBlockPosition|escape:'htmlall':'UTF-8'}">
		{if $sBlockPosition != 'productReassurance'}
		<div class="clr_10"></div>
		{/if}
		{* USE CASE - add social network js *}
		{if !empty($bUseSocialNetworkJs)}
		{literal}
		<script type="text/javascript">
			bt_oUseSocialButton.sFbLang = '{/literal}{$sFbLang|escape:'htmlall':'UTF-8'}{literal}';
		</script>
		{/literal}
		{/if}
		{* /USE CASE - add social network js *}

		<div class="clr_10"></div>
		<div class="block-review">
			<div class="block-review-item">
				<span class="title"><i class="fa fa-star"></i>&nbsp;{l s='Customer ratings and reviews' mod='gsnippetsreviews'}</span>
				<div class="clr_15"></div>
				{if !empty($iReviewAverage) && $sReviewType == 'aggregate'}
					{if !empty($bDisplayProductRichSnippets) && !empty($bProductBadge)}
					<div itemscope itemtype="http://schema.org/Product">
						<meta itemprop="name" content="{$sItemReviewed|escape:'htmlall':'UTF-8'}" />
					{/if}
					<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				{else}
					<div>
				{/if}
						{if !empty($iReviewAverage)}
							<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
							{section loop=$iMaxRatingBlock name=note}
								<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if !empty($iReviewAverage) && $iReviewAverage >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} product-block{if !empty($bHalfStar)}-half{/if}{if !empty($iReviewAverage) && $iReviewAverage >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>
							{/section}
							</div>
							&nbsp;<span>(</span>{if $sReviewType == 'aggregate'}<meta itemprop="worstRating" content="1" />{/if}<span {if $sReviewType == 'aggregate'}itemprop="ratingValue"{/if}>{$fReviewAverage|escape:'htmlall':'UTF-8'}</span><span>/{if $sReviewType == 'aggregate'}<span itemprop="bestRating">{$iAverageMaxRating|intval}</span>{else}{$iAverageMaxRating|intval}{/if})</span>
						{else}
							{l s='Nobody has posted a review yet' mod='gsnippetsreviews'}
							{if !empty($bEnableCustLang)}
								<div class="clr_0"></div>
								{l s='in this language' mod='gsnippetsreviews'}
							{/if}
						{/if}

						{if !empty($iCountRatings) || !empty($iCountReviews)}
							&nbsp;-&nbsp;<span>{if !empty($iCountRatings)}<span {if $sReviewType == 'aggregate'}itemprop="ratingCount"{/if}>{$iCountRatings|intval}</span> {l s='rating(s)' mod='gsnippetsreviews'}{if !empty($iCountReviews)} - <span {if $sReviewType == 'aggregate'}itemprop="reviewCount"{/if}>{$iCountReviews|intval}</span> {l s='review(s)' mod='gsnippetsreviews'}{/if}{/if}</span>
						{/if}


						{if !empty($iReviewAverage)}
						<div class="clr_5"></div>
						<a href="javascript:void(0);" onclick="bt_toggle('.display-distribution');">{l s='View distribution' mod='gsnippetsreviews'}</a>
						{/if}

						{if !empty($aDistribution)}
						<div class="clr_5"></div>
						<div class="display-distribution" style="display: none;">
							{foreach from=$aDistribution name=distrib key=iNote item=iCount}
								<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iDefaultMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $iNote >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="distrib-front{if $iNote >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}&nbsp;<strong>{$iCount|intval}</strong></div>
								<div class="clr_0"></div>
							{/foreach}
							<div class="clr_5"></div>
						</div>
						{/if}

						<div class="clr_10"></div>

						<div>
						{if !empty($iCountReviews)}
						<a class="btn btn-primary" id="bt_btn-review-list" href="javascript:void(0);" onclick="{if !empty($sReviewTabId)}bt_triggerClick('{$sReviewTabId|escape:'htmlall':'UTF-8'}'); /*$('{$sReviewTabId|escape:'htmlall':'UTF-8'}').trigger('click');*/{/if}bt_scrollTo('#anchorReview', 1200);"><i class="fa fa-star-empty"></i> {l s='Read reviews' mod='gsnippetsreviews'}</a>
						{/if}
						{if !empty($bUseRatings) || !empty($bUseComments)}
						<a class="btn btn-primary fancybox.ajax" id="bt_btn-review-form" href="{$sMODULE_URI|escape:'htmlall':'UTF-8'}?sAction={$aQueryParams.reviewForm.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reviewForm.type|escape:'htmlall':'UTF-8'}&iPId={$iProductId|intval}&iCId={$iCustomerId|intval}&sURI={$sURI|urlencode}&btKey={$sSecureReviewKey|escape:'htmlall':'UTF-8'}{if !empty($rtg)}&rtg={$rtg|escape:'htmlall':'UTF-8'}{/if}" rel="nofollow"><i class="icon-pencil"></i> {l s='Rate it' mod='gsnippetsreviews'}</a>
						{/if}
						</div>
						<div class="clr_0"></div>
					</div>
					{if !empty($iReviewAverage) && $sReviewType == 'aggregate' && !empty($bDisplayProductRichSnippets) && !empty($bProductBadge)}
					</div>
					{/if}
			</div>
		</div>
		{if $sBlockPosition != 'productReassurance'}
			<div class="clr_10"></div>
		{/if}
		{literal}
		<script type="text/javascript">
			bt_aFancyReviewForm.selector = 'a#bt_btn-review-form';
			bt_aFancyReviewForm.hideOnContentClick = false;
			bt_aFancyReviewForm.beforeClose = '{/literal}{$sProductLink nofilter}{literal}';
			bt_aFancyReviewForm.click = {/literal}{if !empty($bOpenForm) && (!empty($bUseRatings) || !empty($bUseComments))}true{else}false{/if}{literal};
		</script>
		{/literal}
	</div>
{/if}
<!-- /GSR - Product review block -->
{if !empty($sProductListTemplateInclude)}
	{if empty($bDisplayBlock)}
		<div class="gsr-clr_20"></div>
	{/if}
	{include file="`$sProductListTemplateInclude`"}
{/if}
{* /DISPLAY REVIEWS BLOCK *}