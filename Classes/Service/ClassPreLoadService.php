<?php
namespace VerteXVaaR\Booster\Service;

use Symfony\Component\Process\PhpProcess;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

/**
 * Class ClassPreLoadService
 */
class ClassPreLoadService
{
    /**
     * @var ConsoleOutput
     */
    protected $output = null;

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
    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
        $this->codeGenerationService = new CodeGenerationService();
        $this->temporaryFileService = new TemporaryFileService();
    }

    /**
     * @param string $context
     * @return bool
     */
    public function generate($context)
    {
        $this->output->outputLine('Fetching temporary file');
        $targetFile = $this->temporaryFileService->get($context);

        $this->output->outputLine(sprintf('Generating mock code for %s', $context));
        $code = $this->codeGenerationService->generateCode($context, $targetFile);

        $this->output->outputLine('Executing mock code');
        $process = new PhpProcess($code, PATH_site);
        $process->run();

        $this->output->outputLine('Acquiring file list for preload');
        $files = require($targetFile);

        $this->output->outputLine('Generating preload file');
        return $this->generatePreLoadFile($context, $files);
    }

    /**
     * @param string $context
     * @param array $files
     * @return bool
     */
    protected function generatePreLoadFile($context, array $files)
    {
        $filesCount = count($files);

        $tempFile = $this->temporaryFileService->get('pre_move_' . $context);
        $handle = fopen($tempFile, 'w');

        fwrite($handle, '<?php' . PHP_EOL);

        $classPreLoader = (new \ClassPreloader\Factory())->create();
        foreach ($files as $index => $file) {
            $this->output->outputLine(sprintf('Writing class %d of %d', ($index + 1), $filesCount));
            fwrite($handle, $classPreLoader->getCode($file, false) . PHP_EOL);
        }
        fclose($handle);

        $this->output->outputLine('Finished class concatenation. Moving preload file to destined location.');
        // This path is hardcoded to guarantee atomicity through rename. The PhpFrontend is not capable of such a method
        // But the path reflects the exact file location where the cache would be written according to the configuration

        $this->output->outputLine('Testing the generated code.');
        if (!$this->isValidPhpFile($tempFile)) {
            $this->output->outputLine('The preload file generation failed. Aborting!');
            return false;
        }
        $this->output->outputLine('Success.');

        rename($tempFile, PATH_site . 'typo3temp/Cache/Code/cache_preload/' . $context . '.php');
        touch($tempFile);
        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    protected function isValidPhpFile($file)
    {
        $process = new PhpProcess(file_get_contents($file));
        $code = $process->run();
        return (empty($process->getErrorOutput()) && 0 === $code);
    }
}
