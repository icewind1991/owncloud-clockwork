<?php
/**
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Clockwork\tests\unit;

use Clockwork\Request\Request;
use OCA\Clockwork\DataSource;
use OCP\Diagnostics\IEventLogger;
use OCP\Diagnostics\IQueryLogger;
use Test\TestCase;

class DataSourceTest extends TestCase {

	public function test() {
		/** @var \PHPUnit_Framework_MockObject_MockObject | IQueryLogger $q */
		$q = $this->createMock(IQueryLogger::class);
		$q->expects($this->once())->method('getQueries')->willReturn([]);
		/** @var \PHPUnit_Framework_MockObject_MockObject | IEventLogger $l */
		$l = $this->createMock(IEventLogger::class);
		$l->expects($this->once())->method('getEvents')->willReturn([]);
		$d = new DataSource($q, $l);

		/** @var Request $r */
		$r = $this->createMock(Request::class);
		$r0 = $d->resolve($r);
		$this->assertEquals($r, $r0);
	}

}
