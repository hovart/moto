<?php 
class attributes extends Module {
	function __construct(){
		$this->name = 'attributes';
		$this->tab = 'front_office_features';
        $this->author = 'MyPresta.eu';
		$this->version = '1.0.9';
        $this->dir = '/modules/htmlbox/';
		parent::__construct();
		$this->displayName = $this->l('Product Attributes on List');
		$this->description = $this->l('Module displays product attributes on list');
  
 
	}
  
	function install(){
        if (parent::install() == false 
	    OR $this->registerHook('displayProductOnList') == false
        ){
            return false;
        }
        return true;
	}

	function hookdisplayProductOnList($params){
	    $product=new Product($params['id_product']);
        $combinations=$product->getAttributeCombinations($this->context->language->id);
        $this->smarty->assign('combinations',$combinations);
        return $this->display(__FILE__, 'combinations.tpl');
	}
}
?>