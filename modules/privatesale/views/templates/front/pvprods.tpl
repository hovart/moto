{if isset($error)}
	{$error}
{else}

	<div class="breadcrumb">
	<a href="pvlist.php">{l s='Private Sale' mod='privatesale'}</a>
	<span class="navigation-pipe">&gt;</span>
	{$pvs_name}
	</div>

	<!-- Category image -->
	{if $category->id_image}
	<div class="align_center">
		<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" width="{$categorySize.width}" height="{$categorySize.height}" />
	</div>
	{/if}
	
	{if $products}
		{include file="$tpl_dir./product-list.tpl" products=$products}
	{/if}
	
{/if}