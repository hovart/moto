<h2>{l s='Products by category slider' mod='productsbycategoryslider'}</h2>
<p>{l s='With this module, you can show different products of the same category, below a detail product, to invite your customers to discover them. You will find below the products list by category. You can disable products you don\'t want to display on detail product.' mod='productsbycategoryslider'}</p>
<form name="submit" id="submit" method="post">
	<fieldset>
		<legend><img src="../modules/productsbycategoryslider/logo.gif" alt="" title="" />{l s='Configuration' mod='productsbycategoryslider'}</legend>
		{foreach $categories as $category}
		<label>{$category.name}</label>
		<div class="margin-form">
			<table class="table" cellspacing=0 cellpadding=0 id="cases_{$category.id_category}">
				<tr>
					<th>ID</th>
					<th>{l s='Product' mod='productsbycategoryslider'}</th>
					<th width="60px">{l s='Desactivate' mod='productsbycategoryslider'}</th>
				</tr>
				<tr>
					<td></td>
					<td><b>{l s='Check all products' mod='productsbycategoryslider'}</b></td>
					<td style="text-align:center;">
						 <input type='checkbox' OnClick="checkAll({$category.id_category})" name="checkall_{$category.id_category}" id='checkall_{$category.id_category}'/>
					</td>
				</tr>
				{foreach $products as $product}
				{if $category.id_category == $product.id_category_default}
				<tr>
					<td>{$product.id_product}</td>
					<td>{$product.name}</td>
					<td style="text-align:center;"><input type="checkbox" name="id_product_{$product.id_product}" value="{$product.id_product}" {if $product.disabled == 1}checked="checked"{/if}/></td>
				</tr>
				{/if}
				{/foreach}
			</table>
			<p class="clear">{l s='You can desactivate the products you no want show on the product details for the category' mod='productsbycategoryslider'} <b>{$category.name}</b></p>
		</div>
		{/foreach}
		<center><input type="submit" name="submit" value="{l s='Save modifications' mod='productsbycategoryslider'}" class="button" /></center>
	</fieldset>
</form>
<script type="text/javascript">
{literal}
function checkAll(id)
{
    var cases = $("#cases_"+id).find(':checkbox');
    if($("input[name=checkall_"+id+"]:checked").val())
        cases.attr('checked', true);
    else
        cases.attr('checked', false);
}
{/literal}
</script>