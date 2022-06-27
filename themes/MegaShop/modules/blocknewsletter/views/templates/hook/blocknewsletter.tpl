{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}



<div id="newsletter_block_footer" class="block">
	<span class="title_block">{l s='Newsletter' mod='blocknewsletter'}</span>
	<a class="toggler"></a>
	<ul>
		<!--<li>{l s='Subscribe to our newsletter and receive the latest offers, discounts and updates' mod='blocknewsletter'}</li>
		{if isset($msg) && $msg}
			<p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
		{/if}-->
		<li>
			<form action="{$link->getPageLink('index')|escape:'html'}" method="post">

                <!-- offre inscription newsletter-->
                {if isset($msg) && $msg}
                    {if !$nw_error}
                        <div class="bon-reduc-txt">{l s='Voici votre code de réduction ' mod='blocknewsletter'} : <div class="bon-reduc">MGDNL20</div></div>
                    {/if}
                {else}
                    <div id="nl-img"> <img src="{$img_dir}/gift.png" title="{l s='Un bon de réduction pour les nouveaux inscrits'}" /></div>
                    <div id="nl-txt">
                    <p style="padding:0 5px 10px 5px;"><img src="{$img_ps_dir}/admin/gold.gif"/> {l s='Inscrivez-vous à la newsletter et recevez un bon de réduction de 20CHF à partir de 100CHF' mod='blocknewsletter'}</p></div>
                {/if}
                <!-- offre inscription newsletter-->

				<input class="inputNew" id="newsletter-input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{else}{l s='your e-mail' mod='blocknewsletter'}{/if}" />
				<input type="submit" value="GO" class="button_mini" name="submitNewsletter" />
				<input type="hidden" name="action" value="0" />
			</form>
		</li>	
	</ul>
</div>
<!-- /Block Newsletter module-->

<script type="text/javascript">
    var placeholder = "{l s='your e-mail' mod='blocknewsletter' js=1}";
    {literal}
        $(document).ready(function() {
            $('#newsletter-input').on({
                focus: function() {
                    if ($(this).val() == placeholder) {
                        $(this).val('');
                    }
                },
                blur: function() {
                    if ($(this).val() == '') {
                        $(this).val(placeholder);
                    }
                }
            });
        });
    {/literal}
</script>