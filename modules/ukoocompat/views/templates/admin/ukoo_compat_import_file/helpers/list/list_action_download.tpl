{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<a{if isset($target)} target="{$target|escape:'html':'UTF-8'}"{/if} href="{$href|escape:'html':'UTF-8'}" title="{$action|escape:'html':'UTF-8'}"{if isset($name)} name="{$name|escape:'html':'UTF-8'}"{/if} class="default">
    <i class="icon-download"></i> {$action|escape:'html':'UTF-8'}
</a>