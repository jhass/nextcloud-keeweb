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

$mimeTypeLoader = \OC::$server->getMimeTypeLoader();

// Changes the mimetype of kdbx files in filecache to correct one
$mimetypeId = $mimeTypeLoader->getId('x-application/kdbx');
$mimeTypeLoader->updateFilecache('%.kdbx', $mimetypeId);

// Script for registering file actions
$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener(
	'OCA\Files::loadAdditionalScripts',
	function() {
		\OCP\Util::addScript('keeweb', 'viewer');
	}
);
