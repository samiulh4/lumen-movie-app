<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Language;
use App\Traits\helperTrait;

class DataController extends Controller
{
    use helperTrait;

    public function __construct()
    {

    }

    public function getCountries()
    {
        try {
            $countries = Country::where('is_active', 1)->get(['nice_name_en', 'id']);
            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 200,
                    'message' => 'Get all country successfully.',
                    'countries' => $countries
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 500,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- getCountries()

    public function getLanguages()
    {
        try {
            $languages = Language::where('is_active', 1)->get(['language_name', 'id']);
            return response()->json(
                [
                    'responseStatus' => 1,
                    'message' => 'Get all language successfully.',
                    'languages' => $languages
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- getLanguages()

}// end -:- DataController
