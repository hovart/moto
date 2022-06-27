<div id="pvsale">
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
			<h3>
				<div title="{$temp.name}">{$temp.name}</div>
			</h3>
				<div class = "state">
					{if $temp.status == 1}
						<span style="display:inline; color:orange;">{l s='COMING SOON' mod='privatesale'}</span><br />
					{elseif $temp.status == 2}
						<span style="display:inline; color:green;">{l s='OPEN' mod='privatesale'}</span><br />
					{else}
						<span style="display:inline; color:red;">{l s='CLOSED' mod='privatesale'}</span><br />
					{/if}
				</div>

			<div class="product_img_link" title="{$temp.name}">
				<img width="129" height="129" alt="{$temp.name}" src="{$temp.file_exists}" />
			</div>
			<div class="product_desc">
				<div title="{$temp.description}">{$temp.description}</div>
			</div>

		</div>
		<div class="right_block">

			<div>

				<div class="date">
					{l s='Start' mod='privatesale'} : {$temp.time_start|truncate:16:"":true}<br /> 
					{l s='Finish' mod='privatesale'} : {$temp.time_end|truncate:16:"":true}<br />
				</div>



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
	
	<div class="connection-message">{l s='You must to be connected' mod='privatesale'}</div>


<div class="block">
  <form action="http://www.motogoodeal.ch/fr/module/privatesale/pvlist?controller=authentication&back=http://www.motogoodeal.ch/fr/module/privatesale/pvlist" method="post" id="login_form" class="std">
    <p class="title_block" style="margin-bottom: 10px;">Login</p>
    <div class="form_content clearfix">
        <p class="text">
            <label for="email">Email address</label>
            <span><input type="text" id="email" name="email" value="" class="account_input" style="width: 192px;" /></span>
        </p>
        <p class="text">
            <label for="passwd">Password</label>
            <span><input type="password" id="passwd" name="passwd" value="" class="account_input" style="width: 192px;" /></span>
        </p>
        <p class="submit">
            <input type="hidden" class="hidden" name="back" value="my-account" /> <input type="submit" id="SubmitLogin" name="SubmitLogin" class="button" value="Log in" />
        </p>
    </div>
  </form>
</div>



{else}
	{l s='No private sales' mod='privatesale'}
{/if}

</ul>
</div>