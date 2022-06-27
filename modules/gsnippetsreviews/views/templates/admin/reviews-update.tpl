{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($bImport) && !empty($bUpdate)}
	{* CASE - import prestashop comments *}
	<div class="bootstrap">
		{if $iTotalReviews == $iCountImport}
			<div class="alert alert-success" id="{$sModuleName|escape:'htmlall':'UTF-8'}Confirm">{l s='Reviews updated' mod='gsnippetsreviews'}</div>
			<div class="clr_20"></div>
			<div class="alert alert-info">{l s='You have successfully imported' mod='gsnippetsreviews'} : {$iCountImport|intval} {l s='on' mod='gsnippetsreviews'} {$iTotalReviews|intval} {l s='reviews' mod='gsnippetsreviews'}.</div>
		{else}
			<div class="clr_20"></div>
			{if !empty($iCountImport)}
			<div class="alert alert-warning" id="{$sModuleName|escape:'htmlall':'UTF-8'}Confirm">
				{l s='Reviews updated' mod='gsnippetsreviews'}!<br />
				{l s='But you only have imported' mod='gsnippetsreviews'} : <strong>{$iCountImport|intval}</strong> {l s='on' mod='gsnippetsreviews'} <strong>{$iTotalReviews|intval}</strong> {l s='reviews' mod='gsnippetsreviews'}.
			</div>
			{if !empty($aErrors)}
				{include file="`$sErrorInclude`" bHideErrorClass=false}
			{/if}
			{else}
			<div class="alert alert-danger">
				{l s='You didn\'t imported any reviews' mod='gsnippetsreviews'}.<br />
				{l s='For a technical reason or for mismatch database content, ' mod='gsnippetsreviews'} <strong>{$iCountError|intval}</strong> {l s='reviews in error' mod='gsnippetsreviews'}!
				{if !empty($aErrors)}
					<div class="clr_20"></div>
					{include file="`$sErrorInclude`" bHideErrorClass=true bModal=true}
				{/if}
			</div>
			{/if}
		{/if}
	</div>
{/if}
