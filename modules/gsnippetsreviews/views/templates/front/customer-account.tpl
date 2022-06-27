{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Block customer account -->
<div id="{$sGsrModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
{capture name=path}<a href="{$sMyAccountLink|escape:'htmlall':'UTF-8'}">{l s='My account' mod='gsnippetsreviews'}</a><span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='My Reviews' mod='gsnippetsreviews'}{/capture}

	<h1 class="page-subheading">
		{l s='My Reviews' mod='gsnippetsreviews'}
	</h1>

	<div class="clr_10"></div>
	{if !empty($bUseCallback) && !empty($bActivateReview)}
		<h2 class="page-subheading">
			{l s='Review options' mod='gsnippetsreviews'}
		</h2>
		<div class="checkbox">
			<i class="icon-gear"></i>
			<label for="newsletter">
				<div class="checker" id="uniform-newsletter">
					<span><input class="cbk-pointer" type="checkbox" id="bt_callback-status" name="bt_callback-status" value="{if !empty($bCallbackStatus)}1{else}0{/if}" {if !empty($bCallbackStatus)}checked="checked"{/if} /></span>
				</div>
			</label>
			{l s='Send me a reminder e-mail to rate products after each order I place.' mod='gsnippetsreviews'}
		</div>

		<script type="text/javascript">
			bt_oCallback.run = true;
			bt_oCallback.selector = '#bt_callback-status';
			bt_oCallback.ajaxUri = '{$sAjaxUri|escape:'UTF-8'}';
			bt_oCallback.sParams = 'sAction={$aQueryParams.status.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.status.type|escape:'htmlall':'UTF-8'}&status=';
			bt_oCallback.eltToUpd = 'bt_update-status';
			{*{literal}*}
			{*$("#bt_callback-status").bind('click', function (event)*}
			{*{*}
			{*{/literal}*}
			{*oGsr.ajax("{$sAjaxUri|escape:'UTF-8'}", "sAction={$aQueryParams.status.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.status.type|escape:'htmlall':'UTF-8'}&status="+ $(this).attr('checked'), "bt_update-status", "bt_update-status", false);*}
			{*{literal}*}
			{*});*}
			{*{/literal}*}
		</script>
		<div id="bt_update-status"></div>
		<div class="clr_10"></div>
	{/if}

	{if empty($bActivateReview)}
		<div class="alert alert-info form-info">{l s='The Reviews feature is deactivated' mod='gsnippetsreviews'}.</div>
	{elseif !empty($aProductsToReview) || !empty($aReviewedProducts)}
		{if !empty($aProductsToReview) && !empty($bEnableRatings)}

		<h3 class="page-subheading">
			{l s='Products you have purchased but have not yet rated' mod='gsnippetsreviews'}
		</h3>

		<div id="homepage-slider">
			<ul id="homeslider">
				{section loop=$aProductsToReview name=product}
					<li class="homeslider-container">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="homeslider-description">
									<h3>{$aProductsToReview[product].product_name|escape:'htmlall':'UTF-8'}</h3>
									<p>{$aProductsToReview[product].desc|escape:'htmlall':'UTF-8'}</p>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<img src="{$aProductsToReview[product].img|escape:'htmlall':'UTF-8'}" alt="{$aProductsToReview[product].product_name|escape:'htmlall':'UTF-8'}"/>

								<div class="wrap-ao-rating-block">
									<div class="ao-rating-block">
										<p>{l s='Select your rating below' mod='gsnippetsreviews'}</p>

										<div class="clr_0"></div>

										<div id="bt_display-rating{$smarty.section.product.iteration|intval}">
											{section loop=$iMaxRating name=note}
												<input class="star" type="radio" name="bt_rating{$smarty.section.product.iteration|intval}" value="{$smarty.section.note.iteration|intval}" />
											{/section}
										</div>
										<div class="alert alert-danger" id="iRatingError{$smarty.section.product.iteration|intval}" style="display: none;">
											{l s='You didn\'t select the rating' mod='gsnippetsreviews'}
										</div>

										<div class="clr_10"></div>
										<p>
											<a class="btn btn-primary" href="javascript:void(0);" onclick="oGsr.redirectToProduct('iRating', {$smarty.section.product.iteration|intval}, '{$aProductsToReview[product].link|escape:'htmlall':'UTF-8'}', '{$sOpenForm|escape:'htmlall':'UTF-8'}');"><i class="icon-star-empty"></i> {l s='Rate this product' mod='gsnippetsreviews'}</a>
										</p>
										<input type="hidden" id="iRating" name="iRating{$smarty.section.product.iteration|intval}" value="0" />
									</div>
								</div>
							</div>
						</div>
					</li>
				{/section}
			</ul>
		</div>

		<div class="clr_50"></div>

		{literal}
		<script type="text/javascript">
			{/literal}
			{* USE CASE - instantiate jquery star plugin for every review of the page *}
			{section loop=$aProductsToReview name=review}
			{literal}
			bt_aStarsRating.push({'selector' : '#bt_display-rating{/literal}{$smarty.section.review.iteration|intval}{literal} :radio.star', 'ratingField' : 'iRating', 'readOnly' : false, 'starGif' : '{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}', 'starWidth' : {/literal}{$iMaxRating|intval}{literal}});
			{/literal}
			{/section}
			{literal}
			// handle bsSlider
			bt_oBxSlider.run = true;
			bt_oBxSlider.selector = '#homeslider';
			bt_oBxSlider.slideWidth = {/literal}{$iSliderWidth|intval}{literal};
			bt_oBxSlider.auto = {/literal}{if isset($iNbNoReview) && $iNbNoReview > 1}true{else}false{/if}{literal};
			bt_oBxSlider.speed = {/literal}{$iSliderSpeed|intval}{literal};
			bt_oBxSlider.pause = {/literal}{$iSliderPause|intval}{literal};
		</script>
		{/literal}
		{/if}
		{if !empty($aReviewedProducts)}
			<h3 class="page-subheading">
				{l s='Products you have purchased and have already rated' mod='gsnippetsreviews'} {l s='or only rated' mod='gsnippetsreviews'}
			</h3>
			<div class="responsive-table-line">
				<table id='bt_review_account' class="table table-bordered table-condensed table-body-center" >
					<thead>
					<tr>
						<th class="first_item text-center">{l s='Product' mod='gsnippetsreviews'}</th>
						<th class="item text-center">{l s='Purchased' mod='gsnippetsreviews'} ?</th>
						<th class="item min-120 text-center">{l s='Rating' mod='gsnippetsreviews'}</th>
						<th class="item text-center">{l s='Adding date' mod='gsnippetsreviews'}</th>
						<th class="item text-center">{l s='Title' mod='gsnippetsreviews'}</th>
						<th class="item text-center">{l s='Modify my review' mod='gsnippetsreviews'}</th>
						<th class="last_item text-center">{l s='Status' mod='gsnippetsreviews'}</th>
					</tr>
					</thead>
					<tbody>
					{foreach from=$aReviewedProducts name=review key=iKey item=aReview}
						<tr>
							<td data-title="{l s='Product' mod='gsnippetsreviews'}"><a target="_blank" href="{$aReview.link|escape:'htmlall':'UTF-8'}{if empty($aReview.rvwData)}?{$sOpenForm|escape:'htmlall':'UTF-8'}=true&rtg={$aReview.note|intval}{/if}" title="{if empty($aReview.rvwData)}{l s='Add a review to your rating' mod='gsnippetsreviews'}{else}{l s='See the product' mod='gsnippetsreviews'}{/if}" class="accountLinks" rel="nofollow">{$aReview.product_name|escape:'htmlall':'UTF-8'}</a></td>
							<td data-title="{l s='Purchased' mod='gsnippetsreviews'}" class="center text-center" nowrap="nowrap">
								{if !empty($aReview.productBought)}
									<i class="icon-ok-sign" title="{l s='Product purchased' mod='gsnippetsreviews'}"></i>
								{else}
									<i class="icon-remove-sign" title="{l s='Product not purchased' mod='gsnippetsreviews'}"></i>
								{/if}
							</td>
							<td data-title="{l s='Rating' mod='gsnippetsreviews'}" class="center text-center" nowrap="nowrap">
								<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.note >= $smarty.section.note.iteration}checked="checked"{/if} /><label class="product-tab{if $aReview.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
							</td>
							<td data-title="{l s='Adding date' mod='gsnippetsreviews'}" class="center">{if !empty($aReview.rvwData)}{$aReview.rvwDateAdd|date_format:"%d/%m/%Y"}{else}{$aReview.dateAdd|date_format:"%d/%m/%Y"}{/if}</td>
							<td data-title="{l s='Title' mod='gsnippetsreviews'}" class="center">{if !empty($aReview.rvwData)}<a target="_blank" href="{$aReview.reviewLink|escape:'htmlall':'UTF-8'}" class="accountLinks" rel="nofollow">{$aReview.rvwData.sTitle|escape:'htmlall':'UTF-8'}</a>{else}{l s='No review' mod='gsnippetsreviews'}{/if}</td>
							<td data-title="{l s='Modify my review' mod='gsnippetsreviews'}" class="center text-center">
								{if !empty($aReview.replyData)}
									{if empty($aReview.ratingData.iOldRating) && empty($aReview.rvwData.sOldTitle)}
										<a class="btn btn-info" rel="nofollow" href="{$aReview.modifyReviewLink|escape:'htmlall':'UTF-8'}" target="_blank">{l s='Modify' mod='gsnippetsreviews'}&nbsp;<i class="icon-comment" title="{l s='you can click and modify your review' mod='gsnippetsreviews'}"></i></a>
									{else}
										<i class="icon-ok-sign" title="{l s='Review already changed' mod='gsnippetsreviews'}"></i>
									{/if}
								{else}
									{l s='No reply' mod='gsnippetsreviews'}
								{/if}
							</td>
							<td data-title="{l s='Status' mod='gsnippetsreviews'}" class="pointer center text-center nowrap responsive-table-last-td">
								{if !empty($aReview.rvwData)}
									{if $aReview.display == 0}
										<i class="icon-time" title="{l s='The review is pending moderation' mod='gsnippetsreviews'}"></i>
									{else}
										<i class="icon-ok-sign" title="{l s='Active' mod='gsnippetsreviews'}"></i>
									{/if}
								{else}
									{l s='No review' mod='gsnippetsreviews'}
								{/if}
							</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>
			<div id="block-order-detail">&nbsp;</div>
		{/if}
	{else}
		<h3 class="page-subheading">
			{l s='Products and reviews' mod='gsnippetsreviews'}
		</h3>

		<div class="clr_10"></div>

		<div class="alert alert-warning form-warning">{l s='You have not posted any reviews or bought any products' mod='gsnippetsreviews'}.</div>
	{/if}

	<ul class="footer_links clearfix">
		<li>
			<a class="btn btn-default button button-small" href="{$sMyAccountLink|escape:'htmlall':'UTF-8'}">
			<span>
				<i class="icon-chevron-left"></i> {l s='Back to Your Account' mod='gsnippetsreviews'}
			</span>
			</a>
		</li>
		<li>
			<a class="btn btn-default button button-small" href="{$base_dir|escape:'htmlall':'UTF-8'}">
				<span><i class="icon-chevron-left"></i> {l s='Home' mod='gsnippetsreviews'}</span>
			</a>
		</li>
	</ul>
</div>
<!-- /GSR - Block customer account -->