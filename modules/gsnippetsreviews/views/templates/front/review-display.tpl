{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Individual Review Display -->
{if empty($aErrors)}
<div id="{$sGsrModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="comment-form" class="review block">
		<h1 class="page-subheading">{l s='Review for' mod='gsnippetsreviews'} "{$aProduct.name|escape:'htmlall':'UTF-8'}"</h1>
		<div class="product clearfix">
			<p class="description">
				<a href="{$aProduct.link|escape:'htmlall':'UTF-8'}" alt="{l s='See the product' mod='gsnippetsreviews'}">
					<img class="left" src="{$aProduct.img|escape:'htmlall':'UTF-8'}" alt="{$aProduct.name|escape:'htmlall':'UTF-8'}" />
					<span>
						<strong>{$aProduct.name|escape:'htmlall':'UTF-8'}</strong><br /><br />
						{$aProduct.desc|escape:'htmlall':'UTF-8'}
					</span>
				</a>
			</p>
		</div>

		<div class="clr_10"></div>

		<h3 class="page-subheading">{l s='Review' mod='gsnippetsreviews'}</h3>

		{l s='By' mod='gsnippetsreviews'} <strong>{$aRating.firstname|escape:'htmlall':'UTF-8'}{if !empty($aRating.lastname)} {$aRating.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aRating.address)} ({$aRating.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aRating.review.dateAdd) || !empty($aRating.dateAdd)} {l s='on' mod='gsnippetsreviews'}&nbsp;{if !empty($aRating.review.dateAdd) && !empty($aRating.review.status)}{$aRating.review.dateAdd|escape:'UTF-8'}{else}{$aRating.dateAdd|escape:'UTF-8'}{/if}{/if} : <span class="stars-right rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if !empty($aRating.note) && $aRating.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if !empty($aRating.note) && $aRating.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</span>
		{if !empty($aRating.replyDisplay) && !empty($aRating.data.iOldRating)}<div class="clr_0"></div><span class="pull-right rvw-additional-txt">({l s='old rating' mod='gsnippetsreviews'} {$aRating.data.iOldRating|intval}/{$iMaxRating|intval})&nbsp;</span>{/if}

		<div class="clr_10"></div>

		{if !empty($aRating.review) && !empty($aRating.review.status)}
		<div class="form-group">
			<label for="inputTitle">{l s='Title' mod='gsnippetsreviews'} : </label>
			<input class="form-control review-title" id="disabledInput" type="text" placeholder="{$aRating.review.data.sTitle|escape:'htmlall':'UTF-8'}" disabled>
			<div class="clr_20"></div>
			<label for="inputComment">{l s='Comment' mod='gsnippetsreviews'} : </label>
			<textarea class="form-control review-comment" id="disabledTextArea" disabled>{$aRating.review.data.sComment|escape:'htmlall':'UTF-8'}</textarea>
		</div>
		{else}
		<div class="alert alert-info form-info">
			{l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnippetsreviews'}
		</div>
		{/if}

		{if !empty($aRating.replyDisplay) && !empty($aRating.replyData.sComment)}
		<blockquote class="blockquote-reverse">
			<p>
				<img src="{$sImgUrl|escape:'htmlall':'UTF-8'}quotes-open.png" width="{$smarty.const._GSR_IMG_QUOTE_WIDTH|intval}" height="{$smarty.const._GSR_IMG_QUOTE_HEIGHT|intval}" alt="{l s='review reply' mod='gsnippetsreviews'}" />&nbsp;
				{$aRating.replyData.sComment|strip|escape:'UTF-8'}&nbsp;<img src="{$sImgUrl|escape:'htmlall':'UTF-8'}quotes-close.png" width="{$smarty.const._GSR_IMG_QUOTE_WIDTH|intval}" height="{$smarty.const._GSR_IMG_QUOTE_HEIGHT|intval}" alt="{l s='review reply' mod='gsnippetsreviews'}" />
			</p>
			<footer>{l s='Shop owner reply' mod='gsnippetsreviews'} {if !empty($aRating.replyDateAdd)}{l s='on' mod='gsnippetsreviews'} {$aRating.replyDateAdd|escape:'UTF-8'}{/if}</footer>
		</blockquote>
		{/if}

		{if empty($aRating.review.reportId) && !empty($aRating.review.status) && !empty($sReportUrl) && !empty($bDisplayReportAbuse)}
			<div class="clr_10"></div>

			<span class="review-report">
				<a rel="nofollow" class="fancybox.ajax" id="reportReview1" href="{$sReportUrl|escape:'htmlall':'UTF-8'}" title="{l s='report a review' mod='gsnippetsreviews'}">
					<i class="icon-warning-sign text-primary" title="{l s='warning' mod='gsnippetsreviews'}"></i>
					<strong>{l s='Report abuse' mod='gsnippetsreviews'}</strong>
				</a>
			</span>
		{/if}

		{if empty($aReview.reportId)}
		{literal}
		<script type="text/javascript">
			bt_aReviewReport.push({'selector' : "a#reportReview1", 'hideOnContentClick' : false, 'afterClose' : "{/literal}{$sURI nofilter}{literal}", 'minWidth' : 600});
		</script>
		{/literal}
		{/if}

		{*{literal}*}
		{*<script type="text/javascript">*}
			{*$(document).ready(function(){*}
				{*{/literal}*}
				{*{if empty($aReview.reportId)}*}
				{*{literal}*}
				{*$("a#reportReview").fancybox({*}
					{*'hideOnContentClick' : false,*}
					{*'afterClose' : function() {document.location.href = "{/literal}{$sURI|escape:'UTF-8'}{literal}"},*}
					{*'minWidth' : 600*}
				{*});*}
				{*{/literal}*}
				{*{/if}*}
				{*{literal}*}
			{*});*}
		{*</script>*}
		{*{/literal}*}
	</div>
</div>
{else}
{include file="`$sErrorInclude`"}
{/if}
<!-- /GSR - Individual Review Display -->