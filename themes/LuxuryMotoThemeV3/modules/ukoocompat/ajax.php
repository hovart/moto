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

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/ukoocompat.php');

$ukoocompat = new UkooCompat();

// Soit on lance la recherche d'alias, soit en lance le rechargement ajax des filtres de recherche
if (Tools::isSubmit('q')) {
    echo $ukoocompat->ajaxSearchByAlias();
} else {
    echo $ukoocompat->ajaxReloadFilters();
}
