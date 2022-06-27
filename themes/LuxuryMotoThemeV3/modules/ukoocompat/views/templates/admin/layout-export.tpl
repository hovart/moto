{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

{$export_precontent}{foreach from=$export_headers item=header name=compatLoop}{$text_delimiter}{$header|escape:'htmlall':'UTF-8'}{$text_delimiter}{if !$smarty.foreach.compatLoop.last};{/if}{/foreach}

{foreach from=$export_content item=line}
{foreach from=$line item=content name=compatLoop}{$text_delimiter}{$content|escape:'htmlall':'UTF-8'}{$text_delimiter}{if !$smarty.foreach.compatLoop.last};{/if}{/foreach}

{/foreach}