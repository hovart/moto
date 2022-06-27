{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div class="bootstrap">
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_vouchers-form" name="bt_vouchers-form" onsubmit="oGsr.form('bt_vouchers-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-voucher', 'bt_settings-voucher', false, false, null, 'voucher', 'voucher');return false;">
		<input type="hidden" name="sAction" value="{$aQueryParams.vouchers.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.vouchers.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sVoucherType" value="comment" />

		<h3>{l s='Voucher Incentive Settings' mod='gsnippetsreviews'}</h3>

		{if !empty($bUpdate)}
			<div class="clr_10"></div>
			{include file="`$sConfirmInclude`"}
		{elseif !empty($aErrors)}
			<div class="clr_10"></div>
			{include file="`$sErrorInclude`"}
		{/if}

		<div class="clr_10"></div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"></label>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
				<div class="alert alert-info">{l s='This section lets you offer your customers incentives for posting a comment. You can, if you want, offer them a small voucher amount for each product they review, which they will be able to redeem on their next purchase. If you activate it, a message will also be displayed on the product page above the review form to let people know this feature is available and to encourage them to review your products. Any reminder e-mails sent to them if you activate those will also include this information.' mod='gsnippetsreviews'}</div>
			</div>
		</div>

		<div class="clr_20"></div>

		<div id="comment">
			{include file="`$sVoucherForm`" type="comment"}
		</div>

		<div class="clr_20"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-voucher"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_vouchers-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-voucher', 'bt_settings-voucher', false, false, null, 'voucher', 'voucher');return false;"><i class="process-icon-save"></i>{l s='Update' mod='gsnippetsreviews'}</button></div>
		</div>
	</form>

	<div class="clr_20"></div>

	<script type="text/javascript">
		//bootstrap components init
		$('.label-tooltip, .help-tooltip').tooltip();
		$('.dropdown-toggle').dropdown();
	</script>
</div>