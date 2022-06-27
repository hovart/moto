<?php

if (!defined('_PS_VERSION_'))
     die(header('HTTP/1.0 404 Not Found'));

/**
 * Description of totRules
 *
 * @author Guillaume Deloince
 */
class totSplashScreenModel extends ObjectModel {

     public $id_totsplashscreen,
            $id_template, 
            $type, 
            $id_type, 
            $child_category, 
            $date_start, 
            $date_end, 
            $nb_jour_avant_reapparition, 
            $id_shop, 
            $id_shop_group, 
            $message,
            $name,
            $totsplashscreen_version_cookie;

     //For 1.4
     protected $table = 'totsplashscreen';
     protected $identifier = 'id_totsplashscreen';

     protected $fieldsValidate = array(
         'id_template' => 'isUnsignedId',
         'type' => 'isString',
         'id_type' => 'isInt',
         'child_category' => 'isInt', 
         'date_start' => 'isDateFormat', 
         'date_end' => 'isDateFormat', 
         'nb_jour_avant_reapparition' => 'isInt',
         'name' => 'isString', 
         'totsplashscreen_version_cookie' => 'isInt'
     );

     //For 1.5
     public static $definition = array(
        'table' => 'totsplashscreen', 
        'primary' => 'id_totsplashscreen',
        'fields' => array(
            'name' => array('type' => 3, 'validate' => 'isString', 'required' => true),
            'id_template' => array('type' => 1, 'validate' => 'isInt'),
            'type' => array('type' => 3, 'validate' => 'isString'),
            'id_type' => array('type' => 1, 'validate' => 'isInt'),
            'child_category' => array('type' => 1, 'validate' => 'isInt'),
            'date_start' => array('type' => 5, 'validate' => 'isDateFormat'),
            'date_end' => array('type' => 5, 'validate' => 'isDateFormat'),
            'nb_jour_avant_reapparition' => array('type' => 1, 'validate' => 'isInt'), 
            'totsplashscreen_version_cookie' => array('type' => 1, 'validate' => 'isInt')
        )
    );

     public function getFields()
     {
        if(version_compare('1.5.0', _PS_VERSION_, '<'))
            return parent::getFields();
        else
            return array(
                'id_totsplashscreen' => (int)$this->id_totsplashscreen, 
                'id_template' => (int)$this->id_template, 
                'type' => pSQL($this->type),
                'id_type' => (int)$this->id_type, 
                'child_category' => (int)$this->child_category, 
                'date_start' => $this->date_start,
                'date_end' => $this->date_end,
                'nb_jour_avant_reapparition' => $this->nb_jour_avant_reapparition, 
                'name' => $this->name,
                'totsplashscreen_version_cookie' => $this->totsplashscreen_version_cookie
            );
     }

 

     public function __construct($id = false, $id_lang = false) {
          parent::__construct($id, $id_lang);
     }


     public static function findSplashScreen($type, $id_type)
     {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM " . _DB_PREFIX_ . "totsplashscreen 
                WHERE (
                        (date_start <= '" . $today . "' AND date_end >= '" . $today . "')
                             OR 
                        (date_start <= '" . $today . "' AND date_end = '0000-00-00 00:00:00')
                             OR
                        (date_start < '0000-00-00 00:00:00' AND date_end >= '" . $today . "')
                            OR 
                        (date_start = '0000-00-00 00:00:00' AND date_end = '0000-00-00 00:00:00')
                    )
                    AND type = '" . $type ."' 
                    AND id_type = '" . $id_type ."'
                ORDER BY id_totsplashscreen DESC";
       $splashscreen = Db::getInstance()->getRow($sql); 
       if(!$splashscreen)
       {
            $sql = "SELECT * FROM " . _DB_PREFIX_ . "totsplashscreen 
                WHERE (
                        (date_start <= '" . $today . "' AND date_end >= '" . $today . "')
                             OR 
                        (date_start <= '" . $today . "' AND date_end = '0000-00-00 00:00:00')
                             OR
                        (date_start < '0000-00-00 00:00:00' AND date_end >= '" . $today . "')
                            OR 
                        (date_start = '0000-00-00 00:00:00' AND date_end = '0000-00-00 00:00:00')
                    )
                    AND type = 'none' 
                ORDER BY id_totsplashscreen DESC";
            $splashscreen = Db::getInstance()->getRow($sql);
       }

       return $splashscreen;

     }

     public static function getSplashScreens()
     {
        $sql = "SELECT *, sst.name AS template, ss.name AS splashscreen FROM " . _DB_PREFIX_ . "totsplashscreen AS ss
                LEFT JOIN " . _DB_PREFIX_ . "totsplashscreen_template AS sst ON ss.id_template = sst.id_totsplashscreen_template" ;
        return Db::getInstance()->ExecuteS($sql);
     }
}

?>
