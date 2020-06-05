<?php
  if (isset($_POST['submit'])) { 
      if (!empty($_FILES['upload']['name'])) { 
	  
	  $ch = curl_init(); 
	  $localfile = $_FILES['upload']['tmp_name']; 
	  $fp = fopen($localfile, 'r'); 
	  
	  curl_setopt($ch, CURLOPT_URL, 'ftp://colegio:0353002@ieandinos.net/'.$_FILES['upload']['name']); 
	  curl_setopt($ch, CURLOPT_UPLOAD, 1); 
	  curl_setopt($ch, CURLOPT_INFILE, $fp); 
	  curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile)); 
	  curl_exec ($ch); 

	  $error_no = curl_errno($ch); 
	  curl_close ($ch); 

	  if ($error_no == 0) { 
	    $error = 'Fichero subido correctamente.'; 
	  } else { 
	    $error = 'Error al subir el fichero.'; 
	  } 
      } else { 
	  $error = 'Seleccione un fichero.'; 
      } 
   }
    echo $error;
?>