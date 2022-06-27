<?php
class Blocklateralreinsurance extends BlocklateralreinsuranceModule
{
	public function hookFooter($params)
	{
		$this->context->smarty->assign('theme_dir', _THEME_DIR_);
		
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS(_THEME_DIR_.'css/modules/blocklateralreinsurance/blocklateralreinsurance3.css', 'all');
		if (!$this->isCached('blocklateralreinsurance3.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance3.tpl', $this->getCacheId());
	}


		public function hookHome($params)
		{
		$this->context->smarty->assign('theme_dir', _THEME_DIR_);
		
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS(_THEME_DIR_.'css/modules/blocklateralreinsurance/blocklateralreinsurance3.css', 'all');
		if (!$this->isCached('blocklateralreinsurance2.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance2.tpl', $this->getCacheId());
	}
}
