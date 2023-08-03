<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\EmailController;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_bank_details;
Use App\Models\Contact_info;
use Carbon\Carbon;
use View;


class UsersController extends Controller
{
    public function getUserData(Request $request) {

        $id = $request->session()->get('id');

        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $id)
              ->get(['bfsi_user.*', 'bfsi_users_details.*']);

            //   dd($userData[0]->toArray());
        return View::make('users.user-profile', $userData[0]->toArray());
    }

    public function getUserDataController(Request $request) {

        $id = $request->session()->get('id');

        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $id)
              ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
        return $userData;
    }

    public function getUserDataByUID($uid) {
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $uid)
              ->get([
                  'bfsi_user.augid', 
                  'bfsi_user.pk_user_id', 
                  'bfsi_user.login_id', 
                  'bfsi_users_details.*'])->first();
        return $userData;
    }

    public function getDayWiseNewReg() {
        $myObj = array();
        $myObj['today'] = Bfsi_user::whereDate('created_at', Carbon::today())->count();
        $myObj['total'] = Bfsi_user::count();
        return $myObj; 
    }

    public function saveContactUsForm(Request $request) {
        $contactForm = new Contact_info();
        $contactForm->con_name = $request->formname;
        $contactForm->con_email = $request->formemail;
        $contactForm->con_mobile = $request->formnumber;
        $contactForm->con_msg = $request->formmessage;
        $contactForm_infoSave = $contactForm->save();
        if($contactForm_infoSave) {
            $res2 = (new EmailController)->send_contact_success($request->formemail, $request->formname, $request->formmessage) ;
            $data = [
                "statusCode" => 201,
                "data" => $contactForm_infoSave
            ];
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Submission failed, please try again"
            ];
        }
        return $data;
    }

    

}
