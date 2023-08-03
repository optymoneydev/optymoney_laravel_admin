<?php

namespace App\Http\Controllers;

use App\Models\Contact_info;
use Illuminate\Http\Request;

class ContactusController extends Controller
{
    public function saveContact(Request $request) {

        $cid = Contact_info::create([
            'con_name' => $request->inputName,
            'con_email' => $request->inputEmail,
            'con_mobile' => $request->inputContact,
            'con_message' => $request->message
        ]);
        if($cid->id>0){
            $response = [
              'statusCode' => '200',
              'uid' => $cid->id,
              'message' => 'Data sent successfully'
            ];
        } else {
            $response = [
              'statusCode' => '400',
              'message' => 'Data sending failed',
            ];
        }
        return $response;
    }

}
