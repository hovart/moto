/**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 */

$(document).ready(function(){
    ukooCompatInit();
    // Rechargement dynamique des filtres au chargement de la page
    $('.ukoocompat_search_block_form.dynamic_criteria').each(function(){
        if($(this).find('.ukoocompat_search_block_filter .dynamic_criteria').length > 1)
            reloadDynamicCriteria($(this).find('.ukoocompat_search_block_filter .dynamic_criteria').first());
    });
    // Toggle sur les block de recherche
    $('#toggle_search_block').hide();
    $('#change_search_button').click(function(){
        $('#toggle_search_block, #ukoocompat_search_alias').slideToggle();
    });
});

// Fonction d'initialisation
function ukooCompatInit()
{

    $('.ukoocompat_search_block_form.dynamic_criteria').each(function(){
        // On désative le bouton de soumission si les filtres dynamiques sont actifs et que le dernier éléments n'est pas défini
        if ($(this).find('.ukoocompat_search_block_filter .dynamic_criteria:last').val() == '')
            $(this).find('button[type=submit]').attr('disabled', 'disabled');

        // On active le bouton de soumission lors du choix du dernier élément
        $(this).find('.ukoocompat_search_block_filter .dynamic_criteria:last').change(function(){
            $(this).parents('.ukoocompat_search_block_form.dynamic_criteria').find('button[type=submit]').removeAttr('disabled');
        });

        // Rechargement dynamique des filtres au changement de critère (sauf pour le dernier élément de la liste)
        $(this).find('.ukoocompat_search_block_filter .dynamic_criteria:not(:last)').change(function(){
            reloadDynamicCriteria($(this));
        });

        // Lorsque l'on revient sur un critère
        //      1. On désactive les filtres suivants et on reset leur valeur
        //      2. On reset la valeur du filtre courant
        //var block_filter = $(this);
        $(this).find('.ukoocompat_search_block_filter .dynamic_criteria:not(:last)').click(function(){
            if ($(this).val() !== "") {
                $(this).parent().parent().nextAll('.ukoocompat_search_block_filter').find('.dynamic_criteria').addClass('disabled').val('');
                $(this).val('');
            }
        });

        // reset du bloc de recherche
        $(this).find('.ukoocompat_search_reset').click(function(){
            resetUkooCompatForm($(this));
        });
    });
}

// Rechargement dynamique des filtres si demandé
function reloadDynamicCriteria(elt)
{

    // Animation pour le reload
    animateLoader(elt);

    // Chargement Ajax des filtres
    var data = elt.parents('form').serialize();

    $.ajax({
        type: 'GET',
        url: baseDir + 'modules/ukoocompat/ajax.php?page_name=' + $('#ukoocompat_page_name').val(),
        data: data,
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);


        },
        success: function(data){

            elt.parents('.block_content').html(data);
            ukooCompatInit();
        }
    });
}

// Réinitialise un bloc de recherche lors du clic sur le bouton "reset"
function resetUkooCompatForm(elt)
{
    // Animation pour le reload
    animateLoader(elt);

    // Chargement Ajax des filtres
    var data = elt.parents('form').serialize();
    $.ajax({
        type: 'GET',
        url: baseDir + 'modules/ukoocompat/ajax.php',
        //dataType: 'json',
        data: data+'&reset',
        error: function(jqXHR, textStatus, errorThrown){
            alert(request.responseText);
        },
        success: function(data){
            elt.parents('.block_content').html(data);
            ukooCompatInit();
        }
    });
}

// Animation du loader
function animateLoader(elt)
{
    elt.parents('form').fadeTo('slow', 0.25);
    elt.parents('.block_content').find('.icon-refresh').css('margin-top', Math.round(elt.parents('.block_content').width() / 2));
    elt.parents('.block_content').find('.loader').fadeIn('slow');
}