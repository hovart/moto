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
	{foreach $flash_sale_info item=foo}
		<tr id="flash_sale_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}">
			<td><i style="cursor:pointer;" class="icon-eye icon-2x" onclick="showFlashSaleItemTable({$foo.id_flashsalespro|escape:'intval'});"></i></td>
			<td>{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}</td>
			<td>{$foo.name}</td>
			
			<td id="flash_sale_start_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}"><span id="flash_sale_start_date_span_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}">{$foo.date_start}</span>{if $foo.sale_type eq "timed"}<i style="cursor:pointer;float:right;" class="icon-edit" onclick="updateDateStart({$foo.id_flashsalespro|escape:'intval'}, '{l s='Update' mod='flashsalepro'}', '{l s='New Start of Sale Time' mod='flashsalepro'}  {$foo.name|escape:'htmlall':'UTF-8'}');"></i>{/if}</td>

			<td id="flash_sale_end_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}"><span id="flash_sale_end_date_span_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}">{$foo.date_end|escape:'htmlall':'UTF-8'}</span>{if $foo.sale_type eq "timed"}<i style="cursor:pointer;float:right;" class="icon-edit" onclick="updateDateEnd({$foo.id_flashsalespro|escape:'intval'}, '{l s='Update' mod='flashsalepro'}', '{l s='New End of Sale Time' mod='flashsalepro'}  {$foo.name|escape:'htmlall':'UTF-8'}');"></i>{/if}</td>
			<td id="flash_sale_status_{$foo.id_flashsalespro|escape:'htmlall':'UTF-8'}">
				{if $foo.active eq 1}
					<span class="list-action-enable action-enabled" style="cursor:pointer;" onclick="deactivateFlashSale({$foo.id_flashsalespro|escape:'intval'});">
						<i class="icon-check"></i>
					</span>
				{else}
					<span class="list-action-enable action-disabled" style="cursor:pointer;" onclick="activateFlashSale({$foo.id_flashsalespro|escape:'intval'});">
						<i class="icon-remove"></i>
					</span>
				{/if}
			</td>
			<td>{if $foo.sale_type eq "timed"}{l s='Timed' mod='flashsalepro'}{/if}{if $foo.sale_type eq "manual"}{l s='Manual' mod='flashsalepro'}{/if}{if $foo.sale_type eq "stock"}{l s='Stock Dependent' mod='flashsalepro'}{/if}</td>
			<td>{$foo.group_name|escape:'htmlall':'UTF-8'}</td>
			<td>{$foo.country_name|escape:'htmlall':'UTF-8'}</td>
			<td>{$foo.currency_name|escape:'htmlall':'UTF-8'}</td>
			<td>
				<center>
					<i style="cursor:pointer; padding: 2px;" class="icon-trash icon-2x" onclick="burnItAll({$foo.id_flashsalespro|escape:'intval'}, title, text, confirmButtonText, cancelButtonText, confirmDelete, confirmDeleteMsg, confirmCancel, confirmCancelMsg);"></i>
					<i style="cursor:pointer; padding: 2px;" class="icon-edit" onclick="editFlashSale({$foo.id_flashsalespro|escape:'intval'});"></i>
				</center>
			</td>
		</tr>
	{/foreach}
{literal}
<script>

var title = "{/literal}{l s='Are you sure?' mod='flashsalepro' js=1}{literal}";
var text = "{/literal}{l s='You will not be able to recover this flash sale!' mod='flashsalepro' js=1}{literal}";
var confirmButtonText = "{/literal}{l s='Yes, delete it!' mod='flashsalepro' js=1}{literal}";
var cancelButtonText = "{/literal}{l s='No, cancel!' mod='flashsalepro' js=1}{literal}";
var confirmDelete = "{/literal}{l s='Deleted!' mod='flashsalepro' js=1}{literal}";
var confirmDeleteMsg = "{/literal}{l s='Your flash sale has been deleted.' mod='flashsalepro' js=1}{literal}";
var confirmCancel = "{/literal}{l s='Cancelled' mod='flashsalepro' js=1}{literal}";
var confirmCancelMsg = "{/literal}{l s='You have not deleted your flash sale.' mod='flashsalepro' js=1}{literal}";


</script>
{/literal}