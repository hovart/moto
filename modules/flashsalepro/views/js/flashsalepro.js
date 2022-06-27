/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

// Main Function
var Main = function () {
	// function to custom select
	var runCustomElement = function () {
                // check submit
                var is_submit = $("#modulecontent").attr('role');

                if (is_submit == 1) {
                    $(".list-group-item").each(function() {
                        if ($(this).hasClass('active')) {
                            $(this).removeClass("active");
                        }
                        else if ($(this).attr('href') == "#config") {
                            $(this).addClass("active");
                        }
                    });
                    $('#config').addClass("active");
                    $('#documentation').removeClass("active");
                }

		// toggle panel
		$(".list-group-item").on('click', function() {
			var $el = $(this).parent().closest(".list-group").children(".active");
			if ($el.hasClass("active")) {
				$el.removeClass("active");
				$(this).addClass("active");
			}
		});

		// Hide ugly toolbar
		$('table[class="table"]').each(function(){
			$(this).hide();
			$(this).next('div.clear').hide();
		});

		// Hide ugly multishop select
		if (typeof(_PS_VERSION_) !== 'undefined') {
			var version = _PS_VERSION_.substr(0,3);
			if(version === '1.5') {
				$('.multishop_toolbar').addClass("panel panel-default");
				$('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
				cloneMulti = $(".multishop_toolbar").clone(true, true);
				$(".multishop_toolbar").first().remove();
				cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
				cloneMulti.insertBefore("#modulecontent");
				// Copy checkbox for multishop
				cloneActiveShop = $.trim($('table[class="table"] tr:nth-child(2) th').first().html());
				$(cloneActiveShop).insertAfter("#tab_translation");
			}
		}

		// Custom Select
		$('.selectpicker').selectpicker();

		// Fix bug form builder + bootstrap select
		var z = 1;
		$('.selectpicker').each(function(){
			var select = $(this);
			select.on('click', function() {
				$(this).parents('.bootstrap-select').addClass('open');
				$(this).parents('.bootstrap-select').toggleClass('open');
			});
		});

		// Custom Textarea
		$('.textarea-animated').autosize({append: "\n"});
	};
	return {
		//main function to initiate template pages
		init: function () {
			runCustomElement();
		}
	};
}();

function setTimerColor(type)
{
	if(type == 'bg')
	{
		timer_bg_color = $("#timer_bg_color").val();
		$('.inn').css('background-color', timer_bg_color);
	}
	else if(type == 'text')
	{
		timer_text_color = $("#timer_text_color").val();
		$('.inn').css('color', timer_text_color);
	}else if(type == 'dot')
	{
		timer_dot_color = $("#timer_dot_color").val();
		$('.flip-clock-dot').css('background', timer_dot_color);
	}else if(type == 'bg_banner')
	{
		banner_bg_color = $("#banner_bg_color").val();
		$('#banner_preview').css('background', banner_bg_color);
	}else if(type == 'text_banner')
	{
		banner_text_color = $("#banner_text_color").val();
		$('#banner_preview').css('color', banner_text_color);
	}
}

function hideFlashSaleLangs(id_lang, iso_code)
{
	$('.translatable-field').hide();
	$('.lang-' + id_lang).show();
	$('#flash_sale_name_lang').html(iso_code+'&nbsp;<span class="caret"></span>');
	$('#edit_flash_sale_name_lang').html(iso_code+'&nbsp;<span class="caret"></span>');
}

function selectProduct(id_product, lang)
{
	var flash_type = $('input[name=flash_type]:checked').val();
	$.ajax({
			data: {
				id_product: id_product,
				lang: lang,
				flash_type: flash_type,
				action : 'SelectProduct'
			},
			dataType : 'json'
	  }).done(function(html) {
	  	var table=document.getElementById("selected_product_table");
	  	$(table).find('tbody').append(html.content);
	  	$("#search_product_result_"+id_product).hide();
	  });
}

function selectCategory(id_category, category_name, lang)
{
	$.ajax({
			data: {
				id_category: id_category,
				action : 'SelectCategory',
				lang : lang,
				category_name : category_name
			},
			dataType : 'json'
	  }).done(function(html) {
	  	var table=document.getElementById("selected_product_table");
	  	$(table).find('tbody').append(html.content);
	  	$("#search_category_result_"+id_category).hide();
	  });
}

function insertItemDiscountToDB(id_item, type)
{
	var html = document.getElementById("amount_"+type+id_item);
	var discount = html.value;
	if(discount != '')
	{
		$.ajax({
				data: {
					id_item: id_item,
					discount: discount,
					type: type,
					action : 'tempInsertDiscount',
				},
				dataType : 'json'
		  }).done(function() {
		  });
	}
}

function insertItemDiscountTypeToDB(id_item, type, discount_type)
{
	if(discount_type != '')
	{
		$.ajax({
				data: {
					id_item: id_item,
					discount_type: discount_type,
					type: type,
					action : 'tempInsertDiscountType',
				},
				dataType : 'json'
		  }).done(function() {
		  });
	}
}

function removeItemFromTable(id_item, type)
{
	$.ajax({
			data: {
				id_item: id_item,
				type: type,
				action : 'RemoveItemFromList'
			}
	  }).done(function() {
		  	var id_row = "row_"+type+"_"+id_item;
		  	var row=document.getElementById(id_row);
		  	$(row).remove();
		  	if(type=="product")
		  	{
			  	var id_row_image = "row_product_image_"+id_item;
			  	var row_image=document.getElementById(id_row_image);
			  	$(row_image).remove();
			  	$("#search_product_result_"+id_item).show();
		  	}
		  	if(type=="category")
		  		$("#search_category_result_"+id_item).show();
	  });
}

function update_div_class(id)
{
    if(id == "flash_type_time_label")
    {
        document.getElementById("flash_type_time_label").className = "btn btn-info";
        document.getElementById("flash_type_stock_label").className = "btn btn-default";
        document.getElementById("flash_type_manual_label").className = "btn btn-default";
        $("#flash_sale_dates_div").show(500);
        $("#search_type_category_label").show();
    }
    if(id == "flash_type_stock_label")
    {
        document.getElementById("flash_type_stock_label").className = "btn btn-info";
        document.getElementById("flash_type_time_label").className = "btn btn-default";
        document.getElementById("flash_type_manual_label").className = "btn btn-default";
        $("#flash_sale_dates_div").hide(500);
        $("#search_type_category_label").hide();
    }
    if(id == "flash_type_manual_label")
    {
        document.getElementById("flash_type_manual_label").className = "btn btn-info";
        document.getElementById("flash_type_stock_label").className = "btn btn-default";
        document.getElementById("flash_type_time_label").className = "btn btn-default";
        $("#flash_sale_dates_div").hide(500);
        $("#search_type_category_label").show();
    }
    if(id == "activation_type_manual_label")
    {
        document.getElementById("activation_type_manual_label").className = "btn btn-info";
        document.getElementById("activation_type_programmed_label").className = "btn btn-default";
        $("#flash_sale_dates_div").hide(500);
    }
    if(id == "activation_type_programmed_label")
    {
        document.getElementById("activation_type_programmed_label").className = "btn btn-info";
        document.getElementById("activation_type_manual_label").className = "btn btn-default";
        $("#flash_sale_dates_div").show(500);
    }
    if(id == "search_type_product_label")
    {
        document.getElementById("search_type_product_label").className = "btn btn-info";
        document.getElementById("search_type_category_label").className = "btn btn-default";
        $("#liveSearchResults").show(500);
        $("#flash_sale_category_search_info").hide(500);
        $("#liveSearchResultsCategories").hide(500);

    }
    if(id == "search_type_category_label")
    {
        document.getElementById("search_type_category_label").className = "btn btn-info";
        document.getElementById("search_type_product_label").className = "btn btn-default";
        $("#liveSearchResultsCategories").show(500);
        $("#flash_sale_category_search_info").show(500);
        $("#liveSearchResults").hide(500);
    }
    if(id == "banner_position_label")
    {
        document.getElementById("banner_position_label").className = "btn btn-info";
        document.getElementById("banner_position_home").className = "btn btn-default";
    }
	if(id == "banner_position_home")
    {
        document.getElementById("banner_position_home").className = "btn btn-info";
        document.getElementById("banner_position_label").className = "btn btn-default";
    }

    if(id == "flash_left_column_label")
    {
        document.getElementById("flash_left_column_label").className = "btn btn-info";
        document.getElementById("flash_right_column_label").className = "btn btn-default";
    }
	if(id == "flash_right_column_label")
    {
        document.getElementById("flash_right_column_label").className = "btn btn-info";
        document.getElementById("flash_left_column_label").className = "btn btn-default";
    }
}

function update_edit_discount_type_div_class(type, id_item, symbol) 
{
    $("#"+id_item+"add_product_discount_type_symbol").html(symbol).text();
    $("#"+id_item+"_edit_flash_sale_discount_type").val(type);
}

function hideCornerBannerConfig()
{
	$("#corner_banner_config").hide(500);
}

function showCornerBannerConfig()
{
	$("#corner_banner_config").show(500);
}

function showFlashSaleItemTable(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'ShowFlashSaleItemInfo'
			},
			dataType : 'json'
	  }).done(function(json) {
			$("#flash_sale_item_table_div").show(50);
			$("#item_table_stock_header").hide();
	  		
	  		var header_row=document.getElementById("item_table_stock_header_row");
	  		$(header_row).empty();
	  		$(header_row).append(json['headers']);
	  		
	  		var table=document.getElementById("flash_sale_item_table_body");
	  		$(table).empty();
	  		$(table).append(json['html']);
	  		
	  		$('html, body').animate({
		        scrollTop: $("#flash_sale_item_table").offset().top
		    }, 2000);
	  });
}

