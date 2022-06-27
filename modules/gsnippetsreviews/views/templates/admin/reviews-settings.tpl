{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

{literal}
<script type="text/javascript">
	var oReviewsCallBack = [{
		'name' : 'updateReminders',
		'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}=admin&sAction=display&sType=emailReviews&sDisplay=email',
		'toShow' : 'bt_settings-email',
		'toHide' : 'bt_settings-email',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'updateReminders',
		'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}=admin&sAction=display&sType=emailReviews&sDisplay=litigation',
		'toShow' : 'bt_settings-email-litigation',
		'toHide' : 'bt_settings-email-litigation',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'updateReminders',
		'url' : '{/literal}{$sURI|escape:'UTF-8'}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}=admin&sAction=display&sType=emailReviews&sDisplay=reminder',
		'toShow' : 'bt_settings-email-reminder',
		'toHide' : 'bt_settings-email-reminder',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	}];
</script>
{/literal}

<div class="bootstrap">
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_form-reviews-{$sDisplay|escape:'htmlall':'UTF-8'}" name="bt_form-reviews-{$sDisplay|escape:'htmlall':'UTF-8'}" onsubmit="oGsr.form('bt_form-reviews-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-review-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-review-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oReviewsCallBack, 'review-{$sDisplay|escape:'htmlall':'UTF-8'}', 'review');return false;">
		<input type="hidden" name="sAction" value="{$aQueryParams.reviews.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.reviews.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sDisplay" id="sReviewsDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}global{/if}" />

		<span class="pull-right">
			<a href="{$sAdmniTabUrl|escape:'htmlall':'UTF-8'}" target="_blank" class="btn btn-info btn-lg" role="button">{l s='Moderate reviews'  mod='gsnippetsreviews'}</a>&nbsp;<a href="{$sAdmniTabUrl|escape:'htmlall':'UTF-8'}#2" target="_blank" class="btn btn-info btn-lg" role="button">{l s='Add a review'  mod='gsnippetsreviews'}</a>
		</span>

		<div class="clr_10"></div>

		{************ GLOBAL SETTINGS ************}
		{if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'global')}
			<h3>{l s='Global Settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.' mod='gsnippetsreviews'}">
						<strong>{l s='Activate ratings and reviews' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-reviews" id="bt_display-reviews_on" value="1" {if !empty($bDisplayReviews)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_display-review-rating', null, null, true, true);"  />
						<label for="bt_display-reviews_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-reviews" id="bt_display-reviews_off" value="0" {if empty($bDisplayReviews)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_display-review-rating', null, null, true, false);" />
						<label for="bt_display-reviews_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, then the entire review functionality will be disabled and the module will only output the Rich Snippets code with information such as price, product category, brand etc..., but your Google listings will not have any rating stars displayed below, and your product page will not display anything related to reviews either.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_display-review-rating" style="display: {if !empty($bDisplayReviews)}block{else}none{/if};">
				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Enable Ratings input' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-ratings" id="bt_enable-ratings_on" value="1" {if !empty($bEnableRatings)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-enable-comments', null, null, true, true);"  />
							<label for="bt_enable-ratings_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_enable-ratings" id="bt_enable-ratings_off" value="0" {if empty($bEnableRatings)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-enable-comments', null, null, true, false);" />
							<label for="bt_enable-ratings_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>

				<div class="clr_10"></div>

				<div id="bt_div-enable-comments" style="display: {if !empty($bEnableRatings)}block{else}none{/if};">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Enable Comments input' mod='gsnippetsreviews'}</strong> :</label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_enable-comments" id="bt_enable-comments_on" value="1" {if !empty($bEnableComments)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-force-comments', null, null, true, true);" />
								<label for="bt_enable-comments_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_enable-comments" id="bt_enable-comments_off" value="0" {if empty($bEnableComments)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-force-comments', null, null, true, false);" />
								<label for="bt_enable-comments_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
						</div>
					</div>

					<div id="bt_div-force-comments" style="display: {if !empty($bEnableComments)}block{else}none{/if};">
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Force to write a comment' mod='gsnippetsreviews'}</strong> :</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="bt_force-comments" id="bt_force-comments_on" value="1" {if !empty($bForceComments)}checked="checked"{/if}  />
								<label for="bt_force-comments_on" class="radioCheck">
									{l s='Yes' mod='gsnippetsreviews'}
								</label>
								<input type="radio" name="bt_force-comments" id="bt_force-comments_off" value="0" {if empty($bForceComments)}checked="checked"{/if} />
								<label for="bt_force-comments_off" class="radioCheck">
									{l s='No' mod='gsnippetsreviews'}
								</label>
								<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}


		{************ HANDLING REVIEW SETTINGS ************}
		{if !empty($sDisplay) && $sDisplay == 'review'}
			<h3>{l s='Handling Review Settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Require Admin Approval' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_admin" id="bt_admin_on" value="1" {if !empty($bAdminApproval)}checked="checked"{/if}  />
						<label for="bt_admin_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_admin" id="bt_admin_off" value="0" {if empty($bAdminApproval)}checked="checked"{/if} />
						<label for="bt_admin_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Who can review' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<select name="bt_authorize" id="bt_authorize">
						{foreach from=$aAuthorize name=condition key=sAuthVal item=sAuthTitle}
							<option value="{$sAuthVal|escape:'htmlall':'UTF-8'}" {if !empty($sAuthorizeReview) && $sAuthorizeReview == $sAuthVal}selected="selected"{/if}>{$sAuthTitle|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you activate this option, language filtering will be applied. For example, say one your products has 2 reviews in English and 1 in French. If this is activated, then the English version of your website will say it has 2 reviews, and the French version will say it has 1 review. However, if you do not activate it, then both languages will say it has 3 reviews. You should set this once and for all on initial setup of the module and avoid changing the setting after that, so as not to confuse Google with the number of ratings changing abruptly.' mod='gsnippetsreviews'}"><strong>{l s='Always count and display reviews in the current language ONLY' mod='gsnippetsreviews'}</strong></span> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_enable-cust-lang" id="bt_enable-cust-lang_on" value="1" {if !empty($bEnableCustLang)}checked="checked"{/if}  />
						<label for="bt_enable-cust-lang_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_enable-cust-lang" id="bt_enable-cust-lang_off" value="0" {if empty($bEnableCustLang)}checked="checked"{/if} />
						<label for="bt_enable-cust-lang_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you activate this option, language filtering will be applied. For example, say one your products has 2 reviews in English and 1 in French. If this is activated, then the English version of your website will say it has 2 reviews, and the French version will say it has 1 review. However, if you do not activate it, then both languages will say it has 3 reviews. You should set this once and for all on initial setup of the module and avoid changing the setting after that, so as not to confuse Google with the number of ratings changing abruptly.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<strong>{l s='Number of reviews per page for moderation' mod='gsnippetsreviews'}</strong> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews-moderation" id="bt_nb-reviews-moderation">
						{foreach from=$aEltPerPage name=pagination key=key item=nb}
							<option value="{$nb|intval}" {if $nb == $iNbModerateReviews}selected="selected"{/if}>{$nb|intval}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Number of reviews per reviews list page' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews-list-page" id="bt_nb-reviews-list-page">
						{foreach from=$aEltPerPage name=pagination key=key item=nb}
							<option value="{$nb|intval}" {if $nb == $iReviewsListPerPage}selected="selected"{/if}>{$nb|intval}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Display the report abuse button' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-report-abuse" id="bt_display-report-abuse_on" value="1" {if !empty($bDisplayReportButton)}checked="checked"{/if} />
						<label for="bt_display-report-abuse_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-report-abuse" id="bt_display-report-abuse_off" value="0" {if empty($bDisplayReportButton)}checked="checked"{/if} />
						<label for="bt_display-report-abuse_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Display the customer address' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-address" id="bt_display-address_on" value="1" {if !empty($bDisplayAddress)}checked="checked"{/if} />
						<label for="bt_display-address_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-address" id="bt_display-address_off" value="0" {if empty($bDisplayAddress)}checked="checked"{/if} />
						<label for="bt_display-address_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Display the product\'s image in the reviews list' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-product-photo" id="bt_display-product-photo_on" value="1" {if !empty($bDisplayPhoto)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-display-product-photo', null, null, true, true);" />
						<label for="bt_display-product-photo_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-product-photo" id="bt_display-product-photo_off" value="0" {if empty($bDisplayPhoto)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-display-product-photo', null, null, true, false);" />
						<label for="bt_display-product-photo_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>

			<div id="bt_div-display-product-photo" style="display: {if !empty($bDisplayPhoto)}block{else}none{/if};">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Select product image size for reviews list' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<select name="bt_review-list-prod-img" id="bt_review-list-prod-img">
							{foreach key=key item=sImageSize from=$aImageSize}
								<option value="{$sImageSize|escape:'htmlall':'UTF-8'}" {if $sImageSize == $sReviewListProdImg} selected="selected"{/if}>{$sImageSize|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
		{/if}

		{************ PRODUCT PAGE SETTINGS ************}
		{if !empty($sDisplay) && $sDisplay == 'product'}
			<h3>{l s='Product page Settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections. But some custom themes have added back tabs on the product page. Please select the correct option below' mod='gsnippetsreviews'}.">
						<strong>{l s='Your theme layout' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_reviews-display-mode" id="bt_reviews-display-mode" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						{foreach from=$aReviewsMode name=mode key=sMode item=sTitle}
							<option value="{$sMode|escape:'htmlall':'UTF-8'}" {if $sDisplayReviewMode == $sMode}selected="selected"{/if}>{$sTitle|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections. But some custom themes have added back tabs on the product page. Please select the correct option below' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="form-group" id="bt_display-theme-warning" style="display: {if $sDisplayReviewMode == 'tabs17'}block{else}none{/if};">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="alert alert-warning">
						{l s='On a standard PrestaShop 1.7 theme, the product page has tabs for the various sections close to the description block but there isn\'t any hook to display the module with. You may want to use this tabs section to display the reviews list, so you would follow our FAQ on how to display the module there:' mod='gsnippetsreviews'} <a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=151&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ: How do I display the reviews list in the description block tabs?' mod='gsnippetsreviews'}</a>
						<div class="clr_10"></div>
						<a href="#" data-toggle="modal" data-target="#modal_review_prod_tabs_preview"><span class="icon-eye-open">&nbsp;</span>{l s='Click here to show a preview' mod='gsnippetsreviews'}</a>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="modal_review_prod_tabs_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='gsnippetsreviews'}</span></button>
							<h4 class="modal-title" id="myModalLabel">{l s='Preview' mod='gsnippetsreviews'}</h4>
						</div>
						<div class="modal-body">
							<div class="center"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/screenshot-review-product-tabs.jpg" width="700"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
						</div>
					</div>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='A small block with the average rating, the number of reviews, and a link to post a new rating / review will be displayed on the product page. This determines where this block will be displayed' mod='gsnippetsreviews'}.">
						<strong>{l s='Hook to display' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_hooks" id="bt_hooks" class="col-xs-4 col-sm-4 col-md-4 col-lg-8">
						{foreach from=$aReviewHooks name=condition key=item item=aHook}
							<option value="{$aHook.name|escape:'htmlall':'UTF-8'}" {if $sHook == $aHook.name}selected="selected"{/if}>{$aHook.title|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='A small block with the average rating, the number of reviews, and a link to post a new rating / review will be displayed on the product page. This determines where this block will be displayed' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This image size is used in review form when a customer is going to post a review' mod='gsnippetsreviews'}">
						<strong>{l s='Select image size for review form' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_review-prod-img" id="bt_review-prod-img"  class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						{foreach key=key item=sImageSize from=$aImageSize}
							<option value="{$sImageSize|escape:'htmlall':'UTF-8'}" {if $sImageSize == $sReviewProdImg} selected="selected"{/if}>{$sImageSize|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This image size is used in review form when a customer is going to post a review' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>

					<!-- Button trigger modal -->
					<a href="#" data-toggle="modal" data-target="#modal_preview_form"><span class="icon-eye-open">&nbsp;</span>{l s='Click here to show a preview' mod='gsnippetsreviews'}</a>
					<!-- Modal -->
					<div class="modal fade" id="modal_preview_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content modal-lg">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='gsnippetsreviews'}</span></button>
									<h4 class="modal-title" id="myModalLabel">{l s='Preview' mod='gsnippetsreviews'}</h4>
								</div>
								<div class="modal-body">
									<div class="center"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/screenshot-review-form.jpg" width="700" height="870"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{* BEGIN - CONFIGURE REVIEWS' PICTOS *}
			{if !empty($aImages)}
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Choose your style for the star icons. It is a "sprite" image (3 images in one). The first one is used when no rating has been made, the second one when the user hovers with his mouse, and the third one when the rating has been made. You can create new styles if you want. Simply go to the img/admin/picto folder inside the gsnippetsreviews module folder. Duplicate any existing folder, rename it to something different (no spaces or accents, only letters and dashes "-"), and modify the image to your taste, but make sure it is still called "picto.png" and keep the same image size and space used by each star / element.' mod='gsnippetsreviews'}">
							<strong>{l s='Pictogram to choose for rating' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<select name="bt_picto" id="bt_picto" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{foreach from=$aImages name=picto key=key item=aImage}
								<option value="{$aImage.subpath|escape:'htmlall':'UTF-8'}" {if !empty($sPicto) && $sPicto == $aImage.subpath}selected="selected"{/if}>{$aImage.subpath|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Choose your style for the star icons. It is a "sprite" image (3 images in one). The first one is used when no rating has been made, the second one when the user hovers with his mouse, and the third one when the rating has been made. You can create new styles if you want. Simply go to the img/admin/picto folder inside the gsnippetsreviews module folder. Duplicate any existing folder, rename it to something different (no spaces or accents, only letters and dashes "-"), and modify the image to your taste, but make sure it is still called "picto.png" and keep the same image size and space used by each star / element.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
						{foreach from=$aImages name=picto key=key item=aImage}
							<span id="bt_picto-{$aImage.subpath|escape:'htmlall':'UTF-8'}" style="float: left; margin-left: 15px; margin-bottom: 10px; display: {if !empty($sPicto)}{if $sPicto == $aImage.subpath}inline{else}none{/if}{elseif $smarty.foreach.picto.first == true}inline{else}none{/if};">
								<img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}picto/{$aImage.subpathname|escape:'htmlall':'UTF-8'}" alt="{$aImage.subpath|escape:'htmlall':'UTF-8'}" title="{$aImage.subpath|escape:'htmlall':'UTF-8'}" />
							</span>
						{/foreach}
						<!-- Button trigger modal -->
						<a href="#" data-toggle="modal" data-target="#modal_product_preview"><span class="icon-eye-open">&nbsp;</span>{l s='Click here to show a preview' mod='gsnippetsreviews'}</a>
						<!-- Modal -->
						<div class="modal fade" id="modal_product_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content modal-lg">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='gsnippetsreviews'}</span></button>
										<h4 class="modal-title" id="myModalLabel">{l s='Preview' mod='gsnippetsreviews'}</h4>
									</div>
									<div class="modal-body">
										<div class="center"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/screenshot-product-review.jpg" width="700" height="591"></div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-info" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			{/if}
			{* END - CONFIGURE REVIEWS' PICTOS *}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='It\'s possible you couldn\'t see the review stars correctly and you see them as square because we display them in CSS, and maybe your theme doesn\'t include the CSS file of the default theme to get the "FontAwesome" review stars rendering. In that case, you should activate this option to allow the module to include our own "FontAwesome" css file to display the stars correctly' mod='gsnippetsreviews'}.">
						<strong>{l s='You don\'t see the review stars correctly, I include the module CSS stars file?' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_use-fontawesome" id="bt_use-fontawesome_on" value="1" {if !empty($bUseFontAwesome)}checked="checked"{/if} />
						<label for="bt_use-fontawesome_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_use-fontawesome" id="bt_use-fontawesome_off" value="0" {if empty($bUseFontAwesome)}checked="checked"{/if} />
						<label for="bt_use-fontawesome_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='It\'s possible you couldn\'t see the review stars correctly and you see them as square because we display them in CSS, and maybe your theme doesn\'t include the CSS file of the default theme to get the "FontAwesome" review stars rendering. In that case, you should activate this option to allow the module to include our own "FontAwesome" css file to display the stars correctly' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
					<a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=153&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How do I solve my review stars rendering issue?' mod='gsnippetsreviews'}</a>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Number of reviews per page' mod='gsnippetsreviews'}</strong> :</label>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<select name="bt_nb-reviews" id="bt_nb-reviews">
						{foreach from=$aEltPerPage name=pagination key=key item=nb}
							<option value="{$nb|intval}" {if $nb == $iReviewsPerPage}selected="selected"{/if}>{$nb|intval}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<h4>{l s='Social buttons' mod='gsnippetsreviews'}</h4>
			<div class="clr_hr"></div>
			<div class="clr_10"></div>

			{if !empty($bShareVoucher)}
				<div class="clr_10"></div>
				<div class="alert alert-warning">
					{l s='Because you have activated the "offer a voucher for sharing a review" feature in the "Facebook integration" tab, you should definitely also enable the "display share buttons" option below'  mod='gsnippetsreviews'}
				</div>
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="alert alert-info">
						{l s='If you enable this "display share buttons" option, please be aware that you can also take it further by rewarding your customers for sharing their reviews. To do so, simply go to the "Facebook integration" tab' mod='gsnippetsreviews'}.
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you enable this option, each product review can be shared by your customers on their Facebook and / or Twitter account(s)' mod='gsnippetsreviews'}.">
						<strong>{l s='Display share buttons' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" id ="bt_social-button-div">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_enable-social-button" id="bt_enable-social-button_on" value="1" {if !empty($bEnableSocialButton)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-social-button', null, null, true, true);"  />
						<label for="bt_enable-social-button_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_enable-social-button" id="bt_enable-social-button_off" value="0" {if empty($bEnableSocialButton)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_div-social-button', null, null, true, false);" />
						<label for="bt_enable-social-button_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you enable this option, each product review can be shared by your customers on their Facebook and / or Twitter account(s)' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_div-social-button" style="display: {if !empty($bEnableSocialButton)}block{else}none{/if};">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Display count box' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_count-box-button" id="bt_count-box-button_on" value="1" {if !empty($bCountBoxButton)}checked="checked"{/if}  />
							<label for="bt_count-box-button_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_count-box-button" id="bt_count-box-button_off" value="0" {if empty($bCountBoxButton)}checked="checked"{/if} />
							<label for="bt_count-box-button_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Which FB button kind to use?' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<select name="bt_fb-button-type" id="bt_fb-button-type">
							<option value="1" {if $iFbButtonType == 1}selected="selected"{/if}>{l s='Display the like button' mod='gsnippetsreviews'}</option>
							<option value="2" {if $iFbButtonType == 2}selected="selected"{/if}>{l s='Display the like button with the share button' mod='gsnippetsreviews'}</option>
							<option value="3" {if $iFbButtonType == 3}selected="selected"{/if}>{l s='Display the share button alone' mod='gsnippetsreviews'}</option>
						</select>
					</div>
				</div>

				<div id="bt_div-fb-warning-msg" style="display: {if $iFbButtonType != 1}inline{else}none{/if};">
					<div class="clr_10"></div>

					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="alert alert-warning">
								{l s='Be careful, only the like button can allow your customers to get an incentive if you\'ve activated the option in the "Facebook integration" tab. The click on the share button doesn\'t allow to get a click event on and detect if the user has posted or not something on his own FB timeline' mod='gsnippetsreviews'}.
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}

		{************ MY ACCOUNT SETTINGS ************}
		{if !empty($sDisplay) && $sDisplay == 'account'}
			<h3>{l s='Customer account review settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="alert alert-info">
				{l s='Your customers will have access to a "my reviews" section when they visit their account main page. In that section, they will be able to see the products they have not yet reviewed (presented in a visual slideshow fashion), as well as the products they have reviewed (in a simple table format). The settings below give you little bit control on how all this is displayed' mod='gsnippetsreviews'}.
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please select a size' mod='gsnippetsreviews'}">
						<strong>{l s='Product image size for slideshow' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-prod-img" id="bt_slider-prod-img" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						{foreach key=key item=sImageSize from=$aImageSize}
							<option value="{$sImageSize|escape:'htmlall':'UTF-8'}" {if $sImageSize == $sSliderProdImg} selected="selected"{/if}>{$sImageSize|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Please select a size' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
					<!-- Button trigger modal -->
					<a href="#" data-toggle="modal" data-target="#modal_slider_preview"><span class="icon-eye-open">&nbsp;</span>{l s='Click here to show a preview' mod='gsnippetsreviews'}</a>
					<!-- Modal -->
					<div class="modal fade" id="modal_slider_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content modal-lg">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='gsnippetsreviews'}</span></button>
									<h4 class="modal-title" id="myModalLabel">{l s='Preview' mod='gsnippetsreviews'}</h4>
								</div>
								<div class="modal-body">
									<div class="center"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}admin/screenshot-product-slider.jpg"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal">{l s='Close' mod='gsnippetsreviews'}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Based on the image size you selected above, as well as the specificities of your theme, you may need to adjust this value' mod='gsnippetsreviews'}.">
						<strong>{l s='Width of slideshow container' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-width" id="bt_slider-width" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						{foreach key=key item=iWidth from=$aSliderOpts.width}
							<option value="{$iWidth|intval}" {if $iWidth == $iSliderWidth} selected="selected"{/if}>{$iWidth|intval} px</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Based on the image size you selected above, as well as the specificities of your theme, you may need to adjust this value' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The amount of time (in seconds) each product will be displayed in the slider before the next product' mod='gsnippetsreviews'}.">
						<strong>{l s='Slider time interval' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-pause" id="bt_slider-pause" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						{foreach key=key item=iPause from=$aSliderOpts.pause}
							<option value="{$key|intval}" {if $key == $iSliderPause} selected="selected"{/if}>{$iPause|intval} {l s='sec' mod='gsnippetsreviews'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The amount of time (in seconds) each product will be displayed in the slider before the next product' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The amount of time (in seconds) it will take for a product to slide from right to left' mod='gsnippetsreviews'}.">
						<strong>{l s='Slider movement speed' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_slider-speed" id="bt_slider-speed" class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
						{foreach key=key item=iSpeed from=$aSliderOpts.speed}
							<option value="{$key|intval}" {if $key == $iSliderSpeed} selected="selected"{/if}>{$iSpeed|intval} {l s='sec' mod='gsnippetsreviews'}</option>
						{/foreach}
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The amount of time (in seconds) it will take for a product to slide from right to left' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>
		{/if}

		{************ LAST REVIEWS SETTINGS ************}
		{if !empty($sDisplay) && $sDisplay == 'last'}
			<h3>{l s='Last Reviews Block Settings' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="alert alert-info">
				{l s='The module lets you display a block with the latest customer reviews on various sections of your website. The options below give you control over how this is all displayed' mod='gsnippetsreviews'}.
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, the last reviews block will not be displayed' mod='gsnippetsreviews'}.">
						<strong>{l s='Display block of last reviews' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_display-last-reviews" id="bt_display-last-reviews_on" value="1" {if !empty($bDisplayLastRvwBlock)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_last-review-block', null, null, true, true);" />
						<label for="bt_display-last-reviews_on" class="radioCheck">
							{l s='Yes' mod='gsnippetsreviews'}
						</label>
						<input type="radio" name="bt_display-last-reviews" id="bt_display-last-reviews_off" value="0" {if empty($bDisplayLastRvwBlock)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_last-review-block', null, null, true, false);" />
						<label for="bt_display-last-reviews_off" class="radioCheck">
							{l s='No' mod='gsnippetsreviews'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If disabled, the last reviews block will not be displayed' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_last-review-block" style="display: {if !empty($bDisplayLastRvwBlock)}block{else}none{/if};">
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This determines how many reviews will be displayed in the latest reviews block' mod='gsnippetsreviews'}.">
							<strong>{l s='Number of reviews to display' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						<select name="bt_nb-last-reviews" id="bt_nb-last-reviews" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							{foreach from=$aNbLastReviews name=condition key=key item=nb}
								<option value="{$nb|intval}" {if $nb == $iNbLastReviews}selected="selected"{/if}>{$nb|intval}</option>
							{/foreach}
						</select>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This determines how many reviews will be displayed in the latest reviews block' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				{if !empty($aLastBlockPages)}
				<div class="clr_10"></div>

				{literal}
				<script type="text/javascript">
					function activateLastReviewBlock(elt) {
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
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This determines where the block will be displayed. You can display it on more than one page' mod='gsnippetsreviews'}.">
							<strong>{l s='Display block on the following pages' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<table class="table">
							<thead>
							<tr>
								<th><div class="title_box center">{l s='Page' mod='gsnippetsreviews'}</div></th>
								<th><div class="title_box center">{l s='Active' mod='gsnippetsreviews'}</div></th>
								<th><div class="title_box center">{l s='Hook' mod='gsnippetsreviews'}</div></th>
								<th><div class="title_box center">{l s='Width' mod='gsnippetsreviews'}<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Default width is 100%' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span></div></th>
								<th><div class="title_box center">{l s='Truncate comments' mod='gsnippetsreviews'}<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This determines how many characters of comments that the module will truncate to display the last reviews block correctly' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span></div></th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$aLastBlockPages name=page key=sPageVal item=aPage}
								{if !empty($aPage.use)}
									<tr>
										<td class="center col-xs-12 col-sm-12 col-md-3 col-lg-3">{$aPage.title|escape:'htmlall':'UTF-8'}
											<input type="hidden" name="bt_select-block-pos[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{$sPageVal|escape:'htmlall':'UTF-8'}" />
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-1 col-lg-1 {if !empty($aLastBlockPos[$sPageVal].display)}success{else}danger{/if}">
											<div class="list-action-enable action-{if !empty($aLastBlockPos[$sPageVal].display)}enabled{else}disabled{/if}" onclick="javascript: activateLastReviewBlock(this);"><i class="icon-{if !empty($aLastBlockPos[$sPageVal].display)}check{else}remove{/if}"></i><input type="hidden" name="bt_select-block-pos[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{if !empty($aLastBlockPos[$sPageVal].display)}1{else}0{/if}" {if !empty($aLastBlockPos[$sPageVal].display)} checked="checked"{/if} /></div>
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-4 col-lg-3">
											<select name="bt_last-block-position[{$sPageVal|escape:'htmlall':'UTF-8'}]" id="bt_last-block-position[{$sPageVal|escape:'htmlall':'UTF-8'}]" class="col-xs-12 col-md-12 col-lg-12">
												{foreach from=$aPage.allow name=allow key=iPos item=aPosTitle}
													<option value="{$aPosTitle.position|escape:'htmlall':'UTF-8'}" {if !empty($aLastBlockPos[$sPageVal].position) && $aLastBlockPos[$sPageVal].position == $aPosTitle.position}selected="selected"{/if}>{$aPosTitle.title|escape:'htmlall':'UTF-8'}</option>
												{/foreach}
											</select>
										</td>
										<td class="center col-xs-12 col-sm-12 col-md-2 col-lg-2"><span class="col-xs-10 col-sm-10 col-md-10 col-lg-10"><input type="text" id="bt_last-block-width[{$sPageVal|escape:'htmlall':'UTF-8'}]" name="bt_last-block-width[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{if !empty($aLastBlockPos[$sPageVal].width)}{$aLastBlockPos[$sPageVal].width|intval}{else}100{/if}" /></span>&nbsp;<span class="col-xs-2 col-sm-2 col-md-2 col-lg-2">%</span></td>
										<td class="center col-xs-12 col-sm-12 col-md-2 col-lg-2"><span class="col-xs-10 col-sm-10 col-md-10 col-lg-10"><input type="text" id="bt_last-block-truncate[{$sPageVal|escape:'htmlall':'UTF-8'}]" name="bt_last-block-truncate[{$sPageVal|escape:'htmlall':'UTF-8'}]" value="{if !empty($aLastBlockPos[$sPageVal].truncate)}{$aLastBlockPos[$sPageVal].truncate|intval}{else}30{/if}" /></span>&nbsp;<span class="col-xs-2 col-sm-2 col-md-2 col-lg-2">{l s='chars' mod='gsnippetsreviews'}</span></td>
									</tr>
								{/if}
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
				{/if}

				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-2 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The module usually displays a badge on your website with your average ratings and other information. If you also display the last reviews block, you can choose to display it first (before the badge)' mod='gsnippetsreviews'}.">
							<strong>{l s='Display before the Badge block' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-block-first" id="bt_display-block-first_on" value="1" {if !empty($bLastRvwBlockFirst)}checked="checked"{/if}  />
							<label for="bt_display-block-first_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-block-first" id="bt_display-block-first_off" value="0" {if empty($bLastRvwBlockFirst)}checked="checked"{/if} />
							<label for="bt_display-block-first_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='The module usually displays a badge on your website with your average ratings and other information. If you also display the last reviews block, you can choose to display it first (before the badge)' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>
			</div>
		{/if}

		{************ VARIOUS SETTINGS ************}
		{if !empty($sDisplay) && $sDisplay == 'list'}
			<h3>{l s='Product star ratings in list pages (e.g: category / brand / search etc...)' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='There are 2 ways to apply it, either by using the PrestaShop hook and leave the option activated below or by including the code below by yourself' mod='gsnippetsreviews'}.">
						<strong>{l s='How to display review stars in your product-list.tpl' mod='gsnippetsreviews'}</strong>
					</span> :
				</label>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<select name="bt_stars-review" id="bt_stars-review" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<option value="">...</option>
						<option value="hook" selected="selected">{l s='Use "displayProductListReviews" hook' mod='gsnippetsreviews'}</option>
						<option value="yourself">{l s='Copy / paste the code below' mod='gsnippetsreviews'}</option>
					</select>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='There are 2 ways to apply it, either by using the PrestaShop hook and leave the option activated below or by including the code below by yourself' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>

			<div id="bt_display-options">
				<div class="clr_10"></div>
				{* USE CASE - handle stars by PS hook *}
				<div id="bt_div-stars-hook" style="display: none;">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>{l s='Display review stars in the product-list.tpl' mod='gsnippetsreviews'}</strong> :</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_display-stars-in-list" id="bt_display-stars-in-list_on" value="1" {if !empty($bDisplayStarsInList)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-use-snippets-prodlist', null, null, true, true);" />
							<label for="bt_display-stars-in-list_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_display-stars-in-list" id="bt_display-stars-in-list_off" value="0" {if empty($bDisplayStarsInList)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-use-snippets-prodlist', null, null, true, false);" />
							<label for="bt_display-stars-in-list_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					</div>

					<div id="bt_disp-use-snippets-prodlist" style="display: {if !empty($bDisplayStarsInList)}block{else}none{/if};">

						<div class="clr_10"></div>
						{* USE CASE - for PS 17 and under PS1710, the hook displayProductListReviews doesn't exist, so the merchants need to include it themselves by following our FAQ *}
						{if !empty($bPS17) && empty($bPS1710)}
						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-warning">
									{l s='IMPORTANT NOTE: We have identified you are on PS version between 1.7 and 1.7.1.0, so your version doesn\'t handle the hook "displayProductListReviews" in the standard theme, that\'s why you absolutely need to follow ou FAQ on how to include it in your theme and display review stars in the product list template here:' mod='gsnippetsreviews'}
									<div class="clr_5"></div>
									<a class="badge badge-info" href="{$smarty.const._GSR_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=152&lg={$sCurrentLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ: How do I display the review stars in the product list?' mod='gsnippetsreviews'}</a>
								</div>
							</div>
						</div>
						{/if}

						<div class="clr_20"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, empty stars will be displayed when any rating has been done for the current product in the product list.' mod='gsnippetsreviews'}">
									<strong>{l s='Display empty stars by default in the product list' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_display-empty-stars" id="bt_display-empty-stars_on" value="1" {if !empty($bDisplayEmptyRating)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-befirst-text', null, null, true, true);" />
									<label for="bt_display-empty-stars_on" class="radioCheck">
										{l s='Yes' mod='gsnippetsreviews'}
									</label>
									<input type="radio" name="bt_display-empty-stars" id="bt_display-empty-stars_off" value="0" {if empty($bDisplayEmptyRating)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-befirst-text', null, null, true, false);" />
									<label for="bt_display-empty-stars_off" class="radioCheck">
										{l s='No' mod='gsnippetsreviews'}
									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, empty stars will be displayed when any rating has been done for the current product in the product list.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>

						<div id="bt_disp-befirst-text" style="display: {if !empty($bDisplayEmptyRating)}block{else}none{/if};">
							<div class="form-group">
								<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, a custom message will be displayed to invite your customers to be the first to review the product, but of course according to the option "who can review".' mod='gsnippetsreviews'}">
									<strong>{l s='Display a custom message with empty stars' mod='gsnippetsreviews'}</strong>
								</span> :
								</label>
								<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_display-befirst-msg" id="bt_display-befirst-msg_on" value="1" {if !empty($bDisplayBeFirstMessage)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_use-befirst-text', null, null, true, true);" />
									<label for="bt_display-befirst-msg_on" class="radioCheck">
										{l s='Yes' mod='gsnippetsreviews'}
									</label>
									<input type="radio" name="bt_display-befirst-msg" id="bt_display-befirst-msg_off" value="0" {if empty($bDisplayBeFirstMessage)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_use-befirst-text', null, null, true, false);" />
									<label for="bt_display-befirst-msg_off" class="radioCheck">
										{l s='No' mod='gsnippetsreviews'}
									</label>
									<a class="slide-button btn"></a>
								</span>
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, a custom message will be displayed to invite your customers to be the first to review the product, but of course according to the option "who can review".' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>

							<div id="bt_use-befirst-text" style="display: {if !empty($bDisplayBeFirstMessage)}block{else}none{/if};">
								<div class="form-group ">
									<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
										<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This allows you to set a predefined text that will constitute the text beside the empty stars' mod='gsnippetsreviews'}.">
											<strong>{l s='Custom message' mod='gsnippetsreviews'}</strong>
										</span> :
									</label>
									<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
										{foreach from=$aLangs item=aLang}
											<div id="bt_div-befirst-text_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
												<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
													<input type="text" id="bt_befirst-text_{$aLang.id_lang|intval}" name="bt_befirst-text_{$aLang.id_lang|intval}" {if !empty($aBeFirst)}{foreach from=$aBeFirst key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
												</div>
												<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
													<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
													<ul class="dropdown-menu">
														{foreach from=$aLangs item=aLang}
															<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
														{/foreach}
													</ul>
													<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This allows you to set a predefined text that will constitute the text beside the empty stars' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
												</div>
											</div>
										{/foreach}
									</div>
								</div>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you display stars rating in the product-list.tpl, you have the choice between 2 cases: 1/ display stars alone, 2/ display stars + numeric average + total of ratings' mod='gsnippetsreviews'}">
									<strong>{l s='How do you want to display stars rating?' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<select name="bt_stars-display-mode" id="bt_stars-display-mode" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<option value="1" {if $iStarDisplayMode == 1}selected="selected"{/if}>{l s='Display stars only' mod='gsnippetsreviews'}</option>
									<option value="2" {if $iStarDisplayMode == 2}selected="selected"{/if}>{l s='Display stars + numeric average + total of ratings' mod='gsnippetsreviews'}</option>
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you display stars rating in the product-list.tpl, you have the choice between 2 cases: 1/ display stars alone, 2/ display stars + numeric average + total of ratings' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-warning">
									{l s='IMPORTANT NOTE: If you have selected "display stars only" above, both options below won\'t be activated and you couldn\'t display rich snippets rating tags associated to each product in the product list.' mod='gsnippetsreviews'}
								</div>
							</div>
						</div>

						<h4>{l s='Rich Snippets Rating in list page' mod='gsnippetsreviews'}</h4>
						<div class="clr_hr"></div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, you should check if your theme already includes rich snippets "product" or not in the product-list.tpl. In that way, our module will make your module\'s rich snippets "rating" perfectly compatible with your existing rich snippets tags.' mod='gsnippetsreviews'}">
									<strong>{l s='Display rich snippets "rating"' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_use-snippets-prodlist" id="bt_use-snippets-prodlist_on" value="1" {if !empty($bUseSnippetsProdList)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-has-snippets-prodlist', null, null, true, true);" />
									<label for="bt_use-snippets-prodlist_on" class="radioCheck">
										{l s='Yes' mod='gsnippetsreviews'}
									</label>
									<input type="radio" name="bt_use-snippets-prodlist" id="bt_use-snippets-prodlist_off" value="0" {if empty($bUseSnippetsProdList)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_disp-has-snippets-prodlist', null, null, true, false);" />
									<label for="bt_use-snippets-prodlist_off" class="radioCheck">
										{l s='No' mod='gsnippetsreviews'}
									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='When you activate this option, you should check if your theme already includes rich snippets "product" or not in the product-list.tpl. In that way, our module will make your module\'s rich snippets "rating" perfectly compatible with your existing rich snippets tags.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group" id="bt_disp-has-snippets-prodlist" style="display: {if !empty($bUseSnippetsProdList)}block{else}none{/if};">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Do not forget when you activate it, you should know first if your theme already includes rich snippets "product" tags in the product-list.tpl. You just need to do a test of your pages that using product-list.tpl by doing copy/paste of the URL of these pages in the google rich snippets tool. Then you\'ll be able to say yes or no with the button below as well as our module will include only rich snippets "rating" or "rating" + "product".' mod='gsnippetsreviews'}">
									<strong>{l s='Do you have rich snippets "product" in your product-list.tpl?' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="bt_has-snippets-prodlist" id="bt_has-snippets-prodlist_on" value="1" {if !empty($bHasSnippetsProdList)}checked="checked"{/if}  />
									<label for="bt_has-snippets-prodlist_on" class="radioCheck">
										{l s='Yes' mod='gsnippetsreviews'}
									</label>
									<input type="radio" name="bt_has-snippets-prodlist" id="bt_has-snippets-prodlist_off" value="0" {if empty($bHasSnippetsProdList)}checked="checked"{/if} />
									<label for="bt_has-snippets-prodlist_off" class="radioCheck">
										{l s='No' mod='gsnippetsreviews'}
									</label>
									<a class="slide-button btn"></a>
								</span>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Do not forget when you activate it, you should know first if your theme already includes rich snippets "product" tags in the product-list.tpl. You just need to do a test of your pages that using product-list.tpl by doing copy/paste of the URL of these pages in the google rich snippets tool. Then you\'ll be able to say yes or no with the button below as well as our module will include only rich snippets "rating" or "rating" + "product".' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<h4>{l s='Advanced displaying tool' mod='gsnippetsreviews'}</h4>
						<div class="clr_hr"></div>
						<div class="clr_20"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">&nbsp;</label>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
								<div class="alert alert-info">
									{l s='You may have some issues due to your theme around the stars + text rendering in the product list page, then these options below will offer you to fit the stars + text rendering close to the best rendering to your theme.' mod='gsnippetsreviews'}
									<div class="clr_5"></div>
									<span class="red-text">{l s='IMPORTANT NOTE: only use these options below if you are technical. If not, please advise your technical contact or web agency to set them as the best way as possible to fit to your theme.' mod='gsnippetsreviews'}</span>
								</div>
							</div>
						</div>

						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the star size by decreasing or increasing the value below in "em" unit. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.' mod='gsnippetsreviews'}">
									<strong>{l s='Adjust the stars size' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_stars-size" id="bt_stars-size" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									{foreach from=$aStarSizes name=col key=sKey item=iStarSize}
										<option value="{$sKey|escape:'htmlall':'UTF-8'}" {if !empty($iSelectStarSize) && $iSelectStarSize == $sKey}selected="selected"{/if}>{$iStarSize|floatval} em</option>
									{/foreach}
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the star size by decreasing or increasing the value below in "em" unit. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the text size by decreasing or increasing the value below in "px" unit. The defined value will load the matching css class included into the "hook.css" css file.' mod='gsnippetsreviews'}">
									<strong>{l s='Adjust the text size' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_text-size" id="bt_text-size" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									{foreach from=$aTextSizes name=col key=iPos item=iTextSize}
										<option value="{$iTextSize|intval}" {if !empty($iSelectTextSize) && $iSelectTextSize == $iTextSize}selected="selected"{/if}>{$iTextSize|intval} px</option>
									{/foreach}
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Maybe you have an integration issue about stars and text displayed on 2 lines, so you also can adjust the text size by decreasing or increasing the value below in "px" unit. The defined value will load the matching css class included into the "hook.css" css file.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
						<div class="clr_10"></div>

						<div class="form-group">
							<label class="control-label col-xs-2 col-md-3 col-lg-3">
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='In most cases, stars and text will be displayed together and it looks center-aligned well, so the default value is 0. Sometimes this content is not aligned center in the product block, so you can play and adjust the padding left to pull stars and text in pixel to the middle as much as possible. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.' mod='gsnippetsreviews'}">
									<strong>{l s='Adjust the div stars padding-left' mod='gsnippetsreviews'}</strong>
								</span> :
							</label>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<select name="bt_div-stars-padding" id="bt_div-stars-padding" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									{foreach from=$aStarsPaddingLeft name=col key=iPos item=iPaddingLet}
										<option value="{$iPaddingLet|intval}" {if isset($iStarPaddingLeft) && $iStarPaddingLeft == $iPaddingLet}selected="selected"{/if}>{$iPaddingLet|intval} px</option>
									{/foreach}
								</select>
								<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='In most cases, stars and text will be displayed together and it looks center-aligned well, so the default value is 0. Sometimes this content is not aligned center in the product block, so you can play and adjust the padding left to pull stars and text in pixel to the middle as much as possible. The defined value will load the matching css class included into the "jquery.star-rating.css" css file.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
							</div>
						</div>
					</div>
				</div>

				{* USE CASE - handle stars by yourself *}
				<div id="bt_div-stars-yourself" style="display: none;">
					<div class="clr_20"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3"></label>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="alert alert-info">
								{l s='You can also have the average rating appear on list pages (e.g: category / brand / search etc...) for each product (guaranteed to work on the default PrestaShop theme ONLY). To do so, simply copy and paste the code below in the product-list.tpl template of your theme, right after the'  mod='gsnippetsreviews'}  &lt;p class="product_desc"&gt;&lt;/p&gt; {l s='tag of the product description' mod='gsnippetsreviews'}:<br /><br />
								<strong style="color: red; font-weight: bold;">{l s='IMPORTANT NOTE:' mod='gsnippetsreviews'}</strong> {l s='This is very technical, and if you are not an integrator or webmaster, simply ignore this section' mod='gsnippetsreviews'}<br /><br />
								<pre style="font-family: 'Courier New', Courier, monospace;">{$sIncludingCode|escape:'UTF-8'}</pre><br />
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}

		<div class="clr_20"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-review-{$sDisplay|escape:'htmlall':'UTF-8'}"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"><button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-reviews-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-review-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-review-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oReviewsCallBack, 'review-{$sDisplay|escape:'htmlall':'UTF-8'}', 'review');return false;"><i class="process-icon-save"></i>{l s='Update' mod='gsnippetsreviews'}</button></div>
		</div>
	</form>
	<div class="clr_20"></div>
</div>

{literal}
<script type="text/javascript">
	// handle stars reviews type
	$("#bt_stars-review").bind('change', function (event) {
		$("#bt_stars-review option:selected").each(function () {
			switch ($(this).val()) {
				case 'hook' :
					$("#bt_display-options").show();
					$("#bt_div-stars-hook").show();
					$("#bt_div-stars-yourself").hide();
					break;
				case 'yourself' :
					$("#bt_display-options").show();
					$("#bt_div-stars-hook").hide();
					$("#bt_div-stars-yourself").show();
					break;
				default:
					$("#bt_display-options").hide();
					$("#bt_div-stars-hook").hide();
					$("#bt_div-stars-yourself").hide();
					break;
			}
		});
	}).change();

	// handle fb kind to use
	$("#bt_fb-button-type").bind('change', function (event) {
		$("#bt_fb-button-type option:selected").each(function () {
			switch ($(this).val()) {
				case '1' :
					$("#bt_div-fb-warning-msg").hide();
					break;
				case '2' :
					$("#bt_div-fb-warning-msg").show();
					break;
				case '3' :
					$("#bt_div-fb-warning-msg").show();
					break;
				default:
					$("#bt_div-fb-warning-msg").hide();
					break;
			}
		});
	}).change();

	{/literal}
	{if !empty($aImages)}
	{literal}
	$("#bt_picto").bind('change', function (event) {
		$("#bt_picto option").each(function (i) {
			if ($(this).attr('selected')) {
				$("#bt_picto-" + $(this).val()).css('display', 'inline');
			}
			else {
				$("#bt_picto-" + $(this).val()).css('display', 'none');
			}
		});
	});
	{/literal}
	{/if}
	{literal}

	// handle stars reviews type
	$("#bt_reviews-display-mode").bind('change', function (event) {
		$("#bt_reviews-display-mode option:selected").each(function () {
			switch ($(this).val()) {
				case 'tabs17' :
					$("#bt_display-theme-warning").show();
					break;
				default:
					$("#bt_display-theme-warning").hide();
					break;
			}
		});
	}).change();

	//bootstrap components init
	$('.label-tooltip, .help-tooltip').tooltip();
	$('.dropdown-toggle').dropdown();
</script>
{/literal}