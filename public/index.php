<?php

define("ABSPATH", true);
require "../vendor/autoload.php";

define("ROOT_DIR", dirname(__DIR__));

// url = p/pagename
if (isset($_GET["p"]) && !empty($_GET["p"])) {

    // verify admin cookie
    if (MODE == "live") {
        if (isset($_COOKIE["_2bc_admin"])) {
            $cookie = str_decryptaesgcm($_COOKIE["_2bc_admin"], "\$abhi%@2bcCookie/");
            if ($cookie != "2bytecode@AbhiAdmin") {
                exit("not matched");
            }
        } else {
            exit("doesn't exist");
        }
    }



    if ($_GET["p"] == "upload") {
        require ROOT_DIR.'/view/upload.php';
        exit();
    } else if ($_GET["p"] == "handle") {
        require ROOT_DIR.'/view/handle.php';
        exit();
    } else if ($_GET["p"] == "link") {
        require ROOT_DIR.'/view/link.php';
        exit();
    } else {
        http_response_code(404);
        exit();
    }
} else if (isset($_GET["d"]) && !empty($_GET["d"])) {
    // url = d/video-path-enc

    // user cookie verify
    if (MODE == "live") {

        if ( isset($_COOKIE["_2bc_user"]) && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://2bytecode.in/" ) {
            $cookie = str_decryptaesgcm($_COOKIE["_2bc_user"], "\$abhi%@2bcCookie/");
            if ($cookie != "2bytecode@AbhiUser") {
                exit("not matched");
            }
            getVideoTemplate();
        }
        else if (isset($_COOKIE["_2bc_admin"])) {
            $cookie = str_decryptaesgcm($_COOKIE["_2bc_admin"], "\$abhi%@2bcCookie/");
            if ($cookie != "2bytecode@AbhiAdmin") {
                exit("not matched");
            }
            getVideoTemplate();
        }else{
            die("{'status': 'Error', 'message': 'c not found'}");
        }
    }

    if(MODE == "dev"){
        getVideoTemplate();
    }

} else {
    require ROOT_DIR.'/view/index-data.php';
}



function getVideoTemplate()
{
    $videoPath = decryptURLData($_GET["d"]);
    if (file_exists($videoPath)) {
        require(ROOT_DIR."/view/video.php");
    } else {
        die("{'status': 'Error', 'message': 'file not found'}");
    }
}