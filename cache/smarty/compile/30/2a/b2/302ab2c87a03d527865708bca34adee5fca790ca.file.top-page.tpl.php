<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:31
         compiled from "/home/prestashop-pp/www/modules/lgtagmanager/views/templates/front/top-page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:809521680598976a75e6bb9-55361279%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '302ab2c87a03d527865708bca34adee5fca790ca' => 
    array (
      0 => '/home/prestashop-pp/www/modules/lgtagmanager/views/templates/front/top-page.tpl',
      1 => 1461659772,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '809521680598976a75e6bb9-55361279',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'isOrder' => 0,
    'trans' => 0,
    'items' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a7621330_85069770',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a7621330_85069770')) {function content_598976a7621330_85069770($_smarty_tpl) {?>

<script type="text/javascript">
	<?php if ($_smarty_tpl->tpl_vars['isOrder']->value) {?>
		
		dataLayer = [{
			'event' : 'mgd.orderCompleted',
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

</script>
<?php }} ?>
