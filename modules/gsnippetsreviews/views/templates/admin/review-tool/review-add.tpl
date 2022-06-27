{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div class="bootstrap">
	<script type="text/javascript">
		{literal}
		var oReviewAddCallback = [{
			'name' : 'moderationList',
			'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
			'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction=display&sType=moderation',
			'toShow' : 'bt_settings-moderation',
			'toHide' : 'bt_settings-moderation',
			'bFancybox' : false,
			'bFancyboxActivity' : false,
			'sLoadbar' : null,
			'sScrollTo' : null,
			'oCallBack' : {}
		}];
		{/literal}
	</script>

	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post" id="bt_review-add-form" name="bt_review-add-form" onsubmit="oGsr.form('bt_review-add-form', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_settings-add', 'bt_settings-add', false, false, oReviewAddCallback, 'add-review', 'add', null);return false;">
		<input type="hidden" name="sController" value="{$sController|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sAction" value="{$aQueryParams.addReview.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.addReview.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" id="bCheckFieldText" name="bCheckFieldText" value="1" />
		<div>
			<input type="hidden" id="bt_customer-id" name="bt_customer-id" value="" />
			<input type="hidden" id="bt_product-id" name="bt_product-id" value="" />
			<input type="hidden" id="iRating" name="iRating" value="" />
		</div>

		<h3>{l s='Add your review manually' mod='gsnippetsreviews'}</h3>

		{if !empty($bUpdate)}
			{include file="`$sReviewConfirmInclude`" iLastInsertId=$iLastInsertId}
		{elseif !empty($aErrors)}
			{include file="`$sErrorInclude`"}
		{/if}

		<div class="clr_20"></div>

		<div class="alert alert-info">
			{l s='Here you can manually add reviews. Once you have selected a product, you will be able to select a customer, and then access all the other elements (language, rating, comment...)'  mod='gsnippetsreviews'}.
		</div>

		<div class="clr_20"></div>

		<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2">{l s='Select your shop' mod='gsnippetsreviews'} :</label>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<select name="bt_shop-id" id="bt_shop-id">
				{foreach key=key item=aShop from=$aShops}
					<option value="{$aShop.id_shop|intval}" {if $iCurrentShopId == $aShop.id_shop}selected="selected"{/if}>{$aShop.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>

		<div class="clr_20"></div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<span class="label-tooltip" data-toggle="tooltip" title="{l s='Start to type a product name and get an autocomplete list of products' mod='gsnippetsreviews'}">
					<strong>{l s='Type / search a product name' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="input-group">
					<span class="input-group-addon"><i class="icon-pencil"></i></i></span>
					<input type="text" id="bt_product-filter" class="input-xlarge" name="bt_product-filter" value="" />
					<span class="input-group-addon"><i class="icon-search"></i></span>
				</div>
			</div>
		</div>

		<div class="clr_20"></div>

		<div class="form-group" id="bt_customer-field" style="display: none;">
			<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<span class="label-tooltip" data-toggle="tooltip" title="{l s='Start to type a customer name and get an autocomplete list of customer names' mod='gsnippetsreviews'}">
					<strong>{l s='Type / search a customer name' mod='gsnippetsreviews'}</strong>
				</span> :
			</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="input-group">
					<span class="input-group-addon"><i class="icon-user"></i></i></span>
					<input type="text" id="bt_customer-filter" class="input-xlarge" name="bt_customer-filter" value="" />
					<span class="input-group-addon"><i class="icon-search"></i></span>
				</div>
			</div>
		</div>

		<div class="clr_20"></div>

		<div id="bt_review-form" style="display: none;">
			<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2">{l s='Select a customer language' mod='gsnippetsreviews'} :</label>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<select name="bt_lang-id" id="bt_lang-id">
					{foreach key=key item=aLang from=$aLangs}
						<option value="{$aLang.id_lang|intval}">{$aLang.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</div>

			<div class="clr_30"></div>

			<h3>{l s='Your rating' mod='gsnippetsreviews'}</h3><span style="float: right; display: inline !important; margin-top: -30px !important;"><button type="button" class="close" onclick="$('#bt_review-form').slideUp();$('#bt_customer-field').slideUp();"><i class="process-icon-cancel"></i></button></span>

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2 required"><strong>{l s='Rate / review this product' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					{section loop=$iMaxRating name=note}<input class="star" type="radio" name="rating" value="{$smarty.section.note.iteration|intval}" />{/section}
				</div>
			</div>

			<div class="clr_30"></div>

			<h3>{l s='Your review' mod='gsnippetsreviews'}</h3>

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2 required">
					<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='Please select the review\'s date' mod='gsnippetsreviews'}".>
						<strong>{l s='Review date' mod='gsnippetsreviews'}</strong> :
					</span>
				</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
						<div class="input-group">
							<span class="input-group-addon">Date</span>
							<input type="text" class="datepicker input-medium" name="bt_review-date" value="" id="bt_review-date">
							<span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
						</div>
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please select the review\'s date' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>
			</div>

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2 required">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a title' mod='gsnippetsreviews'}">
						<strong>{l s='Title' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
						<input type="text" id="bt_review-title" name="bt_review-title" value="" />
					</div>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a title' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-2 col-lg-2 required">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a comment' mod='gsnippetsreviews'}">
						<strong>{l s='Comment' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
						<textarea type="text" id="bt_review-comment" name="bt_review-comment" value="" rows="6" ></textarea>
					</div>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Enter a comment' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>
		</div>
	</form>

	<div class="clr_10"></div>
	<div class="clr_hr"></div>
	<div class="clr_20"></div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			<div id="bt_error-add-review"></div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="pull-right">
				<button class="btn btn-success btn-lg"  onclick="oGsr.form('bt_review-add-form', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_settings-add', 'bt_settings-add', false, false, oReviewAddCallback, 'add-review', 'add', null);">{l s='Add' mod='gsnippetsreviews'}</button>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="{$sAutocompleteJs|escape:'htmlall':'UTF-8'}"></script>
	<link rel="stylesheet" type="text/css" href="{$sAutocompleteCss|escape:'htmlall':'UTF-8'}">

	{literal}
	<script type="text/javascript">
		//bootstrap components init
		{/literal}{if !empty($bAjaxMode)}{literal}
		$('.label-tooltip, .help-tooltip').tooltip();
		{/literal}{/if}{literal}

		// activate product autocomplete
		$('#bt_product-filter').autocomplete(
			'ajax-tab.php',{
				minChars: 2,
				max: 50,
				width: 500,
				selectFirst: false,
				scroll: false,
				dataType: 'json',
				formatItem: function(data, i, max, value, term) {
					return value;
				},
				parse: function(data) {
					var mytab = new Array();
					for (var i = 0; i < data.length; i++)
						mytab[mytab.length] = { data: data[i], value: (data[i].reference + ' ' + data[i].name).trim() };
					return mytab;
				},
				extraParams: {
					controller: 'AdminCartRules',
					token: '{/literal}{$sCurrentToken|escape:'htmlall':'UTF-8'}{literal}',
					reductionProductFilter: 1
				}
			}
		)
		.result(function(event, data, formatted) {
				$('#bt_product-id').val(data.id_product);
				$('#bt_product-filter').val((data.reference + ' ' + data.name).trim());
				$('#bt_customer-field').slideDown();
			}
		);

		// activate customer autocomplete
		$('#bt_customer-filter').autocomplete(
			'ajax-tab.php', {
				minChars: 2,
				max: 50,
				width: 500,
				selectFirst: false,
				scroll: false,
				dataType: 'json',
				formatItem: function(data, i, max, value, term) {
					return value;
				},
				parse: function(data) {
					var mytab = new Array();
					for (var i = 0; i < data.length; i++)
						mytab[mytab.length] = { data: data[i], value: data[i].cname + ' (' + data[i].email + ')' };
					return mytab;
				},
				extraParams: {
					controller: 'AdminCartRules',
					token: '{/literal}{$sCurrentToken|escape:'htmlall':'UTF-8'}{literal}',
					customerFilter: 1
				}
			}
		).result(function(event, data, formatted) {
				$('#bt_customer-id').val(data.id_customer);
				$('#bt_customer-filter').val(data.cname + ' (' + data.email + ')');
				$('#bt_review-form').slideDown();
			}
		);

		$(document).ready(function(){
			if ($(".datepicker").length > 0) {
				var date = new Date();
				var hours = date.getHours();
				if (hours < 10)
					hours = "0" + hours;
				var mins = date.getMinutes();
				if (mins < 10)
					mins = "0" + mins;
				var secs = date.getSeconds();
				if (secs < 10)
					secs = "0" + secs;
				$('.datepicker').datepicker({
					prevText: '',
					nextText: '',
					dateFormat: 'yy-mm-dd ' + hours + ':' + mins + ':' + secs
				});
			}

			// star rating
			$('#bt_review-form :radio.star').rating({
				ratingField : "iRating",
				readOnly : false,
				starGif : "{/literal}{$aParamStars.star|escape:'htmlall':'UTF-8'}{literal}",
				starWidth : "{/literal}{$iMaxRating|intval}{literal}"
			});
		});
	</script>
	{/literal}
</div>