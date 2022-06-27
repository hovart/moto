{**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2016
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 *}

{if !isset($ajax_reload)}
<div id="ukoocompat_search_block_{$search->id|intval}" class="block ukoocompat_search_block" style="clear: both;">
	<h4 class="title_block">{$search->title|escape}</h4>
	<div class="block_content">
{/if}
        <div class="loader">
            <div class="icon-refresh icon-spin"></div>
        </div>
        <form id="ukoocompat_search_block_form_{$search->id|intval}" action="{$form_action|escape}" method="get" class="ukoocompat_search_block_form{if $search->dynamic_criteria} dynamic_criteria{/if}">
			<input type="hidden" name="id_search" value="{$search->id|intval}" />
			<input type="hidden" name="id_lang" value="{$search->current_id_lang|intval}" />
			{foreach from=$search->filters item=filter}
                <div class="ukoocompat_search_block_filter filter_{$filter->id}{if isset($filter->disabled) && $filter->disabled|intval == 1} disabled{/if}">
                    {if isset($filter->display_type) && $filter->display_type != 'select'}
                        <span class="ukoocompat_search_block_filter_title">{$filter->name|escape}</span>
                    {/if}
                    <div class="ukoocompat_search_block_filter_filter">
                        {if $filter->display_type == 'radio'}
                            {include file='./search-block-radio.tpl'}
                        {else}
                            {include file='./search-block-select.tpl'}
                        {/if}
                    </div>
                </div>
            {/foreach}
            <div class="ukoocompat_search_block_button">
                {*{if $search->dynamic_criteria}*}
                    {*<button id="ukoocompat_search_block_reset_{$search->id|intval}" type="button" data-form-id="{$search->id|intval}" name="ukoocompat_search_reset" class="button btn btn-default button-medium ukoocompat_search_reset"{if !$display_reset} style="display:none;"{/if}>*}
                        {*<span>{l s='Reset' mod='ukoocompat'}</span>*}
                    {*</button>*}
                {*{/if}*}
                <button id="ukoocompat_search_block_submit_{$search->id|intval}" type="submit" name="ukoocompat_search_submit" class="button btn btn-default button-medium">
					<span>{l s='Search' mod='ukoocompat'}</span>
				</button>
			</div>
            <input type="hidden" id="ukoocompat_page_name" name="page_name" value="{$page_name|escape}"/>
            {if !$is_rewrite_active}
                <input type="hidden" name="fc" value="module"/>
                <input type="hidden" name="module" value="ukoocompat"/>
                <input type="hidden" name="controller" value="{$search->controller|escape}"/>
            {/if}
        </form>
        {if !isset($ajax_reload)}
    </div>
</div>
{if isset($search->display_alias_search_block) && $search->display_alias_search_block}
    {include file='./search-block-alias.tpl'}
{/if}
{/if}