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

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Keeweb\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
     ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#keeweb', 'url' => '/keeweb', 'verb' => 'GET'],
	   ['name' => 'page#manifest', 'url' => '/manifest.appcache', 'verb' => 'GET'],
     ['name' => 'page#config', 'url' => '/config', 'verb' => 'GET'],
     ['name' => 'page#serviceworker', 'url' => '/service-worker.js', 'verb' => 'GET']
    ]
];
