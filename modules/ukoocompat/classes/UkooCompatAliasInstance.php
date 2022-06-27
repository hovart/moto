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

class UkooCompatAliasInstance extends ObjectModel
{
    public $id_ukoocompat_alias;

    public static $definition = array(
        'table' => 'ukoocompat_alias_instance',
        'primary' => 'id_ukoocompat_alias_instance',
        'multilang' => false,
        'fields' => array(
            'id_ukoocompat_alias' => array('type' => self::TYPE_INT, 'required' => true)));

    /**
     * Création des tables d'instances d'alias
     * @return mixed
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_alias_instance`(
			`id_ukoocompat_alias_instance` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_ukoocompat_alias` INT(10) UNSIGNED NOT NULL,
			PRIMARY KEY (`id_ukoocompat_alias_instance`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables d'instance d'alias
     * @return mixed
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_alias_instance`');
    }

    /**
     * Retourne le nombre total d'instance d'alias pour les KPIS (statique)
     * @return int
     */
    public static function getTotalAliasInstance()
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'ukoocompat_alias_instance` WHERE 1');
    }

    /**
     * Retourne la liste des critères d'une instance d'alias
     * @param $id_lang
     * @return array
     */
    public function getAssociatedCriteria($id_lang)
    {
        return UkooCompatAliasInstance::getAliasInstanceAssociatedCriteria((int)$this->id, (int)$id_lang);
    }

    /**
     * Retourne la liste des critères d'une instance d'alias (static)
     * @param $id_alias_instance
     * @param $id_lang
     * @return array
     */
    public static function getAliasInstanceAssociatedCriteria($id_alias_instance, $id_lang)
    {
        // Le CASE permet de remplacer l'id criterion par "*" si celui-ci est égale à "0"
        // (compatible "Tous" critères)
        $ret = array();
        $criteria = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT uac.`id_ukoocompat_alias_instance`, uac.`id_ukoocompat_filter`,
            CASE WHEN uac.`id_ukoocompat_criterion` = 0 THEN "*"
                ELSE uac.`id_ukoocompat_criterion` END AS id_ukoocompat_criterion,
            ufl.`name` as filter_name, ucl.`value` as criterion_value
            FROM `'._DB_PREFIX_.'ukoocompat_alias_criterion` uac
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_filter_lang` ufl
            	ON (ufl.`id_ukoocompat_filter` = uac.`id_ukoocompat_filter` AND ufl.`id_lang` = '.(int)$id_lang.')
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_criterion_lang` ucl
            	ON (ucl.`id_ukoocompat_criterion` = uac.`id_ukoocompat_criterion` AND ucl.`id_lang` = '.(int)$id_lang.')
            WHERE uac.`id_ukoocompat_alias_instance` = '.(int)$id_alias_instance);
        foreach ($criteria as $criterion) {
            $ret[(int)$criterion['id_ukoocompat_filter']] = $criterion;
        }
        return $ret;
    }

    /**
     * Créé une liaison entre un critère, un filtre et une instance d'alias
     * @param $id_filter
     * @param $id_criterion
     * @return bool
     */
    public function addAssociatedCriterion($id_filter, $id_criterion)
    {
        // On test la valeur de l'id_criterion.
        // Seul un entier ou un zéro absolue doit être utilisé. Une valeur vide n'est pas correcte.
        if ($id_criterion === '') {
            return true;
        }

        return Db::getInstance()->insert('ukoocompat_alias_criterion', array(
            'id_ukoocompat_alias_instance' => (int)$this->id,
            'id_ukoocompat_filter' => (int)$id_filter,
            'id_ukoocompat_criterion' => (int)$id_criterion));
    }

    /**
     * Recherche les instances d'alias à partir d'un ID d'alias
     * @param $id_alias
     * @return mixed
     */
    public static function getAliasInstancesByAlias($id_alias)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT ucai.`id_ukoocompat_alias_instance`
			FROM `'._DB_PREFIX_.'ukoocompat_alias_instance` ucai
			WHERE ucai.`id_ukoocompat_alias` = '.(int)$id_alias);
    }

    /**
     * Retourne le tableau des filtres et critères associés à l'instance de recherche
     * @param $id_alias_instance
     * @return array
     */
    public static function getFiltersByAliasInstance($id_alias_instance)
    {
        $rslts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT `id_ukoocompat_filter`, `id_ukoocompat_criterion`
			FROM `'._DB_PREFIX_.'ukoocompat_alias_criterion`
			WHERE `id_ukoocompat_alias_instance` = '.(int)$id_alias_instance);
        $filters = array();
        foreach ($rslts as $rslt) {
            $filters[(int)$rslt['id_ukoocompat_filter']] = $rslt['id_ukoocompat_criterion'];
        }
        return $filters;
    }

    /**
     * Retourne true si l'instance d'alias existe déja avec les mêmes associations (critères et alias) (statique)
     * @param array $compatibility
     * @return bool
     */
    public static function aliasInstanceExists(array $compatibility)
    {
        // le tableau $compatibility ne doit contenir que les filtres => critères lors du test
        // on supprime donc toutes les autres entrées avec "unset()"
        if (isset($compatibility['id_alias'])) {
            $id_alias = (int)$compatibility['id_alias'];
            unset($compatibility['id_alias']);
        }

        if (isset($compatibility['id_product'])) {
            unset($compatibility['id_product']);
        }

        $sql = '
            SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'ukoocompat_alias_instance` uai';
        $i = 0;
        foreach ($compatibility as $id_filter => $id_criterion) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'ukoocompat_alias_criterion` uac'.$i.'
            	ON (uac'.$i.'.`id_ukoocompat_alias_instance` = uai.`id_ukoocompat_alias_instance`
                AND uac'.$i.'.`id_ukoocompat_filter` = '.(int)$id_filter.'
                AND uac'.$i.'.`id_ukoocompat_criterion` = '.(int)$id_criterion.')';
            $i++;
        }
        $sql .= ' WHERE uai.`id_ukoocompat_alias` = '.$id_alias;
        return ((int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql) > 0 ? true : false);
    }
}
