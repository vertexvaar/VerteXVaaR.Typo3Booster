<?php
namespace VerteXVaaR\Booster\Service;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class CodeGenerationService
 */
class CodeGenerationService
{
    /**
     * @var string
     */
    protected $versionTemplatePath = '';

    /**
     * @var string
     */
    protected $wrapperTemplateFile = '';

    /**
     * CodeGenerationService constructor.
     */
    public function __construct()
    {
        $path = ExtensionManagementUtility::extPath('booster') . 'Resources/Private/PHP/';
        $this->wrapperTemplateFile = $path . 'Wrapper/template.php';

        if (is_dir($path . TYPO3_version)) {
            $path .= TYPO3_version;
        } elseif (is_dir($path . TYPO3_branch)) {
            $path .= TYPO3_branch;
        } else {
            throw new \LogicException('No code template found for your TYPO3 version');
        }
        $this->versionTemplatePath = $path . '/';
    }

    /**
     * @param string $context
     * @param string $targetFile
     * @return mixed
     */
    public function generateCode($context, $targetFile)
    {
        return str_replace('{target_file}', $targetFile, $this->getWrappedCode($context));
    }

    /**
     * @param string $context
     * @return string
     */
    protected function getWrappedCode($context)
    {
        return str_replace(
            [
                '{script_path}',
                '{autoload_file}',
                '{template_code}',
            ],
            [
                $this->getScriptPath($context),
                PATH_site . 'vendor/autoload.php',
                str_replace('<?php', '', $this->getTemplateCode($context)),
            ],
            $this->getFileContent($this->wrapperTemplateFile)
        );
    }

    /**
     * @param string $context
     * @return string
     */
    protected function getScriptPath($context)
    {
        switch ($context) {
            case 'Frontend':
                return PATH_site . 'index.php';
            case 'Backend':
                return PATH_typo3 . 'index.php';
            default:
                throw new \InvalidArgumentException(sprintf('The context %s has no script path!', $context));
        }
    }


    /**
     * @param string $context
     * @return string
     */
    protected function getTemplateCode($context)
    {
        return $this->getFileContent($this->versionTemplatePath . $context . '/template.php');
    }

    /**
     * @param string $file
     * @return string
     */
    protected function getFileContent($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File %s not be found!', $file), 1462534241);
        }
        return file_get_contents($file);
    }
}
