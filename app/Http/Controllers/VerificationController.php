<?php

namespace App\Http\Controllers;

use App\Models\SMS;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function otp_verification(Request $request) {

        $sms = \App\Models\SMS::where([
                'contact' => $request->contact,
                'sms_otp' => $request->mob_otp,
                'sms_verification' => 'PENDING',
                'sms_type' => 'OTP'
            ])->orderBy("created_at", "desc")->first();

        $email = \App\Models\Emails::where([
            'emailAddress' => $request->email,
            'email_otp' => $request->email_otp,
            'email_verification' => 'PENDING',
            'email_type' => 'OTP'
        ])->orderBy("created_at", "desc")->first();

        if(isset($sms)) {
            $sms->sms_verification = "VERIFIED";
            $sms->save();
            if(isset($email)) {
                $email->email_verification = "VERIFIED";
                $email->save();
                return "SUCCESS";
            } else {
                return "FAILURE";
            }
        } else {
            return "FAILURE";
        }
    }

    public function otp_verification_api($contact_no, $motp, $login_id, $eotp) {
        $sms = SMS::where([
                'contact' => $contact_no,
                'sms_otp' => $motp,
                'sms_verification' => 'PENDING',
                'sms_type' => 'OTPAPI'
            ])->orderBy("created_at", "desc")->first();
        $email = \App\Models\Emails::where([
                'emailAddress' => $login_id,
                'email_otp' => $eotp,
                'email_verification' => 'PENDING',
                'email_type' => 'OTPAPI'
            ])->orderBy("created_at", "desc")->first();
        if($sms) {
            $sms->sms_verification = "VERIFIED";
            $sms->save();
            if($email) {
                $email->email_verification = "VERIFIED";
                $email->save();
                return "SUCCESS";
            } else {
                return "FAILURE";
            }
        } else {
            return "FAILURE";
        }
    }

    public function otp_email_verification_api(Request $request) {
        $email = \App\Models\Emails::where([
            'emailAddress' => $request->login_id,
            'email_otp' => $request->eotp,
            'email_verification' => 'PENDING',
            'email_type' => 'OTPAPI'
        ])->orderBy("created_at", "desc")->first();

        if(isset($email)) {
            $email->email_verification = "VERIFIED";
            $email->save();
            return "SUCCESS";
        } else {
            return "FAILURE";
        }
    }

    public function fpotp_verification(Request $request) {

        $sms = sms::where([
                'contact' => $request->contact,
                'sms_type' => 'FPOTP',
                'sms_otp' => $request->motp,
                'sms_verification' => 'PENDING'
            ])->orderBy("created_at", "desc")->first();

        $email = \App\Models\Emails::where([
            'emailAddress' => $request->emailAddress,
            'email_otp' => $request->eotp,
            'email_verification' => 'PENDING',
            'email_type' => 'FPOTP'
        ])->orderBy("created_at", "desc")->first();

        if(isset($sms)) {
            $sms->sms_verification = "VERIFIED";
            $sms->save();
            if(isset($email)) {
                $email->email_verification = "VERIFIED";
                $email->save();
                return "SUCCESS";
            } else {
                return "FAILURE1";
            }
        } else {
            return "FAILURE123";
        }
    }

    public function fpotp_verification_api(Request $request, $emailtype) {
        $email = \App\Models\Emails::where([
            'emailAddress' => $request->forgot_email,
            'email_otp' => $request->forgot_otp,
            'email_verification' => 'PENDING',
            'email_type' => $emailtype
        ])->orderBy("created_at", "desc")->first();

        if(isset($email)) {
            $email->email_verification = "VERIFIED";
            $email->save();
            return "SUCCESS";
        } else {
            return "FAILURE";
        }
    }

}
