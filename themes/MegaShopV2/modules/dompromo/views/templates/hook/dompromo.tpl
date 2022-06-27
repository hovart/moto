<!-- MODULE {$moduleinfo}-->
{$timer}
{$slider}
{assign var="heightslide" value="127"}
{assign var="widthslide" value="176"}
{math equation="haut - bord" haut=$heightslide bord=3 assign=heightli}

{if $vfproducts}

	<div id="special_block_right" class="block">
		<h4><img src="{$img_dir}venteflash_{$lang_iso}.png" title="{l s='Specials!' mod='dompromo'}" alt="{l s='Specials!' mod='dompromo'}"/></h4>

		<div class="block_content">
		  <div id="venteflash" name="venteflashslider" style="width:{$widthslide}px;height:{$heightslide}px;overflow:hidden">
		  <ul class="dpclassul">
			{foreach from=$vfproducts item=product name=myLoop}
				{if $product.reduction_type == 'percentage'}
					{if $product.typesale==1 || $product.typesale==2 || $product.typesale==3}
						{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %X" date2=$product.datefin|date_format:"%m/%d/%Y %X" interval="minutes" assign="diff"}
						<li class="dpclassli" style="width:{$widthslide}px;height:{$heightli}px;">
							<div class="dptitrepromo" style="color:#{$blocktexttimeColor};">
						{l s='Time Remaining:' mod='dompromo'}</div>
						<div class="dpinfotemprestant" style="color:#{$blocktimeColor};" id="vftimera{$product.id}">[vftimera{$product.id}]</div>
						<br />
						<script type="text/javascript">
							var cda{$product.id} = new countdown('cda{$product.id}');
							cda{$product.id}.Div	= "vftimera{$product.id}";
							cda{$product.id}.TargetDate	 ="{$product.datefin|date_format:"%m/%d/%Y %X"}";
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
	

					<a class="dpimage" style="float:left" href="{$product.link}"><img class="dpimage" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'vignetteBig')}" alt="{$product.legend|escape:htmlall:'UTF-8'}" title="{$product.name|escape:htmlall:'UTF-8'}" /></a>
					<div id="vente-flash-desc"><h5><div class="dptitreproduct" style="color:#{$blocktextColor};"><a class="dptitreproduct" href="{$product.link}" title="{$product.name|escape:htmlall:'UTF-8'}">{$product.name|truncate:30|escape:'htmlall':'UTF-8'}</a></div></h5>
					<div class="price-discount dpprixbarre">
					<a href="{$product.link}">
						{if !$priceDisplay}{displayWtPrice p=$product.price_without_reduction}
				  	{else}
				  		{if $ps_version && $ps_version >= '1.4.3'}{displayWtPrice p=$product.price_without_reduction}
				  		{else}
				  			{math equation="var1 / (1 + var2 / 100)" var1=$product.price_without_reduction var2=$product.rate assign=px_hors_tx format="%.2f"}
						   	{$px_hors_tx}
						  {/if}
						{/if}
					</a>
					</div>
					{if $product.reduction_percent|intval}<div class="reduction dpreduction"><a href="{$product.link}">-{$product.reduction_percent|intval}%</a></div>{/if}
					<div><div class="dpprixpromo" style="color:#{$blockpriceColor};"><a href="{$product.link}">{if !$priceDisplay}{displayWtPrice p=$product.price}{else}{displayWtPrice p=$product.myprice}{/if}</a></div></div></div>
					</li>
					
					{else}

					{/if}

				{/if}
			{/foreach}
			</ul>
		  
		  </div>
		  <!--<br />
		  {if $csproducts}
		  	<h4><img src="{$img_dir}venteflash_{$lang_iso}.png" title="{l s='Specials!' mod='dompromo'}" alt="{l s='Specials!' mod='dompromo'}"/></h4>
		  	<div class="dpbtnavenir"><a href="{$link->getModuleLink('dompromo', 'default', ['process' => 'commingsoon'])}" title="{l s='Comming soon Specials' mod='dompromo'}"  class="button_large">{l s='Comming soon Specials' mod='dompromo'}</a></p>
		  {/if}
			<p><a href="{$link->getModuleLink('dompromo', 'default', ['process' => 'flashsales'])}" title="{l s='All Specials' mod='dompromo'}"  class="button_large">{l s='All Specials' mod='dompromo'}</a></p>-->
	 	</div></div>
	</div>
	
	{foreach from=$vfproducts item=product name=products}
		{if $product.typesale==1 || $product.typesale==2 || $product.typesale==3}
			<script type="text/javascript">
				<!--
				cda{$product.id}.Setup();
	 			//-->
			</script>
		{/if}
	{/foreach}
{/if}

<!-- /MODULE {$moduleinfo} -->
