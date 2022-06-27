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

{capture name=path}{l s='My account'}{/capture}

<h1 class="page-heading">{l s='My account'}</h1>
{if isset($account_created)}
    <p class="alert alert-success">
        {l s='Your account has been created.'}
    </p>
{/if}
<p class="info-account">
    {l s='Welcome to your account. Here you can manage all of your personal information and orders.'}
</p>
<p class="bg-warning"><i class="fa fa-exclamation-triangle"></i> {l s='Pour éviter tout problème lors de votre transaction sur MotoGooDeal, nous vous invitons à enlever de votre nom, prénom ou adresse accents et caractères spéciaux. Merci de votre compréhension. MotoGooDeal Service Clients.'}</p>
<div class="addresses-lists">
    <ul class="myaccount-link-list row">
        {if $has_customer_an_address}
        <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('address', true)|escape:'html':'UTF-8'}" title="{l s='Add my first address'}"><i class="fa fa-building-o"></i><span>{l s='Add my first address'}</span></a></li>
        {/if}
        <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Orders'}"><i class="fa fa-list-ol"></i><span>{l s='Order history and details'}</span></a></li>
        {if $returnAllowed}
            <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='Merchandise returns'}"><i class="fa fa-refresh"></i><span>{l s='My merchandise returns'}</span></a></li>
        {/if}
        <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('order-slip', true)|escape:'html':'UTF-8'}" title="{l s='Credit slips'}"><i class="fa fa-ban"></i><span>{l s='My credit slips'}</span></a></li>
        <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}"><i class="fa fa-building-o"></i><span>{l s='My addresses'}</span></a></li>
        <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Information'}"><i class="fa fa-user"></i><span>{l s='My personal information'}</span></a></li>
    
{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
    
        {if $voucherAllowed}
            <li class="col-xs-12 col-sm-6 col-md-4"><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}"><i class="fa fa-briefcase"></i><span>{l s='My vouchers'}</span></a></li>
        {/if}
        {$HOOK_CUSTOMER_ACCOUNT}
    </ul>
{/if}
</div>
<ul class="footer_links clearfix">
<li><a class="button std-btn" href="{$base_dir}" title="{l s='Home'}"><i class="fa fa-chevron-left left"></i> {l s='Home'}</a></li>
</ul>
