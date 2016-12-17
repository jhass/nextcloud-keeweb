<?php

namespace OCA\Keeweb\Migration;

use OCP\Migration\IRepairStep;
use OCP\IDBConnection;
use OCP\Migration\IOutput;

class AddMimetypeToFilecache implements IRepairStep {
  public function __construct(IDBConnection $connection) {}

  public function getName() {
    return "Add custom mimetype to filecache";
  }

  public function run(IOutput $output) {
    $mimeTypeDetector = \OC::$server->getMimeTypeDetector();
    $mimeTypeLoader = \OC::$server->getMimeTypeLoader();

    // Register custom mimetype
    $mimeTypeDetector->getAllMappings();
    $mimeTypeDetector->registerType('kdbx', 'x-application/kdbx', 'x-application/kdbx');

    // And update the filecache for it.
    $mimetypeId = $mimeTypeLoader->getId('x-application/kdbx');
    $mimeTypeLoader->updateFilecache('%.kdbx', $mimetypeId);

    $output->info("Added custom mimetype to filecache.");
  }
}
