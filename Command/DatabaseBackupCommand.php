<?php

namespace EB\DoctrineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Class DatabaseBackupCommand
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DatabaseBackupCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('eb:doctrine:database-backup')
            ->addArgument('path', InputArgument::OPTIONAL, 'Backup dir')
            ->setDescription('Backup database');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Path must be rightable
        $fs = $this->getContainer()->get('filesystem');
        $path = $input->getArgument('path') ?: $this->getContainer()->get('kernel')->getRootDir() . '/backup';
        $fs->mkdir($path);

        // Which backup command
        $which = new Process('which "mysqldump"');
        $which->run();
        if (false === $which->isSuccessful()) {
            $output->writeln('<error>"mysqldump" command not found</error>');

            return 1;
        }
        $mysqldump = trim($which->getOutput());

        // Find doctrine configuration
        $co = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getConnection();

        // Prepare backup command
        $backup = new Process(sprintf(
            '%s -h "%s" -P "%s" -u "%s"%s "%s" > "%s"',
            $mysqldump,
            $co->getHost(),
            $co->getPort() ?: 3306,
            $co->getUsername(),
            $co->getPassword() ? sprintf(' -p"%s"', $co->getPassword()) : '',
            $co->getDatabase(),
            $backupFile = sprintf('%s/%s.sql', $path, date('Ymd-His'))
        ));
        $backup->setTimeout(null);

        // Execute backup
        $code = $backup->run();
        if ($backup->isSuccessful()) {
            $output->writeln(sprintf('<info>%s</info>', $backupFile));

            return 0;
        }

        return $code;
    }
}
