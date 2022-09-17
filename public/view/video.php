<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://vjs.zencdn.net/7.20.2/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.20.2/video.min.js"></script>

    <link href="//players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.css" rel="stylesheet">
    <script src="//players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.min.js"></script>
</head>
<style>
    * {
        padding: 0;
        margin: 0;
    }
</style>

<body>

    <video id="video-js" class="video-js vjs-default-skin vjs-big-play-centered" controlslist="nodownload" preload="auto" controls>
        <source src="<?php echo $videoPath ?>" type="application/x-mpegURL">
        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>

    <script>
        var player = videojs('video-js', {
            responsive: true,
            playbackRates: [0.25, 0.5, 1, 1.5, 2],
            plugins: {
                qualityMenu: {
                    useResolutionLabels: true
                }
            }
        });
        player.qualityMenu();
    </script>

</body>

</html>