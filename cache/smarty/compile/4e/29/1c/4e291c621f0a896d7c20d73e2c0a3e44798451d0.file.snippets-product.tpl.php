<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/snippets-product.tpl" */ ?>
<?php /*%%SmartyHeaderCode:496846992598976a7a5a9b7-19150161%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4e291c621f0a896d7c20d73e2c0a3e44798451d0' => 
    array (
      0 => '/home/prestashop-pp/www/modules/gsnippetsreviews/views/templates/hook/snippets-product.tpl',
      1 => 1501512251,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '496846992598976a7a5a9b7-19150161',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bDisplay' => 0,
    'sBadgeStyle' => 0,
    'bColStyle' => 0,
    'sBadgeFreeStyle' => 0,
    'aProduct' => 0,
    'bUseBrand' => 0,
    'sGsrSeparator' => 0,
    'bUseDesc' => 0,
    'bUseIdentifier' => 0,
    'bUseSupplier' => 0,
    'bUseCondition' => 0,
    'bOfferAggregate' => 0,
    'bUseOfferCount' => 0,
    'bUseHighPrice' => 0,
    'bUseCat' => 0,
    'bUseUntilDate' => 0,
    'bUseSeller' => 0,
    'bUseAvailability' => 0,
    'bUseBreadcrumb' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a7b341e6_15013544',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a7b341e6_15013544')) {function content_598976a7b341e6_15013544($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/prestashop-pp/www/tools/smarty/plugins/modifier.escape.php';
?>
<?php if (!empty($_smarty_tpl->tpl_vars['bDisplay']->value)&&!empty($_smarty_tpl->tpl_vars['sBadgeStyle']->value)) {?>
	<!-- GSR - Rich Snippets Product -->
	<?php if ($_smarty_tpl->tpl_vars['sBadgeStyle']->value=="bottom") {?>
	<div class="clr_20"></div>
	<?php }?>
	<?php if (!empty($_smarty_tpl->tpl_vars['bColStyle']->value)) {?>
	<div class="width-100">
	<?php }?>
		<div <?php if (!empty($_smarty_tpl->tpl_vars['sBadgeFreeStyle']->value)) {?>style="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sBadgeFreeStyle']->value, 'UTF-8');?>
"<?php } else { ?>class="badge-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['sBadgeStyle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"<?php }?>>
			
			<?php if (!empty($_smarty_tpl->tpl_vars['sBadgeStyle']->value)&&($_smarty_tpl->tpl_vars['sBadgeStyle']->value=="bottom"||$_smarty_tpl->tpl_vars['sBadgeStyle']->value=="top")) {?>
				<?php $_smarty_tpl->tpl_vars["sGsrSeparator"] = new Smarty_variable(" - ", null, 0);?>
				<?php $_smarty_tpl->tpl_vars["sGsrSeparatorTop"] = new Smarty_variable('', null, 0);?>
			<?php } else { ?>
				<?php $_smarty_tpl->tpl_vars["sGsrSeparator"] = new Smarty_variable("<br />", null, 0);?>
				<?php $_smarty_tpl->tpl_vars["sGsrSeparatorTop"] = new Smarty_variable("<br />", null, 0);?>
			<?php }?>
			<div class="product-snippets">
				
				<span itemscope itemtype="http://schema.org/Product">
					<strong><span itemprop="name"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['name'], 'UTF-8');?>
</span></strong>
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseBrand']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['manufacturer_name'])) {?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<span itemprop="brand"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['manufacturer_name'], 'UTF-8');?>
</span><?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseDesc']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['googleDesc'])) {?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<span itemprop="description"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['googleDesc'], 'UTF-8'),60,"...");?>
</span><?php }?>
					
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseIdentifier']->value)&&(!empty($_smarty_tpl->tpl_vars['aProduct']->value['ean13'])||!empty($_smarty_tpl->tpl_vars['aProduct']->value['upc']))) {?>
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

						<strong><?php echo smartyTranslate(array('s'=>'Product GTIN','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :
						<?php if (!empty($_smarty_tpl->tpl_vars['aProduct']->value['ean13'])) {?>
							<span itemprop="gtin13"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['ean13'], 'UTF-8');?>
<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
</span>
						<?php } elseif (!empty($_smarty_tpl->tpl_vars['aProduct']->value['upc'])) {?>
							<span itemprop="gtin13">0<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['upc'], 'UTF-8');?>
<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
</span>
						<?php }?>
						<?php if (!empty($_smarty_tpl->tpl_vars['aProduct']->value['reference'])) {?>
							<strong><?php echo smartyTranslate(array('s'=>'Product Ref','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : <span itemprop="sku"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['reference'], 'UTF-8');?>
</span>
						<?php }?>
					<?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseSupplier']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['supplier_reference'])) {?>
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<strong><?php echo smartyTranslate(array('s'=>'Supplier Ref','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : <span itemprop="mpn"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['supplier_reference'], 'UTF-8');?>
</span>
					<?php }?>
					
					<?php if (!empty($_smarty_tpl->tpl_vars['bUseCondition']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['condition'])) {?>
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

						<strong><?php echo smartyTranslate(array('s'=>'Label','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> :&nbsp;
						<?php if ($_smarty_tpl->tpl_vars['aProduct']->value['condition']=="used") {?>
							<?php echo smartyTranslate(array('s'=>'Used','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

							<link itemprop="itemCondition" href="http://schema.org/UsedCondition"/>
							<span><?php echo smartyTranslate(array('s'=>'Used','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span>
						<?php } elseif ($_smarty_tpl->tpl_vars['aProduct']->value['condition']=="refurbished") {?>
							<link itemprop="itemCondition" href="http://schema.org/RefurbishedCondition"/>
							<span><?php echo smartyTranslate(array('s'=>'Refurbished','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span>
						<?php } else { ?>
							<link itemprop="itemCondition" href="http://schema.org/NewCondition"/>
							<span><?php echo smartyTranslate(array('s'=>'New','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span>
						<?php }?>
					<?php }?>

					
					<?php if (!empty($_smarty_tpl->tpl_vars['bOfferAggregate']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['combinations'])&&((!empty($_smarty_tpl->tpl_vars['bUseOfferCount']->value)||!empty($_smarty_tpl->tpl_vars['bUseHighPrice']->value))&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['highestPrice'])&&$_smarty_tpl->tpl_vars['aProduct']->value['highestPrice']>$_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice'])) {?>
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

						<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseCat']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['category'])) {?><strong><?php echo smartyTranslate(array('s'=>'Category','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : <span itemprop="category"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['category'], 'UTF-8');?>
</span><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseHighPrice']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['highestPrice'])&&$_smarty_tpl->tpl_vars['aProduct']->value['highestPrice']>$_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice']) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currencyPrefix'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <span itemprop="lowPrice"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span> <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currencySuffix'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseHighPrice']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['highestPrice'])&&$_smarty_tpl->tpl_vars['aProduct']->value['highestPrice']>$_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice']) {?> <?php echo smartyTranslate(array('s'=>'to','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currencyPrefix'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <span itemprop="highPrice"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['highestPrice'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span> <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currencySuffix'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <?php }?>
							<meta itemprop="priceCurrency" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currency'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseOfferCount']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['offerCount'])&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['highestPrice'])&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice'])&&$_smarty_tpl->tpl_vars['aProduct']->value['highestPrice']>$_smarty_tpl->tpl_vars['aProduct']->value['lowestPrice']) {?><?php echo smartyTranslate(array('s'=>'From','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <span itemprop="offerCount"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['offerCount'], 'UTF-8');?>
 <?php echo smartyTranslate(array('s'=>'combinations','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</span><?php }?>
						</span>
					
					<?php } else { ?>
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

						<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseCat']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['category'])) {?><strong><?php echo smartyTranslate(array('s'=>'Category','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : <span itemprop="category"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['category'], 'UTF-8');?>
</span><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<?php }?>
							<strong><?php echo smartyTranslate(array('s'=>'Price','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : <?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['currencyPrefix'], 'UTF-8');?>
<span itemprop="price"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['price'], 'UTF-8');?>
</span><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['currencySuffix'], 'UTF-8');?>

							<meta itemprop="priceCurrency" content="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['currency'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseUntilDate']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['untilDate'])) {?>
								<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

								(<?php echo smartyTranslate(array('s'=>'Sale ends','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <span itemprop="priceValidUntil" itemtype="http://schema.org/Date">
								(<time itemprop="endDate" datetime="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['aProduct']->value['untilDate'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['untilDateHuman'], 'UTF-8');?>
</time>)</span>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseSeller']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['seller'])) {?>
								<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>
<?php echo smartyTranslate(array('s'=>'Available from','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
 <span itemprop="seller"> "<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['seller'], 'UTF-8');?>
"</span>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['bUseAvailability']->value)) {?>
								<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

								<strong><?php echo smartyTranslate(array('s'=>'Stock','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>
</strong> : &nbsp;
								<?php if ($_smarty_tpl->tpl_vars['aProduct']->value['quantity']>0||$_smarty_tpl->tpl_vars['aProduct']->value['stockManagement']==0) {?>
									<link itemprop="availability" href="http://schema.org/InStock"/>
									<?php echo smartyTranslate(array('s'=>'In Stock','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								<?php } else { ?>
									<?php echo smartyTranslate(array('s'=>'Out of Stock','mod'=>'gsnippetsreviews'),$_smarty_tpl);?>

								<?php }?>
							<?php }?>
						</span>
					<?php }?>
				</span>
				<?php if (!empty($_smarty_tpl->tpl_vars['bUseBreadcrumb']->value)&&!empty($_smarty_tpl->tpl_vars['aProduct']->value['breadcrumb'])) {?>
					<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['sGsrSeparator']->value, 'UTF-8');?>

					<span itemscope itemtype="http://schema.org/WebPage">
					<span class="navigation-pipe" itemprop="breadcrumb">
						<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['aProduct']->value['breadcrumb'], 'UTF-8');?>

					</span>
				</span>
				<?php }?>
				<br />
			</div>
			
		</div>
	<?php if (!empty($_smarty_tpl->tpl_vars['bColStyle']->value)) {?>
	</div>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['sBadgeStyle']->value!="bottom") {?>
	<div class="clr_20"></div>
	<?php }?>
	<!-- /GSR - Rich Snippets Product -->
<?php }?><?php }} ?>
