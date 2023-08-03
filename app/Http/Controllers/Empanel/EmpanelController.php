<?php

namespace App\Http\Controllers\Empanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Empanel;
use View;
use File;


class EmpanelController extends Controller
{
    public function getEmpanel(Request $request) {
        $empanelData = Empanel::all()->sortByDesc("em_panel_id");
        return View::make('empanel.empanel-cards', ['empanel' => $empanelData]);
    }
}
