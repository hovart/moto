<div id="tptn_header_links">
<ul>
	{if $logged}
		<li>
			<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='My account' mod='tptnheaderlinks'}" rel="nofollow"><i class="fa fa-user left"></i>
			{if $isMobile != 'mobile'}
				{l s='My account' mod='tptnheaderlinks'}</a>
			{/if}
		</li>
		<li>
			<a class="logout" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" rel="nofollow" title="{l s='Sign out' mod='tptnheaderlinks'}"><i class="fa fa-sign-out left"></i>
			{if $isMobile != 'mobile'}
				{l s='Sign out' mod='tptnheaderlinks'}</a>
			{/if}
		</li>
	{else}
		<li>
			<a class="login" href="{$link->getPageLink('my-account', true)|escape:'html'}" rel="nofollow" title="{l s='Sign in' mod='tptnheaderlinks'}"><i class="fa fa-sign-in left"></i>
			{if $isMobile != 'mobile'}
				{l s='Sign in' mod='tptnheaderlinks'}</a>
			{/if}
		</li>
		<li>
			<a href="{$link->getPageLink('my-account', true)|escape:'html'}" rel="nofollow" title="{l s='Register' mod='tptnheaderlinks'}"><i class="fa fa-user-plus left"></i>
			{if $isMobile != 'mobile'}
				{l s='Register' mod='tptnheaderlinks'}</a>
			{/if}
		</li>
	{/if}
</ul>
</div>