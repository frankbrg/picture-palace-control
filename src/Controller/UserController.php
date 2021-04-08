<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Radarr;

class UserController extends AbstractController
{
    /**
     * @Route("/library", name="user_library")
     */
    public function index(Radarr $radarr): Response
    {
        

        return $this->render('user/index.html.twig', [
            'movies' => $radarr->getMovies(),
        ]);
    }
}
