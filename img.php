<?php

if(true){
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}

include "sql.php";

$ent = $_GET["id"];
updateMail($ent);

header("Content-type: image/png");

$width=1;
$height=1;


$img = imagecreatetruecolor($width, $height);

$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);
imagesavealpha($img, true);
imagepng($img);
imagedestroy($img);

?>
