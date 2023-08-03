<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use View;
Use App\Models\Employee;
Use App\Models\Bfsi_users_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\Augmont\User\UserAugmontController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\KycController;

use Session;

class AuthController extends Controller {

    public function index() {
        return view('authentication/login');
    }  

    public function registration() {
        return view('registration');
    }
    
    public function postLogin(Request $request) {
        request()->validate([
          'email' => 'required',
          'password' => 'required',
        ]);

        $credentials['official_email'] = $request->email;
        $credentials['password'] = $request->password;

        $user = Employee::where([
          'official_email' => $request->email,
          'password' => md5($request->password)
        ])->first();
        if(isset($user)) {
          if($user['aug_pswd']==NULL || $user['aug_pswd']==""){
            if($user->passwd == md5($request->password)) { // If their password is still MD5
              $upstatus = Bfsi_admin_user::where('pk_admin_user_id', $user['pk_admin_user_id'])->update([
                'passwd' => Hash::make($request->password)
              ]);
              if (Auth::attempt($credentials)) {
                $user1 = Auth::user();
                Session::put('id', $user['pk_admin_user_id']);
                Session::put('custname', $user['cust_name']);
                $request->session()->put('LoggedUser', $user['pk_admin_user_id']);
                return redirect()->intended('dashboard/index');
              } else {
                error_log('Authentication failed...');
                return Redirect::to("authentication/login")->withSuccess('Opps! You have entered invalid credentials');
              }
            }
          } else {
            // dd($user);
            if (Auth::attempt($credentials)) {
              $user1 = Auth::user();
              Session::put('id', $user['pk_admin_user_id']);
              Session::put('custname', $user['cust_name']);
              $request->session()->put('LoggedUser', $user['pk_admin_user_id']);
              return redirect()->intended('dashboard/index');
              // dd($user1->pk_user_id);
            } else {
              // dd("not authenticated");
              error_log('Authentication failed...');
              return Redirect::to("authentication/login")->withSuccess('Opps! You have entered invalid credentials');
            }
          }
        } else {
          if (Auth::attempt($credentials)) {
            $user1 = Auth::user();
            Session::put('id', $user1['emp_no']);
            Session::put('emp_name', $user1['full_name']);
            Session::put('emp', $user1);
            $request->session()->put('LoggedUser', $user1['pk_emp_id']);
            return View::make('dashboard/index')->with('userInfo',$user1);
            // return redirect()->intended('dashboard/index');
          } else {
            error_log('Authentication failed...');
            return Redirect::to("authentication/login")->withSuccess('Opps! You have entered invalid credentials');
          }
        }
    }

