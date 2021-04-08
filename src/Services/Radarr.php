<?php
namespace App\Services;

class Radarr
{
    private const apiKey = "2209ca02fb68410e9a8994c63ea060b1";
    private const API_URL = "https://fedalino.lw887.usbx.me/radarr/api/";

    private function preRequest($get, $movie) {

        $link = str_replace(' ', '%20', self::API_URL.$get."?apikey=".self::apiKey);

        $data = [
            "title"             => $movie['title'],
            "qualityProfileId"  => "1",
            "tmdbid"            => $movie['id'],
            "titleslug"         => $movie['title']."-".$movie['id'],
            "moitored"          => "true",
            "rootFolderPath"    => "/home1/fedalino/media/Movies/",
            "addOptions"        => [
                "searchForMovie" => true
            ],
            "images"            => 
            [
                [
                    "covertype" => "poster",
                    "url"       => "https://image.tmdb.org/t/p/w500/".$movie['poster_path']
                ],
            ]
        ];

        $data_string = json_encode($data);

        $opts = [
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_URL             => $link,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $data_string,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string)
            )
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

    public function downloadMovie($movie)
    {
        return $this->doRequest($this->preRequest('movie', $movie));
    }

    public function makeCall($request)
    {
        $url = str_replace(' ', '%20', self::API_URL.$request."?apikey=".self::apiKey);

        $opts = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 5
        ];

        return $this->doRequest($opts);
    }

    public function getMovies() {

        return $this->makeCall('movie');
    }


}
