<?php

namespace OCA\Keeweb\Migration;

require \OC::$SERVERROOT . "/3rdparty/autoload.php";

use OCP\Migration\IOutput;

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
        $configDir = \OC::$configDir;
        $mimetypealiasesFile = $configDir . self::CUSTOM_MIMETYPEALIASES;
        $mimetypemappingFile = $configDir . self::CUSTOM_MIMETYPEMAPPING;

        $this->appendToFile($mimetypealiasesFile, ['application/x-kdbx' => 'kdbx']);
        $this->appendToFile($mimetypemappingFile, ['kdbx' => ['application/x-kdbx']]);
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

    private function appendToFile(string $filename, array $data) {
        $obj = [];
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $obj = json_decode($content, true);
        }
        foreach ($data as $key => $value) {
            $obj[$key] = $value;
        }
        file_put_contents($filename, json_encode($obj,  JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
}