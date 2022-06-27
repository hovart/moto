{*
* All-in-one Rewards Module
*
* @category  Prestashop
* @category  Module
* @author    Yann BONNAILLIE - ByWEB
* @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
* @license   Commercial license see license.txt
* Support by mail  : contact@prestaplugins.com
* Support on forum : Patanock
* Support on Skype : Patanock13
*}
<!-- MODULE allinone_rewards -->
<p id="loyalty">
	{if $credits > 0}
		{l s='By checking out of this shopping cart you can collect up to' mod='allinone_rewards'} <b>{displayPrice price=$credits|floatval}</b>
		{l s='that can be converted into a voucher' mod='allinone_rewards'}{if isset($guest_checkout) && $guest_checkout}<sup>*</sup>{/if}.<br />
		{if isset($guest_checkout) && $guest_checkout}<sup>*</sup> {l s='Not available for Instant checkout order' mod='allinone_rewards'}{/if}
	{else}
		{l s='Add some products to your shopping cart to collect some loyalty credits.' mod='allinone_rewards'}
	{/if}
</p>
<!-- END : MODULE allinone_rewards -->