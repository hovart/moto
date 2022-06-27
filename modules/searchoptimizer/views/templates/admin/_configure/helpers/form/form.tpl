{*
* 2015 Novansis
*
* NOTICE OF LICENSE
*
* Conditions and limitations:
*
* A. This source code file is copyrighted, you cannot remove any copyright notice from this file.  You agree to prevent any unauthorized copying of this file.  Except as expressly provided herein, Novansis does not grant any express or implied right to you under copyrights, trademarks, or trade secret information.
*
* B. You may NOT:  (i) rent or lease the file to any third party; (ii) assign this file or transfer the file without the express written consent of Novansis; (iii) modify, adapt, or translate the file in whole or in part; or (iv) distribute, sublicense or transfer the source code form of any components of the file and derivatives thereof to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer versions in the future.
*
*  @author    Novansis <info@novansis.com>
*  @copyright 2015 Novansis SL
*  @license   http://www.novansis.com/
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}
$(document).ready(function(){
	$("#letterFilter").on('change', function() {
	  request('w');
	});	
	$("#lang").on('change', function() {
	  request('a');
	  request('wtr');
	});	
	$("#addItems").click(add);
	$("#removeItems").click(remove);
	$("#addAllItems").click(addAll);
	$("#removeAllItems").click(removeAll);
	request('wtr');
	
	$("#lang_s").on('change', function() {
	  request('sp');
	});	
	$("#searchList").on('change', function() {
		if ($("#searchList :selected").length > 1) {
			$("#searchResultList").empty();
		} else {
			request('r');
		}
	});	
	$("#availableList").on('change', function() {
		if ($("#availableList :selected").length == 1) {
			request('p');
		} else {
			var products = $("#products");
			products.empty().append('{l s='No results' mod='searchoptimizer'}');
		}
	});	
	$('[name=show_results]').on('change', function() {
	  request('sp');
	});	
	$('[name=SEARCH_OPTIMIZER_RECORD]').on('change', function() {
	  request('rsq');
	});	
	$('#removeSearch').on('click', function() {
	  if ($("#searchList").val() == null)
		alert('{l s='Please, select one or more searches to remove' mod='searchoptimizer'}');
	  else
		request('rv');
	  return false;
	});	

	request('sp');
	
	function request(act)
	{
		var data = 'act=' + act + '&s={$s|urlencode|escape:'htmlall':'UTF-8'}&g={$g|urlencode|escape:'htmlall':'UTF-8'}';
		if (act == 'w') {
			data += '&letterFilter=' + $("#letterFilter").val() + '&lang=' + $("#lang").val();
		} else if (act == 'wtr' || act == 'a') {
			data += '&lang=' + $("#lang").val();
		} else if (act == 'sp') {
			data += '&lang=' + $("#lang_s").val() + '&results=' + $('[name=show_results]:checked').val();
		} else if (act == 'r') {
			data += '&lang=' + $("#lang_s").val() + '&search=' + $("#searchList").val();
		} else if (act == 'rv') {
			data += '&remove=' + encodeURIComponent($("#searchList").val());
		} else if (act == 'rsq') {
			data += '&SEARCH_OPTIMIZER_RECORD=' + $('[name=SEARCH_OPTIMIZER_RECORD]:checked').val();
		} else if (act == 'p') {
			data += '&lang=' + $("#lang").val() + '&word=' + $("#availableList").val();
		}


		$.ajax({
		  type: 'POST',
		  url: '{$module_dir|escape:'htmlall':'UTF-8'}' + 'searchoptimizer/searchoptimizer-ajax.php',
		  data: data,
		  dataType: 'json',
		  success: function(data) {
			if (act == 'w') {
				var sel = $("#availableList");
				sel.empty();
				for(i = 0; i < data.length; i++) {
					if (!$("#wordsToRemoveList").find('option[value=' + data[i].id_option + ']').length) {
						sel.append('<option value="'+data[i].id_option+'">'+data[i].name+'</option>');
					}
				}
			} else if (act == 'wtr') {
				var sel = $("#wordsToRemoveList");
				sel.empty();
				for(i = 0; i < data.length; i++) {
					if (data[i].id_option != '')
						sel.append('<option value="'+data[i].id_option+'">'+data[i].name+'</option>');
				}
				request('w');
				serialize();				
			} else if (act == 'a') {
				var sel = $("#letterFilter");
				sel.empty();
				for(i = 0; i < data.length; i++) {
					sel.append('<option value="'+data[i].id_option+'">'+data[i].name+'</option>');
				}
				request('w');
			} else if (act == 'sp') {
				var sel = $("#searchList");
				sel.empty();
				for(i = 0; i < data.length; i++) {
					sel.append('<option value="'+data[i].id_option+'">'+data[i].name+'</option>');
				}
				$("#searchList").trigger("change");
			} else if (act == 'r') {
				var sel = $("#searchResultList");
				sel.empty();
				for(i = 0; i < data.length; i++) {
					sel.append('<option value="'+data[i].id_option+'" disabled>'+data[i].name+'</option>');
				}
				if (data.length == 0) {
					sel.append('<option value="-1" disabled>{l s='No results' mod='searchoptimizer'}</option>');
				}
			} else if (act == 'rv') {
				request('sp');
			} else if (act == 'p') {
				var products = $("#products");
				if (data.length > 0) {
					products.empty();
					for(i = 0; i < data.length; i++) {
						products.append(data[i] + '<br/>');
					}
				} else {
					products.empty().append('{l s='No results' mod='searchoptimizer'}');
				}
			}
		  },
		  error: function(result) {
            alert("Error getting data");
          }
		});
	}
	
	function addAll() 
	{
		moveItems('wordsToRemoveList','availableList', true);
		orderSelect("availableList");
		serialize();
		return false;
	}
	function add()
	{
		moveItems('wordsToRemoveList','availableList', false);
		orderSelect("availableList");
		serialize();
		return false;
	}
	function removeAll() 
	{
		moveItems('availableList','wordsToRemoveList', true);
		orderSelect("wordsToRemoveList");
		serialize();
		var products = $("#products");
		products.empty().append('{l s='No results' mod='searchoptimizer'}');

		return false;
	}
	function remove()
	{
		moveItems('availableList','wordsToRemoveList', false);
		orderSelect("wordsToRemoveList");
		serialize();
		return false;
	}
	
	function moveItems(origin, dest, all) {
		var x = document.getElementById(origin);
		var y = document.getElementById(dest);
		var optionVal = new Array();
		var toRemove = new Array();
		for (i = 0; i < x.length; i++) {
			if (x.options[i].selected || all) {
				optionVal.push(x.options[i].text);
				toRemove.push(i);
			}
		} 
		for (i = toRemove.length - 1; i >= 0; i--) {
			x.remove(toRemove[i]);
		}
		for (var i = 0; i < optionVal.length; i++) {
			var opt = optionVal[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			y.appendChild(el);
		}
	}
	
	function orderSelect(select) {
		var x = document.getElementById(select);
		var optionVal = new Array();
		for (i = 0; i < x.length; i++) {
			optionVal.push(x.options[i].text);
		}
		for (i = x.length; i >= 0; i--) {
			x.remove(i);
		}
		optionVal.sort();
		for (var i = 0; i < optionVal.length; i++) {
			var opt = optionVal[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			x.appendChild(el);
		}
	}
	
	function serialize()
	{
		var options = "";
		$("#wordsToRemoveList option").each(function(i){
			options += $(this).val()+"|";
		});
		$("#wordsToRemove").val(options.substr(0, options.length - 1));
	}
	
});
{/block}

{block name="input"}
    {if $input.type == 'words_management'}
		<div class="row">
			<div class="col-lg-6">
				<h4 style="margin-top:5px;">{l s='Current search index words' mod='searchoptimizer'}</h4>
				<select multiple="multiple" id="availableList" style="width: 300px; height: 200px;">
				</select>
			</div>
			<div class="col-lg-6">
				<h4 style="margin-top:5px;">{l s='Words removed from search index' mod='searchoptimizer'}</h4>
				<p><select multiple="multiple" id="wordsToRemoveList" style="width: 300px; height: 200px;">
				</select></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6"><a href="#" id="removeItems" class="btn btn-default"><i class="icon-angle-right"></i> {l s='Remove' mod='searchoptimizer'}</a>
			<a href="#" id="removeAllItems" class="btn btn-default"><i class="icon-angle-double-right"></i> {l s='Remove All' mod='searchoptimizer'}</a></div>
			<div class="col-lg-6"><a href="#" id="addItems" class="btn btn-default"><i class="icon-angle-left"></i> {l s='Add' mod='searchoptimizer'}</a>
			<a href="#" id="addAllItems" class="btn btn-default"><i class="icon-angle-double-left"></i> {l s='Add All' mod='searchoptimizer'}</a></div>
		</div>
		<br/>
		<div class="row"><h4 style="margin-top:5px;">{l s='Search result' mod='searchoptimizer'}:</h4><div id="products">{l s='No results' mod='searchoptimizer'}</div></div>
		<br/>
    {elseif $input.type == 'search_management'}
		<div class="row">
			<div class="col-lg-6">
				<h4 style="margin-top:5px;">{l s='Searches recorded' mod='searchoptimizer'}</h4>
				<select multiple="multiple" id="searchList" style="width: 300px; height: 200px;">
				</select>
			</div>
			<div class="col-lg-6">
				<h4 style="margin-top:5px;">{l s='Search result' mod='searchoptimizer'}</h4>
				<p><select multiple="multiple" id="searchResultList" style="width: 300px; height: 200px;">
				</select></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6"><a href="#" id="removeSearch" class="btn btn-default"><i class="icon-trash-o"></i> {l s='Remove Selected Searches' mod='searchoptimizer'}</a>
			</div>
		</div>
		<br/>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
