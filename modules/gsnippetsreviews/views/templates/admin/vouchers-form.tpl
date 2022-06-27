{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{* ENABLE VOUCHER *}
<div class="form-group">
	<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
		<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, this will allow you to configure your vouchers to reward the customer for having' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posted' mod='gsnippetsreviews'}{else}{l s='shared' mod='gsnippetsreviews'}{/if} {l s='a comment' mod='gsnippetsreviews'}">
			<strong>{l s='Offer a voucher for' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posting' mod='gsnippetsreviews'}{else}{l s='sharing' mod='gsnippetsreviews'}{/if} {l s='a review' mod='gsnippetsreviews'}</strong>
		</span> :
	</label>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}" id="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}_on" value="1" {if !empty($aEnableVouchers[$type])}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-voucher-{$type|escape:'htmlall':'UTF-8'}', null, null, true, true);"  />
				<label for="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}_on" class="radioCheck">
					{l s='Yes' mod='gsnippetsreviews'}
				</label>
				<input type="radio" name="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}" id="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}_off" value="0" {if empty($aEnableVouchers[$type])}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-voucher-{$type|escape:'htmlall':'UTF-8'}', null, null, true, false);" />
				<label for="bt_enable-voucher_{$type|escape:'htmlall':'UTF-8'}_off" class="radioCheck">
					{l s='No' mod='gsnippetsreviews'}
				</label>
				<a class="slide-button btn"></a>
			</span>
		<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If activated, this will allow you to configure your vouchers to reward the customer for having' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posted' mod='gsnippetsreviews'}{else}{l s='shared' mod='gsnippetsreviews'}{/if} {l s='a comment' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
	</div>
</div>

{* IF ENABLE VOUCHER *}
<div id="bt_div-voucher-{$type|escape:'htmlall':'UTF-8'}" style="display: {if !empty($aEnableVouchers[$type])}block{else}none{/if};">
	{* VOUCHER PREFIX CODE *}
	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The voucher\'s code prefix. It must be at least 3 characters long. It will be used for the first part of the voucher code which the customer will type in during check-out to receive his discount' mod='gsnippetsreviews'}">
				<strong>{l s='Code' mod='gsnippetsreviews'}</strong>
			</span> :
		</label>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
				<input type="text" size="5" maxlength="5" name="bt_prefix-code[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].prefixCode)}{$aVouchers[$type].prefixCode|escape:'htmlall':'UTF-8'}{else}GSR{/if}" id="bt_prefix-code[{$type|escape:'htmlall':'UTF-8'}]" />
			</div>
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The voucher\'s code prefix. It must be at least 3 characters long. It will be used for the first part of the voucher code which the customer will type in during check-out to receive his discount' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
			<div style="clear: both;"></div><span class="help-block"><i class="icon-warning-sign text-primary">&nbsp;</i>{l s='Invalid characters: numbers and' mod='gsnippetsreviews'} {literal}!<>,;?=+()@#"�{}_$%:{/literal}</span>
		</div>
	</div>

	{* VOUCHER'S TYPE *}
	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<strong>{l s='Type' mod='gsnippetsreviews'}</strong> :
		</label>
		<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
			<select name="bt_discount-type[{$type|escape:'htmlall':'UTF-8'}]" id="bt_discount-type_{$type|escape:'htmlall':'UTF-8'}" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<option value="none" {if empty($aVouchers[$type])}selected="selected"{/if}>{l s='None' mod='gsnippetsreviews'}</option>
				<option value="percentage" {if !empty($aVouchers[$type]) && $aVouchers[$type].discountType == 'percentage'}selected="selected"{/if}>{l s='Discount on order (%)' mod='gsnippetsreviews'}</option>
				<option value="amount" {if !empty($aVouchers[$type]) && $aVouchers[$type].discountType == 'amount'}selected="selected"{/if}>{l s='Discount on order (amount)' mod='gsnippetsreviews'}</option>
			</select>
		</div>
	</div>
	{* PERCENT *}
	<div id="bt_apply-discount-percent-div_{$type|escape:'htmlall':'UTF-8'}" class="form-group" style="display: {if !empty($aVouchers[$type]) && $aVouchers[$type].discountType == 'percentage'}block{else}none{/if};">
		<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Either the monetary amount or the %, depending on Type selected above' mod='gsnippetsreviews'}">
				<strong>{l s='Value' mod='gsnippetsreviews'}</strong>
			</span> :
		</label>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
			<div class="input-group col-xs-6 col-sm-6 col-md-6 col-lg-6" style="float: left;">
				<span class="input-group-addon">%</span>
				<input type="text" id="bt_voucher-percent[{$type|escape:"UTF-8"}]" class="input-mini" name="bt_voucher-percent[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].amount)}{$aVouchers[$type].amount|escape:'htmlall':'UTF-8'}{else}0{/if}">
			</div>
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Either the monetary amount or the %, depending on Type selected above' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
			<div style="clear: both;"></div><span class="help-block"><i class="icon-warning-sign text-primary"></i> {l s='Does not apply to the shipping costs' mod='gsnippetsreviews'}</span>
		</div>
	</div>
	{* AMOUNT *}
	<div id="bt_apply-discount-amount-div_{$type|escape:'htmlall':'UTF-8'}" class="form-group" style="display: {if !empty($aVouchers[$type]) && $aVouchers[$type].discountType == 'amount'}block{else}none{/if};">
		<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Either the monetary amount or the %, depending on Type selected above' mod='gsnippetsreviews'}">
				<strong>{l s='Value' mod='gsnippetsreviews'}</strong>
			</span> :
		</label>
		<div class="bt_voucher-amount col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="row fixed-width-xxl" style="float: left;">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<input type="text" id="bt_voucher-amount[{$type|escape:'htmlall':'UTF-8'}]" name="bt_voucher-amount[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].amount)}{$aVouchers[$type].amount|escape:'htmlall':'UTF-8'}{else}0{/if}" onchange="this.value = this.value.replace(/,/g, '.');">
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<select id="bt_currency-id_{$type|escape:'htmlall':'UTF-8'}" name="bt_currency-id[{$type|escape:'htmlall':'UTF-8'}]">
						{foreach from=$aCurrencies name=currency key=iKey item=aCurrency}
							<option value="{$aCurrency.id_currency|intval}" {if !empty($aVouchers[$type].currency) && $aVouchers[$type].currency == $aCurrency.id_currency}selected="selected"{/if} >{$aCurrency.sign|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<select id="id_tax_{$type|escape:'htmlall':'UTF-8'}" name="bt_tax[{$type|escape:'htmlall':'UTF-8'}]">
						<option value="0" {if !empty($aVouchers[$type].tax) && $aVouchers[$type].tax == 0}selected="selected"{/if} >{l s='Tax Excluded' mod='gsnippetsreviews'}</option>
						<option value="1" {if !empty($aVouchers[$type].tax) && $aVouchers[$type].tax == 1}selected="selected"{/if} >{l s='Tax Included' mod='gsnippetsreviews'}</option>
					</select>
				</div>
			</div>
			<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Either the monetary amount or the %, depending on Type selected above' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
		</div>
	</div>

	{* VOUCHER'S DESCRIPTION *}
	<div id="bt_div-features-display{$type|escape:'htmlall':'UTF-8'}">
		<div class="form-group ">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a brief generic description for your voucher code, such as "Voucher for having' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posted' mod='gsnippetsreviews'}{else}{l s='shared' mod='gsnippetsreviews'}{/if} {l s='a review' mod='gsnippetsreviews'}">
					<strong>{l s='Description' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
				{foreach from=$aLangs item=aLang}
					<div id="bt_voucher-desc{$type|escape:'htmlall':'UTF-8'}_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
						<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
							<input type="text" id="bt_tab-voucher-desc_{$type|escape:'htmlall':'UTF-8'}[{$aLang.id_lang|intval}]" name="bt_tab-voucher-desc_{$type|escape:'htmlall':'UTF-8'}[{$aLang.id_lang|intval}]" {if !empty($aVouchers[$type].langs)}{foreach from=$aVouchers[$type].langs key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
						</div>
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
							<ul class="dropdown-menu">
								{foreach from=$aLangs item=aLang}
									<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
								{/foreach}
							</ul>
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a brief generic description for your voucher code, such as "Voucher for having' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posted' mod='gsnippetsreviews'}{else}{l s='shared' mod='gsnippetsreviews'}{/if} {l s='a review' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
						</div>
					</div>
				{/foreach}
			</div>
		</div>

		{* CATEGORY TRRE *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Check all box(es) of categories to which the discount is to be applied. No categories checked will apply the voucher on all of them' mod='gsnippetsreviews'}.">
					<strong>{l s='Categories' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
				<div id="category_block">
					{if $type == 'comment'}
					{$sCategoryTreeComment|escape:'UTF-8'}
					{else}
					{$sCategoryTreeShare|escape:'UTF-8'}
					{/if}
				</div>
			</div>
		</div>

		{* MAXIMUM NUMBER OF VOUCHER *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The maximum number of vouchers a single client can receive for' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posting' mod='gsnippetsreviews'}{else}{l s='sharing' mod='gsnippetsreviews'}{/if} {l s='a review. If you set it to 5, the client will receive a voucher for the first 5 reviews, but any subsequent reviews starting at the 6th will no longer benefit from a voucher. Enter 0 if not applicable.' mod='gsnippetsreviews'}">
					<strong>{l s='Maximum quantity available' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
					<input type="text" id="bt_maximum-qte[{$type|escape:'htmlall':'UTF-8'}]" name="bt_maximum-qte[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].maximumQty)}{$aVouchers[$type].maximumQty|intval}{else}0{/if}" onchange="this.value = this.value.replace(/,/g, '.');">
				</div>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The maximum number of vouchers a single client can receive for' mod='gsnippetsreviews'} {if $type == 'comment'}{l s='posting' mod='gsnippetsreviews'}{else}{l s='sharing' mod='gsnippetsreviews'}{/if} {l s='a review. If you set it to 5, the client will receive a voucher for the first 5 reviews, but any subsequent reviews starting at the 6th will no longer benefit from a voucher. Enter 0 if not applicable.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
			</div>
		</div>

		{* MINIMUM VOUCHER'S AMOUNT *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Minimum amount of the client\'s order for the voucher to be valid. Enter 0 if not applicable' mod='gsnippetsreviews'}">
					<strong>{l s='Minimum order / purchase amount' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
					<input type="text" size="15" id="bt_minimum[{$type|escape:'htmlall':'UTF-8'}]" name="bt_minimum[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].minimum)}{$aVouchers[$type].minimum|intval}{else}0{/if}" onkeyup="javascript:this.value = this.value.replace(/,/g, '.'); " />
				</div>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Minimum amount of the client\'s order for the voucher to be valid. Enter 0 if not applicable' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
			</div>
		</div>

		{* VOUCHER'S VALIDITY *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Numbers of days for availability date. 365 days (1 year) is standard practice' mod='gsnippetsreviews'}">
					<strong>{l s='Validity' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
					<input type="text" size="3" name="bt_validity[{$type|escape:'htmlall':'UTF-8'}]" id="bt_validity[{$type|escape:'htmlall':'UTF-8'}]" value="{if !empty($aVouchers[$type].validity)}{$aVouchers[$type].validity|intval}{else}365{/if}" />
				</div>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Numbers of days for availability date. 365 days (1 year) is standard practice' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				<div style="clear: both;"></div><span class="help-block"><i class="icon-warning-sign text-primary">&nbsp;</i>{l s='In days' mod='gsnippetsreviews'}</span>
			</div>
		</div>

		{* HIGHLIGHT VOUCHER IN CUSTOMER'S CART *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If the voucher is not yet in the cart, it will be displayed in the cart summary' mod='gsnippetsreviews'}">
					<strong>{l s='Highlight' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_highlight_{$type|escape:'htmlall':'UTF-8'}" id="bt_highlight_{$type|escape:'htmlall':'UTF-8'}_on" value="1" {if !empty($aVouchers[$type])}{if !empty($aVouchers[$type].highlight)}checked="checked"{/if}{else}checked="checked"{/if}  />
					<label for="bt_highlight_{$type|escape:'htmlall':'UTF-8'}_on" class="radioCheck">
						{l s='Yes' mod='gsnippetsreviews'}
					</label>
					<input type="radio" name="bt_highlight_{$type|escape:'htmlall':'UTF-8'}" id="bt_highlight_{$type|escape:'htmlall':'UTF-8'}_off" value="0" {if !empty($aVouchers[$type])}{if empty($aVouchers[$type].highlight)}checked="checked"{/if}{/if} />
					<label for="bt_highlight_{$type|escape:'htmlall':'UTF-8'}_off" class="radioCheck">
						{l s='No' mod='gsnippetsreviews'}
					</label>
					<a class="slide-button btn"></a>
				</span>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If the voucher is not yet in the cart, it will be displayed in the cart summary' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
			</div>
		</div>

		{* CUMULATIVE VOUCHER WITH OTHERS *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Cumulative with others vouchers' mod='gsnippetsreviews'}</strong> :</label>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}" id="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}_on" value="1" {if !empty($aVouchers[$type])}{if !empty($aVouchers[$type].cumulativeOther)}checked="checked"{/if}{else}checked="checked"{/if} />
					<label for="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}_on" class="radioCheck">
						{l s='Yes' mod='gsnippetsreviews'}
					</label>
					<input type="radio" name="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}" id="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}_off" value="0" {if !empty($aVouchers[$type])}{if empty($aVouchers[$type].cumulativeOther)}checked="checked"{/if}{/if} />
					<label for="bt_cumulative-other_{$type|escape:'htmlall':'UTF-8'}_off" class="radioCheck">
						{l s='No' mod='gsnippetsreviews'}
					</label>
					<a class="slide-button btn"></a>
				</span>
			</div>
		</div>

		{* CUMULATIVE VOUCHER WITH PRICE REDUCTION *}
		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Cumulative with price reductions' mod='gsnippetsreviews'}</strong> :</label>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}" id="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}_on" value="1" {if !empty($aVouchers[$type])}{if !empty($aVouchers[$type].cumulativeReduction)}checked="checked"{/if}{else}checked="checked"{/if} />
					<label for="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}_on" class="radioCheck">
						{l s='Yes' mod='gsnippetsreviews'}
					</label>
					<input type="radio" name="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}" id="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}_off" value="0" {if !empty($aVouchers[$type])}{if empty($aVouchers[$type].cumulativeReduction)}checked="checked"{/if}{/if} />
					<label for="bt_cumulative-reduc_{$type|escape:'htmlall':'UTF-8'}_off" class="radioCheck">
						{l s='No' mod='gsnippetsreviews'}
					</label>
					<a class="slide-button btn"></a>
				</span>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	// handle cart rule type
	$("#bt_discount-type_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").bind('change', function (event)
	{
		$("#bt_discount-type_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal} option:selected").each(function ()
		{
			switch ($(this).val()) {
				case 'percentage' :
					$("#bt_apply-discount-percent-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").show();
					$("#bt_apply-discount-amount-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").hide();
					$("#bt_div-features-display{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").show();
					break;
				case 'amount' :
					$("#bt_apply-discount-percent-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").hide();
					$("#bt_apply-discount-amount-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").show();
					$("#bt_div-features-display{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").show();
					break;
				default:
					$("#bt_apply-discount-percent-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").hide();
					$("#bt_apply-discount-amount-div_{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").hide();
					$("#bt_div-features-display{/literal}{$type|escape:'htmlall':'UTF-8'}{literal}").hide();
					break;
			}
		});
	}).change();
</script>
{/literal}