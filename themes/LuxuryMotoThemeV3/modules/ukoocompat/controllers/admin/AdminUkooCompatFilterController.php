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

class AdminUkooCompatFilterController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_filter';
        $this->className = 'UkooCompatFilter';
        $this->lang = true;
        $this->_defaultOrderBy = 'position';
        $this->position_identifier = 'id_ukoocompat_filter';
        $this->context = Context::getContext();
        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->fields_list = array(
            'id_ukoocompat_filter' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'name' => array(
                'title' => $this->l('Name'),
                'align' => 'left'),
            'position' => array(
                'title' => $this->l('Ordre'),
                'filter_key' => 'position',
                'position' => 'position',
                'class' => 'fixed-width-xs',
                'align' => 'center'));
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_ukoocompat_filter'] = array(
                'href' => self::$currentIndex.'&addukoocompat_filter&token='.$this->token,
                'desc' => $this->l('Add new filter', null, null, false),
                'icon' => 'process-icon-new');
            $this->page_header_toolbar_btn['new_ukoocompat_criterion'] = array(
                'href' => 'index.php?controller=AdminUkooCompatCriterion&addukoocompat_criterion&token='.
                    Tools::getAdminTokenLite('AdminUkooCompatCriterion'),
                'desc' => $this->l('Add new filter value', null, null, false),
                'icon' => 'process-icon-new');
        }
        if ($this->display == 'view') {
            $this->page_header_toolbar_btn['new_ukoocompat_criterion'] = array(
                'href' => 'index.php?controller=AdminUkooCompatCriterion&addukoocompat_criterion&token='.
                    Tools::getAdminTokenLite('AdminUkooCompatCriterion'),
                'desc' => $this->l('Add new filter value', null, null, false),
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
     * Affiche la liste des filtres et critères
     * @return mixed
     */
    public function renderList()
    {
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->addJS(_PS_JS_DIR_.'admin-dnd.js'); // nécessaire pour la réorganisation des positions en ajax
        return parent::renderList();
    }

    /**
     * Affiche le formulaire de création d'un filtre
     * @return mixed
     */
    public function renderForm()
    {
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Filter settings'),
                'icon' => 'icon-info-sign'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true)),
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

        return parent::renderForm();
    }

    /**
     * Le rendeView est similaire au renderList mais affiche les valeurs (critères de filtres)
     */
    public function renderView()
    {
        if (($id = Tools::getValue('id_ukoocompat_filter'))) {
            $this->table = 'ukoocompat_criterion';
            $this->className = 'UkooCompatCriterion';
            $this->identifier = 'id_ukoocompat_criterion';
            $this->list_id = 'ukoocompat_criterion';
            $this->lang = true;

            // Action for list
            $this->addRowAction('edit');
            $this->addRowAction('delete');

            if (!Validate::isLoadedObject($obj = new UkooCompatFilter((int)$id))) {
                $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').
                    ' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
                return;
            }

            $this->filter_name = $obj->name;
            $this->toolbar_title = $this->filter_name[$this->context->employee->id_lang];
            $this->fields_list = array(
                'id_ukoocompat_criterion' => array(
                    'title' => $this->l('ID'),
                    'align' => 'center',
                    'class' => 'fixed-width-xs'),
                'value' => array(
                    'title' => $this->l('Value')),
                'position' => array(
                    'title' => $this->l('Ordre'),
                    'filter_key' => 'position',
                    'position' => 'position',
                    'class' => 'fixed-width-xs',
                    'align' => 'center'));

            $this->_where = sprintf('AND `id_ukoocompat_filter` = %d', (int)$id);
            self::$currentIndex = 'index.php?controller=AdminUkooCompatCriterion&id_ukoocompat_filter='.
                (int)$id.'&viewukoocompat_filter';
            $this->token = Tools::getAdminTokenLite('AdminUkooCompatCriterion');
            $this->processFilter();
            return parent::renderList();
        }
    }

    /**
     * Fonction exécutée lors de la création d'un nouveau filtre
     * Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
     * Positionnement du nouveau filtre à la fin
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        // Positionnement à la fin
        if (!count($this->errors) && !$object->updatePosition()) {
            $this->errors[] = Tools::displayError('Unable to update filter position!');
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction exécuté lors de la mise à jour d'un filtre
     * Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
     * @return mixed
     */
    public function processUpdate()
    {
        $object = parent::processUpdate();

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction exécuté lors de la suppression d'un filtre
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
     * TODO :: BUGFIX le positionnement des critères en drag&drop ne fonctionne pas
     */
    public function ajaxProcessUpdatePositions()
    {
        if (Tools::isSubmit('ukoocompat_filter') && Tools::isSubmit('id')) {
            // On récupère les informations sur les lignes après le changement de position
            $error = false;
            foreach (Tools::getValue('ukoocompat_filter') as $position => $row) {
                $r = explode('_', $row);
                if (!UkooCompatFilter::updateFilterPosition((int)$r[2], (int)$position)) {
                    $error = true;
                }
            }

            if ($error) {
                die('{"hasError" : true, errors : "Can not update filter position"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        }
    }
}
