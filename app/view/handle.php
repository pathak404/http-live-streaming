<?php

//* Don't access this file directly
defined('ABSPATH') or die();


$returnData = new stdClass();

// save file
if(isset($_POST["action"]) && $_POST["action"] == "save"){

$courseDir =  $_POST["dir"];
$topicDir =  $_POST["topic"];
$lessonDir = $_POST["lesson"];

$relativeDir = "/.data/".$courseDir."/".$topicDir."/". $lessonDir."/";
$targetDir = ROOT_DIR."/.data/".$courseDir."/".$topicDir."/". $lessonDir."/";

// check dir exist
if(!file_exists($targetDir)){
    mkdir($targetDir, 0777, true);
}else{
    array_map( 'unlink', array_filter((array) glob($targetDir."*") ) );
}

$fileFullName = $_FILES['videofile']['name'];
$fullVideoPath = $targetDir.$fileFullName;

if( move_uploaded_file( $_FILES["videofile"]["tmp_name"], $fullVideoPath  ) ){
    $returnData->status = "success";
    $returnData->message = generateURL($relativeDir.$fileFullName);
    // $returnData->message = getCompleteURL($relativeDir.$fileFullName);
}else{
    $returnData->status = "error";
    $returnData->message = "Unable to save file";
}




// link generate
}else if(isset($_POST["action"]) && $_POST["action"] == "link"){
    

    if( !empty($_POST["dir"]) && $_POST["topic"] == "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null"  ){
        $returnData->target = 1;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR."/.data/".$_POST["dir"]."/") as $single) {
            if($single == "." || $single == "..") continue;
            if(is_dir(ROOT_DIR."/.data/".$_POST["dir"]."/".$single)) array_push($arrayDirs, $single);
        }
        $returnData->options = $arrayDirs;

    }else if( !empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null" ){
        $returnData->target = 2;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/") as $single) {
            if($single == "." || $single == "..") continue;
            if(is_dir(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$single)) array_push($arrayDirs, $single);
        }
        $returnData->options = $arrayDirs;

    }else if( !empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] == "null" ){
        $returnData->target = 3;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/") as $single) {
            if($single == "." || $single == "..") continue;
            if(is_dir(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/".$single)){
                foreach(scandir(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/".$single) as $file){
                    if($file == "." || $file == "..") continue;
                    array_push($arrayDirs, $single."/".$file);
                }
            }
            if(is_file(ROOT_DIR."/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/".$single)){
                array_push($arrayDirs, $single);
            }  
        }
        $returnData->options = $arrayDirs;
    }
    else if( !empty($_POST["dir"]) && isset($_POST["topic"]) && isset($_POST["lesson"]) && isset($_POST["videofile"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] != "null" ) {
        $returnData->target = 0;
        $fullVideoPath = "/.data/".$_POST["dir"]."/".$_POST["topic"]."/".$_POST["lesson"]."/".$_POST["videofile"];
        $returnData->message = generateURL($fullVideoPath);
        // $returnData->message = getCompleteURL($fullVideoPath);
    }
}

echo json_encode($returnData);
