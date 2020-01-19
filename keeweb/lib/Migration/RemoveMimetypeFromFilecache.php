<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;

class RemoveMimetypeFromFilecache implements IRepairStep {

    private $mimeTypeLoader;

    public function __construct(IMimeTypeLoader $mimeTypeLoader) {
        $this->mimeTypeLoader = $mimeTypeLoader;
    }

    public function getName() {
        return 'Remove custom mimetype from filecache';
    }

    public function run(IOutput $output) {
        $mimetypeId = $this->mimeTypeLoader->getId('application/octet-stream');
        $this->mimeTypeLoader->updateFilecache('kdbx', $mimetypeId);
        $output->info('Removed custom mimetype from filecache.');
    }
}
