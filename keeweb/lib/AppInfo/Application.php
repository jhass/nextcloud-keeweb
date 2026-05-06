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

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\IMimeTypeDetector;
use OCP\IRequest;
use OCP\Util;

class Application extends App implements IBootstrap {
    public const APP_ID = 'keeweb';

    public function __construct() {
        parent::__construct(self::APP_ID);
    }

    public function register(IRegistrationContext $context): void {
        // Controllers are auto-wired by the AppFramework, no manual
        // service registration needed.
    }

    public function boot(IBootContext $context): void {
        $context->injectFn(function (IMimeTypeDetector $detector, IRequest $request): void {
            $detector->getAllMappings();
            $detector->registerType('kdbx', 'application/x-kdbx');

            $url = $request->getRequestUri();
            if ($url !== '' && (preg_match('%/apps/files(/.*)?%', $url) || str_contains($url, '/s/'))) {
                Util::addScript(self::APP_ID, 'viewer');
            }
        });
    }
}
