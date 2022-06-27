<!-- Module HomeSlider -->
{if isset($tptnhomeslider_slides)}
<div id="tptnhomeslider">
	{foreach from=$tptnhomeslider_slides item=slide}
	{if $slide.active}
		<a href="{$slide.url}"><img src="{$smarty.const._MODULE_DIR_}/tptnhomeslider/images/{$slide.image}" alt="{$slide.title}" /></a>
	{/if}
	{/foreach}
</div>
{/if}
<!-- /Module HomeSlider -->