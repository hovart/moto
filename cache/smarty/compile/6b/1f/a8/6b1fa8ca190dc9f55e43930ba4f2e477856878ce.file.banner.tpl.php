<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/tmmegamenu/views/templates/hook/items/banner.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1533003290598976a78afa41-13380162%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b1fa8ca190dc9f55e43930ba4f2e477856878ce' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/tmmegamenu/views/templates/hook/items/banner.tpl',
      1 => 1454627212,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1533003290598976a78afa41-13380162',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'banner' => 0,
    'image_baseurl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a78ca085_17495669',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a78ca085_17495669')) {function content_598976a78ca085_17495669($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['banner']->value)&&$_smarty_tpl->tpl_vars['banner']->value) {?>
	<li class="megamenu_banner<?php if ($_smarty_tpl->tpl_vars['banner']->value['specific_class']) {?> <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['banner']->value['specific_class'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>">
    	<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['banner']->value['url'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['banner']->value['blank']) {?>target="_blank"<?php }?>>
        	<img class="img-responsive" src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['image_baseurl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['banner']->value['image'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['banner']->value['title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
            <?php if (isset($_smarty_tpl->tpl_vars['banner']->value['public_title'])&&$_smarty_tpl->tpl_vars['banner']->value['public_title']) {?>
            	<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['banner']->value['public_title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

            <?php }?>
            <?php if (isset($_smarty_tpl->tpl_vars['banner']->value['description'])&&$_smarty_tpl->tpl_vars['banner']->value['description']) {?>
            	<div class="description">
            		<?php echo $_smarty_tpl->tpl_vars['banner']->value['description'];?>

                </div>
            <?php }?>
        </a>
    </li>
<?php }?>
<?php }} ?>
