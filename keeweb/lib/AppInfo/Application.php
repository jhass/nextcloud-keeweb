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

use OC\Files\Type\Detection;
use OCP\AppFramework\App;
use OCA\Keeweb\Controller\PageController;

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

// Script for registering file actions
$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener(
	'OCA\Files::loadAdditionalScripts',
	function() {
		\OCP\Util::addScript('keeweb', 'viewer');
	}
);
