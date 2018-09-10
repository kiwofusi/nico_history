<?php
require_once 'vendor/autoload.php';
require_once 'NicoHistoryClient.php';

$client = new NicoHistoryClient("input_email", "input_password");
$history = $client->getHistory();
print_r($history);
/*
Array
(
    [0] => Array
        (
            [url] => 動画URL
            [title] => 動画タイトル
            [view_time] => 視聴日時(Y-m-d h:i:s)
            [view_count] => 視聴回数(int)
        )
    ...
)
*/