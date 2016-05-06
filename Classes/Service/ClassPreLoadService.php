<?php
namespace VerteXVaaR\Booster\Service;

use Symfony\Component\Process\PhpProcess;

/**
 * Class ClassPreLoadService
 */
class ClassPreLoadService
{
    /**
     * @var CodeGenerationService
     */
    protected $codeGenerationService = null;

    /**
     * @var TemporaryFileService
     */
    protected $temporaryFileService = null;

    /**
     * ClassPreLoadService constructor.
     */
    public function __construct()
    {
        $this->codeGenerationService = new CodeGenerationService();
        $this->temporaryFileService = new TemporaryFileService();
    }

    /**
     * @param $context
     */
    public function generate($context)
    {
        $targetFile = $this->temporaryFileService->get($context);

        $code = $this->codeGenerationService->generateCode($context, $targetFile);

        $process = new PhpProcess($code, PATH_site);
        $process->run();

        $files = require($targetFile);

        $this->generatePreLoadFile($context, $files);
    }

    /**
     * @param string $context
     * @param array $files
     */
    protected function generatePreLoadFile($context, array $files)
    {
        $tempFile = $this->temporaryFileService->get('pre_move_' . $context);
        $handle = fopen($tempFile, 'w');

        fwrite($handle, '<?php' . PHP_EOL);

        $classPreLoader = (new \ClassPreloader\Factory())->create();
        foreach ($files as $file) {
            fwrite($handle, $classPreLoader->getCode($file, false) . PHP_EOL);
        }
        fclose($handle);

        // This path is hardcoded to guarantee atomicity through rename. The PhpFrontend is not capable of such a method
        // But the path reflects the exact file location where the cache would be written according to the configuration
        rename($tempFile, PATH_site . 'typo3temp/Cache/Code/cache_preload/' . $context . '.php');
        touch($tempFile);
    }
}
