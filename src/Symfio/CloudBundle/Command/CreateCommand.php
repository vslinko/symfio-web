<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfio\CloudBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Command that places bundle web assets into a given directory.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class CreateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('symfio:cloud:create')
            ->setDefinition(array(
                new InputArgument('owner', InputArgument::REQUIRED, 'Owner for the project'),
                new InputArgument('repo', InputArgument::REQUIRED, 'Project repository'),
                new InputArgument('type', InputArgument::REQUIRED, 'Type of instance for creation'),
            ))
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Amount of instances', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository('Symfio\WebsiteBundle\Entity\Project')
            ->findOneBy(array(
                'owner' => $input->getArgument('owner'),
                'repo'  => $input->getArgument('repo'),
            ));

        if (!$project) {
            throw new \InvalidArgumentException(sprintf('The project with owner "%s" and repo "%s" does not exist.', $input->getArgument('owner'), $input->getArgument('repo')));
        }
        
        $ids = array();
        $instances = $this->getContainer()
            ->get('symfio.cloud.manager')
            ->get('amazon')
            ->create($project, $input->getArgument('type'), $input->getOption('amount'));
            
        if (0 === count($instances)) {
            return $output->writeln('Not one instance not created.');
        }
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach ($instances as $instance) {
            $ids[] = $instance->getCloudInstanceId();
            $project->addInstance($instance);
            $em->persist($instance);
        }

        $em->flush();

        $output->writeln(sprintf('For project created instances with id %s.', implode($ids, ',')));
    }
}
