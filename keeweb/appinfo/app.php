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

namespace OCA\Keeweb\AppInfo;

use OCP\AppFramework\App;
use OCA\Keeweb\Controller\PageController;

require_once __DIR__ . '/autoload.php';

class Application extends App {
 public function __construct(array $urlParams=array()){
        parent::__construct('keeweb', $urlParams);
        $container = $this->getContainer();
        $container->registerService('PageController', function($c) {
            return new PageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ServerContainer')->getURLGenerator(),
                $c->query('Config')
            );
        });
    }
}

$app = new Application();
$container = $app->getContainer();

$container->query('OCP\INavigationManager')->add(function () use ($container) {
	$urlGenerator = $container->query('OCP\IURLGenerator');
	$l10n = $container->query('OCP\IL10N');
	return [
		// the string under which your app will be referenced in Nextcloud
		'id' => 'keeweb',

		// sorting weight for the navigation. The higher the number, the higher
		// will it be listed in the navigation
		'order' => 2,

		// the route that will be shown on startup
		'href' => $urlGenerator->linkToRoute('keeweb.page.index'),

		// the icon that will be shown in the navigation
		// this file needs to exist in img/
		'icon' => $urlGenerator->imagePath('keeweb', 'app.svg'),

		// the title of your application. This will be used in the
		// navigation or on the settings page of your app
		'name' => $l10n->t('Keeweb'),
	];
});

$mimeTypeDetector = \OC::$server->getMimeTypeDetector();
$mimeTypeLoader = \OC::$server->getMimeTypeLoader();

// Register custom mimetype we can hook in the frontend
$mimeTypeDetector->getAllMappings();
$mimeTypeDetector->registerType('kdbx', 'x-application/kdbx', 'x-application/kdbx');

// And update the filecache for it. TODO: do this on enable only
// Registering a post_enable hook below does not work because
// this file is run only after (on the next request)
$mimetypeId = $mimeTypeLoader->getId('x-application/kdbx');
$mimeTypeLoader->updateFilecache('%.kdbx', $mimetypeId);

// Remove custom mime-type from filecache as app is disabled
class Hooks {
	static public function pre_disable($params) {
		if ($params['app'] == "keeweb") {
			$mimeTypeLoader = \OC::$server->getMimeTypeLoader();
			$mimetypeId = $mimeTypeLoader->getId('application/octet-stream');
			$mimeTypeLoader->updateFilecache('%.kdbx', $mimetypeId);
		}
	}
}

\OC_Hook::connect('OC_App', 'pre_disable', '\OCA\Keeweb\AppInfo\Hooks', 'pre_disable');

// Script for registering file actions
$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener(
	'OCA\Files::loadAdditionalScripts',
	function() {
		\OCP\Util::addScript('keeweb', 'viewer');
	}
);
