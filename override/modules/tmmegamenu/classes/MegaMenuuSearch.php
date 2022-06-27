<?php 
class MegaMenuSearch {
    
    public function getBrandes(){
        
        if(isset($_GET["mkarkPage"])){
             $page = $_GET["mkarkPage"];
        }else{
            
        $page=1;
        }
           
        $shift = 35 * ($page - 1);
     
        $Brandes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT *   FROM `ps_marks`  ORDER BY id  LIMIT $shift, 35");
        return $Brandes;        
    }
    public function getCategories(){
        if(isset($_GET["categoryPage"])){
             $page = $_GET["categoryPage"];
        }else{ 
        $page=1;
        }
        $shift = 35 * ($page - 1);
        $Categories = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT *   FROM `ps_tmmegamenu_category`  ORDER BY id  LIMIT $shift, 35");
        return $Categories;        
    }
    public function getArticles(){
        
        if(isset($_GET["articlePage"])){
             $page = $_GET["articlePage"];
        }else{ 
        $page=1;
        }
        $shift = 35 * ($page - 1);
        $articles = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT *   FROM `ps_articles`  ORDER BY id  LIMIT $shift, 35");
        return $articles;        
    }
    public function getAllBrandes(){  
        $Brandes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT *   FROM `ps_marks`  ORDER BY id ");
        return $Brandes;        
    }
    public function getReception(){
     if(isset($_GET["receptionPage"])){
         $page = $_GET["receptionPage"];
        } else{
                    $page=1;
        }
        $shift = 35 * ($page - 1);
        $receptions = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT * FROM "._DB_PREFIX_."reception LIMIT $shift, 35");          
        return $receptions;  
    }
    
    
    /*================Paginations======================*/
    public function marksPagination(){
        $Brandes        =   Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(" SELECT * FROM "._DB_PREFIX_."marks ");
        $count          =   count($Brandes);
        $base_url       =   Tools::getHttpHost(true).__PS_BASE_URI__;
        $url            =   $base_url.$_SERVER['REQUEST_URI']."&mkarkPage=";
        $page_size      =   35;
         if(isset($_GET["mkarkPage"])){$current_page = $_GET["mkarkPage"];  }
            else{$current_page=1;  }
        $pagination= $this->pagination($page_size,$url,$count,$current_page);
        return $pagination;
       
    } 
    public function categoryPagination(){
        $Categorys      =   Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(" SELECT * FROM "._DB_PREFIX_."tmmegamenu_category");
        $count          =   count($Categorys);
        $base_url       =   Tools::getHttpHost(true).__PS_BASE_URI__;
        $url            =   $base_url.$_SERVER['REQUEST_URI']."&categoryPage=";
        $page_size      =   35;
         if(isset($_GET["categoryPage"])){$current_page = $_GET["categoryPage"];  }
            else{$current_page=1;}
        $pagination= $this->pagination($page_size,$url,$count,$current_page);
        return $pagination;
       
    }
    public  function ArticlesPagination(){ 
            $reception = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT * FROM "._DB_PREFIX_."articles");
            $count = count($reception);
            $base_url       =Tools::getHttpHost(true).__PS_BASE_URI__;
            $url=$base_url.$_SERVER['REQUEST_URI']."&articlePage=";
            if(isset($_GET["articlePage"])){
                         $current_page = $_GET["articlePage"];
            }
            else{
                       $current_page=1;
            }
            $page_size=35;
             $pagination= $this->pagination($page_size,$url,$count,$current_page);
            return $pagination;

        }
    public  function receptionPagination(){ 
            $reception = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT * FROM "._DB_PREFIX_."reception");
            $count = count($reception);
            $base_url       =Tools::getHttpHost(true).__PS_BASE_URI__;
            $url=$base_url.$_SERVER['REQUEST_URI']."&receptionPage=";
            if(isset($_GET["receptionPage"])){
                         $current_page = $_GET["receptionPage"];
            }
            else{
                       $current_page=1;
            }
            $page_size=35;
             $pagination= $this->pagination($page_size,$url,$count,$current_page);
            return $pagination;

        }
    
    
    
    
    
    
    
    private function pagination($page_size,$url,$count,$current_page){
        $pages = ceil($count/$page_size);
         if ($count > $page_size) {
        $pagination = "";
        $pagination .= '<div class="row">';
        $pagination .= '<ul class="pagination">';
        $pagination .= '<li><a href="'.$url .'1">&laquo;</a></li>';
        if ($current_page - 1 > 0) {
            $pagination .= '<li><a href="'.$url  . ($current_page - 1) . '">&#8249;</a></li>';
        } else {
            $pagination .= '<li><a href="'.$url  . $current_page . '">&#8249;</a></li>';
        }
        if ($pages > 6) {
            if ($current_page >= 1 && $current_page < $pages - 2) {
                if ($current_page < 3) {
                    if ($current_page == 1) {
                        $pagination .= '<li><a href="'.$url .'1">1</a></li>';
                        $pagination .= '<li><a href="'.$url .'2">2</a></li>';
                        $pagination .= '<li><a href="'.$url .'3">3</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . $pages . '">' . $pages . '</a></li>';
                    } else {
                        $pagination .= '<li><a href="'.$url . ($current_page - 1) . '">' . ($current_page - 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url  . $current_page . '">' . $current_page . '</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">' . ($current_page + 1) . '</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . $pages . '">' . $pages . '</a></li>';
                    }
                } else {
                    if ($current_page == 3) {
                        $pagination .= '<li><a href="'.$url .'1">1</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page - 1) . '?>">' . ($current_page - 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url . $current_page . '">' . $current_page . '</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">' . ($current_page + 1) . '</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . $pages . '">' . $pages . '</a></li>';
                    } else {
                        $pagination .= '<li><a href="'.$url .'1">1</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page - 1) . '">' . ($current_page - 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url . $current_page . '">' . $current_page . '</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">' . ($current_page + 1) . '</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . $pages . '">' . $pages . '</a></li>';
                    }
                }
                } else {
                    if ($current_page == $pages - 2) {
                        $pagination .= '<li><a href="'.$url .'1">1</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page - 1) . '">' . ($current_page - 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url . $current_page . '">' . $current_page . '</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">' . ($current_page + 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url . $pages . '">' . $pages . '</a></li>';
                    } else {
                        $pagination .= '<li><a href="'.$url .'1">1</a></li>';
                        $pagination .= '<li><a>...</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page - 1) . '">' . ($current_page - 1) . '</a></li>';
                        $pagination .= '<li><a href="'.$url . ($current_page) . '">' . $current_page . '</a></li>';
                        if ($current_page != $pages) {
                            $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">' . ($current_page + 1) . '</a></li>';
                        }
                    }
                }
            } else {
                for ($i = 1; $i <= $pages; $i++) {
                    $pagination .= '<li><a href="'.$url . $i . '">' . $i . '</a></li>';
                }
            }
            if ($current_page + 1 < $pages) {
                $pagination .= '<li><a href="'.$url . ($current_page + 1) . '">&#8250;</a></li>';
            } else {
                $pagination .= '<li><a href="'.$url . $pages . '">&#8250;</a></li>';
            }
            $pagination .= '<li><a href="'.$url . $pages . '">&raquo;</a></li>';
            $pagination .= '</ul>';
            $pagination .= '</div>';

            return $pagination;
        } 
    }
    
    
}
?>