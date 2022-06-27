{*
* 2013-2016 MADEF IT
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
*  @author    MADEF IT <contact@madef.fr>
*  @copyright 2013-2016 SASU MADEF IT
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="toolbarBox toolbarHead">
	<div class="pageTitle">
		<h3>
			<span id="current_obj" style="font-weight: normal;">
				<span class="breadcrumb item-0 ">{l s='Advanced Importer' mod='advancedimporter'}
					<img alt="&gt;" style="margin-right:5px" src="../img/admin/separator_breadcrumb.png">
				</span>
				<span class="breadcrumb item-1 ">{l s='Blocks' mod='advancedimporter'}
				</span>
			</span>
		</h3>
	</div>
</div>
<fieldset id="fieldset_0">
	<label>{l s='Callback :' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->callback|escape:'htmlall':'UTF-8'}
|escape:'htmlall'
	</div>
	<div class="clear"></div>
	
	<label>{l s='Channel:' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->channel|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Block :' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->block|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Result :' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{if $obj->result == 0}
			Non exécuté
		{else if $obj->result == -1}
			Erreur lors de l'éxecution
		{else}
			Exécuté
		{/if}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Error :' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->error|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Memory :' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->memory|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Created at:' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->created_at|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Plannified at:' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->planned_at|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Treatement start:' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->treatment_start|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
	
	<label>{l s='Treatement end:' mod='advancedimporter'}</label>
	<div class="margin-form" style="padding: 0.2em 0 1em 260px; font-size: 1em; color: #585A69;">
		{$obj->treatment_end|escape:'htmlall':'UTF-8'}
	</div>
	<div class="clear"></div>
</fieldset>
