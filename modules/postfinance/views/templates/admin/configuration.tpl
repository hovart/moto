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

{if $ps_version == 0}
    <div class="bootstrap">
	{include file="./header.tpl"}
{/if}
	<!-- Module content -->
	<div id="modulecontent" class="clearfix" role="{$is_submit|escape:'htmlall':'UTF-8'}">
		<!-- Nav tabs -->
		<div class="col-lg-2" style="position:fixed">

			<div class="list-group">
				<a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='postfinance'} {$module_version|escape:'htmlall':'UTF-8'}</a>
				<a href="#documentation" class="list-group-item active" data-toggle="tab"><i class="icon-book"></i> {l s='Documentation' mod='postfinance'}</a>
				<a href="#config" class="list-group-item" data-toggle="tab"><i class="icon-briefcase"></i> {l s='Configuration' mod='postfinance'}</a>
				<a href="#contacts" class="list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Contact' mod='postfinance'}</a>
			</div>
		</div>
		<!-- Tab panes -->
		<div class="tab-content col-lg-9" style="position:relative;left:19%">

			<div class="tab-pane active panel" id="documentation">
				{include file="./tabs/documentation.tpl"}
			</div>
   
			<div class="tab-pane panel" id="config">
                {include file="./tabs/config.tpl"}
			</div>
		
			{include file="./addons.tpl"}
		</div>
	</div>

{if $ps_version == 0}
	<!-- Manage translations -->
	{include file="./translations.tpl"}
</div>
{/if}
