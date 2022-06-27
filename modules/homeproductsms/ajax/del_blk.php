<?php
/**
* 2007-2015 Mack Stores
*
* NOTICE OF LICENSE
*
* This code is a property of MackStores. In No way any one is authorised to use this code  or modify this code and redistribute without prior
* permission from the authour i.e MackStores
*
*
*  @author    Mack Stores contact:-sales@mackstores.com
*  @copyright 2007-2015 Mack Stores
*  International Registered Trademark & Property of Mack Stores
*/
include_once('../../../config/config.inc.php');
include_once('../../../init.php');
include_once('../homeproductsms.php');

$del_id = Tools::getValue('del_id');
$home_prd_ms = new Homeproductsms();

if (!Tools::isSubmit('hmprdmsseckey') || Tools::getValue('hmprdmsseckey') != $home_prd_ms->secure_key )
	die(1);

if ($del_id)
{

		Db::getInstance()->Execute('DELETE
                                       FROM `'._DB_PREFIX_.'homeproductsms`
                                       WHERE `id_ms` = '.(int)$del_id.'
                                       ');

		Db::getInstance()->Execute('DELETE
                                       FROM `'._DB_PREFIX_.'homeproductsms_lang`
                                       WHERE `id_mss` = '.(int)$del_id.'
                                       ');
}



/* /write a log

            $log_file = "log_readme_install.txt";
            //if there is a log file get rid of it mack
            if (@file_exists($log_file))
            {
                unlink($log_file);
            }

            //open the log file mack
            if (!@file_exists($log_file))
            {
                fopen($log_file, 'w');
            }

            $filename = $log_file;
            $handle = fopen($filename, 'a');

            //write the goodies to the file mack
        //   foreach($tablems as $item)
           // {
            //	$item = explode("_", $item[1]);
              //  $somecontent = $item." \n";
               // fwrite($handle, var_dump($somecontent));
           // }
             fwrite($handle, $a);

             ob_start();
			 var_dump($del_id);
			 $data = ob_get_clean();
			$fp = fopen($log_file, "w");
			 fwrite($fp, $data);
			 fclose($fp);

            fclose($handle);
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

*/


?>