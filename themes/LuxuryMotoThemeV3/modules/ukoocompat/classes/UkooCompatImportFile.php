<?php
/**
 * Recherche de produits par compatibilité
 *
 * @author    Guillaume Heid - Ukoo <modules@ukoo.fr>
 * @copyright Ukoo 2015
 * @license   Ukoo - Tous droits réservés
 *
 * "In Ukoo we trust!"
 */

class UkooCompatImportFile extends ObjectModel
{
    public $id_ukoocompat_import_file;
    public $filename;
    public $col_separator;
    public $create_filters;
    public $create_criteria;
    public $create_alias;
    public $link_to_product;
    public $status;
    public $date_add;
    public $date_import;
    public $date_analyze;

    public static $definition = array(
        'table' => 'ukoocompat_import_file',
        'primary' => 'id_ukoocompat_import_file',
        'multilang' => false,
        'fields' => array(
            'filename' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 120),
            'col_separator' => array(
                'type' => self::TYPE_STRING,
                'size' => 1),
            'create_filters' => array(
                'type' => self::TYPE_BOOL),
            'create_criteria' => array(
                'type' => self::TYPE_BOOL),
            'create_alias' => array(
                'type' => self::TYPE_BOOL),
            'link_to_product' => array(
                'type' => self::TYPE_STRING,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120),
            'status' => array(
                'type' => self::TYPE_INT,
                'required' => true),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDateFormat'),
            'date_import' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDateFormat'),
            'date_analyze' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDateFormat')));

    /**
     * Création des tables des fichiers d'import
     * @return mixed
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_import_file`(
			`id_ukoocompat_import_file` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `filename` VARCHAR(120) DEFAULT NULL,
			`col_separator` VARCHAR(1) DEFAULT \';\',
            `create_filters` TINYINT(1) NOT NULL DEFAULT \'1\',
            `create_criteria` TINYINT(1) NOT NULL DEFAULT \'1\',
            `create_alias` TINYINT(1) NOT NULL DEFAULT \'0\',
            `link_to_product` VARCHAR(120) DEFAULT NULL,
			`status` INT(10) NOT NULL DEFAULT \'0\',
			`date_add` datetime NOT NULL,
			`date_import` datetime NOT NULL,
			`date_analyze` datetime NOT NULL,
			PRIMARY KEY (`id_ukoocompat_import_file`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables des fichiers d'import
     * @return mixed
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_import_file`');
    }

    /**
     * Retourne la liste des IDS d'import à partir d'un statut (static)
     * @param $status
     * @return array
     */
    public static function getImportIdsFromStatus($status)
    {
        $ret = array();
        $imports = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_ukoocompat_import_file`
			FROM `'._DB_PREFIX_.'ukoocompat_import_file`
			WHERE `status` = '.(int)$status);
        foreach ($imports as $import) {
            $ret[] = $import['id_ukoocompat_import_file'];
        }
        return $ret;
    }
}
