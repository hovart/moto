<?php

class OrderInvoice extends OrderInvoiceCore
{
	public static function getInvoicesByStatusAndCarriers($id_order_state, $id_carrier, $po_start, $po_end)
	{
		$sql = 'SELECT oi.*
			FROM `'._DB_PREFIX_.'order_invoice` oi
			LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = oi.`id_order`)
			WHERE '.(int)$id_order_state.' = (
				SELECT id_order_state
				FROM '._DB_PREFIX_.'order_history oh
				WHERE oh.id_order = o.id_order
				ORDER BY date_add DESC, id_order_history DESC
				LIMIT 1
			)
			AND id_carrier = '.(int)$id_carrier;
		if ($po_start)
			$sql .= ' AND o.date_add >= "'.$po_start.'" ';
		if ($po_end)
			$sql .= ' AND o.date_add <= "'.$po_end.'" ';
		$sql .=	Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o').'
			ORDER BY invoice_date ASC';

		$order_invoice_list = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		return ObjectModel::hydrateCollection('OrderInvoice', $order_invoice_list);
	}
}