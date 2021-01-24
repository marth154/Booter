<?php

require('vendor/autoload.php');

use GuzzleHttp\Client;

$client = new Client([
    'timeout'  => 2.0,
]);

$response = $client -> request('GET', 'https://accounts.google.com/.well-know/openid-condiguration');	

var_dump($response);

?>