<?php

namespace App\Controller;

use App\Services\Movies;
use App\Form\QueryMovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movies")
     */
    public function index(Request $request, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $param = [];
        $param['popular'] = $movies->getPopular();
        $form = $this->createForm(QueryMovieType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $searchmovie = $form->getData()['movie'];
            $response = $movies->getMoviesByName($searchmovie);
            $movies = $response;
            if ($movies) {
                $param['movies'] = $movies;
            }
        }
        $param['form'] = $form->createView();

        return $this->render('movies/movies_dashboard.html.twig', $param);
    }

    /**
     * @Route("/movie/{id}", name="movie")
     */
    public function findOutMoreAboutMovie($id, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('movies/movie.html.twig', [
            'movie' => $movies->getMovieById($id),
            'cast' => $movies->getCastByMovieId($id),
            'similar' => $movies->getSimilarByMovieId($id)
        ]);

    }

    /**
     * @Route("/download/{id}", name="download")
     */
    public function download($id, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $response = $movies->downloadMovie($movies->getMovieById($id));
        return $this->redirectToRoute('movies');
    }
}
