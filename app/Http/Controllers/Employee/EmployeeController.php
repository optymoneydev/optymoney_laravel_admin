<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use App\Http\Controllers\Augmont\AugmontController;
Use App\Models\Employee;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_bank_details;
Use App\Models\Bfsi_user;
Use App\Models\Empcust;
use View;
use File;


class EmployeeController extends Controller
{
    public function getEmpCards(Request $request) {
        $empData = Employee::all();
        return View::make('hr.employee-cards', ['articles' => $empData]);
        // return View::make('crm.client-cards', ['articles' => $empData]);
    }

    public function getEmpClientCards(Request $request) {
        $empData = Employee::all();
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        $empCustData = Empcust::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'emp_cust.cust_id')
            ->join('emp_master', 'emp_master.pk_emp_id', '=', 'emp_cust.emp_id')
            ->get(['emp_cust.id', 'bfsi_users_details.cust_name', 'emp_master.full_name'])
            ->sortByDesc("id");
        return View::make('hr.emp-cust-cards', ['articles' => $empData, 'clients' => $clientData, 'empCustData' => $empCustData]);
    }
    
    public function addEmpCustMap(Request $request) {
        foreach ($request->cust_id as $sch) {
            $empcust = new Empcust();
            $empcust->emp_id = $request->emp_id;
            $empcust->cust_id = $sch;
            $saveData = $empcust->save();   
        }
        return $saveData;
    }

    public function getEmpCardsObj() {
        $empData = Employee::all();
        return $empData;
    }

    public function getEmpCard($id) {
        $empData = Employee::where('pk_emp_id',$id)->first(); 
        return View::make('hr.employee-profile', ['employee' => $empData]);
    }

    public function getEmpCardEdit($id) {
        $empData = Employee::where('pk_emp_id',$id)->first(); 
        return View::make('hr.employee-edit-profile', ['employee' => $empData]);
    }

    public function newEmployee(Request $request) {
        $id = $request->session()->get('id');
        $emp = new Employee();
        $emp->full_name = $request->full_name;
        $emp->emp_no = $request->emp_no;
        $emp->official_email = $request->official_email; 
        $emp->official_mobile = $request->official_mobile;
        $emp->password = Hash::make($request->password);
        $emp->created_by = $id; 
        $saveEmp = $emp->save();

        $emp = new Employee();
        $emp->full_name = $request->full_name;
        $emp->emp_no = $request->emp_no;
        $emp->official_email = $request->official_email; 
        $emp->official_mobile = $request->official_mobile;
        $emp->password = Hash::make($request->password);
        $emp->created_by = $id; 
        $saveEmp = $emp->save();
        
        return $saveEmp;
    }

    public function updateEmployee(Request $request) {
        $id = $request->session()->get('id');
        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'emp_no' => $request->emp_no,
            'official_email' => $request->official_email,
            'official_mobile' => $request->official_mobile,
            'password' => $request->password,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

    public function updatePersonal(Request $request) {
        $id = $request->session()->get('id');
        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'uan_no' => $request->uan_no, 
            'pf_no' => $request->pf_no,
            'esi_no' => $request->esi_no,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'marital_status' => $request->marital_status,
            'father_name' => $request->father_name,
            'spouse_name' => $request->spouse_name,
            'personal_mobile' => $request->personal_mobile,
            'personal_email' => $request->personal_email,
            'alternate_contact_person' => $request->alternate_contact_person,
            'alternate_contact_mobile' => $request->alternate_contact_mobile,
            'employee_status' => $request->employee_status,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

    public function updateOfficial(Request $request) {
        $id = $request->session()->get('id');
        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'doj' => $request->doj,
            'access_code' => $request->access_code,
            'department' => $request->department,
            'designation' => $request->designation,
            'role' => $request->role,
            'd_drive_access' => $request->d_drive_access,
            'laptop_name' => $request->laptop_name,
            'laptop_id' => $request->laptop_id,
            'id_card' => $request->id_card,
            'authorization_letter' => $request->authorization_letter,
            'exit_date' => $request->exit_date,
            'employee_status' => $request->employee_status,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

    public function updateBank(Request $request) {
        $id = $request->session()->get('id');
        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'personal_bank_name' => $request->personal_bank_name,
            'personal_bank_acno' => $request->personal_bank_acno,
            'personal_name_as_on_bank' => $request->personal_name_as_on_bank,
            'personal_ifsc_code' => $request->personal_ifsc_code,
            'salary_bank_name' => $request->salary_bank_name,
            'salary_bank_acno' => $request->salary_bank_acno,
            'salary_name_as_on_bank' => $request->salary_name_as_on_bank,
            'salary_ifsc_code' => $request->salary_ifsc_code,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

    public function updateAddress(Request $request) {
        $id = $request->session()->get('id');
        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'present_address_line1' => $request->present_address_line1,
            'present_address_line2' => $request->present_address_line2,
            'present_city' => $request->present_city,
            'present_state' => $request->present_state,
            'present_pincode' => $request->present_pincode,
            'permanent_address_line1' => $request->permanent_address_line1,
            'permanent_address_line2' => $request->permanent_address_line2,
            'permanent_city' => $request->permanent_city,
            'permanent_state' => $request->permanent_state,
            'permanent_pincode' => $request->permanent_pincode,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

    public function updateDocuments(Request $request) {
        
        $id = $request->session()->get('id');
        $empid = $request->eid;
        $emp = Employee::where('pk_emp_id', $empid)->first();

        $path = public_path('uploads').'/employees/'.$request->eid;
        
        if(!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $pan_uploaded_name = "";
        $pan = request('pan_upload');
        if($pan != null) {
            $pan_path = $pan->getPathname();
            // $file_mime = $file->getMimeType('image');
            $pan_uploaded_name = $pan->getClientOriginalName();
            // $fileName = $empid."_PAN_'.$request->pan_upload->extension();  
            $pan_upload_status = $request->pan_upload->move($path, $pan_uploaded_name);
        }

        $aadhar_uploaded_name = "";
        $aadhar = request('aadhar_upload');
        if($aadhar != null) {
            $aadhar_path = $aadhar->getPathname();
            // $file_mime = $file->getMimeType('image');
            $aadhar_uploaded_name = $aadhar->getClientOriginalName();
            // $fileName = $empid."_PAN_'.$request->pan_upload->extension();  
            $aadhar_upload_status = $request->aadhar_upload->move($path, $aadhar_uploaded_name);
        }

        $cheque_uploaded_name = "";
        $cheque = request('cheque_upload');
        if($cheque != null) {
            $cheque_path = $cheque->getPathname();
            // $file_mime = $file->getMimeType('image');
            $cheque_uploaded_name = $cheque->getClientOriginalName();
            // $fileName = $empid."_PAN_'.$request->pan_upload->extension();  
            $cheque_upload_status = $request->cheque_upload->move($path, $cheque_uploaded_name);
        }

        $passport_uploaded_name = "";
        $passport = request('passport_upload');
        if($passport != null) {
            $passport_path = $passport->getPathname();
            // $file_mime = $file->getMimeType('image');
            $passport_uploaded_name = $passport->getClientOriginalName();
            // $fileName = $empid."_PAN_'.$request->pan_upload->extension();  
            $passport_upload_status = $request->passport_upload->move($path, $passport_uploaded_name);
        }

        $qualification_uploaded_name = "";
        $qualification = request('qualification_upload');
        if($qualification != null) {
            $qualification_path = $qualification->getPathname();
            // $file_mime = $file->getMimeType('image');
            $qualification_uploaded_name = $qualification->getClientOriginalName();
            // $fileName = $empid."_PAN_'.$request->pan_upload->extension();  
            $qualification_upload_status = $request->qualification_upload->move($path, $qualification_uploaded_name);
        }

        $empStatus = Employee::where('pk_emp_id', $request->eid)->update([
            'pan' => $request->pan,
            'aadhar' => $request->aadhar,
            'passport_no' => $request->passport_no,
            'qualification' => $request->qualification,
            'pan_upload' => $pan_uploaded_name,
            'aadhar_upload' => $aadhar_uploaded_name,
            'cheque_upload' => $cheque_uploaded_name,
            'passport_upload' => $passport_uploaded_name,
            'qualification_upload' => $qualification_uploaded_name,
            'updated_by' => $id
        ]);
        return $empStatus;
    }

}
