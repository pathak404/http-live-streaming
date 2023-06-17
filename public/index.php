<?php

define("ABSPATH", true);
define("APP_DIR", dirname(__DIR__)."/app");
define("ROOT_DIR", __DIR__);
require APP_DIR."/vendor/autoload.php";


$page = (isset($_GET["p"]) && !empty($_GET["p"]) ? filter_input(INPUT_GET, "p", FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE) : (isset($_GET["v"]) && !empty($_GET["v"]) ? "v" : "/"));
// url = p/pagename
if($page == "/"){
    require APP_DIR."/view/index-data.php";
}
else if (file_exists(APP_DIR."/view/{$page}.php")) {
    require APP_DIR."/view/{$page}.php";
} else if ($page == "v") {
    // url = v/video-path-enc
    $path = decryptURLData($_GET["v"]);
    require(APP_DIR."/view/video.php");
}else{
    http_response_code(404);
}
