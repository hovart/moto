{*
* 2007-2013 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta http-equiv="content-language" content="{$meta_language}" />
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		<script type="text/javascript">
			var baseDir = '{$content_dir|addslashes}';
			var baseUri = '{$base_uri|addslashes}';
			var static_token = '{$static_token|addslashes}';
			var token = '{$token|addslashes}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
			var priceDisplayMethod = {$priceDisplay};
			var roundMode = {$roundMode};
		</script>
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:600" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://fast.fonts.net/jsapi/6d0e2a7e-a7ba-4436-9684-3d122c7928b1.js"></script>
{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}

		{$HOOK_HEADER}
		{hook h='displaytptnhead'}

<!-- Facebook Conversion Code for key pages motogoodeal -->
		<script>(function() {
		  var _fbq = window._fbq || (window._fbq = []);
		  if (!_fbq.loaded) {
		    var fbds = document.createElement('script');
		    fbds.async = true;
		    fbds.src = '//connect.facebook.net/en_US/fbds.js';
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(fbds, s);
		    _fbq.loaded = true;
		  }
		})();
		{literal}
			window._fbq = window._fbq || [];
			window._fbq.push(['track', '6020849970548', {'value':'0.00','currency':'CHF'}]);
		{/literal}
	</script>
	<noscript>
		<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6020849970548&amp;cd[value]=0.00&amp;cd[currency]=CHF&amp;noscript=1" /></noscript>

<!-- Facebook Conversion Code for Ajouts au panier -->
	<script>
		{literal}
			window._fbq = window._fbq || [];
			window._fbq.push(['track', '6020873145348', {'value':'0.00','currency':'CHF'}]);
		{/literal}
	</script>
	<noscript>
		<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6020873145348&amp;cd[value]=0.00&amp;cd[currency]=CHF&amp;noscript=1" /></noscript>


<!-- Facebook Conversion Code for Prospects -->
	<script>
		{literal}
			window._fbq = window._fbq || [];
			window._fbq.push(['track', '6020877390948', {'value':'0.00','currency':'CHF'}]);
		{/literal}
	</script>
	<noscript>
		<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6020877390948&amp;cd[value]=0.00&amp;cd[currency]=CHF&amp;noscript=1" /></noscript>
<!--Fin insertion-->
	
	</head>
	
	<body itemscope="" itemtype="http://schema.org/WebPage" {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'htmlall':'UTF-8'}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if $content_only} content_only{/if}">
	{if !$content_only}
		<div class="wrapper">
		{if isset($restricted_country_mode) && $restricted_country_mode}
		<div id="restricted-country">
			<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country}</span></p>
		</div>
		{/if}
		
		{hook h='displaytptnbody'}
		
		<div id="back-top" style="display:block;"></div>
		
		<div id="page">
			<div id="bg_overlay"></div>
			<!-- Header -->
			<header id="header">
                <div id="header_top" class="row"></div>
				<div id="header_right" class="row">
					<a id="header_logo" href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
						<img class="logo" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_image_width}width="{$logo_image_width}"{/if} {if $logo_image_height}height="{$logo_image_height}" {/if} />
					</a>
					{$HOOK_TOP}
				</div>
			</header>

			<div id="columns" class="row clearfix">
				<!-- Left -->
				<div id="left_column" class="subcol">
					{$HOOK_LEFT_COLUMN}
				</div>

				<!-- Center -->
				<div id="center_column" class="maincol">
	{/if}