function hideFlashSaleItemTable()
{
	$("#flash_sale_item_table_div").hide(50);
	var table=document.getElementById("flash_sale_item_table_body");
	$(table).empty();
}

function changeColumn(side)
{
	if (side == 'left')
		$('#flash_right_column_switch_no').click();
	if (side == 'right')
		$('#flash_left_column_switch_no').click();
}

function deactivateFlashSale(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'DeactivateShowFlashSale'
			}
	  }).done(function() {
  		refreshTable();
	  });
}

function activateFlashSale(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'ActivateShowFlashSale'
			},
			dataType : 'json'
	  }).done(function(activate_result) {
	  	if(activate_result==-1)
	  		swal({title: "Expiration Date Problem", text: "To activate a Flash-Sale, please change the expiration date to a future time.", type: "warning"});
	  	refreshTable();
	  });
}

function editFlashSale(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'editFlashSale'
			},
			dataType : 'json'
	}).done(function(result) {
		$.each(result['names'], function(args, val) {
			$('#edit_flash_sale_name_'+args).val(val);
		});
		$('#edit_flash_sale_id').val(result['sale']['id_flashsalespro']);
		$('#id_flash_sale_to_edit').val(result['sale']['id_flashsalespro']);
		$('#id_flash_sale_items_count').val(result['items_count']);

		$('#edit_discount_currency_restriction').val(result['sale']['id_currency_restriction']);
		$('#edit_discount_country_restriction').val(result['sale']['id_country_restriction']);
		$('#edit_discount_group_restriction').val(result['sale']['id_group_restriction']);
		$('#edit_flashsale_text_font').val(result['sale']['font']);
		$('#edit_flashsale_text_color').val(result['sale']['text_color']);
		$('#edit_flashsale_bg_color').val(result['sale']['bg_color']);
		$("#flash_sale_edit_product_block").html(result['product_html']);
		$("#pagnation_product_pages").html(result['pagination_html']);
		$('#edit_flash_sale_modal').modal('show');
	});
}

