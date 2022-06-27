{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div class="bootstrap">
	{if !empty($bUpdate)}
		<div class="alert alert-success" id="{$sModuleName|escape:'htmlall':'UTF-8'}Confirm">{l s='Review litigation reply updated' mod='gsnippetsreviews'}
			{if !empty($bSendEmail)}
				<br />{l s='and your e-mail has been sent' mod='gsnippetsreviews'}
			{/if}
		</div>
	{elseif !empty($aErrors)}
		{include file="`$sErrorInclude`"}
	{/if}
</div>