<div id="languages_block_top">
<div id="countries">
  <ul style="list-style:none;">
  {foreach from=$languages key=k item=language name="languages"}
   <li {if $language.iso_code == $lang_iso}class="selected_language"{/if} style="display:inline-block;margin:2px 5px;">
   {if $language.iso_code != $lang_iso}
	{assign var=indice_lang value=$language.id_lang}
	{if isset($lang_rewrite_urls.$indice_lang)}
	 <a href="{$lang_rewrite_urls.$indice_lang|escape:htmlall}" title="{$language.name}">
	{else}
	 <a href="{$link->getLanguageLink($language.id_lang)|escape:htmlall}" title="{$language.name}">
	{/if}
   {/if}
	 <img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="19" height="12" />
   {if $language.iso_code != $lang_iso}
	</a>
   {/if}
   </li>
  {/foreach}
  </ul>
</div>
</div>