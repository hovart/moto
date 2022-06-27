{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews) && (!empty($iCountRatings) || !empty($bUseRatings))}<!-- GSR - Product Review Tab title --><li><a id="more_info_tab_reviews" href="#idTab{$iIdTab|intval}" {if !empty($sTabMode) && $sTabMode == 'bootstrap'}data-toggle="tab"{/if} >{l s='Reviews' mod='gsnippetsreviews'} ({$iCountRatings|intval})</a></li><a name="anchorReview" id="anchorReview"></a><!-- /GSR - Product Review Tab title -->{/if}