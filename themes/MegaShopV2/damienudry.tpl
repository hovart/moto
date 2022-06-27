<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="/modules/homesliderpro/js/slidereverywhere.js"></script>
<script type="text/javascript" src="/modules/tptnheaderlinks/js/tptncarousel.js"></script>
<script type="text/javascript" src="/themes/MegaShopV2/damienudry/js/jquery.social.stream.wall.1.6.js"></script>
<script type="text/javascript" src="/themes/MegaShopV2/damienudry/js/jquery.social.stream.1.5.11.js"></script>
<script type="text/javascript" src="/themes/MegaShopV2/damienudry/inc/js/jquery.plugins.js"></script>
<script type="text/javascript" src="/themes/MegaShopV2/damienudry/inc/js/jquery.site.js"></script>
<script type="text/javascript" src="/modules/pm_crosssellingoncart/js/owl-carousel/owl.carousel.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#social-stream').dcSocialStream({
		feeds: {
			facebook: {
				limit:8,
				id: '1489019704689577',
				out: 'intro,name,thumb,title,text',
				icon :'facebook.png'
			},

		},
		rotate: {
			direction: 'down',
			delay: 0
		},
		days: 365,
		height: 140,
		control: false,
		filter: false,
		wall: true,
		center: true,
		cache: false,
		max: 'limit',
		limit: 8,
		iconPath: '/themes/MegaShopV2/damienudry/images/dcsns-dark/',
		imagePath: '/themes/MegaShopV2/damienudry/images/dcsns-dark/'
	});


/*FACEBOOK COUNT*/
	// Fetch Facebook Likes once and every 30 seconds thereafter
	// Adjust setInterval to either fetch content in different frequency or remove to only fetch once.

/*	setInterval("realtime_fb_likes()", 30000);
*/
// Fetch FB Likes
// Get FB ID here:  http://graph.facebook.com/your_page_name
/*function realtime_fb_likes() {
	$.getJSON('https://graph.facebook.com/audemarspiguet?access_token=263338023702230|wWt9gzDzOtNO3x5TgDMr5EjGCek', function(data) {
		var fb_likes = addCommas(data.likes);
		$('#fb-likes-count').text(fb_likes);
	});
}*/

/*realtime_fb_likes();
*/});

// Pretty number format to add commas between numbers
// Source: http://www.mredkj.com/javascript/nfbasic.html

</script>

<img class="banner" src="{l s='https://www.motogoodeal.ch/themes/MegaShopV2/damienudry/images/dakar_banner_page_damien_fr.jpg'}" height="auto" width="100%">

<div id="description">
	<p>{l s="_bio_damien_udry_1"}</p>
	<p>{l s="_bio_damien_udry_2"}</p>
</div>

<!--<a href="{l s='https://apps.facebook.com/1695446070688523'}" target='_blank'><img class="game" src="{l s='https://www.motogoodeal.ch/themes/MegaShopV2/damienudry/images/dakar_banner_page_damien_2_fr.jpg'}" height="auto" width="100%"></a>-->
<img class="game" src="{l s='https://www.motogoodeal.ch/themes/MegaShopV2/damienudry/images/dakar_banner_page_damien_coming_soon_fr.jpg'}" height="auto" width="100%">

<div id="calandar">
<h2><span>{l s='Calendrier'}</span></h2>
<p>{l s="Rallye du maroc 3/10/2015 au 9/10/2015"}
	<a href='{l s="http://rallyemaroc.npo.fr/"}' alt='{l s="Rallye du maroc 3/10/2015 au 9/10/2015"}' target='_blank'>{l s="http://rallyemaroc.npo.fr/"}</a></p>
<p>{l s="Rallye de Merzouga  10/10/2015 au 17/10/2015"}
	<a href='{l s="http://www.merzougarally.com/"}' alt='{l s="Rallye de Merzouga  10/10/2015 au 17/10/2015"}' target='_blank'>{l s="http://www.merzougarally.com/"}</a></p>
<p>{l s="Dakar 3/01/2016 au 16/01/2016"}
	<a href='{l s="http://www.dakar.com/"}' alt='{l s="Dakar 3/01/2016 au 16/01/2016"}' target='_blank'>{l s="http://www.dakar.com/"}</a></p>
</div>

<div id="slider">
	{hook h="displaySlidersPro" slider="damien_udry"}
</div>

<div id="wall">
	<div id="social-stream"></div>
</div>
<div id="products">
	{hook h="displayDamienUdry"}
</div>

