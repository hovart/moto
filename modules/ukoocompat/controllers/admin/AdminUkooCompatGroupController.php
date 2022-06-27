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

class AdminUkooCompatGroupController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_group';
        $this->className = 'UkooCompatGroup';
        $this->lang = true;
        $this->_defaultOrderBy = 'position';
        $this->position_identifier = 'id_ukoocompat_group';
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
            'id_ukoocompat_group' => array(
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

        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new_ukoocompat_group'] = array(
                'href' => self::$currentIndex.'&addukoocompat_group&token='.$this->token,
                'desc' => $this->l('Add new criteria group', null, null, false),
                'icon' => 'process-icon-new');
        }
    }

    /**
     * Affiche la liste des groupes créés
     * @return mixed
     */
    public function renderList()
    {
        $this->addJS(_PS_JS_DIR_.'admin-dnd.js'); // nécessaire pour la réorganisation des positions en ajax
        return parent::renderList();
    }

    /**
     * Affiche le formulaire de création et édition des groupes de critères
     */
    public function renderForm()
    {
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $filters = UkooCompatFilter::getFilters($this->context->language->id);
        if (Tools::isEmpty($filters)) {
            $this->errors[] = Tools::displayError('You have to create filters and criteria first!');
        }

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Group settings'),
                'icon' => 'icon-info-sign'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                    'hint' => $this->l('This will be display as options group in the associated filter')),
                array(
                    'type' => 'select',
                    'label' => $this->l('Filter'),
                    'name' => 'id_ukoocompat_filter',
                    'required' => true,
                    'options' => array(
                        'query' => $filters,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2'),
                array(
                    'type' => 'select',
                    'label' => $this->l('Criteria'),
                    'name' => 'selected',
                    'required' => true,
                    'multiple' => true,
                    'options' => array(
                        'query' => $filters,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2')),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitAdd'.$this->table.'AndBackToParent'));

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'un nouveau groupe
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Positionnement à la fin
        if (!$object->updatePosition()) {
            $this->errors[] = Tools::displayError('Unable to update search position!');
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
     * Fonction AJAX pour mettre à jour les positions
     */
    public function ajaxProcessUpdatePositions()
    {
        if (Tools::isSubmit('ukoocompat_group') && Tools::isSubmit('id')) {
            // on récupère les informations sur les lignes après le changement de position
            $error = false;
            foreach (Tools::getValue('ukoocompat_group') as $position => $row) {
                $r = explode('_', $row);
                if (!UkooCompatGroup::updateGroupPosition((int)$r[2], (int)$position)) {
                    $error = true;
                }
            }

            if ($error) {
                die('{"hasError" : true, errors : "Can not update group position"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        }
    }

    /**
     * Fonction AJAX pour chargé le formulaire d'édition du groupe dans la modal
     */
    public function ajaxProcessGetGroup()
    {
        if (Tools::isSubmit('id_group') && Tools::isSubmit('id_search_filter')) {
            $error = false;

            // On charge l'objet du filtre de recherche
            $search_filter = new UkooCompatSearchFilter((int)Tools::getValue('id_search_filter'));
            $search_filter->criteria = $search_filter->getCriteria();
            $search_filter->groups = UkooCompatSearchFilter::getGroups((int)$search_filter->id, null, 'all');

            // si l'id du groupe est soumis, c'est une édition
            if (Tools::getValue('id_group') != 0) {
                // On charge l'objet du group
                $group = new UkooCompatGroup((int)Tools::getValue('id_group'));
                $group->selected_array = explode(',', $group->selected);
                $group_edit = true;
            } else {
                $group = null;
                $group_edit = false;
            }

            // Infos de retour ajax
            $this->context->smarty->assign(array(
                'currentObject' => $search_filter,
                'group' => $group,
                'group_edit' => $group_edit,
                'id_lang_default' => (int)Configuration::get('PS_LANG_DEFAULT'),
                'languages' => Language::getLanguages()));

            if ($error) {
                die('{"hasError" : true, errors : "Can not load group edition"}');
            } else {
                $this->context->smarty->display(
                    '../modules/ukoocompat/views/templates/admin/ukoo_compat_search_filter/helpers/form/group_form.tpl'
                );
            }
        }
    }

    /**
     * Fonction AJAX pour supprimer un groupe de critères dans la modal
     */
    public function ajaxProcessDeleteGroup()
    {
        if (Tools::isSubmit('id_group')) {
            $id_group = (int)Tools::getValue('id_group');

            // on tente de récupérer le filtre en question pour voir s'il existe
            if (!UkooCompatGroup::existsInDatabase($id_group, 'ukoocompat_group')) {
                die('{"hasError" : true, errors : "Group dosent exists!"}');
            }

            // On charge l'objet
            $group = new UkooCompatGroup($id_group);
            // On tente de supprimer l'objet
            if (!$group->delete()) {
                die('{"hasError" : true, errors : "Can not delete group"}');
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
     * Fonction AJAX pour enregistrer un groupe ou un nouveau groupe dans la modal
     */
    public function ajaxProcessSaveGroup()
    {
        if (Tools::isSubmit('id_group') && Tools::isSubmit('id_search_filter')) {
            $error = false;

            // si l'id du groupe est soumis, c'est une édition
            if (Tools::getValue('id_group') != 0) {
                $id_group = (int)Tools::getValue('id_group');

                // on tente de récupérer le filtre en question pour voir s'il existe
                if (!UkooCompatGroup::existsInDatabase($id_group, 'ukoocompat_group')) {
                    die('{"hasError" : true, errors : "Group dosent exists!"}');
                }

                // On charge l'objet du group
                $group = new UkooCompatGroup($id_group);
                $group->name = Tools::getValue('group_name');
                if (isset($group->name[0])) {
                    unset($group->name[0]);
                }
                $group->active = (Tools::isSubmit('group_active') ? Tools::getValue('group_active') : 1);
                $group->selected = (Tools::isSubmit('group_selected') ?
                    implode(',', Tools::getValue('group_selected')) : '');

                if (!$group->update()) {
                    die('{"hasError" : true, errors : "Unable to update criteria group!"}');
                }
            } else { // sinon, c'est un nouveau groupe à créer
                $group = new UkooCompatGroup();
                $group->id_ukoocompat_search_filter = (int)(int)Tools::getValue('id_search_filter');
                $group->name = Tools::getValue('group_name');
                if (isset($group->name[0])) {
                    unset($group->name[0]);
                }
                $group->position = (int)UkooCompatGroup::getNextGroupPosition();
                $group->active = (Tools::isSubmit('group_active') ? Tools::getValue('group_active') : 1);
                $group->selected = (Tools::isSubmit('group_selected') ?
                    implode(',', Tools::getValue('group_selected')) : '');

                if (!$group->add()) {
                    die('{"hasError" : true, errors : "Unable to create new criteria group!"}');
                }
            }

            // Infos de retour ajax
            $this->context->smarty->assign(array(
                'group' => $group,
                'current_id_lang' => (int)$this->context->language->id));

            if ($error) {
                die('{"hasError" : true, errors : "Can not load group edition"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                $this->context->smarty->display(
                    '../modules/ukoocompat/views/templates/admin/ukoo_compat_search_filter/helpers/form/group_tr.tpl'
                );
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Id!"}');
        }
    }

    /**
     * Fonction AJAX pour changer le statut d'un groupe de critères (toggle)
     */
    public function ajaxProcessToggleGroupState()
    {
        if (Tools::isSubmit('id_group')) {
            $id_group = (int)Tools::getValue('id_group');

            // on tente de récupérer le filtre en question pour voir s'il existe
            if (!UkooCompatGroup::existsInDatabase($id_group, 'ukoocompat_group')) {
                die('{"hasError" : true, errors : "Group dosent exists!"}');
            }

            $group = new UkooCompatGroup($id_group);

            if (!$group->toggleStatus()) {
                die('{"hasError" : true, errors : "Unable to toggle group!"}');
            } else {

                // Suppression du cache du module
                UkooCompat::clearUkooCompatCache();

                die(true);
            }
        } else {
            die('{"hasError" : true, errors : "Invalid Id!"}');
        }
    }
}
