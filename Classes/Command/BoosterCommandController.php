<?php
namespace VerteXVaaR\Typo3Booster\Command;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use VerteXVaaR\Typo3Booster\Service\ClassPreLoadService;

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
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public function initializeObject()
    {
        $this->classPreLoadService = new ClassPreLoadService($this->output);
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
            if ($this->classPreLoadService->generate($context)) {
                $this->output->outputLine('Done.');
            } else {
                $this->output->outputLine('<error>Failure.</error>');
            }
        } else {
            $this->outputLine('Skipping preload because it\'s already present. Use --force to overwrite it.');
        }
    }
}
