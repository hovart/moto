<div id="tptncontact" class="block">
	<span class="title_block">{l s='Contact us' mod='tptncontact'}</span>
	<a class="toggler"></a>
	<ul>
		<li>
			<p>{$tptncontact_address1|escape:'htmlall':'UTF-8'}</p>
			<p>{$tptncontact_address2|escape:'htmlall':'UTF-8'}</p>
			<p class="lastline">{$tptncontact_address3|escape:'htmlall':'UTF-8'}</p>
		</li>
		{if $tptncontact_phone != ''}<li>{$tptncontact_phone|escape:'htmlall':'UTF-8'}</li>{/if}
		{if $tptncontact_email != ''}<li>{mailto address=$tptncontact_email|escape:'htmlall':'UTF-8' encode="hex"}</li>{/if}
	</ul>
	<ul class="social">
		{if $tptncontact_facebook != ''}<li class="facebook"><a href="{$tptncontact_facebook|escape:html:'UTF-8'}" title="Facebook"></a></li>{/if}
		{if $tptncontact_twitter != ''}<li class="twitter"><a href="{$tptncontact_twitter|escape:html:'UTF-8'}" title="Twitter"></a></li>{/if}
		{if $tptncontact_pinterest != ''}<li class="pinterest"><a href="{$tptncontact_pinterest|escape:html:'UTF-8'}" title="Pinterest"></a></li>{/if}
		{if $tptncontact_google != ''}<li class="google"><a href="{$tptncontact_google|escape:html:'UTF-8'}" title="google"></a></li>{/if}
		{if $tptncontact_linkedin != ''}<li class="linkedin"><a href="{$tptncontact_linkedin|escape:html:'UTF-8'}" title="linkedin"></a></li>{/if}
		{if $tptncontact_youtube != ''}<li class="youtube"><a href="{$tptncontact_youtube|escape:html:'UTF-8'}" title="Youtube"></a></li>{/if}
	</ul>
</div>
