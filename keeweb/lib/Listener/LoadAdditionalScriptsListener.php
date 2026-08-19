<?php
declare(strict_types=1);

namespace OCA\Keeweb\Listener;

use OCA\Keeweb\AppInfo\Application;
use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

class LoadAdditionalScriptsListener implements IEventListener {
    public function handle(Event $event): void {
        if (!$event instanceof LoadAdditionalScriptsEvent) {
            return;
        }

        Util::addScript(Application::APP_ID, 'viewer');
    }
}