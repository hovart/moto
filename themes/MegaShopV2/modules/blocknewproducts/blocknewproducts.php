<?php
class BlockNewProducts extends BlockNewProductsModule
{
	public function hookRightColumn($params)
	{
		if (!$this->isCached('blocknewproducts.tpl', $this->getCacheId()))
		{
			if (!Configuration::get('NEW_PRODUCTS_NBR'))
				return;
			$newProducts = false;
			if (Configuration::get('PS_NB_DAYS_NEW_PRODUCT'))
				$newProducts = Product::getNewProducts((int) $params['cookie']->id_lang, 0, (int)Configuration::get('NEW_PRODUCTS_NBR'));
				shuffle($newProducts); // DETAILS.CH - Add shuffle
			if (!$newProducts && !Configuration::get('PS_BLOCK_NEWPRODUCTS_DISPLAY'))
				return;
			$this->smarty->assign(array(
				'new_products' => $newProducts,
				'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
			));
		}
		return $this->display(__FILE__, 'blocknewproducts.tpl', $this->getCacheId());
	}
}