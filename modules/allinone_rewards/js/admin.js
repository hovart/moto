/**
 * All-in-one Rewards Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Yann BONNAILLIE - ByWEB
 * @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
 * @license   Commercial license see license.txt
 * Support by mail  : contact@prestaplugins.com
 * Support on forum : Patanock
 * Support on Skype : Patanock13
 */

var pathCSS = '';
var iso = '';
var ad = '';

jQuery(function($){
	// templates
	$('select.rewards_template').change(function(){
		if ($(this).val() == 0) {
			$(this).parents('form').find('input.optional').hide();
		}
		reloadTemplate($(this));
	});

	$('.tabcontent form:not(form.rewards_template)').submit(function(event){
		template = $(this).parents('.tabcontent').find('select.rewards_template');
		if (template.val() != 0)
			$(this).append('<input type="hidden" name="'+template.attr('name')+'" value="'+template.val()+'">');
	});

	// reward payment
	$('input[name="rewards_payment"]').click(function(){
		if ($(this).val() == 1)
			$('.rewards_payment_optional').show();
		else
			$('.rewards_payment_optional').hide();
	});

	// reward transformation
	$('input[name="rewards_voucher"]').click(function(){
		if ($(this).val() == 1)
			$('.rewards_voucher_optional').show();
		else
			$('.rewards_voucher_optional').hide();
	});

	// cron
	$('input[name="rewards_use_cron"]').click(function(){
		if ($(this).val() == 1)
			$('.rewards_use_cron_optional').show();
		else
			$('.rewards_use_cron_optional').hide();
	});

	// reminder
	$('input[name="rewards_reminder"]').click(function(){
		if ($(this).val() == 1)
			$('.rewards_reminder_optional').show();
		else
			$('.rewards_reminder_optional').hide();
	});

	// loyalty reward type
	$('input[name="rloyalty_type"]').click(function(){
		if ($(this).val() == 0) {
			$('.reward_type_optional_1').hide();
			$('.reward_type_optional_2').hide();
			$('.reward_type_optional_0').show();
		} else if ($(this).val() == 1) {
			$('.reward_type_optional_0').hide();
			$('.reward_type_optional_2').hide();
			$('.reward_type_optional_1').show();
		} else {
			$('.reward_type_optional_0').hide();
			$('.reward_type_optional_1').hide();
			$('.reward_type_optional_2').show();
		}
	});


	// sponsor
	$('input[name="reward_s"]').click(function(){
		if ($(this).val() == 1)
			$('.sponsor_optional').show();
		else
			$('.sponsor_optional').hide();
	});

	// sponsored
	$('input[name="discount_gc"]').click(function(){
		if ($(this).val() == 1)
			$('.sponsored_optional').show();
		else
			$('.sponsored_optional').hide();
	});

	// popup
	$('input[name="popup"]').click(function(){
		if ($(this).val() == 1)
			$('.popup_optional').show();
		else
			$('.popup_optional').hide();
	});

	// open inviter
	$('input[name="open_inviter"]').click(function(){
		if ($(this).val() == 1)
			$('.open_inviter_optional').show();
		else
			$('.open_inviter_optional').hide();
	});

	$('#add_level').click(function(){
		addSponsorshipLevel();
	});

	// Facebook reward for guest
	$('input[name="facebook_reward_guest"]').click(function(){
		if ($(this).val() == 1)
			$('.facebook_voucher_optional').show();
		else
			$('.facebook_voucher_optional').hide();
	});

	initForm();
});

function initForm(){
	$('input[name="rewards_payment"]:checked').trigger('click');
	$('input[name="rewards_voucher"]:checked').trigger('click');
	$('input[name="rewards_use_cron"]:checked').trigger('click');
	$('input[name="rewards_reminder"]:checked').trigger('click');
	$('input[name="reward_s"]:checked').trigger('click');
	$('input[name="rloyalty_type"]:checked').trigger('click');
	$('input[name="discount_gc"]:checked').trigger('click');
	$('input[name="popup"]:checked').trigger('click');
	$('input[name="open_inviter"]:checked').trigger('click');
	$('input[name^="discount_type_gc"]:checked').trigger('click');
	$('input[name^="reward_type_s"]').each(function(i){
		checkType($(this));
	});
	$('input[name="facebook_reward_guest"]:checked').trigger('click');
	$('input[name^="facebook_voucher_type"]:checked').trigger('click');

	$('select.rewards_template').each(function(i){
		if ($(this).val() != 0) {
			$(this).parents('.tabcontent').find('.not_templated').hide();
		}
	});

	$('.tabs').tabs();
	$('.tabs').show();
}

