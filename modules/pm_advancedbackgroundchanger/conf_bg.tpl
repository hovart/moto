{if $smarty.const._PS_VERSION_ < '1.4'}
	<!-- MODULE PM_ABG || Presta-Module.com -->
	<link href="{$content_dir}modules/pm_advancedbackgroundchanger/css/abg_advanced.css" rel="stylesheet" type="text/css" media="all" />
	{if (isset($pm_bg_slide) && $pm_bg_slide) || (isset($abg_overlay) && $abg_overlay)}
		<link href="{$content_dir}modules/pm_advancedbackgroundchanger/css/jquery.vegas.css" rel="stylesheet" type="text/css" media="all" />
		<script type="text/javascript" src="{$content_dir}modules/pm_advancedbackgroundchanger/js/jquery.vegas.js"></script>
	{/if}
{/if}
<style type="text/css">
{literal}
	body {
		background:{/literal}
		{if (isset($pm_bg_static) && $pm_bg_static)}
			{foreach from=$pm_bg_static key=k item=bg name=static}
				{if isset($bg.image) && $bg.image}
			url("{$base_dir}modules/pm_advancedbackgroundchanger/uploads/slides/{$bg.image}"){if isset($bg.bg_position)} {$bg.bg_position}{/if}{if isset($bg.bg_repeat)} {$bg.bg_repeat}{/if}{if $bg.bg_fixed} fixed{else} scroll{/if},
				{/if}
			{/foreach}
		{/if}
		{if isset($pm_group->bg_color) && $pm_group->bg_color} {$pm_group->bg_color}{else} transparent{/if};
	{rdelim}

{if $pm_bg_zone|@count}
{foreach from=$pm_bg_zone key=k item=zone name=zone_css}
#abg_zone{$zone.id_czone} {ldelim}
	display: block;
	width: {$zone.width};
	height: {$zone.height};
	{if $zone.border}border: 2px dotted {if isset($zone.color) && $zone.color}{$zone.color}{else}white{/if};{/if}
	position: absolute;
	left: {$zone.marginLeft}px;
	top: {$zone.marginTop}px;
{rdelim}
{/foreach}
{/if}
</style>
<!--[if lt IE 10]>
<script type="text/javascript" src="{$base_dir}modules/pm_advancedbackgroundchanger/js/PIE.js"></script>
<![endif]-->
{literal}
<script type="text/javascript">
// <![CDATA[
{/literal}
{if $pm_bg_zone|@count}
	var topPosition = {ldelim}{rdelim};
	$(document).ready(function() {ldelim}
		{* ajoute les zones de lien au début du body *}
		$('body').prepend('{foreach from=$pm_bg_zone key=k item=zone name=zone_html}<a href="{$zone.href}" class="abg_click_zone" id="abg_zone{$zone.id_czone}" title="{$zone.title|escape:'htmlall':'UTF-8'}"><img src="{$base_dir}modules/pm_advancedbackgroundchanger/img/index.png" height="100%" width="100%" title="{$zone.title|escape:'htmlall':'UTF-8'}" alt="{$zone.title|escape:'htmlall':'UTF-8'}" /></a>{/foreach}');
		{foreach  from=$pm_bg_zone key=k item=zone name=zone_html}
		topPosition[{$zone.id_czone}] = parseInt($("#abg_zone{$zone.id_czone}").css('top'));
		{/foreach}
		{* suit le scroll si on est en fixed *}
		var tempScrollTop, currentScrollTop = 0;
		$(document).scroll(function() {ldelim}
			currentScrollTop = $(this).scrollTop();
			{if $pm_bg_static|@count}
				{foreach from=$pm_bg_zone key=k item=zone name=zone_html}
				{if $zone.position == 1}
					$("#abg_zone{$k}").css('top' , String({if $zone.side == 3 || $zone.side == 4}-{$zone.height|string_format:"%d"}-{/if}{$zone.marginTop}+$(this).scrollTop()) +"px") ;
				{/if}
				{/foreach}
			{/if}
			{if $pm_bg_slide|@count}
			{foreach from=$pm_bg_zone key=k item=zone name=zone_html}
				if($(window).height()+$(this).scrollTop() < parseInt($("#abg_zone{$zone.id_czone}").css('top')) +$("#abg_zone{$zone.id_czone}").height()+5 && tempScrollTop < currentScrollTop)
					$('#abg_zone{$zone.id_czone}').height($('#abg_zone{$zone.id_czone}').height()-4);
				{if $zone.position == 1}
					$("#abg_zone{$zone.id_czone}").css('top' , String(topPosition[{$zone.id_czone}]+$(this).scrollTop()) +"px");
				{/if}
			{/foreach}
			{/if}
			tempScrollTop = currentScrollTop;
		{rdelim});
	{rdelim});
{/if}

	if ($.browser.msie  && parseInt($.browser.version, 10) <= 7) {ldelim} {literal}
		$(document).ready(function() {
			var cssObj = {
				'position': 'absolute',
				'top': '0',
				'left': '0',
				'width': document.body.clientWidth+'px',
				'height': document.body.clientHeight+'px',{/literal}
				'-pie-background': '{if (isset($pm_bg_static) && $pm_bg_static)}{foreach from=$pm_bg_static key=k item=bg name=static}{if isset($bg.image) && $bg.image} url("{$base_dir}modules/pm_advancedbackgroundchanger/uploads/slides/{$bg.image}") {if isset($bg.bg_position)}{$bg.bg_position} {/if}{if isset($bg.bg_repeat)}{$bg.bg_repeat} {/if}{if $bg.bg_fixed} fixed {else} scroll {/if} {if !$smarty.foreach.static.last}, {/if}{/if}{/foreach}{/if}{if isset($pm_group->bg_color) && $pm_group->bg_color} {$pm_group->bg_color} {else} transparent {/if}'{literal}
			}
			$('body').append('<div id="ABG_MultipleBG"></div>');
			$('#ABG_MultipleBG').css(cssObj);
			$('#ABG_MultipleBG').each(function() { if (window.PIE) PIE.attach(this); });
		});
	}{/literal}
	{if (isset($pm_bg_slide) && $pm_bg_slide) || (isset($abg_overlay) && $abg_overlay)}	{literal}
		$(document).ready(function() {
			$.vegas('slideshow', {{/literal}
				ZoneAsArray:{$pm_bg_zone_js},
				delay:{$pm_group->delay},
				backgrounds:[
				{if isset($pm_bg_slide) && $pm_bg_slide}
					{foreach  from=$pm_bg_slide key=k item=slide name=bg_foreach}
						{ldelim}
							id_slide: {$slide.id_slide},
							src:'{$base_dir}modules/pm_advancedbackgroundchanger/uploads/slides/{$slide.image}',
							fade:{$slide.fade_time},
							align: '{$slide.bg_halign}',
							valign: '{$slide.bg_valign}',
							attachement: {if $slide.bg_fixed} 'fixed' {else}'absolute'  {/if},
							complete:function() {ldelim}
								var img = {$pm_bg_class->getSlideZones($slide.id_slide, true)};
								if(img != false && img.length>0){ldelim}
									for (var val in img) {ldelim}
										$('a#abg_zone'+img[val]['id_czone']).css('display', "block");
									{rdelim}
								{rdelim} else {ldelim}
									$('a[id^="abg_zone"]').css('display', "none");
								{rdelim}
							{rdelim},
							load:function() {ldelim}
								var img = {$pm_bg_class->getSlideZones($slide.id_slide, true)};
								if(img != false && img.length>0){ldelim}
									for (var val in img) {ldelim}
										$('a[id^="abg_zone"]:not([id$="e'+img[val]['id_czone']+'"])').css('display', "none");
									{rdelim}
								{rdelim} else {ldelim}
									$('a[id^="abg_zone"]').css('display', "none");
								{rdelim}
							{rdelim}
						{rdelim}
						{if !$smarty.foreach.bg_foreach.last},{/if}
					{/foreach}
				{/if}
				]
			 {rdelim})
			{if isset($abg_overlay) && $abg_overlay}
				('overlay', {ldelim} src:'{$base_dir}modules/pm_advancedbackgroundchanger/img/overlays/{$abg_overlay}.png' {rdelim});
			{/if}
			{rdelim});
{/if}
// ]]>
</script>

{literal}
<!--[if IE 8]>
<style type="text/css">
 body {
  -pie-background: {/literal}
		{if (isset($pm_bg_static) && $pm_bg_static)}
		{foreach  from=$pm_bg_static key=k item=bg name=static}

			{if isset($bg.image) && $bg.image}

			    url("{$base_dir}modules/pm_advancedbackgroundchanger/uploads/slides/{$bg.image}")
				{if isset($bg.bg_position)}
					{$bg.bg_position}
				{/if}

			    {if isset($bg.bg_repeat)}
			    	{$bg.bg_repeat}
			    {/if}

				{if $bg.bg_fixed} fixed {else} scroll {/if}

				{if !$smarty.foreach.static.last}, {/if}
			{/if}

		{/foreach}
		{/if}

			{if isset($pm_group->bg_color) && $pm_group->bg_color} {$pm_group->bg_color} {else} transparent {/if} ;

 	 /* behavior: url({$smarty.const.__PS_BASE_URI__}modules/pm_advancedbackgroundchanger/css/PIE.php); */
 }
</style>
<script type="text/javascript">
{literal}
$(document).ready(function() {
	$('body').each(function() {
		if (window.PIE) PIE.attach(this);
	});
});
{/literal}
</script>
<![endif]-->