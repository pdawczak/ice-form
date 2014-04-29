<?php

namespace Ice\FormTestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Runs Symfony2 application using PHP built-in web server
 *
 * Overrides the default implementation to provide the -xdebug configuration option, enabling the setting of an xdebug
 * remote host. Useful when we need to tunnel to the server, which breaks remote_connect_back.
 *
 * @author Michał Pipa <michal.pipa.xsolve@gmail.com>
 */
class ServerRunCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            return false;
        }

        return parent::isEnabled();
    }

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', 'localhost:8000'),
                new InputOption('docroot', 'd', InputOption::VALUE_REQUIRED, 'Document root', 'web/'),
                new InputOption('router', 'r', InputOption::VALUE_REQUIRED, 'Path to custom router script'),
                new InputOption('xdebug', 'x', InputOption::VALUE_REQUIRED, 'XDebug remote host'),
            ))
            ->setName('server:run')
            ->setDescription('Runs PHP built-in web server')
            ->setHelp(<<<EOF
The <info>%command.name%</info> runs PHP built-in web server:

  <info>%command.full_name%</info>

To change default bind address and port use the <info>address</info> argument:

  <info>%command.full_name% 127.0.0.1:8080</info>

To change default docroot directory use the <info>--docroot</info> option:

  <info>%command.full_name% --docroot=htdocs/</info>

If you have custom docroot directory layout, you can specify your own
router script using <info>--router</info> option:

  <info>%command.full_name% --router=app/config/router.php</info>

See also: http://www.php.net/manual/en/features.commandline.webserver.php
EOF
            )
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $input->getOption('router') ?: $this
            ->getContainer()
            ->get('kernel')
            ->locateResource('@FrameworkBundle/Resources/config/router.php')
        ;

        $commandLineOptions = array(PHP_BINARY, '-S', $input->getArgument('address'));

        $output->writeln(sprintf("Server running on <info>%s</info>", $input->getArgument('address')));

        if ($xDebugHost = $input->getOption('xdebug')) {
            $commandLineOptions = array_merge($commandLineOptions, [
                '-d', 'xdebug.remote_host=10.1.1.48',
                '-d', 'xdebug.remote_connect_back=0',
                '-d', 'xdebug.remote_autostart=1'
            ]);
            $output->writeln(sprintf("XDebug host configured to <info>%s</info>\n", $xDebugHost));
        } else {
            $output->writeln(sprintf("XDebug host not set (use -x)\n", $xDebugHost));
        }

        $commandLineOptions[] = $router;

        $builder = new ProcessBuilder($commandLineOptions);
        $builder->setWorkingDirectory($input->getOption('docroot'));
        $builder->setTimeout(null);
        $builder->getProcess()->run(function ($type, $buffer) use ($output) {
            if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
                $output->write($buffer);
            }
        });
    }
}
