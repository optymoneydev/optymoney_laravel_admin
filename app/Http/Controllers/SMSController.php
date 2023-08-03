<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SMS;
use Illuminate\Support\Facades\Http;

class SMSController extends Controller {
    
    public function send_otp_sms($mobno, $smstype) {

        $otpnew = mt_rand(10000, 99999);
        $_SESSION['otp'] = $otpnew; 
        $otp_msg = 'Your OTP to Register on OPTYMONEY is "'.$otpnew.'" The OTP will be valid for next 15 mins';
        $url = "http://alerts.kaleyra.com/api/v4/?method=sms&api_key=".env('SMSAPIKEY')."&message=".$otp_msg."&sender=DEVMAN&to=".$mobno."&entity_id=&template_id=1307160472687884955";
        
        $response = Http::get($url);
        // $sms_res = json_decode($response->body());
        
        $sms = new sms;
        $sms->contact = $mobno;
        $sms->sms_type = $smstype;
        $sms->sms_content = $otp_msg;
        $sms->sms_status = "SUCCESS";
        $sms->sms_otp = $otpnew;
        $sms->sms_verification = "PENDING";
        if($sms->save()) {
          return "SUCCESS";
        } else {
          return "FAILURE";
        }

    }

    public function sms_requestOTPForVerification($mobno, $smstype) {

      $otpnew = mt_rand(10000, 99999);
      $_SESSION['otp'] = $otpnew; 
      $otp_msg = 'Your OTP to activate family member on your OPTYMONEY profile is "'.$otpnew.'" The OTP will be valid for next 15 mins';
      $url = "http://alerts.kaleyra.com/api/v4/?method=sms&api_key=".env('SMSAPIKEY')."&message=".$otp_msg."&sender=OPTMNY&to=".$mobno."&entity_id=&template_id=1307168001970817175";
      $response = Http::get($url);
      $sms_res = json_decode($response->body());
      $sms = new SMS();
      $sms->contact = $mobno;
      $sms->sms_type = $smstype;
      $sms->sms_content = $otp_msg;
      $sms->sms_status = "SUCCESS";
      $sms->sms_otp = $otpnew;
      $sms->sms_verification = "PENDING";
      $sms->save();

      if($sms_res->status=='A404') {
        $data = [
          'status_code' => $sms_res->status,
          'message' => "SMS sending failed, Please try after sometime.",
          'otp' => $otpnew
        ];
      } else {
        // $sms = new SMS();
        // $sms->contact = $mobno;
        // $sms->sms_type = $smstype;
        // $sms->sms_content = $otp_msg;
        // $sms->sms_status = "SUCCESS";
        // $sms->sms_otp = $otpnew;
        // $sms->sms_verification = "PENDING";
        if($sms->save()) {
          $data = [
            'status_code' => $sms_res,
            'message' => "SMS sent.",
            'otp' => $otpnew,
            'url' => $url
          ];
        } else {
          $data = [
            'status_code' => $sms_res.status,
            'message' => "SMS sending failed, Please try after sometime.",
            'otp' => $otpnew,
            'url' => $url
          ];
        }
      }
      return $data;

  }
    
}
