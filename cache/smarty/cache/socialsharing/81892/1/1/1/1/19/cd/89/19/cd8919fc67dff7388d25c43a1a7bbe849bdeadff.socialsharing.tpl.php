<?php /*%%SmartyHeaderCode:20260800195981f5cdd49723-04123822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd8919fc67dff7388d25c43a1a7bbe849bdeadff' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/socialsharing/views/templates/hook/socialsharing.tpl',
      1 => 1454627188,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20260800195981f5cdd49723-04123822',
  'variables' => 
  array (
    'PS_SC_TWITTER' => 0,
    'PS_SC_FACEBOOK' => 0,
    'PS_SC_GOOGLE' => 0,
    'PS_SC_PINTEREST' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5981f5cdd77114_31094751',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5981f5cdd77114_31094751')) {function content_5981f5cdd77114_31094751($_smarty_tpl) {?><ul class="socialSharing"><li class="txt">Partager :</li><li> <button data-type="twitter" type="button" class="btn-twitter social-sharing"> <i class="fa fa-twitter"></i> </button></li><li> <button data-type="facebook" type="button" class="btn-facebook social-sharing"> <i class="fa fa-facebook"></i> </button></li><li> <button data-type="google-plus" type="button" class="btn-google-plus social-sharing"> <i class="fa fa-google-plus"></i> </button></li><li> <button data-type="pinterest" type="button" class="btn-pinterest social-sharing"> <i class="fa fa-pinterest"></i> </button></li><li class="sendtofriend"><a id="send_friend_button" href="#send_friend_form" title="Send to a friend"> <i class="fa fa-share"></i></a></li></ul><?php }} ?>
