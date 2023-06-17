<?php 
//* Don't access this file directly
defined('ABSPATH') or die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nothing found</title>
  <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body{
      width: 100%;
      height: 100vh;
      max-height: 100vh;
      display: grid;
      place-items: center;
    }
    div.container {
      width: 100%;
      max-width: 1200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    h1 {
      font-family: "Verdana", sans-serif;
      font-size: 3.5rem;
    }
    .mb {
      margin-bottom: 1.5rem;
    }
    @media only screen and (max-width: 500px) {
      h1 {
        position: relative;
        top: -4.5rem;
        font-size: 2.2rem;
      }
    }
  </style>
</head>
<body>
    <div class="container">
      <h1 class="mb">Hi 👋</h1>
      <p class="mb">Check out these pages...</p>
      <p class="mb"><a href="/p/upload">Upload a video</a></p>
      <p class="mb"><a href="/p/link">Get link of video</a></p>
    </div>
    
</body>
</html>