
{if isset($tptnbotbanner_slides)}
<div id="tptnbotbanner" class="tptnbanner container">
	<ul class="row">
	{foreach from=$tptnbotbanner_slides item=slide name=BotBanner}
	{if $slide.active}
		<li class="{$slide.bsclass}">
			<a href="{$slide.url}" title="{$slide.title}">
				<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`tptnbotbanner/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.title|escape:'htmlall':'UTF-8'}" />
			</a>
		</li>
	{/if}
	{/foreach}
	</ul>
</div>
{/if}