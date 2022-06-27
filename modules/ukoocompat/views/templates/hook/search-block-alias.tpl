{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}


<div id="ukoocompat_search_block_alias_{$search->id|intval}" class="block ukoocompat_search_block_alias">
    <h4 class="title_block">{l s='Search by alias' mod='ukoocompat'}</h4>
    <div class="block_content">
        <form id="ukoocompat_search_block_alias_form_{$search->id|intval}" action="{$form_action|escape}" method="get" class="ukoocompat_search_block_alias_form{if $search->dynamic_criteria} dynamic_criteria{/if}">
            {if !$is_rewrite_active}
                <input type="hidden" name="fc" value="module" />
                <input type="hidden" name="module" value="ukoocompat" />
                <input type="hidden" name="controller" value="{$search->controller|escape}" />
            {/if}
            <input type="hidden" name="id_search" value="{$search->id|intval}" />
            <input type="hidden" name="id_lang" value="{$search->current_id_lang|intval}" />

            <input id="ukoocompat_search_alias_{$search->id|intval}" type="text" class="form-control" placeholder="{l s='Type your alias here' mod='ukoocompat'}" />
            <p class="help-block">{l s='eg. ALIAS1' mod='ukoocompat'}</p>

            <div class="ukoocompat_search_block_button">
                <button id="ukoocompat_search_block_alias_submit_{$search->id|intval}" type="submit" name="ukoocompat_search_submit" class="button btn btn-default button-medium">
                    <span>{l s='Search' mod='ukoocompat'}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function() {
        $("#ukoocompat_search_alias_{$search->id|intval}").autocomplete(
            '{$module_dir}ajax.php',
            {
                minChars: 3,
                max: 10,
                width: 500,
                selectFirst: false,
                scroll: false,
                dataType: "json",
                formatItem: function(data, i, max, value, term) {
                    return value;
                },
                parse: function(data) {
                    var mytab = [];
                    for (var i = 0; i < data.length; i++){
                        mytab[mytab.length] = { data: data[i], value: '<b>' + data[i].alias + '</b>' };
                        for (var j = 0; j < data[i].instances.length; j++){
                            mytab[mytab.length] = { data: data[i].instances[j], value: data[i].instances[j]['criteria'] };
                        }
                    }
                    return mytab;
                },
                extraParams: {
                    search_controller: '{$search->controller|escape}',
                    id_lang: {$cookie->id_lang|intval},
                    id_search: {$search->id|intval},
                    fc: 'module',
                    module: 'ukoocompat'
                }
            })
            .result(function(event, data, formatted) {
                if (data.link != undefined){
                    $("#ukoocompat_search_alias_{$search->id|intval}").val(data.criteria);
                    document.location.href = data.link;
                }else{
                    document.location.href = 'javascript:void(0);';
                }
            });
    });
    // ]]>
</script>
