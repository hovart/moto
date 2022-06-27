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
	{if !isset($content_only) || !$content_only}
						
					</div> <!-- #center_column -->
				{if $page_name!='index'}
				</div>
				</div>
				{/if}
			</div> <!-- #columns -->
			<div id="topFooter">
				<div class="container">
					<div class="row">
						{hook h=TopFooter}
					</div>
				</div>
			</div>
			{if isset($HOOK_FOOTER)}

					{hook h=StickyFooter}


			<!-- Footer -->			
			<footer id="footer">
				<div class="footer_top">
					<div class="container">
					<div class="row">
						{$HOOK_FOOTER}
					</div>
					</div>
				</div>
				<div class="footer_bottom">
					<div class="container">
					<div class="row">
						<div class="copyright_txt col-xs-12 col-md-12">©Copyright Motogoodeal <bR>Designed and handcrafted by <a href="http://details.ch" alt="Details.ch - Agence de communication digitale - Genève">details.ch</a></div>

					</div>
					</div>
				</div>
			</footer>
			{/if}
			
		</div> <!-- #page -->
		{hook h=modalHook}
	{/if}

	{include file="$tpl_dir./global.tpl"}

	</body>
</html>