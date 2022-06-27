/**
 *
 * PM_JsCoreFunctions
 *
 * @author    Presta-Module.com <support@presta-module.com>
 * @copyright Presta-Module 2011
 * @version   0.0.1
 *
 *************************************
 ** JS Core Functions For PM Addons  *
 **   http://www.presta-module.com   *
 **             V 0.0.1              *
 *************************************
 * + Description: Js Core Functions For PM Addons
 * + Languages: EN, FR
 **/


function switchBgPositionChoice() {
		var val = parseInt($jqPm('#position_way').val());
		var pos = $jqPm('#pm_bg_position');
		var per = $jqPm('#pm_bg_per') ;
		var px = $jqPm('#pm_bg_px') ;
		var free = $jqPm('#pm_bg_free') ;
		
		pos.hide('medium');
		per.hide('medium');
		px.hide('medium');
		free.hide('medium');
			
		if(val==1 )			
			pos.slideToggle('medium');
		
		if(val==2)
			per.slideToggle('medium');
			
		if(val==3)
			px.slideToggle('medium');

		if(val==4)
			free.slideToggle('medium');
			
}

(function(){
	var old = $jqPm.ui.dialog.prototype._create;
	$jqPm.ui.dialog.prototype._create = function(d){
		old.call(this, d);

		var self = this;
		var options = self.options,
		oldHeight = options.height,
		oldWidth = options.width;
		fixDialogSize(self,options,oldHeight,oldWidth);
		$jqPm(window).unbind('resize.uidialog').bind('resize.uidialog', function() {
			fixDialogSize(self,options,oldHeight,oldWidth);
		});
	};
})();

function fixDialogSize(self,options,oldHeight,oldWidth) {

	var fitHeight = options.fitHeight,
	screenHeight 	= $jqPm(window).height(),
	screenWidth 	= $jqPm(window).width(),
	dialogHeight	= options.height,
	dialogWidth		= options.width;

	if(!fitHeight && (screenHeight < oldHeight)) {
		fitHeight = true;
	}else if(!fitHeight && (dialogHeight < oldHeight) && (screenHeight < oldHeight)) {
		$jqPm(self).dialog( "option", "height",  screenHeight);
	}

	uiDialogTitlebarFull = $jqPm('<a href="#"><span class="ui-icon ui-icon-newwin"></span></a>')
		.addClass(
			'ui-dialog-titlebar-full ' +
			'ui-corner-all'
		)
		.attr('role', 'button')
		.hover(
			function() {
				uiDialogTitlebarFull.addClass('ui-state-hover');
			},
			function() {
				uiDialogTitlebarFull.removeClass('ui-state-hover');
			}
		)
		.toggle(
			function() {
				self._setOptions({
					height : window.innerHeight - 10,
					width : oldWidth
				});
				self._position('center');
				return false;
			},
			function() {
				self._setOptions({
					height : oldHeight,
					width : oldWidth
				});
				self._position('center');
				return false;
			}
		)
		.focus(function() {
			uiDialogTitlebarFull.addClass('ui-state-focus');
		})
		.blur(function() {
			uiDialogTitlebarFull.removeClass('ui-state-focus');
		})
		.appendTo(self.uiDialogTitlebar),

		uiDialogTitlebarFullText = $jqPm('<span></span>')
			.addClass(
				'ui-icon ' +
				'ui-icon-newwin'
			)
			.text(options.fullText);
	if(fitHeight) {
		self._setOptions({
			height : window.innerHeight - 10,
			width : oldWidth
		});
		self._position('center');
	}
	self._position('center');
}

function hideNextIfTrue(e) {
	var val = parseInt($jqPm(e).val());
	if (val) {
		$jqPm(e).parent('.margin-form').next('div').slideUp('fast');
	} else {
		$jqPm(e).parent('.margin-form').next('div').slideDown('fast');
	}
}
function showNextIfTrue(e) {
	var val = parseInt($jqPm(e).val());
	if (val) {
		showNext(e);
	} else {
		hideNext(e);
	}
}
function showNext(e) {
	$jqPm(e).parent('.margin-form').next('div').slideDown('fast');
}
function hideNext(e) {
	$jqPm(e).parent('.margin-form').next('div').slideUp('fast');
}
function showSpanIfChecked(e, idToShow) {
	var val = $jqPm(e).attr('checked');
	if (val) {
		$jqPm(idToShow).css('display', 'inline');
	} else {
		$jqPm(idToShow).hide();
	}
}

