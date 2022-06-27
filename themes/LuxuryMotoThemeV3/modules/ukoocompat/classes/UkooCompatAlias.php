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

class UkooCompatAlias extends ObjectModel
{
    public $alias;
	public $link;
    public $image;

    /* Attributs de langue */
    public $description;

    public static $definition = array(
        'table' => 'ukoocompat_alias',
        'primary' => 'id_ukoocompat_alias',
        'multilang' => true,
        'fields' => array(
            'alias' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 120),
            'link' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'image' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'validate' => 'isString',
                'size' => 120),
            /* table _lang */
            'description' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString',
                'size' => 399999999999)));

    /**
     * Création des tables d'alias
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_alias`(
			`id_ukoocompat_alias` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`alias` VARCHAR(120) NOT NULL,
			`link` VARCHAR(255) DEFAULT NULL,
			`image` VARCHAR(120) DEFAULT NULL,
			PRIMARY KEY (`id_ukoocompat_alias`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_alias_lang`(
			`id_ukoocompat_alias` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`description` TEXT,
			PRIMARY KEY (`id_ukoocompat_alias`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_alias_criterion`(
			`id_ukoocompat_alias_instance` INT(10) UNSIGNED NOT NULL,
			`id_ukoocompat_filter` INT(10) UNSIGNED NOT NULL,
			`id_ukoocompat_criterion` INT(10) UNSIGNED NOT NULL,
			PRIMARY KEY (`id_ukoocompat_alias_instance`,`id_ukoocompat_filter`,`id_ukoocompat_criterion`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables d'alias
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_alias`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_alias_lang`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_alias_criterion`');
    }

    /**
     * Recherche un alias à partir de son texte dans la table et retourne les IDs et les valeurs si trouvés
     * @param $alias
     * @return mixed
     */
    public static function searchAlias($alias)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_ukoocompat_alias`, `alias`
			FROM `'._DB_PREFIX_.'ukoocompat_alias`
			WHERE `alias` LIKE "%'.pSQL($alias).'%"');
    }

    /**
     * Recherche un alias à partir de son texte dans la table et retourne l'IDs trouvé
     * @param $alias
     * @return int
     */
    public static function getAliasIdFromAlias($alias)
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `id_ukoocompat_alias`
			FROM `'._DB_PREFIX_.'ukoocompat_alias`
			WHERE `alias` = "'.pSQL($alias).'"');
    }

    /**
     * Retourne un alias à partir des critères recherchés
     * @param $selected_criteria
     * @return int
     */
    public static function getAliasFromSelectedCriteria($selected_criteria)
    {
        if (!empty($selected_criteria)) {
            $sql = '
			SELECT uai.`id_ukoocompat_alias`
			FROM `'._DB_PREFIX_.'ukoocompat_alias_instance` uai';
            // on créé une jointure pour chaque filtre sélectionné
            foreach ($selected_criteria as $id_filter => $id_criterion) {
                // si l'id_criterion est vide, on saute la jointure
                if ($id_criterion !== '') {
                    $prefix = 'uac'.(int)$id_filter;
                    $sql .= ' JOIN `'._DB_PREFIX_.'ukoocompat_alias_criterion` '.$prefix.'
					ON ('.$prefix.'.`id_ukoocompat_filter` = '.(int)$id_filter.'
						AND '.$prefix.'.`id_ukoocompat_alias_instance` = uai.`id_ukoocompat_alias_instance`
						AND (
							'.$prefix.'.`id_ukoocompat_criterion` = '.(int)$id_criterion.'
							OR '.$prefix.'.`id_ukoocompat_criterion` = 0
						)
					)';
                }
            }
            return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }
    }
}
