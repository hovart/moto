{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{extends file='page.tpl'}
{debug}
{block name='page_content'}
	{if empty($aErrors)}
		<!-- GSR - Review list page -->
		<div id="{$sGsrModuleName nofilter}" class="bootstrap block all-review">
		<h1 class="h1 title">{l s='All reviews' mod='gsnippetsreviews'}</h1>
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

			{foreach from=$aReviewList name=review key=iKey item=aReview}
			<div class="review-line-list">
				<div class="review-line-name">
					{l s='By' mod='gsnippetsreviews'} <strong>{$aReview.firstname|escape:'htmlall':'UTF-8'}{if !empty($aReview.lastname)} {$aReview.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aReview.address)} ({$aReview.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aReview.dateAdd)}&nbsp;{l s='on' mod='gsnippetsreviews'}&nbsp;{$aReview.dateAdd|escape:'UTF-8'}{/if} :
					<div class="review-line-rating pull-right">
						<div class="pull-left text-size-07">({$aReview.rating.note|escape:'htmlall':'UTF-8'}/{$iMaxRating|intval})&nbsp;</div>
						<div class="pull-right rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.rating.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.rating.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
					</div>
				</div>

				<div class="review-line-comment">
					<span class="clr_10"></span>
					{if !empty($aReview.data)}
						{l s='Product rated' mod='gsnippetsreviews'} : <a href="{$aReview.sProductLink|escape:'htmlall':'UTF-8'}" title="{$aReview.sProductName|escape:'htmlall':'UTF-8'}">{$aReview.sProductName|escape:'htmlall':'UTF-8'} <i class="icon icon-chevron-right"></i></a><br /><br />
						{if !empty($aReview.sProductImage)}
							<div class="row">
								<div class="pull-left margin-right">
									<a href="{$aReview.sProductLink|escape:'htmlall':'UTF-8'}" title="{$aReview.sProductName|escape:'htmlall':'UTF-8'}">
										<img class="img-responsive" src="{$aReview.sProductImage|escape:'htmlall':'UTF-8'}" alt="{$aReview.sProductName|escape:'htmlall':'UTF-8'}" />
									</a>
								</div>
								<div class="center-block">
									<strong>{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}</strong><br /><br />
									<p>{$aReview.data.sComment nofilter}</p>
								</div>
							</div>
						{else}
							<strong>{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}</strong><br /><br />
							<p class="border-left">{$aReview.data.sComment nofilter}</p>
						{/if}

						<div class="clr_20"></div>

						{if !empty($bDisplayButtons)}
							<div class="inline {$sModuleName|escape:'htmlall':'UTF-8'}_social_buttons">
								{*SHARE BUTTONS WITH COUNT BOX*}
								{if !empty($bCountBoxButton)}
									<div class="inline width-auto">
										{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
										{if isset($iFbButton) && $iFbButton == 3}
											<div class="inline zindex">
												<div class="fb-share-button fb-no-valign" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button_count" data-size="small"></div>
												<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											</div>
										{else}
											<div class="inline zindex">
												<div class="fb-like fb-no-valign" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="60" data-layout="button_count" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
												<a class="inline twitter-share-button" href="https://twitter.com/share" data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											</div>
										{/if}
									</div>
									{*SHARE BUTTONS WITHOUT COUNT BOX*}
								{else}
									{* use case - when we display the share button alone, we have to display in the second position to get them inline without any problem *}
									{if isset($iFbButton) && $iFbButton == 3}
										<div class="inline zindex">
											<div class="fb-share-button fb-no-valign" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-layout="button" data-size="small"></div>
											<a class="inline twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
										</div>
									{else}
										<div class="inline zindex">
											<a class="twitter-share-button" href="https://twitter.com/share"  data-url="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" {if isset($sTwitterLang)}data-lang="{$sTwitterLang|escape:'htmlall':'UTF-8'}" {/if}>Tweet</a>
											<div class="fb-like fb-height-24" data-href="{$aReview.sReviewUrl|escape:'htmlall':'UTF-8'}" data-show-faces="false" data-width="220" data-share="{if isset($iFbButton) && $iFbButton == 2}true{else}false{/if}"></div>
										</div>
									{/if}
								{/if}
							</div>
						{/if}

						{if empty($aReview.reportId) && !empty($bDisplayReportAbuse)}
						<span class="pull-right">
							<a class="fancybox.ajax btn-sm btn-primary" id="reportReview{$smarty.foreach.review.iteration|intval}" rel="nofollow" href="{$aReview.sReportUrl|escape:'htmlall':'UTF-8'}" title="{l s='report a review' mod='gsnippetsreviews'}">{l s='Report abuse' mod='gsnippetsreviews'}</a>
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
							{$aReview.rating.replyData.sComment nofilter}&nbsp;<img src="{$sImgUrl|escape:'htmlall':'UTF-8'}quotes-close.png" width="{$smarty.const._GSR_IMG_QUOTE_WIDTH|intval}" height="{$smarty.const._GSR_IMG_QUOTE_HEIGHT|intval}" alt="{l s='review reply' mod='gsnippetsreviews'}" />
						</p>
						<footer>{l s='Shop owner reply' mod='gsnippetsreviews'} {if !empty($aReview.rating.replyDateAdd)}{l s='on' mod='gsnippetsreviews'} {$aReview.rating.replyDateAdd|escape:'UTF-8'}{/if}</footer>
					</blockquote>
				{/if}
			</div>
			<span class="clr_5"></span>
			<span class="clr_hr"></span>
			<span class="clr_10"></span>
			{/foreach}

			<div class="clr_20"></div>

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

							<li><a rel="nofollow" href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$prev|intval}" rel="nofollow" class="previous{if $iCurrentPage > 1}{else} disabled{/if}"><i class="material-icons">&#xE314;</i>&nbsp;{l s='Previous' mod='gsnippetsreviews'}</a></li>
							{section name=pagination start=$nStart loop=$nEnd}
							{if $smarty.section.pagination.index eq $iCurrentPage}
								<li class="current"><a href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$smarty.section.pagination.index|intval}" class="disabled" rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
							{else}
								<li><a {if $smarty.section.pagination.index eq 1}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage=1"{else}href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$smarty.section.pagination.index|intval}"{/if} rel="nofollow">{$smarty.section.pagination.index|intval}</a></li>
							{/if}
							{/section}
							{if $iCurrentPage < $iTotalPage}
								{assign var=next value=$iCurrentPage+1}
							{else}
								{assign var=next value=$iTotalPage}
							{/if}
							<li><a href="{$sPaginationRvwCtrlUrl|escape:'htmlall':'UTF-8'}&iPage={$next|intval}" rel="nofollow" class="next{if $iCurrentPage < $iTotalPage}{else} disabled{/if}">{l s='Next' mod='gsnippetsreviews'}&nbsp;<i class="material-icons">&#xE315;</i></a></li>
						</ul>
					</div>
				</nav>
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
		{else}
		{l s='There is currently no reviews on the shop' mod='gsnippetsreviews'}
		{/if}
		</div>
		<!-- /GSR - Review list page -->
	{/if}
{/block}