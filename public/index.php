<?php

define("ABSPATH", true);
define("APP_DIR", dirname(__DIR__)."/app");
define("ROOT_DIR", __DIR__);

require APP_DIR."/vendor/autoload.php";



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
        require APP_DIR.'/view/upload.php';
        exit();
    } else if ($_GET["p"] == "handle") {
        require APP_DIR.'/view/handle.php';
        exit();
    } else if ($_GET["p"] == "link") {
        require APP_DIR.'/view/link.php';
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
    require APP_DIR.'/view/index-data.php';
}



function getVideoTemplate()
{
    // $videoPath = decryptURLData($_GET["d"]);
    $videoPath = $_GET["d"];
    if (file_exists(ROOT_DIR.$videoPath)) {
        require(APP_DIR."/view/video.php");
    } else {
        die("{'status': 'Error', 'message': 'file not found'}");
    }
}