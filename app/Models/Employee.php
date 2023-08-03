<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    protected $table = 'emp_master';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_emp_id', 'emp_no', 'access_code', 'full_name', 'doj', 'department', 'role', 'designation', 'uan_no', 'pf_no', 'esi_no', 'dob', 'gender', 'blood_group', 'marital_status', 
        'father_name', 'spouse_name', 'official_mobile', 'official_email', 'personal_email', 'personal_mobile', 'alternate_contact_person', 'alternate_contact_mobile', 'pan', 
        'pan_upload', 'aadhar', 'aadhar_upload', 'passport_no', 'passport_upload', 'qualification', 'qualification_upload', 'add_qualification', 'personal_bank_name', 'personal_bank_acno', 
        'personal_name_as_on_bank', 'personal_ifsc_code', 'salary_bank_name', 'salary_bank_acno', 'salary_name_as_on_bank', 'salary_ifsc_code', 'present_address_line1', 
        'present_address_line2', 'present_city', 'present_state', 'present_pincode', 'permanent_address_line1', 'permanent_address_line2', 'permanent_city', 'permanent_state', 
        'permanent_pincode', 'd_drive_access', 'laptop_name', 'laptop_id', 'id_card', 'authorization_letter', 'exit_date', 'employee_status', 'remarks', 'password', 'login_time', 
        'logout_time', 'login_ip', 'created_by', 'updated_by', 'profile_img'
      
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'login_time' => 'datetime', 
        'logout_time' => 'datetime',
    ];
}
