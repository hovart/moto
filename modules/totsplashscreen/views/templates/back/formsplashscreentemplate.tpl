<script type="text/javascript">
	var iso = '{$isoTinyMCE}';
	var pathCSS = '{$_THEME_CSS_DIR_}';
	var ad = '{$ad}';
</script>
{include file='./multilangform/js.tpl'}
<script type="text/javascript">id_language = Number({$defaultLanguage});</script>
<h2>{l s='module totSplashScreen' mod='totsplashscreen'}</h2>
<fieldset class="width6">
	<legend><img src="{$path}logo.gif">{l s='Settings for totSplashScreen' mod='totsplashscreen'}</legend>
	<form method="post" action="{$url}" enctype="multipart/form-data">
		<table class="width6" cellspacing="10">
			<tr>
				<td style="width: 180px;">
					{l s='Template\'s name' mod='totsplashscreen'}
				</td>
				<td>
					<input type="text" value="{$name}" name="name"/>
				</td>
			</tr>
			<tr>
				<td>{l s='Display Facebook page zone ?' mod='totsplashscreen'}</td>
				<td>
					<!-- Si la configuration est active pour la page facebook on coche la case-->		
					<input type="checkbox" name="fan_page" onClick=" javascript:$('.fanpage').toggle();" style="margin-top:3px;" {$checked}/>
				</td>
			</tr>
			<!-- Si la configuration facebook est active, on affiche le formulaire pour y insérer un liens-->
			<tr class="fanpage" {$style}>
				<td>{l s='Facebook Fan Page link' mod='totsplashscreen'}</td>
				<td>
					<input type="text" name="fan_page_url" size="64" value="{$totsplashscreen_fan_page_url}" style="float:left;" />
					<br />
					<p><small style="color:#999;font-size:10px;">{l s='Fan page full URL, example : https://www.facebook.com/pages/Exclu-Mariagecom/103202581933' mod='totsplashscreen'}</small></p>
				</td>
			</tr>
			<tr>
				<td>{l s='Display Newsletter zone' mod='totsplashscreen'}</td><td>
					<div>
						<input type="checkbox" name="newsletter" {$checked_newsletter}/>
					</div>
					<div class="infos">
						<small style="color:#999;font-size:10px;">{l s='Blocknewsletter module must be installed' mod='totsplashscreen'} : {$txt}</small>
					</div>
				</td>
			</tr>
			<tr>
				<td>{l s='Message before central zone' mod='totsplashscreen'}</td>
				<td>

					{if $create}
						{include file='./multilangform/textarea.tpl' textareaname='message' value=''}
					{else}
						{include file='./multilangform/textarea.tpl' textareaname='message' value=$message}
					{/if}
				</td>
			</tr>
			<tr>
				<td>{l s='Width of the popup' mod='totsplashscreen'}</td>
				<td><input type="text" value="{$width}" name="width">px</td>
			</tr>
			<tr>
				<td>{l s='Height of the popup' mod='totsplashscreen'}</td>
				<td><input type="text" value="{$height}" name="height">px</td>
			</tr>
			<tr>
				<td>{l s='Background color of the popup' mod='totsplashscreen'}</td>
				<td><input type="text" value="{$backgroundColor}" name="backgroundColor"></td>
			</tr>
			<tr>
				<td>{l s='Opacity of layer' mod='totsplashscreen'}</td>
				<td><input type="text" value="{$opacity}" name="opacity"><p><small style="color:#999;font-size:10px;">{l s='value between 0 and 100' mod='totsplashscreen'}</small></p></td>

			</tr>
			<tr>
				<td>{l s='Permission mode' mod='totsplashscreen'}</td>
				<td>
					<input type="radio" name="permission_mode" value="1" {if $permission_mode == 1} checked {/if}>{l s='Yes'}&nbsp;&nbsp;&nbsp;
					<input type="radio" name="permission_mode" value="0" {if $permission_mode == 0} checked {/if}>{l s='No'}<br/>
					<p><small style="color:#999;font-size:10px;">{l s='Two buttons "Coming in" and "Leave" will appear in the centre of the popup if permission mode is enabled' mod='totsplashscreen'}</small></p>


				</td>
			</tr>
			<tr class="redirect_mode">
				<td>{l s='Redirection address' mod='totsplashscreen'}</td>
				<td><input type="text" name="permission_redirect" value="{$permission_redirect}"></td>
			</tr>
			<tr class="redirect_mode">
				<td>{l s='Enter image' mod='totsplashscreen'}</td>
				<td><input type="file" name="image_enter"/></td>
			</tr>
			<tr class="redirect_mode">
				<td>{l s='Leave image' mod='totsplashscreen'}</td>
				<td><input type="file" name="image_leave"/></td>
			</tr>

			<tr>
				<td colspan="2">
					{if isset($id)}
						<input type="hidden" name="idtemplate" value="{$id}">
					{else}
						<input type="hidden" name="newTemplateForm" value="1"/>
					{/if}
					<input type="submit" class="button"/>	
					<a href="{$url}">
						<input type="button" class="button" value="{l s='Go back to module' mod='totsplashscreen'}">
					</a>	
				</td>
			</tr>


		</table>
	</form>
</fieldset>
<script type="text/javascript">
	function delcookie() {
		var date = new Date();
		date.setTime(date.getTime());
		var expires = "; expires=" + date.toGMTString();
		document.cookie = "totSplashScreen=view" + expires + "; path=' . $url . '";
		document.cookie = "totsplashscreen_count_page=1" + expires + "; path=' . $url . '";
		alert("{l s='Cookie deleted, please refresh homepage to display Splash Screen again' mod='totsplashscreen'}");
	}
</script>
<br />
<fieldset class="width6">
	<legend><img src="{$path}logo.gif">{l s='Help' mod='totsplashscreen'}</legend>
	<p>
		- {l s='You can use as much as you want a template for create a Splash Screen.' mod='totsplashscreen'}<br />
		- {l s='If you display the facebook and newsletter zones with a dark background color for the popup, you can modify the color of the text only by CSS.' mod='totsplashscreen'}<br />
		- {l s='For add a video from Youtube for example, you have to copy/paste the embeded code of the video in the text editor which is displayed when you click to the button \"HTML\"' mod='totsplashscreen'}<br />
		- {l s='If you set the opacity with a value of 100, your customers won\'t be able to see other thing than your popup. It is really useful when you want to hide the page displayed and enable the permission mode.' mod='totsplashscreen'}<br />
		- {l s='For personnalize the buttons \"Enter\" and \"Leave\", you can add an image for each.' mod='totsplashscreen'} <br />
		- {l s='For newsletter subscription parameters, please check the bloc newsletter settings page :' mod='totsplashscreen'} <a style="text-decoration:underline;" target="_blank" href="{$url}&configure=blocknewsletter&tab_module=front_office_features&module_name=blocknewsletter">{l s='here' mod='totsplashscreen'}</a> <br />
	</p>
</fieldset>