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
use OCP\Util;
use Psr\Container\ContainerInterface;
use OCP\Files\IMimeTypeDetector;

class Application extends App {
    public function __construct(){
        $appName = "keeweb";

        parent::__construct($appName);

        if (array_key_exists("REQUEST_URI", \OC::$server->getRequest()->server))
        {
            $url = \OC::$server->getRequest()->server["REQUEST_URI"];
            if (isset($url)) {
                if (preg_match("%/apps/files(/.*)?%", $url) || str_contains($url, "/s/")) // Files app and file sharing
                {
                    Util::addScript($appName, "viewer");
                }
            }
        }

        $context = $this->getContainer();
        $context->registerService('PageController', function (ContainerInterface $c) {
            return new PageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('ServerContainer')->getURLGenerator(),
                $c->query('Config')
            );
        });

        $detector = $context->query(IMimeTypeDetector::class);
        $detector->getAllMappings();
        $detector->registerType("kdbx", "application/x-kdbx");

    }

}