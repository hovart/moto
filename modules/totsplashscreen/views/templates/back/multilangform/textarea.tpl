{*
Parametre : 
$language : variable globale non passé en paramètre Language::getLanguages
$value : array(
$id_lang => value
$id_lang1 => value1
$id_lang2 => value2
...
)
$textareaname 
*}

{assign  var="defaultlanguage" value=Configuration::get('PS_LANG_DEFAULT')}


{foreach from=$languages item=language}

	<div class="{$textareaname}_{$language.id_lang}" style="display: {if $language.id_lang == $defaultlanguage} block {else} none {/if};float: left;">
		<textarea name="{$textareaname}[{$language.id_lang}]" id="{$textareaname}_{$language.id_lang}" class="rte">{if isset($value[$language.id_lang])} {$value[$language.id_lang]}{/if}</textarea>
	</div>
{/foreach}
{$thismodule->displayFlags($languages, $defaultlanguage, $textareaname, $textareaname, true)}