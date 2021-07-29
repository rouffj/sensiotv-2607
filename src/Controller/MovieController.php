<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @var OmdbClient
     */
    private $omdbClient;

    public function __construct(OmdbClient $omdbClient)
    {
        $this->omdbClient = $omdbClient;
    }

    /**
     * @Route("/{imdbId}", name="details", requirements={"imdbId": "tt\d+"})
     */
    public function details($imdbId, MovieRepository $movieRepository): Response
    {
        $movie = $this->omdbClient->requestById($imdbId);
        $movieFromDb = $movieRepository->findOneBy(['title' => 'Memento']);
        //dump($movie, $movieFromDb);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'movieFromDb' => $movieFromDb,
        ]);
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    /**
     * @Route("/top-rated", name="top_rated")
     */
    public function topRated(): Response
    {
        $movies = $this->omdbClient->requestBySearch('matrix', []);
        dump($movies);

        return $this->render('movie/top-rated.html.twig', ['movies' => $movies['Search']]);
    }

    /**
     * @Route("/genres", name="genres")
     */
    public function genres(): Response
    {
        return $this->render('movie/genres.html.twig');
    }

    /**
     * @Route("/latest", name="latest")
     */
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }
    
    /**
     * @Route("/api/movies", name="api_movies")
     */
    public function apiList(): Response
    {
        $movies = [
            ['name' => 'Matrix', 'releaseDate' => '2001'],
            ['name' => 'Intouchable', 'releaseDate' => '2014'],
        ];

        //$response = new Response(json_encode($movies));
        //$response->headers->set('Content-Type', 'application/json');

        //return $response;

        return $this->json($movies);
    }
}
