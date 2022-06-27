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

class UkooCompatSearch extends ObjectModel
{
    public $id_hook;
    public $name;
    public $active;
    public $position;
    public $hide_button;
    public $hide_on_catalog;
    public $prefilter;
    public $display_nb_products_catalog;
    public $skip_catalog;
    public $display_menu;
    public $display_product_tab;
    public $display_reset_button;
    public $display_alias_search_block;
    public $dynamic_criteria;

    /* Attributs de langue */
    public $title;
    public $catalog_meta_title;
    public $catalog_meta_description;
    public $catalog_meta_keywords;
    public $catalog_title;
    public $catalog_description;
    public $listing_meta_title;
    public $listing_meta_description;
    public $listing_meta_keywords;
    public $listing_title;
    public $listing_description;

    /* Attributs dynamiques */
    public $id;
    public $current_id_lang;
    public $filters;
    public $selected_criteria;
    public $controller;
    public $tags;

    public static $definition = array(
        'table' => 'ukoocompat_search',
        'primary' => 'id_ukoocompat_search',
        'multilang' => true,
        'fields' => array(
            'id_hook' => array('type' => self::TYPE_INT, 'required' => true),
            'name' => array(
                'type' => self::TYPE_STRING,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120),
            'active' => array('type' => self::TYPE_BOOL, 'required' => false),
            'position' => array('type' => self::TYPE_INT, 'required' => false),
            'hide_button' => array('type' => self::TYPE_BOOL, 'required' => false),
            'hide_on_catalog' => array('type' => self::TYPE_BOOL, 'required' => false),
            'prefilter' => array('type' => self::TYPE_BOOL, 'required' => false),
            'display_nb_products_catalog' => array('type' => self::TYPE_BOOL, 'required' => false),
            'skip_catalog' => array('type' => self::TYPE_BOOL, 'required' => false),
            'display_menu' => array('type' => self::TYPE_BOOL, 'required' => false),
            'display_product_tab' => array('type' => self::TYPE_BOOL, 'required' => false),
            'display_reset_button' => array('type' => self::TYPE_BOOL, 'required' => false),
            'display_alias_search_block' => array('type' => self::TYPE_BOOL, 'required' => false),
            'dynamic_criteria' => array('type' => self::TYPE_BOOL, 'required' => false),
            /* table _lang */
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120),
            'catalog_meta_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'catalog_meta_description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'catalog_meta_keywords' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'catalog_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'catalog_description' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString',
                'size' => 399999999999),
            'listing_meta_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'listing_meta_description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'listing_meta_keywords' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'listing_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => false,
                'validate' => 'isString',
                'size' => 255),
            'listing_description' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString',
                'size' => 399999999999)),
        'associations' => array(
            'categories' => array(
                'type' => self::HAS_MANY,
                'field' => 'id_category',
                'association' => 'ukoocompat_category')));

    /**
     * Création des tables de recherches
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_search`(
			`id_ukoocompat_search` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_hook` INT(10) UNSIGNED NOT NULL,
			`name` VARCHAR(120) DEFAULT NULL,
			`active` TINYINT(1) NOT NULL DEFAULT \'1\',
			`position` INT(10) UNSIGNED NOT NULL DEFAULT \'999\',
			`hide_button` TINYINT(1) NOT NULL DEFAULT \'0\',
			`hide_on_catalog` TINYINT(1) NOT NULL DEFAULT \'1\',
			`prefilter` TINYINT(1) NOT NULL DEFAULT \'0\',
			`display_nb_products_catalog` TINYINT(1) NOT NULL DEFAULT \'0\',
			`skip_catalog` TINYINT(1) NOT NULL DEFAULT \'0\',
			`display_menu` TINYINT(1) NOT NULL DEFAULT \'0\',
			`display_product_tab` TINYINT(1) NOT NULL DEFAULT \'1\',
			`display_reset_button` TINYINT(1) NOT NULL DEFAULT \'1\',
			`display_alias_search_block` TINYINT(1) NOT NULL DEFAULT \'1\',
			`dynamic_criteria` TINYINT(1) NOT NULL DEFAULT \'1\',
			PRIMARY KEY (`id_ukoocompat_search`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_search_lang`(
			`id_ukoocompat_search` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`title` VARCHAR(120) NOT NULL,
			`catalog_meta_title` VARCHAR(255) DEFAULT NULL,
            `catalog_meta_description` VARCHAR(255) DEFAULT NULL,
            `catalog_meta_keywords` VARCHAR(255) DEFAULT NULL,
            `catalog_title` VARCHAR(255) DEFAULT NULL,
            `catalog_description` TEXT,
            `listing_meta_title` VARCHAR(255) DEFAULT NULL,
            `listing_meta_description` VARCHAR(255) DEFAULT NULL,
            `listing_meta_keywords` VARCHAR(255) DEFAULT NULL,
            `listing_title` VARCHAR(255) DEFAULT NULL,
            `listing_description` TEXT,
			PRIMARY KEY (`id_ukoocompat_search`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_category`(
			`id_ukoocompat_search` INT(10) UNSIGNED NOT NULL,
			`id_category` INT(10) UNSIGNED NOT NULL,
			PRIMARY KEY (`id_ukoocompat_search`,`id_category`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables de recherche
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_search`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_search_lang`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_category`');
    }

    /**
     * Met à jour les catégories associée à une recherche (suppression + création)
     * @param array $categories
     * @return bool
     */
    public function updateAssociatedCategories(array $categories)
    {
        if (Db::getInstance()->delete('ukoocompat_category', '`id_ukoocompat_search` = '.(int)$this->id)) {
            foreach ($categories as $id_category) {
                if (!Db::getInstance()->insert('ukoocompat_category', array(
                    'id_ukoocompat_search' => (int)$this->id,
                    'id_category' => (int)$id_category))) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retourne la liste des catégories associées à la recherche
     * @return array
     */
    public function getCategories()
    {
        return UkooCompatSearch::getSearchCategories($this->id);
    }

    /**
     * Retourne la liste des catégories associées à la recherche (static)
     * @param $id_search
     * @return array
     */
    public static function getSearchCategories($id_search)
    {
        $ret = array();
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_category`
			FROM `'._DB_PREFIX_.'ukoocompat_category`
			WHERE `id_ukoocompat_search` = '.(int)$id_search);
        if ($row) {
            foreach ($row as $val) {
                $ret[] = $val['id_category'];
            }
        }
        return $ret;
    }

    /**
     * Met à jour la position de la recherche
     * @param null $position
     * @return bool
     */
    public function updatePosition($position = null)
    {
        return UkooCompatSearch::updateSearchPosition($this->id, $position);
    }

    /**
     * Met à jour la position de la recherche (static)
     * @param $id_search
     * @param null $position
     * @return bool
     */
    public static function updateSearchPosition($id_search, $position = null)
    {
        if ($position === null) {
            if (!$position = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
                'SELECT COUNT(*)
                FROM `'._DB_PREFIX_.'ukoocompat_search`
                WHERE 1'
            )
            ) {
                return false;
            }

            if (!Db::getInstance()->update(
                'ukoocompat_search',
                array(
                    'position' => $position - 1),
                '`id_ukoocompat_search` = '.(int)$id_search
            )
            ) {
                return false;
            } else {
                return true;
            }
        } else {
            // on met à jour la recherche avec sa nouvelle position
            if (!Db::getInstance()->update(
                'ukoocompat_search',
                array(
                    'position' => (int)$position),
                '`id_ukoocompat_search` = '.(int)$id_search
            )
            ) {
                return false;
            }
            return true;
        }
    }

    /**
     * Récupère la prochaine position pour l'ajout d'un filtre à la recherche
     * @param $id_search
     * @return int
     */
    public static function getNextPosition($id_search)
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'ukoocompat_search_filter`
			WHERE `id_ukoocompat_search` = '.(int)$id_search.'
		');
    }

    /**
     * Récupère la liste des filtres disponibles et utilisés par la recherche sous la forme de 2 tableaux
     * @param $id_lang
     * @return array
     */
    public function getAvailableAndUsedFilters($id_lang)
    {
        $tmp_filters = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT uf.`id_ukoocompat_filter` AS id, uf.`position` AS default_position, ufl.`name` AS default_name,
            usf.`id_ukoocompat_search_filter`, usf.`id_ukoocompat_search`, usf.`display_type`, usf.`order_by`,
            usf.`order_way`, usf.`position`, usf.`active`, usfl.`name`
            FROM `'._DB_PREFIX_.'ukoocompat_filter` uf
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_filter_lang` ufl
            	ON (uf.`id_ukoocompat_filter` = ufl.`id_ukoocompat_filter`)
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_search_filter` usf
            	ON (uf.`id_ukoocompat_filter` = usf.`id_ukoocompat_filter`)
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_search_filter_lang` usfl
            	ON (usf.`id_ukoocompat_search_filter` = usfl.`id_ukoocompat_search_filter`
            	    AND usfl.`id_lang` = '.(int)$id_lang.')
            WHERE ufl.`id_lang` = '.(int)$id_lang);
        $filters = array('available' => array(), 'used' => array());
        foreach ($tmp_filters as $filter) {
            // Si certains champs de la jointure sont NULL, alors le filtre n'est pas utilisé par la recherche
            if ((int)$filter['id_ukoocompat_search'] !== (int)$this->id) {
                $filters['available'][(int)$filter['default_position']] = array_filter($filter);
            } else {
                $filters['used'][(int)$filter['position']] = $filter;
            }
        }
        ksort($filters['available']);
        ksort($filters['used']);
        return $filters;
    }

    /**
     * Retourne la liste des recherches selon la catégorie et le hook donnés
     * @param $hook_name
     * @param $id_category
     * @param $id_lang
     * @return mixed
     */
    public static function getSearchByHook($hook_name, $id_category, $id_lang)
    {
        $sql = '
            SELECT us.`id_ukoocompat_search`, us.`name` AS internal_name, us.`position`, us.`hide_button`,
            us.`prefilter`, us.`skip_catalog`, us.`display_menu`, usl.`title`
            FROM `'._DB_PREFIX_.'ukoocompat_search` us';

        // si $id_category est null, il s'agit du displayHome
        if ($hook_name != 'displayUkooCompatBlock') {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'hook` h
            	ON (h.`id_hook` = us.`id_hook` AND h.`name` = "'.pSQL($hook_name).'")';
        }

        // si $id_category est null, il s'agit du displayHome
        if (!empty($id_category)) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'ukoocompat_category` uc
				ON (uc.`id_ukoocompat_search` = us.`id_ukoocompat_search`
				    AND uc.`id_category` = "'.(int)$id_category.'")';
        }

        $sql .= '
            INNER JOIN `'._DB_PREFIX_.'ukoocompat_search_lang` usl
            	ON (us.`id_ukoocompat_search` = usl.`id_ukoocompat_search` AND usl.`id_lang` = "'.(int)$id_lang.'")
            WHERE us.`active` = 1
            ORDER BY us.`position`
        ';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Retourne la liste des recherches affichées sur les fiches produits en front
     * @param $id_lang
     * @param null $active
     * @return mixed
     */
    public static function getSearchInProductTab($id_lang, $active = null)
    {
        $sql = '
            SELECT us.`id_ukoocompat_search`, us.`name` AS internal_name, us.`position`, us.`hide_button`,
            us.`prefilter`, us.`skip_catalog`, us.`display_menu`, usl.`title`
            FROM `'._DB_PREFIX_.'ukoocompat_search` us
            INNER JOIN `'._DB_PREFIX_.'ukoocompat_search_lang` usl
            	ON (us.`id_ukoocompat_search` = usl.`id_ukoocompat_search` AND usl.`id_lang` = "'.(int)$id_lang.'")
            WHERE 1'.($active !== null ? ' AND us.`active` = '.(int)(bool)$active : '').'
			AND us.`display_product_tab` = 1
            ORDER BY us.`position`
        ';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Retourne la liste des filtres associés à la recherche
     * @param null $id_lang
     * @param bool $active
     * @return array
     */
    public function getFilters($id_lang = null, $active = true)
    {
        return UkooCompatSearchFilter::getSearchFilters($id_lang, $this->id, (bool)$active);
    }

    /**
     * Remplace toutes les occurences de tags par leur valeur au sein des différents champs textuels
     */
    public function replaceSEOTags()
    {
        // on ajoute la correspondance de la catégorie pour le listing
        $this->tags['{CATEGORY}'] = (isset($this->category->name) ? $this->category->name : '');
        // pour chaque tag du tableau, on remplace la balise par sa valeur ou "vide" si introuvable
        foreach ($this->tags as $tag => $value) {
            // catalogue
            $this->catalog_meta_title = str_replace($tag, $value, $this->catalog_meta_title);
            $this->catalog_meta_description = str_replace($tag, $value, $this->catalog_meta_description);
            $this->catalog_meta_keywords = str_replace($tag, $value, $this->catalog_meta_keywords);
            $this->catalog_title = str_replace($tag, $value, $this->catalog_title);
            $this->catalog_description = str_replace($tag, $value, $this->catalog_description);
            // listing
            $this->listing_meta_title = str_replace($tag, $value, $this->listing_meta_title);
            $this->listing_meta_description = str_replace($tag, $value, $this->listing_meta_description);
            $this->listing_meta_keywords = str_replace($tag, $value, $this->listing_meta_keywords);
            $this->listing_title = str_replace($tag, $value, $this->listing_title);
            $this->listing_description = str_replace($tag, $value, $this->listing_description);
        }
    }
}
