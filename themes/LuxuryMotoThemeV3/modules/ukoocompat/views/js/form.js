/**
* Recherche de produits par compatibilité
*
* @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
* @copyright Ukoo 2015
* @license   Ukoo - Tous droits réservés
*
* "In Ukoo we trust!"
*/

$(document).ready(function() {

    // Tous les scripts propres au formulaire de recherche
    if($('#ukoocompat_search_form').length > 0){

        // Déplacement de la modal
        $('#ukoocompat_modal').appendTo('#main #content');

        // Gestion des onglets
        $('.search_tab').hide();
        $('.tab-row.active').removeClass('active');
        var currentFormTab = $('#currentFormTab').val();
        $('#search_' + currentFormTab).show();
        $('#search_link_' + currentFormTab).parent().addClass('active');

        // Positions des filtres dans le formulaire d'une recherche
        $("table.sortable").sortable({
            placeholder: "ui-state-highlight",
            handle : ".dragHandle",
            axis: "y",
            items: "tr.table_row",
            helper: function(e, tr)
            {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function(index)
                {
                    // Set helper cell sizes to match the original sizes
                    $(this).width($originals.eq(index).width())
                });
                return $helper;
            },
            update : function(){
                $.ajax({
                    url: './index.php?controller=AdminUkooCompatSearch&ajax&action=updateSearchFilterPositions',
                        type: 'POST',
                        data: {
                        token: $('#currentToken').val(),
                        id_search: parseInt($('#id_ukoocompat_search').val()),
                        sorted_ids: $('table.sortable').sortable('toArray')
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(request.responseText);
                    },
                    success: function(data){
                        $("#table_used_filter .table_row .positions").each(function(index){
                            $(this).text(index);
                        });
                    }
                });
            }
        });

        // initialisation des WYSIWYG
        tinySetup({
            editor_selector :"autoload_rte"
        });
    }

    // Tous les scripts propres au formulaire de compatibilité
    if($('#ukoocompat_compat_form').length > 0){

        // Sélection de produit dans le formulaire de compatibilité
        if($("#id_product").length>0){
            $("#product_autocomplete_input")
                .autocomplete("ajax_products_list.php", {
                minChars: 1,
                autoFill: true,
                max:20,
                matchContains: true,
                mustMatch:false,
                scroll:false,
                cacheLength:0,
                formatItem: function(item){
                    return item[1]+' - '+item[0];
                }
            }).result(addProduct);
        }

        $("#product_autocomplete_input").setOptions({
            extraParams: {
                excludeIds : getProductsIds()
            }
        });
    }

    // Tous les scripts propres au formulaire d'instance d'alias
    if($('#ukoocompat_alias_instance_form').length > 0){

        // Sélection de produit dans le formulaire de compatibilité
        if($('#id_alias').length>0){
            $('#alias_autocomplete_input')
                .autocomplete('./index.php?controller=AdminUkooCompatAlias&ajax=1&action=searchAlias&token=' + $('#aliasToken').val(), {
                    minChars: 1,
                    autoFill: true,
                    max:20,
                    matchContains: true,
                    mustMatch:false,
                    scroll:false,
                    cacheLength:0,
                    formatItem: function(item){
                        return item[1]+' - '+item[0];
                    }
                }).result(addAlias);
        }

        $("#alias_autocomplete_input").setOptions({
            extraParams: {
                excludeIds : getAliasIds()
            }
        });
    }
});

/* ajout d'un produit à la compatibilité */
function addProduct(event, data, formatted){
    if (data == null)
        return false;
    var productId = data[1];
    var productName = data[0];

    var $divProduct = $("#divProduct");
    var $id_product = $("#id_product");
    var $nameProduct = $("#nameProduct");

    $divProduct.html('<div class="form-control-static"><button type="button" class="delProduct btn btn-default" name="' + productId + '" onclick="delProduct(\'' + productId + '\');"><i class="icon-remove text-danger"></i></button>&nbsp;'+ productName +'</div>');
    $nameProduct.val(productName);
    $id_product.val(productId);
    $('#product_autocomplete_input').val('');
    $('#product_autocomplete_input').setOptions({
        extraParams: {excludeIds : getProductsIds()}
    });
}

/* incrémente les produits pour l'ajout aux campatibilités */
function getProductsIds(){
    if ($("#id_product").val() === undefined)
        return '99999999,';
    return '99999999,' + $("#id_product").val().replace(/\-/g,",");
}

