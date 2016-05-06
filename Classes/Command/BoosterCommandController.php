<?php
namespace VerteXVaaR\Booster\Command;

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
     * BoosterCommandController constructor.
     */
    public function __construct()
    {
        $this->classPreLoadService = new ClassPreLoadService();
    }

    /**
     *
     */
    public function frontendCommand()
    {
        $this->classPreLoadService->generate('Frontend');
    }

    /**
     *
     */
    public function backendCommand()
    {
        $this->classPreLoadService->generate('Backend');
    }
}
