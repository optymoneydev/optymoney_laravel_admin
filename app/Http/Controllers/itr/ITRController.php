<?php

namespace App\Http\Controllers\itr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\customer\UserAuthController;
use App\Http\Controllers\EmailController;
Use App\Models\Employee;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_itr;
Use App\Models\Tbl_uploads;
Use App\Models\KycStatus;
Use App\Models\FileUploads;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_bank_details;
use View;
use File;


class ITRController extends Controller
{
    public function getitrFiled(Request $request) {
        $itrData = Bfsi_itr::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_itr.fr_user_id')
              ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'bfsi_itr.fr_user_id')
              ->get(['bfsi_itr.*', 'bfsi_user.login_id', 'bfsi_users_details.*'])
              ->sortByDesc("form_created_date");
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
              ->sortByDesc("pk_user_id");
        return View::make('itr.itr-filed-cards', ['articles' => $itrData, 'clients' => $clientData]);
    }

    public function itrVUpload(Request $request) {
        
        $id = $request->session()->get('id');
        $custid = $request->itr_cust_id;
        $userData = Bfsi_user::where('pk_user_id', $custid)->first();

        $upstatus = Bfsi_users_detail::where('fr_user_id', $custid)->update([
            'pan_number' => $request->pan
        ]);

        $newDate = date("Y-m-d", strtotime($request->panDOB));

        $itrv_fileName = "";
        if(request('itrv_file')) {
            $file               = request('itrv_file');
            $file_path          = $file->getPathname();
            $file_uploaded_name = $file->getClientOriginalName();
            
            $path = public_path('uploads').'/users/'.$custid;
            
            if(!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $itrv_fileName = $custid."_".time().'_itrv.pdf';//.$request->itrv_file->extension();  
            $file_upload_status = $request->itrv_file->move($path, $itrv_fileName);
        }

        $itr_comp_fileName = "";
        if(request('itrv_comp_file')) {
            $comp_file               = request('itrv_comp_file');
            $comp_file_path          = $comp_file->getPathname();
            $comp_file_uploaded_name = $comp_file->getClientOriginalName();
            
            $path = public_path('uploads').'/users/'.$custid;
            
            $itr_comp_fileName = $custid."_".time().'_itr_comp.'.$request->itrv_comp_file->extension();  
            $file_upload_status = $request->itrv_comp_file->move($path, $itr_comp_fileName);
        }
        
        $ins = $this->insertData($id, $userData, $request->pan, $request->year, "SUCCESS", $itrv_fileName, $itr_comp_fileName, $request->sec_80c, $request->sec_80d, $request->ip());
        // $res2 = (new EmailController)->send_itrv_status($userData, "KYC_UPLOAD_AUGMONT");
        $data = [
            'status_code' => 200,
            'message' => 'ITRV Added Successfully.'
        ];
        return $data;
        // } else {
        //     $data = [
        //         'status_code' => 424,
        //         'message' => 'Failed to upload the ITRV file. Please try again.'
        //     ];
        //     return $data;
        // }
    }

    public function insertData($id, $userData, $pan, $year, $file_upload_status, $itrv_fileName, $itr_comp_fileName, $sec_80c, $sec_80d, $ip) {
        $bfsi_itr = new Bfsi_itr();
        $bfsi_itr->fr_user_id = $userData['pk_user_id'];
        $bfsi_itr->fr_customer_id = $userData['fr_customer_id'];
        $bfsi_itr->asses_year = $year; 
        if($file_upload_status!="") {
            $bfsi_itr->itr_status = "Success";
        } else {
            $bfsi_itr->itr_status = "Failed";
        }
        $bfsi_itr->itr_pan = $pan;
        $bfsi_itr->itr_v = $itrv_fileName;
        $bfsi_itr->itrv_comp_file = $itr_comp_fileName;
        $bfsi_itr->sec_80c = $sec_80c;
        $bfsi_itr->sec_80d = $sec_80d;
        $bfsi_itr->created_by = $id; 
        $bfsi_itr->created_ip = $ip; 
        $saveBfsi_itr = $bfsi_itr->save();
        return $bfsi_itr;
    }

    public function getItrHelpdesk(Request $request) {
        $itrData = Tbl_uploads::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'tbl_uploads.user_id')
              ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'tbl_uploads.user_id')
              ->get(['tbl_uploads.*', 'bfsi_user.login_id', 'bfsi_users_details.*'])
              ->sortByDesc("created_at");
        return View::make('itr.itr-helpdesk-cards', ['articles' => $itrData]);
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

    public function getDocsByUser(Request $request) {
        $userDocs = Bfsi_itr::where('fr_user_id', $request->id)->get();
        $custDocs = Tbl_uploads::where('user_id', $request->id)->get();
        $goldDocs = KycStatus::where('fr_user_id', $request->id)->get();
        $fileUploadDocs = FileUploads::where('fr_user_id', $request->id)->get();
        
        $fetch_portfolio["itr"] = $userDocs;
		$fetch_portfolio["docs"] = $custDocs;
		$fetch_portfolio["goldupload"] = $goldDocs;
		$fetch_portfolio["fileuploads"] = $fileUploadDocs;
		return $fetch_portfolio;
    }

    public function getDocs(Request $request) {
        $data = [
            "statusCode" => 401,
            "message" => "Unauthenticated_data."
        ];
        $user = auth('userapi')->user();
        // return $user;
        if($user) {
            
            $id = $user->pk_user_id;
            $userDocs = Bfsi_itr::where('fr_user_id', $id)->get();
            $custDocs = Tbl_uploads::where('user_id', $id)->get();
            $goldDocs = KycStatus::where('fr_user_id', $id)->get();
            $fileUploadDocs = FileUploads::where('fr_user_id', $id)->get();
            
            $fetch_portfolio["itr"] = $userDocs;
            $fetch_portfolio["docs"] = $custDocs;
            $fetch_portfolio["goldupload"] = $goldDocs;
            $fetch_portfolio["fileuploads"] = $fileUploadDocs;
            $data = [
                "statusCode" => 201,
                "data" => $fetch_portfolio
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

    public function itrRegistrationAPI(Request $request) {
        $allowedfileExtension=['pdf','jpg','png','docx'];
        $user = auth('userapi')->user();
        $newUser = 0;
        if($user) {
            $id = $user->pk_user_id;
            $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                ->where('bfsi_user.pk_user_id', $id)
                ->get(['bfsi_user.*', 'bfsi_users_details.*']);
            $userDetails = Bfsi_users_detail::where('fr_user_id', $id)->update([
                'cust_name' => $request->fname, 
                'contact_no' => $request->hd_mobile, 
                'email' => $request->hd_email, 
                'father_name' => $request->father_name, 
                'sex' => $request->sex, 
                'pan_number' => $request->pan, 
                'aadhaar_no' => $request->aadhaar, 
            ]);
            $newUser = 0;
        } else {
            $newUser = 1;
            $userCheck = Bfsi_user::where([['login_id','=',$request->hd_email]])->first();
			if($userCheck) {
				$newUser = 0;
				$id = $userCheck->pk_user_id;
				$userData = $userCheck;
                $response = [
                    'status_code' => 200,
                    'message' => 'Already Registered',
                    'id' => $id
                ];  
			} else {
                $user = Bfsi_user::create([
                    'contact' => $request->hd_mobile, 
                    'login_id' => $request->hd_email, 
                    'password' => bcrypt("optymoney")
                ]);
				$userDetails = Bfsi_users_detail::create([
                    'cust_name' => $request->fname, 
                    'contact_no' => $request->hd_mobile, 
                    'email' => $request->hd_email, 
                    'father_name' => $request->father_name, 
                    'gender' => $request->sex, 
                    'pan_number' => $request->pan, 
                    'aadhaar_no' => $request->aadhaar, 
                ]);
			}
        }

        $path = public_path('uploads').'/users/'.$id;
        if(!File::exists($path)) {
            $profile_path = $path.'/itrv1';
            File::makeDirectory($path, 0777, true, true);
            File::makeDirectory($profile_path, 0777, true, true);
        } else {
            $profile_path = $path.'/itrv1';
            File::makeDirectory($profile_path, 0777, true, true);
        }

        $files = [];
        $fileitrList = [];
        $addFileitrList = [];
        $noticeCopyList = [];
        $itrfiledcopyList = [];
        $addeassestList = [];

        if($request->itr_e == "itr") {
            if($request->file('fileitr') != null) {
                foreach($request->file('fileitr') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    if($check) {
                        $fileName = $id."_".$filename.'.'.$extension;  
                        $file_upload_status = $file->move($profile_path, $fileName);
                        $fileitrList[] = $fileName;
                    }
                }
            }
            if($request->file('addfileitr') != null) {
                foreach($request->file('addfileitr') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    if($check) {
                        $fileName = $id."_".$filename.'.'.$extension;  
                        $file_upload_status = $file->move($profile_path, $fileName);
                        $addFileitrList[] = $fileName;
                    }
                }
            }
        } else {
            if($request->file('noticeCopy') != null) {
                foreach($request->file('noticeCopy') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    if($check) {
                        $fileName = $id."_".$filename.'.'.$extension;  
                        $file_upload_status = $file->move($profile_path, $fileName);
                        $noticeCopyList[] = $fileName;
                    }
                }
            }
            if($request->file('itrfiledcopy') != null) {
                foreach($request->file('itrfiledcopy') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    if($check) {
                        $fileName = $id."_".$filename.'.'.$extension;  
                        $file_upload_status = $file->move($profile_path, $fileName);
                        $itrfiledcopyList[] = $fileName;
                    }
                }
            }
            if($request->file('addeassest') != null) {
                foreach($request->file('addeassest') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    if($check) {
                        $fileName = $id."_".$filename.'.'.$extension;  
                        $file_upload_status = $file->move($profile_path, $fileName);
                        $addeassestList[] = $fileName;
                    }
                }
            }
        }
		
        $itrForm = new Tbl_uploads ();
        // $itrForm->id = $request->pan; 
        
        $itrForm->taxplan = $request->taxPlanForm;
        $itrForm->pan = $request->pan;
        $itrForm->fathers_name = $request->father_name;
        $itrForm->aadhaar = $request->aadhaar;
        // $itrForm->amount = 0;
        $itrForm->user_id = $id;
        $itrForm->description = $request->description;
        // $itrForm->address = $request->pan;
        // $itrForm->file = $request->pan;
        // $itrForm->type = $request->pan;
        // $itrForm->size = $request->pan;
        // $itrForm->file_status = $request->pan;
        // $itrForm->return_file = $request->pan;
        // $itrForm->upload_date = $request->pan;
        $itrForm->bank = $request->bank;
        $itrForm->acno = $request->acno;
        $itrForm->ifsc = $request->ifsc;

        // $itrForm->tax_userid = $request->pan;
        // $itrForm->tax_pwd = $request->pan;
        $itrForm->c_acnt = $request->c_acnt;
        $itrForm->f_travel = $request->f_travel;
        $itrForm->e_bill = $request->e_bill;

        $itrForm->itr_e = $request->itr_e;
        $itrForm->noticeCopy = implode('|', $noticeCopyList);
        $itrForm->itrfiledcopy = implode('|', $itrfiledcopyList);
        $itrForm->addeassest = implode('|', $addeassestList);
        $itrForm->fileitr = implode('|',$fileitrList);
        $itrForm->addfileitr = implode('|',$addFileitrList);

        $itrFileUploads = $itrForm->save();
        if($itrFileUploads) {
            $messgae2 = "Documents are shared from ".$request->fname."<br>Email Address :".$request->hd_email."<br>Contact No. :".$request->hd_mobile;
            $res1 = (new EmailController)->send_itr_fileupload($request->hd_email, $request->fname, "Successful submission of ITR filing data with optymoney");
            $res2 = (new EmailController)->send_itr_fileupload("tax@optymoney.com", $request->fname, $messgae2);
            $data = [
                "status_code" => 201,
                "data" => $itrFileUploads,
                "message" => "ITR filing submitted Successfully",
                "user_email" => $res1,
                "admin_email" => $res2,
                "newUser" => $newUser
            ];
		} else {
			$data = [
                'msg' => "Thers is some issue.Please fill the data again properly", 
                'status_code' => 400,
                "newUser" => $newUser
            ];
		}
		return $data;
    }
}