/* décrémente les produits pour l'ajout aux compatibilités */
function delProduct(id){
    var div = getE("divProduct");
    var input = getE("id_product");
    var name = getE("nameProduct");

    var inputCut = input.value.split("-");
    var nameCut = name.value.split("¤");

    if (inputCut.length != nameCut.length)
        return jAlert("Bad size");

    input.value = "";
    name.value = "";
    div.innerHTML = "";
    for (i in inputCut){
        if (!inputCut[i] || !nameCut[i])
            continue ;

        if (inputCut[i] != id){
            input.value = inputCut[i];
            name.value = nameCut[i];
            div.innerHTML += "<div class=\"form-control-static\"><button type=\"button\" class=\"delProduct btn btn-default\" onclick=\"delProduct(" + inputCut[i] + ")\" name=\"" + inputCut[i] +"\"><i class=\"icon-remove text-danger\"></i></button>&nbsp;" + nameCut[i] + "</div>";
        }
        else
            $("#selectProduct").append("<option selected=\"selected\" value=\"" + inputCut[i] + "-" + nameCut[i] + "\">" + inputCut[i] + " - " + nameCut[i] + "</option>");
    }

    $("#product_autocomplete_input2").setOptions({
        extraParams: {excludeIds : getProductsIds()}
    });
}

// ajout d'un alias à l'instance
function addAlias(event, data, formatted){
    if (data == null)
        return false;
    var aliasId = data[1];
    var aliasName = data[0];

    var $divAlias = $("#divAlias");
    var $id_alias = $("#id_alias");
    var $alias = $("#alias");

    $divAlias.html('<div class="form-control-static"><button type="button" class="delAlias btn btn-default" name="' + aliasId + '" onclick="delAlias(\'' + aliasId + '\');"><i class="icon-remove text-danger"></i></button>&nbsp;'+ aliasName +'</div>');
    $alias.val(aliasName);
    $id_alias.val(aliasId);
    $('#alias_autocomplete_input').val('');
    $('#alias_autocomplete_input').setOptions({
        extraParams: {excludeIds : getAliasIds()}
    });
}

function getAliasIds(){
    if ($("#id_alias").val() === undefined)
        return '99999999,';
    return '99999999,' + $("#id_alias").val().replace(/\-/g,",");
}

function delAlias(id){
    var div = getE("divAlias");
    var input = getE("id_alias");
    var name = getE("alias");

    var inputCut = input.value.split("-");
    var nameCut = name.value.split("¤");

    if (inputCut.length != nameCut.length)
        return jAlert("Bad size");

    input.value = "";
    name.value = "";
    div.innerHTML = "";
    for (i in inputCut){
        if (!inputCut[i] || !nameCut[i])
            continue ;

        if (inputCut[i] != id){
            input.value = inputCut[i];
            name.value = nameCut[i];
            div.innerHTML += "<div class=\"form-control-static\"><button type=\"button\" class=\"delAlias btn btn-default\" onclick=\"delAlias(" + inputCut[i] + ")\" name=\"" + inputCut[i] +"\"><i class=\"icon-remove text-danger\"></i></button>&nbsp;" + nameCut[i] + "</div>";
        }
        else
            $("#selectAlias").append("<option selected=\"selected\" value=\"" + inputCut[i] + "-" + nameCut[i] + "\">" + inputCut[i] + " - " + nameCut[i] + "</option>");
    }

    $("#alias_autocomplete_input2").setOptions({
        extraParams: {excludeIds : getAliasIds()}
    });
}

/* gestion des onglets */
function displaySearchTab(tab)
{
    $('.search_tab').hide();
    $('.tab-row.active').removeClass('active');
    $('#search_' + tab).show();
    $('#search_link_' + tab).parent().addClass('active');
    $('#currentFormTab').val(tab);
}

/* ajout du filtre à la recherche */
function addFilterToSearch(id_filter)
{
    $('#tr_available_filter_' + parseInt(id_filter) + ' button i').attr('class', 'icon-refresh icon-spin icon-fw');
    $.ajax({
        url: './index.php?controller=AdminUkooCompatSearch&ajax&action=addFilterToSearch',
        type: 'POST',
        data: {
            token: $('#currentToken').val(),
            id_search: parseInt($('#id_ukoocompat_search').val()),
            id_filter: parseInt(id_filter)
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            $('#tr_available_filter_' + parseInt(id_filter)).remove();
            $('#table_used_filter_empty').hide();
            if($('#table_available_filter tbody tr.table_row').length < 1)
                $('#table_available_filter_empty').show();
            $('#badge_available_filter').text(parseInt($('#badge_available_filter').text())-1);
            $('#badge_used_filter').text(parseInt($('#badge_used_filter').text())+1);
            $('#table_used_filter tbody').append(data);
        }
    });
}

