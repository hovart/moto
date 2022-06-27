{include file='./multilangform/js.tpl'}
<script type="text/javascript">
var iso =  '{$isoTinyMCE}' ;
var pathCSS = '{$_THEME_CSS_DIR_}' ;
var ad = '{$ad}' ;
</script>
<script type="text/javascript">id_language = Number({$defaultLanguage});</script>
<h2>{l s='module totSplashScreen' mod='totsplashscreen'}</h2>
<fieldset class="width6">
<legend><img src="{$path}logo.gif">{l s='Settings for totSplashScreen' mod='totsplashscreen'}</legend>
<form method="post" action="{$url}">
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
			<input type="checkbox" name="fan_page" onClick=" javascript:$('.fanpage').toggle(); " style="margin-top:3px;" {$checked}/>
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
		<td>{l s="Height of the popup" mod='totsplashscreen'}</td>
		<td><input type="text" value="{$height}" name="height">px</td>
	</tr>
	<tr>
		<td>{l s="Background color of the popup" mod='totsplashscreen'}</td>
		<td><input type="text" value="{$backgroundColor}" name="backgroundColor"></td>
	</tr>
	<tr>
		<td>{l s="Opacity of layer" mod='totsplashscreen'}</td>
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
	<tr>
		<td>{l s='Redirection address' mod='totsplashscreen'}</td>
		<td><input type="text" name="permission_redirect" value="{$permission_redirect}"></td>
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
function delcookie(){
var date = new Date();
date.setTime(date.getTime());
var expires = "; expires="+date.toGMTString();
document.cookie = "totSplashScreen=view"+expires+"; path=' . $url . '";
document.cookie = "totsplashscreen_count_page=1"+expires+"; path=' . $url . '";
alert("{l s='Cookie deleted, please refresh homepage to display Splash Screen again' mod='totsplashscreen'}");
}
</script>
<br />
<fieldset class="width6">
<legend><img src="{$path}logo.gif">{l s='Help' mod='totsplashscreen'}</legend>
<p>
    - {l s='You can use the button "Previsualize" for have a look of what your SplashScreen will be in Front office.' mod='totsplashscreen'}<br />
    - {l s='"Number of days before reshow" allows you to define the number of days before a new Splash Screen is displayed again.' mod='totsplashscreen'}<br />
    - {l s='You can insert text, picture or movie before newsletter and facebook zones' mod='totsplashscreen'}<br />
    - {l s='For newsletter subscription parameters, please check the bloc newsletter settings page :' mod='totsplashscreen'} <a style="text-decoration:underline;" target="_blank" href="{$url}&module_name=blocknewsletter">{l s='here' mod='totsplashscreen'}</a> <br />
    - {l s='You can change all texts shown in the Splash Screen module using translations tool : ' mod='totsplashscreen'} <a style="text-decoration:underline;" target="_blank" href="index.php?tab=AdminTranslations&token=' . Tools::getAdminTokenLite('AdminTranslations' mod='totsplashscreen'}&type=modules&lang=' . $iso . '#totsplashscreen">{l s='here' mod='totsplashscreen'}</a><br /><br />
    {l s='Splash Screen by' mod='totsplashscreen'} <a style="text-decoration:underline;" href="http://www.202-ecommerce.com/' . $this->name . '?sourceid=mod&lang=' . (int) $cookie->id_lang . '" target="_blank">202-ecommerce</a>
</p>
</fieldset>