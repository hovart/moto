
jQuery(document).ready(function() {
	
	jQuery('.postfinancecw-transaction-table .postfinancecw-more-details-button').each(function() {
		jQuery(this).click(function() {
			
			// hide all open 
			jQuery('.postfinancecw-transaction-table').find('.active').removeClass('active');
			
			// Get transaction ID
			var mainRow = jQuery(this).parents('.postfinancecw-main-row');
			var transactionId = mainRow.attr('id').replace('postfinancecw-_main_row_', '');
			
			var selector = '.postfinancecw-transaction-table #postfinancecw_details_row_' + transactionId;
			jQuery(selector).addClass('active');
			jQuery(mainRow).addClass('active');
		})
	});
	
	jQuery('.postfinancecw-transaction-table .postfinancecw-less-details-button').each(function() {
		jQuery(this).click(function() {
			// hide all open 
			jQuery('.postfinancecw-transaction-table').find('.active').removeClass('active');
		})
	});
	
	jQuery('.postfinancecw-transaction-table .transaction-information-table .description').each(function() {
		jQuery(this).mouseenter(function() {
			jQuery(this).toggleClass('hidden');
		});
		jQuery(this).mouseleave(function() {
			jQuery(this).toggleClass('hidden');
		})
	});
	
});