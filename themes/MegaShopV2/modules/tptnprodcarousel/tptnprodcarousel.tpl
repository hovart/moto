<!-- Featured products -->
{if isset($show_featured_prod) AND $show_featured_prod}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{l s='Featured products' mod='tptnprodcarousel'}</span></h4>
	{if isset($featured_products) AND $featured_products}
		<div class="ftslides prodlist">
		{foreach from=$featured_products item=product name=homeFeaturedProducts}
			{include file="$self/tptnprodcarousel-list.tpl"}
		{/foreach}
		</div>
	{else}
		<p>{l s='No featured products' mod='tptnprodcarousel'}</p>
	{/if}
</div>
{/if}

{if isset($myprod1) AND $myprod1}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname1|escape:html:'UTF-8'}</span></h4>
	<div class="categ1slides prodlist">
	{foreach from=$myprod1 item=product name=myProducts1}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod2) AND $myprod2}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname2|escape:html:'UTF-8'}</span></h4>
	<div class="categ2slides prodlist">
	{foreach from=$myprod2 item=product name=myProducts2}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod3) AND $myprod3}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname3|escape:html:'UTF-8'}</span></h4>
	<div class="categ3slides prodlist">
	{foreach from=$myprod3 item=product name=myProducts3}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod4) AND $myprod4}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname4|escape:html:'UTF-8'}</span></h4>
	<div class="categ4slides prodlist">
	{foreach from=$myprod4 item=product name=myProducts4}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod5) AND $myprod5}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname5|escape:html:'UTF-8'}</span></h4>
	<div class="categ5slides prodlist">
	{foreach from=$myprod5 item=product name=myProducts5}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod6) AND $myprod6}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname6|escape:html:'UTF-8'}</span></h4>
	<div class="categ6slides prodlist">
	{foreach from=$myprod6 item=product name=myProducts6}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod7) AND $myprod7}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname7|escape:html:'UTF-8'}</span></h4>
	<div class="categ7slides prodlist">
	{foreach from=$myprod7 item=product name=myProducts7}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod8) AND $myprod8}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname8|escape:html:'UTF-8'}</span></h4>
	<div class="categ8slides prodlist">
	{foreach from=$myprod8 item=product name=myProducts8}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod9) AND $myprod9}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname9|escape:html:'UTF-8'}</span></h4>
	<div class="categ9slides prodlist">
	{foreach from=$myprod9 item=product name=myProducts9}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}

{if isset($myprod10) AND $myprod10}
<div class="tptncarousel clearfix">
	<h4 class="title_block"><span>{$categname10|escape:html:'UTF-8'}</span></h4>
	<div class="categ10slides prodlist">
	{foreach from=$myprod10 item=product name=myProducts10}
		{include file="$self/tptnprodcarousel-list.tpl"}
	{/foreach}
	</div>
</div>
{/if}