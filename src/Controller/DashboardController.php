<?php

namespace App\Controller;

use App\Service\Movies;
use App\Form\QueryMovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request): Response
    {
        $movie = new Movies();
        $form = $this->createForm(QueryMovieType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $movie = $form->getData()['movie'];
            $response = Movies::getMovieByName($movie);
            $movies = $response['results'];

            return $this->render('dashboard/index.html.twig', [
                'controller_name' => 'DashboardController',
                'form' => $form->createView(),
                'movies' => $movies,
            ]);
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/download/{id}", name="download")
     */
    public function download($id): Response
    {

        $movie = Movies::getMovieById($id);
        $response = Movies::downloadMovie($movie);


        return $this->render('dashboard/download.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
