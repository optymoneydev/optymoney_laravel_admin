<?php

namespace App\Http\Controllers\pms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\Employee\EmployeeController;
Use App\Models\Employee;
Use App\Models\Bfsi_user;
Use App\Models\Pms;
Use App\Models\Bfsi_users_detail;
use View;
use File;


class PMSController extends Controller
{
    public function getPMS(Request $request) {
        $pmsData = Pms::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'pms.pms_cust_id')
            ->get(['pms.*', 'bfsi_users_details.*']);
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        return View::make('pms.pms-cards', ['pmss' => $pmsData, 'clients' => $clientData]);
    }

    public function getPmsAPI(Request $request) {
        $pmsData = Pms::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'pms.pms_cust_id')
            ->get(['pms.*', 'bfsi_users_details.*']);
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        return $pmsData;
    }

    public function pmsUpload(Request $request) {
        $id = $request->session()->get('id');
        $custid = $request->pms_cust_id;
        $allowedfileExtension=['pdf','jpg','png','docx'];

        $path = public_path('uploads').'/users/'.$custid;
        
        $userData = Bfsi_user::where('pk_user_id', $custid)->first();

        $newDate = date("Y-m-d", strtotime($request->panDOB));
        $files = request('pms_document');

        if(!File::exists($path)) {
            $pms_path = $path.'/pms';
            File::makeDirectory($path, 0777, true, true);
            File::makeDirectory($pms_path, 0777, true, true);
        } else {
            $pms_path = $path.'/pms';
            File::makeDirectory($pms_path, 0777, true, true);
        }

        $arr = [];
        foreach($files as $file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            //dd($check);
            if($check) {
                $fileName = $custid."_".time().'_'.$extension;  
                $file_upload_status = $file->move($pms_path, $filename);
                $arr[] = $filename;
            }
        }

        $filesList = implode("|",$arr);
        if($file_upload_status!="") {
            $pms = $this->insertData($id, $userData, $request, "SUCCESS", $filesList, $request->ip());
            // $res2 = (new EmailController)->send_itrv_status($userData, "KYC_UPLOAD_AUGMONT");
            if($request['pms_id']=="") {
                $data = [
                    'status_code' => 201,
                    'message' => 'PMS uploaded successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 200,
                    'message' => 'PMS uploaded successfully.'
                ];
            }
        } else {
            $data = [
                'status_code' => 424,
                'message' => 'Failed to upload the PMS file. Please try again.'
            ];
        }
        return $data;
    }

    public function insertData($id, $userData, $req, $file_upload_status, $file_uploaded_name, $ip) {
        $pms = new Pms();
        if($req['pms_id'] != "") {
            $pms = Pms::find($req['pms_id']);
            $pms->pms_id = $req['pms_id'];
            $pms->pms_modified_by = $id;
            $pms->pms_modified_ip = $ip;
            $pms->pms_document = $pms->pms_document."|".$file_uploaded_name;
        } else {
            $pms->pms_created_by = $id;
            $pms->pms_created_ip = $ip;
            $pms->pms_document = $file_uploaded_name;
        }
        $pms->pms_cust_id = $req['pms_cust_id'];
        $pms->pms_prod_type = $req['pms_prod_type'];
        $pms->pms_trans_date = $req['pms_trans_date'];
        $pms->pms_trans_type = $req['pms_trans_type'];
        $pms->pms_trans_amt = $req['pms_trans_amt'];
        $savePms = $pms->save();
        return $pms;
    }

    public function pmsById(Request $request) {
        $pmsData = Pms::where('pms_id', '=', $request->pms_id)->get()->first();
        return $pmsData;
    }

    public function getPmsByUser(Request $request) {
        $pmsData = Pms::where('pms_cust_id', '=', $request->id)->get();
        return $pmsData;
    }

    public function getPmsByUserAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;
            $pmsData = Pms::where('pms_cust_id', '=', $id)->get();
            $data = [
                "statusCode" => 201,
                "data" => $pmsData
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
}
