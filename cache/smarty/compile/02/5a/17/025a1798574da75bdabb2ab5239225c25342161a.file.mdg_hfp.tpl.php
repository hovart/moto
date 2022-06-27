<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/mdg_hfp/views/templates/hook/mdg_hfp.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1903607783598976a836b099-31646253%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '025a1798574da75bdabb2ab5239225c25342161a' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/mdg_hfp/views/templates/hook/mdg_hfp.tpl',
      1 => 1459263186,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1903607783598976a836b099-31646253',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blocks' => 0,
    'count_tab' => 0,
    'block' => 0,
    'compteur' => 0,
    'tabcounter' => 0,
    'hook' => 0,
    'product' => 0,
    'link' => 0,
    'smallSize' => 0,
    'PS_CATALOG_MODE' => 0,
    'restricted_country_mode' => 0,
    'priceDisplay' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a83f67e9_97230173',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a83f67e9_97230173')) {function content_598976a83f67e9_97230173($_smarty_tpl) {?><?php if (!is_callable('smarty_function_counter')) include '/home/prestashop-pp/www/tools/smarty/plugins/function.counter.php';
?>

<?php if ($_smarty_tpl->tpl_vars['blocks']->value) {?>
<div class="container" id="tptnprodtabs">
    <!-- MODULE Home Featured Products -->

        <?php if (isset($_smarty_tpl->tpl_vars['count_tab']->value)) {?>

            <!-- tabs block -->

                <ul  class="tabs_title">
                    <?php echo smarty_function_counter(array('start'=>0,'assign'=>'compteur'),$_smarty_tpl);?>

                    <?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->_loop = true;
?><?php echo smarty_function_counter(array(),$_smarty_tpl);?>

                        <?php if ($_smarty_tpl->tpl_vars['block']->value['is_tab']) {?>
                            <li><a class="<?php if ($_smarty_tpl->tpl_vars['compteur']->value==1) {?>selected <?php }?>" href="#<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-featured"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</a></li>
                        <?php }?>

                    <?php } ?>
                </ul>

                <?php $_smarty_tpl->tpl_vars['tabcounter'] = new Smarty_variable(0, null, 0);?>

                <?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
                    <?php if ($_smarty_tpl->tpl_vars['block']->value['is_tab']) {?>
                    <div class="tptncarousel" id="<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-featured">
                        <div class="tptnslides row"  <?php if ($_smarty_tpl->tpl_vars['tabcounter']->value==1) {?> active<?php }?>">

                        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./modules/mdg_hfp/views/templates/hook/tptnprodtabs-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['block']->value['products']), 0);?>

                        </div>
                    </div>
                    <?php }?>
                <?php } ?>

            </div>

        <?php }?>

        <!-- default block -->
        <?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
            <?php if (!$_smarty_tpl->tpl_vars['block']->value['is_tab']) {?>
            <div class="mdg_hfp mdg_hfp-<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>
 block products_block clearfix">
                <?php if ($_smarty_tpl->tpl_vars['hook']->value=='columns') {?>
                    <p class="title_block"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</p>
                    <div class="block_content products-block" style="">
                        <ul>
                        <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['block']->value['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
                            <li class="clearfix">
                                <a class="products-block-image" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
">
                                    <img class="replace-2x img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'small_default'), ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php if (!empty($_smarty_tpl->tpl_vars['product']->value['legend'])) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['legend'], ENT_QUOTES, 'UTF-8', true);?>
<?php } else { ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" title="<?php if (!empty($_smarty_tpl->tpl_vars['product']->value['legend'])) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['legend'], ENT_QUOTES, 'UTF-8', true);?>
<?php } else { ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" <?php if (isset($_smarty_tpl->tpl_vars['smallSize']->value)) {?> width="<?php echo $_smarty_tpl->tpl_vars['smallSize']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['smallSize']->value['height'];?>
"<?php }?> itemprop="image">
                                </a>
                                <div class="product-content">
                                    <h5><a class="product-name" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],30,'...'), ENT_QUOTES, 'UTF-8', true);?>
</a></h5>
                                    <p class="product-description"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['product']->value['description_short']),45,'...');?>
</p>
                                    <?php if ((!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value&&((isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price'])||(isset($_smarty_tpl->tpl_vars['product']->value['available_for_order'])&&$_smarty_tpl->tpl_vars['product']->value['available_for_order'])))) {?>
                                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="price-box">
                                        <?php if (isset($_smarty_tpl->tpl_vars['product']->value['show_price'])&&$_smarty_tpl->tpl_vars['product']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)) {?>
                                            <?php if (isset($_smarty_tpl->tpl_vars['product']->value['specific_prices'])&&$_smarty_tpl->tpl_vars['product']->value['specific_prices']&&isset($_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction'])&&$_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction']>0) {?>
                                                <span itemprop="price" class="price special-price">
                                                    <?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
<?php } else { ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?>
                                                </span>
                                                <meta itemprop="priceCurrency" content="<?php echo $_smarty_tpl->tpl_vars['priceDisplay']->value;?>
" />
                                                <?php if ($_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction_type']=='percentage') {?>
                                                    <span class="price-percent-reduction">-<?php echo $_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction']*100;?>
%</span>
                                                <?php }?>
                                                <span class="old-price">
                                                    <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['product']->value['price_without_reduction']),$_smarty_tpl);?>

                                                </span>
                                            <?php } else { ?>
                                                <span itemprop="price" class="price product-price">
                                                    <?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
<?php } else { ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['product']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?>
                                                </span>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                    <?php }?>
                                </div>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>
                <?php } else { ?>
                    <h2 class="page-heading product-listing"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</h2>
                    <div class="block_content">
                        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['block']->value['products']), 0);?>

                    </div>
                <?php }?>
            </div>
            <?php }?>
        <?php } ?>

    </ul>

</div>
<?php }?><?php }} ?>
