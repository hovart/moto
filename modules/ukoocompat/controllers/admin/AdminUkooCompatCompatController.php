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

class AdminUkooCompatCompatController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_compat';
        $this->className = 'UkooCompatCompat';
        $this->lang = false;
        $this->_orderBy = 'id_ukoocompat_compat';
        $this->_orderWay = 'DESC';
        $this->allow_export = true;
        $this->context = Context::getContext();
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->_select = 'pl.`name` AS `product_name`, p.`reference`, a.`id_ukoocompat_compat` AS `compatibilities`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
			ON (a.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.'
			    AND pl.`id_shop` = '.(int)$this->context->shop->id.')
            LEFT JOIN `'._DB_PREFIX_.'product` p ON (a.`id_product` = p.`id_product`)';

        $this->fields_list = array(
            'id_ukoocompat_compat' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'product_name' => array(
                'title' => $this->l('Product'),
                'align' => 'left',
                'orderby' => false,
                'filter' => false,
                'search' => false),
            'reference' => array(
                'title' => $this->l('Product reference'),
                'align' => 'left'),
            'compatibilities' => array(
                'title' => $this->l('Associated criteria'),
                'align' => 'left',
                'callback' => 'getListAssociatedCriteria',
                'orderby' => false,
                'filter' => false,
                'search' => false));
    }

    /**
     * Récupère la liste des critères compatibles et l'affiche dans la liste
     * @param $id_ukoocompat_compat
     * @return string
     */
    public function getListAssociatedCriteria($id_ukoocompat_compat)
    {
        $criteria = UkooCompatCompat::getCompatibilityAssociatedCriteria(
            $id_ukoocompat_compat,
            (int)$this->context->language->id);
        $output = array();
        foreach ($criteria as $criterion) {
            $output[] = $criterion['filter_name'].$this->l(': ').
                ($criterion['id_ukoocompat_criterion'] == '*' ? $this->l('All') : $criterion['criterion_value']);
        }
        return implode(', ', $output);
    }

    /**
     * Construction des KPIS pour la page des compatibilités
     * Nombre de compatibilités total
     * Nombre de produits concernés par les compatibilités
     * Nombre d'instance d'alias
     * @return mixed
     */
    public function renderKpis()
    {
        $kpis = array();

        // le nombre de compatibilités total
        $helper = new HelperKpi();
        $helper->id = 'box-total-compat';
        $helper->icon = 'icon-check';
        $helper->color = 'color4';
        $helper->title = $this->l('Total compatibilities');
        $helper->value = (int)UkooCompatCompat::getTotalCompatibilities();
        $kpis[] = $helper->generate();

        // le nombre de produits concernés par les compatibilités
        $helper = new HelperKpi();
        $helper->id = 'box-products';
        $helper->icon = 'icon-AdminCatalog';
        $helper->color = 'color1';
        $helper->title = $this->l('Compatibles products');
        $helper->value = (int)UkooCompatCompat::getTotalCompatibleProducts();
        $kpis[] = $helper->generate();

        // le nombre d'instance d'alias
        $helper = new HelperKpi();
        $helper->id = 'box-total-alias';
        $helper->icon = 'icon-share';
        $helper->color = 'color2';
        $helper->title = $this->l('Total alias');
        $helper->value = (int)UkooCompatAliasinstance::getTotalAliasInstance();
        $kpis[] = $helper->generate();

        $helper = new HelperKpiRow();
        $helper->kpis = $kpis;
        return $helper->generate();
    }

    /**
     * Affiche le formulaire de création ou d'édition d'une compatibilité
     */
    public function renderForm()
    {
        $this->initToolbar();
        $this->addJqueryPlugin('autocomplete');

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Compatibility settings'),
                'icon' => 'icon-check'),
            'input' => array(
                array(
                    'type' => 'text',
                    'suffix' => '<i class="icon-search"></i>',
                    'label' => $this->l('Product(s)'),
                    'name' => 'id_product',
                    'lang' => false,
                    'required' => true,
                    'hint' => $this->l('Select your product(s), then their compatibilities'))),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitAdd'.$this->table.'AndBackToParent'),
            'buttons' => array(
                'save_and_new' => array(
                    'name' => 'submitAdd'.$this->table.'AndNew',
                    'type' => 'submit',
                    'title' => $this->l('Save and new'),
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save')));

        $this->context->smarty->assign(array(
            'currentToken' => $this->token,
            'currentObject' => $obj,
            'currentTab' => $this,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            'languages' => Language::getLanguages(),
            'filters' => UkooCompatFilter::getFilters((int)$this->context->language->id, true),
            'selected_criteria' => $obj->getAssociatedCriteria((int)$this->context->language->id)));

        if (isset($obj->id_product) && !empty($obj->id_product)) {
            $this->context->smarty->assign(array(
                'product' => new Product((int)$obj->id_product, false, (int)$this->context->language->id)));
        }

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'une nouvelle compatibilité
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Ajout des critères associés à la compat dans la table ukoocompat_criterion
        if (Tools::isSubmit('id_ukoocompat_criterion') && !count($this->errors)) {
            foreach (Tools::getValue('id_ukoocompat_criterion') as $id_filter => $id_criterion) {
                if (!$object->addAssociatedCriterion($id_filter, $id_criterion)) {
                    $this->errors[] = $this->l('Unabled to associate criterion to compatibility!');
                }
            }
        }

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction exécutée lors de la mise à jour d'une compatibilité
     * @return mixed
     */
    public function processUpdate()
    {
        $object = parent::processUpdate();

        // MAJ des critères associés à la compat dans la table ukoocompat_criterion
        if (Tools::isSubmit('id_ukoocompat_criterion') && !count($this->errors)) {
            // Pour la MAJ, on supprime toutes les entrées, puis on les re-créé
            if (!$object->deleteAssociatedCriteria()) {
                $this->errors[] = $this->l('Unabled to desassociate criteria from compatibility!');
            }

            foreach (Tools::getValue('id_ukoocompat_criterion') as $id_filter => $id_criterion) {
                if (!$object->addAssociatedCriterion($id_filter, $id_criterion)) {
                    $this->errors[] = $this->l('Unabled to associate criterion to compatibility!');
                }
            }
        }

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction exécutée lors de la suppression d'une compatibilité
     * @return mixed
     */
    public function processDelete()
    {
        $object = parent::processDelete();

        // Suppression des critères associés à la compat dans la table ukoocompat_criterion
        if (!$object->deleteAssociatedCriteria()) {
            $this->errors[] = $this->l('Unabled to desassociate criteria from compatibility!');
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Function exécutée lors de l'export des compatibilités
     * @param string $text_delimiter
     */
    public function processExport($text_delimiter = '"')
    {
        // On vide le buffer si ce n'est déjà fait !
        if (ob_get_level() && ob_get_length() > 0) {
            ob_clean();
        }
        $this->getList($this->context->language->id, null, null, 0, false);
        if (!count($this->_list)) {
            return;
        }

        $filters = UkooCompatFilter::getFilters((int)$this->context->language->id, false);

        header('Content-type: text/csv');
        header('Content-Type: application/force-download; charset=UTF-8');
        header('Cache-Control: no-store, no-cache');
        header('Content-disposition: attachment; filename="'.$this->table.'_'.date('Y-m-d_His').'.csv"');

        // Génération de la ligne d'en-tête avec le nom des filtres
        $headers = array('ID product');
        foreach ($filters as $filter) {
            $headers[] = Tools::htmlentitiesDecodeUTF8($filter['name']);
        }

        $content = array();
        foreach ($this->_list as $k => $compat) {
            // Récupération des critères de la compat
            $criteria = UkooCompatCompat::getCompatibilityAssociatedCriteria(
                (int)$compat['id_ukoocompat_compat'],
                (int)$this->context->language->id);

            // Génération de la ligne de compat
            $content[$k] = array((int)$compat['id_product']);
            foreach ($filters as $filter) {
                if (isset($criteria[(int)$filter['id']])) {
                    $content[$k][] = ($criteria[(int)$filter['id']]['id_ukoocompat_criterion'] == '*' ?
                        '*' : $criteria[(int)$filter['id']]['criterion_value']);
                } else {
                    $content[$k][] = '';
                }
            }
        }

        $this->context->smarty->assign(array(
            'export_precontent' => "\xEF\xBB\xBF",
            'export_headers' => $headers,
            'export_content' => $content,
            'text_delimiter' => $text_delimiter));

        $this->layout = '../modules/ukoocompat/views/templates/admin/layout-export.tpl';
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if ($this->display != 'edit' && $this->display != 'add') {
            // Bouton nouveau dans la toolbar
            $this->page_header_toolbar_btn['new_ukoocompat_compat'] = array(
                'href' => self::$currentIndex.'&addukoocompat_compat&token='.$this->token,
                'desc' => $this->l('Add new compatibility'),
                'icon' => 'process-icon-new');
            // Bouton Export dans la toolbar
            $this->page_header_toolbar_btn['export_ukoocompat_compat'] = array(
                'href' => self::$currentIndex.'&exportukoocompat_compat&token='.$this->token,
                'desc' => $this->l('Export compatibilities'),
                'icon' => 'process-icon-export');
        }
        // Bouton documentation dans la toolbar
        $this->page_header_toolbar_btn['doc_ukoocompat_compat'] = array(
            'href' => '../modules/ukoocompat/doc/documentation_'.
                ($this->context->language->iso_code == 'fr' ? 'FR' : 'EN').'.pdf',
            'desc' => $this->l('Documentation'),
            'target' => '_blank',
            'icon' => 'process-icon-book');
    }

    /**
     * Création du menu latéral dans le BO
     * @return bool
     */
    public static function installInBO()
    {
        $languages = Language::getLanguages(true);
        $tab_trad = array('AdminUkooCompatParent' => array(
            'active' => 1, 'fr' => 'Compatibilités',
            'en' => 'Compatibilities'),
            'AdminUkooCompatCompat' => array(
                'active' => 1,
                'fr' => 'Compatibilités',
                'en' => 'Compatibilities'),
            'AdminUkooCompatSearch' => array(
                'active' => 1,
                'fr' => 'Recherches',
                'en' => 'Search'),
            'AdminUkooCompatFilter' => array(
                'active' => 1,
                'fr' => 'Filtres de recherche',
                'en' => 'Search filters'),
            'AdminUkooCompatCriterion' => array(
                'active' => 0,
                'fr' => 'Critères',
                'en' => 'Criteria'),
            'AdminUkooCompatGroup' => array(
                'active' => 0,
                'fr' => 'Groupes de critères',
                'en' => 'Criteria groups'),
            'AdminUkooCompatSearchFilter' => array(
                'active' => 0,
                'fr' => 'Filtres par recherche',
                'en' => 'Filters by search'),
            'AdminUkooCompatImportFile' => array(
                'active' => 1,
                'fr' => 'Imports CSV',
                'en' => 'CSV imports'),
            'AdminUkooCompatAlias' => array(
                'active' => 1,
                'fr' => 'Alias',
                'en' => 'Alias'),
            'AdminUkooCompatAliasInstance' => array(
                'active' => 1,
                'fr' => 'Correspondances',
                'en' => 'Alias instance'));

        // menu principal "compatibilities"
        $new_menu = new Tab();
        $new_menu->id_parent = 0;
        $new_menu->class_name = 'AdminUkooCompatParent'; // la classe "parent" n'existe pas
        $new_menu->module = 'ukoocompat';
        $new_menu->active = 1;

        foreach ($languages as $language) {
            if (isset($tab_trad['AdminUkooCompatParent'][$language['iso_code']])) {
                $new_menu->name[(int)$language['id_lang']] = $tab_trad['AdminUkooCompatParent'][$language['iso_code']];
            } else {
                $new_menu->name[(int)$language['id_lang']] = $tab_trad['AdminUkooCompatParent']['en'];
            }
        }
        $new_menu->save();

        // création des autres menus
        foreach ($tab_trad as $tab => $trad) {
            if ($tab != 'AdminUkooCompatParent') {
                $new_menu = new Tab();
                $new_menu->id_parent = Tab::getIdFromClassName('AdminUkooCompatParent');
                $new_menu->class_name = $tab;
                $new_menu->module = 'ukoocompat';
                $new_menu->active = (int)$trad['active'];

                foreach ($languages as $language) {
                    if (isset($tab_trad[$tab][$language['iso_code']])) {
                        $new_menu->name[(int)$language['id_lang']] = $tab_trad[$tab][$language['iso_code']];
                    } else {
                        $new_menu->name[(int)$language['id_lang']] = $tab_trad[$tab]['en'];
                    }
                }
                $new_menu->save();
            }
        }
        return true;
    }

    /**
     * Supprime l'élément parent, ainsi que tous ces enfants, du menu létarél dans le BO
     * @return bool
     */
    public static function removeFromBO()
    {
        $remove_id = Tab::getIdFromClassName('AdminUkooCompatParent');
        if ($remove_id) {
            $to_remove = new Tab($remove_id);
            if (validate::isLoadedObject($to_remove)) {
                return $to_remove->delete();
            }
        }
        return false;
    }

    /**
     * Supprimer une compatibilité, ainsi que toutes ses associations de critères
     */
    public function ajaxProcessDeleteCompatibility()
    {
        if (!Tools::isSubmit('id_compat')) {
            die(Tools::jsonEncode(array('status' => 'error', 'message' => $this->l('Wrong id_compat.'))));
        }

        if ($this->tabAccess['delete'] === '1') {
            // On récupère l'ID de la compatibilité à supprimer
            $id_compat = (int)Tools::getValue('id_compat');

            // On tente de charger la compatibilité (objet)
            if ($id_compat && Validate::isUnsignedId($id_compat) &&
                Validate::isLoadedObject($compatibility = new UkooCompatCompat($id_compat))) {
                // On tente de supprimer les associations de critères de cette compatiilité
                if (!$compatibility->deleteAssociatedCriteria()) {
                    $json = array(
                        'status' => 'error',
                        'message' => $this->l('Unabled to desassociate criteria from compatibility!'));
                } else {
                    // On supprime l'entrée de la compatibilité
                    if (!$compatibility->delete()) {
                        $json = array(
                            'status' => 'error',
                            'message' => $this->l('Unabled to delete the compatibility!'));
                    } else {
                        $json = array(
                            'status' => 'ok',
                            'message' => $this->l('Compatibility successfully deleted!'),
                            'id_compat' => (int)$id_compat);
                    }
                }
            } else {
                $json = array(
                    'status' => 'error',
                    'message' => $this->l('You cannot delete this compatibility.'));
            }
        } else {
            $json = array(
                'status' => 'error',
                'message' => $this->l('You do not have permission to delete this.'));
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        die(Tools::jsonEncode($json));
    }
}
