<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;

class RegisterMimeType implements IRepairStep
{
    private $mimeTypeLoader;

    public function __construct(IMimeTypeLoader $mimeTypeLoader)
    {
        $this->mimeTypeLoader = $mimeTypeLoader;
    }

    public function getName()
    {
        return 'Register MIME type for "application/x-kdbx"';
    }

    private function registerForExistingFiles()
    {
        $mimetypeId = $this->mimeTypeLoader->getId('application/x-kdbx');
        $this->mimeTypeLoader->updateFilecache('kdbx', $mimetypeId);
    }

    private function registerForNewFiles()
    {
        $mapping = ['kdbx' => ['application/x-kdbx']];
        $mappingFile = \OC::$configDir . 'mimetypemapping.json';

        if (file_exists($mappingFile)) {
            $existingMapping = json_decode(file_get_contents($mappingFile), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $mapping = array_merge($existingMapping, $mapping);
            }
        }

        file_put_contents($mappingFile, json_encode($mapping, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function run(IOutput $output)
    {
        $this->logger->info('Registering the mimetype...');

        // Register the mime type for existing files
        $this->registerForExistingFiles();

        // Register the mime type for new files
        $this->registerForNewFiles();

        $this->logger->info('The mimetype was successfully registered.');
    }
}