function itemPaginationChange(index)
{
	var item_count = $("#id_flash_sale_items_count").val();
	var id_flash_sale = $("#id_flash_sale_to_edit").val();
	var max_index = $('#max_pagination_index').val();

	if(index <= max_index && index > 0)
	{
		$.ajax({
				data: {
					id_flash_sale: id_flash_sale,
					index: index,
					item_count: item_count,
					action : 'itemPaginationChange'
				},
				dataType : 'json'
		}).done(function(result) {
			$("#flash_sale_edit_product_block").html(result['product_html']);
			if(index != 0)
				$("#current_active_pagination_index").val(index);
			else
				$("#current_active_pagination_index").val(1);
		});
	}
}

function itemPaginationPreviousNext(direction)
{
	var active_index = parseInt($("#current_active_pagination_index").val());
	if(direction == 'next')
		itemPaginationChange(active_index+1);
	if(direction == 'previous')
		itemPaginationChange(active_index-1);
}

function addProductToSale(id_product)
{
	var discount_type = $("#"+id_product+"_edit_flash_sale_discount_type").val();
	var discount_amount = $("#amount_add_product"+id_product).val();
	var id_flash_sale = $('#id_flash_sale_to_edit').val();

	$.ajax({
			data: {
				id_flash_sale : id_flash_sale, 
				id_product : id_product,
				discount_type : discount_type,
				discount_amount : discount_amount,
				action : 'addItemToFlashSale'
			},
			dataType : 'json'
	}).done(function(result) {
		if(result['query']==true)
		{
			$("#edit_sale_add_product_search").val("");
			$("#edit_sale_add_product_search_results").hide();
			$("#edit_sale_add_product_"+id_product).empty();
			refreshEditProductBlock(id_flash_sale);
			setTimeout(function(){
				$("#edit_sale_add_product_search_results_confirmation").empty();
				$("#edit_sale_add_product_"+id_product).removeClass("well");
				$("#edit_sale_add_product_"+id_product).empty();
			}, 1000);
		}
	});
}

