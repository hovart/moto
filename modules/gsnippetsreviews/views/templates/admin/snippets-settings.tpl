{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<script type="text/javascript">
	{literal}
	// load the last jquery UI js for FireFox with sortable description
	var sUserAgent = window.navigator.userAgent;

	if (sUserAgent.search("Firefox/38") != -1) {
		var element = document.createElement("script");
		element.src = "{/literal}{$smarty.const._GSR_URL_JS|escape:'htmlall':'UTF-8'}{literal}jquery-ui.min.js";
		document.body.appendChild(element);
	}

	{/literal}{if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'snippet')}{literal}
	var oSnippetsCallBack = [{
		'name' : 'updateDesc',
		'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction=display&sType=snippets&sDisplay=badge',
		'toShow' : 'bt_settings-snippets-badge',
		'toHide' : 'bt_settings-snippets-badge',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	}];
	{/literal}
	{else}
	{literal}
	var oSnippetsCallBack = [];
	{/literal}
	{/if}
</script>

<div class="bootstrap">
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_form-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}" name="bt_form-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}" onsubmit="javascript: oGsr.form('bt_form-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oSnippetsCallBack, 'snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', 'snippets');return false;">
		<input type="hidden" name="sAction" value="{$aQueryParams.snippets.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.snippets.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sDisplay" id="sSnippetsDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}snippet{/if}" />

		{* PRODUCT'S RICH SNIPPETS - START *}
		{if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'snippet')}
			<h3>{l s='Google rich snippets settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If your PrestaShop 1.6 theme is not standard or does not include the product rich snippets code, you need to enable this. If you do not know whether or not your theme already includes rich snippets, please visit' mod='gsnippetsreviews'} : http://faq.businesstech.fr/faq.php?id=47"><strong>{l s='Include product rich snippets code' mod='gsnippetsreviews'}</strong></span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-rich-snippets-prod" id="bt_display-rich-snippets-prod_on" value="1" {if !empty($bDisplayProductRichSnippets)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-rich-snippets-prod', null, null, true, true);"  />
						<label for="bt_display-rich-snippets-prod_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-rich-snippets-prod" id="bt_display-rich-snippets-prod_off" value="0" {if empty($bDisplayProductRichSnippets)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-rich-snippets-prod', null, null, true, false);" />
						<label for="bt_display-rich-snippets-prod_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If your PrestaShop 1.6 theme is not standard or does not include the product rich snippets code, you need to enable this. If you do not know whether or not your theme already includes rich snippets, please visit' mod='gsnippetsreviews'} : http://faq.businesstech.fr/faq.php?id=47">&nbsp;<i class="icon-question-sign"></i></span>
					<a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=105&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How Google handles rich snippets?' mod='gsnippetsreviews'}</a>
				</div>
			</div>

			{* USE CASE - PRODUCT'S RICH SNIPPETS *}
			<div id="bt_div-rich-snippets-prod" style="display: {if !empty($bDisplayProductRichSnippets)}block{else}none{/if};">
				<div class="clr_20"></div>
				{* DESCRIPTION *}
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display description tag in badge' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-desc" id="bt_display-desc_on" value="1" {if !empty($bDisplayDesc)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-display-desc', null, null, true, true);"  />
							<label for="bt_display-desc_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-desc" id="bt_display-desc_off" value="0" {if empty($bDisplayDesc)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-display-desc', null, null, true, false);" />
							<label for="bt_display-desc_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div id="bt_div-display-desc" style="display: {if !empty($bDisplayDesc)}block{else}none{/if};">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please drag and drop the above elements to sort them into the desired order. Let\'s say you put Meta description first, Short description second, and Long description third. When rendering the snippets HTML code on your product pages, the module will first try to use the product\'s meta-description. If none is available, it will then try the short description, and finally the long one if the short one is not available either' mod='gsnippetsreviews'}"><strong>{l s='Select your description\'s order' mod='gsnippetsreviews'}</strong></span> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<ul id="bt_sortable-desc" class="pointer dragHandle">
								{foreach from=$aDesc name=desc key=sDescVal item=sDescTitle}
									<li id="{$sDescVal|escape:'htmlall':'UTF-8'}">{$sDescTitle|escape:'htmlall':'UTF-8'}</li>
								{/foreach}
							</ul>
							<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please drag and drop the above elements to sort them into the desired order. Let\'s say you put Meta description first, Short description second, and Long description third. When rendering the snippets HTML code on your product pages, the module will first try to use the product\'s meta-description. If none is available, it will then try the short description, and finally the long one if the short one is not available either' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display brand tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-brand" id="bt_display-brand_on" value="1" {if !empty($bDisplayBrand)}checked="checked"{/if} />
							<label for="bt_display-brand_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-brand" id="bt_display-brand_off" value="0" {if empty($bDisplayBrand)}checked="checked"{/if} />
							<label for="bt_display-brand_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>


				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display category tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-category" id="bt_display-category_on" value="1" {if !empty($bDisplayCat)}checked="checked"{/if} />
							<label for="bt_display-category_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-category" id="bt_display-category_off" value="0" {if empty($bDisplayCat)}checked="checked"{/if} />
							<label for="bt_display-category_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display breadcrumb tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-breadcrumb" id="bt_display-breadcrumb_on" value="1" {if !empty($bDisplayBreadcrumb)}checked="checked"{/if} />
							<label for="bt_display-breadcrumb_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-breadcrumb" id="bt_display-breadcrumb_off" value="0" {if empty($bDisplayBreadcrumb)}checked="checked"{/if} />
							<label for="bt_display-breadcrumb_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display identifier tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-identifier" id="bt_display-identifier_on" value="1" {if !empty($bDisplayIdentifier)}checked="checked"{/if} />
							<label for="bt_display-identifier_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-identifier" id="bt_display-identifier_off" value="0" {if empty($bDisplayIdentifier)}checked="checked"{/if} />
							<label for="bt_display-identifier_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display supplier tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-supplier" id="bt_display-supplier_on" value="1" {if !empty($bDisplaySupplier)}checked="checked"{/if} />
							<label for="bt_display-supplier_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-supplier" id="bt_display-supplier_off" value="0" {if empty($bDisplaySupplier)}checked="checked"{/if} />
							<label for="bt_display-supplier_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display condition tag' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-condition" id="bt_display-condition_on" value="1" {if !empty($bDisplayCondition)}checked="checked"{/if} />
							<label for="bt_display-condition_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-condition" id="bt_display-condition_off" value="0" {if empty($bDisplayCondition)}checked="checked"{/if} />
							<label for="bt_display-condition_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				{* USE CASE - OFFERS *}
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This concerns products with attribute combinations. If you select "Offer" => the module will include only the default combination and will use the price of that combination. If you select "Offer Aggregate" => it will also include the lowest and highest price of the product, based on the combination prices' mod='gsnippetsreviews'}."><strong>{l s='Select your offer\'s type' mod='gsnippetsreviews'}</strong></span> :</label>
					<div class="col-lg-3">
						<select name="bt_offers" id="bt_offers" class="col-lg-4">
							<option value="offer" {if $sOfferType == 'offer'}selected="selected"{/if}>{l s='Offer' mod='gsnippetsreviews'}</option>
							<option value="aggregate" {if $sOfferType == 'aggregate'}selected="selected"{/if}>{l s='Aggregate' mod='gsnippetsreviews'}</option>
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This concerns products with attribute combinations. If you select "Offer" => the module will include only the default combination and will use the price of that combination. If you select "Offer Aggregate" => it will also include the lowest and highest price of the product, based on the combination prices' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>


				{* USE CASE - OFFER TYPE *}
				<div id="bt_div-offer" style="display: {if $sOfferType == 'offer'}block{else}none{/if};">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display seller tag' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_display-seller" id="bt_display-seller_on" value="1" {if !empty($bDisplaySeller)}checked="checked"{/if} />
								<label for="bt_display-seller_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_display-seller" id="bt_display-seller_off" value="0" {if empty($bDisplaySeller)}checked="checked"{/if} />
								<label for="bt_display-seller_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display price valid until date tag' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_display-until-date" id="bt_display-until-date_on" value="1" {if !empty($bDisplayUntilDate)}checked="checked"{/if} />
								<label for="bt_display-until-date_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_display-until-date" id="bt_display-until-date_off" value="0" {if empty($bDisplayUntilDate)}checked="checked"{/if} />
								<label for="bt_display-until-date_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display avaibility tag' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_display-avaibility" id="bt_display-avaibility_on" value="1" {if !empty($bDisplayAvaibility)}checked="checked"{/if} />
								<label for="bt_display-avaibility_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_display-avaibility" id="bt_display-avaibility_off" value="0" {if empty($bDisplayAvaibility)}checked="checked"{/if} />
								<label for="bt_display-avaibility_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>
				</div>

				{* USE CASE - OFFER AGGREGATE TYPE *}
				<div id="bt_div-offer-aggregate" style="display: {if $sOfferType == 'aggregate'}block{else}none{/if};">

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display highest product price tag' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_display-highest-price" id="bt_display-highest-price_on" value="1" {if !empty($bDisplayHighPrice)}checked="checked"{/if} />
								<label for="bt_display-highest-price_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_display-highest-price" id="bt_display-highest-price_off" value="0" {if empty($bDisplayHighPrice)}checked="checked"{/if} />
								<label for="bt_display-highest-price_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><strong>{l s='Display number of product variants tag' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_display-offer-count" id="bt_display-offer-count_on" value="1" {if !empty($bDisplayOfferCount)}checked="checked"{/if} />
								<label for="bt_display-offer-count_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_display-offer-count" id="bt_display-offer-count_off" value="0" {if empty($bDisplayOfferCount)}checked="checked"{/if} />
								<label for="bt_display-offer-count_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"></label>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="alert alert-warning">{l s='If you enable the "Include product rich snippets code" option, please be sure to click the "Badges" sub-menu on left, to decide how the Snippets badge will be displayed on your product pages' mod='gsnippetsreviews'}.</div>
				</div>
			</div>

			<div class="clr_20"></div>
		{/if}

		{* USE CASE - REVIEWS SNIPPETS *}
		{if !empty($sDisplay) && $sDisplay == 'review'}
			<h3>{l s='Review rich snippets settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select "Individual", your enhanced review will include the number of stars from the latest rating and, if a written review was typed, it will be included as well. If you select "Aggregate", it the number of stars will represent the average of all reviews for that product, and the text will be picked automatically by Google from the content of your page.' mod='gsnippetsreviews'}"><strong>{l s='Reviews' mod='gsnippetsreviews'}</strong></span> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_review-type" id="bt_review-type" class="col-lg-4">
						<option value="individual" {if $sReviewType == 'individual'}selected="selected"{/if}>{l s='Individual' mod='gsnippetsreviews'}</option>
						<option value="aggregate" {if $sReviewType == 'aggregate'}selected="selected"{/if}>{l s='Aggregate' mod='gsnippetsreviews'}</option>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select "Individual", your enhanced review will include the number of stars from the latest rating and, if a written review was typed, it will be included as well. If you select "Aggregate", it the number of stars will represent the average of all reviews for that product, and the text will be picked automatically by Google from the content of your page.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>
		{/if}

		{* BEGIN - DISPLAY BADGES OPTIONS *}
		{if !empty($sDisplay) && $sDisplay == 'badge'}
			<h3>{l s='Badge settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You need to activate this option if you want the Google Rich Snippets functionality to work correctly. It will display a nice visual badge with summary information about your product and its ratings on each product page, as well as aggregate ratings on product category pages and your homepage.' mod='gsnippetsreviews'}"><strong>{l s='Display Snippets Badges' mod='gsnippetsreviews'}</strong></span> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-badge" id="bt_display-badge_on" value="1" {if !empty($bDisplayBadge)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-badge-css', null, null, true, true);"  />
						<label for="bt_display-badge_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-badge" id="bt_display-badge_off" value="0" {if empty($bDisplayBadge)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-badge-css', null, null, true, false);" />
						<label for="bt_display-badge_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You need to activate this option if you want the Google Rich Snippets functionality to work correctly. It will display a nice visual badge with summary information about your product and its ratings on each product page, as well as aggregate ratings on product category pages and your homepage.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_div-badge-css" style="display: {if !empty($bDisplayBadge)}block{else}none{/if};">
				{if !empty($aBadgePages)}
					<div class="clr_20"></div>

					{literal}
					<script type="text/javascript">
						function activateBadge(elt) {
							if ($(elt).is('.action-enabled')){
								$(elt).removeClass('action-enabled');
								$(elt).addClass('action-disabled');
								$(elt).children('i').removeClass('icon-check');
								$(elt).parent().removeClass('success');
								$(elt).children('i').addClass('icon-remove');
								$(elt).parent().addClass('danger');
								$(elt).children('input').removeAttr('checked', 'checked');
								$(elt).children('input').val(0);
							}
							else {
								$(elt).removeClass('action-disabled');
								$(elt).addClass('action-enabled');
								$(elt).children('i').removeClass('icon-remove');
								$(elt).parent().removeClass('danger');
								$(elt).children('i').addClass('icon-check');
								$(elt).parent().addClass('success');
								$(elt).children('input').attr('checked', 'checked');
								$(elt).children('input').val(1);
							}
						};
					</script>
					{/literal}

					<div class="form-group">
						<label class="control-label col-xs-12 col-md-12 col-md-3 col-lg-2"><strong>{l s='Display badge on the following pages' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-md-12 col-md-8 col-lg-8">
							<table class="table table-responsive">
								<thead>
								<tr>
									<th><span class="title_box center">{l s='Badge' mod='gsnippetsreviews'}</span></th>
									<th><span class="title_box center">{l s='Active' mod='gsnippetsreviews'}</span></th>
									<th><span class="title_box center">{l s='Position' mod='gsnippetsreviews'}</span></th>
									<th><span class="title_box center">{l s='Custom CSS (if you select "custom css" from the position menu)' mod='gsnippetsreviews'}</span></th>
								</tr>
								</thead>
								<tbody>
								{foreach from=$aBadgePages name=page key=sPageVal item=aPage}
									{if !empty($aPage.use)}
										<tr>
											<td class="col-xs-12 col-md-12 col-md-4 col-lg-4 center">{$aPage.title|escape:'htmlall':'UTF-8'}
												<input type="hidden" name="bt_select-badge[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{$sPageVal|escape:'htmlall':'UTF-8'}" />
											</td>
											<td class="col-xs-12 col-md-12 col-md-1 col-lg-1 center {if !empty($aBadges[$sPageVal].display)}success{else}danger{/if}">
												<div class="pointer list-action-enable action-{if !empty($aBadges[$sPageVal].display)}enabled{else}disabled{/if}" onclick="javascript: activateBadge(this);"><i class="icon-{if !empty($aBadges[$sPageVal].display)}check{else}remove{/if}"></i><input type="hidden" name="bt_select-badge[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{if !empty($aBadges[$sPageVal].display)}1{else}0{/if}" {if !empty($aBadges[$sPageVal].display)} checked="checked"{/if} /></div>
											</td>
											<td class="col-xs-12 col-md-12 col-md-4 col-lg-4 center">
												<select name="bt_badge-position[{$sPageVal|escape:'htmlall':'UTF-8'}]" id="bt_badge-position[{$sPageVal|escape:'htmlall':'UTF-8'}]">
													{foreach from=$aPage.allow name=allow key=iPos item=aPosTitle}
														<option value="{$aPosTitle.position|escape:'htmlall':'UTF-8'}" {if !empty($aBadges[$sPageVal].position) && $aBadges[$sPageVal].position == $aPosTitle.position}selected="selected"{/if}>{$aPosTitle.title|escape:'htmlall':'UTF-8'}</option>
													{/foreach}
												</select>
											</td>
											<td class="col-xs-12 col-md-12 col-md-3 col-lg-3 center"><textarea rows="3" id="bt_badge-freestyle[{$sPageVal|escape:'htmlall':'UTF-8'}]" name="bt_badge-freestyle[{$sPageVal|escape:'htmlall':'UTF-8'}]" {if empty($aBadges[$sPageVal].custom)}disabled="disabled"{/if}>{if !empty($aBadges[$sPageVal].custom)}{$aBadges[$sPageVal].custom|escape:'htmlall':'UTF-8'}{/if}</textarea></td>
										</tr>
									{/if}
								{/foreach}
								</tbody>
							</table>
						</div>
					</div>
				{/if}
			</div>
		{/if}
		{* END - DISPLAY BADGES OPTIONS *}

		<div class="clr_20"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oSnippetsCallBack, 'snippets-{$sDisplay|escape:'htmlall':'UTF-8'}', 'snippets');return false;"><i class="process-icon-save"></i>{l s='Update' mod='gsnippetsreviews'}</button></div>
		</div>
	</form>
	<div class="clr_20"></div>
</div>

{literal}
<script type="text/javascript">
	$(document).ready(function(){
		// set sortable
		oGsr.sortDesc('#bt_sortable-desc');

		// handle offer type
		$("#bt_offers").bind('change', function (event) {
			$("#bt_offers option:selected").each(function () {
				switch ($(this).val()) {
					case 'offer' :
						// set sortable
						$("#bt_div-offer").show();
						$("#bt_div-offer-aggregate").hide();
						break;
					default:
						// set sortable
						$("#bt_div-offer").hide();
						$("#bt_div-offer-aggregate").show();
						break;
				}
			});
		}).change();

		{/literal}
		{foreach from=$aBadgePages name=page key=sPageVal item=aPage}
		{literal}
		// handle review type
		$("select[id='bt_badge-position[{/literal}{$sPageVal|escape:'htmlall':'UTF-8'}{literal}]']").bind('change', function (event) {
			$("select[id='bt_badge-position[{/literal}{$sPageVal|escape:'htmlall':'UTF-8'}{literal}]'] option:selected").each(function () {
				switch ($(this).val()) {
					case 'wizard' :
						$("textarea[id='bt_badge-freestyle[{/literal}{$sPageVal|escape:'htmlall':'UTF-8'}{literal}]']").removeAttr('disabled');
						break;
					default:
						$("textarea[id='bt_badge-freestyle[{/literal}{$sPageVal|escape:'htmlall':'UTF-8'}{literal}]']").attr('disabled','disabled');
						break;
				}
			});
		});
		{/literal}
		{/foreach}
		{literal}
	});
	//bootstrap components init
	$('.label-tooltip, .help-tooltip').tooltip();
</script>
{/literal}