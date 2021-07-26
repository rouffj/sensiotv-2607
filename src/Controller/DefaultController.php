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
     * @Route("/contact")
     */
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}
