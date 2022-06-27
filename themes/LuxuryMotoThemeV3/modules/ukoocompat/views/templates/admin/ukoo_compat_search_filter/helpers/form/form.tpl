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
<div class="panel" id="ukoocompat_search_filter_form_panel">
	<h3><i class="{$form.legend.icon|escape:'htmlall':'UTF-8'}"></i> {$form.legend.title|escape:'htmlall':'UTF-8'}</h3>
	<div class="productTabs">
		<ul class="tab nav nav-tabs">
			<li class="tab-row">
				<a class="tab-page" id="search_filter_link_display" href="javascript:displaySearchFilterTab('display');"><i class="icon-eye"></i> {l s='Display' mod='ukoocompat'}</a>
			</li>
			<li class="tab-row">
				<a class="tab-page" id="search_filter_link_group" href="javascript:displaySearchFilterTab('group');"><i class="icon-tags"></i> {l s='Criteria groups' mod='ukoocompat'}</a>
			</li>
		</ul>
	</div>
	<form action="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}" id="ukoocompat_search_filter_form" class="defaultForm form-horizontal AdminUkooCompatSearchFilter" method="post">
        <input type="hidden" id="groupToken" name="groupToken" value="{$groupToken|escape:'htmlall':'UTF-8'}" />
        <div id="search_filter_display" class="panel search_filter_tab">
			{include file='./display.tpl'}
		</div>
		<div id="search_filter_group" class="panel search_filter_tab">
			{include file='./group.tpl'}
		</div>
		<div class="panel-footer">
			<button type="button" class="btn btn-default btn btn-default pull-right" name="submit"><i class="process-icon-save"></i> {l s='Save' mod='ukoocompat'}</button>
			<button type="button" class="btn btn-default btn btn-default" name="cancel"><i class="process-icon-cancel"></i> {l s='Cancel' mod='ukoocompat'}</button>
		</div>
	</form>
</div>