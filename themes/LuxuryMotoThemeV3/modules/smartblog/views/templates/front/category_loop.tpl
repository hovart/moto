<div itemtype="#" itemscope="" class="sdsarticleCat">
	<div id="smartblogpost-{$post.id_post}" class="row">
		<div class="col-xs-12 col-sm-5">
			<div class="articleContent">
				<a itemprop="url" title="{$post.meta_title}" class="imageFeaturedLink">
				{assign var="activeimgincat" value='0'}
				{$activeimgincat = $smartshownoimg} 
				{if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
				<img itemprop="image" alt="{$post.meta_title}" src="{$modules_dir}smartblog/images/{$post.post_img}-single-default.jpg" class="imageFeatured">
				{/if}
				</a>
			</div>
		</div>
		<div class="col-xs-12 col-sm-7">
			<div class="sdsarticleHeader">
				{assign var="options" value=null}
				{$options.id_post = $post.id_post} 
				{$options.slug = $post.link_rewrite}
				<p class="sdstitle_block"><a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>{$post.meta_title}</a></p>
				{assign var="options" value=null}
				{$options.id_post = $post.id_post}
				{$options.slug = $post.link_rewrite}
				{assign var="catlink" value=null}
				{$catlink.id_category = $post.id_category}
				{$catlink.slug = $post.cat_link_rewrite}
				<div class="post-info">
					{if $smartshowauthor ==1}<div><i class="fa fa-user left"></i><span itemprop="author">{if $smartshowauthorstyle != 0}{$post.firstname} {$post.lastname}{else}{$post.lastname} {$post.firstname}{/if}</span></div>{/if}
					<div><i class="fa fa-calendar left"></i><span itemprop="dateCreated">{$post.created|date_format}</span></div>
					<div><i class="fa fa-tags left"></i><span itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catlink)}">{if $title_category != ''}{$title_category}{else}{$post.cat_name}{/if}</a></span></div>
					<div><i class="fa fa-comments left"></i><a title="{$post.totalcomment} {l s='Comments' mod='smartblog'}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}#articleComments">{$post.totalcomment} {l s='Comments' mod='smartblog'}</a></div>
					{if $smartshowviewed ==1}<div><i class="fa fa-eye left"></i>{l s='Views' mod='smartblog'} ({$post.viewed})</div>{/if}
				</div>
			</div>
			
			<div class="sdsarticle-des">
				<span itemprop="description"><div id="lipsum">{$post.short_description|truncate:200:'...'}</div></span>
			</div>
			<div class="sdsreadMore">
				{assign var="options" value=null}
				{$options.id_post = $post.id_post}  
				{$options.slug = $post.link_rewrite}  
				<a title="{l s='Read more' mod='smartblog'}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">+ {l s='Read more' mod='smartblog'}</a>
			</div>
		</div>
	</div>
</div>