function checkType(obj){
	if ($(obj).attr('checked')) {
		if ($(obj).val() == 1) {
			$(obj).parents('div.level_information').find('.reward_percentage').hide();
			$(obj).parents('div.level_information').find('.reward_amount').show();
		} else {
			$(obj).parents('div.level_information').find('.reward_amount').hide();
			$(obj).parents('div.level_information').find('.reward_percentage').show();
		}
	}
}

function addSponsorshipLevel() {
	var levels = $('div.level_information');
	var nb = levels.size();
	var newLevel = $(levels[nb-1]).clone(true);
	newLevel.find('span.numlevel').html(nb + 1);
	var reg=new RegExp('\\\['+(nb-1)+'\\\]"', "g");
	newLevel.html(newLevel.html().replace(reg,'['+nb+']"'));
	$(levels[nb-1]).after(newLevel);
	$('#unlimited_level').html(nb + 1);
	// hack pour cocher le type sur la nouvelle ligne à l'identique, sinon sur FF ca bug
	var selectedValue = $(levels[nb-1]).find('input[name^="reward_type_s"]:checked').val();
	newLevel.find('input[name^="reward_type_s"][value="'+selectedValue+'"]').trigger('click');
	return false;
}

function delSponsorshipLevel(obj) {
	var nb = $("div.level_information").size();
	if (nb > 1) {
		$(obj).parents('div.level_information').remove();
		var cpt = 1;
		// on réaffecte des ID séquentiels aux levels
		$("div.level_information").each(function(i){
			$(this).find("span.numlevel").html(cpt);
			cpt++;
		});
		$('#unlimited_level').html(nb - 1);
	}
	return false;
}

function showDetails(id_sponsor, url) {
	$('.statistics .details').remove();
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: url + '&id_sponsor=' + id_sponsor,
		dataType: "html",
		success : function(data) {
			$('#line_' + id_sponsor).after(data);
		}
	});
}

function convertCurrencyValue(obj, fromField, rate) {
	fromField = $('input[name^='+fromField+'].currency_default');
	if (fromField.size() > 1) {
		fromField = $(obj).parents('.level_information').find('input[name^='+fromField+'].currency_default');
	}
	value = fromField.val();
	fieldTo = $(obj).parent().find('input');
	fieldTo.val((value * rate).toFixed(4));
	return false;
}

function reloadTemplate(obj) {
	obj.parents('form').find('input[name=rewards_template_action]').val('');
	obj.parents('form').submit();
}

function promptTemplate(obj, action, label, value, title) {
	jPrompt(label, value, title, function(r) {
	    if (r) {
	    	obj.parents('form').find('input[name=rewards_template_action]').val(action);
	    	obj.parents('form').find('input[name=rewards_template_name]').val(r);
	    	obj.parents('form').submit();
	    }
	});
}

function deleteTemplate(obj, label, title) {
	jConfirm(label, title, function(r) {
	    if (r) {
	    	obj.parents('form').find('input[name=rewards_template_action]').val('delete');
	    	obj.parents('form').submit();
	    }
	});
}

function initTemplate(version1_6) {
	$(function() {
		initTableSorter();
		initAutocomplete(version1_6);
	});
}

function addTemplateCustomer(customer) {
	$('#new_customer').parents('form').find('input[name=rewards_template_action]').val('add_customer');
	$.ajax({
		type	: 'POST',
		async	: false,
		cache	: false,
		url		: $('#new_customer').parents('form').attr('action'),
		dataType: 'json',
		data 	: $('#new_customer').parents('form').serialize()+'&ajax=1&id_customer='+customer.id_customer,
		success : function(data) {
			var row = '<tr id="'+customer.id_customer+'"><td class="id">'+customer.id_customer+'</td><td>'+customer.firstname+'</td><td>'+customer.lastname+'</td><td>'+customer.email+'</td><td><img src="../img/admin/delete.gif" class="delete"></td></tr>';
			$row = $(row);
			$('.tablesorter').find('tbody').append($row)
			$('.tablesorter').trigger('addRows', [$row]);
		}
	});
}

function delTemplateCustomer(obj) {
	$('#new_customer').parents('form').find('input[name=rewards_template_action]').val('delete_customer');
	$.ajax({
		type	: 'POST',
		async	: false,
		cache	: false,
		url		: $('#new_customer').parents('form').attr('action'),
		dataType: 'json',
		data 	: $('#new_customer').parents('form').serialize()+'&ajax=1&id_customer='+$(obj).closest('tr').attr('id'),
		success : function(data) {
			$(obj).closest('tr').remove();
			$('.tablesorter').trigger('update');
		}
	});
  	return false;
}


