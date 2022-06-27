{literal}
<script type="text/javascript">
	$(document).ready(function(){
	    // add div fb-root
	    if ($('div#fb-root').length == 0)
	    {
	        FBRootDom = $('<div>', {'id':'fb-root'});
	        $('body').prepend(FBRootDom);
	    }
	});

	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id; js.async = true;
		js.src = "{/literal}{$fb_url}{literal}";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
{/literal}