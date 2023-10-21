<?php

namespace App\Http\Controllers\Augmont\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class UserAugmontController extends Controller
{
    public function createAugmontAccount($data) {
        try {
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();

            $uniqueId = "Augo".$data['pk_user_id'];
            $client = new Client(['verify' => false ]);
        
            if(Str::contains($data['dob'], '/')) {
                $newDate = \Carbon\Carbon::createFromFormat('m/d/Y', $data['dob'])
                        ->format('Y-m-d');
                $data['dob'] = $newDate;
            }
            $mob = str_replace(' ', '', $data['contact_no']);
            if(strlen($mob) == 11) {
                if (substr($mob, 0, 1) === '0') {
                    $mob = substr($mob, 1, strlen($mob));
                } else {
                    $mob = $mob;
                }
            }
            $options = [
                'mobileNumber' => $mob,
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
            $output = (new AugmontController)->clientRequests('POST', 'merchant/v1/users', $options);
            
            \Log::channel('itsolution')->info(json_encode(['user_id' => $data['pk_user_id'], ])." -> createAugmontAccount :".$options);
            return $output;
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($data." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function checkUpdateAugmont($userData) {
        try {
            $augresult = $this->createAugmontAccount($userData);
            $augid = "Augo".$userData['pk_user_id'];
            if (isset($augresult->errors)) {
            // if(Arr::exists($augresult, 'errors')) {
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
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($userData['pk_user_id']." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function createManualAugmontAccount(Request $request) {
        $userProfile = (new UsersController)->getUserDataByUID($request->id);
        $augid = (new UserAugmontController)->checkUpdateAugmont($userProfile);
        return $augid;
    }
}
