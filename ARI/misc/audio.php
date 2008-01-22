<?php

/**
 * @file
 * plays recording file
 */



if (isset($_GET['recording'])) {

  chdir("..");
  include_once("./includes/bootstrap.php");

  global $ARI_CRYPT_PASSWORD;

  $crypt = new Crypt();

  $path = $crypt->decrypt($_GET['recording'],$ARI_CRYPT_PASSWORD);

  // strip ".." from path for security
  $path = preg_replace('/\.\./','',$path);
  
  // See if the file exists
  if (!is_file($path)) { die("<b>404 File not found!</b>"); }

  // Gather relevent info about file
  $size = filesize($path);
  $name = basename($path);
  $extension = strtolower(substr(strrchr($name,"."),1));

  // This will set the Content-Type to the appropriate setting for the file
  $ctype ='';
  switch( $extension ) {
    case "mp3": $ctype="audio/mpeg"; break;
    case "wav": $ctype="audio/x-wav"; break;
    case "Wav": $ctype="audio/x-wav"; break;
    case "WAV": $ctype="audio/x-wav"; break;
    case "gsm": $ctype="audio/x-gsm"; break;

    // not downloadable
    default: die("<b>404 File not found!</b>"); break ;
  }

  // need to check if file is mislabeled or a liar.
  $fp=fopen($path, "rb");
  if ($size && $ctype && $fp) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: wav file");
    header("Content-Type: " . $ctype);
    header("Content-Disposition: attachment; filename=" . $name);
    header("Content-Transfer-Encoding: binary");
    header("Content-length: " . $size);
    fpassthru($fp);
  } 
}

?>