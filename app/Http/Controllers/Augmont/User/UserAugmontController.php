<?php

namespace App\Http\Controllers\Augmont\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class UserAugmontController extends Controller
{
    public function createAugmontAccount($data) {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $uniqueId = "Augo".$data['pk_user_id'];
        $client = new Client(['verify' => false ]);
    
        if(Str::contains($data['dob'], '/')) {
            $newDate = \Carbon\Carbon::createFromFormat('m/d/Y', $data['dob'])
                    ->format('Y-m-d');
            $data['dob'] = $newDate;
        }
        
        $options = [
            'mobileNumber' => $data['contact_no'],
            'emailId' => $data['login_id'],
            'uniqueId' => $uniqueId,
            'userName' => $data['cust_name'],
            'userCity' => $data['augcity'],
            'userState' => $data['augstate'],
            'userPincode' => $data['pincode'],
            'dateOfBirth' => $data['dob'],
            // 'nomineeName' => $data['nominee_name'],
            // 'nomineeDateOfBirth' => $data['nominee_dob'],
            // 'nomineeRelation' => $data['r_of_nominee_w_app'],
            'utmSource' => 'FD',
            'utmMedium' => 'SA',
            'utmCampaign' => 'EM42342434'
        ]; 
        return (new AugmontController)->clientRequests('POST', 'merchant/v1/users', $options);
    }

    public function checkUpdateAugmont($userData) {
        $augresult = $this->createAugmontAccount($userData);
        if(Arr::exists($augresult, 'errors')) {
            $errors = (new AugmontController)->errorCapture($augresult);
            if($errors=="4298") {
                $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $userData['pk_user_id'])->update([
                    'augid' => "Augo".$userData['pk_user_id']
                ]);
                $augid = "Augo".$userData['pk_user_id'];
            }
        } else {
            $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $userData['pk_user_id'])->update([
                'augid' => "Augo".$userData['pk_user_id']
            ]);
            $augid = "Augo".$userData['pk_user_id'];
        }
        return $augid;
    }
}
