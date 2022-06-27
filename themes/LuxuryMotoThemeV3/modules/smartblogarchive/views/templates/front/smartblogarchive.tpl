{if isset($archives) AND !empty($archives)}
<div id="smartblogarchive" class="block blogModule">
    <h4 class="title_block"><a title="{l s='Blog Archive' mod='smartblogarchive'}" href="{smartblog::GetSmartBlogLink('smartblog_archive')}">{l s='Blog Archive' mod='smartblogarchive'}</a></h4>
    <div class="block_content list-block">
    <ul>
        {foreach from=$archives item="archive"}
            {foreach from=$archive.month item="months"}
                {assign var="linkurl" value=null}
                {$linkurl.year = $archive.year}
                {$linkurl.month = $months.month}
                {assign var="monthname" value=null}
                {if $months.month == 1}{$monthname = {l s='January' mod='smartblogarchive'}}
                {elseif $months.month == 2}{$monthname = {l s='February' mod='smartblogarchive'}}
                {elseif $months.month == 3}{$monthname = {l s='March' mod='smartblogarchive'}}
                {elseif $months.month == 4}{$monthname = {l s='April' mod='smartblogarchive'}}
                {elseif $months.month == 5}{$monthname = {l s='May' mod='smartblogarchive'}}
                {elseif $months.month == 6}{$monthname = {l s='June' mod='smartblogarchive'}}
                {elseif $months.month == 7}{$monthname = {l s='July' mod='smartblogarchive'}}
                {elseif $months.month == 8}{$monthname = {l s='August' mod='smartblogarchive'}}
                {elseif $months.month == 9}{$monthname = {l s='September' mod='smartblogarchive'}}
                {elseif $months.month == 10}{$monthname = {l s='October' mod='smartblogarchive'}}
                {elseif $months.month == 11}{$monthname = {l s='November' mod='smartblogarchive'}}
                {elseif $months.month == 12}{$monthname = {l s='December' mod='smartblogarchive'}}
                {/if}
                <li>
                    <a title="{$monthname}-{$archive.year}" href="{smartblog::GetSmartBlogLink('smartblog_month',$linkurl)}">{$monthname}-{$archive.year}</a>
                </li>
            {/foreach}
        {/foreach}
    </ul>
    </div>
</div>
{/if}