function updateFlashSaleInfo()
{
	var id_flash_sale = parseInt($("#id_flash_sale_to_edit").val());
	var group_restriction = $('select[name=edit_discount_group_restriction]').val();
	var currency_restriction = $('select[name=edit_discount_currency_restriction]').val();
	var country_restriction = $('select[name=edit_discount_country_restriction]').val();
	var font = $('select[name=edit_flashsale_text_font]').val();
	var bg_color = $("#edit_flashsale_bg_color").val();
	var text_color = $("#edit_flashsale_text_color").val();
	var name_keys = [];
	var name_values = [];
	jQuery('.edit-flash-sale-name').each(function() {
    	var currentElement = $(this);
    	var value = $(this).val();
    	var id_lang = $(this).attr('renos-face');
    	name_keys.push(id_lang);
    	name_values.push(value);
	});
	$.ajax({
			data: {
				id_flash_sale : id_flash_sale, 
				group_restriction : group_restriction,
				currency_restriction : currency_restriction,
				country_restriction : country_restriction,
				font : font,
				bg_color : bg_color,
				text_color : text_color,
				name_keys : name_keys,
				name_values : name_values,
				action : 'updateFlashSaleInfo'
			},
			dataType : 'json'
	}).done(function(result) {
		refreshTable();
	});
}

function updateDateStart(id_flash_sale, update, label_text)
{
	$("#update_date_end").empty();
	$("#update_date_start").empty();

	var label = '<div class="col-sm-12 col-md-12 col-lg-12"><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><img style="cursor: pointer;" src="../img/admin/disabled.gif" onclick="hideUpdateDate();">&nbsp;&nbsp;&nbsp;&nbsp;<label class="label label-default">'+label_text+'</label>&nbsp;&nbsp;</div>';
	var input = '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><input type="text" class="" id="flash_sale_date_from_edit_'+id_flash_sale+'" name="flash_sale_date_from_edit_'+id_flash_sale+'" style="width:150px;"/>';
	var js = '<script type="text/javascript">$(function () {$("#flash_sale_date_from_edit_'+id_flash_sale+'").datetimepicker({ dateFormat: "yy-mm-dd", pickDate: true, pickTime: true});});</script></div>';
	var button = '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><button class="btn btn-primary" type="button" onClick="changeStartDate('+id_flash_sale+');"><i class="icon-calendar"></i>&nbsp;&nbsp;'+update+'</button></span></div></div><div class="clear">&nbsp;</div>';
	var html = label+input+js+button;

	$("#update_date_start").html(html);
}

function updateDateEnd(id_flash_sale, update, label_text)
{
	$("#update_date_end").empty();
	$("#update_date_start").empty();

	var label = '<div class="col-sm-12 col-md-12 col-lg-12"><div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><img style="cursor: pointer;" src="../img/admin/disabled.gif" onclick="hideUpdateDate();">&nbsp;&nbsp;&nbsp;&nbsp;<label class="label label-default">'+label_text+'</label>&nbsp;&nbsp;</div>';
	var input = '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><input type="text" class="" id="flash_sale_date_to_edit_'+id_flash_sale+'" name="flash_sale_date_to_edit_'+id_flash_sale+'" style="width:150px;"/>';
    var js = '<script type="text/javascript">$(function () {$("#flash_sale_date_to_edit_'+id_flash_sale+'").datetimepicker({ dateFormat: "yy-mm-dd", pickDate: true, pickTime: true});});</script></div>';
    var button = '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"><button class="btn btn-primary" type="button" onClick="changeEndDate('+id_flash_sale+');"><i class="icon-calendar"></i>&nbsp;&nbsp;'+update+'</button></div></div><div class="clear">&nbsp;</div>';
	var html = label+input+js+button;

	$("#update_date_end").html(html);
}

function hideUpdateDate()
{
	$("#update_date_end").empty();
	$("#update_date_start").empty();
}

function showFlashSaleDefaultImage()
{
	$("#flash_sale_default_img_div").show(500);
	$("#show_default_img").hide(500);
}

