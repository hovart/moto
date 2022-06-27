{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<div class="form-group">
	<label class="control-label col-lg-3 required">
		<span class="label-tooltip" data-toggle="tooltip"
		      title="{l s='This will be display in your search block' mod='ukoocompat'}">
			{l s='Display Name' mod='ukoocompat'}
		</span>
	</label>
	<div class="col-lg-8">
		{foreach from=$languages item=language}
			{if $languages|count > 1}
				<div class="row">
				<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
				<div class="col-lg-9">
			{/if}
            <input id="name_{$language.id_lang|intval}" class="name_by_lang" type="text" name="name_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'name', $language.id_lang|intval)|escape:'html':'UTF-8'}">
			{if $languages|count > 1}
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{$language.iso_code|escape:'htmlall':'UTF-8'}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=language}
							<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a></li>
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
    <label class="control-label col-lg-3">
        {l s='Display search filter' mod='ukoocompat'}
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="active" id="active_on" value="1" {if $currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
			<label class="t" for="active_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="active" id="active_off" value="0" {if !$currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
			<label class="t" for="active_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Display type' mod='ukoocompat'}
    </label>
    <div class="col-lg-2">
        <select name="display_type" class="fixed-width-xl" id="display_type">
            {foreach from=$display_type item=type}
                <option value="{$type.id|escape:'htmlall':'UTF-8'}"
                        {if $currentTab->getFieldValue($currentObject, 'display_type') == $type.id}
                            selected="selected"
                        {/if}
                        >
                    {$type.name|escape:'html':'UTF-8'}
                </option>
            {/foreach}
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Order by' mod='ukoocompat'}
    </label>
    <div class="col-lg-2">
        <select name="order_by" class="fixed-width-xl" id="order_by">
            {foreach from=$order_by item=order}
                <option value="{$order.id|escape:'htmlall':'UTF-8'}"
                        {if $currentTab->getFieldValue($currentObject, 'order_by') == $order.id}
                            selected="selected"
                        {/if}
                        >
                    {$order.name|escape:'html':'UTF-8'}
                </option>
            {/foreach}
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Order way' mod='ukoocompat'}
    </label>
    <div class="col-lg-2">
        <select name="order_way" class="fixed-width-xl" id="order_way">
            {foreach from=$order_way item=order}
                <option value="{$order.id|escape:'htmlall':'UTF-8'}"
                        {if $currentTab->getFieldValue($currentObject, 'order_way') == $order.id}
                            selected="selected"
                        {/if}
                        >
                    {$order.name|escape:'html':'UTF-8'}
                </option>
            {/foreach}
        </select>
    </div>
</div>