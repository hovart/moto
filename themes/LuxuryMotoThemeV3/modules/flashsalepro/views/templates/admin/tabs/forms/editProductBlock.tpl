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

<div id="edit_item_{$id_product|escape:'htmlall':'UTF-8'}" class="col-sm-12 col-md-4 col-lg-3">
    <center>
    <img src="{$image_link|escape:'htmlall':'UTF-8'}" class="product-block-img_edit">
        <p><b>{$name|escape:'htmlall':'UTF-8'}</b></p>
        <span class="price-percent-reduction discount-figure">-&nbsp;{$item.discount|escape:'htmlall':'UTF-8'}{if $item.discount_type eq "percentage"}%{else}{$currency_symbol|escape:'htmlall':'UTF-8'}{/if}</span>
        <div class="clear">&nbsp;</div>
        <i class="icon-trash icon-button" onclick="editSaleRemoveItem({$id_product|escape:'intval'}, {$item.id_specific_price|escape:'intval'}, edit_remove_product_title, edit_remove_product_text, edit_remove_product_confirmButtonText, edit_remove_product_cancelButtonText, edit_remove_product_confirmDelete, edit_remove_product_confirmDeleteMsg, edit_remove_product_confirmCancel, edit_remove_product_confirmCancelMsg);"></i>
    </center>
</div>
{literal}
<script>
var edit_remove_product_title = "{/literal}{l s='Are you sure?' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_text = "{/literal}{l s='You will not be able to recover this flash sale product!' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_confirmButtonText = "{/literal}{l s='Yes, delete it!' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_cancelButtonText = "{/literal}{l s='No, cancel!' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_confirmDelete = "{/literal}{l s='Deleted!' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_confirmDeleteMsg = "{/literal}{l s='This product has been deleted from the sale.' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_confirmCancel = "{/literal}{l s='Cancelled' mod='flashsalepro' js=1}{literal}";
var edit_remove_product_confirmCancelMsg = "{/literal}{l s='You have not deleted this product from your flash sale.' mod='flashsalepro' js=1}{literal}";
</script>
{/literal}