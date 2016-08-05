<?php
/**
 * Nextcloud - keeweb
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jonne Haß <me@jhass.eu>
 * @copyright Jonne Haß 2016
 */

namespace OCA\Keeweb\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Controller;

class PageController extends Controller {


	public function __construct($AppName, IRequest $request, $UserId){
		parent::__construct($AppName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index($open) {
		$params = [];
		if (isset($open)) {
			$params['config'] = 'config?file='.$open;
		}
		$response = new TemplateResponse("keeweb", "main", $params);
		// Override default CSP
		$csp = new ContentSecurityPolicy();
		$csp->addAllowedFrameDomain("'self'");
		$response->setContentSecurityPolicy($csp);
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function keeweb() {
		$response = new TemplateResponse("keeweb", "keeweb", [], "blank");
		// Override default CSP
		$csp = new ContentSecurityPolicy();
		$csp->allowInlineScript(true);
		$csp->addAllowedFontDomain("data:");
		$response->setContentSecurityPolicy($csp);
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function manifest() {
		$response = new TemplateResponse("keeweb", "manifest.appcache", array(), "blank");
		$response->addHeader("Content-Type", "text/plain");
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function config($file) {
		$csrfToken = \OC::$server->getCSRFTokenManager()->getToken()->getEncryptedValue();
		$webdavBase = $this->request->getServerProtocol().'://'.$this->request->getServerHost().'/remote.php/webdav';
		$config = [
			'settings' => (object) null,
			'files' => [
				[
					'storage' => 'webdav',
    			'name' => $file.' on Nextcloud',
    			'path' => $webdavBase.$file.'?requesttoken='.urlencode($csrfToken),
    			"options" => ['user' => null, 'password' => null]
    		]
    	]
    ];

    return new JSONResponse($config);
	}
}
