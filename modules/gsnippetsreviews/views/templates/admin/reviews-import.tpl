{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="bt_new-comment-form">
		<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post" id="bt_comment-import" name="bt_comment-import" onsubmit="oGsr.form('bt_comment-import', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_new-comment-form', 'bt_new-comment-form', false, false, null, 'import-comment', 'import-comment');return false;">
			<input type="hidden" name="sAction" value="{$aQueryParams.import.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.import.type|escape:'htmlall':'UTF-8'}" />

			<h2>{l s='Import your reviews from "Product Comments" module' mod='gsnippetsreviews'}</h2>

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">{l s='Number of reviews to import' mod='gsnippetsreviews'} :</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<table class="table table-responsive table-striped">
						<thead>
							<tr>
								<th><span class="title_box center">{l s='Total' mod='gsnippetsreviews'}</span></th>
								<th><span class="title_box center">{l s='Moderated' mod='gsnippetsreviews'}</span></th>
								<th><span class="title_box center">{l s='Non-moderated' mod='gsnippetsreviews'}</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-xs-12 col-md-12 col-md-4 col-lg-4 center success">{$iTotalReviews|intval}</td>
								<td class="col-xs-12 col-md-12 col-md-1 col-lg-4 center info">
									{$iTotalValidReviews|intval}
								</td>
								<td class="col-xs-12 col-md-12 col-md-4 col-lg-4 center warning">
									{$iTotalInvalidReviews|intval}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="clr_20"></div>

			<h3>{l s='Choose the way to import your reviews' mod='gsnippetsreviews'}</h3>

			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="alert alert-info">
						{l s='Please select which reviews you wish to import from the "product comments" module (unmoderated reviews, moderated reviews or both)' mod='gsnippetsreviews'}.
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<select name="bt_import-type" id="bt_import-type">
						<option value="0" {if $iCommentsImportType == 0}selected="selected"{/if}><strong>{l s='Import unmoderated comments only' mod='gsnippetsreviews'}</strong></option>
						<option value="1" {if $iCommentsImportType == 1}selected="selected"{/if}><strong>{l s='Import moderated comments only' mod='gsnippetsreviews'}</strong></option>
						<option value="2" {if $iCommentsImportType == 2}selected="selected"{/if}><strong>{l s='Import both' mod='gsnippetsreviews'}</strong></option>
					</select>
				</div>
			</div>

			<div class="clr_20"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="alert alert-info">
						{l s='In the rare event that a review from the "product comments" module does not have a numeric rating, you can set the default rating below (we recommend something neutral such as 3 / 5)' mod='gsnippetsreviews'}.
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</label>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<select name="bt_import-rating" id="bt_import-rating">
						{section name=rating start=1 loop=$iMaxRating+1}
							<option value="{$smarty.section.rating.index|intval}" {if $smarty.section.rating.last}selected="selected"{/if}>{$smarty.section.rating.index|intval}</option>
						{/section}
					</select>
				</div>
			</div>

			<div class="clr_10"></div>
			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<div id="bt_error-import-comment"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="pull-right">
						{if empty($iCustomerNote) || !empty($bEnableComments)}
						<button class="btn btn-success btn-lg" name="bt_comment-import-button" onclick="oGsr.form('bt_comment-import', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_new-comment-form', 'bt_new-comment-form', false, false, null, 'import-comment', 'import-comment');return false;" >{l s='Import' mod='gsnippetsreviews'}</button>
						{/if}
						<button class="btn btn-danger btn-lg"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gsnippetsreviews'}</button>
					</div>
				</div>
			</div>

		</form>
	</div>
	<div class="clr_20"></div>
	<div id="bt_loading-div-import-comment" style="display: none;">
		<div class="alert alert-info">
			<p class="center"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p class="center">{l s='Your reviews import is in progress' mod='gsnippetsreviews'}</p>
		</div>
	</div>
</div>
