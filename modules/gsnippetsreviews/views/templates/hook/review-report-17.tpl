{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<!-- GSR - Review report form -->
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap block">
	{if !empty($bDisplayForm)}
		<div id="comment-form">
			<form method="post" id="bt_report-form" name="bt_report-form" onsubmit="oGsr.form('bt_report-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'bt_report-form', 'bt_report-form', false, false, null, 'report', 'report');return false;">
				<input type="hidden" name="sAction" value="{$aQueryParams.reportReview.action|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="sType" value="{$aQueryParams.reportReview.type|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="iId" value="{$aReview.id|intval}" />
				<input type="hidden" name="iCustomerId" value="{$iCustomerId|intval}" />
				<input type="hidden" name="btKey" value="{$sSecureKey|escape:'htmlall':'UTF-8'}" />

				<h1 class="h1 title">{l s='Report an abuse' mod='gsnippetsreviews'}</h1>

				<div class="page-product-box">
					<div class="form-group">
						<label for="inputTitle" class="control-label">{l s='Title' mod='gsnippetsreviews'} : </label>
						<div>
							<input class="form-control" id="disabledInput" type="text" placeholder="{$aReview.data.sTitle|escape:'htmlall':'UTF-8'}" disabled>
						</div>
						<div class="clr_20"></div>
						<label for="inputComment" class="control-label">{l s='Comment' mod='gsnippetsreviews'} : </label>
						<div>
							<textarea class="form-control height200" id="disabledTextArea" disabled>{$aReview.data.sComment|escape:'UTF-8'}</textarea>
						</div>
					</div>

					<div class="clr_20"></div>

					<h3>{l s='Your reason' mod='gsnippetsreviews'}</h3>
					<div class="clr_hr"></div>

					<div class="form-group">
						<label for="content" class="control-label"><sup class="required">*</sup>&nbsp;{l s='Comment' mod='gsnippetsreviews'} : </label>
						<div>
							<textarea class="form-control height200" rows="3" name="bt_report-comment" id="bt_report-comment"></textarea>
							<span class="help-block"><sup class="required">*</sup> {l s='Required fields' mod='gsnippetsreviews'}</span>
						</div>
					</div>
				</div>

				<div class="clr_5"></div>

				<div id="bt_error-report"></div>

				<div class="clr_10"></div>

				<p align="center" class="report-button-margin">
					<button name="bt_comment-button" class="btn btn-primary" value="{l s='Report' mod='gsnippetsreviews'}"  onclick="oGsr.form('bt_report-form', '{$sMODULE_URI|escape:'htmlall':'UTF-8'}', '', 'bt_report-form', 'bt_report-form', false, false, null, 'report', 'report');return false;" ><i class="material-icons">&#xE876;</i>{l s='Report' mod='gsnippetsreviews'}</button>
					<button class="btn btn-secondary" value="{l s='Cancel' mod='gsnippetsreviews'}"  onclick="$.fancybox.close();return false;" >{l s='Cancel' mod='gsnippetsreviews'}</button>
				</p>
			</form>
		</div>
		<div id="bt_loading-div-report" style="display: none;">
			<div class="alert alert-info">
				<p class="text-center"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
				<p class="text-center">{l s='Abuse reporting is in progress ...' mod='gsnippetsreviews'}</p>
			</div>
		</div>
	{elseif $sForbiddenMsg == 'exist'}
		<div class="alert alert-warning">
			<p class="text-center">{l s='You cannot report this review because somebody has already posted a report' mod='gsnippetsreviews'}</p>
		</div>
	{elseif $sForbiddenMsg == 'customer'}
		<div class="alert alert-warning">
			<p class="text-center">{l s='You cannot report this review because you are not logged as a customer' mod='gsnippetsreviews'}</p>
			<div class="clr_10"></div>
			<p class="text-center"><a class="btn btn-info" href="{$sLoginURI|escape:'htmlall':'UTF-8'}"><i class="icon-star-empty"></i> {l s='Log in / sign up' mod='gsnippetsreviews'}</a></p>
		</div>
	{elseif $sForbiddenMsg == 'secure'}
		<div class="alert alert-warning">
			{l s='There is an internal server error (unsecure content)' mod='gsnippetsreviews'} !
		</div>
	{/if}
</div>
<!-- /GSR - Review report form -->