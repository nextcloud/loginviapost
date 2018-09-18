<?php
/**
 * @copyright Copyright (c) 2017 Lukas Reschke <lukas@statuscode.ch>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

$serverContainer = \OC::$server;

$currentUrl = $serverContainer->getRequest()->getServerProtocol() . '://' .$serverContainer->getRequest()->getServerHost() . $serverContainer->getRequest()->getRequestUri();
$expectedUrl = $serverContainer->getURLGenerator()->getAbsoluteURL('/index.php/apps/loginviapost/login');

//  Only process on login URL
if($currentUrl !== $expectedUrl && !defined('PHPUNIT_BYPASS_URL')) {
	return;
}

// Register the request service again
$serverContainer->registerService('Request', function() use ($serverContainer) {
	if (defined('PHPUNIT_RUN') && PHPUNIT_RUN
		&& in_array('fakeinput', stream_get_wrappers())
	) {
		$stream = 'fakeinput://data';
	} else {
		$stream = 'php://input';
	}

	return new \OCA\LoginViaPost\Request(
		[
			'get' => $_GET,
			'post' => $_POST,
			'files' => $_FILES,
			'server' => $_SERVER,
			'env' => $_ENV,
			'cookies' => $_COOKIE,
			'method' => (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD']))
				? $_SERVER['REQUEST_METHOD']
				: null,
			'urlParams' => isset($serverContainer['urlParams']) ? $serverContainer['urlParams'] : [],
		],
		$serverContainer->getSecureRandom(),
		$serverContainer->getConfig(),
		$serverContainer->getCsrfTokenManager(),
		$stream
	);
});
