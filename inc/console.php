<?php

use CLB\Core\Console\Commands\MigrationCreateCommand;
use CLB\Core\Console\Commands\MigrationRunCommand;
use Symfony\Component\Console\Application;

require_once realpath(dirname(__FILE__,2)) . '/vendor/autoload.php';
const CLB_CONSOLE = 1;

$application = new Application();
$application->setName('Collab Consolea');

// ... register commands
$application->addCommands([
    new MigrationRunCommand(),
    new MigrationCreateCommand()
]);

try {
    //$argv = $_SERVER['argv'];
    //dump($argv);
    $application->run();
} catch (Exception $e) {
    //log
}

