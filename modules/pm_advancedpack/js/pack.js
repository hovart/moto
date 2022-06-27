var ap5Debug = false;
var ap5_topLimit = 0;
var ap5_autoScrollBuyBlockEnabled = (typeof(ap5_isPS16) !== 'undefined' && ap5_isPS16 && typeof(ap5_autoScrollBuyBlock) !== 'undefined' && ap5_autoScrollBuyBlock);
var ap5_productPackExclude = [];
var ap5_productPackExcludeBackup = [];

function ap5_log(txt) {
	if (ap5Debug)
		console.log(new Date().toUTCString() + ' - ' + txt);
}

function ap5_displayErrors(jsonData) {
	// User errors display
	if (jsonData.hasError) {
		var errors = '';
		for (error in jsonData.errors)
			// IE6 bug fix
			if (error != 'indexOf')
				errors += $('<div />').html(jsonData.errors[error]).text() + "\n";
		if (typeof(ap5_isPS16) !== 'undefined' && ap5_isPS16 && !!$.prototype.fancybox)
			$.fancybox.open([
				{
					type: 'inline',
					autoScale: true,
					minHeight: 30,
					content: '<p class="fancybox-error">' + errors + '</p>'
				}
			], {
				padding: 0
			});
		else
			alert(errors);
	}
}

