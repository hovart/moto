{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

{assign var=form value=$fields[0].form}
<div class="panel">
    <h3><i class="{$form.legend.icon|escape:'htmlall':'UTF-8'}"></i> {$form.legend.title|escape:'htmlall':'UTF-8'}</h3>
    <form action="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}" id="ukoocompat_alias_instance_form" class="defaultForm form-horizontal AdminUkooCompatAlias" method="post">
        {if $currentObject->id}
            <input type="hidden" name="{$identifier|escape:'htmlall':'UTF-8'}" id="{$identifier|escape:'htmlall':'UTF-8'}" value="{$currentObject->id|intval}" />
        {else}
            <input type="hidden" name="submitAddukoocompat_alias_instance" value="1" />
        {/if}
        <input type="hidden" id="currentFormTab" name="currentFormTab" value="general" />
        <input type="hidden" id="currentToken" name="currentToken" value="{$currentToken|escape:'htmlall':'UTF-8'}" />
        <input type="hidden" id="aliasToken" name="aliasToken" value="{$aliasToken|escape:'htmlall':'UTF-8'}" />
        <input type="hidden" id="currentIdLang" name="currentIdLang" value="{$current_id_lang|intval}" />

        <div class="alert alert-info">
            {l s='Give an alias (shortcut) to access a combination of criteria' mod='ukoocompat'}
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3 required" for="alias_autocomplete_input">
                {l s='Alias' mod='ukoocompat'}
            </label>
            <div class="col-lg-5">
                <input type="hidden" name="id_ukoocompat_alias" id="id_alias" value="{if isset($alias)}{$alias->id|intval}{/if}" />
                <input type="hidden" name="alias" id="alias" value="{if isset($alias)}{$alias->alias|escape:'html':'UTF-8'}{/if}" />
                <div id="ajax_choose_alias">
                    <div class="input-group">
                        <input type="text" id="alias_autocomplete_input" name="alias_autocomplete_input" />
                        <span class="input-group-addon"><i class="icon-search"></i></span>
                    </div>
                </div>

                <div id="divAlias">
                    {if isset($alias)}
                        <div class="form-control-static">
                            <button type="button" class="btn btn-default delAlias" name="{$alias->id|intval}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                            {$alias->alias|escape:'html':'UTF-8'} {if !empty($alias->id)}(#{$alias->id|intval}){/if}
                        </div>
                    {/if}
                </div>
            </div>
        </div>

        {foreach from=$filters item=filter}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {$filter.name|escape:'html':'UTF-8'}
                </label>
                <div class="col-lg-2">
                    <select name="id_ukoocompat_criterion[{$filter.id|intval}]" class="fixed-width-xl" id="id_ukoocompat_criterion_{$filter.id|intval}">
                        <option value="" >{l s='---' mod='ukoocompat'}</option>
                        <option value="*"{if isset($selected_criteria[$filter.id|intval]) && $selected_criteria[$filter.id|intval].id_ukoocompat_criterion == '*'} selected="selected"{/if}>{l s='All' mod='ukoocompat'}</option>
                        {foreach from=$filter.criteria item=criterion}
                            <option value="{$criterion.id|intval}"{if isset($selected_criteria[$filter.id|intval]) && $selected_criteria[$filter.id|intval].id_ukoocompat_criterion == $criterion.id|intval} selected="selected"{/if}>
                                {$criterion.value|escape:'html':'UTF-8'}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        {/foreach}

        <div class="panel-footer">
            <a href="{$link->getAdminLink('AdminUkooCompatAliasInstance')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='ukoocompat'}</a>
            <button type="submit" name="submitAddukoocompat_alias_instanceAndBackToParent" id="ukoocompat_alias_instance_form_submit_btn" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='ukoocompat'}</button>
            <button type="submit" name="submitAddukoocompat_alias_instanceAndNew" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and new' mod='ukoocompat'}</button>
        </div>
    </form>
    <script type="text/javascript" src="../modules/ukoocompat/views/js/form.js"></script>
</div>