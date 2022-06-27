<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/productsbycategoryslider/productsbycategoryslider.tpl" */ ?>
<?php /*%%SmartyHeaderCode:347732713598976a82a2798-70134251%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd1cd6a3218adf48de1c9930d3412b88305f4476d' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/productsbycategoryslider/productsbycategoryslider.tpl',
      1 => 1457973392,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '347732713598976a82a2798-70134251',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categoryProducts' => 0,
    'categoryProduct' => 0,
    'link' => 0,
    'homeSize' => 0,
    'img_manu_dir' => 0,
    'PS_CATALOG_MODE' => 0,
    'restricted_country_mode' => 0,
    'priceDisplay' => 0,
    'currency' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a830f716_07325395',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a830f716_07325395')) {function content_598976a830f716_07325395($_smarty_tpl) {?><?php if (count($_smarty_tpl->tpl_vars['categoryProducts']->value)>0&&$_smarty_tpl->tpl_vars['categoryProducts']->value!==false) {?>
	<h2 class="productscategory_h2"><span><?php echo smartyTranslate(array('s'=>'Discover also','mod'=>'productsbycategoryslider'),$_smarty_tpl);?>
 </span></h2>


	<div  id="slider-categoryslider" class="tptncarousel">
		<div class="tptnslides row">
			<?php  $_smarty_tpl->tpl_vars['categoryProduct'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['categoryProduct']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categoryProducts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['categoryProduct']->key => $_smarty_tpl->tpl_vars['categoryProduct']->value) {
$_smarty_tpl->tpl_vars['categoryProduct']->_loop = true;
?>
				<div class="ajax_block_product item col-xs-12" itemscope itemtype="http://schema.org/Product">
                    <div class="item-content">
                        <div class="left-block">
                            <div class="product-image-container">
                                <a  href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" itemprop="url" >
                                    <img class="replace-2x" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['categoryProduct']->value['link_rewrite'],$_smarty_tpl->tpl_vars['categoryProduct']->value['id_image'],'home_default'), ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name']);?>
" <?php if (isset($_smarty_tpl->tpl_vars['homeSize']->value)) {?> width="<?php echo $_smarty_tpl->tpl_vars['homeSize']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['homeSize']->value['height'];?>
"<?php }?> itemprop="image" />
                                </a>
                             <?php if (isset($_smarty_tpl->tpl_vars['categoryProduct']->value['new'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['new']==1) {?><span class="new-box"><?php echo smartyTranslate(array('s'=>'New','mod'=>'tptnprodtabs'),$_smarty_tpl);?>
</span><?php }?>
                            <?php if (isset($_smarty_tpl->tpl_vars['categoryProduct']->value['reduction'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['reduction']) {?><span class="sale-box">
                                - <?php echo $_smarty_tpl->tpl_vars['categoryProduct']->value['specific_prices']['reduction']*100;?>
%
                                </span><?php }?>
                            </div>
                            <div class="right-block">
                                <div class="manufacturerContainer">
                                    <img class="manufacturer" src="<?php echo $_smarty_tpl->tpl_vars['img_manu_dir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['categoryProduct']->value['id_manufacturer'];?>
.jpg" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['manufacturer_name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['manufacturer_name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
                                </div>
                                <h5 itemprop="name">
                                    <?php if (isset($_smarty_tpl->tpl_vars['categoryProduct']->value['pack_quantity'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['pack_quantity']) {?><?php echo (intval($_smarty_tpl->tpl_vars['categoryProduct']->value['pack_quantity'])).(' x ');?>
<?php }?>
                                    <a class="product-name" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['link'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryProduct']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" itemprop="url" >
                                        <?php echo htmlspecialchars($_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate($_smarty_tpl->tpl_vars['categoryProduct']->value['name'],30,'...'), ENT_QUOTES, 'UTF-8', true);?>

                                    </a>
                                </h5>
                                <?php if ((!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value&&((isset($_smarty_tpl->tpl_vars['categoryProduct']->value['show_price'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['show_price'])||(isset($_smarty_tpl->tpl_vars['categoryProduct']->value['available_for_order'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['available_for_order'])))) {?>
                                    <div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <?php if (isset($_smarty_tpl->tpl_vars['categoryProduct']->value['show_price'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['show_price']&&!isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)) {?>
                                            <span itemprop="price" class="price product-price">
                                            <?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['categoryProduct']->value['price']),$_smarty_tpl);?>
<?php } else { ?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['categoryProduct']->value['price_tax_exc']),$_smarty_tpl);?>
<?php }?>
                                        </span>
                                                                    <meta itemprop="priceCurrency" content="<?php echo $_smarty_tpl->tpl_vars['currency']->value->iso_code;?>
" />
                                                                    <?php if (isset($_smarty_tpl->tpl_vars['categoryProduct']->value['specific_prices'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['specific_prices']&&isset($_smarty_tpl->tpl_vars['categoryProduct']->value['specific_prices']['reduction'])&&$_smarty_tpl->tpl_vars['categoryProduct']->value['specific_prices']['reduction']>0) {?>
                                                                        <span class="old-price product-price">
                                                <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayWtPrice'][0][0]->displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['categoryProduct']->value['price_without_reduction']),$_smarty_tpl);?>

                                            </span>
                                                <?php }?>
                                            <?php }?>
                                        </div>
                                    <?php }?>
                            </div>
                         </div>
                      </div>
                </div>
			<?php } ?>
		</div>
	</div>


    

<?php }?>
<?php }} ?>
