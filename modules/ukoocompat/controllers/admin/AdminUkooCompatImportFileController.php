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

class AdminUkooCompatImportFileController extends ModuleAdminController
{
    /**
     * TODO :: analyse du fichier avant import ?
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'ukoocompat_import_file';
        $this->identifier = 'id_ukoocompat_import_file';
        $this->className = 'UkooCompatImportFile';
        $this->lang = false;
        $this->_orderBy = 'id_ukoocompat_import_file';
        $this->_orderWay = 'DESC';
        $this->context = Context::getContext();
        parent::__construct();

//        $this->addRowAction('view');
//        $this->addRowAction('analyze');
//        $this->addRowActionSkipList('view', UkooCompatImportFile::getImportIdsFromStatus(0));
        $this->addRowAction('import');
        $this->addRowActionSkipList('import', UkooCompatImportFile::getImportIdsFromStatus(0));
        $this->addRowAction('download');
        $this->addRowActionSkipList('download', UkooCompatImportFile::getImportIdsFromStatus(0));
        $this->addRowAction('edit');
        $this->addRowAction('delete');
//        $this->list_no_link = true; // empêche le clic sur les lignes de la vue liste (pas d'édition)
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));

        $this->_select = 'a.`id_ukoocompat_import_file` AS id_file';

        $this->fields_list = array(
            'id_ukoocompat_import_file' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'),
            'filename' => array(
                'title' => $this->l('File'),
                'align' => 'left',
                'callback' => 'getListFilename'),
            'id_file' => array(
                'title' => $this->l('File size'),
                'align' => 'left',
                'callback' => 'getListFileSize'),
            'link_to_product' => array(
                'title' => $this->l('Association method'),
                'align' => 'left'),
            'col_separator' => array(
                'title' => $this->l('Data separator'),
                'align' => 'left'),
            'create_filters' => array(
                'title' => $this->l('Create filters'),
                'active' => 'create_filters',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false),
            'create_criteria' => array(
                'title' => $this->l('Create criteria'),
                'active' => 'create_criteria',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false),
            'create_alias' => array(
                'title' => $this->l('Create alias'),
                'active' => 'create_alias',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'orderby' => false),
            'date_import' => array(
                'title' => $this->l('Last import'),
                'align' => 'center'),
            'status' => array(
                'title' => $this->l('Status'),
                'align' => 'left',
                'callback' => 'getListStatus'));
    }

    /**
     * Récupère le nom original du fichier pour la vue liste
     * @param $filename
     * @return string
     */
    public function getListFilename($filename)
    {
        if (!empty($filename)) {
            return $filename;
        } else {
            return '<em>'.$this->l('File not found').'<em>';
        }
    }

    /**
     * Récupère le poids d'un fichier pour la vue liste
     * TODO :: permettre de configurer le dossier d'import dans la config du module ?
     * @param $id_file
     * @return string
     */
    public function getListFileSize($id_file)
    {
        $path = './import/ukoocompat_import_'.(int)$id_file.'.csv';
        if (!empty($id_file) && file_exists($path)) {
            $size = filesize($path);
            if ($size > 1048576) {
                return round($size / 1048576, 2).' '.$this->l('Mb');
            } elseif ($size > 1024) {
                return round($size / 1024, 2).' '.$this->l('Kb');
            } else {
                return $size.' '.$this->l('bytes');
            }
        } else {
            return '-';
        }
    }

    /**
     * Récupère les label de statuts pour la vue liste
     * TODO :: améliorer leur utilisation
     * @param $status
     * @return string
     */
    public function getListStatus($status)
    {
        if (isset($this->module->import_status[(int)$status])) {
            return '<span class="label color_field" style="background-color:'.
            $this->module->import_status[(int)$status]['label_color'].'; color:'.
            $this->module->import_status[(int)$status]['text_color'].';">'.
            $this->module->import_status[(int)$status]['name'].'</span>';
        } else {
            return 'unknow';
        }
    }

    /**
     * Défini une nouvelle option "import" dans la liste
     * @param null $token
     * @param $id
     * @return mixed
     */
    public function displayImportLink($token, $id)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_import.tpl');
        if (!array_key_exists('Import', self::$cache_lang)) {
            self::$cache_lang['Import'] = $this->l('Import', 'Helper');
        }

