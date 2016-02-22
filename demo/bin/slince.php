<?php
use App\AppKernel;
use Slince\Console\Console;

include __DIR__ . '/../config/bootstrap.php';

$kernel = new AppKernel();
$console = new Console();

$console->addCommands([
    new \Slince\Application\Command\ClearCommand($kernel)
]);
$console->run();
