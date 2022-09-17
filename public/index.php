<?php

define("ABSPATH", true);
require "./vendor/autoload.php";


// url = p/pagename
if (isset($_GET["p"]) && !empty($_GET["p"])) {

    // verify admin cookie
    if (MODE == "live") {
        if (isset($_COOKIE["_2bc_admin"])) {
            $cookie = str_decryptaesgcm($_COOKIE["_2bc_admin"], "\$abhi%@2bcCookie/", "base64");
            if ($cookie != "2bytecode@AbhiAdmin") {
                exit("not matched");
            }
        } else {
            exit("doesn't exist");
        }
    }



    if ($_GET["p"] == "upload") {
        require './view/upload.php';
        exit();
    } else if ($_GET["p"] == "handle") {
        require './view/handle.php';
        exit();
    } else if ($_GET["p"] == "link") {
        require './view/link.php';
        exit();
    } else {
        http_response_code(404);
        exit();
    }
} else if (isset($_GET["d"]) && !empty($_GET["d"])) {
    // url = ?d=video-path-enc

    // user cookie verify
    if (MODE == "live") {
        if (isset($_COOKIE["_2bc_user"])) {
            $cookie = str_decryptaesgcm($_COOKIE["_2bc_user"], "\$abhi%@2bcCookie/", "base64");
            if ($cookie != "2bytecode@AbhiUser") {
                exit("not matched");
            }
        } else {
            exit("doesn't exist");
        }
    }

    $videoPath = decryptURLData($_GET["d"]);

    if (file_exists($videoPath)) {
        require("./view/video.php");
    } else {
        echo "{'status': 'Error', 'message': 'file not found'}";
    }
    
} else {
    require './view/index-data.php';
}
