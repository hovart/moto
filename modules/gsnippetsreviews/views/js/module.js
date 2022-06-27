/*
 * 2003-2017 Business Tech
 *
 *  @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 *  @copyright 2003-2017 Business Tech SARL
 */
// declare main object of module
var GsrModule = function(sName){
	// set name
	this.name = sName;

	// set name
	this.oldVersion = false;

	// set translated js msgs
	this.msgs = {};

	// stock error array
	this.aError = [];

	// set url of admin img
	this.sImgUrl = '';

	// set url of module's web service
	this.sWebService = '';

	// set this in obj context
	var oThis = this;

	/**
	 * show() method show effect and assign HTML in
	 *
	 * @param string sId : container to show in
	 * @param string sHtml : HTML to display
	 */
	this.show = function(sId, sHtml){
		$("#" + sId).html(sHtml).css('style', 'none');
		$("#" + sId).show('fast');
	};

	/**
	 * hide() method hide effect and delete html
	 *
	 * @param string sId : container to hide in
	 */
	this.hide = function(sId, bOnlyHide){
		$('#' + sId).hide('fast');
		if (bOnlyHide == null) {
			$('#' + sId).empty();
		}
		//$("#" + sId).hide('fast', function(){
		//		$("#" + sId).html('');
		//	}
		//);
	};

	/**
	 * form() method check all fields of current form and execute : XHR or submit => used for update all admin config
	 *
	 * @see ajax
	 * @param string sForm : form
	 * @param string sURI : query params used for XHR
	 * @param string sRequestParam : param action and type in order to send with post mode
	 * @param string sToDisplay :
	 * @param string sToHide : force to hide specific ID
	 * @param bool bSubmit : used only for sending main form
	 * @param bool bFancyBox : used only for fancybox in xhr
	 * @param string oCallBack : used only for callback to execute as ajax request
	 * @param string sErrorType :
	 * @param string sLoadBar :
	 * @param string sScrollTo :
	 * @return string : HTML returned by smarty
	 */
	this.form = function(sForm, sURI, sRequestParam, sToDisplay, sToHide, bSubmit, bFancyBox, oCallBack, sErrorType, sLoadBar, sScrollTo){
		// set loading bar
		if (sLoadBar) {
			$('#bt_loading-div-'+sLoadBar).show();
		}

		// set return validation
		var aError = [];

		// get all fields of form
		var fields = $("#" + sForm).serializeArray();

		// set counter
		var iCounter = 0;

		// set bIsError
		var bIsError = false;

		// set check review and order status
		var bCheckStatus = false;
		var bCheckedStatus = false;
		var bCheckReview = false;
		var bCheckedReview = false;

		// check element form
		jQuery.each(fields, function(i, field) {
			bIsError = false;

			switch(field.name) {
				case 'bt_delay-email' :
					if (isNaN(field.value)) {
						oThis.aError[iCounter] = oThis.msgs.delay;
						bIsError = true;
					}
					break;
				case 'bt_review-date' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.reviewDate;
						bIsError = true;
					}
					break;
				case 'bt_review-title' :
					if (field.value == '' && ($('#bt_review-comment').val() != '' || $('#bCheckFieldText').val() == 1)) {
						console.log(oThis.msgs);
						oThis.aError[iCounter] = oThis.msgs.title;
						bIsError = true;
					}
					break;
				case 'bt_review-comment' :
					if (field.value == '' && ($('#bt_review-title').val() != '' || $('#bCheckFieldText').val() == 1)) {
						oThis.aError[iCounter] = oThis.msgs.comment;
						bIsError = true;
					}
					break;
				case 'bt_reply-comment' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.comment;
						bIsError = true;
					}
					break;
				case 'bt_report-comment' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.report;
						bIsError = true;
					}
					break;
				case 'bt_email' :
					if (field.value == '' && $('input:checked[name="bt_enable-email"]').val() == 1) {
						oThis.aError[iCounter] = oThis.msgs.email;
						bIsError = true;
					}
					break;
				case 'bt_carbon-copy-email' :
					if (field.value == '' && $('input:checked[name="bt_enable-carbon-copy"]').val() == 1) {
						oThis.aError[iCounter] = oThis.msgs.email;
						bIsError = true;
					}
					break;
				case 'iRating' :
					if (field.value == 0 || field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.rating;
						bIsError = true;
					}
					break;
				case 'bt_order-status[]' :
					bCheckedStatus = true;
					break;
				case 'bCheckStatus' :
					bCheckStatus = true;
					break;
				case 'bCheckReview' :
					bCheckReview = true;
					break;
				default:
					// check if language field
					if (field.name.indexOf('email-review-title') != -1 &&  field.value == '') {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reply notification
					if (field.name.indexOf('email-reply-title') != -1 &&  field.value == '') {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reply text notification
					if (field.name.indexOf('email-reply-text') != -1 &&  field.value == '') {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for subject email
					if (field.name.indexOf('email-reminder-title') != -1 &&  field.value == '' && $('#bt_enable-callback-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reminder category label
					if (field.name.indexOf('email-category-label') != -1 &&  field.value == '' && $('#bt_enable-callback-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emaillCategory[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reminder product label
					if (field.name.indexOf('email-product-label') != -1 &&  field.value == '' && $('#bt_enable-callback-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emaillProduct[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reminder sentence label
					if (field.name.indexOf('email-sentence') != -1 &&  field.value == '' && $('#bt_enable-callback-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emaillSentence[aLangId[2]];

						bIsError = true;
					}
					// check if language field for sentence of FB post
					if (field.name.indexOf('tab-fb-post-phrase') != -1 &&  field.value == '' && $('#bt_enable-fb-post-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of FB post
					if (field.name.indexOf('tab-fb-post-label') != -1 &&  field.value == '' && $('#bt_enable-fb-post-div :checked').val() == 1) {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of e-mail reviews notification
					if (field.name.indexOf('review-tab-title') != -1 &&  field.value == '') {
						var aLangId = field.name.split('_');
						oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[2]];

						bIsError = true;
					}
					// check if language field for label of desc of voucher
					if (field.name.indexOf('tab-voucher-desc') != -1 &&  field.value == '') {
						var aVoucherType = field.name.split('_');
						var aVoucherForm = aVoucherType[2].match(/[a-z]{1,}/g);
						var aLangId = aVoucherType[2].match(/[0-9]{1,}/g);

						if ($("input:checked[name='bt_enable-voucher_" + aVoucherForm[0] +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.emailTitle[aLangId[0]];

							bIsError = true;
						}
					}
					// check if string for label of voucher code
					if (field.name.indexOf('prefix-code') != -1 && field.value == '') {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.vouchercode;

							bIsError = true;
						}
					}
					// check if string for label of voucher amount
					if (field.name.indexOf('voucher-amount') != -1 && (isNaN(field.value) || field.value == 0)) {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($('#bt_discount-type_' + sType + ' :checked').val() == 'amount' && $("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.voucheramount;
							bIsError = true;
						}
					}
					// check if string for label of voucher percent
					if (field.name.indexOf('voucher-percent') != -1 && (isNaN(field.value) || field.value == 0)) {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($('#bt_discount-type_' + sType + ' :checked').val() == 'percentage' && $("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.voucheramount;

							bIsError = true;
						}
					}
					// check if string for label of voucher minimum amount
					if (field.name.indexOf('minimum') != -1 && isNaN(field.value)) {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.voucherminimum;

							bIsError = true;
						}
					}
					// check if string for label of voucher maximum quantity
					if (field.name.indexOf('maximum-qte') != -1 && isNaN(field.value)) {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.vouchermaximum;

							bIsError = true;
						}
					}
					// check if string for label of voucher validity
					if (field.name.indexOf('validity') != -1 && (isNaN(field.value) || field.value == 0)) {
						var aVoucherForm = field.name.match(/\[([a-z]{1,})\]/g);
						var sType = aVoucherForm[0].replace('[', '').replace(']', '');

						if ($("input:checked[name='bt_enable-voucher_" + sType +"']").val() == 'true') {
							oThis.aError[iCounter] = oThis.msgs.vouchervalidity;

							bIsError = true;
						}
					}

					if (bCheckReview && field.name.indexOf('check-review') != -1) {
						bCheckedReview = true;
					}
					break;
			}

			if (($('input[name="' + field.name + '"]') != undefined
				|| $('textarea[name="' + field.name + '"]') != undefined
				|| $('select[name="' + field.name + '"]').length != undefined)
				&& bIsError == true
			) {
				if ($('input[name="' + field.name + '"]').length != 0) {
					$('input[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
				}
				if ($('textarea[name="' + field.name + '"]').length != 0) {
					$('textarea[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
				}
				if ($('select[name="' + field.name + '"]').length != 0) {
					$('select[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
				}
				++iCounter;
			}
		});

		if (bCheckStatus && !bCheckedStatus) {
			oThis.aError[iCounter] = oThis.msgs.status;
			bIsError = true;
		}

		if (bCheckReview && !bCheckedReview) {
			oThis.aError[iCounter] = oThis.msgs.checkreview;
			bIsError = true;
		}

		// use case - no errors in form
		if (oThis.aError.length == 0 && !bIsError) {
			// use case - Ajax request
			if (bSubmit == undefined || bSubmit == null || !bSubmit) {
				if (sLoadBar && sToHide != null) {
					oThis.hide(sToHide, true);
				}

				// format object of fields in string to execute Ajax request
				var sFormParams = $.param(fields);

				if (sRequestParam != null && sRequestParam != '') {
					sFormParams = sRequestParam + '&' + sFormParams;
				}

				// execute others ajax request if needed. In this case, we can update any other tab from the module in the same time
				if (oCallBack != null && oCallBack.length != 0) {
					for (var fx in oCallBack) {
						if (oCallBack[fx].name != '' && oCallBack[fx].name == 'updateDesc') {
							var sDescList = this.updateDescSort();

							if (sDescList != '') {
								sFormParams += '&bt_desc_list=' + sDescList;
							}
						}
					}
				}

				// execute Ajax request
				this.ajax(sURI, sFormParams, sToDisplay, sToHide, bFancyBox, null, sLoadBar, sScrollTo, oCallBack);
				return true;
			}
			// use case - send form
			else {
				// hide loading bar
				if (sLoadBar) {
					// set loading bar
					if (sLoadBar) {
						$('#bt_loading-div-'+sLoadBar).hide();
					}
				}
				document.forms[sForm].submit();
				return true;
			}
		}
		// display errors
		this.displayError(sErrorType);

		// set loading bar
		if (sLoadBar) {
			$('#bt_loading-div-'+sLoadBar).hide();
		}

		return false;
	};


	/**
	 * ajax() method execute XHR
	 *
	 * @param string sURI : query params used for XHR
	 * @param string sParams :
	 * @param string sToShow :
	 * @param string sToHide :
	 * @param bool bFancyBox : used only for fancybox in xhr
	 * @param bool bFancyBoxActivity : used only for fancybox in xhr
	 * @param string sLoadBar : used only for loading
	 * @param string sScrollTo : used only for scrolling
	 * @param obj oCallBack : used only for callback to execute as ajax request
	 * @return string : HTML returned by smarty
	 */
	this.ajax = function(sURI, sParams, sToShow, sToHide, bFancyBox, bFancyBoxActivity, sLoadBar, sScrollTo, oCallBack) {
		sParams = 'sMode=xhr' + ((sParams == null || sParams == undefined) ? '' : '&' + sParams) ;

		// configure XHR
		$.ajax({
			type : 'POST',
			url : sURI,
			data : sParams,
			dataType : 'html',
			success: function(data) {
				// hide loading bar
				if (sLoadBar) {
					$('#bt_loading-div-'+sLoadBar).hide();
				}
				if (bFancyBox) {
					// update fancybox content
					$.fancybox(data);
				}
				else if (sToShow != null && sToHide != null) {
					// same hide and show
					if (sToShow == sToHide) {
						oThis.hide(sToHide);
						setTimeout('', 1000);
						oThis.show(sToShow, data);
					}
					else {
						oThis.hide(sToHide);
						setTimeout('', 1000);
						oThis.show(sToShow, data);
					}
				}
				else if (sToShow != null) {
					oThis.show(sToShow, data);
				}
				else if (sToHide != null) {
					oThis.hide(sToHide);
				}

				if (sScrollTo !== null && typeof sScrollTo !== 'undefined' && $(sScrollTo).length != 0) {
					var iPosTop = $(sScrollTo).offset().top-30;
					if(iPosTop < 0) iPosTop = 0;
					
					$(document).scrollTop(iPosTop);
				}

				// execute others ajax request if needed. In this case, we can update any other tab from the module in the same time
				if (oCallBack != null && oCallBack.length != 0) {
					for (var fx in oCallBack) {
						oThis.ajax(oCallBack[fx].url, oCallBack[fx].params, oCallBack[fx].toShow, oCallBack[fx].toHide, oCallBack[fx].bFancybox, oCallBack[fx].bFancyboxActivity, oCallBack[fx].sLoadbar, oCallBack[fx].sScrollTo , oCallBack[fx].oCallBack);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$("#" + oThis.name + "FormError").addClass('alert alert-danger');
				oThis.show("#" + oThis.name + "FormError", '<h3>internal error</h3>');
			}
		});
	};

	/**
	 * displayError() method display errors
	 *
	 * @param string sType : type of container
	 * @return bool
	 */
	this.displayError = function(sType){
		if (oThis.aError.length != 0) {
			var sError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><ul class="list-unstyled">';
			for (var i = 0; i < oThis.aError.length;++i) {
				sError += '<li>' + oThis.aError[i] + '</li>';
			}
			sError += '</ul></div>';
			$("#bt_error-" + sType).html(sError);
			$("#bt_error-" + sType).slideDown();

			// flush errors
			oThis.aError = [];

			return false;
		}
	};

	/**
	 * sortDesc() method set sortable desc
	 *
	 * @param elt
	 */
	this.sortDesc = function(elt) {
		// set sortable elt
		$(elt).sortable();
		$(elt).disableSelection();
	};

	/**
	 * updateDescSort() method verify all childnodes of des list and determine position to manage snippets desc
	 *
	 * @return string : HTML returned by smarty
	 */
	this.updateDescSort = function(){
		var sList = '';

		if ($("#bt_sortable-desc li").length != 0) {
			var aDescId = [];
			$("#bt_sortable-desc li").each(function(i, elt)
			{
				aDescId[i] = elt.id;
			});
			sList = aDescId.toString();
		}
		return sList;
	};

	/**
	 * changeSelect() method displays or hide related option form
	 *
	 * @param string sId : type of container
	 * @param mixed mDestId
	 * @param string sDestId2
	 * @param string sType of second dest id
	 * @param bool bForce
	 * @param bool mVal
	 */
	this.changeSelect = function(sId, mDestId, sDestId2, sDestIdToHide, bForce, mVal){
		if (bForce) {
			if (typeof mDestId == 'string') {
				mDestId = [mDestId];
			}

			for (var i = 0; i < mDestId.length; ++i) {
				if (mVal) {
					$("#" + mDestId[i]).fadeIn('fast', function() {$("#" + mDestId[i]).css('display', 'block')});
				}
				else {
					$("#" + mDestId[i]).fadeOut('fast');
				}
			}
		}
		else {
			$("#" + sId).bind('change', function (event){
				$("#" + sId + " input:checked").each(function (){
					switch ($(this).val()) {
						case 'true' :
							// display option features
							$("#" + sDestId).fadeIn('fast', function() {$("#" + sDestId).css('display', 'block')});
							break;
						default:
							// hide option features
							$("#" + sDestId).fadeOut('fast');

							// set to false
							if (sDestId2 && sDestIdToHide) {
								$("#" + sDestId2 + " input").each(function (){
										switch ($(this).val()) {
											case 'false' :
												$(this).attr('checked', 'checked');
												// hide option features
												$("#" + sDestIdToHide).fadeOut('fast');
												break;
											default:
												$(this).attr('checked', '');
												break;
										}
									}
								);
							}
							break;
					}
				});
			});
		}
	};

	/**
	 * selectAll() method select / deselect all checkbox
	 *
	 * @param string sId : type of container
	 * @param string sCible : all checkbox to process
	 */
	this.selectAll = function(sCible, sType){
		if (sType == 'check') {
			$(sCible).attr('checked', true);
		}
		else{
			$(sCible).attr('checked', false);
		}
	};

	/**
	 * scrollTo() method scrollTo
	 *
	 * @param string sId : selector
	 * @param int iDuration
	 */
	this.scrollTo = function(sId, iDuration){
		$.scrollTo(sId, iDuration);
	};


	/**
	 * activateReviewTab() method activate the review tab
	 *
	 * @param string sId : selector
	 * @param string sIdTab : tabID
	 * @param string sLiSelector : li selector
	 * @param string sCntSelector : content selector
	 */
	this.activateReviewTab = function(sTheme, sIdTab, sLiSelector, sCntSelector){
		if ($(sLiSelector).length != 0) {
			$(sLiSelector +" li").each(function(i, elt) {
				if ($(this).find('a[href="#idTab'+sIdTab+'"]').length != 0 ) {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {
						$('a', this).addClass('active');
					}
					else {
						$('a', this).addClass('selected');
					}
				}
				else {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {
						$('a', this).removeClass('active');
					}
					else {
						$('a', this).addClass('selected');
					}
				}
			});
		}
		if ($(sCntSelector).length != 0) {
			$(sCntSelector).children().each(function(i, elt) {
				if ($(this).attr('id') == 'idTab'+ sIdTab) {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {$(this).addClass('active');}
					if (sTheme != 'tabs17') {$(this).removeClass('block_hidden_only_for_screen');}
				}
				else if ($(this).attr('id') != '') {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {$(this).removeClass('active');}
					if (sTheme != 'tabs17') {$(this).addClass('block_hidden_only_for_screen');}
				}
			});
		}
	};

	/**
	 * deactivateReviewTab() method deactivate the review tab after 3 seconds to let Facebook parse hte source code to render the button layout
	 *
	 * @param string sId : selector
	 * @param string sIdTab : tabID
	 * @param string sLiSelector : li selector
	 * @param string sCntSelector : content selector
	 * @param int iDuration
	 */
	this.deactivateReviewTab = function(sTheme, sIdTab, sLiSelector, sCntSelector, iDuration){
		if ($(sLiSelector).length != 0) {
			$(sLiSelector +" li").each(function(i, elt) {
				if ($(this).find('a[href="#'+sIdTab+'"]').length != 0 ) {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {
						$('a', this).addClass('in active');
					}
					else {
						$('a', this).addClass('selected');
					}
				}
				else {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {
						$('a', this).removeClass('in active');
					}
					else {
						$('a', this).removeClass('selected');
					}
				}
			});
		}
		if ($(sCntSelector).length != 0) {
			$(sCntSelector).children().each(function(i, elt) {
				if ($(this).attr('id') == sIdTab) {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {$(this).addClass('active');}
					if (sTheme != 'tabs17') {$(this).removeClass('block_hidden_only_for_screen');}
				}
				else if ($(this).attr('id') != '') {
					if (sTheme == 'bootstrap' || sTheme == 'tabs17') {$(this).removeClass('active');}
					if (sTheme != 'tabs17') {$(this).addClass('block_hidden_only_for_screen');}
				}
			});
		}
	};

	/**
	 * redirectToProduct() method redirect the customer to the product to rate when he clicks on the slider's product
	 *
	 * @param string sRatingName : rating name object
	 * @param int iRatingId
	 * @param string sProductLink
	 * @param string sOpenForm
	 */
	this.redirectToProduct = function(sRatingName, iRatingId, sProductLink, sOpenForm) {
		var iRating = 0;

		$('input[name="bt_rating' + iRatingId + '"]').each(function(index) {
			if ($(this).attr('checked') == 'checked') {
				iRating = $(this).val();
			}
		});

		// error
		if ($('input[name="'+ sRatingName+iRatingId+'"]').val() == '0'
			&& iRating == 0
		) {
			$("#"+ sRatingName + 'Error' + iRatingId).slideDown();
			setTimeout(function(){$("#"+ sRatingName + 'Error' + iRatingId).slideUp();}, 3000);
		}
		// redirect on product page with the rating
		else {
			// set product's URL
			window.open(sProductLink + '?'+sOpenForm+'=true&rtg=' + iRating);
		}
	};


	/**
	 * getProductAverage() method return visual and average of one product
	 *
	 * @param string iProductId : product id
	 * @param string sSuffix : suffix for HTML id
	 */
	this.getProductAverage = function(iProductId, sSuffix){
		if (typeof(sSuffix) == 'undefined') {
			sSuffix = '';
		}

		this.ajax(this.sWebService, 'sAction=display&sType=average&id=' + iProductId + '&suffix='+sSuffix, 'productRating' + iProductId + sSuffix, null, null, null, null);
	};
};

