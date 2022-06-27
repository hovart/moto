/*
* 2007-2011 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 8130 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (typeof(treeViewSetting) == 'undefined') {
	var treeViewSetting = new Array();
}

function formatCategoryIdTreeView(category_id) {
	if (typeof(category_id) != 'undefined' && category_id.indexOf("-") != -1)
		category_id = category_id.substring(0, category_id.indexOf("-"));
	return parseInt(category_id);
}

function loadTreeView(inputName, categoryRootId, selectedCategories, selectedLabel, home, use_radio) {
	treeViewSetting[inputName] = new Array();
	treeViewSetting[inputName]['categoryRootId'] = categoryRootId;
	treeViewSetting[inputName]['selectedCategories'] = selectedCategories;
	treeViewSetting[inputName]['selectedLabel'] = selectedLabel;
	treeViewSetting[inputName]['readyToExpand'] = true;
	treeViewSetting[inputName]['categoryBoxName'] = 'categoryBox[]';
	treeViewSetting[inputName]['needCheckAll'] = false;
	treeViewSetting[inputName]['needUncheckAll'] = false;
	treeViewSetting[inputName]['arrayCatToExpand'] = new Array();
	treeViewSetting[inputName]['id'] = 0;
	treeViewSetting[inputName]['interval'] = null;
	treeViewSetting[inputName]['home'] = home;
	treeViewSetting[inputName]['use_radio'] = use_radio;
	treeViewSetting[inputName]['selector'] = '#categories-treeview-'+inputName;
	treeViewSetting[inputName]['selector'] = treeViewSetting[inputName]['selector'].replace('[', '').replace(']', '');
	
	treeViewSetting[inputName]['inputNameSelector'] = inputName.replace('[', '').replace(']', '');
	
	$jqPm(document).ready(function() {
		$jqPm(treeViewSetting[inputName]['selector']).treeview({
			inputNameValue: inputName,
			inputNameSelector: inputName.replace('[', '').replace(']', ''),
			categoryRootId: categoryRootId,
			selectedLabel: selectedLabel,
			use_radio: use_radio,
			url : _base_config_url+'&getPanel=getChildrenCategories',
			toggle: function () { callbackToggle($jqPm(this), inputName); },
			ajax : {
				type: 'POST',
				async: true,
				data: { selectedCat: selectedCategories }
			}
		});
		//console.log(treeViewSetting[inputName]['selector'] + ' li#'+categoryRootId+'-'+treeViewSetting[inputName]['inputNameSelector']+' span');

		$jqPm(treeViewSetting[inputName]['selector'] + ' li#'+categoryRootId+'-'+treeViewSetting[inputName]['inputNameSelector']+' span').trigger('click');
		$jqPm(treeViewSetting[inputName]['selector'] + ' li#'+categoryRootId+'-'+treeViewSetting[inputName]['inputNameSelector']).children('div').remove();
		$jqPm(treeViewSetting[inputName]['selector'] + ' li#'+categoryRootId+'-'+treeViewSetting[inputName]['inputNameSelector']).
			removeClass('collapsable lastCollapsable').
			addClass('last static');
		
		//console.log('#expand_all-'+treeViewSetting[inputName]['inputNameSelector']);
		
		$jqPm('#expand_all-'+treeViewSetting[inputName]['inputNameSelector']).click( function () {
			if ($jqPm(this).attr('rel') != '') treeViewSetting[inputName]['categoryBoxName'] = $jqPm(this).attr('rel');
			expandAllCategories(inputName);
			return false;
		});

		$jqPm('#collapse_all-'+treeViewSetting[inputName]['inputNameSelector']).click( function () {
			if ($jqPm(this).attr('rel') != '') treeViewSetting[inputName]['categoryBoxName'] = $jqPm(this).attr('rel');
			collapseAllCategories(inputName);
			return false;
		});

		$jqPm('#check_all-'+treeViewSetting[inputName]['inputNameSelector']).click( function () {
			if ($jqPm(this).attr('rel') != '') treeViewSetting[inputName]['categoryBoxName'] = $jqPm(this).attr('rel');
			treeViewSetting[inputName]['needCheckAll'] = true;
			checkAllCategories(inputName);
			return false;
		});

		$jqPm('#uncheck_all-'+treeViewSetting[inputName]['inputNameSelector']).click( function () {
			if ($jqPm(this).attr('rel') != '') treeViewSetting[inputName]['categoryBoxName'] = $jqPm(this).attr('rel');
			treeViewSetting[inputName]['needUncheckAll'] = true;
			uncheckAllCategories(inputName);
			return false;
		});
	});
}

function callbackToggle(element, inputName) {
	//console.log('[callbackToggle] - ' + inputName);
	
	if (!element.is('.expandable'))
		return false;

	if (element.children('ul').children('li.collapsable').length != 0)
		closeChildrenCategories(element, inputName);
}

function closeChildrenCategories(element, inputName) {
	//console.log('[closeChildrenCategories] - ' + inputName);
	
	var arrayLevel = new Array();

	if (element.children('ul').find('li.collapsable').length == 0) {
		//console.log('rien a fermer');
		return false;
	} else {
		//console.log('ya des choses a fermer');
	}

	element.children('ul').find('li.collapsable').each(function() {
		var level = $jqPm(this).children('span.category_level').html();
		if (arrayLevel[level] == undefined)
			arrayLevel[level] = new Array();

		arrayLevel[level].push(formatCategoryIdTreeView($jqPm(this).attr('id')));
	});

	for(i=arrayLevel.length-1;i!=0;i--)
		if (arrayLevel[i] != undefined)
			for(j=0;j<arrayLevel[i].length;j++)
			{
				$jqPm('li#'+arrayLevel[i][j]+'-'+treeViewSetting[inputName]['inputNameSelector']+'.collapsable').children('span.category_label').trigger('click');
				$jqPm('li#'+arrayLevel[i][j]+'-'+treeViewSetting[inputName]['inputNameSelector']+'.expandable').children('ul').hide();
			}
}

function setCategoryToExpand(inputName) {
	//console.log('[setCategoryToExpand] - ' + inputName);
	var ret = false;

	treeViewSetting[inputName]['id'] = 0;
	treeViewSetting[inputName]['arrayCatToExpand'] = new Array();
	$jqPm(treeViewSetting[inputName]['selector'] + ' li.expandable:visible').each(function() {
		treeViewSetting[inputName]['arrayCatToExpand'].push($jqPm(this).attr('id'));
		ret = true;
	});

	return ret;
}

function needExpandAllCategories(inputName) {
	//console.log('[needExpandAllCategories] - ' + inputName);
	//console.log($jqPm(treeViewSetting[inputName]['selector'] + ' li').is('.expandable'));
	return $jqPm(treeViewSetting[inputName]['selector'] + ' li').is('.expandable');
}

function expandAllCategories(inputName) {
	//console.log('[expandAllCategories] - ' + inputName);
	
	// if no category to expand, no action
	if (!needExpandAllCategories(inputName)) return;
	
	
	// force to open main category
	if ($jqPm('li#'+treeViewSetting[inputName]['categoryRootId']+'-'+treeViewSetting[inputName]['inputNameSelector']).is('.expandable'))
		$jqPm('li#'+treeViewSetting[inputName]['categoryRootId']+'-'+treeViewSetting[inputName]['inputNameSelector']).children('span.folder').trigger('click');
	treeViewSetting[inputName]['readyToExpand'] = true;
	if (setCategoryToExpand(inputName)) {
		treeViewSetting[inputName]['interval'] = setInterval('openCategory("'+inputName+'")', 10);
		//console.log('expand');
	} else {
		//console.log('no expand');
	}
}

function openCategory(inputName) {
	//console.log('[openCategory] - ' + inputName);
	// Check readyToExpand in order to don't clearInterval if AJAX request is in progress
	// readyToExpand = category has been expanded, go to next ;)
	//console.log(treeViewSetting[inputName]['id']);
	//console.log(treeViewSetting[inputName]['arrayCatToExpand']);
	if (treeViewSetting[inputName]['id'] >= treeViewSetting[inputName]['arrayCatToExpand'].length && treeViewSetting[inputName]['readyToExpand']) {
		if (!setCategoryToExpand(inputName)) {
			clearInterval(treeViewSetting[inputName]['interval']);
			// delete interval value
			treeViewSetting[inputName]['interval'] = null;
			treeViewSetting[inputName]['readyToExpand'] = false;
			if (treeViewSetting[inputName]['needCheckAll']) {
				checkAllCategories(inputName);
				treeViewSetting[inputName]['needCheckAll'] = false;
			}
			else if (treeViewSetting[inputName]['needUncheckAll']) {
				uncheckAllCategories(inputName);
				treeViewSetting[inputName]['needUncheckAll'] = false;
			}
		}
		else
			treeViewSetting[inputName]['readyToExpand'] = true;
	}

	if (treeViewSetting[inputName]['readyToExpand']) {
		//console.log('ready to expand');
		//console.log('li#'+treeViewSetting[inputName]['arrayCatToExpand'][treeViewSetting[inputName]['id']]+'.hasChildren');
		if ($jqPm('li#'+treeViewSetting[inputName]['arrayCatToExpand'][treeViewSetting[inputName]['id']]+'.hasChildren').length > 0)
			treeViewSetting[inputName]['readyToExpand'] = false;
		$jqPm('li#'+treeViewSetting[inputName]['arrayCatToExpand'][treeViewSetting[inputName]['id']]+'.expandable:visible span.category_label').trigger('click');
		treeViewSetting[inputName]['id']++;
	}
}

function collapseAllCategories(inputName) {
	//console.log('[collapseAllCategories] - ' + inputName);
	closeChildrenCategories($jqPm('li#'+treeViewSetting[inputName]['categoryRootId']+'-'+treeViewSetting[inputName]['inputNameSelector']), inputName);
}

function checkAllCategories(inputName) {
	//console.log('[checkAllCategories] - ' + inputName);
	
	if (needExpandAllCategories(inputName)) {
		//console.log('need expand');
		expandAllCategories(inputName);
	} else {
		//console.log('do not need expand');
		$jqPm('input[name="'+treeViewSetting[inputName]['categoryBoxName']+'"]').not(':checked').each(function () {
			$jqPm(this).attr('checked', 'checked');
			clickOnCategoryBox($jqPm(this), inputName);
		});
	}
}

function uncheckAllCategories(inputName) {
	//console.log('[uncheckAllCategories] - ' + inputName);
	
	if (needExpandAllCategories(inputName))
		expandAllCategories(inputName);
	else
	{
		$jqPm('input[name="'+treeViewSetting[inputName]['categoryBoxName']+'"]:checked').each(function () {
			$jqPm(this).removeAttr('checked');
			clickOnCategoryBox($jqPm(this), inputName);
		});
	}
}

function clickOnCategoryBox(category, inputName) {
	//console.log('[clickOnCategoryBox] - ' + inputName);
	
	if (category.is(':checked')) {
		$jqPm('select#id_category_default').append('<option value="'+category.val()+'">'+(category.val() != treeViewSetting[inputName]['categoryRootId'] ? category.parent().find('span').html() : treeViewSetting[inputName]['home'])+'</option>');
		updateNbSubCategorySelected(category, true, inputName);
		if ($jqPm('select#id_category_default option').length > 0)
		{
			$jqPm('select#id_category_default').show();
			$jqPm('#no_default_category').hide();
		}
	}
	else
	{
		$jqPm('select#id_category_default option[value='+category.val()+']').remove();
		updateNbSubCategorySelected(category, false, inputName);
		if ($jqPm('select#id_category_default option').length == 0)
		{
			$jqPm('select#id_category_default').hide();
			$jqPm('#no_default_category').show();
		}
	}
}

function updateNbSubCategorySelected(category, add, inputName) {
	//console.log('[updateNbSubCategorySelected] - ' + inputName);
	
	var currentSpan = category.parent().parent().parent().children('.nb_sub_cat_selected');
	var parentNbSubCategorySelected = currentSpan.children('.nb_sub_cat_selected_value').html();

	if (treeViewSetting[inputName]['use_radio']) {
		$jqPm('.nb_sub_cat_selected').hide();
		return false;
	}

	if (add)
		var newValue = parseInt(parentNbSubCategorySelected)+1;
	else
		var newValue = parseInt(parentNbSubCategorySelected)-1;

	currentSpan.children('.nb_sub_cat_selected_value').html(newValue);
	currentSpan.children('.nb_sub_cat_selected_word').html(treeViewSetting[inputName]['selectedLabel']);

	if (newValue == 0)
		currentSpan.hide();
	else
		currentSpan.show();

	if (currentSpan.parent().children('.nb_sub_cat_selected').length != 0)
		updateNbSubCategorySelected(currentSpan.parent().children('input'), add, inputName);
}
function checkChildrenCategory(e, id_category, inputName) {
	if($jqPm(e).attr('checked')) {
		$jqPm('li#'+id_category+'-'+treeViewSetting[inputName]['inputNameSelector']+'.expandable:visible span.category_label').trigger('click');
		treeViewSetting[inputName]['interval'] = setInterval(function() {
			if($jqPm(e).parent('li').children('ul').children('li').children('input:not([value="undefined"]):not(.check_all_children)').length) {
				$jqPm(e).parent('li').children('ul').children('li').children('input:not(.check_all_children)').attr('checked','checked');
				clearInterval(treeViewSetting[inputName]['interval']);
			}
		}, 200);
	}else {
		$jqPm(e).parent('li').children('ul').children('li').children('input:not(.check_all_children)').attr('checked','');
	}
}
