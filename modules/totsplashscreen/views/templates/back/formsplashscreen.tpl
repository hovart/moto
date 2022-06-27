{include file='./datepicker/js.tpl'}
<script type="text/javascript">
	{literal}
		$(function() {
			$('#type').change(function() {
				$('.type').slideUp();
				if (this.value == 'category')
					$('#typecategory').slideDown();
				if (this.value == 'product')
					$('#typeproduct').slideDown();
				if (this.value == 'cms')
					$('#typecms').slideDown();
				if (this.value == 'none')
					$('#typeall').slideDown();

			})
		});

	{/literal}
</script>

<fieldset>
	<legend>{l s='New splash screen' mod='totsplashscreen'}</legend>

	<form action="{$url}" method="post">
		<label for="">{l s='Name' mod='totsplashscreen'}</label>
		<div class="margin">
			<input type="text" name="name" {if isset($splashscreen)} value="{$splashscreen->name}" {/if}/>
		</div>
		<div class="clear both"></div>
		<label for="">{l s='Number of days before reshow' mod='totsplashscreen'}</label>
		<div class="margin">
			<input type="text" name="nb_jour_avant_reapparition" {if isset($splashscreen)} value="{$splashscreen->nb_jour_avant_reapparition}" {/if}/>
		</div>
		<div class="clear both"></div>

		<label for="">{l s='Template' mod='totsplashscreen'}</label>
		<div class="margin">
			<select name="template">
				{foreach from=$templates item=template}
					<option value="{$template.id_totsplashscreen_template}" {if isset($splashscreen)} {if $splashscreen->id_template == $template.id_totsplashscreen_template} selected="selected" {/if} {/if}>{$template.name}</option>
				{/foreach}	
			</select>
		</div>
		<div class="clear both"></div>

		<label for="">{l s='Page type' mod='totsplashscreen'}</label>
		<div class="margin">
			<select name="type" id="type">
				{foreach from=$types item=type key=key}
					<option value="{$key}" {if isset($splashscreen)} {if $splashscreen->type == $key} selected="selected" {/if} {/if}>{$type}</option>
				{/foreach}
			</select>

		<div class="clear both"></div>
		<div id="typeall" class="type" {if (isset($splashscreen) && $splashscreen->type == 'none') || !isset($splashscreen)} style="display:block;"{else} style="display:none;"{/if}>
			<label for="">{l s='Pages views before show' mod='totsplashscreen'}</label>
			<div class="margin">
				<input type="text" name="navigation" {if isset($splashscreen) && $splashscreen->type == 'none'} value="{$splashscreen->id_type}" {/if}/>
			</div>
		</div>
		<div class="clear both"></div>

		<div id="typecategory" class="type" {if !isset($splashscreen) || $splashscreen->type != 'category'} style="display:none;"{/if} >
			<label for="">{l s='Category' mod='totsplashscreen'}</label>
			<div class="margin">
				<select name="category" id="">
					{foreach from=$categories item=category}
						<option value="{$category.id}" {if isset($splashscreen)}{if $splashscreen->type == 'category' && $splashscreen->id_type == $category.id} selected="selected" {/if} {/if}>{$category.name}</option>
					{/foreach}
				</select>
			</div>	
		</div>
		<div class="clear both"></div>

		<div class="type" id="typeproduct" {if !isset($splashscreen) || $splashscreen->type != 'product'} style="display:none;"{/if}>
			<label for="">{l s='Product' mod='totsplashscreen'}</label>
			<div class="margin">
				{include file='./autocompleteProduct/js.tpl'}
				{if isset($product)}
					{include file='./autocompleteProduct/input.tpl' autocompleteproduct=$product}
				{else}
					{include file='./autocompleteProduct/input.tpl' autocompleteproduct=NULL}
				{/if}
			</div>
		</div>
		<div class="clear both"></div>

		<div class="type" id="typecms" {if !isset($splashscreen) || $splashscreen->type != 'cms'} style="display:none;"{/if}>
			<label for="">{l s='CMS' mod='totsplashscreen'}</label>
			<div class="margin">
				<select name="cms" id="">
					{foreach from=$cmss item=cms}
						<option value="{$cms.id_cms}" {if isset($splashscreen)}{if $splashscreen->type == 'cms' && $splashscreen->id_type == $cms.id_cms} selected="selected" {/if}{/if}>{$cms.meta_title}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="clear both"></div>

		<label for="">{l s='date_begin' mod='totsplashscreen'}</label>
		<div class="margin">
			{if isset($splashscreen)}
				{include file='./datepicker/input.tpl' name='date_start' value=$splashscreen->date_start}
			{else}
				{include file='./datepicker/input.tpl' name='date_start' value=''}
			{/if}
		</div>
		<div class="clear both"></div>

		<label for="">{l s='date_end' mod='totsplashscreen'}</label>
		<div class="margin">
			{if isset($splashscreen)}
				{include file='./datepicker/input.tpl' name='date_end' value=$splashscreen->date_end}
			{else}
				{include file='./datepicker/input.tpl' name='date_end' value=''}
			{/if}
		</div>
		<div class="clear both"></div>

		{if isset($splashscreen)}
			<input type="hidden" name="idSplashScreen" value="{$splashscreen->id_totsplashscreen}">
		{else}
			<input type="hidden" name="newSplashScreen" value="1"/>
		{/if}

		<label for="">&nbsp;</label>
		<div class="margin" style="margin-top:10px;">
			<input type="submit" class="button"> 
			<a href="{$url}">
				<input type="button" class="button" value="{l s='Go back to module' mod='totsplashscreen'}">
			</a>	
		</div>
		<div class="clear both"></div>
	</form>
</fieldset>