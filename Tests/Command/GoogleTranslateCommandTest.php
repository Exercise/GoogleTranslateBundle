<?php
namespace Exercise\GoogleTranslateBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exercise\GoogleTranslateBundle\Command\GoogleTranslateCommand;
use Symfony\Component\Yaml\Yaml;

class GoogleTranslateCommandTest extends WebTestCase
{
    protected $expectedArray = array
    (
        'food' => array
        (
            'title' => '%tasty% Еды',
            'fruit' => array
                (
                    'apple' => '%green% Яблок',
                    'lemon' => '%yellow% Лимоном',
                    'orange' => 'оранжевый',
                    'banana' => '%yellow% Банан',
                ),
            'vegetable' => array
                (
                    'eggplant' => 'баклажан',
                    'potato' => 'картофель',
                    'cabbage' => 'капуста',
                ),
        ),
    );

    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new GoogleTranslateCommand());

        $command = $application->find('gtranslate:translate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'localeFrom' => 'en',
            'localeTo' => 'ru',
            'bundle' => 'ExerciseGoogleTranslateBundle',
            '--override' => true
            )
        );

        $actualArray = $this->getArrayFromMessageBundle('ExerciseGoogleTranslateBundle', 'ru');

        $this->assertEquals($this->expectedArray, $actualArray);
    }

    protected function getArrayFromMessageBundle($bundle, $locate)
    {
        $client = parent::createClient();
        $kernel = $client->getKernel();

        $foundBundle = $kernel->getBundle($bundle);
        $bundleTransPath = $foundBundle->getPath().'/Resources/translations';

        return $array = Yaml::parse($bundleTransPath.'/messages.'.$locate.'.yml');
    }
}