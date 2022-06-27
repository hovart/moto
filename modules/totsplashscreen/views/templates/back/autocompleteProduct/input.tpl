
<div id="choix" class="margin" style="height:20px;float:left">
	{if $autocompleteproduct != NULL}
		{$autocompleteproduct->id}-{$autocompleteproduct->name}
		<span onclick="delProductAuto('{$autocompleteproduct->id}');" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span><br />
	{/if}
</div>
<div id="choose" {if $autocompleteproduct != NULL}style="display:none;"{/if}>
	<input type="hidden" name="addProductAutoComplete" id="addProductAutoComplete" value="{if $autocompleteproduct != NULL}{$autocompleteproduct->id}{/if}" />
	<input type="text" name="addProductAutoCompleteName" id="addProductAutoCompleteName" placeholder="{l s='Product' mod='totquantity'}"/>
	<span onclick="addProductAuto();" style="cursor: pointer;">
		<img src="../img/admin/add.gif" alt="{l s='Add an item to the pack' mod='totquantity'}" title="{l s='Add an item to the pack' mod='totquantity'}" />
	</span>
</div>
<div style="clear:both"></div>


