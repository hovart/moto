/**
* 2007-2015 Mack Stores
*
* NOTICE OF LICENSE
*
* This code is a property of MackStores. In No way any one is authorised to use this code  or modify this code and redistribute without prior
* permission from the authour i.e MackStores
*
*
*  @author    Mack Stores contact:-sales@mackstores.com
*  @copyright 2007-2015 Mack Stores
*  International Registered Trademark & Property of Mack Stores
*/


$(document).ready(function(){
	if (typeof qckvw !== 'undefined' && qckvw)
		qckvw();




});





function qckvw()
{
	$(document).on('click', '.qckvw:visible, .qckvw-mobile:visible', function(e)
	{
		e.preventDefault();
		var url = this.rel;
		if (url.indexOf('?') != -1)
			url += '&';
		else
			url += '?';

		if (!!$.prototype.fancybox)
			$.fancybox({
				'padding':  0,
				'width':    1087,
				'height':   610,
				'type':     'iframe',
				'href':     url + 'content_only=1'
			});
	});
}




