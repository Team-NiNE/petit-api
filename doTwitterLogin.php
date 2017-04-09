<?php
function giveHost($host_with_subdomain) {
	$array = explode(".", $host_with_subdomain);
	return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1];
}
ini_set('session.cookie_domain', '.'.giveHost($_SERVER['SERVER_NAME']));
session_start();

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
$config = parse_ini_file('config.ini', true);
$consumer_key = $config['twitter']['key'];
$consumer_secret = $config['twitter']['secret'];
$connection = new TwitterOAuth($consumer_key, $consumer_secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => $config['site']['api_root'] . 'getTwitterData.php'));
$oauth_token=$request_token['oauth_token'];
$token_secret=$request_token['oauth_token_secret'];
setcookie("token_secret", " ", time()-3600);
setcookie("token_secret", $token_secret, time()+60*10);
setcookie("oauth_token", " ", time()-3600);
setcookie("oauth_token", $oauth_token, time()+60*10);
$_SESSION['oauth_token'] = $oauth_token;
$_SESSION['token_secret'] = $token_secret;
$url = $connection->url("oauth/authorize", array("oauth_token" => $oauth_token));
header('Location: ' . $url);
?>