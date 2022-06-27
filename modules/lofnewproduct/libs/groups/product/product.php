<?php
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
if (!defined('_CAN_LOAD_FILES_')){
    define('_CAN_LOAD_FILES_',1);
}
if( !class_exists('LofNewProductDataSource', false) ){  
	class LofNewProductDataSource extends LofNewProductDataSourceBase{
        function getNewProducts( $params ){
            global $cookie, $link;
           	$id_lang = intval($cookie->id_lang);
           	
            $maxDesc = $params->get( 'des_max_chars',100);
			$limit_items = $params->get( 'limit_items',5);
    		
            $home_sorce = $params->get("home_sorce");
            if($home_sorce == "productids"){
                $product_ids = $params->get("product_ids"); 
                $where = ' AND p.id_product IN ('.$product_ids.')';
				if(_PS_VERSION_ < "1.5"){
					$newProducts = $this->getProductsV14($where,0,$limit_items,"p.id_product");
                }else{
					$newProducts = $this->getProductsV15($where,0,$limit_items,"p.id_product");
				}
            }else{
                $newProducts = Product::getNewProducts((int)(intval($cookie->id_lang)), 0, $limit_items);
                $newProducts = Product::getProductsProperties($id_lang, $newProducts);   
            }

            foreach( $newProducts as $k=>$v){
				//add data for product
                $newProducts[$k]['description']=substr(trim(strip_tags($newProducts[$k]['description_short'])),0, $maxDesc);
                $newProducts[$k]['price']=Tools::displayPrice($newProducts[$k]['price']);                 
                $newProducts[$k] = $this->parseImages( $newProducts[$k],$params );                
                $newProducts[$k] = $this->generateImages( $newProducts[$k], $params );
            }
            
            return $newProducts;
        }
        /**
        * Get data source: 
        */
    	public static function getProductsV14($where='', $limiStart=0, $limit=10, $order=''){		
    		global $cookie, $link;
        	$id_lang = intval($cookie->id_lang);
    		$sql = '
    		SELECT DISTINCT p.id_product, p.quantity, p.id_category_default , p.ean13 ,p.out_of_stock, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF( now(), p.date_add) as newnumdays, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new,
    			(p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1)) AS orderprice       
    		FROM `'._DB_PREFIX_.'category_product` cp
    		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
            LEFT JOIN `'._DB_PREFIX_.'category` c ON c.`id_category` = cp.`id_category`
    		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
    		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)($id_lang).')
    		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
    		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
    		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
    		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
    		                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
    	                                           	   AND tr.`id_state` = 0)
    	    LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
    		LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.(int)($id_lang).')
    		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`		
    		WHERE  p.`active` = 1'.$where;			
    		$sql .= ' ORDER BY '.$order
    		.' LIMIT '.$limiStart.','.$limit;   
            
    		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
            
    		return Product::getProductsProperties($id_lang, $result);
    	}
		
        public static function getProductsV15($where='', $limiStart=0, $limit=10, $order=''){		
    		global $cookie, $link;
        	$id_lang = intval($cookie->id_lang);
			
        	$context = Context::getContext();
			$id_country = (int)($context->country->id);
			$front = true;
			if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
				$front = false;
			
            $sql = 'SELECT DISTINCT p.`id_product`, p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
					il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new,
					(product_shop.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1)) AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group` AND tr.`id_country` = '.(int)$context->country->id.'
					AND tr.`id_state` = 0
					AND tr.`zipcode_from` = 0)
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
				AND ((product_attribute_shop.id_product_attribute IS NOT NULL OR pa.id_product_attribute IS NULL) 
					OR (product_attribute_shop.id_product_attribute IS NULL AND pa.default_on=1))
					AND product_shop.`active` = 1'.$where
					.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
				' ORDER BY '.$order.' LIMIT '.$limiStart.','.$limit;
			
    		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);		
    		$return = Product::getProductsProperties($id_lang, $result);
			
			return $return;
    	}
        /**
		 * get main image and thumb
		 *
		 * @param poiter $row .
		 * @return void
		 */
		public  function parseImages( $product, $params ){
			global $link;
            
            $isRenderedMainImage = 	$params->get("cre_main_size",0);
			if(_PS_VERSION_ <= "1.5.0.17")
				$mainImageSize       =  $params->get("main_img_size",'thickbox');
			else
				$mainImageSize       =  $params->get("main_img_size",'thickbox_default');
            
            if( $isRenderedMainImage ) { 
				if((int)Configuration::get('PS_REWRITING_SETTINGS') == 1){
					$product["mainImge"] = $this->getImageLink($product["link_rewrite"], $product["id_image"] );
				}else{
					$product["mainImge"] = $link->getImageLink($product["link_rewrite"], $product["id_image"] );
				}
	        } else{
	        	$product["mainImge"] = $link->getImageLink($product["link_rewrite"], $product["id_image"], $mainImageSize ); 
	        }
            $product["thumbImge"] = $product["mainImge"];

            return $product; 
		}

	}
}
?>