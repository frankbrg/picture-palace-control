<?php
namespace App\Services\Item;

use App\Services\Api\Sonarr;

class TvShows extends AbstractItem 
{
    private const apiKey = "384666e68d8f8e3ccd0d317fbd9f359a";
    private const link = "https://api.themoviedb.org/3/";
    private const type = "tv";

    public function __construct() {
        $this->controllerPath = 'show_tv';
        $this->name = 'TV Show';
        $this->slug = 'tvshow';
    }

    private function preRequest($get, $search = null) {

        if($search != null) {
            $link = str_replace(' ', '%20', self::link.$get."/".self::type."?api_key=".self::apiKey."&query=".$search);
        } else {
            $link = str_replace(' ', '%20', self::link.self::type."/".$get."?api_key=".self::apiKey);
        }
        $opts = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL            => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30
        ];

        return $opts;
    }

    private function doRequest($opts){
        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (isset($response["results"]) ) {
            return $response["results"]; 
        } else {
            return $response;
        }
    }

    public function getPopular() {

        return $this->doRequest($this->preRequest("popular"));
    }

    public function getTvShowByName(string $name) {
        return $this->doRequest($this->preRequest("search", $name));
    }

    public function getTvShowById(string $id) {
        
        $tv = $this->doRequest($this->preRequest($id));
        $tv['api_db_id'] = null;

        return $tv;
    }

    public function getCastByTvShowId($id)
    {
        return $this->doRequest($this->preRequest("$id/credits"));
    }

    public function getSimilarByTvShowId($id)
    {
        return $this->doRequest($this->preRequest("$id/similar"));
    }

    public function downloadTv($tvShow)
    {
        $sonarr = new Sonarr();
        $tvShow['id'] = $this->doRequest($this->preRequest("{$tvShow["id"]}/external_ids"))['tvdb_id'];
        return $sonarr->downloadTvShow($tvShow);

    }
}