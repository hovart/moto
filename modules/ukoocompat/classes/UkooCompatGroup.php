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

class UkooCompatGroup extends ObjectModel
{
    public $id_ukoocompat_group;
    public $id_ukoocompat_search_filter;
    public $active;
    public $position;
    public $selected;

    /* Attributs de langue */
    public $name;

    public static $definition = array(
        'table' => 'ukoocompat_group',
        'primary' => 'id_ukoocompat_group',
        'multilang' => true,
        'fields' => array(
            'id_ukoocompat_search_filter' => array('type' => self::TYPE_INT, 'required' => true),
            'active' => array('type' => self::TYPE_BOOL, 'required' => false),
            'position' => array('type' => self::TYPE_INT, 'required' => false),
            'selected' => array('type' => self::TYPE_STRING, 'required' => false),
            /* table _lang */
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120)));

    /**
     * Création des tables de groupe
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_group`(
			`id_ukoocompat_group` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_ukoocompat_search_filter` INT(10) UNSIGNED NOT NULL,
			`active` TINYINT(1) NOT NULL DEFAULT \'1\',
			`position` INT(10) UNSIGNED NOT NULL DEFAULT \'999\',
			`selected` TEXT DEFAULT NULL,
			PRIMARY KEY (`id_ukoocompat_group`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_group_lang`(
			`id_ukoocompat_group` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`name` VARCHAR(120) NOT NULL,
			PRIMARY KEY (`id_ukoocompat_group`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables de groupe
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_group`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_group_lang`');
    }

    /**
     * Met à jour la position d'un groupe de critères
     * @param null $position
     * @return bool
     */
    public function updatePosition($position = null)
    {
        return UkooCompatGroup::updateGroupPosition($this->id, $position);
    }

    /**
     * Met à jour la position d'un groupe de critères (static)
     * @param $id_group
     * @param null $position
     * @return bool
     */
    public static function updateGroupPosition($id_group, $position = null)
    {
        if ($position === null) {
            if (!$position = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
                'SELECT COUNT(*)
                FROM `'._DB_PREFIX_.'ukoocompat_group`
                WHERE 1'
            )
            ) {
                return false;
            }

            if (!Db::getInstance()->update(
                'ukoocompat_group',
                array(
                    'position' => $position - 1
                ),
                '`id_ukoocompat_group` = '.(int)$id_group
            )
            ) {
                return false;
            }

                return true;
        } else {
            // on met à jour le group avec sa nouvelle position
            if (!Db::getInstance()->update(
                'ukoocompat_group',
                array(
                    'position' => (int)$position
                ),
                '`id_ukoocompat_group` = '.(int)$id_group
            )
            ) {
                return false;
            }

            return true;
        }
    }

    /**
     * Retourne la prochaine position pour la création d'un groupe de critères
     * @return int
     */
    public static function getNextGroupPosition()
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(*) FROM `'._DB_PREFIX_.'ukoocompat_group`'
        );
    }
}