/* tablesorter */
function initTableSorter() {
	if ($('.tablesorter').length > 0) {
		var pagerOptions = {
			container: $('.pager'),
			output: footer_pager,
			page: 0,
			size: 10,
			removeRows: false,
			ajaxUrl: null,
			cssNext: '.next', // next page arrow
		    cssPrev: '.prev', // previous page arrow
		    cssFirst: '.first', // go to first page arrow
		    cssLast: '.last', // go to last page arrow
		    cssGoto: '.gotoPage', // select dropdown to allow choosing a page
		    cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
		    cssPageSize: '.pagesize', // page size selector - select dropdown that sets the "size" option
		    // class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
		    cssDisabled: 'disabled', // Note there is no period "." in front of this class name
		    cssErrorRow: 'tablesorter-errorRow' // ajax error information row
		};

		$('.tablesorter').delegate('img.delete', 'click', function(){
	    	return delTemplateCustomer($(this));
	    });

	    $('#view_template_customers').click(function() {
	    	$('#rewards_template_customers').toggle();
	    });

		$('.tablesorter').tablesorter({
			theme: 'ice',
			widthFixed: true,
			sortList: [[0,0]],
			widgets: ['filter']
		}).tablesorterPager(pagerOptions);
	}
}

function initAutocomplete(version1_6) {
	if (version1_6) {
		$('#new_customer').autocomplete(
			$('#new_customer').parents('form').attr('action'),
			{
				cacheLength: 0,
				minChars: 2,
				width: 570,
				selectFirst: false,
				scroll: true,
				scrollHeight: 160,
				dataType: 'json',
				formatItem: function(item, i, max, value, term) {
					if ($('.autocomplete_header').length == 0)
						$('.ac_results').prepend('<div class="autocomplete_header"><span class="autocomplete_id_customer">'+idText+'</span><span class="autocomplete_firstname">'+firstnameText+'</span><span class="autocomplete_lastname">'+lastnameText+'</span><span class="autocomplete_email">'+emailText+'</span></div>');
					return '<a><span class="autocomplete_id_customer">'+item.id_customer+'</span><span class="autocomplete_firstname">'+item.firstname+'</span><span class="autocomplete_lastname">'+item.lastname+'</span><span class="autocomplete_email">'+item.email+'</span></a>';
				},
				parse: function(data) {
					var mytab = new Array();
					for (var i = 0; i < data.length; i++)
						mytab[mytab.length] = { data: data[i], value: data[i].id_customer };
					return mytab;
				},
				extraParams: {
					ajax: 1,
					rewards_template_action: 'list_customer',
					id_template: $('#new_customer').parents('form').find('select').val(),
					plugin: $('#new_customer').parents('form').find('input[name=plugin]').val()
				}
			}
		)
		.result(function(event, data, formatted) {
			addTemplateCustomer(data);
		});
	} else {
		$('#new_customer').autocomplete({
			source: function(request, response) {
					$.getJSON($('#new_customer').parents('form').attr('action')+'&ajax=1&rewards_template_action=list_customer&id_template='+$('#new_customer').parents('form').find('select').val()+'&plugin='+$('#new_customer').parents('form').find('input[name=plugin]').val(), request, function(data, status, xhr) {
					response($.map(data, function(item) {
						return {
							label: '<span class="autocomplete_id_customer">'+item.id_customer+'</span><span class="autocomplete_firstname">'+item.firstname+'</span><span class="autocomplete_lastname">'+item.lastname+'</span><span class="autocomplete_email">'+item.email+'</span>',
							value: '',
							obj: item
						}
					}));
				});
			},
			minLength: 2,
			html: true,
			select: function(event, ui) {
				addTemplateCustomer(ui.item.obj);
			}
		});

		/*
		* jQuery UI Autocomplete HTML Extension
		*
		* Copyright 2010, Scott González (http://scottgonzalez.com)
		* Dual licensed under the MIT or GPL Version 2 licenses.
		*
		* http://github.com/scottgonzalez/jquery-ui-extensions
		*/
		var proto = $.ui.autocomplete.prototype,
		initSource = proto._initSource;

		function filter( array, term ) {
	    	var matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );
	      	return $.grep( array, function(value) {
	        	return matcher.test( $( "<div>" ).html( value.label || value.value || value ).text() );
	        });
		}

		$.extend( proto, {
	    	_initSource: function() {
				if ( this.options.html && $.isArray(this.options.source) ) {
					this.source = function( request, response ) {
						response( filter( this.options.source, request.term ) );
					};
				} else {
					initSource.call( this );
				}
			},
	        _renderItem: function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( $( "<a></a>" )[ this.options.html ? "html" : "text" ]( item.label ) )
					.appendTo( ul );
			},
			_renderMenu: function( ul, items ) {
	            var self = this;
	            ul.prepend('<div class="autocomplete_header"><span class="autocomplete_id_customer">'+idText+'</span><span class="autocomplete_firstname">'+firstnameText+'</span><span class="autocomplete_lastname">'+lastnameText+'</span><span class="autocomplete_email">'+emailText+'</span></div>');
	            $.each( items, function( index, item ) {
	                self._renderItem( ul, item );
	            });
	        }
		});
	}
}