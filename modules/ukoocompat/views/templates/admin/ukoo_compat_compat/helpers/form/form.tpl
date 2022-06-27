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
    <form action="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}" id="ukoocompat_compat_form" class="defaultForm form-horizontal AdminUkooCompatCompat" method="post">
        {if $currentObject->id}
	        <input type="hidden" name="{$identifier|escape:'htmlall':'UTF-8'}" id="{$identifier|escape:'htmlall':'UTF-8'}" value="{$currentObject->id|intval}" />
        {else}
	        <input type="hidden" name="submitAddukoocompat_compat" value="1" />
        {/if}
        <input type="hidden" id="currentFormTab" name="currentFormTab" value="general" />
        <input type="hidden" id="currentToken" name="currentToken" value="{$currentToken|escape:'htmlall':'UTF-8'}" />
        <input type="hidden" id="currentIdLang" name="currentIdLang" value="{$current_id_lang|intval}" />

	    <div class="alert alert-info">
		    {l s='First, select the product you want, then, indicate the combination of criteria with which there is compatible.' mod='ukoocompat'}
	    </div>

	    <div class="form-group">
		    <label class="control-label col-lg-3 required" for="product_autocomplete_input">
				{l s='Product' mod='ukoocompat'}
		    </label>
		    <div class="col-lg-5">
			    <input type="hidden" name="id_product" id="id_product" value="{if isset($product)}{$product->id|intval}{/if}" />
			    <input type="hidden" name="nameProduct" id="nameProduct" value="{if isset($product)}{$product->name|escape:'html':'UTF-8'}{/if}" />
			    <div id="ajax_choose_product">
				    <div class="input-group">
					    <input type="text" id="product_autocomplete_input" name="product_autocomplete_input" />
					    <span class="input-group-addon"><i class="icon-search"></i></span>
				    </div>
			    </div>

			    <div id="divProduct">
				    {if isset($product)}
					    <div class="form-control-static">
						    <button type="button" class="btn btn-default delProduct" name="{$product->id|intval}">
							    <i class="icon-remove text-danger"></i>
						    </button>
						    {$product->name|escape:'html':'UTF-8'} {if !empty($product->reference)}({$product->reference|escape:'html':'UTF-8'}){/if}
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
            <a href="{$link->getAdminLink('AdminUkooCompatCompat')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='ukoocompat'}</a>
            <button type="submit" name="submitAddukoocompat_compatAndBackToParent" id="ukoocompat_compat_form_submit_btn" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='ukoocompat'}</button>
            <button type="submit" name="submitAddukoocompat_compatAndNew" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and new' mod='ukoocompat'}</button>
        </div>
    </form>
    <script type="text/javascript" src="../modules/ukoocompat/views/js/form.js"></script>
</div>