{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<h3>{l s='Prerequisites check' mod='gsnippetsreviews'}</h3>

<div class="clr_hr"></div>
<div class="clr_20"></div>

<div class="form-group">
	<label class="control-label col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.' mod='gsnippetsreviews'}"><strong>{l s='Activate ratings and reviews' mod='gsnippetsreviews'}</strong></span> :</label>
	<div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews" id="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews_on" value="1" {if !empty($bDisplayReviews)}checked="checked"{/if} onclick="oGsr.changeSelect('{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews', '{$sModuleName|escape:'htmlall':'UTF-8'}ReviewRating', null, null, true, true);"  />
			<label for="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews_on" class="radioCheck">
				{l s='Yes' mod='gsnippetsreviews'}
			</label>
			<input type="radio" name="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews" id="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews_off" value="0" {if empty($bDisplayReviews)}checked="checked"{/if} onclick="oGsr.changeSelect('{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews', '{$sModuleName|escape:'htmlall':'UTF-8'}ReviewRating', null, null, true, false);" />
			<label for="{$sModuleName|escape:'htmlall':'UTF-8'}DisplayReviews_off" class="radioCheck">
				{l s='No' mod='gsnippetsreviews'}
			</label>
			<a class="slide-button btn"></a>
		</span>
		<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
	</div>
</div>