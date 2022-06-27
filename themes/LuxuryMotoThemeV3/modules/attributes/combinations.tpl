{if !empty($combinations)}

    <div class="sizes_display">

        <div class="sizes_display_title">{if $lang_iso == fr }Disponibles: {else}  verfügbar: {/if}</div>
            <ul>
            {foreach $combinations as $k=>$v}
                {* $v|@var_dump *}
                {if $v.id_attribute_group == "4"}
                    <li>{$v.attribute_name}</li>
                {/if}
            {/foreach}
            </ul>
            <button class="btn btn-primary btn-size"> {if $lang_iso == de }Produkt ansehen{else} Voir le produit{/if}</button>
    </div>

{/if}