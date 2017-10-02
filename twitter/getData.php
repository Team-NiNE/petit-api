<?php
require '../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
$config = parse_ini_file('../config.ini', true);

ini_set('session.cookie_domain', '.'.$config['root']['domain']);
session_start();

$referrer = $_SERVER['HTTP_REFERER'];
if($referrer !== 'https://api.twitter.com/oauth/authorize') {
	header('Status: 302', true);
	header('Location: ' . $config['root']['main']);
} else {
	session_start();
	$consumer_key = $config['twitter']['key'];
	$consumer_secret = $config['twitter']['secret'];
	$oauth_verifier = $_GET['oauth_verifier'];
	$token_secret = $_SESSION['token_secret'];
	$oauth_token = $_SESSION['oauth_token'];
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $token_secret);
	$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $oauth_verifier));
	$accessToken = $access_token['oauth_token'];
	$secretToken = $access_token['oauth_token_secret'];
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $accessToken, $secretToken);
	$data = $connection->get('account/verify_credentials', ['skip_status' => 'true', 'include_email' => 'true']);
	$arraytwitter = json_decode(json_encode($data), true);
	$_SESSION['twitteraccess'] = $accessToken;
	$_SESSION['twittersecret'] = $secretToken;
	$_SESSION['twitterdata'] = $arraytwitter;
	$_SESSION['noti_type'] = 'twitter';
	header('Status: 302', true);
	header('Location: ' . $config['site']['apply_root'] . 'getSession.php');
}
