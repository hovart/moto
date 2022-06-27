{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to http://doc.prestashop.com/display/PS15/Overriding+default+behaviors
* #Overridingdefaultbehaviors-Overridingamodule%27sbehavior for more information.
*
* @author Samdha <contact@samdha.net>
* @copyright  Samdha
* @license    commercial license see license.txt
*}
{if $writable}
	<div id="tabModel" class="col-lg-10 col-md-9">
	<div class="panel">
        <h3 class="tab"> <i class="icon-sign-out"></i> {l s='Export models' mod='export_catalog'}</h3>
		<form id="{$module_short_name|escape:'htmlall':'UTF-8'}model_form" action="{$module_url|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
			<input type="hidden" id="{$module_short_name|escape:'htmlall':'UTF-8'}_current_model" value="{$model->filename|escape:'htmlall':'UTF-8'}">
			{if $model->filename}
				<div class="form-group">
					<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_model">{l s='Export model' mod='export_catalog'}</label>
					<div class="margin-form actions_list">
						<input type="submit" class="hidden" name="actionSaveModel" value="{l s='Save' mod='export_catalog'}"/>
						<select class="input_large" name="{$module_short_name|escape:'htmlall':'UTF-8'}_model" id="{$module_short_name|escape:'htmlall':'UTF-8'}_model" style="margin-bottom: 1em;">
							{assign var='models' value=$model->getFiles()}
							{html_options options=$models selected=$model->filename}
						</select>
						<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#model">?</a>
						<div style="display: inline-block; vertical-align: middle">
							<button type="submit" class="samdha_button action" name="" title="{l s='Select an action' mod='export_catalog'}" value="1"/>{l s='Action' mod='export_catalog'}</button>
							<button class="action_select">{l s='Select an action' mod='export_catalog'}</button>
						</div>
						<ul>
							<li>
								<a href="#" rel="actionLoadModel" id="model_load_button" data-placement="left" title="{l s='Load the selected model for modification.' mod='export_catalog'}"><span class="ui-icon ui-icon-pencil"></span> {l s='Modify' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionDuplicateModel" id="model_duplicate_button" data-placement="left" title="{l s='Create a copy of the selected model and open it for modification.' mod='export_catalog'}"><span class="ui-icon ui-icon-copy"></span> {l s='Duplicate' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionDeleteModel" id="model_delete_button" data-placement="left" title="{l s='Delete the selected model. Warning, this is definive.' mod='export_catalog'}" alt_title="{l s='Delete the selected model. Warning, this is definive.' mod='export_catalog'}"><span class="ui-icon ui-icon-trash"></span> {l s='Delete' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionDownloadModel" id="model_download_button" data-placement="left" title="{l s='Download the selected model on your computer.' mod='export_catalog'}"><span class="ui-icon ui-icon-arrowstop-1-s"></span> {l s='Download' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionUploadModel" id="model_upload_button" title="{l s='Upload a new model from your computer.' mod='export_catalog'}"><span class="ui-icon ui-icon-arrowstop-1-n"></span> {l s='Upload' mod='export_catalog'}</a>
							</li>
						</ul>
						<div style="display: inline-block; vertical-align: middle">
							<button class="samdha_button ui-button-primary" name="actionExportModel" value="1" id="model_export_button" title="{l s='Manually export the selected model in a CSV file' mod='export_catalog'}">{l s='Export as CSV' mod='export_catalog'}</button>
						</div>
						<input type="file" name="model_filename" id="model_upload_input" />
					</div>
				</div>
				<hr/>

                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
					{if !$export->filename && (count($models) == 1)}
	                    {l s='Well done, you have created your first export model. You can now use it to create a CSV file by clicking the button "Export as CSV" or create a scheduled export to generate CSV files automaticaly. Click on the "Scheduled exports" for that.' mod='export_catalog'}
	                    <p>{l s='If you want to create another export model use the "Duplicate" button and modify the copy.' mod='export_catalog'}</p>
	                {else}
	                	{l s='If you want to create another export model use the "Duplicate" button and modify the copy.' mod='export_catalog'}
	                {/if}
                </div>

				<h3>{l s='Model edition' mod='export_catalog'}</h3>
			{else}
                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
                    {l s='It seems that you have not created any export model yet. Let me explain why and how to do that.' mod='export_catalog'}
                    <p>{l s='This symbol' mod='export_catalog'} <span class="module_help">?</span> {l s='indicates that you can find more help by clicking on it. Don\'t forget to visit the "Help" tab too.' mod='export_catalog'}</p>
                    <p>{l s='An export generates a CSV file. A comma-separated values (CSV) (also sometimes also called character-separated values, because the separator character does not have to be a comma) file stores tabular data (numbers and text) in plain-text form. Plain text means that the file is a sequence of characters, with no data that has to be interpreted instead, as binary numbers. A CSV file consists of any number of records, separated by line breaks of some kind; each record consists of fields, separated by some other character or string, most commonly a literal comma or tab (text from Wikipedia).' mod='export_catalog'}</p>
                    <p>{l s='An export model describes what products (records) to export and how to organise them in the CSV file.' mod='export_catalog'}</p>
                    <p>{l s='You can create as many export models as you want therefore choose an appropriate name is a good idea for easy retrieval.' mod='export_catalog'}</p>
                </div>
			{/if}
			<div class="form-group clear">
				<label for="{$module_short_name|escape:'htmlall':'UTF-8'}model_name">{l s='Model name' mod='export_catalog'}</label>
				<div class="margin-form">
					<input type="text" class="input_large" name="model[name]" id="{$module_short_name|escape:'htmlall':'UTF-8'}model_name" value="{if $model->name}{$model->name|escape:'htmlall':'UTF-8'}{else}{l s='New model' mod='export_catalog'}{/if}" />
					<input type="submit" class="samdha_button" name="actionSaveModel" value="{l s='Save' mod='export_catalog'}" title="{l s='Save the current model' mod='export_catalog'}"/>
				</div>
			</div>

			<div class="accordion">
				<h3 class="modal-title">{l s='File structure' mod='export_catalog'}</h3>
				<div>
	                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
	                    {l s='Define here how the generated file will be structured.' mod='export_catalog'}
						{if !$model->filename}
		                    <p>{l s='Most important part is the "Columns" value, choose the fields to export and their order.' mod='export_catalog'}</p>
							<p>{l s='When you have finish with this part, click on "Products to export" below to choose... how to say... the products to export.' mod='export_catalog'}</p>
						{/if}
	                </div>

					<div class="form-group clear">
						<label>{l s='Columns' mod='export_catalog'}</label>
						<div class="margin-form">
							<table id="{$module_short_name|escape:'htmlall':'UTF-8'}fields">
								<thead>
									<tr>
										<th>
											{l s='Value' mod='export_catalog'}
											<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#field_value">?</a>
										</th>
										<th class="field_title" {if !$model->datas.header}style="display: none"{/if}>
											{l s='Title' mod='export_catalog'}
											<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#field_title">?</a>
										</th>
										<th class="field_decoration" {if !array_key_exists('decoration', $model->datas) || !$model->datas.decoration}style="display: none"{/if}>
											{l s='Decoration' mod='export_catalog'}
											<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#field_decoration">?</a>
										</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								{foreach from=$model->datas.fields item=field name=fields}
									<tr>
										<td>
											{if count($possible_fields) >= 100}
												<div class="fake_select input_large">
													<input type="hidden" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][id]" value="{$field.id|escape:'htmlall':'UTF-8'}" />
													<span>{if isset($possible_fields[$field.id])}{$possible_fields[$field.id]|escape:'htmlall':'UTF-8'}{else}{l s='(Not possible)' mod='export_catalog'}{/if}</span>
												</div>
											{else}
												<select class="input_large" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][id]">
													{html_options options=$possible_fields selected=$field.id}
												</select>
											{/if}
											<div class="field_value" {if $field.id != 'fix' && (strpos($field.id, '_all', strlen($field.id) - 4) === false)}style="display: none"{/if}>
												<input class="input_large" type="text" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][value]" value="{if isset($field.value)}{$field.value|escape:'htmlall':'UTF-8'}{/if}" {if $field.id == 'fix'}placeholder="{l s='Value' mod='export_catalog'}"{else}placeholder="{l s='Separator' mod='export_catalog'}"{/if}/>
											</div>
										</td>
										<td class="field_title" {if !$model->datas.header}style="display: none"{/if}>
											<input class="input_medium field_title" type="text" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][title]" value="{$field.title|escape:'htmlall':'UTF-8'}" />
										</td>
										<td class="field_decoration" {if !array_key_exists('decoration', $model->datas) || !$model->datas.decoration}style="display: none"{/if}>
											<input class="input_small" type="text" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][before]" value="{if isset($field.before)}{$field.before|escape:'htmlall':'UTF-8'}{/if}" placeholder="{l s='Text before' mod='export_catalog'}"/><br/>
											<input class="input_small" type="text" name="model[datas][fields][{$smarty.foreach.fields.index|escape:'htmlall':'UTF-8'}][after]" value="{if isset($field.after)}{$field.after|escape:'htmlall':'UTF-8'}{/if}" placeholder="{l s='Text after' mod='export_catalog'}"/>
										</td>
										<td>
											<span style="display: inline-block" class="field_add ui-icon ui-icon-circle-plus" title="{l s='add new column' mod='export_catalog'}"></span>
											<span {if $smarty.foreach.fields.first}style="display: none"{else} style="display: inline-block"{/if} class="field_delete ui-icon ui-icon-circle-minus" title="{l s='delete' mod='export_catalog'}"></span>
											<span {if $smarty.foreach.fields.first}style="display: none"{else} style="display: inline-block"{/if} class="field_up ui-icon ui-icon-circle-arrow-n" title="{l s='move up' mod='export_catalog'}"></span>
											<span {if $smarty.foreach.fields.last}style="display: none"{else} style="display: inline-block"{/if} class="field_down ui-icon ui-icon-circle-arrow-s" title="{l s='move down' mod='export_catalog'}"></span>
										</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Use' mod='export_catalog'} <span  style="display: inline-block" class="ui-icon ui-icon-circle-plus"></span> {l s='to insert a new column to the CSV file.' mod='export_catalog'}<br/>
								{l s='Use' mod='export_catalog'} <span style="display: inline-block" class="ui-icon ui-icon-circle-minus"></span> {l s='to remove a column from the CSV file.' mod='export_catalog'}<br/>
							</p>
						</div>
					</div>

					<div class="form-group clear">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filename" >{l s='Filename' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" name="model[datas][filename]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filename" value="{$model->datas.filename|escape:'htmlall':'UTF-8'}" title="{l s='%%Y will be replaced by the year, %%m by the month and %%d by the day at the time the file will be created' mod='export_catalog'}"/>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#filename">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Products will be exported in this file:' mod='export_catalog'} {$model->datas.filename|strftime|escape:'htmlall':'UTF-8'}
							</p>
						</div>
					</div>

					<div class="form-group clear">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_separator" >{l s='CSV delimiter' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" name="model[datas][separator]" maxlength="1" class="input_small" id="{$module_short_name|escape:'htmlall':'UTF-8'}_separator" value="{$model->datas.separator|escape:'htmlall':'UTF-8'}" />
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#separator">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Most of the time , or ; but it can be any character' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_enclosure" >{l s='CSV enclosure' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" name="model[datas][enclosure]" maxlength="1" class="input_small" id="{$module_short_name|escape:'htmlall':'UTF-8'}_enclosure" value="{$model->datas.enclosure|escape:'htmlall':'UTF-8'}" />
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#enclosure">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Should be " but it can be any character' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label>{l s='Force enclosure' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][force_enclosure]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_force_enclosure_on" value="1" {if $model->datas.force_enclosure}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_force_enclosure_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][force_enclosure]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_force_enclosure_off" value="0" {if !$model->datas.force_enclosure}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_force_enclosure_off">{l s='No' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#force_enclosure">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Use enclosure for all fields.' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_charset">{l s='Character encoding' mod='export_catalog'}</label>
						<div class="margin-form">
							<select name="model[datas][charset]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_charset">
								{html_options options=$charsets selected=$model->datas.charset}
							</select>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#charset">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Ok this part is not easy. Most of the time you should choose UTF-8 or ISO-8859-15.' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label>{l s='Add header line' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][header]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_header_on" value="1" {if $model->datas.header}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_header_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][header]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_header_off" value="0" {if !$model->datas.header}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_header_off">{l s='No' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#header">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Add a first line in the CSV file with columns names. You can modify the names with the translation tool of Prestashop' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_decimal" >{l s='Decimal separator' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" maxlength="1" class="input_small" length=1 id="{$module_short_name|escape:'htmlall':'UTF-8'}_decimal" name="model[datas][decimal]" value="{$model->datas.decimal|escape:'htmlall':'UTF-8'}" />
							<p {if $version_16}class="help-block"{/if}>
								{l s='Will be used in the prices. You can choose to have 12,34 or 12.34 (or 12@43 if you want).' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_precision" >{l s='Number of decimal points' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="number" step="1" min="0" id="{$module_short_name|escape:'htmlall':'UTF-8'}_precision" name="model[datas][precision]" value="{$model->datas.precision|escape:'htmlall':'UTF-8'}" />
							<p {if $version_16}class="help-block"{/if}>
								{l s='Will be used in the prices. You can choose to have 12.34 instead of 12.34321.' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {if $model->datas.price_format == ''}{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on{/if}">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_price_format" >{l s='Prices decoration' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" class="input_small" id="{$module_short_name|escape:'htmlall':'UTF-8'}_price_format" name="model[datas][price_format]" value="{$model->datas.price_format|escape:'htmlall':'UTF-8'}" />
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#price_format">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Will be used in the prices. "[PRICE] USD" will give "13.46 USD", "$[PRICE]" will give "$13.46", live empty to have only number' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">
						<label>{l s='Columns decoration' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][decoration]" id="decoration_on" value="1" {if array_key_exists('decoration', $model->datas) && $model->datas.decoration}checked="checked"{/if} />
								<label for="decoration_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][decoration]" id="decoration_off" value="0" {if !array_key_exists('decoration', $model->datas) || !$model->datas.decoration}checked="checked"{/if}/>
								<label for="decoration_off">{l s='No' mod='export_catalog'}</label>
							</span>
							<a class="module_help" href="{$documentation_url|escape:'htmlall':'UTF-8'}#decoration">?</a>
						</div>
					</div>
				</div>

				<h3 class="modal-title">{l s='Products to export' mod='export_catalog'}</h3>
				<div>
					{if !$model->filename}
	                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
	                    {l s='Here you can filter the products you want to export. I think the options are obvious so I will wait for you at the next part "Context".' mod='export_catalog'}
	                </div>
	                {/if}

					<div class="form-group clear">
						<label>{l s='Inactive products' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][inactive]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_on" value="1" {if $model->datas.inactive == 1}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][inactive]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_off" value="0" {if $model->datas.inactive == 0}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_off">{l s='No' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][inactive]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_only" value="2" {if $model->datas.inactive == 2}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_inactive_only">{l s='Only' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#inactive">?</a>
						</div>
					</div>

					<div class="form-group clear">
						<label>{l s='Products out of stock' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][no_stock]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_on" value="1" {if $model->datas.no_stock == 1}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][no_stock]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_off" value="0" {if $model->datas.no_stock == 0}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_off">{l s='No' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][no_stock]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_only" value="2" {if $model->datas.no_stock == 2}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_no_stock_only">{l s='Only' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#no_stock">?</a>
						</div>
					</div>

					<div class="form-group clear">
						<label>{l s='Products with EAN13' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][with_ean]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_on" value="1" {if $model->datas.with_ean == 1}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][with_ean]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_off" value="0" {if $model->datas.with_ean == 0}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_off">{l s='No' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][with_ean]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_only" value="2" {if $model->datas.with_ean == 2}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_with_ean_only">{l s='Only' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#with_ean">?</a>
						</div>
					</div>

					<div class="form-group clear">
						<label>{l s='Export combinations' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][attribute]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_on" value="1" {if $model->datas.attribute}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][attribute]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_off" value="0" {if !$model->datas.attribute}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_off">{l s='No' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][attribute]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_only" value="2" {if $model->datas.attribute == 2}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_attribute_only">{l s='Only' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#attribute">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='If activated, a line will be created for each attributes of the products and the prices and quantities will be accurate for each attributes.' mod='export_catalog'}
							</p>
						</div>
					</div>

					<div class="form-group clear {if !$model->datas.new}{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on{/if}">
						<label>{l s='Only not yet exported' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][new]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_new_on" value="1" {if $model->datas.new}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_new_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][new]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_new_off" value="0" {if !$model->datas.new}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_new_off">{l s='No' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#attribute">?</a>
							<p {if $version_16}class="help-block"{/if}>
								{l s='Export only products created since the previous export.' mod='export_catalog'}
							</p>
						</div>
					</div>

					{if $model->datas.last_run != 0}
						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_new_on">
							<label>{l s='Reset export date' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="model[datas][last_run]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_last_run_on" value="0" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_last_run_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="model[datas][last_run]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_last_run_off" value="{$model->datas.last_run|escape:'htmlall':'UTF-8'}" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_last_run_off">{l s='No' mod='export_catalog'}</label>
								</span>
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#attribute">?</a>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_new_on">
							<label>{l s='Last export date' mod='export_catalog'}</label>
							<div class="margin-form">
								{$model->last_run_formated|escape:'htmlall':'UTF-8'} {l s='(server time)' mod='export_catalog'}
							</div>
						</div>
					{else}
						<input type="hidden" name="model[datas][last_run]" value="0" />
					{/if}

					{if empty($model->datas.categories)}
						<div class="form-group clear">
							<label>{l s='Filter by categories' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="filter_category" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_category_on" value="1" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_category_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="filter_category" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_category_off" value="0" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_category_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_filter_category_on">
						<label>{l s='Categories' mod='export_catalog'} </label>
						<div class="margin-form">
							<table cellspacing="0" cellpadding="0" id="{$module_short_name|escape:'htmlall':'UTF-8'}_categories">
								<thead>
									<tr>
										<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, 'model[datas][categories][]', this.checked);" /></th>
										<th>{l s='ID' mod='export_catalog'}</th>
										<th>{l s='Name' mod='export_catalog'}</th>
									</tr>
								</thead>
								<tbody>
									{$categories_html}
								</tbody>
							</table>
							<p style="padding:0px; margin:0px 0px 10px 0px;" {if $version_16}class="help-block"{/if}>{l s='Mark all checkbox(es) of categories to export. If no category is checked, all categories will be exported.' mod='export_catalog'}</p>
						</div>
					</div>

					{if empty($model->datas.manufacturers)}
						<div class="form-group clear">
							<label>{l s='Filter by manufacturers' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="filter_manufacturer" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_manufacturer_on" value="1" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_manufacturer_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="filter_manufacturer" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_manufacturer_off" value="0" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_manufacturer_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_filter_manufacturer_on">
						<label>{l s='Manufacturers' mod='export_catalog'} </label>
						<div class="margin-form">
							<table cellspacing="0" cellpadding="0" id="{$module_short_name|escape:'htmlall':'UTF-8'}_manufacturers">
								<thead>
									<tr>
										<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, 'model[datas][manufacturers][]', this.checked);" /></th>
										<th>{l s='ID' mod='export_catalog'}</th>
										<th>{l s='Name' mod='export_catalog'}</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input type="checkbox" name="model[datas][manufacturers][]" class="manufacturerBox" id="manufacturerBox_0" value="0" {if in_array(0, $model->datas.manufacturers)} checked="checked"{/if} />
										</td>
										<td><label for="manufacturerBox_0" class="t">0</label></td>
										<td><label for="manufacturerBox_0" class="t">{l s='None' mod='export_catalog'}</label></td>
									</tr>
									{foreach from=$manufacturers item=manufacturer_name key=manufacturer_id}
										<tr>
											<td>
												<input type="checkbox" name="model[datas][manufacturers][]" class="manufacturerBox" id="manufacturerBox_{$manufacturer_id|escape:'htmlall':'UTF-8'}" value="{$manufacturer_id|escape:'htmlall':'UTF-8'}" {if in_array($manufacturer_id, $model->datas.manufacturers)} checked="checked"{/if} />
											</td>
											<td><label for="manufacturerBox_{$manufacturer_id|escape:'htmlall':'UTF-8'}" class="t">{$manufacturer_id|escape:'htmlall':'UTF-8'}</label></td>
											<td><label for="manufacturerBox_{$manufacturer_id|escape:'htmlall':'UTF-8'}" class="t">{$manufacturer_name|escape:'htmlall':'UTF-8'}</label></td>
										</tr>
									{/foreach}
								</tbody>
							</table>
							<p style="padding:0px; margin:0px 0px 10px 0px;" {if $version_16}class="help-block"{/if}>{l s='Mark all checkbox(es) of manufacturers to export. If no manufacturer is checked, all manufacturers will be exported.' mod='export_catalog'}</p>
						</div>
					</div>

					{if empty($model->datas.suppliers)}
						<div class="form-group clear">
							<label>{l s='Filter by suppliers' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="filter_supplier" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_supplier_on" value="1" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_supplier_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="filter_supplier" id="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_supplier_off" value="0" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_filter_supplier_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_filter_supplier_on">
						<label>{l s='Suppliers' mod='export_catalog'} </label>
						<div class="margin-form">
							<table cellspacing="0" cellpadding="0" id="{$module_short_name|escape:'htmlall':'UTF-8'}_suppliers">
								<thead>
									<tr>
										<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, 'model[datas][suppliers][]', this.checked);" /></th>
										<th>{l s='ID' mod='export_catalog'}</th>
										<th>{l s='Name' mod='export_catalog'}</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input type="checkbox" name="model[datas][suppliers][]" class="supplierBox" id="supplierBox_0" value="0" {if in_array(0, $model->datas.suppliers)} checked="checked"{/if} />
										</td>
										<td><label for="supplierBox_0" class="t">0</label></td>
										<td><label for="supplierBox_0" class="t">{l s='None' mod='export_catalog'}</label></td>
									</tr>
									{foreach from=$suppliers item=supplier_name key=supplier_id}
										<tr>
											<td>
												<input type="checkbox" name="model[datas][suppliers][]" class="supplierBox" id="supplierBox_{$supplier_id|escape:'htmlall':'UTF-8'}" value="{$supplier_id|escape:'htmlall':'UTF-8'}" {if in_array($supplier_id, $model->datas.suppliers)} checked="checked"{/if} />
											</td>
											<td><label for="supplierBox_{$supplier_id|escape:'htmlall':'UTF-8'}" class="t">{$supplier_id|escape:'htmlall':'UTF-8'}</label></td>
											<td><label for="supplierBox_{$supplier_id|escape:'htmlall':'UTF-8'}" class="t">{$supplier_name|escape:'htmlall':'UTF-8'}</label></td>
										</tr>
									{/foreach}
								</tbody>
							</table>
							<p style="padding:0px; margin:0px 0px 10px 0px;" {if $version_16}class="help-block"{/if}>{l s='Mark all checkbox(es) of suppliers to export. If no supplier is checked, all suppliers will be exported.' mod='export_catalog'}</p>
						</div>
					</div>
				</div>

				<h3 class="modal-title">{l s='Context' mod='export_catalog'}</h3>
				<div>
	                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
						{if $model->filename}
		                    {l s='The context can modify the products list, the prices and stock. Moreover it defines the language of the exported file (if you have more than one language enabled).' mod='export_catalog'}
						{else}
		                    {l s='Last part of the export model creation. In Prestashop, you don\'t have one list of products and one price by product. Prices can be customised by group, shop, countries. Same thing for categories.' mod='export_catalog'}
		                    <p>{l s='Here you can choose the context of the export (and the exported images size because I didn\'t found where to put it else).' mod='export_catalog'}</p>
		                    <p>{l s='When you have finish click on the "Save" button near to export model name.' mod='export_catalog'}</p>
		                {/if}
	                </div>

					<div class="form-group clear">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_size">{l s='Exported image size' mod='export_catalog'}</label>
						<div class="margin-form">
							<select  class="input_large" name="model[datas][size]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_size">
								{html_options options=$images_types selected=$model->datas.size}
							</select>
						</div>
					</div>

					{if $groups}
						{if count($groups) == 1}
							{foreach from=$groups item=group key=id_group}
								<input type="hidden" value="{$id_group|escape:'htmlall':'UTF-8'}" name="model[datas][id_group]">
							{/foreach}
						{else}
							<div class="form-group clear">
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_group">{l s='Group' mod='export_catalog'}</label>
								<div class="margin-form">
									<select  class="input_large" name="model[datas][id_group]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_id_group">
										{html_options options=$groups selected=$model->datas.id_group}
									</select>
								</div>
							</div>
						{/if}
					{else}
						<input type="hidden" value="0" name="model[datas][id_group]">
					{/if}

					{if count($languages) == 1}
						{foreach from=$languages item=lang key=id_lang}
							<input type="hidden" value="{$id_lang|escape:'htmlall':'UTF-8'}" name="model[datas][id_lang]">
						{/foreach}
					{else}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_lang">{l s='Language' mod='export_catalog'}</label>
							<div class="margin-form">
								<select  class="input_large" name="model[datas][id_lang]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_id_lang">
									{html_options options=$languages selected=$model->datas.id_lang}
								</select>
							</div>
						</div>
					{/if}

					{if count($currencies) == 1}
						{foreach from=$currencies item=currency key=id_currency}
							<input type="hidden" value="{$id_currency|escape:'htmlall':'UTF-8'}" name="model[datas][id_currency]">
						{/foreach}
					{else}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_currency">{l s='Currency' mod='export_catalog'}</label>
							<div class="margin-form">
								<select  class="input_large" name="model[datas][id_currency]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_id_currency">
									{html_options options=$currencies selected=$model->datas.id_currency}
								</select>
							</div>
						</div>
					{/if}

					{if count($countries) == 1}
						{foreach from=$countries item=country key=id_country}
							<input type="hidden" value="{$id_country|escape:'htmlall':'UTF-8'}" name="model[datas][id_country]">
						{/foreach}
					{else}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_country">{l s='Country for shipping calculation' mod='export_catalog'}</label>
							<div class="margin-form">
								<select  class="input_large" name="model[datas][id_country]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_id_country">
									{html_options options=$countries selected=$model->datas.id_country}
								</select>
							</div>
						</div>
					{/if}

					<div class="form-group clear {if $model->datas.simple_shipping}{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on{/if}">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_simple_shipping">{l s='Use fast method for shipping cost' mod='export_catalog'}</label>
						<div class="margin-form">
							<span class="radio">
								<input type="radio" name="model[datas][simple_shipping]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_simple_shipping_on" value="1" {if $model->datas.simple_shipping}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_simple_shipping_on">{l s='Yes' mod='export_catalog'}</label>
								<input type="radio" name="model[datas][simple_shipping]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_simple_shipping_off" value="0" {if !$model->datas.simple_shipping}checked="checked"{/if} />
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_simple_shipping_off">{l s='No' mod='export_catalog'}</label>
							</span>
							<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#simple_shipping">?</a>
							<p {if $version_16}class="help-block"{/if}>{l s='Faster but may be wrong for complicated cart rules' mod='export_catalog'}</p>
						</div>
					</div>

					{if $shops}
						{if count($shops) == 1}
							{foreach from=$shops item=shop key=id_shop}
								<input type="hidden" value="{$id_shop|escape:'htmlall':'UTF-8'}" name="model[datas][id_shop]">
							{/foreach}
						{else}
							<div class="form-group clear">
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_shop">{l s='Shop' mod='export_catalog'}</label>
								<div class="margin-form">
									<select  class="input_large" name="model[datas][id_shop]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_id_shop">
										{html_options options=$shops selected=$model->datas.id_shop}
									</select>
								</div>
							</div>
						{/if}
					{else}
						<input type="hidden" value="0" name="model[datas][id_shop]">
					{/if}
				</div>
			</div>
		</form>
	</div>
	</div>
{/if}
{if $writable && $model->filename}
	<div id="tabCron" class="col-lg-10 col-md-9">
	<div class="panel">
        <h3 class="tab"> <i class="icon-calendar"></i> {l s='Scheduled exports' mod='export_catalog'}</h3>
		<form action="{$module_url|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
			<input type="hidden" id="{$module_short_name|escape:'htmlall':'UTF-8'}_current_export" value="{$export->filename|escape:'htmlall':'UTF-8'}">
			{if $export->filename}
				<div class="form-group clear">
					<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export">{l s='Scheduled export' mod='export_catalog'}</label>
					<div class="margin-form actions_list">
						<input type="submit" class="hidden" name="actionSaveExport" value="{l s='Save' mod='export_catalog'}"/>
						<select class="input_large" name="{$module_short_name|escape:'htmlall':'UTF-8'}_export" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export" style="margin-bottom: 1em">
							{html_options options=$export->getFiles() selected=$export->filename}
						</select>
						<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#export">?</a>
						<div style="display: inline-block; vertical-align: middle">
							<button type="submit" class="samdha_button action" name="" title="{l s='Select an action' mod='export_catalog'}" value="1"/>{l s='Action' mod='export_catalog'}</button>
							<button class="action_select">{l s='Select an action' mod='export_catalog'}</button>
						</div>
						<ul>
							<li>
								<a href="#" rel="actionLoadExport" id="export_load_button" title="{l s='Load the selected export for modification.' mod='export_catalog'}"><span class="ui-icon ui-icon-pencil"></span> {l s='Modify' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionDuplicateExport" id="export_duplicate_button" title="{l s='Create a copy of the selected export and open it for modification.' mod='export_catalog'}"><span class="ui-icon ui-icon-copy"></span> {l s='Duplicate' mod='export_catalog'}</a>
							</li>
							<li>
								<a href="#" rel="actionDeleteExport" id="export_delete_button" title="{l s='Delete the selected model. Warning, this is definive.' mod='export_catalog'}" alt_title="{l s='Delete the selected export. Warning, this is definive.' mod='export_catalog'}"><span class="ui-icon ui-icon-trash"></span> {l s='Delete' mod='export_catalog'}</a>
							</li>
						</ul>
						<div style="display: inline-block; vertical-align: middle">
							<input type="submit" class="samdha_button ui-button-primary" name="actionRunExport" value="{l s='Run now' mod='export_catalog'}" id="export_run_button" title="{l s='Run this export manually.' mod='export_catalog'}"/>
						</div>
					</div>
				</div>
				<hr/>

                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
					{if !$module_config._cron || !$cron_exists || !task_created}
	                    {l s='If you haven\'t do it yet, you have to go to the "Parameters" tab in order to finish the module configuration.' mod='export_catalog'}
	                    <p>{l s='If you want to create another scheduled export use the "Duplicate" button and modify the copy.' mod='export_catalog'}</p>
	                {else}
	                	{l s='If you want to create another scheduled export use the "Duplicate" button and modify the copy.' mod='export_catalog'}
	                {/if}
                </div>

				<h3>{l s='Export modification' mod='export_catalog'}</h3>
			{else}
                <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
                    {l s='Lets create a scheduled export now. That\'s easier than an export model.' mod='export_catalog'}
                    <p>{l s='You will have to indicate the model to use, when and what to do with the created file. You can send it by email and/or save it on a folder of your server.' mod='export_catalog'}</p>
                    <p>{l s='When every thing is ok click on "Save", I will explain you the last step of the configuration. Unfortunately it\'s not the easiest.' mod='export_catalog'}</p>
                </div>
			{/if}

			<div class="form-group clear">
				<label for="{$module_short_name|escape:'htmlall':'UTF-8'}export_name">{l s='Export name' mod='export_catalog'}</label>
				<div class="margin-form">
					<input type="text" class="text" name="export[name]" id="{$module_short_name|escape:'htmlall':'UTF-8'}export_name" value="{if $export->name}{$export->name|escape:'htmlall':'UTF-8'}{else}{l s='New export' mod='export_catalog'}{/if}" />
					<input type="submit" class="samdha_button" name="actionSaveExport" value="{l s='Save' mod='export_catalog'}" id="export_save_button" title="{l s='Save the current export.' mod='export_catalog'}"/>
				</div>
			</div>

			<div class="accordion">
				<h3 class="modal-title">{l s='Parameters' mod='export_catalog'}</h3>
				<div>
					<div class="form-group clear">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_model">{l s='Model' mod='export_catalog'}</label>
						<div class="margin-form">
							<select name="export[datas][model]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_model">
								{html_options options=$model->getFiles() selected=$export->datas.model}
							</select>
						</div>
					</div>

					{if empty($export->datas.days)}
						<div class="form-group clear">
							<label>{l s='Export every days' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="export_every_days" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_days_on" value="1" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_days_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="export_every_days" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_days_off" value="0" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_days_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_every_days_off">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_days">{l s='Export days' mod='export_catalog'}</label>
						<div class="margin-form">
							<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_days" multiple="multiple" size="7" name="export[datas][days][]" class="nochosen">
								{html_options options=$days selected=$export->datas.days}
							</select>
						</div>
					</div>

					{if (count($export->datas.hours) == 1) && (count($export->datas.minutes) == 1)}
						<div class="form-group clear">
							<label>{l s='Export once a day' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="export_once_day" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_on" value="1" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="export_once_day" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_off" value="0" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_on">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_hour">{l s='Export hour' mod='export_catalog'}</label>
							<div class="margin-form">
								<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_hour" class="input_tiny">
									{if empty($export->datas.hours)}
										{html_options options=$hours selected=0}
									{else}
										{html_options options=$hours selected=$export->datas.hours[0]}
									{/if}
								</select>
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_minute" class="t"/>:</label>
								<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_minute" class="input_tiny">
									{if empty($export->datas.minutes)}
										{html_options options=$minutes selected=0}
									{else}
										{html_options options=$minutes selected=$export->datas.minutes[0]}
									{/if}
								</select>
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#hour">?</a>
							</div>
						</div>
					{/if}

					<div class="{$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_off">
						{if count($export->datas.hours) == 0}
							<div class="form-group clear">
								<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours">{l s='Export every hours' mod='export_catalog'}</label>
								<div class="margin-form">
									<span class="radio">
										<input type="radio" name="export_every_hours" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_on" value="1" checked="checked" />
										<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_on">{l s='Yes' mod='export_catalog'}</label>
										<input type="radio" name="export_every_hours" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_off" value="0" />
										<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_off">{l s='No' mod='export_catalog'}</label>
									</span>
								</div>
							</div>
						{/if}

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_off">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_hours">{l s='Export hours' mod='export_catalog'}</label>
							<div class="margin-form">
								<div class="{$module_short_name|escape:'htmlall':'UTF-8'}_export_every_hours_off">
									<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_hours"multiple="multiple" size="12" name="export[datas][hours][]" class="nochosen">
										{html_options options=$hours selected=$export->datas.hours}
									</select>
									<p {if $version_16}class="help-block"{/if}>{l s='Click on the wanted hours.' mod='export_catalog'}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_once_day_off">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_minutes">{l s='Export minutes' mod='export_catalog'}</label>
						<div class="margin-form">
							<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_minutes" multiple="multiple" size="12" name="export[datas][minutes][]" class="nochosen">
								{html_options options=$minutes selected=$export->datas.minutes}
							</select>
							<p {if $version_16}class="help-block"{/if}>{l s='Click on the wanted minutes.' mod='export_catalog'}</p>
						</div>
					</div>
					{if isset($export->datas.last_run)}
						<div class="form-group clear">
							<label>{l s='Last export date' mod='export_catalog'}</label>
							<div class="margin-form">
								{$export->last_run_formated|escape:'htmlall':'UTF-8'} {l s='(server time)' mod='export_catalog'}
							</div>
						</div>
					{/if}
					{if isset($export->datas.next_run)}
						<div class="form-group clear">
							<label>{l s='Next export date' mod='export_catalog'}</label>
							<div class="margin-form">
								{$export->next_run_formated|escape:'htmlall':'UTF-8'} {l s='(server time)' mod='export_catalog'}
							</div>
						</div>
					{/if}
				</div>

				<h3 class="modal-title">{l s='Action' mod='export_catalog'}</h3>
				<div>
					{if count($export->datas.employees) == 0}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_send_mail">{l s='Send by mail' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="export_send_mail" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_send_mail_on" value="1" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_send_mail_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="export_send_mail" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_send_mail_off" value="0" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_send_mail_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_send_mail_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_id_employees">{l s='Send file to' mod='export_catalog'}</label>
						<div class="margin-form">
							<select id="{$module_short_name|escape:'htmlall':'UTF-8'}_employees" multiple="multiple" size="5" name="export[datas][employees][]" class="nochosen">
								{html_options options=$employees selected=$export->datas.employees}
							</select>
							<p {if $version_16}class="help-block"{/if}>{l s='Will send the generated file by email to the selected employees.' mod='export_catalog'}</p>
						</div>
					</div>

					{if $export->datas.folder == ''}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_save">{l s='Save on server' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="export_save" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_save_on" value="1" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_save_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="export_save" id="{$module_short_name|escape:'htmlall':'UTF-8'}_export_save_off" value="0" checked="checked" />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_export_save_off">{l s='No' mod='export_catalog'}</label>
								</span>
							</div>
						</div>
					{/if}

					<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_export_save_on">
						<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_folder"> {l s='Save in folder' mod='export_catalog'}</label>
						<div class="margin-form">
							<input type="text" value="{$export->datas.folder|escape:'htmlall':'UTF-8'}" name="export[datas][folder]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_folder" />
							<div id="jqueryFileTree_div"></div>
							<p {if $version_16}class="help-block"{/if}>{l s='Will save the generated file in the selected folder. This folder must be writable (not red).' mod='export_catalog'}</p>
							<p {if $version_16}class="help-block"{/if} id="{$module_short_name|escape:'htmlall':'UTF-8'}_url_preview" {if $export->datas.folder === ''}style="display: none"{/if}>{l s='Exported file URL will be : ' mod='export_catalog'} <a href="">{$shop_url|trim:'/'|escape:'htmlall':'UTF-8'}<span id="{$module_short_name|escape:'htmlall':'UTF-8'}_folder_preview">{$export->datas.folder|escape:'htmlall':'UTF-8'}</span><span id="{$module_short_name|escape:'htmlall':'UTF-8'}_filename_preview"></span></a></p>
						</div>
					</div>

					{if $internet_access}
						<div class="form-group clear">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp">{l s='Send by FTP' mod='export_catalog'}</label>
							<div class="margin-form">
								<span class="radio">
									<input type="radio" name="export[datas][ftp]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on" value="1" {if $export->datas.ftp}checked="checked"{/if} />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on">{l s='Yes' mod='export_catalog'}</label>
									<input type="radio" name="export[datas][ftp]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_off" value="0" {if !$export->datas.ftp}checked="checked"{/if} />
									<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_off">{l s='No' mod='export_catalog'}</label>
								</span>
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#ftp">?</a>
								<p {if $version_16}class="help-block"{/if}>{l s='Will save the generated file on an FTP server' mod='export_catalog'}</p>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_host">{l s='FTP host' mod='export_catalog'}</label>
							<div class="margin-form">
								<input type="text" value="{$export->datas.ftp_host|escape:'htmlall':'UTF-8'}" name="export[datas][ftp_host]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_host" />
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#ftp_host">?</a>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_login">{l s='FTP login' mod='export_catalog'}</label>
							<div class="margin-form">
								<input type="text" value="{$export->datas.ftp_login|escape:'htmlall':'UTF-8'}" name="export[datas][ftp_login]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_login" />
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#ftp_login">?</a>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_password">{l s='FTP password' mod='export_catalog'}</label>
							<div class="margin-form">
								<input type="password" value="{$export->datas.ftp_password|escape:'htmlall':'UTF-8'}" name="export[datas][ftp_password]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_password" />
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#ftp_password">?</a>
							</div>
						</div>

						<div class="form-group clear {$module_short_name|escape:'htmlall':'UTF-8'}_ftp_on">
							<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_folder">{l s='FTP folder' mod='export_catalog'}</label>
							<div class="margin-form">
								<input type="text" value="{$export->datas.ftp_folder|escape:'htmlall':'UTF-8'}" name="export[datas][ftp_folder]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_ftp_folder" />
								<a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="{$documentation_url|escape:'htmlall':'UTF-8'}#ftp_folder">?</a>
							</div>
						</div>
					{/if}
				</div>
			</div>
		</form>
	</div>
	</div>
{/if}
<div id="tabParameters" class="col-lg-10 col-md-9">
<div class="panel">
    <h3 class="tab"> <i class="icon-wrench"></i> {l s='Parameters' mod='export_catalog'}</h3>
	{if $export->filename && (!$module_config._cron || !$cron_exists || !$task_created)}
          <div class="{if $version_16 && $bootstrap}alert alert-info{else}hint solid_hint{/if}">
              {l s='To make the scheduled exports work, you use one of the following methods :' mod='export_catalog'}
			<ol style="margin-top: 1em">
				<li>
					{l s='Create a cron job:' mod='export_catalog'}  <a class="module_help" title="{l s='Click to see more informations about this element' mod='export_catalog'}" href="http://prestawiki.samdha.net/wiki/How-to:create_a_cron_task">?</a>
					<br/><pre>*/10 * * * * {$php_dir|escape:'htmlall':'UTF-8'} {$module_directory|escape:'htmlall':'UTF-8'}{$smarty.const.DIRECTORY_SEPARATOR|escape:'htmlall':'UTF-8'}cron.php token={$module_config._token|escape:'htmlall':'UTF-8'}</pre>
				</li>
				<li>
				{capture assign=cron_url}http:{$module_path|escape:'htmlall':'UTF-8'}cron.php?token={$module_config._token|escape:'htmlall':'UTF-8'}{/capture}

               {l s='Or visit this URL every ten minutes' mod='export_catalog'}
               <br/><pre>{$cron_url|escape:'htmlall':'UTF-8'}</pre><br/>
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAErklEQVQ4T6WUeUyTZxzHv+/bgx4cpbRccogtggULDFzGlITDRUycZMumuENlcyM7Y+YfMrNljUt2uLCZLDrj/nAJcyNhKnMhuuzQxTjnOMYAC6VQFMaxcbWF0tLjffd7CzgQNEt80qdp0t/zeb7P9/d9HgZ3GadMkG3TIVLGI0osgprhIQMDMc/Axfng9Iox0WrHeNErmCEEdyeGWYHLXjoGxcNRWA0e6SKWLbnRrSyRKDVqsBKW84xOeaanbhkz/DW0UesYh76nTsJ++TL8i1nLwAQN3aiFnhYVWvviD0qStsembq4AFHEAIwY8o4CjE10NR2b02pbPCXZu3IPO2N0YW6x8Cdi0F7Kq7UhiAyiy/qX/wLDzhAqRawB+lqZvThAjoi8p4JuFpfY1v0596SgfwJmWf2B+6HU4F1QvAQ99BY1ajDyLNfTo+h2fpjHqVVTnAlie5vwS+gmOlnES2o+BpW7/tC7B+kaAw49bj2NgwZLb4MJCiC++ihSRCE/Y+o3vppa9LGIkbhJHfRHTFOBCtQD10/TRTgEFui/WQ6f9oY7jA9WnW/FnhQme4MEWpJuehPTQHqxva2SO5GwqKGZS8oEQAssCYBbgQjGBeS9BZ8kSTwgwbof5p7qRtHWO3c23cH3Bjtvg70xQbHkARnO79DPjBkM2EvWAchaMkpotD8wpp8wFlRKUdxHYJQNGx2C52jaVaph81jKMKxn7YJ8/15xmIbdP5yOro0V6LDt3VS5iwwAVD0ZBjZN4aQrm0hBC5SV/faTWQU38exKWJoczNXPymfYhXMmpCDZQMGxuBK14AYbW3/Be3kb9VkRNA5FuMH6yTEFgJRUJbCEc/aQ4XkE2SIBJLczXBvrTslzPNXehcZkVVM66G5AoCsE2mzn247WPpEmhMFN0KZ4BIibOK6AYC3eNlxLYlQnrpTHoUm6e4MCdPH0VncuaJyz74xxU6RHItTSLPzKWbsmBJhRMyE1SNgTop4QTAjY5nUQL3p8CTCnQ2XBhItXo3O8M4GdNCYbnihalImgHRa7qbSSzLDb33NBUpz++SwmlFIyb7AgjD+jksFPT5CGUDBms357l1iR3f0LvR933VrQ/Whl8N4JjxSu9KR1GhsPzvQNrKtY+tpfyRQa7KBksiZHTtaYPP/ILOs43dKVlc2/aXbgWUwbBpNuP0TKwcFHqqqANZ1HS0yapXleaEM2ocinTWwF3ByBygHe3oPuCxafTuw9zPM6+fwo9pjpQh/8bK71uc9HbgCzWL3mpb1C5R1+cSjmmOf4NJSQX1uvddNvcHT7RzIHeaTRl7MDEYuiKVswXsKNnEKNiYeidzquXSkJD4xIiIZN64fdI4AxEQzr55WGxeOaLD2sweKfae4GDqh80gAtRHxwYcCdHx8UlISpGh7HBVnDeqUDSyIvGJjNsRaagBf/roV9yqqbz79hmVEUpUapQhKvUcDoc8Nst7qyCcgry3ceKHi8uP/TWgV937azIl8tliIiIgNfrh83y+1hBcZn2vsDx8fGa8vKdjRkZmas1Gi1stl6fQhG2o7JyX/19gYXFGo1mbWlp6ddarTa8trb28PDwcM29oMJ//wLNGcAmi6ehdgAAAABJRU5ErkJggg=="> {l s='Schedule it in one click with' mod='export_catalog'} <a style="text-decoration:underline" href="https://www.easycron.com/cron-job-scheduler?ref=10651&url={$cron_url|escape:'url':'UTF-8'}&specifiedBy=1&specifiedValue=7" target="_blank">EasyCron</a>  {l s='(it\'s free)' mod='export_catalog'}<br/><br/>
				</li>
		{if !$cron_exists}
				<li>
					{l s='Or install the free module' mod='export_catalog'} <a href="{l s='http://www.prestatoolbox.com/free-modules/115-crontab.html' mod='export_catalog'}" style="text-decoration: underline">{l s='\'Crontab for Prestashop\'' mod='export_catalog'}</a>
				</li>
		{/if}
			</ol>
          </div>
	{/if}

	<form action="{$module_url|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
		<div class="form-group clear">
			<label>{l s='Advanced parameters' mod='export_catalog'}</label>
			<div class="margin-form">
				<span class="radio">
					<input type="radio" name="setting[advanced]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on" value="1" {if $module_config.advanced}checked="checked"{/if} />
					<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on">{l s='Yes' mod='export_catalog'}</label>
					<input type="radio" name="setting[advanced]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_off" value="0" {if !$module_config.advanced}checked="checked"{/if} />
					<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_off">{l s='No' mod='export_catalog'}</label>
				</span>
			</div>
		</div>

		<div class="form-group clear {if $writable}{$module_short_name|escape:'htmlall':'UTF-8'}_advanced_on{/if}">
			<label for="{$module_short_name|escape:'htmlall':'UTF-8'}_directory"> {l s='Working directory' mod='export_catalog'}</label>
			<div class="margin-form">
				<input type="text" class="input_large" value="{$module_config._directory|escape:'htmlall':'UTF-8'}" name="setting[_directory]" id="{$module_short_name|escape:'htmlall':'UTF-8'}_directory" />
				<div id="jqueryFileTree_div2"></div>
				<p {if $version_16}class="help-block"{/if}>
					{l s='This folder must be writable (not red).' mod='export_catalog'}
					<a class="module_help" href="{$documentation_url|escape:'htmlall':'UTF-8'}#directory">?</a>
				</p>
			</div>
		</div>

		{if !$version_14}
			<div class="form-group clear">
				<label for="{$module_short_name|escape:'htmlall':'UTF-8'}host"> {l s='Shop domain' mod='export_catalog'}</label>
				<div class="margin-form">
					<input type="text" class="input_large" value="{$module_config.host|escape:'htmlall':'UTF-8'}" name="setting[host]" id="{$module_short_name|escape:'htmlall':'UTF-8'}host" />
				</div>
			</div>
		{/if}

		{if $cron_exists}
			<div class="form-group clear">
				<label>{l s='Use \'Crontab for Prestashop\' module' mod='export_catalog'}</label>
				<div class="margin-form">
					<span class="radio">
						<input type="radio" name="setting[_cron]" id="cron_on" value="1" {if $module_config._cron}checked="checked"{/if} />
						<label for="cron_on">{l s='Yes' mod='export_catalog'}</label>
						<input type="radio" name="setting[_cron]" id="cron_off" value="0" {if !$module_config._cron}checked="checked"{/if}/>
						<label for="cron_off">{l s='No' mod='export_catalog'}</label>
					</span>
					<a class="module_help" href="{$documentation_url|escape:'htmlall':'UTF-8'}#cron">?</a>
				</div>
			</div>
		{/if}
		<div style="clear: both"></div>

		<p><input type="submit" class="samdha_button" name="saveSettings" value="{l s='Save' mod='export_catalog'}" /></p>
	</form>
</div>
</div>
