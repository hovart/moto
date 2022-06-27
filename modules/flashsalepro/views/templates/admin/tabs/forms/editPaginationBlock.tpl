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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
{if $page_count > 1}
	<center>
		<nav>
			<ul class="pagination">
				<li onclick="itemPaginationPreviousNext('previous');">
					<a href="#" aria-label="{l s='Previous' mod='flashsalepro'}">
					<span aria-hidden="true">&laquo;</span>
					</a>
				</li>
				{for $foo=1 to $page_count}
					<li id="{$foo|escape:'htmlall':'UTF-8'}_pagination" onclick="itemPaginationChange({$foo|escape:'intval'});" class="pagination-bar"><a href="#">{$foo|escape:'htmlall':'UTF-8'}</a></li>
				{/for}
				<li onclick="itemPaginationPreviousNext('next');">
					<a href="#" aria-label="Next">
					<span aria-hidden="{l s='Next' mod='flashsalepro'}">&raquo;</span>
					</a>
				</li>
			</ul>
		</nav>
	</center>
	<input type="hidden" id="current_active_pagination_index" name="current_active_pagination_index" value=1>
	<input type="hidden" id="max_pagination_index" name="max_pagination_index" value={$page_count|escape:'htmlall':'UTF-8'}>
{/if}