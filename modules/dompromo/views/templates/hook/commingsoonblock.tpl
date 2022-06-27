<!-- MODULE {$moduleinfo} -->
{$timer}
{$slider}
{assign var="heightslide" value="143"}
{assign var="widthslide" value="176"}
{math equation="haut - bord" haut=$heightslide bord=3 assign=heightli}

<div id="special_block_right" class="block">
	<h4><a href="{$link->getModuleLink('dompromo', 'default', ['process' => 'commingsoon'])}" title="{l s='Comming Soon!' mod='dompromo'}">{l s='Price drop!' mod='dompromo'}</a></h4>
	<div class="block_content">
    <div id="venteflash" name="venteflashslider" style="width:{$widthslide}px;height:{$heightslide}px;overflow:hidden">
			<ul class="dpclassul">
				{foreach from=$csproducts item=product name=products}
					{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
						{math equation="x-((x*y)/100)" x=$product.price_without_reduction y=$product.vfreduction format="%.2f" assign="vf_blockreduction"}
						{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %X" date2=$product.datedebut|date_format:"%m/%d/%Y %X" interval="minutes" assign="diff"}
					{else}
						{math equation="x-((x*y)/100)" x=$product.price_without_reduction y=$product.reduction_percent format="%.2f" assign="vf_blockreduction"}
						{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %X" date2=$product.reduction_from|cat:" 00:00:00"|date_format:"%m/%d/%Y %X" interval="minutes" assign="diff"}
					{/if}
					<script type="text/javascript">
						var cda{$product.id} = new countdown('cda{$product.id}');
						cda{$product.id}.Div	= "vftimera{$product.id}";
						{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
							cda{$product.id}.TargetDate	 ="{$product.datedebut|date_format:"%m/%d/%Y %X"}";
						{else}
							cda{$product.id}.TargetDate	 ="{$product.reduction_from|cat:" 00:00:00"|date_format:"%m/%d/%Y %X"}";
						{/if}
					</script>
					{if $diff >= 1440}
						<script type="text/javascript">
							cda{$product.id}.DisplayFormat = "%%D%%{$vfday} %%H%%{$vfhour} %%M%%{$vfminute} %%S%%{$vfsecond}";
						</script>
					{elseif $diff > 60 AND $diff < 1440}
						<script type="text/javascript">
							cda{$product.id}.DisplayFormat = "%%H%%{$vfhour} %%M%%{$vfminute} %%S%%{$vfsecond}";
						</script>
					{elseif $diff < 60 AND $diff >= 1}
						<script type="text/javascript">
							cda{$product.id}.DisplayFormat = "%%M%%{$vfminute} %%S%%{$vfsecond}";
						</script>
					{else}
						<script type="text/javascript">
							cda{$product.id}.DisplayFormat = "%%S%%{$vfsecond}";
						</script>
					{/if}

					<li class="dpclassli" style="width:{$widthslide}px;height:{$heightli}px;">
						<div class="dptitrepromo" style="color:#{$blockcommingtexttimeColor};">{l s='Deadline to begin:' mod='dompromo'}</div>
						<div class="dpinfotemprestant" style="color:#{$blockcommingtimeColor};" id="vftimera{$product.id}">{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
							[vftimera{$product.id}]{else}{$product.reduction_from|date_format:"%d/%m/%Y"}{/if}
						</div>
						<br />

						<a class="dpimage" style="float:left" href="{$product.link}"><img class="dpimage" src="{$link->getImageLink($product.link_rewrite, $product.id_image, $tailleimg)}" alt="{$product.legend|escape:htmlall:'UTF-8'}" title="{$product.name|escape:htmlall:'UTF-8'}" /></a>
						<div>
							<h5><div class="dptitreproduct" style="color:#{$blockcommingtextColor};"><a href="{$product.link}" title="{$product.name|escape:htmlall:'UTF-8'}">{$product.name|truncate:25|escape:'htmlall':'UTF-8'}</a></div></h5>
							<span class="price-discount dpprixbarre">{displayWtPrice p=$product.price_without_reduction}</span>
					 		<span class="reduction dpreduction">(- 
					 			{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
									{$product.vfreduction|intval}%)
								{else}
									{$product.reduction_percent|intval}%)
								{/if}
							</span>
							<span ><div class="dpprixpromo" style="color:#{$blockcommingpriceColor};">{convertPrice price=$vf_blockreduction}</div></span>
						</div>
					</li>

				{/foreach}
			</ul>
    </div><br />

    <p><a href="{$link->getModuleLink('dompromo', 'default', ['process' => 'commingsoon'])}" title="{l s='Comming Soon' mod='dompromo'}"  class="button_large">{l s='Comming Soon' mod='dompromo'}</a></p>

	</div>
</div>

{foreach from=$csproducts item=product name=products}
	<script type="text/javascript">
		<!--
			cda{$product.id}.Setup();
		//-->
	</script>
{/foreach}

{literal}
	<script type="text/javascript">
		$(document).ready(function(){
					venteflashslider = new Slider({
					speed: {/literal}{$effect}{literal},
					duration: {/literal}{$transition}{literal}});
					venteflashslider.init("venteflash");
		});
	</script>
{/literal}
<!-- /MODULE {$moduleinfo} -->
