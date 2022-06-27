{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bDisplayReviews) && (!empty($iCountRatings) || !empty($bUseRatings))}<!-- GSR - Product Review Tab title --><li class="nav-item"><a class="nav-link" data-toggle="tab" href="#idTab{$iIdTab|intval}">{l s='Reviews' mod='gsnippetsreviews'} ({$iCountRatings|intval})</a><a name="anchorReview" id="anchorReview"></a></li><!-- /GSR - Product Review Tab title -->{/if}