function saveProductSelection(order) {
	if(!order) order = 'orderProductSelection';
	$jqPm.get(base_config_url+'&'+order, { },function(data) {
		show_info('saveorder',data);
	});
}
var queue = false;
var next = false;
function show_info(id,content) {
	if(queue){next = new Array(id,content);return;}
	queue = true;
	if($jqPm('#'+id).is("div") === false)
		$jqPm('body').append('<div id="'+id+'" class="info_screen ui-state-hover"></div>');
	else return
	$jqPm('#'+id).html(content);
	$jqPm('#'+id).slideDown('slow');

	setTimeout(function() { $jqPm('#'+id).slideUp('slow',function() {$jqPm('#'+id).remove();queue = false;if(next){show_info(next[0],next[1]);next = false;} }) },2000);
}
function addProductToSelection()
{
	var valueToAdd = $jqPm('#selectProductSelection').val();

	if (valueToAdd == '0')
		return false;

	var $jqPmdivProductSelection = $jqPm('#divProductSelection');

	pos = valueToAdd.indexOf('-');
	var productId = valueToAdd.slice(0, pos);
	var productName = valueToAdd.slice(pos + 1);

	/* delete product from select + add product line to the div, input_name, input_ids elements */
	$jqPm('#selectProductSelection option[value=' + valueToAdd + ']').remove();
	$jqPmdivProductSelection.html($jqPmdivProductSelection.html() +'<div id="orderProductSelection-'+productId+'" class="table list-item"><div style="width: 35px; float: left; padding-top: 9px;" class="dragHandle"><img src="../modules/pm_crosssellingoncart/images/arrow.png"></div>'+ productName + ' <span onclick="delProductToSelection(' + productId + ');"  class="delSelection"><img src="../img/admin/delete.gif" /></span></div>');
	var order = $jqPm("#divProductSelection").sortable("serialize");
	saveProductSelection(order);
	$jqPm('#poc_noproduct').fadeOut('fast');
}
function delProductToSelection(id)
{
	$jqPm('#orderProductSelection-'+id).remove();
	var order = $jqPm("#divProductSelection").sortable("serialize");
	if(!order)
		$jqPm('#poc_noproduct').fadeIn('fast');
	saveProductSelection(order);
}

var dialogInline;
function openDialogInline(contentId,dialogWidth,dialogHeight,fitScreenHeight) {
	
	dialogInline = $jqPm(contentId).dialog({
		modal: true,
		width:dialogWidth,
		height:dialogHeight,
		fitHeight:(typeof(fitScreenHeight)!='undefined' && fitScreenHeight ? true:false),
		close: function(event, ui) {$jqPm('body').css('overflow','auto'); $jqPm(contentId).dialog("destroy");},
		open: function (event,ui) {$jqPm('body').css('overflow','hidden');$jqPm(this).css('width','93%');$jqPm(contentId).show();$jqPm(contentId).css('overflow','auto');}
	});
}

function closeDialogInline() {
	$jqPm(dialogInline).dialog("close");
}

function showHideElementsOnLoading() {
	if ($jqPm("#PM_CSOC_CROSSSELLING_off:checked").length) {
		$jqPm("#NB_CROSSSELLING").addClass('pmHide');
		$jqPm("#CROSSSELLING_NB_DAYS").addClass('pmHide');
	}
	if ($jqPm("#PM_MC_CSOC_CROSSSELLING_off:checked").length) {
		$jqPm("#NB_CROSSSELLING").addClass('pmHide');
		$jqPm("#CROSSSELLING_NB_DAYS").addClass('pmHide');
	}

	if ($jqPm("#PM_CSOC_ACCESSORIES_off:checked").length) {
		$jqPm("#NB_ACCESSORIES").addClass('pmHide');
	}
	if ($jqPm("#PM_MC_CSOC_ACCESSORIES_off:checked").length) {
		$jqPm("#NB_ACCESSORIES").addClass('pmHide');
	}
}

function handleSwitch(element, prefixFieldsOptions)
{
	if (element == 'CROSSSELLING') {
		$jqPm("input[name="+prefixFieldsOptions+"_CROSSSELLING]").click(
			function() {
				if ($jqPm(this).val() == 1) {
					$jqPm("#NB_CROSSSELLING").removeClass("pmHide").show();
					$jqPm("#CROSSSELLING_NB_DAYS").removeClass("pmHide").show();
				} else { 
					$jqPm("#NB_CROSSSELLING").hide();
					$jqPm("#CROSSSELLING_NB_DAYS").hide();
				}
			}
		);
	} else if (element == 'ACCESSORIES') {
		$jqPm("input[name="+prefixFieldsOptions+"_ACCESSORIES]").click(
			function() {
				if ($jqPm(this).val() == 1)
					$jqPm("#NB_ACCESSORIES").removeClass("pmHide").show();
				else 
					$jqPm("#NB_ACCESSORIES").hide();
			}
		);
	}
}

$(document).ready(function() {
	$('div#addons-rating-container p.dismiss a').click(function() {
		$('div#addons-rating-container').hide(500);
		$.ajax({type : "GET", url : window.location+'&dismissRating=1' });
		return false;
	});

	showHideElementsOnLoading();
});