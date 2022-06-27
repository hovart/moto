/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2016 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */

(function ($) {
	
	
	var postfinancecwBuildHiddenFormFields = function (fields) {
		var output = '';
		for (var key in fields) {
			if (fields.hasOwnProperty(key)) {
				output += '<input type="hidden" value="' + fields[key].replace('"', '&quot;') + '" name="' + key + '" />';
			}
		}
		return output;
	};
	
	var attachEventHandlers = function() {
		
		$('*').off('.postfinancecw');
		
		$('.postfinancecw-alias-form').on('submit.postfinancecw',function(event) {
			var form = $(this);
			var completeForm = form.parents('.postfinancecw-payment-form');
			var completeFormId = completeForm.attr('id');
			
			$("#" + completeFormId).animate({
				opacity : 0.3,
				duration: 30, 
			});
			
			$.ajax({
				type: 		'POST',
				url: 		form.attr('action'),
				data: 		form.serialize() + '&ajaxAliasForm=true',
				success: 	function( response ) {
					
					var newPane = $("#" + completeFormId, $(response));
					if (newPane.length > 0) {
						var newContent = newPane.html();
						$("#" + completeFormId).html(newContent);
						
						// Execute the JS to make sure any JS inside newContent is executed
						$(response).each(function(k, e) {
							if(typeof e === 'object' && e.nodeName == 'SCRIPT') {
								jQuery.globalEval(e.innerHTML);
							}
						});
						attachEventHandlers();
					}
					
					$("#" + completeFormId).animate({
						opacity : 1,
						duration: 100, 
					});
				},
			});
			
			return false;
		});
		$('.postfinancecw-alias-form').find("input[type='checkbox']").on('change.postfinancecw',function(event) {
			$(this).parents('form').submit();
		});
		$('.postfinancecw-alias-form').find("select").on('change.postfinancecw',function(event) {
			$(this).parents('form').submit();
		});
		$('.postfinancecw-alias-form').find("input[type='submit']").on('click.postfinancecw',function(event) {
			$(this).parents('form').append('<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />');
		});
		
		$('.postfinancecw-ajax-authorization-form').each(function() {
			
			var ajaxForm = $(this);
			ajaxForm.parents('.postfinancecw-payment-form').find('[name="processPayment"]').on('click.postfinancecw',function(event) {
				$(this).hide();
				var methodName = ajaxForm.attr('data-method-name');
				var callback = window['postfinancecw_ajax_submit_callback_' + methodName];
					
				var validationCallback = window['cwValidateFields'+'postfinancecw_' + methodName + '_'];
				if (typeof validationCallback != 'undefined') {
					validationCallback(function(valid){ajaxFormPostFinanceCw_ValidationSuccess(ajaxForm);}, ajaxFormPostFinanceCw_ValidationFailure);
					return;
				}
				ajaxFormPostFinanceCw_ValidationSuccess(ajaxForm);
				return;
				
			});
			
		});
		
		$('.postfinancecw-create-transaction').each(function() {
			var ajaxUrl = $(this).attr('data-ajax-url');
			var sendFormDataBack = $(this).attr('data-send-form-back') == 'true' ? true : false;
			var form = $(this).children('form[data-method-name]');
			var methodName = form.attr('data-method-name');
			form.on('submit.postfinancecw', function() {
				if (window.postfinancecwAjaxRequestInProgress === true) {
					return false;
				}
				window.postfinancecwAjaxRequestInProgress = true;
				var formData = null;
				if (sendFormDataBack) {
					formData = form.serialize()
				}
				
				var validationCallback = window['cwValidateFields'+'postfinancecw_' + methodName + '_'];
				if (typeof validationCallback != 'undefined') {
					validationCallback(function(valid){createTransactionPostFinanceCw_ValidationSuccess(form, formData, ajaxUrl);}, createTransactionPostFinanceCw_ValidationFailure );
					return false;
				}
				createTransactionPostFinanceCw_ValidationSuccess(form, formData, ajaxUrl);
				return false;
			});
		});
		
		$('.postfinancecw-payment-form.postfinancecw-create-transaction').find('[name="processPayment"]').on('click.postfinancecw', function(event) {
			$(this).hide();
			return true;
		});
		
	};
	
	var ajaxFormPostFinanceCw_ValidationSuccess = function(ajaxForm) {
		var methodName = ajaxForm.attr('data-method-name');
		var callback = window['postfinancecw_ajax_submit_callback_' + methodName];
		if (typeof callback == 'undefined') {
			alert("No Ajax callback found.");
		}
		else {
			var fields = {};
			var data = ajaxForm.serializeArray();
			$(data).each(function(index, value) {
				fields[value.name] = value.value;
			});
			callback(fields);
		}
	}
	
	var ajaxFormPostFinanceCw_ValidationFailure = function(errors, valid) {
		alert(errors[Object.keys(errors)[0]]);
		$('.postfinancecw-payment-form').find('[name="processPayment"]').each(function() {
			$(this).show();
		});
	}
	
	
	var createTransactionPostFinanceCw_ValidationSuccess = function(form, formData, ajaxUrl){
		
		var fields = {};
		$(form.serializeArray()).each(function(index, value) {
			fields[value.name] = value.value;
		});
		
		form.animate({
			opacity : 0.3,
			duration: 30, 
		});
		$.ajax({
			type: 		'POST',
			url: 		ajaxUrl,
			data: 		formData,
			success: 	function( response ) {
				var error = response;
				try {
					var data = $.parseJSON(response);
					
					if (data.status == 'success') {
						var func = eval('[' + data.callback + ']')[0];
						func();
						return;
					}
					else {
						error = data.message;
					}
				}
				catch(e) {
					console.log(e);
				}
				
				form.animate({
					opacity : 1,
					duration: 100, 
				});
				form.prepend("<div class='postfinancecw-error-message-inline alert alert-danger'>" + error + "</div>");
				window.postfinancecwAjaxRequestInProgress = false;
			},
		});
		
	}
	
	var createTransactionPostFinanceCw_ValidationFailure = function(errors, valid){
		alert(errors[Object.keys(errors)[0]]);
		$('.postfinancecw-payment-form').find('[name="processPayment"]').each(function() {
			$(this).show();
		});
		window.postfinancecwAjaxRequestInProgress = false;
	}
	
	$( document ).ready(function() {
		
		// Make JS required section visible
		$('.postfinancecw-javascript-required').show();
		
		attachEventHandlers();
	});
	
}(jQuery));