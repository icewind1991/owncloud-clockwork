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

namespace OCA\Clockwork\Controller;

use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\AppFramework\Controller;

class ClockworkController extends Controller {
	/**
	 * @var \Clockwork\Storage\Storage
	 */
	private $storage;

	public function __construct($appName, IRequest $request, $storage) {
		parent::__construct($appName, $request);
		$this->storage = $storage;
	}


	/**
	 * CAUTION: the @Stuff turn off security checks, for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
	}


	/**
	 * Simply method that posts back the payload of the request
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @param string $name
	 * @return \OCP\AppFramework\Http\Response
	 */
	public function retrieve($name) {
		$data = $this->storage->find($name);
		return new JSONResponse($data);
	}
}
