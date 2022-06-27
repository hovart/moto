{if isset($posts) AND !empty($posts)}
<div id="articleRelated" class="box">
    <h3 class="subheading">{l s='Related Posts' mod='smartblogrelatedposts'}</h3>
    <div class="sdsbox-content"> 
        <ul>
            {foreach from=$posts item="post"}
                {assign var="options" value=null}
                {$options.id_post= $post.id_smart_blog_post}
                {$options.slug= $post.link_rewrite}
                <li>
                   <a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.meta_title}</a>
                </li> 
            {/foreach}
        </ul>
    </div>
</div>
{/if}