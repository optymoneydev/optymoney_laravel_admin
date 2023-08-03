<?php

namespace App\Http\Controllers\crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Users\UsersBankController;
Use App\Models\Employee;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_bank_details;
Use App\Models\Empcust;
use Illuminate\Support\Facades\Hash;
use View;
use Session;


class ClientController extends Controller
{
    public function getclientsCards(Request $request) {
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->get(['bfsi_user.*', 'bfsi_users_details.*'])
              ->sortByDesc("pk_user_id");
        return View::make('crm.client-cards', ['articles' => $clientData]);
    }

    public function getEmpClientCard(Request $request) {
        if(Session::get('emp')->department == "partner") {
            $empCustData = Empcust::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'emp_cust.cust_id')
                ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'bfsi_users_details.fr_user_id')
                ->where('emp_cust.emp_id', Session::get('LoggedUser'))
                ->get(['emp_cust.id', 'bfsi_users_details.*', 'bfsi_user.*']);
                        // $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            //       
            //       ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();

            return $empCustData;
        } else {
            $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                // ->sortByDesc("bfsi_user.pk_user_id")
                // ->limit(100)
                ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.pan_number', 'bfsi_user.bse_id', 'bfsi_users_details.pan_number', 'bfsi_users_details.contact_no', 'bfsi_users_details.cust_name', 'bfsi_users_details.nsdl_kyc_res', 'bfsi_user.user_status', 'bfsi_user.created_from', 'bfsi_user.created_date', 'bfsi_users_details.fr_user_id']);
            return $clientData;
        }
    }
    public function getClientCard($id) {
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('pk_user_id', $id)
              ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();

        $bankData = (new UsersBankController)->allUserBanksByID($id);
        $clientData->bank = $bankData;
        return View::make('crm.client-profile', ['client' => $clientData]);
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

    public function saveUser(Request $request) {
        $id = $request->session()->get('id');
        $bfsi_user = new Bfsi_user();
        $bfsi_user->login_id = $request->emailAddress;
        $bfsi_user->password = Hash::make($request->password);
        $bfsi_user->aug_pswd = Hash::make($request->password); 
        $bfsi_user->created_from = "admin";
        $bfsi_user->contact = $request->contact; 
        $saveUser = $bfsi_user->save();
        if($saveUser == 1) {
            // return $saveUser;
            // 'pk_user_detail_id', 'fr_user_id', 'cust_name', 'father_name', 'pan_number', 'aadhaar_no', 'isd', 'contact_no', 'landline', 'email', 'sex', 'dob', 
            // 'age', 'nationality', 'religion', 'company_name', 'address1', 'address2', 'address3', 'city', 'state', 'pincode', 'country', 'cor_addr1', 'cor_addr2', 
            // 'cor_addr3', 'cor_city', 'cor_state', 'cor_country', 'cor_zip', 'profession', 'mother_name', 'occupation', 'nominee_name', 'nominee_dob', 
            // 'r_of_nominee_w_app', 'bfsi_users_detailscol', 'taxstatus', 'will_assets', 'custodianselected', 'kyc_onboarding_id', 'kyc_status', 'nsdl_kyc_status', 
            // 'nsdl_kyc_res', 'mode_holding', 'pi_place', 'pi_date', 'cor_as_perm', 'signature', 'cancelledcheque', 'signatureURL', 'cancelledchequeURL', 'ucc_submission', 
            // 'ucc_form_url', 'ucc_form_filename', 'pan_file', 'aug_kyc_status', 'details_modified_by'
            $bfsi_user_details = new Bfsi_users_detail();
            $bfsi_user_details->fr_user_id = $bfsi_user->pk_user_id;
            $bfsi_user_details->cust_name = $request->fname;
            $bfsi_user_details->father_name = $request->lname; 
            $bfsi_user_details->pan_number = $request->pan;
            $bfsi_user_details->aadhaar_no = $request->aadhaar; 
            $bfsi_user_details->contact_no = $request->contact; 
            $bfsi_user_details->email = $request->emailAddress; 
            $bfsi_user_details->dob = $request->dob; 
            $bfsi_user_details->address1 = $request->address1; 
            $bfsi_user_details->address2 = $request->address2; 
            $bfsi_user_details->city = $request->cityName; 
            $bfsi_user_details->state = $request->stateName;
            $bfsi_user_details->augcity = $request->city;
            $bfsi_user_details->augstate = $request->state; 
            $bfsi_user_details->nominee_name = $request->nominee_name; 
            $bfsi_user_details->nominee_dob = $request->nominee_dob; 
            $bfsi_user_details->r_of_nominee_w_app = $request->nominee_relation; 
            $saveEmp = $bfsi_user_details->save();
            if($saveEmp == 1) {
                $response = [
                    'status_code' => 200,
                    'message' => 'User added successfully'
                ];
            } else {
                $response = [
                    'status_code' => 400,
                    'message' => 'User adding failed'
                ];
            }
        } else {
            $response = [
                'status_code' => 400,
                'message' => 'User adding failed'
            ];
        }

        return $response;
    }
}
