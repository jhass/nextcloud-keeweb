<?php

namespace OCA\Keeweb\Migration;

use OCP\Migration\IRepairStep;
use OCP\IDBConnection;
use OCP\Migration\IOutput;

class RemoveMimetypeFromFilecache implements IRepairStep {
  public function __construct(IDBConnection $connection) {}

  public function getName() {
    return "Remove custom mimetype from filecache";
  }

  public function run(IOutput $output) {
      $mimeTypeLoader = \OC::$server->getMimeTypeLoader();
      $mimetypeId = $mimeTypeLoader->getId('application/octet-stream');
      $mimeTypeLoader->updateFilecache('%.kdbx', $mimetypeId);
      $output->info("Removed custom mimetype from filecache.");
  }
}
