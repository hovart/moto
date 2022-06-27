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

<script language="javascript" type="text/javascript">
//<![CDATA[
	var submited = false
	var modules_list_loaded = false;
	$(function() {
		//get reference on save link
		btn_save = $('span[class~="process-icon-save"]').parent();

		//get reference on form submit button
		btn_submit = $('#advancedimporter_upload_form_submit_btn');

		if (btn_save.length > 0 && btn_submit.length > 0)
		{
			//get reference on save and stay link
			btn_save_and_stay = $('span[class~="process-icon-save-and-stay"]').parent();

			//get reference on current save link label
			lbl_save = $('#desc-advancedimporter_configuration-save div');

			//override save link label with submit button value
			if (btn_submit.val().length > 0)
				lbl_save.html(btn_submit.attr("value"));

			if (btn_save_and_stay.length > 0)
			{

				//get reference on current save link label
				lbl_save_and_stay = $('#desc-advancedimporter_cron-save-and-stay div');

				//override save and stay link label with submit button value
				if (btn_submit.val().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked'))
				{
					lbl_save_and_stay.html(btn_submit.val() + " et rester ");
				}

			}

			//hide standard submit button
			btn_submit.hide();
			//bind enter key press to validate form
			$('#advancedimporter_upload_form').keypress(function (e) {
				if (e.which == 13 && e.target.localName != 'textarea')
					$('#desc-advancedimporter_configuration-save').click();
			});
			//submit the form
			
			btn_save.click(function() {
				// Avoid double click
				if (submited)
					return false;
				submited = true;
				
				//add hidden input to emulate submit button click when posting the form -> field name posted
				btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');

				$('#advancedimporter_upload_form').submit();
				return false;
			});

			if (btn_save_and_stay)
			{
				btn_save_and_stay.click(function() {
					//add hidden input to emulate submit button click when posting the form -> field name posted
					btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');

					$('#advancedimporter_upload_form').submit();
					return false;
				});
			}
		}
	});
//]]>
</script>
<div class="hint clear bootstrap" style="display:block;">
	<div id="infos_block" class="{if version_compare($smarty.const._PS_VERSION_,'1.6','>')}alert{/if} alert-info">
		{l s='The uploded file will be put on the directory "%s/advancedimporter/flows/import/queue/" and a file "UPLOADED" will be created.' sprintf=[$moduleDir] mod='advancedimporter'}<br /><br />
		{l s='You can upload a CSV file, it will be converted in XML.' mod='advancedimporter'}
		<a href="http://prestashopxmlimporter.madef.fr/#importCSV">{l s='See documentation for more details.' mod='advancedimporter'}</a>
	</div>
</div>
<br />
<form method="post" id="advancedimporter_upload_form" enctype="multipart/form-data">
	<fieldset>
		<h1>{l s='Import a flow' mod='advancedimporter'}</h1>
		<label for="AI_FILE_TO_UPLOAD">{l s='File to upload: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<input type="file" name="AI_FILE_TO_UPLOAD" id="AI_FILE_TO_UPLOAD" class="" size="92"/>
		</div>
        <!--
		<label for="AI_CONVERT_CSV">{l s='Convert CSV to XML (deprecated)' mod='advancedimporter'}</label>
		<div class="margin-form">
			<input type="checkbox" id="AI_CONVERT_CSV" name="AI_CONVERT_CSV" value="1" class="radio" />
		</div>
        -->
		<div class="margin-form">
			<input type="submit" id="advancedimporter_upload_form_submit_btn" value="{l s='Save' mod='advancedimporter'}" name="submitAddadvancedimporter_upload" class="button" style="{if isset($display_button) && $display_button}display: none;{/if}">
		</div>
		<p>
			<b>{l s='Step to import flow:' mod='advancedimporter'}</b>
		</p>
		<ul>
			<li>{l s='1. Upload de flow' mod='advancedimporter'}</li>
			<li>{l s='2. Wait few minutes' mod='advancedimporter'}
			<li>{l s='3. Go to the tab "flow"' mod='advancedimporter'}
				<ul>
					<li>{l s='3.1. If the flow is on list, go to step 4' mod='advancedimporter'}</li>
					<li>{l s='3.2. If the flow do not appears, do again step 2' mod='advancedimporter'}</li>
					<li>{l s='3.3. If the flow is still not on the tab, check its format' mod='advancedimporter'}</li>
				</ul>
			</li>
			<li>{l s='4. Refresh the home page and follow the progress in the tab "flow"' mod='advancedimporter'}</li>
		</ul>
	</fieldset>
</form>
