<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('tptnsearch.php');

$context = Context::getContext();
$tptn_search = new TptnSearch();

$ajax_search = Tools::getValue('ajaxSearch');

$query = urldecode(Tools::getValue('q'));
    if ($ajax_search){
        $searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
	                
        foreach ($searchResults as &$product){
            //$image = Image::getImages((int)(Tools::getValue('id_lang')), $product['id_product']);
            $cover = Product::getCover($product['id_product']);           
            $product['product_link'] = $context->link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);
            $product['product_image'] = $context->link->getImageLink($product['prewrite'], $cover['id_image'], 'small_default');
        }
        
	die(Tools::jsonEncode($searchResults));
    }