{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bReport)}
	<div class="alert alert-success" id="{$sModuleName|escape:'htmlall':'UTF-8'}Confirm">
		{l s='Your report has been taken into account' mod='gsnippetsreviews'}{if !empty($bSendMail)} {l s='and the merchant has been warned by e-mail' mod='gsnippetsreviews'}{/if}</strong>
	</div>
{else}
	<p class="alert alert-error">{l s='There was an internal server error. Sorry...' mod='gsnippetsreviews'}</p>
{/if}