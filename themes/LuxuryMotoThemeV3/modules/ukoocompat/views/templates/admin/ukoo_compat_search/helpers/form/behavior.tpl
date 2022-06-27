{**
  * Recherche de produits par compatibilité
  *
  * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
  * @copyright Ukoo 2015
  * @license   Ukoo - Tous droits réservés
  *
  * "In Ukoo we trust!"
  *}

<div class="form-group">
    <label class="control-label col-lg-3">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Do not show the catalog view. Usefull if you dont have multiple categories results' mod='ukoocompat'}">
			{l s='Display product listing immediatly after search submission' mod='ukoocompat'}
		</span>
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="skip_catalog" id="skip_catalog_on" value="1" {if $currentTab->getFieldValue($currentObject, 'skip_catalog')|intval}checked="checked"{/if} />
			<label class="t" for="skip_catalog_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="skip_catalog" id="skip_catalog_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'skip_catalog')|intval}checked="checked"{/if} />
			<label class="t" for="skip_catalog_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>

{* TODO :: améliorer la rapidité d'exécution avant de mettre en place cette fonctionnalité *}
{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*{l s='Display the number of compatible products by category in the "catalog" view.' mod='ukoocompat'}*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="display_nb_products_catalog" id="display_nb_products_catalog_on" value="1" {if $currentTab->getFieldValue($currentObject, 'display_nb_products_catalog')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_nb_products_catalog_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="display_nb_products_catalog" id="display_nb_products_catalog_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'display_nb_products_catalog')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_nb_products_catalog_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

<div class="form-group">
    <label class="control-label col-lg-3">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Reload other filters based on the last selected criteria' mod='ukoocompat'}">
            {l s='Dynamic criteria' mod='ukoocompat'}
        </span>
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="dynamic_criteria" id="dynamic_criteria_on" value="1" {if $currentTab->getFieldValue($currentObject, 'dynamic_criteria')|intval}checked="checked"{/if} />
			<label class="t" for="dynamic_criteria_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="dynamic_criteria" id="dynamic_criteria_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'dynamic_criteria')|intval}checked="checked"{/if} />
			<label class="t" for="dynamic_criteria_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>

{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*{l s='Display a reset button in the search block' mod='ukoocompat'}*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="display_reset_button" id="display_reset_button_on" value="1" {if $currentTab->getFieldValue($currentObject, 'display_reset_button')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_reset_button_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="display_reset_button" id="display_reset_button_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'display_reset_button')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_reset_button_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*<span class="label-tooltip" data-toggle="tooltip" title="{l s='Your customers will have to specify all fields' mod='ukoocompat'}">*}
			{*{l s='Submit search without button' mod='ukoocompat'}*}
		{*</span>*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="hide_button" id="hide_button_on" value="1" {if $currentTab->getFieldValue($currentObject, 'hide_button')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="hide_button_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="hide_button" id="hide_button_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'hide_button')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="hide_button_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*{l s='Hide the search block on the catalog pages and listing' mod='ukoocompat'}*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="hide_on_catalog" id="hide_on_catalog_on" value="1" {if $currentTab->getFieldValue($currentObject, 'hide_on_catalog')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="hide_on_catalog_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="hide_on_catalog" id="hide_on_catalog_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'hide_on_catalog')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="hide_on_catalog_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*{l s='Pre-filter criteria according to the current category' mod='ukoocompat'}*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="prefilter" id="prefilter_on" value="1" {if $currentTab->getFieldValue($currentObject, 'prefilter')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="prefilter_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="prefilter" id="prefilter_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'prefilter')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="prefilter_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

{*<div class="form-group">*}
    {*<label class="control-label col-lg-3">*}
        {*<span class="label-tooltip" data-toggle="tooltip" title="{l s='Will not be display if you skip the catalog view' mod='ukoocompat'}">*}
			{*{l s='List the catalog as menu in the listing' mod='ukoocompat'}*}
		{*</span>*}
    {*</label>*}
    {*<div class="col-lg-9">*}
		{*<span class="switch prestashop-switch fixed-width-lg">*}
			{*<input type="radio" name="display_menu" id="display_menu_on" value="1" {if $currentTab->getFieldValue($currentObject, 'display_menu')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_menu_on">{l s='Yes' mod='ukoocompat'}</label>*}
			{*<input type="radio" name="display_menu" id="display_menu_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'display_menu')|intval}checked="checked"{/if} />*}
			{*<label class="t" for="display_menu_off">{l s='No' mod='ukoocompat'}</label>*}
			{*<a class="slide-button btn"></a>*}
		{*</span>*}
    {*</div>*}
{*</div>*}

<div class="form-group">
    <label class="control-label col-lg-3">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Do not display helps save space, but necessary if you use aliases' mod='ukoocompat'}">
			{l s='Display alias search block' mod='ukoocompat'}
		</span>
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="display_alias_search_block" id="display_alias_search_block_on" value="1" {if $currentTab->getFieldValue($currentObject, 'display_alias_search_block')|intval}checked="checked"{/if} />
			<label class="t" for="display_alias_search_block_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="display_alias_search_block" id="display_alias_search_block_off" value="0" {if !$currentTab->getFieldValue($currentObject, 'display_alias_search_block')|intval}checked="checked"{/if} />
			<label class="t" for="display_alias_search_block_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='This can slow down the display of the product page if you have a lot of compatibility!' mod='ukoocompat'}">
			{l s='Display compatibilty tab on the product page' mod='ukoocompat'}
		</span>
    </label>
    <div class="col-lg-9">
		<span class="switch prestashop-switch fixed-width-lg">
			<input type="radio" name="display_product_tab" id="display_product_tab_on" value="1" {if $currentTab->getFieldValue($currentObject, 'display_product_tab')|intval}checked="checked"{/if} />
			<label class="t" for="display_product_tab_on">{l s='Yes' mod='ukoocompat'}</label>
			<input type="radio" name="display_product_tab" id="display_product_tab_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'display_product_tab')|intval}checked="checked"{/if} />
			<label class="t" for="display_product_tab_off">{l s='No' mod='ukoocompat'}</label>
			<a class="slide-button btn"></a>
		</span>
    </div>
</div>