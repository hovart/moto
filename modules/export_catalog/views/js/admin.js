/**
 * Common behaviour for modules configuration
 *
 * @category Prestashop
 * @category Module
 * @author Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license commercial license see license.txt
**/

// http://stackoverflow.com/a/2548133
if (typeof String.prototype.endsWith !== 'function') {
    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - ((suffix && suffix.length) || 0)) !== -1;
    };
}

/**
 * Init module interface
 */

; samdha_module.preInit = function() {
	"use strict";

	var $ = samdha_module.$;
	var config = samdha_module.config;

};

samdha_module.postInit = function () {
    "use strict";

    var $ = samdha_module.$;
    var config = samdha_module.config;
    var messages = samdha_module.messages;

	/**
	 * reset columns index
	 */
	var orderColumns = function() {
		var index = 0;
		var $lines = $( "#" + config.short_name + "fields tbody tr" );
		$lines.each(function() {
			var $this = $(this);
			$('select, input', $this).each(function() {
				if ($(this).prop('name')) {
					$(this).prop('name', $(this).prop('name').replace(/\[fields\]\[\d+\]/g, '[fields]['+index+']'));
				}
			});
			if (index%2) {
				$this.addClass('alt_row');
			} else {
				$this.removeClass('alt_row');
			}
			$('.field_delete, .field_up, .field_down', $this).css('display', 'none');
			if (index > 0) {
				$('.field_delete, .field_up', $this).css('display', 'inline-block');
			}
			index++;
			if (index < $lines.length) {
				$('.field_down', $this).css('display', 'inline-block');
			}
			if (index == $lines.length) {
				$this.addClass('last');
			} else {
				$this.removeClass('last');
			}
		});
	};

	var columnsTableWidth = function() {
		if (!config.version_15) {
			if ($('#' + config.short_name + 'fields').width() > 413) {
				$('#' + config.short_name + 'fields').parent().css({
					'paddingLeft' : '0',
					'clear' : 'left'
				});
			} else {
				$('#' + config.short_name + 'fields').parent().removeAttr('style');
			}
		}
	};

	var testAjax = function() {
        if (Object.keys(config.models_url).length > 0)
        {
            var id_model = Object.keys(config.models_url)[0];
            $.ajax({
                "url": config.models_url[id_model],
                "type": "POST",
                "dataType" : "json",
                "data": {
                    "ajax": 1,
                    "action": "ajaxTest",
                    "id_shop": config.models_shop[id_model],
                    "id_lang": config.models_lang[id_model],
                    "id_currency": config.models_currency[id_model]
                }
            })
            .done(function(json) {
                if (json.status != 'success') {
                    samdha_module.displayError(messages.access_error);
                }
            })
            .fail(function() {
                samdha_module.displayError(messages.ajax_error);
            });
        }
	};

	/**
	 * pre export the catalog
	 */
	var exportCatalog = function(current, total, filename) {
		var id_model = $('#' + config.short_name + '_model').val();
		$.ajax({
			"url": config.models_url[id_model],
			"type": "POST",
			"dataType" : "json",
			"cache": false,
			"data": {
				"ajax": 1,
				"action": "preExportCatalog",
				"current": current,
				"total": total,
				"filename": filename,
				"model": id_model,
				"id_shop": config.models_shop[id_model],
				"id_lang": config.models_lang[id_model],
				"id_currency": config.models_currency[id_model]
			}
		})
		.done(function(json) {
			if (json.status == 'success') {
				if (json.data.next == -1) {
					$("#model_export_image").hide();
					$("#model_export_button").show();
					var parameters = {
						'filename': json.data.filename,
						'model': id_model,
						'actionExportModel': 1,
                        'rand': Math.random()
					};
					window.location = config.module_url + '&' + $.param(parameters);
				} else {
					var percent = 100 * json.data.next / json.data.total;
					$("#model_export_image").html("<span title='" + json.data.next + '/' + json.data.total + "'><img style='vertical-align: middle' src='../img/loader.gif'/> " + percent.toFixed(2) + '%</span>');
					setTimeout(function(){exportCatalog(json.data.next, json.data.total, json.data.filename);},1);
				}
			} else {
				if (json.message) {
					alert(json.message);
				}
				$("#model_export_image").remove();
				$("#model_export_button").show();
			}
		})
		.fail(function() {
			$("#model_export_image").remove();
			$("#model_export_button").show();
		});
	};

	/**
	 * pre run the export
	 */
	var runExport = function(current, total, filename) {
		var id_export = $('#' + config.short_name + '_export').val();
		var id_model = config.exports_model[id_export];
		$.ajax({
			"url": config.models_url[id_model],
			"type": "POST",
			"cache": false,
			"dataType" : "json",
			"data": {
				"ajax": 1,
				"action": "preExportCatalog",
				"current": current,
				"total": total,
				"filename": filename,
				"model": id_model,
				"id_shop": config.models_shop[id_model],
				"id_lang": config.models_lang[id_model],
				"id_currency": config.models_currency[id_model]
			}
		})
		.done(function(json) {
			if (json.status == 'success') {
				if (json.data.next == -1) {
					$.ajax({
						"url": config.models_url[id_model],
						"type": "POST",
						"dataType" : "json",
						"cache": false,
						"data": {
							"ajax": 1,
							"action": "postExportCatalog",
							"filename": json.data.filename,
							"export": id_export,
							"id_shop": config.models_shop[id_model],
							"id_lang": config.models_lang[id_model],
							"id_currency": config.models_currency[id_model]
						},
						success: function(json) {
							if (json.status == 'success') {
								alert(messages.done);
							} else {
								if (json.message) {
									alert(json.message);
								}
							}
							$("#run_export_image").remove();
							$("#export_run_button").show();
						}
					});
				} else {
					var percent = 100 * json.data.next / json.data.total;
					$("#run_export_image").html("<span title='" + json.data.next + '/' + json.data.total + "'><img style='vertical-align: middle' src='../img/loader.gif'/> " + percent.toFixed(2) + '%</span>');
					setTimeout(function(){runExport(json.data.next, json.data.total, json.data.filename);},1);
				}
			} else {
				if (json.message) {
					alert(json.message);
				}
				$("#run_export_image").remove();
				$("#export_run_button").show();
			}
		})
		.fail(function() {
			$("#run_export_image").remove();
			$("#export_run_button").show();
		});
	};

	// fake select
	if (config.active_tab && config.active_tab != 'tabModel') {
		var active = $('#samdha_tab').tabs('option', 'active');
		$('#samdha_tab').tabs("option", 'active', 0);
		if (!config.version_15) {
			$('#' + config.short_name + 'fields').width('413');
		}
		var td_width = $('#' + config.short_name + 'fields td:first').width();
		var td_width2 = $('#' + config.short_name + 'fields td:first').next().width();
		$('#' + config.short_name + 'fields .fake_select').each(function() {
			$(this).width(td_width).addClass('chosen-container chosen-container-single').find('span').wrap('<a/>');
			$('a', this).addClass('chosen-single').append('<div><b/></div>');
		});
		$('#' + config.short_name + 'fields .field_title input').width(td_width2);
		if (!config.version_15) {
			$('#' + config.short_name + 'fields').width('');
		}
		$('#samdha_tab').tabs("option", 'active', active);
	}

	// scheduled export folder
	$('#' + config.short_name + '_folder').hide();
	$("#jqueryFileTree_div").fileTree({
		root: "",
		startFolder: $('#' + config.short_name + '_folder').val(),
		script: config.module_url + "&ajax=1&action=getFileTree&dontsave=1"
	}, function(file) {
        $('#' + config.short_name + '_folder').val(file);
		  if (file !== '')
		  {
			$('#' + config.short_name + '_folder_preview').text(file);
			$('#' + config.short_name + '_url_preview a').prop('href', $('#' + config.short_name + '_url_preview a').text());
			$('#' + config.short_name + '_url_preview').show();
		}
		else
			$('#' + config.short_name + '_url_preview').hide();
    });
	$('#' + config.short_name + '_export_model').change(function() {
      $('#' + config.short_name + '_filename_preview').text(config.models_filename[$(this).val()]);
		$('#' + config.short_name + '_url_preview a').prop('href', $('#' + config.short_name + '_url_preview a').text());
	}).change();

	// working directory
	$('#' + config.short_name + '_directory').prop('readonly', 'readonly');
	$("#jqueryFileTree_div2").fileTree({
		root: "",
		startFolder: $('#' + config.short_name + '_directory').val(),
		script: config.module_url + "&ajax=1&action=getFileTree&dontsave=0"
	}, function(file) {
        $('#' + config.short_name + '_directory').val(file);
    });

	// buttons
	$('#samdha_content .actions_list .action_select')
		.button({
			text: false,
			icons: {
				primary: "ui-icon-triangle-1-s"
			}
		})
		.click(function() {
			var menu = $( this ).parent().next().show().position({
				my: "left top",
				at: "left bottom",
				of: this
			});
			$( document ).one( "click", function() {
				menu.hide();
			});
			return false;
		})
		.parent()
			.buttonset()
			.next()
				.hide()
				.menu();
	$('#samdha_content .actions_list button.action').click(function(event) {
		if (!$(this).prop('name')) {
			setTimeout(
				(function(el) {
					return function() {
						el.focus().click();
					};
				})($(this).next()),
				1
			);
			event.preventDefault();
		}
	});
	$('#samdha_content .actions_list ul a')
		.on('click', function(event) {
			event.preventDefault();
			var $current_button = $(this);
			$current_button.tooltip('destroy');
			$('button.action', $(this).parents('.actions_list'))
				.tooltip('destroy')
				.prop('name', $current_button.prop('rel'))
				.prop('title', $current_button.attr('title'))
				.button({label:$current_button.text()})
				.tooltip({
		            position: {
		                my: "center bottom-20",
		                at: "center top",
		                using: function( position, feedback ) {
		                    $( this ).css( position );
		                    $( "<div>" )
		                        .addClass( "arrow" )
		                        .addClass( feedback.vertical )
		                        .addClass( feedback.horizontal )
		                        .appendTo( this );
		                }
		            }
		        })
				.click();
			$current_button.tooltip({
	            position: {
	                my: "right center",
	                at: "left center"
	            }
	        });
		})
		.tooltip('destroy')
		.tooltip({
            position: {
                my: "right center",
                at: "left center"
            }
        });


	$('#samdha_content .actions_list').on('click', 'button[name=actionDeleteModel], button[name=actionDeleteExport]', function (event) {
		if (!confirm(messages.delete_confirmation))
			event.preventDefault();
	});

	$('#samdha_content .actions_list').on('click', 'button[name=actionUploadModel]', function (event) {
		if (!$('#model_upload_input').val()) {
			$('#model_upload_input').focus().click();
			event.preventDefault();
		}
	});

	$("#samdha_content input[name=actionSaveModel]").click(function (event) {
		if ($.trim($("#" + config.short_name + "model_name").val()) == '') {
			event.preventDefault();
			$("#" + config.short_name + "model_name").focus();
			alert(messages.missing_name);
		}
		if (
			($("#" + config.short_name + "_model").length > 0)
			&& ($("#" + config.short_name + "_model").val() != $("#" + config.short_name + "_current_model").val())
			&& !confirm(messages.override_confirmation)
		) {
			event.preventDefault();
		}
	});

	$("#samdha_content input[name=actionSaveExport]").click(function (event) {
		if ($.trim($("#" + config.short_name + "export_name").val()) == '') {
			event.preventDefault();
			$("#" + config.short_name + "export_name").focus();
			alert(messages.missing_name);
		}

		if (
			($("#" + config.short_name + "_employees option:selected").length == 0)
			&& ($("#" + config.short_name + "_folder").val() == '')
			&& (
				($("#" + config.short_name + "_ftp_off").length == 0)
				|| ($("#" + config.short_name + "_ftp_off").is(':checked'))
			)
		) {
			event.preventDefault();
			$("input." + config.short_name + "_employees").first().focus();
			alert(messages.missing_action);
		}

		if (
			($("#" + config.short_name + "_export").length > 0)
			&& ($("#" + config.short_name + "_export").val() != $("#" + config.short_name + "_current_export").val())
			&& !confirm(messages.override_confirmation)
		) {
			event.preventDefault();
		}
	});

	$("#model_export_button").click(function (event) {
		event.preventDefault();
		$(this).hide().after("<span id='model_export_image'><img style='vertical-align: middle' src='../img/loader.gif'/> 0.00%</span>");
		exportCatalog(0, 0, 0, 0, '');
	});

	$("#export_run_button").click(function (event) {
		event.preventDefault();
		$(this).hide().after("<span id='run_export_image'><img style='vertical-align: middle' src='../img/loader.gif'/> 0.00%</span>");
		runExport(0, 0, 0, 0, '');
	});

	$('#samdha_content').on('click', '.field_add', function (event) {
		$(this).tooltip('close');
		var $original = $(this).parent().parent();
		$('select', $original).chosen('destroy');
		var $field = $original.clone();
		$field.insertAfter($original);
		orderColumns();
        $('*[title]', $field).tooltip({
            position: {
                my: "center bottom-20",
                at: "center top",
                using: function( position, feedback ) {
                    $( this ).css( position );
                    $( "<div>" )
                        .addClass( "arrow" )
                        .addClass( feedback.vertical )
                        .addClass( feedback.horizontal )
                        .appendTo( this );
                }
            }
        });
		$('td', $field).removeClass('ui-state-hover');
		if ($('select', $field).length)
		{
			$('select', $field)[0].selectedIndex = 0;
			$('input.field_title', $field).val($('select option:selected', $field).text()).data('modified', false);
		}
		$('span', $field).css('display','inline-block');
		$('select', $original).chosen();
		$('.field_value, input', $field).val('');
		$('select', $field).change().chosen().focus();
	});

	$('#samdha_content').on('click', '.field_delete', function (event) {
		$(this).tooltip('close');
		$(this).parent().parent().remove();
		orderColumns();
	});

	$('#samdha_content').on('click', '.field_up', function (event) {
		$(this).tooltip('close');
		var $line = $(this).parent().parent();
		$line.prev().before($line);
		orderColumns();
	});

	$('#samdha_content').on('click', '.field_down', function (event) {
		$(this).tooltip('close');
		var $line = $(this).parent().parent();
		$line.next().after($line);
		orderColumns();
	});

	// update columns title according to column value
	// unless if title has been customized
	$('#' + config.short_name + 'fields').on('change', 'select', function (event) {
		var $line = $(this).parent().parent();
		if (!$('input.field_title', $line).data('modified')) {
			$('input.field_title', $line).val($('option:selected', $(this)).text());
		}
		if ($(this).val() == 'fix') {
			$('div.field_value input', $line).prop('placeholder', messages.value);
			$('div.field_value', $line).show();
		} else if ($(this).val().endsWith('_all')) {
			$('div.field_value input', $line).prop('placeholder', messages.separator);
			$('div.field_value', $line).show();
		} else {
			$('div.field_value', $line).hide();
		}
	});

	// fake select
    if (!config.active_tab) {
        var td_width = $('#' + config.short_name + 'fields td:first').width();
        var td_width2 = $('#' + config.short_name + 'fields td:first').next().width();
        $('#' + config.short_name + 'fields .fake_select').each(function() {
            $(this).width(td_width).addClass('chosen-container chosen-container-single').find('span').wrap('<a/>');
            $('a', this).addClass('chosen-single').append('<div><b/></div>');
        });
        $('#' + config.short_name + 'fields .field_title input').width(td_width2);
    }
	$('#' + config.short_name + 'fields').on('click', '.fake_select', function (event) {
		var $input = $('input', this).first();
		var $select = $('<select/>').addClass('input_large').width($(this).width()).prop('name', $input.prop('name'));

		$('span', this).html('<img style="padding-top: 4px" height="16" src="../img/loader.gif">');
		for (var key in config.possible_fields) {
			$select.append(
				$('<option>', { value : key })
          			.text(config.possible_fields[key])
          	);
		};
		$select.val($input.val());
		$(this).after($select);
		$(this).remove();
		$select.on('chosen:ready', function(event, args) {
			var $this = $(this);
			$this.off('chosen:ready');
			setTimeout(function(){
				$this.trigger('chosen:open');
			},1);
		}).chosen({
			placeholder_text_single: ''
		});
    });

	// set column titles as customized
	$('#' + config.short_name + 'fields').on('change', 'input.field_title', function(event) {
		$(this).data('modified', true);
	});

	// check if column titles have been customized
	$("#" + config.short_name + "fields input.field_title").each(function() {
		var $this = $(this);
		var $line = $this.parent().parent();

		$this.data('modified', $this.val() != $('select option:selected', $line).text());
	});

	$('input[name="model[datas][header]"]').change(function() {
		if ($('input[name="model[datas][header]"]:checked').val() == '1') {
			$('#' + config.short_name + 'fields .field_title').show();
		} else {
			$('#' + config.short_name + 'fields .field_title').hide();
		}
		columnsTableWidth();
	});
	$('input[name="model[datas][decoration]"]').change(function() {
		if ($('input[name="model[datas][decoration]"]:checked').val() == '1') {
			$('#' + config.short_name + 'fields .field_decoration').show();
		} else {
			$('#' + config.short_name + 'fields .field_decoration').hide();
		}
		columnsTableWidth();
	});
	$('input[name="model[datas][separator]"]').keydown(function(event) {
		var code = event.keyCode || event.which;
		if (code == '9') {
			if ($(this).val() == '') {
				$(this).val('	');
				event.preventDefault();
			}
		}
	});

    $('#samdha_content input[type=radio]').change(function() {
        if ($('input[type=radio][name="' + $(this).attr('name') + '"]:checked').attr('value') == '1') {
            $('.' + $('input[type=radio][name="' + $(this).attr('name') + '"][value=1]').attr('id')).show();
            $('.' + $('input[type=radio][name="' + $(this).attr('name') + '"][value=0]').attr('id')).hide();
        } else {
            $('.' + $('input[type=radio][name="' + $(this).attr('name') + '"][value=1]').attr('id')).hide();
            $('.' + $('input[type=radio][name="' + $(this).attr('name') + '"][value=0]').attr('id')).show();
        }
    }).change();

    $('select.nochosen[multiple=\'multiple\']').each(function() {
    	$(this).multiSelect({
		  selectableHeader: "<div class='selectableHeader'></div>",
		  selectionHeader: "<div class='selectionHeader'></div>"
		});
    	$('.ms-container', $(this).parent())
    		.css('position', 'relative')
    		.append('<span class="samdha_button multiselect_addall">⇉</span>')
    		.append('<span class="samdha_button multiselect_removeall">⇇</span>')
    		.find('.samdha_button').button();
    	$('.multiselect_addall', $(this).parent())
    		.attr('title', messages.select_all)
	    	.on('click', function() {
		    	$('select', $(this).parent().parent()).multiSelect('select_all');
	    	});
    	$('.multiselect_removeall', $(this).parent())
    		.attr('title', messages.unselect_all)
	    	.on('click', function() {
		    	$('select', $(this).parent().parent()).multiSelect('deselect_all');
	    	});
    });
    $('.selectableHeader').text(messages.selectable_header);
    $('.selectionHeader').text(messages.selection_header);

	$('#' + config.short_name + '_hour').on('change', function(event) {
		$('#' + config.short_name + '_hours')
			.multiSelect('deselect_all')
			.multiSelect('select', $(this).val());
	});
	$('#' + config.short_name + '_minute').on('change', function(event) {
		$('#' + config.short_name + '_minutes')
			.multiSelect('deselect_all')
			.multiSelect('select', $(this).val());
	});

	columnsTableWidth();
	testAjax();
};
