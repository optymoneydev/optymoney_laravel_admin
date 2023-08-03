<?php

namespace App\Http\Controllers;

use App\Models\sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Models\Contact_info;

class GeneralController extends Controller
{
    public function uniqueId($length) {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  
        // Shuffle the $str_result and returns substring of specified length
        return substr(str_shuffle($str_result), 0, $length);
    }

    public function uniqueNumericId($length) {
        // String of all alphanumeric character
        $str_result = '0123456789';
  
        // Shuffle the $str_result and returns substring of specified length
        return substr(str_shuffle($str_result), 0, $length);
    }

    public function previousPath() {
        $parsed = parse_url(url()->previous());

        return $parsed['path'];
    }

    public function AuthenticateUser($cred) {
        $base64 = base64_encode($cred['user'].":".$cred['pass']);
        $client = new Client([
            'base_uri' => 'http://auth-restpi-hostname',
            'timeout' => 300,
            'headers' => ['Content-Type' => 'application/json', "Accept" => "application/json", 'Authorization' => "Basic " . $base64],
            'http_errors' => false,
            'verify' => false
        ]);
        $client = $this->_client($this->praxisAPI, $cred);
        try {
            $response = $client->get("/user");
            $data = json_decode($response->getBody()->getContents(), true);
            $status = $response->getStatusCode();

            if($status == 200) {
                return true;
            }

            return false;

        } catch (\Exception $ex) { 
            Log::critical($ex);       
            return Helper::jsonpError("Auth - Unable to get user account details", 400, 400);
        }
    }

    public function xmlToArray($xmlstring){
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
      
        return $array;  
    }

    public function callbackRequest(Request $request) {
        $data = $request->all();
        $contact_info = new Contact_info();
        $contact_info->con_name = $data['name'];
        $contact_info->con_mobile = $data['mobile'];
        $contact_infoSave = $contact_info->save();
        if($contact_infoSave>0) {
            return response()->json([
                'status' => '200'
            ]);
        } else {
            return response()->json([
                'status' => '500'
            ]);
        }
    }

    public function getFile(Request $request) {
        if($request->folder == "") {
            $file = Storage::disk('public_uploads_users')->get($request->uid.'/'.$request->filename);
        } else {
            $file = Storage::disk('public_uploads_users')->get($request->uid.'/'.$request->folder.'/'.$request->filename);
        }
        $extension = pathinfo($request->filename, PATHINFO_EXTENSION);
        // return $this->getFileExtensions($extension);
        return (new Response($file, 200))->header('Content-Type', $this->getFileExtensions($extension));
    }

    public function getFileExtensions($ext) {
        if($ext=="pdf") {
            return "application/pdf";
        } else {
            if($ext=="csv") {
                return "text/csv";
            } else {
                if($ext=="json") {
                    return "application/json";
                } else {
                    if($ext=="jpeg" || $ext=="jpg") {
                        return "image/jpeg";
                    } else {
                        return "";
                    }   
                }
            }
        }
    }
}
