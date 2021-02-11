<?php
namespace App\Services;

class TvShows
{

    private const apiKey = "384666e68d8f8e3ccd0d317fbd9f359a";
    private const link = "https://api.themoviedb.org/3/";
    private const type = "tv";

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

        return $this->doRequest($this->preRequest($id));
    }
   
}