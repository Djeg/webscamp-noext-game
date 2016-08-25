<?php

namespace App\Symfony\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Nelmio\Alice\Fixtures;

class LoadFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:fixtures:load')
            ->setDescription('Load a set of data fixtures')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Loadind data fixtures ...');

        $fallbackPath = sprintf(
            '%s/config/fixtures/orm/fixtures.yml',
            $this->getContainer()->getParameter('kernel.root_dir')
        );
        $path = sprintf(
            '%s/config/fixtures/orm/fixtures.%s.yml',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $this->getContainer()->getParameter('kernel.environment')
        );
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $data = file_exists($path) ?
            Fixtures::load($path, $manager) :
            Fixtures::load($fallbackPath, $manager)
        ;

        foreach ($data as $persistentObject) {
            $manager->persist($persistentObject);
        }

        $manager->flush();

        $output->writeln('DONE !');
    }
}
