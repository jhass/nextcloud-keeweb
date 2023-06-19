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

use OCA\Keeweb\Controller\PageController;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Util;
use Psr\Container\ContainerInterface;

class Application extends App implements IBootstrap {
    public function __construct(){
        parent::__construct('keeweb');
    }

    public function register(IRegistrationContext $context): void {
        $context->registerService('PageController', function (ContainerInterface $c) {
            return new PageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ServerContainer')->getURLGenerator(),
                $c->query('Config')
            );
        });
    }

    public function boot(IBootContext $context): void {
        $context->injectFn(function (IEventDispatcher $eventDispatcher) {
            $eventDispatcher->addListener(
                'OCA\Files::loadAdditionalScripts',
                function() {
                    Util::addScript('keeweb', 'viewer');
                }
            );
        });
    }
}
