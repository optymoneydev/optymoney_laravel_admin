<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\Employee\EmployeeController;
Use App\Models\Employee;
Use App\Models\Emp_roles;
Use App\Models\Bfsi_users_detail;
use View;
use File;


class EmployeeMngtController extends Controller
{
    public function getEmployeeRoles(Request $request) {
        $rolesData = Emp_roles::all();
        return $rolesData;
    }

    public function saveUserRoles(Request $request) {
        $id = $request->session()->get('id');
        $empRoleData = Emp_roles::where('roleName',$request->roleName)->first(); 
        if ($request->has('id')) {
            $empRoleCount = Emp_roles::where('roleName',$request->roleName)->get()->count();
            if($empRoleCount>1) {
                $data = [
                    'status_code' => 409,
                    'message' => 'Role already exist.'
                ];
            } else {
                $empRoleData->roleName = $request->roleName;
                $empRoleData->roles = $request->menulist;
                $empRoleData->updated_by = $id; 
                $saveEmp = $empRoleData->save();
                if($saveEmp==1) {
                    $data = [
                        'status_code' => 201,
                        'message' => 'Role Updated.'
                    ];
                } else {
                    $data = [
                        'status_code' => 400,
                        'message' => 'Role updation failed.'
                    ];
                }
            }
        } else {
            if($empRoleData) {
                $data = [
                    'status_code' => 409,
                    'message' => 'Role already exist.'
                ];
            } else {
                $emp = new Emp_roles();
                $emp->roleName = $request->roleName;
                $emp->roles = $request->menulist;
                $emp->created_by = $id; 
                $saveEmp = $emp->save();
                if($saveEmp==1) {
                    $data = [
                        'status_code' => 201,
                        'message' => 'Role added.'
                    ];
                } else {
                    $data = [
                        'status_code' => 200,
                        'message' => 'Role adding failed.'
                    ];
                }
            }
        }
        
        return $data;
    }

    public function roleById(Request $request) {
        $rolesData = Emp_roles::where('id', '=', $request->id)->get()->first();
        return $rolesData;
    }

    public function deleteRole(Request $request) {
        $rolesData = Emp_roles::where('id', '=', $request->id)->delete();
        if($rolesData==1) {
            $data = [
                'status_code' => 201,
                'message' => 'Role Deleted.'
            ];
        } else {
            $data = [
                'status_code' => 400,
                'message' => 'Role deletion failed.'
            ];
        }
        return $data;
    }

    public function getEmployeeRole(Request $request) {
        $id = $request->session()->get('id');
        $data = Employee::join('emp_roles', 'emp_roles.id', '=', 'emp_master.role')
              ->where('emp_master.emp_no', $id)
              ->get([
                  'emp_master.role', 
                  'emp_roles.roleName', 
                  'emp_roles.roles'])->first();
        return $data;
    }

    public function getEmployeeRoleInternal($id) {
        $data = Employee::join('emp_roles', 'emp_roles.id', '=', 'emp_master.role')
              ->where('emp_master.emp_no', $id)
              ->get([
                  'emp_master.role', 
                  'emp_roles.roleName'])->first();
        return $data;
    }
}
