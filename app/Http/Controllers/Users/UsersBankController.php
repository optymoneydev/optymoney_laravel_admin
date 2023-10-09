<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\mf\BSEController;
use App\Http\Controllers\mf\StarMFWebServiceController;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;
use View;

class UsersBankController extends Controller
{
    public function createAugmontBankAccount($data, $uniqueId, $custname) {
        $form_params = [
            'accountNumber' => $data['acc_no'],
            'accountName' => $custname,
            'ifscCode' => $data['ifsc_code'],
            'status' => "active"
        ];

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            // return (new AugmontController)->clientRequests('POST', '/merchant/v1/users/'.$uniqueId.'/banks', $form_params);
            dd((new AugmontController)->clientRequests('POST', '/merchant/v1/users/'.$uniqueId.'/banks', $form_params));
        }
    }

    public function createBankAccount(Request $request) {
        $data = $request->all();
        $augmentController = new AugmontController();
        $id = $request->session()->get('id');
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $id)
              ->get(['bfsi_user.*', 'bfsi_users_details.*']);
        $userBankCheck = Bfsi_bank_details::where([
            'acc_no' => $data['acc_no'],
            'fr_user_id' => $id
        ])->first();
        if($userBankCheck) {
            $banks['status'] = "422";
            $banks['message'] = "Bank Account already exist";
            return View::make('users.banks', $banks);
        } else {
            // $bankAugResponse = $this->createAugmontBankAccount($data, $userData[0]->augid, $userData[0]->cust_name);
            // dd($bankAugResponse);
            $bankAccount = new Bfsi_bank_details();
            $bankAccount->fr_user_id = $id;
            $bankAccount->acc_no = $data['acc_no'];
            $bankAccount->bank_name = $data['bank_name'];
            $bankAccount->ifsc_code = $data['ifsc_code'];
            $bankAccount->augBankStatus = 'acitve';
            $bankStatus = $bankAccount->save();
            if($bankStatus) {
                $banks['status'] = "200";
                $banks['message'] = "Bank Account Added Successfully";
                return View::make('users.banks', $banks);
            } else {
                $banks['status'] = "422";
                $banks['message'] = "Bank Account adding failed. Please try after sometime...";
                return View::make('users.banks', $banks);
            }
        }
        
    }

    public function allUserBanks(Request $request) {
        $id = $request->session()->get('id');
        $data = Bfsi_bank_details::where('fr_user_id',$id)->get(); 
        return $data;
    }

    public function api_allUserBanks(Request $request) {
        $id = $request->uid;
        $data = Bfsi_bank_details::where('fr_user_id',$id)->get(); 
        return $data;
    }

    public function bankAccountsAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;
            $banksList = Bfsi_bank_details::where('fr_user_id',$id)->get();
            $data = [
                "statusCode" => 201,
                "data" => $banksList
            ];
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }
    
    public function savebankAccountAPI(Request $request) {
        $bc = new StarMFWebServiceController();
        $mandateAuth = $bc->eMandateAuthURL('2795', 'OPMY002052', "809676");
        return $mandateAuth;
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            if($request->id!="") {
                $upstatus = Bfsi_bank_details::where('pk_bank_detail_id', $request->id)->update([
                    'bank_name' => $request->bank_name,
                    'acc_no' => $request->acc_no,
                    'ac_type' => $request->ac_type,
                    'branch_name' => $request->branch_name,
                    'ifsc_code' => $request->ifsc_code,
                    'ba_addr1' => $request->ba_addr1,
                    'ba_addr2' => $request->ba_addr2,
                    'ba_city' => $request->ba_city,
                    'ba_state' => $request->ba_state,
                    'ba_zip' => $request->ba_zip
                ]);
                if($upstatus) {
                    $data = [
                        "statusCode" => 201,
                        "data" => $upstatus
                    ];
                } else {
                    $data = [
                        "statusCode" => 401,
                        "message" => "Bank Details updation failed, please try again"
                    ];
                }
            } else {
                $bfsi_bank = new Bfsi_bank_details();
                $bfsi_bank->fr_user_id = $id;
                $bfsi_bank->bank_name = $request->bank_name;
                $bfsi_bank->acc_no = $request->acc_no;
                $bfsi_bank->ac_type = $request->ac_type;
                $bfsi_bank->branch_name = $request->branch_name;
                $bfsi_bank->ifsc_code = $request->ifsc_code;
                $bfsi_bank->ba_addr1 = $request->ba_addr1;
                $bfsi_bank->ba_addr2 = $request->ba_addr2;
                $bfsi_bank->ba_city = $request->ba_city;
                $bfsi_bank->ba_state = $request->ba_state;
                $bfsi_bank->ba_zip = $request->ba_zip;
                $bfsi_bank_infoSave = $bfsi_bank->save();
                $bc = new BSEController();
                $mandate = $bc->mandateRegistration($request->acc_no, $request->ac_type, $request->ifsc_code, $user->bse_id, $id, $bfsi_bank->id);
                $bc = new StarMFWebServiceController();
                $mandateAuth = $bc->eMandateAuthURL($bfsi_bank->id, $user->bse_id, $mandate['mandate_id']);
                // return $mandateAuth;
                $mandateDetails = $bc->mandateDetails($bfsi_bank->id, $user->bse_id);
                return $mandateDetails;
                if($bfsi_bank_infoSave) {
                    $data = [
                        "statusCode" => 201,
                        "data" => $bfsi_bank_infoSave
                    ];
                } else {
                    $data = [
                        "statusCode" => 401,
                        "message" => "Bank Details updation failed, please try again"
                    ];
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
        }
        return $data;
    }

    public function getBankAccountByIdAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $request->id;
            $bankDetails = Bfsi_bank_details::where('pk_bank_detail_id',$id)->get()->first();
            $data = [
                "statusCode" => 201,
                "data" => $bankDetails
            ];
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function allUserBanksByID($id) {
        $data = Bfsi_bank_details::where('fr_user_id',$id)->get(); 
        return $data;
    }

    public function deleteBankAccountByIdAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $bankAccounts = $this->allUserBanksByID($id);
            $data = Bfsi_bank_details::where('pk_bank_detail_id', $request->id)->delete(); 
            $data = [
                "statusCode" => 201,
                "data" => "Bank account deleted successfully"
            ];
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
        }
        return $data;
    }
}
