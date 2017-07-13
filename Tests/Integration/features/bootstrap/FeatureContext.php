<?php

use Behat\Behat\Context\Context;

class FeatureContext implements Context {
	/** @var \GuzzleHttp\Psr7\Response */
	private $response;
	/** @var array */
	private $createdUsers = [];

	/** @AfterScenario */
	public function afterScenario() {
		foreach($this->createdUsers as $user) {
			$cmd = sprintf('php ../../../../occ user:delete %s', $user);
			shell_exec($cmd);
		}
	}

	private function sendRequest($username,
								 $password,
								 $cookies = false) {
		$params = [];
		if($cookies) {
			$params['cookies'] = \GuzzleHttp\Cookie\CookieJar::fromArray([
				'TestCookie' => 'TestValue'
			], 'localhost:8080');
		} else {
			$params['cookies'] = \GuzzleHttp\Cookie\CookieJar::fromArray([], 'localhost:8080');
		}
		$params['form_params'] = [
			'username' => $username,
			'password' => $password,
		];

		$client = new GuzzleHttp\Client(['allow_redirects' => ['track_redirects' => true]]);
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
		$realUrl = $this->response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER);
		$realUrl = $realUrl[count($realUrl) - 1];
		if($realUrl !== $url) {
			throw new InvalidArgumentException("Expected $url, got $realUrl");
		}
	}

	/**
	 * @Given User :user exists
	 */
	public function userExists($user) {
		$this->createdUsers[] = $user;
		$cmd = sprintf('php ../../../../occ config:system:set auth.bruteforce.protection.enabled --value false --type boolean');
		shell_exec($cmd);
		putenv('OC_PASS=password');
		$cmd = sprintf('php ../../../../occ user:add %s --password-from-env', $user);
		shell_exec($cmd);
		sleep(1);
	}
}
