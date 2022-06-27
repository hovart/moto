<?php

class AdminPdfController extends AdminPdfControllerCore
{
	public function processGenerateInvoicesPDF3()
	{
		$order_invoice_collection = array();
		$id_order_state = Tools::getValue('id_order_state');
		$id_carrier = Tools::getValue('id_carrier');
		$start = Tools::getValue('start');
		$end = Tools::getValue('end');

		if (is_array($order_invoices = OrderInvoice::getInvoicesByStatusAndCarriers($id_order_state, $id_carrier, $start, $end)))
			$order_invoice_collection = array_merge($order_invoices, $order_invoice_collection);

		if (!count($order_invoice_collection))
			die(Tools::displayError('No invoice was found.'));

		$this->generatePDF($order_invoice_collection, PDF::TEMPLATE_INVOICE);
	}

	public function processGenerateDeliverySlipsPDF2()
	{
		$order_invoice_collection = array();
		$id_order_state = Tools::getValue('id_order_state');
		$id_carrier = Tools::getValue('id_carrier');
		$start = Tools::getValue('start');
		$end = Tools::getValue('end');

		if (is_array($order_invoices = OrderInvoice::getInvoicesByStatusAndCarriers($id_order_state, $id_carrier, $start, $end)))
			$order_invoice_collection = array_merge($order_invoices, $order_invoice_collection);

		if (!count($order_invoice_collection))
			die(Tools::displayError('No delivery slip was found.'));

		$this->generatePDF($order_invoice_collection, PDF::TEMPLATE_DELIVERY_SLIP);
	}
}
