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

class AdminUkooCompatAliasController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_alias';
        $this->className = 'UkooCompatAlias';
        $this->lang = false;
        $this->_defaultOrderBy = 'alias';
        $this->position_identifier = 'id_ukoocompat_alias';
        $this->context = Context::getContext();
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->_select = 'ual.`description` AS description';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'ukoocompat_alias_lang` ual
				ON (a.`id_ukoocompat_alias` = ual.`id_ukoocompat_alias`
				    AND ual.`id_lang` = '.(int)$this->context->language->id.')';

        $this->fields_list = array(
            'id_ukoocompat_alias' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'alias' => array(
                'title' => $this->l('Alias'),
                'align' => 'left'),
            'image' => array(
                'title' => $this->l('Image'),
                'align' => 'center',
                'callback' => 'getPreview',
                'orderby' => false,
                'filter' => false,
                'search' => false),
            'description' => array(
                'title' => $this->l('Description'),
                'align' => 'left',
                'callback' => 'getDescriptionClean'));
    }

    /**
    * Retourne la miniature dans la liste des alias en BO
    * @param $thumbnail_extension
    * @param $image
    * @return string
    */
    public function getPreview($image)
    {
        if (!empty($image)) {
            return '<img class="img-thumbnail" src="./../modules/ukoocompat/img/'.
            $image.'?rand='.(int)rand(0, 9999).'" alt="preview">';
        } else {
            return '';
        }
    }

    /**
     * Retourne la description nettoyée de tout HTML pour un meilleur rendu
     * @param $description
     * @return mixed
     */
    public static function getDescriptionClean($description)
    {
        return Tools::getDescriptionClean($description);
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        // Bouton nouvel alias
        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new_ukoocompat_alias'] = array(
                'href' => self::$currentIndex.'&addukoocompat_alias&token='.$this->token,
                'desc' => $this->l('Add new alias', null, null, false),
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
     * Affiche le formulaire de création ou d'édition d'un alias
     */
    public function renderForm()
    {
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $input = array(
			array(
				'type' => 'text',
				'label' => $this->l('Alias'),
				'name' => 'alias',
				'lang' => false,
				'required' => true,
				'hint' => $this->l('Alias used to quick search some compatibilities')),
			array(
				'type' => 'textarea',
				'label' => $this->l('Description'),
				'name' => 'description',
				'autoload_rte' => true,
				'lang' => true),
			array(
				'type' => 'text',
				'label' => $this->l('Link'),
				'name' => 'link',
				'lang' => false,
				'required' => false,
				'hint' => $this->l('Will be added to a "See more" button'))
		);

		// Si c'est une édition et que les fichiers existent sur le FTP, alors on les affiche
		if ($this->display == 'edit' && file_exists('./../modules/ukoocompat/img/'.$obj->image)) {
			$input = array_merge($input, array(
                array(
                    'type' => 'html',
                    'label' => $this->l('Image preview'),
                    'name' => 'image_preview',
                    'html_content' => '<img src="./../modules/ukoocompat/img/'.
                        $obj->image.'?'.rand(0, 999999).'" height="120" />'
                )
            ));
        }

        $input = array_merge($input, array(
			array(
				'type' => 'file',
				'label' => $this->l('Image'),
				'name' => 'image',
				'lang' => false,
				'required' => false
			)
		));

		if ($this->display == 'edit' && file_exists('./../modules/ukoocompat/pdf/notice_'.(int)$obj->id.'.pdf'))
			$input = array_merge($input, array(
				array(
					'type' => 'html',
					'label' => $this->l('Download documentation'),
					'name' => 'link_to_file',
					'html_content' =>
					    '<a target="_blank" href="./../modules/ukoocompat/pdf/notice_'.(int)$obj->id.'.pdf?'.
					    rand(0, 999999).'" class="btn btn-default"><i class="icon-download"></i> '.
					    $this->l('Download file').'</a>'
				)
			));

		$input = array_merge($input, array(
			array(
				'type' => 'file',
				'label' => $this->l('Notice'),
				'name' => 'file',
				'lang' => false,
				'required' => false
			)
		));

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Alias'),
                'icon' => 'icon-share'),
            'input' => $input,
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

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'un nouvel alias
     * @return mixed
     */
    public function processAdd()
    {
        $object = parent::processAdd();

        // Rajout de l'utilisation d'un bouton "Enregistrer et nouveau"
        if (Tools::isSubmit('submitAdd'.$this->table.'AndNew') && !count($this->errors)) {
            $this->redirect_after = self::$currentIndex.'&add'.$this->table.'&conf=3&token='.$this->token;
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

    /**
	 * Fonction exécutée lors de l'enregistrement d'un alias, maj de l'image et du fichier
	 * @return mixed
	 */
	public function processSave()
	{
        $object = parent::processSave();
		$filename_image = 'img/'.$_FILES['image']['name'];
		$filename_pdf = 'pdf/notice_'.(int)$object->id.'.pdf';

		if (!count($this->errors) && isset($_FILES['image']) && empty($_FILES['image']['error']))
			$this->uploadFile($_FILES['image'], $filename_image, $object);

		if (!count($this->errors) && isset($_FILES['file']) && empty($_FILES['file']['error']))
			$this->uploadFile($_FILES['file'], $filename_pdf, $object);

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        $this->errors = array_unique($this->errors);
		if (!empty($this->errors))
			return false;

		return $object;
	}

    /**
     * Fonction exécutée lors de la suppression d'un alias
     * @return mixed
     */
    public function processDelete()
    {
        $object = parent::processDelete();

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $object;
    }

	/**
	 * Upload un fichier dans un répertoire du module
	 * @param $file
	 * @param $filename
	 * @param $object
	 * @return bool
	 */
	public function uploadFile($file, $filename, $object)
	{
		if (isset($file))
		{
			if (!empty($file['error']))
				switch ($file['error'])
				{
					case UPLOAD_ERR_INI_SIZE:
						$this->errors[] = Tools::displayError('The uploaded file exceeds the upload_max_filesize directive in php.ini.
							If your server configuration allows it, you may add a directive in your .htaccess.');
						return false;
					case UPLOAD_ERR_FORM_SIZE:
						$this->errors[] = Tools::displayError('The uploaded file exceeds the post_max_size directive in php.ini.
							If your server configuration allows it, you may add a directive in your .htaccess, for example:')
							.'<br/><a href="'.$this->context->link->getAdminLink('AdminMeta').'" > <code>php_value post_max_size 20M</code> '.
							Tools::displayError('(click to open "Generators" page)').'</a>';
						return false;
					case UPLOAD_ERR_PARTIAL:
						$this->errors[] = Tools::displayError('The uploaded file was only partially uploaded.');
						return false;
					case UPLOAD_ERR_NO_FILE:
						$this->errors[] = Tools::displayError('No file was uploaded.');
						return false;
				}
			elseif (!preg_match('/.*\.jpg$/i', $file['name']) && !preg_match('/.*\.pdf$/i', $file['name']))
				$this->errors[] = Tools::displayError('The extension of your file should be .jpg or .pdf.');
			elseif (!filemtime($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], '../modules/ukoocompat/'.$filename))
				$this->errors[] = $this->l('An error occurred while uploading / copying the file.');
			else
			{

                // si c'est l'image qui est envoyée, il faut la retraiter (redim etc)
                if (preg_match('/.*\.jpg$/i', $file['name']))
                {
                    if (!ImageManager::resize(_PS_MODULE_DIR_.'ukoocompat/'.$filename, _PS_MODULE_DIR_.'ukoocompat/'.$filename, 120, 120))
                        $this->errors[] = $this->l('An error occurred while resize image.');
                    $object->image = str_replace('img/', '', $filename);
                    $object->save();
                }

                chmod('../modules/ukoocompat/'.$filename, 0664);

				return true;
			}
			return false;
		}
	}


    /**
     * Fonction permettant de récupérer la liste des alias correspondant à la recherche
     */
    public function displayAjaxSearchAlias()
    {
        // On récupère l'alias questionné
        if (Tools::isSubmit('q')) {
            $alias = Tools::getValue('q');

            // On cherche dans notre base d'alias s'il est enregistré
            $alias_results = UkooCompatAlias::searchAlias($alias);

            foreach ($alias_results as $item) {
                echo trim($item['alias']).'|'.(int)($item['id_ukoocompat_alias'])."\n";
            }
        } else {
            die('{"hasError" : true, errors : "Invalid vars!"}');
        }
    }
}
