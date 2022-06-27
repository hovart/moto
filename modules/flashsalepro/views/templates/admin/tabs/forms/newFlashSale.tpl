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

<div class="modal fade" id="new_flash_sale_modal" tabindex="-1" role="dialog" aria-labelledby="contract_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">                                      
        <form role="form" class="form-inline" enctype="multipart/form-data" action="{$requestUri|escape:'htmlall':'UTF-8'}" method="post" id="newFlashSaleForm" name="newFlashSaleForm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #428bca;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="contract_modal_title" style="color: #fff;">{l s='Create New Flash Sale' mod='flashsalepro'}</h4>
            </div>
            <div class="modal-body">
                <div class="progress" id="flash_sale_progress_1">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:25%">
                        {l s='Step 1/4' mod='flashsalepro'}
                    </div>
                </div>
                <div class="progress" id="flash_sale_progress_2" style="display:none;">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                        {l s='Step 2/4' mod='flashsalepro'}
                    </div>
                </div>
                <div class="progress" id="flash_sale_progress_3" style="display:none;">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%">
                        {l s='Step 3/4' mod='flashsalepro'}
                    </div>
                </div>
                <div class="progress" id="flash_sale_progress_4" style="display:none;">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        {l s='Step 4/4' mod='flashsalepro'}
                    </div>
                </div>
                <div class="tab-pane" id="flash_sale_information_div">
                    {include file="./flashSaleInformationTab.tpl"}
                </div>
                <div class="tab-pane" id="flash_sale_design_div">
                    {include file="./flashSaleDesignTab.tpl"}
                </div>
                <div class="tab-pane" id="flash_sale_configuration_div">
                    {include file="./flashSaleConfigurationTab.tpl"}
                </div> 
                <div class="tab-pane" id="flash_sale_confirmation_div">
                    {include file="./flashSaleConfirmationTab.tpl"}
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <input name="removed_products_array" id="removed_products_array" type="hidden" value="" />
                <input name="submittedFormCheck" id="submittedFormCheck" type="hidden" value="check" />
                <button style="float:left;" class="btn btn-default" type="button" onClick="step3();" id="back_step3"><i class="icon-arrow-circle-left"></i>&nbsp;&nbsp;{l s="Back" mod='flashsalepro'}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="process-icon-cancel"></i>{l s='Close' mod='flashsalepro'}</button>
                <button type="button" class="btn btn-primary" name="submitFlashSale" onclick="submitCreateFlashSale();"><i class="process-icon-save"></i>{l s='Save' mod='flashsalepro'}</button>
            </div> 
        </form>
        </div>
    </div>
</div>

{literal}
<script>

</script>
{/literal}