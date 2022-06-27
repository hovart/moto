{**
* 2015 Aretmic
*
* NOTICE OF LICENSE 
* 
* ARETMIC the Company grants to each customer who buys a virtual product license to use, and non-exclusive and worldwide. This license is 
* valid only once for a single e-commerce store. No assignment of rights is hereby granted by the Company to the Customer. It is also 
* forbidden for the * Customer to resell or use on other virtual shops Products made by ARETMIC. This restriction includes all resources 
* provided with the virtual product. 
*
* @author    Aretmic SA
* @copyright 2015 Aretmic SA
* @license   ARETMIC
* International Registered Trademark & Property of Aretmic SA
*}
{if isset($page_name) && $page_name != $contactform}

<!-- Block contact  -->



<!-- Modal -->
<div class="modal  fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="row-fluid">
                    <div class="col-md-6 hidden-xs leftCol">
                        <h2>
                            {if $lang_iso == de}
                                Unser Kundenservice ruft <br> Sie gerne zurück um Ihre Fragen zu <span class="color">beantworten</span>
                            {else}
                                Nous serons heureux<br> de vous <span class="color">rappeler</span>
                            {/if}
                        </h2>

                        <img class="img-responsive" src="/img/sav.jpg" >
                        <p>
                            {if $lang_iso == de}Wir <span class="color">rufen</span> Sie <br>auch gerne zurück
                            {else}
                                Notre <span class="color">service client </span>vous rappelle pour répondre à vos demandes
                            {/if}
                        </p>
                    </div>
                    <div class="col-md-6 col-xs-12 rightCol">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        <div class="modal-body">
                            <div id="contactform_block" class="block">
                                <h2 class="visible-xs-block">
                                    {if $lang_iso == de}
                                        Unser Kundenservice ruft <br> Sie gerne zurück um Ihre Fragen zu <span class="color">beantworten</span>
                                    {else}
                                        Nous serons heureux<br> de vous <span class="color">rappeler</span>
                                    {/if}
                                </h2>
                                {$forms|escape:'quotes':'UTF-8'}
                            </div>
                        </div>

                    </div>
                </div>

        </div>
    </div>
</div>

{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
	{literal}
	<script type="text/javascript">
/*
        $.uniform.defaults.fileDefaultHtml = "{/literal}{$nofile|escape:'html':'UTF-8'}{literal}";
        $.uniform.defaults.fileButtonHtml = "{/literal}{$choosefile|escape:'html':'UTF-8'}{literal}";
*/
    </script>
	{/literal}
{/if}
{/if}
<!-- Block ContactForm -->