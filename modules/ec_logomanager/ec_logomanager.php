<?php
if (!defined('_PS_VERSION_'))
    exit;

class ec_logoManager extends Module
{
    public function __construct()
    {
        $this->name = 'ec_logomanager';
        $this->tab = 'administration';
        $this->version = '1';
        $this->author = 'Ether Création';
        $this->need_instance = 0;
		$this->module_key = '7014bdc89030429592715833056883a9';

        parent::__construct();

        $this->displayName = $this->l('Ec Logo Manager');
        $this->description = $this->l('Manage your logo for each language and shop');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (parent::install() == false)
            return false;

		return true;
    }

    public function uninstall()
    {
		if (!parent::uninstall())
			return false;

		return true;
    }

    public function getContent()
    {
		$this->_html = '';
        $this->postProcess();
		$helper = $this->initForm();
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
		{

			if(Shop::IsFeatureActive() && Context::getContext()->shop->getContext() != Shop::CONTEXT_ALL)
			{
				$helper->fields_value['PS_LOGO_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id).'.jpg">' : '');
				if ($helper->fields_value['PS_LOGO_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id))) / 1000;

				$helper->fields_value['PS_LOGO_MOBILE_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id).'.jpg">' : '');
				if ($helper->fields_value['PS_LOGO_MOBILE_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_MOBILE_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id))) / 1000;

				$helper->fields_value['PS_LOGO_MAIL_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id).'">' : '');
				if ($helper->fields_value['PS_LOGO_MAIL_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_MAIL_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id))) / 1000;

				$helper->fields_value['PS_LOGO_INVOICE_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id).'">' : '');
				if ($helper->fields_value['PS_LOGO_INVOICE_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_INVOICE_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id))) / 1000;

				$helper->fields_value['PS_FAVICON_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_FAVICON_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? '<img src="'._PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id).'">' : '');
				if ($helper->fields_value['PS_FAVICON_LINK'.$language['id_lang']])
					$helper->fields_value['PS_FAVICON_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id))) / 1000;

				$helper->fields_value['logo_name_'.$language['id_lang']] = ((Configuration::get('PS_LOGO_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? (Configuration::get('PS_LOGO_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) : '');
				$helper->fields_value['mobile_logo_name_'.$language['id_lang']] = ((Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) ? (Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id)) : '');

			}
			else
			{
				$helper->fields_value['PS_LOGO_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_LINK_'.$language['language_code'])) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_LINK_'.$language['language_code']).'.jpg">' : '');
				if ($helper->fields_value['PS_LOGO_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_LINK_'.$language['language_code']))) / 1000;

				$helper->fields_value['PS_LOGO_MOBILE_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'])) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code']).'.jpg">' : '');
				if ($helper->fields_value['PS_LOGO_MOBILE_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_MOBILE_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code']))) / 1000;

				$helper->fields_value['PS_LOGO_MAIL_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code'])) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code']).'">' : '');
				if ($helper->fields_value['PS_LOGO_MAIL_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_MAIL_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_MAIL_LINK_'.$language['language_code']))) / 1000;

				$helper->fields_value['PS_LOGO_INVOICE_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'])) ? '<img src="'._PS_IMG_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code']).'">' : '');
				if ($helper->fields_value['PS_LOGO_INVOICE_LINK'.$language['id_lang']])
					$helper->fields_value['PS_LOGO_INVOICE_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code']))) / 1000;

				$helper->fields_value['PS_FAVICON_LINK_'.$language['id_lang']]['image'] = ((Configuration::get('PS_FAVICON_LINK_'.$language['language_code'])) ? '<img src="'._PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.$language['language_code']).'">' : '');
				if ($helper->fields_value['PS_FAVICON_LINK'.$language['id_lang']])
					$helper->fields_value['PS_FAVICON_LINK'.$language['id_lang']]['size'] = filesize(dirname(_PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.$language['language_code']))) / 1000;

				$helper->fields_value['logo_name_'.$language['id_lang']] = ((Configuration::get('PS_LOGO_LINK_'.$language['language_code'])) ? (Configuration::get('PS_LOGO_LINK_'.$language['language_code'])) : '');
				$helper->fields_value['mobile_logo_name_'.$language['id_lang']] = ((Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'])) ? (Configuration::get('PS_LOGO_MOBILE_LINK_'.$language['language_code'])) : '');


			}
		}
		$this->_html .= $helper->generateForm($this->fields_form);
		$this->_html .='<fieldset class="space">
                        <legend>Info / Coordonnées</legend>
                        <p>'.$this->description.'</p>
                        <p>Développé par : Agence '.$this->author.'</p>
                        <p>Site : <a href="http://www.ethercreation.com">www.ethercreation.com</a></p>
                        <p>Tel : 02.85.52.07.81 / Mail: <a href="mailto:contact@ethercreation.com">contact@ethercreation.com</a>
                        <p>&nbsp;</p>
                        <p><i><strong>Ce module ne peut ni être diffusé, modifié, ou vendu sans l\'accord au préalable écrit de la société Ether Création</i></strong></p>
                    	</fieldset>';
		return $this->_html;
    }

