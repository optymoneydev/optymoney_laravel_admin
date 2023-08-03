<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_user;
Use App\Models\KycStatus;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Users\UsersController;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use View;

class KycController extends Controller
{
    
    public function augKYCUpload(Request $request) {
        
        $id = $request->session()->get('id');
        $userData = Bfsi_user::where('pk_user_id', $request->session()->get('id'))->get(['augid']);

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $headers = [
            'AccessToken' => 'key',
            'Authorization' => $authToken,
            'Accept' => 'application/json'
        ];

        $newDate = date("Y-m-d", strtotime($request->panDOB));
        $file               = request('panFile');
        $file_path          = $file->getPathname();
        $file_mime          = $file->getMimeType('image');
        $file_uploaded_name = $file->getClientOriginalName();
        $form_params = [
            [
                'name'      => 'panAttachment',
                'filename' => $file_uploaded_name,
                'filepath' => $file_path,
                'Mime-Type'=> $file_mime,
                'contents' => fopen($file_path, 'r'),
            ]
        ];
        $query = [
            'panNumber' => $request->panNumber,
            'dateOfBirth' => $newDate,
            'nameAsPerPan' => $request->panName,
            'status' => "approved"
        ];

        $fileName = time().'.'.$request->panFile->extension();  
        $request->panFile->move(public_path('uploads'), $file_uploaded_name);

        $response =  (new AugmontController)->clientFileUploadRequests($query, $form_params, 'merchant/v1/users/'.$userData[0]->augid.'/kyc');
        if(!isset($response->errors)) {
            $content = $response->result->data;
        } else {
            $content = null;
        }
        $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
            'pan_number' => $request->panNumber
        ]);
        if($response->statusCode==200) {
            $ins = $this->insertKyc($id, "", $content->uniqueId, $content->panNumber, $file_uploaded_name, "", "", "uploaded", $content->status, "", "", $content->rejectedReason, "", "", "", "", "", $content->errorCode, "aug");
            $userData = (new UsersController)->getUserDataByUID($id);
            $res2 = (new EmailController)->send_kyc_upload($userData, "KYC_UPLOAD_AUGMONT");
            $orderData = session()->get('orderData');
            $var2 = str_replace(".", "/", $orderData['redirectURL']);
            return View::make($orderData['redirectURL'], $orderData);
        } else {
            $ins = $this->insertKyc($id, "", $userData[0]->augid, $request->panNumber, $file_uploaded_name, "", "", "uploaded", "approved", "", "", $response->errors->status[0]->message, "", "", "", "", "", $response->errors->status[0]->code, "aug");
            $orderData = session()->get('orderData');
            $var2 = str_replace(".", "/", $orderData['redirectURL']);
            return View::make($orderData['redirectURL'], $orderData);
        }
    }

    public function aug_kyc_check($id) {
        $userData = Bfsi_user::where('pk_user_id', $id)->get(['augid']);

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            $content =  (new AugmontController)->clientRequests('GET', 'merchant/v1/users/'.$userData[0]->augid.'/kyc', "");
            if($content->statusCode==200) {
                $kyc = KycStatus::where('fr_user_id', $id)->update([
                    'aug_kyc_status' => $content->result->data->status
                ]);
                return $content->result->data->status;
            } else {
                return "FAILURE";
            }
            
        }
    }

    public function aug_kyc_check_sip($id) {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            $content =  (new AugmontController)->clientRequests('GET', 'merchant/v1/users/'.$id.'/kyc', "");
            if($content->statusCode==200) {
                $kyc = KycStatus::where('fr_user_id', $id)->update([
                    'aug_kyc_status' => $content->result->data->status,
                    'accountId' => $content->result->data->accountId
                ]);
                return $content->result->data;
            } else {
                return "FAILURE";
            }
            
        }
    }

    public function aug_kyc_db_check(Request $request) {
        $augStatus = KycStatus::where('fr_user_id', $request->session()->get('id'))->get(['panAttachment', 'aug_kyc_status', 'aug_doc_submit'])->first();
        if(isset($augStatus)) {
            $aug_kyc_status = $augStatus['aug_kyc_status'];
            $aug_doc_submit = $augStatus['aug_doc_submit'];
            $panAttachment = $augStatus['panAttachment'];
            if($aug_doc_submit=="" || $panAttachment=="") {
                return null;
            } else {
                if($aug_kyc_status=="" || $aug_kyc_status=="pending" || $aug_kyc_status=="Pending") {
                    $aug_kyc_status = $this->aug_kyc_check($request->session()->get('id'));
                }
                return $aug_kyc_status;
            }
        } else {
            return null;
        }
        
    }

    public function kyc_db_check(Request $request) {
        $kycStatus = KycStatus::where('fr_user_id', $request->session()->get('id'))->get(['aug_kyc_status'])->first();
        if(isset($augStatus)) {
            return $kycStatus;
        } else {
            return null;
        }
        
    }

    public function insertKyc($id, $accountId, $uniqueId, $panNumber, $panAttachment, $aadharNumber, $aadharAttachment, $aug_doc_submit, $aug_kyc_status, 
    $nsdl_response, $nsdl_status, $rejectedReason, $signzyId, $signzy_username, $signzy_submit_date, $autoLoginUrL, $signzy_status, $errorCode, $loc) {
        $kyc = KycStatus::where([
            // 'fr_user_id' => 3956,
            'panNumber' => $panNumber
        ])->get()->first();
        if($kyc) {
            if($loc=="nsdl") {
                $saveKycStatus = KycStatus::where('fr_user_id', $id)->update([
                    'nsdl_response' => $nsdl_response,
                    'nsdl_status' => $nsdl_status
                ]);
            } else {
                if($loc=="aug") {
                    $saveKycStatus = KycStatus::where('fr_user_id', $id)->update([
                        'aug_kyc_status' => $aug_kyc_status,
                        'aug_doc_submit' => $aug_doc_submit
                    ]);
                }
            }
            return $saveKycStatus;
        } else {
            $kycstatus = new KycStatus();
            $kycstatus->fr_user_id = $id;
            $kycstatus->accountId = $accountId;
            $kycstatus->uniqueId = $uniqueId;
            $kycstatus->panNumber = $panNumber;
            $kycstatus->panAttachment = $panAttachment;
            $kycstatus->aadharNumber = $aadharNumber;
            $kycstatus->aadharAttachment = $aadharAttachment;
            $kycstatus->aug_kyc_status = $aug_kyc_status;
            $kycstatus->nsdl_response = $nsdl_response;
            $kycstatus->rejectedReason = $rejectedReason;
            $kycstatus->errorCode = $errorCode;
            
            $saveKycStatus = $kycstatus->save();
            return $kycstatus;
        }
       
    }
    
}
