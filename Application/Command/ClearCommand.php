<?php
namespace Slince\Application\Command;

use Slince\Console\Context\Argument;
use Slince\Application\Exception\InvalidArgumentException;
use Slince\Console\Context\Argv;
use Slince\Console\Context\Io;

class ClearCommand extends Command
{

    protected $name = 'clear';

    function configure()
    {
        $this->addOption('type', Argument::VALUE_OPTIONAL, 'The tmp file type you want clear', null);
    }

    function execute(Io $io, Argv $argv)
    {
        $type = $argv->getOption('type');
        $paths = $this->getClearPaths();
        if (! empty($type)) {
            $paths = array_intersect_key($paths, array_flip(explode(',', $type)));
        }
        $this->clearPaths($paths);
        $io->writeln('Clear ok!');
    }

    function getClearPaths()
    {
        $kernel = $this->getKernel();
        return [
            'logs' => $kernel->getRootPath() . '/tmp/logs',
            'sessions' => $kernel->getRootPath() . '/tmp/sessions',
            'cache' => $kernel->getRootPath() . '/tmp/cache'
        ];
    }

    function clearPaths(array $paths)
    {
        foreach ($paths as $path) {
            if (! is_dir($path)) {
                throw new InvalidArgumentException('The directory "%s" is invalid', $path);
            }
            foreach (glob("{$path}/*") as $file) {
                @unlink($file);
            }
        }
    }
}
