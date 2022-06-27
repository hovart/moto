{if isset($manufacturers) AND $manufacturers}
<div id="tptnbrands" class="tptncarousel container">
	<div class="block_title"><span>{l s='Manufacturers' mod='tptnbrands'}</span></div>
	<div class="brandcrsl row">
	{foreach from=$manufacturers item=manufacturer name=homeBrands}
		<div class="brand-item col-xs-12">
			<a class="logo_image" href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html':'UTF-8'}" title="{$manufacturer.name|escape:'html':'UTF-8'}">
				<img src="{$img_manu_dir}{$manufacturer.id_manufacturer|escape:'html':'UTF-8'}-small_default.jpg" alt="{$manufacturer.name|escape:'html':'UTF-8'}" />
			</a>	
		</div>
	{/foreach}
	</div>
</div>
{/if}