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
    <div class="productTabs">
        <ul class="tab nav nav-tabs">
            <li class="tab-row">
                <a class="tab-page" id="search_link_general" href="javascript:displaySearchTab('general');"><i class="icon-info"></i> {l s='General' mod='ukoocompat'}</a>
            </li>
            <li class="tab-row">
                <a class="tab-page" id="search_link_behavior" href="javascript:displaySearchTab('behavior');"><i class="icon-eye"></i> {l s='Behavior' mod='ukoocompat'}</a>
            </li>
            <li class="tab-row">
                <a class="tab-page" id="search_link_association" href="javascript:displaySearchTab('association');"><i class="icon-link"></i> {l s='Associations' mod='ukoocompat'}</a>
            </li>
            {if $currentObject->id}
            <li class="tab-row">
                <a class="tab-page" id="search_link_filter" href="javascript:displaySearchTab('filter');"><i class="icon-tags"></i> {l s='Filters' mod='ukoocompat'}</a>
            </li>
            <li class="tab-row">
	            <a class="tab-page" id="search_link_seo" href="javascript:displaySearchTab('seo');"><i class="icon-cogs"></i> {l s='SEO' mod='ukoocompat'}</a>
            </li>
            {/if}
        </ul>
    </div>
    <form action="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}" id="ukoocompat_search_form" class="defaultForm form-horizontal AdminUkooCompatSearch" method="post">
        {if $currentObject->id}<input type="hidden" name="{$identifier|escape:'htmlall':'UTF-8'}" id="{$identifier|escape:'htmlall':'UTF-8'}" value="{$currentObject->id|intval}" />{/if}
        <input type="hidden" id="currentFormTab" name="currentFormTab" value="general" />
        <input type="hidden" id="currentToken" name="currentToken" value="{$currentToken|escape:'htmlall':'UTF-8'}" />
        <input type="hidden" id="searchFilterToken" name="searchFilterToken" value="{$searchFilterToken|escape:'htmlall':'UTF-8'}" />
        <input type="hidden" id="currentIdLang" name="currentIdLang" value="{$current_id_lang|intval}" />
        <div id="search_general" class="panel search_tab">
            {include file='./general.tpl'}
        </div>
        <div id="search_behavior" class="panel search_tab">
            {include file='./behavior.tpl'}
        </div>
        <div id="search_association" class="panel search_tab">
            {include file='./association.tpl'}
        </div>
        {if $currentObject->id}
        <div id="search_filter" class="panel search_tab">
            {include file='./filter.tpl'}
        </div>
        <div id="search_seo" class="panel search_tab">
	        {include file='./seo.tpl'}
        </div>
        {/if}
        <div class="panel-footer">
            <a href="{$link->getAdminLink('AdminUkooCompatSearch')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='ukoocompat'}</a>
            <button type="submit" name="submitAddukoocompat_searchAndBackToParent" id="ukoocompat_search_form_submit_btn" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='ukoocompat'}</button>
            <button type="submit" name="submitAddukoocompat_searchAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='ukoocompat'}</button>
        </div>
    </form>
    <script type="text/javascript" src="../modules/ukoocompat/views/js/form.js"></script>
	<script type="text/javascript">
		var iso = '{$iso|escape:'htmlall':'UTF-8'}';
		var pathCSS = '{$smarty.const._THEME_CSS_DIR_|escape:'htmlall':'UTF-8'}';
		var ad = '{$ad|escape:'htmlall':'UTF-8'}';
	</script>
</div>
{include file='modal.tpl' id='ukoocompat_modal'}