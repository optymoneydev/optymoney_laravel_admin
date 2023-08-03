<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Help;
use View;


class HelpController extends Controller
{
    public function getHelp(Request $request) {
        $helpData = Help::get()
              ->sortByDesc("help_id")
              ->toJson();
        $data = [
            'helpCategory' => $this->getHelpCategory(),
            'helpSubCategory' => $this->getHelpSubCategory(),
            'help' => $helpData
        ];
        return $data;
    }
    
    public function getHelpCategory() {
        $helpCategoryData = Help::get()
                ->groupBy('help_category')
                ->sortBy("help_category");
        return $helpCategoryData;
    }

    public function getHelpSubCategory() {
        $helpSubCategoryData = Help::get()
                ->groupBy('help_sub_category')
                ->sortBy("help_sub_category");
        return $helpSubCategoryData;
    }

    public function saveHelp(Request $request) {
        $id = $request->session()->get('id');

        $help = new Help();
        if($request['help_id'] != "") {
            $help = Help::find($request['help_id']);
            $help->help_id = $request['help_id'];
            $help->help_modified_by = $id;
            $help->help_modified_ip = $request->ip();
        } else {
            $help->help_created_by = $id;
            $help->help_created_ip = $request->ip();
        }
        $help->help_category = $request['help_category'];
        $help->help_sub_category = $request['help_sub_category'];
        $help->help_question = $request['help_question'];
        $help->help_answer = $request['help_answer'];
        $help->help_keywords = $request['help_keywords'];
        $savehelp = $help->save();
        if($savehelp==1) {
            if($request['help_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Help updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Help added successfully.'
                ];
            }
        } else {
            if($request['help_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Help updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Help adding failed.'
                ];
            }
        }
        return $data;
    }

    public function helpById(Request $request) {
        $helpData = Help::where('help_id', '=', $request->help_id)->get()->first();
        return $helpData;
    }

    public function deletehelpById(Request $request) {
        $helpData = Help::where('help_id', '=', $request->help_id)->delete();
        return $helpData;
    }

}
