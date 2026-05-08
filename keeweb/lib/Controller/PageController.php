<?php
declare(strict_types=1);

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

use OC\Security\CSRF\CsrfTokenManager;
use OCA\Keeweb\AppInfo\Application;
use OCP\App\IAppManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Util;

class PageController extends Controller {

	public function __construct(
		IRequest $request,
		private IURLGenerator $urlGenerator,
		private IConfig $settings,
		private IAppManager $appManager,
		private CsrfTokenManager $csrfTokenManager,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function index(?string $open = null): TemplateResponse {
		$params = ['keeweb' => $this->urlGenerator->linkToRoute('keeweb.page.keeweb')];
		$params['config'] = $open !== null
			? 'config?file=' . urlencode($open)
			: 'config';
		$response = new TemplateResponse(Application::APP_ID, 'main', $params);
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function keeweb(): TemplateResponse {
		$response = new TemplateResponse(Application::APP_ID, 'keeweb', [], 'blank');
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function manifest(): TemplateResponse {
		$params = [
			'keeweb' => $this->urlGenerator->linkToRoute('keeweb.page.keeweb'),
			'version' => $this->settings->getAppValue($this->appName, 'installed_version'),
		];
		$response = new TemplateResponse(Application::APP_ID, 'manifest.appcache', $params, 'blank');
		$response->addHeader('Content-Type', 'text/plain');
		return $response;
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function config(?string $file = null): JSONResponse {
		$csrfToken = $this->csrfTokenManager->getToken()->getEncryptedValue();
		$webdavBase = Util::linkToRemote('webdav');
		$config = ['settings' => ['allowIframes' => true]];
		if ($file !== null) {
			$config['files'] = [
				[
					'storage' => 'webdav',
					'name' => $file . ' on ' . $this->request->getServerHost(),
					'path' => $this->joinPaths($webdavBase, $file . '?requesttoken=' . urlencode($csrfToken)),
					'options' => ['user' => null, 'password' => null],
				],
			];
		}
		return new JSONResponse($config);
	}

	#[NoAdminRequired]
	#[NoCSRFRequired]
	public function serviceworker(): DataDisplayResponse {
		$path = $this->joinPaths($this->appManager->getAppPath(Application::APP_ID), 'templates/service-worker.js');
		$response = new DataDisplayResponse(file_get_contents($path));
		$response->addHeader('Content-Type', 'application/javascript; charset=utf8');
		$response->setContentSecurityPolicy($this->getCSP());
		return $response;
	}

	private function joinPaths(string $base, string $path): string {
		return rtrim($base, '/') . '/' . ltrim($path, '/');
	}

	private function getCSP(): EmptyContentSecurityPolicy {
		$csp = new EmptyContentSecurityPolicy();
		$csp->addAllowedFrameDomain("'self'");
		$csp->addAllowedFrameDomain("'unsafe-inline'");
		$csp->addAllowedFrameDomain("'unsafe-eval'");
		$csp->addAllowedFrameDomain('blob:');
		$csp->addAllowedFrameAncestorDomain("'self'");
		$csp->addAllowedStyleDomain("'self'");
		$csp->addAllowedFontDomain("'self'");
		$csp->addAllowedFontDomain('data:');
		$csp->addAllowedImageDomain("'self'");
		$csp->addAllowedImageDomain('data:');
		$csp->addAllowedImageDomain('blob:');
		$csp->addAllowedImageDomain('https://services.keeweb.info');
		$csp->addAllowedScriptDomain("'self'");
		$csp->addAllowedConnectDomain("'self'");
		$csp->addAllowedConnectDomain('https://services.keeweb.info');
		$csp->addAllowedScriptDomain('https://plugins.keeweb.info');
		$csp->addAllowedScriptDomain("'unsafe-inline'");
		$csp->addAllowedScriptDomain('blob:');
		$csp->addAllowedConnectDomain('https://plugins.keeweb.info');
		$csp->allowEvalScript(true);
		$csp->allowInlineStyle();
		return $csp;
	}
}
