<?php

namespace App\Command;

use App\Omdb\OmdbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSearchCommand extends Command
{
    protected static $defaultName = 'app:movie:search';
    protected static $defaultDescription = 'Cette commande permet de rechercher tous les films contenant le mot-clé.';
    /**
     * @var OmdbClient
     */
    private $omdbClient;

    public function __construct(OmdbClient $omdbClient)
    {
        $this->omdbClient = $omdbClient;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::OPTIONAL, 'Titre du film à chercher dans le catalogue IMDB')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'Type de media à afficher (movie, game...) ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$title = $input->getArgument('title')) {
            $title = $io->ask('Quel film souhaitez vous chercher ?', 'Matrix', function($answer) {
                if (strlen($answer) < 5) {
                    throw new \InvalidArgumentException('The search is too short');
                };

                return $answer;
            });
        }

        if (!$type = $input->getOption('type')) {
            $type = $io->choice('Quel type de media voulez-vous afficher ?', ['movie', 'series', 'episode', 'game', 'tous']);
        }

        $io->title('Film recherché: ' . $title);
        $searchOptions = ('tous' === $type) ? [] : ['type' => $type];
        $movies = $this->omdbClient->requestBySearch($title, $searchOptions)['Search'];

        $rows = [];
        $io->progressStart(count($movies));
        foreach ($movies as $movie) {
            $rows[] = [$movie['Title'], $movie['Year'], $movie['Type'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/'];
            usleep(100000);
            $io->progressAdvance(1);
        }
        $io->write("\r");
        //$io->progressFinish();

        $io->table(['Titre', 'Sortie en', 'Type', 'Fiche film'], $rows);
        //dump($movies);

        return Command::SUCCESS;
    }
}
