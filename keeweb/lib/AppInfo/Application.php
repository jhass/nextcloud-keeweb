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

namespace OCA\Keeweb\AppInfo;

use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCA\Keeweb\Listener\LoadAdditionalScriptsListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\IMimeTypeDetector;

class Application extends App implements IBootstrap {
    public const APP_ID = 'keeweb';

    public function __construct(array $urlParams = []) {
        parent::__construct(self::APP_ID, $urlParams);
    }

    public function register(IRegistrationContext $context): void {
        $context->registerEventListener(
            LoadAdditionalScriptsEvent::class,
            LoadAdditionalScriptsListener::class
        );
    }

    public function boot(IBootContext $context): void {
        $context->injectFn(function (IMimeTypeDetector $detector): void {
            $detector->getAllMappings();
            $detector->registerType('kdbx', 'application/x-kdbx');
        });
    }
}
