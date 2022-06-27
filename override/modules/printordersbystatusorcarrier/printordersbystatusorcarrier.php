<?php

if (!defined('_PS_VERSION_'))
	exit;

class PrintOrdersByStatusOrCarrierOverride extends PrintOrdersByStatusOrCarrier
{
	
	public function getInvoices()
	{
		$my_invoices = array();

		foreach ($this->carriers as $id_carrier => $name)
		{
			$orders = $this->orders[$id_carrier]['invoices'];
			foreach ($orders as $key => $order)
			{
				if ($key == "Paiement accepté"){
				$n = count($orders[$key]);
				$option_name = $name.' - '.$key.' ('.$n.' '.($n > 1 ? $this->l('orders') : $this->l('order')).')';
				$my_invoices[] = array(
					'id'   => $id_carrier.'_'.$this->orders_states[$key],
					'name' => $option_name
				);
				}
			}
		}

		return $my_invoices;
	}

	
}