<fieldset>
	<legend>{l s='Global configuration' mod='totsplashscreen'}</legend>

	<table class="table">
		<tr>
			<th>{l s='Name' mod='totsplashscreen'}</th>
			<th>{l s='Template' mod='totsplashscreen'}</th>
			<th>{l s='Type of page' mod='totsplashscreen'}</th>
			<th>{l s='Begin date' mod='totsplashscreen'}</th>
			<th>{l s='End date' mod='totsplashscreen'}</th>
			<th>{l s='Cookie' mod='totsplashscreen'}</th>
			<th>{l s='Cookie for all' mod='totsplashscreen'}</th>
			<th>{l s='Action' mod='totsplashscreen'}</th>
		</tr>
		{foreach from=$totsplashscreens item=splashscreen}
			<tr>
				<td>
					{$splashscreen.splashscreen}
				</td>
				<td>
					{$splashscreen.template}
				</td>
				<td>
					{$splashscreen.type}
				</td>
				<td>
					{$splashscreen.date_start|truncate:12:""}
				</td>
				<td>
					{$splashscreen.date_end|truncate:12:""}
				</td>
				<td>
					<a href="{$url}&cleanmycookie={$splashscreen.id_totsplashscreen}">			
						<input type="button" class="button" value="{l s='Clean my cookie' mod='totsplashscreen'}" />
					</a>
				</td>
				<td>
					<a href="{$url}&cleancookie={$splashscreen.id_totsplashscreen}">
						<input type="button" class="button" value="{l s='Clean cookie for everyone' mod='totsplashscreen'}">
					</a>
				</td>
				<td>
					<a href="{$url}&editTotsplashscreen={$splashscreen.id_totsplashscreen}">
						<img src="../img/admin/edit.gif" alt="">
					</a>
					<a href="{$url}&deleteSplashScreen={$splashscreen.id_totsplashscreen}">
						<img src="../img/admin/delete.gif" />
					</a>
				</td>
			</tr>
		{/foreach}
	</table>

	<a href="{$url}&newTotsplashscreen=1" style="margin-top:10px;display:block;">
		<input type="button" class="button" value="{l s='Add new splash screen' mod='totsplashscreen'}">
	</a>
</fieldset>

<fieldset style="margin-top:10px">
	<legend>{l s='Templates' mod='totsplashscreen'}</legend>
	<table class="table">
		<tr>
			<th>{l s='Name' mod='totsplashscreen'}</th>
			<th>{l s='Action' mod='totsplashscreen'}</th>
		</tr>
		{foreach from=$tottemplates item=template}
			<tr>
				<td>
					{$template.name}
				</td>
				<td>
					<a href="{$url}&viewTotsplashscreenTemplate={$template.id_totsplashscreen_template}">
						<img src="../img/admin/details.gif" alt="" title="{l s='Previsualize' mod='totsplashscreen'}">
					</a>
					<a href="{$url}&editTotsplashscreenTemplate={$template.id_totsplashscreen_template}">
						<img src="../img/admin/edit.gif" alt="" title="{l s='Modify' mod='totsplashscreen'}" >
					</a>
					<a href="{$url}&deleteTemplate={$template.id_totsplashscreen_template}">
						<img src="../img/admin/delete.gif" title="{l s='Delete' mod='totsplashscreen'}"/>
					</a>
				</td>
			</tr>
		{/foreach}
	</table>
	<a href="{$url}&newTotsplashscreenTemplate=1"  style="margin-top:10px;display:block;">
		<input type="button" class="button" value="{l s='Add new template' mod='totsplashscreen'}">
	</a>
</fieldset>

<br />
<fieldset class="width6">
	<legend><img src="{$path}logo.gif">{l s='Help' mod='totsplashscreen'}</legend>
	<p>
		- {l s='For create a Splash Screen, you need to create a template first.' mod='totsplashscreen'}<br />
		- {l s='A template can be used several times.' mod='totsplashscreen'}<br />
		- {l s='If you don\'t see the Splash Screen, make sure to verify the beginning date, the ending date and to delete your cookie.' mod='totsplashscreen'}<br />
		- {l s='You can change all texts shown in the Splash Screen module using translations tool : ' mod='totsplashscreen'} <a style="text-decoration:underline;" target="_blank" href="index.php?tab=AdminTranslations&token={Tools::getAdminTokenLite('AdminTranslations')}&type=modules&lang={$iso}#totsplashscreen">{l s='here' mod='totsplashscreen'}</a><br /><br />
	</p>
</fieldset>
{if isset($totSplashScreen['preview']) && $totSplashScreen['preview'] == true}
	{include file="../hook/totsplashscreen.tpl"}
{/if}