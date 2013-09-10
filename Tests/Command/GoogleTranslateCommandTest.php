<?php
namespace Exercise\GoogleTranslateBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exercise\GoogleTranslateBundle\Command\GoogleTranslateCommand;
use Exercise\GoogleTranslateBundle\Tests\App\AppKernel;
use Symfony\Component\Yaml\Yaml;

class GoogleTranslateCommandTest extends WebTestCase
{
    static $class = 'Exercise\GoogleTranslateBundle\Tests\App\AppKernel';

    protected $expectedArray = array
    (
        'food' => array
        (
            'title' => '%tasty% еду',
            'fruit' => array
                (
                    'apple' => '%green% яблоко',
                    'lemon' => '%yellow% лимона',
                    'orange' => 'оранжевый',
                    'banana' => '%yellow% банан',
                ),
              'vegetable' => array
                (
                    'eggplant' => 'баклажан',
                    'potato' => 'картофель',
                    'cabbage' => 'капуста',
                ),
        ),
        'pluralization' => array
        (
            'one' => '{0}Там нет яблок|[20,Inf]Есть много яблок|Существует одно яблоко|a_few:Есть %count% яблоками',
            'two' => '{0}Там нет яблок|{1}Существует одно яблоко|]1,19]Есть %count% яблоками|[20,Inf]Есть много яблок',
        )
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