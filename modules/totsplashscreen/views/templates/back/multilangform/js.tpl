{literal}
<script type="text/javascript" src="../js/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript" src="../js/tinymce.inc.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/advimage/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/advlink/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/contextmenu/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/fullscreen/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/inlinepopups/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/media/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/pagebreak/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/paste/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/preview/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/style/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/table/editor_plugin.js"></script>
<script type="text/javascript" src="../js/tiny_mce/plugins/xhtmlxtras/editor_plugin.js"></script>
<script type="text/javascript">
ad={/literal}"{$admin_link}"{literal};
if(typeof changeLanguage == 'function'){
	id_language_old = new Array();
	id_language_started = {/literal}{$default_lang}{literal};
	function changeLanguage(field, fieldsString, id_language_new, iso_code){
	     $("." + fieldsString + "_" + id_language_started).css("display", "none");
	     if( id_language_old[""+field+""] != "" )
	          $("." + fieldsString + "_" + id_language_old[""+field+""]).css("display", "none");
	     $("." + fieldsString + "_" + id_language_new).css("display", "block");
	     $("#language_current_" + fieldsString).attr("src", "../img/l/" + id_language_new + ".jpg");
	     $("#languages_" + field).css("display", "none");
	     id_language_old[""+field+""] = id_language_new;
	}
}
/* Pour les textarea en tinymce */
config = {
	mode : "specific_textareas",
		skin:"cirkuit",
		theme : "advanced",
		
		editor_selector : "rte",
		editor_deselector : "noEditor",
		plugins : "safari,pagebreak,style,table,advimage,advlink,inlinepopups,media,contextmenu,paste,fullscreen,xhtmlxtras,preview",
		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor, media, fullscreen",
	//	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,|,ltr,rtl,|",
	//	theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,pagebreak",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",	
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		document_base_url : ad,
		width: "600",
		height: "auto",
		font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
		elements : "nourlconvert,ajaxfilemanager",
		file_browser_callback : "ajaxfilemanager",
		entity_encoding: "raw",
		convert_urls : false,
		language : {/literal}'{$iso}'{literal}
}


$(function () {
	tinyMCE.init(config);
});
</script>
{/literal}
