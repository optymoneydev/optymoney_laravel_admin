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
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userData = Bfsi_user::where('pk_user_id', $id)->get(['augid']);

            $data = $request->all();
            $augmentController = new AugmontController();
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
                return $banks;
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
                    return $banks;
                } else {
                    $banks['status'] = "422";
                    $banks['message'] = "Bank Account adding failed. Please try after sometime...";
                    return $banks;
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    /**
        * @OA\Get(
        * path="/api/customer/users/allUserBanks",
        * operationId="allUserBanks",
        * tags={"User Profile"},
        * summary="Get Bank details by user",
        * description="Get bank details by user",
        * security={{"bearerAuth":{}}},
        *      @OA\Response(
        *          response=201,
        *          description="Data retrieved",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Data retrieved",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function allUserBanks(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $data = Bfsi_bank_details::where('fr_user_id',$id)->get(); 
            return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function api_allUserBanks(Request $request) {
        $id = $request->uid;
        $data = Bfsi_bank_details::where('fr_user_id',$id)->get(); 
        return $data;
    }

    /**
        * @OA\Get(
        * path="/api/customer/bankAccountsAPI",
        * operationId="bankAccountsAPI",
        * tags={"User Profile"},
        * summary="Get Bank details by user",
        * description="Get bank details by user",
        * security={{"bearerAuth":{}}},
        *      @OA\Response(
        *          response=201,
        *          description="Data retrieved",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Data retrieved",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
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
    
    /**
        * @OA\Post(
        * path="/api/customer/savebankAccountAPI",
        * operationId="savebankAccountAPI",
        * tags={"User Profile"},
        * summary="Save Bank Account",
        * description="Save Bank Account",
        * security={{"bearerAuth":{}}}, 
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"ifsc_code", "bank_name", "ac_type", "acc_no", "branch_name", "ba_addr1", "ba_addr2", "ba_city", "ba_state", "ba_zip", "ba_country"},
        *               @OA\Property(property="ifsc_code", type="text"),
        *               @OA\Property(property="bank_name", type="text"),
        *               @OA\Property(property="ac_type", type="text"),
        *               @OA\Property(property="acc_no", type="text"),
        *               @OA\Property(property="branch_name", type="text"),
        *               @OA\Property(property="ba_addr1", type="text"),
        *               @OA\Property(property="ba_addr2", type="text"),
        *               @OA\Property(property="ba_city", type="text"),
        *               @OA\Property(property="ba_state", type="text"),
        *               @OA\Property(property="ba_zip", type="text"),
        *               @OA\Property(property="ba_country", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Bank Account Saved Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Bank Account Saved Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function savebankAccountAPI(Request $request) {
        // $bc = new StarMFWebServiceController();
        // $mandateAuth = $bc->eMandateAuthURL('2795', 'OPMY002052', "809676");
        // return $mandateAuth;
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
