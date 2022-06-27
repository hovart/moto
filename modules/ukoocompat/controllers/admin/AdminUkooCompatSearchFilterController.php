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

class AdminUkooCompatSearchFilterController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_search_filter';
        $this->className = 'UkooCompatSearchFilter';
        $this->lang = true;
        $this->_defaultOrderBy = 'position';
        $this->position_identifier = 'id_ukoocompat_search_filter';
        $this->context = Context::getContext();
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->_select = 'us.`name` AS search_name, ufl.`name` AS filter_name';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'ukoocompat_search` us
				ON (a.`id_ukoocompat_search` = us.`id_ukoocompat_search`)
			LEFT JOIN `'._DB_PREFIX_.'ukoocompat_filter_lang` ufl
				ON (a.`id_ukoocompat_filter` = ufl.`id_ukoocompat_filter` AND ufl.`id_lang` = '.
            (int)$this->context->language->id.')';

        $this->fields_list = array(
            'id_ukoocompat_search_filter' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'name' => array(
                'title' => $this->l('Name'),
                'align' => 'left'),
            'search_name' => array(
                'title' => $this->l('Search name'),
                'align' => 'left'),
            'filter_name' => array(
                'title' => $this->l('Filter'),
                'align' => 'left'),
            'display_type' => array(
                'title' => $this->l('Display type'),
                'align' => 'left'),
            'order_by' => array(
                'title' => $this->l('Order by'),
                'align' => 'left'),
            'order_way' => array(
                'title' => $this->l('Order way'),
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
     * Affiche le formulaire de création / édition d'un nouveau filtre de recherche
     */
    public function renderForm()
    {
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        // On charge les critères du filtre
        $obj->criteria = $obj->getCriteria();

        // On charge les groupes de critères du filtre
        $obj->groups = UkooCompatSearchFilter::getGroups((int)$obj->id, null, 'all');

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Filter Search settings'),
                'icon' => 'icon-search'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Display Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                    'class' => 'name_by_lang',
                    'hint' => $this->l('This will be display in your search block')),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display search filter'),
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
                    'label' => $this->l('Display type'),
                    'name' => 'display_type',
                    'required' => true,
                    'options' => array(
                        'query' => $this->module->display_type,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2'),
                array(
                    'type' => 'select',
                    'label' => $this->l('Order by'),
                    'name' => 'order_by',
                    'required' => true,
                    'options' => array(
                        'query' => $this->module->order_by,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2'),
                array(
                    'type' => 'select',
                    'label' => $this->l('Order way'),
                    'name' => 'order_way',
                    'required' => true,
                    'options' => array(
                        'query' => $this->module->order_way,
                        'id' => 'id',
                        'name' => 'name'),
                    'col' => '2')),
            'buttons' => array(
                'submit' => array(
                    'name' => 'submit',
                    'type' => 'button',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'),
                'cancel' => array(
                    'name' => 'cancel',
                    'type' => 'button',
                    'title' => $this->l('Cancel'),
                    'class' => 'btn btn-default',
                    'icon' => 'process-icon-cancel')));

        $this->context->smarty->assign(array(
            'currentToken' => $this->token,
            'currentIndex' => 'index.php?controller=Admin'.$this->className.'Controller',
            'groupToken' => Tools::getAdminTokenLite('AdminUkooCompatGroup'),
            'currentObject' => $obj,
            'currentTab' => $this,
            'display_type' => $this->module->display_type,
            'order_by' => $this->module->order_by,
            'order_way' => $this->module->order_way,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            'languages' => Language::getLanguages()));

        return parent::renderForm();
    }

    /**
     * Fonction AJAX pour afficher l'édition du filtre dans une modal (renderForm)
     */
    public function ajaxProcessGetSearchFilterForm()
    {
        if (Tools::isSubmit('id_search_filter')) {
            // on créé l'objet qui sera utilisé par le renderForm
            $this->object = new UkooCompatSearchFilter((int)Tools::getValue('id_search_filter'));

            die(Tools::jsonEncode(array(
                'render_form' => str_replace('active_', 'search_filter_active_', $this->renderForm()))));
        } else {
            die('{"hasError" : true, errors : "Invalid Ids!"}');
        }
    }

    /**
     * Fonction AJAX pour faire un update d'un filtre de recherche
     */
    public function ajaxProcessUpdateSearchFilterForm()
    {
        if (Tools::isSubmit('id_search_filter')) {
            // on récupère l'objet en bdd
            $search_filter = new UkooCompatSearchFilter((int)Tools::getValue('id_search_filter'));

            // on met à jour sa configuration
            $search_filter->name = (Tools::isSubmit('name') ? Tools::getValue('name') : 1);
            if (isset($search_filter->name[0])) {
                unset($search_filter->name[0]);
            }
            $search_filter->active = (Tools::isSubmit('active') ? Tools::getValue('active') : 1);
            $search_filter->order_by = (Tools::isSubmit('order_by') ? Tools::getValue('order_by') : 'position');
            $search_filter->order_way = (Tools::isSubmit('order_way') ? Tools::getValue('order_way') : 'ASC');
            $search_filter->display_type = (Tools::isSubmit('display_type') ?
                Tools::getValue('display_type') : 'select');

            // Update du filtre de recherche
            if (!$search_filter->update()) {
                die('{"hasError" : true, errors : "Unable to update search filter!"}');
            } else {
                // Infos de retour ajax
                $this->context->smarty->assign('filter', array(
                    'id_ukoocompat_search_filter' => (int)$search_filter->id,
                    'id' => (int)$search_filter->id,
                    'name' => $search_filter->name[(int)$this->context->language->id],
                    'position' => $search_filter->position, 'active' => $search_filter->active));

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
     * Fonction AJAX pour modifier les positions des groupes de critères au sein d'un filtre
     */
    public function ajaxProcessUpdateGroupsPositions()
    {
        if (Tools::isSubmit('id_search_filter') && Tools::isSubmit('sorted_ids')) {
            // pour chaque ligne on met à jour sa position
            $error = false;
            foreach (Tools::getValue('sorted_ids') as $position => $row) {
                $r = explode('_', $row);
                if (!UkooCompatGroup::updateGroupPosition((int)$r[3], (int)$position)) {
                    $error = true;
                }
            }
            if ($error) {
                die('{"hasError" : true, errors : "Can not update groups positions"}');
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
