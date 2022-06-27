{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews)}
	{if !empty($bUseRatings) || !empty($bUseComments) || !empty($aReviews)}
		<!-- GSR - Product Review Tab content -->
		{* USE CASE - REVIEWS DISPLAY MODE IS WITH PRODUCT TABS THEME *}
		{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
		<div id="idTab{$iIdTab|intval}" {if $sDisplayReviewMode == 'bootstrap'}class="page-product-box tab-pane"{/if}>
		{* USE CASE - REVIEWS DISPLAY MODE IS FLAT DESIGN THEME *}
		{else}
		<section class="page-product-box">
			<h3 class="page-product-heading"><i class="icon-star-empty"></i> {l s='Reviews' mod='gsnippetsreviews'}</h3>
		{/if}
		<a name="anchorReview" id="anchorReview"></a>
		<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="rte">
		{if !empty($aErrors)}
			{include file="`$sErrorInclude`"}
		{/if}
		{if !empty($aReviews)}
			{foreach from=$aReviews name=review key=iKey item=aReview}
				<div class="review-line">
					<div itemprop="review" itemscope itemtype="http://schema.org/Review">
						<div class="review-line-name text-muted">
							{l s='By' mod='gsnippetsreviews'}
							<strong>
								<span itemprop="author">
								{$aReview.firstname|escape:'htmlall':'UTF-8'}
								{if !empty($aReview.lastname)}
									{$aReview.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.
								{/if}
								</span>
							</strong>

							{if !empty($aReview.address)}
								({$aReview.address|escape:'htmlall':'UTF-8'})
							{/if}
							{if !empty($aReview.review.dateAdd) || !empty($aReview.dateAdd)}
								{l s='on' mod='gsnippetsreviews'}&nbsp;
								{if !empty($aReview.dateAdd)}
									<meta itemprop="datePublished" content="{$aReview.date|escape:'htmlall':'UTF-8'}">{$aReview.dateAdd|escape:'UTF-8'}
								{elseif !empty($aReview.review.dateAdd) && !empty($aReview.review.status)}
									<meta itemprop="datePublished" content="{$aReview.review.date|escape:'htmlall':'UTF-8'}">{$aReview.review.dateAdd|escape:'UTF-8'}
								{/if}
							{/if}
							<span class="text-size-07">(<span itemprop="itemReviewed">{$sProductName|escape:'htmlall':'UTF-8'}</span>)</span> :

							<div class="review-line-rating">
								<div class="left text-size-07" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
									(<span itemprop="ratingValue">{$aReview.note|intval}</span>/<span itemprop="bestRating">{$iMaxRating|intval}</span>)&nbsp;
								</div>
								<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
								{if !empty($aReview.replyDisplay) && !empty($aReview.data.iOldRating)}<br /><span class="rvw-additional-txt">({l s='old rating' mod='gsnippetsreviews'} {$aReview.data.iOldRating|intval}/{$iMaxRating|intval})&nbsp;</span>{/if}
							</div>
						</div>

						<div class="review-line-comment">
							<span class="clr_0"></span>
							{if !empty($aReview.review.data)}
								<p itemprop="name"><strong>{$aReview.review.data.sTitle|escape:'htmlall':'UTF-8'}</strong></p>
								<p itemprop="description">{$aReview.review.data.sComment|escape:'UTF-8'}</p>

								{if !empty($bDisplayButtons)}
								<div class="inline {$sModuleName|escape:'htmlall':'UTF-8'}_social_buttons">
									{*SHARE BUTTONS WITH COUNT BOX*}
									{if !empty($bCountBoxButton)}
									<div class="inline width-auto">
										{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="inline zindex">
												<div class="fb-share-button" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button_count" data-size="small"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
										{else}
											<div class="inline zindex">
												<div class="fb-like valign-top" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="60" data-layout="button_count" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
										{/if}
									</div>
									{*SHARE BUTTONS WITHOUT COUNT BOX*}
									{else}
										{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="inline zindex">
												<div class="fb-share-button" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button" data-size="small"></div>
											</div>
											<a class="inline twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
										{else}
											<a class="valign-top padding0202 twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											<div class="absolute inline">
												<div class="padding0202 zindex">
													<div class="fb-like valign-top" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="220" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
												</div>
											</div>
										{/if}
									{/if}
								</div>
								{/if}
								{if empty($aReview.review.reportId) && !empty($aReview.review) && !empty($bDisplayReportAbuse)}
								<span class="review-report {$sModuleName|escape:'htmlall':'UTF-8'}_report_button">
									<a class="fancybox.ajax" id="reportReview{$smarty.foreach.review.iteration|intval}" rel="nofollow" href="{$aReview.review.sReportUrl|escape:'htmlall':'UTF-8'}" title="{l s='report a review' mod='gsnippetsreviews'}"><i class="icon-warning-sign text-primary"></i>&nbsp;{l s='Report abuse' mod='gsnippetsreviews'}</a>
								</span>
								{/if}
								<div class="clr_5"></div>
							{else}
							{l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnippetsreviews'}
							<div class="clr_15"></div>
							{/if}
							{if !empty($aReview.review.data) && !empty($aReview.replyDisplay) && !empty($aReview.replyData.sComment)}
							<div class="clr_10"></div>
							<blockquote class="blockquote">
								<p>{$aReview.replyData.sComment|escape:'UTF-8'}</p>
								<footer>{l s='Shop owner reply' mod='gsnippetsreviews'} {if !empty($aReview.replyDateAdd)}{l s='on' mod='gsnippetsreviews'} {$aReview.replyDateAdd|escape:'htmlall':'UTF-8'}{/if}</footer>
							</blockquote>
							{/if}
						</div>
					</div>
				</div>
			{/foreach}

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
					<li id="previous"><a {if $prev eq 1}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage=1"{else}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$prev|intval}"{/if} rel="nofollow">&laquo;&nbsp;{l s='Previous' mod='gsnippetsreviews'}</a></li>
				{/if}
				{if $iCurrentPage > 10}
					<li class="disabled"><a href="javascript:void(0);">…</a></li>
				{/if}
				{section name=pagination start=$nStart loop=$nEnd}
					{if $smarty.section.pagination.index eq $iCurrentPage}
						<li class="active"><span>{$smarty.section.pagination.index|intval}</span></li>
					{else}
						<li><a {if $smarty.section.pagination.index eq 1}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage=1"{else}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$smarty.section.pagination.index|intval}"{/if} rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
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
					<li id="next"><a href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$next|intval}" rel="nofollow">{l s='Next' mod='gsnippetsreviews'}&nbsp;&raquo;</a></li>
				{/if}
				</ul>
			</div>
			{/if}
			{* /Pagination *}

			{literal}
			<script type="text/javascript">
				// declare the FB callback to execute after clicking on the like button
				function bt_generateFbVoucherCode(response) {
					{/literal}
					oGsr.ajax('{$sMODULE_URI|escape:'UTF-8'}', 'sAction={$aQueryParams.popinFB.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.popinFB.type|escape:'htmlall':'UTF-8'}&sReviewUrl=' + encodeURIComponent(response), null, null, true, false, false);
					{literal}
				}

				{/literal}
				{* USE CASE - instantiate jquery report fancybox for every review of the page *}
				{foreach from=$aReviews name=review key=iKey item=aReview}
				{if empty($aReview.review.reportId)}
				{literal}
					bt_aReviewReport.push({'selector' : "a#reportReview{/literal}{$smarty.foreach.review.iteration|intval}{literal}", 'hideOnContentClick' : false, 'afterClose' : "{/literal}{$sProductLink nofilter}{literal}", 'minWidth' : 500});
				{/literal}
				{/if}
				{/foreach}

				{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
				{literal}
					bt_oActivateReviewTab.run = true;
					bt_oActivateReviewTab.theme = '{/literal}{$sDisplayReviewMode|escape:'htmlall':'UTF-8'}{literal}';
					bt_oActivateReviewTab.idTab = '{/literal}{$iIdTab|intval}{literal}';
					bt_oActivateReviewTab.liSelector = '{/literal}{if $sDisplayReviewMode == 'tabs17'}.tabs{else}#more_info_tabs{/if}{literal}';
					bt_oActivateReviewTab.cntSelector = '{/literal}{if $sDisplayReviewMode == 'tabs17'}#tab-content{else}#more_info_sheets{/if}{literal}';
				{/literal}
				{/if}

				{* USE CASE - scroll to review anchor when a review has been posted or the Fancy box review form has been executed *}
				{if !empty($bAddReview) || !empty($bGetReviewPage)}
					bt_oScrollTo.execute = true;
					bt_oScrollTo.id = '#anchorReview';
					bt_oScrollTo.duration = 500;
				{elseif !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
					bt_oDeactivateReviewTab.run = true;
					bt_oDeactivateReviewTab.duration = 3000;
					bt_oDeactivateReviewTab.theme = '{$sDisplayReviewMode|escape:'htmlall':'UTF-8'}';
					bt_oDeactivateReviewTab.idTab = '{if $sDisplayReviewMode == 'tabs17'}description{else}idTab1{/if}';
					bt_oDeactivateReviewTab.liSelector = '{if $sDisplayReviewMode == 'tabs17'}.tabs{else}#more_info_tabs{/if}';
					bt_oDeactivateReviewTab.cntSelector = '{if $sDisplayReviewMode == 'tabs17'}#tab-content{else}#more_info_sheets{/if}';
				{/if}

				{* USE CASE - if FB used *}
				{if !empty($bUseSocialNetworkJs) && !empty($aJSCallback)}
				{* Loop on reviews to push every review on authorized URLS *}
				{foreach from=$aJSCallback name=cbk key=iKey item=aCallback}
				{literal}
					bt_aFacebookCallback.push({'url' : '{/literal}{$aCallback.url nofilter}{literal}', 'function' : '{/literal}{$aCallback.function|escape:'UTF-8'}{literal}'});
				{/literal}
				{/foreach}
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

			{*// declare the FB callback to execute after clicking on the like button*}
			{*function bt_generateFbVoucherCode(response) {*}
				{*{/literal}*}
				{*oGsr.ajax('{$sMODULE_URI|escape:'UTF-8'}', 'sAction={$aQueryParams.popinFB.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.popinFB.type|escape:'htmlall':'UTF-8'}&sReviewUrl=' + encodeURIComponent(response), null, null, true, false, false);*}
				{*{literal}*}
			{*}*}

			{*$(document).ready(function(){*}
				{*{/literal}*}
				{* USE CASE - instantiate jquery star plugin for every review of the page *}
				{*{foreach from=$aReviews name=review key=iKey item=aReview}*}
					{*{if empty($aReview.review.reportId)}*}
					{*{literal}*}
					{*$("a#reportReview{/literal}{$smarty.foreach.review.iteration|intval}{literal}").fancybox({*}
						{*'hideOnContentClick' : false,*}
						{*'afterClose' : function() {document.location.href = "{/literal}{$sProductLink|escape:'UTF-8'}{literal}"},*}
						{*'minWidth' : 500*}
					{*});*}
					{*{/literal}*}
					{*{/if}*}
				{*{/foreach}*}

				{*{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}*}
				{*{literal}*}
				{*if ($('#more_info_tabs').length != 0) {*}
					{*$("#more_info_tabs li").each(function(i, elt)*}
					{*{*}
						{*if ($(this).find('a[href=#idTab{/literal}{$iIdTab|intval}{literal}]').length != 0 ) {*}
							{*{/literal}*}
							{*{if $sDisplayReviewMode == 'bootstrap'}*}
							{*$(this).addClass('active');*}
							{*{else}*}
							{*$('a', this).addClass('selected');*}
							{*{/if}*}
							{*{literal}*}
						{*}*}
						{*else {*}
							{*{/literal}*}
							{*{if $sDisplayReviewMode == 'bootstrap'}*}
							{*$(this).removeClass('in active');*}
							{*{else}*}
							{*$('a', this).addClass('selected');*}
							{*{/if}*}
							{*{literal}*}
						{*}*}
					{*});*}
				{*}*}
				{*if ($('#more_info_sheets').length != 0) {*}
					{*$("#more_info_sheets").children().each(function(i, elt)*}
					{*{*}
						{*if ($(this).attr('id') == 'idTab{/literal}{$iIdTab|intval}{literal}') {*}
							{*{/literal}{if $sDisplayReviewMode == 'bootstrap'}$(this).addClass('in active');{/if}{literal}*}
							{*$(this).removeClass('block_hidden_only_for_screen');*}
						{*}*}
						{*else if ($(this).attr('id') != '') {*}
							{*{/literal}{if $sDisplayReviewMode == 'bootstrap'}$(this).removeClass('in active');{/if}{literal}*}
							{*$(this).addClass('block_hidden_only_for_screen');*}
						{*}*}
					{*});*}
				{*}*}
				{*{/literal}*}
				{*{/if}*}

				{* USE CASE - scroll to review anchor when a review has been posted or the Fancy box review form has been executed *}
				{*{if !empty($bAddReview) || !empty($bGetReviewPage)}*}
					{*$.scrollTo('#anchorReview', 1200 );*}
				{*{elseif !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}*}
					{*{literal}*}
					{*function deactivateReviewTab() {*}
						{*if ($('#more_info_tabs').length != 0) {*}
							{*$("#more_info_tabs li").each(function(i, elt)*}
							{*{*}
								{*if ($(this).find('a[href=#idTab1]').length != 0 ) {*}
									{*{/literal}*}
									{*{if $sDisplayReviewMode == 'bootstrap'}*}
									{*$(this).addClass('in active');*}
									{*{else}*}
									{*$('a', this).addClass('selected');*}
									{*{/if}*}
									{*{literal}*}
								{*}*}
								{*else {*}
									{*{/literal}*}
									{*{if $sDisplayReviewMode == 'bootstrap'}*}
									{*$(this).removeClass('in active');*}
									{*{else}*}
									{*$('a', this).removeClass('selected');*}
									{*{/if}*}
									{*{literal}*}
								{*}*}
							{*});*}
						{*}*}
						{*if ($('#more_info_sheets').length != 0) {*}
							{*$("#more_info_sheets").children().each(function(i, elt)*}
							{*{*}
								{*if ($(this).attr('id') == 'idTab1') {*}
									{*{/literal}{if $sDisplayReviewMode == 'bootstrap'}$(this).addClass('in active');{/if}{literal}*}
									{*$(this).removeClass('block_hidden_only_for_screen');*}
								{*}*}
								{*else if ($(this).attr('id') != '') {*}
									{*{/literal}{if $sDisplayReviewMode == 'bootstrap'}$(this).removeClass('in active');{/if}{literal}*}
									{*$(this).addClass('block_hidden_only_for_screen');*}
								{*}*}
							{*});*}
						{*}*}
					{*}*}
					{*setTimeout(function() {deactivateReviewTab()}, 3000);*}
					{*{/literal}*}
				{*{/if}*}

				{* USE CASE - if FB used *}
				{*{if !empty($bUseSocialNetworkJs) && !empty($aJSCallback)}*}
					{* Loop on reviews to push every review on authorized URLS *}
					{*{foreach from=$aJSCallback name=cbk key=iKey item=aCallback}*}
					{*{literal}*}
					{*bt_aFacebookCallback.push({'url' : '{/literal}{$aCallback.url|escape:'UTF-8'}{literal}', 'function' : '{/literal}{$aCallback.function|escape:'UTF-8'}{literal}'});*}
					{*{/literal}*}
					{*{/foreach}*}
					{*{literal}*}

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
			<p class="align_center">
				<a class="fancybox.ajax" id="reviewTabForm" href="{$sMODULE_URI|escape:'htmlall':'UTF-8'}?sAction={$aQueryParams.reviewForm.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reviewForm.type|escape:'htmlall':'UTF-8'}&iPId={$iProductId|intval}&iCId={$iCustomerId|intval}&sURI={$sURI|urlencode}&btKey={$sSecureReviewKey|escape:'htmlall':'UTF-8'}{if !empty($rtg)}&rtg={$rtg|escape:'htmlall':'UTF-8'}{/if}" rel="nofollow">{l s='Be the first to write your review' mod='gsnippetsreviews'} !</a>
			</p>
			{literal}
			<script type="text/javascript">
				bt_aFancyReviewTabForm.selector = 'a#reviewTabForm';
				bt_aFancyReviewTabForm.hideOnContentClick = false;
				bt_aFancyReviewTabForm.beforeClose = '{/literal}{$sProductLink nofilter}{literal}';
			</script>
			{/literal}
			{*{literal}*}
			{*<script type="text/javascript">*}
				{*$(document).ready(function(){*}
					{*{/literal}*}{* USE CASE - instantiate review form *}{*{literal}*}
					{*$("a#reviewTabForm").fancybox({*}
						{*'hideOnContentClick' : false,*}
						{*'beforeClose' : function() {document.location.href = "{/literal}{$sProductLink|escape:'UTF-8'}{literal}"}*}
					{*});*}
				{*});*}
			{*</script>*}
			{*{/literal}*}
		{/if}
		</div>
	{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
	</div>
	{else}
	</section>
	{/if}
	<!-- /GSR - Product Review Tab content -->
	{/if}
{/if}