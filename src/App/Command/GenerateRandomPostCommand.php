<?php
declare(strict_types=1);

namespace App\Command;

use App\Validator\PostValidator;
use Domain\Post\PostManager;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomPostCommand extends Command
{
    protected static $defaultName = 'app:generate-random-post';
    protected static $defaultDescription = 'Run app:generate-random-post generate <comment>basic</comment> or <comment>summary</comment> post';

    private const POST_TYPE_BASIC = 'basic';
    private const POST_TYPE_SUMMARY = 'summary';

    private PostManager $postManager;
    private LoremIpsum $loremIpsum;

    public function __construct(
        PostManager $postManager,
        LoremIpsum $loremIpsum,
        string $name = null
    ) {
        parent::__construct($name);
        $this->postManager = $postManager;
        $this->loremIpsum = $loremIpsum;
    }

    protected function configure(): void
    {
        $this->addOption(
            'post-type',
            null,
            InputOption::VALUE_OPTIONAL,
            'Post type, we can choose between basic and summary. The option takes the following values <comment>basic or summary</comment> default usage value ',
            self::POST_TYPE_BASIC
        )->setHelp(<<<'EOF'
The <info>%command.name%</info> command lists all commands:

  <info>%command.full_name%</info>
  
 You can run the commands for not option:

  <info>%command.full_name%</info>

You can run the commands for option <comment>basic</comment>:

  <info>%command.full_name% --post-type=basic</info>

You can run the commands for option <comment>summary</comment>:

  <info>%command.full_name% --post-type=summary</info>
EOF
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $optionValue = $input->getOption('post-type');
        if ($optionValue !== self::POST_TYPE_BASIC && $optionValue !== self::POST_TYPE_SUMMARY) {
            $output->writeln('<error>Post type is not supported</error>');
        }

        if ($optionValue === self::POST_TYPE_BASIC) {
            $title = $this->loremIpsum->words(mt_rand(4, 6));
            $content = $this->loremIpsum->paragraphs(2);

            if ($errors = PostValidator::validate($title, $content)) {
                $output->writeln('<error>Validation error</error>');
                foreach ($errors as $key => $values) {
                    foreach ($values as $value) {
                        $output->writeln("<info>{$key}</info> {$value}");
                    }
                }
                return Command::FAILURE;
            }

            $this->postManager->addPost($title, $content);

            $postTypeBasic = strtoupper(self::POST_TYPE_BASIC);
            $output->writeln("<info>[{$postTypeBasic}]</info> A random post has been generated.");
        }

        if ($optionValue === self::POST_TYPE_SUMMARY) {
            $title = sprintf('Summary %s', date('Y-m-d'));
            $content = $this->loremIpsum->paragraphs(1);

            if ($errors = PostValidator::validate($title, $content)) {
                $output->writeln('<error>Validation error</error>');
                foreach ($errors as $key => $values) {
                    foreach ($values as $value) {
                        $output->writeln("<info>{$key}</info> {$value}");
                    }
                }
                return Command::FAILURE;
            }

            $this->postManager->addPost($title, $content);

            $postTypeSummary = strtoupper(self::POST_TYPE_SUMMARY);
            $output->writeln("<info>[{$postTypeSummary}]</info> A summary post has been generated.");
        }

        return Command::SUCCESS;
    }
}
