<?php

namespace App\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetIndexCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $command = $application->find('analyzer:get-index');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'num'      => 7,
            'array'    => '1,7,24,12,5,4,7,2,42,85',
            'user'     => null
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('index: 8', $output);
    }
}