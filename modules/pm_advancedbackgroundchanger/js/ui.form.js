// JavaScript Document


$.widget("ui.form",{
		 _init:function(){
			 var object = this;
			 var form = this.element;
			 var inputs = form.find("input , select ,textarea");

			  form.find("fieldset").addClass("ui-widget-content");
			  form.find("legend").addClass("ui-widget-header ui-corner-all");
			  form.addClass("ui-widget");

			  $jqPm.each(inputs,function(){
				$jqPm(this).addClass('ui-state-default ui-corner-all');
				$jqPm(this).wrap("<label />");

				if($jqPm(this).is(":reset ,:submit"))
				object.buttons(this);
				else if($jqPm(this).is(":checkbox"))
				object.checkboxes(this);
				else if($jqPm(this).is("input[type='text']")||$jqPm(this).is("textarea")||$jqPm(this).is("input[type='password']"))
				object.textelements(this);
				else if($jqPm(this).is(":radio"))
				object.radio(this);
				else if($jqPm(this).is("select"))
				object.selector(this);

				if($jqPm(this).hasClass("date"))
				{
					$jqPm(this).datepicker();


				}


				});
			  var div = jQuery("<div />",{
							   css:{
							   width:20,height:20,
							   margin:10,textAlign:"center"
							   }

							   }).addClass("ui-state-default drag");
			  var no = Math.ceil(Math.random() * 4);
			  var holder = jQuery("<div />",{
								  id:'droppable',
								  text:"Drop the box with "+no+" here",
								 css:{
									 width:100,height:100,float:'right',fontWeight:'bold'}
								  }).addClass('ui-state-default');
			   $jqPm(form).find("fieldset").append(holder);
			  for(var i=1;i<5;i++)
				{

					$jqPm(form).find("fieldset").append(div.clone().html(i).attr("id",i));

				}


			   $jqPm(".drag").draggable({containment: 'parent'});
			   $jqPm("#droppable").droppable({
			accept:'#'+no,
			drop: function(event, ui) {
				$jqPm(this).addClass('ui-state-highlight').html("Right One");

				form.find(":submit").removeClass('ui-state-disabled').unbind('click');
				}
		});
			 $jqPm(".hover").hover(function(){
						  $jqPm(this).addClass("ui-state-hover");
						   },function(){
						  $jqPm(this).removeClass("ui-state-hover");
						   });

			 },
		 textelements:function(element){

			$jqPm(element).bind({

 			  focusin: function() {
 			   $jqPm(this).toggleClass('ui-state-focus');
 				 },
			   focusout: function() {
 			    $jqPm(this).toggleClass('ui-state-focus');
 				 }
			  });

			 },
		 buttons:function(element)
		 {
			if($jqPm(element).is(":submit"))
			{
				$jqPm(element).addClass("ui-priority-primary ui-corner-all ui-state-disabled hover");
			 $jqPm(element).bind("click",function(event)
			   {
				   event.preventDefault();
			   });
			}
			else if($jqPm(element).is(":reset"))
			$jqPm(element).addClass("ui-priority-secondary ui-corner-all hover");
			$jqPm(element).bind('mousedown mouseup', function() {
 			   $jqPm(this).toggleClass('ui-state-active');
 				 }

			  );
		 },

		 checkboxes:function(element){
		  $jqPm(element).parent("label").after("<span />");
		  var parent =  $jqPm(element).parent("label").next();
			 $jqPm(element).addClass("ui-helper-hidden");
				parent.css({width:16,height:16,display:"block"});

				 parent.wrap("<span class='ui-state-default ui-corner-all' style='display:inline-block;width:16px;height:16px;margin-right:5px;'/>");

			 parent.parent().addClass('hover');


			 parent.parent("span").click(function(event){
						 $jqPm(this).toggleClass("ui-state-active");
						 parent.toggleClass("ui-icon ui-icon-check");
						$jqPm(element).click();

						});

			 },
		 radio:function(element){
			   $jqPm(element).parent("label").after("<span />");
		  var parent =  $jqPm(element).parent("label").next();
			 $jqPm(element).addClass("ui-helper-hidden");
			 parent.addClass("ui-icon ui-icon-radio-off");
				 parent.wrap("<span class='ui-state-default ui-corner-all' style='display:inline-block;width:16px;height:16px;margin-right:5px;'/>");

			 parent.parent().addClass('hover');


			 parent.parent("span").click(function(event){
						 $jqPm(this).toggleClass("ui-state-active");
						 parent.toggleClass("ui-icon-radio-off ui-icon-bullet");
						$jqPm(element).click();

						});
			 },
			 selector:function(element){
				 var parent = $jqPm(element).parent();
				 parent.css({"display":"block",width:140,height:21}).addClass("ui-state-default ui-corner-all");
				  $jqPm(element).addClass("ui-helper-hidden");
				 parent.append("<span id='labeltext' style='float:left;'></span><span style='float:right;display:inline-block' class='ui-icon ui-icon-triangle-1-s' ></span>");
				 parent.after("<ul class=' ui-helper-reset ui-widget-content ui-helper-hidden' style='position:absolute;z-index:50;width:140px;' ></ul>");
				 $jqPm.each($jqPm(element).find("option"),function(){

													  $jqPm(parent).next("ul").append("<li class='hover'>"+$jqPm(this).html()+"</li>");

													   });
				 $jqPm(parent).next("ul").find("li").click(function(){ $jqPm("#labeltext").html($jqPm(this).html());
																							$jqPm(element).val($jqPm(this).html());

																									 });

				 $jqPm(parent).click(function(event){ $jqPm(this).next().slideToggle('fast');
												 event.preventDefault();
																		});

				}


		 });
