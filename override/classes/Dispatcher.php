<?php
/**
* 2014 Aretmic
*
* NOTICE OF LICENSE 
* 
* ARETMIC the Company grants to each customer who buys a virtual product license to use, and non-exclusive and worldwide. This license is 
* valid only once for a single e-commerce store. No assignment of rights is hereby granted by the Company to the Customer. It is also 
* forbidden for the * Customer to resell or use on other virtual shops Products made by ARETMIC. This restriction includes all resources 
* provided with the virtual product. 
*
* @author    Aretmic SA
* @copyright 2015 Aretmic SA
* @license   ARETMIC
* International Registered Trademark & Property of Aretmic SA
*/

class Dispatcher extends DispatcherCore {
	protected function __construct()
	{
		$contacformactive = Configuration::get('CONTACTFORM_FILENAME');
		$contactform = explode('.', $contacformactive);
		$contactform = ($contactform[0] == 'contact-form') ? 'contact' : 'cform';
		$contact_rule = array(
			'controller' => $contactform,
			'rule' => '{rewrite}-{fid}',
			'keywords' => array(
				'fid' => array('regexp' => '[0-9]+', 'param' => 'fid'),
				'rewrite' => array('regexp' => '[_a-zA-Z-\pL]*'),
				)
		);
		$this->default_routes[$contactform.'_rule'] = $contact_rule;
		parent::__construct();
	}
}
?>