{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews) && !empty($aReviews)}
	<!-- GSR - Block last reviews -->
	<div class="{if empty($bDisplayFirst)}clr_20{else}clr_10{/if}"></div>
	<div class="last-reviews-{$sPosition|escape:'htmlall':'UTF-8'}" {if !empty($aBadeOptions.width)}style="{if !empty($aBadeOptions.width)}width: {$aBadeOptions.width|intval}% !important;{/if}"{/if}>
		<a href="{$sReviewsControllerUrl|escape:'htmlall':'UTF-8'}" title="{l s='all Last reviews' mod='gsnippetsreviews'}">
			<h2 class="h2">{l s='Last reviews' mod='gsnippetsreviews'}</h2>
		</a>

		<div class="block_content">
			{foreach from=$aReviews name=reviewBlock key=iKey item=aReview}
				<div class="clr_10"></div>
				<div class="review-line">
					{* USE CASE - Display in column *}
					{if $sPosition == 'colLeft' || $sPosition == 'colRight'}
						<p class="review-name">
							{l s='By' mod='gsnippetsreviews'} <strong>{$aReview.firstname|escape:'htmlall':'UTF-8'} {if !empty($aReview.lastname)}{$aReview.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aReview.address)} ({$aReview.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aReview.dateAdd)}&nbsp;{l s='on' mod='gsnippetsreviews'} {$aReview.dateAdd|escape:'UTF-8'}{/if} :
						</p>
						<div class="clr_0"></div>
						<div class="inline pull-right">
							<span class="pull-left text-size-07">({$aReview.rating.note|intval}/{$iMaxRating|intval})&nbsp;</span>
							<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.rating.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.rating.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
						</div>
					{else}
						{* USE CASE - Display in home / top or bottom width 100% *}
						<div class="review-line-name">
							{l s='By' mod='gsnippetsreviews'} <strong>{$aReview.firstname|escape:'htmlall':'UTF-8'} {if !empty($aReview.lastname)}{$aReview.lastname|truncate:"1":""|upper|escape:'htmlall':'UTF-8'}.{/if}</strong>{if !empty($aReview.address)} ({$aReview.address|escape:'htmlall':'UTF-8'}){/if}{if !empty($aReview.dateAdd)}&nbsp;<span>{l s='on' mod='gsnippetsreviews'} {$aReview.dateAdd|escape:'htmlall':'UTF-8'}</span>{/if} :
							<div class="pull-right review-line-rating">
								<div class="left text-size-07">
									({$aReview.rating.note|intval}/{$iMaxRating|intval})&nbsp;
								</div>
								<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.rating.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab{if $aReview.rating.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</div>
							</div>
						</div>
					{/if}
					<div class="review-line-comment">
						<div class="clr_5"></div>
						{l s='Product rated' mod='gsnippetsreviews'} : <a href="{$aReview.sProductLink|escape:'htmlall':'UTF-8'}" title="{$aReview.sProductName|escape:'htmlall':'UTF-8'}">{$aReview.sProductName|escape:'htmlall':'UTF-8'} <i class="icon icon-chevron-right"></i></a>
						<span class="clr_10"></span>
						{if !empty($aBadeOptions.truncate)}
							{$aReview.data.sComment|truncate:$aBadeOptions.truncate:"..."|escape:'UTF-8'}
						{else}
							{$aReview.data.sComment|truncate:30:"..."|escape:'UTF-8'}
						{/if}
					</div>
				</div>
			{/foreach}

			<div class="clr_5"></div>

			<div class="pull-right">
				<a class="btn btn-default button button-small" href="{$sReviewsControllerUrl|escape:'htmlall':'UTF-8'}" title="{l s='All last reviews' mod='gsnippetsreviews'}">
					<span>{l s='All reviews' mod='gsnippetsreviews'}<i class="icon-chevron-right right"></i></span>
				</a>
			</div>
		</div>
	</div>
	<div class="clr_10"></div>
	<!-- /GSR - Block last reviews -->
{/if}