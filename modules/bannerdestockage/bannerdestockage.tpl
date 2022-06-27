
<!-- Module SubBanner -->
{if isset($bannerdestockage_slides)}
<div id="subbanner">
	<ul>
	{assign var='BannerPerLine' value=3}
	{foreach from=$bannerdestockage_slides item=slide name=SubBanner}
	{if $slide.active}
		<li {if $smarty.foreach.SubBanner.iteration%$BannerPerLine == 1}class="first"{/if}>
			<a href="{$slide.url}" title="{$slide.title}">
				<img src="{$smarty.const._MODULE_DIR_}/bannerdestockage/images/{$slide.image}" alt="{$slide.title}" title="{$slide.title}" />
			</a>
		</li>
	{/if}
	{/foreach}
	</ul>
</div>
{/if}

