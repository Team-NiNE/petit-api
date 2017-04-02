<?php
function giveHost($host_with_subdomain) {
	$array = explode(".", $host_with_subdomain);
	return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1];
}
ini_set('session.cookie_domain', '.'.giveHost($_SERVER['SERVER_NAME']));
session_start();

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
$config = require_once 'config.php';
$consumer_key = $config['consumer_key'];
$consumer_secret = $config['consumer_secret'];
$connection = new TwitterOAuth($consumer_key, $consumer_secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => $config['api_root'] . 'getTwitterData.php'));
$oauth_token=$request_token['oauth_token'];
$token_secret=$request_token['oauth_token_secret'];
unset($_COOKIE['oauth_token']);
unset($_COOKIE['token_secret']);
$_SESSION['oauth_token'] = $oauth_token;
$_SESSION['token_secret'] = $token_secret;
$url = $connection->url("oauth/authorize", array("oauth_token" => $oauth_token));
header('Location: ' . $url);
?>