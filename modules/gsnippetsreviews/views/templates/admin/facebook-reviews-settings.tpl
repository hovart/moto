{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}

<div class="bootstrap">
	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_form-fb-{$sDisplay|escape:'htmlall':'UTF-8'}" name="bt_form-fb-{$sDisplay|escape:'htmlall':'UTF-8'}" onsubmit="oGsr.form('bt_form-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, null, 'fb-{$sDisplay|escape:'htmlall':'UTF-8'}', 'facebook');return false;">
		<input type="hidden" name="sAction" value="{$aQueryParams.reviewsFB.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.reviewsFB.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sDisplay" id="sFbDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}voucher{/if}" />
		<input type="hidden" name="sVoucherType" value="share" />

		{************ FB INCENTIVE VOUCHER  ************}
		{if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'voucher')}
			<h3>{l s='Reward your customers when they share their product reviews on their Facebook account' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			{if !empty($bShareVoucher) && empty($bEnableSocialButton)}
				<div class="clr_10"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-2"></label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<div class="alert alert-warning">
							{l s='Because you have activated the "offer a voucher for sharing a review" feature below, you should definitely also enable the "display share buttons" option in the "reviews" tab (product page sub-tab)'  mod='gsnippetsreviews'}
						</div>
					</div>
				</div>
			{/if}

			<div class="clr_10"></div>

			<div id="share">
				{include file="`$sVoucherForm`" type="share"}
			</div>
		{/if}

		{************ FB WALL POST ************}
		{if !empty($sDisplay) && $sDisplay == 'post'}
			<h3>{l s='For posting on your Facebook Fan page' mod='gsnippetsreviews'}</h3>

			{if !empty($bUpdate)}
				<div class="clr_10"></div>
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				<div class="clr_10"></div>
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="clr_10"></div>

			{if !empty($bFbPsWallPosts)}
				<div class="alert alert-info">
					{l s='This section lets you integrate with our Facebook PS Wall Posts module, and allows you to have any ratings and comments posted on a product on your PrestaShop website to be also automatically posted to your Facebook fan page. If you have enabled comments moderation in the "Review Settings" tab, it will only be posted once you approve the rating and comment in the moderation interface.' mod='gsnippetsreviews'}
				</div>

				<div class="clr_20"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Select yes if you wish to have customer rating and comments published on your Facebook page. If you have enabled comments moderation in the "Review Settings" tab, it will only be posted once you approve the rating and comment in the moderation interface.' mod='gsnippetsreviews'}">
							<strong>{l s='Enable Facebook post' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_enable-fb-post" id="bt_enable-fb-post_on" value="1" {if !empty($bEnableFbPost)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_fb-post', null, null, true, true);" />
							<label for="bt_enable-fb-post_on" class="radioCheck">
								{l s='Yes' mod='gsnippetsreviews'}
							</label>
							<input type="radio" name="bt_enable-fb-post" id="bt_enable-fb-post_off" value="0" {if empty($bEnableFbPost)}checked="checked"{/if} onclick="oGsr.changeSelect(null, 'bt_fb-post', null, null, true, false);" />
							<label for="bt_enable-fb-post_off" class="radioCheck">
								{l s='No' mod='gsnippetsreviews'}
							</label>
							<a class="slide-button btn"></a>
						</span>
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Select yes if you wish to have customer rating and comments published on your Facebook page. If you have enabled comments moderation in the "Review Settings" tab, it will only be posted once you approve the rating and comment in the moderation interface.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
					</div>
				</div>

				<div id="bt_fb-post" style="display: {if !empty($bEnableFbPost)}block{else}none{/if};" class="form-group">
					<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 required">
						<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This is the generic text that will be used to post on Facebook. The parts between' mod='gsnippetsreviews'}{literal} { } {/literal}{l s='will be replaced by the person\'s real name and by the real comment posted, if one was posted.' mod='gsnippetsreviews'}">
							<strong>{l s='Facebook post\'s template text' mod='gsnippetsreviews'}</strong>
						</span> :
					</label>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 form-inline no-label-width">
						{foreach from=$aLangs item=aLang}
							<div id="bt_fb-post-phrase_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<label class="control-label">{literal}{John .D}{/literal}</label>
									<input class="fixed-width-xxl" type="text" id="bt_tab-fb-post-phrase_{$aLang.id_lang|intval}" name="bt_tab-fb-post-phrase_{$aLang.id_lang|intval}" {if !empty($aFbPostPhrase)}{foreach from=$aFbPostPhrase key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
									<label class="control-label">&nbsp;: ★★★★☆</label>
									<input class="fixed-width-sm" type="text" id="bt_tab-fb-post-label_{$aLang.id_lang|intval}" name="bt_tab-fb-post-label_{$aLang.id_lang|intval}" {if !empty($aFbPostLabel)}{foreach from=$aFbPostLabel key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
									<label class="control-label">&nbsp;: {l s='{customer_comment}' mod='gsnippetsreviews'}</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
									<ul class="dropdown-menu">
										{foreach from=$aLangs item=aLang}
											<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
										{/foreach}
									</ul>
									<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This is the generic text that will be used to post on Facebook. The parts between' mod='gsnippetsreviews'}{literal} { } {/literal}{l s='will be replaced by the person\'s real name and by the real comment posted, if one was posted.' mod='gsnippetsreviews'}">&nbsp;<span class="icon-question-sign"></span></span>
								</div>
							</div>
						{/foreach}
					</div>
				</div>
				<div class="clr_20"></div>
			{else}
				<div class="alert alert-danger">
					{l s='This feature requires you to have purchased, installed and correctly configured our Facebook PS Wall Posts module. You may purchase it on:' mod='gsnippetsreviews'} <a href="{l s='http://prestashop-modules.businesstech.fr/en/prestashop-modules-social-networks-facebook/10-facebook-ps-wall-post.html' mod='gsnippetsreviews'}" target="_blank"><strong>Business Tech</strong></a> {l s='or' mod='gsnippetsreviews'} <a href="{l s='http://addons.prestashop.com/en/social-commerce-facebook-prestashop-modules/4429-Facebook-PS-Wall-Posts.html' mod='gsnippetsreviews'}" target="_blank"><strong>PrestaShop Addons</strong></a>. {l s='Once this is all set, you will see the configuration options instead of this red text.' mod='gsnippetsreviews'}
				</div>
			{/if}
		{/if}

		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-fb-{$sDisplay|escape:'htmlall':'UTF-8'}"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
				<button class="btn btn-default pull-right" onclick="oGsr.form('bt_form-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_settings-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_settings-fb-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, null, 'fb-{$sDisplay|escape:'htmlall':'UTF-8'}', 'facebook');return false;"><i class="process-icon-save"></i>{l s='Update' mod='gsnippetsreviews'}</button>
			</div>
		</div>
	</form>

	<div class="clr_20"></div>

	<script type="text/javascript">
		//bootstrap components init
		$('.label-tooltip, .help-tooltip').tooltip();
		$('.dropdown-toggle').dropdown();
	</script>
</div>