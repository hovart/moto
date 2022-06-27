<div id="tptnsearch">
	<form method="get" action="{$link->getPageLink('search',true)|escape:'html'}" id="searchbox">
		<label for="search_query_top"></label>
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query" type="text" id="search_query_top" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{else}{l s='Search' mod='tptnsearch'}{/if}" onfocus="javascript:if(this.value=='{l s='Search' mod='tptnsearch' js=1}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='Search' mod='tptnsearch' js=1}';"  />
		<input type="submit" name="submit_search" value="" class="button" />
	</form>
</div>
{include file="$self/tptnsearch-instantsearch.tpl"}