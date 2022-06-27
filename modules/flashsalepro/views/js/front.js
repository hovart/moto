/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

var clock;

$(document).ready(function() {
	console.log('test');
				$('.autoplay').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2500,
					speed: 2000,
					dots : false,
					draggable: false,
					touchMove: false,
				});
	if(typeof(date_end) != "undefined" && date_end !== null) {
		var currentDate = new Date();
		var futureDate = new Date(date_end*1000);
		// Calculate the difference in seconds between the future and current date
		var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
		if(diff > 0)
		{
			// Instantiate a coutdown FlipClock
			clock = $('.clock').FlipClock(diff, {
				clockFace: 'HourlyCounter',
				countdown: true,
				language: lang_code
			});

			clock_miniture = $('.clock-mini').FlipClock(diff, {
				clockFace: 'HourlyCounter',
				countdown: true,
				language: lang_code,
			});
		}
	}

	$(".flash-sale-image-class").each(function () {
		var id_div = this.id;
		var id = id_div.substring(11);
		var max_width = $('#'+id_div).width();
		$('#flash_sale_img_'+id).css({'max-width': max_width});

	});
});


function addToCartFlashSale(id_product)
{
	$("#fs_add_to_cart_"+id_product).click();
}