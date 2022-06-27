{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews)}
	<!-- GSR - Rich Snippets Review for product -->
	{if !empty($sBadgeStyle) && ($sBadgeStyle == "bottom" || $sBadgeStyle == "top" || $sBadgeStyle == "home")}
		{assign var="sGsrSeparator" value=" - "}
		{assign var="sGsrSeparatorTop" value=""}
	{else}
		{assign var="sGsrSeparator" value="<br />"}
		{assign var="sGsrSeparatorTop" value="<br />"}
	{/if}
	{if !empty($sReviewsControllerUrl)}
	<a href="{$sReviewsControllerUrl|escape:'htmlall':'UTF-8'}" title="{l s='All last reviews' mod='gsnippetsreviews'}" class="{if $sGsrSeparatorTop == '<br />'}badge-reviews-link-br{else}badge-reviews-link{/if}">
	{/if}
	{* Individual Review *}
	{if !empty($bIndividualReview) && !empty($aReview)}
		<strong class="heading">{l s='Rating(s) and review(s)' mod='gsnippetsreviews'} {$sGsrSeparatorTop|escape:'UTF-8'}</strong>
		{if !empty($aReview.note) && !empty($aReview.firstname) && !empty($aReview.lastname)}
		<span>
			{if !empty($sItemReviewed)}<span>{$sItemReviewed|escape:'htmlall':'UTF-8'}</span>{$sGsrSeparator|escape:'UTF-8'}{/if}
			{l s='Reviewed by' mod='gsnippetsreviews'} : <span>{$aReview.firstname|escape:'htmlall':'UTF-8'} {$aReview.lastname|ucfirst|truncate:'1':''|escape:'htmlall':'UTF-8'}</span>
			{if !empty($bUseReviewDate) && !empty($aReview.date)} {l s='On' mod='gsnippetsreviews'} {$aReview.humanDate|escape:'UTF-8'}{/if}{$sGsrSeparator|escape:'UTF-8'}
			{if !empty($bUseRating)}
			<span>
				<span class="badge-stars rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
				{section loop=$iBestRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aReview.note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-front{if $aReview.note >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
				</span>{$sGsrSeparatorTop|escape:'UTF-8'}
				{l s='Rating' mod='gsnippetsreviews'} <span>{$aReview.note|intval}</span> / <span>{$iBestRating|intval}</span>
			</span>
			{/if}
			{if !empty($aReview.data.sTitle) && !empty($bUseReviewTitle)}
			<span>{$aReview.data.sTitle|escape:'UTF-8'}</span>{$sGsrSeparator|escape:'UTF-8'}
			{/if}
			{if !empty($aReview.data.sComment) && !empty($bUseReviewDesc)}
			<span>{$aReview.data.sComment|escape:'UTF-8'}</span>
			{/if}
		</span>
		{/if}
	{* Aggregate Reviews *}
	{elseif !empty($iCountRatings) && !empty($iAverage)}
		<strong class="heading">{l s='Rating(s) and review(s)' mod='gsnippetsreviews'} {$sGsrSeparatorTop|escape:'UTF-8'}</strong>
		<span>
			<span class="badge-stars">
				<span class="rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">{section loop=$iMaxRating name=note}<input type="radio" value="{if !empty($bHalfStar)}{math equation="x/2" x=$smarty.section.note.iteration}{else}{$smarty.section.note.iteration|intval}{/if}" {if !empty($iRating) && $iRating >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="{if !empty($bHalfStar) && $smarty.section.note.iteration%2}half{/if} badge-front{if !empty($bHalfStar)}-half{/if}{if !empty($iRating) && $iRating >= $smarty.section.note.iteration} checked{/if}" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}</span>
			</span>
			(<span>{$fReviewAverage|escape:'UTF-8'}</span>/<span>{$iBestRating|intval}</span>)
			{$sGsrSeparatorTop|escape:'UTF-8'}
			{if !empty($sItemReviewed)}<strong>{if !empty($sCurrentName)}{$sCurrentName|escape:'UTF-8'}{else}{l s='Name' mod='gsnippetsreviews'}{/if} :</strong> <span>{$sItemReviewed|escape:'UTF-8'}</span>{$sGsrSeparator|escape:'UTF-8'}{/if}
			{l s='Based on' mod='gsnippetsreviews'} <span class="font-weight">{$iCountRatings|intval}</span> {l s='rating(s)' mod='gsnippetsreviews'}
			{if !empty($iCountReviews)}
				{l s='and' mod='gsnippetsreviews'} <span class="font-weight">{$iCountReviews|intval}</span> {l s='user review(s)' mod='gsnippetsreviews'}
			{/if}
		</span>
	{/if}
	{if !empty($sReviewsControllerUrl)}
	{if $sGsrSeparatorTop != '<br />'}&nbsp;{$sGsrSeparator|escape:'UTF-8'}&nbsp;{/if}
	{l s='All reviews' mod='gsnippetsreviews'}&nbsp;<i class="text-size-9" class="icon-chevron-right right"></i>
	</a>
	{/if}
	<!-- /GSR - Rich Snippets Review for product -->
{/if}