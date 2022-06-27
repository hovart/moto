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

class UkooCompatSearchFilter extends ObjectModel
{
    public $id_ukoocompat_search;
    public $id_ukoocompat_filter;
    public $display_type;
    public $order_by;
    public $order_way;
    public $position;
    public $active;

    /* Attributs de langue */
    public $name;

    /* Attributs dynamiques */
    public $groups;

    public static $definition = array(
        'table' => 'ukoocompat_search_filter',
        'primary' => 'id_ukoocompat_search_filter',
        'multilang' => true,
        'fields' => array(
            'id_ukoocompat_search' => array('type' => self::TYPE_INT, 'required' => true),
            'id_ukoocompat_filter' => array('type' => self::TYPE_INT, 'required' => true),
            'display_type' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'validate' => 'isGenericName',
                'size' => 120),
            'order_by' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'validate' => 'isGenericName',
                'size' => 120),
            'order_way' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'validate' => 'isGenericName',
                'size' => 120),
            'position' => array('type' => self::TYPE_INT, 'required' => false),
            'active' => array('type' => self::TYPE_BOOL, 'required' => false),
            /* table _lang */
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120)));

    /**
     * Création des tables de filtres de recherche
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_search_filter`(
			`id_ukoocompat_search_filter` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_ukoocompat_search` INT(10) UNSIGNED NOT NULL,
			`id_ukoocompat_filter` INT(10) UNSIGNED NOT NULL,
			`display_type` VARCHAR(120) NOT NULL DEFAULT \'select\',
			`order_by` VARCHAR(120) NOT NULL DEFAULT \'position\',
			`order_way` VARCHAR(120) NOT NULL DEFAULT \'ASC\',
			`position` INT(10) UNSIGNED NOT NULL DEFAULT \'999\',
			`active` TINYINT(1) NOT NULL DEFAULT \'1\',
			PRIMARY KEY (`id_ukoocompat_search_filter`),
			UNIQUE KEY `id_ukoocompat_search` (`id_ukoocompat_search`,`id_ukoocompat_filter`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_search_filter_lang`(
			`id_ukoocompat_search_filter` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`name` VARCHAR(120) NOT NULL,
			PRIMARY KEY (`id_ukoocompat_search_filter`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables de filtres de recherche
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_search_filter`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_search_filter_lang`');
    }

    /**
     * Retourne un objet SearchFilter à partir de l'id de la recherche et du filtre (static)
     * @param $id_search
     * @param $id_filter
     * @param null $id_lang
     * @return UkooCompatSearchFilter
     */
    public static function getObjectFromSearchAndFilterIds($id_search, $id_filter, $id_lang = null)
    {
        return new UkooCompatSearchFilter((int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_ukoocompat_search_filter`
				FROM `'._DB_PREFIX_.'ukoocompat_search_filter`
				WHERE `id_ukoocompat_search` = '.(int)$id_search.'
				AND `id_ukoocompat_filter` = '.(int)$id_filter.'
			'), $id_lang);
    }

    /**
     * Mettre à jour la position d'un filtre au sein d'une recherche (static)
     * @param $id_search_filter
     * @param $position
     * @return bool
     */
    public static function updateSearchFilterPosition($id_search_filter, $position)
    {
        if (!Db::getInstance()->update(
            'ukoocompat_search_filter',
            array(
                'position' => (int)$position
            ),
            '`id_ukoocompat_search_filter` = '.(int)$id_search_filter
        )
        ) {
            return false;
        }
        return true;
    }

    /**
     * Retourne la liste des filtres associés à la recherche sous forme d'objets (statique)
     * @param $id_lang
     * @param $id_search
     * @param $active
     * @return array
     */
    public static function getSearchFilters($id_lang, $id_search, $active)
    {
        $id_filters = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT `id_ukoocompat_search_filter` AS id
            FROM `'._DB_PREFIX_.'ukoocompat_search_filter`
            WHERE `id_ukoocompat_search` = '.(int)$id_search.'
            AND `active` = '.(int)(bool)$active.'
            ORDER BY `position` ASC');

        // pour chaque filtre, on instancie l'objet
        $filters = array();
        foreach ($id_filters as $key => $filter) {
            $filter = new UkooCompatSearchFilter((int)$filter['id'], (int)$id_lang);
            $filter->groups = UkooCompatSearchFilter::getGroups((int)$filter->id, (int)$id_lang);
            $filters[$key] = $filter;
        }

        return $filters;
    }

    /**
     * Retourne la liste des groupes de critères pour un filtre de recherche donné
     * @param $id_filter
     * @param $id_lang
     * @return array
     */
    public static function getGroups($id_filter, $id_lang = null, $active = 1)
    {
        // 1. récupérer les ID des groups concernés
        $sql = 'SELECT ug.`id_ukoocompat_group`
		FROM `'._DB_PREFIX_.'ukoocompat_group` ug
		WHERE ug.`id_ukoocompat_search_filter` = '.(int)$id_filter.($active == 'all' ? '' : '
		AND ug.`active` = '.(int)(bool)$active).'
		ORDER BY ug.`position` ASC';
        $id_groups = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        // 2. chargés les objets
        $groups = array();
        foreach ($id_groups as $id_group) {
            $group = new UkooCompatGroup((int)$id_group['id_ukoocompat_group'], (int)$id_lang);
            $group->criteria = explode(',', $group->selected);
            $groups[] = $group;
        }

        // 3. retourner le tableau des objets
        return $groups;
    }

    /**
     * Retourne la liste des critères d'un filtre
     * @param null $id_criterion
     * @return array|bool|mixed
     */
    public function getCriteria($id_criterion = null)
    {
        // Si l'ID criterion est communiqué, on retourne un critère unique
        // (permet de gagner du temps lors de la sélection dynamique des critères)
        if ($id_criterion == null) {
            return UkooCompatCriterion::getCriteria(
                (int)$this->id_ukoocompat_filter,
                (int)$this->id_lang,
                $this->order_by,
                $this->order_way
            );
        } else {
            return array(
                0 => array(
                    'id' => (int)$id_criterion,
                    'id_ukoocompat_filter' => (int)$this->id_ukoocompat_filter,
                    'value' => UkooCompatCriterion::getCriterionNameFromId((int)$id_criterion, (int)$this->id_lang)));
        }
    }
}
