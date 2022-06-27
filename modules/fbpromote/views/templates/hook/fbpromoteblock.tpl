<div id="fbpromote_block_left" class="block">
    <div id="bt_FbLogged" style="display: block;">
        <h4 style="padding-top:5px;"><img src="{$base_dir}modules/fbpromote/views/img/fb-icon.png" alt="Facebook" style="float:left; height:14px; width:14px; margin-right:3px;" /> {l s='Be fan' mod='fbpromote'}</h4>
        <div class="block_content" style="padding-bottom:42px;height:50px;">
            <p style="padding:5px 7px;">{l s='Be fan of our Facebook page and get' mod='fbpromote'} <b>{$fb_value}</b> {l s='discount!' mod='fbpromote'}</p>
            <div id="container_fb" style="position:absolute;">
                <div style="margin-top:5px;margin-left:40px;z-index:10000;">
                    <fb:like href="{$fb_page}" show_faces="false" width="500" height="500" font="" layout="button_count"></fb:like>
                </div>
            </div>
        </div>
    </div>
    <div id="bt_FbNotLogged" style="display: none;">
        <h4 style="padding-top:5px;"><img src="{$base_dir}modules/fbpromote/views/img/fb-icon.png" alt="Facebook" style="float:left; height:14px; width:14px; margin-right:3px;" /> {l s='Be fan' mod='fbpromote'}</h4>
        <div class="block_content" style="padding-bottom:42px;height:50px;">
            <p style="padding:5px 7px;">{l s='If you are not logged in to Facebook, please first log in and then refresh this page before clicking the Like button. Otherwise, you will not get your voucher' mod='fbpromote'}.</p>
        </div>
    </div>
</div>

{literal}
<script type="text/javascript">
	{/literal}
	{if !empty($aJSCallback)}
	{literal}

    if (typeof(bt_aFacebookCallback) !== 'undefined') {
        bt_aFacebookCallback.push({'url' : '{/literal}{$aJSCallback.url}{literal}', 'function' : '{/literal}{$aJSCallback.function}{literal}'});
	}
	else {
		var bt_aFacebookCallback = [{'url' : '{/literal}{$aJSCallback.url}{literal}', 'function' : '{/literal}{$aJSCallback.function}{literal}'}];
	}

    function bt_generateVoucherCode() {
	    $('div#container_fb').parents('div.column').css('overflow', 'visible');
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/fbpromote/views/templates/ajax/ajax.php',
            async: true,
            cache: false,
            data: 'like=1',
            success: function(data){
                if ($('div#fb-discount').length == 0){
                    FBDiscountDom = $('<div>', {'id':'fb-discount'});
                    $('body').append(FBDiscountDom);
                }
                if ($('div#fb-glow').length == 0){
                    FBGlowDom = $('<div>', {'id':'fb-glow'});
                    $('body').append(FBGlowDom);
                }
                $('div#fb-glow').fadeIn(function(){
                    $(this).css('filter', 'alpha(opacity=70)');
                    $(this).bind('click dblclick', function(){
                        $('div#fb-discount').hide();
                        $(this).fadeOut();
                    });
                });
                $('div#fb-discount').html(data).fadeIn();
            }
        });
    }

    $(document).ready(function(){
        window.fbAsyncInit = function() {
            FB.init({
			{/literal}
			{if !empty($fb_appId)}
			{literal}appId     : '{/literal}{$fb_appId}{literal}', // app id{/literal}
			{/if}{literal}
	            status     : {/literal}{if !empty($fb_appId)}false{else}true{/if}{literal}, // Check Facebook Login status
	            xfbml      : true // Look for social plugins on the page
            });

            {/literal}{if !empty($fb_appId)}{literal}
            FB.getLoginStatus(function(response) {
                if (response.status !== 'connected' && response.status !== 'not_authorized') {
                    // user not connected
                    $('#bt_FbLogged').css('display', 'none');
                    $('#bt_FbNotLogged').css('display', 'block');
                }
            });
            {/literal}{/if}{literal}

            FB.Event.subscribe('edge.create', function(response){
                for (var i = 0; i < bt_aFacebookCallback.length; ++i) {
                    if (typeof(bt_aFacebookCallback[i].url) !== 'undefined') {
                        if (response == bt_aFacebookCallback[i].url) {
                            // display fancy box voucher box
                            eval(bt_aFacebookCallback[i].function + '("' + response + '")');
                        }
                    }
                }
            }, true);
        };
	});
	{/literal}
	{/if}
	{literal}
</script>
{/literal}