var dialogIframe;
function openDialogIframe(url,dialogWidth,dialogHeight,fitScreenHeight) {
	$jqPm('body').css('overflow','hidden');
	dialogIframe = $jqPm('<iframe class="dialogIFrame" frameborder="0" marginheight="0" marginwidth="0" src="'+url+'"></iframe>').dialog({
		bgiframe: true,
		modal: true,
		width:dialogWidth,
		height:dialogHeight,
		fitHeight:(typeof(fitScreenHeight)!='undefined' && fitScreenHeight ? true:false),
		close: function(event, ui) {$jqPm('body').css('overflow','auto'); },
		open: function (event,ui) {$jqPm(this).css('width','97%');}
	});
}
function closeDialogIframe() {
	$jqPm(dialogIframe).dialog("close");
}

var dialogInline;
function openDialogInline(contentId,dialogWidth,dialogHeight,fitScreenHeight) {
	$jqPm('body').css('overflow','hidden');
	dialogInline = $jqPm(contentId).dialog({
		modal: true,
		width:dialogWidth,
		height:dialogHeight,
		fitHeight:(typeof(fitScreenHeight)!='undefined' && fitScreenHeight ? true:false),
		close: function(event, ui) {$jqPm('body').css('overflow','auto'); },
		open: function (event,ui) {$jqPm(this).css('width','97%');}
	});
}
function reloadPanel(idPanel) {
	var url = $jqPm('#'+idPanel).attr('rel');
	if(!url) show_info('','Attribute rel is not set for panel '+idPanel);
	$jqPm('#'+idPanel).load(url, function(response, status, xhr) {
		if (status == "error") {
		    return;
		}
	});
}
function loadPanel(idPanel,url) {
	$jqPm('#'+idPanel).attr('rel',url);
	reloadPanel(idPanel);
}

function show_info(content) {
	if(content == '')return;
	$jqPm.jGrowl(content,{ themeState: 'ui-state-highlight' });
}
function show_error(content) {
	$jqPm.jGrowl(content,{ /*sticky: true,*/ themeState: 'ui-state-error' });
}
function objectToarray (o,e) {
	a = new Array;
	for (var i=1; i<o.length; i++) {
		a.push(parseInt(o[i][e]));
	}
	return a;
}

