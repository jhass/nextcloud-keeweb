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
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\AppFramework\Controller;
use OCP\App\IAppManager;

class PageController extends Controller {

	private $urlGenerator;
	private $settings;
	private $appManager;

	public function __construct(
		$AppName,
		IRequest $request,
		IURLGenerator $urlGenerator,
		IConfig $settings,
		IAppManager $appManager) {
		parent::__construct($AppName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->settings = $settings;
		$this->appManager = $appManager;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index($open) {
		$params = ['keeweb' => $this->urlGenerator->linkToRoute('keeweb.page.keeweb')];
		if (isset($open)) {
			$params['config'] = 'config?file='.urlencode($open);
		} else {
			$params['config'] = 'config';
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
		$config = ['settings' => (object) null];
		if (isset($file)) {
			$config['files'] = [
				[
					'storage' => 'webdav',
					'name' => $file.' on '.$this->request->getServerHost(),
					'path' => $this->joinPaths($webdavBase, $file.'?requesttoken='.urlencode($csrfToken)),
					"options" => ['user' => null, 'password' => null]
				]
			];
		}
		return new JSONResponse($config);
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function serviceworker() {
		$response = new DataDisplayResponse(file_get_contents(
                  $this->joinPaths($this->appManager->getAppPath("keeweb"), "templates/service-worker.js")));
		$response->addHeader("Content-Type", "application/javascript; charset=utf8");
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	private function joinPaths($base, $path) {
		return rtrim($base, '/').'/'.ltrim($path, '/');
	}

	private function getCSP() {
		$csp = new EmptyContentSecurityPolicy();
		$csp->addAllowedFrameDomain("'self'");
		$csp->addAllowedFrameDomain("'unsafe-inline'");
		$csp->addAllowedFrameDomain("'unsafe-eval'");
		$csp->addAllowedFrameDomain("blob:");
		$csp->addAllowedStyleDomain("'self'");
		$csp->addAllowedFontDomain("'self'");
		$csp->addAllowedFontDomain("data:");
		$csp->addAllowedImageDomain("'self'");
		$csp->addAllowedImageDomain("data:");
		$csp->addAllowedImageDomain("blob:");
		$csp->addAllowedImageDomain("https://favicon.keeweb.info");
		$csp->addAllowedScriptDomain("'self'");
		$csp->addAllowedConnectDomain("'self'");
		$csp->addAllowedScriptDomain('https://plugins.keeweb.info');
		$csp->addAllowedConnectDomain('https://plugins.keeweb.info');
		$csp->addAllowedChildSrcDomain("blob:");
		$csp->addAllowedChildSrcDomain("'self'");
		$csp->allowEvalScript(true);
		$csp->allowInlineScript(true);
		$csp->allowInlineStyle(true);
		return $csp;
	}
}
