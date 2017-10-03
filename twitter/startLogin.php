<?php
require '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
$config = parse_ini_file('../config.ini', true);

ini_set('session.cookie_domain', '.'.$config['root']['domain']);
session_start();

$twitterConfig = $config['twitter'];
$connection = new TwitterOAuth($twitterConfig['key'], $twitterConfig['secret']);
$request_token = $connection->oauth('oauth/request_token', ['oauth_callback' => $config['root']['api'].'twitter/getData.php']);
$oauth_token = $request_token['oauth_token'];
$token_secret = $request_token['oauth_token_secret'];
setcookie('oauth_token', $token_secret, time() + 60 * 10);
setcookie('token_secret', $token_secret, time() + 60 * 10);
$_SESSION['oauth_token'] = $oauth_token;
$_SESSION['token_secret'] = $token_secret;
$url = $connection->url('oauth/authorize', ['oauth_token' => $oauth_token]);
header('Location: ' . $url);
?>
