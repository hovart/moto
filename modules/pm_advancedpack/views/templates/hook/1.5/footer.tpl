<script>
$(document).ready(function() {
{foreach from=$cartPackProducts item=cartPackContent key=cartPackSmallAttribute}
	$('#cart_block dl dd *:contains("{$cartPackSmallAttribute}"), .cart_block dl dt *:contains("{$cartPackSmallAttribute}")').each(function (idx, elem) {
		if (!$(elem).children().size()) {
			var changed = $(elem).html().replace("{$cartPackSmallAttribute}", {$cartPackContent.block_cart});
			$(elem).html(changed);
		}
	});
	$('#cart_summary .cart_description *:contains("{$cartPackSmallAttribute}")').each(function (idx, elem) {
		if (!$(elem).children().size()) {
			var changed = $(elem).html().replace("{$cartPackSmallAttribute}", {$cartPackContent.cart});
			$(elem).html(changed);
		}
	});
{/foreach}
});
</script>