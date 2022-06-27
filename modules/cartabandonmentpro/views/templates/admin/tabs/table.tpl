{*
* 2007-2011 PrestaShop 
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
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="product_table" class="product_table">
    <table id='productTable' cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th>{l s='Shop Name' mod='cartabandonmentpro'}</th>
                <th>{l s='Selected Products' mod='cartabandonmentpro'}</th>
                <th>{l s='Retail Price' mod='cartabandonmentpro'}</th>
                <th>{l s='Discount' mod='cartabandonmentpro'}</th>
                <th>{l s='Final Price' mod='cartabandonmentpro'}</th>
                <th>{l s='Discount Name' mod='cartabandonmentpro'}</th>
                <th>{l s='Priority' mod='cartabandonmentpro'}</th>
                <th>{l s='Active' mod='cartabandonmentpro'}</th>
                <th>{l s='Delete' mod='cartabandonmentpro'}</th>
            </tr>
        </thead>
        <tbody id="table_body">
            {$productTable|escape:'htmlall':'UTF-8':'UTF-8'}
        </tbody>
    </table>
</div>    