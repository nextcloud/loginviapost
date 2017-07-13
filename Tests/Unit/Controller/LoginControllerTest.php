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

namespace OCA\LoginViaPost\Tests\Unit;

use OC\Authentication\TwoFactorAuth\Manager;
use OC\User\Session;
use OCA\LoginViaPost\Controller\LoginController;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\IConfig;
use OCP\ILogger;
use OCP\IRequest;
use OCP\ISession;
use OCP\IURLGenerator;
use OCP\IUserManager;
use OCP\IUserSession;
use Test\TestCase;

class LoginControllerTest extends TestCase {
	/** @var IRequest|\PHPUnit_Framework_MockObject_MockObject */
	private $request;
	/** @var IURLGenerator|\PHPUnit_Framework_MockObject_MockObject */
	private $urlGenerator;
	/** @var IUserSession|\PHPUnit_Framework_MockObject_MockObject */
	private $userSession;
	/** @var \OC\User\Manager|\PHPUnit_Framework_MockObject_MockObject */
	private $userManager;
	/** @var IConfig|\PHPUnit_Framework_MockObject_MockObject */
	private $config;
	/** @var ISession|\PHPUnit_Framework_MockObject_MockObject */
	private $session;
	/** @var ILogger|\PHPUnit_Framework_MockObject_MockObject */
	private $logger;
	/** @var Manager|\PHPUnit_Framework_MockObject_MockObject */
	private $twoFactorAuthManager;
	/** @var LoginController */
	private $loginController;

	public function setUp() {
		parent::setUp();

		$this->request = $this->createMock(IRequest::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->userSession = $this->createMock(Session::class);
		$this->userManager = $this->createMock(\OC\User\Manager::class);
		$this->config = $this->createMock(IConfig::class);
		$this->session = $this->createMock(ISession::class);
		$this->logger = $this->createMock(ILogger::class);
		$this->twoFactorAuthManager = $this->createMock(Manager::class);

		$this->loginController = new LoginController(
			'loginviapost',
			$this->request,
			$this->urlGenerator,
			$this->userSession,
			$this->userManager,
			$this->config,
			$this->session,
			$this->logger,
			$this->twoFactorAuthManager
		);
	}

	public function testLogin() {
		$this->userManager
			->expects($this->once())
			->method('checkPasswordNoLogging')
			->willReturn(false);

		$this->assertSame(RedirectResponse::class, get_class($this->loginController->login('foo', 'bar')));
	}
}
