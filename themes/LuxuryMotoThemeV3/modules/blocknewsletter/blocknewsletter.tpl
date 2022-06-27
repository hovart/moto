{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- Block Newsletter module-->
<section id="newsletter_block_left" class="footer-block col-xs-12 col-md-4">
	<h4>Newsletter</h4>
	<!--<p class="lead">{l s='Subscribe to our newsletter and receive the latest offers, discounts and updates' mod='blocknewsletter'}</p>-->

    		<form action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
    			<div class="form-group{if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
					<!-- offre inscription newsletter-->
					{if isset($msg) && $msg}
						{if !$nw_error}
							<div class="bon-reduc-txt">{l s='Voici votre code de réduction ' mod='blocknewsletter'} : <div class="bon-reduc">MGDNL5</div></div>
						{/if}
					{else}
						<!-- {if $page_name != 'index'}
                    <div id="nl-img"> <img src="{$img_dir}/gift.png" title="{l s='Un bon de réduction pour les nouveaux inscrits'}" /></div>
                {/if}-->

						<div id="nl-txt">
							<p style="padding:0 5px 10px 5px;"><img src="{$img_ps_dir}/admin/gold.gif"/> {if $lang_iso == fr }
									Inscrivez-vous à la newsletter et recevez un bon de réduction de 5CHF à partir de 100CHF

								{else}

									Abonnieren Sie unsere Newsletter und erhalten Sie einen Gutschein in Höhe von 5 CHF ab 100 CHF Einkauf
								{/if}
							</p></div>
					{/if}
					<!-- offre inscription newsletter-->
    				<input class="inputNew form-control newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="{if isset($msg) && $msg}{$msg}{elseif isset($value) && $value}{$value}{else}{l s='Enter your e-mail' mod='blocknewsletter'}{/if}" />
                    <button type="submit" name="submitNewsletter" class="button"><i class="fa fa-check"></i></button>
    				<input type="hidden" name="action" value="0" />
    			</div>

				<div class="payment-icon">

				</div>
    {hook h="displayBlockNewsletterBottom" from='blocknewsletter'}
			</form>




</section>
<!-- /Block Newsletter module-->
{strip}
{if isset($msg) && $msg}
{addJsDef msg_newsl=$msg|@addcslashes:'\''}
{/if}
{if isset($nw_error)}
{addJsDef nw_error=$nw_error}
{/if}
{addJsDefL name=placeholder_blocknewsletter}{l s='Enter your e-mail' mod='blocknewsletter' js=1}{/addJsDefL}
{if isset($msg) && $msg}
    {addJsDefL name=alert_blocknewsletter}{l s='Newsletter : %1$s' sprintf=$msg js=1 mod="blocknewsletter"}{/addJsDefL}
{/if}
{/strip}
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