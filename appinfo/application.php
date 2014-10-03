<?php
/**
 * ownCloud - clockwork
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Robin Appelman <robin@icewind.nl>
 * @copyright Robin Appelman 2014
 */

namespace OCA\Clockwork\AppInfo;

use Clockwork\Clockwork;
use Clockwork\DataSource\PhpDataSource;
use Clockwork\Storage\FileStorage;
use OCA\Clockwork\Controller\ClockworkController;
use OCA\Clockwork\DataSource;
use \OCP\AppFramework\App;
use \OCP\IContainer;

class Application extends App {
	public function __construct(array $urlParams = array()) {
		parent::__construct('clockwork', $urlParams);

		$container = $this->getContainer();

		/**
		 * Controllers
		 */
		$container->registerService('ClockworkController', function (IContainer $c) {
			return new ClockworkController(
				$c->query('AppName'),
				$c->query('Request'),
				$c->query('Storage')
			);
		});

		/**
		 * Core
		 */
		$container->registerService('Storage', function (IContainer $c) {
			return new FileStorage('/srv/http/xdebug');
		});

		$container->registerService('DataSource', function (IContainer $c) {
			return new DataSource(\OC::$server->getQueryLogger(), \OC::$server->getEventLogger());
		});

		$container->registerService('Clockwork', function (IContainer $c) {
			$clockwork = new Clockwork();
			$clockwork->addDataSource($c->query('DataSource'));
			$clockwork->addDataSource(new PhpDataSource());
			$clockwork->setStorage($c->query('Storage'));
			return $clockwork;
		});
	}

	public function sendHeaders() {
		$container = $this->getContainer();
		/** @var \Clockwork\Clockwork $clockwork */
		$clockwork = $container->query('Clockwork');
		$urlGenerator = \OC::$server->getURLGenerator();
		header("X-Clockwork-Id: " . $clockwork->getRequest()->id);
		header("X-Clockwork-Version: " . Clockwork::VERSION);
		header("X-Clockwork-Path: " . $urlGenerator->linkToRoute('clockwork.clockwork.index'));
	}

	public function storeRequest() {
		$container = $this->getContainer();
		/** @var \Clockwork\Clockwork $clockwork */
		$clockwork = $container->query('Clockwork');
		$clockwork->resolveRequest();
		$clockwork->storeRequest();
	}
}
