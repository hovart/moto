<?php

// Désactiver le rapport d'erreurs
error_reporting(0);

/* Nom du module */
$nomModule = $_GET['module'];
/* Déclaration des variables */
if (isset($_GET['id_user'])) $idUser = $_GET['id_user']; else $idUser = '';
if (isset($_GET['addml'])) $addml = $_GET['addml']; else $addml = '';
if (isset($_GET['refere'])) $adrurl = $_GET['refere']; else $adrurl = '';
if (isset($_GET['majauto'])) $infointeger = $_GET['majauto']; else $infointeger = 1;
if (isset($_GET['infotexte'])) $infotexte = $_GET['infotexte']; else $infotexte = '';

function telechargezip($url_file, $nomFichier) {
	// téléchargement du fichier zip.
	$old_umask = umask(0);
	if (@copy($url_file, "../".$nomFichier)) return true;
	else return false;
}

function unzip($nomModule, $nomFichier) {
	// On décompresse le fichier ZIP.
	$old_umask = umask(0);

  //on inclut la librairie de dézippage :
  if (!class_exists('PclZip',false)) 
  	include('./pclzip.lib.php');
	$archive = new PclZip("../".$nomFichier);
	if ($archive) {
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "../../", //.$nomModule,	
									PCLZIP_OPT_SET_CHMOD, 0755,
									PCLZIP_OPT_REPLACE_NEWER);
  }
} 

function getWebservice($nomModule, $addml, $adrurl, $idUser, $infotexte, $infointeger) {
	// Active mode Debug (true) ou pas (false)
	$debug = false;
	// Désactivation du cache WSDL
	ini_set("soap.wsdl_cache_enabled", "0");  

	$options = array("trace" => $debug);
  $client = new SoapClient("http://maj.aideaunet.com/webservice/majmodule.wsdl", $options); 
  // $client = new SoapClient("http://localhost/webservice/webservice/majmodule.wsdl", $options);
  try { 
  	$valRetour = $client->getUrlZip($nomModule, $addml, $adrurl, $idUser, $infotexte, $infointeger); 
  	if ($debug == true) {
  		// Affiche les requetes si mode debug = True.
			print "<pre>\n"; 
			print "Request :\n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
			print "Response:\n".htmlspecialchars($client->__getLastResponse())."\n"; 
			print "</pre>"; 
		}
  } catch (SoapFault $exception) { 
    $valRetour =  '';      
  } 
  return $valRetour;
}



$nomFichierLocal = "temporaire.zip";
$url_file = getWebservice($nomModule, $addml, $adrurl, $idUser, $infotexte, $infointeger); 

// On vérifie que l'url retournée commence bien par HTTP, sinon il s'agit d'un message d'erreur.
if (substr($url_file,0,7) == "http://") {
	/* On vérifie si c'est une mise à jour automatique */
	if ($infointeger == 1) {
		// Téléchargement du fichier zip.
		$retour = telechargezip($url_file, $nomFichierLocal);

		// On vérifie que le fichier zip est bien présent.
		if ($retour == true) {
			// On décompresse le fichier zip.
			unzip($nomModule, $nomFichierLocal);
	
			// On supprime le fichier zip.
			@unlink("../".$nomFichierLocal);
		}
		header('Location: ' . $_SERVER['HTTP_REFERER'] );
		
	} else { 
		/* Téléchargement de la mise à jour */
		header('Location: ' . $url_file );
	}
} else {
	/* Variable de retour ne commence pas http://, il s'agit d'un message d'erreur. */
	print $url_file;
	print "<p><a href=\"javascript:window.history.go(-1)\">Retour au Module</a></p>";
}

?>