function hideFlashSaleDefaultImage()
{
	$("#flash_sale_default_img_div").hide(500);
	$("#show_default_img").show(500);
}


function showDiv(div1)
{
	$('#'+div1).show(500);
}

function hideDiv(div1)
{
	$('#'+div1).hide(500);
}

function showEditDivSections(div1, div2)
{
	$('#'+div1).hide();
	$('#'+div2).show();
}

function hideEditDivSections(div1, div2)
{
	$('#'+div1).hide();
	$('#'+div2).show();
}

function refreshEditProductBlock(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'editFlashSale'
			},
			dataType : 'json'
	}).done(function(result) {
		$("#flash_sale_edit_product_block").empty();
		$("#flash_sale_edit_product_block").html(result['product_html']);
		$("#pagnation_product_pages").html(result['pagination_html']);
	});
}

function editSaleRemoveItem(id_product, id_specific_price, title, text, confirmButtonText, cancelButtonText, confirmDelete, confirmDeleteMsg, confirmCancel, confirmCancelMsg)
{
	var id_flash_sale = $("#id_flash_sale_to_edit").val();
	$('#edit_flash_sale_modal').modal('hide');
	swal({
		title: title,
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: confirmButtonText,
		cancelButtonText: cancelButtonText,
		closeOnConfirm: false,
		closeOnCancel: false 
	}, function(isConfirm){
		if (isConfirm) {
			$.ajax({
					data: {
						id_flash_sale: id_flash_sale,
						id_product : id_product,
						id_specific_price : id_specific_price,
						action : 'EditSaleRemoveItem'
					}
			}).done(function() {
				refreshTable();
				refreshEditProductBlock(id_flash_sale);
				swal(confirmDelete, confirmDeleteMsg, "success");
				$('#edit_item_'+id_product).remove();
				$('#edit_flash_sale_modal').modal('show', 300);
			});
		} else {
			swal(confirmCancel, confirmCancelMsg, "error");   
			$('#edit_flash_sale_modal').modal('show');
		} });
}

function changeEndDate(id_flash_sale, date)
{
	var new_date = $('#flash_sale_date_to_edit_'+id_flash_sale).val();
	if(new_date!='')
	{
		$.ajax({
				data: {
					id_flash_sale: id_flash_sale,
					new_date: new_date,
					action : 'UpdateEndDate'
				}
		}).done(function(html) {
			refreshTable();
			$("#update_date_end").empty();
			$("#update_date_start").empty();
	  });
	}
}

function changeStartDate(id_flash_sale, date)
{
	var new_date = $('#flash_sale_date_from_edit_'+id_flash_sale).val();
	if(new_date!='')
	{
		$.ajax({
				data: {
					id_flash_sale: id_flash_sale,
					new_date: new_date,
					action : 'UpdateStartDate'
				}
		}).done(function(html) {
			refreshTable();
			$("#update_date_start").empty();
			$("#update_date_end").empty();
	  });
	}
}

function update_payment_type_div_class(id, id_item, type) 
{
    if(id == type+"_"+id_item+"_discount_type_amount_label")
    {
        document.getElementById(type+"_"+id_item+"_discount_type_amount_label").className = "btn btn-info";
        document.getElementById(type+"_"+id_item+"_discount_type_percent_label").className = "btn btn-default";
        $("#"+id_item+type+"_discount_type_symbol").html(default_currency_sign).text();

    	insertItemDiscountTypeToDB(id_item, type, 'amount');
    }
    if(id == type+"_"+id_item+"_discount_type_percent_label")
    {
        document.getElementById(type+"_"+id_item+"_discount_type_percent_label").className = "btn btn-info";
        document.getElementById(type+"_"+id_item+"_discount_type_amount_label").className = "btn btn-default";
        $("#"+id_item+type+"_discount_type_symbol").text("%");

    	insertItemDiscountTypeToDB(id_item, type, 'percentage');
    }
}

function refreshTable()
{
	$.ajax({
			data: {
				action : 'RefreshTable'
			},
			dataType : 'json'
	  }).done(function(flash_sale_info) {
		$("#flash_sale_info_table_body").empty();
		$("#flash_sale_info_table_body").html(decodeURIComponent(flash_sale_info['content']));
	  });
}

function deleteItAll(id_flash_sale)
{
	$.ajax({
			data: {
				id_flash_sale: id_flash_sale,
				action : 'DeleteItAll'
			}
	}).done(function() {
		refreshTable();
	});
}

