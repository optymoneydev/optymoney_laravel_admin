<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Events;
Use App\Models\Emails;
use View;
use League\Csv\Reader;
Use App\Mail\OptyEmail;
use Mail;



class BulkEmailController extends Controller
{
    public function sendBulkEmails(Request $bm_data) {

        $failedEmail = array();
		//$emailList = explode("\n",$bm_data[bm_emails]);
		$emailId = trim($bm_data['bm_emails']);
		$tempAttach = 0;
		// $message = "<p>".$bm_data[bm_content]."</p>"; 
		$emailStat = array();
        $files = request('bm_csvfile');
        $allowedfileExtension=['csv'];
        $filename = $files->getClientOriginalName();
        $extension = $files->getClientOriginalExtension();
        $check=in_array($extension,$allowedfileExtension);
        if($check) {
            // Create a CSV reader instance
            $reader = Reader::createFromFileObject($files->openFile());

            // Create a customer from each row in the CSV file
            foreach ($reader as $index => $row) {
                
                $message = $bm_data['bm_content'];
                $message = str_replace("&lt;--NAME--&gt;",$row[0],$message);
                
                $mailInfo = new \stdClass();
                $mailInfo->recieverName = $row[0];
                $mailInfo->sender = "Optymoney";
                $mailInfo->senderCompany = "Optymoney";
                $mailInfo->to = $row[1];
                $mailInfo->subject = $bm_data['bm_subject'];
                $mailInfo->name = "Optymoney";
                $mailInfo->from = "no-reply@optymoney.com";
                $mailInfo->template = "email-templates.basic-template";
                $mailInfo->otp = "";
                $mailInfo->attachment = "no";
                $mailInfo->files = "";
                $mailInfo->metalType = "";
                $mailInfo->amount = "";
                $mailInfo->message = $message;
                // $mailInfo->cc = "ci@email.com";
                // $mailInfo->bcc = "jim@email.com";

                $res = Mail::to($row[1])->send(new OptyEmail($mailInfo));
                $emails = new Emails;
                $emails->emailAddress = $row[1];
                $emails->email_type = "promotional";
                $emails->email_content = $bm_data['bm_subject'];
                $emails->email_status = "SUCCESS";
                $emails->email_otp = 0;
                $emails->email_verification = "PENDING";
                if($emails->save()) {
                    $emailStat[] = trim($row[1]," ")."-".json_encode($res);
                } else {
                    $failedEmail[] = trim($row[1]," ")."-".json_encode($res);
                }   
            }
            $data = [
                'status_code' => 201,
                'successMails' => json_encode($emailStat),
                'failureMails' => json_encode($failedEmail)
            ];
            return $data;
        } else {
            return "failure";
        }

    }


}
