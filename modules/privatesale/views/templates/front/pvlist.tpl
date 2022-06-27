<center><h1>{l s='List of Private Sales' mod='privatesale'}</h1></center>

{if isset($register)}
	<center><p>
	{if $register == 1}
		<font color="GREEN">{l s='You are now registered to the sale' mod='privatesale'}.</font>
	{else}
		<font color="RED">{l s='Error while registering to the sale' mod='privatesale'}.</font>
	{/if}
	</p></center>
{/if}

<ul id="product_list" class="clear" style="margin-top:0px;">
{if isset($pvs_list) && !empty($pvs_list)}
{foreach from=$pvs_list item=temp}
	<li class="ajax_block_product clearfix">
		<div class="center_block">
			<a class="product_img_link" title="{$temp.name}">
				<img width="129" height="129" alt="{$temp.name}" src="{$temp.file_exists}" />
			</a>
			<h3>
				<a title="{$temp.name}">{$temp.name}</a>
			</h3>
			<p class="product_desc">
				<a title="{$temp.description}">{$temp.description}</a>
			</p>
			<p>
				{l s='Start' mod='privatesale'} : {$temp.time_start|truncate:16:"":true}<br /> 
				{l s='Finish' mod='privatesale'} : {$temp.time_end|truncate:16:"":true}<br />
			</p>
		</div>
		<div class="right_block">
			<div>
				{if $temp.status == 1}
					<span style="display:inline; color:orange; font-size:10pt;">{l s='COMING SOON' mod='privatesale'}</span><br />
				{elseif $temp.status == 2}
					<span style="display:inline; color:green; font-size:10pt;">{l s='OPEN' mod='privatesale'}</span><br />
				{else}
					<span style="display:inline; color:red; font-size:10pt;">{l s='CLOSED' mod='privatesale'}</span><br />
				{/if}
			</div>
			<div style="margin-top:85px;">
				{if ($temp.status == 1 || $temp.status == 2) && $temp.access == 0}
					<a class="button" title="{l s='Register' mod='privatesale'}" href="{$temp.register}" rel="ajax_id_product_1">{l s='Register' mod='privatesale'}</a>					
				{elseif $temp.access == 1}
					{if $temp.status == 2}
						<a class="button exclusive" title="{l s='Access to the sale' mod='privatesale'}" href="{$temp.link}" rel="ajax_id_product_1">{l s='Access to the sale' mod='privatesale'}</a>
					{elseif $temp.status == 1}
						{l s='Waiting for the opening of the sale' mod='privatesale'}
					{/if}
				{/if}
			</div>
		</div>
	</li>
{/foreach}
{elseif !isset($pv_cookie->id_customer)}
	{l s='You must to be connected' mod='privatesale'}
{else}
	{l s='No private sales' mod='privatesale'}
{/if}

</ul>