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

class UkooCompatListingModuleFrontController extends ModuleFrontController
{
    public $display_column_left = true;

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    public function setMedia()
    {
        parent::setMedia();

        if (!$this->useMobileTheme()) {
            $this->addCSS(array(_THEME_CSS_DIR_.'category.css' => 'all', _THEME_CSS_DIR_.'product_list.css' => 'all'));
        }
        $this->addJS(_THEME_JS_DIR_.'category.js');
    }

    public function initContent()
    {
        // si les variables ne sont pas présente, on redirige vers la home
        if (!Tools::isSubmit('id_search')) {
            Tools::redirect('index');
        }

        parent::initContent();

        $this->productSort();

        // on récupère les informations de la recherche
        $search = new UkooCompatSearch((int)Tools::getValue('id_search'), (int)$this->context->language->id);
        $search->current_id_lang = (int)$this->context->language->id;
        $search->filters = $search->getFilters((int)$this->context->language->id);

        // on assigne les critères sélectionnés à la recherche (pré-remplissage des valeurs saisies)
        $search->selected_criteria = unserialize($this->context->cookie->__get('ukoocompat_search_'.(int)$search->id));

        // on assigne la catégorie courante à la recherche
        $search->category = new Category(
            (Tools::isSubmit('id_category') ? (int)Tools::getValue('id_category') : null),
            (int)$this->context->language->id
        );

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

        // on assigne les produits compatibles
        $products = UkooCompatCompat::getCompatiblesProducts(
            $search->selected_criteria,
            (int)$this->context->language->id,
            (Tools::isSubmit('id_category') ? (int)Tools::getValue('id_category') : null),
            abs((int)Tools::getValue('p', 1)),
            abs((int)Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))),
            (Tools::isSubmit('orderby') ? Tools::getValue('orderby') : null),
            (Tools::isSubmit('orderway') ? Tools::getValue('orderway') : null)
        );
        $nb_products = (int)UkooCompatCompat::getCompatiblesProducts(
            $search->selected_criteria,
            (int)$this->context->language->id,
            (Tools::isSubmit('id_category') ? (int)Tools::getValue('id_category') : null),
            1,
            999,
            null,
            null,
            true
        );

        // affectation des coloris aux produits
        $this->addColorsToProductList($products);

        $this->context->smarty->assign(
            array(
                'search' => $search,
                'products' => $products,
                'nb_products' => $nb_products,
                'catalog_link' => $this->context->link->getModuleLink(
                    'ukoocompat',
                    'catalog',
                    array(
                        'id_search' => $search->id,
                        'filters' => $search->selected_criteria)
                ),
                'meta_title' => $search->listing_meta_title,
                'meta_description' => $search->listing_meta_description)
        );
        if (isset($this->context->cookie->id_compare)) {
            $this->context->smarty->assign(
                'compareProducts',
                CompareProduct::getCompareProducts((int)$this->context->cookie->id_compare)
            );
        }

        $this->setTemplate('listing.tpl');
    }
}
