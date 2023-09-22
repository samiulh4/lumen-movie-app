<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Users;
use App\Models\Movie;

use App\Traits\helperTrait;

class MovieController extends Controller
{
    use helperTrait;

    public function __construct()
    {

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'released_year' => 'required',
            'language_id' => 'required',
            'country_id' => 'required',
            'picture' => 'file|required',
        ]);
        try {
            $movie = new Movie();
            $movie->title = $request->title;
            $movie->released_year = $request->released_year;
            $movie->language_id = $request->language_id;
            $movie->country_id = $request->country_id;
            $movie->description = $request->description;
            if (!empty($request->released_date)) {
                $movie->released_date = $request->released_date;
            }
            if ($request->hasFile('picture')) {
                $request_file = $request->file('picture');
                $original_filename = $request->file('picture')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './uploads/movies/';
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                $file_save_as = 'MOVIE_' . date('Ymd') . '_' . time() . '.' . $file_ext;
                if ($request_file->move($destination_path, $file_save_as)) {
                    $movie->picture = str_replace(".", "", $destination_path) . $file_save_as;
                }
            }
            $movie->created_by = Auth::user()->id;
            $movie->updated_by = Auth::user()->id;
            $movie->save();
            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 201,
                    'message' => $request->title . ' stored successfully.',
                ], 201);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 500,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (empty($id)) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 404,
                    'message' => 'The server cannot find the requested resource.',
                ], 404);
        }

       $decode_id = $id;
       $this->validate($request, [
            'title' => 'required|string',
            'released_year' => 'required',
            'language_id' => 'required',
            'country_id' => 'required',
        ]);
        try {
            $movie = Movie::where('id', $decode_id)->first();
            $movie->title = $request->title;
            $movie->released_year = $request->released_year;
            $movie->language_id = $request->language_id;
            $movie->country_id = $request->country_id;
            $movie->description = $request->description;
            if (!empty($request->released_date)) {
                $movie->released_date = date('Y-m-d', strtotime($request->released_date));
            }
            if ($request->hasFile('picture')) {
                $request_file = $request->file('picture');
                $original_filename = $request->file('picture')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './uploads/movies/';
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                $file_save_as = 'MOVIE_PICTURE_' . date('Ymd') . '_' . time() . '.' . $file_ext;
                if ($request_file->move($destination_path, $file_save_as)) {
                    $movie->picture = str_replace(".", "", $destination_path) . $file_save_as;
                }
            }
            if ($request->hasFile('cover')) {
                $request_file = $request->file('cover');
                $original_filename = $request->file('cover')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './uploads/movies/';
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                $file_save_as = 'MOVIE_COVER_' . date('Ymd') . '_' . time() . '.' . $file_ext;
                if ($request_file->move($destination_path, $file_save_as)) {
                    $movie->cover = str_replace(".", "", $destination_path) . $file_save_as;
                }
            }
            $movie->updated_by = Auth::user()->id;
            $movie->save();
            return response()->json(
                [
                    'responseStatus' => 1,
                    'message' => $request->title . ' updated successfully.',
                    'movie' => $movie,
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- update()

    public function getWebMovies(Request $request)
    {
        try {
            $movies = Movie::leftJoin('countries', 'movies.country_id', '=', 'countries.id')
                ->leftJoin('languages', 'movies.language_id', '=', 'languages.id')
                ->where('movies.is_active', 1)
                ->orderBy('movies.updated_at', 'desc')
                ->get([
                    'movies.*',
                    'countries.nice_name_en as country_title',
                    'languages.language_name'
                ]);
            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 200,
                    'message' => 'Get all movies successfully.',
                    'movies' => $movies
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 500,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- getWebMovies()

    public function getMovieViewDataById($id)
    {
        if (empty($id)) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'message' => 'The server cannot find the requested resource.',
                ], 404);
        }
        try {
            $movie = Movie::leftJoin('users', 'movies.created_by', '=', 'users.id')
                ->leftJoin('countries', 'movies.country_id', '=', 'countries.id')
                ->leftJoin('languages', 'movies.language_id', '=', 'languages.id')
                ->where('movies.id', $id)
                ->first([
                    'movies.*',
                    'countries.nice_name_en as country_title',
                    'languages.language_name as language_title',
                    'users.name as author_name',
                    'users.email as author_email',
                    'users.avatar as author_avatar'
                ]);
            return response()->json(
                [
                    'responseStatus' => 1,
                    'message' => $movie->title . ' get successfully.',
                    'movie' => $movie
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- getMovieViewDataById()
}// end -:- MovieController
