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

class AdminUkooCompatSearchController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_search';
        $this->className = 'UkooCompatSearch';
        $this->lang = true;
        $this->_defaultOrderBy = 'position';
        $this->position_identifier = 'id_ukoocompat_search';
        $this->context = Context::getContext();
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->fields_list = array(
            'id_ukoocompat_search' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'name' => array(
                'title' => $this->l('Name'),
                'align' => 'left'),
            'id_hook' => array(
                'title' => $this->l('Hook'),
                'align' => 'left',
                'callback' => 'getHookName'),
            'display_alias_search_block' => array(
                'title' => $this->l('Display alias search'),
                'active' => 'display_alias_search_block',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false),
            'position' => array(
                'title' => $this->l('Ordre'),
                'filter_key' => 'position',
                'position' => 'position',
                'class' => 'fixed-width-xs',
                'align' => 'center'),
            'active' => array(
                'title' => $this->l('Displayed'),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false));
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        // Bouton nouvelle recherche
        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new_ukoocompat_search'] = array(
                'href' => self::$currentIndex.'&addukoocompat_search&token='.$this->token,
                'desc' => $this->l('Add new search', null, null, false),
                'icon' => 'process-icon-new');
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
     * Override la function setMedia pour appeler jQuery UI et tagify sur la page d'édition de la recherche
     */
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
    }

    /**
     * Fonction qui retourne le nom du hook d'après son id d'après les hook autorisés par le module
     * @param $id_hook
     * @return mixed
     */
    public function getHookName($id_hook)
    {
        foreach ($this->module->allowed_hooks as $hook) {
            if ((int)$hook['id'] == (int)$id_hook) {
                return $hook['name'];
            }
        }
    }


    /**
     * Ajoute un script à la vue renderList
     * Nécessaire pour la réorganisation des positions en ajax
     * @return mixed
     */
    public function renderList()
    {
        $this->addJS(_PS_JS_DIR_.'admin-dnd.js');
        return parent::renderList();
    }

    /**
     * Affiche le formulaire de création et édition d'une recherche
     * Attention : Vue très personnalisée !
     * TODO :: Lors de "enregistrer et rester", faire en sorte de revenir au même onglet
     * TODO :: Différencier les catégories d'affichage et les catégories sélectionnées pour la recherche
     */
    public function renderForm()
    {
        $this->addJqueryUI('ui.sortable');
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $selected_categories = $obj->getCategories();

        // Générer l'arbre des catégories (pour le tpl custom)
        // FIX (2015-07-26) :: ajout de l'ID de la catégorie "Accueil (#2)" (bug de l'arbre des catégories)
        $tree = new HelperTreeCategories('categories-tree', $this->l('Associated categories'), 2);
        $tree->setInputName('categories')
            ->setSelectedCategories($selected_categories)
            ->setUseCheckBox(true)
            ->setUseSearch(true);

        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Search settings'),
                'icon' => 'icon-search'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Internal Name'),
                    'name' => 'name',
                    'lang' => false,
                    'required' => true,
                    'hint' => $this->l('Only used to managed in back-office')),
                // comportement
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display search block'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')))),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'id_hook',
                    'required' => true,
                    'options' => array(
                        'query' => $this->module->allowed_hooks,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2'),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Submit search without button'),
                    'hint' => $this->l('Your customers will have to specify all fields'),
                    'name' => 'hide_button',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_button_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_button_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide the search block on the catalog pages and listing'),
                    'name' => 'hide_on_catalog',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_on_catalog_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_on_catalog_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display alias search block'),
                    'hint' => $this->l('Do not display helps save space, but necessary if you use aliases'),
                    'name' => 'display_alais_search_block',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_alais_search_block_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_alais_search_block_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Pre-filter criteria according to the current category'),
                    'name' => 'prefilter',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'prefilter_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'prefilter_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display product listing immediatly after search submission'),
                    'hint' =>
                        $this->l('Do not show the ctalog view. Usefull if you dont have multiple categories results'),
                    'name' => 'skip_catalog',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'skip_catalog_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'skip_catalog_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('List the catalog as menu in the listing'),
                    'hint' => $this->l('Will not be display if you skip the catalog view'),
                    'name' => 'display_menu',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_menu_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_menu_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display compatibilty tab on the product page'),
                    'hint' =>
                        $this->l(
                            'This can slow down the display of the product page if you have a lot of compatibility!'
                        ),
                    'name' => 'display_product_tab',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_product_tab_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'display_product_tab_off',
                            'value' => 0,
                            'label' => $this->l('No')))),
                // table _lang
                array(
                    'type' => 'text',
                    'label' => $this->l('Front-Office Name'),
                    'name' => 'title',
                    'lang' => true,
                    'required' => true,
                    'hint' => $this->l('Will be display as block title in front-office')),
                array(
                    'type' => 'text',
                    'label' => $this->l('Catalog page meta title'),
                    'name' => 'catalog_meta_title',
                    'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Catalog page meta description'),
                    'name' => 'catalog_meta_description',
                    'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Catalog page meta keywords'),
                    'name' => 'catalog_meta_keywords', 'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Catalog page title'),
                    'name' => 'catalog_title',
                    'lang' => true),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Catalog page description'),
                    'name' => 'catalog_description',
                    'autoload_rte' => true,
                    'lang' => true),
                array('type' => 'text',
                    'label' => $this->l('Listing page meta title'),
                    'name' => 'listing_meta_title',
                    'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Listing page meta description'),
                    'name' => 'listing_meta_description',
                    'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Listing page meta keywords'),
                    'name' => 'listing_meta_keywords',
                    'lang' => true),
                array(
                    'type' => 'text',
                    'label' => $this->l('Listing page title'),
                    'name' => 'listing_title',
                    'lang' => true),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Listing page description'),
                    'name' => 'listing_description',
                    'autoload_rte' => true,
                    'lang' => true),
                // association aux catégories
                array(
                    'type' => 'categories',
                    'label' => $this->l('Associated categories'),
                    'name' => 'categories',
                    'tree' => array(
                        'id' => 'categories-tree',
                        'selected_categories' => $selected_categories,
                        'disabled_categories' => null,
                        'use_checkbox' => true,
                        'use_search' => true))),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitAdd'.$this->table.'AndBackToParent'),
            'buttons' => array(
                'save_and_stay' => array(
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'title' => $this->l('Save and stay'),
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save')));

        $this->context->smarty->assign(array(
            'currentToken' => $this->token,
            'searchFilterToken' => Tools::getAdminTokenLite('AdminUkooCompatSearchFilter'),
            'currentObject' => $obj,
            'currentTab' => $this,
            'id_lang_default' => (int)Configuration::get('PS_LANG_DEFAULT'),
            'languages' => Language::getLanguages(),
            'allowed_hooks' => $this->module->allowed_hooks,
            'display_type' => $this->module->display_type,
            'category_tree' => $tree->render(),
            'filters' => $obj->getAvailableAndUsedFilters((int)$this->context->language->id),
            'modal_id' => 'ukoocompat_modal',
            'modal_content' => ''));

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'une nouvelle recherche
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Positionnement à la fin
        if (!$object->updatePosition()) {
            $this->errors[] = Tools::displayError('Unable to update search position!');
        }

        // on lance la fonction Update pour mettre à jour différentes infos après création de l'ID
        $this->processUpdate();

        return $object;
    }

    /**
     * Fonction exécutée lors de la mise à jour d'une recherche
     * @return mixed
     */
    public function processUpdate()
    {
        $object = parent::processUpdate();

        // Mise à jour des catégories associées
        if (Tools::isSubmit('categories')) {
            if (!$object->updateAssociatedCategories(Tools::getValue('categories'))) {
                $this->errors[] = Tools::displayError('Unable to update associated categories!');
            }
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction exécutée lors de la suppression d'une recherche
     * @return mixed
     */
    public function processDelete()
    {
        $object = parent::processDelete();

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction AJAX pour mettre à jour les positions
     */
    public function ajaxProcessUpdatePositions()
    {
        if (Tools::isSubmit('ukoocompat_search') && Tools::isSubmit('id')) {
            // on récupère les informations sur les lignes après le changement de position
            $error = false;
            foreach (Tools::getValue('ukoocompat_search') as $position => $row) {
                $r = explode('_', $row);
                if (!UkooCompatSearch::updateSearchPosition((int)$r[2], (int)$position)) {
                    $error = true;
                }
            }

            if ($error) {
                die('{"hasError" : true, errors : "Can not update search position"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Ids!"}');
        }
    }

    /**
     * Fonction AJAX pour ajouter un filtre à une recherche
     */
    public function ajaxProcessAddFilterToSearch()
    {
        if (Tools::isSubmit('id_search') && Tools::isSubmit('id_filter')) {
            $id_search = (int)Tools::getValue('id_search');
            $id_filter = (int)Tools::getValue('id_filter');

            // on tente de récupérer le filtre et la recherche en question pour voir s'ils existent
            if (!UkooCompatSearch::existsInDatabase($id_search, 'ukoocompat_search') ||
                !UkooCompatFilter::existsInDatabase($id_filter, 'ukoocompat_filter')) {
                die('{"hasError" : true, errors : "Filter or search (or both) dosnt exists!"}');
            }

            $filter = new UkooCompatFilter($id_filter);

            $search_filter = new UkooCompatSearchFilter();
            $search_filter->id_ukoocompat_search = $id_search;
            $search_filter->id_ukoocompat_filter = $id_filter;
            $search_filter->name = $filter->name;
            $search_filter->current_name = $filter->name[(int)$this->context->language->id];
            $search_filter->display_type = 'select';
            $search_filter->order_by = 'position';
            $search_filter->order_way = 'ASC';
            $search_filter->position = UkooCompatSearch::getNextPosition($id_search);
            $search_filter->active = 1;

            if (!$search_filter->add()) {
                die('{"hasError" : true, errors : "Filter or search (or both) doesnt exists!"}');
            } else {
                $search_filter = UkooCompatSearchFilter::getObjectFromSearchAndFilterIds(
                    (int)$id_search,
                    (int)$id_filter,
                    (int)$this->context->language->id
                );

                $this->context->smarty->assign(
                    'filter',
                    array(
                        'id_ukoocompat_search_filter' => (int)$search_filter->id,
                        'id' => (int)$id_filter,
                        'name' => $search_filter->name,
                        'position' => $search_filter->position,
                        'active' => $search_filter->active)
                );

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                $this->context->smarty->display(
                    '../modules/ukoocompat/views/templates/admin/ukoo_compat_search/helpers/form/used_filter_tr.tpl'
                );
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Ids!"}');
        }
    }

    /**
     * Fonction AJAX pour retirer un filtre d'une recherche
     */
    public function ajaxProcessRemoveFilterFromSearch()
    {
        if (Tools::isSubmit('id_search_filter')) {
            $id_search_filter = (int)Tools::getValue('id_search_filter');

            // on tente de récupérer le filtre en question pour voir s'il existe
            if (!UkooCompatSearchFilter::existsInDatabase($id_search_filter, 'ukoocompat_search_filter')) {
                die('{"hasError" : true, errors : "Search filter dosent exists!"}');
            }

            $search_filter = new UkooCompatSearchFilter($id_search_filter, (int)$this->context->language->id);

            if (!$search_filter->delete()) {
                die('{"hasError" : true, errors : "Unable to delete search filter!"}');
            } else {
                $this->context->smarty->assign(
                    'filter',
                    array(
                        'id' => (int)$search_filter->id_ukoocompat_filter,
                        'default_name' => $search_filter->name)
                );

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                $this->context->smarty->display(
                    '../modules/ukoocompat/views/templates/admin/ukoo_compat_search/helpers/form/'.
                    'available_filter_tr.tpl'
                );
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Ids!"}');
        }
    }

    /**
     * Fonction AJAX pour changer le statut d'un filtre associé à la recherche (toggle)
     */
    public function ajaxProcessToggleSearchFilterState()
    {
        if (Tools::isSubmit('id_search_filter')) {
            $id_search_filter = (int)Tools::getValue('id_search_filter');

            // on tente de récupérer le filtre en question pour voir s'il existe
            if (!UkooCompatSearchFilter::existsInDatabase($id_search_filter, 'ukoocompat_search_filter')) {
                die('{"hasError" : true, errors : "Search filter dosent exists!"}');
            }

            $search_filter = new UkooCompatSearchFilter($id_search_filter);

            if (!$search_filter->toggleStatus()) {
                die('{"hasError" : true, errors : "Unable to toggle search filter!"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Id!"}');
        }
    }

    /**
     * Fonction AJAX pour modifier les positions des filtres au sein d'une recherche
     */
    public function ajaxProcessUpdateSearchFilterPositions()
    {
        if (Tools::isSubmit('id_search') && Tools::isSubmit('sorted_ids')) {
            //$id_search = (int)Tools::getValue('id_search');

            // pour chaque ligne on met à jour sa position
            $error = false;
            foreach (Tools::getValue('sorted_ids') as $position => $row) {
                $r = explode('_', $row);
                if (!UkooCompatSearchFilter::updateSearchFilterPosition((int)$r[3], (int)$position)) {
                    $error = true;
                }
            }
            if ($error) {
                die('{"hasError" : true, errors : "Can not update search filter positions"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Id or sorted_ids var!"}');
        }
    }
}
