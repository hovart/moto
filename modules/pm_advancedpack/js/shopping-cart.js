function ap5_updateCartData() {
	$.ajax({
		type: 'POST',
		headers: { "cache-control": "no-cache" },
		url: baseUri + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		dataType : "json",
		data: 'controller=update_cart&ajax=1&fc=module&module=pm_advancedpack&&ajax=true&token=' + static_token,
		success: function(jsonData) {
			if (typeof(jsonData.hasError) !== 'undefined' && !jsonData.hasError) {
				if (typeof(ajaxCart) != 'undefined') {
					ajaxCart.updateCartInformation(jsonData.cartData);
					ajaxCart.updateCartEverywhere(jsonData.cartData);
				}
				if (typeof(updateCartSummary) !== 'undefined')
					updateCartSummary(jsonData.cartData.summary);
			}
		}
	});
}

$(document).ajaxSuccess(function(e, ajaxOptions, ajaxData) {
	if (typeof(ajaxData) !== 'undefined' && typeof(ajaxData.data) !== 'undefined' && ajaxData.data.indexOf('controller=update_cart') == -1 && ajaxData.data.indexOf('controller=cart') > -1)
		ap5_updateCartData();
});

ap5_updateCartData();