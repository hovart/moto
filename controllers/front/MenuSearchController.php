<?php 
session_start();
@ini_set('display_errors', 'off');

class MenuSearchControllerCore extends FrontController
{
	public $php_self = 'MenuSearch';
	
		public function init() {
		
			parent::init();
		}


    public function setMedia()
    {
        parent::setMedia();
       
    }
	public function initContent() {

		parent::initContent();
		$key="key";
		$query=null;
		$articles=[];

			unset($reception);
			unset($mark) ;
			unset($cylinder);
			unset($model);
			unset($year);
			
	
	
		if(isset($_GET['reception_by_type']) && $_GET['reception_by_type']!=null){
			$reception 		=	$_GET['reception_by_type'];
			$query.="ps_articles.reception_type="."'".$reception."'"; 
		 $this->context->smarty->assign('reception', $reception);
		}
		if(isset($_GET['marks']) && $_GET['marks']!=null ){

			$mark 		=	$_GET['marks'];
			if(isset($reception)){
					$query.=" AND ps_reception.mark="."'".$mark."'"; 
			}else{
					$query.="mark="."'".$mark."'"; 
			}
		
		 $this->context->smarty->assign('mark', $mark);
		}
		if(isset($_GET['cylinder'])&&  $_GET['cylinder']!=null ){

			$cylinder		=	$_GET['cylinder'];
				$cylinderArray	=	explode('-',$cylinder);
    		$cylinderMin	=	$cylinderArray[0];
    		$cylinderMax	=	$cylinderArray[1];
    		if ($cylinderMin==1100) {
    			$query.=" AND cylinder>".$cylinderMin;
    				
    		}
    		else{
    			$query.=" AND cylinder>".$cylinderMin." AND cylinder< ".$cylinderMax;
    		}	
			
			 $this->context->smarty->assign('cylinder', $cylinder);
		}
		else{
				$cylinder=null;
			$this->context->smarty->assign('cylinder', $cylinder);

		}
		if(isset($_GET['model'])&& $_GET['model']!=null){
			$model		=	$_GET['model'];
			$query      .=" AND model="."'".$model."'";
            $model=str_replace("%20"," ",$_GET['model']);
			 $this->context->smarty->assign('model', $model);
		}
		if(isset($_GET['year']) && $_GET['year'] != null){
			$year		=	$_GET['year'];
			$query.=" AND construction_year="."'".$year."'";
			 $this->context->smarty->assign('year', $year);
			
		}
		if($query){
			$articles = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
               SELECT * 
				FROM ps_reception
				RIGHT JOIN ps_articles
				ON ps_reception.reception_type=ps_articles.reception_type WHERE $query ");
		}	
		if($articles){
					if(!$reception ){
					$reception=$articles[0]['reception_type'];
						 $this->context->smarty->assign('reception', $reception);
					}
					if (!$cylinder) {
						$cylinder=$articles[0]['cylinder'];
						 $this->context->smarty->assign('cylinder', $cylinder);
					}
					if (!$mark) {
						$mark =$articles[0]['mark'];
						 $this->context->smarty->assign('mark', $mark);
					}
					if (!$model ) {
						$model =$articles[0]['model'];
						 $this->context->smarty->assign('model', $model);
					}
					if (!$year ) {
						$year =$articles[0]['construction_year'];
						 $this->context->smarty->assign('year', $year);
					}

			$articlesReceptionType=$articles[0]['reception_type'];
				$categorys = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
	               SELECT *
						FROM ps_articles
						RIGHT JOIN ps_tmmegamenu_category
						ON ps_articles.category= ps_tmmegamenu_category.category_id  WHERE ps_articles.reception_type ='$articlesReceptionType' ");

				if($this->context->language->id ==1)
				{
							$a=$categorys[0]['category_french'];
							foreach ($categorys as $category) {
						
								if($a==$category['category_french']){
									$article_number="'".$category['article_number']."'";
								    $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
					                SELECT    id_product  FROM ps_product WHERE    ps_product.reference=$article_number");
								if($row){
									$product = new Product($row[0]['id_product'],false, $this->context->language->id);
   		 						 $image = Image::getCover($product->id);
   		 						 $link = new Link;
								$imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], 'home_default');
								$articlesByCategorys[$a][$product->id]['images']="//".$imagePath;
								}
								
								$articlesByCategorys[$a][$product->id]['products']=$product;
								}
								$a=$category['category_french'];
							}
				}else
				{
					$a=$categorys[0]['category_german'];
							foreach ($categorys as $category) {
						
								if($a==$category['category_german']){
									$article_number="'".$category['article_number']."'";
								    $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
					                SELECT    id_product  FROM ps_product WHERE    ps_product.reference=$article_number");
								if($row){
									$product = new Product($row[0]['id_product'],false, $this->context->language->id);
   		 						 $image = Image::getCover($product->id);
   		 						 $link = new Link;
								$imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], 'home_default');
								$articlesByCategorys[$a][$product->id]['images']="//".$imagePath;
								}
								
								$articlesByCategorys[$a][$product->id]['products']=$product;
								}
								$a=$category['category_german'];
							}
					
				}
					$this->context->smarty->assign('articlesByCategorys', $articlesByCategorys);
					$this->context->smarty->assign('link', $link);	
		}
				
		$this->setTemplate(_PS_THEME_DIR_.'tmmegamenuarticle.tpl');
    }  
}
?>