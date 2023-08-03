<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\HTMLPDFController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Mail;
Use \App\Models\EmailFormat;
Use App\Mail\OptyEmail;

class InvoiceAugmontController extends Controller
{
    public function buyInvoice(Request $request) {
        $client = new Client(['verify' => false ]);
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();
        $headers = [
            'Content-Type'=> 'application/pdf',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];
        $res = $client->request('GET', 
            env('AUG_URL').'merchant/v1/invoice/'.$request->invoice,[
            'headers' => $headers
        ]);
        $statusCode = $res->getStatusCode(); 
        $content = json_decode($res->getBody()->getContents());
        $pdf = (new HTMLPDFController)->htmlPdf($this->object_to_array($content->result->data));
        // dd($pdf);
        return $pdf->download($request->invoice.'_pdf.pdf');
    }

    public function generatePDF($content) {
        return (new HTMLPDFController)->htmlPdf($this->object_to_array($content->result->data));
    }

    public function getInvoiceData($invoice) {
        $client = new Client(['verify' => false ]);
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            return (new AugmontController)->clientRequests('GET', 'merchant/v1/invoice/'.$invoice, '');
        }
        // $res = $client->request('GET', 
        // env('AUG_URL').'merchant/v1/invoice/'.$invoice,[
        //     'headers' => $headers
        // ]);
        // $statusCode = $res->getStatusCode(); 
        // return json_decode($res->getBody()->getContents());
    }

    public function emailInvoice(Request $request) {

        $content = $this->getInvoiceData($request->invoice);

        $dataTemp = $content->result->data;
        // dd($dataTemp->userInfo->name);
        $pdf = $this->generatePDF($content);

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
        $mailInfo->files = $pdf->download($request->invoice.'_pdf.pdf');
        $mailInfo->metalType = $dataTemp->metalType;
        $mailInfo->amount = $dataTemp->netAmount;

        Mail::to($dataTemp->userInfo->email)->send(new OptyEmail($mailInfo));

        return $pdf->download($request->invoice.'_pdf.pdf');
    }

    public function sellInvoice(Request $request) {
        $client = new Client(['verify' => false ]);
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();
        $headers = [
            'Content-Type'=> 'application/pdf',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];
        $res = $client->request('GET', 
        env('AUG_URL').'merchant/v1/invoice/order/'.$request->invoice,[
            'headers' => $headers
        ]);
        $statusCode = $res->getStatusCode(); 
        $content = json_decode($res->getBody()->getContents());
        $pdf = (new HTMLPDFController)->redeemInvoice($this->object_to_array($content->result->data));
        return $pdf->download($request->invoice.'_pdf.pdf');
    }

    public function buyInvoiceContent($invoice) {
        $content = $this->getInvoiceData($invoice);
        return $this->object_to_array($content->result->data);
    }

    function object_to_array($data) {
        if(is_array($data) || is_object($data)){
            $result = array();
            foreach($data as $key => $value){
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
}
