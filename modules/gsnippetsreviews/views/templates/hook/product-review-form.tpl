{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Product Review form -->
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
{if !empty($bDisplayForm)}
	<div id="comment-form" class="block">
		<form method="post" id="bt_comment-form" name="bt_comment-form" onsubmit="oGsr.form('bt_comment-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'comment-form', 'comment-form', false, false, null, 'review', 'review', '#fancybox-content');return false;">
			<input type="hidden" name="sAction" value="{$aQueryParams.postReview.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.postReview.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iPId" value="{$aProduct.id|intval}" />
			<input type="hidden" name="iCId" value="{$iCustomerId|intval}" />
			<input type="hidden" id="iRating" name="iRating" value="{if !empty($iCustomerNote)}{$iCustomerNote|intval}{elseif !empty($iPreSelectedRating)}{$iPreSelectedRating|intval}{/if}" />
			<input type="hidden" id="bCheckFieldText" name="bCheckFieldText" value="{if (!empty($iCustomerNote) && !empty($bEnableComments)) || !empty($bForceComments)}1{else}0{/if}" />
			<input type="hidden" id="btKey" name="btKey" value="{$sSecureKey|escape:'htmlall':'UTF-8'}" />

			{if !empty($aVoucherComment) || !empty($aVoucherShare)}
				<div class="rewards-info">
					{if !empty($aVoucherComment)}
					<p>
						<span><img src="{$sIMG_URI|escape:'htmlall':'UTF-8'}award.png" width="16" height="16" /> {l s='Submit a review and you will win a discount voucher of ' mod='gsnippetsreviews'} <strong>{$aVoucherComment.amount|escape:'htmlall':'UTF-8'}{$aVoucherComment.tax|escape:'htmlall':'UTF-8'}</strong>, {l s='valid for' mod='gsnippetsreviews'} {$aVoucherComment.validity|escape:'htmlall':'UTF-8'} {l s='days' mod='gsnippetsreviews'}</span>
					</p>
					{/if}
					{if !empty($aVoucherShare)}
					<p>
						<span> <img src="{$sIMG_URI|escape:'htmlall':'UTF-8'}fb.gif" width="16" height="16" /> {l s='Furthermore, you will ALSO win a discount voucher of ' mod='gsnippetsreviews'} <strong>{$aVoucherShare.amount|escape:'htmlall':'UTF-8'}{$aVoucherShare.tax|escape:'htmlall':'UTF-8'}</strong>, {l s='valid for' mod='gsnippetsreviews'} {$aVoucherShare.validity|escape:'htmlall':'UTF-8'} {l s='days' mod='gsnippetsreviews'} {l s='if you then share your review on Facebook !' mod='gsnippetsreviews'}</span>
					</p>
					{/if}
				</div>
			{/if}

			<h3 class="title_block">{l s='Write your review' mod='gsnippetsreviews'}</h3>
			<div class="product clearfix">
				<p class="description">
					<img src="{$aProduct.img|escape:'htmlall':'UTF-8'}" alt="{$aProduct.name|escape:'htmlall':'UTF-8'}" />
					<span>
						<strong>{$aProduct.name|escape:'htmlall':'UTF-8'}</strong><br /><br />
						{$aProduct.desc|escape:'htmlall':'UTF-8'}
					</span>
				</p>
			</div>

			<h3 class="title_block">{l s='Your Rating' mod='gsnippetsreviews'}</h3>

			<div class="margin-15 padding-20">
				<span class="left"><sup class="required">*</sup>&nbsp;<strong class="size12">{l s='Rate this product' mod='gsnippetsreviews'}</strong>&nbsp;:&nbsp;</span>
				{section loop=$iMaxRating name=note}<input class="star" type="radio" name="bt_rating" value="{$smarty.section.note.iteration|intval}" {if (!empty($iCustomerNote) && $iCustomerNote == $smarty.section.note.iteration) || (!empty($iPreSelectedRating) && $iPreSelectedRating == $smarty.section.note.iteration)}checked="checked"{/if}/>{/section}
			</div>

			{if !empty($iCustomerNote)}
				<p class="clear">&nbsp;</p>
				<div class="bootstrap"><div class="alert alert-info">{l s='You have already rated this product' mod='gsnippetsreviews'}{if !empty($bEnableComments)} {l s='but you can add a comment' mod='gsnippetsreviews'}{/if}</div></div>
			{/if}

			<div class="clr_20"></div>

			<div class="content">
				{* REVIEW SYSTEM ACTIVE *}
				{if $bCanReview}
				<h3 class="title_block">{l s='Your review' mod='gsnippetsreviews'}</h3>

				<div class="padding-20">
					<label for="comment_title">{if !empty($bForceComments)}<sup class="required">*</sup>&nbsp;{/if}{l s='Title' mod='gsnippetsreviews'}: </label>
					<input name="bt_review-title" id="bt_review-title" class="review-title" type="text" value="" />
					<label for="content">{if !empty($bForceComments)}<sup class="required">*</sup>&nbsp;{/if}{l s='Comment' mod='gsnippetsreviews'}: </label>
					<textarea name="bt_review-comment" id="bt_review-comment" class="review-comment"></textarea>
					{/if}
					{* /REVIEW SYSTEM ACTIVE *}

					<div class="clr_20"></div>
					<div id="bt_error-review"></div>

					<div id="footer">
						<p class="text-center">
							{if empty($iCustomerNote) || !empty($bEnableComments)}
								<button name="bt_comment-button" class="btn btn-success" value="{l s='Send' mod='gsnippetsreviews'}"  onclick="oGsr.form('bt_comment-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'comment-form', 'comment-form', false, false, null, 'review', 'review', '#fancybox-content');return false;" >{l s='Send' mod='gsnippetsreviews'}</button>
							{/if}
							&nbsp;
							<button class="btn btn-danger" value="{l s='Cancel' mod='gsnippetsreviews'}"  onclick="$.fancybox.close();return false;" >{l s='Cancel' mod='gsnippetsreviews'}</button>
						</p>
						<div class="clr_20"></div>
					</div>
					{if $bCanReview}
				</div>
				{/if}
			</div>
		</form>
		{literal}
		<script type="text/javascript">
			$(document).ready(function() {
				$('#bt_comment-form :radio.star').rating({
					ratingField : "iRating",
					readOnly : {/literal}{if !empty($iCustomerNote)}true{else}false{/if}{literal},
					starGif : "{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}",
					starWidth : "{/literal}{$iMaxRating|intval}{literal}"
				});
			});
		</script>
		{/literal}
	</div>
	<div id="bt_loading-div-review" style="display: none;">
		<div class="alert alert-info">
			<p class="text-center"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p class="text-center">{l s='Review is in progress (Facebook wall post and voucher can take a long time ) ...' mod='gsnippetsreviews'}</p>
		</div>
	</div>
{elseif !empty($sForbiddenMsg)}
	{if $sForbiddenMsg == 'buyer'}
		<div class="alert alert-warning form-warning">{l s='You cannot post a review because you are either not logged in or you have not purchased this product' mod='gsnippetsreviews'}</div>
	{elseif $sForbiddenMsg == 'customer'}
		<div class="alert alert-warning form-warning">{l s='You cannot post a review because you are not logged as a customer' mod='gsnippetsreviews'}<br />
			<div class="clr_10"></div>
			<p class="text-center"><a class="btn btn-primary" href="{$sLoginURI|escape:'htmlall':'UTF-8'}"><i class="icon-star-empty"></i> {l s='Log in / sign up' mod='gsnippetsreviews'}</a></p>
		</div>
	{elseif $sForbiddenMsg == 'review'}
		<div class="alert alert-info form-info">{l s='You have already rated or posted a comment for this product' mod='gsnippetsreviews'}</div>
	{elseif $sForbiddenMsg == 'secure'}
		<div class="alert alert-danger">{l s='There is an internal server error (unsecure content)' mod='gsnippetsreviews'} !</div>
	{/if}
{/if}
</div>
<!-- /GSR - Product Review form -->