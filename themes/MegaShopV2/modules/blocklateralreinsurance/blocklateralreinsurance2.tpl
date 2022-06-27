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
{if $infos|@count > 0}
<!-- MODULE Block lateralreinsurance -->
<div id="lateralreinsurance3_block" class="clearfix">
	<ul class="width{$nbblocks}">	
		{foreach from=$infos item=info}
			
			{if $info.id_lateralreinsurance==1}{$url="index.php?id_cms=3&controller=cms"}{/if}
			{if $info.id_lateralreinsurance==2}{$url="index.php?id_cms=3&controller=cms"}{/if}
			{if $info.id_lateralreinsurance==3}{$url="index.php?id_cms=3&controller=cms"}{/if}
			{if $info.id_lateralreinsurance==4}{$url="index.php?id_cms=3&controller=cms"}{/if}
			{if $info.id_lateralreinsurance==5}{$url="index.php?id_cms=3&controller=cms"}{/if}
			
			<!-- if you want to use specific url for each item remove next line and fill in the preceding lines -->
			{$url="index.php?id_cms_category=2&controller=cms"}
			
			<li>
			<a href="{$url}">
				<div><img src="{$theme_dir}modules/blocklateralreinsurance/img/{$info.file_name}" alt="{$info.text|escape:html:'UTF-8'}" /></div>
				<div class="reinsurance-text"><span>{$info.text|escape:html:'UTF-8'}</span></div>
			</a>
			</li>
		{/foreach}
	</ul>
</div>
<!-- /MODULE Block lateralreinsurance -->
{/if}