    public function createAccount(Request $request) {  
        $data = $request->all();
        $user = \App\Models\Bfsi_user::where([
          'pk_user_id' => $data['uid'],
        ])->first();
        if($user) {
          $up_stat = $this->createUserProfile($data);
          if($up_stat) {
            $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
              'passwd' => md5($data['password']),
              'aug_pswd' => Hash::make($data['password']),
              'steps' => 3
            ]);
            if($userStatus>0) {
              $response = [
                'statusCode' => '201',
                'message' => 'Account created Successfully',
                'userStatus' => $userStatus,
                'steps' => 3
              ];
            } else {
              $response = [
                'statusCode' => '417',
                'message' => 'Account created Failed Try again',
              ];
            }
          } else {
            $response = [
              'statusCode' => '417',
              'message' => 'User Account not accessible. Please try after sometime'
            ];
          }
        } else {
          $response = [
            'statusCode' => '417',
            'message' => 'User Account not accessible. Please try after sometime'
          ];
        }
        return $response;
    }

    public function createUserProfile($data) {
      $userProfile = \App\Models\Bfsi_users_detail::where([
        'fr_user_id' => $data['uid'],
      ])->first();
      if($userProfile) {
        return Bfsi_users_detail::where('fr_user_id', $data['uid'])->update([
          'cust_name' => $data['fname']." ".$data['lname'],
          'contact_no' => $data['contact']
        ]);
      } else {
        return Bfsi_users_detail::create([
          'fr_user_id' => $data['uid'],
          'cust_name' => $data['fname']." ".$data['lname'],
          'contact_no' => $data['contact']
        ]);
      }
    }

    public function validatePanAadhaar(Request $request) {  
      $data = $request->all();
      $user = \App\Models\Bfsi_user::where([
        'pk_user_id' => $data['uid'],
      ])->first();
      if($user) {
        if($data['pan']!="") {
          $result = (new NsdlController)->getPasscodeEncyrt($data['pan'], $user['contact']);
          if(array_key_exists('ERROR', $result['data'])) {
            $kyc =  (new KycController)->insertKyc($data['uid'], "", "", $data['pan'], "", $data['aadhaar'], "", "", "", $result['data']['ERROR'], "", "", "", "", "", "", "", "", "nsdl");
          } else {
            $kyc =  (new KycController)->insertKyc($data['uid'], "", "", $data['pan'], "", $data['aadhaar'], "", "", "", $result['data']['APP_STATUS'], "", "", "", "", "", "", "", "", "nsdl");
          }
          $upstatus = Bfsi_users_detail::where('fr_user_id', $data['uid'])->update([
            'pan_number' => $data['pan'],
            'aadhaar_no' => $data['aadhaar'],
            'dob' => $data['dob'],
            'nsdl_kyc_status' => $result['status']
          ]);
        } else {
          $upstatus = Bfsi_users_detail::where('fr_user_id', $data['uid'])->update([
            'pan_number' => $data['pan'],
            'aadhaar_no' => $data['aadhaar'],
            'dob' => $data['dob']
          ]);
        }
        if($upstatus) {
          $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
            'steps' => 4
          ]);
          if($userStatus>0) {
            $response = [
              'statusCode' => '201',
              'uid' => $data['uid'],
              'message' => 'User profile updated successfully',
              'upstatus' => $upstatus,
              'steps' => 4
            ];
          } else {
            $response = [
              'statusCode' => '417',
              'message' => 'Account Update Failed Try again',
            ];
          }
        } else {
          $response = [
            'statusCode' => '417',
            'message' => 'User Account not accessible. Please try after sometime'
          ];
        }
      } else {

      }
      
      return $response;
    }

    public function finishSignup(Request $request) {
      $data = $request->all();
      // $result = (new NsdlController)->getPasscodeEncyrt();
      $upstatus = Bfsi_users_detail::where('fr_user_id', $data['uid'])->update([
        'augcity' => $data['city'],
        'augstate' => $data['state'],
        'nominee_name' => $data['nominee_name'],
        'nominee_dob' => $data['nominee_dob'],
        'r_of_nominee_w_app' => $data['nominee_relation']
      ]);
      if($upstatus) {
        if($data['city']==null || $data['state']==null || $data['nominee_name']==null || $data['nominee_dob']==null || $data['nominee_relation']==null) {
          $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
            'steps' => 4
          ]);
        }
        
        $userData = (new UsersController)->getUserDataByUID($data['uid']);
        if($userData->augid==null) {
          $augresult = (new UserAugmontController)->createAugmontAccount($userData);
          $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
            'augid' => "Augo".$data['uid'],
            'steps' => 5
          ]);
        } else {
          $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
            'steps' => 5
          ]);
        }
        
        if($userStatus) {
          // Sending email after creating the account
          $res2 = (new EmailController)->send_user_creation_email($userData);

          Session::put('id', $userData->pk_user_id);
          Session::put('custname', $userData->cust_name);
          $response = [
            'statusCode' => '201',
            'uid' => $data['uid'],
            'message' => 'User profile updated successfully',
            'upstatus' => $upstatus,
            'result' => $userStatus,
            'userdata' => $userData
          ];
        } else {
          $response = [
            'statusCode' => '417',
            'uid' => $data['uid'],
            'message' => 'User profile update failed',
            'upstatus' => $upstatus,
            'result' => $userStatus,
            'userdata' => $userData
          ];
        }
      } else {
        $response = [
          'statusCode' => '417',
          'message' => 'User Account not accessible. Please try after sometime'
        ];
      }
      return $response;
    }
    
    public function dashboard() {
      if(Auth::check()){
        return view('dashboard');
      }
       return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }

	public function create(array $data) {
	  return Bfsi_user::create([
	    'login_id' => $data['login_id'],
      'fr_customer_id' => " ",
      'bse_id' => " ",
      'alternate_email_id' => " ",
	    'communication_email' => "Permanent",
	    'user_status' => "Active",
      'signup_ip' => getenv('REMOTE_ADDR'),
      'signup_date' => \Carbon\Carbon::now()->timestamp,
      'profile_image' => " ",
      'last_login' => " ",
      'nach_update' => " ",
      'p_code' => " ",
      'user_org' => " ",
      'mpin' => " ",
      'details_modified_by' => " ",
      'password' => Hash::make($data['password'])
	  ]);


    // 'signup_ip' => $_SERVER['HTTP_CLIENT_IP']
//     getenv('REMOTE_ADDR');
// getenv('HTTP_X_FORWARDED_FOR');
	}

	  public function logout(Request $request) {
      // $this->guard()->logout();
      $request->session()->flush();
      $request->session()->regenerate();
      return redirect('/authentication/login');
    }

    public function profileAddressUpdate(Request $request) {
      $data = $request->all();
      $id = $request->session()->get('id');
      $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
        'augcity' => $data['city'],
        'augstate' => $data['state'],
        'dob' => $data['dob']
      ]);
      $orderData = Session::get('orderData');
      if($upstatus) {
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
        ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
        ->get(['bfsi_user.*', 'bfsi_users_details.*']);
        $augid = (new UserAugmontController)->checkUpdateAugmont($userData[0]);
        if($augid!="" || $augid!=NULL) {
          if($orderData['kycRequired']==false) {
            return View::make($orderData['redirectURL'], $orderData);
          } else {
            $augKycStatus = (new KycController)->aug_kyc_db_check($request);
            if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                $prevPath = (new GeneralController)->previousPath();
                return redirect($prevPath)->with('augKycStatus', $augKycStatus);
            } else {
                if($augKycStatus=="approved") {
                    return View::make($redirectURL, $data);
                } else {
                    if($augKycStatus==null || $augKycStatus=="") {
                        return redirect('/augmont/kyc')->with('status', $data);
                    }
                }
            }
          }
        }
      } else {
        return redirect()->intended('/augmont/augmontReq');
      }
      return $response;
    }
}