    private function initForm()
	{
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language)
            $languages[$k]['is_default'] = (int)($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));
       $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->languages = $languages;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = false;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitLogoUpload';
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->displayName
            ),
            'input' => array(
                array(
                    'type' => 'file',
					'label' => $this->l('Header Logo'),
                    'lang' => true,
                    'name' => 'PS_LOGO_LINK',
                    'display_image' => true
                ),
				array(
					'type' => 'text',
					'label' => $this->l('Name of the Header Logo'),
					'desc' => $this->l('Give a name for your logo.').' '.$this->l('Default value: logo'),
					'name' => 'logo_name',
					'lang' => true,
					'size' => 64,
				),
				array(
					'type' => 'file',
					'label' => $this->l('Mobile Logo'),
					'lang' => true,
					'name' => 'PS_LOGO_MOBILE_LINK',
					'display_image' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('Name of the Mobile Logo'),
					'desc' => $this->l('Give a name for your mobile logo.').' '.$this->l('Default value: mobile_logo'),
					'name' => 'mobile_logo_name',
					'lang' => true,
					'size' => 64,
				),
				array(
					'type' => 'file',
					'label' => $this->l('Favicon'),
					'desc' => $this->l('Only .ico'),
					'lang' => true,
					'name' => 'PS_FAVICON_LINK',
					'display_image' => true
				),
				array(
					'type' => 'file',
					'label' => $this->l('Mail Logo'),
					'lang' => true,
					'name' => 'PS_LOGO_MAIL_LINK',
					'display_image' => true
				),
				array(
					'type' => 'file',
					'label' => $this->l('Invoice Logo'),
					'lang' => true,
					'name' => 'PS_LOGO_INVOICE_LINK',
					'display_image' => true
				)

            ),
			'submit' => array(
				'name' => 'submitLogoUpload',
				'title' => $this->l('Save '),
				'class' => 'button'
			)
        );

        return $helper;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitLogoUpload'))
        {
            $languages = Language::getLanguages(false);
            foreach ($languages AS $language)
			{
				if(isset($_FILES['PS_LOGO_LINK_'.$language['id_lang']]))
				{
				if (isset($_POST['logo_name_'.$language[id_lang]]) && !empty($_POST['logo_name_'.$language[id_lang]]) )
                	$this->updateLogo('PS_LOGO_LINK_','PS_LOGO_LINK_'.$language['id_lang'], $_POST['logo_name_'.$language[id_lang]],$language['language_code'],$_POST['logo_name_'.$language[id_lang]]);
				else
					$this->updateLogo('PS_LOGO_LINK_','PS_LOGO_LINK_'.$language['id_lang'], 'logo',$language['language_code'],'logo');
				}
				if(isset($_FILES['PS_LOGO_MOBILE_LINK_'.$language['id_lang']]))
				{
					if (isset($_POST['mobile_logo_name_'.$language[id_lang]]) && !empty($_POST['mobile_logo_name_'.$language[id_lang]]) )
						$this->updateLogo('PS_LOGO_MOBILE_LINK_','PS_LOGO_MOBILE_LINK_'.$language['id_lang'], $_POST['mobile_logo_name_'.$language[id_lang]],$language['language_code'],$_POST['mobile_logo_name_'.$language[id_lang]]);
					else
						$this->updateLogo('PS_LOGO_MOBILE_LINK_','PS_LOGO_MOBILE_LINK_'.$language['id_lang'], 'mobile_logo',$language['language_code'],'mobile_logo');
				}
				if(isset($_FILES['PS_FAVICON_LINK_'.$language['id_lang']]) && $_FILES['PS_FAVICON_LINK_'.$language['id_lang']]['tmp_name'])
				{

					if(Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || Shop::isFeatureActive() == false)
					{
						$this->uploadIco('PS_FAVICON_LINK_'.$language['id_lang'], _PS_IMG_DIR_.'favicon_'.$language['language_code'].'.ico');
						Configuration::updateValue('PS_FAVICON_LINK_'.$language['language_code'], 'favicon_'.$language['language_code'].'.ico');
					}
					else
					{
						$this->uploadIco('PS_FAVICON_LINK_'.$language['id_lang'], _PS_IMG_DIR_.'favicon_'.$language['language_code'].'-'.Context::getContext()->shop->id.'.ico');
						Configuration::updateValue('PS_FAVICON_LINK_'.$language['language_code'].'-'.Context::getContext()->shop->id, 'favicon_'.$language['language_code'].'-'.Context::getContext()->shop->id.'.ico');
					}
				}
				if(isset($_FILES['PS_LOGO_MAIL_LINK_'.$language['id_lang']]))
				{
					$this->updateLogo('PS_LOGO_MAIL_LINK_','PS_LOGO_MAIL_LINK_'.$language['id_lang'], 'logo_mail_'.$language['language_code'],$language['language_code'],'');
				}
				if(isset($_FILES['PS_LOGO_INVOICE_LINK_'.$language['id_lang']]))
				{
					$this->updateLogo('PS_LOGO_INVOICE_LINK_','PS_LOGO_INVOICE_LINK_'.$language['id_lang'], 'logo_invoice_'.$language['language_code'],$language['language_code'],'');
				}
			}
		}


    }

    protected function updateLogo($field,$field_name, $logo_prefix,$lang,$savedname)
    {
        $id_shop = Context::getContext()->shop->id;
        if (isset($_FILES[$field_name]['tmp_name']) && $_FILES[$field_name]['tmp_name'])
        {
            if ($error = ImageManager::validateUpload($_FILES[$field_name], 300000))
                $this->errors[] = $error;

            $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
            if (!$tmp_name || !move_uploaded_file($_FILES[$field_name]['tmp_name'], $tmp_name))
                return false;

            $ext = ($field_name == 'PS_STORES_ICON') ? '.gif' : '.jpg';
            $logo_name = $logo_prefix.$ext;
            if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || $id_shop == 0 || Shop::isFeatureActive() == false)
                $logo_name = $logo_prefix.$ext;

            if ($field_name == 'PS_STORES_ICON')
            {
                if (!@ImageManager::resize($tmp_name, _PS_IMG_DIR_.$logo_name, null, null, 'gif', true))
                    $this->errors[] = Tools::displayError('An error occurred while attempting to copy your logo.');
            }
            else
            {
                if (!@ImageManager::resize($tmp_name, _PS_IMG_DIR_.$logo_name))
                    $this->errors[] = Tools::displayError('An error occurred while attempting to copy your logo.');
            }
			if ($field == 'PS_LOGO_LINK_')
			{
				if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || Shop::isFeatureActive() == false)
				{
					Configuration::updateValue('PS_LOGO_LINK_'.$lang, $savedname);
					list($width, $height, $type, $attr) = getimagesize(_PS_IMG_DIR_.Configuration::get('PS_LOGO_LINK_'.$lang.'jpg'));
					Configuration::updateValue('SHOP_LOGO_LINK_H_'.$lang, (int)round($height));
					Configuration::updateValue('SHOP_LOGO_LINK_W_'.$lang, (int)round($width));
				}
				else
				{
					Configuration::updateValue('PS_LOGO_LINK_'.$lang.'-'.Context::getContext()->shop->id, $savedname);
					list($width, $height, $type, $attr) = getimagesize(_PS_IMG_DIR_.Configuration::get('PS_LOGO_LINK_'.$lang.'-'.Context::getContext()->shop->id.'.jpg'));
					Configuration::updateValue('SHOP_LOGO_LINK_H_'.$lang.'-'.Context::getContext()->shop->id, (int)round($height));
					Configuration::updateValue('SHOP_LOGO_LINK_W_'.$lang.'-'.Context::getContext()->shop->id, (int)round($width));
				}
			}
			if ($field == 'PS_LOGO_MOBILE_LINK_')
			{
				if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || Shop::isFeatureActive() == false)
				{
					Configuration::updateValue('PS_LOGO_MOBILE_LINK_'.$lang, $savedname);
					list($width, $height, $type, $attr) = getimagesize(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$lang.'.jpg'));
					Configuration::updateValue('SHOP_LOGO_MOBILE_LINK_H_'.$lang, (int)round($height));
					Configuration::updateValue('SHOP_LOGO_MOBILE_LINK_W_'.$lang, (int)round($width));
				}

				else
				{
					Configuration::updateValue('PS_LOGO_MOBILE_LINK_'.$lang.'-'.Context::getContext()->shop->id, $savedname);
					list($width, $height, $type, $attr) = getimagesize(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MOBILE_LINK_'.$lang.'-'.Context::getContext()->shop->id.'.jpg'));
					Configuration::updateValue('SHOP_LOGO_MOBILE_LINK_H_'.$lang.'-'.Context::getContext()->shop->id, (int)round($height));
					Configuration::updateValue('SHOP_LOGO_MOBILE_LINK_W_'.$lang.'-'.Context::getContext()->shop->id, (int)round($width));
				}
			}
			if ($field == 'PS_LOGO_MAIL_LINK_')
			{
				if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || Shop::isFeatureActive() == false)
					Configuration::updateValue('PS_LOGO_MAIL_LINK_'.$lang, $logo_name);
				else
					Configuration::updateValue('PS_LOGO_MAIL_LINK_'.$lang.'-'.Context::getContext()->shop->id, $logo_name);
			}
			if ($field == 'PS_LOGO_INVOICE_LINK_')
			{
				if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || Shop::isFeatureActive() == false)
					Configuration::updateValue('PS_LOGO_INVOICE_LINK_'.$lang, $logo_name);
				else
					Configuration::updateValue('PS_LOGO_INVOICE_LINK_'.$lang.'-'.Context::getContext()->shop->id, $logo_name);
			}

            unlink($tmp_name);
        }
    }

	protected function uploadIco($name, $dest)
	{
		if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
		{
			// Check ico validity
			if ($error = ImageManager::validateIconUpload($_FILES[$name]))
				$this->errors[] = $error;
			// Copy new ico
			elseif (!copy($_FILES[$name]['tmp_name'], $dest))
				$this->errors[] = sprintf(Tools::displayError('An error occurred while uploading favicon: %s to %s'), $_FILES[$name]['tmp_name'], $dest);
		}
		return !count($this->errors) ? true : false;
	}

}
