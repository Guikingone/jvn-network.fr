<?php

namespace CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlogCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('blog:create')
            ->setDescription('Allow to create a Blog.')
            ->addArgument(
                        'name',
                        InputArgument::REQUIRED,
                        'Veuillez saisir le nom du blog à créer :'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $command = $this->getApplication()->find('generate:controller');
        $arguments = array(
            'command'              => 'generate:controller',
            '--controller'         => 'CoreBundle:' . $name,
            '--route-format'       => 'annotation',
            '--template-format'    => 'twig',
            '--actions'            => [
                'actionName'       => [
                    'name'         => 'indexAction',
                    'route'        => '/blog/' . $name . '/home',
                    'placeholders' => null,
                    'template'     => 'CoreBundle:' . $name . ':index.html.twig',
                ]
            ]
        );
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }
}