/* retrait du filtre à la recherche */
function removeFilterFromSearch(id_search_filter)
{
    $('#tr_used_filter_' + parseInt(id_search_filter) + ' button i').attr('class', 'icon-refresh icon-spin icon-fw');
    $.ajax({
        url: './index.php?controller=AdminUkooCompatSearch&ajax&action=removeFilterFromSearch',
        type: 'POST',
        data: {
            token: $('#currentToken').val(),
            id_search_filter: parseInt(id_search_filter)
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            $('#tr_used_filter_' + parseInt(id_search_filter)).remove();
            $('#table_available_filter_empty').hide();
            if($('#table_used_filter tbody tr.table_row').length < 1)
                $('#table_used_filter_empty').show();
            $('#badge_used_filter').text(parseInt($('#badge_used_filter').text())-1);
            $('#badge_available_filter').text(parseInt($('#badge_available_filter').text())+1);
            $('#table_available_filter tbody').append(data);
        }
    });
}


/* toggle du statut du filtre pour la recherche */
function toggleSearchFilterState(id_search_filter)
{
    $.ajax({
        url: './index.php?controller=AdminUkooCompatSearch&ajax&action=toggleSearchFilterState',
        type: 'POST',
        data: {
            token: $('#currentToken').val(),
            id_search_filter: parseInt(id_search_filter)
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            if(data == 1){
                $('#tr_used_filter_' + id_search_filter + ' i.icon-check, #tr_used_filter_' + id_search_filter + ' i.icon-remove').toggleClass('hidden');
                $('#tr_used_filter_' + id_search_filter + ' .list-action-enable').toggleClass('action-enabled action-disabled');
            }else{
                alert(data);
            }
        }
    });
}

/* affichage des options du filtre dans la modal (UkooCompatSearchFilter::renderForm) */
function getSearchFilterForm(id_search_filter)
{
    $('#tr_used_filter_' + parseInt(id_search_filter) + ' button i').attr('class', 'icon-refresh icon-spin icon-fw');
    $.ajax({
        url: './index.php?controller=AdminUkooCompatSearchFilter&ajax&action=getSearchFilterForm',
        type: 'POST',
        data: {
            token: $('#searchFilterToken').val(),
            id_search_filter: parseInt(id_search_filter)
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            json = $.parseJSON(data);
            $('#ukoocompat_modal .modal-content').html(json.render_form);
            $('#ukoocompat_modal').modal('show');
            initSearchFilterModal();
            $('#ukoocompat_modal [name=cancel]').click(function(){
                $('#ukoocompat_modal').modal('hide');
            });
            $('#ukoocompat_modal [name=submit]').click(function(){
                updateSearchFilterForm(id_search_filter);
            });
            $('#tr_used_filter_' + parseInt(id_search_filter) + ' button i').attr('class', 'icon-caret-down');

            // Positions des groupes de critères dans le filtre
            $("#table_available_groups").sortable({
                placeholder: "ui-state-highlight",
                handle : ".dragHandle",
                axis: "y",
                items: "tr.table_row",
                helper: function(e, tr)
                {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index)
                    {
                        // Set helper cell sizes to match the original sizes
                        $(this).width($originals.eq(index).width())
                    });
                    return $helper;
                },
                update : function(){
                    $.ajax({
                        url: './index.php?controller=AdminUkooCompatSearchFilter&ajax&action=updateGroupsPositions',
                        type: 'POST',
                        data: {
                            token: $('#searchFilterToken').val(),
                            id_search_filter: parseInt($('#id_search_filter').val()),
                            sorted_ids: $('#table_available_groups').sortable('toArray')
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(request.responseText);
                        },
                        success: function(data){
                            $("#table_available_groups .table_row .positions").each(function(index){
                                $(this).text(index);
                            });
                        }
                    });
                }
            });

        }
    });
}

/* met à jour un filtre de la recherche */
function updateSearchFilterForm(id_search_filter)
{
    $('#ukoocompat_modal [name=submit] i').attr('class', 'process-icon-refresh icon-spin icon-fw');
    var name = new Array();
    $('.name_by_lang').each(function(){
        var elt = $(this);
        name[parseInt(elt.attr('id').replace('name_', ''))] = elt.val();
    });

    $.ajax({
        url: './index.php?controller=AdminUkooCompatSearchFilter&ajax&action=updateSearchFilterForm',
        type: 'POST',
        data: {
            token: $('#searchFilterToken').val(),
            id_search_filter: parseInt(id_search_filter),
            active: $('#ukoocompat_modal [name=active]:checked').val(),
            order_by: $('#ukoocompat_modal [name=order_by] option:selected').val(),
            order_way: $('#ukoocompat_modal [name=order_way] option:selected').val(),
            display_type: $('#ukoocompat_modal [name=display_type] option:selected').val(),
            name: name
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            $('#ukoocompat_modal [name=submit] i').attr('class', 'process-icon-save');
            $('#tr_used_filter_' + id_search_filter).replaceWith(data);
            $('#ukoocompat_modal').modal('hide');
        }
    });
}

