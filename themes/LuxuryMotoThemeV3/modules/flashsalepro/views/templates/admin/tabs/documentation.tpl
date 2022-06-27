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
<h3><i class="icon-book"></i> {l s='Documentation' mod='flashsalepro'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small></h3>
<div class="media">
        
        <p>{l s='Access to Prestashops free documentation:' mod='flashsalepro'}</p>
        <ul style='list-style-type:circle;'>
            <li><a href="http://doc.prestashop.com/dashboard.action" target="_blank"> http://doc.prestashop.com/dashboard.action</a></li>
        </ul>
        <p>{l s='Need help? Click the "contact" tab' mod='flashsalepro'}</p>

        <div class="well" id="flash_sale_info">
        	<h3>{l s='Flash Sale Information' mod='flashsalepro'}</h3>
        	<ul>
				<li>{l s='Only one Flash Sale can be activated for a group, currency or country. Multiple Flash Sales can be activated if the group, currency and country are different for each Flash Sale.' mod='flashsalepro'}</li>
				<li>{l s='For a timed Flash Sale to be activated automatically (the start date has passed), all other Flash Sales must be inactive.' mod='flashsalepro'}<br />
					{l s='If there are 2 timed Flash Sales that are inactive, the earliest Flash Sale will be activated.' mod='flashsalepro'}<br />
					{l s='If other Flash Sales are active, timed sales can be activated by clicking the X in the table, like all the other Flash Sale types.' mod='flashsalepro'}</li>
			</ul>
        </div>
        <div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="timed_video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/5jqpQByK77I" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
</div>