function burnItAll(id_flash_sale, title, text, confirmButtonText, cancelButtonText, confirmDelete, confirmDeleteMsg, confirmCancel, confirmCancelMsg)
{
	swal({
		title: title,
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: confirmButtonText,
		cancelButtonText: cancelButtonText,
		closeOnConfirm: false,
		closeOnCancel: false 
	}, function(isConfirm){
		if (isConfirm) {
			$.ajax({
					data: {
						id_flash_sale: id_flash_sale,
						action : 'DeleteItAll'
					}
			}).done(function() {
				refreshTable();
			});
			swal(confirmDelete, confirmDeleteMsg, "success");
		} else {
			swal(confirmCancel, confirmCancelMsg, "error");   
		} });
}

function saleCreatedAlert()
{
	swal(sale_created_header, sale_created_text, "success");
}

function step1()
{
  	$('#flashsales_selected_items_table_body').empty();
	$("#flash_sale_information_div").show();
	$("#flash_sale_progress_1").show(100);
	$("#flash_sale_progress_2").hide(100);
	$("#flash_sale_progress_3").hide(100);
	$("#flash_sale_progress_4").hide(100);
	$("#flash_sale_design_div").hide();
	$("#flash_sale_configuration_div").hide();
	$("#flash_sale_confirmation_div").hide();
	$("#modal-footer").hide();
}

function step2(direction)
{
	if(direction=="back")
	{
		$.ajax({
				data: {
					action : 'emptyTempDiscount'
				}
		  }).done(function() {
		  	$('#flashsales_selected_items_table_body').empty();
			$("#flash_sale_information_div").hide(500);
			$("#flash_sale_progress_2").show(100);
			$("#flash_sale_progress_1").hide(100);
			$("#flash_sale_progress_3").hide(100);
			$("#flash_sale_progress_4").hide(100);
			$("#flash_sale_design_div").show(500);
			$("#flash_sale_configuration_div").hide();
			$("#flash_sale_confirmation_div").hide();
			$("#modal-footer").hide();
		});
	}
	else
	{
		var date_from = $('#flash_sale_date_from').val();
		var date_to = $('#flash_sale_date_to').val();
		var flash_type = $('input[name=flash_type]:checked').val();
		if(flash_type == 'timed' && (!date_from || !date_to))
		{
			if(!date_from)
				$("#flash_sale_date_from_div").addClass("has-error");
			if(!date_to)
				$("#flash_sale_date_to_div").addClass("has-error");
		}
		else
		{
			$('#flashsales_selected_items_table_body').empty();
			$("#flash_sale_progress_2").show(100);
			$("#flash_sale_progress_1").hide(100);
			$("#flash_sale_progress_3").hide(100);
			$("#flash_sale_progress_4").hide(100);
			$("#flash_sale_information_div").hide(500);
			$("#flash_sale_design_div").show(500);
			$("#flash_sale_configuration_div").hide();
			$("#flash_sale_confirmation_div").hide();
			$("#modal-footer").hide();
		}
	}
}

function step3()
{
	$("#flash_sale_progress_3").show(100);
	$("#flash_sale_progress_1").hide(100);
	$("#flash_sale_progress_2").hide(100);
	$("#flash_sale_progress_4").hide(100);

	$("#flash_sale_confirmation_div").hide();
	$("#flash_sale_configuration_div").show();
	$("#flash_sale_information_div").hide(500);
	$("#flash_sale_design_div").hide(500);
	$("#modal-footer").hide();
}

function step4()
{
	var count = 0;
	/* Check to see if all selected products/categories have an amount entered */
	$.each($(".flash-sale-amount-input"), function(index, value){
		if(!$(this).val())
		{
			count++;
		}
	});
	if(count > 0)
		$(".flash-sale-amount-div").addClass("has-error");
	else
	{
		$("#flash_sale_progress_4").show(100);
		$("#flash_sale_progress_1").hide(100);
		$("#flash_sale_progress_2").hide(100);
		$("#flash_sale_progress_3").hide(100);
		fillConfirmationDiv();
		$("#flash_sale_information_div").hide();
		$("#flash_sale_design_div").hide();
		$("#flash_sale_configuration_div").hide(500);
		$("#flash_sale_confirmation_div").show(500);
		$("#modal-footer").show(500);
	}
}

