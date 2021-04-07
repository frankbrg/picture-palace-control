<?php

namespace App\Controller;

use App\Services\TvShows;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\QueryTvShowType;

class TvShowController extends AbstractController
{
    /**
     * @Route("/tv/show", name="tv_show")
     */
    public function index(Request $request, TvShows $tvShow): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $param = [];
        $param['popular'] = $tvShow->getPopular();
        $form = $this->createForm(QueryTvShowType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tvshow = $form->getData()['tvshow'];
            $response = $tvShow->getTvShowByName($tvshow);
            $tvshows = $response;
            if ($tvshows) {
                $param['tvshows'] = $tvshows;
            }
        }
        $param['form'] = $form->createView();

        return $this->render('tv_show/tvshows_dashboard.html.twig', $param);
    }

    /**
     * @Route("/tvshow/{id}", name="tvshow")
     */
    public function findOutMoreAboutMovie($id, TvShows $tvShow): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('tv_show/tvshow.html.twig', [
            'tvshow' => $tvShow->getTvShowById($id),
        ]);

    }
}