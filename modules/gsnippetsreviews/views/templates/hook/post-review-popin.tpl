{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Post review popin -->
{if !empty($aVoucherComment) || !empty($aVoucherFb) || !empty($aFbLike) || !empty($bAddReview) || !empty($bAddRating)}
	<div id="{$sModuleName|escape:'htmlall':'UTF-8'}">
		<!-- Customer just won a voucher for posting comment -->
		{if !empty($aVoucherComment)}
		<h3><i class="icon-star"></i> {l s='You just won a voucher worth' mod='gsnippetsreviews'} <strong>{$aVoucherComment.amount|escape:'htmlall':'UTF-8'}{$aVoucherComment.tax|escape:'htmlall':'UTF-8'}</strong> {l s='for having posted a review' mod='gsnippetsreviews'}.</h3>
		<div class="alert alert-info form-info" class="size14">
			{l s='Here is your voucher code' mod='gsnippetsreviews'} : <strong>{$aVoucherComment.name|escape:'htmlall':'UTF-8'}</strong><br />
			{l s='It is valid until' mod='gsnippetsreviews'} : <strong>{$aVoucherComment.dateTo|escape:'htmlall':'UTF-8'}</strong>
		</div>
		<div class="clr_10"></div>
		{/if}

		{if empty($aVoucherFb) && empty($aFbLike)}
		<!-- Customer can win another voucher for posting comment on Facebook -->
		<h3><i class="gsr icon icon-facebook"></i> {l s='Share this comment on Facebook' mod='gsnippetsreviews'}</h3>
		<div class="alert alert-info form-info" class="size14">
			{if !empty($aVoucherShare)}
			{l s='Win an additional voucher worth' mod='gsnippetsreviews'} <strong>{$aVoucherShare.amount|escape:'htmlall':'UTF-8'}{$aVoucherShare.tax|escape:'htmlall':'UTF-8'}</strong> {l s='if you share your review on Facebook !' mod='gsnippetsreviews'}<br />
			<span class="clear"></span>
			<span>{l s='The voucher will be valid for' mod='gsnippetsreviews'} <strong>{$aVoucherShare.validity|escape:'htmlall':'UTF-8'}</strong> {l s='days' mod='gsnippetsreviews'}</span><br />
			{/if}
			{l s='To share your review, simply use the Like button below your review on the product page' mod='gsnippetsreviews'}
		</div>
		<div class="clr_10"></div>
		{/if}

		<!-- Customer just won a voucher for posting comment on Facebook -->
		{if !empty($aVoucherFb)}
		<h3><i class="gsr icon icon-facebook"></i> {l s='You just won a voucher worth' mod='gsnippetsreviews'} {$aVoucherFb.amount|escape:'htmlall':'UTF-8'}{$aVoucherFb.tax|escape:'htmlall':'UTF-8'} {l s='for having shared your review on Facebook' mod='gsnippetsreviews'}.</h3>
		<div class="alert alert-info form-info" class="size14">
			{l s='Here is your voucher code' mod='gsnippetsreviews'} : <strong>{$aVoucherFb.name|escape:'htmlall':'UTF-8'}</strong><br />
			{l s='It is valid until' mod='gsnippetsreviews'} : <strong>{$aVoucherFb.dateTo|escape:'htmlall':'UTF-8'}</strong>
		</div>
		<div class="clr_10"></div>
		<!-- For other users (not customer who posted the review), thank you for sharing on Facebook -->
		{elseif !empty($aFbLike)}
			<div class="alert alert-success">{l s='Thank you for having shared this review on Facebook' mod='gsnippetsreviews'}.</div>
		{/if}

		<!-- Customer has posted a (new) rating or (new) review -->
		{if !empty($bAddRating) && !empty($bAddReview)}
		<div class="alert alert-success">
			{l s='Your rating and review have been posted' mod='gsnippetsreviews'}
		</div>
		{elseif !empty($bAddRating)}
		<div class="alert alert-success">
			{l s='Your rating has been posted' mod='gsnippetsreviews'}
		</div>
		{elseif !empty($bAddReview)}
		<div class="alert alert-success">
			{l s='Your review has been posted' mod='gsnippetsreviews'}
		</div>
		{/if}
	</div>
{elseif !empty($aErrors)}
	{include file="`$sErrorInclude`"}
{else}
<div class="alert alert-danger">{l s='There was an internal server error. Sorry...' mod='gsnippetsreviews'}</div>
{/if}
{if !empty($bAddRefreshButton)}
	<p class="text-center"><button class="btn btn-info btn-lg" onclick="window.location.href = window.location.href;">{l s='Reload' mod='gsnippetsreviews'}&nbsp;<i class="icon-refresh"></i></button></p>
{/if}
<!-- /GSR - Post review popin -->