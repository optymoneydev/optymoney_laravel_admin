<?php

namespace App\Http\Controllers;

use App\Models\Emails;
use Mail;
use Illuminate\Http\Request;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
Use \App\Models\EmailFormat;
Use App\Mail\OptyEmail;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Augmont\InvoiceAugmontController;

class EmailController extends Controller
{
    public function send_otp_email($email , $emailtype) {
      try {
        $otpnew = mt_rand(10000, 99999);
        $_SESSION['otp'] = $otpnew; 
        $otp_msg = 'Your OTP to Register on OPTYMONEY is '.$otpnew.' The OTP will be valid for next 15 mins';
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $email;
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $email;
        $mailInfo->subject = "Optymoney - OTP Verification";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.otp";
        $mailInfo->otp = $otpnew;
        $mailInfo->attachment = "no";
        $mailInfo->files = "";
        $mailInfo->metalType = "";
        $mailInfo->amount = "";
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        $emailStat = Mail::to($email)->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info(json_encode(['email' => $email, 'function' => "send_otp_email", 'emailstatus' => $emailStat]));
        $emails = new Emails;
        $emails->emailAddress = $email;
        $emails->email_type = $emailtype;
        $emails->email_content = $otp_msg;
        $emails->email_status = "SUCCESS";
        $emails->email_otp = $otpnew;
        $emails->email_verification = "PENDING";
        if($emails->save()) {
          return "SUCCESS";
        } else {
          return "FAILURE";
        }
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $email, 'function' => "send_otp_email", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_fp_otp_email($email , $emailtype, $cust_name) {
      try {
        $otpnew = mt_rand(10000, 99999);
        $_SESSION['otp'] = $otpnew; 
        $otp_msg = 'Your OTP to Register on OPTYMONEY is '.$otpnew.' The OTP will be valid for next 15 mins';

        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $cust_name;
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $email;
        $mailInfo->subject = "Optymoney - OTP Verification";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.otp";
        $mailInfo->otp = $otpnew;
        $mailInfo->attachment = "no";
        $mailInfo->files = "";
        $mailInfo->metalType = "";
        $mailInfo->amount = "";
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        $emailStat = Mail::to($email)->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info("send_fp_otp_email: ".json_encode(['user' => $email, 'result' => $emailStat]));
        $emails = new Emails;
        $emails->emailAddress = $email;
        $emails->email_type = $emailtype;
        $emails->email_content = $otp_msg;
        $emails->email_status = "SUCCESS";
        $emails->email_otp = $otpnew;
        $emails->email_verification = "PENDING";
        if($emails->save()) {
          return "SUCCESS";
        } else {
          return "FAILURE";
        }
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $email, 'function' => "send_fp_otp_email", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_user_creation_email($userdata) {
      try {
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $userdata['cust_name'];
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $userdata['login_id'];
        $mailInfo->subject = "Optymoney - Account Creation";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.account-creation";
        $mailInfo->attachment = "no";
        $mailInfo->files = "";
        $mailInfo->metalType = "";
        $mailInfo->amount = "";
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        $emailStat = Mail::to($userdata['login_id'])
          ->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info("send_user_creation_email: ".json_encode(['user' => $userdata, 'result' => $emailStat]));
        return $this->emailReport($userdata['login_id'], "Account Activation", "", "SUCCESS", 0, "");
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $userdata, 'function' => "send_user_creation_email", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_purchase_success($augOrderRes) {
      try {
        $content = (new InvoiceAugmontController)->getInvoiceData($augOrderRes);

        $dataTemp = $content->result->data;
        $pdf = (new InvoiceAugmontController)->generatePDF($content);
        
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $dataTemp->userInfo->name;
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $dataTemp->userInfo->email;
        $mailInfo->subject = "Optymoney - Invoice : ".$dataTemp->invoiceNumber;
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.purchase-success";
        $mailInfo->attachment = "yes";
        $mailInfo->files = $pdf;
        $mailInfo->filename = "OPTY_".$dataTemp->invoiceNumber."_invoice";
        $mailInfo->metalType = $dataTemp->metalType;
        $mailInfo->amount = $dataTemp->netAmount;
        $emailData = new OptyEmail($mailInfo);
        $emailStat = Mail::to($dataTemp->userInfo->email)->send($emailData);
        \Log::channel('accounts')->info("send_purchase_success: ".json_encode(['user' => $dataTemp->userInfo->email, 'result' => $emailStat]));
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $dataTemp->userInfo->email, 'function' => "send_subscription_success", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_subscription_success($email, $custname, $message, $subscriptionData) {
      try {
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $email;
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $email;
        $mailInfo->subject = "Subscription Created for OPTYMONEY";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.subscription-success";
        $mailInfo->subscriptionPlan = $subscriptionData->notes->name;
        $mailInfo->subscriptionDescription = $subscriptionData->notes->description;
        $mailInfo->attachment = "no";
        $mailInfo->message = $message;
        $mailInfo->subscriptionId = $subscriptionData->id;
        $mailInfo->metalType = $subscriptionData->notes->metalType;
        $mailInfo->amount = $subscriptionData->notes->amount;
        $mailInfo->customerName = $custname;
        // dd($mailInfo);
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        $emailStat = Mail::to($email)->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info("send_subscription_success: ".json_encode(['user' => $email, 'result' => $emailStat]));
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $email, 'function' => "send_subscription_success", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_subscription_failed($email, $custname, $message, $planData, $subscriptionId) {
      try {
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $email;
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $email;
        $mailInfo->subject = "Subscription Payment Failed for OPTYMONEY";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.subscription-pending";
        $mailInfo->subscriptionPlan = $planData->name;
        $mailInfo->subscriptionDescription = $planData->description;
        $mailInfo->attachment = "no";
        $mailInfo->message = $message;
        $mailInfo->subscriptionId = $subscriptionId;
        // $mailInfo->metalType = $subscriptionData->notes->metalType;
        $mailInfo->amount = $planData->amount;
        $mailInfo->customerName = $custname;
        // dd($mailInfo);
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

        $emailStat = Mail::to($email)->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info("send_subscription_failed: ".json_encode(['user' => $email, 'result' => $emailStat]));
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
        return $e->getMessage();
      }
  }

  public function send_subscription_halted($email, $custname, $message, $planData, $subscriptionId) {
    try {
      $mailInfo = new \stdClass();
      $mailInfo->recieverName = $email;
      $mailInfo->sender = "Optymoney";
      $mailInfo->senderCompany = "Optymoney";
      $mailInfo->to = $email;
      $mailInfo->subject = "Subscription multiple Payments Failed for OPTYMONEY";
      $mailInfo->name = "Optymoney";
      $mailInfo->from = "no-reply@optymoney.com";
      $mailInfo->template = "email-templates.subscription-status";
      $mailInfo->subscriptionPlan = $planData->name;
      $mailInfo->subscriptionDescription = $planData->description;
      $mailInfo->attachment = "no";
      $mailInfo->message = $message;
      $mailInfo->subscriptionId = $subscriptionId;
      // $mailInfo->metalType = $subscriptionData->notes->metalType;
      $mailInfo->amount = $planData->amount;
      $mailInfo->customerName = $custname;
      // dd($mailInfo);
      // $mailInfo->cc = "ci@email.com";
      // $mailInfo->bcc = "jim@email.com";

      $emailStat = Mail::to($email)->send(new OptyEmail($mailInfo));
      \Log::channel('accounts')->info("send_subscription_halted: ".json_encode(['user' => $email, 'result' => $emailStat]));
    } catch (\Exception $e) {
      \Log::channel('itsolution')->info(json_encode(['input' => $email, 'function' => "send_subscription_halted", 'output' => $e]));
      return $e->getMessage();
    }
}
    

    public function send_kyc_upload($userdata) {
      try {
        $mailInfo = new \stdClass();
        $mailInfo->recieverName = $userdata['cust_name'];
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = $userdata['login_id'];
        $mailInfo->subject = "Optymoney - KYC Upload";
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.kyc_upload";
        $mailInfo->attachment = "no";
        $mailInfo->files = "";
        $mailInfo->metalType = "";
        $mailInfo->amount = "";
        // $mailInfo->cc = "ci@email.com";
        // $mailInfo->bcc = "jim@email.com";

      $emailStat = Mail::to($userdata['login_id'])->send(new OptyEmail($mailInfo));
        \Log::channel('accounts')->info("send_kyc_upload: ".json_encode(['user' => $userdata, 'result' => $emailStat]));
        return $this->emailReport($userdata['login_id'], "KYC Document Upload", "", "SUCCESS", 0, "");
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $userdata, 'function' => "send_kyc_upload", 'output' => $e]));
        return $e->getMessage();
      }
    }
  
    public function send_itrv_status($userdata) {
      $mailInfo = new \stdClass();
      $mailInfo->recieverName = $userdata['cust_name'];
      $mailInfo->sender = env('ITRV_SENDER');
      $mailInfo->senderCompany = env('ITRV_SENDERCOMPANY');
      $mailInfo->to = $userdata['login_id'];
      $mailInfo->subject = "Optymoney - KYC Upload";
      $mailInfo->name = env('ITRV_NAME');
      $mailInfo->from = env('ITRV_FROM');
      $mailInfo->template = env('ITRV_TEMPLATE');
      $mailInfo->attachment = "no";
      $mailInfo->files = "";

      Mail::to($userdata['login_id'])
        ->send(new OptyEmail($mailInfo));
      
      return $this->emailReport($userdata['login_id'], "ITRV Uploaded", "", "SUCCESS", 0, "");
    }
  
    public function emailReport($email, $email_type, $email_content, $email_status, $email_otp, $email_verification) {
      try {
        $emails = new Emails;
        $emails->emailAddress = $email;
        $emails->email_type = $email_type;
        $emails->email_content = $email_content;
        $emails->email_status = $email_status;
        $emails->email_otp = $email_otp;
        $emails->email_verification = $email_verification;
        if($emails->save()) {
          \Log::channel('accounts')->info("emailReport: ".json_encode(['user' => $email]));
          return "SUCCESS";
        } else {
          return "FAILURE";
        }
      } catch (\Exception $e) {
        \Log::channel('itsolution')->info(json_encode(['input' => $email, 'function' => "emailReport", 'output' => $e]));
        return $e->getMessage();
      }
    }

    public function send_contact_success($email, $custname, $message) {

      $mailInfo = new \stdClass();
      $mailInfo->recieverName = $email;
      $mailInfo->sender = "Optymoney";
      $mailInfo->senderCompany = "Optymoney";
      $mailInfo->to = $email;
      $mailInfo->subject = "Requested for assistance from OPTYMONEY";
      $mailInfo->name = "Optymoney";
      $mailInfo->from = "no-reply@optymoney.com";
      $mailInfo->template = "email-templates.contactform-success";
      $mailInfo->attachment = "no";
      $mailInfo->message = $message;
      $mailInfo->customerName = $custname;
      // dd($mailInfo);
      // $mailInfo->cc = "ci@email.com";
      // $mailInfo->bcc = "jim@email.com";

      Mail::to($email)->send(new OptyEmail($mailInfo));
  }
    

  public function send_subscription_success_pre($email, $custname, $message) {

    $mailInfo = new \stdClass();
    $mailInfo->recieverName = $email;
    $mailInfo->sender = "Optymoney";
    $mailInfo->senderCompany = "Optymoney";
    $mailInfo->to = $email;
    $mailInfo->subject = "Optymoney Newsletter subscription";
    $mailInfo->name = "Optymoney";
    $mailInfo->from = "no-reply@optymoney.com";
    $mailInfo->template = "email-templates.contactform-success";
    $mailInfo->attachment = "no";
    $mailInfo->message = $message;
    $mailInfo->customerName = $custname;
    // dd($mailInfo);
    // $mailInfo->cc = "ci@email.com";
    // $mailInfo->bcc = "jim@email.com";

    Mail::to($email)->send(new OptyEmail($mailInfo));
  }

  public function send_itr_fileupload($email, $custname, $message) {

    $mailInfo = new \stdClass();
    $mailInfo->recieverName = $email;
    $mailInfo->sender = "Optymoney";
    $mailInfo->senderCompany = "Optymoney";
    $mailInfo->to = $email;
    $mailInfo->subject = "Optymoney ITR Filing Request Submission";
    $mailInfo->name = "Optymoney";
    $mailInfo->from = "no-reply@optymoney.com";
    $mailInfo->template = "email-templates.itr-flieupload-success";
    $mailInfo->attachment = "no";
    $mailInfo->message = $message;
    $mailInfo->customerName = $custname;
    // dd($mailInfo);
    // $mailInfo->cc = "ci@email.com";
    // $mailInfo->bcc = "jim@email.com";
    // return $mailInfo;
    Mail::to($email)->send(new OptyEmail($mailInfo));
  }

  public function send_user_creation_email_from_event($userdata, $pswd) {
    $mailInfo = new \stdClass();
    $mailInfo->recieverName = $userdata['cust_name'];
    $mailInfo->sender = "Optymoney";
    $mailInfo->senderCompany = "Optymoney";
    $mailInfo->to = $userdata['login_id'];
    $mailInfo->subject = "Optymoney - Account Creation";
    $mailInfo->name = "Optymoney";
    $mailInfo->from = "no-reply@optymoney.com";
    $mailInfo->template = "email-templates.account-creation-event";
    $mailInfo->attachment = "no";
    $mailInfo->files = "";
    $mailInfo->metalType = "";
    $mailInfo->amount = "";
    $mailInfo->password = $pswd;
    // $mailInfo->cc = "ci@email.com";
    // $mailInfo->bcc = "jim@email.com";

    Mail::to($userdata['login_id'])
       ->send(new OptyEmail($mailInfo));
    
    return $this->emailReport($userdata['login_id'], "Account Activation", "", "SUCCESS", 0, "");
  }

  public function send_event_reg_status($userdata, $eventData) {
    $mailInfo = new \stdClass();
    $mailInfo->recieverName = $userdata['cust_name'];
    $mailInfo->sender = "Optymoney";
    $mailInfo->senderCompany = "Optymoney";
    $mailInfo->to = $userdata['login_id'];
    $mailInfo->subject = $eventData->event_subject;
    $mailInfo->name = "Optymoney";
    $mailInfo->from = "no-reply@optymoney.com";
    $mailInfo->template = "email-templates.event-reg";
    $mailInfo->attachment = "no";
    $mailInfo->files = "";
    $mailInfo->eventContent = $eventData->bm_content;
    $mailInfo->eventName = $eventData->event_name;
    // $mailInfo->cc = "ci@email.com";
    // $mailInfo->bcc = "jim@email.com";

    Mail::to($userdata['login_id'])
      ->send(new OptyEmail($mailInfo));
    
    return $this->emailReport($userdata['login_id'], "event registered", "", "SUCCESS", 0, "");
  }
  public function send_event_feedback_status($userdata, $eventData) {
    $mailInfo = new \stdClass();
    $mailInfo->recieverName = $userdata['cust_name'];
    $mailInfo->sender = "Optymoney";
    $mailInfo->senderCompany = "Optymoney";
    $mailInfo->to = $userdata['login_id'];
    $mailInfo->subject = $eventData->event_subject;
    $mailInfo->name = "Optymoney";
    $mailInfo->from = "no-reply@optymoney.com";
    $mailInfo->template = "email-templates.event-reg";
    $mailInfo->attachment = "no";
    $mailInfo->files = "";
    $mailInfo->eventContent = "Your feedback submitted successfully";
    $mailInfo->eventName = $eventData->event_name;
    // $mailInfo->cc = "ci@email.com";
    // $mailInfo->bcc = "jim@email.com";

    Mail::to($userdata['login_id'])
      ->send(new OptyEmail($mailInfo));
    
    return $this->emailReport($userdata['login_id'], "event registered", "", "SUCCESS", 0, "");
  }

}
