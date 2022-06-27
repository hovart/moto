<?php /* Smarty version Smarty-3.1.19, created on 2017-08-08 10:30:33
         compiled from "/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/contactform/views/templates/hook/ContactForm_column.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1353469248598976a92f4120-24898194%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fefe7d5528aa9343da0d65f2d35000ba82ae16e8' => 
    array (
      0 => '/home/prestashop-pp/www/themes/LuxuryMotoThemeV3/modules/contactform/views/templates/hook/ContactForm_column.tpl',
      1 => 1470753650,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1353469248598976a92f4120-24898194',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page_name' => 0,
    'contactform' => 0,
    'lang_iso' => 0,
    'forms' => 0,
    'nofile' => 0,
    'choosefile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_598976a9324637_61362224',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598976a9324637_61362224')) {function content_598976a9324637_61362224($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['page_name']->value)&&$_smarty_tpl->tpl_vars['page_name']->value!=$_smarty_tpl->tpl_vars['contactform']->value) {?>

<!-- Block contact  -->



<!-- Modal -->
<div class="modal  fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="row-fluid">
                    <div class="col-md-6 hidden-xs leftCol">
                        <h2>
                            <?php if ($_smarty_tpl->tpl_vars['lang_iso']->value=='de') {?>
                                Unser Kundenservice ruft <br> Sie gerne zurück um Ihre Fragen zu <span class="color">beantworten</span>
                            <?php } else { ?>
                                Nous serons heureux<br> de vous <span class="color">rappeler</span>
                            <?php }?>
                        </h2>

                        <img class="img-responsive" src="/img/sav.jpg" >
                        <p>
                            <?php if ($_smarty_tpl->tpl_vars['lang_iso']->value=='de') {?>Wir <span class="color">rufen</span> Sie <br>auch gerne zurück
                            <?php } else { ?>
                                Notre <span class="color">service client </span>vous rappelle pour répondre à vos demandes
                            <?php }?>
                        </p>
                    </div>
                    <div class="col-md-6 col-xs-12 rightCol">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        <div class="modal-body">
                            <div id="contactform_block" class="block">
                                <h2 class="visible-xs-block">
                                    <?php if ($_smarty_tpl->tpl_vars['lang_iso']->value=='de') {?>
                                        Unser Kundenservice ruft <br> Sie gerne zurück um Ihre Fragen zu <span class="color">beantworten</span>
                                    <?php } else { ?>
                                        Nous serons heureux<br> de vous <span class="color">rappeler</span>
                                    <?php }?>
                                </h2>
                                <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['forms']->value);?>

                            </div>
                        </div>

                    </div>
                </div>

        </div>
    </div>
</div>

<?php if (version_compare(@constant('_PS_VERSION_'),'1.6','>=')) {?>
	
	<script type="text/javascript">
/*
        $.uniform.defaults.fileDefaultHtml = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nofile']->value, ENT_QUOTES, 'UTF-8', true);?>
";
        $.uniform.defaults.fileButtonHtml = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['choosefile']->value, ENT_QUOTES, 'UTF-8', true);?>
";
*/
    </script>
	
<?php }?>
<?php }?>
<!-- Block ContactForm --><?php }} ?>
