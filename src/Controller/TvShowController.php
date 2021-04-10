<?php

namespace App\Controller;

use App\Services\Item\TvShows;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\QueryTvShowType;

class TvShowController extends AbstractController
{
    /**
     * @Route("/tv", name="tv")
     */
    public function index(Request $request, TvShows $tvShow): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $param = [];
        $param['data'] = $tvShow;
        $form = $this->createForm(QueryTvShowType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tvshow = $form->getData()['search'];
            $response = $tvShow->getTvShowByName($tvshow);
            $tvshows = $response;
            if ($tvshows) {
                $param['results'] = $tvshows;
            }
        }
        $param['form'] = $form->createView();
        return $this->render('item/dashboard.html.twig', $param);
    }

    /**
     * @Route("/tv/{id}", name="show_tv")
     */
    public function findOutMoreAboutTvShow($id, TvShows $tvShow): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $item = $tvShow->getTvShowById($id);

        return $this->render('item/show.html.twig', [
            'data' => $tvShow,
            'title' => $item['name'],
            'item' => $item,
            'cast' => $tvShow->getCastByTvShowId($id),
            'similar' => $tvShow->getSimilarByTvShowId($id),
            'pathDownload' => 'download_tv'
        ]);

    }

        /**
     * @Route("/tv/download/{id}", name="download_tv")
     */
    public function download($id, TvShows $tvShows): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $response = $tvShows->downloadTv($tvShows->getTvShowById($id));
        return $this->redirectToRoute('tv');
    }
}