<div id="tptntwitter">
	<div class="tptntwitter_icon"></div>
	<div class="tptntwitter_box">
		<a class="twitter-timeline" href="https://twitter.com/{$twitterUser}" data-widget-id="{$twitterWidgetId}" height="300px" width="237px" lang="{$iso_code}"></a>
		<script>
		// <![CDATA[
		{literal}
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
		{/literal}
		//]]>
		</script>
	</div>
</div>