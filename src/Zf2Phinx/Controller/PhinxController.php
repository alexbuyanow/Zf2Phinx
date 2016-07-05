<?php

namespace Zf2Phinx\Controller;

use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zf2Phinx\Module;
use Zf2Phinx\Service\Zf2PhinxService;

/**
 * Phinx console controller
 */
class PhinxController extends AbstractConsoleController
{
    /**
     * Phinx service
     *
     * @var Zf2PhinxService
     */
    private $phinxService;

    /**
     * @param Zf2PhinxService $phinxService
     */
    public function __construct(Zf2PhinxService $phinxService)
    {
        $this->phinxService = $phinxService;
    }

    public function indexAction()
    {
    }

    /**
     * Runs 'Test' command
     *
     * @return void
     */
    public function testAction()
    {
        $this->writeModuleInfo();
        $this->getPhinxService()->runTestCommand($this->getArgvArray());
    }

    /**
     * Runs 'Create' command
     *
     * @return void
     */
    public function createAction()
    {
        $this->writeModuleInfo();
        $this->getPhinxService()->runCreateCommand($this->getArgvArray());
    }

    /**
     * Runs 'Migrate' command
     *
     * @return void
     */
    public function migrateAction()
    {
        $this->writeModuleInfo();
        $this->getPhinxService()->runMigrateCommand($this->getArgvArray());
    }

    /**
     * Runs 'Rollback' command
     *
     * @return void
     */
    public function rollbackAction()
    {
        $this->writeModuleInfo();
        $this->getPhinxService()->runRollbackCommand($this->getArgvArray());
    }

    /**
     * Runs 'Status' command
     *
     * @return void
     */
    public function statusAction()
    {
        $this->writeModuleInfo();
        $this->getPhinxService()->runStatusCommand($this->getArgvArray());
    }

    /**
     * Gets Phinx service
     *
     * @return Zf2PhinxService
     */
    private function getPhinxService()
    {
        return $this->phinxService;
    }

    /**
     * Get console command arguments
     *
     * @return array
     */
    private function getArgvArray()
    {
        $argvArray = $_SERVER['argv'];
        array_shift($argvArray);

        return $argvArray;
    }

    /**
     * Writes copyright & version into console
     *
     * @return void
     */
    private function writeModuleInfo()
    {
        $console = $this->getConsole();
        $console->write('Zf2Phinx by Alexey Buyanov. ', ColorInterface::GREEN);
        $console->write('version: ', ColorInterface::WHITE);
        $console->writeLine(Module::MODULE_VERSION, ColorInterface::YELLOW);
    }
}
