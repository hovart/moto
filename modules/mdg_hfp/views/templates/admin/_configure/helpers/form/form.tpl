{*
*  Michel Dumont | www.michel-dumont.fr
*
*  @author Michel Dumont <michel|at|dumont|dot|io>
*  @copyright  Since 2014
*  @version  1.0.3 - 2016-03-02
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.6
*
*}


{extends file="helpers/form/form.tpl"}

{block name="input"}
	{if $input.type == 'autocomplete-product'}
		<input type="hidden" name="input{$input.name}" id="input{$input.name}" value="{foreach $input.values.input as $v}{$v}-{/foreach}" />
		<input type="hidden" name="name{$input.name}" id="name{$input.name}" value="{foreach $input.values.name as $v}{$v}¤{/foreach}" />
		<div class="input-group"><input type="text" id="{$input.name}_autocomplete_input" /><span class="input-group-addon"><i class="icon-search"></i></span></div>
		<div id="div{$input.name}">
			{foreach $input.values.input as $k => $v}
				{if $v}<div class="form-control-static"><button type="button" class="btn btn-default del{$input.name}" name="{$v}"><i class="icon-remove text-danger"></i></button>{$input.values.name[$k]|escape:'html':'UTF-8'}</div>{/if}
			{/foreach}
		</div>
		
        <script type="text/javascript">
			productAutocomplete = new function(){
				var self = this;
				this.initAccessoriesAutocomplete = function (){
					$('#{$input.name}_autocomplete_input')
						.autocomplete('ajax_products_list.php', {
							minChars: 1,
							autoFill: true,
							max:20,
							matchContains: true,
							mustMatch:false,
							scroll:false,
							cacheLength:0,
							formatItem: function(item) {
								return item[1]+' - '+item[0];
							}
						}).result(self.addAccessory);
			
					$('#{$input.name}_autocomplete_input').setOptions({
						extraParams: {
							excludeIds : self.getAccessoriesIds()
						}
					});
				};
				this.getAccessoriesIds = function()
				{
					if ($('#input{$input.name}').val() === undefined)
						return '-1';
					return '-1,' + $('#input{$input.name}').val().replace(/\-/g,',');
				}
				this.addAccessory = function(event, data, formatted)
				{
					if (data == null)
						return false;
					var productId = data[1];
					var productName = data[0].replace(/\"/g,'').replace(/\'/g,'');
			
					var $divAccessories = $('#div{$input.name}');
					var $inputAccessories = $('#input{$input.name}');
					var $nameAccessories = $('#name{$input.name}');
			
					/* delete product from select + add product line to the div, input_name, input_ids elements */
					$divAccessories.html($divAccessories.html() + '<div class="form-control-static"><button type="button" class="del{$input.name} btn btn-default" name="' + productId + '"><i class="icon-remove text-danger"></i></button>&nbsp;'+ productName +'</div>');
					$nameAccessories.val($nameAccessories.val() + productName + '¤');
					$inputAccessories.val($inputAccessories.val() + productId + '-');
					$('#{$input.name}_autocomplete_input').val('');
					$('#{$input.name}_autocomplete_input').setOptions({
						extraParams: {
							excludeIds : self.getAccessoriesIds()
						}
					});
				};
				this.delAccessory = function(id)
				{
					var div = getE('div{$input.name}');
					var input = getE('input{$input.name}');
					var name = getE('name{$input.name}');
			
					// Cut hidden fields in array
					var inputCut = input.value.split('-');
					var nameCut = name.value.split('¤');
			
					if (inputCut.length != nameCut.length)
						return jAlert('Bad size');
			
					// Reset all hidden fields
					input.value = '';
					name.value = '';
					div.innerHTML = '';
					for (i in inputCut)
					{
						// If empty, error, next
						if (!inputCut[i] || !nameCut[i])
							continue ;
			
						// Add to hidden fields no selected products OR add to select field selected product
						if (inputCut[i] != id)
						{
							input.value += inputCut[i] + '-';
							name.value += nameCut[i] + '¤';
							div.innerHTML += '<div class="form-control-static"><button type="button" class="del{$input.name} btn btn-default" name="' + inputCut[i] +'"><i class="icon-remove text-danger"></i></button>&nbsp;' + nameCut[i] + '</div>';
						}
					}
			
					$('#{$input.name}_autocomplete_input').setOptions({
						extraParams: {
							excludeIds : self.getAccessoriesIds()
						}
					});
				};

				this.onReady = function(){
					self.initAccessoriesAutocomplete();
					$('#div{$input.name} ').delegate('.del{$input.name}', 'click', function(){
						self.delAccessory($(this).attr('name'));
					});
				};
			};
			
			productAutocomplete.onReady();
            </script>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="footer"}

	{* Ajoute href aux bouton disponible à partir de 1.6.0.6 *}
	{if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
		<div class="panel-footer">
			{if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
			<button
				type="submit"
				value="1"
				id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}"
				name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}"
				class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-default pull-right{/if}"
				>
				<i class="{if isset($fieldset['form']['submit']['icon'])}{$fieldset['form']['submit']['icon']}{else}process-icon-save{/if}"></i> {$fieldset['form']['submit']['title']}
			</button>
			{/if}
			{if isset($show_cancel_button) && $show_cancel_button}
			<a href="{$back_url}" class="btn btn-default" onclick="window.history.back()">
				<i class="process-icon-cancel"></i> {l s='Cancel' mod='mdg_hfp'}
			</a>
			{/if}
			{if isset($fieldset['form']['reset'])}
			<button
				type="reset"
				id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']}{else}{$table}_form_reset_btn{/if}"
				class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']}{else}btn btn-default{/if}"
				>
				{if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']}"></i> {/if} {$fieldset['form']['reset']['title']}
			</button>
			{/if}
			{if isset($fieldset['form']['buttons'])}
			{foreach from=$fieldset['form']['buttons'] item=btn key=k}
				{if isset($btn.href) && trim($btn.href) != ''}
					<a href="{$btn.href}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</a>
				{else}
					<button type="{if isset($btn['type'])}{$btn['type']}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</button>
				{/if}
			{/foreach}
			{/if}
		</div>
	{/if}

{/block}
