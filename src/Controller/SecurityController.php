<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(RegisterType::class);

        // Bind les data qui se trouvent dans la request vers l'objet User
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->get('terms')->getData()) {
            /** @var $user User */
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);
        }


        return $this->render('security/register.html.twig', [
            'register_form' => $form->createView()
        ]);
    }
}
