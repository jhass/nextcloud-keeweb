<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;

class RegisterMimeType extends MimeTypeMigration
{
    public function getName()
    {
        return 'Register MIME type for "application/x-kdbx"';
    }

    private function registerForExistingFiles()
    {
        $mimeTypeId = $this->mimeTypeLoader->getId('application/x-kdbx');
        $this->mimeTypeLoader->updateFilecache('kdbx', $mimeTypeId);
    }

    private function registerForNewFiles()
    {
        $mapping = ['kdbx' => ['application/x-kdbx']];
        $mappingFile = \OC::$configDir . self::CUSTOM_MIMETYPEMAPPING;

        if (file_exists($mappingFile)) {
            $existingMapping = json_decode(file_get_contents($mappingFile), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($existingMapping)) {
                $mapping = array_merge($existingMapping, $mapping);
            }
        }

        file_put_contents($mappingFile, json_encode($mapping, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function run(IOutput $output)
    {
        $output->info('Registering the mimetype...');

        // Register the mime type for existing files
        $this->registerForExistingFiles();

        // Register the mime type for new files
        $this->registerForNewFiles();

        $output->info('The mimetype was successfully registered.');
    }
}
