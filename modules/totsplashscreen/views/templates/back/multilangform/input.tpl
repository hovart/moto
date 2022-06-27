{*
	Parametre : 
	$language : variable globale Language::getLanguages
	$value : array(
		$id_lang => value
		$id_lang1 => value1
		$id_lang2 => value2
		...
	)
	$inputname 
*}
{assign  var="defaultlanguage" value=Configuration::get('PS_LANG_DEFAULT')}

{foreach from=$languages item=language}

<div class="{$inputname}_{$language.id_lang}" style="display: {if $language.id_lang == Configuration::get('PS_LANG_DEFAULT')} block {else} none {/if};float: left;">
  	<input type="text" 
  		name="{$inputname}[{$language.id_lang}]" 
  		class="input_{$inputname}_{$language.id_lang}" 
  		value="{if isset($value[$language.id_lang])}{$value[$language.id_lang]} {/if}" />
 </div>
{/foreach}
{$thismodule->displayFlags($languages, Configuration::get('PS_LANG_DEFAULT'), $inputname, $inputname, true)}