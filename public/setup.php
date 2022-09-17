<?php
$is_set = file_get_contents("./view/logs/setup-status.log");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            width: 100%;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form {
            padding: 20px;
            width: 100%;
            height: auto;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 10px;
        }
    </style>
</head>
<body>



<?php 

if(!isset($_POST["submit"]) && !$is_set): ?>
<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>">
    <p>Web address:</p>
    <input type="text" name="web" require>
    <span style="color:#717376; margin-bottom: 2rem;">Eg: https://example.com</span>
    <input type="submit" name="submit" value="Submit">
</form>
<?php endif; ?>




<?php

if($is_set){
    echo "<h1 style='color:#00A300'>Set up done.</h1>";
}


function htaccess_data($web){
return 
'RewriteEngine On
Options -Indexes

RewriteRule ^p/([^/]+)?$ index.php?p=$1 [L,QSA]

RewriteCond %{REQUEST_URI} \.(mp4|m4v|avi|ts|m3u8|log)$ [NC]
RewriteCond %{HTTP_REFERER} !^'.$web.'.*$ [NC,OR]
RewriteCond %{HTTP_REFERER} !^'.$web.'.*$ [NC]
RewriteRule ^.* - [F,L]

<IfModule mod_headers.c>
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set X-Content-Type-Options "nosniff"
Header set Cache-Control "no-cache, no-store, must-revalidate"
Header set Pragma "no-cache"
Header set Expires 0
Header set Access-Control-Allow-Origin "'.explode("//", $web)[1].'"
</IfModule>

<Files .htaccess>
Order allow,deny
Deny from all
</Files>';

}


if(isset($_POST["submit"]) && !$is_set){

    $file = fopen(".htaccess", "w+");
    fwrite($file, htaccess_data($_POST["web"]));
    fclose($file);
    
    file_put_contents("./view/logs/setup-status.log", 1);

    echo "<h1 style='color:#00A300'>Success</h1>";
}


?>

</body>
</html>