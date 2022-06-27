{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

<div class="panel" id="create_group_criteria_block">
    <div class="panel-heading">
        {if isset($group_edit) && $group_edit}
            <i class="icon-pencil"></i> {l s='Edit criteria group' mod='ukoocompat'}
        {else}
            <i class="icon-plus-sign"></i> {l s='New criteria group' mod='ukoocompat'}
        {/if}
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3 required">
            <span class="label-tooltip" data-toggle="tooltip"
                  title="{l s='Will be display in front-office' mod='ukoocompat'}">
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
                <input id="group_name_{$language.id_lang|intval}" class="group_name_by_lang" type="text" name="group_name_{$language.id_lang|intval}" value="{if isset($group->name[$language.id_lang|intval])}{$group->name[$language.id_lang|intval]|escape}{/if}">
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
        <label class="control-label col-lg-3">
            {l s='Display group' mod='ukoocompat'}
        </label>
        <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="group_active" id="group_active_on" value="1"{if isset($group->active) && $group->active|intval == 1} checked="checked"{/if} />
			<label class="t" for="group_active_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="group_active" id="group_active_off" value="0"{if !isset($group->active) || isset($group->active) && $group->active|intval == 0} checked="checked"{/if} />
			<label class="t" for="group_active_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3 required">
            {l s='Group elements' mod='ukoocompat'}
        </label>
        <div class="col-lg-2">
            <select name="group_selected" class="fixed-width-xl" id="group_selected" multiple>
                {foreach from=$currentObject->criteria item=criterion}
                    <option value="{$criterion.id|intval}"
                            {if isset($group->selected_array) && in_array($criterion.id, $group->selected_array)}
                                selected="selected"
                            {/if}
                            >
                        {$criterion.value[$language.id_lang|intval]|escape:'html':'UTF-8'}
                    </option>
                {/foreach}
            </select>
        </div>
    </div>

    <input type="hidden" id="id_search_filter" name="id_search_filter" value="{$currentObject->id|intval}" />
    <input type="hidden" id="id_group" name="id_group" value="{if isset($group->id)}{$group->id|intval}{else}0{/if}" />

    <div class="panel-footer">
        {*{if !empty($currentObject->groups)}*}
            <a href="javascript:void(0);" class="btn btn-default" id="cancel_group">
                <i class="icon-remove"></i> {l s='Cancel' mod='ukoocompat'}
            </a>
        {*{/if}*}
        <a href="javascript:void(0);" class="btn btn-default pull-right" id="save_group">
            <i class="icon-save"></i> {l s='Save group' mod='ukoocompat'}
        </a>
    </div>
</div>
