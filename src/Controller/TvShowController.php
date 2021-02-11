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
TvShowController.php on line 51:
array:31 [▼
  "backdrop_path" => "/57vVjteucIF3bGnZj6PmaoJRScw.jpg"
  "created_by" => array:1 [▶]
  "episode_run_time" => array:2 [▶]
  "first_air_date" => "2021-01-15"
  "genres" => array:4 [▶]
  "homepage" => "https://www.disneyplus.com/series/wandavision/4SrN28ZjDLwH"
  "id" => 85271
  "in_production" => true
  "languages" => array:1 [▶]
  "last_air_date" => "2021-01-29"
  "last_episode_to_air" => array:10 [▶]
  "name" => "WandaVision"
  "next_episode_to_air" => array:10 [▶]
  "networks" => array:1 [▶]
  "number_of_episodes" => 9
  "number_of_seasons" => 1
  "origin_country" => array:1 [▶]
  "original_language" => "en"
  "original_name" => "WandaVision"
  "overview" => "Wanda Maximoff and Vision—two super-powered beings living idealized suburban lives—begin to suspect that everything is not as it seems."
  "popularity" => 2798.473
  "poster_path" => "/glKDfE6btIRcVB5zrjspRIs4r52.jpg"
  "production_companies" => array:1 [▶]
  "production_countries" => array:1 [▶]
  "seasons" => array:1 [▶]
  "spoken_languages" => array:1 [▶]
  "status" => "Returning Series"
  "tagline" => "Experience a new vision of reality."
  "type" => "Miniseries"
  "vote_average" => 8.4
  "vote_count" => 3339
]