{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-left">
	<div class="clr_10"></div>

	<div id="modulenewsletterblock">
		<h3>{l s='Stay in touch with us' mod='gsnippetsreviews'}</h3>

		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				<div class="alert alert-info">{l s='Sign up to our newsletter and stay up-to-date on new modules, important updates and more !' mod='gsnippetsreviews'}</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">{l s='Your e-mail ' mod='gsnippetsreviews'}</span>
						<input type="text" name="newsletter_email" id="newsletter_email" size="50" />
						<input type="hidden" name="newsletter_language" id="newsletter_language" value="{$sCurrentLang|escape:'htmlall':'UTF-8'}" />
					</div>
				</div>
				<button class="btn btn-info" name="dosignup" id="dosignup">{l s='Sign up !' mod='gsnippetsreviews'}</button>
			</div>
		</div>

		<div class="clr_20"></div>

		<div id="box_msg_ok"></div>
		<div id="box_msg_error"></div>
	</div>

	<div class="clr_10"></div>
	<h3>{l s='Follow Us' mod='gsnippetsreviews'}</h3>
	<div class="clr_10"></div>

	<div class="form-group">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="fb-like" data-href="https://www.facebook.com/businesstech.fr/?fref=ts" data-layout="box_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
			<a class="twitter-follow-button" href="https://twitter.com/_businesstech_"  data-size="default">	Follow @BusinessTech</a>
		</div>
	</div>
</div>

<div class="clr_20"></div>

{*Facebook Like*}
<div id="fb-root"></div>
<script type="text/javascript">
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.7&appId=892585157542155";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

{*Twitter Follow*}
<script type="text/javascript">
	window.twttr = (function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0],
			t = window.twttr || {};
		if (d.getElementById(id)) return t;
		js = d.createElement(s);
		js.id = id;
		js.src = "https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js, fjs);
		t._e = [];
		t.ready = function(f) {
			t._e.push(f);
		};
		return t;
	}(document, "script", "twitter-wjs"));</script>

{literal}
<script type="text/javascript">
	function display_confirmation_message(data) {
		var msg_invalid_email = {/literal}'{l s="Sorry... The e-mail you have entered is invalid. Please double-check it." mod="gsnippetsreviews"}'{literal};
		var msg_invalid_language = {/literal}'{l s="Sorry... The language parameter is invalid. Please reload this page, try again and contact us if this problem persists." mod="gsnippetsreviews"}'{literal};
		var msg_duplicate = {/literal}'{l s="Your e-mail was already in our list, no need to enter it again. If you have unsubscribed directly from MailChimp, please contact us so we can add you there again manually" mod="gsnippetsreviews"}'{literal};
		var msg_unknown_error = {/literal}'{l s="Sorry... There was an unexpected error. Please reload this page, try again and contact us if this problem persists." mod="gsnippetsreviews"}'{literal};
		var msg_unknown_error_ajax = {/literal}'{l s="Sorry... There was an unexpected network error. Please reload this page, try again and contact us if this problem persists." mod="gsnippetsreviews"}'{literal};
		var msg_ok = {/literal}'{l s="Thank you ! You have been successfully added to our newsletter list." mod="gsnippetsreviews"}'{literal};

		$("#box_msg_ok").hide();
		$("#box_msg_error").hide();

		console.log($("#box_msg_ok"));
		console.log($("#box_msg_error"));

		var sContent = '<button type="button" class="close" data-dismiss="alert">×</button>';
		var sBoxType = 'error';

		switch (data.response) {
			case 'invalid_email' :
				sContent = '<div class="alert alert-danger">'+sContent+'<p>'+msg_invalid_email+'</p>';
				break;
			case 'invalid_language' :
				sContent = '<div class="alert alert-danger">'+sContent+'<p>'+msg_invalid_language+'</p>';
				break;
			case 'duplicate' :
				sContent = '<div class="alert alert-danger">'+sContent+'<p>'+msg_duplicate+'</p>';
				break;
			case 'unknown_error' :
				sContent = '<div class="alert alert-danger">'+sContent+'<p>'+msg_unknown_error+'</p>';
				break;
			case 'ok' :
				sContent = '<div class="alert alert-success">'+sContent+'<p>'+msg_ok+'</p>';
				sBoxType = 'ok'
				break;
			default:
				sContent += '<p>'+msg_unknown_error_ajax+'</p>';
				break;
		}
		sContent += '</div>';
		$("#box_msg_"+ sBoxType).html(sContent).show();

		return false;
	}

	$(document).ready(function() {
		$("#dosignup").click(function(e) {
			var email = $("#newsletter_email").val();
			var language = $("#newsletter_language").val();

			$.ajax({
				type: "GET",
				url: "https://www.businesstech.fr/api_newsletter.php",
				data: "email="+email+"&language="+language,
				dataType: "jsonp",
				crossDomain: true,
				async : true,
				jsonpCallback: "display_confirmation_message"
			});
		});
	});
</script>
{/literal}