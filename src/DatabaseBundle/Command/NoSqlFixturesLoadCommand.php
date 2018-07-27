<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/07/17
 * Time: 16:33
 */

namespace DatabaseBundle\Command;

use DatabaseBundle\DataFixtures\NoSql\LoadFixtures;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class DatabaseFixturesCommand
 * @package DatabaseBundle\Command
 */
class NoSqlFixturesLoadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:nosql:fixtures:load')
            ->setDescription('Load data fixtures to your NoSql database.')
            ->setHelp('This command allows you to load the configured NoSql data...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $input->getOption('no-interaction')) {
            $message = '<question>Continue with this action? [y/N]</question> ';

            if (! $this->confirm($input, $output, $message)) {
                return;
            }

            $output->writeln('');
        }

        try {
            $output->write('Loading data fixtures...');

            LoadFixtures::load(
                $this->getContainer()->get('doctrine.orm.default_entity_manager'),
                $this->getContainer()->get('nosql.database.connection.provider'),
                $this->getContainer()->get('gearman.taskmanager'),
                LoadFixtures::FIXTURES_CONF
            );

            $output->writeln(' <fg=green>done</>');
        } catch (\Throwable $e) {
            $output->writeln(PHP_EOL);
            $output->writeln('<error>Failed to load NoSql data fixtures.</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $message
     * @param bool $default
     * @return bool
     */
    protected function confirm(
        InputInterface $input,
        OutputInterface $output,
        string $message,
        bool $default = false
    ): bool {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            $message,
            $default
        );

        return $helper->ask($input, $output, $question);
    }
}
