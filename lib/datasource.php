<?php
/**
 * Copyright (c) 2014 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Clockwork;

use Clockwork\Request\Request;
use OCP\Diagnostics\IEventLogger;
use OCP\Diagnostics\IQueryLogger;

class DataSource extends \Clockwork\DataSource\DataSource {
	/**
	 * @var \OCP\Diagnostics\IQueryLogger
	 */
	private $queryLogger;

	/**
	 * @var \OCP\Diagnostics\IEventLogger
	 */
	private $eventLogger;

	/**
	 * @param \OCP\Diagnostics\IQueryLogger $queryLogger
	 * @param \OCP\Diagnostics\IEventLogger $eventLogger
	 */
	public function __construct(IQueryLogger $queryLogger, IEventLogger $eventLogger) {
		$this->queryLogger = $queryLogger;
		$this->eventLogger = $eventLogger;
	}

	/**
	 * @param \Clockwork\Request\Request $request
	 * @return \Clockwork\Request\Request
	 */
	public function resolve(Request $request) {
		$request->timelineData = $this->getTimeLine();
		$request->databaseQueries = $this->getQueries();
	}

	/**
	 * format the timeline in the format Clockwork wants it
	 *
	 * @return array
	 */
	private function getTimeLine() {
		$events = $this->eventLogger->getEvents();
		return array_map(function ($event) {
			/** @var \OCP\Diagnostics\IEvent $event */
			return array(
				'start' => $event->getStart(),
				'end' => $event->getEnd(),
				'duration' => $event->getDuration() * 1000,
				'description' => $event->getDescription()
			);
		}, $events);
	}

	/**
	 * format the queries in the format Clockwork wants it
	 *
	 * @return array
	 */
	private function getQueries() {
		$queries = $this->queryLogger->getQueries();
		return array_map(function ($query) {
			/** @var \OCP\Diagnostics\IQuery $query */
			return array(
				'query' => $query->getSql(),
				'duration' => $query->getDuration() * 1000
			);
		}, $queries);
	}
}
