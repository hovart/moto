{*
* 2003-2017 Business Tech
*
* @author Business Tech SARL <http://www.businesstech.fr/en/contact-us>
* @copyright  2003-2017 Business Tech SARL
*}
{if !empty($aErrors)}
{include file="`$sErrorInclude`"}
{else}
{include file="`$sConfirmInclude`" sMsg=$bStatus}
{/if}