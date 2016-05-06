<?php
namespace VerteXVaaR\Booster\Command;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use VerteXVaaR\Booster\Service\ClassPreLoadService;

/**
 * Class BoosterCommandController
 */
class BoosterCommandController extends CommandController
{
    /**
     * @var ClassPreLoadService
     */
    protected $classPreLoadService = null;

    /**
     * @var FrontendInterface
     */
    protected $cache = null;

    /**
     * BoosterCommandController constructor.
     */
    public function __construct()
    {
        $this->classPreLoadService = new ClassPreLoadService();
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_preload');
    }

    /**
     * @param bool $force
     */
    public function frontendCommand($force = false)
    {
        $this->run($force, 'Frontend');
    }

    /**
     * @param bool $force
     */
    public function backendCommand($force = false)
    {
        $this->run($force, 'Backend');
    }

    /**
     * @param bool $force
     * @param string $context
     */
    protected function run($force, $context)
    {
        if (true === $force || !$this->cache->has($context)) {
            $this->outputLine(sprintf('Generating class preload file for %s', $context));
            $this->classPreLoadService->generate($context);
        } else {
            $this->outputLine('Skipping preload because it\'s already present. Use --force to overwrite it.');
        }
    }
}
