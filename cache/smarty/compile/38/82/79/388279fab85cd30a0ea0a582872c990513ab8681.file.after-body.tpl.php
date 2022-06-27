<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:32
         compiled from "/home/prestashop-pp/www/modules/lgtagmanager//views/templates/front/after-body.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1837832025598976a8ee7af1-65005416%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '388279fab85cd30a0ea0a582872c990513ab8681' => 
    array (
      0 => '/home/prestashop-pp/www/modules/lgtagmanager//views/templates/front/after-body.tpl',
      1 => 1461146882,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1837832025598976a8ee7af1-65005416',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TAG_MANAGER_ID' => 0,
    'isOrder' => 0,
    'trans' => 0,
    'items' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a8f19d87_32352305',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a8f19d87_32352305')) {function content_598976a8f19d87_32352305($_smarty_tpl) {?>


	<!-- Google Tag Manager -->

	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo strtr($_smarty_tpl->tpl_vars['TAG_MANAGER_ID']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
"

	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

	})(window,document,'script','dataLayer','<?php echo strtr($_smarty_tpl->tpl_vars['TAG_MANAGER_ID']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');</script>
	 
	<!-- End Google Tag Manager -->



<script type="text/javascript">
	<?php if ($_smarty_tpl->tpl_vars['isOrder']->value) {?>
	
	dataLayer = [{
		'transactionId': '<?php echo strtr($_smarty_tpl->tpl_vars['trans']->value['id'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
		'transactionAffiliation': '<?php echo strtr($_smarty_tpl->tpl_vars['trans']->value['store'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
		'transactionTotal': <?php echo strtr($_smarty_tpl->tpl_vars['trans']->value['total'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
,
		'transactionTax': <?php echo strtr($_smarty_tpl->tpl_vars['trans']->value['tax'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
,
		'transactionShipping': <?php echo strtr($_smarty_tpl->tpl_vars['trans']->value['shipping'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
,
		'transactionProducts': [{
			
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
			'sku': '<?php echo strtr($_smarty_tpl->tpl_vars['item']->value['SKU'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
			'name': '<?php echo strtr($_smarty_tpl->tpl_vars['item']->value['Product'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
			'category': '<?php echo strtr($_smarty_tpl->tpl_vars['item']->value['Category'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
			'price': <?php echo strtr($_smarty_tpl->tpl_vars['item']->value['Price'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
,
			'quantity': <?php echo strtr($_smarty_tpl->tpl_vars['item']->value['Quantity'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>

			},
			<?php } ?>

			
		]
	}];
	

	<?php }?>

</script><?php }} ?>
