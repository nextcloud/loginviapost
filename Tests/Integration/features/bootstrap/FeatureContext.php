<?php

use Behat\Behat\Context\Context;

class FeatureContext implements Context {
	/** @var \GuzzleHttp\Message\ResponseInterface */
	private $response;

	private function sendRequest($username,
								 $password,
								 $cookies = false) {

		$params = [];
		if($cookies) {
			$params['cookies'] = [
				'testCookie' => '1234567890',
			];
		}
		$params['form_params'] = [
			'username' => $username,
			'password' => $password,
		];

		$client = new GuzzleHttp\Client();
		$this->response = $client->request(
			'POST',
			'http://localhost:8080/index.php/apps/loginviapost/login',
			$params
		);
	}

	/**
	 * @Given A login request with :username :password is sent without cookies
	 */
	public function aLoginRequestWithIsSentWithoutCookies($username,
														  $password) {
		$this->sendRequest($username, $password);
	}

	/**
	 * @Given A login request with :username :password is sent with cookies
	 */
	public function aLoginRequestWithIsSentWithCookies($username,
													   $password) {
		$this->sendRequest($username, $password, true);
	}

	/**
	 * @Then The URL should redirect to :url
	 */
	public function theUrlShouldRedirectTo($url) {
		if($this->response->getEffectiveUrl() !== $url) {
			throw new InvalidArgumentException("Expected {$this->response->getEffectiveUrl()}, got $url");
		}
	}

	/**
	 * @Given User :user exists
	 */
	public function userExists($user) {
		putenv('OC_PASS=password');
		$cmd = sprintf('php ../../../../occ user:delete %s', $user);
		shell_exec($cmd);
		$cmd = sprintf('php ../../../../occ user:add %s --password-from-env', $user);
		shell_exec($cmd);
	}
}
