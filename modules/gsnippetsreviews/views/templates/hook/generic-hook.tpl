{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($aContent)}
	<div id="{$sModuleName|escape:'htmlall':'UTF-8'}">
	{foreach from=$aContent name=generic key=iKey item=sContent}
		{if !empty($sContent)}{$sContent nofilter}{/if}
	{/foreach}
	</div>
{/if}