<form name="discounts_form" id="discounts_form" action="#" method="POST">
	<div class="row">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="discounts_active">
				{l s='Active' mod='cartabandonmentpro'}
			</label>
			<span style="float:left;" class="switch prestashop-switch input-group col-lg-2">
				<input type="radio" name="discounts_active" id="discounts_active_on" {if isset($discountsActive) and $discountsActive eq 1}checked="checked"{/if}value="1"/>
				<label for="discounts_active_on" class="radioCheck" onClick="setVal('CARTABAND_DISCOUNT', 1);discountsActive(1);">
					<i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
				</label>
				<input type="radio" name="discounts_active" id="discounts_active_off" value="0" {if !isset($discountsActive) or $discountsActive eq 0}checked="checked"{/if} />
				<label for="discounts_active_off" class="radioCheck" onClick="setVal('CARTABAND_DISCOUNT', 0);discountsActive(0);">
					<i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
				</label>
				<a class="slide-button btn"></a>
			</span>
			<input type="hidden" id="discounts_active" name="discounts_active" value="{if isset($discountsActive)}{$discountsActive}{else}0{/if}" />
		</div>
	</div>
	<div id="discounts_configure" {if !isset($discountsActive) or $discountsActive eq 0}style="display:none;"{/if}>
		<br><br>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-6 control-label" for="discounts_different_val">
					{l s='Would you like differents discount according to the value of the cart ?' mod='cartabandonmentpro'}
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
				<input type="hidden" id="discounts_different_val" name="discounts_different_val" value="{if isset($discountsDif)}{$discountsDif}{else}0{/if}" />
			</div>
		</div>
		<br>
		<div id="same_discounts" {if isset($discountsDif) and $discountsDif eq 1}style="display: none;"{/if}>
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_type">
						{l s='Type' mod='cartabandonmentpro'}
					</label>
					<button type="button" id="discounts_type_percent" value="discounts_type_percent" name="discounts_type" class="discounts_type col-sm-1 btn btn-default btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
						{l s='Percent' mod='cartabandonmentpro'}
					</button>
					<button type="button" id="discounts_type_currency" value="discounts_type_currency" name="discounts_type" class="discounts_type col-sm-1 btn btn-primary btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
						{l s='Currency' mod='cartabandonmentpro'}
					</button>
					<button type="button" id="discounts_type_shipping" value="discounts_type_shipping" name="discounts_type" class="discounts_type col-sm-1 btn btn-default btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
						{l s='Free shipping' mod='cartabandonmentpro'}
					</button>
					<input type="hidden" id="discounts_type" name="discounts_type" value="currency">
				</div>
			</div>
			<br>
			<div class="row" id="same_value">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_value">
						{l s='Value' mod='cartabandonmentpro'}
					</label>
					<div class="input-group col-md-3">
					  <input type="text" name="discounts_value" id="discounts_value" value="10" class="form-control col-md-2">
					  <span class="input-group-addon">{$currency}</span>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_validity">
						{l s='Discount validity' mod='cartabandonmentpro'}
					</label>
					<!--<span style="float:left;" class="switch prestashop-switch input-group col-lg-2">
						<input type="radio" name="discounts_validity" id="discounts_validity_on" {if isset($templates.0.tpl_same) and $templates.0.tpl_same eq 1}checked="checked"{/if}value="1"/>
						<label for="discounts_validity_on" class="radioCheck" onClick="discountsValidity('days');">
							<i class="color_success"></i> {l s='Time' mod='cartabandonmentpro'}
						</label>
						<input type="radio" name="discounts_validity" id="discounts_validity_off" value="0" {if !isset($templates.0.tpl_same) or $templates.0.tpl_same eq 0}checked="checked"{/if} />
						<label for="discounts_validity_off" class="radioCheck" onClick="discountsValidity('date');">
							<i class="color_danger"></i> {l s='Date' mod='cartabandonmentpro'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<input type="hidden" id="discounts_validity" name="discounts_validity" value="time" />-->
					<div class="input-group col-md-3">
						<input type="text" name="discounts_validity_days" id="discounts_validity_days" value="7" class="form-control col-md-2">
						<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
					</div>
				</div>
			</div>
			<br>
			<!--
			<div class="row">
				<div class="form-group col-md-4" id="div_discounts_validity_days">
					<div class="input-group col-md-3 col-sm-offset-6">
						<input type="text" name="discounts_validity_days" id="discounts_validity_days" value="7" class="form-control col-md-2">
						<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
					</div>
				</div>
				<div class="form-group col-md-4" id="div_discounts_validity_date" style="display: none;">
					<div class="input-group col-md-3 date" data-date-format="mm-dd-yyyy" id="datetimepicker1">
						<input type="text" name="discounts_validity_date" id="discounts_validity_date" class="form-control">
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			<br>-->
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_value">
						{l s='Cart minimum price' mod='cartabandonmentpro'}
					</label>
					<div class="input-group col-md-3">
					  <input type="text" name="discounts_min" id="discounts_min" value="20" class="form-control col-md-2">
					  <span class="input-group-addon">{$currency}</span>
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_value">
						{l s='Cart maximum price' mod='cartabandonmentpro'}
					</label>
					<div class="input-group col-md-3">
					  <input type="text" name="discounts_max" id="discounts_max" value="0" class="form-control col-md-2">
					  <span class="input-group-addon">{$currency}</span>
					</div>
				</div>
			</div>
		</div>
		<div id="different_discounts" {if !isset($discountsDif) or $discountsDif eq 0}style="display:none;"{/if}>
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="discounts_value">
						{l s='Tranche' mod='cartabandonmentpro'}
					</label>
					<div class="input-group col-md-1">
						<select name="discounts_tranche" id="discounts_tranche">
							<option>1</option>
							<option>2</option>
							<option>3</option>
						</select>
					</div>
				</div>
			</div>
			<div id="discount_1" class="tranches">
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_type_1">
							{l s='Type' mod='cartabandonmentpro'}
						</label>
						<button type="button" id="discounts_type_1_percent" value="percent" name="discounts_type" class="discounts_type_1 col-sm-1 btn btn-default btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Percent' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_1_currency" value="currency" name="discounts_type_1" class="discounts_type_1 col-sm-1 btn btn-primary btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Currency' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_1_shipping" value="shipping" name="discounts_type_1" class="discounts_type_1 col-sm-1 btn btn-default btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Free shipping' mod='cartabandonmentpro'}
						</button>
						<input type="hidden" id="discounts_type_1" name="discounts_type_1" value="currency" />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_beyond_1">
							{l s='beyond' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_beyond_1" id="discounts_beyond_1" value="0" class="form-control col-md-2">
						  <span class="input-group-addon">{$currency}</span>
						</div>
					</div>
				</div>
				<br>
				<div id="value_1" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_value_1">
							{l s='Value' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_value_1" id="discounts_value_1" value="0" class="form-control col-md-2">
						  <span class="input-group-addon currency">{$currency}</span>
						</div>
					</div>
				</div>
				<div id="validity_1" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_validity">
							{l s='Discount validity' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
							<input type="text" name="discounts_validity_days_1" id="discounts_validity_days_1" value="7" class="form-control col-md-2">
							<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
						</div>
					</div>
				</div>
			</div>
			<div id="discount_2" class="tranches" style="display: none;">
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_type_2">
							{l s='Type' mod='cartabandonmentpro'}
						</label>
						<button type="button" id="discounts_type_2_percent" value="percent" name="discounts_type" class="discounts_type_2 col-sm-1 btn btn-default btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Percent' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_2_currency" value="currency" name="discounts_type_2" class="discounts_type_2 col-sm-1 btn btn-primary btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Currency' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_2_shipping" value="shipping" name="discounts_type_2" class="discounts_type_2 col-sm-1 btn btn-default btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Free shipping' mod='cartabandonmentpro'}
						</button>
						<input type="hidden" id="discounts_type_2" name="discounts_type_2" value="currency" />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_beyond_2">
							{l s='beyond' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_beyond_2" id="discounts_beyond_2" value="0" class="form-control col-md-2">
						  <span class="input-group-addon">{$currency}</span>
						</div>
					</div>
				</div>
				<br>
				<div id="value_2" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_value_2">
							{l s='Value' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_value_2" id="discounts_value_2" value="0" class="form-control col-md-2">
						  <span class="input-group-addon currency">{$currency}</span>
						</div>
					</div>
				</div>
				<div id="validity_2" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_validity">
							{l s='Discount validity' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
							<input type="text" name="discounts_validity_days_2" id="discounts_validity_days_2" value="7" class="form-control col-md-2">
							<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
						</div>
					</div>
				</div>
			</div>
			<div id="discount_3" class="tranches" style="display: none;">
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_type_3">
							{l s='Type' mod='cartabandonmentpro'}
						</label>
						<button type="button" id="discounts_type_3_percent" value="percent" name="discounts_type" class="discounts_type_3 col-sm-1 btn btn-default btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Percent' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_3_currency" value="currency" name="discounts_type_3" class="discounts_type_3 col-sm-1 btn btn-primary btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Currency' mod='cartabandonmentpro'}
						</button>
						<button type="button" id="discounts_type_3_shipping" value="shipping" name="discounts_type_3" class="discounts_type_3 col-sm-1 btn btn-default btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;">
							{l s='Free shipping' mod='cartabandonmentpro'}
						</button>
						<input type="hidden" id="discounts_type_3" name="discounts_type_3" value="currency" />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_beyond_3">
							{l s='beyond' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_beyond_3" id="discounts_beyond_3" value="0" class="form-control col-md-2">
						  <span class="input-group-addon">{$currency}</span>
						</div>
					</div>
				</div>
				<br>
				<div id="value_3" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_value_3">
							{l s='Value' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
						  <input type="text" name="discounts_value_3" id="discounts_value_3" value="0" class="form-control col-md-2">
						  <span class="input-group-addon currency">{$currency}</span>
						</div>
					</div>
				</div>
				<div id="validity_3" class="row">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="discounts_validity">
							{l s='Discount validity' mod='cartabandonmentpro'}
						</label>
						<div class="input-group col-md-3">
							<input type="text" name="discounts_validity_days_3" id="discounts_validity_days_3" value="7" class="form-control col-md-2">
							<span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top: 5px;">
		<div class="col-md-1 col-md-offset-4">
			<input type="hidden" id="discount_template" name="discount_template" value=1>
			<button class="btn btn-teal btn-block btn-primary col-md-1" name="discounts_form_submit" type="submit">
				{l s="Save" mod="cartabandonmentpro"} <i class="fa fa-arrow-circle-right"></i>
			</button>
		</div>
	</div>
</form>