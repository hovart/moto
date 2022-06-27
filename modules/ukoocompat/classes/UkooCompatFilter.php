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

class UkooCompatFilter extends ObjectModel
{
    public $id_ukoocompat_filter;
    public $position;

    /* Attributs de langue */
    public $name;

    public static $definition = array(
        'table' => 'ukoocompat_filter',
        'primary' => 'id_ukoocompat_filter',
        'multilang' => true,
        'fields' => array('position' => array('type' => self::TYPE_INT, 'required' => false),
        /* table _lang */
        'name' => array(
            'type' => self::TYPE_STRING,
            'lang' => true,
            'required' => true,
            'validate' => 'isGenericName',
            'size' => 120)));

    /**
     * Création des tables des filtres
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_filter`(
			`id_ukoocompat_filter` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`position` INT(10) UNSIGNED NOT NULL DEFAULT \'999\',
			PRIMARY KEY (`id_ukoocompat_filter`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_filter_lang`(
			`id_ukoocompat_filter` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`name` VARCHAR(120) NOT NULL,
			PRIMARY KEY (`id_ukoocompat_filter`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables des filtres
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_filter`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_filter_lang`');
    }

    /**
     * Met à jour la position d'un filtre
     * @param null $position
     * @return bool
     */
    public function updatePosition($position = null)
    {
        return UkooCompatFilter::updateFilterPosition($this->id, $position);
    }

    /**
     * Met à jour la position d'un filtre (static)
     * @param $id_filter
     * @param null $position
     * @return bool
     */
    public static function updateFilterPosition($id_filter, $position = null)
    {
        if ($position === null) {
            if (!$position = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
                'SELECT COUNT(*)
                FROM `'._DB_PREFIX_.'ukoocompat_filter`
                WHERE 1'
            )
            ) {
                return false;
            }

            if (!Db::getInstance()->update(
                'ukoocompat_filter',
                array(
                    'position' => $position - 1
                ),
                '`id_ukoocompat_filter` = '.(int)$id_filter
            )
            ) {
                return false;
            }
            return true;
        } else {
            // on met à jour le filtre avec sa nouvelle position
            if (!Db::getInstance()->update(
                'ukoocompat_filter',
                array(
                    'position' => (int)$position
                ),
                '`id_ukoocompat_filter` = '.(int)$id_filter
            )
            ) {
                return false;
            }
            return true;
        }
    }

    /**
     * Retourne la prochaine position pour la création d'un filtre
     * @return int
     */
    public static function getNextFilterPosition()
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(*) FROM `'._DB_PREFIX_.'ukoocompat_filter`'
        );
    }

    /**
     * Retourne la liste des filtres existants
     * Si l'id_lang est passé, retourne la langue souhaitée,
     *  sinon retourne toutes les langues sous la forme d'un tableau
     * Si get_value vaut true, récupère pour chaque filtre l'ensemble des valeurs associées
     * @param null $id_lang
     * @param bool $get_values
     * @return mixed
     */
    public static function getFilters($id_lang = null, $get_values = false)
    {
        $filters = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT uf.`id_ukoocompat_filter` AS id, uf.`position`
            FROM `'._DB_PREFIX_.'ukoocompat_filter` uf
            WHERE 1');
        foreach ($filters as $key => $filter) {
            // aucune langue particulère n'est demandée, on les retournes toutes
            if ($id_lang === null) {
                $filter_lang = array();
                $languages = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT `id_lang`, `name`
					FROM `'._DB_PREFIX_.'ukoocompat_filter_lang`
					WHERE `id_ukoocompat_filter` = '.(int)$filter['id']);
                foreach ($languages as $lang) {
                    $filter_lang[(int)$lang['id_lang']] = $lang['name'];
                }
                $filters[$key]['name'] = $filter_lang;
            } else {
                $filters[$key]['name'] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
					SELECT `name`
					FROM `'._DB_PREFIX_.'ukoocompat_filter_lang`
					WHERE `id_ukoocompat_filter` = '.(int)$filter['id'].'
					AND `id_lang` = '.(int)$id_lang);
            }

            // on récupère également les valeurs des filtres si elles sont demandées
            if ($get_values) {
                $filters[$key]['criteria'] = UkooCompatCriterion::getCriteria((int)$filter['id'], (int)$id_lang);
            }
        }
        return $filters;
    }

    /**
     * Retourne l'id d'un filtre à partir de son nom (pour l'import)
     * @param $name
     * @param $id_lang
     * @return int
     */
    public static function getFilterIdFromName($name, $id_lang)
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `id_ukoocompat_filter`
			FROM `'._DB_PREFIX_.'ukoocompat_filter_lang`
			WHERE `name` = "'.pSQL($name).'"
			AND `id_lang` = '.(int)$id_lang);
    }
}
