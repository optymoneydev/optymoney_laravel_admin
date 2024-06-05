<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\NewsLetter;
use View;
use File;


class NewsLetterController extends Controller
{
    public function getNewsLetter(Request $request) {
        $newsLetterData = NewsLetter::orderBy('id', 'DESC')
            ->get()
            ->toJson();
        return $newsLetterData;
    }

    public function getNewsLetterAPI(Request $request) {
        $newsLetterData = NewsLetter::orderBy('id', 'DESC')
            ->get()
            ->toJson();
        return $newsLetterData;
    }

    public function saveNewsLetter(Request $request) {
        $id = $request->session()->get('id');

        $newsletter = new NewsLetter();
        if($request['id'] != "") {
            $newsletter = NewsLetter::find($request['id']);
            $newsletter->id = $request['id'];
        }

        $pdfdocument = $request->file('pdfDocument');
        $extension = $request->file('pdfDocument')->extension();
        $newsletter->title = $request['title'];
        $newsletter->datetitle = $request['datetitle'];
        $filename = str_replace(' ', '_', $request['datetitle']);
        if($pdfdocument != "") {
            $newsletter->pdfdocument = strtolower($filename.".".$extension);
        }
        $saveNewsletter = $newsletter->save();
        
        $allowedfileExtension=['pdf'];
        $path = public_path('uploads').'/files';

        if(!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        } else {
        }

        if($pdfdocument != "") {
            $filename = $pdfdocument->getClientOriginalName();
            $extension = $pdfdocument->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            if($check) {
                $file_upload_status = $pdfdocument->move($path, strtolower($filename.".".$pdfdocument->extension()));
            }
        }
        
        if($saveNewsletter==1) {
            if($request['id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Newsletter updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Newsletter added successfully.'
                ];
            }
        } else {
            if($request['id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Newsletter updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Newsletter adding failed.'
                ];
            }
        }
        return $data;
    }

    public function newsLetterById(Request $request) {
        $newsLetterData = NewsLetter::where('id', '=', $request->id)->get()->first();
        return $newsLetterData;
    }

    public function deleteNewsLetterById(Request $request) {
        $newsLetterData = NewsLetter::where('id', '=', $request->id)->delete();
        return $newsLetterData;
    }

}
