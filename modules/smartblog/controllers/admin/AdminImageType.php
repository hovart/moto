<?php
class AdminImageTypeController extends ModuleAdminController {

    public function __construct() {
        $this->table = 'smart_blog_imagetype';
        $this->className = 'BlogImageType';
        $this->module = 'smartblog';
        $this->lang = false;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->fields_list = array(
                            'id_smart_blog_imagetype' => array(
                                    'title' => $this->l('Id'),
                                    'width' => 100,
                                    'type' => 'text',
                            ),
                            'type_name' => array(
                                    'title' => $this->l('Type Name'),
                                    'width' => 350,
                                    'type' => 'text',
                            ),
                            'width' => array(
                                    'title' => $this->l('Width'),
                                    'width' => 60,
                                    'type' => 'text',
                            ),
                            'height' => array(
                                    'title' => $this->l('Height'),
                                    'width' => 60,
                                    'type' => 'text',
                            ),
                            'type' => array(
                                    'title' => $this->l('Type'),
                                    'width' => 220,
                                    'type' => 'text',
                            ),
                            'active' => array(
                                'title' => $this->l('Status'),
                                'width' => 60,
                                'align' => 'center',
                                'active' => 'status',
                                'type' => 'bool',
                                'orderby' => false
                            )
                    );
        parent::__construct();
    }

    public function renderForm()
            {
        $this->fields_form = array(
          'legend' => array(
          'title' => $this->l('Blog Category'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Image Type Name'),
                    'name' => 'type_name',
                    'class' => 'fixed-width-lg',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Width'),
                    'name' => 'width',
                    'class' => 'fixed-width-sm',
                    'required' => true,
                    'desc' => $this->l('in pixels')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Height'),
                    'name' => 'height',
                    'class' => 'fixed-width-sm',
                    'required' => true,
                    'desc' => $this->l('in pixels')
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Type'),
                    'name' => 'type',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                                    array(
                                    'id_option' => 'post',
                                    'name' => 'Post'
                                    ),
                                    array(
                                    'id_option' => 'Category',
                                    'name' => 'category'
                                    ),
                                    array(
                                    'id_option' => 'Author',
                                    'name' => 'author'
                                    )
                                ),
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'values' => array(
                        array(
                            'id' => 'active',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save')
            )
        );

        if (!($BlogImageType = $this->loadObject(true)))
            return;

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save')
        );
        return parent::renderForm();
    }
    public function renderList() {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();;
    }
    public function initToolbar() {
        parent::initToolbar();
    }
}
