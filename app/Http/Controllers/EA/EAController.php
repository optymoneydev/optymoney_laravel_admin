<?php

namespace App\Http\Controllers\EA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\ExpertAssistance;
use View;


class EAController extends Controller
{

    public function getExpertAssistance(Request $request) {
        $eaData = ExpertAssistance::get()
              ->sortByDesc("ea_id")
              ->toJson();
        return $eaData;
    }

}
