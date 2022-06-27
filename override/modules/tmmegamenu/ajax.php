<?php

include_once('../../../config/config.inc.php');
if(isset($_GET['method'])){
    $action = $_GET['method'];
   
}elseif(isset($_POST['method'])){
    $action=$_POST['method'];
}

switch ($action) {
        /*===================upload=======================*/
        case 'uploadReceptionCsv':
		return uploadReceptionCsv();
		break;  
        case 'uploadCategoryCsv':
		return uploadCategoryCsv();
		break; 
        case 'uploadArticlesCsv':
		return uploadArticlesCsv();
		break; 
        /*===================End upload===================*/
        /*===================Edit=========================*/
        case 'editCategory':
		return editCategory();
		break;  
        case 'editArticle':
		return editArticle();
		break;
        case 'editReception':
		return editReception();
		break;
        case 'brandNameUpdate':
		return brandNameUpdate();
		break; 
        case 'brandDisplay':
		return brandDisplay();
		break;
        /*===================End edit=========================*/
        /*===================delete===========================*/
        case 'categoryDelete':
		return categoryDelete();
		break; 
         case 'articleDelete':
		return articleDelete();
		break; 
        case 'receptionDelete':
		return receptionDelete();
		break; 
         case 'brandDelete':
		return brandDelete();
		break;
        /*==================end delete=======================*/
        /*===================add=============================*/
        case 'addCategory':
		return addCategory();
		break;  
        case 'addArticle':
		return addArticle();
		break; 
        case 'AddNewReception':
		return AddNewReception();
		break; 
         case 'addNewMark':
		return addNewMark();
		break; 
        /*===================end add=============================*/
        /*===================Search fields=======================*/
        case 'getCylinder':
		return getCylinders();
		break;  
        case 'getModels':
		return getModels();
		break;
        case 'getYears':
		return getYears();
		break;
	    default:
		break;
}

/*===========================================Add=================================================*/

    function AddNewReception(){
          
            $mark           =   $_GET['mark'];
            $model          =   $_GET['model'];
            $cylinder       =   $_GET['cylinder'];
            $year           =   date_format(date_create($_GET['year']),"d.m.Y"); 
            $reception      =   $_GET['reception'];
            $back           =   $_GET['back'];
         
                      
           var_dump($mark);
           var_dump($model);
           var_dump($cylinder);
           var_dump($year);
           var_dump($reception);
        
            $sql ="INSERT INTO `marks` (`brand`, `status`) VALUES ('$name, '$status')";
            Db::getInstance()->insert('reception', array(
                    'mark'              => $mark,
                    'model'             => $model,
                    'cylinder'          => $cylinder,
                    'construction_year' => $year,
                    'reception_type'    => $reception,
                ));
            Tools::redirect($back);
        }
    function addCategory(){
            $category_id        =    $_GET['category_id'];
            $category_german    =    $_GET['category_german'];
            $category_french    =    $_GET['category_french'];
            $back               =    $_GET['back'];
                      
            Db::getInstance()->insert('tmmegamenu_category', array(
                    'category_id'            => $category_id,
                    'category_german'        => $category_german,
                    'category_french'        => $category_french,
                ));
          
            Tools::redirect($back);
        }
    function addArticle(){
            $article       =    $_GET['article'];
            $reception     =    $_GET['reception'];
            $category      =    $_GET['category'];
            $back          =    $_GET['back'];
                      
            Db::getInstance()->insert('articles', array(
                    'article_number'          => $article,
                    'reception_type'        => $reception,
                    'category'         => $category,
                ));
          
            Tools::redirect($back);
            
        }
    function addNewMark(){
            $status     =   0;
            $name       =   $_GET['markmame'];
            $back       =   $_GET['back'];
            
            if(isset($_GET['status']))
            $status=1;
           
            //$sql ="INSERT INTO `marks` (`brand`, `status`) VALUES ('$name, '$status')";
            Db::getInstance()->insert('marks', array(
                    'brand'         => $name,
                    'status'        => $status,
                ));
            Tools::redirect($back);
        }

