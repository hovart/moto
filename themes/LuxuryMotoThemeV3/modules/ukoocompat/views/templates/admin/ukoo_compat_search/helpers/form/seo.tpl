{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<div class="alert alert-info">
	{l s='You can define a model for meta, title and content of the catalog view for compatible products.' mod='ukoocompat'} {l s='Use the "SEO tags" (eg. {FILTER:5}) in your models to generate unique texts for each search.' mod='ukoocompat'}
</div>

<div class="form-group">
    <label class="control-label col-lg-3 ">
		{l s='Catalog page meta title' mod='ukoocompat'}
    </label>
    <div class="col-lg-8">
        {foreach from=$languages item=language}
            {if $languages|count > 1}
                <div class="row">
                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
                <div class="col-lg-9">
            {/if}
            <input id="catalog_meta_title_{$language.id_lang|intval}" type="text"  name="catalog_meta_title_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'catalog_meta_title', $language.id_lang|intval)|escape:'html':'UTF-8'}">
            {if $languages|count > 1}
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {$language.iso_code|escape:'htmlall':'UTF-8'}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$languages item=language}
                            <li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                        {/foreach}
                    </ul>
                </div>
                </div>
                </div>
            {/if}
        {/foreach}
	    <p class="help-block">{l s='eg. "Find compatible products with' mod='ukoocompat'} {literal}{FILTER:1} {FILTER:2}{/literal}{l s='". Will be replace by "Find compatible products with' mod='ukoocompat'} Microsoft Windows 7{l s='".' mod='ukoocompat'}</p>
    </div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Catalog page meta description' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="catalog_meta_description_{$language.id_lang|intval}" type="text"  name="catalog_meta_description_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'catalog_meta_description', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Catalog page meta keywords' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="catalog_meta_keywords_{$language.id_lang|intval}" type="text"  name="catalog_meta_keywords_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'catalog_meta_keywords', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Catalog page title' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="catalog_title_{$language.id_lang|intval}" type="text"  name="catalog_title_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'catalog_title', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3" for="description_{$language.id_lang|intval}">
		<span class="label-tooltip" data-toggle="tooltip"
		      title="{l s='Appears in the body of the catalog view' mod='ukoocompat'}">
			{l s='Catalog page description' mod='ukoocompat'}
		</span>
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="translatable-field row lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
					<div class="col-lg-9">
			{/if}
						<textarea id="catalog_description_{$language.id_lang|intval}" name="catalog_description_{$language.id_lang|intval}" class="autoload_rte">{$currentTab->getFieldValue($currentObject, 'catalog_description', $language.id_lang|intval)|escape:'html':'UTF-8'}</textarea>
						<span class="counter" data-max="{if isset($max)}{$max|intval}{else}none{/if}"></span>
			{if $languages|count > 1}
					</div>
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							{$language.iso_code|escape:'htmlall':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=language}
								<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				</div>
			{/if}
		{/foreach}
		<script type="text/javascript">
			$(".textarea-autosize").autosize();
		</script>
	</div>
</div>

<hr />

<div class="alert alert-info">
	{l s='You can define a model for meta, title and content of the list for compatible products.' mod='ukoocompat'} {l s='Use the "SEO tags" (eg. {FILTER:5}) in your models to generate unique texts for each search.' mod='ukoocompat'}
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Listing page meta title' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="listing_meta_title_{$language.id_lang|intval}" type="text"  name="listing_meta_title_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'listing_meta_title', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
		<p class="help-block">{l s='eg. "Find compatible products with' mod='ukoocompat'} {literal}{FILTER:1} {FILTER:2}{/literal}{l s='". Will be replace by "Find compatible products with' mod='ukoocompat'} Microsoft Windows 7{l s='".' mod='ukoocompat'}<br />
        {l s='{CATEGORY} will be replaced with the category name.' mod='ukoocompat'}</p>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Listing page meta description' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="listing_meta_description_{$language.id_lang|intval}" type="text"  name="listing_meta_description_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'listing_meta_description', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Listing page meta keywords' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="listing_meta_keywords_{$language.id_lang|intval}" type="text"  name="listing_meta_keywords_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'listing_meta_keywords', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3 ">
		{l s='Listing page title' mod='ukoocompat'}
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<input id="listing_title_{$language.id_lang|intval}" type="text"  name="listing_title_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'listing_title', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
				</div>
			{/if}
		{/foreach}
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3" for="description_{$language.id_lang|intval}">
		<span class="label-tooltip" data-toggle="tooltip"
		      title="{l s='Appears in the body of the listing view' mod='ukoocompat'}">
			{l s='Listing page description' mod='ukoocompat'}
		</span>
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="translatable-field row lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
			<textarea id="listing_description_{$language.id_lang|intval}" name="listing_description_{$language.id_lang|intval}" class="autoload_rte">{$currentTab->getFieldValue($currentObject, 'listing_description', $language.id_lang|intval)|escape:'html':'UTF-8'}</textarea>
			<span class="counter" data-max="{if isset($max)}{$max|intval}{else}none{/if}"></span>
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
						{/foreach}
					</ul>
				</div>
				</div>
			{/if}
		{/foreach}
		<script type="text/javascript">
			$(".textarea-autosize").autosize();
		</script>
	</div>
</div>