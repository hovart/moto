<!-- MODULE {$moduleinfo} -->
{$timer}
{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %R" date2=$datetime|date_format:"%m/%d/%Y %R" interval="minutes" assign="vfdiff"}

<script type="text/javascript">
	var vfday 		= '{$vfday}';
	var vfhour 		= '{$vfhour}';
	var vfminute 	= '{$vfminute}';
	var vfsecond 	= '{$vfsecond}';
</script>

<script type="text/javascript">
	var cd{$vfproduct} = new countdown('cd{$vfproduct}');
	cd{$vfproduct}.Div	= "vftimer{$vfproduct}";
	cd{$vfproduct}.TargetDate	 = "{$datetime|date_format:"%m/%d/%Y %X"}";
</script>

{if $vfdiff >= 1440}
	<script type="text/javascript">
		cd{$vfproduct}.DisplayFormat = "%%D%%"+vfday+" %%H%%"+vfhour+" %%M%%"+vfminute+" %%S%%"+vfsecond;
	</script>
{elseif $vfdiff > 60 AND $vfdiff < 1440}
	<script type="text/javascript">
		cd{$vfproduct}.DisplayFormat = "%%H%%"+vfhour+" %%M%%"+vfminute+" %%S%%"+vfsecond;
	</script>
{elseif $vfdiff < 60 AND $vfdiff >= 1}
	<script type="text/javascript">
		cd{$vfproduct}.DisplayFormat = "%%M%%"+vfminute+" %%S%%"+vfsecond;
	</script>
{else}
	<script type="text/javascript">
		cd{$vfproduct}.DisplayFormat = "%%S%%"+vfsecond";
	</script>
{/if}

<div id="block" class="dpblocproduit">
	<div class="dptypevente" style="color:#{$producttypeColor};"> 
		{if $saletype==1 && $titrevf==1}{l s='Flash Sale ' mod='dompromo'}{/if}
		{if $saletype==2 && $titrepc==1}{l s='Coutant Price ' mod='dompromo'}{/if}
		{if $saletype==3 && $titreds==1}{l s='Reduction of Stocks ' mod='dompromo'}{/if}
	</div>
	{if $saletype==1 && $imgvf==1}
		<div class="dptypevente" style="color:#{$producttypeColor};"><img class="dpimgtypevente" src="{$varpath}images/imports/venteflash.gif" /></div>
	{elseif $saletype==2 && $imgpc==1}
		<div class="dptypevente" style="color:#{$producttypeColor};"><img class="dpimgtypevente" src="{$varpath}images/imports/prixcoutant.gif" /></div>
	{elseif $saletype==3 && $imgds==1}
		<div class="dptypevente" style="color:#{$producttypeColor};"><img class="dpimgtypevente" src="{$varpath}images/imports/destockage.gif" /></div>
	{/if}
	<div class="dptemprestant" style="color:#{$producttexttimeColor};"> {l s='Less than:' mod='dompromo'}</div>
	<div id="vftimer{$vfproduct}" class="dptimer" style="color:#{$producttimeColor};">[vftimer{$vfproduct}]</div>
</div>

<script type="text/javascript">
	<!--
	cd{$vfproduct}.Setup();
	 //-->
</script>
<!-- /MODULE {$moduleinfo} -->
