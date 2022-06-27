{*

*}

<!-- Block Change background -->


<style>
{literal}
@media (min-width : 641px) { /* applique uniquement sur grands ecrans */
body{background-image :{/literal}{if $change_imageactivate=='yes'}{literal} url({/literal}{$image}{literal}){/literal}{/if}{if $change_imageactivate2=='yes'}{literal},{/literal}{literal} url({/literal}{$image2}{literal}){/literal}{/if}{literal};
background-position: {/literal}{if $change_imageactivate=='yes'}{$change_positionwidth} {$change_positionheight}{/if}{if $change_imageactivate2=='yes'}{literal},{/literal}{$change_positionwidth2} {$change_positionheight2}{/if}{literal};
background-repeat:{/literal}{if $change_imageactivate=='yes'}{$change_repeat}{/if}{if $change_imageactivate2=='yes'}{literal},{/literal}{$change_repeat2}{/if}{literal};
background-attachment:{/literal}{if $change_imageactivate=='yes'}{$change_fixed}{/if}{if $change_imageactivate2=='yes'}{literal},{/literal}{$change_fixed2}{/if}{literal};{/literal}
{if $change_coloractivate=='yes'}{literal}background-color:#{/literal}{$change_color}{literal};{/literal}{/if}
{literal}}
}
{/literal}
</style>