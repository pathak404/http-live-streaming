<?php

//* Don't access this file directly
defined('ABSPATH') or die();

$returnData = new stdClass();

// save file
if(isset($_POST["action"]) && $_POST["action"] == "save"){

$courseDir =  $_POST["dir"];
$topicDir =  $_POST["topic"];
$lessonDir = $_POST["lesson"];
$fileExt = pathinfo($_FILES['videofile']['name'], PATHINFO_EXTENSION);
$filename = pathinfo($_FILES['videofile']['name'], PATHINFO_FILENAME);

$targetDir = "./.data/".$courseDir."/".$topicDir."/". $lessonDir."/";

// check dir exist
if(!file_exists($targetDir)){
    mkdir($targetDir, 0777, true);
}else{
    array_map( 'unlink', array_filter((array) glob($targetDir."*") ) );
}

$fullVideoPath = $targetDir."data.m3u8";
$tempVideoPath = $_FILES["videofile"]["tmp_name"];

require("./view/hls-generate.php");
$returnData->status = "success";
$returnData->message = generateURL($fullVideoPath);




// link generate
}else if(isset($_POST["action"]) && $_POST["action"] == "link"){
    

    if( !empty($_POST["dir"]) && $_POST["topic"] == "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null"  ){
        $returnData->target = 1;

        $scannedDirs = glob('.data/'.$_POST["dir"].'/*', GLOB_ONLYDIR);
        $arrayDirs = array();
        foreach ($scannedDirs as $single){
            array_push( $arrayDirs, explode("/", $single, 3)[2]);
        }
        $returnData->options = $arrayDirs;
        

    }else if( !empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null" ){
        $returnData->target = 2;

        $scannedDirs = glob('.data/'.$_POST["dir"]."/".$_POST["topic"].'/*', GLOB_ONLYDIR);
        $arrayDirs = array();
        foreach ($scannedDirs as $single){
            array_push( $arrayDirs, explode("/", $single, 4)[3]);
        }
        $returnData->options = $arrayDirs;

    }else if( !empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] == "null" ){
        $returnData->target = 3;
        $scannedDirs = glob('.data/'.$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"].'/*.*');
        $arrayDirs = array();
        foreach ($scannedDirs as $single){
            array_push( $arrayDirs, explode("/", $single, 5)[4]);
        }
        $returnData->options = $arrayDirs;
    }
    else if( !empty($_POST["dir"]) && isset($_POST["topic"]) && isset($_POST["lesson"]) && isset($_POST["videofile"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] != "null" ) {
        $returnData->target = 0;
        $fullVideoPath = "./.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/".$_POST["videofile"];
        $returnData->message = generateURL($fullVideoPath);
    }

}

echo json_encode($returnData);
