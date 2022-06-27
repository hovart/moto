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
    <img class="image3_ok" src="modules/blocklateralreinsurance/img/ok.png" width="32" height="32" alt="Garanties"> 
	<style="float:right;"</style> 
    <p> {l s='warranty' mod='blocklateralreinsurance'} </p>
	<ul class="width{$nbblocks}">	
		{foreach from=$infos item=info}
         {if $info.id_lateralreinsurance==1}{$url="index.php?id_cms=4&controller=cms"}{/if}
		 {if $info.id_lateralreinsurance==2}{$url="index.php?id_cms=2&controller=cms"}{/if}
		 {if $info.id_lateralreinsurance==3}{$url="index.php?id_cms=3&controller=cms"}{/if}
		 {if $info.id_lateralreinsurance==4}{$url="index.php?id_cms=1&controller=cms"}{/if}
		 {if $info.id_lateralreinsurance==5}{$url="index.php?id_cms=5&controller=cms"}{/if}
		
		 <li><a href="{$url}"><img src="{$module_dir}img/{$info.file_name}" alt="{$info.text|escape:html:'UTF-8'}" /> <span>{$info.text|escape:html:'UTF-8'}</span></a></li>
  {/foreach}
			
	</ul>
</div>
<!-- /MODULE Block lateralreinsurance -->
{/if}