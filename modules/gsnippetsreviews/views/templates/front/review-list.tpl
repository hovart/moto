{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if empty($aErrors)}
	<!-- GSR - Review list page -->
	<div id="{$sGsrModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap block">
		<h1 class="page-subheading">{l s='All reviews' mod='gsnippetsreviews'}</h1>
		<div class="clr_10"></div>
		{if !empty($bDisplayReviewList) && !empty($aReviewList)}
			{* USE CASE - add social network js *}
			{if !empty($bUseSocialNetworkJs)}
				{* JS - FACEBOOK & twitter *}
				{literal}
				<script type="text/javascript">
					bt_oUseSocialButton.sFbLang = '{/literal}{$sFbLang|escape:'htmlall':'UTF-8'}{literal}';
				</script>
				{/literal}
				{* /JS - FACEBOOK & twitter *}
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

			{foreach from=$aReviewList name=review key=iKey item=aReview}
			<div class="review-line-list">
				<div class="review-line-name">
					{l s='By' mod='gsnippetsreviews'} <strong>{$aReview.firstname|escape:'htmlall':'UTF-8'}{if !empty($aReview.lastname)} {$aReview.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aReview.address)} ({$aReview.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aReview.dateAdd)}&nbsp;{l s='on' mod='gsnippetsreviews'}&nbsp;{$aReview.dateAdd|escape:'UTF-8'}{/if} :
					<div class="review-line-rating">
						<div class="left text-size-07">({$aReview.rating.note|escape:'htmlall':'UTF-8'}/{$iMaxRating|intval})&nbsp;</div>
						<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.rating.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.rating.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
					</div>
				</div>

				<div class="review-line-comment">
					<span class="clr_10"></span>
					{if !empty($aReview.data)}
						{l s='Product rated' mod='gsnippetsreviews'} : <a href="{$aReview.sProductLink|escape:'htmlall':'UTF-8'}" title="{$aReview.sProductName|escape:'htmlall':'UTF-8'}">{$aReview.sProductName|escape:'htmlall':'UTF-8'} <i class="icon icon-chevron-right"></i></a><br /><br />
						{if !empty($aReview.sProductImage)}
						<div class="row">
							<div class="left-block margin-right">
								<a href="{$aReview.sProductLink|escape:'htmlall':'UTF-8'}" title="{$aReview.sProductName|escape:'htmlall':'UTF-8'}">
									<img src="{$aReview.sProductImage|escape:'htmlall':'UTF-8'}" alt="{$aReview.sProductName|escape:'htmlall':'UTF-8'}" />
								</a>
							</div>
							<div class="center-block">
								<strong>{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}</strong><br /><br />
								<p>{$aReview.data.sComment|escape:'UTF-8'}</p>
							</div>
						</div>
						{else}
						<strong>{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}</strong><br /><br />
						<p class="border-left">{$aReview.data.sComment|escape:'UTF-8'}</p>
						{/if}

						<div class="clr_20"></div>

						{if !empty($bDisplayButtons)}
							<div class="inline">
								{* SHARE BUTTONS WITH COUNT BOX *}
								{if !empty($bCountBoxButton)}
									<div class="inline zindex">
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="fb-share-button" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button_count" data-size="small"></div>
										{else}
											<div class="fb-like valign-top" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="60" data-layout="button_count" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
										{/if}
									</div>
									<a class="valign-top inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-count="horizontal" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
									{* SHARE BUTTONS WITHOUT COUNT BOX *}
								{else}
									<a class="valign-top padding0202 twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-count="none" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
									<div class="absolute inline">
										<div class="padding0202 zindex">
											{if isset($iFbButton) && $iFbButton == 3}
												<div class="fb-share-button" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button" data-size="small"></div>
											{else}
												<div class="fb-like valign-top" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="220" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
											{/if}
										</div>
									</div>
								{/if}
							</div>
						{/if}
						{if empty($aReview.reportId) && !empty($bDisplayReportAbuse)}
						<span class="review-report">
							<a class="fancybox.ajax" id="reportReview{$smarty.foreach.review.iteration|intval}" rel="nofollow" href="{$aReview.sReportUrl|escape:'htmlall':'UTF-8'}" title="{l s='report a review' mod='gsnippetsreviews'}"><i  class="icon-warning-sign text-primary"></i>&nbsp;{l s='Report abuse' mod='gsnippetsreviews'}</a>
						</span>
						<div class="clr_10"></div>
						{/if}
					{else}
						{l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnippetsreviews'}
					{/if}
				</div>
				{if !empty($aReview.rating.replyDisplay) && !empty($aReview.rating.replyData.sComment)}
				<blockquote class="blockquote-reverse">
					<p>
						<img src="{$sImgUrl|escape:'htmlall':'UTF-8'}quotes-open.png" width="{$smarty.const._GSR_IMG_QUOTE_WIDTH|intval}" height="{$smarty.const._GSR_IMG_QUOTE_HEIGHT|intval}" alt="{l s='review reply' mod='gsnippetsreviews'}" />&nbsp;
						{$aReview.rating.replyData.sComment|strip|escape:'UTF-8'}&nbsp;<img src="{$sImgUrl|escape:'htmlall':'UTF-8'}quotes-close.png" width="{$smarty.const._GSR_IMG_QUOTE_WIDTH|intval}" height="{$smarty.const._GSR_IMG_QUOTE_HEIGHT|intval}" alt="{l s='review reply' mod='gsnippetsreviews'}" />
					</p>
					<footer>{l s='Shop owner reply' mod='gsnippetsreviews'} {if !empty($aReview.rating.replyDateAdd)}{l s='on' mod='gsnippetsreviews'} {$aReview.rating.replyDateAdd|escape:'UTF-8'}{/if}</footer>
				</blockquote>
				{/if}
			</div>
			{/foreach}

			<div class="clr_20"></div>

			{* Pagination *}
			{if $iTotalPage > 1}
				<div class="pagination">
					<ul class="pagination">
						{if $iCurrentPage > 1}
							{assign var=prev value=$iCurrentPage-1}
						{/if}
						{if $iTotalPage > 10}
							{if $iCurrentPage > 5}
								{if $iCurrentPage <= $iTotalPage-5}
									{assign var=nStart value=$iCurrentPage-4}
									{assign var=nEnd value=$iCurrentPage+5}
								{else}
									{assign var=nStart value=$iTotalPage-9}
									{assign var=nEnd value=$iTotalPage+1}
								{/if}
							{else}
								{assign var=nStart value=1}
								{assign var=nEnd value=11}
							{/if}
						{else}
							{assign var=nStart value=1}
							{assign var=nEnd value=$iTotalPage+1}
						{/if}

						{if $iCurrentPage > 1}
							<li id="previous"><a {if $prev eq 1}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage=1"{else}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$prev|intval}"{/if} rel="nofollow">&laquo;&nbsp;{l s='Previous' mod='gsnippetsreviews'}</a></li>
						{/if}
						{if $iCurrentPage > 10}
							<li class="disabled"><a href="javascript:void(0);">…</a></li>
						{/if}
						{section name=pagination start=$nStart loop=$nEnd}
							{if $smarty.section.pagination.index eq $iCurrentPage}
								<li class="active"><span>{$smarty.section.pagination.index|intval}</span></li>
							{else}
								<li><a {if $smarty.section.pagination.index eq 1}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage=1"{else}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$smarty.section.pagination.index|intval}"{/if} rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
							{/if}
						{/section}
						{if $iTotalPage > 10 && $iCurrentPage < $iTotalPage}
							<li class="disabled"><a href="javascript:void(0);">…</a></li>
							{*&nbsp;{l s='On' mod='gsnippetsreviews'} {$iTotalPage|intval}*}
						{/if}
						{if $iCurrentPage < $iTotalPage}
							{assign var=next value=$iCurrentPage+1}
						{/if}
						{if $iCurrentPage < $iTotalPage}
							<li id="next"><a href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$next|intval}" rel="nofollow">{l s='Next' mod='gsnippetsreviews'}&nbsp;&raquo;</a></li>
						{/if}
					</ul>
				</div>
			{/if}
			{* /Pagination *}

			{literal}
			<script type="text/javascript">
				{/literal}
				{* USE CASE - instantiate jquery report fancybox for every review of the page *}
				{foreach from=$aReviewList name=review key=iKey item=aReview}
				{if empty($aReview.review.reportId)}
				{literal}
				bt_aReviewReport.push({'selector' : "a#reportReview{/literal}{$smarty.foreach.review.iteration|intval}{literal}", 'hideOnContentClick' : false, 'afterClose' : "{/literal}{$sCurrentRvwCtrlUrl nofilter}{literal}", 'minWidth' : 500});
				{/literal}
				{/if}
				{/foreach}

				{* USE CASE - if FB used *}
				{if !empty($bUseSocialNetworkJs) && !empty($aJSCallback)}
				{* Loop on reviews to push every review on authorized URLS *}
				{foreach from=$aJSCallback name=cbk key=iKey item=aCallback}
				{literal}
				bt_aFacebookCallback.push({'url' : '{/literal}{$aCallback.url nofilter}{literal}', 'function' : '{/literal}{$aCallback.function|escape:'UTF-8'}{literal}'});
				{/literal}
				{/foreach}
				{literal}

				function bt_generateFbVoucherCode(response) {
					{/literal}
					oGsr.ajax('{$sMODULE_URI|escape:'UTF-8'}', 'sAction={$aQueryParams.popinFB.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.popinFB.type|escape:'htmlall':'UTF-8'}&sReviewUrl=' + encodeURIComponent(response), null, null, true, false, false);
					{literal}
				}
				{/literal}
				{/if}
				{literal}
			</script>
			{/literal}
			{*{literal}*}
			{*<script type="text/javascript">*}
				{*// detect if array of valid URLs is defined*}
				{*if (typeof(bt_aFacebookCallback) == 'undefined') {*}
					{*var bt_aFacebookCallback = new Array();*}
				{*}*}
				{*$(document).ready(function(){*}
					{*{/literal}*}
					{* USE CASE - instantiate jquery star plugin for every review of the page *}
					{*{foreach from=$aReviewList name=review key=iKey item=aReview}*}
					{*{if empty($aReview.reportId)}*}
					{*{literal}*}
					{*$("a#reportReview{/literal}{$smarty.foreach.review.iteration|intval}{literal}").fancybox({*}
						{*'hideOnContentClick' : false,*}
						{*'afterClose' : function() {document.location.href = "{/literal}{$sCurrentRvwCtrlUrl|escape:'UTF-8'}{literal}"},*}
						{*'minWidth' : 500*}
					{*});*}
					{*{/literal}*}
					{*{/if}*}
					{*{/foreach}*}

					{* USE CASE - if FB used *}
					{*{if !empty($bUseSocialNetworkJs) && !empty($aJSCallback)}*}
					{* Loop on reviews to push every review on authorized URLS *}
					{*{foreach from=$aJSCallback name=cbk key=iKey item=aCallback}*}
					{*{literal}*}
					{*bt_aFacebookCallback.push({'url' : '{/literal}{$aCallback.url|escape:'UTF-8'}{literal}', 'function' : '{/literal}{$aCallback.function|escape:'UTF-8'}{literal}'});*}
					{*{/literal}*}
					{*{/foreach}*}
					{*{literal}*}

					{*function bt_generateFbVoucherCode(response) {*}
						{*{/literal}*}
						{*oGsr.ajax('{$sMODULE_URI|escape:'UTF-8'}', 'sAction={$aQueryParams.popinFB.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.popinFB.type|escape:'htmlall':'UTF-8'}&sReviewUrl=' + encodeURIComponent(response), null, null, true, false, false);*}
						{*{literal}*}
					{*}*}

					{*window.fbAsyncInit = function() {*}
						{*FB.Event.subscribe('edge.create', function(response){*}
							{*for (var i = 0; i < bt_aFacebookCallback.length; ++i) {*}
								{*if (typeof(bt_aFacebookCallback[i].url) !== 'undefined') {*}
									{*if (response == bt_aFacebookCallback[i].url) {*}
										{*// display fancy box voucher box*}
										{*eval(bt_aFacebookCallback[i].function + '("' + response + '")');*}
									{*}*}
								{*}*}
							{*}*}
						{*}, true);*}
					{*}*}
					{*{/literal}*}
					{*{/if}*}
					{*{literal}*}
				{*});*}
			{*</script>*}
			{*{/literal}*}
		{else}
			{l s='There is currently no reviews on the shop' mod='gsnippetsreviews'}
		{/if}
	</div>
	<!-- /GSR - Review list page -->
{else}
	{include file="`$sErrorInclude`"}
{/if}