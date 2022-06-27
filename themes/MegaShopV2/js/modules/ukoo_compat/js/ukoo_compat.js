$(document).ready(function(){   
    $(".ukoo_compat_search_bloc select.standard").change(function(){
        if($(this).parent().nextAll("p").find("select.standard").length>0){
            $(this).parent().nextAll("p").find("select.standard").attr('disabled', 'disabled');
            if($(this).val()!=''){
                $(this).parent().next("p").find("select.standard").removeAttr('disabled');
                var cache = Array();
                var id_cache = '';
                if($(this).parent().prevAll("p").find("select.standard").length>0){
                    $(this).parent().prevAll("p").find("select.standard").each(function(){
                        cache.push($(this).attr('id').replace(/id_critere_filtre_/, '') + '_' + $(this).val());
                    });
                }
                for(var i=cache.length-1; i>=0; i--){
                    if(i!=cache.length-1)
                        id_cache += '|';
                    id_cache += cache[i];
                }
                loadSelect($(this).attr('id'), $(this).val(), $(this).parent().next("p").find("select.standard").attr('id'), $(this).parent().next("p").find("select.standard").val(), id_cache, 1);
            }else $(this).parent().nextAll("p").find("select.standard").attr('disabled', 'disabled');
            $(this).parent().nextAll("p").find("select.standard").val('');
        }
    });
    
/*    
*       On déplace le block ukoo_compat qui se trouve dans la colonne de gauche et on le met en place avant le titre
*/    //$('#left_column #ukoo_compat_search_block_side').insertAfter('#center_column h1');
        $('#left_column [id^="ukoo_compat"][id$="_search_block_side"]').insertAfter('#center_column h1');

/*
*       Sur la page de resultat On masque les moteurs de recherches des autres UkooCompat
*/      var url = window.location.href;
        if (url.indexOf('module') >= 0) {
            $("#center_column div[id*='_search_block_side']").hide();
        };
});