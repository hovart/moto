<div id="footer_block1" class="block">
	<span class="title_block">{l s='Custom Block' mod='tptnfooterblock1'}</span>
	<a class="toggler"></a>
	<ul>
		{foreach from=$tptn_footerblock1 item=info}
			<li><a href="{$info.url|escape:html:'UTF-8'}" title="{$info.text|escape:html:'UTF-8'}">{$info.text|escape:html:'UTF-8'}</a></li>
		{/foreach}
	</ul>
</div>