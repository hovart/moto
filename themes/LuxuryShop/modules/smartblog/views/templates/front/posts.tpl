{capture name=path}<a title="{l s='All Blog News' mod='smartblog'}" href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='All Blog News' mod='smartblog'}</a><span class="navigation-pipe">{$navigationPipe}</span>{$meta_title}{/capture}
<div id="content" class="block">
	<div itemtype="#" itemscope="" id="sdsblogArticle" class="blog-post">
	<h1 class="page-heading">{$meta_title}</h1>
		<div class="post-info">
			{assign var="catOptions" value=null}
			{$catOptions.id_category = $id_category}
			{$catOptions.slug = $cat_link_rewrite}
			{if $smartshowauthor ==1}<div><i class="fa fa-user left"></i><span itemprop="author">{if $smartshowauthorstyle != 0}{$firstname} {$lastname}{else}{$lastname} {$firstname}{/if}</span></div>{/if}
			<div><i class="fa fa-calendar left"></i><span itemprop="dateCreated">{$created|date_format}</span></div>
			<div><i class="fa fa-tags left"></i><span itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catOptions)}">{$title_category}</a></span></div>
			<div><i class="fa fa-comments left"></i>{if $countcomment != ''}{$countcomment}{else}0{/if}&nbsp;{l s='Comments' mod='smartblog'}</div>
			<a title="" style="display:none" itemprop="url" href="#"></a>
		</div>
		<div itemprop="articleBody">
			<div id="lipsum" class="articleContent">
				{assign var="activeimgincat" value='0'}
				{$activeimgincat = $smartshownoimg} 
				{if ($post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
					<a id="post_images" href="{$modules_dir}/smartblog/images/{$post_img}-single-default.jpg"><img src="{$modules_dir}/smartblog/images/{$post_img}-single-default.jpg" alt="{$meta_title}"></a>
				{/if}
			</div>
			<div class="sdsarticle-des">
				{$content}
			</div>
			{if $tags != ''}
			<div class="sdstags-update">
				<ul class="tags">
					<li class="first">{l s='Tags' mod='smartblog'}:</li>
					{foreach from=$tags item=tag}
					    {assign var="options" value=null}
						{$options.tag = $tag.name}
						<li><a title="{$tag.name}" href="{smartblog::GetSmartBlogLink('smartblog_tag',$options)}">{$tag.name}</a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
		</div>
		 
		<div class="sdsarticleBottom">
			{$HOOK_SMART_BLOG_POST_FOOTER}
		</div>
	</div>

	{if $countcomment != ''}
	<div id="articleComments">
		<h3 class="subheading">{if $countcomment != ''}{$countcomment}{else}0{/if}&nbsp;{l s='Comments' mod='smartblog'}<span></span></h3>
		<div id="comments">
			{$i=1}
			{foreach from=$comments item=comment}
				{include file="./comment_loop.tpl" childcommnets=$comment}
			{/foreach}
		</div>
	</div>
	{/if}
</div>

{if Configuration::get('smartenablecomment') == 1}
{if $comment_status == 1}

	<div class="smartblogcomments box" id="respond">
		<h3 class="comment-reply-title subheading" id="reply-title">
			{l s='Leave a reply'  mod='smartblog'}
			<small style="float:right;">
                <a style="display: none;" href="#respond" id="cancel-comment-reply-link" rel="nofollow">{l s='Cancel reply'  mod='smartblog'}</a>
            </small>
		</h3>
		<div id="commentInput">
			<form action="" method="post" id="commentform">
				<div class="form-group">
					<label>{l s='Name' mod='smartblog'}<sup>*</sup></label>
					<input type="text" tabindex="1" class="inputName form-control" value="" name="name" />
				</div>
				<div class="form-group">
					<label>{l s='E-mail' mod='smartblog'}<sup>*</sup><span class="note">{l s='(Not Published)' mod='smartblog'}</span></label>
					<input type="text" tabindex="2" class="inputMail form-control" value="" name="mail" />
				</div>
				<div class="form-group">
					<label>{l s='Website' mod='smartblog'}<span class="note">(http://www.example.com)</span></label>
					<input type="text" tabindex="3" class="form-control" value="" name="website" />
				</div>
				<div class="form-group">
					<label>{l s='Comment' mod='smartblog'}<sup>*</sup></label>
					<textarea tabindex="4" class="inputContent form-control" name="comment"></textarea>
				</div>
				{if Configuration::get('smartcaptchaoption') == '1'}
				<div class="form-group captcha">
					<label>{l s='Enter Code' mod='smartblog'}<sup>*</sup></label>
					<img src="{$modules_dir}smartblog/classes/CaptchaSecurityImages.php?width=100&height=40&characters=5" />
					<input type="text" tabindex="" class="smartblogcaptcha form-control" value="" name="smartblogcaptcha" />
				</div>			
				{/if}		
				<input type='hidden' name='comment_post_ID' value='1478' id='comment_post_ID' />
				<input type='hidden' name='id_post' value='{$id_post}' id='id_post' />
				<input type='hidden' name='comment_parent' id='comment_parent' value='0' />
				
				<div class="submit">
					<input type="submit" name="addComment" id="submitComment" class="button" value="{l s='Submit' mod='smartblog'}" />
				</div>
			</form>
		</div>
	</div>

	<script type="text/javascript">

		$('#submitComment').bind('click',function(event) {
			event.preventDefault();
			var data = {
				'action':'postcomment', 
				'id_post':$('input[name=\'id_post\']').val(),
				'comment_parent':$('input[name=\'comment_parent\']').val(),
				'name':$('input[name=\'name\']').val(),
				'website':$('input[name=\'website\']').val(),
				'smartblogcaptcha':$('input[name=\'smartblogcaptcha\']').val(),
				'comment':$('textarea[name=\'comment\']').val(),
				'mail':$('input[name=\'mail\']').val()
			};
			$.ajax({
				url: baseDir + 'modules/smartblog/ajax.php',
				data: data,
				dataType: 'json',
				beforeSend: function() {
					$('.alert-success, .warning, .alert-danger').remove();
					$('#submitComment').attr('disabled', true);
					$('#commentInput').before('<div class="attention">Please wait!</div>');
				},
				complete: function() {
					$('#submitComment').attr('disabled', false);
					$('.attention').remove();
				},
				success: function(json) {
					if (json['error']) {
						$('#commentInput').before('<div class="alert alert-danger">' + json['error']['common'] + '</div>');
						if (json['error']['name']) {
							$('.inputName').after('<span class="error">' + json['error']['name'] + '</span>');
						}
						if (json['error']['mail']) {
							$('.inputMail').after('<span class="error">' + json['error']['mail'] + '</span>');
						}
						if (json['error']['comment']) {
							$('.inputContent').after('<span class="error">' + json['error']['comment'] + '</span>');
						}
						if (json['error']['captcha']) {
							$('.smartblogcaptcha').after('<span class="error">' + json['error']['captcha'] + '</span>');
						}
					}					
					if (json['success']) {
						$('input[name=\'name\']').val('');
						$('input[name=\'mail\']').val('');
						$('input[name=\'website\']').val('');
						$('textarea[name=\'comment\']').val('');
				 		$('input[name=\'smartblogcaptcha\']').val('');
						$('#commentInput').before('<div class="alert alert-success">' + json['success'] + '</div>');
						setTimeout(function(){
							$('.alert-success').fadeOut(300).delay(450).remove();
						},2500);
							
					}
				}
			});
		});
			
	    var addComment = {
			moveForm : function(commId, parentId, respondId, postId) {

				var t = this, div, comm = t.I(commId),
					respond = t.I(respondId),
					cancel = t.I('cancel-comment-reply-link'),
					parent = t.I('comment_parent'),
					post = t.I('comment_post_ID');

				if (!comm || !respond || !cancel || !parent)
					return;
		 
				t.respondId = respondId;
				postId = postId || false;

				if ( ! t.I('wp-temp-form-div') ) {
					div = document.createElement('div');
					div.id = 'wp-temp-form-div';
					div.style.display = 'none';
					respond.parentNode.insertBefore(div, respond);
				}

				comm.parentNode.insertBefore(respond, comm.nextSibling);
				if ( post && postId )
					post.value = postId;
				parent.value = parentId;
				cancel.style.display = '';

				cancel.onclick = function() {
					var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

					if ( ! temp || ! respond )
						return;

					t.I('comment_parent').value = '0';
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp);
					this.style.display = 'none';
					this.onclick = null;
					return false;
				};

				try { t.I('comment').focus(); }
				catch(e) {}

				return false;
			},

			I : function(e) {
				return document.getElementById(e);
			}
		};

	</script>
{/if}
{/if}

{if isset($smartcustomcss)}
    <style>
        {$smartcustomcss}
    </style>
{/if}