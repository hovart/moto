/*
 * DC jQuery Vertical Accordion Menu - jQuery vertical accordion menu plugin
 * Copyright (c) 2011 Design Chemical
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 */

(function($){

	$.fn.dcAccordion = function(options) {

		//set default options 
		var defaults = {
			classParent	 : 'tptnresp-parent',
			classActive	 : 'active',
			classArrow	 : 'tglr',
			classExpand	 : 'tptnresp-current-parent',
			classDisable : '',
			eventType	 : 'click',
			autoClose    : false,
			autoExpand	 : false,
			speed        : 'fast',
			disableLink	 : true
		};

		//call in the default otions
		var options = $.extend(defaults, options);

		this.each(function(options){

			var obj = this;
			$objSub = $('li > ul',obj);
			if(defaults.classDisable){
				$objSub = $('li:not(.'+defaults.classDisable+') > ul',obj);
			}
			
			classActive = defaults.classActive;
			
			setUpAccordion();

			if(defaults.autoExpand == true){
				$('li.'+defaults.classExpand+' > a').addClass(classActive);
			}
			resetAccordion();

			$('.tglr').click(function(e){

				if ($(this).hasClass('fa-plus') ) {
					$(this).removeClass('fa-plus');
					$(this).addClass('fa-minus');
				} else {
					$(this).removeClass('fa-minus');
					$(this).addClass('fa-plus');
				}

				$activeLi = $(this).parent('li');
				$parentsLi = $activeLi.parents('li');
				$parentsUl = $activeLi.parents('ul');

				// Prevent browsing to link if has child links
				if(defaults.disableLink == true){
					if($(this).siblings('ul').length >0){
						e.preventDefault();
					}
				}

				// Auto close sibling menus
				if(defaults.autoClose == true){
					autoCloseAccordion($parentsLi, $parentsUl);
				}

				if ($('> ul',$activeLi).is(':visible')){
					$('ul',$activeLi).slideUp(defaults.speed);
					$('a',$activeLi).removeClass(classActive);
					$('.tglr',$activeLi).removeClass(classActive);
				} else {
					$(this).siblings('ul').slideToggle(defaults.speed);
					$('> a',$activeLi).addClass(classActive);
					$('> .tglr',$activeLi).addClass(classActive);
				}
				
			});

			// Set up accordion
			function setUpAccordion(){

				$arrow = '<i class="fa fa-plus '+defaults.classArrow+'"></i>';
				var classParentLi = defaults.classParent+'-li';
				$objSub.show();
				$('li',obj).each(function(){
					if($('> ul',this).length > 0){
						$(this).addClass(classParentLi).prepend($arrow);
						$('> a',this).addClass(defaults.classParent);
					}
				});
				$objSub.hide();
				if(defaults.classDisable){
					$('li.'+defaults.classDisable+' > ul').show();
				}
				
			}
			

		// Auto-Close Open Menu Items
		function autoCloseAccordion($parentsLi, $parentsUl){
			$('ul',obj).not($parentsUl).slideUp(defaults.speed);
			// Reset active links
			$('a',obj).removeClass(classActive);
			$('> a',$parentsLi).addClass(classActive);
		}
		// Reset accordion using active links
		function resetAccordion(){
			$objSub.hide();
			var $parentsLi = $('a.'+classActive,obj).parents('li');
			$('> a',$parentsLi).addClass(classActive);
			$allActiveLi = $('a.'+classActive,obj);
			$($allActiveLi).siblings('ul').show();
		}
		});

	};
})(jQuery);

$(function() {
	jQuery('#tptnmobilemenu').dcAccordion(); 
	
});