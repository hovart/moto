{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if empty($bHideErrorClass)}
<div class="clr_20"></div>

<div class="alert alert-danger">
{/if}
	{if empty($bModal)}<button type="button" class="close" data-dismiss="alert">×</button>{/if}
	<ul class="list-unstyled">
	{foreach from=$aErrors name=condition key=nKey item=aError}
		<li>{$aError.msg|escape:'htmlall':'UTF-8'}</li>
		{if $bDebug == true}
			<ol>
				{if !empty($aError.code)}<li>{l s='Error code' mod='gsnippetsreviews'} : {$aError.code|intval}</li>{/if}
				{if !empty($aError.file)}<li>{l s='Error file' mod='gsnippetsreviews'} : {$aError.file|escape:'htmlall':'UTF-8'}</li>{/if}
				{if !empty($aError.line)}<li>{l s='Error line' mod='gsnippetsreviews'} : {$aError.line|intval}</li>{/if}
				{if !empty($aError.context)}<li>{l s='Error context' mod='gsnippetsreviews'} : {$aError.context|escape:'htmlall':'UTF-8'}</li>{/if}
			</ol>
		{/if}
	{/foreach}
	</ul>
{if empty($bHideErrorClass)}
</div>
{/if}