{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{* DISPLAY REVIEWS BLOCK *}
<!-- GSR - Product review block -->
{if !empty($bDisplayReviews)}
	{* USE CASE - add social network js *}
	{if !empty($bUseSocialNetworkJs)}
	{literal}
	<script type="text/javascript">
		bt_oUseSocialButton.sFbLang = '{/literal}{$sFbLang|escape:'htmlall':'UTF-8'}{literal}';
	</script>
	{/literal}
	{/if}
	{* /USE CASE - add social network js *}
	{* USE CASE - add social network js *}
	{*{if !empty($bUseSocialNetworkJs)}*}
	{* JS - FACEBOOK *}
	{*{literal}*}
	{*<script type="text/javascript">*}
		{*$(document).ready(function(){*}
			{*// add div fb-root*}
			{*if ($('div#fb-root').length == 0) {*}
				{*FBRootDom = $('<div>', {'id':'fb-root'});*}
				{*$('body').prepend(FBRootDom);*}
			{*}*}
			{*(function(d, s, id) {*}
				{*var js, fjs = d.getElementsByTagName(s)[0];*}
				{*if (d.getElementById(id)) return;*}
				{*js = d.createElement(s); js.id = id; js.async = true;*}
				{*js.src = "//connect.facebook.net/{/literal}{$sFbLang|escape:'htmlall':'UTF-8'}{literal}/all.js#xfbml=1";*}
				{*fjs.parentNode.insertBefore(js, fjs);*}
			{*}(document, 'script', 'facebook-jssdk'));*}
		{*});*}
	{*</script>*}
	{*{/literal}*}
	{* /JS - FACEBOOK *}

	{* JS - TWITTER *}
	{*{literal}*}
	{*<script type="text/javascript">*}
		{*$(document).ready(function() {*}
			{*!function (d, s, id) {*}
				{*var js, fjs = d.getElementsByTagName(s)[0];*}
				{*if (!d.getElementById(id)) {*}
					{*js = d.createElement(s);*}
					{*js.id = id;*}
					{*js.src = "//platform.twitter.com/widgets.js";*}
					{*fjs.parentNode.insertBefore(js, fjs);*}
				{*}*}
			{*}(document, "script", "twitter-wjs");*}
		{*});*}
	{*</script>*}
	{*{/literal}*}
	{* /JS - TWITTER *}
	{*{/if}*}
	{* /USE CASE - add social network js *}
	<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="{$sBlockPosition|escape:'htmlall':'UTF-8'} average-heading">
		<div class="clear"></div>
		<p class="average-heading-title padding-left-15"><i class="icon-star-empty"></i> <strong>{l s='Customer ratings and reviews' mod='gsnippetsreviews'}</strong></p>
		{if !empty($iReviewAverage) && $sReviewType == 'aggregate'}
		{if !empty($bDisplayProductRichSnippets) && !empty($bProductBadge)}
		<div itemscope itemtype="http://schema.org/Product">
			<meta itemprop="name" content="{$sItemReviewed|escape:'htmlall':'UTF-8'}" />
		{/if}
		<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		{else}
			<div>
				{/if}
				<div class="display-review padding-left-right">
					<div class="pull-left">
						{if !empty($iReviewAverage)}
							<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
								{section loop=$iMaxRatingBlock name=note}
									<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if !empty($iReviewAverage) && $iReviewAverage >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} product-block{if !empty($bHalfStar)}-half{/if}{if !empty($iReviewAverage) && $iReviewAverage >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>
								{/section}
							</div>
							<div class="pull-right">
								<span class="left text-size-07 padding-left5">(</span>{if $sReviewType == 'aggregate'}<meta itemprop="worstRating" content="1" />{/if}<span class="left text-size-07" {if $sReviewType == 'aggregate'}itemprop="ratingValue"{/if}>{$fReviewAverage|escape:'htmlall':'UTF-8'}</span><span class="left text-size-07">/{if $sReviewType == 'aggregate'}<span itemprop="bestRating" class="text-size-1">{$iAverageMaxRating|intval}</span>{else}{$iAverageMaxRating|intval}{/if})</span>
							</div>
						{else}
							<span class="left default-text">{l s='Nobody has posted a review yet' mod='gsnippetsreviews'}</span>{if !empty($bEnableCustLang)}<div class="clr_0"></div><span class="left default-text">{l s='in this language' mod='gsnippetsreviews'}</span>{/if}
						{/if}
					</div>

					{if !empty($iCountRatings) || !empty($iCountReviews)}
						<div class="review-count-text left">{if !empty($iCountRatings)}<span class="padding-left5" {if $sReviewType == 'aggregate'}itemprop="ratingCount"{/if}>{$iCountRatings|intval}</span> {l s='rating(s)' mod='gsnippetsreviews'}{if !empty($iCountReviews)} - <span {if $sReviewType == 'aggregate'}itemprop="reviewCount"{/if}>{$iCountReviews|intval}</span> {l s='review(s)' mod='gsnippetsreviews'}{/if}{/if}</div>
					{/if}

					{if !empty($iReviewAverage)}
						<div class="clr_5"></div>
						<a class="distrib-text padding-left-15" href="javascript:void(0);" onclick="bt_toggle('.display-distribution');/*$('.display-distribution').toggle();*/">{l s='View distribution' mod='gsnippetsreviews'}</a>
					{/if}
				</div>

				{if !empty($aDistribution)}
					<div class="display-distribution" style="display: none;">
						{foreach from=$aDistribution name=distrib key=iNote item=iCount}
							<div class="display-distribution-line rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}" id="{$sModuleName|escape:'htmlall':'UTF-8'}Distribution{$smarty.foreach.distrib.iteration|intval}">{section loop=$iDefaultMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $iNote >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="distrib-front{if $iNote >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}&nbsp;<strong>{$iCount|intval}</strong></div>
						{/foreach}
						<div class="clr_5"></div>
					</div>
				{/if}

				<div class="clr_5"></div>

				<div {if $sBlockPosition == 'productAction'}class="text-center"{/if}>
					{if !empty($iCountReviews)}
						<a class="btn btn-primary" href="javascript:void(0);" onclick="{if !empty($sReviewTabId)}bt_triggerClick('{$sReviewTabId|escape:'htmlall':'UTF-8'}');/*$('{$sReviewTabId|escape:'htmlall':'UTF-8'}').trigger('click');*/{/if}bt_scrollTo('#anchorReview', 1200);/*$.scrollTo('#anchorReview', 1200);*/"><i class="icon-star-empty"></i> {l s='Read reviews' mod='gsnippetsreviews'}</a>
					{/if}
					{if !empty($bUseRatings) || !empty($bUseComments)}
						<a class="btn btn-default fancybox.ajax" id="bt_btn-review-form" href="{$sMODULE_URI|escape:'htmlall':'UTF-8'}?sAction={$aQueryParams.reviewForm.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reviewForm.type|escape:'htmlall':'UTF-8'}&iPId={$iProductId|intval}&iCId={$iCustomerId|intval}&sURI={$sURI|urlencode}&btKey={$sSecureReviewKey|escape:'htmlall':'UTF-8'}{if !empty($rtg)}&rtg={$rtg|escape:'htmlall':'UTF-8'}{/if}" rel="nofollow"><i class="icon-pencil"></i> {l s='Rate it' mod='gsnippetsreviews'}</a>
					{/if}
				</div>
				<div class="clr_0"></div>
			</div>
		{if !empty($iReviewAverage) && $sReviewType == 'aggregate' && !empty($bDisplayProductRichSnippets) && !empty($bProductBadge)}
		</div>
		{/if}
	</div>
	{literal}
	<script type="text/javascript">
		bt_aFancyReviewForm.selector = 'a#bt_btn-review-form';
		bt_aFancyReviewForm.hideOnContentClick = false;
		bt_aFancyReviewForm.beforeClose = '{/literal}{$sProductLink nofilter}{literal}';
		bt_aFancyReviewForm.click = {/literal}{if !empty($bOpenForm) && (!empty($bUseRatings) || !empty($bUseComments))}true{else}false{/if}{literal};
	</script>
	{/literal}
	{*{literal}*}
	{*<script type="text/javascript">*}
		{*$(document).ready(function(){*}
			{*{/literal}*}{* USE CASE - instantiate review form *}{*{literal}*}
			{*$("a#reviewForm").fancybox({*}
				{*'hideOnContentClick' : false,*}
				{*'beforeClose' : function() {document.location.href = "{/literal}{$sProductLink|escape:'UTF-8'}{literal}"}*}
			{*});*}
			{*{/literal}*}
			{* USE CASE - if detect open form instruction, open it automatically *}
			{*{if !empty($bOpenForm) && (!empty($bUseRatings) || !empty($bUseComments))}*}
			{*$("a#reviewForm").trigger('click');*}
			{*{/if}*}
			{*{literal}*}
		{*});*}
	{*</script>*}
	{*{/literal}*}
	{/if}
	<!-- /GSR - Product review block -->
{* /DISPLAY REVIEWS BLOCK *}