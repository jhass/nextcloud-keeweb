<?php

namespace OCA\Keeweb\Migration;

use OCP\Migration\IOutput;

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
        $configDir = \OC::$configDir;
        $mimetypealiasesFile = $configDir . self::CUSTOM_MIMETYPEALIASES;
        $mimetypemappingFile = $configDir . self::CUSTOM_MIMETYPEMAPPING;

        $this->removeFromFile($mimetypealiasesFile, ['application/x-kdbx' => 'kdbx']);
        $this->removeFromFile($mimetypemappingFile, ['kdbx' => ['application/x-kdbx']]);
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


    private function removeFromFile(string $filename, array $data) {
        $obj = [];
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $obj = json_decode($content, true);
        }
        foreach ($data as $key => $value) {
            unset($obj[$key]);
        }
        file_put_contents($filename, json_encode($obj,  JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
}
