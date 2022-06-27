{* BASIC OF AUTOCOMPLETE LOGIC *}

{if $_PS_VERSION_|version_compare:'1.5.0':'>'}
    <link rel="stylesheet" type="text/css" href="../js/jquery/plugins/autocomplete/jquery.autocomplete.css" />
    <script type="text/javascript" src="../js/jquery/plugins/autocomplete/jquery.autocomplete.js"></script>
    <script type="text/javascript">
      urlToCall = null;
      /* function autocomplete */
      $(function() {
           $("#addProductAutoCompleteName")
                .autocomplete("ajax_products_list.php", {
                          minChars: 1,
                          autoFill: true,
                          max:20,
                          matchContains: true,
                          mustMatch:true,
                          scroll:false,
                          cacheLength:0,
                          formatItem: function(item) {
                               return item[1]+" ¤ "+item[0];
                          }
                }).result(function(event, item){
                          $("#addProductAutoComplete").val(item[1]);
                });
      });
      
    </script>
{else}
    <link rel="stylesheet" type="text/css" href="../css/jquery.autocomplete.css" />
    <script type="text/javascript" src="../js/jquery/jquery.autocomplete.js"></script>
    <script type="text/javascript">
        urlToCall = null;
        /* function autocomplete */
        $(function() {
             $("#addProductAutoCompleteName")
                .autocomplete("ajax_products_list.php", {
                     delay: 100,
                     minChars: 1,
                     autoFill: true,
                     max:20,
                     matchContains: true,
                     mustMatch:true,
                     scroll:false,
                     cacheLength:0,
                     multipleSeparator:"||",
                     formatItem: function(item) {
                          return item[0];
                     }
                }).result(function(event, item){
                          $("#addProductAutoComplete").val(item[1]);
                });
        });
    </script>
{/if}
{* END OF BASIC AUTOCOMPLETE LOGIC *}

{* ACTION ON ADD AND DELETE *}

  {* BASIC ADD AND DELETE *}
  <script type="text/javascript">
  function addProductAuto(){
      if( $("#addProductAutoComplete").val() != "" ){
          var lineDisplay = $("#addProductAutoComplete").val() + " - " + $("#addProductAutoCompleteName").val();
          $("#choix").html(lineDisplay + '<span onclick="delProductAuto(' + $('#addProductAutoComplete').val() + ');" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span><br />');
          $("#choix").slideDown();
          $("#choose").slideUp();
      }
  }

  function delProductAuto(){
          $("#addProductAutoComplete").val("");
          $("#addProductAutoCompleteName").val("");
          $("#choix").html("");
              $("#choix").slideUp();
          $("#choose").slideDown();
     }
  </script>

  {* CUSTOM ADD AND DELETE *}
  <!--<script type="text/javascript">
    function addProductAuto(){
       if( $("#addProductAutoComplete").val() != "" ){
         str = '<tr>';
         str += '<td>';
         str += $('#addProductAutoCompleteName').val();
         str += '<input type="hidden" name="addProductQuantity[]" value="' + $("#addProductAutoComplete").val() + '" />';
         str += '</td>';
         str += '<td>';
         str += '<span onclick="delProductAuto($(this));" style="cursor: pointer;"><img src="../img/admin/delete.gif" /></span>';
         str += '</td>';
         str += '</tr>';
         $('#totquantitytable tr:last').before(str);
         $('#addProductAutoComplete, #addProductAutoCompleteName').val("");
       }
    }

    function delProductAuto(element){
      element.parent().parent().remove();

    }
  </script>-->
