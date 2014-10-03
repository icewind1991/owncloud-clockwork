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

require_once __DIR__ . '/../3rdparty/autoload.php';

$application = new Application();

$pathInfo = \OC_Request::getPathInfo();
if (strstr($pathInfo, 'apps/clockwork/') === false) {
	$application->sendHeaders();

	register_shutdown_function(function () use ($application) {
		$application->storeRequest();
	});
}
