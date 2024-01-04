<?php

namespace OCA\Keeweb\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IRepairStep;

abstract class MimeTypeMigration implements IRepairStep
{
    const CUSTOM_MIMETYPEMAPPING = 'mimetypemapping.json';
    const CUSTOM_MIMETYPEALIASES = 'mimetypealiases.json';

    protected $mimeTypeLoader;
    protected $updateJS;

    public function __construct(IMimeTypeLoader $mimeTypeLoader)
    {
        $this->mimeTypeLoader = $mimeTypeLoader;
    }
}
