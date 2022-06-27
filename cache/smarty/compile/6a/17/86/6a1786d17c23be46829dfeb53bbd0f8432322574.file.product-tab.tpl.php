<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:86431078598976a856b4a5-74884771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a1786d17c23be46829dfeb53bbd0f8432322574' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/product-tab.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86431078598976a856b4a5-74884771',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bDisplayReviews' => 0,
    'iCountRatings' => 0,
    'bUseRatings' => 0,
    'iIdTab' => 0,
    'sTabMode' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a8579107_75544296',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a8579107_75544296')) {function content_598976a8579107_75544296($_smarty_tpl) {?>
<?php if (!empty($_smarty_tpl->tpl_vars['bDisplayReviews']->value)&&(!empty($_smarty_tpl->tpl_vars['iCountRatings']->value)||!empty($_smarty_tpl->tpl_vars['bUseRatings']->value))) {?><!-- GSR - Product Review Tab title --><li><a id="more_info_tab_reviews" href="#idTab<?php echo intval($_smarty_tpl->tpl_vars['iIdTab']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['sTabMode']->value)&&$_smarty_tpl->tpl_vars['sTabMode']->value=='bootstrap') {?>data-toggle="tab"<?php }?> ><?php echo smartyTranslate(array('s'=>'Reviews','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 (<?php echo intval($_smarty_tpl->tpl_vars['iCountRatings']->value);?>
)</a></li><a name="anchorReview" id="anchorReview"></a><!-- /GSR - Product Review Tab title --><?php }?><?php }} ?>
