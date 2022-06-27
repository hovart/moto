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

{if !$error_php_version && !$error_write_import_folder && !$error_write_error_folder
	&& !$error_write_queue_folder && !$error_write_imported_folder}
	<div class="alert alert-success">
		{l s='No errors detected.' mod='advancedimporter'}
	</div>
{/if}
{if $error_php_version}
	<div class="alert alert-danger">
		{l s='PHP version must be greater or equal that 5.3.0.' mod='advancedimporter'}
		<br />
		{l s='Actual version: ' mod='advancedimporter'}{$php_version|escape:'htmlall':'UTF-8'}
	</div>
{/if}
{if $error_write_import_folder || $error_write_error_folder || $error_write_queue_folder || $error_write_imported_folder}
	<div class="alert alert-danger">
		{if $error_write_import_folder}
			{l s='Please check right for the folder "modules/advancedimporter/import/import". The module need to write in this folder!' mod='advancedimporter'}
			<br />
		{/if}
		{if $error_write_error_folder}
			{l s='Please check right for the folder "modules/advancedimporter/import/error". The module need to write in this folder!' mod='advancedimporter'}
			<br />
		{/if}
		{if $error_write_queue_folder}
			{l s='Please check right for the folder "modules/advancedimporter/import/queue". The module need to write in this folder!' mod='advancedimporter'}
			<br />
		{/if}
		{if $error_write_imported_folder}
			{l s='Please check right for the folder "modules/advancedimporter/import/imported". The module need to write in this folder!' mod='advancedimporter'}
			<br />
		{/if}
	</div>
{/if}
{if $error_local_host}
	<div class="alert alert-warning">
		{l s='It seems your PrestaShop is installed on a local network (not the production server). Smart cron cannot work properly.' mod='advancedimporter'}
		<br />
		{l s='You have to execute cron.php and plan.php on a unix cron tab or manually.' mod='advancedimporter'}
	</div>
{/if}
{if $error_smart_cron}
	<div class="alert alert-warning">
		{l s='Smart cron is not enable. To use Smart cron, set "Order reference" and "Use smart cron" in the configuration.' mod='advancedimporter'}
		<br />
		{l s='Smart cron simulate "unix cron".' mod='advancedimporter'}
		<br />
		{l s='If you dont want to use the API, you will have to create two unix crontab that execute cron.php and plan.php.' mod='advancedimporter'}
	</div>
{/if}
{if $error_importer}
	<div class="alert alert-warning">
		{l s='No %s defined. An uploader upload your flows automatically from external server.' sprintf=[$importer_link] mod='advancedimporter' js=1}
	</div>
{/if}
{if $error_csv}
	<div class="alert alert-warning">
		{l s='No CSV templates defined. A csv template is necessary to import flow in CSV format.' mod='advancedimporter'} <a href="{$csv_link|escape:'htmlall':'UTF-8'}">{l s='Add a template.' mod='advancedimporter'}</a>
	</div>
{/if}
