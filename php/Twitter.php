<?php
require_once("TwitterApiExchange.php");
class Twitter {
	
	protected $wrapper;
	private $usersToFollow;
	
	public function __construct($consumerToken, $consumerSecret, $accessToken, $accessSecret) {
		$this->wrapper = new TwitterApiExchange(array(
			'oauth_access_token' => $consumerToken,
			'oauth_access_token_secret' => $consumerSecret,
			'consumer_key' => $accessToken,
			'consumer_secret' => $accessSecret
		));
	}
	
	public function getUserTweets($userHandle) {
		return sendGetRequest("https://api.twitter.com/1.1/statuses/user_timeline.json", "q", "@" . $userHandle);
	}
	
	public function streamUser($user) {
		$usersToFollow += "," + $user;
	}
	
	public function startStream() {
		if($usersToFollow == "") {
			throw new Exception("At least one user must be followed before staring the stream")
		}
		sendPostRequest("https://stream.twitter.com/1.1/statuses/filter.json", "follow", $usersToFollow);
		// TODO: Specify a callback function that is run when the stream sends an event
	}
	
	protected function sendGetRequest($url, $field_name, $field_val) {
		return sendRequest($url, $field_name, $field_val, "GET");
	}
	
	protected function sendPostRequest($url, $field_name, $field_val) {
		return sendRequest($url, $field_name, $field_val, "POST");
	}
	
	protected function sendRequest($url, $field_name, $field_val, $method) {
		return $wrapper->setGetField("?" . urlencode($field_name . "=" . $field_val))
			->buildOauth(urlencode($url), $method)->performRequest();
	}
	
}
?>
