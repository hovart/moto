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

class UkooCompatCriterion extends ObjectModel
{
    public $id_ukoocompat_filter;
    public $position;

    /* Attributs de langue */
    public $value;

    public static $definition = array(
        'table' => 'ukoocompat_criterion',
        'primary' => 'id_ukoocompat_criterion',
        'multilang' => true,
        'fields' => array(
            'id_ukoocompat_filter' => array('type' => self::TYPE_INT, 'required' => true),
            'position' => array('type' => self::TYPE_INT, 'required' => false),
            /* table _lang */
            'value' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => true,
                'validate' => 'isGenericName',
                'size' => 120)));

    /**
     * Création des tables de critères
     * @return bool
     */
    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_criterion`(
			`id_ukoocompat_criterion` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_ukoocompat_filter` INT(10) UNSIGNED NOT NULL,
			`position` INT(10) UNSIGNED NOT NULL DEFAULT \'999\',
			PRIMARY KEY (`id_ukoocompat_criterion`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8') &&
            Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ukoocompat_criterion_lang`(
			`id_ukoocompat_criterion` INT(10) UNSIGNED NOT NULL,
			`id_lang` INT(10) UNSIGNED NOT NULL,
			`value` VARCHAR(120) NOT NULL,
			PRIMARY KEY (`id_ukoocompat_criterion`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    /**
     * Suppression des tables de critères
     * @return bool
     */
    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_criterion`') &&
            Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'ukoocompat_criterion_lang`');
    }

    /**
     * Met à jour la position du critère
     * @param null $position
     * @return bool
     */
    public function updatePosition($position = null)
    {
        return UkooCompatCriterion::updateCriterionPosition($this->id, $position);
    }

    /**
     * Met à jour la position du critère (static)
     * @param $id_criterion
     * @param null $position
     * @return bool
     */
    public static function updateCriterionPosition($id_criterion, $position = null)
    {
        if ($position === null) {
            if (!$position = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(*)
                FROM `'._DB_PREFIX_.'ukoocompat_criterion`
                WHERE 1')
            ) {
                return false;
            }

            if (!Db::getInstance()->update(
                'ukoocompat_criterion',
                array(
                    'position' => $position - 1
                ),
                '`id_ukoocompat_criterion` = '.(int)$id_criterion
            )
            ) {
                return false;
            } else {
                return true;
            }
        } else {
            // on met à jour le critère avec sa nouvelle position
            if (!Db::getInstance()->update(
                'ukoocompat_criterion',
                array(
                    'position' => (int)$position
                ),
                '`id_ukoocompat_criterion` = '.(int)$id_criterion
            )
            ) {
                return false;
            }
            return true;
        }
    }

    /**
     * Retourne la prochaine position pour la création d'un critère
     * @return int
     */
    public static function getNextCriterionPosition()
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(`id_ukoocompat_criterion`)
            FROM `'._DB_PREFIX_.'ukoocompat_criterion`'
        );
    }


    /**
     * Retourne une liste de critères ordonnée selon le filtre et la sélection soumis
     * TODO :: voir pour optimiser, améliorer
     * @param null $id_filter
     * @param null $id_lang
     * @param string $order_by
     * @param string $order_way
     * @param null $selected_criteria
     * @return bool|mixed
     */
    public static function getCriteria(
        $id_filter = null,
        $id_lang = null,
        $order_by = 'id',
        $order_way = 'ASC',
        $selected_criteria = null
    ) {

        if ($order_by != 'position' && $order_by != 'value' && $order_by != 'id') {
            $order_by = 'id';
        }

        $order_way = Tools::strtoupper($order_way);
        if ($order_way != 'ASC' && $order_way != 'DESC') {
            $order_way = 'ASC';
        }

        // rafraichissement ajax des filtres
        if (!empty($selected_criteria)) {
//        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
//            // test cache
//            $cache_path = 'ukoocompat/filters/dyn/';
//            foreach ($selected_criteria as $id_filterb => $id_criterionb) {
//                $cache_path .= ($id_criterionb !== '' ? $id_criterionb : 'undefined').'/';
//            }
//            $cache_path .= 'f'.(int)$id_filter.'l'.(int)$id_lang.'ob'.$order_by.'ow'.$order_way;
//            if (UkooCache::exists($cache_path)) {
//                $cache_content = UkooCache::get($cache_path);
//            } else {
//                $cache_content = false;
//            }
//        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
//            // si le cache contient un résultat, alors on l'utilise
//            if ($cache_content) {
//                $criteria = $cache_content;
//            } else {
                // si le filtre existe dans les critères sélectionnés, on n'effectue qu'une requête basique
//                if (isset($selected_criteria[(int)$id_filter]) && !empty($selected_criteria[(int)$id_filter])) {
//                    p("le filtre existe dans les critères sélectionnés");
//                    $sql = '
//					SELECT DISTINCT ucc.`id_ukoocompat_criterion` AS id, ucc.`id_ukoocompat_filter`'.
//                        ($order_by == 'position' ? ', uc.`position`' : '').'
//					FROM `'._DB_PREFIX_.'ukoocompat_compat_criterion` ucc
//					'.($order_by == 'position' ? ' INNER JOIN `'._DB_PREFIX_.'ukoocompat_criterion` uc
//					    ON (uc.`id_ukoocompat_criterion` = ucc.`id_ukoocompat_criterion`)' : '').'
//					WHERE ucc.`id_ukoocompat_criterion` = '.(int)$selected_criteria[(int)$id_filter].
//                        ($order_by == 'position' ? ' ORDER BY uc.`position` '.$order_way : ($order_by == 'id' ? '
//                        ORDER BY ucc.`id_ukoocompat_criterion` '.$order_way : ''));
//                    $criteria = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
////        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
////                    // on écrit le résultat dans un fichier de cache
////                    UkooCache::write($cache_path, $criteria);
//                } else {
//                    p("le filtre n'existe pas dans les critères sélectionnés");
                    $restrictions = array();
                    foreach ($selected_criteria as $id_filter_bis => $id_criterion) {
                        if ((int)$id_filter_bis == (int)$id_filter) {
//                            p('break');
                            break;
                        } else {
                            $restrictions[(int)$id_filter_bis] = (int)$id_criterion;

                        }
                    }
//            p('$restrictions');
//            p($restrictions);

                    $sql = '
					SELECT DISTINCT ucc.`id_ukoocompat_criterion` AS id, ucc.`id_ukoocompat_filter`
					FROM `'._DB_PREFIX_.'ukoocompat_compat_criterion` ucc';
                    if (!empty($restrictions)) {
                        $sql .= '
						JOIN (
							SELECT uc.`id_ukoocompat_compat`
							FROM `'._DB_PREFIX_.'ukoocompat_compat` uc';
                        // on créé une jointure pour chaque restriction
                        foreach ($restrictions as $id_filter_bis => $id_criterion) {
                            // si l'id_criterion est vide, on saute la jointure
                            if ($id_criterion !== '') {
                                $prefix = 'ucc'.(int)$id_filter_bis;
                                $sql .= ' JOIN `'._DB_PREFIX_.'ukoocompat_compat_criterion` '.$prefix.'
                                    ON ('.$prefix.'.`id_ukoocompat_filter` = '.(int)$id_filter_bis.'
                                        AND '.$prefix.'.`id_ukoocompat_compat` = uc.`id_ukoocompat_compat`
                                        AND (
                                            '.$prefix.'.`id_ukoocompat_criterion` = '.(int)$id_criterion.'
                                            OR '.$prefix.'.`id_ukoocompat_criterion` = 0
                                        )
                                    )';
                            }
                        }
                        $sql .= ') uccX
						ON (ucc.`id_ukoocompat_compat` = uccX.`id_ukoocompat_compat`'.($id_filter != null ? '
						AND ucc.`id_ukoocompat_filter` = '.(int)$id_filter : '').')';
                    }
                    $sql .= ' WHERE 1'.($id_filter != null ? ' AND ucc.`id_ukoocompat_filter` = '.(int)$id_filter : '');
//                    p($sql);
                    $criteria = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
//        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
//                    // on écrit le résultat dans un fichier de cache
//                    UkooCache::write($cache_path, $criteria);

                    // TODO :: optimiser cette requête pour le trie par position
                    // Si on récupère un 0 ça signifie que tous les critères de ce filtre doivent être affichés
                    // On relance la fonction avec les mêmes paramètres, mais sans la restrictions des compatibilités
                    foreach ($criteria as $criterion) {
                        if ($criterion['id'] == '0') {
                            return self::getCriteria($id_filter, $id_lang, $order_by, $order_way);
                        }
                    }

                    // Sinon, on relance la première requête pour récupérer la position SI trie par position
                    // (attention, peut être très, TRES lent !
                    if ($order_by == 'position') {
                        $sql = '
						SELECT DISTINCT ucc.`id_ukoocompat_criterion` AS id, ucc.`id_ukoocompat_filter`, uc.`position`
						FROM `'._DB_PREFIX_.'ukoocompat_compat_criterion` ucc
						INNER JOIN `'._DB_PREFIX_.'ukoocompat_criterion` uc
							ON (uc.`id_ukoocompat_criterion` = ucc.`id_ukoocompat_criterion`)
						WHERE 1'.($id_filter != null ? ' AND ucc.`id_ukoocompat_filter` = '.(int)$id_filter : '').'
						AND ucc.`id_ukoocompat_compat` IN (
							SELECT uc.`id_ukoocompat_compat`
							FROM `'._DB_PREFIX_.'ukoocompat_compat` uc';
                        // on créé une jointure pour chaque filtre sélectionné
                        foreach ($restrictions as $id_filter_bis => $id_criterion) {
                            // si l'id_criterion est vide, on saute la jointure
                            if ($id_criterion !== '') {
                                $prefix = 'ucc'.(int)$id_filter_bis;
                                $sql .= ' INNER JOIN `'._DB_PREFIX_.'ukoocompat_compat_criterion` '.$prefix.'
										ON ('.$prefix.'.`id_ukoocompat_compat` = uc.`id_ukoocompat_compat`
											AND '.$prefix.'.`id_ukoocompat_filter` = '.(int)$id_filter_bis.'
											AND (
												'.$prefix.'.`id_ukoocompat_criterion` = '.(int)$id_criterion.'
												OR '.$prefix.'.`id_ukoocompat_criterion` = 0
											)
										)';
                            }
                        }
                        // suite et fin de la requête
                        $sql .= ' WHERE 1
						)';
                        $criteria = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
//        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
//                        // on écrit le résultat dans un fichier de cache
//                        UkooCache::write($cache_path, $criteria);
                    }
//                }
//        [ukoo_guillaume] 2016-01-14 :: changer le système de cache par le natif PS
//            }
        } else {
            $criteria = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                'SELECT DISTINCT uc.`id_ukoocompat_criterion` AS id, uc.`id_ukoocompat_filter`'.
                ($order_by == 'position' ? ', uc.`position`' : '').'
                FROM `'._DB_PREFIX_.'ukoocompat_criterion` uc
                WHERE 1'.($id_filter != null ? ' AND uc.`id_ukoocompat_filter` = '.(int)$id_filter : '').
                ($order_by == 'position' ? ' ORDER BY uc.`position` '.$order_way : '')
            );
        }

        foreach ($criteria as $key => $criterion) {
            // aucune langue particulère n'est demandée, on les retournes toutes
            if ($id_lang == null) {
                $criterion_lang = array();
                $languages = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT `id_lang`, `value`
					FROM `'._DB_PREFIX_.'ukoocompat_criterion_lang`
					WHERE `id_ukoocompat_criterion` = '.(int)$criterion['id']);
                foreach ($languages as $lang) {
                    $criterion_lang[(int)$lang['id_lang']] = $lang['value'];
                }
                $criteria[$key]['value'] = $criterion_lang;
            } else {
                $criteria[$key]['value'] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
					SELECT `value`
					FROM `'._DB_PREFIX_.'ukoocompat_criterion_lang`
					WHERE `id_ukoocompat_criterion` = '.(int)$criterion['id'].'
					AND `id_lang` = '.(int)$id_lang);
            }
        }

        // trie par ordre alphabétique si demandé (on ne peut pas trier si plusieurs langues sont demandées)
        if ($order_by == 'value' && $id_lang != null) {
            $criteria = UkooCompatCompat::arrayMsort($criteria, array('value' => 'SORT_'.$order_way));
        }

        return $criteria;
    }

    /**
     * Retourne l'id d'un critère à partir de son nom et de l'id du filtre (pour l'import)
     * @param $value
     * @param $id_filter
     * @param $id_lang
     * @return int
     */
    public static function getCriterionIdFromName($value, $id_filter, $id_lang)
    {
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT uc.`id_ukoocompat_criterion`
            FROM `'._DB_PREFIX_.'ukoocompat_criterion` uc
            LEFT JOIN `'._DB_PREFIX_.'ukoocompat_criterion_lang` ucl
            	ON (uc.`id_ukoocompat_criterion` = ucl.`id_ukoocompat_criterion` AND ucl.`id_lang` = '.(int)$id_lang.')
            WHERE ucl.`value` = "'.pSQL($value).'"
            AND uc.`id_ukoocompat_filter` = '.(int)$id_filter);
    }

    /**
     * Retourne la valeur d'un critère à partir de son id
     * @param $id_criterion
     * @param $id_lang
     * @return mixed
     */
    public static function getCriterionNameFromId($id_criterion, $id_lang)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT ucl.`value`
            FROM `'._DB_PREFIX_.'ukoocompat_criterion_lang` ucl
            WHERE ucl.`id_ukoocompat_criterion` = '.(int)$id_criterion.'
            AND ucl.`id_lang` = '.(int)$id_lang);
    }
}
