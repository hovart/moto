function display_cat_picker() {
	var val = parseInt($jqPm('input[name="bool_cat"]:checked').val());
	if(val)
		$jqPm('#category_picker').show('medium');
	else
		$jqPm('#category_picker').hide('medium');
			
}

function display_prod_cat_picker() {
	var val = parseInt($jqPm('input[name="bool_prod_cat"]:checked').val());
	if(val)
		$jqPm('#products_category_picker').show('medium');
	else
		$jqPm('#products_category_picker').hide('medium');
			
}

function display_border_color() {
	var val = parseInt($jqPm('input[name="border"]:checked').val());
	if(val)
		$jqPm('#border_color').show('medium');
	else
		$jqPm('#border_color').hide('medium');
			
}

function display_prod_picker() {
	var val = parseInt($jqPm('input[name="bool_prod"]:checked').val());
	if(val)
		$jqPm('#product_picker').show('medium');
	else
		$jqPm('#product_picker').hide('medium');
			
}


function display_manu_picker() {
	var val = parseInt($jqPm('input[name="bool_manu"]:checked').val());
	if(val)
		$jqPm('#manu_picker').show('medium');
	else
		$jqPm('#manu_picker').hide('medium');
			
}


function display_supp_picker() {
	var val = parseInt($jqPm('input[name="bool_supp"]:checked').val());
	if(val)
		$jqPm('#supp_picker').show('medium');
	else
		$jqPm('#supp_picker').hide('medium');
			
}


function display_cms_picker() {
	var val = parseInt($jqPm('input[name="bool_cms"]:checked').val());
	if(val)
		$jqPm('#cms_picker').show('medium');
	else
		$jqPm('#cms_picker').hide('medium');
			
}


function display_spe_picker() {
	var val = parseInt($jqPm('input[name="bool_spe"]:checked').val());
	if(val)
		$jqPm('#special_pages').show('medium');
	else
		$jqPm('#special_pages').hide('medium');
			
}

function display_usergroup_cb() {
	var val = parseInt($jqPm('select[name="usertype"]').val());
	if(val == 2)
		$jqPm('#users_groups').show('medium');
	else
		$jqPm('#users_groups').hide('medium');
			
}

function display_assos() {
	var val = parseInt($jqPm('input[name="default_group"]:checked').val());
	if(val)
		$jqPm('fieldset:eq(2)').hide('medium');
	else
		$jqPm('fieldset:eq(2)').show('medium');
			
}

function display_formByType() {
	var val = parseInt($jqPm('select[name="bg_type"]').val());
	
	if(val==1){
		$jqPm('#pm_bg_static_form').show('fast');
		$jqPm('#pm_bg_slide_form').hide('fast');
	}
	
	else{
		$jqPm('#pm_bg_static_form').hide('fast');
		$jqPm('#pm_bg_slide_form').show('fast');	
	}
}
function toogleWizardPanel() {
	$jqPm('#button-wizard').slideUp('fast');
	$jqPm('#abg_hide_panel').slideDown('fast');
}