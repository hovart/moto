// jquery.mdg-slider 3.5.5 - 2013-05-28
// ------------------------------------------------------------------------
//
// Developed and maintained by Michel Dumont
// http://www.graphart-crea.com
//
//
(function($)
{

	$.fn.doAutoComplete = function()
	{
		this.each(function(){ $(this).data('ac', new AutoComplete($(this))); });
		return this;
	}
	
	function AutoComplete(root)
	{
		var oRoot = this,
			oAutoInput = root,
			setId = root.parent().attr('data-id'),
			setMax = root.parent().attr('data-max'),
			oSelectedProducts = $('#'+setId+'_selected'),
			oInputProductsIds = $('#'+setId+'_ids'),
			oInputProductsNames = $('#'+setId+'_names')
			;
		

	
		this.getProductsIds = function()
		{
			if (oInputProductsIds.val() === undefined)
				return '';
			var ids = '';
			ids += oInputProductsIds.val().replace(/\\-/g,',').replace(/\\,$/,'');
			ids = ids.replace(/\,$/,'');
		
			return ids;
		};
		oRoot.addSelectedProduct = function(event, data, formatted)
		{
			if (data == null)
				return false;
			var productId = data[1];
			var productName = data[0];
		
			oSelectedProducts.append('<li id="product_'+productId+'">'+productName + ' <span class="delSelectedProduct" data-id="' + productId + '" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span></li>');
			oInputProductsIds.val(oInputProductsIds.val() + productId + ',');
			oInputProductsNames.val(oInputProductsNames.val() + productName + '¤');
		
			oAutoInput.val('');
			oAutoInput.setOptions({
				extraParams: {excludeIds : oRoot.getProductsIds()}
			});
		};
		oRoot.removeProduct = function(id_product)
		{
			// Cut hidden fields in array
			var inputCut = oInputProductsIds.val().split(',');
			var nameCut = oInputProductsNames.val().split('¤');
			var input = '';
			var name = '';
		
			for (i in inputCut)
			{
				// If empty, error, next
				if (!inputCut[i])
					continue ;
		
				if (inputCut[i] != id_product)
				{
					input += inputCut[i] + ',';
					name += nameCut[i] + '¤';
				}
				else
					$('#product_'+inputCut[i],oSelectedProducts).remove();
			}
			oInputProductsIds.val(input);
			oInputProductsNames.val(name);
		
			oAutoInput.setOptions({
				extraParams: {excludeIds : oRoot.getProductsIds()}
			});
		};

		init = function()
		{
			oAutoInput.autocomplete('ajax_products_list.php', {
				minChars: 1,
				autoFill: true,
				max:20,
				matchContains: true,
				mustMatch:true,
				scroll:false,
				cacheLength:0,
				formatItem: function(item) {
					return item[1]+' - '+item[0];
				}
			})
			.result(oRoot.addSelectedProduct)
			.setOptions({
				extraParams: {excludeIds : oRoot.getProductsIds()}
			});
			$('.delSelectedProduct',oSelectedProducts).live('click',function(){
				oRoot.removeProduct($(this).attr('data-id'));
			});
		}
		init();
	}


})(jQuery);
