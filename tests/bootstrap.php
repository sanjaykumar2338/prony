<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;
use Prony\Tests\Setup\AppKernel;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

function bootstrap(): void
{
    $kernel = new App\Kernel('test', false);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:drop',
        '--force' => '1',
        '-q' => '1',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:create',
        '-q' => '1',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:schema:create',
        '--no-interaction' => '1',
        '-q' => '1',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:fixtures:load',
        '-n' => '1',
    ]));

    $kernel->shutdown();
}

bootstrap();