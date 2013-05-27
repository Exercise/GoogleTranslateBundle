<?php
namespace Exercise\GTranslateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class GTranslateCommand extends ContainerAwareCommand
{
    protected $progress;

    protected function configure()
    {
        $this
            ->setName('gtranslate:translate')
            ->setDefinition(array(
                new InputArgument('localeFrom', InputArgument::REQUIRED, 'The locale'),
                new InputArgument('localeTo', InputArgument::REQUIRED, 'The locale'),
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle where to load the messages'),
            ))
            ->setDescription('translate message files in your project with Google Translate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $foundBundle = $this->getApplication()->getKernel()->getBundle($input->getArgument('bundle'));
        $bundleTransPath = $foundBundle->getPath().'/Resources/translations';

        if (!is_dir($bundleTransPath)) {
            throw new \Exception('Bundle has no translation message!');
        }

        $output->writeln(sprintf('Generating "<info>%s</info>" translation files for "<info>%s</info>"', $input->getArgument('localeTo'), $foundBundle->getName()));

        $array = Yaml::parse($bundleTransPath.'/messages.'.$input->getArgument('localeFrom').'.yml');

        $count = count($array, COUNT_RECURSIVE);
        $this->progress = $this->getHelperSet()->get('progress');
        $this->progress->start($output, $count);

        $array = $this->translateArray($array, $input->getArgument('localeFrom'), $input->getArgument('localeTo'));

        $this->progress->finish();

        $file = $bundleTransPath.'/messages.'.$input->getArgument('localeTo').'.yml';


        $output->writeln(sprintf('Creating "<info>%s</info>" file', 'messages.'.$input->getArgument('localeTo').'.yml'));
        file_put_contents($file, Yaml::dump($array, 100500));

        $output->writeln(sprintf('Translate is success!'));
    }

    /**
     * Recursive translate array value message
     *
     * @param $array array
     * @param $langFrom string
     * @param $langTo string
     * @return array
     */
    public function translateArray($array, $langFrom, $langTo)
    {
        $translator = $this->getContainer()->get('exercise_g_translate.translator');

        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $array[$key] = $this->translateArray($value, $langFrom, $langTo);
            } else {
                $array[$key] = $translator->translateString($value, $langFrom, $langTo);
            }

            $this->progress->advance();
        }

        return $array;
    }
}