function ap5_addPackToCart(idPack, idProductAttributeList, callerElement, callBack) {
	ap5_log('[ap5_addPackToCart] Call');
	if (idPack > 0) {
		var ap5_submitButton = $('input[type="submit"]', callerElement);
		var ap5_quantityWanted = parseInt($('input[name=qty]').val());
		if (isNaN(ap5_quantityWanted) || ap5_quantityWanted <= 0)
			ap5_quantityWanted = 1;
		$(ap5_submitButton).attr('disabled', true).removeClass('exclusive').addClass('exclusive_disabled');
		$.ajax({
			type: 'POST',
			url: $(callerElement).attr('action'),
			data: {
				id_product_attribute_list: idProductAttributeList,
				productPackExclude: ap5_productPackExclude,
				qty: ap5_quantityWanted,
				token: static_token
			},
			dataType: 'json',
			cache: false,
			success: function(jsonData,textStatus,jqXHR) {
				ap5_log('[ap5_addPackToCart] Success');

				if (typeof(ap5_isPS16) !== 'undefined' && ap5_isPS16) {
					/* PS 1.6 CODE */
					$('#ap5-add-to-cart button').prop('disabled', 'disabled').addClass('disabled');
					$('.filled').removeClass('filled');
					if ($('.cart_block_list').hasClass('collapsed'))
						this.expand();
					if (!jsonData.hasError) {
						// Modal Cart 3
						if (typeof(modalAjaxCart) !== 'undefined' && typeof(jsonData.ap5Data) !== 'undefined' && typeof(jsonData.ap5Data.idProductAttribute) !== 'undefined') {
							modalAjaxCart.showModal("pack_add", idPack, jsonData.ap5Data.idProductAttribute);
						} else if (typeof(ajaxCart) !== 'undefined' && typeof(ajaxCart.updateLayer) !== 'undefined') {
							$(jsonData.products).each(function() {
								if (this.id != undefined && this.id == parseInt(idPack) && this.idCombination == parseInt(jsonData.ap5Data.idProductAttribute))
									if (typeof(contentOnly) !== 'undefined' && contentOnly && typeof(window.parent.ajaxCart) !== 'undefined') {
										window.parent.ajaxCart.updateLayer(this);
									} else {
										ajaxCart.updateLayer(this);
									}
							});
						}
						if (typeof(contentOnly) !== 'undefined' && contentOnly && typeof(window.parent.ajaxCart) !== 'undefined') {
							window.parent.ajaxCart.updateCartInformation(jsonData, true);
						} else if (typeof(window.parent.ajaxCart) !== 'undefined') {
							window.parent.ajaxCart.updateCartInformation(jsonData, true);
						} else if (typeof(ajaxCart) !== 'undefined') {
							ajaxCart.updateCartInformation(jsonData, true);
						}
						$('#ap5-add-to-cart button').removeProp('disabled').removeClass('disabled');
						if (!jsonData.hasError || jsonData.hasError == false)
							$('#ap5-add-to-cart button').addClass('added');
						else
							$('#ap5-add-to-cart button').removeClass('added');

						// Close quick view
						if (typeof(contentOnly) !== 'undefined' && contentOnly) {
							parent.$.fancybox.close();
						}
					} else {
						$('#ap5-add-to-cart button').removeProp('disabled').removeClass('disabled');
						ap5_displayErrors(jsonData);
					}
				} else {
					if (!jsonData.hasError) {
						// Modal Cart 3
						if (typeof(modalAjaxCart) !== 'undefined' && typeof(jsonData.ap5Data) !== 'undefined' && typeof(jsonData.ap5Data.idProductAttribute) !== 'undefined') {
							if (typeof(ajaxCart) !== 'undefined')
								ajaxCart.updateCartInformation(jsonData, true);
							modalAjaxCart.showModal("pack_add", idPack, jsonData.ap5Data.idProductAttribute);
						} else {
							// Add the first product's picture to the cart
							var $element = $(document).find('.ap5-pack-product-image img');
							if (!$element.length)
								$element = $('#bigpic');
							var $picture = $element.clone();
							var pictureOffsetOriginal = $element.offset();
							if (pictureOffsetOriginal != null) {
								pictureOffsetOriginal.right = $(window).innerWidth() - pictureOffsetOriginal.left - $element.width();

								if ($picture.length)
								{
									$picture.css({
										position: 'absolute',
										top: pictureOffsetOriginal.top,
										right: pictureOffsetOriginal.right
									});
								}

								var pictureOffset = $picture.offset();
								var cartBlock = $('#cart_block');
								if (!$('#cart_block')[0] || !$('#cart_block').offset().top || !$('#cart_block').offset().left)
									cartBlock = $('#shopping_cart');
								var cartBlockOffset = cartBlock.offset();
								cartBlockOffset.right = $(window).innerWidth() - cartBlockOffset.left - cartBlock.width();

								// Check if the block cart is activated for the animation
								if (cartBlockOffset != undefined && $picture.length)
								{
									$picture.appendTo('body');
									$picture
										.css({
											position: 'absolute',
											top: pictureOffsetOriginal.top,
											right: pictureOffsetOriginal.right,
											zIndex: 4242
										})
										.animate({
											width: $element.attr('width')*0.66,
											height: $element.attr('height')*0.66,
											opacity: 0.2,
											top: cartBlockOffset.top + 30,
											right: cartBlockOffset.right + 15
										}, 1000)
										.fadeOut(100, function() {
											ajaxCart.updateCartInformation(jsonData, true);
											$(this).remove();
										});
								}
								else
									ajaxCart.updateCartInformation(jsonData, true);
							} else {
								ajaxCart.updateCartInformation(jsonData, true);
							}
						}
					} else {
						ap5_displayErrors(jsonData);
					}
				}
				$(document).trigger('ap5-After-AddPackToCart', [idPack, idProductAttributeList, callerElement]);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Impossible to add the product to the cart.\n\ntextStatus: '" + textStatus + "'\nerrorThrown: '" + errorThrown + "'\nresponseText:\n" + XMLHttpRequest.responseText);
				if (typeof(ap5_isPS16) !== 'undefined' && ap5_isPS16) {
					/* PS 1.6 CODE */
					$('#add_to_cart button').removeProp('disabled').removeClass('disabled');
				} else {
					/* PS 1.5 CODE */
					$(ap5_submitButton).removeAttr('disabled').addClass('exclusive').removeClass('exclusive_disabled');
				}
			},
			complete: function(jqXHR, textStatus) {
				if (typeof(ap5_isPS16) !== 'undefined' && ap5_isPS16) {
					/* PS 1.6 CODE */
					$('#add_to_cart button').removeProp('disabled').removeClass('disabled');
				} else {
					/* PS 1.5 CODE */
					$(ap5_submitButton).removeAttr('disabled').addClass('exclusive').removeClass('exclusive_disabled');
				}
			}
		});
	}
}

// Add pack to cart
$(document).on('submit', 'form.ap5-buy-block', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var pm_ap5_id_pack = parseInt($('input[name=id_product]').val());
	var pm_ap5_id_product_attribute_list = $('#idCombination').val();

	ap5_addPackToCart(pm_ap5_id_pack, pm_ap5_id_product_attribute_list, $(this));
	return false;
});

// Attribute choice
$(document).on('click', '.ap5-attributes .color_pick', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	ap5_colorPickerClick($(this));
	ap5_updatePackTable();
});

