<?php
function CopyFile($file_name,$slug) {

$TemplateFileSourceURL = AROMA_DIR . "/pages/" . $file_name; // Address to your file in the plugin directory
$TemplateFileTargetURL = get_stylesheet_directory() . '/page-'.$slug.'.php'; // Note the "page-" prefix, it is necessary for WP to select this file instead of the general "page.php". The name after the prefix must match the slug of the page created in WP. 

if ( !file_exists( $TemplateFileSourceURL ) ) {
  return FALSE;
}

$GetTemplate = file_get_contents( $TemplateFileSourceURL );
if ( !$GetTemplate ) {
  return FALSE;
}

$WriteTemplate = file_put_contents( $TemplateFileTargetURL, $GetTemplate );
if ( !$WriteTemplate ) {
  return FALSE;
}
return TRUE;
}


CopyFile("CRUD_tests_page.php","aroma-tests");
CopyFile("CRUD_answers_page.php","aroma-answers");
