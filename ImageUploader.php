<?php

$base = $_REQUEST['image'];

$filePath = getcwd()."/images";

$type = $_REQUEST['imageType'];

$municipalityID = $_REQUEST['municipalityID'];

$requestDTO = json_decode($_REQUEST['requestDTO']);

$typeImage = "";

$filename = $_REQUEST['filename'];



//header('Content-Type: bitmap; charset=utf-8');

switch ($type1) {
    case 1:
        $typeImage = "issueImages/" . $municipalityID;
        break;
    case 2:
        $typeImage = 'flyers/' . $municipalityID;
        break;
    case 3:
        $typeImage = 'flyers/' . $municipalityID . '/minutes';
        break;
}

$filePath = $filePath . '/' . $typeImage;

for ($i = 0; $i < 3; $i++) {
	$binary = base64_decode($base[$i]);
    if (!file_exists($filePath)) {
		echo $filePath;
        mkdir($filePath, 0777,true);
		chmod($filePath, 0777);
    }
	//echo 'Image upload complete, Please check your php file directory '.$filePath;
    $file = fopen($filePath . '/' . $filename[$i], 'wb');
// Create File
    fwrite($file, $binary);
}
fclose($file);

?>