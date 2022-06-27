/*
* 2002-2015 TemplateMonster
*
* TemplateMonster Mega Menu
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2015 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

var responsiveflagTMMenu = false;
var TmCategoryMenu = $('ul.menu');
var TmCategoryGrover = $('.top_menu .menu-title');

$(document).ready(function(){
	TmCategoryMenu = $('ul.menu');
	TmCategoryGrover = $('.top_menu .menu-title');
	setColumnClean();
	responsiveTmMenu();
	$(window).resize(responsiveTmMenu);

	/**
	 * Hack menu temporaire
	 * */
	//MainMenu

	var flip = 0;
	$( ".menu-title.tmmegamenu_item" ).click(function() {
		//your code that shows the menus fully
		$(".top_menu .menu.clearfix.top-level-menu").toggle( flip++ % 2 === 0 );


		//Casques
		$( ".it_86243289.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").show( "slow" );
			$(".it_51199960.menu-mobile").hide();
			$(".it_87565369.menu-mobile").hide();
			$(".it_07846372.menu-mobile").hide();
			$(".it_59280706.menu-mobile").hide();
			$(".it_09493380").hide();

		});
		//Eqpt motard
		$( ".it_51199960.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").hide();
			$(".it_51199960.menu-mobile").show( "slow" );
			$(".it_87565369.menu-mobile").hide();
			$(".it_07846372.menu-mobile").hide();
			$(".it_59280706.menu-mobile").hide();
			$(".it_09493380").hide();

		});
		//Accessoires et pieces
		$( ".it_87565369.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").hide();
			$(".it_51199960.menu-mobile").hide();
			$(".it_87565369.menu-mobile").show( "slow" );
			$(".it_07846372.menu-mobile").hide();
			$(".it_59280706.menu-mobile").hide();
			$(".it_09493380").hide();

		});
		//Scooter
		$( ".it_07846372.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").hide();
			$(".it_51199960.menu-mobile").hide();
			$(".it_87565369.menu-mobile").hide();
			$(".it_07846372.menu-mobile").show( "slow" );
			$(".it_59280706.menu-mobile").hide();
			$(".it_09493380").hide();

		});
		//Tout terrain
		$( ".it_59280706.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").hide();
			$(".it_51199960.menu-mobile").hide();
			$(".it_87565369.menu-mobile").hide();
			$(".it_07846372.menu-mobile").hide();
			$(".it_59280706.menu-mobile").show( "slow" );
			$(".it_09493380").hide();

		});
		$( ".it_09493380.top-level-menu-li" ).click(function() {
			$(".it_86243289.menu-mobile").hide();
			$(".it_51199960.menu-mobile").hide();
			$(".it_87565369.menu-mobile").hide();
			$(".it_07846372.menu-mobile").hide();
			$(".it_59280706.menu-mobile").hide();
			$(".it_09493380").show( "slow" );
		});



		//now set up an event listener so that clicking anywhere outside will close the menu
		$('html').click(function(event) {
			//check up the tree of the click target to check whether user has clicked outside of menu
			if ($(event.target).parents('.menu-title.tmmegamenu_item').length==0) {
				// your code to hide menu

				//this event listener has done its job so we can unbind it.
				$(this).unbind(event);
			}

		})
	});










});

// check resolution
function responsiveTmMenu()
{
   if ($(document).width() <= 1023 && responsiveflagTMMenu == false)
	{
		menuChange('enable');
		responsiveflagTMMenu = true;
	}
	else if ($(document).width() >= 1024)
	{
		menuChange('disable');
		responsiveflagTMMenu = false;
	}
}

function TmdesktopInit()
{
	TmCategoryGrover.off();
	TmCategoryGrover.removeClass('active');
	$('.menu > li > ul, .menu > li > ul.is-simplemenu ul, .menu > li > div.is-megamenu').removeClass('menu-mobile').parent().find('.menu-mobile-grover').remove();
	$('.menu').removeAttr('style');
	TmCategoryMenu.superfish('init');
	//add class for width define
	$('.menu > li > ul').addClass('submenu-container clearfix'); 
}

function TmmobileInit()
{
	var TmclickEventType=((document.ontouchstart!==null)?'click':'touchstart');
	TmCategoryMenu.superfish('destroy');
	$('.menu').removeAttr('style');

	TmCategoryGrover.on(TmclickEventType, function(e){
		$(this).toggleClass('active').parent().find('ul.menu').stop().slideToggle('medium');
		return false;
	});

	$('.menu > li > ul, .menu > li > div.is-megamenu, .menu > li > ul.is-simplemenu ul').addClass('menu-mobile clearfix').parent().prepend('<span class="menu-mobile-grover"></span>');

	$(".menu .menu-mobile-grover").on(TmclickEventType, function(e){
		var catSubUl = $(this).next().next('.menu-mobile');
		if (catSubUl.is(':hidden'))
		{
			catSubUl.slideDown();
			$(this).addClass('active');
		}
		else
		{
			catSubUl.slideUp();
			$(this).removeClass('active');
		}
		return false;
	});

	$('.top_menu > ul:first > li > a, .block_content > ul:first > li > a').on(TmclickEventType, function(e){



		var parentOffset = $(this).prev().offset(); 
	   	var relX = parentOffset.left - e.pageX;
		if ($(this).parent('li').find('ul').length && relX >= 0 && relX <= 20)
		{
			e.preventDefault();
			var mobCatSubUl = $(this).next('.menu-mobile');
			var mobMenuGrover = $(this).prev();
			if (mobCatSubUl.is(':hidden'))
			{
				mobCatSubUl.slideDown();
				mobMenuGrover.addClass('active');
			}
			else
			{
				mobCatSubUl.slideUp();
				mobMenuGrover.removeClass('active');
			}
		}
	});
}

// change the menu display at different resolutions
function menuChange(status)
{
	status == 'enable' ? TmmobileInit(): TmdesktopInit();
}
function setColumnClean()
{
	$('.menu div.is-megamenu > div').each(function(){
		i = 1;
       	$(this).children('.megamenu-col').each(function(index, element) {
           if(i % 3 == 0)
		   {
                $(this).addClass('first-in-line-sm');
		   }
			i++; 
        });


});
}
