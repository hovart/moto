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
<p id="loyalty" class="align_justify">
	{if $credits}
		{l s='By buying this product you can collect up to' mod='allinone_rewards'} <b><span id="loyalty_points">{displayPrice price=$credits|floatval}</span></b> {l s=' with our loyalty program.' mod='allinone_rewards'}
		{l s='Your cart will total' mod='allinone_rewards'} <b><span id="total_loyalty_points">{displayPrice price=$total_credits|floatval}</span></b> {l s='that can be converted into a voucher.' mod='allinone_rewards'}
	{else}
		{if isset($no_pts_discounted) && $no_pts_discounted == 1}
			{l s='No reward credits for this product because there\'s already a discount.' mod='allinone_rewards'}
		{else}
			{l s='Your basket must contain at least %s of products in order to get loyalty rewards.' sprintf={displayPrice price=$minimum|floatval} mod='allinone_rewards'}
		{/if}
	{/if}
</p>
<br class="clear" />
<!-- END : MODULE allinone_rewards -->