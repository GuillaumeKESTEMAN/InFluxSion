<?php

namespace App\Command;

use App\Service\ArticleService;
use App\Repository\SourceRepository;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand(
    name: 'app:fetch-articles',
    description: 'Fetch and save articles from RSS sources',
)]
#[AsCronTask('* * * * *')]
class ArticleFetchTask extends Command
{
    public function __construct(
        private ArticleService $articleService,
        private SourceRepository $sourceRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fetching articles from RSS sources');

        $sources = $this->sourceRepository->findAll();

        foreach ($io->progressIterate($sources) as $source) {
            try {
                $this->articleService->fetchAndSaveArticlesFromRss($source);
            } catch(Exception $e) {}
            
        }

        $io->success(sprintf('Fetched articles from %d sources', count($sources)));

        return Command::SUCCESS;
    }
}