<?php declare(strict_types=1);

namespace App\Tests\Integration\Command;

use App\Client\GoogleSpreadSheetClient;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ReadFileCommandTest extends KernelTestCase
{
    public function testExecuteFail()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:read-file');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['src' => 'asdf',]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString(
            'fopen(asdf): Failed to open stream: No such file or directory',
            $output
        );
    }

    public function testExecuteSuccess()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:read-file');
        $commandTester = new CommandTester($command);

        $googleSpreadSheetClientMock = $this
            ->getMockBuilder(GoogleSpreadSheetClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveSheet'])
            ->getMock();

        $command->googleSpreadSheetClient = $googleSpreadSheetClientMock;

        $commandTester->execute(['src' => '',]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Xml file was read', $output);
        $this->assertStringContainsString('Rows were transformed before save', $output);
        $this->assertStringContainsString('Spreadsheet was saved', $output);
    }
}
