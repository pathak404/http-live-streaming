<?php

//* Don't access this file directly
defined('ABSPATH') or die();


$returnData = new stdClass();


function removeDir($dir){
    if(is_dir($dir)){
        $objs = scandir($dir);
        foreach($objs as $obj){
            if($obj != "." && $obj != ".."){
                if(is_dir($dir."/".$obj)){
                    removeDir($dir."/".$obj);
                }else{
                    unlink($dir."/".$obj);
                }
            }
        }
        reset($objs);
        rmdir($dir);
    }
}

// save file
if (isset($_POST["action"]) && $_POST["action"] == "save") {

    $courseDir =  $_POST["dir"];
    $topicDir =  $_POST["topic"];
    $lessonDir = $_POST["lesson"];

    $relativeDir = "/.data/" . $courseDir . "/" . $topicDir . "/" . $lessonDir . "/";
    $targetDir = ROOT_DIR . "/.data/" . $courseDir . "/" . $topicDir . "/" . $lessonDir . "/";

    // if dir exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    } else {
        // array_map('unlink', array_filter((array) glob($targetDir . "*")));
        removeDir($targetDir);
        mkdir($targetDir, 0777, true);
    }



    $fileFullName = $_FILES['videofile']['name'];
    $fullVideoPath = $targetDir . $fileFullName;

    if (move_uploaded_file($_FILES["videofile"]["tmp_name"], $fullVideoPath)) {
        $cmd = 'ffmpeg -i "'.$fileFullName.'" -preset fast -g 150 -sc_threshold 0 -threads 4 -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0? -map 0:v:0 -map 0:a:0 -map 0:v:0 -map 0:a:0 -filter:v:0 scale=h=360:w=-2 -minrate:v:0 138k -maxrate:v:0 400k -bufsize:v:0 552k -b:v:0 276k -filter:v:1 scale=h=480:w=-2 -minrate:v:1 375k -maxrate:v:1 1088k -bufsize:v:1 1500k -b:v:1 750k -filter:v:2 scale=h=720:w=-2 -minrate:v:2 512k -maxrate:v:2 1485k -bufsize:v:2 2048k -b:v:2 1024k -filter:v:3 scale=h=1080:w=-2 -minrate:v:3 800k -maxrate:v:3 1900k -bufsize:v:3 2400k -b:v:3 4096k -var_stream_map "v:0,a:0 v:1,a:1 v:2,a:2 v:3,a:3" -master_pl_name master.m3u8 -f hls -hls_time 10 -segment_time 10 -hls_list_size 0 -segment_format mpegts -hls_playlist_type vod -hls_segment_filename "res-%v/segment-%d.ts" res-%v/playlist.m3u8';
        $cd = "cd ../../public".$relativeDir;
        if(chdir($targetDir)){
            if(PHP_OS === "WINNT"){
                $command = "start /B $cmd > NUL";
                pclose( popen( $command, 'r' ) );
            }else{
                exec("$cmd > /dev/null 2>&1 &");
            }
        }
        $returnData->status = "success";
        $returnData->message = generateURL($relativeDir . $fileFullName);
        // $returnData->message = getCompleteURL($relativeDir.$fileFullName);
    } else {
        $returnData->status = "error";
        $returnData->message = "Unable to save file";
    }

    // link generate
} else if (isset($_POST["action"]) && $_POST["action"] == "link") {

    if (!empty($_POST["dir"]) && $_POST["topic"] == "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null") {
        $returnData->target = 1;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/") as $single) {
            if ($single == "." || $single == "..") continue;
            if (is_dir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $single)) array_push($arrayDirs, $single);
        }
        $returnData->options = $arrayDirs;
    } else if (!empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] == "null" && $_POST["videofile"] == "null") {
        $returnData->target = 2;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/") as $single) {
            if ($single == "." || $single == "..") continue;
            if (is_dir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $single)) array_push($arrayDirs, $single);
        }
        $returnData->options = $arrayDirs;
    } else if (!empty($_POST["dir"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] == "null") {
        $returnData->target = 3;
        $arrayDirs = array();
        foreach (scandir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $_POST["lesson"] . "/") as $single) {
            if ($single == "." || $single == "..") continue;
            if (is_dir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $_POST["lesson"] . "/" . $single)) {
                foreach (scandir(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $_POST["lesson"] . "/" . $single) as $file) {
                    if ($file == "." || $file == "..") continue;
                    array_push($arrayDirs, $single . "/" . $file);
                }
            }
            if (is_file(ROOT_DIR . "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $_POST["lesson"] . "/" . $single)) {
                array_push($arrayDirs, $single);
            }
        }
        $returnData->options = $arrayDirs;
    } else if (!empty($_POST["dir"]) && isset($_POST["topic"]) && isset($_POST["lesson"]) && isset($_POST["videofile"]) && $_POST["topic"] != "null" && $_POST["lesson"] != "null" && $_POST["videofile"] != "null") {
        $returnData->target = 0;
        $fullVideoPath = "/.data/" . $_POST["dir"] . "/" . $_POST["topic"] . "/" . $_POST["lesson"] . "/" . $_POST["videofile"];
        $returnData->message = generateURL($fullVideoPath);
        // $returnData->message = getCompleteURL($fullVideoPath);
    }
}

echo json_encode($returnData);
