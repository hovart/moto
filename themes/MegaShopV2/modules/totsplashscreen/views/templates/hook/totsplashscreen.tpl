<!-- Block totsplashscreen-->
{if $totSplashScreen}
     {if isset($totSplashScreen.totsplashscreen_newsletter) and $totSplashScreen.totsplashscreen_newsletter == 'on' and isset($totSplashScreen.totsplashscreen_fan_page) and $totSplashScreen.totsplashscreen_fan_page == 'on' and isset($install) and $install == 'on'}
          {assign var='style1' value='style="width:260px;padding-right:10px;float:left;"'}
          {assign var='style2' value='style="width:260px;float:right;"'}
     {else}
          {assign var='style1' value='style="width:100%;float:none;border:0;max-width:none;"'}
          {assign var='style2' value='style="width:100%;float:none;border:0;max-width:none;"'}
     {/if}
     <div id="totsplashscreenfont" {if $totSplashScreen.totsplashscreen_permission_mode  != 1} onClick="totSplashScreenHide('{$totSplashScreen.totsplashscreen_delai}','{$totSplashScreen.totsplashscreen_version_cookie}');" {/if} style="opacity: {$totSplashScreen.totsplashscreen_opacity/100};filter:alpha(opacity={$totSplashScreen.totsplashscreen_opacity});"></div>
     <div id="totsplashscreen" style="width:{$totSplashScreen.totsplashscreen_width}px; height:{$totSplashScreen.totsplashscreen_height}px;background-color:{$totSplashScreen.totsplashscreen_backgroundColor}" class="{$lang_iso}">
          {if $totSplashScreen.totsplashscreen_permission_mode  != 1}
          <div id="closed" onClick="totSplashScreenHide('{$totSplashScreen.totsplashscreen_delai}','{$totSplashScreen.totsplashscreen_version_cookie}');">
               
          </div>
          {/if}
          <p>{$totSplashScreen.totsplashscreen_text_before}</p>
          {if $totSplashScreen.totsplashscreen_newsletter == 'on' and $install == 'on'}
               <div id="totSplashLeft" {$style1}>
                    <h2>{l s='Subscription Newsletter' mod='totSplashScreen'}</h2>
                    <p>{l s='Text newsletter' mod='totSplashScreen'}</p> 
                    {if isset($totsplashscreen_message) && $totsplashscreen_message}
                         <p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$totsplashscreen_message}</p>
                    {/if}	
                    <form action="" method="post">	
                         <p>
                              <input type="text" name="TOTemail" size="18" value="{if isset($value) && $value}{$value}{else}{l s='your e-mail' mod='totSplashScreen'}{/if}" onfocus="javascript:if(this.value=='{l s='your e-mail' mod='totSplashScreen'}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='your e-mail' mod='totSplashScreen'}';" />
                         </p>
                         <input type="hidden" name="TOTaction" value="0" />
                         
                         <center><input type="submit" value="{l s='Subscribe' mod='totSplashScreen'}" class="button" name="TOTsubmitNewsletter" /></center>
                         
                    </form>
                    <div class="both" style="margin-bottom: 10px;"></div>
               </div>		    
          {/if}		    
          {if $totSplashScreen.totsplashscreen_fan_page == 'on'}
               <div id="totSplashRight" {$style2}>
                    <h2>{l s='Become fan' mod='totSplashScreen'}</h2>
                    <p>{l s='Text facebook' mod='totSplashScreen'}<br /><br /></p>
                    <center>
                         <iframe src="//www.facebook.com/plugins/like.php?href={$totSplashScreen.totsplashscreen_fan_page_url}&amp;send=false&amp;layout=standard&amp;width=150&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; width:250px; height:70px; " allowTransparency="true"></iframe>
                    </center>		    
               </div>		    
          {/if}		    
          <div class="both" style="clear:both"></div>	

          {if $totSplashScreen.totsplashscreen_permission_mode == 1}
               <div class="totSplashPermission">
                    <a href="#" onclick="totSplashScreenHide('{$totSplashScreen.totsplashscreen_delai}','{$totSplashScreen.totsplashscreen_version_cookie}');" class="{if $totSplashScreen.image_enter == ''}button{/if}">
                         {if $totSplashScreen.image_enter != ''}
                              <img src="{$totSplashScreen.image_enter}" alt="">
                         {else}
                              {l s='Enter' mod='totsplashscreen'}
                         {/if}
                    </a>
                    <a href="{$totSplashScreen.totsplashscreen_permission_redirect}" class="{if $totSplashScreen.image_enter == ''}button{/if}">
                         {if $totSplashScreen.image_leave != ''}
                              <img src="{$totSplashScreen.image_leave}" alt="">
                         {else}
                              {l s='Leave' mod='totsplashscreen'}
                         {/if}
                    </a>
               </div>
          {/if}	 
     </div>
{literal}
     <script type="text/javascript">
     //marginTop = -180 - ( ($("#totsplashscreen").height() - 300)  / 2);
     //$("#totsplashscreen").css('margin-top', marginTop);
     function totSplashScreenHide(day, version){
          $("#totsplashscreenfont").hide();
          $("#totsplashscreen").hide();
          if( day == 0)
               day = 1000;
          var date = new Date();
          date.setTime(date.getTime()+(day*24*60*60*1000));
          var expires = "; expires="+date.toGMTString();
          /*   document.cookie = "totSplashScreen{/literal}{$totSplashScreen.id_totsplashscreen}{literal}="+version+expires+"; path={/literal}{$url}{literal}";*/
     }
     jQuery.fn.center = function () {
         this.css("position","absolute");
         $('body').append(this);
         this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight())) / 2) + "px");
         this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth())) / 2) + "px");
         return this;
     }
     $(function(){$('#totsplashscreen').center();});

     </script>
{/literal}
{/if}
<!-- /Block totsplashscreen-->     