$(document).on('change', '.ap5-attributes .attribute_select', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	ap5_log('[ap5_Event] Attribute select click');
	ap5_updatePackTable();
});

$(document).on('click', '.ap5-attributes .attribute_radio', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	ap5_log('[ap5_Event] Attribute radio click');
	ap5_updatePackTable();
});

$(document).on('ap5-CombinationUpdate', function(e){
	ap5_log('[ap5_Event] Combination update');
	ap5_updatePackTable();
});

// Quantity increment
$(document).on('click', '.product_quantity_up', function(e){
	e.preventDefault();
	var currentVal = parseInt($('input[name=qty]').val());
	if (quantityAvailable > 0)
		quantityAvailableT = quantityAvailable;
	else
		quantityAvailableT = 100000000;
	if (!isNaN(currentVal) && currentVal < quantityAvailableT)
		$('input[name=qty]').val(currentVal + 1).trigger('keyup');
	else
		$('input[name=qty]').val(quantityAvailableT);
});
// Quantity decrement
$(document).on('click', '.product_quantity_down', function(e){
	e.preventDefault();
	var currentVal = parseInt($('input[name=qty]').val());
	if (!isNaN(currentVal) && currentVal > 1)
		$('input[name=qty]').val(currentVal - 1).trigger('keyup');
	else
		$('input[name=qty]').val(1);
});
// Quantity check
$(document).on('keyup', 'input[name=qty]', function(e){
	var currentVal = parseInt($('input[name=qty]').val());
	if (isNaN(currentVal) || currentVal <= 0)
		$('input[name=qty]').val(1);
});

// Exclude product
$(document).on('click', '.ap5-pack-product-icon-remove', function(e){
	ap5_log('[ap5_Event] Product exclude');
	e.preventDefault();
	ap5_productPackExcludeBackup = ap5_productPackExclude.slice(0);
	var idProductPack = parseInt($(this).attr('data-id-product-pack'));
	if (ap5_productPackExclude.indexOf(idProductPack) == -1)
		ap5_productPackExclude.push(idProductPack);
	ap5_updatePackTable();
});

// Include product
$(document).on('click', '.ap5-pack-product-icon-check', function(e){
	ap5_log('[ap5_Event] Product include');
	e.preventDefault();
	ap5_productPackExcludeBackup = ap5_productPackExclude.slice(0);
	var idProductPack = parseInt($(this).attr('data-id-product-pack'));
	if (ap5_productPackExclude.indexOf(idProductPack) > -1)
		ap5_productPackExclude.splice(ap5_productPackExclude.indexOf(idProductPack), 1);
	ap5_updatePackTable();
});

$(document).ready(function() {
	// Add classes to body
	$('body').addClass('ap5-pack-page');
});

$(window).load(function() {
	ap5_initNewContent();

	if (ap5_autoScrollBuyBlockEnabled) {
		/* PS 1.6 CODE */
		ap5_topLimit = $('form.ap5-buy-block').offset().top + parseFloat($('form.ap5-buy-block').css('marginTop').replace(/auto/, 0));
		$(window).scroll(function() {
			ap5_windowWidth = (navigator.userAgent.indexOf('Macintosh') > -1 && navigator.userAgent.indexOf('Safari/') > -1 ? $(window).width() : window.innerWidth);
			var ap5_scrollTop = $(window).scrollTop();
			var ap5_originalTop = parseFloat($('form.ap5-buy-block').css('top'));
			var ap5_maxScroll = -10 + ($('#ap5-pack-description-block').size() > 0 && $('#ap5-pack-description-block').offset().top < $('#ap5-pack-content-block').offset().top ? $('#ap5-pack-description-block').offset().top : $('#ap5-pack-content-block').offset().top);
			var ap5_buyBlockHeight = $('form.ap5-buy-block').height();

			if (ap5_windowWidth >= 768 && ap5_scrollTop >= ap5_topLimit) {
				$('form.ap5-buy-block').addClass('ap5-fixed');
	 			$('form.ap5-buy-block').css('width', $('form.ap5-buy-block').parent().width() - parseFloat($('form.ap5-buy-block').css('marginLeft').replace(/auto/, 0)) );
				if ((ap5_scrollTop + ap5_buyBlockHeight) >= ap5_maxScroll) {
					if (ap5_scrollTop > (ap5_maxScroll - ap5_buyBlockHeight)) {
						if (ap5_scrollTop < ap5_maxScroll) {
							toTop = (ap5_scrollTop - ap5_maxScroll + ap5_buyBlockHeight) * -1;
							$('form.ap5-buy-block').css('top', toTop);
						} else {
							$('form.ap5-buy-block').css('top', -ap5_buyBlockHeight);
						}
					}
				} else {
					$('form.ap5-buy-block').css('top', '');
				}
			} else {
				$('form.ap5-buy-block').css('top', '');
	 			$('form.ap5-buy-block').css('width', '');
				$('form.ap5-buy-block').removeClass('ap5-fixed');
			}
		});

		$(window).trigger('scroll');
	}

	$(window).resize(function() {
		if (ap5_autoScrollBuyBlockEnabled)
			ap5_topLimit = $('form.ap5-buy-block').offset().top + parseFloat($('form.ap5-buy-block').css('marginTop').replace(/auto/, 0));
		ap5_applyProductListMinHeight($('#ap5-pack-product-tab-list li'), true, 'height');
		ap5_applyProductListMinHeight($('.ap5-pack-product-name'), false, 'min-height');
		ap5_addCSSClasses();
	});
});

