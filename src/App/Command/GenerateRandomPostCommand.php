<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateRandomPostCommand extends Command
{
    protected static $defaultName = 'app:generate-random-post';
    protected static $defaultDescription = 'Run app:generate-random-post';

    private EntityManagerInterface $em;
    private LoremIpsum $loremIpsum;

    public function __construct(EntityManagerInterface $em, LoremIpsum $loremIpsum, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->loremIpsum = $loremIpsum;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $title = $this->loremIpsum->words(mt_rand(4, 6));
        $content = $this->loremIpsum->paragraphs(2);

        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);

        $this->em->persist($post);
        $this->em->flush();

        $output->writeln('A random post has been generated.');

        return Command::SUCCESS;
    }
}
