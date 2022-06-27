{if $instantsearch}
	<script type="text/javascript">
	// <![CDATA[
		function tryToCloseInstantSearch() {
			if ($('#old_center_column').length > 0)
			{
				$('#center_column').remove();
				$('#old_center_column').attr('id', 'center_column');
				$('#center_column').show();
				return false;
			}
		}
		
		instantSearchQueries = new Array();
		function stopInstantSearchQueries(){
			for(i=0;i<instantSearchQueries.length;i++) {
				instantSearchQueries[i].abort();
			}
			instantSearchQueries = new Array();
		}
		
		$("#search_query_top").keyup(function(){
			if($(this).val().length > 0){
				stopInstantSearchQueries();
				instantSearchQuery = $.ajax({
					url: '{if $search_ssl == 1}{$link->getPageLink('search', true)|addslashes}{else}{$link->getPageLink('search')|addslashes}{/if}',
					data: {
						instantSearch: 1,
						id_lang: {$cookie->id_lang},
						q: $(this).val()
					},
					dataType: 'html',
					type: 'POST',
					success: function(data){
						if($("#search_query_top").val().length > 0)
						{
							tryToCloseInstantSearch();
							$('#center_column').attr('id', 'old_center_column');
							$('#old_center_column').after('<div id="center_column" class="' + $('#old_center_column').attr('class') + '">'+data+'</div>');
							$('#old_center_column').hide();
							$("#instant_search_results a.close").click(function() {
								$("#search_query_top").val('');
								return tryToCloseInstantSearch();
							});
							return false;
						}
						else
							tryToCloseInstantSearch();
					}
				});
				instantSearchQueries.push(instantSearchQuery);
			}
			else
				tryToCloseInstantSearch();
		});
	// ]]>
	</script>
{/if}
{if $ajaxsearch}
<script type="text/javascript">
	var moduleDir = "{$module_dir}";
	// <![CDATA[
		$('document').ready( function() {
			$("#search_query_top")
				.autocomplete(moduleDir + "tptnsearch_ajax.php", {
						minChars: 3,
						max: 10,
						width: 300,
						selectFirst: false,
						scroll: false,
						dataType: "json",
						formatItem: function(data, i, max, value, term) {
							return value;
						},
						parse: function(data) {
							var mytab = new Array();	
							for (var i = 0; i < data.length; i++){
								if (data[i].pname.length > 35){
									var pname = jQuery.trim(data[i].pname).substring(0, 35).split(" ").slice(0, -1).join(" ") + "...";      
								}else{
									var pname = data[i].pname;
								}
								mytab[mytab.length] = { data: data[i], value: '<img class="ac_product_img" src="' + data[i].product_image + '" alt="' + data[i].pname + '" height="30" />' + '<span class="ac_product_name">' + pname + '</span>' };
							}
								
							 //data[i].product_link = "{if $search_ssl == 1}{$link->getPageLink('search', true)}{else}{$link->getPageLink('search')}{/if}&search_query=" + $(this).val ;
							 //mytab[mytab.length] = { data: data[i], value: '<span class="ac_more_link">{l s='More' mod='tptnsearch'}</span>' };
							return mytab;
						},
						extraParams: {
							ajaxSearch: 1,
							id_lang: {$cookie->id_lang}
						}
					}
				)
				.result(function(event, data, formatted) {
					$('#search_query_top').val(data.pname);
					document.location.href = data.product_link;
				})
		});
	// ]]>
	</script>
{/if}