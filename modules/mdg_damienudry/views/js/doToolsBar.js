$(function (){
	// Toolbar
	var submited = false
	//get reference on save link
	var btn_save = $('span[class~="process-icon-form"]').parent();

	//get reference on form submit button
	var btn_submit = $('#_form_submit_btn');

	if (btn_save.length > 0 && btn_submit.length > 0)
	{
		//get reference on save and stay link
		btn_save_and_stay = $('span[class~="process-icon-save-and-stay"]').parent();

		//get reference on current save link label
		lbl_save = $('#desc--save div');

		//override save link label with submit button value
		if (btn_submit.val().length > 0)
			lbl_save.html(btn_submit.attr("value"));

		if (btn_save_and_stay.length > 0)
		{

			//get reference on current save link label
			lbl_save_and_stay = $('#desc--save-and-stay div');

			//override save and stay link label with submit button value
			if (btn_submit.val().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked'))
			{
				lbl_save_and_stay.html(btn_submit.val() + " and stay ");
			}

		}

		//hide standard submit button
		btn_submit.hide();
		//bind enter key press to validate form
		$('#_form').keypress(function (e) {
			if (e.which == 13 && e.target.localName != 'textarea')
				$('#desc--save').click();
		});
		//submit the form
		
			btn_save.click(function() {
				// Avoid double click
				if (submited)
					return false;
				submited = true;
				
				//add hidden input to emulate submit button click when posting the form -> field name posted
				btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');

				$('#_form').submit();
				return false;
			});

			if (btn_save_and_stay)
			{
				btn_save_and_stay.click(function() {
					//add hidden input to emulate submit button click when posting the form -> field name posted
					btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');

					$('#_form').submit();
					return false;
				});
			}
		
	}

});
