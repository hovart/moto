{literal}
<script type="text/javascript">
	if ($('select#FBPROMOTE_VOUCHER_TYPE').val() == "percent")
	{
		$('div#voucherbycurrency-container').hide();
	}
	else
	{
		$('div#voucherbypercent-container').hide();	
	}
	
	$('select#FBPROMOTE_VOUCHER_TYPE').bind('change', function(){
		if ($(this).val() == "currency")
		{
			$('input#FBPROMOTE_VOUCHER_PERCENT').attr('value', 0);
			$('div#voucherbypercent-container').slideUp();
			$('div#voucherbycurrency-container').slideDown();
		}
		else
		{
			$('input.FBPROMOTE_VOUCHER_AMOUNT').attr('value', 0);
			$('div#voucherbycurrency-container').slideUp();
			$('div#voucherbypercent-container').slideDown();
		}
	});

	if (!$('input#FBPROMOTE_VOUCHER_HASMINAMOUNT').is(':checked'))
	{
		$('div#voucherminamount-container').hide();
	}
	
	$('input#FBPROMOTE_VOUCHER_HASMINAMOUNT').bind('click', function(){
		if ($(this).is(':checked'))
		{
			$(this).attr('value', '1');
			$('div#voucherminamount-container').slideDown();
		}
		else
		{
			$(this).attr('value', '0');
			$('input.FBPROMOTE_VOUCHER_MINAMOUNT').attr('value', 0);
			$('div#voucherminamount-container').slideUp();
		}
	});

	$('input#FBPROMOTE_DISPLAY_CART_ORDER').bind('click', function(){
		$(this).attr('value', Number($(this).is(':checked')));
	});
</script>
{/literal}