        $tpl->assign(
            array(
                'href' => self::$currentIndex.'&importukoocompat_import_file&id_ukoocompat_import_file='.
                    (int)$id.'&token='.($token != null ? $token : $this->token),
                'action' => self::$cache_lang['Import'], 'id' => (int)$id)
        );
        return $tpl->fetch();
    }

    /**
     * Défini une nouvelle option "télécharger" dans la liste
     * @param null $token
     * @param $id
     * @return mixed
     */
    public function displayDownloadLink($token, $id)
    {
        $token = Tools::getAdminTokenLite('AdminImport');
        $tpl = $this->createTemplate('helpers/list/list_action_download.tpl');
        if (!array_key_exists('Download', self::$cache_lang)) {
            self::$cache_lang['Download'] = $this->l('Download', 'Helper');
        }

        $tpl->assign(
            array(
                'href' => 'index.php?controller=AdminImport&token='.$token.
                    '&csvfilename=ukoocompat_import_'.(int)$id.'.csv',
                'action' => self::$cache_lang['Download'],
                'id' => (int)$id, 'target' => '_blank')
        );
        return $tpl->fetch();
    }

    /**
     * Rendu personnalisé du formulaire d'upload d'un fichier CSV d'import
     * @return mixed
     */
    public function renderForm()
    {
        $this->initToolbar();

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $input = array(
            array(
                'type' => 'file',
                'label' => $this->l('File'),
                'name' => 'file',
                'lang' => false,
                'required' => ($this->display == 'add' ? true : false),
                'hint' => $this->l('Select your CSV file to upload'),
                'desc' => $this->l('Download our samples files for:').
                    ' <a download="import_sample_cars.csv" href="../modules/ukoocompat/doc/import_sample_cars.csv">'.
                    $this->l('cars').'</a>,'.
                    ' <a download="import_sample_operatingsystems.csv" '.
                    'href="../modules/ukoocompat/doc/import_sample_operatingsystems.csv">'.
                    $this->l('operating systems').'</a> '.$this->l('and').
                    ' <a download="import_sample_electrohousehold.csv" '.
                    'href="../modules/ukoocompat/doc/import_sample_electrohousehold.csv">'.
                    $this->l('electro household').'</a>.'));

        // Si c'est une édition et que le fichier existe sur le FTP, alors on affiche un lien pour le télécharger
        if ($this->display == 'edit' && file_exists('./import/ukoocompat_import_'.(int)$obj->id.'.csv')) {
            $input = array_merge($input, array(
                array(
                    'type' => 'html',
                    'label' => $this->l('Link to the file'),
                    'name' => 'link_to_file',
                    'html_content' => '<a target="_blank" href="index.php?controller=AdminImport&token='.
                        Tools::getAdminTokenLite('AdminImport').
                        '&csvfilename=ukoocompat_import_'.(int)$obj->id.
                        '.csv" class="btn btn-default"><i class="icon-download"></i> '.
                        $this->l('Download file').'</a>')));
        }

        $input = array_merge($input, array(
            array(
                'type' => 'select',
                'label' => $this->l('Link to product by'),
                'hint' => $this->l('Select the method by which you want to associate products'),
                'name' => 'link_to_product',
                'required' => true,
                'options' => array(
                    'query' => $this->module->link_to_product,
                    'id' => 'id',
                    'name' => 'name'),
                'col' => '2'),
            array(
                'type' => 'switch',
                'label' => $this->l('Create new filters'),
                'hint' => $this->l('If set to "yes", found new filters will be created during the import, else the compatibility will be skiped.'),
                'name' => 'create_filters',
                'required' => false,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'create_filters_on',
                        'value' => 1,
                        'label' => $this->l('Yes')),
                    array(
                        'id' => 'create_filters_off',
                        'value' => 0,
                        'label' => $this->l('No')))),
            array(
                'type' => 'switch',
                'label' => $this->l('Create new criteria'),
                'hint' => $this->l('If set to "yes", found new criteria will be created during the import, else the compatibility will be skiped.'),
                'name' => 'create_criteria',
                'required' => false,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'create_criteria_on',
                        'value' => 1,
                        'label' => $this->l('Yes')),
                    array(
                        'id' => 'create_criteria_off',
                        'value' => 0,
                        'label' => $this->l('No')))),
            array(
                'type' => 'switch',
                'label' => $this->l('Create and associate new alias'),
                'hint' => $this->l('If set to "yes", found new alias will be created during the import, else the alias association will be skiped.'),
                'desc' => $this->l('Your file should contain an additional column in second place for the alias. eg.').' 8;Alias;Apple;Mac OS X;2002',
                'name' => 'create_alias',
                'required' => false,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'create_alias_on',
                        'value' => 1,
                        'label' => $this->l('Yes')),
                    array(
                        'id' => 'create_alias_off',
                        'value' => 0,
                        'label' => $this->l('No')))),
            array(
                'type' => 'text',
                'label' => $this->l('Data separator'),
                'name' => 'col_separator',
                'lang' => false,
                'required' => false,
                'class' => 'fixed-width-xs',
                'hint' => $this->l('Separator used between data fields in CSV file'),
                'desc' => $this->l('eg.').' 8;Apple;Mac OS X;2002'),
            array(
                'type' => 'hidden',
                'name' => 'status',
                'lang' => false,
                'required' => true),
            array('type' => 'hidden',
                'name' => 'filename',
                'lang' => false,
                'required' => true)));

        $this->fields_form = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Import file'),
                'icon' => 'icon-upload'),
            'input' => $input,
            'submit' => array(
                'title' => $this->l('Save'),
                'icon' => 'process-icon-save',
                'name' => 'submitAdd'.$this->table.'AndBackToParent'));

        $helper = new HelperForm();
        $helper->show_cancel_button = true;

        $back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex.'&token='.$this->token;
        }
        if (!Validate::isCleanHtml($back)) {
            die(Tools::displayError());
        }

        // Valeurs par défaut
        $this->fields_value = array(
            'col_separator' => (!isset($obj->separator) || empty($obj->separator) ? ';' : $obj->separator),
            'create_filters' => (!isset($obj->create_filters) ? 1 : $obj->create_filters),
            'create_criteria' => (!isset($obj->create_criteria) ? 1 : $obj->create_criteria),
            'create_alias' => (!isset($obj->create_alias) ? 0 : $obj->create_alias),
            'status' => (!isset($obj->status) || empty($obj->status) ? 0 : $obj->status),
            'filename' => (!isset($obj->filename) || empty($obj->filename) ? '' : $obj->filename));

        return parent::renderForm();
    }

    /**
     * Fonction exécutée lors de la création d'une entrée (upload du fichier)
     * @return bool
     */
    public function processSave()
    {
        $object = parent::processSave();
        $filename = 'ukoocompat_import_'.(int)$object->id.'.csv';

        if (!count($this->errors) && isset($_FILES['file']) && empty($_FILES['file']['error'])) {
            $this->uploadFile($_FILES['file'], $filename, $object);
        }

        $this->errors = array_unique($this->errors);
        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * Upload le fichier CSV dans le répertoire /import du module
     * @param $file
     * @param $filename
     * @param $object
     * @return bool
     */
    public function uploadFile($file, $filename, $object)
    {
        if (isset($file)) {
            if (!empty($file['error'])) {
                switch ($file['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $this->errors[] =
                            Tools::displayError('The uploaded file exceeds the upload_max_filesize directive in php.ini.
							If your server configuration allows it, you may add a directive in your .htaccess.');
                        return false;
                    case UPLOAD_ERR_FORM_SIZE:
                        $this->errors[] =
                            Tools::displayError('The uploaded file exceeds the post_max_size directive in php.ini.
							If your server configuration allows it, you may add a directive in your .htaccess, for example:').
                            '<br/><a href="'.$this->context->link->getAdminLink('AdminMeta').'" > '.
                            '<code>php_value post_max_size 20M</code> '.
                            Tools::displayError('(click to open "Generators" page)').'</a>';
                        return false;
                    case UPLOAD_ERR_PARTIAL:
                        $this->errors[] = Tools::displayError('The uploaded file was only partially uploaded.');
                        return false;
                    case UPLOAD_ERR_NO_FILE:
                        $this->errors[] = Tools::displayError('No file was uploaded.');
                        return false;
                }
            } elseif (!preg_match('/.*\.csv$/i', $file['name'])) {
                $this->errors[] = Tools::displayError('The extension of your file should be .csv.');
            } elseif (!filemtime($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], './import/'.$filename)) {
                $this->errors[] = $this->l('An error occurred while uploading / copying the file.');
            } else {
                chmod('./import/'.$filename, 0664);
                $object->filename = $file['name'];
                $object->status = 1;
                if (!$object->update()) {
                    $this->errors[] = Tools::displayError('Unable to update import entry!');
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Override de la methode iniProcess() pour la gestion de l'import
     */
    public function initProcess()
    {
        $this->id_object = (int)Tools::getValue($this->identifier);
        if (Tools::getIsset('import'.$this->table)) {
            $this->action = 'import';
            $this->display = 'list';
        } else {
            parent::initProcess();
        }
    }

    /**
     * Fonction exécutée lors de l'import d'un fichier CSV
     * @return bool|string
     */
    public function processImport()
    {
        if (!isset($this->className) || empty($this->className)) {
            return false;
        }

        if (count($this->errors) <= 0 && Tools::isSubmit($this->identifier)) {
            $id_import = (int)Tools::getValue($this->identifier);
            if (UkooCompatImportFile::existsInDatabase($id_import, 'ukoocompat_import_file')) {
                $this->object = new $this->className($id_import);

                // Ouverture du fichier
                $file = './import/ukoocompat_import_'.(int)$this->object->id.'.csv';
                $handle = false;
                if (is_file($file) && is_readable($file)) {
                    $handle = fopen($file, 'r');
                }
                if (!$handle) {
                    return $this->errors[] = Tools::displayError('Cannot read the .CSV file');
                } else {
                    $mask_row = array();
                    $skiped_col = array();
                    $nb_col = 0;
                    // le fichier est lisible, on tente d'en importer les lignes
                    for ($current_row = 0; $row = fgetcsv($handle, 0, $this->object->col_separator); $current_row++) {
                        // traitement différent pour la première ligne (récupération des filtres utilisés)
                        if ($current_row == 0) {
                            // pour chaque colonne, on défini le masque à appliquer
                            foreach ($row as $k => $value) {
                                // la première colonne définie la clé du produit
                                // (id, référence, référence fournisseur, ean13...)
                                if ($k == 0) {
                                    $mask_row[$k] = $this->object->link_to_product;
                                } elseif ($k == 1 && (int)$this->object->create_alias == 1) {
                                    // la deuxième colonne peut contenir un alias (ou pas ^^)
                                    $this->confirmations[] = $this->l('A column containing aliases has been found.').
                                        ' ('.$this->l('column').' #'.($k + 1).')<br />';
                                    $skiped_col[] = $k;
                                } else {
                                    // on test si le filtre existe déja
                                    if ($id_filter = (int)UkooCompatFilter::getFilterIdFromName(
                                        $value,
                                        (int)$this->context->language->id
                                    )
                                    ) {
                                        $mask_row[$k] = $id_filter;
                                    } else {
                                        // on tente de créer le filtre associé si demandé dans la config de l'import
                                        if ((int)$this->object->create_filters) {
                                            $filter_to_create = new UkooCompatFilter();
                                            $filter_to_create->name = AdminUkooCompatImportFileController::createMultiLangField($value);
                                            $filter_to_create->position =
                                                (int)UkooCompatFilter::getNextFilterPosition();
                                            if (!$filter_to_create->add()) {
                                                return $this->errors[] = $this->l('Error during filter creation:').' "'.$value.'"<br />';
                                            } else {
                                                $this->confirmations[] = $this->l('The filter').' "'.$value.'" '.$this->l('was created.').'<br />';
                                            }
                                            $mask_row[$k] = (int)UkooCompatFilter::getFilterIdFromName($value, (int)$this->context->language->id);
                                        } else {
                                            // le filtre n'existe pas, et on ne souhaite pas le créer
                                            $this->confirmations[] =
                                                $this->l('You dont want to create the filter').' "'.
                                                $value.'" '.$this->l('. This column will be skiped.').'<br />';
                                            $skiped_col[] = $k;
                                        }
                                    }
                                }
                            }
                            $nb_col = count($mask_row) + count($skiped_col);
                        } else {
                            // traitement des autres lignes
                            // on test si la ligne à au moins autant de colonnes que la ligne d'en-tête
                            if (count($row) <= $nb_col) {
                                $compatibility = array();
                                // pour chaque colonne
                                foreach ($row as $k => $value) {
                                    // la première colonne contient le lien avec le produit (id, ref, ean13...)
                                    if ($k == 0) {
                                        // TODO :: stocker les correspondances des ID produits pour éviter d'effectuer
                                        // plusieurs fois la même requête en BDD
                                        switch ($this->object->link_to_product) {
                                            case 'id_product':
                                                // l'ID du produit est donné dans le fichier d'import, on test juste si le produit existe dans la boutique
                                                $id_product = (int)$value;
                                                if (!Product::existsInDatabase($id_product, 'product')) {
                                                    $this->errors[] = $this->l('Product doesnt exists in database').' '.$this->l('ID').' '.$id_product.'<br />';
                                                    $id_product = 0;
                                                }
                                                break;
                                            case 'reference':
                                                // la référence du produit est donné dans le fichier d'import, on récupère le produit concerné
                                                // ATTENTION ! plusieurs produits peuvent correspondre, mais on n'en récupère qu'un seul !
                                                if (!$id_product = (int)UkooCompatCompat::getIdProductFromReference($value, 'reference')) {
                                                    $this->errors[] = $this->l('Product doesnt exists in database').' '.$this->l('Reference').' '.$value.'<br />';
                                                }
                                                break;
                                            case 'supplier_reference':
                                                // la référence fournisseur du produit est donné dans le fichier d'import, on récupère le produit concerné
                                                // ATTENTION ! plusieurs produits peuvent correspondre, mais on n'en récupère qu'un seul !
                                                if (!$id_product = (int)UkooCompatCompat::getIdProductFromReference($value, 'supplier_reference')) {
                                                    $this->errors[] = $this->l('Product doesnt exists in database').' '.$this->l('Supplier reference').' '.$value.'<br />';
                                                }
                                                break;
                                            case 'ean13':
                                                // l'EAN13 du produit est donné dans le fichier d'import,
                                                // on récupère le produit concerné
                                                // ATTENTION ! plusieurs produits peuvent correspondre, mais on n'en récupère qu'un seul !
                                                if (!$id_product = (int)UkooCompatCompat::getIdProductFromReference($value, 'ean13')) {
                                                    $this->errors[] = $this->l('Product doesnt exists in database').' '.$this->l('EAN13').' '.$value.'<br />';
                                                }
                                                break;
                                            case 'upc':
                                                // l'UPC du produit est donné dans le fichier d'import, on récupère le produit concerné
                                                // ATTENTION ! plusieurs produits peuvent correspondre, mais on n'en récupère qu'un seul !
                                                if (!$id_product = (int)UkooCompatCompat::getIdProductFromReference($value, 'upc')) {
                                                    $this->errors[] = $this->l('Product doesnt exists in database').' '.$this->l('UPC').' '.$value.'<br />';
                                                }
                                                break;
                                            default:
                                                return $this->errors[] = $this->l('Unable to establish link to products').'<br />';
                                        }
                                        $compatibility['id_product'] = $id_product;
                                    } elseif ($k == 1 && (int)$this->object->create_alias == 1) {
                                        // la deuxième colonne peut contenir un alias (ou pas ^^)
                                        // si le champ est vide, aucun alias ou instance d'alias ne sera créé
                                        if (str_replace(' ', '', $value) != '') {
                                            // on test si l'alias existe déjà en BDD
                                            if (!$id_alias = (int)UkooCompatAlias::getAliasIdFromAlias($value)) {
                                                // on tente de le créer
                                                $alias_to_create = new UkooCompatAlias();
                                                $alias_to_create->alias = $value;
                                                if (!$alias_to_create->add()) {
                                                    return $this->errors[] = $this->l('Error during alias creation:').' "'.$value.'"<br />';
                                                } else {
                                                    $this->confirmations[] = $this->l('The alias').' "'.$value.'" '.$this->l('was created.').'<br />';
                                                }
                                                $id_alias = (int)UkooCompatAlias::getAliasIdFromAlias($value);
                                            }
                                            // on stocke l'id dans la variable de compat pour la création ultérieur
                                            if ($id_alias !== '' && $id_alias != 0) {
                                                $compatibility['id_alias'] = $id_alias;
                                            } else {
                                                $this->errors[] = $this->l('Empty alias: l.').' '.($current_row + 1).'<br />';
                                            }
                                        } else {
                                            // traitement si valeur vide
                                            $this->errors[] = $this->l('Empty alias: l.').' '.($current_row + 1).'<br />';
                                        }
                                    } elseif (!in_array($k, $skiped_col)) {
                                        // on saute les colonnes dont le filtre n'existe pas
                                        // si le critère est vide, aucune association avec ce filtre ne sera créé
                                        if (str_replace(' ', '', $value) != '') {
                                            // si le critère est différent de "*" Tou(te)s, on recherche son ID ou on essaye de le créer
                                            if ($value != '*') {
                                                // on test si le critère existe déjà pour le filtre associé
                                                // $mask_row[$k] contient l'id du filtre pour cette colonne
                                                if (!$id_criterion = (int)UkooCompatCriterion::getCriterionIdFromName($value, (int)$mask_row[$k], (int)$this->context->language->id)) {
                                                    // on tente de créer le critère, si demandé dans la config de l'import
                                                    if ((int)$this->object->create_criteria) {
                                                        $criterion_to_create = new UkooCompatCriterion();
                                                        $criterion_to_create->id_ukoocompat_filter = (int)$mask_row[$k];
                                                        $criterion_to_create->value = AdminUkooCompatImportFileController::createMultiLangField($value);
                                                        $criterion_to_create->position = (int)UkooCompatCriterion::getNextCriterionPosition();
                                                        if (!$criterion_to_create->add()) {
                                                            return $this->errors[] = $this->l('Error during criterion creation:').' "'.$value.'"<br />';
                                                        } else {
                                                            $this->confirmations[] = $this->l('The criterion').' "'.$value.'" '.$this->l('was created.').'<br />';
                                                        }
                                                        $id_criterion = (int)UkooCompatCriterion::getCriterionIdFromName($value, (int)$mask_row[$k], (int)$this->context->language->id);
                                                    } else {
                                                        // le critère n'existe pas, et on ne souhaite pas le créer
                                                        $id_criterion = '';
                                                    }
                                                }
                                            } else {
                                                // traitement si le critère est associé à "*" Tou(te)s
                                                $id_criterion = 0;
                                            }

                                            // on stock les IDs dans la variable $compatibility pour la création ultérieure
                                            // uniquement si le critère existe (0 peut être accepté, et correspond à une compatibilité universelle)
                                            if ($id_criterion !== '') {
                                                $compatibility[$mask_row[$k]] = $id_criterion;
                                            } else {
                                                $this->errors[] = $this->l('You dont want to create the criterion:').' "'.$value.'"'.$this->l('. Compatibility will be create without.').'<br />';
                                            }
                                        } else {
                                            // traitement si valeur vide
                                            $this->errors[] = $this->l('Empty criterion: l.').' '.($current_row + 1).'<br />';
                                        }
                                    }
                                }

                                // Si le lien avec le produit n'est pas bon, on "saute" la compatibilité
                                if ((int)$compatibility['id_product'] == 0) {
                                    $this->errors[] = $this->l('Unable to establish a link to a product (compatibilty skiped): l.').' '.($current_row + 1).'<br />';
                                } elseif (count($compatibility) <= 1) {
                                    // Si la compatibilité n'a pas de critères associés, on la "saute" aussi
                                    $this->errors[] = $this->l('No criteria associated to the compatibility: l.').' '.($current_row + 1).'<br />';
                                } elseif (UkooCompatCompat::compatibilityExists($compatibility)) {
                                    // Si la compatibilité existe déjà, on la "saute" aussi
                                    $this->errors[] = $this->l('Compatibility already exists: l.').' '.($current_row + 1).'<br />';
                                } else {
                                    // On tente de créer la compatibilité
                                    $compatibility_to_create = new UkooCompatCompat();
                                    $compatibility_to_create->id_product = (int)$compatibility['id_product'];
                                    if (!$compatibility_to_create->add()) {
                                        $this->errors[] = $this->l('Error during compatibility creation: l.').' '.($current_row + 1).'<br />';
                                    } else {
                                        $error = false;
                                        // on tente de créer les associations avec les critères
                                        foreach ($compatibility as $id_filter => $id_criterion) {
                                            if ($id_filter != 'id_product' && $id_filter != 'id_alias') {
                                                if (!$compatibility_to_create->addAssociatedCriterion(
                                                    (int)$id_filter,
                                                    (int)$id_criterion
                                                )
                                                ) {
                                                    $error = true;
                                                    $this->errors[] = $this->l('Unabled to associate criterion to compatibility: l.').' '.($current_row + 1).'<br />';
                                                }
                                            }
                                        }
                                        if (!$error) {
                                            $this->confirmations[] = $this->l('Compatibility successfully created: l.').' '.($current_row + 1).'<br />';
                                        }
                                    }
                                }

                                // Si la compatibilité n'a pas d'ID d'alias, on ne créé pas d'instance
                                if (!isset($compatibility['id_alias'])) {
//									$this->errors[] = $this->l('Unable to establish a link to a product (compatibilty skiped): l.').' '.($current_row + 1).'<br />';
                                } elseif (count($compatibility) <= 2) {
                                    $this->errors[] = $this->l('No criteria associated to the alias: l.').' '.($current_row + 1).'<br />';
                                } elseif (UkooCompatAliasInstance::aliasInstanceExists($compatibility)) {
                                    // Si l'instance d'alias existe déjà, on la "saute" aussi
                                    $this->errors[] = $this->l('Compatibility already exists: l.').' '.($current_row + 1).'<br />';
                                } else {
                                    // On tente de créer l'instance d'alias
                                    $alias_instance_to_create = new UkooCompatAliasInstance();
                                    $alias_instance_to_create->id_ukoocompat_alias = (int)$compatibility['id_alias'];
                                    if (!$alias_instance_to_create->add()) {
                                        $this->errors[] = $this->l('Error during alias instance creation: l.').' '.($current_row + 1).'<br />';
                                    } else {
                                        $error = false;
                                        // on tente de créer les associations avec les critères
                                        foreach ($compatibility as $id_filter => $id_criterion) {
                                            if ($id_filter != 'id_product' && $id_filter != 'id_alias') {
                                                if (!$alias_instance_to_create->addAssociatedCriterion((int)$id_filter, (int)$id_criterion)) {
                                                    $error = true;
                                                    $this->errors[] = $this->l('Unabled to associate criterion to alias instance: l.').' '.($current_row + 1).'<br />';
                                                }
                                            }
                                        }
                                        if (!$error) {
                                            $this->confirmations[] = $this->l('Alias instance successfully created: l.').' '.($current_row + 1).'<br />';
                                        }
                                    }
                                }
                            } else {
                                $this->errors[] = $this->l('The line does not contain the required number of columns (line skiped): l.').' '.($current_row + 1).'<br />';
                            }
                        }
                    }

                    // Import terminé sans "fatal error" on met à jour la dernière date d'import en BDD
                    $this->object->date_import = date('Y-m-d H:i:s');
                    if (!$this->object->update()) {
                        return $this->errors[] = Tools::displayError('Unable to update last import date');
                    }
                }
            } else {
                return $this->errors[] = Tools::displayError('Import doesnt exists');
            }
        } else {
            return $this->errors[] = Tools::displayError('Invalid import ID');
        }

        $this->errors = array_unique($this->errors);
        if (!empty($this->errors)) {
            $this->display = 'list';
            return false;
        }

        // Suppression du cache du module
        UkooCompat::clearUkooCompatCache();

        return $this->object;
    }

    /**
     * Ajoute des boutons spécifiques dans la barre d'outils
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        // Bouton nouveau dans la toolbar
        if ($this->display != 'edit' && $this->display != 'add' && $this->display != 'view') {
            $this->page_header_toolbar_btn['new_ukoocompat_import_file'] = array(
                'href' => self::$currentIndex.'&addukoocompat_import_file&token='.$this->token,
                'desc' => $this->l('New CSV file'),
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
     * Utilisé pour créer des tableaux de langue à partir d'une même valeur, lors de l'import par exemple
     * @param $field
     * @return array
     */
    protected static function createMultiLangField($field)
    {
        $languages = Language::getLanguages(false);
        $res = array();
        foreach ($languages as $lang) {
            $res[$lang['id_lang']] = $field;
        }
        return $res;
    }
}
