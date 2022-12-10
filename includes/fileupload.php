<?php
 $formData = $_POST['formData'];

 /** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
        define('ABSPATH', explode('wp-content',dirname(__FILE__))[0]);


/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-load.php';
 if ( ! function_exists( 'wp_handle_upload' ) ) {require_once  ABSPATH . 'wp-admin/includes/file.php';}

 if(isset($_POST['originalUrl'])){
    
     $upload_dir  = wp_upload_dir();
     
     $upload_url  = $upload_dir['url'];
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

//	$img             = str_replace( 'data:image/png;base64,', '',$_POST['formDatabase'] );
//	$img             = str_replace( ' ', '+', $img );
  //  $decoded         = base64_decode( $img );
    $filename        = 'print.png';
  //  $file_type       = 'image/png';
	$hashed_filename = md5( 'fy' . microtime() ) . '_fy'.$filename;
    
  //  $upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
    
    require 'SimpleImage.php';
error_reporting(0);
try {
  // Create a new  object
  $image = new \claviska\SimpleImage();
  $image2	 = new \claviska\SimpleImage();
  $layer2 = $image2->fromFile($_POST['userFile'])->resize($_POST['userFileWidth'],$_POST['userFileHeight'])->rotate($_POST['angle'])->autoOrient();    //src image

    
  $image->fromFile($_POST['originalUrl'])->resize($_POST['originalWidth'],$_POST['originalHeight'])->autoOrient()  
    ->overlay($layer2,'top left',1,$_POST['imageLeft'],$_POST['imageTop'])
    ->toFile($upload_path . $hashed_filename, 'image/png');  

}
 catch(Exception $err) {
  // Handle errors
	
  echo $err->getMessage();
}
    
    echo $upload_url.'/'. $hashed_filename;
   
     exit;
 }
 
$uploadedfile = $_FILES['formData'];
$upload_overrides = array( 'test_form' => false );

 $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

if ( $movefile ) {
    
    echo $movefile['url']; 
} else {
    echo "Possible file upload attack!\n";
}
 
?>