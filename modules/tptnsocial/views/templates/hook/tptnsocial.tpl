<div id="tptnsocial" class="hidden-sm hidden-xs">
	<ul>
		{if $tptnfacebook != ''}<li class="facebook"><a href="{$tptnfacebook|escape:html:'UTF-8'}" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>{/if}
		{if $tptntwitter != ''}<li class="twitter"><a href="{$tptntwitter|escape:html:'UTF-8'}" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>{/if}
		{if $tptngoogle != ''}<li class="google"><a href="{$tptngoogle|escape:html:'UTF-8'}" title="Google+" target="_blank"><i class="fa fa-google-plus"></i></a></li>{/if}
		{if $tptninstagram != ''}<li class="instagram"><a href="{$tptninstagram|escape:html:'UTF-8'}" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>{/if}
		{if $tptnyoutube != ''}<li class="youtube"><a href="{$tptnyoutube|escape:html:'UTF-8'}" title="Youtube" target="_blank"><i class="fa fa-youtube-play"></i></a></li>{/if}
	</ul>
</div>