/*
 * DC Mega Menu - jQuery mega menu
 * Copyright (c) 2011 Design Chemical
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 */
(function($){

	//define the defaults for the plugin and how to call it	
	$.fn.dcMegaMenu = function(options){
		//set default options  
		var defaults = {
			classParent: 'tptn-mega',
			classContainer: 'sub-container',
			classSubParent: 'mega-hdr',
			classSubLink: 'mega-hdr',
			classWidget: 'tptn-extra',
			rowItems: 6,
			speed: 'fast',
			effect: 'fade',
			fullWidth: true,
			onLoad : function(){},
            beforeOpen : function(){},
			beforeClose: function(){}
		};

		//call in the default otions
		var options = $.extend(defaults, options);
		var $dcMegaMenuObj = this;

		//act upon the element that is passed into the design    
		return $dcMegaMenuObj.each(function(options){

			var clSubParent = defaults.classSubParent;
			var clSubLink = defaults.classSubLink;
			var clParent = defaults.classParent;
			var clContainer = defaults.classContainer;
			var clWidget = defaults.classWidget;
			
			megaSetup();
			
			function megaOver(){
				var subNav = $('.sub',this);
				$(this).addClass('mega-hover');
				$(subNav).fadeIn(defaults.speed);
				
				// beforeOpen callback;
				defaults.beforeOpen.call(this);
			}
			function megaAction(obj){
				var subNav = $('.sub',obj);
				$(obj).addClass('mega-hover');
				$(subNav).fadeIn(defaults.speed);
								
				// beforeOpen callback;
				defaults.beforeOpen.call(this);
			}
			function megaOut(){
				var subNav = $('.sub',this);
				$(this).removeClass('mega-hover');
				$(subNav).hide();
				// beforeClose callback;
				defaults.beforeClose.call(this);
			}
			function megaActionClose(obj){
				var subNav = $('.sub',obj);
				$(obj).removeClass('mega-hover');
				$(subNav).hide();
				// beforeClose callback;
				defaults.beforeClose.call(this);
			}
			function megaReset(){
				$('li',$dcMegaMenuObj).removeClass('mega-hover');
				$('.sub',$dcMegaMenuObj).hide();
			}

			function megaSetup(){
				var clParentLi = clParent+'-li';
				var menuWidth = $dcMegaMenuObj.outerWidth();
				$('> li',$dcMegaMenuObj).each(function(){
					//Set Width of sub
					var $mainSub = $('> ul',this);
					var $primaryLink = $('> a',this);
					if($mainSub.length){
						$primaryLink.addClass(clParent);
						$mainSub.addClass('sub').wrap('<div class="'+clContainer+'" />');
						
						var pos = $(this).position();
						pl = pos.left;
							
						if($('ul',$mainSub).length){
							$(this).addClass(clParentLi);
							$('.'+clContainer,this).addClass('mega');
							$('> li',$mainSub).each(function(){
								if(!$(this).hasClass(clWidget)){
									$(this).addClass('mega-unit');
									if($('> ul',this).length){
										$(this).addClass(clSubParent);
										$('> a',this).addClass(clSubParent+'-a');
									} else {
										$(this).addClass(clSubLink);
										$('> a',this).addClass(clSubLink+'-a');
									}
								}
							});

							// Create Rows
							var hdrs = $('.mega-unit',this);
							rowSize = parseInt(defaults.rowItems);
							for(var i = 0; i < hdrs.length; i+=rowSize){
								hdrs.slice(i, i+rowSize).wrapAll('<div class="tptn-mega-row" />');
							}

							// Get Sub Dimensions & Set Row Height
							$mainSub.show();
							
							// Get Position of Parent Item
							var pw = $(this).width();
							var pr = pl + pw;
							
							// Check available right margin
							var mr = menuWidth - pr;
							
							// // Calc Width of Sub Menu
							var subw = $mainSub.outerWidth();
							var totw = $mainSub.parent('.'+clContainer).outerWidth();
							var cpad = totw - subw;
							
							if(defaults.fullWidth == true){
								var fw = menuWidth - cpad;
								$mainSub.parent('.'+clContainer).css({width: fw+'px'});
								$dcMegaMenuObj.addClass('full-width');
							}
							var iw = $('.mega-unit',$mainSub).outerWidth(true);
							var rowItems = $('.tptn-mega-row:eq(0) .mega-unit',$mainSub).length;
							var inneriw = iw * rowItems;
							var totiw = inneriw + cpad;
							
							// Set mega header height
							$('.tptn-mega-row',this).each(function(){
								$('.mega-unit:last',this).addClass('last');
								var maxValue = undefined;
								$('.mega-unit > a',this).each(function(){
									var val = parseInt($(this).height());
									if (maxValue === undefined || maxValue < val){
										maxValue = val;
									}
								});
								$('.mega-unit > a',this).css('height',maxValue+'px');
								//$(this).css('width',inneriw+'px');
							});
							
							// Calc Required Left Margin incl additional required for right align
							
							if(defaults.fullWidth == true){
								params = {left: '15px'};
							} else {
								
								var ml = mr < ml ? ml + ml - mr : (totiw - pw)/2;
								var subLeft = pl - ml;

								// If Left Position Is Negative Set To Left Margin
								var params = {left: pl+'px', marginLeft: -ml+'px'};
								
								if(subLeft < 0){
									params = {left: 0};
								}else if(mr < ml){
									params = {right: 0};
								}
							}
							$('.'+clContainer,this).css(params);
							
							// Calculate Row Height
							$('.tptn-mega-row',$mainSub).each(function(){
								var rh = $(this).height();
								$('.mega-unit',this).css({height: rh+'px'});
								$(this).parent('.tptn-mega-row').css({height: rh+'px'});
							});
							$mainSub.hide();
					
						} else {
							$('.'+clContainer,this).addClass('non-mega').css('left','0');
						}
					}
				});
				// Set position of mega dropdown to bottom of main menu
				var menuHeight = $('> li > a',$dcMegaMenuObj).outerHeight(true);
				$('.'+clContainer,$dcMegaMenuObj).css({top: menuHeight+'px'}).css('z-index','1000');
				
				// HoverIntent Configuration
				var config = {
					sensitivity: 2,
					interval: 0,
					over: megaOver,
					timeout: 0,
					out: megaOut
				};
				$('li',$dcMegaMenuObj).hoverIntent(config);

				// onLoad callback;
				defaults.onLoad.call(this);
			}
		});
	};
})(jQuery);

jQuery(function(){
	jQuery('#mega-menu-1').dcMegaMenu();
});