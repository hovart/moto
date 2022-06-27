{*
* Man Carousel v0.2
* @author kik-off.com <info@kik-off.com>
*}
<!-- MODULE Man Carousel -->
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$("#mancarousel").carouFredSel({
		debug	: true,
		circular: {/literal}{$man_carousel_cir}{literal},
	    infinite: {/literal}{$man_carousel_inf}{literal},
	    align   : "center",
		width   : null,
		height  : null,
	    auto	: {
    		play	: {/literal}{$man_carousel_auto}{literal},
	    	timeoutDuration : {/literal}{$man_carousel_pause_time}{literal}
	    },
	    items	: {
			visible	: {/literal}{$man_carousel_display_items}{literal},
			start	: {/literal}{$man_carousel_rand}{literal},
			width   : "{/literal}{$imageSize.width}{literal}",
			height  : "{/literal}{$imageSize.height}{literal}"
		},
		scroll	: {
			items	: {/literal}{$man_carousel_scroll_items}{literal},
			fx	    : "{/literal}{$man_carousel_fx}{literal}",
			duration: {/literal}{$man_carousel_fx_time}{literal},
			pauseOnHover: {/literal}{$man_carousel_mouseover}{literal}
		},
		prev	: {
			button	: "#mancarousel_prev",
			key		: "left"
		},
		next	: {
			button	: "#mancarousel_next",
			key		: "right"
		}
	}, {
	    wrapper			: {
		    element			: "div",
		    classname		: "mancarousel_wrapper"
	    },
	    classnames		: {
		    selected		: "selected",
		    hidden			: "hidden",
		    disabled		: "disabled",
		    paused			: "paused",
		    stopped			: "stopped"
	    }
    });
});
{/literal}
</script>

<div class="image_carousel">
	<div id="mancarousel">
		{foreach $mancarousel as $man}
		    <a href="{$link->getmanufacturerLink($man.id_manufacturer, $man.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$man.name|escape:'htmlall':'UTF-8'}" class="lnk_img">
				<img src="{$img_manu_dir}{$man.id_manufacturer|escape:'htmlall':'UTF-8'}-{$imageName}.jpg" alt="{$man.name|escape:'htmlall':'UTF-8'}" width="{$imageSize.width}" height="{$imageSize.height}" />
			</a>
		{/foreach}
	</div>
	<div class="clearfix"></div>
	<a class="prev" id="mancarousel_prev" href="#"><span>{l s='prev' mod='mancarousel'}</span></a>
	<a class="next" id="mancarousel_next" href="#"><span>{l s='next' mod='mancarousel'}</span></a>
</div>

<!-- /MODULE Man Carousel -->