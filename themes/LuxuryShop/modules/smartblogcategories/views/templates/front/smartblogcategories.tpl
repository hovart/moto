{if isset($categories) AND !empty($categories)}
<div id="category_blog_block_left" class="block blogModule">
    <h4 class="title_block"><a title="{l s='Blog Categories' mod='smartblogcategories'}" href="{smartblog::GetSmartBlogLink('smartblog_list')}">{l s='Blog Categories' mod='smartblogcategories'}</a></h4>
    <div class="block_content list-block">
        <ul>
            {foreach from=$categories item="category"}
                {assign var="options" value=null}
                {$options.id_category = $category.id_smart_blog_category}
                {$options.slug = $category.link_rewrite}
                <li>
                    <a title="{$category.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_category',$options)}">{$category.meta_title} [{$category.count}]</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}