{*
* 2007-2017 PrestaShop
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
*	@author PrestaShop SA <contact@prestashop.com>
*	@copyright	2007-2017 PrestaShop SA
*	@license		http://opensource.org/licenses/afl-3.0.php	Academic Free License (AFL 3.0)
*	International Registered Trademark & Property of PrestaShop SA
*}
<div class="row">
  <div class="col-xs-12">
    <div class="well">
      <div class="text-center">
        <img class="mail-chimp" src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/mail-chimp.png">
        <h1>MailChimp for PrestaShop</h1>
        <div class="row">
          <div class="col-xs-10 col-xs-offset-1">
            <h4>When you connect your PrestaShop store to MailChimp, you increase the power of both. Our marketing automation and reporting tools seamlessly integrate with your store, so you can use your customer data to personalize your marketing, sell more stuff, and grow your business.</h4>
          </div>
        </div>
      </div>
      <br></br>
      <h2>Instructions</h2>
      <ul>
        <li> You must first have a MailChimp account, which you can create for free <a href="https://login.mailchimp.com/signup/">here.</a>
        <li>Your store must have a physical address (set in <b>Preferences->Store Contacts</b>) in order to create a new list.</li>
        <li>Upon selecting a MailChimp List or creating a new List, your store will sync with MailChimp. Please be patient as this can take some time.</li>
        <li>After the sync completes, new Orders, Products, and Customers that are created in your PrestaShop store will automatically sync with MailChimp in the future.</li>
        <li>If you'd like to disable these features for any reason, disconnect from MailChimp on this configuration page.</li>
      </ul>
      <br></br>
      {if isset($list_name)}
        <span class="label label-success" style="font-size: 16px">Connected to list {$list_name|escape:'htmlall':'UTF-8'}</span>
      {/if}
    </div>
  </div>
</div>
