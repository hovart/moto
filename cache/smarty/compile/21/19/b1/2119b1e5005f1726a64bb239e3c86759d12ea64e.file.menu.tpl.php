<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/tmmegamenu/views/templates/hook/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1832400025598976a7a27136-65951347%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2119b1e5005f1726a64bb239e3c86759d12ea64e' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/tmmegamenu/views/templates/hook/menu.tpl',
      1 => 1454627186,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1832400025598976a7a27136-65951347',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU' => 0,
    'hook' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a7a38964_90851642',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a7a38964_90851642')) {function content_598976a7a38964_90851642($_smarty_tpl) {?>
</div>
<?php if (isset($_smarty_tpl->tpl_vars['MENU']->value)&&$_smarty_tpl->tpl_vars['MENU']->value!='') {?> 
    <?php if ($_smarty_tpl->tpl_vars['hook']->value=='left_column'||$_smarty_tpl->tpl_vars['hook']->value=='right_column') {?>
        <section class="block">
            <h4 class="title_block"><?php echo smartyTranslate(array('s'=>'Menu','mod'=>'tmmegamenu'),$_smarty_tpl);?>
</h4>
            <div class="block_content <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
_menu column_menu top-level tmmegamenu_item">
    <?php } else { ?>
        <div class="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
_menu top-level tmmegamenu_item" id="mgdMainMenu">
            <div class="menu-title tmmegamenu_item"><?php echo smartyTranslate(array('s'=>'Menu','mod'=>'tmmegamenu'),$_smarty_tpl);?>
</div>
    <?php }?>
            <div class="container">
                <?php echo $_smarty_tpl->tpl_vars['MENU']->value;?>

            </div>
    <?php if ($_smarty_tpl->tpl_vars['hook']->value=='left_column'||$_smarty_tpl->tpl_vars['hook']->value=='right_column') {?>
            </div>
        </section>
    <?php } else { ?>
        </div>

        </div>
    <?php }?>
<?php }?><?php }} ?>
