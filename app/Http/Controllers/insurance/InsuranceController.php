<?php

namespace App\Http\Controllers\insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\Employee\EmployeeController;
Use App\Models\Employee;
Use App\Models\Bfsi_user;
Use App\Models\Insurance;
Use App\Models\Bfsi_users_detail;
use View;
use File;


class InsuranceController extends Controller
{
    public function getInsurance(Request $request) {
        $insuranceData = Insurance::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'insurance.ins_cust_id')
            ->get(['insurance.*', 'bfsi_users_details.*']);
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        return View::make('insurance.insurance-cards', ['insurances' => $insuranceData, 'clients' => $clientData]);
    }

    public function getInsuranceAPI(Request $request) {
        $insuranceData = Insurance::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'insurance.ins_cust_id')
            ->get(['insurance.*', 'bfsi_users_details.*']);
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        return $insuranceData;
    }

    public function insuranceUpload(Request $request) {
        $id = $request->session()->get('id');
        $custid = $request->ins_cust_id;
        $allowedfileExtension=['pdf','jpg','png','docx'];

        $path = public_path('uploads').'/users/'.$custid;
        
        $userData = Bfsi_user::where('pk_user_id', $custid)->first();

        $newDate = date("Y-m-d", strtotime($request->panDOB));
        $files = request('ins_policy_document');

        if(!File::exists($path)) {
            $insurance_path = $path.'/insurance';
            File::makeDirectory($path, 0777, true, true);
            File::makeDirectory($insurance_path, 0777, true, true);
        } else {
            $insurance_path = $path.'/insurance';
            File::makeDirectory($insurance_path, 0777, true, true);
        }

        $arr = [];
        foreach($files as $file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            //dd($check);
            if($check) {
                $fileName = $custid."_".time().'_'.$extension;  
                $file_upload_status = $file->move($insurance_path, $filename);
                $arr[] = $filename;
            }
        }

        $filesList = implode("|",$arr);
        if($file_upload_status!="") {
            $ins = $this->insertData($id, $userData, $request, "SUCCESS", $filesList, $request->ip());
            // $res2 = (new EmailController)->send_itrv_status($userData, "KYC_UPLOAD_AUGMONT");
            if($request['ins_id']=="") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Insurance uploaded successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 200,
                    'message' => 'Insurance uploaded successfully.'
                ];
            }
        } else {
            $data = [
                'status_code' => 424,
                'message' => 'Failed to upload the Insurance file. Please try again.'
            ];
        }
        return $data;
    }

    public function insertData($id, $userData, $req, $file_upload_status, $file_uploaded_name, $ip) {
        $insurance = new Insurance();
        if($req['ins_id'] != "") {
            $insurance = Insurance::find($req['ins_id']);
            $insurance->ins_id = $req['ins_id'];
            $insurance->ins_modified_by = $id;
            $insurance->ins_modified_ip = $ip;
            $insurance->ins_policy_document = $insurance->ins_policy_document."|".$file_uploaded_name;
        } else {
            $insurance->ins_created_by = $id;
            $insurance->ins_created_ip = $ip;
            $insurance->ins_policy_document = $file_uploaded_name;
        }
        $insurance->ins_cust_id = $req['ins_cust_id'];
        $insurance->ins_prod_type = $req['ins_prod_type'];
        $insurance->ins_comp_name = $req['ins_comp_name'];
        $insurance->ins_comp_branch = $req['ins_comp_branch'];
        $insurance->ins_policy_name = $req['ins_policy_name'];
        $insurance->ins_policy_no = $req['ins_policy_no'];
        $insurance->ins_policy_issued_date = $req['ins_policy_issued_date'];
        $insurance->ins_policy_maturity_date = $req['ins_policy_maturity_date'];
        $insurance->ins_policy_prem_amt = $req['ins_policy_prem_amt'];
        $insurance->ins_policy_sa_amt = $req['ins_policy_sa_amt'];
        $insurance->ins_policy_pay_mode = $req['ins_policy_pay_mode'];
        $insurance->ins_policy_status = $req['ins_policy_status'];
        if($req['ins_prod_type'] == "general") {
            $insurance->ins_policy_term_years = $req['ins_policy_term_years'];
            $ins_policy_premium_pay_term_years = $req['ins_policy_premium_pay_term_years'];
            $insurance->ins_policy_next_prem_date = $req['ins_policy_next_prem_date'];
            $insurance->ins_policy_money_back = $req['ins_policy_money_back'];
            $insurance->ins_policy_acci_death_benefit = $req['ins_policy_acci_death_benefit'];
        } else {
            if($req['ins_prod_type'] == "life") {
                $insurance->ins_policy_term_years = $req['ins_policy_term_years'];
                $ins_policy_premium_pay_term_years = $req['ins_policy_premium_pay_term_years'];
                $insurance->ins_policy_next_prem_date = $req['ins_policy_next_prem_date'];
                $insurance->ins_policy_plan_type = $req['ins_policy_plan_type'];
                $insurance->ins_policy_money_back = $req['ins_policy_money_back'];
                $insurance->ins_policy_acci_death_benefit = $req['ins_policy_acci_death_benefit'];
                $insurance->ins_policy_nominee_name = $req['ins_policy_nominee_name'];
                $insurance->ins_policy_nominee_relation = $req['ins_policy_nominee_relation'];
                $insurance->ins_policy_loan_taken = $req['ins_policy_loan_taken'];
                $insurance->ins_policy_loan_date = $req['ins_policy_loan_date'];
                $insurance->ins_policy_bal_units = $req['ins_policy_bal_units'];
                $insurance->ins_policy_bal_date = $req['ins_policy_bal_date'];
                $insurance->ins_policy_cur_value = $req['ins_policy_cur_value'];
                $insurance->ins_policy_exp_maturity_value = $req['ins_policy_exp_maturity_value'];
            } else {
                if($req['ins_prod_type'] == "health") {
                    $ins_policy_premium_pay_term_years = $req['ins_policy_premium_pay_term_years'];
                    $insurance->ins_policy_next_prem_date = $req['ins_policy_next_prem_date'];
                } else {
                    if($req['ins_prod_type'] == "motor") {
                        $insurance->ins_policy_veh_type = $req['ins_policy_veh_type'];
                        $insurance->ins_policy_veh_reg_no = $req['ins_policy_veh_reg_no'];
                        $insurance->ins_policy_veh_model = $req['ins_policy_veh_model'];
                    }
                }
            }
        }
        $insurance->ins_policy_remarks = $req['ins_policy_remarks'];
        $saveInsurance = $insurance->save();
        return $insurance;
    }

    public function insuranceById(Request $request) {
        $insuranceData = Insurance::where('ins_id', '=', $request->ins_id)->get()->first();
        return $insuranceData;
    }

    public function getHelpdeskCard($id) {
        $helpdeskData = Tbl_uploads::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'tbl_uploads.user_id')
              ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'tbl_uploads.user_id')
              ->where('id', $id)
              ->get(['tbl_uploads.*', 'bfsi_user.*', 'bfsi_users_details.*'])->first();
        $bankDetails = (new UsersBankController)->allUserBanksByID($helpdeskData['user_id']);
        $empDetails = (new EmployeeController)->getEmpCardsObj();
        return View::make('itr.helpdesk-profile', ['helpdesk' => $helpdeskData, 'bankdata' => $bankDetails, 'empdata' => $empDetails] );
    }

    public function updateHelpdeskStatus(Request $request) {
        
        $id = $request->session()->get('id');
        $helpdeskId = $request->statusId;
        $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
            'augcity' => $data['city'],
            'augstate' => $data['state'],
            'dob' => $data['dob']
        ]);
        $helpdesk = Tbl_uploads::find($helpdeskId);

        // $userData = Bfsi_user::where('pk_user_id', $custid)->first();

        // $tbl_uploads = new Tbl_uploads();
        // $tbl_uploads->fr_user_id = $userData['pk_user_id'];
        // $saveTbl_uploads = $tbl_uploads->save();
        return $helpdesk;
    }

    public function getEmpCardEdit($id) {
        $empData = Employee::where('pk_emp_id',$id)->first(); 
        return View::make('hr.employee-edit-profile', ['employee' => $empData]);
    }

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
                  'bfsi_users_details.contact_no', 
                  'bfsi_users_details.cust_name', 
                  'bfsi_users_details.pan_number', 
                  'bfsi_users_details.augcity', 
                  'bfsi_users_details.augstate', 
                  'bfsi_users_details.pincode', 
                  'bfsi_users_details.dob', 
                  'bfsi_users_details.nominee_name', 
                  'bfsi_users_details.nominee_dob', 
                  'bfsi_users_details.r_of_nominee_w_app' ])->first();
        return $userData;
    }

    public function getInsuranceByUser(Request $request) {
        $insuranceData = Insurance::where('ins_cust_id', '=', $request->id)->get();
        return $insuranceData;
    }

    /**
        * @OA\Get(
        * path="/api/insurance/getInsuranceByUserAPI",
        * operationId="getInsuranceByUserAPI",
        * tags={"Insurance"},
        * summary="Get Insurance details by user",
        * description="Get Insurance details by user",
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
    public function getInsuranceByUserAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;
            $insuranceData = Insurance::where('ins_cust_id', '=', $id)->get();
            $data = [
                "statusCode" => 201,
                "data" => $insuranceData
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
