<script>
	var tpl_selected = {$template_disc};
	{foreach from=$errors key=k item=v}
		var {$k} = "{$v}";
	{/foreach}
	var currency = "{$currency}";
</script>
<h3>{l s="Discounts value configuration" mod="cartabandonmentpro"}</h3>
<div class="row">
	<form action="#" method="POST" id="template_chose">
		<div class="row">
			<div class="form-group">
				<label class="col-xs-1 control-label" for="discounts_template">
					{l s='Template' mod='cartabandonmentpro'}
				</label>
				<select name="discounts_template" id="discounts_template" class="col-xs-1" style="width:150px;">
					{if $first_reminder_active eq 1}<option value="1" {if $template_disc eq 1}selected="selected"{/if}>{l s='Reminder' mod='cartabandonmentpro'} 1</option>{/if}
					{if $second_reminder_active eq 1}<option value="2" {if $template_disc eq 2}selected="selected"{/if}>{l s='Reminder' mod='cartabandonmentpro'} 2</option>{/if}
					{if $third_reminder_active eq 1}<option value="3" {if $template_disc eq 3}selected="selected"{/if}>{l s='Reminder' mod='cartabandonmentpro'} 3</option>{/if}
				</select>
			</div>
		</div>
		<input type="hidden" name="discounts_chose_template" value="1">
	</form>
