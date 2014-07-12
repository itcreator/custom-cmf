<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Server\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class StartServerCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('server:start')
            ->setDescription('Starts PHP development built-in web server. <comment>Not for production.</comment>')
            ->setDefinition(array(
                new InputArgument('host', InputArgument::OPTIONAL, 'Address', 'localhost'),
                new InputArgument('port', InputArgument::OPTIONAL, 'Port', '8000'),
            ))
            ->setHelp(<<<EOT
<comment>Don't use this server in production</comment>
For starting of the server run the command:
<info>bin/console server:start</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host =  $input->getArgument('host');
        $port = $input->getArgument('port');

        $output->writeln(sprintf("Server starting  on <info>http://%s:%s</info>", $host, $port));

        chdir(PUBLIC_DIR);
        $command = sprintf('"%s" -S %s:%s -t "%s" ../bin/server.php', PHP_BINARY, $host, $port, PUBLIC_DIR);
        passthru($command);
    }
}
