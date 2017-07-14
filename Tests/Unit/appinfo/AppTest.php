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

namespace OCA\LoginViaPost\Tests\Unit\appinfo;

use OC\AppFramework\Http\Request;
use Test\TestCase;

class AppTest extends TestCase {
	public function setUp() {

		parent::setUp();
	}

	public function testRegularPage() {
		require  __DIR__ . '/../../../appinfo/app.php';
		$this->assertSame(Request::class, get_class(\OC::$server->getRequest()));
	}

	public function testLoginPage() {
		define('PHPUNIT_BYPASS_URL', 'foo');
		require __DIR__ . '/../../../appinfo/app.php';
		$this->assertSame(\OCA\LoginViaPost\Request::class, get_class(\OC::$server->getRequest()));
	}
}