function ap5_initNewContent() {
	$(document).trigger('ap5-Before-InitNewContent');

	$('div.ap5-pack-product-image a.fancybox, div.ap5-pack-product-slideshow a.fancybox').fancybox();
	$("div.ap5-pack-product-slideshow:not(.no-carousel)").owlCarousel({
		items : 3,
		itemsDesktop : [1000, 3],
		itemsDesktopSmall : [900, 3],
		itemsTablet: [600, 3],
		itemsMobile : false,
		autoPlay: true,
		stopOnHover: true,
		pagination: false
	});
	$("div.ap5-pack-product-mobile-slideshow").owlCarousel({
		singleItem: true,
		autoPlay: true,
		stopOnHover: true
	});
	if (typeof($.uniform) != 'undefined') {
		// Init PS 1.6 theme default behaviour
		$("select.form-control,input[type='checkbox']:not(.comparator),input[type='radio']").uniform();
		// /Init PS 1.6 theme default behaviour
	}
	ap5_applyProductListMinHeight($('.ap5-pack-product-name'), false, 'min-height');
	ap5_applyProductListMinHeight($('div.ap5-pack-product-content'), true, 'min-height');
	ap5_applyProductListMinHeight($('div.ap5-pack-product-price-table-container'), true, 'height');
	ap5_applyProductListMinHeight($('#ap5-pack-product-tab-list li'), true, 'height');
	ap5_applyProductListMinHeight($('div.ap5-right'), true, 'min-height', $('div.ap5-pack-product'));
	
	ap5_addCSSClasses();

	$(document).trigger('ap5-After-InitNewContent');
}

function ap5_addCSSClasses() {
	var minLeft = $('div.ap5-pack-product:not(.ap5-right):eq(0)').offset().left;
	var sameMinLeft = true;
	$('div.ap5-pack-product:not(.ap5-right)').each(function() {
		var offsetLeft = $(this).offset().left;
		sameMinLeft &= (offsetLeft == minLeft);
		if (offsetLeft > minLeft)
			$(this).removeClass('ap5-no-plus-icon');
	});
	if (sameMinLeft) {
		$('div.ap5-pack-product:not(.ap5-right)').each(function(index, value) {
			if (index > 0)
				$(this).removeClass('ap5-no-plus-icon');
		});
	}
}

function ap5_applyProductListMinHeight(items, includePadding, property, reference) {
	var minHeight = 0;
	var sourcesItem = (typeof(reference) != 'undefined' ? reference : items);
	$(items).css(property, '');
	$(sourcesItem).each(function() {
		if ((includePadding === true ? $(this).outerHeight() : $(this).height())  > minHeight)
			minHeight = (includePadding === true ? $(this).outerHeight() : $(this).height());
	});
	if (minHeight > 0)
		$(items).css(property, minHeight);
}

// Color Picker click
function ap5_colorPickerClick(elt) {
	id_attribute = $(elt).attr('data-id-attribute');
	id_attribute_group = $(elt).attr('data-id-attribute-group');
	id_product_pack = $(elt).attr('data-id-product-pack');
	ap5_log('[ap5_Event] Color picker click - ' + id_product_pack + ' - ' + id_attribute + ' - ' + id_attribute_group);
	$('ul.ap5-color-to-pick-list-' + id_product_pack + '-' + id_attribute_group).children().removeClass('selected');
	$('.color_pick_hidden_' + id_product_pack + '_' + id_attribute_group).val(id_attribute);
}