/*===========================================End Add=============================================*/
/*===========================================Edit================================================*/

    function editReception(){
            $id             =   $_GET['id'];
            $mark           =   $_GET['mark'];
            $model          =   $_GET['model'];
            $cylinder       =   $_GET['cylinder'];
            $year           =   date_format(date_create($_GET['year']),"d.m.Y"); 
            $reception      =   $_GET['reception'];
            $back           =   $_GET['back'];
    
            $sql        =       "UPDATE `ps_reception` SET 
            mark='$mark', 
            model='$model', 
            cylinder='$cylinder' , 
            construction_year='$year',
            reception_type='$reception'
            WHERE id=$id";
            $ReceptionEdit = Db::getInstance()->execute($sql);
             if($ReceptionEdit){
               Tools::redirect($back); 
                 return;
            }
        }
    function editArticle(){
            $id             =   $_GET['id'];
            $article        =   $_GET['article'];
            $category       =   $_GET['category'];
            $reception      =   $_GET['reception'];
            $back           =   $_GET['back'];
            
            $sql        =       "UPDATE `ps_articles` SET 
            article_number='$article',  
            category='$category' ,
            reception_type='$reception'
            WHERE id=$id";
            $ReceptionEdit = Db::getInstance()->execute($sql);
             if($ReceptionEdit){
               Tools::redirect($back); 
                 return;
            }
        }
    function brandNameUpdate(){
            $id         =       $_GET['id'];
            $brand      =       $_GET['brand'];
            $sql        =       "UPDATE `ps_marks` SET brand='$brand' WHERE id=$id";
            $brandesNameUpdate = Db::getInstance()->execute($sql);
             if($brandesNameUpdate){
                echo $brand;
                return;
            }
        }
    function brandDisplay(){
            $id         =   $_GET['id'];
            $display    =   $_GET['status'];
            if($display == 1){
                 $sql = "UPDATE `ps_marks` SET status=1 WHERE id=$id";
            }else{
                 $sql = "UPDATE `ps_marks` SET status=0 WHERE id=$id";
            }
                $Brandes = Db::getInstance()->execute($sql);
        }  
    function editCategory(){
            $id                 =   $_GET['id'];
            $category_id        =   $_GET['category_id'];
            $category_german    =   $_GET['category_german'];
            $category_french    =   $_GET['category_french'];
        var_dump($id);
        var_dump($category_german);
        var_dump($category_french);
        
            $back               =   $_GET['back'];
            $sql                =       "UPDATE `ps_tmmegamenu_category` SET 
            category_id             = '$category_id',
            category_german         = '$category_german',
            category_french         = '$category_french'
            WHERE id='$id'";
            $ReceptionEdit = Db::getInstance()->execute($sql);
             if($ReceptionEdit){
               Tools::redirect($back); 
                 return;
            }
        }

/*===========================================Delete==========================================*/

    function categoryDelete(){
            $id=$_GET['id'];  
           
            $sql = "DELETE FROM `ps_tmmegamenu_category` WHERE id = $id";
            $Category = Db::getInstance()->execute($sql);
            
            if($Category){
                echo true;
                return;
            }
        }
    function articleDelete(){
            $id=$_GET['id'];         
            $sql = "DELETE FROM `ps_articles` WHERE id = $id";
            $BrandDelete = Db::getInstance()->execute($sql);
            if($BrandDelete){
                echo true;
                return;
            }
        }
    function receptionDelete(){
            $id=$_GET['id'];   
            $sql = "DELETE FROM `ps_reception` WHERE id = $id";
            $ReceptionDelete = Db::getInstance()->execute($sql);
            if($ReceptionDelete){
                echo true;
                return;
            }
        }
    function brandDelete(){
            $id=$_GET['id'];         
            $sql = "DELETE FROM `ps_marks` WHERE id = $id";
            $BrandDelete = Db::getInstance()->execute($sql);
            if($BrandDelete){
                echo true;
                return;
            }
        }

