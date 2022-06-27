{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplay) && !empty($sBadgeStyle) && !empty($iCountRatings) && !empty($iAverage)}
	<!-- GSR - Rich Snippets Review for HP -->
	{if $sBadgeStyle == "bottom"}
	<div class="clr_20"></div>
	{/if}
	<div {if !empty($sBadgeFreeStyle)}style="{$sBadgeFreeStyle|escape:'UTF-8'}"{else}class="badge-{$sBadgeStyle|escape:'htmlall':'UTF-8'}"{/if}>
		{include file="`$sReviewSnippetsIncl`"}
	</div>
	{if $sBadgeStyle != "bottom"}
	<div class="clr_20"></div>
	{/if}
	<!-- /GSR - Rich Snippets Review for HP -->
{/if}