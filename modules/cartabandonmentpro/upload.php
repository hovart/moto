<?php
function upload($index,$destination,$maxsize=false,$extensions=FALSE)
{
   //Test1: fichier correctement uploadé
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
   //Test2: taille limite
    if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
   //Test3: extension
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
    if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
   //Déplacement
    if(move_uploaded_file($_FILES[$index]['tmp_name'],$destination.$_FILES[$index]['name']))
		return $_FILES[$index]['name'];
	else
		return false;
}

echo upload('image', 'uploads/', false, array('png','gif','jpg','jpeg'));