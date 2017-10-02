<?php
require '../vendor/autoload.php';
use Discord\OAuth\Discord;
$config = parse_ini_file('../config.ini', true);

ini_set('session.cookie_domain', '.'.$config['root']['domain']);
session_start();

$discord = new Discord($config['discord']);

$token = $discord->getAccessToken('authorization_code', [
	'code' => $_GET['code']
]);

$user = $discord->getResourceOwner($token);

echo(var_dump($user));
