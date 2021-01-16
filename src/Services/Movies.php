<?php
namespace App\Service;

class Movies
{
    public static function getMovieByName(string $name)
    {
        $curl = curl_init();
        $link = "https://api.themoviedb.org/3/search/movie?api_key=384666e68d8f8e3ccd0d317fbd9f359a&query={$name}";
        $link = str_replace ( ' ', '%20', $link);
        $opts = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL            => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30
        ];

        
        curl_setopt_array($curl, $opts);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $response;
    }
}