/*
 * 2003-2017 Business Tech
 *
 *  @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 *  @copyright 2003-2017 Business Tech SARL
 */
// init different JS object useful for the module but we cannot leave them as inline JS

/**
 * bt_scrollTo() method scrollTo
 *
 * @param string sId : selector
 * @param int iDuration
 */

// detect if array of valid URLs is defined
if (typeof(bt_aFacebookCallback) == 'undefined') {
	var bt_aFacebookCallback = new Array();
}

function bt_scrollTo(sId, iDuration) {
	oGsr.scrollTo(sId, iDuration);
}

function bt_toggle(sId) {
	$(sId).toggle();
}

function bt_triggerClick(sId) {
	$(sId).trigger('click');
}

$(document).ready(function() {
	if (typeof(sGsrModuleName) == 'undefined') {
		var sGsrModuleName = 'gsr';
	}
	// instantiate our JS module object
	oGsr = new GsrModule(''+sGsrModuleName+'');
	oGsr.msgs = bt_msgs;
	oGsr.sImgUrl = bt_sImgUrl;
	oGsr.sWebService = bt_sWebService;

	// activate tab when the theme requires it
	if (bt_oActivateReviewTab.run) {
		oGsr.activateReviewTab(bt_oActivateReviewTab.theme, bt_oActivateReviewTab.idTab, bt_oActivateReviewTab.liSelector, bt_oActivateReviewTab.cntSelector);
	}
	// deactivate tab when the theme requires it
	if (bt_oDeactivateReviewTab.run) {
		setTimeout(function() {oGsr.deactivateReviewTab(bt_oDeactivateReviewTab.theme, bt_oDeactivateReviewTab.idTab, bt_oDeactivateReviewTab.liSelector, bt_oDeactivateReviewTab.cntSelector)}, bt_oDeactivateReviewTab.duration);
	}

	// initiate the fancybox review action
	if (bt_aFancyReviewForm.length != 0) {
		$(bt_aFancyReviewForm.selector).fancybox({
			'hideOnContentClick' : bt_aFancyReviewForm.hideOnContentClick,
			'beforeClose' : function() {document.location.href = ""+bt_aFancyReviewForm.beforeClose+""}
		});
		if (bt_aFancyReviewForm.click) {
			$(bt_aFancyReviewForm.selector).click();
		}
	}
	// initiate the fancybox tab review action
	if (bt_aFancyReviewTabForm.length != 0) {
		$(bt_aFancyReviewTabForm.selector).fancybox({
			'hideOnContentClick' : bt_aFancyReviewTabForm.hideOnContentClick,
			'beforeClose' : function() {document.location.href = ""+bt_aFancyReviewTabForm.beforeClose+""}
		});
	}
	// initiate the social buttons
	if (bt_oUseSocialButton.length != 0) {
		// add div fb-root
		if ($('div#fb-root').length == 0) {
			FBRootDom = $('<div>', {'id':'fb-root'});
			$('body').prepend(FBRootDom);
		}
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/"+bt_oUseSocialButton.sFbLang+"/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		!function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (!d.getElementById(id)) {
				js = d.createElement(s);
				js.id = id;
				js.src = "//platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js, fjs);
			}
		}(document, "script", "twitter-wjs");
	}
	// initiate the report fancybox
	if (bt_aReviewReport.length != 0) {
		for (var i = 0; i < bt_aReviewReport.length; ++i) {
			var sAfterClose = bt_aReviewReport[i].afterClose;
			$(bt_aReviewReport[i].selector).fancybox({
				'hideOnContentClick': bt_aReviewReport[i].hideOnContentClick,
				'minWidth' : bt_aReviewReport[i].minWidth,
				'afterClose': function () {document.location.href = ""+sAfterClose+""}
			});
		}
	}
	// check if the scroll need to be done
	if (bt_oScrollTo.length != 0) {
		if (bt_oScrollTo.execute) {
			bt_scrollTo(bt_oScrollTo.id, bt_oScrollTo.duration);
		}
	}

	// if callback in the customer account is activated
	if (bt_oCallback.run) {
		$(bt_oCallback.selector).bind('click', function (event) {
			var sChecked = '';
			if ($(this).val() == 1) {
				sChecked = 'unchecked';
				$(this).val(0);
			}
			else {
				sChecked = 'checked';
				$(this).val(1);
			}
			oGsr.ajax(bt_oCallback.ajaxUri, bt_oCallback.sParams + sChecked, bt_oCallback.eltToUpd, bt_oCallback.eltToUpd, false);
		});
	}
	// initiate the stars rating plugin
	if (bt_aStarsRating.length != 0) {
		for (var i = 0; i < bt_aStarsRating.length; ++i) {
			$(bt_aStarsRating[i].selector).rating({
				'ratingField': bt_aStarsRating[i].ratingField,
				'readOnly': bt_aStarsRating[i].readOnly,
				'starGif' : bt_aStarsRating[i].starGif,
				'starWidth' : bt_aStarsRating[i].starWidth
			});
		}
	}
	// if the bxSlider is activated in the customer account page
	if (bt_oBxSlider.run) {
		if (!!$.prototype.bxSlider) {
			// set slider params
			$(bt_oBxSlider.selector).bxSlider({
				useCSS: false,
				maxSlides: 1,
				slideWidth: bt_oBxSlider.slideWidth,
				infiniteLoop: false,
				hideControlOnEnd: true,
				pager: false,
				autoHover: true,
				auto: bt_oBxSlider.auto,
				speed: parseInt(bt_oBxSlider.speed),
				pause: bt_oBxSlider.pause,
				controls: true
			});
		};
	}
	// use case - if the FB callback is activated
	if (bt_aFacebookCallback.length != 0) {
		window.fbAsyncInit = function() {
			FB.Event.subscribe('edge.create', function(response){
				for (var i = 0; i < bt_aFacebookCallback.length; ++i) {
					if (typeof(bt_aFacebookCallback[i].url) !== 'undefined') {
						if (response == bt_aFacebookCallback[i].url) {
							// display fancy box voucher box
							eval(bt_aFacebookCallback[i].function + '("' + response + '")');
						}
					}
				}
			}, true);
		}
	}
});
