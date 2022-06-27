<div class="pagenotfound">
	<h1 class="page-heading">{l s='Sorry, but nothing matched your search terms' mod='smartblog'}</h1>
	<p>
		{l s='Please try again with some different keywords.' mod='smartblog'}
	</p>
	<form class="std box" method="post" action="{smartblog::GetSmartBlogLink('smartblog_search')}">
		<fieldset>
			<div>
				<input type="hidden" value="0" name="smartblogaction">
				<input type="text" class="form-control grey" value="{$smartsearch}" name="smartsearch" id="search_query">
				<button class="btn btn-default button button-small" value="OK" name="smartblogsubmit" type="submit"><span>{l s='Ok' mod='smartblog'}</span></button>
			</div>
		</fieldset>
	</form>

	<div class="buttons">
		<a title="{l s='All Blogs' mod='smartblog'}" href="{smartblog::GetSmartBlogLink('smartblog')}" class="std-btn"><i class="fa fa-chevron-left left"></i>{l s='All Blogs' mod='smartblog'}</a>
	</div>
</div>