{*
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
*}
<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">

    <div class="pull-left flashicon"><span class="mgd-venteflashicon"></span></div>
    <div class="clock-outer-center">
        <div class="clock-inner-center">
            <h5>{l s='Flash Sale Ends In...' mod='flashsalepro'}</h5>
            <div class="price-percent-reduction discount-figure col-xs-12 col-sm-12 col-md-12 col-lg-12">

            </div>
            <div class="clear">&nbsp;</div>
            <div class="mini-clock">
                <div class="clock-mini"></div>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
    var date_end = "{/literal}{$flash_sale_info.end_date_timestamp|escape:'htmlall':'UTF-8'}{literal}";
    var lang_code = "{/literal}{$flash_sale_info.lang_code|escape:'htmlall':'UTF-8'}{literal}";
    /* miniature clock - Variables used in flipclock.min.js for inline style on element of class "inn" */
    var timer_bg_color = "{/literal}{$timer_bg_color|escape:'htmlall':'UTF-8'}{literal}";
    var timer_text_color = "{/literal}{$timer_text_color|escape:'htmlall':'UTF-8'}{literal}";
    var timer_dot_color = "{/literal}{$timer_dot_color|escape:'htmlall':'UTF-8'}{literal}";
</script>
{/literal}