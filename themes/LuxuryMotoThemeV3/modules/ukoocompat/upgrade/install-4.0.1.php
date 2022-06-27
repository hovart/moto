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

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_4_0_1($object)
{
    // Modification du champ "separator" de la table "ukoocompat_import_file"
    if (!Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'ukoocompat_import_file`
        CHANGE `separator` `col_separator` VARCHAR( 1 )
        CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT \';\' ')
    ) {
        return false;
    }
	
    // Modification de la table "ukoocompat_alias"
    if (!Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'ukoocompat_alias`
        ADD `link` VARCHAR(255) NULL DEFAULT NULL,
        ADD `image` VARCHAR(120) NULL DEFAULT NULL')
    ) {
        return false;
    }

    // Le module peut dorénavant se greffer aux hooks Footer et Top
    if (!$object->registerHook('displayTop')
        || !$object->registerHook('displayFooter')
    ) {
        return false;
    }

    return true;
}
