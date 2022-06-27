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

class AdminUkooCompatAliasInstanceController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_alias_instance';
        $this->className = 'UkooCompatAliasInstance';
        $this->lang = false;
        $this->position_identifier = 'id_ukoocompat_alias_instance';
        $this->context = Context::getContext();
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->_select = 'uca.`alias`, a.`id_ukoocompat_alias_instance` AS `instance`';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'ukoocompat_alias` uca
			ON (a.`id_ukoocompat_alias` = uca.`id_ukoocompat_alias`)';


        $this->fields_list = array(
            'id_ukoocompat_alias_instance' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'alias' => array(
                'title' => $this->l('Alias'),
                'align' => 'left'),
            'instance' => array(
                'title' => $this->l('Associated criteria'),
                'align' => 'left',
                'callback' => 'getListAssociatedCriteria',
                'orderby' => false,
                'filter' => false,
                'search' => false));
    }

    /**
     * Récupère la liste des critères de l'instance et l'affiche dans la renderListe
     * @param $id_alias_instance
     * @return string
     */
    public function getListAssociatedCriteria($id_alias_instance)
    {
        $criteria = UkooCompatAliasInstance::getAliasInstanceAssociatedCriteria(
            $id_alias_instance,
            (int)$this->context->language->id
        );
        $output = array();
        foreach ($criteria as $criterion) {
            $output[] = $criterion['filter_name'].$this->l(': ').
                ($criterion['id_ukoocompat_criterion'] == '*' ? $this->l('All') : $criterion['criterion_value']);
        }
        return implode(', ', $output);
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        // Bouton nouvelle instance d'alias
        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new_ukoocompat_alias_instance'] = array(
                'href' => self::$currentIndex.'&addukoocompat_alias_instance&token='.$this->token,
                'desc' => $this->l('Add new alias instance', null, null, false),
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
     * Affiche le formulaire de création ou d'édition d'une instance d'alias
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
                'title' => $this->l('Alias'),
                'icon' => 'icon-share'),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Alias'),
                    'name' => 'alias',
                    'lang' => false,
                    'required' => true,
                    'hint' => $this->l('Alias used to quick search some compatibilities'))),
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
            'aliasToken' => Tools::getAdminTokenLite('AdminUkooCompatAlias'),
            'currentObject' => $obj,
            'currentTab' => $this,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            'languages' => Language::getLanguages(),
            'filters' => UkooCompatFilter::getFilters((int)$this->context->language->id, true),
            'selected_criteria' => $obj->getAssociatedCriteria((int)$this->context->language->id)));

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'une nouvelle instance d'alias
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Ajout des critères associés à l'instance d'alias dans la table ukoocompat_alias_criterion
        if (Tools::isSubmit('id_ukoocompat_criterion') && !count($this->errors)) {
            foreach (Tools::getValue('id_ukoocompat_criterion') as $id_filter => $id_criterion) {
                if (!$object->addAssociatedCriterion($id_filter, $id_criterion)) {
                    $this->errors[] = $this->l('Unabled to associate criterion to alias!');
                }
            }
        }

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        return $object;
    }
}
