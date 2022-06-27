{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

		{if !$content_only}
				</div>
			</div>
		<!-- Footer -->
			<footer id="footer">
				<section class="footer_top">
					<div class="row clearfix">
						{$HOOK_FOOTER}
						{if $PS_ALLOW_MOBILE_DEVICE}
							<p class="center clearBoth"><a href="{$link->getPageLink('index', true)}?mobile_theme_ok">{l s='Browse the mobile site'}</a></p>
						{/if}
					</div>
				</section>
				<section class="footer_bottom row">
					<div id="footer_bottom-content">
<!--						<div class="payment-icon">
							<div id="payment-icon"> <img src="{$img_dir}payment.png"/></div>
						</div>-->
						<div class="copyright_txt">&copy;Copyright Motogoodeal</div>
						<div class="copyright_txt">Designed and handcrafted by <a style="color:#fff;"href="http://www.details.ch" target="_blank">details.ch</a></div>
					</div>
				</section>
			</footer>
		</div>
	</div>	
	{/if}
	</body>

</html>
