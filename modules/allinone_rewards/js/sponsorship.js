/**
 * All-in-one Rewards Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Yann BONNAILLIE - ByWEB
 * @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
 * @license   Commercial license see license.txt
 * Support by mail  : contact@prestaplugins.com
 * Support on forum : Patanock
 * Support on Skype : Patanock13
 */

jQuery(function($){
	if (typeof(url_allinone_sponsorship) != "undefined") {
		if (window.location.href.indexOf('http://')===0) {
			url_allinone_sponsorship = url_allinone_sponsorship.replace('https://','http://');
	    } else {
			url_allinone_sponsorship = url_allinone_sponsorship.replace('http://','https://');
	    }
	}

	if ($('#sponsorship_popup').size() > 0)
		openPopup();

	if ($('#rewards_sponsorship').length > 0)
		initRewards();
});

function openPopup(skeepStep) {
	var scheduled = $('#sponsorship_popup').hasClass('scheduled') ? '1' : '0';
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: url_allinone_sponsorship,
		dataType: "html",
		data 	: "popup=1&scheduled=" + scheduled,
		success : function(data) {
			fancybox(data);
			if (skeepStep) {
				$('#sponsorship_text').hide();
				$('#sponsorship_form').show();
			}
		}
	});
	return false;
}

function initRewards() {
	// utile pour order-confirmation et sponsorship.php
	$('#invite').click(function(){
		$('#sponsorship_text').hide();
		$('#sponsorship_form').show();
		$.fancybox.update();
	});

	$('#noinvite').click(function(){
		$.fancybox.close(true);
	});

	$('#provider').change(function(){
		if ($(this).val() == '') {
			$('#open_inviter_form div').hide();
			$('#open_inviter_contacts').hide();
		} else
			$('#open_inviter_form div').show();
	});

	$('#provider').trigger('change');

	$('#checkall').click(function(){
		checkAll();
	});

	$('a.rules, a.mail').fancybox({
		'type': 'iframe',
		'width': 550,
		'helpers' : {
        	'overlay' : {
            	'closeClick' : $('#sponsorship_popup').size()==0,
        	},
        	'title' : null,
    	}
	});

	$('#open_inviter_form').submit(function() {
		$('#open_inviter_contacts').html('<center><img src="'+baseDir+'modules/allinone_rewards/img/loadingAnimation.gif"></center>').show();
		$.ajax({
			type	: "POST",
			cache	: false,
			url		: url_allinone_sponsorship,
			data	: $(this).serialize() + ($('#sponsorship_popup').size()==1 ? '&popup=1' : ''),
			dataType: "html",
			success : function(data) {
				$('#open_inviter_contacts').html(data);

				$('#checkall').click(function(){
					checkAll();
				});

				$('a.rules').fancybox({
					'type': 'iframe',
					'width': 550,
					'helpers' : {
			        	'overlay' : {
			            	'closeClick' : $('#sponsorship_popup').size()==0,
			        	},
			        	'title' : null,
			    	}
				});

				$('#open_inviter_contacts_form').submit(function() {
					return submitForm($(this));
				});
			}
		});
		return false;
	});

	$('#list_contacts_form').submit(function() {
		return submitForm($(this));
	});
}

function acceptSponsorshipCGV(form) {
	if (!$('input.cgv:checked', $(form)).length) {
		alert(msg);
		return false;
	}
	return true;
}

function submitForm(form) {
	if ($('#sponsorship_popup').size() > 0) {
		if (acceptSponsorshipCGV($(form))) {
			var scheduled = $('#sponsorship_popup').hasClass('scheduled') ? '1' : '0';
			$.fancybox.showLoading();
			$.ajax({
				type	: "POST",
				cache	: false,
				url		: url_allinone_sponsorship,
				data	: $(form).serialize() + "&popup=1&scheduled=" + scheduled,
				dataType: "html",
				success : function(data) {
					fancybox(data);
				}
			});
		}
		return false;
	} else
		return acceptSponsorshipCGV($(form));
}

function fancybox(data) {
	$.fancybox(
	[
		{
			content	: data,
			afterShow : function() {
				initRewards();
			}
		}
	],
	{
		'width': 550,
		'arrows': false,
		'mouseWheel': false,
		"minHeight": 20,
		'helpers' : {
        	'overlay' : {
            	'closeClick' : false,
        	},
        	'title' : null,
    	}
	});
}

function checkAll() {
	if ($('#checkall').attr('checked'))
		$('#checkall').parents('table.std').find(':checkbox').attr('checked', true);
	else
		$('#checkall').parents('table.std').find(':checkbox').attr('checked', false);
}