
jQuery(document).ready(function() {
	
	jQuery('#tptn-config-switch').click(function(){
		if ( jQuery(this).hasClass('config-open') ) {
			jQuery('#tptn-config-inner').fadeIn();
			jQuery(this).removeClass('config-open');
			jQuery.cookie('ckconfigopen', 0);
		} else {
			jQuery('#tptn-config-inner').fadeOut();
			jQuery(this).addClass('config-open');
			jQuery.cookie('ckconfigopen', 1);
		}
		return false;
	});

	if ( jQuery.cookie('ckconfigopen') == 1 ) { 
		jQuery('#tptn-config-inner').css("display","none");
		jQuery('#tptn-config-switch').addClass('config-open');
	}
	if ( jQuery.cookie('ckconfigopen') == 0 ) { 
		jQuery('#tptn-config-inner').css("display","block");
		jQuery('#tptn-config-switch').removeClass('config-open');
	} else {
		jQuery('#tptn-config-inner').css("display","none");
	}

	//=== SKIN SETTINGS ===//
	jQuery('#tptn-config .skin-input-item').click(function(){

		var skin_value = jQuery(this).attr('data-rel');
		
		jQuery('head link[data-name=skins]').attr('href', tptn_theme_path+'skins/'+skin_value+'.css');
		
		jQuery('.apply').click(function() {
			jQuery.cookie('ckskin', skin_value);
		});
	});
	
	//=== RESET ALL COOKIES ====//
	jQuery('.reset').click(function() {
		jQuery.cookie('ckskin', null);
	});

});
