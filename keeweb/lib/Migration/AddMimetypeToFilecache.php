<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;

class AddMimetypeToFilecache implements IRepairStep {

    private $mimeTypeLoader;

    public function __construct(IMimeTypeLoader $mimeTypeLoader) {
        $this->mimeTypeLoader = $mimeTypeLoader;
    }

    public function getName() {
        return 'Add custom mimetype to filecache';
    }

    public function run(IOutput $output) {
        // And update the filecache for it.
        $mimetypeId = $this->mimeTypeLoader->getId('application/x-kdbx');
        $this->mimeTypeLoader->updateFilecache('kdbx', $mimetypeId);
        $output->info('Added custom mimetype to filecache.');
    }
}
