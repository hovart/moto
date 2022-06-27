{literal}
	<script type="text/javascript">
		$(document).ready(function(){	
			$("#slider").easySlider({
				controlsShow: false,
				auto: true,
				speed: 1200,
				continuous: true
			});
		});	
	</script>

	<style>
	#slider ul, #slider li{
		margin:0;
		padding:0;
		list-style:none;
		}
	#slider li{ 
		width:129px;
		height:129px;
		overflow:hidden; 
		}
	</style>
{/literal}

<div class="block">
	<h4>{l s='Private Sales' mod='privatesale'}</h4>
	<div class="block_content">
		<div id="slider" style="margin:auto;">
			<ul>
			{foreach from=$img_list item=img}
				{if $img != '.' && $img != '..'}
					<li><a href="{$pvs_link}"><img width="129px" height="129px" src="{$img}" alt="{l s='Private Sale' mod='privatesale'}" /></a></li>
				{/if}
			{/foreach}
			</ul>
		</div>
		<center><a href="{$pvs_link}">{l s='See all private sales' mod='privatesale'}</a></center>
	</div>
</div>