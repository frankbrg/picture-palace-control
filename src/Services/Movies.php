<?php
namespace App\Services;

class Movies
{
    private const apiKey = "384666e68d8f8e3ccd0d317fbd9f359a";
    private const link = "https://api.themoviedb.org/3/";
    private const type = "movie";

    
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

    public function getMoviesByName(string $name) {
        
        return $this->doRequest($this->preRequest("search", $name));
    }

    public function getMovieById(string $id) {

        return $this->doRequest($this->preRequest($id));
    }

    public function getCastByMovieId($id)
    {
        return $this->doRequest($this->preRequest("$id/credits"));
    }

    public function getSimilarByMovieId($id)
    {
        return $this->doRequest($this->preRequest("$id/similar"));
    }
    public function downloadMovie($movie)
    {
        $radarr = new Radarr();
        return $radarr->downloadMovie($movie);
    }
    public static function getDownloaded()
    {
        $curl = curl_init();
        $link = "https://fedalino.lw887.usbx.me/radarr/api/queue?page=1&pageSize=20&sortDirection=ascending&sortKey=timeLeft&includeUnknownMovieItems=false&apikey=2209ca02fb68410e9a8994c63ea060b1";
        $link = str_replace ( ' ', '%20', $link);
        $opts = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL            => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 5
        ];

        
        curl_setopt_array($curl, $opts);
        $response = json_decode(curl_exec($curl),true);
        $moviesDL = array();
        for ($i=0; $i < sizeof($response); $i++) { 
            $response[$i];
            if ($response[$i]['status'] == "Downloading") {
                array_push($moviesDL, $response[$i]);
            }
        }
        if (empty($moviesDL)) {
            return null;
        }
        $response =$moviesDL;
        curl_close($curl);
        return $response;
    }


}