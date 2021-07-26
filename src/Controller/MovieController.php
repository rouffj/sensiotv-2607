<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
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

    public function __construct(HttpClientInterface $httpClient)
    {
        $apiKey = '28c5b7b1';
        $omdbHost = 'https://www.omdbapi.com/';
        $this->omdbClient = new OmdbClient($httpClient, $apiKey, $omdbHost);
    }

    /**
     * @Route("/{imdbId}", name="details", requirements={"imdbId": "tt\d+"})
     */
    public function details($imdbId): Response
    {
        $movie = $this->omdbClient->requestById($imdbId);
        dump($movie);

        return $this->render('movie/details.html.twig', ['movie' => $movie]);
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
