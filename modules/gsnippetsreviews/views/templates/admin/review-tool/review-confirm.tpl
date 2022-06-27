{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div class="clr_10"></div>
<div class="alert alert-success" id="bt_confirm"><button type="button" class="close" data-dismiss="alert">×</button>
	{l s='Your review has been registered well' mod='gsnippetsreviews'}{if !empty($iLastInsertId)} {l s='and your review ID is' mod='gsnippetsreviews'} : {$iLastInsertId|intval}{else}.{/if}
</div>