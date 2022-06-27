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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="col-sm-12 col-md-12 col-lg-12" id="{$module_name|escape:'htmlall':'UTF-8'}_payment_page_logo">
	<div class="clear">&nbsp;</div>
	<h3>
		<i class="icon-credit-card"></i>&nbsp;{l s='Payment Page Logo' mod='postfinance'}
	</h3>
	<p><b>{l s='Choose your payment image' mod='postfinance'}</b></p>
	<p>{l s='It will be displayed on the payment step of the purchasing funnel.' mod='postfinance'}</p>
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo1" value="1" {if $config.postfinance_LOGO eq 1}checked{/if}>
			    	<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo1.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo2" value="2" {if $config.postfinance_LOGO eq 2}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo2.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo3" value="3" {if $config.postfinance_LOGO eq 3}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo3.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo4" value="4" {if $config.postfinance_LOGO eq 4}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo4.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo5" value="5" {if $config.postfinance_LOGO eq 5}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo5.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo6" value="6" {if $config.postfinance_LOGO eq 6}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo6.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo7" value="7" {if $config.postfinance_LOGO eq 7}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo7.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo8" value="8" {if $config.postfinance_LOGO eq 8}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo8.png">
				</label>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4 logo-div">
				<label>
				<input type="radio" name="pf_logo_img" id="logo9" value="9" {if $config.postfinance_LOGO eq 9}checked{/if}>
					<img src="{$pf_img_path|escape:'htmlall':'UTF-8'}payment_logo9.png">
				</label>
			</div>
		</div>
</div>