function loadAjaxLink() {
	$jqPm('.ajax_script_load').die('click').live('click', function() {
		if($jqPm(this).hasClass('pm_confirm') && !confirm($jqPm(this).attr('title'))) return false;
		if($jqPm(this).next('.progressbar_wrapper').length) {
			var curLink = $jqPm(this);
			$jqPm(curLink).hide();
			var  progressbar = $jqPm(this).next('.progressbar_wrapper').children('.progressbar');
			$jqPm(progressbar).progressbar({
				value: 0,
				complete: function(event, ui) { clearInterval(progressbarInterval);$jqPm(progressbar).text("");$jqPm(this).progressbar( "destroy" );$jqPm(curLink).show();}
			});
			var progressbarInterval = setInterval(function() {
				if($jqPm(progressbar).progressbar('value') == 99) $jqPm(progressbar).progressbar('value',0);
				$jqPm(progressbar).progressbar('value',$jqPm(progressbar).progressbar('value') + 9);
			}, 100);
		}
		var rel = $jqPm(this).attr('rel');
		if(rel) {
			var relSplit = rel.split(/_/g);
			if(relSplit[0] == 'tab') {
				var tabId = '#'+relSplit[1];
				var tabIndex = parseInt(relSplit[2]);
				$jqPm(tabId).unbind( "tabsload").bind( "tabsload", function(event, ui) {
					$jqPm(window).scrollTo("#wrapConfigTab",1000);
				});
				//alert($jqPm(this).attr('href'));
				$jqPm(tabId).tabs( "url" , tabIndex , $jqPm(this).attr('href') );
				$jqPm(tabId).tabs( "load" , tabIndex );
				return false;
			}
		}
		$.ajax( {
			type : "GET",
			url : $jqPm(this).attr('href'),
			dataType : "script",
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//alert(msgAjaxError);
			}
		});
		return false;
	});
	$jqPm('.open_on_dialog_iframe').die('click').live('click', function() {
		var rel = $jqPm(this).attr('rel');
		var dialogWidth = 900;
		var dialogHeight = 600;
		var dialogFixHeight = false;
		if(rel) {
			var relSplit = rel.split(/_/g);
			if(typeof(relSplit[0]) != 'undefined' && typeof(relSplit[1]) != 'undefined') {
				dialogWidth = relSplit[0];
				dialogHeight = relSplit[1];
				dialogFixHeight = (typeof(relSplit[2])!='undefined' && relSplit[2] ? true:false);
			}
		}

		openDialogIframe($jqPm(this).attr('href'),dialogWidth,dialogHeight,dialogFixHeight);
		return false;
	});
	$jqPm('.ajax_load_on_blc').die('click').live('click', function() {
		var rel = $jqPm(this).attr('rel');
		if(rel) {
			loadPanel(rel,$jqPm(this).attr('href'));
		}
		return false;
	});

	$jqPm('.hide_class_show_id').die('click').live('click', function() {
		var e = $jqPm(this);
		var rel = $jqPm(e).attr('rel');

		if(rel) {
			var relSplit = rel.split(/@/g);
			if(typeof(relSplit[0]) != 'undefined' && typeof(relSplit[1]) != 'undefined') {
				class_to_hide = relSplit[0];
				id_to_show = relSplit[1];
			}
			//Hide other block
			$jqPm('.'+class_to_hide).slideUp('fast',function() {
				//Show selected block
				$jqPm('#'+id_to_show).slideDown('fast');
			});
		}
		return false;
	});
}
function bindFillNextSize() {
	$jqPm('.fill_next_size').unbind('click').click(function() {
		$jqPm(this).nextAll('input[type=text]').val($jqPm(this).prev('input[type=text]').val());
	})
}
function initMakeGradient() {
	$jqPm('.makeGradient').unbind('click').click(function() {
		var e = $jqPm(this).parent('span').prev('span');
		if($jqPm(e).css('display') == 'inline')
			$jqPm(this).parent('span').prev('span').hide();
		else
			$jqPm(this).parent('span').prev('span').show();
	});
}
function checkChildrenCheckbox(e) {
	if($jqPm(e).children('input[type=checkbox]:checked').length)
		$jqPm(e).children('input[type=checkbox]').removeAttr('checked');
	else
		$jqPm(e).children('input[type=checkbox]').attr('checked','checked');
}
function unCheckAllChildrenCheckbox(e) {
	$jqPm(e).find('input[type=checkbox]').removeAttr('checked');
}
function display(message) {
	$jqPm.jGrowl(message, { sticky: true });
}
function hide(message) {
	$jqPm.jGrowl('close');
}
function initTips(e) {
	$jqPm(document).ready(function() {
		$jqPm(e+"-tips").tipTip();
	});
}
$jqPm(document).ready(function() {
	loadAjaxLink();
	$jqPm(".pm_tips").tipTip();
	$jqPm( ".pm_datepicker" ).each(function() {
		$jqPm(this).datepicker({
			dateFormat: $jqPm( this ).attr('rel')
		});
	});
	$jqPm('link[href$="js/jquery/datepicker/datepicker.css"]').remove();
	$jqPm('div#addons-rating-container p.dismiss a').click(function() {
		$jqPm('div#addons-rating-container').hide(500);
		$jqPm.ajax({type : "GET", url : window.location+'&dismissRating=1' });
		return false;
	});
});