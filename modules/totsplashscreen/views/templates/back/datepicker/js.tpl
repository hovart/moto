{if $_PS_VERSION_|version_compare:'1.5.0':'>'}
    <link rel="stylesheet" type="text/css" href="../js/jquery/ui/themes/base/jquery.ui.datepicker.css" />
    <link rel="stylesheet" type="text/css" href="../js/jquery/ui/themes/base/jquery.ui.core.css" />
    <link rel="stylesheet" type="text/css" href="../js/jquery/ui/themes/base/jquery.ui.theme.css" />
    <script type="text/javascript" src="../js/jquery/ui/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="../js/jquery/ui/jquery.ui.datepicker.min.js"></script>
    <script type="text/javascript" src="../js/jquery/ui/i18n/jquery.ui.datepicker-{$iso}.js"></script>
    <script type="text/javascript">
       $(function(){
          $(".datepicker").datepicker({
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd'
          });
       });
    </script>
{else}
    <link rel="stylesheet" type="text/css" href="../js/jquery/datepicker/datepicker.css" />
    <script type="text/javascript" src="../js/jquery/jquery-ui-1.8.10.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery/datepicker/ui/i18n/ui.datepicker-fr.js"></script>
    <script type="text/javascript">
        $(function(){
          $(".datepicker").datepicker({
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd'
          });
       });
    </script>
{/if}