<?php


use TencentCloud\Common\Core\Client;

include dirname(__DIR__).'/src/autoload.inc.php';

$options = [
	'secret_id' => '',
	'secret_key' => '',
];

Client::setSendCallback(function(Client $client){
	$url = $client->getUrl();
	$param = $client->getData();
	list($result) = $client->getResponse();
	$k = 1;
});