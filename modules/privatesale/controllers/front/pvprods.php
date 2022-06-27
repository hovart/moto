<?php

require_once(dirname(__FILE__).'/../../privatesale.php');

class privatesalepvprodsModuleFrontController extends ModuleFrontController
{
	protected $pvs_module;
	
	/*public function __construct()
	{
 		$this->context = Context::getContext();
 		$this->context->controller = new FrontController();
		$this->context->controller->init();
 		//d($this->context);
		$this->pvs_module = new privatesale();
	}*/

	public function initContent()
	{
		parent::initContent();

		$this->pvs_module = new privatesale();
		$this->displayContent();
	}

	public function displayContent()
	{
		global $smarty, $cookie;
		
		$translations = $this->pvs_module->getTranslations();
		
		if (!isset($cookie->id_customer))
		{
			$this->context->smarty->assign('error', $translations[0]);
		}
		elseif (isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$pvsale = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'pvsale WHERE id="'.(int)$_GET['id'].'"');
			if ($pvsale == FALSE)
				$this->context->smarty->assign('error', $translations[1]);
			elseif ($this->pvs_module->CheckSaleRegister($cookie->id_customer, $pvsale['id']) == FALSE)
				$this->context->smarty->assign('error', $translations[2]);
			else
			{
				$pvstatus = $this->pvs_module->getSaleStatus($pvsale['time_start'], $pvsale['time_end']);				
				if ($pvstatus == 3)
					$this->context->smarty->assign('error', $translations[3]);
				elseif($pvstatus == 1)
					$this->context->smarty->assign('error', $translations[4]);
				else
				{
					$category = new Category($pvsale['category'], $cookie->id_lang);
					$products = $category->getProducts($cookie->id_lang, 1, 100, NULL, NULL, false, false, false, 1, true);

					$subCategories = $category->getSubCategories((int)($cookie->id_lang));				
					if (isset($subCategories) AND !empty($subCategories) AND $subCategories)
					{
						$this->context->smarty->assign('subcategories', $subCategories);
						$this->context->smarty->assign(array(
							'subcategories_nb_total' => sizeof($subCategories),
							'subcategories_nb_half' => ceil(sizeof($subCategories) / 2)));
					}
					
					$this->context->smarty->assign('category', $category);					
					$this->context->smarty->assign(array(
						'products' => (isset($products) AND $products) ? $products : NULL,
						'id_category' => (int)($category->id),
						'id_category_parent' => (int)($category->id_parent),
						'return_category_name' => Tools::safeOutput($category->name),
						'path' => Tools::getPath((int)($category->id)),
						'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
						'categorySize' => Image::getSize('category'),
						'mediumSize' => Image::getSize('medium'),
						'thumbSceneSize' => Image::getSize('thumb_scene'),
						'homeSize' => Image::getSize('home')
					));
				}
				$this->context->smarty->assign('pvs_name', $pvsale['name']);
			}
		}
		else
			$this->context->smarty->assign('error', $translations[1]);
		
		//return self::$smarty->display(_PS_MODULE_DIR_.'privatesale/public/pvprods.tpl');
		$this->setTemplate('pvprods.tpl');
	}
}