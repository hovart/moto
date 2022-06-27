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

$(function() {
    // Load functions
    Main.init();
    setTimeout('$("#alert_config_pf").hide(500)',3000);

	$('#pf_logo_checkbox').change(function() {
		if(!this.checked)
			$('#pf_logo_link').attr('readonly', 'readonly'); 
		else
		    $('#pf_logo_link').removeAttr('readonly');
	});

	$('#layout-demo-tooltip-bg').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/bg.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-text').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/text.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-table-bg').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/table_bg.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-table-text').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/table_text.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-button-text').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/button_text.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-button-bg').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/button_bg.png" /></span>'),
		position: 'right'
	});
	$('#layout-demo-tooltip-logo').tooltipster({
		content: $('<span><img class="layout_img" src="'+pf_img_path+'layout-demo/custom_logo.png" /></span>'),
		position: 'right'
	});
});
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
		$('table[class="table"]').each(function() {
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
		$('.selectpicker').each(function() {
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

function update_div_class(id) {
    if(id == "flash_type_time_label") {
        document.getElementById("flash_type_time_label").className = "btn btn-info";
        document.getElementById("flash_type_stock_label").className = "btn btn-default";
    }
    if(id == "flash_type_stock_label") {
        document.getElementById("flash_type_stock_label").className = "btn btn-info";
        document.getElementById("flash_type_time_label").className = "btn btn-default";
    }
}

function showDiv(id) {
	$("#"+id).show(300);
}

function hideDiv(id) {
	$("#"+id).hide(300);
}

function setShopLogoForPaymentPage(url)
{
	$('#pf_logo_link').val(url);
}

function submitConfigForm()
{
	customize_button = $('input[name=payment_page_custom]:checked').val();
	title = $('#pf_title').val();

	if(customize_button == "0" || (customize_button == "1" && title.length > 0))
		$('#form_postfinance').submit();
	else
		alert(pf_title_error);
}