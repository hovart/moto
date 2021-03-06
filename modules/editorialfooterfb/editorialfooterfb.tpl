{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Module editorialfooterfb -->
<div id="editorialfooterfb_block_center" class="editorialfooterfb_block">
	{if $editorialfooterfb->body_home_logo_link}<a href="{$editorialfooterfb->body_home_logo_link|escape:'html':'UTF-8'}" title="{$editorialfooterfb->body_title|escape:'html':'UTF-8'|stripslashes}">{/if}
	{if $homepage_logo}<img src="{$link->getMediaLink($image_path)|escape:'html'}" alt="{$editorialfooterfb->body_title|escape:'html':'UTF-8'|stripslashes}" {if $image_width}width="{$image_width}"{/if} {if $image_height}height="{$image_height}" {/if}/>{/if}
	{if $editorialfooterfb->body_home_logo_link}</a>{/if}
	{if $editorialfooterfb->body_logo_subheading}<p id="editorialfooterfb_image_legend">{$editorialfooterfb->body_logo_subheading|stripslashes}</p>{/if}
	{if $editorialfooterfb->body_title}<h1>{$editorialfooterfb->body_title|stripslashes}</h1>{/if}
	{if $editorialfooterfb->body_subheading}<h2>{$editorialfooterfb->body_subheading|stripslashes}</h2>{/if}
	{if $editorialfooterfb->body_paragraph}<div class="rte">{$editorialfooterfb->body_paragraph|stripslashes}</div>{/if}
</div>
<!-- /Module editorialfooterfb -->
