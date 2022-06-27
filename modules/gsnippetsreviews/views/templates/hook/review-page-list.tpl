{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bProcessJson)}
	{$json|escape:'UTF-8'}
{elseif !empty($bDisplayReviews) && isset($iAverage)}
	<!-- GSR - Product list reviews -->
	{if $iStarDisplayMode == 1 || ($iStarDisplayMode != 1 && empty($iCountRatings))}
		<div id="gsr-review-list" {if !empty($bPsVersion1611)}class="{$sModuleName|escape:'htmlall':'UTF-8'}-review-stars-1611"{/if}>
			{if !empty($sTextFirst) && empty($iCountRatings)}
				<div class="col-xs-12 gsr-text-center bg-white">
					<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}{if !empty($iStarPaddingLeft)} gsr-stars-padding-left-{$iStarPaddingLeft|intval}{/if}">
						{section loop=$iMaxRating name=note}
							<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if $iAverage >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} list-front{if !empty($bHalfStar)}-half{/if}{if $iAverage >= $smarty.section.note.iteration} checked{/if} rating-star-size-{$iStarSize|escape:'htmlall':'UTF-8'}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>
						{/section}
					</div>
					<a href="{$sProductLink|escape:'htmlall':'UTF-8'}" class="gsr-no-underline gsr-side-text-{$iTextSize|intval}">{$sTextFirst|escape:'htmlall':'UTF-8'}</a>
				</div>
			{else}
				<div class="col-xs-12 bg-white">
					<div class="col-xs-3"></div>
					<div class="col-xs-8">
						<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
							{section loop=$iMaxRating name=note}
								<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if $iAverage >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} list-front{if !empty($bHalfStar)}-half{/if}{if $iAverage >= $smarty.section.note.iteration} checked{/if} rating-star-size-{$iStarSize|escape:'htmlall':'UTF-8'}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>
							{/section}
						</div>
					</div>
					<div class="col-xs-1"></div>
				</div>
			{/if}
		</div>
	{* USE CASE - display not only stars ratings *}
	{elseif !empty($iCountRatings)}
		<div id="gsr-review-list" class="col-xs-12 gsr-text-center bg-white">
			<div class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}{if !empty($bPsVersion1611)} {$sModuleName|escape:'htmlall':'UTF-8'}-review-stars-1611{/if}{if !empty($iStarPaddingLeft)} gsr-stars-padding-left-{$iStarPaddingLeft|intval}{/if}">
				{section loop=$iMaxRating name=note}
					<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if $iAverage >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} list-front{if !empty($bHalfStar)}-half{/if}{if $iAverage >= $smarty.section.note.iteration} checked{/if} rating-star-size-{$iStarSize|escape:'htmlall':'UTF-8'}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>
				{/section}
			</div>
			<div class="{$sModuleName|escape:'htmlall':'UTF-8'}-review-count-padding{if !empty($bPsVersion1611)}-1611{/if} gsr-side-text-{$iTextSize|intval}">
				{* USE CASE - display rich snippets rating *}
				{if !empty($bSnippetsProdList)}
					{if !empty($sItemReviewed)}<div itemscope itemtype="http://schema.org/Product"><meta itemprop="name" content="{$sItemReviewed|escape:'htmlall':'UTF-8'}" />{/if}
					<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="{$sModuleName|escape:'htmlall':'UTF-8'}-review-count-rating"> (<span itemprop="ratingValue">{$fDetailAverage|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating">{$iDefaultMaxRating|intval}</span>)&nbsp;{l s='on' mod='gsnippetsreviews'}&nbsp;<meta itemprop="worstRating" content="1" /><span itemprop="ratingCount">{$iCountRatings|intval}</span> {l s='rating(s)' mod='gsnippetsreviews'}</div>
					{if !empty($sItemReviewed)}</div>{/if}
					{* USE CASE - do not display rich snippets rating *}
				{else}
					<div class="{$sModuleName|escape:'htmlall':'UTF-8'}-review-count-rating"> ({$fDetailAverage|escape:'htmlall':'UTF-8'}/{$iDefaultMaxRating|intval})&nbsp;{l s='on' mod='gsnippetsreviews'}&nbsp;{$iCountRatings|intval} {l s='rating(s)' mod='gsnippetsreviews'}</div>
				{/if}
			</div>
		</div>
	{/if}
	<div class="gsr-clr_20"></div>
	<!-- /GSR - Product list reviews -->
{/if}