</div>
<br><br>
<div class="row">
	<form name="discounts_form" id="discounts_form" action="#" method="POST" onSubmit="return checkDiscountForm();">
		<input type="hidden" name="discounts_template" value="{$template_disc}">
		<div class="row">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="discounts_active">
					{l s='Active' mod='cartabandonmentpro'}
				</label>
				<span style="float:left;" class="switch prestashop-switch input-group col-lg-2">
					<input type="radio" name="discounts_active" id="discounts_active_on" {if isset($discountsActive) and $discountsActive eq 1}checked="checked"{/if}value="1"/>
					<label for="discounts_active_on" class="radioCheck" onClick="discountsActive(1);">
						<i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
					</label>
					<input type="radio" name="discounts_active" id="discounts_active_off" value="0" {if !isset($discountsActive) or $discountsActive eq 0}checked="checked"{/if} />
					<label for="discounts_active_off" class="radioCheck" onClick="discountsActive(0);">
						<i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
					</label>
					<a class="slide-button btn"></a>
				</span>
				<input type="hidden" id="discounts_active_val" name="discounts_active_val" value="{if isset($discountsActive)}{$discountsActive}{else}0{/if}" />
			</div>
		</div>
		<div id="discounts_configure" {if !isset($discountsActive) or $discountsActive eq 0}style="display:none;"{/if}>
			<br><br>
			<div class="row">
				<div class="form-group">
					<label class="col-xs-6 control-label" for="discounts_different_val">
						{l s='Would you like the value of your discount varied depending on the shopping cart total of the customer ?' mod='cartabandonmentpro'}
					</label>
					<span style="float:left;" class="switch prestashop-switch input-group col-lg-2">
						<input type="radio" name="discounts_different_val" id="discounts_different_val_on" {if isset($discountsDif) and $discountsDif eq 1}checked="checked"{/if}value="1"/>
						<label for="discounts_different_val_on" class="radioCheck" onClick="discountsDiffVal(1);">
							<i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
						</label>
						<input type="radio" name="discounts_different_val" id="discounts_different_val_off" value="0" {if !isset($discountsDif) or $discountsDif eq 0}checked="checked"{/if} />
						<label for="discounts_different_val_off" class="radioCheck" onClick="discountsDiffVal(0);">
							<i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<input type="hidden" id="discounts_different_val2" name="discounts_different_val2" value="{if isset($discountsDif)}{$discountsDif}{else}0{/if}" />
				</div>
			</div>
			<br>
			<!-- SAME DISCOUNTS -->
			<div id="same_discounts" {if isset($discountsDif) and $discountsDif eq 1}style="display: none;"{/if}>
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_type">
							{l s='Type' mod='cartabandonmentpro'}
						</label>
						<button type="button" id="discounts_type_percent" value="percent" name="discounts_type" class="diff_type discounts_type col-sm-1 btn {if $discounts2.0.type eq 'percent'}btn-primary{else}btn-default{/if} btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
							{l s='Percent' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_currency" value="currency" name="discounts_type" class="discounts_type col-sm-1 btn {if $discounts2.0.type eq 'currency'}btn-primary{else}btn-default{/if} btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
							{l s='Currency' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_shipping" value="shipping" name="discounts_type" class="discounts_type col-sm-1 btn {if $discounts2.0.type eq 'shipping'}btn-primary{else}btn-default{/if} btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
							{l s='Free shipping' mod='cartabandonmentpro'}
						</button>
						<input type="hidden" id="discounts_type" name="discounts_type" value="{if $discounts2.0.type}{$discounts2.0.type}{/if}">
					</div>
				</div>
				<br>
				<div class="row" id="same_value" {if $discounts2.0.type eq 'shipping'}style="display:none;"{/if}>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_value">
							{l s='Value' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_value" id="discounts_value" value="{if isset($discounts2.0.value)}{$discounts2.0.value}{/if}" class="form-control col-md-2">
						  <span id="value_operator" class="input-group-addon">{if $discounts2.0.type eq 'percent'}%{else}{$currency}{/if}</span>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_validity">
							{l s='Discount validity' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
							<input type="text" name="discounts_validity_days" id="discounts_validity_days" value="{if isset($discounts2.0.valid_value)}{$discounts2.0.valid_value}{/if}" class="form-control col-md-2">
							<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
						</div>
					</div>
				</div>
				<br>
				<input type="hidden" name="discounts_min" id="discounts_min" value="0" class="form-control col-md-2">
				<input type="hidden" name="discounts_max" id="discounts_max" value="2147483647" class="form-control col-md-2">
			</div>
			
			<!-- DIFFERENT DISCOUNTS -->
			<div id="different_discounts" {if !isset($discountsDif) or $discountsDif eq 0}style="display:none;"{/if}>
				<div class="row" style="margin-left: 20px;">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_tranche">
							{l s='Choose the number of discount ranges' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-1">
							<select name="discounts_tranche" id="discounts_tranche">
								<option {if $tranches eq 1}selected="selected"{/if}>1</option>
								<option {if $tranches eq 2}selected="selected"{/if}>2</option>
								<option {if $tranches eq 3}selected="selected"{/if}>3</option>
							</select>
						</div>
					</div>
				</div>
				<div id="discount_1" class="tranches panel">
					<div class="panel-heading">
						<i class="fa fa-credit-card"></i>
						{l s='Range 1' mod='cartabandonmentpro'}
					</div>
					<div class="row">
						<div class="form-group">
							<label class="col-xs-3 control-label pull-left" for="discounts_max_1">
								{l s='A shopping cart total more than' mod='cartabandonmentpro'}
							</label>
							<div class="input-group col-xs-1 pull-left">
							  <input type="text" name="discounts_min_1" id="discounts_min_1" value="{if isset($discounts2.0.min_amount)}{$discounts2.0.min_amount}{/if}" class="form-control col-md-2">
							  <span class="input-group-addon">{$currency}</span>
							</div>
							<input type="hidden" name="discounts_max_1" value="0">
							<label class="col-xs-1 control-label pull-left" for="discounts_type_1" style="margin-left: 20px;">
								{l s='Type' mod='cartabandonmentpro'}
							</label>
							<select name="discounts_type_1" id="discounts_type_1" class="diff_type pull-left col-xs-1" style="width:100px;">
								<option value="percent" {if $discounts2.0.type eq 'percent'}selected="selected"{/if}>
									{l s='Percent' mod='cartabandonmentpro'}
								</option>
								<option value="currency" {if $discounts2.0.type eq 'currency'}selected="selected"{/if}>
									{l s='Currency' mod='cartabandonmentpro'}
								</option>
								<option value="shipping" {if $discounts2.0.type eq 'shipping'}selected="selected"{/if}>
									{l s='Free shipping' mod='cartabandonmentpro'}
								</option>
							</select>
							<div>
								<table>
									<tr id="value_1" class="value" {if $discounts2.0.type eq 'shipping'}style="display:none;"{/if}><td width="150px">
										<label class="control-label" for="discounts_value_1">
											{l s='discount value is' mod='cartabandonmentpro'}
										</label>
									</td><td width="100px">
										<div class="input-group">
										  <input type="text" name="discounts_value_1" id="discounts_value_1" value="{if isset($discounts2.0.value)}{$discounts2.0.value}{/if}" class="form-control">
										  <span id="value_operator_1" class="input-group-addon currency">{if $discounts2.0.type eq 'percent'}%{else}{$currency}{/if}</span>
										</div>
									</td></tr>
									<tr><td>
										<label class="control-label" for="discounts_validity">
											{l s='Discount validity' mod='cartabandonmentpro'}
										</label>
									</td><td>
										<div class="input-group">
											<input type="text" name="discounts_validity_days_1" id="discounts_validity_days_1" value="{if isset($discounts2.0.valid_value)}{$discounts2.0.valid_value}{/if}" class="form-control">
											<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
										</div>
									</td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="discount_2" class="tranches panel" {if $tranches  < 2}style="display:none;"{/if}>
					<div class="panel-heading">
						<i class="fa fa-credit-card"></i>
						{l s='Range 2' mod='cartabandonmentpro'}
					</div>
					<div class="row">
						<div class="form-group">
							<label class="col-xs-3 control-label pull-left" for="discounts_max_2">
								{l s='A shopping cart total more than' mod='cartabandonmentpro'}
							</label>
							<div class="input-group col-xs-1 pull-left">
							  <input type="text" name="discounts_min_2" id="discounts_min_2" value="{if isset($discounts2.1.min_amount)}{$discounts2.1.min_amount}{/if}" class="form-control col-md-2">
							  <span class="input-group-addon">{$currency}</span>
							</div>
							<input type="hidden" name="discounts_max_2" value="0">
							<label class="col-xs-1 control-label pull-left" for="discounts_type_2" style="margin-left: 20px;">
								{l s='Type' mod='cartabandonmentpro'}
							</label>
							<select name="discounts_type_2" id="discounts_type_2" class="diff_type pull-left col-xs-1" style="width: 100px;">
								<option value="percent" {if $discounts2.1.type eq 'percent'}selected="selected"{/if}>
									{l s='Percent' mod='cartabandonmentpro'}
								</option>
								<option value="currency" {if $discounts2.1.type eq 'currency'}selected="selected"{/if}>
									{l s='Currency' mod='cartabandonmentpro'}
								</option>
								<option value="shipping" {if $discounts2.1.type eq 'shipping'}selected="selected"{/if}>
									{l s='Free shipping' mod='cartabandonmentpro'}
								</option>
							</select>
							<div style="margin-left: 20px;">
								<table>
									<tr id="value_2" class="value" {if $discounts2.1.type eq 'shipping'}style="display:none;"{/if}><td width="150px">
										<label class="control-label" for="discounts_value_2">
											{l s='discount value is' mod='cartabandonmentpro'}
										</label>
									</td><td width="100px">
										<div class="input-group">
										  <input type="text" name="discounts_value_2" id="discounts_value_2" value="{if isset($discounts2.1.value)}{$discounts2.1.value}{/if}" class="form-control">
										  <span id="value_operator_2" class="input-group-addon currency">{if $discounts2.1.type eq 'percent'}%{else}{$currency}{/if}</span>
										</div>
									</td></tr>
									<tr><td>
										<label class="control-label" for="discounts_validity">
											{l s='Discount validity' mod='cartabandonmentpro'}
										</label>
									</td><td>
										<div class="input-group">
											<input type="text" name="discounts_validity_days_2" id="discounts_validity_days_2" value="{if isset($discounts2.1.valid_value)}{$discounts2.1.valid_value}{/if}" class="form-control">
											<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
										</div>
									</td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="discount_3" class="tranches panel" {if $tranches  < 3}style="display:none;"{/if}>
					<div class="panel-heading">
						<i class="fa fa-credit-card"></i>
						{l s='Range 3' mod='cartabandonmentpro'}
					</div>
					<div class="row">
						<div class="form-group">
							<label class="col-xs-3 control-label pull-left" for="discounts_max_3">
								{l s='A shopping cart total more than' mod='cartabandonmentpro'}
							</label>
							<div class="input-group col-xs-1 pull-left">
							  <input type="text" name="discounts_min_3" id="discounts_min_3" value="{if isset($discounts2.2.min_amount)}{$discounts2.2.min_amount}{/if}" class="form-control col-md-2">
							  <span class="input-group-addon">{$currency}</span>
							</div>
							<input type="hidden" name="discounts_max_3" value="2147483647">
							<label class="col-xs-1 control-label pull-left" for="discounts_type_3" style="margin-left: 20px;">
								{l s='Type' mod='cartabandonmentpro'}
							</label>
							<select name="discounts_type_3" id="discounts_type_3" class="diff_type pull-left col-xs-1" style="width: 100px;">
								<option value="percent" {if $discounts2.2.type eq 'percent'}selected="selected"{/if}>
									{l s='Percent' mod='cartabandonmentpro'}
								</option>
								<option value="currency" {if $discounts2.2.type eq 'currency'}selected="selected"{/if}>
									{l s='Currency' mod='cartabandonmentpro'}
								</option>
								<option value="shipping" {if $discounts2.2.type eq 'shipping'}selected="selected"{/if}>
									{l s='Free shipping' mod='cartabandonmentpro'}
								</option>
							</select>
							<div style="margin-left: 20px;">
								<table>
									<tr id="value_3" class="value" {if $discounts2.2.type eq 'shipping'}style="display:none;"{/if}><td width="150px">
										<label class="control-label" for="discounts_value_3">
											{l s='discount value is' mod='cartabandonmentpro'}
										</label>
									</td><td width="100px">
										<div class="input-group">
										  <input type="text" name="discounts_value_3" id="discounts_value_3" value="{if isset($discounts2.2.value)}{$discounts2.2.value}{/if}" class="form-control">
										  <span id="value_operator_3" class="input-group-addon currency">{if $discounts2.2.type eq 'percent'}%{else}{$currency}{/if}</span>
										</div>
									</td></tr>
									<tr><td>
										<label class="control-label" for="discounts_validity">
											{l s='Discount validity' mod='cartabandonmentpro'}
										</label>
									</td><td>
										<div class="input-group">
											<input type="text" name="discounts_validity_days_3" id="discounts_validity_days_3" value="{if isset($discounts2.2.valid_value)}{$discounts2.2.valid_value}{/if}" class="form-control">
											<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
										</div>
									</td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br>
		<h3>{l s="Discounts text configuration" mod="cartabandonmentpro"}</h3>
		<div class="row">
			<div class="alert alert-info" role="alert">
				<strong>{l s='Important:' mod='cartabandonmentpro'}</strong>{l s='Discounts texts will be the same for every reminders.' mod='cartabandonmentpro'}
			</div>
		</div>
		<div class="row">
			{l s='You can add to your remind email a text with the total amount, validity date and discount code automatically with proposed tags.' mod='cartabandonmentpro'}
			<br>
			{l s='Use the field “Discount text" if your promotion is a percentage or an amount.' mod='cartabandonmentpro'}
			<br>
			{l s='Use the field Free shipping text" if promotion offers free shipping.' mod='cartabandonmentpro'}
			<br><br>
			{l s='Here’s the list of tags:' mod='cartabandonmentpro'}
			<br>
			<b>%DISCOUNT_VALUE%</b> - {l s='Amount of discount. E.g. 20% or 50 €' mod='cartabandonmentpro'}
			<br>
			<b>%DISCOUNT_VALID_DAY% %DISCOUNT_VALID_MONTH% %DISCOUNT_VALID_YEAR%</b> - {l s='Discount expiry date. E.g. 4/11/2014' mod='cartabandonmentpro'}
			<br>
			<b>%DISCOUNT_CODE%</b> - {l s='Discount code. E.g. CAVa70c6' mod='cartabandonmentpro'}
			<br><br>
			{l s='You also can translate this text on the language field above.' mod='cartabandonmentpro'}
			<br><br>
			<br>
			<br>
			<div class="row">
				{foreach from=$languages item=language}
					<button type="button" toggle="input_select_{$language.id_lang}_container" toggle_lang="{$language.id_lang}" class="lang_toggle_{$language.id_lang} lang_toggle btn {if $language.id_lang neq $lang_default}btn-default{else} btn-primary{/if}">{$language.iso_code}</button>
				{/foreach}
			</div>
			<br>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="discounts_value">
					{l s='Discount text' mod='cartabandonmentpro'}
				</label>
				<div class="input-group col-xs-9">
					{foreach from=$languages item=language}
						<input type="text" name="discount_val_text_{$language.id_lang}" id="discount_val_text_{$language.id_lang}" class="{if $language.id_lang neq $lang_default} hidden{/if} multilang {$language.id_lang}_container form-control col-xs-12" value="{$discount_val_text_{$language.id_lang}}">
					{/foreach}
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="discounts_value">
					{l s='Free shipping text' mod='cartabandonmentpro'}
				</label>
				<div class="input-group col-xs-9">
					{foreach from=$languages item=language}
						<input type="text" name="discount_shipping_text_{$language.id_lang}" id="discount_shipping_text_{$language.id_lang}" value="{$discount_shipping_text_{$language.id_lang}}" class="{if $language.id_lang neq $lang_default} hidden{/if} multilang {$language.id_lang}_container form-control col-xs-12">
					{/foreach}
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="submit" name="discounts_form_submit" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s="Save" mod="cartabandonmentpro"}</button>
		</div>
	</form>
</div>