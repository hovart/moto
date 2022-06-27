<div id="smartblogsearch" class="block blogModule">
	<h4 class="title_block">{l s='Blog Search' mod='smartblogsearch'}</h4>
	<div id="sdssearch_block_top" class="block_content">
		<form action="{smartblog::GetSmartBlogLink('smartblog_search')}" method="post" id="sdssearchbox">
		    <input type="hidden" value="0" name="smartblogaction">
			<input type="text" value="" placeholder="{l s='Search' mod='smartblogsearch'}" name="smartsearch" id="sdssearch_query_top" class="search_query form-control ac_input" autocomplete="off">
			<button class="btn btn-default button-search" name="smartblogsubmit" type="submit"><i class="fa fa-search"></i></button>
		</form>
	</div>
</div>