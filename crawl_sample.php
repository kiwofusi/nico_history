<?php
require_once 'vendor/autoload.php';
require_once 'NicoHistoryClient.php';

$client = new NicoHistoryClient("input_email", "input_password");
$history = $client->getHistory();
print_r($history);