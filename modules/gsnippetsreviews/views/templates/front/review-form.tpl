{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Litigation Review form -->
<div id="{$sGsrModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	{if !empty($bDisplayForm)}
		<div id="comment-form" class="review block">
			<h1 class="title_block margin-top">{l s='Review for' mod='gsnippetsreviews'} "{$aProduct.name|escape:'htmlall':'UTF-8'}"</h1>
			<div class="product clearfix">
				<p class="description">
					<a href="{$aProduct.link|escape:'htmlall':'UTF-8'}" alt="{l s='See the product' mod='gsnippetsreviews'}">
						<img src="{$aProduct.img|escape:'htmlall':'UTF-8'}" alt="{$aProduct.name|escape:'htmlall':'UTF-8'}" />
						<span>
							<strong>{$aProduct.name|escape:'htmlall':'UTF-8'}</strong><br /><br />
							{$aProduct.desc|escape:'htmlall':'UTF-8'}
					   </span>
					</a>
				</p>
			</div>

			<div class="clr_10"></div>

			<form method="post" id="bt_comment-form" name="bt_comment-form" onsubmit="oGsr.form('bt_comment-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'comment-form', 'comment-form', false, false, null, 'modify-review', 'modify-review');return false;">
				<input type="hidden" name="sAction" value="{$aQueryParams.reply.action|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="sType" value="{$aQueryParams.reply.type|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="iCId" value="{$aRating.custId|intval}" />
				<input type="hidden" name="iPId" value="{$aRating.prodId|intval}" />
				<input type="hidden" name="iRId" value="{$aRating.id|intval}" />
				<input type="hidden" id="rating" name="rating" value="{if empty($aRating.data.iOldRating)}1{else}0{/if}" />
				<input type="hidden" id="iOldRating" name="iOldRating" value="{$aRating.note|intval}" />
				<input type="hidden" id="iRating" name="iRating" value="{$aRating.note|intval}" />
				<input type="hidden" id="iLangId" name="iLangId" value="{$aRating.review.data.iLangId|intval}" />
				<input type="hidden" id="sLangIso" name="sLangIso" value="{$aRating.review.data.sLangIso|escape:'htmlall':'UTF-8'}" />
				{if !empty($aRating.review) && empty($aRating.review.data.sOldTitle) && empty($aRating.review.data.sOldComment)}
				<input type="hidden" id="bt_old-review-title" name="bt_old-review-title" value="{$aRating.review.data.sTitle|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" id="bt_old-review-comment" name="bt_old-review-comment" value="{$aRating.review.data.sComment|escape:'UTF-8'}" />
				<input type="hidden" id="bCheckFieldText" name="bCheckFieldText" value="1" />
				{/if}
				<input type="hidden" id="btKey" name="btKey" value="{$sSecureKey|escape:'htmlall':'UTF-8'}" />

				<div class="alert alert-info form-info">
					{l s='You can change your rating and / or review below, but please note you can only do this once' mod='gsnippetsreviews'}.
				</div>

				<div class="clr_20"></div>

				<h3 class="page-product-heading">{l s='Your review' mod='gsnippetsreviews'}</h3>

				<div class="clr_10"></div>

				<div id="bt_star-rating" class="margin-left">
					{l s='You' mod='gsnippetsreviews'} <strong>{$aRating.firstname|escape:'htmlall':'UTF-8'}{if !empty($aRating.lastname)} {$aRating.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aRating.address)} ({$aRating.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aRating.review.dateAdd) || !empty($aRating.dateAdd)} {l s='on' mod='gsnippetsreviews'} {if !empty($aRating.review.dateAdd)}{$aRating.review.dateAdd|escape:'UTF-8'}{else}{$aRating.dateAdd|escape:'UTF-8'}{/if}{/if} : <span class="stars-right">{section loop=$iMaxRating name=note}<input class="star" type="radio" name="gsrRating" value="{$smarty.section.note.iteration|intval}" {if !empty($aRating.note) && $aRating.note == $smarty.section.note.iteration}checked="checked"{/if}/>{/section}</span>
				</div>

				<div class="clr_20"></div>

				{if !empty($aRating.review)}
					<div class="form-group">
						<label for="inputTitle" class="control-label">{l s='Title' mod='gsnippetsreviews'} : </label>
						<div>
							<input name="bt_review-title" id="bt_review-title" class="form-control review-title" type="text" value="{$aRating.review.data.sTitle|escape:'htmlall':'UTF-8'}" {if !empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment)}disabled{/if} >
						</div>
						<div class="clr_20"></div>
						<label for="inputComment" class="control-label">{l s='Comment' mod='gsnippetsreviews'} : </label>
						<div>
							<textarea class="form-control" name="bt_review-comment" id="bt_review-comment" class="review-comment" {if !empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment)}disabled{/if}>{$aRating.review.data.sComment|escape:'UTF-8'}</textarea>
						</div>
					</div>
				{else}
					<div class="alert alert-warning form-warning">
						{l s='You have rated the product but have not posted a review, or the review is pending moderation' mod='gsnippetsreviews'}
					</div>
				{/if}

				<div class="clr_20"></div>

				<div id="bt_error-modify-review"></div>

				{if empty($aRating.data.iOldRating) || (!empty($aRating.review) && empty($aRating.review.data.sOldTitle) && empty($aRating.review.data.sOldComment))}
					<p class="text-center">
						<button name="bt_comment-button" class="btn btn-success" value="{l s='Send' mod='gsnippetsreviews'}"  onclick="oGsr.form('bt_comment-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'comment-form', 'comment-form', false, false, null, 'modify-review', 'modify-review');return false;" >{l s='Send' mod='gsnippetsreviews'}</button>
					</p>
				{/if}

				{* USE CASE - OLD REVIEW *}
				{if !empty($aRating.data.iOldRating) || (!empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment))}
					<div class="clr_20"></div>
					<div class="alert alert-warning form-warning">
						{if !empty($aRating.data.iOldRating) && (!empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment))}
						{l s='You have already changed your rating and review. You can do it only once' mod='gsnippetsreviews'}!
						{elseif !empty($aRating.data.iOldRating) && empty($aRating.review.data.sOldTitle) && empty($aRating.review.data.sOldComment)}
						{l s='You have already changed your rating. You can do it only once' mod='gsnippetsreviews'}!
						{elseif empty($aRating.data.iOldRating) && !empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment)}
						{l s='You have already changed your review. You can do it only once' mod='gsnippetsreviews'}!
						{/if}
						<br />
						{l s='You can see the old review by clicking on the button below' mod='gsnippetsreviews'}.
					</div>

					<span onclick="$('#bt_old-review').slideToggle();" class="btn btn-warning">{l s='Past review' mod='gsnippetsreviews'}&nbsp;<span class="icon-eye-open"></span></span>

					<div id="bt_old-review" style="display: none;">
						<div class="clr_20"></div>

						<h3 class="page-product-heading">{l s='Your old review' mod='gsnippetsreviews'}</h3>

						<div class="clr_10"></div>

						{if !empty($aRating.data.iOldRating)}
						<div class="form-group">
							<label for="inputTitle">{l s='Your old rating' mod='gsnippetsreviews'} : </label>
							<div>
								<span class="left" id="bt_old-star-rating">{section loop=$iMaxRating name=note}<input class="star" type="radio" id="bt_old-rating" name="bt_old-rating" value="{$smarty.section.note.iteration|intval}" {if !empty($aRating.data.iOldRating) && $aRating.data.iOldRating == $smarty.section.note.iteration}checked="checked"{/if}/>{/section}</span>
							</div>
						</div>
						{/if}
						{if !empty($aRating.review.data.sOldTitle) && !empty($aRating.review.data.sOldComment)}
							<div class="clr_20"></div>
							<div class="form-group">
								<label for="inputTitle" class="control-label">{l s='Your old title' mod='gsnippetsreviews'} : </label>
								<div>
									<input name="bt_old-review-title" id="bt_old-review-title" class="form-control" type="text" value="{$aRating.review.data.sOldTitle|escape:'htmlall':'UTF-8'}" disabled >
								</div>
								<div class="clr_20"></div>
								<label for="inputComment" class="control-label">{l s='Your old comment' mod='gsnippetsreviews'} : </label>
								<div>
									<textarea class="form-control" name="bt_old-review-comment" id="bt_old-review-comment" class="review-comment" disabled >{$aRating.review.data.sOldComment|escape:'UTF-8'}</textarea>
								</div>
							</div>
						{/if}
					</div>
				{/if}
			</form>
			{literal}
			<script type="text/javascript">
				bt_aStarsRating.push({'selector' : '#bt_star-rating :radio.star', 'ratingField' : 'iRating', 'readOnly' : {/literal}{if !empty($aRating.data.iOldRating)}true{else}false{/if}{literal}, 'starGif' : '{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}', 'starWidth' : {/literal}{$iMaxRating|intval}{literal}});
				{/literal}
				{if !empty($aRating.data.iOldRating)}
				{literal}
				bt_aStarsRating.push({'selector' : '#bt_old-star-rating :radio.star', 'ratingField' : 'iRating', 'readOnly' : true, 'starGif' : '{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}', 'starWidth' : {/literal}{$iMaxRating|intval}{literal}});
				{/literal}
				{/if}
				{literal}
			</script>
			{/literal}
			{*{literal}*}
			{*<script type="text/javascript">*}
				{*$(document).ready(function(){*}
					{*$('#bt_star-rating :radio.star').rating({*}
						{*ratingField : "iRating",*}
						{*readOnly : {/literal}{if !empty($aRating.data.iOldRating)}true{else}false{/if}{literal},*}
						{*starGif : "{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}",*}
						{*starWidth : "{/literal}{$iMaxRating|intval}{literal}"*}
					{*});*}
					{*{/literal}*}
					{*{if !empty($aRating.data.iOldRating)}*}
					{*{literal}*}
					{*$('#bt_old-star-rating :radio.star').rating({*}
						{*ratingField : "iRating",*}
						{*readOnly : true,*}
						{*starGif : "{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}",*}
						{*starWidth : "{/literal}{$iMaxRating|intval}{literal}"*}
					{*});*}
					{*{/literal}*}
					{*{/if}*}
					{*{literal}*}
				{*});*}
			{*</script>*}
			{*{/literal}*}
		</div>
		<div id="bt_loading-div-modify-review" style="display: none;">
			<div class="alert alert-info">
				<p class="text-center"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
				<p class="text-center">{l s='Review is in progress ...' mod='gsnippetsreviews'}</p>
			</div>
		</div>
	{elseif !empty($sForbiddenMsg)}
		{if $sForbiddenMsg == 'buyer'}
			<div class="alert alert-warning form-warning">{l s='You cannot post a review because you are either not logged in or you have not purchased this product' mod='gsnippetsreviews'}</div>
		{elseif $sForbiddenMsg == 'customer'}
			<div class="alert alert-warning form-warning">{l s='You cannot post a review because you are not logged as a customer' mod='gsnippetsreviews'}</div>
		{elseif $sForbiddenMsg == 'identification'}
			<div class="alert alert-warning form-warning">{l s='You cannot post a review because you are not logged as the customer who made this review' mod='gsnippetsreviews'}</div>
		{elseif $sForbiddenMsg == 'secure'}
			<div class="alert alert-danger">{l s='There is an internal server error (unsecure content)' mod='gsnippetsreviews'} !</div>
		{/if}
	{/if}
</div>
<!-- /GSR - Litigation Review form -->