$(document).ready(function() {
	$('div#stop_rating p.stop a').click(function() {
		$('div#stop_rating').hide(500);
		$.ajax({type : "GET", url : window.location+'&stop_rating=1' });
		return false;
	});

    /*$('#po_invoices').change(function() {
        invoiceChange();
        return false;
    });

    $('#po_delivery_slips').change(function() {
        deliveryChange();
        return false;
    });

    deliveryChange();
    invoiceChange();

    function deliveryChange() {
        var liconeD = '#desc-module-save-calendar';
        var motcleD = "delivery_slips=";
        var selecteurD = "#po_delivery_slips";
        var numberD = $(liconeD).attr("href").toString().lastIndexOf(motcleD);
        $(liconeD).attr("href",$(liconeD).attr("href").toString().substr(0,numberD+motcleD.length)+$(selecteurD).val());
    }

    function invoiceChange() {
        var licone = '#desc-module-new';
        var motcle = "invoices=";
        var selecteur = "#po_invoices";
        var number = $(licone).attr("href").toString().lastIndexOf(motcle);
        $(licone).attr("href",$(licone).attr("href").toString().substr(0,number+motcle.length)+$(selecteur).val());
    }*/
});

