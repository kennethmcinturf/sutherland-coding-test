<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MovieAppController extends Controller
{
    public function viewApp() {
        return view('movie-app');
    }

    public function searchMovies(Request $request) {
        try {
            $search = $request->get('search');

            if (!$search) {
                return response()->json([ 'error' => 'Must include search term to find movie' ], 400);
            }


            $search = preg_replace('/[[:space:]]+/', '+', $search);
            $token = env('MOVIE_APP_TOKEN');

            $client = new Client();
            $res = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key='.$token.'&query='.$search);
            $movies = json_decode($res->getBody())->results;

            if (!count($movies)) {
                return response()->json([ 'movie' => [] ]);
            }

            $movieRes = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movies[0]->id.'?api_key='.$token.'');
            $movieDetails = json_decode($movieRes->getBody());
            $hours = floor($movieDetails->runtime / 60);
            $minutes = $movieDetails->runtime % 60;
            $runtime = sprintf('%02d:%02d', $hours, $minutes);

            $castRes = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movies[0]->id.'/credits?api_key='.$token.'');
            $castDetails = json_decode($castRes->getBody(), true)['cast'];
            $castDetails = array_values($castDetails);
            $returnedCast = [];

            for ($x = 0; $x <= 9; $x++) {
                if (!isset($castDetails[$x])) {
                    break;
                }

                $castMember = $castDetails[$x];

                $returnedCast[] = [
                    'name' => $castMember['name'],
                    'character' => $castMember['character'],
                ];
            }

            return response()->json(['movie' => [
                'title' => $movieDetails->title,
                'overview' => $movieDetails->overview,
                'release_date' => $movieDetails->release_date,
                'runtime' => $runtime,
                'cast' => $returnedCast
            ]]);
        } catch(Exception $e) {
            info($e);
            return response()->json([ 'error' => 'Error Retrieving Movie!!' ], 400);
        }
    }
}
