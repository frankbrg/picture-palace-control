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
    
    public static function downloadMovie($movie){
        $curl = curl_init();
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

        $link = "https://fedalino.lw887.usbx.me/radarr/api/movie?apikey=2209ca02fb68410e9a8994c63ea060b1";
        $link = str_replace ( ' ', '%20', $link);
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

        $data = array("name" => "Hagrid", "age" => "36");                                                                    

        curl_setopt_array($curl, $opts);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response;
    }

    public static function getMovieById($id)
    {
        $curl = curl_init();
        $link = "https://api.themoviedb.org/3/movie/{$id}?api_key=384666e68d8f8e3ccd0d317fbd9f359a&language=en-US";
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

    public static function getMoviePopular()
    {
        $curl = curl_init();
        $link = "https://api.themoviedb.org/3/movie/popular?api_key=384666e68d8f8e3ccd0d317fbd9f359a&language=en-US&page=1";
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
        return $response['results'];
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