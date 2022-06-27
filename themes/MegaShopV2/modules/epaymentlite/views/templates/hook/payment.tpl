{*
** Creator   : WDXperience SARL : YM (120612)
** Copyright : All Right Reserved - Licence available for 1 shop
** Licence   : Prices and Conditions on http://www.wdxperience.ch/shop/
** Compat    : Prestashop v1.5
*}

<p class="payment_module">
	<a href="javascript:javascript:document.epaymentformlite.submit();" style="overflow: auto;">
		<span class="postfinance" style="display: block; float: left; width: 100%; height: 40px;">
			<span class="txt-postfiance">
			 {l s='Pay with your PostCard or PostFinance e-Finance' mod='epaymentlite'}
			</span>
		<img class="logo-postfinance" src="{$this_path}img/postfinance.jpg" alt="Postfinance" /></span>
		<img class="logo-visa" src="{$this_path}img/visa.png" alt="Visa" /></span>
		<img class="logo-mastercard" src="{$this_path}img/mastercard.png" alt="Mastercard" /></span>
		<img class="logo-paypal" src="{$this_path}img/paypal.png" alt="Paypal" /></span>
		<img class="logo-postcard" src="{$this_path}img/postcard-postfinance.png" alt="Postcard" /></span>
		<span class="go-payment">
		 {l s='Confirm order' mod='epaymentlite'}
		</span>
	</a>
</p>

<form method="post" action="{$psp_url}" id="epaymentformlite" name="epaymentformlite">
<p>
{foreach from=$parametres item=parametre}
	<input type="hidden" id="{$parametre.name}"  name="{$parametre.name}" value="{$parametre.value}" />
{/foreach}
	<input type="hidden" id="SHASign" name="SHASign" value="{$shacode}" />
</p>
</form>
