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
use OCP\IURLGenerator;
use \OCP\IConfig;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\AppFramework\Controller;

class PageController extends Controller {

	private $urlGenerator;
	private $settings;

	public function __construct($AppName, IRequest $request, IURLGenerator $urlGenerator, IConfig $settings) {
		parent::__construct($AppName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->settings = $settings;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index($open) {
		$params = ['keeweb' => $this->urlGenerator->linkToRoute('keeweb.page.keeweb')];
		if (isset($open)) {
			$params['config'] = 'config?file='.$open;
		}
		$response = new TemplateResponse("keeweb", "main", $params);
		// Override default CSP
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function keeweb() {
		$response = new TemplateResponse("keeweb", "keeweb", [], "blank");
		// Override default CSP
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function manifest() {
		$params = ['keeweb' => $this->urlGenerator->linkToRoute('keeweb.page.keeweb'),
							 'version' => $this->settings->getAppValue($this->appName, 'installed_version')];
		$response = new TemplateResponse("keeweb", "manifest.appcache", $params, "blank");
		$response->addHeader("Content-Type", "text/plain");
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function config($file) {
		$csrfToken = \OC::$server->getCSRFTokenManager()->getToken()->getEncryptedValue();
		$webdavBase = \OCP\Util::linkToRemote('webdav');
		$config = [
			'settings' => (object) null,
			'files' => [
				[
					'storage' => 'webdav',
					'name' => $file.' on '.$this->request->getServerHost(),
					'path' => $this->joinPaths($webdavBase, $file.'?requesttoken='.urlencode($csrfToken)),
					"options" => ['user' => null, 'password' => null]
				]
			]
		];

		return new JSONResponse($config);
	}

	private function joinPaths($base, $path) {
		return rtrim($base, '/').'/'.ltrim($path, '/');
	}

	private function getCSP() {
		$csp = new EmptyContentSecurityPolicy();
		$csp->addAllowedFrameDomain("'self'");
		$csp->addAllowedFrameDomain("'unsafe-inline'");
		$csp->addAllowedFrameDomain("'unsafe-eval'");
		$csp->addAllowedStyleDomain("'self'");
		$csp->addAllowedFontDomain("'self'");
		$csp->addAllowedFontDomain("data:");
		$csp->addAllowedImageDomain("'self'");
		$csp->addAllowedImageDomain("data:");
		$csp->addAllowedImageDomain("blob:");
		$csp->addAllowedScriptDomain("'self'");
		$csp->addAllowedConnectDomain("'self'");
		$csp->addAllowedScriptDomain('https://plugins.keeweb.info');
		$csp->addAllowedConnectDomain('https://plugins.keeweb.info');
		$csp->addAllowedChildSrcDomain("blob:");
		$csp->allowEvalScript(true);
		$csp->allowInlineScript(true);
		$csp->allowInlineStyle(true);
		return $csp;
	}
}