function fillConfirmationDiv()
{
	var name = $('#flash_sale_name_'+lang).val();
	var date_from = $('#flash_sale_date_from').val();
	var date_to = $('#flash_sale_date_to').val();
	var group = $('#discount_group_restriction').val();
	var country = $('#discount_country_restriction').val();
	var currency = $('#discount_currency_restriction').val();
	var flash_type = $('input[name=flash_type]:checked').val();
	$.ajax({
			data: {
				group: group,
				country: country,
				currency: currency,
				flash_type: flash_type,
				dataType: "json",
				action : 'CreateConfirmationTable'
			}
	}).done(function(ids) {
		var ids = JSON.parse(ids);
		$("#flash_sale_confirmation_name").text(name);
		$("#flash_sale_confirmation_date_from").text(date_from);
		$("#flash_sale_confirmation_date_to").text(date_to);
		if(flash_type != 'timed')
			$("#flash_sale_confirmation_date_from_div").hide();
		$("#flash_sale_confirmation_type").text(ids.flash_type_name);
		$("#flash_sale_confirmation_group").text(ids.group_name);
		$("#flash_sale_confirmation_currency").text(ids.currency_name);
		$("#flash_sale_confirmation_country").text(ids.country_name);

		$("#flash_sale_confirmation_product_table_body").empty();
		$("#flash_sale_confirmation_product_table_body").append(ids.html);
	});
}

function removeItemFromSaleCreation(id_product)
{
	$("#tr_flash_info_"+id_product).addClass("hidden");
}

function submitCreateFlashSale()
{
	//event.preventDefault();
	var table = document.getElementById("confirmation_table");
	var removed = $(table).find('tr.hidden');
	var ids_removed = [];
	$(removed).each(function(){
		var id_product = this.id.substr(14);
		ids_removed.push(id_product);
	})
	var stringified = JSON.stringify(ids_removed);
	document.getElementById("removed_products_array").value = stringified;

	document.getElementById("newFlashSaleForm").submit();
}