/*===========================================End Edit============================================*/
/*===========================================Upload csv==========================================*/

    function uploadReceptionCsv(){        
            $file_name      =   $_FILES['csv']['name'];
            $file_new_name  =   "Reception.csv";
            $file_size      =   $_FILES['csv']['size'];
            $file_tmp       =   $_FILES['csv']['tmp_name'];
            $file_type      =   $_FILES['csv']['type'];
            $file_ext       =   explode('.', $file_name);
            $file_ext       =   strtolower(end($file_ext));
            $back           =   $_POST['back'];
            $expensions     =   array("csv");
          if(in_array($file_ext,$expensions)=== false){
              $back=$back."&csvUploadMessage=Extension not allowed, please choose a csv file.";
            Tools::redirect($back);     
          }                  
          if($file_size > 10485760) {
            $back=$back."&csvUploadMessage=File size must be excately 10 MB";
            Tools::redirect($back);    
          }
            move_uploaded_file($file_tmp,"/home/prestashop-pp/www/override/modules/tmmegamenu/$file_new_name");
            if (($handle = fopen("Reception.csv", "r")) != FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) != FALSE) {
                $mark                   =   $data[0];
                $cylinder               =   $data[1];
                $model                  =   $data[2];
                $construction_year      =   $data[3];
                $reception_type         =   $data[4];
                $mark                   =   addslashes($mark);
                $model                  =   addslashes($model);
                Db::getInstance()->insert('reception', array(
                    'mark'              =>  $mark,
                    'cylinder'          =>  $cylinder,
                    'model'             =>  $model,
                    'construction_year' =>  $construction_year,
                    'reception_type'    =>  $reception_type,
                ));
            }
            fclose($handle);
        }
            $back=$back."&ReceptionCsvUploadMessage=Success";
            unlink('Reception.csv');
            Tools::redirect($back);            
        }
    function uploadCategoryCsv(){        
            $file_name      =   $_FILES['csv']['name'];
            $file_new_name  =   "Category.csv";
            $file_size      =   $_FILES['csv']['size'];
            $file_tmp       =   $_FILES['csv']['tmp_name'];
            $file_type      =   $_FILES['csv']['type'];
            $file_ext       =   explode('.', $file_name);
            $file_ext       =   strtolower(end($file_ext));
            $back           =   $_POST['back'];
            $expensions     =   array("csv");
          if(in_array($file_ext,$expensions)=== false){
              $back=$back."&CategoryCsvUploadMessage=Extension not allowed, please choose a csv file.";
            Tools::redirect($back);     
          }                  
          if($file_size > 10485760) {
            $back=$back."&CategoryCsvUploadMessage=File size must be excately 10 MB";
            Tools::redirect($back);    
          }
            move_uploaded_file($file_tmp,"/home/prestashop-pp/www/override/modules/tmmegamenu/$file_new_name");
            if (($handle = fopen("Category.csv", "r")) != FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) != FALSE) {
                $category_id         =   $data[0];
                $category_german     =   $data[1];
                $category_french     =   $data[2];
                $category_german     =   addslashes($category_german);        
                $category_french     =   addslashes( $category_french);
                Db::getInstance()->insert('tmmegamenu_category', array(
                    'category_id'             =>  $category_id ,
                    'category_german'          =>  $category_german ,
                    'category_french'         =>  $category_french ,
                    
                ));
            }
            fclose($handle);
        }
            $back=$back."&CategoryCsvUploadMessage=Success";
            unlink('Category.csv');
            Tools::redirect($back);            
        }
    function uploadArticlesCsv(){        
            $file_name      =   $_FILES['csv']['name'];
            $file_new_name  =   "Articles.csv";
            $file_size      =   $_FILES['csv']['size'];
            $file_tmp       =   $_FILES['csv']['tmp_name'];
            $file_type      =   $_FILES['csv']['type'];
            $file_ext       =   explode('.', $file_name);
            $file_ext       =   strtolower(end($file_ext));
            $back           =   $_POST['back'];
            $expensions     =   array("csv");
            
          if(in_array($file_ext,$expensions)=== false){
              $back=$back."&ArticleCsvUploadMessage=Extension not allowed, please choose a csv file.";
            Tools::redirect($back);     
          }                  
          if($file_size > 10485760) {
            $back=$back."&ArticleCsvUploadMessage=File size must be excately 10 MB";
            Tools::redirect($back);    
          }
            move_uploaded_file($file_tmp,"/home/prestashop-pp/www/override/modules/tmmegamenu/$file_new_name");
            if (($handle = fopen("Articles.csv", "r")) != FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) != FALSE) {
                $article_number         =   $data[0];
                $reception              =   $data[1];
                $category               =   $data[2];
                $article_number         = addslashes($article_number);
                $category               =addslashes($category);
                Db::getInstance()->insert('articles', array(
                    'article_number'    =>  $article_number,
                    'reception_type'    =>  $reception,
                    'category'          =>  $category,
                ));
            }
            fclose($handle);
        }
            $back=$back."&ArticleCsvUploadMessage=Success";
            unlink('Articles.csv');
            Tools::redirect($back);            
        }

