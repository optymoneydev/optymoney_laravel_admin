<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Bfsi_user;
use App\Models\Bfsi_users_detail;
use App\Models\Mf_cams;
use App\Models\Mf_karvy;
use App\Models\KycStatus;
use App\Models\FamilyAccounts;
use Validator;
use File;
use Session;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Augmont\User\UserAugmontController;

class UserAuthController extends Controller
{
    /**
     * Create a new UserAuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:userapi', ['except' => ['tokenCheckgold', 'login', 'register', 'simple_signup', 'validateRegistrationOTP', 'forgot_verifyOTP', 'forgot_sendOTP', 'forgot_submitPassword', 'contact', 'requestOTPAPI', 'verifyOTPAPI', 'createAccountAPI', 'validatePanAadhaarAPI', 'finishSignupAPI']]);
    }

    public function simple_signup(Request $request) {
        if($request->eotp) {
            $result = (new VerificationController)->otp_verification_api($request->contact_no, $request->motp, $request->login_id, $request->eotp);
            if($result=="SUCCESS") {
                $data = $request->all();
                $uid = $this->createVerifiedAccountNew($data, request()->headers->get('origin'))->pk_user_id;
                $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $uid)->update([
                    'password' => md5($data['password']),
                    'aug_pswd' => Hash::make($data['password']),
                    'steps' => 3
                ]);
                $userStatus = Bfsi_users_detail::create([
                    'fr_user_id' => $uid,
                    'cust_name' => $data['fname']
                ]);
                if($uid>0){
                    $response = [
                        'status_code' => '201',
                        'uid' => $uid,
                        'message' => 'User profile created successfully',
                        'steps' => 2
                    ];
                    return $this->login($request);
                } else {
                    $response = [
                    'status_code' => '302',
                    'message' => 'User profile creation Failed',
                    ];
                }
            } else {
                $response = [
                    'status_code' => '417',
                    'message' => 'OTP Verification Failed',
                ];
            }
        } else {
            $user = \App\Models\Bfsi_user::where([
                'login_id' => $request->login_id
            ])->first();
            if($user) {
                $result = "true";
                $response = [
                    'status_code' => 422,
                    'message' => 'Already Verified',
                    'steps' => $user->steps,
                    'uid' => $user->pk_user_id
                ];
            } else {
                $res1 = (new SMSController)->send_otp_sms($request->contact_no, "OTPAPI");
                if($res1=="SUCCESS") {
                    $res2 = (new EmailController)->send_otp_email($request->login_id, "OTPAPI");
                    if($res2=="SUCCESS") {
                        $response = [
                            'status_code' => 200,
                            'message' => 'OTP sent to your mobile number and email address, if not recived mail kindly check spam.',
                            'email_otp_status' => 'email otp sent',
                            'mobile_otp_status' => 'mobile otp sent'
                        ];
                    } else {
                        $response = [
                            'status_code' => 417,
                            'message' => 'Failed sending OTP. Try again...',
                            'resEmail' => $res2
                        ];  
                    }
                } else {
                    $response = [
                    'status_code' => 417,
                    'message' => 'Failed sending OTP. Try again...',
                    ];  
                }
                // $res2 = (new EmailController)->send_otp_email($request->login_id, "OTPAPI");
                // if($res2=="SUCCESS") {
                //     $response = [
                //         'status_code' => 200,
                //         'message' => 'OTP sent to your email address, if not recived mail kindly check spam.',
                //         'email_otp_status' => 'email otp sent',
                //     ];
                // } else {
                //     $response = [
                //         'status_code' => 417,
                //         'message' => 'OTP sending Failed. Try again...',
                //         'resEmail' => $res2
                //     ];  
                // }
            }
        }
        return $response;
    }

    public function requestOTPAPI(Request $request) {
        $user = \App\Models\Bfsi_user::where([
            'login_id' => $request->emailAddress
        ])->first();
        if($user) {
            $result = "true";
            $response = [
                'status_code' => 422,
                'message' => 'Already Verified',
                'steps' => $user->steps,
                'uid' => $user->pk_user_id
            ];
        } else {
            $res1 = (new SMSController)->send_otp_sms($request->contact, "OTPAPI");
            if($res1=="SUCCESS") {
                $res2 = (new EmailController)->send_otp_email($request->emailAddress, "OTPAPI");
                if($res2=="SUCCESS") {
                    $response = [
                        'status_code' => 200,
                        'message' => 'OTP sent to your mobile number and email address',
                        'email_otp_status' => 'email otp sent',
                        'mobile_otp_status' => 'mobile otp sent'
                    ];
                } else {
                    $response = [
                        'status_code' => 417,
                        'message' => 'Failed sending OTP. Try again...',
                        'resEmail' => $res2
                    ];  
                }
            } else {
            $response = [
              'status_code' => 417,
              'message' => 'Failed sending OTP. Try again...',
            ];  
          }
        }
        return $response;
    }

    public function verifyOTPAPI(Request $request) {
        $result = (new VerificationController)->otp_verification_api($request);
        if($result=="SUCCESS") {
          $data = $request->all();
          $uid = $this->createVerifiedAccount($data, request()->headers->get('origin'))->pk_user_id;
          if($uid>0){
            $response = [
              'status_code' => '201',
              'uid' => $uid,
              'message' => 'User profile created successfully',
              'steps' => 2
            ];
          } else {
            $response = [
              'status_code' => '302',
              'message' => 'User profile creation Failed',
            ];
          }
        } else {
          $response = [
            'status_code' => '417',
            'message' => 'OTP Verification Failed',
          ];
        }
        return $response;
    }

    public function createVerifiedAccount($data, $source) {
        return Bfsi_user::create([
          'login_id' => $data['email'],
          'communication_email' => "Permanent",
          'user_status' => "Active",
          'signup_ip' => getenv('REMOTE_ADDR'),
          'signup_date' => \Carbon\Carbon::now()->timestamp,
          'contact' => $data['contact'],
          'steps' => '2',
          'created_from' => $source
        ]);
    }

    public function createVerifiedAccountNew($data, $source) {
        return Bfsi_user::create([
            'login_id' => $data['login_id'],
            'communication_email' => "Permanent",
            'user_status' => "Active",
            'signup_ip' => getenv('REMOTE_ADDR'),
            'signup_date' => \Carbon\Carbon::now()->timestamp,
            'contact' => "",
            'steps' => '2',
            'created_from' => $source,
            'password' => md5($data['password']),
            'aug_pswd' => Hash::make($data['password']),
        ]);
    }

    public function createAccountAPI(Request $request) {  
        $data = $request->all();
        $user = \App\Models\Bfsi_user::where([
          'pk_user_id' => $data['uid'],
        ])->first();
        if($user) {
          $up_stat = $this->createUserProfile($data);
          if($up_stat) {
            $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $data['uid'])->update([
              'password' => md5($data['password']),
              'aug_pswd' => Hash::make($data['password']),
              'steps' => 3
            ]);
            if($userStatus>0) {
              $response = [
                'status_code' => '201',
                'message' => 'Account created Successfully',
                'userStatus' => $userStatus,
                'steps' => 3
              ];
            } else {
              $response = [
                'status_code' => '417',
                'message' => 'Account created Failed Try again',
              ];
            }
          } else {
            $response = [
              'status_code' => '417',
              'message' => 'User Account not accessible. Please try after sometime'
            ];
          }
        } else {
          $response = [
            'status_code' => '417',
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

    public function validatePanAadhaarAPI(Request $request) {  
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
              'status_code' => '201',
              'uid' => $data['uid'],
              'message' => 'User profile updated successfully',
              'upstatus' => $upstatus,
              'steps' => 4
            ];
          } else {
            $response = [
              'status_code' => '417',
              'message' => 'Account Update Failed Try again',
            ];
          }
        } else {
          $response = [
            'status_code' => '417',
            'message' => 'User Account not accessible. Please try after sometime'
          ];
        }
      } else {

      }
      
      return $response;
    }

    public function finishSignupAPI(Request $request) {
      $data = $request->all();
      // $result = (new NsdlController)->getPasscodeEncyrt();
      $upstatus = Bfsi_users_detail::where('fr_user_id', $data['uid'])->update([
        'address1' => $data['address1'], 
        'address2' => $data['address2'], 
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
            'status_code' => '201',
            'uid' => $data['uid'],
            'message' => 'User profile updated successfully',
            'upstatus' => $upstatus,
            'result' => $userStatus,
            'userdata' => $userData
          ];
        } else {
          $response = [
            'status_code' => '417',
            'uid' => $data['uid'],
            'message' => 'User profile update failed',
            'upstatus' => $upstatus,
            'result' => $userStatus,
            'userdata' => $userData
          ];
        }
      } else {
        $response = [
          'status_code' => '417',
          'message' => 'User Account not accessible. Please try after sometime'
        ];
      }
      return $response;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'login_id' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('userapi')->attempt($validator->validated())) {
            
            $user = Bfsi_user::where([
                'login_id' => $request->login_id,
                'password' => md5($request->password)
            ])->first();
            if(isset($user)) {
                if($user->password == md5($request->password)) { // If their password is still MD5
                    $user->password = Hash::make($request->password); // Convert to new format
                    $user->save();
                    if (! $token = auth()->guard('userapi')->attempt($validator->validated())) {
                        return response()->json(['error' => 'Unauthorized'], 401);
                    }
                } else {
                    return response()->json(['error' => 'Unauthorized'], 401);    
                }
            } else {
                $user = Bfsi_user::where([
                    'login_id' => $request->login_id,
                    'password' => Hash::make($request->password)
                ])->first();
                if($user) {

                } else {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }
        } else {
            return $this->createNewToken($token);
            // return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
        
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validator->fails()){
            $user  = User::where([['email','=',$request->email],['verificationStatus','=',"success"]])->first();
            $res2 = (new EmailController)->send_otp_email($request->email, "OTP");
            if($res2=="SUCCESS") {
                $response = [
                    'statusCode' => '200',
                    'message' => 'OTP sent to your email address',
                    'email_otp_status' => 'email otp sent',
                ];
            } else {
                $response = [
                    'statusCode' => '417',
                    'message' => 'Failed sending OTP. Try again...',
                ];  
            }

            return response()->json($response);
            if($user==null) {
                $arr = $validator->errors()->messages();
                $arr['verification'] = "Failed";
                return response()->json($arr, 400);    
            } else {
                return response()->json($validator->errors()->toJson(), 400);
            }
        } else {
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));
    
            $res2 = (new EmailController)->send_otp_email($request->email, "OTP");
            if($res2=="SUCCESS") {
                $response = [
                    'statusCode' => '200',
                    'message' => 'OTP sent to your email address',
                    'email_otp_status' => 'email otp sent',
                ];
            } else {
                $response = [
                    'statusCode' => '417',
                    'message' => 'Failed sending OTP. Try again...',
                ];  
            }
        }

        return response()->json($response);
    }

    /**
     * Validate Registration OTP.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    
     public function validateRegistrationOTP(Request $request) {
        $result = (new VerificationController)->otp_verification($request);
        if($result=="SUCCESS") {
            $upstatus = User::where('email', $request->email)->update([
                'verificationStatus' => "Verified"
            ]);
            $user  = User::where([['email','=',$request->email]])->first();
            $response = [
                'statusCode' => '201',
                'message' => 'OTP Verified Successfully',
                'user' => $user
            ];
        } else {
            $response = [
                'statusCode' => '417',
                'message' => 'OTP Verification Failed',
            ];
        }
        return response()->json($response);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::guard('userapi')->logout();

        return response()->json([
            'status_code' => 201,
            'message' => 'Logout Successfully'
        ], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * auth check a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenCheck(Request $request) {
        // return "Sai";
        return response()->json([ 'valid' => auth('userapi')->check() ]);
    }

    public function tokenCheckgold(Request $request) {
        // return "Sai";
        return response()->json([ 'valid' => auth('userapi')->check() ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $id = auth('userapi')->user()->pk_user_id;
        $userData = Bfsi_users_detail::where(['fr_user_id' => $id])->get()->first();
        $path = public_path('uploads').'/users/'.$id.'/profile/'.$userData->signature;
        $base64 = "data:image/png;base64,".base64_encode(file_get_contents($path));
        $data = [
            "statusCode" => 201,
            "userdata" => auth('userapi')->user(),
            "profileDate" => $userData,
            "path" => $base64
        ];
        return response()->json($data);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $id = auth('userapi')->user()->pk_user_id;
        $userData = Bfsi_users_detail::where(['fr_user_id' => $id])->get()->first();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('userapi')->factory()->getTTL() * 60,
            'user' => auth('userapi')->user(),
            'profileDate' => $userData,
            'status_code' => 201
        ]);
    }

    /**
     * Forgot password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request) {
        $user  = User::where([['email','=',$request->email]])->first();
        $res2 = (new EmailController)->send_fp_otp_email($request->email, "OTP", $user->firstname);
        if($res2=="SUCCESS") {
            $response = [
                'statusCode' => '200',
                'message' => 'OTP sent to your email address',
                'email_otp_status' => $res2,
                'uid' => $user->id
            ];
        } else {
            $response = [
                'statusCode' => '417',
                'message' => 'Failed sending OTP. Try again...',
                'email_otp_status' => $res2
            ];  
        }

        return response()->json($response);
        
    }

    /**
     * Validate Forgot password OTP.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function validateFPOTP(Request $request) {
        $result = (new VerificationController)->otp_verification($request);
        if($result=="SUCCESS") {
            $user  = User::where([['email','=',$request->email]])->first();
            $response = [
                'statusCode' => '201',
                'message' => 'OTP Verified Successfully',
                'user' => $user
            ];
        } else {
            $response = [
                'statusCode' => '417',
                'message' => 'OTP Verification Failed',
            ];
        }
        return response()->json($response);
    }

    /**
     * Update password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
     public function updatePassword(Request $request) {

        $upstatus = User::where('id', $request->id)->update([
            'password' => Hash::make($request->password),
            'aug_pswd' => Hash::make($request->password)
        ]);
        if($upstatus) {
            $response = [
              'statusCode' => '201',
              'message' => 'Password updated successfully'
            ];
        } else {
            $response = [
              'statusCode' => '404',
              'message' => 'Password update failed'
            ];
        }
        return response()->json($response);
    }

    public function getUserDetails($id) {
        $user = Bfsi_users_detail::where('fr_user_id', $id)->first();
        return $user;
    }

    public function getDocs(Request $request) {
        // $userDocs = Bfsi_itr::where('fr_user_id', $request->id)->get();
        // $custDocs = Tbl_uploads::where('user_id', $request->id)->get();
        // $goldDocs = KycStatus::where('fr_user_id', $request->id)->get();
        // $fileUploadDocs = FileUploads::where('fr_user_id', $request->id)->get();
        
        // $fetch_portfolio["itr"] = $userDocs;
		// $fetch_portfolio["docs"] = $custDocs;
		// $fetch_portfolio["goldupload"] = $goldDocs;
		// $fetch_portfolio["fileuploads"] = $fileUploadDocs;
		return "User Controller";
    }

    public function saveBasicInfoAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
                'aadhaar_no' => $request->aadhaar_no,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'city' => $request->cityName,
                'contact_no' => $request->contact_no,
                'cust_name' => $request->cust_name,
                'dob' => $request->dob,
                // 'example-textarea-input' => $request->,
                'father_name' => $request->father_name,
                'email' => $request->login_id,
                'nominee_dob' => $request->nominee_dob,
                'nominee_name' => $request->nominee_name,
                'pan_number' => $request->pan_number,
                'pincode' => $request->pincode,
                'r_of_nominee_w_app' => $request->r_of_nominee_w_app,
                'sex' => $request->sex,
                'state' => $request->stateName,
                'augcity' => $request->city,
                'augstate' => $request->state,
                'clientHolding' => $request->clientHolding,
                'occupationCode' => $request->occupationCode,
                'taxStatus' => $request->taxStatus,
                'sourceOfWealth' => $request->sourceOfWealth,
                'bsestatecode' => $request->bsestatecode
            ]);
            if($upstatus) {
                $data = [
                    "statusCode" => 201,
                    "data" => $upstatus
                ];
            } else {
                $data = [
                    "statusCode" => 401,
                    "message" => "User Details updation failed, please try again"
                ];
            }
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function uploadProfileImgAPI (Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;

            $allowedfileExtension=['jpg','png'];

            $path = public_path('uploads').'/users/'.$id;
            
            $newDate = date("Y-m-d", strtotime($request->panDOB));
            $file = request('profile');

            if(!File::exists($path)) {
                $profile_path = $path.'/profile';
                File::makeDirectory($path, 0777, true, true);
                File::makeDirectory($profile_path, 0777, true, true);
            } else {
                $profile_path = $path.'/profile';
                File::makeDirectory($profile_path, 0777, true, true);
            }

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            if($check) {
                $fileName = $id."_".time().'.'.$extension;  
                $file_upload_status = $file->move($profile_path, $fileName);
            }
            
            if($file_upload_status!="") {
                $uploadStat = $userData = Bfsi_user::where('pk_user_id', $id)->update([
                    'profile_image' => $fileName
                ]);
                // $res2 = (new EmailController)->send_itrv_status($userData, "KYC_UPLOAD_AUGMONT");
                if($uploadStat == 1) {
                    $data = [
                        'status_code' => 201,
                        'message' => 'Profile pic uploaded successfully.'
                    ];
                } else {
                    $data = [
                        'status_code' => 424,
                        'message' => 'Failed to upload the profile pic. Please try again.'
                    ];
                }
            } else {
                $data = [
                    'status_code' => 424,
                    'message' => 'Failed to upload the profile pic. Please try again.'
                ];
            }
            return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function checkPAN(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userDetails = Bfsi_users_detail::where('fr_user_id', $id)->get()->first();
            if($userDetails->pan_number == $request->family_pan_number) {
                $data = [
                    'status_code' => 401,
                    'message' => "You cannot add your own PAN number"
                ];
            } else {
                $fa = FamilyAccounts::where([
                    'pan' => $request->family_pan_number,
                    'status' => "active"
                    ])->get()->first();
                if(!$fa) {
                    $fa = new FamilyAccounts();
                    $fa->fr_user_id = $id;
                    $fa->pan = $request->family_pan_number;
                    $fa->save();
                    $mfcams = Mf_cams::where('pan', $request->family_pan_number)->get()->count();
                    $mfkarvy = Mf_karvy::where('pan1', $request->family_pan_number)->get()->count();
                    if($mfcams) {
                        $data = [
                            'status_code' => 201,
                            'cams' => $mfcams,
                            'karvy' => $mfkarvy,
                            'message' => "Data Available for the PAN number entered"
                        ];
                    } else {
                        
                        $data = [
                            'status_code' => 424,
                            'message' => 'No data available for the PAN number.'
                        ];
                    }
                } else {
                    $data = [
                        'status_code' => 401,
                        'message' => "Family PAN number already activated"
                    ];
                }
            }
            
            return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function requestOTPForVerification(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $smsStatus = (new SMSController)->sms_requestOTPForVerification($request->family_pan_mobile, "family account verification");
            $fa = FamilyAccounts::where('pan', $request->family_pan_number)->get()->first();
            $fa->family_pan_mobile = $request->family_pan_mobile;
            $fa->fullname = $request->fullname;
            $fa->family_pan_mobile_otp = $smsStatus['otp'];
            $fa->save();
            if($smsStatus) {
                $data = [
                    'status_code' => 201,
                    'message' => "SMS was sent to linked phone number.",
                    'sms_status' => $smsStatus
                ];
            } else {
                
                $data = [
                    'status_code' => 424,
                    'message' => 'OTP sendin failed.',
                    'sms_status' => $smsStatus
                ];
            }
            return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function activateFamilyMember(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $fa = FamilyAccounts::where([
                'pan' => $request->family_pan_number,
                'family_pan_mobile' => $request->family_pan_mobile,
                'family_pan_mobile_otp' => $request->family_pan_mobile_otp
                ])->get()->first();
            if($fa) {
                $fa->status = "active";
                $fastat = $fa->save();
                if($fastat) {
                    $kycStatus = (new NsdlController)->getPasscodeEncyrt($request->family_pan_number);
                    $kycDBUpdate = (new NsdlController)->nsdlResponseAPIUpdate($kycStatus, $id);

                    $data = [
                        'status_code' => 201,
                        'message' => "Family member activated.",
                        'kycStatus' => $kycStatus,
                        'kycupdate' => $kycDBUpdate
                    ];
                } else {
                    $data = [
                        'status_code' => 201,
                        'message' => "Activation failed, Please try again."
                    ];
                }
            } else {
                $data = [
                    'status_code' => 401,
                    'message' => "Wrong OTP, Please try again."
                ];
            }
            return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function familyListAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;
            $familyList = FamilyAccounts::leftJoin('kyc_status', 'kyc_status.panNumber', '=', 'family_accounts.pan')
            ->where('family_accounts.fr_user_id', $id)
            ->where('family_accounts.status', 'active')
            ->get(['family_accounts.fr_user_id', 'family_accounts.pan', 'family_accounts.fullname', 'kyc_status.nsdl_response']);
            $data = [
                "statusCode" => 201,
                "data" => $familyList
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

    public function forgot_sendOTP(Request $request) {
        // $userDetails = Bfsi_user::where('login_id', $request->forgot_email)->get()->first();
        $userDetails = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->where('bfsi_user.login_id', $request->forgot_email)
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name'])->first();
        if($userDetails) {
            $res2 = (new EmailController)->send_fp_otp_email($request->forgot_email, "FPOTPAPI", $userDetails->cust_name);
            if($res2=="SUCCESS") {
                $data = [
                    'status_code' => 201,
                    'message' => 'OTP sent to your registered email address',
                    'email_otp_status' => $res2,
                    'uid' => $userDetails->pk_user_id
                ];
            } else {
                $data = [
                    'status_code' => 417,
                    'message' => 'Failed sending OTP. Try again...',
                    'sts' => $res2
                ];  
            }
        } else {
            $data = [
                'status_code' => 401,
                'message' => "Please enter registered email address"
            ];
        }
        
        return $data;
    }

    public function forgot_verifyOTP(Request $request) {
        $result = (new VerificationController)->fpotp_verification_api($request, "FPOTPAPI");
        if($result=="SUCCESS") {
            $data = [
                'status_code' => 201,
                'message' => "OTP Verified.",
                'ver_status' => $result
            ];
        } else {
            $data = [
                'status_code' => 424,
                'message' => 'OTP verification failed.',
                'ver_status' => $result
            ];
        }
        return $data;
    }

    public function forgot_submitPassword(Request $request) {
        $upstatus = Bfsi_user::where('login_id', $request->forgot_email)->update([
            'password' => bcrypt($request->forgot_password),
            'aug_pswd' => Hash::make($request->forgot_password)
        ]);
        if($upstatus) {
            $response = [
              'status_code' => '201',
              'message' => 'Password updated successfully'
            ];
        } else {
            $response = [
              'status_code' => '404',
              'message' => 'Password update failed'
            ];
        }
        return response()->json($response);
    }

    public function uploadSignAndCheque (Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;

            $allowedfileExtension=['jpg','png'];

            $path = public_path('uploads').'/users/'.$id;
            
            $newDate = date("Y-m-d", strtotime($request->panDOB));
            $signature = request('signature');
            $cancelledCheque = request('cancelledCheque');

            if(!File::exists($path)) {
                $profile_path = $path.'/profile';
                File::makeDirectory($path, 0777, true, true);
                File::makeDirectory($profile_path, 0777, true, true);
            } else {
                $profile_path = $path.'/profile';
                File::makeDirectory($profile_path, 0777, true, true);
            }

            $signaturefilename = $signature->getClientOriginalName();
            $signatureextension = $signature->getClientOriginalExtension();
            $check=in_array($signatureextension,$allowedfileExtension);
            if($check) {
                $signaturefileName = $id."_sign_".time().'.'.$signatureextension;  
                $file_upload_status = $signature->move($profile_path, $signaturefileName);
            }
            $cancelledChequefilename = $cancelledCheque->getClientOriginalName();
            $cancelledChequeextension = $cancelledCheque->getClientOriginalExtension();
            $check=in_array($cancelledChequeextension,$allowedfileExtension);
            if($check) {
                $cancelledChequefileName = $id."_cheque_".time().'.'.$cancelledChequeextension;  
                $file_upload_status = $cancelledCheque->move($profile_path, $cancelledChequefileName);
            }
            
            if($file_upload_status!="") {
                $uploadStat = $userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
                    'signature' => $signaturefileName,
                    'cancelledcheque' => $cancelledChequefileName
                ]);
                // $res2 = (new EmailController)->send_itrv_status($userData, "KYC_UPLOAD_AUGMONT");
                if($uploadStat == 1) {
                    $data = [
                        'status_code' => 201,
                        'message' => 'Signature & Cancelled Cheque uploaded successfully.'
                    ];
                } else {
                    $data = [
                        'status_code' => 424,
                        'message' => 'Failed to upload the files. Please try again.'
                    ];
                }
            } else {
                $data = [
                    'status_code' => 424,
                    'message' => 'Failed to upload the files. Please try again.'
                ];
            }
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
