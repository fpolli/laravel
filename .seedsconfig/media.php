<?php

return
  [
    "featured" => [
      "width" => 900,
      "height" => 300,
      "aspect" => '3:1'
    ],
    "content" => [
      "fullWidth" => 900,
      "portraitWidth" => 600,
      "videoResMin" => "720p"
    ],
    "ad" => [
      "maxWidth" => 400,
      "maxHeight" => 400
    ],
    "audio" => [
      "posterWidth" => 480,
      "posterHeight" => 270,
      "posterAspect" => '16:9'
    ],
    "aspect" => [
      "landscape" => 21,
      "portrait" => 13
    ],
    "allowed" => [
      "image" => [
        "png",
        "jpg",
        "jpeg",
        "webp"
      ],
      "audio" => [
        "mp3",
        "m4a"
      ],
      "video" => [
        "mp4",
        "webm",
      ],
      "animated" => [
        "fbx",
        "usd"
      ]
    ],
    "avatar" => [
      "width" => 400,
      "height" => 400,
      "aspect" => '1:1',
      "allowed" => [
        "image"
      ]
    ]
  ];
