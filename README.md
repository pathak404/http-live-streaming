
# HTTP Live Streaming

![PHP >= 7.2](https://img.shields.io/badge/PHP-%3E%3D7.2-787CB5)
![ffmpeg - N-107944-g4fce3bab64-20220830](https://img.shields.io/badge/ffmpeg-N--107944--g4fce3bab64--20220830-378B3B)


HLS (HTTP Live Streaming) is a streaming protocol developed by Apple Inc. It is widely used for delivering live and on-demand multimedia content over the internet. HLS breaks the content into small, manageable chunks and segments them into files. These segments are then delivered to the client devices via HTTP. The media files in HLS are typically encoded in H.264 for video and AAC for audio.









## Features
- Automatic chunks creation on video upload without blocking request.
- Encrypted link generation
- Dynamically switch between different quality levels based on the viewer's network conditions
- Enables the use of Content Delivery Networks (CDNs)
- Analytics and Ad Insertion
## Installation

- Install ffmpeg, setup system path
- Must be accessable via `ffmpeg` command
- Clone the project

```bash
  git clone https://github.com/pathak404/http-live-streaming
```
- Move the folder to server's root
- Entry point - `public/index.php`