/* Charge l'édition d'un groupe de critères */
function editGroup(id_group)
{
    $('#tr_available_group_' + parseInt(id_group) + ' button i').attr('class', 'icon-refresh icon-spin icon-fw');
    $.ajax({
        url: './index.php?controller=AdminUkooCompatGroup&ajax&action=getGroup',
        type: 'POST',
        data: {
            token: $('#groupToken').val(),
            id_group: id_group,
            id_search_filter: parseInt($('#id_search_filter').val())
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            $('#group_form').html(data);
            if($('#group_form').is(':hidden')){
                $('#group_form, #add_new_group_block').slideToggle(400);
            }
            $('#cancel_group').click(function(){
                $('#group_form, #add_new_group_block').slideToggle(400);
            });
            $('#save_group').click(function(){
                saveGroup();
            });
            $('#tr_available_group_' + parseInt(id_group) + ' button i').attr('class', 'icon-caret-down');
        }
    });
}

/* Suppression d'un groupe de critères */
function deleteGroup(id_group)
{
    $.ajax({
        url: './index.php?controller=AdminUkooCompatGroup&ajax&action=deleteGroup',
        type: 'POST',
        data: {
            token: $('#groupToken').val(),
            id_group: id_group
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            if (data)
            {
                $('#tr_available_group_' + parseInt(id_group)).remove();
                if($('#table_available_groups tbody tr.table_row').length < 1)
                    $('#table_available_groups_empty').show();
                $('#badge_available_groups').text(parseInt($('#badge_available_groups').text())-1);
            }
            else alert(data);
        }
    });
}

/* Met à jour ou enregistre un nouveau groupe de critères */
function saveGroup()
{
    var group_name = new Array();
    $('.group_name_by_lang').each(function(){
        var elt = $(this);
        group_name[parseInt(elt.attr('id').replace('group_name_', ''))] = elt.val();
    });
    var group_selected = [];
    $('#group_selected option:selected').each(function(i, selected){
        group_selected[i] = $(selected).val();
    });

    var id_group = parseInt($('#id_group').val());

    $.ajax({
        url: './index.php?controller=AdminUkooCompatGroup&ajax&action=saveGroup',
        type: 'POST',
        data: {
            token: $('#groupToken').val(),
            id_group: id_group,
            id_search_filter: parseInt($('#id_search_filter').val()),
            group_name: group_name,
            group_active: $('#ukoocompat_modal [name=group_active]:checked').val(),
            group_selected: group_selected
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            // édition
            if (id_group != 0)
                $('#tr_available_group_' + parseInt(id_group)).replaceWith(data);
            // création
            else
            {
                $('#table_available_groups_empty').hide();
                $('#badge_available_groups').text(parseInt($('#badge_available_groups').text())+1);
                $('#table_available_groups tbody').append(data);
            }
            $('#group_form, #add_new_group_block').slideToggle(400);
        }
    });
}

/* toggle du statut du groupe de critères */
function toggleGroupState(id_group)
{
    $.ajax({
        url: './index.php?controller=AdminUkooCompatGroup&ajax&action=toggleGroupState',
        type: 'POST',
        data: {
            token: $('#groupToken').val(),
            id_group: id_group
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            if(data == 1){
                $('#tr_available_group_' + id_group + ' i.icon-check, #tr_available_group_' + id_group + ' i.icon-remove').toggleClass('hidden');
                $('#tr_available_group_' + id_group + ' .list-action-enable').toggleClass('action-enabled action-disabled');
            }else{
                alert(data);
            }
        }
    });
}

/* Initialise la modal */
function initSearchFilterModal()
{
    // Gestion des onglets
    $('.search_filter_tab').hide();
    $('#ukoocompat_search_filter_form_panel .tab-row.active').removeClass('active');
    $('#search_filter_display').show();
    $('#search_filter_link_display').parent().addClass('active');

    // Toggle sur le bouton d'ajout de groupe
    if ($('#add_new_group_block').length > 0)
    {
        $('#group_form').hide();
        $('#cancel_group').click(function(){
            $('#group_form, #add_new_group_block').slideToggle(400);
        });
    }

    // Chargement du formulaire de création d'un nouveau groupe de critères
    $('#add_new_group').click(function(){
        editGroup(0);
    });

    $('#save_group').click(function(){
        saveGroup();
    });
}

/* gestion des onglets de la modal */
function displaySearchFilterTab(tab)
{
    $('.search_filter_tab').hide();
    $('#ukoocompat_search_filter_form_panel .tab-row.active').removeClass('active');
    $('#search_filter_' + tab).show();
    $('#search_filter_link_' + tab).parent().addClass('active');

}