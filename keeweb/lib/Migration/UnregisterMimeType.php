<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;

class UnregisterMimeType extends MimeTypeMigration
{
    public function getName()
    {
        return 'Unregister MIME type for "application/x-kdbx"';
    }

    private function unregisterForExistingFiles()
    {
        $mimeTypeId = $this->mimeTypeLoader->getId('application/octet-stream');
        $this->mimeTypeLoader->updateFilecache('kdbx', $mimeTypeId);
    }

    private function unregisterForNewFiles()
    {
        $mappingFile = \OC::$configDir . self::CUSTOM_MIMETYPEMAPPING;

        if (file_exists($mappingFile)) {
            $mapping = json_decode(file_get_contents($mappingFile), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($mapping)) {
                unset($mapping['kdbx']);
            } else {
                $mapping = [];
            }
            file_put_contents($mappingFile, json_encode($mapping, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }

    public function run(IOutput $output)
    {
        $output->info('Unregistering the mimetype...');

        // Register the mime type for existing files
        $this->unregisterForExistingFiles();

        // Register the mime type for new files
        $this->unregisterForNewFiles();

        $output->info('The mimetype was successfully unregistered.');
    }
}
