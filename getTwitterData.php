<?php 
function giveHost($host_with_subdomain) {
	$array = explode(".", $host_with_subdomain);
	return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1];
}
ini_set('session.cookie_domain', '.'.giveHost($_SERVER['SERVER_NAME']));
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
$config = require_once 'config.php';
$referrer = $_SERVER['HTTP_REFERER'];
if($referrer !== "https://api.twitter.com/oauth/authorize") {
	header('Status: 302', true);
	header('Location: ' . $config['root_location']);
} else {
	session_start();
	$consumer_key = $config['consumer_key'];
	$consumer_secret = $config['consumer_secret'];
	$oauth_verifier = $_GET['oauth_verifier'];
	$token_secret = $_COOKIE['token_secret'];
	$oauth_token = $_COOKIE['oauth_token'];
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $token_secret);
	$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));
	$accessToken=$access_token['oauth_token'];
	$secretToken=$access_token['oauth_token_secret'];
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $accessToken, $secretToken);
	$data = $connection->get("account/verify_credentials", ["skip_status" => "true", "include_email" => "true"]);
	$arraytwitter = json_decode(json_encode($data), true);
	$_SESSION['twitteraccess'] = $accessToken;
	$_SESSION['twittersecret'] = $secretToken;
	$_SESSION['twitterdata'] = $arraytwitter;
	$_SESSION['noti_type'] = 'twitter';
	header('Status: 302', true);
	header('Location: https://' . $config['root_location'] . '/getSession.php');
}