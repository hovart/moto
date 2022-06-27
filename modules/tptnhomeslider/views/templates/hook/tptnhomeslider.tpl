{if isset($tptnhomeslider_slides)}
<div id="tptnhomeslider" class="flexslider">
	<ul class="slides">
    {foreach from=$tptnhomeslider_slides item=slide}
    {if $slide.active}
    <li>
        <a href="{$slide.url}">
            <img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`tptnhomeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.title|escape:'htmlall':'UTF-8'}" />
        </a>
    </li>
    {/if}
    {/foreach}
	</ul>
</div>
{/if}