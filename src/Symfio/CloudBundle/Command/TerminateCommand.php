<?php

namespace Symfio\CloudBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TerminateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('symfio:cloud:terminate')
            ->setDefinition(array(
                new InputArgument('owner', InputArgument::REQUIRED, 'Owner for the project'),
                new InputArgument('repo', InputArgument::REQUIRED, 'Project repository'),
            ))
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Amount of instances', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectManager = $this->getContainer()->get('symfio.website.project.manager');
        
        $project = $projectManager->findProjectByOwnerAndRepo(
            $input->getArgument('owner'),
            $input->getArgument('repo')
        );

        if (!$project) {
            throw new \InvalidArgumentException(sprintf('The project with owner "%s" and repo "%s" does not exist.', $input->getArgument('owner'), $input->getArgument('repo')));
        }
        
        $instances = $projectManager->terminate($project, $input->getOption('amount'));

        if (0 === count($instances)) {
            return $output->writeln('Not one instance not terminated.');
        }

        $ids = array();
        foreach ($instances as $instance) {
            $ids[] = $instance->getCloudInstanceId();
        }            

        $output->writeln(sprintf('For project terminated instances with id %s.', implode($ids, ',')));
    }
}