/*===========================================End Upload csv======================================*/
/*===========================================Search fields=======================================*/

    function getCylinders(){
     global $cookie;
        $id_lang    =   $cookie->id_lang;
        $mark       =   $_GET['mark'];
        $options    =   [];
        $cylinders  =   Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT  DISTINCT  cylinder  FROM `ps_reception` WHERE mark = '$mark'   ORDER BY model ASC ");
        foreach ($cylinders as $cylinder) {

            if ($cylinder['cylinder']>=0 && $cylinder['cylinder']<100){
                $options[0] = '<option value="0-99">...-99</option>';
            } elseif ($cylinder['cylinder']>=100 && $cylinder['cylinder']<200 ) {
                $options[1] = '<option value="100-199">100-199</option>';
            }
            elseif ($cylinder['cylinder']>=200 && $cylinder['cylinder']<300){
                $options[2] = ' <option value="200-299">200-299</option>';
            }elseif ($cylinder['cylinder']>=300 && $cylinder['cylinder']<400){
                $options[3] = ' <option value="300-399">300-399</option>';
            }elseif ($cylinder['cylinder']>=400 && $cylinder['cylinder']<500){
                $options[4] =' <option value="400-499">400-499</option>';
            }elseif ($cylinder['cylinder']>=500 && $cylinder['cylinder']<600){
                $options[5] =' <option value="500-599">500-599</option>';
            }elseif ($cylinder['cylinder']>=600 && $cylinder['cylinder']<700){
                $options[6] =' <option value="600-699">600-699</option>';
            }elseif ($cylinder['cylinder']>=700 && $cylinder['cylinder']<800){
                $options[7] =' <option value="700-799">700-799</option>';
            }elseif ($cylinder['cylinder']>=800 && $cylinder['cylinder']<900){
                $options[8] =' <option value="800-899">800-899</option>';
            }elseif ($cylinder['cylinder']>=900 && $cylinder['cylinder']<1000){
                $options[9] =' <option value="900-999">900-999</option>';
            }elseif ($cylinder['cylinder']>=1000 && $cylinder['cylinder']<1100){
                $options[10] =' <option value="1000-1099">1000-1099</option>';
            }elseif ($cylinder['cylinder']>=1100 ){
                $options[11] =' <option value="1100-...">1100-...</option>';

            }
        }
        if($id_lang == 1){
             $optionsString = '<option value="" >Choisissez une cylindrée</option>';
             }
             elseif ($id_lang == 2) {
                 $optionsString = '<option value="" >Hubraum wählen</option>';
             }

        for ($i=0;$i<12;$i++){
            if(isset($options[$i])){
                $optionsString.=$options[$i];
            }
        }
            var_dump($cylinders);

        echo $optionsString;

    }
    function getModels(){
       
          global $cookie;
       $id_lang = $cookie->id_lang;
        $mark       =   $_GET['mark'];
        $cylinder   =   $_GET['cylinder'];
        $cylinderArray=explode('-',$cylinder);
        $cylinderMin=$cylinderArray[0];
        $cylinderMax=$cylinderArray[1];
         if($cylinderMin=='1100'){
            $models  =   Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT  DISTINCT  model  FROM `ps_reception` WHERE mark = '$mark' AND cylinder >='$cylinderMin'  ORDER BY model ASC ");
        }else{
            $models  =   Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT  DISTINCT  model  FROM `ps_reception` WHERE mark = '$mark' AND (cylinder >='$cylinderMin' AND cylinder<='$cylinderMax') ORDER BY model ASC ");

        }
        if($id_lang == 1){
             $options = '<option value="" >Choisissez un modèle</option>';
             }
             elseif ($id_lang == 2) {
                 $options = '<option value="" >Modell wählen</option>';
             }
        foreach ($models as $model) {
      
        
            $options.="<option value='".str_replace(" ","%20",$model['model'])."'>".$model['model']."</option>"; // to do:
        }
        echo $options;
    }
    function getYears(){
        global $cookie;
        $id_lang = $cookie->id_lang;
        $mark       =   $_GET['mark'];
        $model      =   str_replace("%20"," ",$_GET['model']); // to do:
        $cylinder   =   $_GET['cylinder'];
        $cylinderArray=explode('-',$cylinder);
        $cylinderMin=$cylinderArray[0];
        $cylinderMax=$cylinderArray[1];
        if($cylinderMin=='1100') {
            $years = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT  DISTINCT  construction_year  FROM `ps_reception` WHERE mark = '$mark' AND cylinder >='$cylinderMin' AND model ='$model'  ORDER BY construction_year ASC");
        }else{

            $years = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("
                SELECT  DISTINCT  construction_year  FROM `ps_reception` WHERE mark = '$mark' AND (cylinder >='$cylinderMin' AND cylinder<='$cylinderMax') AND model ='$model' GROUP BY construction_year  ORDER BY construction_year ASC");
        }
        $yearOptions = [];
           
        if($id_lang == 1){
             $options = '<option value="" >Choisissez une année</option>';
             }
             elseif ($id_lang == 2) {
                 $options = '<option value="" >Baujahr  wählen</option>';
             }
        
        foreach ($years as $year) {
            $yearOptions[$year['construction_year']] = date("Y", strtotime($year['construction_year']));
        }
        $years = array_unique($yearOptions);
          asort($years);

        foreach ($years as $key => $year) {

            $options.="<option value='".$key."'>".$year."</option>";

        }
        echo $options;
    }

/*===========================================End Search fields===================================*/
?>




