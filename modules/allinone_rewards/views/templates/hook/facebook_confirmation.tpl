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
<script type="text/javascript">
//<![CDATA[
	var url_allinone_facebook="{$link->getModuleLink('allinone_rewards', 'facebook', [], true)|escape:'strval'}";
//]]>
</script>
<div id="rewards_facebookconfirm">
	{$facebook_confirm_txt|escape:'strval'}
	{if $facebook_code}
	<center>{l s='Code :' mod='allinone_rewards'} <span id="rewards_facebookcode"></span></center>
	{/if}
</div>
<!-- END : MODULE allinone_rewards -->