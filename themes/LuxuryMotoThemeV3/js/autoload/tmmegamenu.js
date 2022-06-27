
$(function(){
var i=0;
   
	$(document).on('keyup','#reception_by_type_input',function(){
        var inputVal = $('#reception_by_type_input').val()
			 if($.trim(inputVal)==''){
                 $('#menuFilterButton').attr("disabled","disabled");
               
             }else{
                  $('#menuFilterButton').removeAttr("disabled");
             }
  	});
	
    
            $(document).on('change','#year' , function(){
              $("#tmmegamenuSearchForm").submit();
               });
	  

//		$('#marks').chosen();	
//        $('#cylinder').chosen();       
//        $('#model').chosen();       
//        $('#year').chosen();       

		$(document).on('change','#marks',function(){
              if(!$('#marks').val())
              {
                $('#cylinder').attr("disabled","disabled");
                $('#cylinder').html("");
                $('#cylinder').trigger("chosen:updated");
                $('#model').attr("disabled","disabled");
                $('#model').html("");
                $('#model').trigger("chosen:updated");
                $('#year').attr("disabled","disabled");
                $('#year').html("");
                $('#year').trigger("chosen:updated");
                if($('#reception_by_type_input').val()==''){
                 $('#menuFilterButton').attr("disabled","disabled");}
                  return;
              }

	        var mark= $('#marks').find(":selected").val();
	     	$('#cylinder').removeAttr("disabled");
	     	$('#model').find(':selected').removeAttr('selected','selected');
	     	$('#model').attr('disabled','disabled');
             	$('#year').find(':selected').removeAttr('selected','selected');
	     	$('#year').attr('disabled','disabled');
          

				var cylinderAjax=$.ajax({
					type : 'GET',
					dataType: "html",
					url : baseDir+'override/modules/tmmegamenu/ajax.php',
					data : {
						//required parameters
					     method : 'getCylinder',
					     mark		:  mark,	

					},
					// success:function(d){
					// 	console.log(d);
					// },
				
				});

					cylinderAjax.done(function(options){
					 	$("#cylinder").html(options);
                        $('#cylinder').removeAttr("disabled");
                        $('#cylinder').trigger("chosen:updated");	
                    });
		});
		
	$(document).on('change','#cylinder',function(){
            if(!$('#cylinder').val())
              {
                $('#model').attr("disabled","disabled");
                $('#model').html("");
                $('#model').trigger("chosen:updated");
                $('#year').attr("disabled","disabled");
                $('#year').html("");
                $('#year').trigger("chosen:updated");
                   if($('#reception_by_type_input').val()==''){
                 $('#menuFilterButton').attr("disabled","disabled");}
                  return;
              }

	        var mark= $('#marks').find(":selected").val();
	        var cylinder= $('#cylinder').find(":selected").val();
	     	$('#model').find(':selected').removeAttr('selected','selected');
	     	$('#year').attr('disabled','disabled');

				var modelAjax=$.ajax({
					type : 'GET',
					dataType: "html",
					url : baseDir+'override/modules/tmmegamenu/ajax.php',
					data : {
						//required parameters
					     method : 'getModels',
					     mark		:  mark,	
					     cylinder   :cylinder,
					},
				});

					modelAjax.done(function(options){
					 	$("#model").html(options);
					 	$('#model').removeAttr("disabled");
                        $('#model').trigger("chosen:updated");	
					 });
	});

	$(document).on('change','#model',function(){
           
            var cylinder = $('#cylinder').find(":selected").val();
            var mark     = $('#marks').find(":selected").val();
            var model    = $('#model').find(":selected").val();
          if(!$('#model').val())
              {
                
                $('#year').html("");
                $('#year').attr("disabled","disabled");
                $('#year').trigger("chosen:updated");
                   if($('#reception_by_type_input').val()==''){
                 $('#menuFilterButton').attr("disabled","disabled");}
                  return;
              }
        
            var yearAjax=$.ajax({
			type : 'GET',
			dataType: "html",
			url : baseDir+'override/modules/tmmegamenu/ajax.php',
			data : {
				//required parameters
			     method     : 'getYears',
			     mark		:  mark,	
			     cylinder   :  cylinder,
			     model      :  model,
			
			},
		
		});
		yearAjax.done(function(options){
            $("#year").html(options);
            $('#year').removeAttr("disabled");
            $('#year').trigger("chosen:updated");	


		});
    });
});    
