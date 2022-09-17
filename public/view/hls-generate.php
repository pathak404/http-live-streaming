<?php

//* Don't access this file directly
defined('ABSPATH') or die();

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Streaming\FFMpeg;

$log = new Logger('FFmpeg_Streaming');
$log->pushHandler(new StreamHandler('./view/logs/ffmpeg-streaming.log')); // path to log file
    
$ffmpeg = FFMpeg::create($ffmpegConfig, $log);

$video = $ffmpeg->open($fullVideoPath);

$video->hls()
    ->x264()
    ->autoGenerateRepresentations()
    ->save($targetDir."data.m3u8");

unlink($fullVideoPath);