$(function() {

	$.ajaxSetup({
		type: "POST",
		url: admin_module_ajax_url,
		data: {
			ajax : true,
			id_tab : current_id_tab,
			controller : admin_module_controller,
		}
	});

	/*setTimerColor('bg');
	setTimerColor('shadow');*/

	if(sale_created_alert == 1)
		saleCreatedAlert();
	$("#flash_sale_information_div").show();
	$("#flash_sale_design_div").hide();
	$("#flash_sale_configuration_div").hide();
	$("#flash_sale_confirmation_div").hide();
	$("#modal-footer").hide();
	$("#liveSearchResultsCategories").hide(500);

	function search(lang)
	{
		$.ajax({
				data: { value: $("#liveSearch").val(), action: 'search', lang: lang}
		  }).success(function(msg) 
		  {
				json = jQuery.parseJSON(msg);

				var html = '<p style="color:red;"><b>Products</b></p>'
		  		html += '<ul style="list-style-type: none;border:1px;">';
				var count = 0;
				$(json).each( function (index, value) 
				{
					var existing = "";
					var temp = "";
					if(value.existing == 1)
						existing = '<p><span class="label label-danger">'+flash_existing_sp+'</span></p>';
					if(value.temp == 1)
						temp = ' style="display:none;"';
					var val = value.name.replace(new RegExp("'", 'g'), '');
					html += '<li id="search_product_result_'+value.id_product+'"'+temp+'><a style="opacity:1.0;color:#000000;cursor:pointer;" onClick="selectProduct('+value.id_product+', \''+lang+'\');"><h4><u>' + value.name + '</u></h4><p>Stock : '+value.stock+'</p>'+existing+'<img style="height:auto; width:auto; max-width:100px; max-height:100px;" src="'+value.image+'"><span>'+value.price+value.currency_symbol+'</span></a><hr></li>';
					count++;
				});
				html += '</ul>';
				if(count == 0){ $("#liveSearchResults").hide(); return; }
				$("#liveSearchResults").html(html);
				if($('#search_type_product').prop('checked'))
					$("#liveSearchResults").show();
		  });
	}

	function searchCategory(lang)
	{
		$.ajax({
				data: { value: $("#liveSearch").val(), action: 'searchCategory', lang:lang}
		  }).success(function(msg) 
		  {
				json = jQuery.parseJSON(msg);
				var html = '<p style="color:red;"><b>Categories</b></p>'
		  		html += '<ul style="list-style-type: none;border:1px;">';
				var count = 0;
				$(json).each( function (index, value) 
				{
					var temp = "";
					if(value.existing == 1)
						temp = ' style="display:none;"';
					var val = value.name.replace("'", " ");
					html += '<li id="search_category_result_'+value.id_category+'"'+temp+'><a style="opacity:1.0;color:#000000;cursor:pointer;" onClick="selectCategory('+value.id_category+',\''+val+'\', \''+lang+'\');"><h4><u>' + val + '</u></h4><span>'+value.text_category+'</span><br /><span>'+value.stripped_desc+'</span></a></li>';
					count++;
				});
				html += '</ul>';
				if(count == 0){ $("#liveSearchResultsCategories").hide(); return; }
				$("#liveSearchResultsCategories").html(html);

				if($('#search_type_category').prop('checked'))
					$("#liveSearchResultsCategories").show();
		  });
	}

	function editFlashSaleSearch(lang)
	{
		$.ajax({
				data: { 
					value: $("#edit_sale_add_product_search").val(),
					action: 'editSaleProductSearch',
					lang: lang,
				},
			dataType : 'json'
		  }).success(function(json) 
		  {
		  		if(json['empty'] == 1)
		  		{
		  			$("#edit_sale_add_product_search_results").empty();
					$("#edit_sale_add_product_search_results").removeClass("edit-sale-add-product-search-results");
		  		}
		  		else
		  		{
					$("#edit_sale_add_product_search_results").removeClass("hidden");
		  			$("#edit_sale_add_product_search_results").addClass("edit-sale-add-product-search-results");
			  		$('#edit_sale_add_product_search_results').html(json['content']);
					$("#edit_sale_add_product_search_results").show(50);
			  	}
		  });
	}

	function viewFlashSaleSearch(lang)
	{
		var id_flash_sale = $("#id_flash_sale_to_edit").val();
		$.ajax({
				data: { 
					value: $("#edit_sale_view_product_search").val(),
					action: 'viewSaleProductSearch',
					lang: lang,
					id_flash_sale: id_flash_sale
				},
			dataType : 'json'
		  }).success(function(json) 
		  {
		  		$("#flash_sale_edit_product_block").html('');
		  		$('#flash_sale_edit_product_block').html(json['search_html']);
		  });
	}

//Livesearch
	$("#liveSearch").keyup(function (e) {
		search_val = $("#liveSearch").val();
		if(e.keyCode != 13) 
		{
			if (search_val.length >= 3) {
				searchCategory(lang);
				search(lang);	// Calls the search function.
			}

			if (search_val.length < 3) {
				$("#liveSearchResults").html("");
				$("#liveSearchResultsCategories").html("");
			}
		}
		else
			e.preventDefault();
	});

// Edit Sale Search
	$("#edit_sale_add_product_search").keyup(function (e) {
		search_val = $("#edit_sale_add_product_search").val();
		if(e.keyCode != 13) 
		{
			if (search_val.length >= 3)
			{
				editFlashSaleSearch(lang);	// Calls the search function.
				$("#edit_sale_add_product_search_results").addClass("edit-sale-add-product-search-results");
				$("#edit_sale_add_product_search_results").removeClass("hidden");
			}

			if (search_val.length < 3)
			{
				$("#edit_sale_add_product_search_results").empty();
				$("#edit_sale_add_product_search_results").removeClass("edit-sale-add-product-search-results");
				$("#edit_sale_add_product_search_results").addClass("hidden");
			}
		}
		else
			e.preventDefault();
	});

// View Sale Search
	$("#edit_sale_view_product_search").keyup(function (e) {
		search_val = $("#edit_sale_view_product_search").val();
		if(e.keyCode != 13) 
		{
			if (search_val.length >= 3)
				viewFlashSaleSearch(lang);	// Calls the search function.

			if (search_val.length < 3)
			{
				var id_flash_sale = $("#id_flash_sale_to_edit").val();
				refreshEditProductBlock(id_flash_sale);
				$("#pagnation_product_pages").show();
			}
		}
		else
			e.preventDefault();
	});

    // Load functions
    Main.init();
    $('.module_confirmation').delay(5000).hide(500);
    $('#alert_activate_reminder').delay(15000).hide(500);

    /*Flipclock back-office */
    var currentDate = new Date();
	var futureDate = new Date(future_timestamp*1000);
	// Calculate the difference in seconds between the future and current date
	var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
	if(diff > 0)
	{
		// Instantiate a coutdown FlipClock
		clock = $('.clock').FlipClock(diff, {
			clockFace: 'HourlyCounter',
			countdown: true,
			language: lang_code
		});

		clock_miniture = $('.clock-mini').FlipClock(diff, {
			clockFace: 'HourlyCounter',
			countdown: true,
			language: lang_code,
		});
	}
});