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
		<span class="label-tooltip" data-toggle="tooltip" title="{l s='Only used to managed in back-office' mod='ukoocompat'}">
			{l s='Internal Name' mod='ukoocompat'}
		</span>
    </label>
    <div class="col-lg-8">
        <input id="name" type="text" name="name" value="{$currentTab->getFieldValue($currentObject, 'name')|escape:'html':'UTF-8'}" />
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
		<span class="label-tooltip" data-toggle="tooltip"
              title="{l s='Will be display as block title in front-office' mod='ukoocompat'}">
			{l s='Front-Office Name' mod='ukoocompat'}
		</span>
    </label>
    <div class="col-lg-8">
        {foreach from=$languages item=language}
            {if $languages|count > 1}
                <div class="row">
                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_lang_default}style="display:none"{/if}>
                <div class="col-lg-9">
            {/if}
            <input id="title_{$language.id_lang|intval}" type="text"  name="title_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'title', $language.id_lang|intval)|escape:'html':'UTF-8'}">
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
    <label class="control-label col-lg-3 required">
        {l s='Hook' mod='ukoocompat'}
    </label>
    <div class="col-lg-2">
        <select name="id_hook" class="fixed-width-xl" id="id_hook">
            {foreach from=$allowed_hooks item=hook}
                <option value="{$hook.id|intval}"
                        {if $currentTab->getFieldValue($currentObject, 'id_hook') == $hook.id}
                            selected="selected"
                        {/if}
                        >
                    {$hook.name|escape:'html':'UTF-8'}
                </option>
            {/foreach}
        </select>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Display search block' mod='ukoocompat'}
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="active" id="active_on" value="1" {if $currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
			<label class="t" for="active_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="active" id="active_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
			<label class="t" for="active_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>