// Add layer and spinner
function ap5_addLayerLoading(pmAjaxSpinnerTarget) {
	// Remove previous spinner first
	ap5_removeLayerLoading(pmAjaxSpinnerTarget);
	// Create the spinner here
	$(pmAjaxSpinnerTarget).addClass('ap5-loader-blur').append('<div class="ap5-loader"></div>');
	$(pmAjaxSpinnerTarget).find('.ap5-loader').each(function() {
		$(this).css('top', $(pmAjaxSpinnerTarget).outerHeight()/2 - $(this).outerHeight()*1.4);
	});
	return pmAjaxSpinnerTarget;
}

// Remove layer and spinner
function ap5_removeLayerLoading(pmAjaxSpinnerTarget) {
	// Remove layer and spinner
	$(pmAjaxSpinnerTarget).removeClass('ap5-loader-blur');
	$('.ap5-loader', pmAjaxSpinnerTarget).remove();
}

// Send ajax query in order to update pack table
function ap5_updatePackTable() {
	ap5_log('[ap5_updatePackTable] Call');
	var productPackChoice = [];
	$('.ap5-attributes').each(function (index, element) {
		id_product_pack = $(this).attr('data-id-product-pack');
		productChoice = { idProductPack: id_product_pack, attributesList: []};
		$('select, input[type=hidden], input[type=radio]:checked', $(element)).each(function(){
			productChoice.attributesList.push(parseInt($(this).val()));
		});
		productPackChoice.push(productChoice);
	});

	var pmAjaxSpinnerInstance = ap5_addLayerLoading($('#ap5-product-list'));
	$.ajax({
		type: 'POST',
		url: ap5_updatePackURL,
		data: {
			productPackChoice: productPackChoice,
			productPackExclude: ap5_productPackExclude,
			token: static_token
		},
		dataType: 'json',
		cache: false,
		success: function(jsonData, textStatus, jqXHR) {
			$(document).trigger('ap5-Before-UpdatePackContent');
			if (typeof(jsonData.hasError) !== 'undefined' && jsonData.hasError) {
				ap5_displayErrors(jsonData);
				// Restore exclusion
				ap5_productPackExclude = ap5_productPackExcludeBackup.slice(0);
			} else {
				if (typeof(jsonData.packContentTable) !== 'undefined')
					$('#ap5-product-list').replaceWith(jsonData.packContentTable);
				if (typeof(jsonData.packPriceContainer) !== 'undefined')
					$('#ap5-buy-block-container').replaceWith(jsonData.packPriceContainer);
				if (typeof(jsonData.HOOK_EXTRA_RIGHT) !== 'undefined')
					$('#ap5-hook-product-extra-right-container').html(jsonData.HOOK_EXTRA_RIGHT);
				if (typeof(jsonData.productPackExclude) !== 'undefined')
					ap5_productPackExclude = jsonData.productPackExclude;
				if ((typeof(jsonData.packHasFatalErrors) !== 'undefined' && jsonData.packHasFatalErrors === true) ||
					(typeof(jsonData.packHasErrors) !== 'undefined' && jsonData.packHasErrors === true) ||
					(typeof(jsonData.packAvailableQuantity) !== 'undefined' && jsonData.packAvailableQuantity <= 0)
				)
					$('#ap5-add-to-cart').hide();
				else {
					$('#idCombination').val(jsonData.packAttributesList);
					$('#ap5-add-to-cart').show();
				}
			}
			setTimeout(function(){ 
				ap5_initNewContent();
				ap5_removeLayerLoading(pmAjaxSpinnerInstance);
				$(document).trigger('ap5-After-UpdatePackContent');
			}, 100);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			$('#ap5-add-to-cart').hide();
			alert("Impossible to update pack attribute choice.\n\ntextStatus: '" + textStatus + "'\nerrorThrown: '" + errorThrown + "'\nresponseText:\n" + XMLHttpRequest.responseText);
		},
		complete: function(jqXHR, textStatus) {
			ap5_removeLayerLoading(pmAjaxSpinnerInstance);
		}
	});
}