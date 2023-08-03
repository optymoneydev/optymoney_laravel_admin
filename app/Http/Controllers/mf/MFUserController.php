<?php

namespace App\Http\Controllers\mf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\customer\UserAuthController;
use Illuminate\Http\Request;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;

class MFUserController extends Controller
{

    public function ucc_check(Request $request) {
        $user = auth('userapi')->user();
        
        if($user) {
            $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                ->where('bfsi_user.pk_user_id', $user->pk_user_id)
                ->get(['bfsi_user.pk_user_id', 'bfsi_user.bse_id', 'bfsi_users_details.*']);
            // if($ucc_status['bse_id']=="") {
            //     $userInfo = $this->UCC_Mandate();
            //     $userInfo['bank_name'] = $data['bank_name'];
            //     $userInfo['acc_no'] = $data['acc_no'];
            //     $userInfo['ifsc_code'] = $data['ifsc_code'];
            //     $userInfo['client_code'] = $ucc_status['bse_id'];
            //     $userInfo['tot_bank_ac'] = $total_records;
            //     $ucc_update = $buySell->create_user($userInfo);
            //     $pos = strpos($ucc_update['mandate_id'], "FAILED");
            //     if($pos>0) {
            //         $val['status'] = "failure";
            //         $val['msg'] =  "Please Update the basic details before going to proceed";
            //         $val['user'] = $userInfo;
            //         $val['mandate_id'] = $ucc_update;
            //     } else {
            //         $result = $this->db->db_run_query("Update bfsi_bank_details set mandate_id ='".$ucc_update['mandate_id']."' where pk_bank_detail_id ='".$bankid."'");	
            //         $val['mandate_id'] = $ucc_update;
            //         $val['status'] = "success";
            //         $val['user'] = $userInfo;
            //     }
                
            // } else {
            //     $userInfo = $this->UCC_Mandate();
            //     //print_r($userInfo);
            //     $userInfo['bank_name'] = $data['bank_name'];
            //     $userInfo['acc_no'] = $data['acc_no'];
            //     $userInfo['ifsc_code'] = $data['ifsc_code'];
            //     $userInfo['client_code'] = $ucc_status['bse_id'];
            //     $userInfo['tot_bank_ac'] = $total_records;
            //     if($ucc_status['bse_id']!="") {
            //         $ucc_update = $buySell->update_user($userInfo);
            //     } else {
            //         $ucc_update = $buySell->create_user($userInfo);
            //     }
            //     $pos = strpos($ucc_update['mandate_status'], "FAILED");
            //     if($pos>0) {
            //         $val['mandate'] = $ucc_update;
            //         $val['mandate_status'] = $ucc_update['mandate_status'];
            //         $val['mandate_id'] = $ucc_update['mandate_id'];
            //         $val['status'] = "failure";
            //         $val['ucc_status'] = $ucc_update['ucc_status'];
            //     } else {
            //         $result = $this->db->db_run_query("Update bfsi_bank_details set mandate_id ='".$ucc_update['mandate_status']."' where pk_bank_detail_id ='".$bankid."'");	
            //         $val['mandate'] = $ucc_update;
            //         $val['mandate_status'] = json_decode($ucc_update['mandate_status']);
            //         $val['ucc_status'] = $ucc_update['ucc_status'];
            //         $val['bank_id'] = json_decode($ucc_update);
            //         $val['status'] = "success";
            //     }
            // }
            // $val['ucc_status'] = $ucc_status;
            return $userData;
            
        } else {
            return "no data";
        }
	}

    public function mandateCheck(Request $request) {
        $user = auth('userapi')->user();
        
        if($user) {
            $bankData = Bfsi_bank_details::where('bfsi_bank_details.fr_user_id', $user->pk_user_id)
                ->get();
            return $bankData;
        } else {
            return "no data";
        }
    }

    public function createBSEAccount(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);

        }
    }
}
