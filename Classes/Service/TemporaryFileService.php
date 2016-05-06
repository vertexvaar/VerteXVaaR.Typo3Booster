<?php
namespace VerteXVaaR\Typo3Booster\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TemporaryFileService
 */
class TemporaryFileService
{
    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $streams = [];

    /**
     * @param string $key
     * @return string File identifier of the temporary file
     */
    public function get($key)
    {
        if (!isset($this->files[$key])) {
            $file = GeneralUtility::tempnam($key, '.php');
            register_shutdown_function('unlink', $file);
            $this->files[$key] = $file;
        }
        return $this->files[$key];
    }
}
