
<!-- Module ColBanner -->
{if isset($tptncolbanner_slides)}
<div id="colbanner">
	<ul>
	{foreach from=$tptncolbanner_slides item=slide name=ColBanner}
	{if $slide.active}
		<li class="{if $smarty.foreach.ColBanner.first}first{else}{/if}">
			<a href="{$slide.url}" title="{$slide.title}">
				<img src="{$smarty.const._MODULE_DIR_}/tptncolbanner/images/{$slide.image}" alt="{$slide.title}" title="{$slide.title}" />
			</a>
		</li>
	{/if}
	{/foreach}
	</ul>
</div>
{/if}

