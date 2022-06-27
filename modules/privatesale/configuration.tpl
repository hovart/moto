<link type="text/css" href="../modules/privatesale/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="../js/jquery/jquery-ui-1.8.16.custom.min.js"></script>
{$addJS}
{literal}
<script type="text/javascript">
	$(function() {
		$( "#date_begin" ).datepicker({
			dateFormat: 'dd/mm/yy' 
			});
	});
	
	$(function() {
		$( "#date_finish" ).datepicker({
			dateFormat: 'dd/mm/yy'
			});
	});
	
	function runEffect() {
		$( "#msg_box" ).hide("blind");
	};
	
	setTimeout('runEffect();', 1500);
</script>
{/literal}

<h2>{l s='PrivateSale Configuration' mod='privatesale'}</h2>

{if isset($success)}
	<div id="msg_box" class="module_confirmation conf confirm">
		<img src="../img/admin/ok.gif" alt="ok" title="" />{$success}
	</div>
{/if}

{if isset($error)}
	<div id="msg_box" class="error">
		<img src="../img/admin/error.png" alt="warning" title="" />{$error}
	</div>
{/if}

{if !isset($smarty.get.pvs_action)}
<p><a href="{$link}&pvs_action=add"><img src="../img/admin/add.gif" /> {l s='Add a private sale' mod='privatesale'}</a></p>
{/if}

{if isset($smarty.get.pvs_action) || isset($smarty.get.pvs_edit)}
<fieldset>
	{if isset($pvs_edit)}
		<legend>{l s='Edit a Private Sale' mod='privatesale'}</legend>
	{else}
		<legend>{l s='Add a Private Sale' mod='privatesale'}</legend>
	{/if}
	
	<form method="post" action="{$link}" enctype="multipart/form-data">
		<table>
		<tr>
			<td>{l s='Private Sale Name' mod='privatesale'} :</td>
			<td><input type="text" name="add_name" style="width:450px;" {if isset($pvs_edit)}value="{$pvs_edit.name}"{/if}/></td>
		</tr>
		<tr>
			<td>{l s='Description' mod='privatesale'} :</td>
			<td><textarea name="add_description" rows="8" style="width:450px;">{if isset($pvs_edit)}{$pvs_edit.description}{/if}</textarea></td>
		</tr>
		<tr>
			<td>{l s='Select category' mod='privatesale'} :</td>
			<td><select name="add_cat">
				{foreach from=$list_data.categories item=temp}
					{if $temp.id_category != 1}
						<option value="{$temp.id_category}" {if isset($pvs_edit) && $temp.id_category == $pvs_edit.category}selected{/if}>{$temp.name}</option>
					{/if}
				{/foreach}
			</select></td>
		</tr>
		<tr>
			<td>{l s='Select group' mod='privatesale'} :</td>
			<td>
				{foreach from=$list_data.groups item=temp}
					<input type="checkbox" name="grp_{$temp.id_group}" value="{$temp.id_group}" 
					{if isset($pvs_edit) && in_array($temp.id_group, explode(',', $pvs_edit.groups))} checked="checked" {/if}/>{$temp.name}<br />
				{/foreach}
			</td>
		</tr>
		
		<tr>
			<td>{l s='Date begin' mod='privatesale'} :</td>
			<td><input type="text" id="date_begin" name="date_begin" {if isset($time_start)}value="{$time_start|date_format:'%d/%m/%Y'}"{/if}>
			{l s='Hour' mod='privatesale'} :
			<input type="text" name="hour_start_h" size="1" {if isset($time_start)}value="{$time_start|date_format:'%H'}"{/if} /> h
			<input type="text" name="hour_start_m" size="1" {if isset($time_start)}value="{$time_start|date_format:'%M'}"{/if} />
			</td>
		</tr>
		
		<tr>
			<td>{l s='Date finish' mod='privatesale'} :</td>
			<td><input type="text" id="date_finish" name="date_finish" {if isset($time_end)}value="{$time_end|date_format:'%d/%m/%Y'}"{/if} />
			{l s='Hour' mod='privatesale'} :
			<input type="text" name="hour_finish_h" size="1" {if isset($time_end)}value="{$time_end|date_format:'%H'}"{/if} /> h
			<input type="text" name="hour_finish_m" size="1" {if isset($time_end)}value="{$time_end|date_format:'%M'}"{/if} />
			</td>
		</tr>
		
		<tr>
			<td>{l s='Logo' mod='privatesale'} :</td>
			<td><input type="file" name="logo_upload" /></td>
		</tr>
		</table>
				
		<br />
		{if isset($pvs_edit)}
			<input type="hidden" name="edit_id" value="{$pvs_edit.id}" />
			<input type="submit" name="edit_pvsale" class="button" value="{l s='Edit' mod='privatesale'}" />
		{else}
			<input type="submit" name="add_pvsale" class="button" value="{l s='Add' mod='privatesale'}" />
		{/if}
	</form>
</fieldset>
{/if}

<br />

<fieldset><legend>{l s='Private Sale List' mod='privatesale'}</legend>
	{if empty($pvs_list)}
		{l s='No private sale' mod='privatesale'}
	{else}
		<table class="table tableDnD" style="text-align:center;">
			<tr>
				<td width="40px"><b>{l s='ID' mod='privatesale'}</b></td>
				<td width="150px"><b>{l s='Name' mod='privatesale'}</b></td>
				<td width="230px"><b>{l s='Description' mod='privatesale'}</b></td>
				<td width="100px"><b>{l s='Categorie' mod='privatesale'}</b></td>
				<td width="150px"><b>{l s='Group' mod='privatesale'}</b></td>
				<td width="70px"><b>{l s='Begin' mod='privatesale'}</b></td>
				<td width="70px"><b>{l s='End' mod='privatesale'}</b></td>
				<td width="100px"><b></b></td>
			</tr>
		{foreach from=$pvs_list item=temp}
			<tr>
				<td>{$temp.id}</td>
				<td>{if $temp.name == ''}--{else}{$temp.name}{/if}</td>
				<td>{if $temp.description == ''}--{else}{$temp.description}{/if}</td>
				<td>{$temp.cat_name}</td>
				<td>{if $temp.grp_name == ''}--{else}{$temp.grp_name}{/if}</td>
				<td>{$temp.time_start}</td>
				<td>{$temp.time_end}</td>
				<td><a href="{$link}&pvs_active={$temp.id}">{if $temp.active == 1}<img src="../img/admin/enabled.gif" />{else}<img src="../img/admin/disabled.gif" />{/if}</a><a href="{$link}&pvs_edit={$temp.id}"><img src="../img/admin/edit.gif" /></a><a href="{$link}&pvs_delete={$temp.id}"><img src="../img/admin/delete.gif" /></a> </td>
			</tr>
		{/foreach}
		</table>
	{/if}
</fieldset><br />