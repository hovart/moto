/**
 * 2013-2016 MADEF IT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.frr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author MADEF IT <contact@madef.fr>
 *  @copyright  2013-2016 MADEF IT
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$(function() {
    $('#desc-advancedimporter_log-delete, #desc-advancedimporter_flow-delete, #desc-advancedimporter_block-delete').click(function() {
        return confirm(CONFIRM_DELETE);
    });

    $('.easyCsvFormContainer').on('change', '.easyCsvForm', function() {
        var data = $('.easyCsvForm').serialize();
        AIRefreshForm(data);
    });

    $('.easyCsvFormContainer').on('click', '.easyCsvFormRemove', function() {
        var input = $('<input type="hidden" class="easyCsvForm" name="'+$(this).attr('name')+'" value="1" />');
        $('.easyCsvFormContainer').append(input);
        input.change();
    });

    $('[name=advanced_mode]').change(function() {
        AIRefreshForm($('#data').val());

        AIHideUnusedForm();
    });

    $('#flow_type').change(function() {
        AIRefreshForm();
    });

    $('.js-preview-flow').fancybox({
        width: '90%',
        minHeight: '90%',
        scrolling: 'visible'
    });

});

function AIHideUnusedForm()
{
    if ($('#advanced_mode_on').is(':checked')) {
        $('#template').parent().parent().show();
        $('#flow_type').parent().parent().hide();
        $('.easyCsvFormContainer').parent().parent().hide();
    } else {
        $('#template').parent().parent().hide();
        $('#flow_type').parent().parent().show();
        $('.easyCsvFormContainer').parent().parent().show();
    }
}

function AIRefreshForm(data)
{
    if ($('#advanced_mode_on').is(':checked')) {
        return;
    }

    data += '&flow_type='+$('#flow_type').val().split('.')[0];
    data += '&object_type='+$('#flow_type').val().split('.')[1];
    $.ajax({
        'url': window.location.href+'&ajax=1',
        method: 'POST',
        data: data,
        dataType: 'json'
    }).done(function(data) {
        $('.easyCsvFormContainer').html(data.form);
        $('#template').val(data.template);
        $('#data').val(data.data);
    });
}
