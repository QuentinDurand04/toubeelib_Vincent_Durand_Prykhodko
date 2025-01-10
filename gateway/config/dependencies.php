<?php


return [

    'guzzle' => new \GuzzleHttp\Client([
//        'base_uri' => 'http://localhost:6080',
        'timeout'  => 5.0,
        'verify'   => false,
    ]),

];