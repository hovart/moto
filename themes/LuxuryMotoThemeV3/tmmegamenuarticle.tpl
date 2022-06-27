
<style >
	.panel-heading-selector{
		display: block;
		width: 100%;
	}
    .priceSize{
        font-size:42px;
        color: #c80909;
        font-family: 'GiorgioSans-Medium';
        letter-spacing: 0.03em;
        font-weight: normal;
    }
    .imgSize{
        width: 50%;
    }
.price{

    position: absolute;
    top: 50%;
    right: 0px;
    transform: translateY(-50%);

}
.panel-body >.row{
	position: relative;
}
    .searcharguments{
        font:inherit;
        color:#535353 !important;
        font-size:18px !important;
        
    }
    .productName{
        font-size:36px;
        font-family:'GiorgioSans-Bold' !important;
        color:#302f2f !important;
    }
</style>


<div class="row align-left rte" >
	<div class="col-xs-12 align-left" >
		<h1 class="page-heading product-listing"> 
			{l s='research' }
		</h1>
	</div>
	<hr>
	<div class="col-xs-12 " style=" ">
		{if isset($reception) }
        <p  class="searcharguments"> {l s='Reception' } :  {$reception}</p>

		{/if}

		{if isset($mark) }
        <p class="searcharguments">{l s='Mark ' } :  {$mark}</p>

		{/if}
		
		{if isset($cylinder) }
		  <p class="searcharguments">  {l s='Cylinder  '} :   {$cylinder}</p>
		{/if}

		{if isset($model) }
		  <p class="searcharguments">  {l s='model ' } :   {$model}</p>
		{/if}

		{if isset($year) }
		  <p class="searcharguments">  {l s='Year  ' } :   {$year}</p>
		{/if}
	</div>

	<div class="container ">
			<div class="row">
			<div class="col-xs-12 " style="margin-top: 20px;" >
				{if isset($articlesByCategorys) }
					{foreach from=$articlesByCategorys  key=key item=articlesByCategory}
							<div class="panel panel-default">
							    <div class="panel-heading">
							        <h4 class="panel-title">
							            <a data-toggle="collapse"  style="font-size:20px;" sdata-parent="#acco rdion" href="#{str_replace(' ' ,'', $key)}" class="panel-heading-selector"><i class="fa fa-minus"> &nbsp;</i>{$key}</a>
							            <span class="pull-right panel-collapse-clickable" data-toggle="collapse" data-parent="#accordion" href="#{str_replace(' ' ,'', $key)}">
							                
							            </span>
							        </h4>
							    </div>

							    <div id="{str_replace(' ' ,'', $key)}" class="panel-collapse panel-collapse collapse">


					        		<div class="panel-body" >
					        		
										{foreach from=$articlesByCategory  item=article}
												<div class="row " >
                                                    <div class="col-xs-6 col-sm-3 ">
														{if isset($article['images'])}
																<a href="{$base_dir}{$lang_iso}/{$article['products']->category}/{$article['products']->id}-{$article['products']->link_rewrite}.html">
																	<img class="imgSize" src="{$article['images']}">
																</a>																 
														{/if}
													</div>
												   <div class=" col-xs-6 col-sm-3 product-field">
													 <a  class="productName" href="{$base_dir}{$lang_iso}/{$article['products']->category}/{$article['products']->id}-{$article['products']->link_rewrite}.html">{$article['products']->name}</a>
												 	</div>	
													
													<div class="col-xs-6 col-sm-3 searcharguments product-field" style="font-size:20px;">
														{$article['products']->description_short}
													</div>
													<div class="col-xs-6 col-sm-3 price product-field">
														<span class="priceSize">
															{convertPriceWithCurrency price=$article['products']->getPrice(true, $smarty.const.NULL, 2) currency=$currencies.$cookie->id_currency  convert=true}
                                                        </span>
													</div>										
												</div>
											<hr>
										{/foreach}
									</div>
							    </div>
							</div>
					{/foreach}
					
				{else}

                <div  class="searcharguments"> {l s='Nothing found with this filter parameters:' }</div>
				{/if}
			</div>
	    </div>
	</div>
    <!--/panel-group-->
</div>

</div>
<script>

// accordion pluse minus goes here    	  
   jQuery(document).on("click",'.panel-heading-selector',function(){
   	var that= jQuery(this);
   	  that.children("i").toggleClass("fa-minus").toggleClass("fa-plus"); 
   		});
</script>