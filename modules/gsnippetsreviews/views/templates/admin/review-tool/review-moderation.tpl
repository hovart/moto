{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div class="bootstrap">
	{literal}
	<script type="text/javascript">
		function manageReviewCheckbox(id) {
			if ($('#' + id).attr("checked") == "checked") {
				$('#' + id).removeAttr("checked");
			}
			else if ($('#' + id).val() != '') {
				$('#' + id).attr("checked", "checked");
			}
		}
	</script>
	{/literal}

	<h3>{l s='Moderation' mod='gsnippetsreviews'}</h3>

	{if !empty($bUpdate)}
		<div class="clr_10"></div>
		{include file="`$sConfirmInclude`"}
	{elseif !empty($aErrors)}
		<div class="clr_10"></div>
		{include file="`$sErrorInclude`"}
	{/if}

	{* USE CASE - review deleted OK *}
	{if !empty($bDelete)}
		<div class="clr_20"></div>
		<div class="alert alert-success">
			{l s='Your review has been deleted well'  mod='gsnippetsreviews'}.
		</div>
	{/if}

	{if !empty($aRatings[0])}
	<div class="clr_20"></div>

	<div class="alert alert-info">
		{l s='For each of the reviews below, you can click the magnifier glass icon to view the details'  mod='gsnippetsreviews'}.
	</div>

	<div class="clr_20"></div>

	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-12 col-md-1 col-lg-1 pull-left">
			<span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='This drop-down list allows you to execute bulk actions on your reviews' mod='gsnippetsreviews'}.">
				<strong>{l s='Bulk actions' mod='gsnippetsreviews'} :</strong>
			</span>
		</label>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
				<select id="bt_select-action" name="bt_select-action">
					<option value="activate">{l s='activate' mod='gsnippetsreviews'}</option>
					<option value="deactivate">{l s='deactivate' mod='gsnippetsreviews'}</option>
					<option value="delete">{l s='delete' mod='gsnippetsreviews'}</option>
				</select>
			</div>
			<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
				&nbsp;<button type="submit" name="bt_moderation-button" class="btn btn-info" onclick="javascript: globalCheck = confirm('{l s='You are about to activate / deactivate / delete reviews in bulk. NOTE: if you delete reviews, this is a permanent and irreversible action. Are you sure you want to do this' mod='gsnippetsreviews'} ?');if(!globalCheck)return false;oGsr.form('bt_moderation-form', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_settings-moderation', 'bt_settings-moderation', false, false, null, 'moderation', 'moderation');">{l s='Go' mod='gsnippetsreviews'}</button>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This drop-down list allows you to execute bulk actions on your reviews' mod='gsnippetsreviews'}.">&nbsp;<span class="icon-question-sign"></span></span>
			</div>
		</div>
	</div>

	<div class="clr_10"></div>

	<form class="form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bt_moderation-form" name="bt_moderation-form" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" onsubmit="oGsr.form('bt_moderation-form', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_settings-moderation', 'bt_settings-moderation', false, false, null, 'moderation', 'moderation');return false;">
		<input type="hidden" id="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" id="sAction" name="sAction" value="{$aQueryParams.moderationAll.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" id="sType" name="sType" value="{$aQueryParams.moderationAll.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" id="bCheckReview" name="bCheckReview" value="1" />
		<input type="hidden" id="iPage" name="iPage" value="{$iCurrentPage|intval}" />

		<table class="table table-striped">
			<thead>
			<tr class="nodrag nodrop">
				<th class="center">
					<div class="pull-left">
						<span class="btn btn-default btn-xs" id="bt_checkall" onclick="return oGsr.selectAll('.checkbox', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gsnippetsreviews'}</span> - <span class="btn btn-default btn-xs" id="bt_uncheckall" onclick="return oGsr.selectAll('.checkbox', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gsnippetsreviews'}</span>
					</div>
				</th>
				<th class="center">
					{l s='Details' mod='gsnippetsreviews'}
				</th>
				<th class="center">{l s='Adding date' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=dateAdd&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'dateAdd' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=dateAdd&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'dateAdd' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='ID' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=id&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'id' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=id&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'id' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Customer' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=customer&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'customer' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=customer&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'customer' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Product' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=product&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'product' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=product&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'product' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Rating' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=ranking&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'ranking' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=ranking&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'ranking' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Title' mod='gsnippetsreviews'}</th>
				{if !empty($bMultiShop)}
				<th class="center">
					{l s='Shop' mod='gsnippetsreviews'}
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=shop&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'shop' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=shop&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'shop' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
				</th>
				{/if}
				<th class="center">{l s='Status' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=status&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'status' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
					<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=status&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'status' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Abuse flag' mod='gsnippetsreviews'}
					{if $iNbReview > 1}&nbsp;
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=abuse&sWay=desc&iPage={$iCurrentPage|intval}"><i class="icon-caret-down icon-caret{if !empty($sSortableField) && $sSortableField == 'abuse' && $sWay == 'desc'} current{/if}">&nbsp;</i></a>
						<a href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderationSort.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderationSort.type|escape:'htmlall':'UTF-8'}&sSort=abuse&sWay=asc&iPage={$iCurrentPage|intval}"><i class="icon-caret-up icon-caret{if !empty($sSortableField) && $sSortableField == 'abuse' && $sWay == 'asc'} current{/if}">&nbsp;</i></a>
					{/if}
				</th>
				<th class="center">{l s='Edit' mod='gsnippetsreviews'}</th>
				<th class="center">{l s='Review litigation' mod='gsnippetsreviews'}</th>
				<th class="center">{l s='Delete' mod='gsnippetsreviews'}</th>
			</tr>
			</thead>
			{section loop=$aRatings name=rating}
			<tr>
				<td class="center">
					<input type="checkbox" name="bt_check-rating[]" id="bt_check-rating" value="{$aRatings[rating].id|intval}" class="checkbox" onclick="manageReviewCheckbox('bt_check-review{$smarty.section.rating.iteration|intval}');" />
					<input type="checkbox" name="bt_check-review[]" id="bt_check-review{$smarty.section.rating.iteration|intval}" value="{if !empty($aRatings[rating].review)}{$aRatings[rating].review.id|intval}{else}0{/if}" class="checkbox" style="display: none !important;" />
				</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">
					<i class="icon-zoom-in" title="{l s='see review details' mod='gsnippetsreviews'}"></i>
				</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">{if !empty($aRatings[rating].review)}{$aRatings[rating].review.dateAdd|escape:'UTF-8'}{else}{$aRatings[rating].dateAdd|escape:'UTF-8'}{/if}</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">{$aRatings[rating].id|intval}</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">{$aRatings[rating].firstname|escape:'htmlall':'UTF-8'} {$aRatings[rating].lastname|upper|truncate:2:"."|escape:'htmlall':'UTF-8'}</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">{$aRatings[rating].name|escape:'htmlall':'UTF-8'}</td>
				<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);"><strong>{$aRatings[rating].note|intval}</strong> / {$iMaxRating|intval}</td>
				<td class="center">
					{if !empty($aRatings[rating].review)}
						<a target="_blank" style="text-decoration: underline" href="{$aRatings[rating].review.sReviewUrl|escape:'htmlall':'UTF-8'}">
						{$aRatings[rating].review.data.sTitle|escape:'htmlall':'UTF-8'}
						</a>
					{else}
						<strong>{l s='No Comment posted yet' mod='gsnippetsreviews'}</strong>
					{/if}
				</td>
				{if !empty($bMultiShop)}
					<td class="center" style="cursor: pointer !important;" onclick="$('#bt_review-detail{$smarty.section.rating.iteration|intval}').toggle(800);">{if !empty($aRatings[rating].shopName)}{$aRatings[rating].shopName|escape:'htmlall':'UTF-8'}{else}{$sShopName|escape:'htmlall':'UTF-8'}{/if}</td>
				{/if}
				<td class="pointer center">
					{* USE CASE - review related *}
					{if !empty($aRatings[rating].review)}
					<div id="bt_loading-div-status{$aRatings[rating].review.id|intval}" style="display: none;"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}loader.gif" height="25" width="25" alt="Loading" /></div>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You can click to activate or deactivate the current review, both actions are authorized' mod='gsnippetsreviews'}">
						<div id="bt_set-status{$smarty.section.rating.iteration|intval}" onclick="$('#bt_loading-div-status{$aRatings[rating].review.id|intval}').hide();$('#bt_loading-div-status{$aRatings[rating].review.id|intval}').show();oGsr.ajax('{$sURI|escape:'htmlall':'UTF-8'}', '{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.moderation.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.moderation.type|escape:'htmlall':'UTF-8'}&iReviewId={$aRatings[rating].review.id|intval}', 'bt_set-status{$smarty.section.rating.iteration|intval}','bt_set-status{$smarty.section.rating.iteration|intval}', false, false, 'status{$aRatings[rating].review.id|intval}');">
						{include file="`$sReviewStatusInclude`" bStatus=$aRatings[rating].review.status}
						</div>
					</span>
					{* USE CASE - no review related *}
					{else}
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You do not have any reviews yet' mod='gsnippetsreviews'}">
						<div id="{$sModuleName|escape:'htmlall':'UTF-8'}SetStatus{$smarty.section.rating.iteration|intval}" style="cursor: default;">
							<i class="icon-ban" title="{l s='no review' mod='gsnippetsreviews'}"></i>
						</div>
					</span>
					{/if}
				</td>
				<td class="pointer center">
				{* USE CASE - review related *}
				{if !empty($aRatings[rating].review)}
					<div id="bt_loading-div-abuse{$aRatings[rating].review.id|intval}" style="display: none;"><img src="{$smarty.const._GSR_URL_IMG|escape:'htmlall':'UTF-8'}loader.gif" height="25" width="25" alt="Loading" /></div>
					{* USE CASE - review abusive report *}
					{if !empty($aRatings[rating].review.reportId)}
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Click here to reset the flag and indicate this review is NOT abusive' mod='gsnippetsreviews'}">
						<div id="bt_set-abuse{$smarty.section.rating.iteration|intval}" onclick="$('#bt_loading-div-abuse{$aRatings[rating].review.id|intval}').hide();$('#bt_loading-div-abuse{$aRatings[rating].review.id|intval}').show();oGsr.ajax('{$sURI|escape:'htmlall':'UTF-8'}', '{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.abuse.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.abuse.type|escape:'htmlall':'UTF-8'}&iReviewId={$aRatings[rating].review.id|intval}', 'bt_set-abuse{$smarty.section.rating.iteration|intval}','bt_set-abuse{$smarty.section.rating.iteration|intval}', false, false, 'abuse{$aRatings[rating].review.id|intval}');">
							{include file="`$sReviewStatusInclude`" bStatus=false bWarning=true}
							<i class="icon-pencil" style="font-size:20px;" title="{l s='modify' mod='gsnippetsreviews'}"></i>
						</div>
					</span>
					{* USE CASE - no review abusive report *}
					{else}
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='This icon is not clickable when the review is not flagged as abusive' mod='gsnippetsreviews'}">
						<div id="bt_set-abuse{$smarty.section.rating.iteration|intval}" style="cursor: default;">
							{include file="`$sReviewStatusInclude`" bStatus=true}
						</div>
					</span>
					{/if}
				{else}
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='You do not have any reviews yet' mod='gsnippetsreviews'}">
						<div id="bt_set-abuse{$smarty.section.rating.iteration|intval}" style="cursor: default;">
							<i class="icon-ban" title="{l s='no review' mod='gsnippetsreviews'}"></i>
						</div>
					</span>
				{/if}
				</td>
				<td class="pointer center" style="white-space: nowrap;">
					{if !empty($aRatings[rating].review)}
					<a id="bt_review-edit" class="fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.review.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.review.type|escape:'htmlall':'UTF-8'}&iReviewId={$aRatings[rating].review.id|intval}&iPage={$iCurrentPage|intval}">
						<i class="icon-pencil" style="font-size:20px;" title="{l s='modify' mod='gsnippetsreviews'}"></i>
					</a>
					{else}
						<strong>{l s='No Comment to edit' mod='gsnippetsreviews'}</strong>
					{/if}
				</td>
				<td class="pointer center" style="white-space: nowrap;">
					<a id="bt_reply-edit" class="fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.reply.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reply.type|escape:'htmlall':'UTF-8'}&iRatingId={$aRatings[rating].id|intval}&iPage={$iCurrentPage|intval}">
						<i class="icon-pencil" style="font-size:20px;" title="{l s='modify' mod='gsnippetsreviews'}"></i>
						{if !empty($aRatings[rating].replyData)}
							{if empty($aRatings[rating].data) && (empty($aRatings[rating].review.data.sOldTitle) || empty($aRatings[rating].review.data.sOldComment))}
								<i class="icon-time" style="font-size:20px;" title="{l s='The customer\'s review is pending modification' mod='gsnippetsreviews'}"></i>
							{else}
								<i class="icon-ok-sign text-success" style="font-size:20px;" title="{l s='The customer has changed his review' mod='gsnippetsreviews'}"></i>
							{/if}
						{/if}
					</a>
				</td>
				<td class="pointer center" style="white-space: nowrap;">
					<i class="icon-trash" style="font-size:20px;" title="{l s='delete' mod='gsnippetsreviews'}" onclick="check = confirm('{if !empty($aRatings[rating].review)}{l s='Are you sure to want to delete this rating and review' mod='gsnippetsreviews'}{else}{l s='Are you sure to want to delete this rating' mod='gsnippetsreviews'}{/if} ? {l s='It will be definitively removed from your database' mod='gsnippetsreviews'}');if(!check)return false;oGsr.ajax('{$sURI|escape:'htmlall':'UTF-8'}', '{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.delete.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.delete.type|escape:'htmlall':'UTF-8'}&iRatingId={$aRatings[rating].id|intval}&iPage={$iCurrentPage|intval}', 'bt_settings-moderation', 'bt_settings-moderation');" ></i>
				</td>
			</tr>
			<tr id="bt_review-detail{$smarty.section.rating.iteration|intval}" style="display: none;">
				<td colspan="{if !empty($bMultiShop)}14{else}13{/if}">
					<div class="clr_10"></div>
					<div class="aoWrapPanel">
						<div class="panel aoPanel">
							<p class="aoTitle">{l s='Rating & review\'s detail' mod='gsnippetsreviews'}</p>
							<div class="clr_5"></div>
							<p>
								<span class="badge"><a style="color:#FFF;" href="{$aRatings[rating].customerLink|escape:'htmlall':'UTF-8'}" target="_blank">{$aRatings[rating].firstname|capitalize|escape:'htmlall':'UTF-8'} {$aRatings[rating].lastname|capitalize|escape:'htmlall':'UTF-8'}</a></span>
								<strong>{$aRatings[rating].langTitle|escape:'htmlall':'UTF-8'}</strong>, {l s='posted on' mod='gsnippetsreviews'} <strong>{$aRatings[rating].dateAdd|escape:'UTF-8'}</strong> {if !empty($aRatings[rating].review)}({l s='updated on' mod='gsnippetsreviews'} <strong>{$aRatings[rating].review.dateUpd|escape:'htmlall':'UTF-8'}</strong>){/if}
							</p>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									{* USE CASE - new rating has been done and maybe a new title and comment *}
									{if !empty($aRatings[rating].data.iOldRating) || !empty($aRatings[rating].review.data.sOldTitle) || !empty($aRatings[rating].review.data.sOldComment)}
									<h4>{l s='New review' mod='gsnippetsreviews'}</h4>
									<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
										<div class="col-xs-12 rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
											{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aRatings[rating].note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
											&nbsp;({$aRatings[rating].note|intval} / {$iMaxRating|intval})
										</div>
									</div>
									<div class="clr_10"></div>
									<strong>{if !empty($aRatings[rating].review.data.sOldTitle)}{$aRatings[rating].review.data.sTitle|escape:'htmlall':'UTF-8'}{else}{l s='No title changed' mod='gsnippetsreviews'}{/if}</strong>
									<div class="clr_10"></div>
									<div style="border-left: 5px solid #DFDCDC;"><p style="padding-left: 5px !important;">{if !empty($aRatings[rating].review.data.sOldComment)}{$aRatings[rating].review.data.sComment|escape:'UTF-8'}{else}{l s='No comment changed' mod='gsnippetsreviews'}{/if}</p></div>
									{else}
									<div class="clr_10"></div>
									<div id="bt_display-rating{$smarty.section.rating.iteration|intval}">
										<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
											<div class="col-xs-12 rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
												{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aRatings[rating].note >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
												&nbsp;({$aRatings[rating].note|intval} / {$iMaxRating|intval})
											</div>
										</div>
									</div>
									<div class="clr_10"></div>
									<strong>{if !empty($aRatings[rating].review)}{$aRatings[rating].review.data.sTitle|escape:'htmlall':'UTF-8'}{/if}</strong>
									<div class="clr_10"></div>
									<div style="border-left: 5px solid #DFDCDC;"><p style="padding-left: 5px !important;">{if !empty($aRatings[rating].review)}{$aRatings[rating].review.data.sComment|escape:'UTF-8'}{/if}</p></div>
									{/if}
								</div>
								{if !empty($aRatings[rating].data.iOldRating) || !empty($aRatings[rating].review.data.sOldTitle) || !empty($aRatings[rating].review.data.sOldComment)}
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<h4>{l s='Old review' mod='gsnippetsreviews'}</h4>
									<div class="clr_0"></div>
									<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
										<div class="col-xs-12 rating-{$sRatingClassName|escape:'htmlall':'UTF-8'}">
											{section loop=$iMaxRating name=note}<input type="radio" value="{$smarty.section.note.iteration|intval}" {if $aRatings[rating].data.iOldRating >= $smarty.section.note.iteration}checked="checked"{/if}/><label class="product-tab" for="rating{$smarty.section.note.iteration|intval}" title="{$smarty.section.note.iteration|intval}"></label>{/section}
											&nbsp;({if !empty($aRatings[rating].data.iOldRating)}{$aRatings[rating].data.iOldRating|intval}{else}{$aRatings[rating].note|intval}{/if} / {$iMaxRating|intval})
										</div>
									</div>
									<div class="clr_10"></div>
									{if !empty($aRatings[rating].review.data.sOldTitle) && !empty($aRatings[rating].review.data.sOldComment)}
										<strong>{$aRatings[rating].review.data.sOldTitle|escape:'htmlall':'UTF-8'}</strong>
										<div class="clr_10"></div>
										<div style="border-left: 5px solid #DFDCDC;"><p style="padding-left: 5px !important;">{$aRatings[rating].review.data.sOldComment|escape:'UTF-8'}</p></div>
									{else}
										<strong>{if !empty($aRatings[rating].review)}{$aRatings[rating].review.data.sTitle|escape:'htmlall':'UTF-8'}{/if}</strong>
										<div class="clr_5"></div>
										<div style="border-left: 5px solid #DFDCDC;"><p style="padding-left: 5px !important;">{$aRatings[rating].review.data.sComment|escape:'UTF-8'}</p></div>
									{/if}
								</div>
								{/if}
							</div>
						</div>
					</div>
				</td>
			</tr>
			{/section}
		</table>

		<div class="clr_20"></div>

		{* Pagination *}
		{if $iTotalPage > 1}
		<div class="pagination">
			<ul class="pagination">
				{if $iCurrentPage gt 1}
					{assign var=prev value=$iCurrentPage-1}
				{/if}
				{if $iTotalPage gt 10}
					{if $iCurrentPage gt 5}
						{if $iCurrentPage lte $iTotalPage-5}
							{assign var=nStart value=$iCurrentPage-4}
							{assign var=nEnd value=$iCurrentPage+5}
						{else}
							{assign var=nStart value=$iTotalPage-9}
							{assign var=nEnd value=$iTotalPage+1}
						{/if}
					{else}
						{assign var=nStart value=1}
						{assign var=nEnd value=10}
					{/if}
				{else}
					{assign var=nStart value=1}
					{assign var=nEnd value=$iTotalPage+1}
				{/if}
				{if $iCurrentPage gt 1}
					<li id="pagination_previous"><a href="{$sURI|escape:'htmlall':'UTF-8'}">&laquo;&nbsp;{l s='First' mod='gsnippetsreviews'}</a></li>
					<li id="pagination_previous"><a {if $prev eq 1}href="{$sURI|escape:'htmlall':'UTF-8'}"{else}href="{$sURI|escape:'htmlall':'UTF-8'}&iPage={$prev|intval}"{/if}>&laquo;&nbsp;{l s='Previous' mod='gsnippetsreviews'}</a>
				{/if}
				{if $iCurrentPage gt 10}
					<li class="disabled"><a href="javascript:void(0);">…</a></li>
				{/if}
				{section name=pagination start=$nStart loop=$nEnd}
				{if $smarty.section.pagination.index eq $iCurrentPage}
					<li class="active"><a {if $smarty.section.pagination.last neq true}vSep{/if}" {if $smarty.section.pagination.index eq 1}href="{$sURI|escape:'htmlall':'UTF-8'}"{else}href="{$sURI|escape:'htmlall':'UTF-8'}&iPage={$smarty.section.pagination.index|intval}"{/if}>{$smarty.section.pagination.index|intval}</a></li>
				{else}
					<li><a class="{if $smarty.section.pagination.last neq true}vSep{/if}" {if $smarty.section.pagination.index eq 1}href="{$sURI|escape:'htmlall':'UTF-8'}"{else}href="{$sURI|escape:'htmlall':'UTF-8'}&iPage={$smarty.section.pagination.index|intval}"{/if}>{$smarty.section.pagination.index|intval}</a></li>
				{/if}
				{/section}
				{if $iTotalPage gt 10 && $iCurrentPage lt $iTotalPage}
					<li class="disabled"><a href="javascript:void(0);">…</a></li>
					{*&nbsp;{l s='on' mod='gsnippetsreviews'} {$iTotalPage|intval}*}
				{/if}
				{if $iCurrentPage lt $iTotalPage}
					{assign var=next value=$iCurrentPage+1}
				{/if}
				{if $iCurrentPage lt $iTotalPage}
					<li id="pagination_next"><a href="{$sURI|escape:'htmlall':'UTF-8'}&iPage={$next|intval}">{l s='Next' mod='gsnippetsreviews'}&nbsp;&raquo;</a></li>
					<li id="pagination_next"><a href="{$sURI|escape:'htmlall':'UTF-8'}&iPage={$iTotalPage|intval}">{l s='Last' mod='gsnippetsreviews'}&nbsp;&raquo;</a></li>
				{/if}
			</ul>
		</div>
		{/if}
		{* /Pagination *}

		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
				<div id="bt_error-moderation"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
				{*<button type="submit" class="btn btn-default pull-right" onclick="globalCheck = confirm('{l s='You are about to activate / deactivate / delete reviews in bulk. NOTE: if you delete reviews, this is a permanent and irreversible action. Are you sure you want to do this' mod='gsnippetsreviews'} ?');if(!globalCheck)return false;oGsr.form('bt_moderation-form', '{$sURI|escape:'htmlall':'UTF-8'}', '', 'bt_settings-moderation', 'bt_settings-moderation', false, false, null, 'moderation', 'moderation');"><i class="process-icon-save"></i>{l s='Go' mod='gsnippetsreviews'}</button>*}
			</div>
		</div>
	</form>

	<div class="clr_20"></div>

	{literal}
	<script type="text/javascript">
		$(document).ready(function()
		{
			$("a#bt_review-edit").fancybox({
				'hideOnContentClick' : false,
				'minWidth' : 800
			});
			$("a#bt_reply-edit").fancybox({
				'hideOnContentClick' : false,
				'minWidth' : 800
			});

			$("#bt_select-action").bind('change', function (event)
			{
				$("#bt_select-action option:selected").each(function ()
				{
					switch ($(this).val()) {
						case 'delete' :
							$('#sType').val('{/literal}{$aQueryParams.delete.type|escape:'htmlall':'UTF-8'}{literal}');
							$('#sAction').val('{/literal}{$aQueryParams.delete.action|escape:'htmlall':'UTF-8'}{literal}');
							break;
						default:
							$('#sType').val(''+ $(this).val()+'');
							$('#sAction').val('update');
							break;
					}
				});
			});

			$('.label-tooltip, .help-tooltip').tooltip();
		});
	</script>
	{/literal}
	{else}
	<div class="clr_20"></div>
	<div class="alert alert-info">{l s='There is no reviews to moderate' mod='gsnippetsreviews'}</div>
	{/if}
</div>