<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/latest_news", name="latest_news")
     */
    public function latestNews(): Response
    {
        $date = new \DateTime();
        return $this->render('latest_news.html.twig', ['date' => $date]);
    }

    /**
     * @Route("/contact")
     */
    public function contact(): Response
    {
        $response = $this->render('contact.html.twig');

        $response->setPublic();
        $response->setMaxAge(100);

        return $response;
    }
}
