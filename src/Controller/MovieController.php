<?php

namespace App\Controller;

use App\Services\Item\Movies;
use App\Form\QueryMovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function home(Request $request, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('movies');
    }

    /**
     * @Route("/movies", name="movies")
     */
    public function index(Request $request, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $param = [];
        $param['data'] = $movies;
        $form = $this->createForm(QueryMovieType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $searchmovie = $form->getData()['search'];
            $response = $movies->getMoviesByName($searchmovie);
            $movies = $response;
            if ($movies) {
                $param['results'] = $movies;
            }
        }
        $param['form'] = $form->createView();

        return $this->render('item/dashboard.html.twig', $param);
    }

    /**
     * @Route("/movie/{id}", name="show_movie")
     */
    public function findOutMoreAboutMovie($id, Movies $movies): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $item = $movies->getMovieById($id);

        return $this->render('item/show.html.twig', [
            'data' => $movies,
            'title' => $item['title'],
            'item' => $item,
            'cast' => $movies->getCastByMovieId($id),
            'similar' => $movies->getSimilarByMovieId($id),
            'pathDownload' => 'download_movie'
        ]);

    }

    /**
     * @Route("/movie/download/{id}", name="download_movie")
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
