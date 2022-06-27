<?php
class Link extends LinkCore
{
	public function getImageLink($name, $ids, $type = null, $rollover = null)
	{
		$not_default = false;
		// legacy mode or default image
		$theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_.$ids.($type ? '-'.$type : '').'-'.(int)Context::getContext()->shop->id_theme.'.jpg')) ? '-'.Context::getContext()->shop->id_theme : '');
		if ((Configuration::get('PS_LEGACY_IMAGES')
			&& (file_exists(_PS_PROD_IMG_DIR_.$ids.($type ? '-'.$type : '').$theme.'.jpg')))
			|| ($not_default = strpos($ids, 'default') !== false))
		{
			if ($this->allow == 1 && !$not_default)
				$uri_path = __PS_BASE_URI__.$ids.($type ? '-'.$type : '').$theme.'/'.$name.'.jpg';
			else
				$uri_path = _THEME_PROD_DIR_.$ids.($type ? '-'.$type : '').$theme.'.jpg';
		}
		else
		{
			// if ids if of the form id_product-id_image, we want to extract the id_image part
			$split_ids = explode('-', $ids);
			$id_image = (isset($split_ids[1]) ? $split_ids[1] : $split_ids[0]);
			$theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_.Image::getImgFolderStatic($id_image).$id_image.($type ? '-'.$type : '').'-'.(int)Context::getContext()->shop->id_theme.'.jpg')) ? '-'.Context::getContext()->shop->id_theme : '');
			
			if(isset($rollover))
			{
				$hover_image = Db::getInstance()->getRow('SELECT id_image FROM '._DB_PREFIX_.'image WHERE id_product = '.$rollover.' AND position = 2');
				$no_hover = Db::getInstance()->getRow('SELECT id_image FROM '._DB_PREFIX_.'image WHERE id_product = '.$rollover.' AND position = 1');
 
				if($hover_image)
					$id_image = array_shift($hover_image);
				else
					$id_image = array_shift($no_hover);
			}
 
			if ($this->allow == 1)
				$uri_path = __PS_BASE_URI__.$id_image.($type ? '-'.$type : '').$theme.'/'.$name.'.jpg';
			else
				$uri_path = _THEME_PROD_DIR_.Image::getImgFolderStatic($id_image).$id_image.($type ? '-'.$type : '').$theme.'.jpg';
		}
 
		return $this->protocol_content.Tools::getMediaServer($uri_path).$uri_path;
	}
}