<?php

/*
 * This file is part of the "PHP Project Stat" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use App\Author\ClassAuthorAnalyzer;
use App\Analyzers\ClassesAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for getting information about how many classes/interfaces/traits
 * was created by some developer.
 *
 * Example of usage
 * ./bin/console stat:class-author vldmr.kuprienko@gmail.com
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
final class ClassesStatisticCommand extends Command
{
    private $analyzer;

    /**
     * {@inheritdoc}
     */
    public function __construct(ClassesAnalyzer $analyzer, string $name = null)
    {
        $this->analyzer = $analyzer;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stat:class')
            ->setDescription('Shows statistic about classes')
            ->addArgument(
                'className',
                InputArgument::REQUIRED,
                'Name of needed class'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $className = $input->getArgument('className');

        $data = $this->analyzer->analyze($className);

        $output->writeln(
            $data);
    }
}
