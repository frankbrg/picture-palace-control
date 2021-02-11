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
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $movie = new Movies();
        $form = $this->createForm(QueryMovieType::class);
        $downloadingMovies = Movies::getDownloaded();
        $param = [];
        
        $popularMovies = Movies::getMoviePopular();
        if ($downloadingMovies) {
            $param['downloadingMovies'] = $downloadingMovies;
        }
        if ($popularMovies) {
            $param['popularMovies'] = $popularMovies;
         }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $movie = $form->getData()['movie'];
            $response = Movies::getMovieByName($movie);
            $movies = $response['results'];
            $param['form'] = $form;
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
    public function findOutMoreAboutMovie($id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $movie = Movies::getMovieById($id);
        $cast= Movies::getCastByMovieId($id);
        $similar = Movies::getSimilarByMovieId($id);
        $movie['backdrop'] = Movies::getSimilarByMovieId($id);

        return $this->render('movies/movie.html.twig', [
            'movie' => $movie,
            'cast'  => $cast,
            'similar' => $similar,
        ]);

    }

    /**
     * @Route("/download/{id}", name="download")
     */
    public function download($id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $movie = Movies::getMovieById($id);
        $response = Movies::downloadMovie($movie);


        return $this->render('movies/download.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
}
