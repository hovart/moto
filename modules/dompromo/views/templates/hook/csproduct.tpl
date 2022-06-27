<!-- MODULE {$moduleinfo} -->
{$timer}
{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %R" date2=$datetime|date_format:"%m/%d/%Y %R" interval="minutes" assign="csdiff"}

<script type="text/javascript">
	var vfday   = '{$vfday}';
	var vfhour  = '{$vfhour}';
	var vfmin   = '{$vfminute}';
	var vfsec   = '{$vfsecond}';
</script>

<script type="text/javascript">
	var cd{$vproducts} = new countdown('cd{$vproducts}');
	cd{$vproducts}.Div	= "vftimer{$vproducts}";
	cd{$vproducts}.TargetDate	 ="{$datetime}";
</script>   

{if $csdiff >= 1440}
	<script type="text/javascript">
		cd{$vproducts}.DisplayFormat = "%%D%%"+vfday+" %%H%%"+vfhour+" %%M%%"+vfmin+" %%S%%"+vfsec;
	</script>
{elseif $csdiff > 60 && $csdiff < 1440}
	<script type="text/javascript">
		cd{$vproducts}.DisplayFormat = "%%H%%"+vfhour+" %%M%%"+vfmin+" %%S%%"+vfsec;
	</script>
{elseif $csdiff < 60 && $csdiff >= 1}
	<script type="text/javascript">
		cd{$vproducts}.DisplayFormat = "%%M%%"+vfmin+" %%S%%"+vfsec;
	</script>
{else}
	<script type="text/javascript">
		cd{$vproducts}.DisplayFormat = "%%S%%"+vfsec;
	</script>
{/if}

<div id="block" class="dpblocproduit">
	<div class="dptypevente" style="color:#{$producttexttimeColor};"> 
		{l s='Coming soon ' mod='dompromo'}</div>
	<div class="dptemprestant" style="color:#{$producttypeColor};"> 
		{if $saletype==1}{l s='Flash Sale ' mod='dompromo'}{/if}
		{if $saletype==2}{l s='Coutant Price ' mod='dompromo'}{/if}
		{if $saletype==3}{l s='Reduction of Stocks ' mod='dompromo'}{/if}
		{l s='In ' mod='dompromo'}
	</div>
	<div id="vftimer{$vproducts}" class="dptimer" style="color:#{$producttimeColor};">[vftimer{$vproducts}]</div>
</div>

<script type="text/javascript">
	<!--
	cd{$vproducts}.Setup();
	 //-->
</script>
<!-- /MODULE {$moduleinfo} -->
