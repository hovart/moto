
{if isset($tptnsubbanner_slides)}
<div id="tptnsubbanner" class="tptnbanner container">
	<ul class="row">
	{foreach from=$tptnsubbanner_slides item=slide name=SubBanner}
	{if $slide.active}
		<li class="col-xs-12 col-sm-4">
			<a href="{$slide.url}" title="{$slide.title}">
				<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`tptnsubbanner/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.title|escape:'htmlall':'UTF-8'}" />
			</a>
		</li>
	{/if}
	{/foreach}
	</ul>
</div>
{/if}