{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews)}
	{if !empty($bUseRatings) || !empty($bUseComments) || !empty($aReviews)}
		{* USE CASE - REVIEWS DISPLAY MODE IS WITH PRODUCT TABS THEME *}
		{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
		<div id="idTab{$iIdTab|intval}" {if $sDisplayReviewMode == 'tabs17'}class="tab-pane fade in"{else}class="page-product-box tab-pane"{/if}>
		{/if}
			<a name="anchorReview" id="anchorReview"></a>
			<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="list-review{if !empty($sDisplayReviewMode) && $sDisplayReviewMode == 'tabs17'}-tabs17{/if}">
				{if !empty($sDisplayReviewMode) && $sDisplayReviewMode == 'classic'}
				<span class="title"><i class="fa fa-star"></i>&nbsp;{l s='Reviews' mod='gsnippetsreviews'}</span>
				{/if}
				{if !empty($aErrors)}
					{include file="`$sErrorInclude`"}
				{/if}
				{if !empty($aReviews)}
				{foreach from=$aReviews name=review key=iKey item=aReview}
				<div class="review-line">
					<div class="clr_10"></div>
					<div itemprop="review" itemscope itemtype="http://schema.org/Review">
						<div class="text-muted">
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
									<meta itemprop="datePublished" content="{$aReview.date|escape:'htmlall':'UTF-8'}">{$aReview.dateAdd|escape:'htmlall':'UTF-8'}
								{elseif !empty($aReview.review.dateAdd) && !empty($aReview.review.status)}
									<meta itemprop="datePublished" content="{$aReview.review.date|escape:'htmlall':'UTF-8'}">{$aReview.review.dateAdd|escape:'htmlall':'UTF-8'}
								{/if}
							{/if}
							(<span itemprop="itemReviewed">{$sProductName|escape:'htmlall':'UTF-8'}</span>) :

							<div class="pull-right inline">
								<div class="pull-left" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
									(<span itemprop="ratingValue">{$aReview.note|intval}</span>/<span itemprop="bestRating">{$iMaxRating|intval}</span>)&nbsp;
								</div>
								<div class="pull-right rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
								{if !empty($aReview.replyDisplay) && !empty($aReview.data.iOldRating)}<br /><span>({l s='old rating' mod='gsnippetsreviews'} {$aReview.data.iOldRating|intval}/{$iMaxRating|intval})&nbsp;</span>{/if}
							</div>
						</div>

						<div>
							<span class="clr_0"></span>
							{if !empty($aReview.review.data)}
								<p itemprop="name"><strong>{$aReview.review.data.sTitle|escape:'htmlall':'UTF-8'}</strong></p>
								<p itemprop="description" class="border-left">{$aReview.review.data.sComment nofilter}</p>

								{if !empty($bDisplayButtons)}
								<div class="inline {$sModuleName|escape:'htmlall':'UTF-8'}_social_buttons">
									{*SHARE BUTTONS WITH COUNT BOX*}
									{if !empty($bCountBoxButton)}
									<div class="inline width-auto">
										{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="inline zindex">
												<div class="fb-share-button fb-no-valign" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button_count" data-size="small"></div>
												<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											</div>
										{else}
											<div class="inline zindex">
												<div class="fb-like fb-no-valign" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="60" data-layout="button_count" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
												<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											</div>
										{/if}
									</div>
									{*SHARE BUTTONS WITHOUT COUNT BOX*}
									{else}
										{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="inline zindex">
												<div class="fb-share-button fb-no-valign" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button" data-size="small"></div>
												<a class="inline twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											</div>
										{else}
											<div class="inline zindex">
												<a class="twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
												<div class="fb-like fb-height-24" data-href="{$aReview.review.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="220" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
											</div>
										{/if}
									{/if}
								</div>
								{/if}
								{if empty($aReview.review.reportId) && !empty($aReview.review) && !empty($bDisplayReportAbuse)}
								<span class="pull-right">
									<a class="fancybox.ajax btn-sm btn-primary" id="reportReview{$smarty.foreach.review.iteration|intval}" rel="nofollow" href="{$aReview.review.sReportUrl|escape:'htmlall':'UTF-8'}" title="{l s='report a review' mod='gsnippetsreviews'}">{l s='Report abuse' mod='gsnippetsreviews'}</a>
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
								<p>{$aReview.replyData.sComment nofilter}</p>
								<footer>{l s='Shop owner reply' mod='gsnippetsreviews'} {if !empty($aReview.replyDateAdd)}{l s='on' mod='gsnippetsreviews'} {$aReview.replyDateAdd|escape:'htmlall':'UTF-8'}{/if}</footer>
							</blockquote>
							{/if}
						</div>
					</div>
				</div>
				{/foreach}

				{* Pagination *}
				{if $iTotalPage > 1}
				<nav class="pagination">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<ul class="page-list text-xs-center">
							{if $iCurrentPage > 1}
								{assign var=prev value=$iCurrentPage-1}
							{else}
								{assign var=prev value=1}
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

							{*<li><a rel="nofollow" href="{$sURI|escape:'htmlall':'UTF-8'}" class="previous{if $iCurrentPage > 1}{else} disabled{/if}"><i class="material-icons">&#xE314;</i>&nbsp;{l s='First' mod='gsnippetsreviews'}</a></li>*}
							<li><a rel="nofollow" href="{$sBASE_URI|escape:'htmlall':'UTF-8'}&iPage={$prev|intval}" rel="nofollow" class="previous{if $iCurrentPage > 1}{else} disabled{/if}"><i class="material-icons">&#xE314;</i>&nbsp;{l s='Previous' mod='gsnippetsreviews'}</a></li>
							{section name=pagination start=$nStart loop=$nEnd}
								{if $smarty.section.pagination.index eq $iCurrentPage}
									<li class="current"><a href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$smarty.section.pagination.index|intval}" class="disabled" rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
								{else}
									<li><a {if $smarty.section.pagination.index eq 1}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage=1"{else}href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$smarty.section.pagination.index|intval}"{/if} rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
								{/if}
							{/section}
							{if $iCurrentPage < $iTotalPage}
								{assign var=next value=$iCurrentPage+1}
							{else}
								{assign var=next value=$iTotalPage}
							{/if}
							<li><a href="{$sBASE_URI|escape:'htmlall':'UTF-8'}iPage={$next|intval}" rel="nofollow" class="next{if $iCurrentPage < $iTotalPage}{else} disabled{/if}">{l s='Next' mod='gsnippetsreviews'}&nbsp;<i class="material-icons">&#xE315;</i></a></li>
						</ul>
					</div>
				</nav>
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
			{else}
				<div class="clr_10"></div>
				<p>
					<a class="fancybox.ajax" id="reviewTabForm" href="{$sMODULE_URI|escape:'htmlall':'UTF-8'}?sAction={$aQueryParams.reviewForm.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reviewForm.type|escape:'htmlall':'UTF-8'}&iPId={$iProductId|intval}&iCId={$iCustomerId|intval}&sURI={$sURI|urlencode}&btKey={$sSecureReviewKey|escape:'htmlall':'UTF-8'}{if !empty($rtg)}&rtg={$rtg|escape:'htmlall':'UTF-8'}{/if}" rel="nofollow">{l s='Be the first to write your review' mod='gsnippetsreviews'} !</a>
				</p>
				{literal}
				<script type="text/javascript">
					bt_aFancyReviewTabForm.selector = 'a#reviewTabForm';
					bt_aFancyReviewTabForm.hideOnContentClick = false;
					bt_aFancyReviewTabForm.beforeClose = '{/literal}{$sProductLink nofilter}{literal}';
				</script>
				{/literal}
			{/if}
			</div>
		{* USE CASE - REVIEWS DISPLAY MODE IS WITH PRODUCT TABS THEME *}
		{if !empty($sDisplayReviewMode) && $sDisplayReviewMode != 'classic'}
		</div>
		{/if}
	<!-- /GSR - Product Review Tab content -->
	{/if}
{/if}