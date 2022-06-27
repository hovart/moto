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

class UkooCompatCatalogModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    public function initContent()
    {
        // si les variables ne sont pas présente, on redirige vers la home
        if (!Tools::isSubmit('id_search')) {
            Tools::redirect('index');
        }

        parent::initContent();

        $ukoocompat = new UkooCompat();

        // on récupère les informations de la recherche
        $search = new UkooCompatSearch((int)Tools::getValue('id_search'), (int)$this->context->language->id);
        $search->current_id_lang = (int)$this->context->language->id;
        $search->filters = $search->getFilters((int)$this->context->language->id);

        // on assigne les critères sélectionnés à la recherche (pré-remplissage des valeurs saisies)
        $search->selected_criteria = unserialize($this->context->cookie->__get('ukoocompat_search_'.(int)$search->id));

        // on récupère les tags et leurs valeurs pour les filtres sélectionnés
        // puis on remplace les tags par leur valeur dans les différents éléments de la recherche
        $search->tags = UkooCompatCompat::getTags($search->selected_criteria, (int)$this->context->language->id);
        $search->replaceSEOTags();

        // on récupère les informations de l'alias pour affichage
        $id_alias = (int)UkooCompatAlias::getAliasFromSelectedCriteria($search->selected_criteria);
        if ($id_alias != 0) {
            $search->alias = new UkooCompatAlias($id_alias, (int)$this->context->language->id);
        } else {
            $search->alias = null;
        }

        $this->context->smarty->assign(
            array(
                'search' => $search,
                'meta_title' => $search->catalog_meta_title,
                'meta_description' => $search->catalog_meta_description,
                'meta_keywords' => $search->catalog_meta_keywords)
        );

        $this->setTemplate('catalog.tpl');
    }
}
